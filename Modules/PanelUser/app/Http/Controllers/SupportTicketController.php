<?php

namespace Modules\PanelUser\Http\Controllers;

use App\Helpers\TicketHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\Ticket;
use Modules\Core\Models\TicketMessage;
use Modules\Notifications\Services\NotificationService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupportTicketController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->with(['messages.user', 'assignedAgent'])->latest()->paginate(10);
        $isPro = Auth::user()->isPro();

        $allTickets = Ticket::where('user_id', Auth::id())->get();
        $stats = [
            'total' => $allTickets->count(),
            'abertos' => $allTickets->where('status', 'open')->count(),
            'pendentes' => $allTickets->where('status', 'pending')->count(),
            'resolvidos' => $allTickets->whereIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('paneluser::tickets.index', compact('tickets', 'isPro', 'stats'));
    }

    public function create()
    {
        $isPro = Auth::user()->isPro();

        return view('paneluser::tickets.create', compact('isPro'));
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
        $preview = TicketHelper::safeMessagePreview($request->message);
        $body = 'O usuário '.Auth::user()->name.' abriu um novo chamado: '.$ticket->subject;
        if ($preview !== '') {
            $body .= "\n\nPreview: ".$preview;
        }
        $this->notificationService->sendToRole(
            'admin',
            'Novo Chamado de Suporte',
            $body,
            'info',
            route('admin.support.show', $ticket)
        );

        return redirect()->route('user.tickets.show', $ticket)->with('success', 'Chamado aberto com sucesso!');
    }

    public function messages(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load('messages.user');
        $messages = $ticket->messages->map(fn ($m) => $this->formatMessageForJson($m));

        return response()->json([
            'messages' => $messages->values()->all(),
            'ticket' => [
                'id' => $ticket->id,
                'status' => $ticket->status,
                'closed_at' => $ticket->closed_at?->toIso8601String(),
            ],
        ]);
    }

    protected function formatMessageForJson(TicketMessage $msg, ?int $ticketUserId = null): array
    {
        $senderName = $msg->is_system ? 'Vertex Inspection' : ($msg->is_admin_reply ? ($msg->user->name ?? 'Suporte') : ($msg->user->name ?? 'Cliente'));
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

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['messages.user', 'assignedAgent', 'closedBy']);
        $isPro = Auth::user()->isPro();

        $firstResponseAt = null;
        $firstResponseHours = null;
        if ($isPro) {
            $firstReply = $ticket->messages->where('is_admin_reply', true)->first();
            if ($firstReply) {
                $firstResponseAt = $firstReply->created_at;
                $firstResponseHours = round($ticket->created_at->diffInMinutes($firstResponseAt) / 60, 1);
            }
        }

        $inspectionActive = \Modules\Core\Models\Inspection::where('ticket_id', $ticket->id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->exists();

        $initialMessagesForVue = $ticket->messages->map(fn ($m) => $this->formatMessageForJson($m))->values()->all();

        return view('paneluser::tickets.show', compact(
            'ticket', 'isPro', 'inspectionActive',
            'firstResponseAt', 'firstResponseHours', 'initialMessagesForVue'
        ));
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
            'message' => 'required|string|max:10000',
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);
        $message->load('user');

        $ticket->update(['last_reply_at' => now(), 'status' => 'open']); // Re-open if user replies

        // Notify Agents (com preview da mensagem)
        $preview = TicketHelper::safeMessagePreview($request->message);
        $body = 'O usuário enviou uma nova mensagem no chamado #'.$ticket->id.': '.$ticket->subject;
        if ($preview !== '') {
            $body .= "\n\nPreview: ".$preview;
        }
        $this->notificationService->sendToRole(
            'admin',
            'Resposta do Usuário no Chamado #'.$ticket->id,
            $body,
            'info',
            route('admin.support.show', $ticket)
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $this->formatMessageForJson($message, $ticket->user_id),
                'ticket' => ['status' => $ticket->fresh()->status],
            ], 201);
        }

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

    /**
     * Export tickets history as CSV (PRO only).
     */
    public function exportTickets(): StreamedResponse
    {
        if (!Auth::user()->isPro()) {
            abort(403, 'Recurso exclusivo para Vertex PRO.');
        }

        $tickets = Ticket::where('user_id', Auth::id())
            ->with('messages')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'historico-chamados-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($tickets) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Assunto', 'Prioridade', 'Status', 'Data Abertura', 'Mensagens', 'Fechado Em'], ';');
            foreach ($tickets as $ticket) {
                fputcsv($handle, [
                    $ticket->id,
                    $ticket->subject,
                    $ticket->priority,
                    $ticket->status,
                    $ticket->created_at->format('d/m/Y H:i'),
                    $ticket->messages->count(),
                    $ticket->closed_at ? $ticket->closed_at->format('d/m/Y H:i') : '-',
                ], ';');
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Export single ticket as CSV (PRO only).
     */
    public function exportTicket(Ticket $ticket): StreamedResponse
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }
        if (! Auth::user()->isPro()) {
            abort(403, 'Recurso exclusivo para Vertex PRO.');
        }

        $filename = 'chamado-' . $ticket->id . '-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($ticket) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Data', 'Remetente', 'Mensagem'], ';');
            foreach ($ticket->messages as $msg) {
                $sender = $msg->is_system ? 'Vertex Inspection' : ($msg->is_admin_reply ? ($msg->user->name ?? 'Suporte') : 'Você');
                fputcsv($handle, [
                    $msg->created_at->format('d/m/Y H:i:s'),
                    $sender,
                    $msg->message,
                ], ';');
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
