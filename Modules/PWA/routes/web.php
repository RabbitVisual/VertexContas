<?php

use Illuminate\Support\Facades\Route;
use Modules\PWA\Http\Controllers\ManifestController;
use Modules\PWA\Http\Controllers\PwaApiController;
use Modules\PWA\Http\Controllers\PWAController;
use Modules\PWA\Http\Controllers\ServiceWorkerController;

// Public: Web App Manifest
Route::get('/manifest.webmanifest', [ManifestController::class, 'show'])->name('pwa.manifest');

// Public: Service Worker
Route::get('/sw.js', ServiceWorkerController::class)->name('pwa.sw');

// API: version (public), install + ping (throttled)
Route::prefix('api')->middleware(['throttle:60,1'])->group(function () {
    Route::get('/pwa/version', [PwaApiController::class, 'version'])->name('pwa.api.version');
    Route::post('/pwa/installed', [PwaApiController::class, 'installed'])->middleware('throttle:10,1')->name('pwa.api.installed');
    Route::get('/pwa/ping', [PwaApiController::class, 'ping'])->name('pwa.api.ping');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pwas', PWAController::class)->names('pwa');
});
