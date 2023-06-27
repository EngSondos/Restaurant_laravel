<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable =[
        "name" ,
        "total_price"  ,
        "image",
        "category_id",
        "extra"
    ];

    public function ingredients():BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class,'product_ingredient')->withPivot('quantity', 'total', 'price');
      }
      protected $casts = [
        'extra' => 'array',
    ];


}
