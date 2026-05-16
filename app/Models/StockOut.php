<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $fillable = [
        'reference_no', 'item_id', 'user_id', 'quantity',
        'purpose', 'recipient', 'issued_date', 'notes',
    ];

    protected $casts = ['issued_date' => 'date'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
