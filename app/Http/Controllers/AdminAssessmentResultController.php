<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\AsesiSchedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminAssessmentResultController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
        $this->middleware('permission:view assessment');
    }

    /**
     * Tampilkan semua hasil asesmen
     */
    public function index(Request $request)
    {
        $query = Schedule::with([
            'assessment.scheme',
            'asesor',
            'asesiSchedules.asesi.profile'
        ])
        ->where('status', Schedule::STATUS_COMPLETED);

        // Filter berdasarkan parameter
        if ($request->filled('scheme_id')) {
            $query->whereHas('assessment', function($q) use ($request) {
                $q->where('scheme_id', $request->scheme_id);
            });
        }

        if ($request->filled('asesor_id')) {
            $query->where('asesor_id', $request->asesor_id);
        }

        if ($request->filled('status')) {
            $query->whereHas('asesiSchedules', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $schedules = $query->latest('schedule_time')->paginate(10);

        return Inertia::render('admin/AssessmentResults/Index', [
            'schedules' => $schedules,
            'filters' => $request->only(['scheme_id', 'asesor_id', 'status']),
            'statusOptions' => AsesiSchedule::getStatusOptions()
        ]);
    }

    /**
     * Tampilkan detail hasil asesmen untuk schedule tertentu
     */
    public function show(Schedule $schedule)
    {
        $schedule->load([
            'assessment.scheme.units',
            'asesor.profile',
            'asesiSchedules.asesi.profile'
        ]);

        return Inertia::render('admin/AssessmentResults/Show', [
            'schedule' => $schedule,
            'statusOptions' => AsesiSchedule::getStatusOptions()
        ]);
    }

    /**
     * Export hasil asesmen ke Excel/PDF
     */
    public function export(Request $request)
    {
        // Implementation for exporting assessment results
        // This can be added later based on requirements
    }
}