<?php

namespace Modules\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Notifications\Services\NotificationService;
use Spatie\Permission\Models\Role;

class AdminNotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        // Simple history: Get recent notifications sent by system
        // We'll group them by title and message to show "blasts"
        $recentNotifications = \DB::table('notifications')
            ->select('data', \DB::raw('count(*) as count'), \DB::raw('max(created_at) as last_sent'), \DB::raw('max(id) as last_id'))
            ->groupBy('data')
            ->orderBy('last_sent', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notif) {
                return (object) [
                    'data' => json_decode($notif->data),
                    'count' => $notif->count,
                    'created_at' => $notif->last_sent,
                    'id' => $notif->last_id,
                ];
            });

        return view('notifications::admin.index', compact('recentNotifications'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['admin', 'super-admin'])->get();

        return view('notifications::admin.create', compact('roles'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'audience' => 'required|in:all,role,user',
            'role' => 'nullable|required_if:audience,role|exists:roles,name',
            'user_search' => 'nullable|required_if:audience,user',
            'user_id' => 'nullable|required_if:audience,user|exists:users,id',
            'type' => 'required|in:info,success,warning,danger',
        ]);

        $type = $request->type;
        $title = $request->title;
        $message = $request->message;

        switch ($request->audience) {
            case 'all':
                $this->notificationService->sendSystemWide($title, $message, $type);
                $target = 'todos os usuários';
                break;
            case 'role':
                $this->notificationService->sendToRole($request->role, $title, $message, $type);
                $translatedRole = match($request->role) {
                    'free_user', 'user' => 'Usuários Comuns',
                    'pro_user', 'pro' => 'Usuários VIP / Pro',
                    'suporte' => 'Equipe de Suporte',
                    'financeiro' => 'Setor Financeiro',
                    default => $request->role
                };
                $target = "usuários do grupo {$translatedRole}";
                break;
            case 'user':
                $user = User::findOrFail($request->user_id);
                $this->notificationService->sendToUser($user, $title, $message, $type);
                $target = "o usuário {$user->name}";
                break;
        }

        return back()->with('success', "Notificação enviada com sucesso para {$target}!");
    }

    public function show($id)
    {
        $notification = \DB::table('notifications')->where('id', $id)->first();
        if (!$notification) {
            abort(404);
        }

        $data = json_decode($notification->data);

        // Find all notifications in this blast (same data)
        $blast = \DB::table('notifications')
            ->where('data', $notification->data)
            ->get();

        return view('notifications::admin.show', compact('notification', 'data', 'blast'));
    }

    public function edit($id)
    {
        $notification = \DB::table('notifications')->where('id', $id)->first();
        if (!$notification) {
            abort(404);
        }

        $data = json_decode($notification->data);
        $roles = Role::whereNotIn('name', ['admin', 'super-admin'])->get();

        return view('notifications::admin.create', [
            'roles' => $roles,
            'template' => $data
        ]);
    }

    public function destroy($id)
    {
        $notification = \DB::table('notifications')->where('id', $id)->first();
        if (!$notification) {
            abort(404);
        }

        // Delete the whole blast
        \DB::table('notifications')->where('data', $notification->data)->delete();

        return redirect()->route('admin.notifications.index')->with('success', 'Histórico de notificação removido com sucesso!');
    }

    public function searchUser(Request $request)
    {
        $term = $request->input('term');

        $users = User::where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('cpf', 'like', "%{$term}%") // Assuming CPF field exists
            ->take(5)
            ->get(['id', 'name', 'email', 'cpf']);

        return response()->json($users);
    }
}
