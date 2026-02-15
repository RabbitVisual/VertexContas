<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Services\InspectionGuard;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloqueia alterações de dados durante inspeção remota.
 * Modo inspeção = SOMENTE LEITURA. O agente pode visualizar, mas não criar, editar ou excluir.
 */
class BlockSensitiveInspectionActions
{
    /**
     * Rotas que PODEM receber POST/PUT/PATCH durante inspeção (exceções necessárias).
     */
    private const INSPECTION_ALLOWED_MUTATIONS = [
        'user.inspection.accept',
        'user.inspection.reject',
        'support.inspection.stop',
    ];

    /**
     * Rotas de exportação: bloqueadas quando usuário negou exibir dados financeiros.
     */
    private const FINANCIAL_EXPORT_ROUTES = [
        'core.reports.cashflow.view',
        'core.reports.export.cashflow.csv',
        'core.reports.export.categories.csv',
        'core.reports.categories.view',
        'core.reports.extrato.view',
        'core.reports.export.extrato.csv',
        'core.reports.export.extrato.xlsx',
        'core.reports.export.cashflow.csv',
        'core.reports.export.cashflow.xlsx',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('impersonate_inspection_id')) {
            return $next($request);
        }

        // Bloquear TODAS as mutações (POST, PUT, PATCH, DELETE) exceto as permitidas
        $isMutation = in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true);

        if ($isMutation && ! $request->routeIs(self::INSPECTION_ALLOWED_MUTATIONS)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Modo inspeção: somente leitura. Não é possível criar, editar ou excluir dados.',
                ], 403);
            }

            return back()->with('error', 'Modo inspeção ativo: somente visualização. Alterações não são permitidas.');
        }

        // Exportações: bloquear se usuário negou dados financeiros
        if (InspectionGuard::shouldHideFinancialData() && $request->routeIs(self::FINANCIAL_EXPORT_ROUTES)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Exportação bloqueada: dados financeiros não autorizados para esta sessão.',
                ], 403);
            }

            return back()->with('error', 'Exportação bloqueada durante a inspeção por privacidade.');
        }

        return $next($request);
    }
}
