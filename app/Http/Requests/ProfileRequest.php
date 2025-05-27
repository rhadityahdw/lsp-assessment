<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'max:16'],
            'gender' => ['required', 'string', 'in:' . implode(',', array_column(Gender::cases(), 'value'))],
            'date_of_birth' => ['required', 'date'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'string', 'max:15'],
            'education' => ['required', 'string'],
            'job_title' => ['required','string'],
            'company_name' => ['nullable','string'],
            'company_address' => ['nullable','string'],
            'company_phone' => ['nullable','string','max:15'],
            'company_email' => ['nullable','string','email'],
        ];
    }
}