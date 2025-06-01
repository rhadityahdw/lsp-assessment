<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $asesor = User::role('asesor')->first();
        $scheme = Scheme::first();

        if ($asesor && $scheme) {
            Schedule::create([
                'scheme_id' => $scheme->id,
                'asesor_id' => $asesor->id,
                'date' => now()->addDays(7),
                'time' => '09:00:00',
                'type' => 'online',
                'location' => 'Google Meet',
                'quota' => 10,
            ]);

            Schedule::create([
                'scheme_id' => $scheme->id,
                'asesor_id' => $asesor->id,
                'date' => now()->addDays(14),
                'time' => '13:00:00',
                'type' => 'offline',
                'location' => 'LSP Office',
                'quota' => 5,
            ]);
        }
    }
}