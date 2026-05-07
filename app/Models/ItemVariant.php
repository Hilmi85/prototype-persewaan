<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'sku_code',
        'size',
        'color',
        'stock',
        'available_stock',
        'daily_price',
        'is_active',
    ];

    protected $casts = [
        'stock' => 'integer',
        'available_stock' => 'integer',
        'daily_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function orderItemVariants()
    {
        return $this->hasMany(OrderItemVariant::class);
    }
}
