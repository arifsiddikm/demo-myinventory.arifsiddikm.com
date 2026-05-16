<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $fillable = [
        'reference_no', 'item_id', 'user_id', 'quantity',
        'supplier', 'price_per_unit', 'received_date', 'notes',
    ];

    protected $casts = ['received_date' => 'date'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
