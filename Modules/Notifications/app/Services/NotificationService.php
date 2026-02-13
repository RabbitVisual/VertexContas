<?php

namespace Modules\Notifications\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Modules\Notifications\Notifications\SystemNotification;

class NotificationService
{
    /**
     * Send a notification to a specific user.
     */
    public function sendToUser(User $user, string $title, string $message, string $type = 'info', ?string $actionUrl = null, ?string $icon = null, ?string $color = null): void
    {
        $user->notify(new SystemNotification($title, $message, $actionUrl, $type, $icon, $color));
    }

    /**
     * Send a notification to all users (System Wide).
     */
    public function sendSystemWide(string $title, string $message, string $type = 'info', ?string $actionUrl = null, ?string $icon = null, ?string $color = null): void
    {
        $users = User::all();
        Notification::send($users, new SystemNotification($title, $message, $actionUrl, $type, $icon, $color));
    }

    /**
     * Send a notification to users with a specific role.
     */
    public function sendToRole(string $roleName, string $title, string $message, string $type = 'info', ?string $actionUrl = null, ?string $icon = null, ?string $color = null): void
    {
        $users = User::role($roleName)->get();
        Notification::send($users, new SystemNotification($title, $message, $actionUrl, $type, $icon, $color));
    }
}
