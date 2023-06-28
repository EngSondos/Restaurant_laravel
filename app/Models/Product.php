<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable =[
        "name" ,
        "total_price"  ,
        "image",
        "category_id"
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function orderproduct()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
