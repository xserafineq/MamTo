<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin;
    }

    public function rules(): array
    {
        /** @var \App\Models\User $targetUser */
        $targetUser = $this->route('user');

        return [
            'firstName' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'lastName' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'email' => [
                'required',
                'string',
                'email',
                'max:200',
                Rule::unique('Users', 'email')->ignore($targetUser->id),
            ],
            'phoneNumber' => ['required', 'string', 'max:12', new PhoneNumber],
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
            'phoneNumber.max' => 'Numer telefonu może mieć maksymalnie 11 cyfr (opcjonalnie + na początku).',
        ];
    }
}
