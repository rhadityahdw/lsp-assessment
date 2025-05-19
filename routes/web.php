<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', UserController::class);
Route::resource('schemes', SchemeController::class);
Route::resource('units', UnitController::class);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
