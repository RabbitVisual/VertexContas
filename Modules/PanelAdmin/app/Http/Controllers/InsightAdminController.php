<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Gamification\Models\Insight;

class InsightAdminController extends Controller
{
    /**
     * List insights with optional filter.
     */
    public function index(Request $request)
    {
        $query = Insight::query()->orderBy('trigger_event')->orderBy('created_at', 'desc');

        if ($request->filled('trigger_event')) {
            $query->where('trigger_event', $request->input('trigger_event'));
        }

        $insights = $query->paginate(15)->withQueryString();

        return view('paneladmin::insights.index', compact('insights'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('paneladmin::insights.create');
    }

    /**
     * Store new insight.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trigger_event' => 'required|in:low_balance,budget_reached,savings_milestone,daily_tip',
            'content' => 'required|string|max:2000',
            'level' => 'required|in:info,success,warning,danger',
            'is_active' => 'boolean',
            'is_pro_only' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_pro_only'] = $request->boolean('is_pro_only');

        Insight::create($validated);

        return redirect()->route('admin.insights.index')->with('success', 'Insight criado com sucesso!');
    }

    /**
     * Show edit form.
     */
    public function edit(Insight $insight)
    {
        return view('paneladmin::insights.edit', compact('insight'));
    }

    /**
     * Update insight.
     */
    public function update(Request $request, Insight $insight)
    {
        $validated = $request->validate([
            'trigger_event' => 'required|in:low_balance,budget_reached,savings_milestone,daily_tip',
            'content' => 'required|string|max:2000',
            'level' => 'required|in:info,success,warning,danger',
            'is_active' => 'boolean',
            'is_pro_only' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_pro_only'] = $request->boolean('is_pro_only');

        $insight->update($validated);

        return redirect()->route('admin.insights.index')->with('success', 'Insight atualizado com sucesso!');
    }

    /**
     * Delete insight.
     */
    public function destroy(Insight $insight)
    {
        $insight->delete();

        return redirect()->route('admin.insights.index')->with('success', 'Insight removido com sucesso!');
    }
}
