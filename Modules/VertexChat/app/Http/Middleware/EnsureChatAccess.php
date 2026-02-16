<?php

declare(strict_types=1);

namespace Modules\VertexChat\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureChatAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! vertex_chat_enabled()) {
            return redirect()->back()->with('error', 'Chat VIP está temporariamente desativado.');
        }

        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Faça login para acessar o chat.');
        }

        if ($user->isPro() || $user->hasRole('admin') || $user->hasRole('support')) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'Chat VIP é exclusivo para membros PRO. Faça upgrade para acessar.');
    }
}
