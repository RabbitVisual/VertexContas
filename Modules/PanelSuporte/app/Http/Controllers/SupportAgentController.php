<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Models\Ticket;
use Modules\Core\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;
use Modules\Notifications\Services\NotificationService;
use Modules\Blog\Models\Comment;

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

        $recentTickets = Ticket::with('user')->latest()->take(5)->get();

        // Blog: Pending Comments
        $pendingComments = Comment::where('is_approved', false)->with('post', 'user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('panelsuporte::dashboard', compact('openTickets', 'pendingTickets', 'highPriority', 'recentTickets', 'pendingComments'));
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
        return view('panelsuporte::tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
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

        $ticket->update([
            'status' => $request->status,
            'last_reply_at' => now(),
        ]);

        // Notify User
        $this->notificationService->sendToUser(
            $ticket->user,
            'Nova resposta no chamado #' . $ticket->id,
            'Um agente respondeu ao seu ticket: ' . $ticket->subject,
            'info',
            route('user.tickets.show', $ticket)
        );

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
