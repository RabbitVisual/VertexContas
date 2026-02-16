<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Gamification\Models\Medal;

class MedalAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Medal::query()->orderBy('title');

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->input('difficulty'));
        }
        if ($request->filled('is_pro_only')) {
            $query->where('is_pro_only', $request->boolean('is_pro_only'));
        }

        $medals = $query->paginate(15)->withQueryString();

        return view('paneladmin::gamification.medals.index', compact('medals'));
    }

    public function create()
    {
        return view('paneladmin::gamification.medals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'explanation' => 'nullable|string|max:3000',
            'tips' => 'nullable|string|max:2000',
            'incentive_message' => 'nullable|string|max:1000',
            'icon_name' => 'required|string|max:50',
            'trigger_key' => 'required|string|max:80|unique:medals,trigger_key',
            'color' => 'required|string|max:20',
            'rarity' => 'required|in:bronze,silver,gold,platinum',
            'difficulty' => 'required|in:easy,medium,hard,advanced',
            'is_pro_only' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_pro_only'] = $request->boolean('is_pro_only');
        $validated['is_active'] = $request->boolean('is_active');

        Medal::create($validated);

        return redirect()->route('admin.gamification.medals.index')->with('success', 'Medalha criada com sucesso!');
    }

    public function edit(Medal $medal)
    {
        return view('paneladmin::gamification.medals.edit', compact('medal'));
    }

    public function update(Request $request, Medal $medal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'explanation' => 'nullable|string|max:3000',
            'tips' => 'nullable|string|max:2000',
            'incentive_message' => 'nullable|string|max:1000',
            'icon_name' => 'required|string|max:50',
            'trigger_key' => 'required|string|max:80|unique:medals,trigger_key,' . $medal->id,
            'color' => 'required|string|max:20',
            'rarity' => 'required|in:bronze,silver,gold,platinum',
            'difficulty' => 'required|in:easy,medium,hard,advanced',
            'is_pro_only' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_pro_only'] = $request->boolean('is_pro_only');
        $validated['is_active'] = $request->boolean('is_active');

        $medal->update($validated);

        return redirect()->route('admin.gamification.medals.index')->with('success', 'Medalha atualizada com sucesso!');
    }

    public function destroy(Medal $medal)
    {
        $medal->delete();

        return redirect()->route('admin.gamification.medals.index')->with('success', 'Medalha removida com sucesso!');
    }
}
