<?php

use App\Http\Controllers\AdminCertificateController;
use App\Http\Controllers\AsesiCertificateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\ScheduleController;
use App\Services\SchemeService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('unauthorized', function () {
    return Inertia::render('errors/Unauthorized');
})->name('unauthorized');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('certificates', AdminCertificateController::class);
    Route::get('certificates/{certificate}/download', [AdminCertificateController::class, 'downloadFile'])->name('certificates.download');
});

Route::middleware(['auth', 'role:asesi'])->name('asesi.')->group(function () {
    Route::get('certificates', [AsesiCertificateController::class, 'index'])->name('certificates.index');
    Route::get('certificates/{certificate}', [AsesiCertificateController::class, 'show'])->name('certificates.show');
    Route::get('certificates/{certificate}/download', [AsesiCertificateController::class, 'downloadFile'])->name('certificates.download');
});
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');

    // Move dashboard route here and protect it with permission middleware
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware('permission:view dashboard')->name('dashboard');

    Route::get('skema', function () {
        $schemes = app(SchemeService::class)->getAllSchemes();
        return Inertia::render('Scheme', [
            'schemes' => $schemes
        ]);
    })->name('skema');

    Route::get('pendaftaran', [AttemptController::class, 'create'])->name('pendaftaran');
    Route::post('attempt', [AttemptController::class, 'store'])->name('attempt.store');
    Route::get('success', fn () => Inertia::render('pendaftaran/Success'))->name('success');


    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('schemes', SchemeController::class);
    Route::resource('units', UnitController::class);
    Route::resource('assessments', AssessmentController::class);
    Route::resource('schedules', ScheduleController::class);

    Route::get('attempts', [AttemptController::class, 'index'])->name('attempts.index');
    Route::get('attempts/{id}', [AttemptController::class, 'show'])->name('attempts.show');
    Route::post('attempts/{id}/verify', [AttemptController::class, 'verify'])->name('attempts.verify');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
