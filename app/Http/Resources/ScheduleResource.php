<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'asesor' => new UserResource($this->asesor),
            'schedule_time' => $this->schedule_time,
            'location' => $this->location,
            'status' => $this->status,
            'asesis' => UserResource::collection($this->asesis),
        ];
    }
}
