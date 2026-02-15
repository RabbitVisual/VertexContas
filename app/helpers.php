<?php

declare(strict_types=1);

/**
 * Funções globais de conveniência para Helpers LGPD e FormatHelper.
 * Carregadas via composer autoload para uso em views e controllers.
 */

use App\Helpers\FormatHelper;
use App\Helpers\LgpdHelper;

if (! function_exists('format_currency')) {
    /**
     * @param  float|int|string  $value
     */
    function format_currency($value, string $prefix = 'R$', bool $checkInspection = true): string
    {
        return FormatHelper::currency($value, $prefix, $checkInspection);
    }
}

if (! function_exists('format_percent')) {
    /**
     * @param  float|int|string  $value
     */
    function format_percent($value, int $decimals = 1): string
    {
        return FormatHelper::percent($value, $decimals);
    }
}

if (! function_exists('format_percent_decimal')) {
    /**
     * @param  float|int|string  $value
     */
    function format_percent_decimal($value, int $decimals = 1): string
    {
        return FormatHelper::percentDecimal($value, $decimals);
    }
}

if (! function_exists('format_number')) {
    /**
     * @param  float|int|string  $value
     */
    function format_number($value, int $decimals = 2): string
    {
        return FormatHelper::number($value, $decimals);
    }
}

if (! function_exists('format_compact_number')) {
    /**
     * @param  float|int|string  $value
     */
    function format_compact_number($value): string
    {
        return FormatHelper::compactNumber($value);
    }
}

if (! function_exists('lgpd_mask_cpf')) {
    function lgpd_mask_cpf(?string $cpf): string
    {
        return LgpdHelper::maskCpf($cpf);
    }
}

if (! function_exists('lgpd_format_cpf')) {
    function lgpd_format_cpf(?string $cpf): string
    {
        return LgpdHelper::formatCpf($cpf);
    }
}

if (! function_exists('lgpd_mask_cnpj')) {
    function lgpd_mask_cnpj(?string $cnpj): string
    {
        return LgpdHelper::maskCnpj($cnpj);
    }
}

if (! function_exists('lgpd_format_cnpj')) {
    function lgpd_format_cnpj(?string $cnpj): string
    {
        return LgpdHelper::formatCnpj($cnpj);
    }
}

if (! function_exists('lgpd_mask_phone')) {
    function lgpd_mask_phone(?string $phone): string
    {
        return LgpdHelper::maskPhone($phone);
    }
}

if (! function_exists('lgpd_format_phone')) {
    function lgpd_format_phone(?string $phone): string
    {
        return LgpdHelper::formatPhone($phone);
    }
}

if (! function_exists('lgpd_mask_email')) {
    function lgpd_mask_email(?string $email): string
    {
        return LgpdHelper::maskEmail($email);
    }
}

if (! function_exists('lgpd_clean_cpf')) {
    function lgpd_clean_cpf(?string $value): string
    {
        return LgpdHelper::cleanCpf($value);
    }
}

if (! function_exists('lgpd_clean_cnpj')) {
    function lgpd_clean_cnpj(?string $value): string
    {
        return LgpdHelper::cleanCnpj($value);
    }
}

if (! function_exists('lgpd_clean_phone')) {
    function lgpd_clean_phone(?string $value): string
    {
        return LgpdHelper::cleanPhone($value);
    }
}

if (! function_exists('parse_brl_money')) {
    /**
     * Converte string BRL (R$ 1.500,50 ou 1.500,50) para float.
     */
    function parse_brl_money(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        $str = preg_replace('/[^\d,.-]/', '', (string) $value);
        $str = str_replace('.', '', $str);
        $str = str_replace(',', '.', $str);

        return (float) ($str ?: 0);
    }
}

if (! function_exists('parse_brl_date')) {
    /**
     * Converte data DD/MM/YYYY para Y-m-d (para validação/salvamento).
     */
    function parse_brl_date(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $value = trim($value);
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $m)) {
            return sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]);
        }

        return $value;
    }
}
