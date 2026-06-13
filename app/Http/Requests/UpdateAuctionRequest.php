<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAuctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'categoryId' => ['required', 'integer', Rule::exists('Categories', 'id')],
            'negotiable' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'location' => ['required', 'string', 'max:200'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tytuł aukcji jest wymagany.',
            'name.max' => 'Tytuł może mieć maksymalnie 255 znaków.',
            'description.max' => 'Opis może mieć maksymalnie 5000 znaków.',
            'categoryId.required' => 'Wybierz kategorię.',
            'categoryId.exists' => 'Wybrana kategoria nie istnieje.',
            'negotiable.required' => 'Wybierz, czy cena jest do negocjacji.',
            'price.required' => 'Cena jest wymagana.',
            'price.numeric' => 'Cena musi być liczbą.',
            'price.min' => 'Cena nie może być ujemna.',
            'price.max' => 'Cena jest zbyt wysoka.',
            'location.required' => 'Lokalizacja jest wymagana.',
            'location.max' => 'Lokalizacja może mieć maksymalnie 200 znaków.',
            'thumbnail.image' => 'Miniatura musi być obrazem.',
            'thumbnail.mimes' => 'Miniatura musi być w formacie JPG, PNG lub WEBP.',
            'thumbnail.max' => 'Miniatura może mieć maksymalnie 5 MB.',
            'images.max' => 'Możesz dodać maksymalnie 4 dodatkowe zdjęcia.',
            'images.*.image' => 'Dodatkowe pliki muszą być obrazami.',
            'images.*.mimes' => 'Dodatkowe zdjęcia muszą być w formacie JPG, PNG lub WEBP.',
            'images.*.max' => 'Każde dodatkowe zdjęcie może mieć maksymalnie 5 MB.',
        ];
    }
}
