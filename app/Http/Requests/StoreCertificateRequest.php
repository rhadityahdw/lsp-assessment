<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCertificateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'scheme_id' => ['required', 'exists:schemes,id'],
            'certificate_number' => ['required', 'string', 'max:255', 'unique:certificates,certificate_number'],
            'issued_at' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after:issued_at'],
            'file_path' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Penerima sertifikat wajib dipilih.',
            'user_id.exists' => 'Penerima sertifikat yang dipilih tidak valid.',
            'scheme_id.required' => 'Skema sertifikasi wajib dipilih.',
            'scheme_id.exists' => 'Skema sertifikasi yang dipilih tidak valid.',
            'certificate_number.required' => 'Nomor sertifikat wajib diisi.',
            'certificate_number.string' => 'Nomor sertifikat harus berupa teks.',
            'certificate_number.max' => 'Nomor sertifikat tidak boleh lebih dari 255 karakter.',
            'certificate_number.unique' => 'Nomor sertifikat ini sudah terdaftar.',
            'issued_at.required' => 'Tanggal terbit wajib diisi.',
            'issued_at.date' => 'Tanggal terbit harus berupa format tanggal yang valid.',
            'expiry_date.required' => 'Tanggal kedaluwarsa wajib diisi.',
            'expiry_date.date' => 'Tanggal kedaluwarsa harus berupa format tanggal yang valid.',
            'expiry_date.after' => 'Tanggal kedaluwarsa harus setelah tanggal terbit.',
            'file_path.required' => 'File sertifikat wajib diunggah.',
            'file_path.file' => 'File sertifikat harus berupa berkas.',
            'file_path.mimes' => 'Format file sertifikat harus PDF, JPG, atau PNG.',
            'file_path.max' => 'Ukuran file sertifikat tidak boleh melebihi 2MB.',
        ];
    }
}
