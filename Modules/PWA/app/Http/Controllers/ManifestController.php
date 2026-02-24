<?php

declare(strict_types=1);

namespace Modules\PWA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManifestController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $baseUrl = $request->getSchemeAndHttpHost();
        $scope = config('pwa.scope', '/');
        $startUrl = config('pwa.start_url', '/user');

        $iconUrl = branding_logo_url('user', false) ?: branding_favicon_url();
        if (empty($iconUrl)) {
            $iconUrl = asset('images/placeholder-vertex.svg');
        }
        $iconUrlAbsolute = str_starts_with($iconUrl, 'http') ? $iconUrl : url($iconUrl);

        $manifest = [
            'name' => config('pwa.app_name'),
            'short_name' => config('pwa.short_name'),
            'theme_color' => config('pwa.theme_color'),
            'background_color' => config('pwa.background_color'),
            'display' => config('pwa.display'),
            'scope' => $baseUrl.$scope,
            'start_url' => $baseUrl.$startUrl,
            'icons' => [
                [
                    'src' => $iconUrlAbsolute,
                    'sizes' => '192x192',
                    'type' => 'image/svg+xml',
                    'purpose' => 'any maskable',
                ],
                [
                    'src' => $iconUrlAbsolute,
                    'sizes' => '512x512',
                    'type' => 'image/svg+xml',
                    'purpose' => 'any maskable',
                ],
            ],
            'orientation' => 'portrait',
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}
