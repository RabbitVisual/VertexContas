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
        $cacheName = 'vertex-pwa-' . $cacheVersion;
        $offlinePath = config('pwa.offline_url', '/pwa/offline');

        $js = <<<JSSW
const CACHE_NAME = '{$cacheName}';
const OFFLINE_URL = self.location.origin + '{$offlinePath}';
const STATIC_PREFIXES = ['/build/', '/assets/'];
const API_PREFIX = '/api/';

function isStaticAsset(url) {
    return STATIC_PREFIXES.some(function(p) { return url.pathname.indexOf(p) === 0; });
}
function isApi(url) {
    return url.pathname.indexOf(API_PREFIX) === 0;
}
function isSwOrManifest(url) {
    return url.pathname === '/manifest.webmanifest' || url.pathname === '/sw.js' || url.pathname.endsWith('/sw.js');
}

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function(cache) {
            return cache.add(OFFLINE_URL);
        }).then(function() {
            return self.skipWaiting();
        })
    );
});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(keys) {
            return Promise.all(
                keys.filter(function(key) {
                    return key.startsWith('vertex-pwa-') && key !== CACHE_NAME;
                }).map(function(key) { return caches.delete(key); })
            );
        }).then(function() { return self.clients.claim(); })
    );
});

self.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

self.addEventListener('fetch', function(event) {
    var url = new URL(event.request.url);
    if (event.request.method !== 'GET') return;
    if (isSwOrManifest(url)) return;

    if (isApi(url)) {
        event.respondWith(fetch(event.request));
        return;
    }

    if (isStaticAsset(url)) {
        event.respondWith(
            caches.match(event.request).then(function(cached) {
                if (cached) return cached;
                return fetch(event.request).then(function(response) {
                    if (response.status === 200) {
                        var clone = response.clone();
                        caches.open(CACHE_NAME).then(function(cache) { cache.put(event.request, clone); });
                    }
                    return response;
                });
            })
        );
        return;
    }

    event.respondWith(
        fetch(event.request).then(function(response) {
            var clone = response.clone();
            if (response.status === 200 && response.headers.get('content-type') && response.headers.get('content-type').indexOf('text/html') !== -1) {
                caches.open(CACHE_NAME).then(function(cache) { cache.put(event.request, clone); });
            }
            return response;
        }).catch(function() {
            return caches.match(event.request).then(function(cached) {
                return cached || caches.match(OFFLINE_URL);
            });
        })
    );
});
JSSW;

        return response($js, 200, [
            'Content-Type' => 'application/javascript; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'max-age=0, no-cache, no-store',
        ]);
    }
}
