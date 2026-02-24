<?php

declare(strict_types=1);

namespace Modules\PWA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\PWA\Http\Requests\RecordPwaInstallRequest;
use Modules\PWA\Models\PwaVersion;
use Modules\PWA\Services\PwaInstallService;

class PwaApiController extends Controller
{
    public function __construct(
        private PwaInstallService $installService
    ) {}

    public function version(): JsonResponse
    {
        $release = PwaVersion::latestRelease();

        if (! $release) {
            return response()->json([
                'version' => config('pwa.cache_version', 'v1'),
                'is_force_update' => false,
                'release_notes' => null,
                'released_at' => null,
            ]);
        }

        return response()->json([
            'version' => $release->version,
            'is_force_update' => $release->is_force_update,
            'release_notes' => $release->release_notes,
            'released_at' => $release->released_at?->toIso8601String(),
        ]);
    }

    public function installed(RecordPwaInstallRequest $request): JsonResponse
    {
        $this->installService->recordInstall($request->validated() + [
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['recorded' => true], 200);
    }

    public function ping(Request $request): JsonResponse
    {
        $fingerprint = $request->query('fingerprint');
        if (! is_string($fingerprint) || strlen($fingerprint) > 64) {
            return response()->json(['updated' => false], 400);
        }

        $updated = $this->installService->updateLastSeen($fingerprint);

        return response()->json(['updated' => $updated]);
    }
}
