<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function getAllSchedules(): Collection
    {
        return Schedule::with(['schemes'])->get();
    }
}