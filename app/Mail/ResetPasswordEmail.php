<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Modules\Core\Mail\VertexDynamicMail;

/**
 * Password reset e-mail. Sent synchronously (no queue) for immediate delivery.
 * Uses Core VertexDynamicMail with key password_reset.
 */
class ResetPasswordEmail extends VertexDynamicMail
{
    public function __construct(User $user, string $resetUrl)
    {
        parent::__construct('password_reset', [
            'name' => $user->name,
            'email' => $user->email,
            'reset_link' => $resetUrl,
            'app_url' => config('app.url'),
        ], $user->email);
    }
}
