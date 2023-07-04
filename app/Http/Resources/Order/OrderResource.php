<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $products = $this->whenLoaded('products', function () {
            return $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->total_price,
                    'image'=>$product->image

                ];
            });
        });
        $order_products = $this->whenLoaded('products', function () {
            return $this->products->map(function ($product) {
                return [
                    'id' => $product->pivot->id,
                    'order_id' => $this->id,
                    'product_id' => $product->id,
                    'quantity' => $product->pivot->quantity,
                    'total_price' => $product->pivot->total_price,
                    'status' => $product->pivot->status,
                ];
            });
        });


        return [
            'id' => $this->id,
            'reservation_id' => $this->whenLoaded('reservation', function () {
                return $this->reservation->id;
            }),
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'service_fee' => $this->service_fee,
            'status' => $this->status,
            'table_id' => $this->table_id,
            'user_id' => $this->user_id,
            'customer_id' => $this->customer_id,
            'created_at'=> $this->created_at,
            'products' => $products,
            'order_products' => $order_products,

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
