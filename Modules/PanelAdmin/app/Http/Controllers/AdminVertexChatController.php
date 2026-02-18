<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Core\Models\Inspection;
use Modules\Core\Services\FinancialHealthService;
use Modules\VertexChat\Events\AgentTyping;
use Modules\VertexChat\Models\Conversation;
use Modules\VertexChat\Services\ChatService;

class AdminVertexChatController extends Controller
{
    public function __construct(
        private ChatService $chatService,
        private FinancialHealthService $financialHealthService
    ) {}

    public function index(): View
    {
        $conversations = Conversation::with(['user', 'assignedAgent', 'latestMessage.sender'])
            ->whereIn('status', ['open', 'transferred'])
            ->latest('updated_at')
            ->get();

        return view('paneladmin::chat.command-center', [
            'conversations' => $conversations,
            'selectedConversation' => null,
        ]);
    }

    public function show(Conversation $conversation): View|RedirectResponse
    {
        $this->authorizeAdmin($conversation);
        $conversation->load(['messages.sender', 'user', 'assignedAgent']);

        $conversations = Conversation::with(['user', 'assignedAgent', 'latestMessage.sender'])
            ->whereIn('status', ['open', 'transferred'])
            ->latest('updated_at')
            ->get();

        $user = $conversation->user;
        $financialSnapshot = $this->financialHealthService->getUserFinancialSnapshot($user);
        $reserveMonths = $this->financialHealthService->getReserveMonths($user);

        $hasExistingInspection = $user
            ? Inspection::where('user_id', $user->id)->whereIn('status', ['pending', 'active'])->exists()
            : false;

        return view('paneladmin::chat.command-center', [
            'conversations' => $conversations,
            'selectedConversation' => $conversation,
            'financialSnapshot' => $financialSnapshot,
            'reserveMonths' => $reserveMonths,
            'hasExistingInspection' => $hasExistingInspection,
        ]);
    }

    public function sendMessage(Request $request, Conversation $conversation): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $this->authorizeAdmin($conversation);
        $request->validate(['body' => 'required|string|max:10000']);

        $this->chatService->assignAgent($conversation, Auth::user());
        $message = $this->chatService->sendMessage($conversation, Auth::user(), $request->body);

        if ($request->wantsJson()) {
            $sender = $message->sender;
            $avatarUrl = $sender ? $sender->photo_url : null;
            $senderInitial = $sender ? strtoupper(mb_substr($sender->first_name ?? '?', 0, 1)) : '?';
            return response()->json([
                'message' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $sender ? $sender->name : '',
                    'sender_photo' => $avatarUrl,
                    'sender_initial' => $senderInitial,
                    'body' => $message->body,
                    'type' => $message->type,
                    'created_at' => $message->created_at?->toIso8601String(),
                ],
            ], 201);
        }

        return redirect()->route('admin.chat.show', $conversation)->with('success', 'Mensagem enviada.');
    }

    public function transfer(Request $request, Conversation $conversation): RedirectResponse
    {
        $this->authorizeAdmin($conversation);
        $request->validate(['sector' => 'required|in:support,technical,billing,admin']);

        $this->chatService->transferToSector($conversation, $request->sector, Auth::user());

        return redirect()->route('admin.chat.show', $conversation)->with('success', 'Conversa transferida.');
    }

    public function typing(Conversation $conversation): \Illuminate\Http\JsonResponse
    {
        $this->authorizeAdmin($conversation);
        broadcast(new AgentTyping($conversation->id, Auth::user()))->toOthers();

        return response()->json(['ok' => true]);
    }

    private function authorizeAdmin(Conversation $conversation): void
    {
        if (! Auth::user()->hasRole('admin')) {
            abort(403, 'Acesso negado. Apenas administradores.');
        }
    }
}
