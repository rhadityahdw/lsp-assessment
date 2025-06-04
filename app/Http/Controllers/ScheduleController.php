<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Assessment;
use App\Models\User;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService
    ) {
        $this->middleware('permission:view schedule')->only(['index', 'show']);
        $this->middleware('permission:create schedule')->only(['create', 'store']);
        $this->middleware('permission:edit schedule')->only(['edit', 'update']);
        $this->middleware('permission:delete schedule')->only(['destroy']);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('asesor')) {
            $schedules = $this->scheduleService->getSchedulesByAsesorId($user->id);
        } elseif ($user->hasRole('admin')) {
            $schedules = $this->scheduleService->getAllSchedules();
        }

        return Inertia::render('schedules/Index', [
            'schedules' => ScheduleResource::collection($schedules)->resolve(),
            'permissions' => [
                'create' => $user->can('create schedule'),
                'edit' => $user->can('edit schedule'),
                'delete' => $user->can('delete schedule'),
                'view' => $user->can('view schedule'),
            ]
        ]);
    }

    public function create()
    {
        $assessments = Assessment::select('id', 'name')->get();
        $asesors = User::role('asesor')->select('id', 'name')->get();
        $asesis = User::role('asesi')->select('id', 'name')->get();

        return Inertia::render('schedules/Create', [
            'assessments' => $assessments,
            'asesors' => $asesors,
            'asesis' => $asesis,
        ]);
    }

    public function store(StoreScheduleRequest $request)
    {
        try {
            $this->scheduleService->createSchedule($request->validated());

            return redirect()->route('schedules.index')
                ->with('success', 'Jadwal berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $schedule = $this->scheduleService->getScheduleById($id);
            
            return Inertia::render('schedules/Show', [
                'schedule' => new ScheduleResource($schedule),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('schedules.index')
                ->with('error', 'Jadwal tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            $schedule = $this->scheduleService->getScheduleById($id);
            $assessments = Assessment::select('id', 'name')->get();
            $asesors = User::role('asesor')->select('id', 'name')->get();
            $asesis = User::role('asesi')->select('id', 'name')->get();

            return Inertia::render('schedules/Edit', [
                'schedule' => new ScheduleResource($schedule),
                'assessments' => $assessments,
                'asesors' => $asesors,
                'asesis' => $asesis,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('schedules.index')
                ->with('error', 'Jadwal tidak ditemukan');
        }
    }

    public function update(UpdateScheduleRequest $request, $id)
    {
        try {
            $this->scheduleService->updateSchedule($id, $request->validated());
            
            return redirect()->route('schedules.index')
                ->with('success', 'Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->scheduleService->deleteSchedule($id);
            
            return redirect()->route('schedules.index')
                ->with('success', 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
