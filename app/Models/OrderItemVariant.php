<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItemVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'item_variant_id',
        'qty',
        'unit_price',
        'subtotal_price',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal_price' => 'decimal:2',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function itemVariant()
    {
        return $this->belongsTo(ItemVariant::class);
    }
}
