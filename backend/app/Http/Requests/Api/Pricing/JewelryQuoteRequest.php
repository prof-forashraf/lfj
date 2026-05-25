<?php

namespace App\Http\Requests\Api\Pricing;

use Illuminate\Foundation\Http\FormRequest;

class JewelryQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'metal_symbol' => ['required', 'string', 'max:10'],
            'weight_grams' => ['required', 'numeric', 'min:0.01'],
            'purity_grade' => ['required', 'string', 'max:10'],
            'fabrication_cost' => ['required', 'numeric', 'min:0'],
            'labor_cost' => ['required', 'numeric', 'min:0'],
            'gemstone_cost' => ['required', 'numeric', 'min:0'],
            'markup_percentage' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'debug' => ['sometimes', 'boolean'],
        ];
    }
}
