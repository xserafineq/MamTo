<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstName' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'lastName' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['required', 'string', 'email', 'max:200', 'unique:Users,email'],
            'phoneNumber' => ['required', 'string', 'regex:/^[0-9]{9}$/'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'firstName.required' => 'Imię jest wymagane.',
            'firstName.max' => 'Imię może mieć maksymalnie 100 znaków.',
            'firstName.regex' => 'Imię może zawierać tylko litery.',
            'lastName.required' => 'Nazwisko jest wymagane.',
            'lastName.max' => 'Nazwisko może mieć maksymalnie 100 znaków.',
            'lastName.regex' => 'Nazwisko może zawierać tylko litery.',
            'email.required' => 'Adres e-mail jest wymagany.',
            'email.email' => 'Podaj prawidłowy adres e-mail.',
            'email.max' => 'Adres e-mail może mieć maksymalnie 200 znaków.',
            'email.unique' => 'Ten adres e-mail jest już zajęty.',
            'phoneNumber.required' => 'Numer telefonu jest wymagany.',
            'phoneNumber.regex' => 'Numer telefonu musi składać się z 9 cyfr.',
            'password.required' => 'Hasło jest wymagane.',
            'password.min' => 'Hasło musi mieć co najmniej 8 znaków.',
            'password.confirmed' => 'Hasła nie są identyczne.',
        ];
    }
}
