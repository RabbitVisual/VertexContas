<?php

declare(strict_types=1);

namespace Modules\PWA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ServiceWorkerController extends Controller
{
    public function __invoke(): Response
    {
        $cacheVersion = config('pwa.cache_version', 'v1');
        $cacheName = 'vertex-pwa-'.$cacheVersion;

        $js = <<<JS
const CACHE_NAME = '{$cacheName}';

self.addEventListener('install', function(event) {
    self.skipWaiting();
});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(keys) {
            return Promise.all(
                keys.filter(function(key) {
                    return key.startsWith('vertex-pwa-') && key !== CACHE_NAME;
                }).map(function(key) {
                    return caches.delete(key);
                })
            );
        }).then(function() {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function(event) {
    var url = new URL(event.request.url);
    if (url.pathname === '/manifest.webmanifest' || url.pathname === '/sw.js' || url.pathname.endsWith('/sw.js')) {
        return;
    }
    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request).then(function(response) {
            var clone = response.clone();
            if (response.status === 200 && (event.request.url.startsWith(self.location.origin + '/build/') || event.request.url.startsWith(self.location.origin + '/assets/'))) {
                caches.open(CACHE_NAME).then(function(cache) {
                    cache.put(event.request, clone);
                });
            }
            return response;
        }).catch(function() {
            return caches.match(event.request).then(function(cached) {
                return cached || caches.match(self.location.origin + '/user');
            });
        })
    );
});
JS;

        return response($js, 200, [
            'Content-Type' => 'application/javascript',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'max-age=0, no-cache, no-store',
        ]);
    }
}
