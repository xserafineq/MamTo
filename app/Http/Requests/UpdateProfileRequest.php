<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'phoneNumber.required' => 'Numer telefonu jest wymagany.',
            'phoneNumber.max' => 'Numer telefonu może mieć maksymalnie 11 cyfr (opcjonalnie + na początku).',
        ];
    }
}
