<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'booking_code',
        'event_type',
        'gender',
        'rental_start',
        'rental_end',
        'event_date',
        'fitting_date',
        'makeup_date',
        'pickup_method',
        'booking_status',
        'notes',

        'returned_at',
        'returned_by',
        'return_condition',
        'return_stock_action',
        'return_notes',
        'late_days',
        'late_fee',
        'damage_fee',
        'total_return_fee',
    ];

    protected $casts = [
        'rental_start' => 'date',
        'rental_end' => 'date',
        'event_date' => 'date',
        'fitting_date' => 'date',
        'makeup_date' => 'date',
        'returned_at' => 'datetime',

        'late_days' => 'integer',
        'late_fee' => 'decimal:2',
        'damage_fee' => 'decimal:2',
        'total_return_fee' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function getIsReturnedAttribute(): bool
    {
        return $this->booking_status === 'returned' || !is_null($this->returned_at);
    }
}
