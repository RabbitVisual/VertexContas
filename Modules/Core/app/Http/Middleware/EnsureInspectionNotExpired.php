<?php

declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use App\Models\SupportAuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\Inspection;
use Symfony\Component\HttpFoundation\Response;

class EnsureInspectionNotExpired
{
    /**
     * Handle an incoming request. Auto-stops inspection if max duration exceeded.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $inspectionId = session('impersonate_inspection_id');
        $originalAgentId = session('original_agent_id');

        if (! $inspectionId || ! $originalAgentId) {
            return $next($request);
        }

        $inspection = Inspection::find($inspectionId);
        if (! $inspection || $inspection->status !== 'active') {
            return $next($request);
        }

        $maxDuration = (int) setting('security_inspection_max_duration', 1800);
        $startedAt = $inspection->started_at;

        if (! $startedAt || $startedAt->diffInSeconds(now()) < $maxDuration) {
            return $next($request);
        }

        $inspection->update([
            'status' => 'completed',
            'ended_at' => now(),
        ]);

        session()->forget('impersonate_inspection_id');
        session()->forget('original_agent_id');

        Auth::logout();
        Auth::loginUsingId($originalAgentId);

        SupportAuditLog::create([
            'agent_id' => $originalAgentId,
            'user_id' => $inspection->user_id,
            'action' => 'inspection_expired',
            'metadata' => [
                'report' => 'Sessão expirada automaticamente por tempo máximo configurado.',
                'duration_seconds' => $startedAt->diffInSeconds(now()),
                'ticket_id' => $inspection->ticket_id,
                'conversation_id' => $inspection->conversation_id,
            ],
            'ip_address' => $request->ip(),
        ]);

        if ($inspection->conversation_id) {
            return redirect()->route('support.chat.show', $inspection->conversation)
                ->with('warning', 'Inspeção encerrada automaticamente por tempo limite.');
        }

        return redirect()->route('support.tickets.show', $inspection->ticket_id)
            ->with('warning', 'Inspeção encerrada automaticamente por tempo limite.');
    }
}
