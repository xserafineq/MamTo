<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdatePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isMainAdmin;
    }

    public function rules(): array
    {
        return [
            'isAdmin' => ['required', Rule::in(['0', '1', 0, 1, true, false])],
        ];
    }

    public function messages(): array
    {
        return [
            'isAdmin.required' => 'Wybierz uprawnienia.',
            'isAdmin.in' => 'Wybrane uprawnienia są nieprawidłowe.',
        ];
    }
}
