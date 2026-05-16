<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code', 'name', 'category_id', 'stock', 'min_stock',
        'unit', 'location', 'description', 'image', 'condition', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function getConditionLabelAttribute(): string
    {
        return match($this->condition) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => 'Baik',
        };
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/no-image.png');
    }
}
