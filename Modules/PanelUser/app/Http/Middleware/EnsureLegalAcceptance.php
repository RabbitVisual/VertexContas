<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Models\LegalDocument;
use Modules\Core\Models\UserLegalAcceptance;
use Symfony\Component\HttpFoundation\Response;

class EnsureLegalAcceptance
{
    /**
     * Routes excluded from legal acceptance check.
     */
    private const EXCLUDED_ROUTES = [
        'paneluser.legal.acceptance',
        'paneluser.legal.store',
        'user.subscription.index',
        'user.subscription.cancel',
        'logout',
    ];

    /**
     * Ensure user has accepted all required legal documents (current versions).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, self::EXCLUDED_ROUTES, true)) {
            return $next($request);
        }

        $requiredDocs = LegalDocument::active()
            ->requiresAcceptance()
            ->get();

        foreach ($requiredDocs as $document) {
            $acceptance = UserLegalAcceptance::where('user_id', $request->user()->id)
                ->where('legal_document_id', $document->id)
                ->first();

            if (! $acceptance || $acceptance->version !== $document->version) {
                return redirect()->route('paneluser.legal.acceptance')
                    ->with('info', 'Atualizamos nossos Termos de Uso ou Política de Privacidade. Por favor, leia e aceite para continuar.');
            }
        }

        return $next($request);
    }
}
