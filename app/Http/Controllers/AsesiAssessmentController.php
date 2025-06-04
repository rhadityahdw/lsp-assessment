<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AsesiAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:asesi']);
        $this->middleware('permission:view assessment')->only(['index', 'show']);
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get schedules where the current user is an asesi
        $schedules = Schedule::with(['assessment', 'asesor'])
            ->whereHas('asesis', function($query) use ($user) {
                $query->where('asesi_id', $user->id);
            })
            ->orderBy('schedule_time', 'asc')
            ->get();

        return Inertia::render('asesi/AssessmentIndex', [
            'schedules' => $schedules
        ]);
    }

    public function show($scheduleId)
    {
        $user = Auth::user();

        $schedule = Schedule::with(['assessment', 'asesor', 'asesis'])
            ->whereHas('asesis', function($query) use ($user) {
                $query->where('asesi_id', $user->id);
            })
            ->findOrFail($scheduleId);

        $canAccessAssessment = now() >= $schedule->schedule_time;

        return Inertia::render('asesi/AssessmentShow', [
            'schedule' => $schedule,
            'canAccessAssessment' => $canAccessAssessment
        ]);
    }
}