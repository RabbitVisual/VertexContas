<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Services\TourService;
use Modules\Gamification\Models\Achievement;

class TourController extends Controller
{
    public function __construct(
        protected TourService $tourService
    ) {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Record tour completion for analytics and gamification.
     */
    public function complete(Request $request): JsonResponse
    {
        $request->validate(['tour_id' => 'required|string|max:64']);

        $user = Auth::user();
        $tourId = $request->input('tour_id');

        $this->tourService->recordCompletion($user, $tourId);

        $achievementKey = 'tour_completed_' . $tourId;
        if (! Achievement::hasAchieved($user, $achievementKey, null)) {
            Achievement::create([
                'user_id' => $user->id,
                'achievement_key' => $achievementKey,
                'triggered_at' => now(),
                'metadata' => ['tour_id' => $tourId],
            ]);
        }

        return response()->json(['success' => true, 'tour_id' => $tourId]);
    }
}
