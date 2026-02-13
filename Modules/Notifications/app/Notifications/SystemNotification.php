<?php

namespace Modules\Notifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public $title;

    public $message;

    public $actionUrl;

    public $type; // 'info', 'success', 'warning', 'danger'

    public $icon;

    public $color;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $message, ?string $actionUrl = null, string $type = 'info', ?string $icon = null, ?string $color = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->actionUrl = $actionUrl;
        $this->type = $type;
        $this->icon = $icon;
        $this->color = $color;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'action_url' => $this->actionUrl,
            'type' => $this->type,
            'icon' => $this->icon ?? match ($this->type) {
                'success' => 'check-circle',
                'warning' => 'exclamation-triangle',
                'danger' => 'circle-xmark',
                'pro' => 'crown',
                default => 'bell'
            },
            'color' => $this->color ?? match ($this->type) {
                'success' => 'text-emerald-500',
                'warning' => 'text-amber-500',
                'danger' => 'text-red-500',
                'pro' => 'text-purple-500',
                default => 'text-blue-500'
            },
        ];
    }
}
