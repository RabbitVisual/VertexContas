<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Modules\Core\Services\FinancialHealthService;
use Modules\Core\Services\GeminiService;
use Modules\Core\Services\SettingService;
use Modules\Core\Services\TemplateDocumentService;

class ProjectionController extends Controller
{
    public function __construct(
        protected FinancialHealthService $financialHealth,
        protected GeminiService $geminiService,
        protected SettingService $settingService,
        protected TemplateDocumentService $templateService
    ) {
        $this->middleware(['auth', 'verified', 'pro']);
    }

    /**
     * Analyze 1-year patrimony projection via Gemini. Monthly limit for AI reports.
     */
    public function analyze(Request $request): JsonResponse
    {
        $user = auth()->user();

        $key = 'projection_analyze_' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'error' => 'Aguarde um momento antes de analisar novamente.',
            ], 429);
        }
        RateLimiter::hit($key, 60);

        $projectionData = $this->financialHealth->getProjectionData($user);

        $useGemini = (bool) ($this->settingService->get('gemini_enabled') ?? false) && $this->geminiService->isAvailable();
        if (! $useGemini) {
            return response()->json([
                'projection' => $projectionData['trend_summary'] ?? 'Analise seus relatórios para acompanhar a evolução.',
            ]);
        }

        if (! $this->templateService->canDownloadAiReport($user)) {
            $usage = $this->templateService->getAiReportUsage($user);

            return response()->json([
                'error' => 'Você atingiu o limite de ' . $usage['limit'] . ' relatórios por IA este mês. Renova em ' . $usage['resets_at']->format('d/m') . '.',
            ], 429);
        }

        $this->templateService->logDownload(
            $user,
            TemplateDocumentService::TYPE_PROJECTION,
            'projection-' . now()->format('Y-m'),
            $request
        );

        $contextData = [
            'reserve_months' => $projectionData['reserve_months'],
            'savings_rate' => $projectionData['savings_rate'],
            'balance' => $projectionData['balance'],
            'monthly_income' => $projectionData['monthly_income'],
            'monthly_expense' => $projectionData['monthly_expense'],
        ];

        $projection = $this->geminiService->generateOneYearProjection($contextData);

        if ($projection === null || trim($projection) === '') {
            return response()->json([
                'projection' => $projectionData['trend_summary'] ?? 'Não foi possível gerar a projeção. Tente novamente.',
            ]);
        }

        return response()->json(['projection' => trim($projection)]);
    }
}
