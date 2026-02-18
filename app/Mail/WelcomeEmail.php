<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Modules\Core\Mail\VertexDynamicMail;

class WelcomeEmail extends VertexDynamicMail
{
    public function __construct(User $user)
    {
        parent::__construct('welcome_user', [
            'name' => $user->name,
            'app_url' => config('app.url'),
        ], $user->email);
    }
}
