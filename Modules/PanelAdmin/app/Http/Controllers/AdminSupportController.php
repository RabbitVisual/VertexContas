<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Helpers\TicketHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Core\Models\Ticket;
use Modules\Core\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;
use Modules\Notifications\Services\NotificationService;
use Illuminate\Support\Facades\Storage;

class AdminSupportController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of all tickets.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignedAgent'])->latest();

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('agent') && $request->agent !== 'all') {
             if ($request->agent === 'unassigned') {
                 $query->whereNull('assigned_agent_id');
             } else {
                 $query->where('assigned_agent_id', $request->agent);
             }
        }

        $tickets = $query->paginate(15);
        $agents = User::role(['support', 'admin'])->get();

        return view('paneladmin::support.index', compact('tickets', 'agents'));
    }

    /**
     * Get messages as JSON (for polling).
     */
    public function messages(Ticket $ticket)
    {
        $ticket->load(['messages.user', 'user']);
        $messages = $ticket->messages->map(fn ($m) => $this->formatMessageForJson($m, $ticket));

        return response()->json([
            'messages' => $messages->values()->all(),
            'ticket' => [
                'id' => $ticket->id,
                'status' => $ticket->status,
                'closed_at' => $ticket->closed_at?->toIso8601String(),
            ],
        ]);
    }

    protected function formatMessageForJson(TicketMessage $msg, ?Ticket $ticket = null): array
    {
        $senderName = $msg->is_system ? 'Vertex Inspection' : ($msg->is_admin_reply ? ($msg->user->name ?? 'Suporte') : ($ticket?->user->name ?? 'Cliente'));
        $isOwn = !$msg->is_system && $msg->user_id === Auth::id();

        return [
            'id' => $msg->id,
            'user_id' => $msg->user_id,
            'message' => $msg->message,
            'is_admin_reply' => (bool) $msg->is_admin_reply,
            'is_system' => (bool) $msg->is_system,
            'created_at' => $msg->created_at->toIso8601String(),
            'sender_name' => $senderName,
            'is_own' => $isOwn,
            'user_photo' => $msg->user?->photo ? asset('storage/' . $msg->user->photo) : null,
        ];
    }

    /**
     * Show the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'assignedAgent', 'messages.user']);
        $agents = User::role(['support', 'admin'])->get();
        $initialMessagesForVue = $ticket->messages->map(fn ($m) => $this->formatMessageForJson($m, $ticket))->values()->all();

        return view('paneladmin::support.show', compact('ticket', 'agents', 'initialMessagesForVue'));
    }

    /**
     * Assign a ticket to an agent.
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
        ]);

        $agent = User::findOrFail($request->agent_id);

        // Ensure the user being assigned has support or admin role
        if (!$agent->hasRole(['support', 'admin'])) {
            return back()->with('error', 'Este usuário não possui permissões de suporte.');
        }

        $ticket->update(['assigned_agent_id' => $agent->id]);

        // Notify Agent
        $this->notificationService->sendToUser(
            $agent,
            'Novo ticket atribuído: #' . $ticket->id,
            'O ticket "' . $ticket->subject . '" foi atribuído a você.',
            'info',
            route('support.tickets.show', $ticket)
        );

        return back()->with('success', 'Ticket atribuído com sucesso!');
    }

    /**
     * Take over a ticket (assign to self).
     */
    public function takeover(Ticket $ticket)
    {
        $ticket->update(['assigned_agent_id' => Auth::id()]);

        return back()->with('success', 'Você assumiu este ticket com sucesso!');
    }

    /**
     * Add a reply to the ticket (standard reply logic).
     */
    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string|max:10000',
            'status' => 'required|in:open,pending,answered,closed',
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin_reply' => true,
        ]);

        $message->load('user');

        $ticket->update([
            'status' => $request->status,
            'last_reply_at' => now(),
            'assigned_agent_id' => $ticket->assigned_agent_id ?? Auth::id(),
        ]);

        // Notify User (com preview da mensagem)
        $preview = TicketHelper::safeMessagePreview($request->message);
        $body = 'O administrador respondeu ao seu ticket: ' . $ticket->subject;
        if ($preview !== '') {
            $body .= "\n\nPreview: " . $preview;
        }
        $this->notificationService->sendToUser(
            $ticket->user,
            'Nova resposta no chamado #' . $ticket->id,
            $body,
            'info',
            route('user.tickets.show', $ticket)
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $this->formatMessageForJson($message, $ticket),
                'ticket' => ['status' => $ticket->fresh()->status],
            ], 201);
        }

        return back()->with('success', 'Resposta enviada com sucesso!');
    }

    /**
     * Update user photo from admin panel.
     */
    public function updateUserPhoto(Request $request, User $user)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('users', 'public');
            $user->update(['photo' => $path]);

            return back()->with('success', 'Foto do perfil atualizada com sucesso!');
        }

        return back()->with('error', 'Erro ao processar a imagem.');
    }
}
