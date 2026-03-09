<?php

declare(strict_types=1);

return [
    'name' => 'PWA',

    'enabled' => env('PWA_ENABLED', true),
    'sw_registration' => env('PWA_SW_REGISTRATION', true),

    'theme_color' => env('PWA_THEME_COLOR', '#11C76F'),
    'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),
    'theme_color_dark' => env('PWA_THEME_COLOR_DARK', '#0f172a'),
    'background_color_dark' => env('PWA_BACKGROUND_COLOR_DARK', '#0f172a'),
    'display' => env('PWA_DISPLAY', 'standalone'),
    'scope' => env('PWA_SCOPE', '/'),
    'start_url' => env('PWA_START_URL', '/user'),
    'cache_version' => env('PWA_CACHE_VERSION', 'v1'),

    'manifest_url' => env('PWA_MANIFEST_URL', '/manifest.webmanifest'),
    'sw_url' => env('PWA_SW_URL', '/sw.js'),

    'app_name' => env('PWA_APP_NAME', env('APP_NAME', 'Vertex Contas')),
    'short_name' => env('PWA_SHORT_NAME', 'Vertex'),

    'icons' => [
        'sizes' => [72, 96, 128, 192, 384, 512],
        'png_path' => env('PWA_ICONS_PNG_PATH', ''),
        'maskable_png_path' => env('PWA_MASKABLE_ICON_PNG_PATH', ''),
    ],

    'offline_url' => env('PWA_OFFLINE_URL', '/pwa/offline'),
];
