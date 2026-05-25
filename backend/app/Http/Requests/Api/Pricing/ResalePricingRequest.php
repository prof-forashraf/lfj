<?php

namespace App\Http\Requests\Api\Pricing;

use Illuminate\Foundation\Http\FormRequest;

class ResalePricingRequest extends FormRequest
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
            'condition' => ['required', 'string', 'in:excellent,good,fair,damaged'],
            'buyback_percentage' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'currency' => ['required', 'string', 'size:3'],
            'debug' => ['sometimes', 'boolean'],
        ];
    }
}
