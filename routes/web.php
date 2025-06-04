<?php

use App\Http\Controllers\AdminCertificateController;
use App\Http\Controllers\AsesiAssessmentController;
use App\Http\Controllers\AsesiCertificateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentGradingController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\DashboardController;
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

// Add this inside the asesi middleware group
Route::middleware(['auth', 'role:asesi'])->prefix('asesi')->name('asesi.')->group(function () {
    Route::get('certificates', [AsesiCertificateController::class, 'index'])->name('certificates.index');
    Route::get('certificates/{certificate}', [AsesiCertificateController::class, 'show'])->name('certificates.show');
    Route::get('certificates/{certificate}/download', [AsesiCertificateController::class, 'downloadFile'])->name('certificates.download');
    
    // Add these new routes
    Route::get('assessments', [AsesiAssessmentController::class, 'index'])->name('assessments.index');
    Route::get('assessments/{schedule}', [AsesiAssessmentController::class, 'show'])->name('assessments.show');
});
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');

    // HAPUS ROUTE INI - Duplikasi
    // Route::get('dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->middleware('permission:view dashboard')->name('dashboard');

    Route::get('skema', function () {
        $schemes = app(SchemeService::class)->getAllSchemes();
        return Inertia::render('Scheme', [
            'schemes' => $schemes
        ]);
    })->name('skema');

    Route::get('pendaftaran', [AttemptController::class, 'create'])->name('pendaftaran');
    Route::post('attempt', [AttemptController::class, 'store'])->name('attempt.store');
    Route::get('success', fn () => Inertia::render('pendaftaran/Success'))->name('success');

    // BIARKAN HANYA ROUTE INI
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:view dashboard')
        ->name('dashboard');
    
    // Add route for assessment grading
    Route::get('assessment-grading/{schedule}', [AssessmentGradingController::class, 'show'])
        ->name('assessment-grading.show');
    Route::put('assessment-grading/{schedule}', [AssessmentGradingController::class, 'update'])
        ->name('assessment-grading.update');

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

// Tambahkan dalam group asesor
Route::middleware(['auth', 'role:asesor'])->prefix('asesor')->name('asesor.')->group(function () {
    Route::get('assessments', [AsesiAssessmentController::class, 'index'])->name('assessments.index');
    Route::get('assessments/{schedule}', [AsesiAssessmentController::class, 'show'])->name('assessments.show');
});
// Remove duplicate routes and fix grouping
Route::middleware(['auth', 'role:asesor'])->prefix('asesor')->name('asesor.')->group(function () {
    // Assessment Grading Routes
    Route::get('grading', [AssessmentGradingController::class, 'index'])->name('grading.index');
    Route::get('grading/{schedule}', [AssessmentGradingController::class, 'show'])->name('grading.show');
    Route::patch('grading/{asesiSchedule}', [AssessmentGradingController::class, 'updateAsesiResult'])->name('grading.update');
    Route::patch('grading/{schedule}/multiple', [AssessmentGradingController::class, 'updateMultipleResults'])->name('grading.update-multiple');
});
Route::prefix('assessment/grading')->name('assessment.grading.')->group(function () {
    Route::get('/', [AssessmentGradingController::class, 'index'])->name('index');
    Route::get('/{schedule}', [AssessmentGradingController::class, 'show'])->name('show');
    Route::patch('/{asesiSchedule}', [AssessmentGradingController::class, 'updateAsesiResult'])->name('update');
    Route::patch('/{schedule}/multiple', [AssessmentGradingController::class, 'updateMultipleResults'])->name('update-multiple');
});
