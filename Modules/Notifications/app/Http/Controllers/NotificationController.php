<?php

namespace Modules\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Fetch unread notifications for the current user.
     * Use polling (AJAX) to call this.
     */
    public function fetchUnread()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['count' => 0, 'notifications' => []]);
        }

        $query = $user->unreadNotifications();
        $count = $query->count();

        $unreadNotifications = $query->latest()->take(5)->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Notificação',
                'message' => $n->data['message'] ?? '',
                'type' => $n->data['type'] ?? 'info',
                'icon' => $n->data['icon'] ?? 'bell',
                'color' => $n->data['color'] ?? 'text-blue-500',
                'action_url' => $n->data['action_url'] ?? null,
                'created_at_human' => $n->created_at->diffForHumans(),
                'created_at_timestamp' => $n->created_at->timestamp, // For Toast sorting/detection
            ];
        });

        return response()->json([
            'count' => $count,
            'notifications' => $unreadNotifications
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notificação marcada como lida.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    }
}
