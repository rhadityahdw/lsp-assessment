<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\AsesiSchedule;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $assessments = Assessment::all();
        $asesors = User::role('asesor')->get();
        $asesis = User::role('asesi')->get();

        foreach ($assessments->take(5) as $assessment) {
            $schedule = Schedule::create([
                'assessment_id' => $assessment->id,
                'asesor_id' => $asesors->random()->id,
                'schedule_time' => now()->addDays(rand(1, 30)),
                'location' => 'Ruang Asesmen ' . rand(1, 5),
                'status' => collect([Schedule::STATUS_SCHEDULED, Schedule::STATUS_COMPLETED])->random()
            ]);

            // Tambahkan asesi ke schedule
            $selectedAsesis = $asesis->random(rand(2, 4));
            
            foreach ($selectedAsesis as $asesi) {
                $asesiSchedule = AsesiSchedule::create([
                    'asesi_id' => $asesi->id,
                    'schedule_id' => $schedule->id,
                    'score' => $schedule->isCompleted() ? rand(60, 95) : null,
                    'notes' => $schedule->isCompleted() ? 'Catatan penilaian untuk ' . $asesi->name : null,
                    'status' => $schedule->isCompleted() 
                        ? collect([AsesiSchedule::STATUS_APPROVED, AsesiSchedule::STATUS_REJECTED])->random()
                        : AsesiSchedule::STATUS_PENDING
                ]);
                
                // Optional: Log the created ID for debugging
                // \Log::info('Created AsesiSchedule with ID: ' . $asesiSchedule->id);
            }
        }
    }
}