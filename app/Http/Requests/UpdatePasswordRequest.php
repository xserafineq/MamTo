<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Podaj obecne hasło.',
            'current_password.current_password' => 'Obecne hasło jest nieprawidłowe.',
            'password.required' => 'Nowe hasło jest wymagane.',
            'password.min' => 'Nowe hasło musi mieć co najmniej 8 znaków.',
            'password.confirmed' => 'Hasła nie są identyczne.',
        ];
    }
}
