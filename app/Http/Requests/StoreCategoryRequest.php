<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
     *'status' =>
     * @return array<string, \Illuminate\\Contracts\\Validationz\ValidationRule|array|string>
     */
    public function rules(): array
    {
        //validations
        return [
            'name' => ['string','required','max:50'],
            'image' => ['required','max:1000','mimes:jpg,png,jpeg,gif'],
            'status' => 'required|in:0,1'
        ];
        
    }

    public function validationData():array{
        $data = parent::validationData();

        if($data["status"]== null){
            $data["status"] = '1';
        };
        return $data;
    }
}
