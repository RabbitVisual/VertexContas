<?php

declare(strict_types=1);

/**
 * Helper global para formatação de valores financeiros e numéricos (padrão BRL).
 * Integrado com InspectionGuard para mascarar valores quando inspeção ativa sem permissão.
 *
 * @author Vertex Solutions LTDA
 * @see https://github.com/vertex-solutions
 */

namespace App\Helpers;

use Modules\Core\Services\InspectionGuard;

final class FormatHelper
{
    /**
     * Formata valor monetário em BRL. Respeita InspectionGuard (mascara se inspeção ativa).
     *
     * @param  float|int|string  $value
     */
    public static function currency($value, string $prefix = 'R$', bool $checkInspection = true): string
    {
        if ($checkInspection && InspectionGuard::shouldHideFinancialData()) {
            return InspectionGuard::maskValue($value, $prefix);
        }

        $num = is_numeric($value) ? (float) $value : 0.0;

        return $prefix . ' ' . number_format($num, 2, ',', '.');
    }

    /**
     * Formata porcentagem (valor já em %: 15.5 -> 15,5%).
     *
     * @param  float|int|string  $value
     */
    public static function percent($value, int $decimals = 1): string
    {
        $num = is_numeric($value) ? (float) $value : 0.0;

        return number_format($num, $decimals, ',', '.') . '%';
    }

    /**
     * Converte valor decimal em porcentagem (0.155 -> 15,5%).
     *
     * @param  float|int|string  $value
     */
    public static function percentDecimal($value, int $decimals = 1): string
    {
        $num = is_numeric($value) ? (float) $value : 0.0;

        return number_format($num * 100, $decimals, ',', '.') . '%';
    }

    /**
     * Formata número genérico no padrão BRL (1.500,50).
     *
     * @param  float|int|string  $value
     */
    public static function number($value, int $decimals = 2): string
    {
        $num = is_numeric($value) ? (float) $value : 0.0;

        return number_format($num, $decimals, ',', '.');
    }

    /**
     * Formata números grandes de forma compacta (1.500.000 -> 1,5M).
     *
     * @param  float|int|string  $value
     */
    public static function compactNumber($value): string
    {
        $num = is_numeric($value) ? (float) $value : 0.0;

        if ($num >= 1_000_000) {
            return number_format($num / 1_000_000, 1, ',', '.') . 'M';
        }
        if ($num >= 1_000) {
            return number_format($num / 1_000, 1, ',', '.') . 'K';
        }

        return self::number($num, 0);
    }
}
