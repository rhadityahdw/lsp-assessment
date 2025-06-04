<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assessment' => [
                'id' => $this->assessment?->id,
                'name' => $this->assessment?->name,
                'type' => $this->assessment?->type,
            ],
            'asesor' => [
                'id' => $this->asesor?->id,
                'name' => $this->asesor?->name,
            ],
            'asesis' => $this->asesiSchedules->map(function ($asesiSchedule) {
                return [
                    'id' => $asesiSchedule->asesi?->id,
                    'name' => $asesiSchedule->asesi?->name,
                    'status' => $asesiSchedule->status,
                    'score' => $asesiSchedule->score,
                    'notes' => $asesiSchedule->notes,
                ];
            }),
            'schedule_time' => $this->schedule_time,
            'formatted_schedule_time' => $this->formatted_schedule_time,
            'location' => $this->location,
            'status' => $this->status,
            'asesi_count' => $this->asesiSchedules->count(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
