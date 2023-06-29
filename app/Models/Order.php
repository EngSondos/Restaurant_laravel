<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{
    use HasFactory,SoftDeletes;
    

    protected $fillable = [
        "total_price" , "status" , "user_id" , "table_id" , "customer_id","discount","tax","service_fee"
    ];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot(['quantity', 'total_price', 'status']);
    }

    public function setTotalPriceAttribute($value)
    {
        $this->attributes['total_price'] = $value;
    }

    public function getTotalPriceAttribute()
    {
        $total = $this->attributes['total_price'];

        $tax = $this->attributes['tax'];
        $total += $total * $tax;

        $serviceFee = $this->attributes['service_fee'];
        $total += $total * $serviceFee;

        $discount = $this->attributes['discount'];
        $total -= $total * $discount;

        return $total;
    }




}

