<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GetDiamondPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public endpoint
    }

    public function rules(): array
    {
        return [
            'carat' => ['required', 'numeric', 'min:0.1', 'max:10'],
            'cut' => ['required', 'string', 'in:excellent,very-good,good,fair,poor'],
            'color' => ['required', 'string', 'regex:/^[D-Z]$/'],
            'clarity' => ['required', 'string', 'in:fl,if,vvs1,vvs2,vs1,vs2,si1,si2,i1'],
        ];
    }

    public function messages(): array
    {
        return [
            'carat.required' => 'Carat weight is required.',
            'carat.numeric' => 'Carat weight must be a number.',
            'carat.min' => 'Carat weight must be at least 0.1.',
            'carat.max' => 'Carat weight cannot exceed 10 carats.',
            'cut.required' => 'Diamond cut grade is required.',
            'cut.in' => 'Diamond cut must be one of: excellent, very-good, good, fair, or poor.',
            'color.required' => 'Diamond color grade is required.',
            'color.regex' => 'Diamond color grade must be a letter from D to Z.',
            'clarity.required' => 'Diamond clarity is required.',
            'clarity.in' => 'Invalid clarity grade. Accepted values: FL, IF, VVS1, VVS2, VS1, VS2, SI1, SI2, I1.',
        ];
    }
}
