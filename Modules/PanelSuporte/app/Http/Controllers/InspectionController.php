<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Core\Models\Inspection;
use Modules\Core\Models\Ticket;
use Modules\Core\Models\TicketMessage;
use Modules\Notifications\Services\NotificationService;

class InspectionController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
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

        // System audit message: inspection started
        $agentName = $inspection->agent->name ?? 'Agente';
        TicketMessage::create([
            'ticket_id' => $inspection->ticket_id,
            'user_id' => $inspection->agent_id,
            'message' => "🛡️ Vertex Inspection — Inspeção remota iniciada pelo agente {$agentName}. Acompanhe em tempo real no seu painel. Aguarde a resposta do suporte após a análise.",
            'is_admin_reply' => true,
            'is_system' => true,
        ]);

        return redirect()->route('paneluser.index')
            ->with('success', 'Você entrou no modo de inspeção. O banner superior indica sua sessão ativa.');
    }

    /**
     * Stop an active inspection.
     */
    public function stop(Inspection $inspection)
    {
        // If we are currently impersonating, the Auth::id() is the client's ID.
        // We need to check if the original agent is the one stopping.
        $originalAgentId = session('original_agent_id');

        if (! $originalAgentId) {
            // Fallback for direct agent access if session lost but db active
            if ($inspection->agent_id !== Auth::id()) {
                abort(403);
            }
            $originalAgentId = $inspection->agent_id;
        }

        $inspection->update([
            'status' => 'completed',
            'ended_at' => now(),
        ]);

        // Clear session
        session()->forget('impersonate_inspection_id');
        session()->forget('original_agent_id');

        // Logout from client and log back in as agent
        Auth::logout();
        Auth::loginUsingId($originalAgentId);

        // System audit message: inspection ended (with count, PRO-aware)
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

        // Notify user
        $this->notificationService->sendToUser(
            $inspection->client,
            'Inspeção Concluída',
            'A inspeção remota do suporte foi finalizada com sucesso.',
            'success',
            null, // actionUrl
            'door-open',
            'text-emerald-500'
        );

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
