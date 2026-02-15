<?php

declare(strict_types=1);

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\HomePage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\Account;
use Modules\Core\Services\SettingService;
use Modules\Core\Models\Transaction;
use Modules\Gamification\Models\Medal;
use Modules\HomePage\Services\HomePageProDataService;

class HomePageController extends Controller
{
    /**
     * Display the public homepage. PRO users see personalized vitrine.
     */
    public function home(HomePageProDataService $proDataService, SettingService $settingService)
    {
        $financialSnapshot = null;
        $proHomeData = null;
        $isPro = false;

        if (Auth::check()) {
            $user = Auth::user();
            $isPro = $user->isPro();

            if ($isPro) {
                $proHomeData = $proDataService->getProHomeData($user);
                $financialSnapshot = $proHomeData['financialSnapshot'];
            } else {
                $totalBalance = Account::where('user_id', $user->id)->sum('balance');
                $currentMonth = now()->month;
                $currentYear = now()->year;
                $monthlyIncome = Transaction::where('user_id', $user->id)
                    ->where('type', 'income')
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('amount');
                $monthlyExpense = Transaction::where('user_id', $user->id)
                    ->where('type', 'expense')
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('amount');
                $savingsRate = $monthlyIncome > 0
                    ? round((($monthlyIncome - $monthlyExpense) / $monthlyIncome) * 100, 1)
                    : 0;
                $recentTransactions = Transaction::where('user_id', $user->id)
                    ->whereIn('type', ['income', 'expense'])
                    ->with('category')
                    ->latest('date')
                    ->latest('id')
                    ->take(3)
                    ->get();

                $financialSnapshot = [
                    'total_balance' => $totalBalance,
                    'monthly_income' => $monthlyIncome,
                    'monthly_expense' => $monthlyExpense,
                    'savings_rate' => $savingsRate,
                    'recent_transactions' => $recentTransactions,
                ];
            }
        }

        $rarityOrder = ['platinum' => 0, 'gold' => 1, 'silver' => 2, 'bronze' => 3];
        $medals = Medal::where('is_active', true)
            ->get()
            ->sortBy(fn ($m) => $rarityOrder[$m->rarity ?? 'silver'] ?? 4)
            ->take(4)
            ->values();

        $proHasLimits = (bool) $settingService->get('pro_has_limits', 0);
        $freeLimits = [
            'account' => (int) $settingService->get('limit_free_account', 1),
            'income' => (int) $settingService->get('limit_free_income', 5),
            'expense' => (int) $settingService->get('limit_free_expense', 5),
            'goal' => (int) $settingService->get('limit_free_goal', 1),
            'budget' => (int) $settingService->get('limit_free_budget', 1),
        ];
        $limitsPro = [
            'account' => (int) $settingService->get('limit_pro_account', -1),
            'income' => (int) $settingService->get('limit_pro_income', -1),
            'expense' => (int) $settingService->get('limit_pro_expense', -1),
            'goal' => (int) $settingService->get('limit_pro_goal', -1),
            'budget' => (int) $settingService->get('limit_pro_budget', -1),
        ];
        $planFreeName = (string) $settingService->get('plan_free_name', 'Plano Gratuito');
        $planProName = (string) $settingService->get('plan_pro_name', 'Vertex PRO');

        return view('homepage::homepage', compact(
            'financialSnapshot',
            'proHomeData',
            'isPro',
            'medals',
            'freeLimits',
            'limitsPro',
            'proHasLimits',
            'planFreeName',
            'planProName'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('homepage::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('homepage::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('homepage::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('homepage::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
