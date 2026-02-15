<?php

declare(strict_types=1);

/**
 * Helper global para proteção de dados pessoais conforme LGPD (Lei nº 13.709/2018).
 * Centraliza mascaramento, formatação e limpeza de CPF, CNPJ, telefone e e-mail.
 * Reduz risco de vazamento e infração à lei em logs, listagens e contextos de suporte.
 *
 * @author Vertex Solutions LTDA
 * @see https://github.com/vertex-solutions
 */

namespace App\Helpers;

final class LgpdHelper
{
    /**
     * Mascara CPF para exibição em contextos LGPD (logs, suporte, terceiros).
     * Ex: 12345678900 -> ***.***.***-00
     */
    public static function maskCpf(?string $cpf): string
    {
        $digits = self::cleanCpf($cpf);
        if (strlen($digits) !== 11) {
            return '—';
        }

        return '***.***.***-' . substr($digits, -2);
    }

    /**
     * Formata CPF para exibição legível (perfil próprio, documentos autorizados).
     * Ex: 12345678900 -> 123.456.789-00
     */
    public static function formatCpf(?string $cpf): string
    {
        $digits = self::cleanCpf($cpf);
        if (strlen($digits) !== 11) {
            return '—';
        }

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $digits);
    }

    /**
     * Mascara CNPJ para exibição em contextos LGPD.
     * Ex: 12345678000190 -> mascarado com últimos 2 dígitos visíveis
     */
    public static function maskCnpj(?string $cnpj): string
    {
        $digits = self::cleanCnpj($cnpj);
        if (strlen($digits) !== 14) {
            return '—';
        }

        return '**.***.***/****-' . substr($digits, -2);
    }

    /**
     * Formata CNPJ para exibição legível.
     * Ex: 12345678000190 -> 12.345.678/0001-90
     */
    public static function formatCnpj(?string $cnpj): string
    {
        $digits = self::cleanCnpj($cnpj);
        if (strlen($digits) !== 14) {
            return '—';
        }

        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $digits);
    }

    /**
     * Mascara telefone para exibição em contextos LGPD.
     * Ex: 11988887777 -> (11) *****-7777
     */
    public static function maskPhone(?string $phone): string
    {
        $digits = self::cleanPhone($phone);
        if (strlen($digits) < 10) {
            return '—';
        }

        $ddd = substr($digits, 0, 2);
        $last = substr($digits, -4);

        return "({$ddd}) *****-{$last}";
    }

    /**
     * Formata telefone para exibição legível.
     * Ex: 11988887777 -> (11) 98888-7777
     */
    public static function formatPhone(?string $phone): string
    {
        $digits = self::cleanPhone($phone);
        if (strlen($digits) < 10) {
            return '—';
        }

        $ddd = substr($digits, 0, 2);
        $rest = substr($digits, 2);

        if (strlen($rest) === 9) {
            return "({$ddd}) " . substr($rest, 0, 5) . '-' . substr($rest, 5);
        }

        return "({$ddd}) " . substr($rest, 0, 4) . '-' . substr($rest, 4);
    }

    /**
     * Mascara e-mail parcialmente (primeiro char + ***@domínio).
     * Ex: user@domain.com -> u***@domain.com
     */
    public static function maskEmail(?string $email): string
    {
        if ($email === null || $email === '' || ! str_contains($email, '@')) {
            return '—';
        }

        [$local, $domain] = explode('@', $email, 2);
        $first = mb_substr($local, 0, 1);

        return $first . '***@' . $domain;
    }

    /**
     * Remove caracteres não numéricos de CPF. Retorna apenas dígitos.
     */
    public static function cleanCpf(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return preg_replace('/\D/', '', $value);
    }

    /**
     * Remove caracteres não numéricos de CNPJ. Retorna apenas dígitos.
     */
    public static function cleanCnpj(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return preg_replace('/\D/', '', $value);
    }

    /**
     * Remove caracteres não numéricos de telefone. Retorna apenas dígitos.
     */
    public static function cleanPhone(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return preg_replace('/\D/', '', $value);
    }

    /**
     * Exibe CPF/CNPJ/phone formatado ou mascarado conforme contexto.
     * Se $viewingUser === $targetUser (próprio perfil), usa format. Caso contrário, usa mask.
     *
     * @param  object|null  $targetUser  Usuário cujos dados são exibidos
     * @param  object|null  $viewingUser  Usuário que está visualizando (auth)
     * @param  'cpf'|'cnpj'|'phone'  $field
     */
    public static function forContext(?object $targetUser, ?object $viewingUser, string $field): string
    {
        if ($targetUser === null) {
            return '—';
        }

        $value = $targetUser->{$field} ?? null;
        $isOwn = $viewingUser && $targetUser && isset($targetUser->id) && isset($viewingUser->id) && $targetUser->id === $viewingUser->id;

        return match ($field) {
            'cpf' => $isOwn ? self::formatCpf($value) : self::maskCpf($value),
            'cnpj' => $isOwn ? self::formatCnpj($value) : self::maskCnpj($value),
            'phone' => $isOwn ? self::formatPhone($value) : self::maskPhone($value),
            default => (string) $value,
        };
    }
}
