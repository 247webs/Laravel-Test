<?php

namespace App\Http\Requests;

use App\Helpers\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChargeProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price' => ['required', 'numeric', 'min:1'],
            'product_id' => ['required', 'string', Rule::in([Constants::PRODUCT_B2B, Constants::PRODUCT_B2C])],
            'product_name' => ['required', 'string'],
            'payment_method' => ['required', 'string'],
        ];
    }
}
