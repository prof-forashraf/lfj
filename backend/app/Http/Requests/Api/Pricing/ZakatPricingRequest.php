<?php

namespace App\Http\Requests\Api\Pricing;

use Illuminate\Foundation\Http\FormRequest;

class ZakatPricingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'metal_symbol' => ['required', 'string', 'in:XAU,XAG'],
            'weight_grams' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3'],
            'nisab_basis' => ['sometimes', 'string', 'in:gold,silver'],
            'debug' => ['sometimes', 'boolean'],
        ];
    }
}
