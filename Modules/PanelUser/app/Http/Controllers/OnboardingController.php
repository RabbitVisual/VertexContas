<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Core\Models\RecurringTransaction;

class OnboardingController extends Controller
{
    protected \Modules\Core\Services\FinancialHealthService $financialService;

    public function __construct(\Modules\Core\Services\FinancialHealthService $financialService)
    {
        $this->financialService = $financialService;
    }

    /**
     * Show the Financial Baseline (income) setup wizard.
     */
    public function showSetupIncome(): View
    {
        $user = Auth::user();
        $hasIncome = RecurringTransaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->exists();
        $isEditMode = $user->onboarding_completed || $hasIncome;

        return view('paneluser::onboarding.setup-income', [
            'isPro' => $user->isPro(),
            'isEditMode' => $isEditMode,
            'existingIncomes' => $this->getExistingIncomes($user),
        ]);
    }

    /**
     * Get user's existing recurring income records for pre-fill in edit mode.
     *
     * @return array<int, array{description: string, amount: float, day: int}>
     */
    private function getExistingIncomes(\App\Models\User $user): array
    {
        return RecurringTransaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->where('is_active', true)
            ->orderBy('recurrence_day')
            ->get()
            ->map(fn ($r) => [
                'description' => $r->description,
                'amount' => $r->amount,
                'day' => (string) ($r->recurrence_day ?? 1),
            ])
            ->values()
            ->all();
    }

    /**
     * Store recurring income sources from the onboarding wizard.
     */
    public function storeIncome(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $isPro = $user->isPro();

        $incomes = $request->input('incomes', []);

        $parsedIncomes = [];
        foreach ($incomes as $i => $item) {
            $parsedIncomes[$i] = $item;
            $parsedIncomes[$i]['amount'] = $this->parseMoneyAmount($item['amount'] ?? 0);
        }
        $request->merge(['incomes' => $parsedIncomes]);

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
        $hadExisting = RecurringTransaction::where('user_id', $user->id)->exists();

        $this->financialService->syncUserPlanning($user, $incomes, []);

        return redirect()
            ->route('paneluser.index')
            ->with('success', $hadExisting ? 'Renda atualizada com sucesso.' : 'Fontes de receita cadastradas com sucesso.');
    }

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
}
