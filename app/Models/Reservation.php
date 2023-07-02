<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date',
        'table_id',
        'customer_id',
        'status'
    ];

    public function table():BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
