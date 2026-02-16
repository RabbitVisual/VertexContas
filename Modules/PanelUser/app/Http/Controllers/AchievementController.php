<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Gamification\Models\Medal;
use Modules\Gamification\Models\UserMedal;

class AchievementController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isPro = $user->isPro();

        $allMedals = Medal::where('is_active', true)->orderBy('title')->get();

        $userMedals = UserMedal::where('user_id', $user->id)
            ->get()
            ->keyBy('medal_id');

        $medals = $allMedals->map(function (Medal $m) use ($userMedals, $isPro) {
            $um = $userMedals->get($m->id);
            $isPlatinum = ($m->rarity ?? $m->color) === 'platinum';
            $unlocked = $um !== null && ($isPlatinum ? $isPro : true);

            return [
                'id' => $m->id,
                'title' => $m->title,
                'description' => $m->description,
                'icon_name' => $m->icon_name ?? 'medal',
                'trigger_key' => $m->trigger_key,
                'color' => $m->color,
                'rarity' => $m->rarity ?? 'silver',
                'difficulty' => $m->difficulty ?? 'medium',
                'unlocked' => $unlocked,
                'unlocked_at' => $um?->unlocked_at,
                'is_pro_only' => (bool) $m->is_pro_only,
            ];
        });

        if (! $isPro) {
            $medals = $medals->filter(fn (array $m) => $m['unlocked'] && ! $m['is_pro_only']);
        }

        $earnedCount = $medals->count();
        $totalCount = $isPro ? $allMedals->count() : $medals->count();

        return view('paneluser::achievements.index', compact('medals', 'earnedCount', 'totalCount'));
    }

    public function show(Medal $medal)
    {
        if (! $medal->is_active) {
            abort(404);
        }

        $user = auth()->user();
        $isPro = $user->isPro();
        $userMedal = UserMedal::where('user_id', $user->id)->where('medal_id', $medal->id)->first();
        $isPlatinum = ($medal->rarity ?? $medal->color) === 'platinum';
        $unlocked = $userMedal !== null && ($isPlatinum ? $isPro : true);

        if (! $unlocked) {
            return redirect()->route('user.achievements.index')
                ->with('error', 'Desbloqueie esta medalha antes de visualizar os detalhes.');
        }

        $shareUrl = route('user.achievements.show', $medal);
        $shareText = sprintf('Conquistei a medalha "%s" no Vertex Contas! %s', $medal->title, $shareUrl);

        return view('paneluser::achievements.show', compact('medal', 'userMedal', 'unlocked', 'shareUrl', 'shareText'));
    }
}
