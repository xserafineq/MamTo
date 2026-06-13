<?php

namespace App\Http\Requests;

class StoreAuctionRequest extends AuctionFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->sharedRules(thumbnailRequired: true);
    }

    public function messages(): array
    {
        return [
            'name.required' => $this->isJobCategory() ? 'Stanowisko jest wymagane.' : 'Tytuł aukcji jest wymagany.',
            'name.max' => 'Tytuł może mieć maksymalnie 255 znaków.',
            'description.max' => 'Opis może mieć maksymalnie 5000 znaków.',
            'categoryId.required' => 'Wybierz kategorię.',
            'categoryId.exists' => 'Wybrana kategoria nie istnieje.',
            'negotiable.required' => 'Wybierz, czy cena jest do negocjacji.',
            'price.required' => $this->isJobCategory() ? 'Wynagrodzenie jest wymagane.' : 'Cena jest wymagana.',
            'price.numeric' => $this->isJobCategory() ? 'Wynagrodzenie musi być liczbą.' : 'Cena musi być liczbą.',
            'price.min' => $this->isJobCategory() ? 'Wynagrodzenie nie może być ujemne.' : 'Cena nie może być ujemna.',
            'price.max' => $this->isJobCategory() ? 'Wynagrodzenie jest zbyt wysokie.' : 'Cena jest zbyt wysoka.',
            'salaryType.required' => 'Wybierz rodzaj wynagrodzenia.',
            'salaryType.in' => 'Wybrany rodzaj wynagrodzenia jest nieprawidłowy.',
            'location.required' => 'Lokalizacja jest wymagana.',
            'location.max' => 'Lokalizacja może mieć maksymalnie 200 znaków.',
            'thumbnail.required' => 'Miniatura jest wymagana.',
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
