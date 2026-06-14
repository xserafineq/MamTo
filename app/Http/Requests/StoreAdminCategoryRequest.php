<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'parentId' => ['nullable', 'integer', Rule::exists('Categories', 'id')],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nazwa kategorii jest wymagana.',
            'name.max' => 'Nazwa kategorii może mieć maksymalnie 150 znaków.',
            'parentId.exists' => 'Wybrana kategoria nadrzędna nie istnieje.',
            'image.image' => 'Zdjęcie kategorii musi być obrazem.',
            'image.mimes' => 'Zdjęcie kategorii musi być w formacie JPG, PNG lub WEBP.',
            'image.max' => 'Zdjęcie kategorii może mieć maksymalnie 5 MB.',
        ];
    }
}
