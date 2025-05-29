<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'asesor_id' => 'required|exists:users,id',
            'assessment_id' =>'nullable|exists:assessments,id',
            'schedule_time' => 'required|date',
            'location' => 'nullable|string',
            'asesi_ids' => 'required|array|min:1',
            'asesi_ids.*' => 'required|exists:users,id',
        ];
    }
}
