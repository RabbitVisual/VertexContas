<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VertexBotController extends Controller
{
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
