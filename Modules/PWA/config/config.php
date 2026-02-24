<?php

declare(strict_types=1);

return [
    'name' => 'PWA',

    'enabled' => env('PWA_ENABLED', true),
    'sw_registration' => env('PWA_SW_REGISTRATION', true),

    'theme_color' => env('PWA_THEME_COLOR', '#11C76F'),
    'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),
    'display' => env('PWA_DISPLAY', 'standalone'),
    'scope' => env('PWA_SCOPE', '/'),
    'start_url' => env('PWA_START_URL', '/user'),
    'cache_version' => env('PWA_CACHE_VERSION', 'v1'),

    'manifest_url' => env('PWA_MANIFEST_URL', '/manifest.webmanifest'),
    'sw_url' => env('PWA_SW_URL', '/sw.js'),

    'app_name' => env('PWA_APP_NAME', env('APP_NAME', 'Vertex Contas')),
    'short_name' => env('PWA_SHORT_NAME', 'Vertex'),
];
