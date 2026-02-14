<?php

declare(strict_types=1);

/**
 * Helper global para privacidade de dados sensíveis.
 * Permite esconder/exibir valores financeiros e dados sensíveis no painel do usuário.
 * Uso: ao clicar no ícone de olho na navbar, todos os elementos com classe 'sensitive-value' são mascarados.
 *
 * @author Vertex Solutions LTDA
 * @see https://github.com/vertex-solutions
 */

namespace App\Helpers;

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
}
