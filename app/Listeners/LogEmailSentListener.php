<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Modules\Core\Models\EmailLog;
use Symfony\Component\Mime\Address;

class LogEmailSentListener
{
    /**
     * Log successful email delivery to email_logs when X-Template-Key header is present.
     */
    public function __invoke(MessageSent $event): void
    {
        $message = $event->sent->getOriginalMessage();
        $headers = $message->getHeaders();

        $templateKey = null;
        if ($headers->has('X-Template-Key')) {
            $templateKey = $headers->get('X-Template-Key')->getBodyAsString();
        }
        if ($templateKey === null || $templateKey === '') {
            return;
        }

        $to = $message->getTo();
        $recipient = $this->firstAddress($to);
        if ($recipient === null) {
            return;
        }

        $bodySnapshot = null;
        try {
            $body = $message->getBody();
            if ($body !== null && method_exists($body, 'bodyToString')) {
                $bodySnapshot = $body->bodyToString();
            } elseif ($body !== null && method_exists($body, 'getBody')) {
                $bodySnapshot = $body->getBody();
            }
        } catch (\Throwable $e) {
            // ignore
        }

        EmailLog::create([
            'user_id' => null,
            'recipient_email' => $recipient,
            'template_key' => $templateKey,
            'status' => 'sent',
            'smtp_response' => null,
            'error_details' => null,
            'body_snapshot' => $bodySnapshot,
            'sent_at' => now(),
        ]);
    }

    private function firstAddress(array $addresses): ?string
    {
        foreach ($addresses as $address) {
            if ($address instanceof Address) {
                return $address->getAddress();
            }
        }

        return null;
    }
}
