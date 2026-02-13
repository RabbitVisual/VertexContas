<?php

namespace Modules\PanelAdmin\Http\Controllers;

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
     * Show the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'assignedAgent', 'messages.user']);
        $agents = User::role(['support', 'admin'])->get();

        return view('paneladmin::support.show', compact('ticket', 'agents'));
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
            'message' => 'required|string',
            'status' => 'required|in:open,pending,answered,closed',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin_reply' => true,
        ]);

        $ticket->update([
            'status' => $request->status,
            'last_reply_at' => now(),
            // Auto-assign to self if unassigned and replying
            'assigned_agent_id' => $ticket->assigned_agent_id ?? Auth::id(),
        ]);

        // Notify User
        $this->notificationService->sendToUser(
            $ticket->user,
            'Nova resposta no chamado #' . $ticket->id,
            'O administrador respondeu ao seu ticket: ' . $ticket->subject,
            'info',
            route('user.tickets.show', $ticket)
        );

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
