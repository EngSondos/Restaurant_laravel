<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\OrderCreated;



class Order extends Model
{
    use HasFactory,SoftDeletes;
    

    protected $fillable = [
        "total_price" , "status" , "user_id" , "table_id" , "customer_id","discount","tax","service_fee","reservation_id"
    ];

    protected $attributes = [
        'total_price' => 0,
    ];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot(['quantity', 'total_price', 'status','id']);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }



}

