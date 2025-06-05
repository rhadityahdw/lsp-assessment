<?php

namespace App\Services;

use App\Models\AsesiSchedule;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ScheduleService
{
    public function getAllSchedules(): Collection
    {
        return Schedule::with(['asesor', 'assessment', 'asesiSchedules'])
            ->orderBy('schedule_time', 'desc')
            ->get();
    }

    public function getScheduleById(int $id)
    {
        return Schedule::with(['asesor', 'assessment', 'asesis'])
            ->findOrFail($id);
    }

    public function createSchedule(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Validasi jumlah asesi
            if (count($data['asesis']) > 10) {
                throw new \Exception('Maksimal 10 asesi dapat dipilih dalam satu jadwal.');
            }

            $schedule = Schedule::create([
                'asesor_id' => $data['asesor_id'],
                'assessment_id' => $data['assessment_id'] ?? null,
                'schedule_time' => $data['schedule_time'],
                'location' => $data['location'] ?? null,
                'status' => 'scheduled',
            ]);

            // Attach asesi ke schedule
            $schedule->asesis()->attach($data['asesis']);

            return $schedule;
        });
    }

    public function updateSchedule(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $schedule = Schedule::findOrFail($id);
            
            // Validasi jumlah asesi
            if (count($data['asesis']) > 10) {
                throw new \Exception('Maksimal 10 asesi dapat dipilih dalam satu jadwal.');
            }

            $schedule->update([
                'asesor_id' => $data['asesor_id'],
                'assessment_id' => $data['assessment_id'] ?? null,
                'schedule_time' => $data['schedule_time'],
                'location' => $data['location'] ?? null,
                'status' => $data['status'] ?? $schedule->status,
            ]);

            // Sync asesi (hapus yang lama, tambah yang baru)
            $schedule->asesis()->sync($data['asesis']);
            
            return $schedule;
        });
    }

    public function deleteSchedule(int $id)
    {
        return DB::transaction(function () use ($id) {
            $schedule = Schedule::findOrFail($id);
            
            // ✅ FIXED: Gunakan relasi yang benar
            $schedule->asesis()->detach();
            
            $schedule->delete();
            
            return true;
        });
    }

    // Perbaiki juga method getSchedulesByAsesi
    public function getSchedulesByAsesi(int $asesiId): Collection
    {
        return Schedule::whereHas('asesis', function ($query) use ($asesiId) {
            $query->where('user_id', $asesiId); // Perbaiki field name
        })
        ->with(['asesor', 'assessment'])
        ->orderBy('schedule_time', 'asc')
        ->get();
    }

    public function getSchedulesByAsesor(int $asesorId): Collection
    {
        return Schedule::where('asesor_id', $asesorId)
            ->with(['asesor', 'assessment', 'asesiSchedules'])
            ->orderBy('schedule_time', 'asc')
            ->get();
    }

    public function getSchedulesForGrading(int $asesorId): Collection
    {
        return Schedule::with(['assessment', 'asesiSchedules.asesi.profile'])
            ->where('asesor_id', $asesorId)
            ->where('status', Schedule::STATUS_COMPLETED)
            ->latest('schedule_time')
            ->get();
    }

    public function getGradingProgress(Schedule $schedule): array
    {
        $asesiSchedules = $schedule->asesiSchedules;
        $total = $asesiSchedules->count();
        $graded = $asesiSchedules->where('status', '!=', AsesiSchedule::STATUS_PENDING)->count();
        
        return [
            'total' => $total,
            'graded' => $graded,
            'percentage' => $total > 0 ? round(($graded / $total) * 100, 2) : 0
        ];
    }

    // Tambahkan method untuk menandai schedule selesai
    public function markAsCompleted(int $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['status' => 'completed']);
        return $schedule;
    }
}