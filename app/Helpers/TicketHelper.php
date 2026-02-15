<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Str;

/**
 * Helper para operações seguras relacionadas a tickets de suporte.
 * Garante sanitização de conteúdo em previews e notificações.
 */
final class TicketHelper
{
    /**
     * Tamanho padrão para preview de mensagem em listagens e notificações.
     */
    public const PREVIEW_LIMIT = 80;

    /**
     * Retorna um preview seguro da mensagem (sem HTML) para exibição em listas,
     * notificações e outros contextos onde o texto completo não é necessário.
     *
     * O conteúdo completo permanece como string pura no banco.
     *
     * @param  string|null  $message  Conteúdo bruto da mensagem
     * @param  int  $limit  Máximo de caracteres (default 80)
     * @return string Texto sanitizado e truncado
     */
    public static function safeMessagePreview(?string $message, int $limit = self::PREVIEW_LIMIT): string
    {
        if ($message === null || $message === '') {
            return '';
        }

        $plain = strip_tags($message);
        $decoded = html_entity_decode($plain, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return Str::limit($decoded, $limit);
    }
}
