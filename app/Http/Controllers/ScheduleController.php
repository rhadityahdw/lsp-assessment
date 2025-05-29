<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Scheme;
use App\Models\User;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService
    ) {}

    public function index()
    {
        $schedules = $this->scheduleService->getAllSchedules();

        return Inertia::render('schedules/Index', [
            'schedules' => ScheduleResource::collection($schedules)->resolve(),
        ]);
    }

    public function create()
    {
        $schemes = Scheme::all();
        $asesors = User::role('asesor')->select('id', 'name')->get();
        $asesis = User::role('asesi')->select('id', 'name')->get();    

        return Inertia::render('schedules/Create', [
            'schemes' => $schemes,
            'asesors' => $asesors,
            'asesis' => $asesis,
        ]);
    }

    public function store(StoreScheduleRequest $request)
    {
        $schedule = $this->scheduleService->createSchedule($request->validated());

        return redirect()->json([
            'message' => 'Jadwal berhasil ditambahkan',
            'schedule' => new ScheduleResource($schedule),
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('schedules/Edit');
    }

    public function show($id)
    {
        return Inertia::render('schedules/Show');
    }

    public function destroy($id)
    {
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully');
    }
}
