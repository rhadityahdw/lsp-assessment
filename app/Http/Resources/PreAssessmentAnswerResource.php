<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreAssessmentAnswerResource extends JsonResource
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
            'pre_assessment_id' => $this->pre_assessment_id,
            'unit' => $this->preAssessment->unit->name?? null,
            'question' => $this->preAssessment->question ?? null,
            'answer' => $this->answer,
        ];
    }
}
