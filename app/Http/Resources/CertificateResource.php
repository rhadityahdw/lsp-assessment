<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
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
            'user_id' => $this->user_id,
            'scheme_id' => $this->scheme_id,
            'certificate_number' => $this->certificate_number,
            'issued_at' => $this->issued_at ? $this->issued_at->format('Y-m-d') : null,
            'expiry_date' => $this->expiry_date ? $this->expiry_date->format('Y-m-d') : null,
            'file_path' => $this->file_path,
            'file_url' => $this->file_url,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            'user' => new UserResource($this->whenLoaded('user')),
            'scheme' => new SchemeResource($this->whenLoaded('scheme')),
        ];
    }
}
