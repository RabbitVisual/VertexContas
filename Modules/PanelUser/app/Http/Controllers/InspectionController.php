<?php

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\Inspection;
use Illuminate\Support\Facades\Auth;

class InspectionController extends Controller
{
    private const CACHE_PREFIX = 'inspection_view_';

    /**
     * Accept an inspection request.
     */
    public function accept(Request $request, Inspection $inspection)
    {
        // Security check
        if ($inspection->user_id !== Auth::id() || $inspection->status !== 'pending') {
            abort(403, 'Solicitação inválida ou já processada.');
        }

        $inspection->update([
            'status' => 'active',
            'show_financial_data' => $request->boolean('show_financial_data'),
            'started_at' => now(),
        ]);

        // Security: Store inspection ID in session
        // This will be used by the banner and to enforce "Read/Fix Only" mode
        session(['impersonate_inspection_id' => $inspection->id]);

        return response()->json([
            'success' => true,
            'message' => 'Inspeção autorizada com sucesso. O agente agora pode visualizar seu painel.'
        ]);
    }

    /**
     * Reject an inspection request.
     */
    public function reject(Request $request, Inspection $inspection)
    {
        if ($inspection->user_id !== Auth::id() || $inspection->status !== 'pending') {
            abort(403);
        }

        $inspection->update(['status' => 'rejected']);

        return response()->json(['success' => true]);
    }

    /**
     * Poll endpoint for real-time sync: returns the URL the agent is currently viewing.
     * Used so the client can follow the same screen without manual refresh.
     */
    public function syncUrl(Request $request)
    {
        $inspection = Inspection::where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $inspection) {
            return response()->json(['url' => null, 'active' => false]);
        }

        $url = Cache::get(self::CACHE_PREFIX . $inspection->id);

        return response()->json([
            'url' => $url,
            'active' => true,
        ]);
    }
}
