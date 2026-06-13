<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class AuctionFormRequest extends FormRequest
{
    protected function isJobCategory(): bool
    {
        $categoryId = (int) $this->input('categoryId');

        return $categoryId > 0 && Category::requiresApproval($categoryId);
    }

    protected function salaryTypeOptions(): array
    {
        return ['brutto/h', 'brutto/mies.', 'netto/h', 'do uzgodnienia'];
    }

    protected function sharedRules(bool $thumbnailRequired): array
    {
        $isJob = $this->isJobCategory();

        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'categoryId' => ['required', 'integer', Rule::exists('Categories', 'id')],
            'negotiable' => [$isJob ? 'nullable' : 'required', 'boolean'],
            'price' => [
                $isJob && $this->input('salaryType') === 'do uzgodnienia' ? 'nullable' : 'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
            'salaryType' => [
                Rule::requiredIf($isJob),
                'nullable',
                'string',
                Rule::in($this->salaryTypeOptions()),
            ],
            'location' => ['required', 'string', 'max:200'],
            'thumbnail' => array_filter([
                $thumbnailRequired && ! $isJob ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ]),
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }
}
