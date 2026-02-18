<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportAuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\Account;
use Modules\Core\Models\AiConsultingReport;
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
            'viewConsulting',
            'downloadConsultingPdf',
            'consultoriaHistory',
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
        $user = Auth::user();
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
        $user = Auth::user();

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
        $user = Auth::user();
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
        $user = Auth::user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->subMonths(5)->startOfMonth();
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
        $user = Auth::user();

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
        $user = Auth::user();

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
            plan_pro_name(),
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
        $user = Auth::user();
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
        $user = Auth::user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->subMonths(5)->startOfMonth();
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
        $user = Auth::user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->subMonths(5)->startOfMonth();
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
            plan_pro_name(),
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
        $user = Auth::user();

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
        $user = Auth::user();
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
     * View consulting report in HTML (new tab). 50/30/20, score, AI conclusion, AI projection, medals. PRO only.
     * Uses AiConsultingReport when available; otherwise generates via Gemini (consumes 1 monthly quota).
     */
    public function viewConsulting(Request $request)
    {
        $result = $this->getConsultingReportData($request);
        if ($result instanceof Response || $result instanceof RedirectResponse) {
            return $result;
        }

        return view('core::documents.consulting-report', $result);
    }

    /**
     * Download consulting report as PDF. PRO only. Uses same data as viewConsulting; rendered via DomPDF.
     */
    public function downloadConsultingPdf(Request $request)
    {
        $result = $this->getConsultingReportData($request);
        if ($result instanceof Response || $result instanceof RedirectResponse) {
            return $result;
        }

        $period = $result['consultingData']['period'] ?? now()->format('Y-m');
        $result['forPdf'] = true;

        return Pdf::loadView('core::documents.consulting-report', $result)
            ->setPaper('a4', 'portrait')
            ->download('consultoria-' . $period . '.pdf');
    }

    /**
     * Build consulting report data for view or PDF. Returns Response (redirect/429) or data array.
     *
     * @return array{consultingData: array, templateData: array, user: \App\Models\User, metrics: array, recommendations: array}|Response|RedirectResponse
     */
    private function getConsultingReportData(Request $request): array|Response|RedirectResponse
    {
        $user = Auth::user();
        if (! $user->isPro()) {
            return redirect()->route('core.reports.index')
                ->with('error', 'Consultoria é exclusiva PRO.');
        }

        $period = $request->input('period', now()->format('Y-m'));
        if (! preg_match('/^\d{4}-\d{2}$/', $period)) {
            $period = now()->format('Y-m');
        }

        $forcarNova = $request->boolean('nova');
        $savedReport = $forcarNova ? null : AiConsultingReport::findForUserAndPeriod((int) $user->id, $period);

        if ($savedReport !== null) {
            $consultingData = $savedReport->snapshot ?? [];
            $consultingData['medals'] = isset($consultingData['medals'])
                ? collect($consultingData['medals'])
                : collect();
            $consultingData['recommendations'] = $savedReport->ai_conclusion
                ? [trim($savedReport->ai_conclusion)]
                : $this->reportService->buildRecommendations($consultingData['budget_analysis'] ?? []);
            $consultingData['ai_projection'] = $savedReport->ai_projection;
            $consultingData['ai_tips'] = $consultingData['ai_tips'] ?? $this->reportService->buildRecommendations($consultingData['budget_analysis'] ?? []);
            $consultingData['generated_with_ai'] = (bool) $savedReport->ai_conclusion;
        } else {
            if (! $this->templateService->canDownloadAiReport($user)) {
                $usage = $this->templateService->getAiReportUsage($user);

                return response()->view('core::documents.limit-exceeded', [
                    'message' => 'Você atingiu o limite de ' . $usage['limit'] . ' relatórios por IA este mês. Renova em ' . $usage['resets_at']->format('d/m') . '.',
                    'resets_note' => 'Renova no início do próximo mês.',
                ], 429);
            }

            $consultingData = $this->reportService->getConsultingData($user);
            $useGemini = (bool) ($this->settingService->get('gemini_enabled') ?? false);

            if ($useGemini) {
                $aiContent = $this->reportService->generateConsultingAiContent($consultingData);
                $aiConclusion = $aiContent['conclusion'];
                $aiProjection = $aiContent['projection'];
                $aiTips = $aiContent['ai_tips'] ?? [];

                $consultingData['recommendations'] = $aiConclusion ? [trim($aiConclusion)] : $this->reportService->buildRecommendations($consultingData['budget_analysis'] ?? []);
                $consultingData['ai_projection'] = $aiProjection;
                $consultingData['ai_tips'] = $aiTips;
                $consultingData['generated_with_ai'] = (bool) $aiConclusion;

                $snapshot = [
                    'budget_analysis' => $consultingData['budget_analysis'] ?? [],
                    'financial_score' => $consultingData['financial_score'] ?? 0,
                    'medals' => $consultingData['medals']?->toArray() ?? [],
                    'period_label' => $consultingData['period_label'] ?? now()->locale('pt_BR')->translatedFormat('F Y'),
                    'accounts_summary' => $consultingData['accounts_summary'] ?? [],
                    'projection_data' => $consultingData['projection_data'] ?? [],
                    'income_sources' => $consultingData['income_sources']?->toArray() ?? [],
                    'ai_tips' => $aiTips,
                ];

                AiConsultingReport::updateOrCreate(
                    ['user_id' => $user->id, 'period' => $period],
                    [
                        'ai_conclusion' => $aiConclusion,
                        'ai_projection' => $aiProjection,
                        'snapshot' => $snapshot,
                    ]
                );

                $this->templateService->logDownload(
                    $user,
                    TemplateDocumentService::TYPE_CONSULTING,
                    'consultoria-' . $period,
                    $request
                );

                if (! empty($consultingData['generated_with_ai'])) {
                    SupportAuditLog::create([
                        'agent_id' => $user->id,
                        'user_id' => $user->id,
                        'action' => 'report.consulting.ai_generated',
                        'metadata' => [
                            'period' => $period,
                            'financial_score' => $consultingData['financial_score'] ?? 0,
                        ],
                        'ip_address' => $request->ip(),
                    ]);
                }
            } else {
                $contextData = $this->reportService->buildConsultingContextForAi($consultingData);
                $fallbackTips = $this->reportService->buildPersonalizedTipsFromData($contextData);
                $consultingData['recommendations'] = $this->reportService->buildRecommendations($consultingData['budget_analysis'] ?? []);
                $consultingData['ai_projection'] = null;
                $fallbackTips = array_values(array_unique(array_map('trim', $fallbackTips)));
                if (count($fallbackTips) < 4) {
                    $fallbackTips[] = 'Use as metas e orçamentos da Vertex Contas para acompanhar seus gastos mensalmente.';
                }
                $consultingData['ai_tips'] = array_slice($fallbackTips, 0, 4);
                $consultingData['generated_with_ai'] = false;
            }
        }

        $medals = $consultingData['medals'] ?? collect();
        if (is_array($medals)) {
            $medals = collect($medals);
        }
        $placeholderMedalDesc = 'Exclusivo para assinantes Vertex PRO. Desbloqueie com o plano premium.';
        $consultingData['medals'] = $medals->filter(function ($m) use ($placeholderMedalDesc) {
            $desc = isset($m['description']) ? trim((string) $m['description']) : '';

            return $desc !== $placeholderMedalDesc;
        })->values();

        $consultingData['ai_tips'] = array_slice(array_values(array_unique(array_map('trim', $consultingData['ai_tips'] ?? []))), 0, 4);

        $contextForView = $this->reportService->buildConsultingContextForAi($consultingData);
        $consultingData['pillars_brl'] = $contextForView['rich_ai_context']['pillars_brl'] ?? [];
        $consultingData['period'] = $period;

        $templateData = $this->templateService->getTemplateData();
        $budget = $consultingData['budget_analysis'] ?? [];
        $metrics = [
            'income' => (float) ($budget['baseline_income'] ?? 0),
            'expense' => (float) ($budget['total_expenses'] ?? 0),
            'account_balance' => (float) (($consultingData['metrics']['account_balance'] ?? 0) ?: (($budget['baseline_income'] ?? 0) - ($budget['total_expenses'] ?? 0))),
        ];
        $recommendations = $consultingData['recommendations'] ?? [];

        return compact('consultingData', 'templateData', 'user', 'metrics', 'recommendations');
    }

    /**
     * List saved consulting reports (Vertex Pro). Links to view each by period.
     */
    public function consultoriaHistory()
    {
        $user = Auth::user();
        if (! $user->isPro()) {
            return redirect()->route('core.reports.index')
                ->with('error', 'Histórico de consultoria é exclusivo PRO.');
        }

        $reports = AiConsultingReport::where('user_id', $user->id)
            ->orderByDesc('period')
            ->take(24)
            ->get();

        return view('core::reports.consultoria-history', compact('reports'));
    }

    /**
     * View bank statement in HTML (new tab). User prints or saves as PDF via browser. PRO only.
     */
    public function viewExtrato(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->subMonths(5)->startOfMonth();
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
