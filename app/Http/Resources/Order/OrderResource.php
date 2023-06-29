<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'service_fee' => $this->service_fee,
            'status' => $this->status,
            'table_id' => $this->table_id,
            'user_id' => $this->user_id,
            'customer_id' => $this->customer_id,
         
            // 'products' => ProductResource::collection($this->whenLoaded('products')),
        ];   
     }


     public function with($request)
     {
 
         return [
             'meta' => [
                 'pagination' => [
                     'total' => $this->resource->total(),
                     'per_page' => $this->resource->perPage(),
                     'current_page' => $this->resource->currentPage(),
                     'last_page' => $this->resource->lastPage(),
                     'from' => $this->resource->firstItem(),
                     'to' => $this->resource->lastItem(),
                 ]
             ],
         ];
     }
}
