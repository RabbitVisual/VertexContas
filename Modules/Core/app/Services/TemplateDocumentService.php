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
        $branding = $this->settingService->getByGroup('branding');

        return [
            'company_name' => $settings->get('company_name') ?? $general->get('app_name') ?? 'Vertex Contas',
            'company_address' => $settings->get('company_address') ?? '',
            'company_cnpj' => $settings->get('company_cnpj') ?? '',
            'company_phone' => $settings->get('company_phone') ?? '',
            'company_email' => $settings->get('company_email') ?? '',
            'document_footer_text' => $settings->get('document_footer_text') ?? 'Vertex Contas - Sistema de Gestão Financeira',
            'app_url' => $general->get('app_url') ?? config('app.url'),
            'logo_path' => $branding->get('app_logo') ? asset('storage/' . ltrim(str_replace('storage/', '', $branding->get('app_logo')), '/')) : asset('storage/logos/logo.svg'),
        ];
    }

    protected function getLimitForType(string $documentType): int
    {
        return match ($documentType) {
            self::TYPE_INVOICE => (int) $this->settingService->get('limit_download_invoice_per_day', 10),
            self::TYPE_CASHFLOW, self::TYPE_CATEGORY_RANKING => (int) $this->settingService->get('limit_download_report_per_day', 5),
            default => 5,
        };
    }
}
