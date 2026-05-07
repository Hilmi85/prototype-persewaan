<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_code',
        'method',
        'gateway_ref',
        'transaction_ref',
        'amount',
        'payment_status',
        'paid_at',
        'expired_at',
        'proof_url',
        'snap_token',
        'redirect_url',
        'response_payload',
        'notification_payload',
        'midtrans_status',
        'fraud_status',
        'payment_type',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'response_payload' => 'array',
        'notification_payload' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function getIsQrisAttribute(): bool
    {
        return $this->method === 'qris';
    }

    public function getIsCashAttribute(): bool
    {
        return $this->method === 'tunai';
    }
}
