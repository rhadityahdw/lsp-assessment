<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('asesor');
    }

    public function rules(): array
    {
        return [
            'scheme_id' => ['required', 'exists:schemes,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(['tulis', 'praktel'])],
            'link' => ['required','string','max:255'],
        ];
    }

    public function messages()
    {
        return [
            'scheme_id.required' => 'Skema sertifikasi wajib dipilih.',
            'scheme_id.exists' => 'Skema sertifikasi yang dipilih tidak valid.',
            'name.required' => 'Nama sertifikasi wajib diisi.',
            'name.string' => 'Nama sertifikasi harus berupa teks.', 
            'name.max' => 'Nama sertifikasi tidak boleh lebih dari 255 karakter.',
            'type.required' => 'Tipe sertifikasi wajib dipilih.',
            'type.string' => 'Tipe sertifikasi harus berupa teks.',
            'type.in' => 'Tipe sertifikasi yang dipilih tidak valid.',
            'link.required' => 'Link sertifikasi wajib diisi.',
            'link.string' => 'Link sertifikasi harus berupa teks.',
            'link.max' => 'Link sertifikasi tidak boleh lebih dari 255 karakter.',
        ];
    }
}