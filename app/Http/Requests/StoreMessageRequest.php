<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Treść wiadomości jest wymagana.',
            'text.max' => 'Wiadomość może mieć maksymalnie 2000 znaków.',
        ];
    }
}
