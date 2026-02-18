<?php

declare(strict_types=1);

namespace Modules\Core\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Models\EmailLog;
use Modules\Core\Models\EmailTemplate;

class VertexDynamicMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public string $templateKey;

    public string $recipientEmail = '';

    protected string $resolvedSubject;

    protected string $renderedBody;

    protected bool $isPlain = false;

    public function __construct(string $templateKey, array $variables = [], ?string $recipientEmail = null)
    {
        $this->templateKey = $templateKey;
        $this->recipientEmail = $recipientEmail ?? '';

        $template = EmailTemplate::where('key', $templateKey)->firstOrFail();
        $this->resolvedSubject = $template->subject;
        $this->isPlain = ! ($template->is_html ?? true);

        $content = $this->substituteVariables($template->content_html ?? '', $variables);
        $this->renderedBody = $this->isPlain ? nl2br(e($content)) : $content;

        $fromAddress = setting('mail_from_address', config('mail.from.address'));
        $entityRefId = 'mail_' . uniqid('', true);
        $this->withSymfonyMessage(new AddVertexMailHeaders($fromAddress, $entityRefId, $this->templateKey));
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
            with: [
                'bodyHtml' => $this->renderedBody,
                'isPlain' => $this->isPlain,
            ],
        );
    }

    protected function substituteVariables(string $html, array $variables): string
    {
        $urlKeys = ['reset_link', 'app_url', 'link'];
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

    public function failed(\Throwable $e): void
    {
        EmailLog::create([
            'user_id' => null,
            'recipient_email' => $this->recipientEmail ?: 'unknown',
            'template_key' => $this->templateKey,
            'status' => 'failed',
            'error_details' => $e->getMessage(),
            'sent_at' => null,
        ]);

        $this->notifyAdminOfFailure();
    }

    protected function notifyAdminOfFailure(): void
    {
        try {
            $admins = \App\Models\User::role('admin')->get();
            $message = sprintf(
                'E-mail falhou após 3 tentativas: template "%s", destinatário "%s". Verifique os Logs de Mensageria.',
                $this->templateKey,
                $this->recipientEmail
            );
            foreach ($admins as $admin) {
                $admin->notify(new \Modules\Notifications\Notifications\SystemNotification(
                    'Falha no envio de e-mail',
                    $message,
                    null,
                    'danger'
                ));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
