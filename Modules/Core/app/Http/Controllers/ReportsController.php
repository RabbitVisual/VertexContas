<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Services\ReportService;

class ReportsController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->middleware(['auth', 'verified']);
        // Export permissions handled in methods
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('core::reports.index');
    }

    /**
     * Display cash flow report.
     */
    public function cashFlow(Request $request)
    {
        $months = $request->input('months', 6);
        $user = auth()->user();

        $cashFlow = $this->reportService->getCashFlow($user, $months);

        return view('core::reports.cashflow', compact('cashFlow', 'months'));
    }

    /**
     * Display category ranking report.
     */
    public function categoryRanking(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : now()->startOfMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : now()->endOfMonth();

        $ranking = $this->reportService->getCategoryRanking($user, $startDate, $endDate);
        $summary = $this->reportService->getIncomeExpenseSummary($user, $startDate, $endDate);

        return view('core::reports.category-ranking', compact('ranking', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Export cash flow to CSV.
     */
    public function exportCashFlowCsv(Request $request)
    {
        if (!auth()->user()->hasRole('pro_user') && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Recurso exclusivo para assinantes Pro.');
        }

        $months = $request->input('months', 6);
        $user = auth()->user();

        $cashFlow = $this->reportService->getCashFlow($user, $months);
        $filename = 'fluxo-caixa-' . now()->format('Y-m-d-His');

        $path = $this->reportService->exportToCsv($cashFlow, $filename);

        return response()->download($path)->deleteFileAfterSend();
    }

    /**
     * Export category ranking to CSV.
     */
    public function exportCategoryRankingCsv(Request $request)
    {
        if (!auth()->user()->hasRole('pro_user') && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Recurso exclusivo para assinantes Pro.');
        }

        $user = auth()->user();
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : now()->startOfMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : now()->endOfMonth();

        $ranking = $this->reportService->getCategoryRanking($user, $startDate, $endDate);
        $filename = 'ranking-categorias-' . now()->format('Y-m-d-His');

        $path = $this->reportService->exportToCsv($ranking, $filename);

        return response()->download($path)->deleteFileAfterSend();
    }

    /**
     * Export cash flow to PDF.
     */
    public function exportCashFlowPdf(Request $request)
    {
        if (!auth()->user()->hasRole('pro_user') && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Recurso exclusivo para assinantes Pro.');
        }

        $months = $request->input('months', 6);
        $user = auth()->user();

        $cashFlow = $this->reportService->getCashFlow($user, $months);
        $filename = 'fluxo-caixa-' . now()->format('Y-m-d-His');

        try {
            $path = $this->reportService->exportToPdf($cashFlow, 'Fluxo de Caixa', $filename);
            return response()->download($path)->deleteFileAfterSend();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
