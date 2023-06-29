<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date',
        'table_id',
        'customer_id',
        'status'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
