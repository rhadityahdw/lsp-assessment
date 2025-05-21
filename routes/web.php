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
    return Inertia::render('Pendaftaran');
})->name('pendaftaran');

Route::get('skema', function () {
    return Inertia::render('Scheme');
})->name('skema');

Route::get('profile', function () {
    return Inertia::render('UserProfile');
})->name('profile');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
