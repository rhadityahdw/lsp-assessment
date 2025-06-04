<?php

namespace App\Http\Controllers;

use App\Models\AsesiSchedule;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AssessmentGradingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:asesor');
        $this->middleware('permission:edit assessment');
    }

    /**
     * Tampilkan daftar schedule yang bisa dinilai
     */
    public function index()
    {
        $schedules = Schedule::with(['assessment.scheme', 'asesiSchedules.asesi'])
            ->where('asesor_id', Auth::id())
            ->where('status', Schedule::STATUS_COMPLETED)
            ->latest('schedule_time')
            ->paginate(10);

        return Inertia::render('assessment/Index', [
            'schedules' => $schedules
        ]);
    }

    /**
     * Tampilkan form penilaian untuk schedule tertentu
     */
    public function show(Schedule $schedule)
    {
        // Validasi akses
        if ($schedule->asesor_id !== Auth::id()) {
            abort(403, 'Anda tidak berwenang mengakses halaman ini.');
        }

        // Pastikan schedule sudah completed
        if (!$schedule->canBeGraded()) {
            return redirect()->route('assessment.grading.index')
                ->with('error', 'Asesmen belum selesai atau sudah dibatalkan.');
        }

        $asesiSchedules = $schedule->asesiSchedules()
            ->with('asesi.profile')
            ->get();
        
        foreach ($asesiSchedules as $asesiSchedule) {
            $asesiSchedule->id;
        }

        return Inertia::render('assessment/GradingForm', [
            'schedule' => $schedule->load(['assessment', 'asesor']),
            'asesiSchedules' => $asesiSchedules,
            'statusOptions' => AsesiSchedule::getStatusOptions()
        ]);
    }

    /**
     * Update hasil asesmen asesi
     */
    public function updateAsesiResult(Request $request, AsesiSchedule $asesiSchedule)
    {
        // Validasi akses
        if ($asesiSchedule->schedule->asesor_id !== Auth::id()) {
            abort(403, 'Anda tidak berwenang menilai asesmen ini.');
        }

        // Validasi bahwa schedule sudah completed
        if (!$asesiSchedule->schedule->canBeGraded()) {
            return back()->withErrors([
                'error' => 'Asesmen belum selesai. Tidak dapat memberikan penilaian.'
            ]);
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in([
                AsesiSchedule::STATUS_APPROVED,
                AsesiSchedule::STATUS_REJECTED
            ])]
        ]);

        $asesiSchedule->update($validated);

        return back()->with('success', 
            'Hasil asesmen untuk ' . $asesiSchedule->asesi->name . ' berhasil diperbarui.'
        );
    }

    /**
     * Update multiple asesi results at once
     */
    public function updateMultipleResults(Request $request, Schedule $schedule)
    {
        // Validasi akses
        if ($schedule->asesor_id !== Auth::id()) {
            abort(403, 'Anda tidak berwenang menilai asesmen ini.');
        }

        if (!$schedule->canBeGraded()) {
            return back()->withErrors([
                'error' => 'Asesmen belum selesai. Tidak dapat memberikan penilaian.'
            ]);
        }

        $validated = $request->validate([
            'results' => 'required|array',
            'results.*.id' => 'required|exists:asesi_schedule,id',
            'results.*.score' => 'required|numeric|min:0|max:100',
            'results.*.notes' => 'nullable|string|max:1000',
            // Tambahkan STATUS_PENDING ke validasi
            'results.*.status' => ['required', Rule::in([
                AsesiSchedule::STATUS_PENDING,
                AsesiSchedule::STATUS_APPROVED,
                AsesiSchedule::STATUS_REJECTED
            ])]
        ]);

        foreach ($validated['results'] as $result) {
            $asesiSchedule = AsesiSchedule::find($result['id']);
            
            // Double check ownership
            if ($asesiSchedule->schedule_id === $schedule->id) {
                $asesiSchedule->update([
                    'score' => $result['score'],
                    'notes' => $result['notes'],
                    'status' => $result['status']
                ]);
            }
        }

        return back()->with('success', 'Semua hasil asesmen berhasil diperbarui.');
    }
}