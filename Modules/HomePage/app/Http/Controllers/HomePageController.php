<?php

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
use Modules\Core\Models\Transaction;
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

        return view('homepage::homepage', compact('financialSnapshot', 'proHomeData', 'isPro'));
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
