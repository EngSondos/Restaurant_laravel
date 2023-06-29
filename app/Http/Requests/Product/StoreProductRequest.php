<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name"=>"required|string",
            "total_price"=>"required",
            "description"=>"nullable|string",
            "category_id"=>"required|exists:categories,id",
            "ingredients"=>"array|required",
            "ingredients.*.id"=>"required|exists:ingredients",
            "ingredients.*.quantity"=>"required",
            "ingredients.*.price"=>"required",
            "extra"=>"sometimes|required|array",
            "extra.*"=>"exists:ingredients,id"

                ];
    }
}