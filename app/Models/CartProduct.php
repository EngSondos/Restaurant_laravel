<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    
    use HasFactory;

    protected $table = 'cart_product';
    protected $fillable =[
        'user_id',
        'product_id',
        'total_price',
        'quantity'
    ];

    public function product()
    {  
        return $this->belongsTo(Product::class,'product_id','id'); 
    }

}
