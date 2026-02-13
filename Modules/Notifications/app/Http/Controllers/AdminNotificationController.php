<?php

namespace Modules\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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
        $roles = Role::whereNotIn('name', ['admin', 'super-admin'])->get();
        return view('notifications::admin.index', compact('roles'));
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
                $target = "usuários com perfil {$request->role}";
                break;
            case 'user':
                $user = User::findOrFail($request->user_id);
                $this->notificationService->sendToUser($user, $title, $message, $type);
                $target = "o usuário {$user->name}";
                break;
        }

        return back()->with('success', "Notificação enviada com sucesso para {$target}!");
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
