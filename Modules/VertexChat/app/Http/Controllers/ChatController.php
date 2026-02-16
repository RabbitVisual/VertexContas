<?php

declare(strict_types=1);

namespace Modules\VertexChat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\VertexChat\Models\Conversation;
use Modules\VertexChat\Services\ChatService;

class ChatController extends Controller
{
    public function __construct(
        private ChatService $chatService
    ) {}

    public function index(): View|RedirectResponse
    {
        $conversations = Conversation::where('user_id', Auth::id())
            ->with(['assignedAgent', 'latestMessage'])
            ->latest('updated_at')
            ->get();

        return view('vertexchat::chat.index', compact('conversations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $conversation = Conversation::where('user_id', $user->id)
            ->whereIn('status', ['open', 'transferred'])
            ->latest()
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'sector' => 'support',
                'status' => 'open',
            ]);
        }

        return redirect()->route('vertexchat.chat.show', $conversation);
    }

    public function show(Conversation $conversation): View|RedirectResponse
    {
        $this->authorizeConversation($conversation);
        $conversation->load(['messages.sender', 'user', 'assignedAgent']);

        return view('vertexchat::chat.show', compact('conversation'));
    }

    public function sendMessage(Request $request, Conversation $conversation): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $this->authorizeConversation($conversation);
        $request->validate(['body' => 'required|string|max:10000']);

        $message = $this->chatService->sendMessage($conversation, Auth::user(), $request->body);

        if ($request->wantsJson()) {
            return response()->json(['message' => $this->formatMessage($message)], 201);
        }

        return back()->with('success', 'Mensagem enviada.');
    }

    private function authorizeConversation(Conversation $conversation): void
    {
        $user = Auth::user();
        if (
            (int) $conversation->user_id !== (int) $user->id
            && (int) $conversation->assigned_agent_id !== (int) $user->id
            && ! $user->hasRole('admin')
            && ! $user->hasRole('support')
        ) {
            abort(403, 'Sem permissão para acessar esta conversa.');
        }
    }

    private function formatMessage($message): array
    {
        $message->load('sender');
        $sender = $message->sender;
        $avatarUrl = $sender ? $sender->photo_url : null;
        $senderInitial = $sender
            ? strtoupper(mb_substr($sender->first_name ?? '?', 0, 1))
            : '?';

        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'sender_name' => $sender ? $sender->first_name.' '.$sender->last_name : '',
            'sender_photo' => $avatarUrl,
            'sender_initial' => $senderInitial,
            'body' => $message->body,
            'type' => $message->type,
            'created_at' => $message->created_at?->toIso8601String(),
        ];
    }
}
