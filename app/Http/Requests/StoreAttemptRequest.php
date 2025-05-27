<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttemptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
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
            'scheme_id' => ['required', 'exists:schemes,id'],
            'ktp' => ['required', 'file'],
            'ijazah' => ['required', 'file'],
            'pas_foto' => ['required', 'file'],
            'bukti_kerja' => ['nullable', 'file'],
            'portofolio' => ['nullable', 'file'],
            'pre_assessment_answers' => ['required', 'array'],
            'pre_assessment_answers.*.id' => ['required', 'exists:pre_assessments,id'],
            'pre_assessment_answers.*.answer' => ['required', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'pre_assessment_answers.required' => 'Harap isi semua jawaban pre asesmen',
            'pre_assessment_answers.*.id.exists' => 'Pertanyaan pre asesmen tidak valid.',
            'pre_assessment_answers.*.answer.boolean' => 'Jawaban harus bernilai Ya atau Tidak.',
        ];
    }
}
