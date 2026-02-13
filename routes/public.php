<?php

use Illuminate\Support\Facades\Route;
use Modules\HomePage\Http\Controllers\HomePageController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| This file handles all public routes of the application.
| It maps to the HomePage module.
|
*/

// Public / HomePage
use Nwidart\Modules\Facades\Module;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('homepages', HomePageController::class)->names('homepage');
});

// Add other public routes here
Route::get('/', function () {
    return view('homepage::homepage');
})->name('homepage');

Route::get('/termos', function () {
    return view('homepage::legal.terms');
})->name('terms');

Route::get('/privacidade', function () {
    return view('homepage::legal.privacy');
})->name('privacy');

// Help Center
Route::view('/ajuda', 'homepage::help-center')->name('help-center');

// FAQ
Route::view('/duvidas', 'homepage::faq')->name('faq');

// Features
Route::view('/funcionalidades', 'homepage::features.index')->name('features.index');
Route::view('/metas-e-objetivos', 'homepage::features.goals')->name('features.goals');
Route::view('/relatorios', 'homepage::features.reports')->name('features.reports');

// Maintenance Mode
Route::view('/manutencao', 'homepage::maintenance')->name('maintenance');
