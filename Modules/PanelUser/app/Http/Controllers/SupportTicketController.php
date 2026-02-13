<?php

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\Ticket;
use Modules\Core\Models\TicketMessage;
use Modules\Notifications\Services\NotificationService;

class SupportTicketController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->with('messages.user')->latest()->paginate(10);

        return view('paneluser::tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('paneluser::tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Notify Admins (System Wide or specific Role)
        // Ideally we notify the 'support' role. For now, system wide info.
        $this->notificationService->sendToRole(
            'admin',
            'Novo Chamado de Suporte',
            'O usuário '.Auth::user()->name.' abriu um novo chamado: '.$ticket->subject,
            'info'
        );

        return redirect()->route('user.tickets.show', $ticket)->with('success', 'Chamado aberto com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load('messages.user');

        return view('paneluser::tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if ($ticket->status === 'closed') {
            return back()->with('error', 'Este chamado está encerrado. Entre em contato com o suporte para reabertura.');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $ticket->update(['last_reply_at' => now(), 'status' => 'open']); // Re-open if user replies

        // Notify Agents
        $this->notificationService->sendToRole(
            'admin',
            'Resposta do Usuário no Chamado #'.$ticket->id,
            'O usuário enviou uma nova mensagem.',
            'info'
        );

        return back()->with('success', 'Mensagem enviada!');
    }

    public function rate(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if ($ticket->status !== 'closed') {
            return back()->with('error', 'Você só pode avaliar chamados encerrados.');
        }

        if ($ticket->rating) {
            return back()->with('warning', 'Este chamado já foi avaliado.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'rating_comment' => 'nullable|string|max:1000',
        ]);

        $ticket->update([
            'rating' => $request->rating,
            'rating_comment' => $request->rating_comment,
        ]);

        // Notify Admins/Agent about the rating
        $agentName = $ticket->closedBy ? $ticket->closedBy->name : 'Sistema';
        $this->notificationService->sendToRole(
            'admin',
            'Nova Avaliação de Atendimento',
            "O usuário avaliou o atendimento do agente {$agentName} com {$request->rating} estrelas.",
            'pro'
        );

        return back()->with('success', 'Obrigado por sua avaliação!');
    }
}
