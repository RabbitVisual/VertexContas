<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Modules\Core\Models\Inspection;

/**
 * Centralizes inspection privacy logic.
 * Ensures financial and sensitive data are properly masked when user denies visibility.
 *
 * @author Vertex Solutions LTDA
 */
final class InspectionGuard
{
    private static ?Inspection $cachedInspection = null;

    /**
     * Whether an active inspection session is running (support viewing as user).
     */
    public static function isActive(): bool
    {
        $inspection = self::getActiveInspection();

        return $inspection !== null && $inspection->status === 'active';
    }

    /**
     * Whether financial data must be hidden during inspection (user did not allow).
     */
    public static function shouldHideFinancialData(): bool
    {
        $inspection = self::getActiveInspection();

        return $inspection !== null
            && $inspection->status === 'active'
            && ! $inspection->show_financial_data;
    }

    /**
     * Whether the current session user is a PRO client (for VIP inspection features).
     */
    public static function isProClient(): bool
    {
        $inspection = self::getActiveInspection();

        if (! $inspection) {
            return false;
        }

        $client = $inspection->client;

        return $client && $client->isPro();
    }

    /**
     * Mask a numeric value for display when inspection hides financial data.
     *
     * @param  float|int|string  $value
     */
    public static function maskValue($value, string $prefix = 'R$'): string
    {
        $space = $prefix !== '' ? ' ' : '';

        if (! self::shouldHideFinancialData()) {
            $formatted = is_numeric($value) ? number_format((float) $value, 2, ',', '.') : (string) $value;

            return $prefix.$space.$formatted;
        }

        return $prefix.$space.'00.000,00';
    }

    /**
     * Get CSS classes to apply when inspection hides financial data (blur, placeholder).
     */
    public static function maskClasses(): string
    {
        if (! self::shouldHideFinancialData()) {
            return '';
        }

        return 'blur-[6px] select-none pointer-events-none opacity-50 grayscale';
    }

    /**
     * Sanitize chart/data array for inspection - replace real values with placeholder.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function maskChartData(array $data): array
    {
        if (! self::shouldHideFinancialData()) {
            return $data;
        }

        $masked = $data;

        if (isset($masked['income']) && is_array($masked['income'])) {
            $masked['income'] = array_fill(0, count($masked['income']), 0);
        }
        if (isset($masked['expenses']) && is_array($masked['expenses'])) {
            $masked['expenses'] = array_fill(0, count($masked['expenses']), 0);
        }
        if (isset($masked['values']) && is_array($masked['values'])) {
            $masked['values'] = array_fill(0, count($masked['values']), 0);
        }

        return $masked;
    }

    /**
     * @return Inspection|null
     */
    public static function getActiveInspection(): ?Inspection
    {
        if (self::$cachedInspection !== null) {
            return self::$cachedInspection;
        }

        $id = session('impersonate_inspection_id');
        self::$cachedInspection = $id ? Inspection::find($id) : null;

        return self::$cachedInspection;
    }
}
