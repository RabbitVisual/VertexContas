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

        $earnedCount = $userMedals->count();
        $totalCount = $allMedals->count();

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
                'unlocked' => $unlocked,
                'unlocked_at' => $um?->unlocked_at,
            ];
        });

        return view('paneluser::achievements.index', compact('medals', 'earnedCount', 'totalCount'));
    }
}
