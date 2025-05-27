<?php

namespace App\Http\Resources;

use App\Models\PreAssessmentAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttemptResource extends JsonResource
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
            'scheme' => new SchemeResource($this->scheme),
            'user_id' => $this->user_id,
            'status' => $this->status,
            'document' => [
                'ktp' => $this->ktp ? url('storage/'.$this->ktp) : null,
                'ijazah' => $this->ijazah? url('storage/'.$this->ijazah) : null,
                'pas_foto' => $this->pas_foto? url('storage/'.$this->pas_foto) : null,
                'bukti_kerja' => $this->bukti_kerja? url('storage/'.$this->bukti_kerja) : null,
                'portofolio' => $this->portofolio? url('storage/'.$this->portofolio) : null,
            ],
            'pre_assessment_answers' => PreAssessmentAnswerResource::collection($this->whenLoaded('preAssessmentAnswer')),
            'created_at' => $this->created_at,
        ];
    }
}
