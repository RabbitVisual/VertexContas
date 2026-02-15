<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\Account;
use Modules\Core\Models\Transaction;
use Modules\Core\Services\ReportService;
use Modules\Core\Services\SettingService;
use Modules\Core\Services\TemplateDocumentService;

class ReportsController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected TemplateDocumentService $templateService,
        protected SettingService $settingService
    ) {
        $this->middleware(['auth', 'verified']);
        $this->middleware('pro')->only([
            'extrato',
            'viewExtrato',
            'exportExtratoCsv',
            'exportExtratoXlsx',
            'exportCashFlowCsv',
            'exportCashFlowXlsx',
            'exportCategoryRankingCsv',
            'viewCashFlow',
            'viewCategoryRanking',
        ]);
    }

    public function index()
    {
        $user = auth()->user();
        $transactionCount = Transaction::where('user_id', $user->id)->count();

        return view('core::reports.index', compact('transactionCount'));
    }

    /**
     * Display cash flow report.
     */
    public function cashFlow(Request $request)
    {
        $months = (int) $request->input('months', 6);
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;
        $user = auth()->user();

        $accounts = Account::where('user_id', $user->id)->orderBy('name')->get();
        $cashFlow = $this->reportService->getCashFlow($user, $months, $accountId);
        $cashFlowSummary = $this->reportService->getCashFlowSummary($user, $months, $accountId);
        $cashFlowByAccount = $this->reportService->getCashFlowByAccount($user, $months, $accountId);
        $cashFlowByCategory = $this->reportService->getCashFlowByCategory($user, $months, $accountId);
        $topCategories = $this->reportService->getTopCategoriesForPeriod($user, $months, $accountId);

        return view('core::reports.cashflow', compact(
            'cashFlow',
            'cashFlowSummary',
            'cashFlowByAccount',
            'cashFlowByCategory',
            'topCategories',
            'months',
            'accounts'
        ));
    }

    /**
     * Display category ranking report.
     */
    public function categoryRanking(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;

        $accounts = Account::where('user_id', $user->id)->orderBy('name')->get();
        $ranking = $this->reportService->getCategoryRanking($user, $startDate, $endDate, $accountId);
        $summary = $this->reportService->getIncomeExpenseSummary($user, $startDate, $endDate, $accountId);

        return view('core::reports.category-ranking', compact('ranking', 'summary', 'startDate', 'endDate', 'accounts'));
    }

    /**
     * Display bank statement (extrato) report. PRO only.
     */
    public function extrato(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;
        $type = $request->filled('type') && in_array($request->input('type'), ['income', 'expense'], true)
            ? $request->input('type')
            : null;

        $accounts = Account::where('user_id', $user->id)->orderBy('name')->get();
        $statement = $this->reportService->getBankStatement($user, $startDate, $endDate, $accountId, $type);
        $totals = $this->reportService->getBankStatementTotals($statement);

        return view('core::reports.extrato', compact('statement', 'totals', 'startDate', 'endDate', 'accounts'));
    }

    /**
     * Export cash flow to CSV.
     */
    public function exportCashFlowCsv(Request $request)
    {
        $months = (int) $request->input('months', 6);
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;
        $user = auth()->user();

        $cashFlow = $this->reportService->getCashFlow($user, $months, $accountId);
        $filename = 'fluxo-caixa-' . now()->format('Y-m-d-His');
        $templateData = $this->templateService->getTemplateData();
        $periodLabel = 'Últimos ' . $months . ' meses';

        $path = $this->reportService->exportCashFlowToCsv(
            $cashFlow,
            $filename,
            $templateData['company_name'] ?? 'Vertex Contas',
            $periodLabel
        );

        return response()->download($path)->deleteFileAfterSend();
    }

    /**
     * Export cash flow to XLSX (multiple sheets: Resumo, Por Conta, Por Categoria, Detalhes).
     */
    public function exportCashFlowXlsx(Request $request)
    {
        $months = (int) $request->input('months', 6);
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;
        $user = auth()->user();

        $cashFlow = $this->reportService->getCashFlow($user, $months, $accountId);
        $byAccount = $this->reportService->getCashFlowByAccount($user, $months, $accountId);
        $byCategory = $this->reportService->getCashFlowByCategory($user, $months, $accountId);
        $detail = $this->reportService->getCashFlowDetail($user, $months, $accountId);

        $filename = 'fluxo-caixa-' . now()->format('Y-m-d-His');
        $periodLabel = 'Últimos ' . $months . ' meses';

        $path = $this->reportService->exportCashFlowToXlsx(
            $cashFlow,
            $byAccount,
            $byCategory,
            $detail,
            $filename,
            'Vertex Pro',
            $periodLabel
        );

        return response()->download($path, $filename . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend();
    }

    /**
     * Export category ranking to CSV.
     */
    public function exportCategoryRankingCsv(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;

        $ranking = $this->reportService->getCategoryRanking($user, $startDate, $endDate, $accountId);
        $filename = 'ranking-categorias-' . now()->format('Y-m-d-His');
        $templateData = $this->templateService->getTemplateData();
        $periodLabel = $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');

        $path = $this->reportService->exportCategoryRankingToCsv(
            $ranking,
            $filename,
            $templateData['company_name'] ?? 'Vertex Contas',
            $periodLabel
        );

        return response()->download($path)->deleteFileAfterSend();
    }

    /**
     * Export bank statement to CSV. PRO only.
     */
    public function exportExtratoCsv(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;
        $type = $request->filled('type') && in_array($request->input('type'), ['income', 'expense'], true)
            ? $request->input('type')
            : null;

        $statement = $this->reportService->getBankStatement($user, $startDate, $endDate, $accountId, $type);
        $filename = 'extrato-bancario-' . now()->format('Y-m-d-His');
        $templateData = $this->templateService->getTemplateData();
        $periodLabel = $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');

        $path = $this->reportService->exportBankStatementToCsv(
            $statement,
            $filename,
            $templateData['company_name'] ?? 'Vertex Contas',
            $periodLabel
        );

        return response()->download($path)->deleteFileAfterSend();
    }

    /**
     * Export bank statement to XLSX (formatted Excel). PRO only.
     */
    public function exportExtratoXlsx(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;
        $type = $request->filled('type') && in_array($request->input('type'), ['income', 'expense'], true)
            ? $request->input('type')
            : null;

        $statement = $this->reportService->getBankStatement($user, $startDate, $endDate, $accountId, $type);
        $filename = 'extrato-vertex-' . now()->format('Y-m-d-His');
        $periodLabel = $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');

        $path = $this->reportService->exportBankStatementToXlsx(
            $statement,
            $filename,
            'Vertex Pro',
            $periodLabel
        );

        return response()->download($path, $filename . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend();
    }

    /**
     * View cash flow report in HTML (new tab). User prints or saves as PDF via browser.
     */
    public function viewCashFlow(Request $request)
    {
        $months = (int) $request->input('months', 6);
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;
        $user = auth()->user();

        if (! $this->templateService->canDownload(TemplateDocumentService::TYPE_CASHFLOW, $user)) {
            $limit = (int) $this->settingService->get('limit_download_report_per_day', 5);

            return response()->view('core::documents.limit-exceeded', [
                'message' => "Você abriu {$limit} relatórios para impressão hoje. Esse limite é renovado diariamente.",
            ], 429);
        }

        $cashFlow = $this->reportService->getCashFlow($user, $months, $accountId);
        $cashFlowByAccount = $this->reportService->getCashFlowByAccount($user, $months, $accountId);
        $topCategories = $this->reportService->getTopCategoriesForPeriod($user, $months, $accountId);
        $this->templateService->logDownload($user, TemplateDocumentService::TYPE_CASHFLOW, 'cashflow-' . $months, $request);

        $templateData = $this->templateService->getTemplateData();
        $periodLabel = 'Últimos ' . $months . ' meses';

        return view('core::documents.cashflow-statement', compact(
            'cashFlow',
            'cashFlowByAccount',
            'topCategories',
            'months',
            'templateData',
            'periodLabel'
        ));
    }

    /**
     * View category ranking in HTML (new tab). User prints or saves as PDF via browser.
     */
    public function viewCategoryRanking(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;

        if (! $this->templateService->canDownload(TemplateDocumentService::TYPE_CATEGORY_RANKING, $user)) {
            $limit = (int) $this->settingService->get('limit_download_report_per_day', 5);

            return response()->view('core::documents.limit-exceeded', [
                'message' => "Você abriu {$limit} relatórios para impressão hoje. Esse limite é renovado diariamente.",
            ], 429);
        }

        $ranking = $this->reportService->getCategoryRanking($user, $startDate, $endDate, $accountId);
        $summary = $this->reportService->getIncomeExpenseSummary($user, $startDate, $endDate, $accountId);
        $this->templateService->logDownload(
            $user,
            TemplateDocumentService::TYPE_CATEGORY_RANKING,
            'category-ranking-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd'),
            $request
        );

        $templateData = $this->templateService->getTemplateData();
        $periodLabel = $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');

        return view('core::documents.category-ranking', compact('ranking', 'summary', 'templateData', 'periodLabel'));
    }

    /**
     * View bank statement in HTML (new tab). User prints or saves as PDF via browser. PRO only.
     */
    public function viewExtrato(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();
        $accountId = $request->filled('account_id') ? (int) $request->input('account_id') : null;
        $type = $request->filled('type') && in_array($request->input('type'), ['income', 'expense'], true)
            ? $request->input('type')
            : null;

        if (! $this->templateService->canDownload(TemplateDocumentService::TYPE_EXTRATO, $user)) {
            $limit = (int) $this->settingService->get('limit_download_report_per_day', 5);

            return response()->view('core::documents.limit-exceeded', [
                'message' => "Você abriu {$limit} relatórios para impressão hoje. Esse limite é renovado diariamente.",
            ], 429);
        }

        $statement = $this->reportService->getBankStatement($user, $startDate, $endDate, $accountId, $type);
        $totals = $this->reportService->getBankStatementTotals($statement);
        $this->templateService->logDownload(
            $user,
            TemplateDocumentService::TYPE_EXTRATO,
            'extrato-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd'),
            $request
        );

        $templateData = $this->templateService->getTemplateData();
        $periodLabel = $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');

        return view('core::documents.extrato-bancario', compact('statement', 'totals', 'templateData', 'periodLabel'));
    }
}
