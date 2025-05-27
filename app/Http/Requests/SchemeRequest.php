<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $uniqueRule = $this->isMethod('put') 
            ? 'unique:schemes,code,' . $this->route('scheme')->id
            : 'unique:schemes,code';

        return [
            'code' => ['required', 'string', 'max:50', $uniqueRule],
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:units,id',
            'document_path' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
        ];
    }
}