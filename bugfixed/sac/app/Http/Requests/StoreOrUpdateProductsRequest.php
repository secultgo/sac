<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "product_name" => "string|required|min:3|max:255",
            "product_code" => "integer|required|min:3|max:255",
            "product_description" => "string|required|min:3|max:255",
        ];
    }

    public function messages(): array
    {
        return [
            "product_name.required" => "O nome do produto é obrigatório",
            "product_code.integer" => "O código do produto deve ser um número inteiro",
            "product_description.required" => "A descrição do produto é obrigatória",
        ];
    }
}