<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Helpers\TicketHelper;
use App\Http\Controllers\Controller;
use App\Models\SupportAuditLog;
use Illuminate\Http\Request;
use Modules\Core\Models\Inspection;
use Modules\Core\Models\Ticket;
use Modules\Core\Models\TicketMessage;
use Modules\Core\Services\FinancialHealthService;
use Illuminate\Support\Facades\Auth;
use Modules\Notifications\Services\NotificationService;

class SupportAgentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        $openTickets = Ticket::where('status', 'open')->count();
        $pendingTickets = Ticket::where('status', 'pending')->count();
        $highPriority = Ticket::where('status', 'open')->where('priority', 'high')->count();

        $financialHealthService = app(FinancialHealthService::class);
        $usersAtFinancialRisk = $financialHealthService->getUsersAtFinancialRiskCount();
        $activeInspections = Inspection::whereIn('status', ['pending', 'active'])->count();

        $recentTickets = Ticket::with('user')->latest()->take(5)->get();
        $pendingComments = \Modules\Blog\Models\Comment::with(['post', 'user'])->where('is_approved', false)->take(5)->get();

        $recentSupportActivities = SupportAuditLog::with(['agent', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('panelsuporte::dashboard', compact(
            'openTickets',
            'pendingTickets',
            'highPriority',
            'usersAtFinancialRisk',
            'activeInspections',
            'recentTickets',
            'pendingComments',
            'recentSupportActivities'
        ));
    }

    public function index(Request $request)
    {
        $query = Ticket::with('user')->latest();

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->paginate(15);

        return view('panelsuporte::tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'messages.user']);
        $initialMessagesForVue = $ticket->messages->map(fn ($m) => $this->formatMessageForJson($m, $ticket))->values()->all();
        return view('panelsuporte::tickets.show', compact('ticket', 'initialMessagesForVue'));
    }

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

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string|max:10000',
            'status' => 'required|in:open,pending,answered,closed',
        ]);

        // Enforce: Only Admin can reopen a closed ticket
        if ($ticket->status === 'closed' && $request->status !== 'closed') {
            if (!Auth::user()->hasRole('admin')) {
                return back()->with('error', 'Apenas administradores podem reabrir chamados encerrados.');
            }
        }

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
        ]);

        // Notify User (com preview da mensagem)
        $preview = TicketHelper::safeMessagePreview($request->message);
        $body = 'Um agente respondeu ao seu ticket: ' . $ticket->subject;
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

    public function close(Ticket $ticket)
    {
        if ($ticket->status === 'closed') {
            return back()->with('warning', 'Este chamado já está encerrado.');
        }

        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => Auth::id(),
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => '🔒 **ATENDIMENTO ENCERRADO:** O agente finalizou este chamado. Caso necessite de mais ajuda, o administrador poderá reabri-lo.',
            'is_admin_reply' => true,
        ]);

        // Notify User
        $this->notificationService->sendToUser(
            $ticket->user,
            'Chamado Encerrado',
            'Seu chamado #' . $ticket->id . ' foi encerrado pelo suporte. Por favor, avalie o atendimento.',
            'success',
            route('user.tickets.show', $ticket)
        );

        return back()->with('success', 'Chamado encerrado com sucesso!');
    }
}
