<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Gateways\Models\PaymentLog;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments (incl. recurring subscription payments).
     */
    public function index(Request $request)
    {
        $query = PaymentLog::with(['user', 'subscription'])->latest();

        if ($request->filled('gateway')) {
            $query->where('gateway_slug', $request->gateway);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15)->withQueryString();

        $thirtyDaysAgo = now()->subDays(30);
        $revenue30d = (float) PaymentLog::where('status', 'succeeded')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('amount');
        $countSucceeded = PaymentLog::where('status', 'succeeded')->where('created_at', '>=', $thirtyDaysAgo)->count();
        $countPending = PaymentLog::where('status', 'pending')->count();
        $countFailed = PaymentLog::whereIn('status', ['failed'])->count();

        $dailyRevenue = PaymentLog::where('status', 'succeeded')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get();

        $chartDates = $dailyRevenue->pluck('date');
        $chartValues = $dailyRevenue->pluck('total');

        return view('paneladmin::payments.index', compact(
            'payments',
            'chartDates',
            'chartValues',
            'revenue30d',
            'countSucceeded',
            'countPending',
            'countFailed'
        ));
    }
}
