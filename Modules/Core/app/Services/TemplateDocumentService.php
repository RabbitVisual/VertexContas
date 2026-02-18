<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Core\Models\DocumentDownloadLog;

class TemplateDocumentService
{
    public const TYPE_INVOICE = 'invoice';

    public const TYPE_CASHFLOW = 'cashflow';

    public const TYPE_CATEGORY_RANKING = 'category_ranking';

    public const TYPE_EXTRATO = 'extrato';

    public const TYPE_CONSULTING = 'consulting';

    public const TYPE_PROJECTION = 'projection';

    /** AI report types (Consulting PDF + Year Projection) - share monthly limit */
    public const AI_REPORT_TYPES = [self::TYPE_CONSULTING, self::TYPE_PROJECTION];

    public function __construct(
        protected SettingService $settingService
    ) {}

    /**
     * Check if user can download/view document (within daily limit).
     */
    public function canDownload(string $documentType, User $user): bool
    {
        $limit = $this->getLimitForType($documentType);
        if ($limit <= 0) {
            return true;
        }

        $count = DocumentDownloadLog::where('user_id', $user->id)
            ->where('document_type', $documentType)
            ->whereDate('created_at', today())
            ->count();

        return $count < $limit;
    }

    /**
     * Check if user can request AI report (Consulting or Projection) within monthly limit.
     */
    public function canDownloadAiReport(User $user): bool
    {
        $limit = (int) $this->settingService->get('limit_ai_report_per_month', 5);
        if ($limit <= 0) {
            return true;
        }

        $count = DocumentDownloadLog::where('user_id', $user->id)
            ->whereIn('document_type', self::AI_REPORT_TYPES)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return $count < $limit;
    }

    /**
     * Get AI report usage count and limit for current month.
     *
     * @return array{count: int, limit: int, remaining: int, resets_at: \Carbon\Carbon}
     */
    public function getAiReportUsage(User $user): array
    {
        $limit = (int) $this->settingService->get('limit_ai_report_per_month', 5);
        $count = DocumentDownloadLog::where('user_id', $user->id)
            ->whereIn('document_type', self::AI_REPORT_TYPES)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $resetsAt = now()->endOfMonth()->addDay();

        return [
            'count' => $count,
            'limit' => $limit,
            'remaining' => max(0, $limit - $count),
            'resets_at' => $resetsAt,
        ];
    }

    /**
     * Get remaining downloads for today.
     */
    public function getRemainingDownloads(string $documentType, User $user): int
    {
        $limit = $this->getLimitForType($documentType);
        if ($limit <= 0) {
            return 999;
        }

        $count = DocumentDownloadLog::where('user_id', $user->id)
            ->where('document_type', $documentType)
            ->whereDate('created_at', today())
            ->count();

        return max(0, $limit - $count);
    }

    /**
     * Log a document view/download for audit.
     */
    public function logDownload(User $user, string $documentType, ?string $documentId, Request $request): void
    {
        DocumentDownloadLog::create([
            'user_id' => $user->id,
            'document_type' => $documentType,
            'document_id' => $documentId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Get template data (company info, branding) for documents.
     */
    public function getTemplateData(): array
    {
        $settings = $this->settingService->getByGroup('document_templates');
        $general = $this->settingService->getByGroup('general');

        $companyName = $settings->get('company_name') ?? $general->get('app_name') ?? config('app.name', 'Vertex Contas');

        return [
            'company_name' => $companyName,
            'company_address' => $settings->get('company_address') ?? '',
            'company_cnpj' => $settings->get('company_cnpj') ?? '',
            'company_phone' => $settings->get('company_phone') ?? '',
            'company_email' => $settings->get('company_email') ?? '',
            'document_footer_text' => $settings->get('document_footer_text') ?? $companyName . ' - Sistema de Gestão Financeira',
            'app_url' => $general->get('app_url') ?? config('app.url'),
            'logo_path' => branding_logo_url('default', false),
        ];
    }

    protected function getLimitForType(string $documentType): int
    {
        return match ($documentType) {
            self::TYPE_INVOICE => (int) $this->settingService->get('limit_download_invoice_per_day', 10),
            self::TYPE_CASHFLOW, self::TYPE_CATEGORY_RANKING, self::TYPE_EXTRATO, self::TYPE_CONSULTING => (int) $this->settingService->get('limit_download_report_per_day', 5),
            default => 5,
        };
    }
}
