<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asesor_id' => 'required|exists:users,id',
            'assessment_id' => 'nullable|exists:assessments,id',
            'schedule_time' => 'required|date',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|in:scheduled,completed,cancelled',
            'asesis' => 'required|array|min:1|max:10',
            'asesis.*' => 'required|numeric|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'asesor_id.required' => 'Asesor wajib dipilih.',
            'asesor_id.exists' => 'Asesor yang dipilih tidak valid.',
            'assessment_id.exists' => 'Asesmen yang dipilih tidak valid.',
            'schedule_time.required' => 'Tanggal pelaksanaan wajib diisi.',
            'schedule_time.date' => 'Format tanggal pelaksanaan tidak valid.',
            'location.string' => 'Lokasi pelaksanaan harus berupa teks.',
            'location.max' => 'Lokasi pelaksanaan maksimal 255 karakter.',
            'status.in' => 'Status harus salah satu dari: scheduled, completed, cancelled.',
            'asesis.required' => 'Minimal satu asesi harus dipilih.',
            'asesis.array' => 'Data asesi harus berupa array.',
            'asesis.min' => 'Minimal satu asesi harus dipilih.',
            'asesis.max' => 'Maksimal 10 asesi dapat dipilih.',
            'asesis.*.required' => 'ID asesi wajib diisi.',
            'asesis.*.numeric' => 'ID asesi harus berupa angka.',
            'asesis.*.exists' => 'Asesi yang dipilih tidak valid.',
        ];
    }
}