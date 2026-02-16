<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Gamification\Models\CoachingRule;
use Modules\Gamification\Models\Medal;

class CoachingRuleAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = CoachingRule::with('medal')->orderByDesc('priority');

        if ($request->filled('condition_type')) {
            $query->where('condition_type', $request->input('condition_type'));
        }

        $rules = $query->paginate(15)->withQueryString();

        return view('paneladmin::gamification.rules.index', compact('rules'));
    }

    public function create()
    {
        $medals = Medal::where('is_active', true)->orderBy('title')->get();

        return view('paneladmin::gamification.rules.create', compact('medals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trigger_key' => 'required|string|max:80|unique:coaching_rules,trigger_key',
            'condition_type' => 'required|in:pillar_threshold,reserve_months,consecutive_days,savings_threshold,pro_subscription',
            'condition_params' => 'nullable|string',
            'medal_id' => 'nullable|exists:medals,id',
            'level' => 'required|in:info,success,warning,danger',
            'priority' => 'required|integer|min:0|max:999',
            'message_override' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['medal_id'] = $request->filled('medal_id') ? (int) $request->medal_id : null;
        $validated['condition_params'] = $this->parseConditionParams($request->input('condition_params'));
        $validated['is_active'] = $request->boolean('is_active');

        CoachingRule::create($validated);

        return redirect()->route('admin.gamification.rules.index')->with('success', 'Regra criada com sucesso!');
    }

    public function edit(CoachingRule $rule)
    {
        $medals = Medal::where('is_active', true)->orderBy('title')->get();

        return view('paneladmin::gamification.rules.edit', ['rule' => $rule, 'medals' => $medals]);
    }

    public function update(Request $request, CoachingRule $rule)
    {
        $validated = $request->validate([
            'trigger_key' => 'required|string|max:80|unique:coaching_rules,trigger_key,' . $rule->id,
            'condition_type' => 'required|in:pillar_threshold,reserve_months,consecutive_days,savings_threshold,pro_subscription',
            'condition_params' => 'nullable|string',
            'medal_id' => 'nullable|exists:medals,id',
            'level' => 'required|in:info,success,warning,danger',
            'priority' => 'required|integer|min:0|max:999',
            'message_override' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['medal_id'] = $request->filled('medal_id') ? (int) $request->medal_id : null;
        $validated['condition_params'] = $this->parseConditionParams($request->input('condition_params'));
        $validated['is_active'] = $request->boolean('is_active');

        $rule->update($validated);

        return redirect()->route('admin.gamification.rules.index')->with('success', 'Regra atualizada com sucesso!');
    }

    public function destroy(CoachingRule $rule)
    {
        $rule->delete();

        return redirect()->route('admin.gamification.rules.index')->with('success', 'Regra removida com sucesso!');
    }

    private function parseConditionParams(?string $input): array
    {
        if (empty(trim($input ?? ''))) {
            return [];
        }

        $decoded = json_decode($input, true);

        return is_array($decoded) ? $decoded : [];
    }
}
