<?php

use Illuminate\Support\Facades\Route;
use Modules\Gateways\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public webhook routes (excluded from CSRF)
Route::post('/webhooks/stripe', [WebhookController::class, 'handleStripe'])->name('webhooks.stripe');
Route::match(['get', 'post'], '/webhooks/mercadopago', [WebhookController::class, 'handleMercadoPago'])->name('webhooks.mercadopago');

// Authenticated Checkout Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout/{gateway}', [\Modules\Gateways\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout.init');
});
