<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Core\Models\RecurringTransaction;

class OnboardingController extends Controller
{
    /**
     * Show the Financial Baseline (income) setup wizard.
     */
    public function showSetupIncome(): View
    {
        return view('paneluser::onboarding.setup-income', [
            'isPro' => auth()->user()->isPro(),
        ]);
    }

    /**
     * Store recurring income sources from the onboarding wizard.
     */
    public function storeIncome(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $isPro = $user->isPro();

        $rules = [
            'incomes' => ['required', 'array', 'min:1'],
            'incomes.*.description' => ['required', 'string', 'max:255'],
            'incomes.*.amount' => ['required', 'numeric', 'min:0'],
            'incomes.*.day' => ['required', 'integer', 'min:1', 'max:31'],
        ];

        if (! $isPro) {
            $rules['incomes'][] = 'max:1';
        }

        $request->validate($rules);

        $incomes = $request->input('incomes', []);

        DB::transaction(function () use ($user, $incomes) {
            foreach ($incomes as $index => $item) {
                $amount = $this->parseMoneyAmount($item['amount'] ?? 0);
                $day = (int) ($item['day'] ?? 1);
                $day = max(1, min(31, $day));
                $nextDate = $this->nextDateFromRecurrenceDay($day);

                RecurringTransaction::create([
                    'user_id' => $user->id,
                    'category_id' => null,
                    'account_id' => null,
                    'type' => 'income',
                    'amount' => $amount,
                    'frequency' => 'monthly',
                    'recurrence_day' => $day,
                    'next_date' => $nextDate,
                    'description' => $item['description'] ?? 'Receita',
                    'is_active' => true,
                ]);
            }
        });

        return redirect()
            ->route('paneluser.index')
            ->with('success', __('Fontes de receita cadastradas com sucesso.'));
    }

    /**
     * Parse money input (e.g. "R$ 1.500,50" or "1500.50") to float.
     */
    private function parseMoneyAmount(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        $str = preg_replace('/[^\d,.-]/', '', (string) $value);
        $str = str_replace('.', '', $str);
        $str = str_replace(',', '.', $str);
        return (float) ($str ?: 0);
    }

    /**
     * Next occurrence date for a given day of month (1-31).
     */
    private function nextDateFromRecurrenceDay(int $day): Carbon
    {
        $now = now();
        $day = max(1, min(31, $day));
        $thisMonth = $now->copy()->startOfMonth();
        $daysInThisMonth = $thisMonth->daysInMonth;
        $safeDay = min($day, $daysInThisMonth);
        $thisMonth->day($safeDay);
        if ($thisMonth->gte($now)) {
            return $thisMonth;
        }
        $nextMonth = $now->copy()->addMonth()->startOfMonth();
        $safeDayNext = min($day, $nextMonth->daysInMonth);
        $nextMonth->day($safeDayNext);
        return $nextMonth;
    }
}
