<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $uniqueRule = $this->isMethod('put')
            ? 'unique:units,code,' . $this->route('unit')->id
            : 'unique:units,code';

        return [
            'code' => ['required', 'string', 'max:50', $uniqueRule],
            'name' => 'required|string|max:255',
            'pre_assessments' => 'array',
            'pre_assessments.*.question' => 'required|string|max:255',
            'pre_assessments.*.expected_answer' => 'required|string',
        ];
    }
}