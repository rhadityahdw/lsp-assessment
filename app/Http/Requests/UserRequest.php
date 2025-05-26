<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255'],
            'role_name' => 'required|string|exists:roles,name',
        ];

        if ($this->isMethod('post')) {
            $rules['email'][] = 'unique:users,email';
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        if ($this->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        return $rules;
    }
}