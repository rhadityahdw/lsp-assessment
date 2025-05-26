<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scheme_id' => 'required|exists:schemes,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'link' => 'required|string|max:255',
        ];
    }
}