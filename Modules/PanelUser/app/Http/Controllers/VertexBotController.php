<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VertexBotController extends Controller
{
    /**
     * Página dedicada com a última dica/análise do Mentor Vertex (evita redirecionar para o dashboard).
     */
    public function showAnalysis(Request $request): View|RedirectResponse
    {
        $insight = session('vertex_bot_last_insight');
        if (! $insight || empty($insight['content'] ?? null)) {
            return redirect()->route('paneluser.index')->with('info', 'Nenhuma análise recente. Navegue pelo painel para receber dicas do Mentor Vertex.');
        }

        $financialScore = session('vertex_bot_financial_score', 0);

        return view('paneluser::mentor.analysis', [
            'title' => 'Análise do Mentor Vertex',
            'insight' => $insight,
            'financialScore' => $financialScore,
        ]);
    }

    /**
     * Dismiss an insight (store in session so it doesn't show again this session).
     */
    public function dismissInsight(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'insight_key' => ['required', 'string', 'max:100'],
        ]);

        $key = $validated['insight_key'];
        $dismissed = session('vertex_bot_dismissed', []);
        $dismissed[] = $key;
        session(['vertex_bot_dismissed' => array_slice(array_unique($dismissed), -30)]);

        return response()->json(['success' => true]);
    }
}
