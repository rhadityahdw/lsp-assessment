<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AttemptController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('unauthorized', function () {
    return Inertia::render('errors/Unauthorized');
})->name('unauthorized');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');

    Route::get('skema', function () {
        return Inertia::render('Scheme', [SchemeController::class, 'get']);
    })->name('skema');

    Route::get('pendaftaran', [AttemptController::class, 'create'])->name('pendaftaran');

    Route::middleware('verified')->group(function () {
        Route::middleware('role:admin')->group(function () {
            Route::get('dashboard', function () {
                return Inertia::render('Dashboard');
            })->name('dashboard');

            Route::resource('users', UserController::class);
            Route::resource('schemes', SchemeController::class);
            Route::resource('units', UnitController::class);
            Route::resource('assessments', AssessmentController::class);
        });
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
