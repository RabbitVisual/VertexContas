<?php

use Illuminate\Support\Facades\Route;
use Nwidart\Modules\Facades\Module;

/*
|--------------------------------------------------------------------------
| Web Routes - Main Router
|--------------------------------------------------------------------------
|
| This file centralizes and manages all application routes.
|
*/

// =========================================================================
// Section 1: Core & Auth
// =========================================================================

// Authentication Routes (Login, Register, Password Reset, etc.)
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

// Authenticated General Routes
Route::middleware(['auth'])->group(function () {});

// =========================================================================
// Section 2: Fixed Module Mappings
// =========================================================================

// Public / HomePage
if (Module::find('HomePage')?->isEnabled()) {
    require __DIR__.'/public.php';
}

// Admin Panel
if (Module::find('PanelAdmin')?->isEnabled()) {
    require __DIR__.'/admin.php';
}

// Support Panel
if (Module::find('PanelSuporte')?->isEnabled()) {
    require __DIR__.'/suporte.php';
}

// User Panel
if (Module::find('PanelUser')?->isEnabled()) {
    require __DIR__.'/user.php';
}

// =========================================================================
// Section 3: Dynamic Module Discovery
// =========================================================================

/**
 * Loads routes from modules that are not explicitly handled above.
 */
$explicitModules = ['HomePage', 'PanelAdmin', 'PanelSuporte', 'PanelUser'];

foreach (Module::allEnabled() as $module) {
    if (in_array($module->getName(), $explicitModules)) {
        continue;
    }

    $moduleRoutes = module_path($module->getName(), '/routes/web.php');
    if (file_exists($moduleRoutes)) {
        require $moduleRoutes;
    }
}
