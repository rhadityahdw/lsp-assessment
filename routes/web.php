<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AssessmentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', UserController::class);
Route::resource('schemes', SchemeController::class);
Route::resource('units', UnitController::class);
Route::resource('assessments', AssessmentController::class);

Route::get('pendaftaran', function () {
    $schemes = app(App\Services\SchemeService::class)->getAllSchemes();

    return Inertia::render('Pendaftaran', [
        'schemes' => $schemes,
    ]);
})->name('pendaftaran');

Route::get('skema', function () {
    $schemes = app(App\Services\SchemeService::class)->getAllSchemes();
    return Inertia::render('Scheme', [
        'schemes' => $schemes,
    ]);
})->name('skema');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
