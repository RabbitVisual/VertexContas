<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockSensitiveInspectionActions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('impersonate_inspection_id')) {
            // Define sensitive route names or patterns
            $sensitiveRoutes = [
                'user.security.password',
                'user.profile.update', // Optional: maybe agent can help fix profile? But password is critical.
                'user.profile.delete', // If it exists
                'admin.users.delete',
                'admin.settings.update',
            ];

            if ($request->routeIs($sensitiveRoutes) || $request->isMethod('DELETE')) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'message' => 'Ação bloqueada: Agentes em modo de inspeção não podem realizar esta alteração sensível.',
                    ], 403);
                }

                return back()->with('error', 'Ação bloqueada durante o modo de inspeção por motivos de segurança.');
            }
        }

        return $next($request);
    }
}
