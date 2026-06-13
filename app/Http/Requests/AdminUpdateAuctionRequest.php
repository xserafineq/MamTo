<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class AdminUpdateAuctionRequest extends UpdateAuctionRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'status' => ['required', 'string', Rule::in(['aktywna', 'zakończona'])],
        ]);
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'status.required' => 'Wybierz status aukcji.',
            'status.in' => 'Wybrany status jest nieprawidłowy.',
        ]);
    }
}
