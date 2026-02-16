<?php

declare(strict_types=1);

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Core\Models\Inspection;
use Modules\Core\Models\Ticket;
use Modules\Core\Models\TicketMessage;
use Modules\Notifications\Services\NotificationService;
use Modules\VertexChat\Models\Conversation;

class InspectionController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Request a new inspection from Chat (PRO users).
     */
    public function requestFromChat(Conversation $conversation)
    {
        $user = $conversation->user;
        if (! $user) {
            return back()->with('error', 'Conversa sem usuário associado.');
        }

        $existing = Inspection::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'active'])
            ->first();

        if ($existing) {
            return back()->with('warning', 'Já existe uma solicitação de inspeção em andamento para este cliente.');
        }

        Inspection::create([
            'conversation_id' => $conversation->id,
            'agent_id' => Auth::id(),
            'user_id' => $user->id,
            'status' => 'pending',
            'token' => Str::random(64),
        ]);

        $this->notificationService->sendToUser(
            $user,
            'Solicitação de Inspeção Remota',
            'O agente ' . Auth::user()->name . ' solicitou acesso ao seu painel para atendimento via Chat VIP.',
            'warning',
            route('user.notifications.index'),
            'magnifying-glass-chart',
            'text-amber-500'
        );

        return back()->with('success', 'Solicitação de inspeção enviada ao cliente PRO com sucesso!');
    }

    /**
     * Request a new inspection.
     */
    public function request(Ticket $ticket)
    {
        // Check if there is already a pending or active inspection for this ticket/user
        $existing = Inspection::where('ticket_id', $ticket->id)
            ->whereIn('status', ['pending', 'active'])
            ->first();

        if ($existing) {
            return back()->with('warning', 'Já existe uma solicitação de inspeção em andamento para este ticket.');
        }

        $inspection = Inspection::create([
            'ticket_id' => $ticket->id,
            'agent_id' => Auth::id(),
            'user_id' => $ticket->user_id,
            'status' => 'pending',
            'token' => Str::random(64),
        ]);

        // Send notification to User
        $this->notificationService->sendToUser(
            $ticket->user,
            'Solicitação de Inspeção Remota',
            'O agente '.Auth::user()->name." solicitou acesso ao seu painel para auxiliar no chamado #{$ticket->id}.",
            'warning',
            route('user.notifications.index'), // actionUrl
            'magnifying-glass-chart',         // icon
            'text-amber-500'                 // color
        );

        return back()->with('success', 'Solicitação de inspeção enviada ao cliente com sucesso!');
    }

    /**
     * Enter the user's dashboard (Impersonation).
     */
    public function enter(Inspection $inspection)
    {
        if ($inspection->agent_id !== Auth::id() || $inspection->status !== 'active') {
            abort(403, 'Acesso não autorizado ou inspeção não está ativa.');
        }

        // Store current agent ID to return later
        session(['original_agent_id' => Auth::id()]);
        session(['impersonate_inspection_id' => $inspection->id]);

        // Login as the client
        Auth::loginUsingId($inspection->user_id);

        // System audit message: inspection started (only for ticket-originated)
        if ($inspection->ticket_id) {
            $agentName = $inspection->agent->name ?? 'Agente';
            TicketMessage::create([
                'ticket_id' => $inspection->ticket_id,
                'user_id' => $inspection->agent_id,
                'message' => "🛡️ Vertex Inspection — Inspeção remota iniciada pelo agente {$agentName}. Acompanhe em tempo real no seu painel. Aguarde a resposta do suporte após a análise.",
                'is_admin_reply' => true,
                'is_system' => true,
            ]);
        }

        return redirect()->route('paneluser.index')
            ->with('success', 'Você entrou no modo de inspeção. O banner superior indica sua sessão ativa.');
    }

    /**
     * Stop an active inspection (with mandatory audit report).
     */
    public function stop(Request $request, Inspection $inspection)
    {
        $request->validate([
            'report' => ['required', 'string', 'min:10'],
        ], [
            'report.required' => 'O resumo da ação técnica é obrigatório.',
            'report.min' => 'O resumo deve ter pelo menos 10 caracteres.',
        ]);

        // If we are currently impersonating, the Auth::id() is the client's ID.
        $originalAgentId = session('original_agent_id');

        if (! $originalAgentId) {
            if ($inspection->agent_id !== Auth::id()) {
                abort(403);
            }
            $originalAgentId = $inspection->agent_id;
        }

        $startedAt = $inspection->started_at;
        $durationSeconds = $startedAt ? now()->diffInSeconds($startedAt) : 0;

        $inspection->update([
            'status' => 'completed',
            'ended_at' => now(),
        ]);

        session()->forget('impersonate_inspection_id');
        session()->forget('original_agent_id');

        Auth::logout();
        Auth::loginUsingId($originalAgentId);

        // Mandatory audit log
        SupportAuditLog::create([
            'agent_id' => $originalAgentId,
            'user_id' => $inspection->user_id,
            'action' => 'inspection_completed',
            'metadata' => [
                'report' => $request->string('report')->trim()->toString(),
                'duration_seconds' => $durationSeconds,
                'ticket_id' => $inspection->ticket_id,
                'conversation_id' => $inspection->conversation_id,
            ],
            'ip_address' => $request->ip(),
        ]);

        // System audit message: only for ticket-originated
        if ($inspection->ticket_id) {
            $inspectionCount = Inspection::where('ticket_id', $inspection->ticket_id)->where('status', 'completed')->count();
            $isPro = $inspection->client->isPro();
            $countText = $inspectionCount === 1
                ? '1ª inspeção deste chamado'
                : "{$inspectionCount}ª inspeção deste chamado";
            $message = $isPro
                ? "✅ Vertex Inspection — Inspeção finalizada com sucesso pelo agente. {$countText}. Análise concluída. Aguarde o retorno do suporte com o diagnóstico e próximos passos."
                : "✅ Vertex Inspection — Inspeção finalizada pelo agente. {$countText}. Aguarde a resposta do suporte.";
            TicketMessage::create([
                'ticket_id' => $inspection->ticket_id,
                'user_id' => $originalAgentId,
                'message' => $message,
                'is_admin_reply' => true,
                'is_system' => true,
            ]);
        }

        $this->notificationService->sendToUser(
            $inspection->client,
            'Inspeção Concluída',
            'A inspeção remota do suporte foi finalizada com sucesso.',
            'success',
            null,
            'door-open',
            'text-emerald-500'
        );

        if ($inspection->conversation_id) {
            return redirect()->route('support.chat.show', $inspection->conversation)
                ->with('success', 'Inspeção finalizada. Você retornou ao painel de suporte.');
        }

        return redirect()->route('support.tickets.show', $inspection->ticket_id)
            ->with('success', 'Inspeção finalizada. Você retornou ao seu painel de suporte.');
    }

    /**
     * Check if the current user has an active inspection session.
     * Usado via AJAX pelo banner. Se acessado diretamente no navegador, redireciona.
     */
    public function checkSession()
    {
        if (! request()->ajax() && ! request()->wantsJson()) {
            return redirect()->route('paneluser.index');
        }

        return response()->json([
            'active' => session()->has('impersonate_inspection_id')
        ]);
    }
}
