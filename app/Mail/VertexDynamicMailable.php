<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Modules\Core\Models\EmailTemplate;

class VertexDynamicMailable extends Mailable
{
    /** Template key for logging (e.g. password_reset, welcome_email). */
    public string $templateKey;

    /** Recipient email for failure logging (set by child when queued). */
    public string $recipientEmail = '';

    /** Resolved subject from template. */
    protected string $resolvedSubject;

    /** Rendered HTML body (variables already substituted). */
    protected string $renderedBody;

    public function __construct(string $templateKey, array $variables = [])
    {
        $this->templateKey = $templateKey;

        $template = EmailTemplate::where('key', $templateKey)->firstOrFail();
        $this->resolvedSubject = $template->subject;
        $raw = $this->substituteVariables($template->content_html ?? $template->body_html ?? '', $variables);
        $this->renderedBody = ! ($template->is_html ?? true) ? nl2br(e($raw)) : $raw;

        $fromAddress = setting('mail_from_address', config('mail.from.address'));
        $this->withSymfonyMessage(function ($message) use ($fromAddress): void {
            $message->getHeaders()->addTextHeader('List-Unsubscribe', '<mailto:' . $fromAddress . '>');
            $message->getHeaders()->addTextHeader('X-Template-Key', $this->templateKey);
        });
    }

    public function envelope(): Envelope
    {
        $fromAddress = setting('mail_from_address', config('mail.from.address'));
        $fromName = setting('mail_from_name', config('mail.from.name'));
        $from = new Address($fromAddress, $fromName);

        return new Envelope(
            subject: $this->resolvedSubject,
            from: $from,
            replyTo: [$fromAddress],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dynamic-content',
            with: ['bodyHtml' => $this->renderedBody],
        );
    }

    /**
     * Replace {{ key }} placeholders in HTML; escape text values for XSS safety.
     * URL variables (reset_link, app_url) are passed as-is (caller must pass safe URLs).
     */
    protected function substituteVariables(string $html, array $variables): string
    {
        $urlKeys = ['reset_link', 'app_url'];
        $variables['app_url'] = $variables['app_url'] ?? config('app.url');

        foreach ($variables as $key => $value) {
            $placeholder = '{{ ' . $key . ' }}';
            if (in_array($key, $urlKeys, true)) {
                $html = str_replace($placeholder, (string) $value, $html);
            } else {
                $html = str_replace($placeholder, e((string) $value), $html);
            }
        }

        return $html;
    }

    /**
     * Called when the queued mailable job fails. Log to email_logs if recipient is set.
     */
    public function failed(\Throwable $e): void
    {
        if ($this->recipientEmail === '') {
            return;
        }
        \Modules\Core\Models\EmailLog::create([
            'user_id' => null,
            'recipient_email' => $this->recipientEmail,
            'template_key' => $this->templateKey,
            'status' => 'failed',
            'error_details' => $e->getMessage(),
            'sent_at' => null,
        ]);
    }
}

