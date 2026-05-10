<?php

namespace App\Services;

use App\Models\ItemVariant;
use App\Models\OrderItemVariant;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class RentalAvailabilityService
{
    public const BLOCKING_ORDER_STATUSES = [
        'pending',
        'confirmed',
        'booked',
        'in_progress',
        'success',
    ];

    public const BLOCKING_BOOKING_STATUSES = [
        'pending',
        'scheduled',
        'rescheduled',
        'picked_up',
        'done',
    ];

    public function availability(ItemVariant $variant, string $rentalStart, string $rentalEnd, ?int $ignoreOrderId = null): array
    {
        $start = Carbon::parse($rentalStart)->startOfDay();
        $end = Carbon::parse($rentalEnd)->endOfDay();

        if ($end->lt($start)) {
            throw ValidationException::withMessages([
                'rental_end' => 'Tanggal selesai sewa tidak boleh sebelum tanggal mulai sewa.',
            ]);
        }

        $baseStock = max(0, min((int) $variant->stock, (int) $variant->available_stock));

        $reservedStock = $this->reservedQuantity(
            $variant,
            $start->toDateString(),
            $end->toDateString(),
            $ignoreOrderId
        );

        return [
            'variant_id' => $variant->id,
            'base_stock' => $baseStock,
            'reserved_stock' => $reservedStock,
            'available_stock' => max(0, $baseStock - $reservedStock),
            'rental_start' => $start->toDateString(),
            'rental_end' => $end->toDateString(),
        ];
    }

    public function reservedQuantity(ItemVariant $variant, string $rentalStart, string $rentalEnd, ?int $ignoreOrderId = null): int
    {
        return (int) OrderItemVariant::query()
            ->join('order_items', 'order_item_variants.order_item_id', '=', 'order_items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('rental_bookings', 'orders.id', '=', 'rental_bookings.order_id')
            ->whereNull('orders.deleted_at')
            ->whereNull('order_items.deleted_at')
            ->where('order_item_variants.item_variant_id', $variant->id)
            ->whereIn('orders.status', self::BLOCKING_ORDER_STATUSES)
            ->whereIn('rental_bookings.booking_status', self::BLOCKING_BOOKING_STATUSES)
            ->whereDate('rental_bookings.rental_start', '<=', $rentalEnd)
            ->whereDate('rental_bookings.rental_end', '>=', $rentalStart)
            ->when($ignoreOrderId, function ($query) use ($ignoreOrderId) {
                $query->where('orders.id', '!=', $ignoreOrderId);
            })
            ->sum('order_item_variants.qty');
    }

    public function ensureAvailable(
        ItemVariant $variant,
        int $quantity,
        string $rentalStart,
        string $rentalEnd,
        ?string $label = null,
        ?int $ignoreOrderId = null
    ): array {
        $availability = $this->availability(
            $variant,
            $rentalStart,
            $rentalEnd,
            $ignoreOrderId
        );

        if ($quantity > $availability['available_stock']) {
            $name = $label ?: trim(
                ($variant->item->name ?? 'Item') . ' ' .
                ($variant->size ?? '') . ' ' .
                ($variant->color ?? '')
            );

            throw ValidationException::withMessages([
                'rental_start' => 'Stok ' . $name . ' tidak cukup untuk tanggal '
                    . Carbon::parse($rentalStart)->format('d-m-Y')
                    . ' sampai '
                    . Carbon::parse($rentalEnd)->format('d-m-Y')
                    . '. Sisa tersedia pada tanggal tersebut: '
                    . $availability['available_stock'] . '.',
            ]);
        }

        return $availability;
    }
}
