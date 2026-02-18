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
use Modules\Core\Models\Plan;
use Modules\Core\Models\Transaction;
use Modules\Gamification\Models\Medal;
use Modules\HomePage\Services\HomePageProDataService;

class HomePageController extends Controller
{
    /**
     * Display the public homepage. PRO users see personalized vitrine.
     */
    public function home(HomePageProDataService $proDataService)
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

        $planFree = Plan::getDefaultFree();
        $paidPlans = Plan::where('is_free', false)->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        $freeLimits = ['account' => 1, 'income' => 15, 'expense' => 15, 'goal' => 1, 'budget' => 1];
        $limitsPro = ['account' => -1, 'income' => -1, 'expense' => -1, 'goal' => -1, 'budget' => -1];
        $proHasLimits = false;
        if ($planFree) {
            foreach (Plan::limitEntities() as $entity) {
                $val = $planFree->getLimit($entity);
                $freeLimits[$entity] = $val === 'unlimited' ? -1 : (int) $val;
            }
        }
        $firstPaid = $paidPlans->first();
        if ($firstPaid) {
            foreach (Plan::limitEntities() as $entity) {
                $val = $firstPaid->getLimit($entity);
                $limitsPro[$entity] = $val === 'unlimited' ? -1 : (int) $val;
            }
            $proHasLimits = collect($limitsPro)->contains(fn ($v) => $v >= 0);
        }

        return view('homepage::homepage', compact(
            'financialSnapshot',
            'proHomeData',
            'isPro',
            'medals',
            'planFree',
            'paidPlans',
            'freeLimits',
            'limitsPro',
            'proHasLimits'
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
