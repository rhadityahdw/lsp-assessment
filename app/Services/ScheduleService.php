<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ScheduleService
{
    public function getAllSchedules(): Collection
    {
        return Schedule::with('asesor')->get();
    }

    public function getScheduleById(int $id)
    {
        return Schedule::with('asesis')->findOrFail($id);
    }

    public function createSchedule(array $data)
    {
        return DB::transaction(function () use ($data) {
            $schedule = Schedule::create([
                'asesor_id' => $data['asesor_id'],
                'assessment_id' => $data['assessment_id'] ?? '',
                'schedule_time' => $data['schedule_time'],
                'location' => $data['location'],
                'status' => 'draft',
            ]);

            $schedule->asesis()->attach($data['asesi_ids']);

            return $schedule;
        });
    }
}