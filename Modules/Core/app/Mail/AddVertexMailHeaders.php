<?php

declare(strict_types=1);

namespace Modules\Core\Mail;

use Symfony\Component\Mime\Message;

/**
 * Serializable callable to add Vertex headers to a queued Mailable.
 * Using a class instead of a Closure allows the job to be serialized.
 */
final class AddVertexMailHeaders
{
    public function __construct(
        public readonly string $fromAddress,
        public readonly string $entityRefId,
        public readonly string $templateKey,
    ) {}

    public function __invoke(Message $message): void
    {
        $message->getHeaders()->addTextHeader('List-Unsubscribe', '<mailto:' . $this->fromAddress . '>');
        $message->getHeaders()->addTextHeader('X-Template-Key', $this->templateKey);
        $message->getHeaders()->addTextHeader('X-Entity-Ref-ID', $this->entityRefId);
    }
}
