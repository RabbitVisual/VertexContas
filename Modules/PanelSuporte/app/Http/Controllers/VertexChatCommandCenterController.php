<?php

declare(strict_types=1);

namespace Modules\PanelSuporte\Http\Controllers;

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

class VertexChatCommandCenterController extends Controller
{
    public function __construct(
        private ChatService $chatService,
        private FinancialHealthService $financialHealthService
    ) {}

    public function index(): View
    {
        $conversations = Conversation::with(['user', 'assignedAgent', 'latestMessage'])
            ->whereIn('status', ['open', 'transferred'])
            ->latest('updated_at')
            ->get();

        return view('panelsuporte::chat.command-center', [
            'conversations' => $conversations,
            'selectedConversation' => null,
        ]);
    }

    public function show(Conversation $conversation): View|RedirectResponse
    {
        $this->authorizeAgent($conversation);
        $conversation->load(['messages.sender', 'user', 'assignedAgent']);

        $conversations = Conversation::with(['user', 'assignedAgent', 'latestMessage'])
            ->whereIn('status', ['open', 'transferred'])
            ->latest('updated_at')
            ->get();

        $user = $conversation->user;
        $financialSnapshot = $this->financialHealthService->getUserFinancialSnapshot($user);
        $reserveMonths = $this->financialHealthService->getReserveMonths($user);

        $hasExistingInspection = $user
            ? Inspection::where('user_id', $user->id)->whereIn('status', ['pending', 'active'])->exists()
            : false;

        return view('panelsuporte::chat.command-center', [
            'conversations' => $conversations,
            'selectedConversation' => $conversation,
            'financialSnapshot' => $financialSnapshot,
            'reserveMonths' => $reserveMonths,
            'hasExistingInspection' => $hasExistingInspection,
        ]);
    }

    public function sendMessage(Request $request, Conversation $conversation): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $this->authorizeAgent($conversation);
        $request->validate(['body' => 'required|string|max:10000']);

        $this->chatService->assignAgent($conversation, Auth::user());
        $message = $this->chatService->sendMessage($conversation, Auth::user(), $request->body);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender?->first_name.' '.$message->sender?->last_name,
                    'body' => $message->body,
                    'type' => $message->type,
                    'created_at' => $message->created_at?->toIso8601String(),
                ],
            ], 201);
        }

        return redirect()->route('support.chat.show', $conversation)->with('success', 'Mensagem enviada.');
    }

    public function transfer(Request $request, Conversation $conversation): RedirectResponse
    {
        $this->authorizeAgent($conversation);
        $request->validate(['sector' => 'required|in:support,technical,billing,admin']);

        $this->chatService->transferToSector($conversation, $request->sector, Auth::user());

        return redirect()->route('support.chat.show', $conversation)->with('success', 'Conversa transferida.');
    }

    public function typing(Conversation $conversation): \Illuminate\Http\JsonResponse
    {
        $this->authorizeAgent($conversation);
        broadcast(new AgentTyping($conversation->id, Auth::user()))->toOthers();

        return response()->json(['ok' => true]);
    }

    private function authorizeAgent(Conversation $conversation): void
    {
        $user = Auth::user();
        if (! $user->hasRole('admin') && ! $user->hasRole('support')) {
            abort(403, 'Acesso negado.');
        }
    }
}
