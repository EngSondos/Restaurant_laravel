<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
                'total_price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric',
                'tax'=> 'required|numeric|max:1',
                'payment_method' => 'nullable|in:CASH,VISA',
                'service_fee' => 'required|numeric|max:1',
                'status' => 'required|in:Pending,Accepted,Prepare,Complete,Served,Canceled,Paid',
                'table_id' => 'required|exists:tables,id',
                'user_id' => 'required|exists:users,id',
                'customer_id' => 'required|exists:customers,id',
                
            ];       
    }
}