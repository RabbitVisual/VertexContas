<?php

declare(strict_types=1);

/**
 * Helper global para privacidade de dados sensíveis.
 * Permite esconder/exibir valores financeiros e dados sensíveis no painel do usuário.
 * Uso: ao clicar no ícone de olho na navbar, todos os elementos com classe 'sensitive-value' são mascarados.
 *
 * Durante inspeção remota: quando o usuário não autoriza "Exibir Dados Financeiros",
 * o InspectionGuard força o mascaramento no servidor (dados jamais são enviados ao agente).
 *
 * @author Vertex Solutions LTDA
 * @see https://github.com/vertex-solutions
 */

namespace App\Helpers;

use Modules\Core\Services\InspectionGuard;

final class SensitiveHelper
{
    /**
     * Classe CSS aplicada a elementos que devem ser mascarados quando o modo privacidade está ativo.
     */
    public const SENSITIVE_CLASS = 'sensitive-value';

    /**
     * Chave do localStorage para persistir o estado (ocultar/exibir).
     */
    public const STORAGE_KEY = 'sensitive-hidden';

    /**
     * Retorna a classe CSS para elementos sensíveis.
     */
    public static function sensitiveClass(): string
    {
        return self::SENSITIVE_CLASS;
    }

    /**
     * Retorna o atributo data para uso em elementos HTML.
     */
    public static function dataAttr(): string
    {
        return 'data-sensitive="true"';
    }

    /**
     * Verifica se o modo privacidade deve iniciar ativo (via localStorage, no client).
     * Este método é apenas informativo; o estado real é controlado no JavaScript.
     */
    public static function storageKey(): string
    {
        return self::STORAGE_KEY;
    }

    /**
     * Retorna o valor formatado para exibição, mascarado quando inspeção ativa sem permissão financeira.
     *
     * @param  float|int|string  $value
     */
    public static function formatForInspection($value, string $prefix = 'R$'): string
    {
        return InspectionGuard::maskValue($value, $prefix);
    }

    /**
     * Se deve ocultar dados financeiros (inspeção ativa + usuário negou).
     */
    public static function inspectionHidesFinancial(): bool
    {
        return InspectionGuard::shouldHideFinancialData();
    }
}
