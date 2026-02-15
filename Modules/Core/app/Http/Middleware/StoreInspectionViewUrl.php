<?php

declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Durante inspeção ativa: armazena a URL atual que o agente está visualizando
 * para que o cliente possa acompanhar em tempo real via polling.
 */
class StoreInspectionViewUrl
{
    private const CACHE_PREFIX = 'inspection_view_';
    private const CACHE_TTL_SECONDS = 90;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $inspectionId = session('impersonate_inspection_id');
        if ($inspectionId && $request->isMethod('GET') && ! $request->ajax()) {
            Cache::put(self::CACHE_PREFIX . $inspectionId, $request->fullUrl(), self::CACHE_TTL_SECONDS);
        }

        return $response;
    }
}
