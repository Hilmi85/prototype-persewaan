<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class ExpiredOrderService
{
    public function expirePendingQrisOrders(?int $limit = 100): array
    {
        $query = Payment::query()
            ->where('method', 'qris')
            ->where('payment_status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->whereHas('order', function ($query) {
                $query->where('status', 'pending');
            })
            ->orderBy('expired_at');

        if ($limit) {
            $query->limit($limit);
        }

        $payments = $query->get();

        $expired = [];
        $skipped = 0;

        foreach ($payments as $payment) {
            $result = $this->expirePayment($payment);

            if ($result) {
                $expired[] = $result;
            } else {
                $skipped++;
            }
        }

        return [
            'expired_count' => count($expired),
            'skipped_count' => $skipped,
            'expired_orders' => $expired,
        ];
    }

    public function expireOrder(Order $order): ?array
    {
        $payment = Payment::query()
            ->where('order_id', $order->id)
            ->where('method', 'qris')
            ->where('payment_status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->first();

        if (!$payment) {
            return null;
        }

        return $this->expirePayment($payment);
    }

    public function expirePayment(Payment $payment): ?array
    {
        return DB::transaction(function () use ($payment) {
            $lockedPayment = Payment::query()
                ->whereKey($payment->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedPayment) {
                return null;
            }

            if ($lockedPayment->method !== 'qris') {
                return null;
            }

            if ($lockedPayment->payment_status !== 'pending') {
                return null;
            }

            if (!$lockedPayment->expired_at || $lockedPayment->expired_at->gt(now())) {
                return null;
            }

            $order = Order::with(['user', 'rentalBookings'])
                ->whereKey($lockedPayment->order_id)
                ->lockForUpdate()
                ->first();

            if (!$order) {
                return null;
            }

            if ($order->status !== 'pending') {
                return null;
            }

            $lockedPayment->update([
                'payment_status' => 'expired',
                'midtrans_status' => $lockedPayment->midtrans_status ?: 'expire',
            ]);

            $order->update([
                'status' => 'cancelled',
            ]);

            $cancelledBookings = 0;

            foreach ($order->rentalBookings as $booking) {
                if (!in_array($booking->booking_status, ['cancelled', 'returned'], true)) {
                    $booking->update([
                        'booking_status' => 'cancelled',
                    ]);

                    $cancelledBookings++;
                }
            }

            return [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'customer' => $order->user->fullname ?? '-',
                'payment_id' => $lockedPayment->id,
                'payment_code' => $lockedPayment->payment_code,
                'expired_at' => optional($lockedPayment->expired_at)->format('d-m-Y H:i'),
                'cancelled_bookings' => $cancelledBookings,
            ];
        });
    }

    public function countExpiredPendingQrisOrders(): int
    {
        return Payment::query()
            ->where('method', 'qris')
            ->where('payment_status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->whereHas('order', function ($query) {
                $query->where('status', 'pending');
            })
            ->count();
    }
}
