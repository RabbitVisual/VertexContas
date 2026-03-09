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
        $baseUrl = rtrim($request->getSchemeAndHttpHost(), '/');
        $scope = config('pwa.scope', '/');
        $startUrl = config('pwa.start_url', '/user');

        $iconUrl = branding_logo_url('user', false) ?: branding_favicon_url();
        if (empty($iconUrl)) {
            $iconUrl = asset('images/placeholder-vertex.svg');
        }
        $iconUrlAbsolute = str_starts_with($iconUrl, 'http') ? $iconUrl : url($iconUrl);

        $icons = $this->buildIcons($baseUrl, $iconUrlAbsolute);
        $shortcutIconUrl = str_starts_with((string) branding_favicon_url(), 'http') ? branding_favicon_url() : url(branding_favicon_url());
        $shortcuts = $this->buildShortcuts($baseUrl, $shortcutIconUrl);

        $manifest = [
            'name' => config('pwa.app_name'),
            'short_name' => config('pwa.short_name'),
            'theme_color' => config('pwa.theme_color'),
            'background_color' => config('pwa.background_color'),
            'display' => config('pwa.display'),
            'display_override' => ['window-controls-overlay', 'standalone'],
            'scope' => $baseUrl . $scope,
            'start_url' => $baseUrl . $startUrl,
            'icons' => $icons,
            'shortcuts' => $shortcuts,
            'categories' => ['finance'],
            'orientation' => 'portrait',
            'prefer_related_applications' => false,
        ];

        $response = response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Cache-Control', 'public, max-age=3600');

        return $response;
    }

    /**
     * Build manifest icons: SVG for "any" at multiple sizes; optional PNG for maskable.
     */
    private function buildIcons(string $baseUrl, string $svgUrl): array
    {
        $sizes = config('pwa.icons.sizes', [72, 96, 128, 192, 384, 512]);
        $icons = [];

        foreach ($sizes as $size) {
            $icons[] = [
                'src' => $svgUrl,
                'sizes' => "{$size}x{$size}",
                'type' => 'image/svg+xml',
                'purpose' => 'any',
            ];
        }

        $pngPath = config('pwa.icons.png_path');
        $maskablePath = config('pwa.icons.maskable_png_path');

        if ($pngPath !== '' && $pngPath !== null) {
            $path = ltrim($pngPath, '/');
            foreach ($sizes as $size) {
                $icons[] = [
                    'src' => $baseUrl . '/' . $path . "/{$size}x{$size}.png",
                    'sizes' => "{$size}x{$size}",
                    'type' => 'image/png',
                    'purpose' => 'any',
                ];
            }
        }

        if ($maskablePath !== '' && $maskablePath !== null) {
            $path = ltrim($maskablePath, '/');
            foreach ([192, 512] as $size) {
                $icons[] = [
                    'src' => $baseUrl . '/' . $path . "/{$size}x{$size}.png",
                    'sizes' => "{$size}x{$size}",
                    'type' => 'image/png',
                    'purpose' => 'maskable',
                ];
            }
        } else {
            foreach ([192, 512] as $size) {
                $icons[] = [
                    'src' => $svgUrl,
                    'sizes' => "{$size}x{$size}",
                    'type' => 'image/svg+xml',
                    'purpose' => 'maskable',
                ];
            }
        }

        return $icons;
    }

    /**
     * Build app shortcuts for installed PWA.
     */
    private function buildShortcuts(string $baseUrl, string $iconUrl): array
    {
        return [
            [
                'name' => 'Dashboard',
                'short_name' => 'Início',
                'description' => 'Abrir painel principal',
                'url' => $baseUrl . config('pwa.start_url', '/user'),
                'icons' => [['src' => $iconUrl, 'sizes' => '96x96', 'type' => 'image/svg+xml', 'purpose' => 'any']],
            ],
            [
                'name' => 'Nova transação',
                'short_name' => 'Nova transação',
                'description' => 'Registrar receita ou despesa',
                'url' => $baseUrl . '/transactions/create',
                'icons' => [['src' => $iconUrl, 'sizes' => '96x96', 'type' => 'image/svg+xml', 'purpose' => 'any']],
            ],
        ];
    }
}
