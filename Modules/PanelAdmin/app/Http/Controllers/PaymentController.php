<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Gateways\Models\PaymentLog; // Assuming this model exists based on previous work

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $query = PaymentLog::with('user')->latest();

        // Filters
        if ($request->has('gateway') && $request->gateway != '') {
            $query->where('gateway_slug', $request->gateway);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15)->withQueryString();

        // Stats for Charts
        // Daily Revenue (Last 30 days)
        // Group by date, sum amount
        $dailyRevenue = PaymentLog::where('status', 'succeeded') // or 'paid' depending on logic
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('date')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->get();

        // Prepare chart data
        $chartDates = $dailyRevenue->pluck('date');
        $chartValues = $dailyRevenue->pluck('total');

        return view('paneladmin::payments.index', compact('payments', 'chartDates', 'chartValues'));
    }
}
