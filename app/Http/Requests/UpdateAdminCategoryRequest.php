<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nazwa kategorii jest wymagana.',
            'name.max' => 'Nazwa kategorii może mieć maksymalnie 150 znaków.',
            'image.image' => 'Zdjęcie kategorii musi być obrazem.',
            'image.mimes' => 'Zdjęcie kategorii musi być w formacie JPG, PNG lub WEBP.',
            'image.max' => 'Zdjęcie kategorii może mieć maksymalnie 5 MB.',
        ];
    }
}
