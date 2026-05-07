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
    ];

    protected $casts = [
        'rental_start' => 'date',
        'rental_end' => 'date',
        'event_date' => 'date',
        'fitting_date' => 'date',
        'makeup_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
