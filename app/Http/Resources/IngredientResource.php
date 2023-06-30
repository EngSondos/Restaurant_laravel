<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this[0]->id,
            'name'=>$this[0]->name,
            'price'=> $this[0]->price,
            'profit' => $this[0]->profit,
            'quntity' => $this[0]->quntity,
            'status' =>$this[0]->status
        ];    
    }
    }
