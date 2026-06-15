<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Wybierz plik zdjęcia.',
            'image.image' => 'Plik musi być obrazem.',
            'image.mimes' => 'Zdjęcie musi być w formacie JPG, PNG lub WEBP.',
            'image.max' => 'Zdjęcie może mieć maksymalnie 5 MB.',
        ];
    }
}
