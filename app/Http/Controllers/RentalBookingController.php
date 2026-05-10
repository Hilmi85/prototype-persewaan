<?php

namespace App\Http\Controllers;

use App\Models\ItemVariant;
use App\Models\Order;
use App\Models\RentalBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RentalBookingController extends Controller
{
    public function index()
    {
        $bookings = RentalBooking::with([
                'order.user',
                'order.orderItems.item',
                'order.orderItems.orderItemVariants.itemVariant.item',
                'returnedBy',
            ])
            ->latest()
            ->get();

        $returnSummary = [
            'total' => $bookings->count(),
            'active' => $bookings->whereNotIn('booking_status', ['returned', 'cancelled'])->count(),
            'late' => $bookings->filter(function ($booking) {
                return !$booking->returned_at
                    && $booking->rental_end
                    && $booking->rental_end->lt(now()->startOfDay())
                    && !in_array($booking->booking_status, ['returned', 'cancelled'], true);
            })->count(),
            'returned' => $bookings->where('booking_status', 'returned')->count(),
        ];

        return view('admin.rental-booking.index', compact('bookings', 'returnSummary'));
    }

    public function create()
    {
        $orders = Order::with('user')
            ->latest()
            ->get();

        return view('admin.rental-booking.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateBooking($request);

        if (empty($validated['booking_code'])) {
            $validated['booking_code'] = $this->generateBookingCode();
        }

        RentalBooking::create($validated);

        return redirect()
            ->route('rental-bookings.index')
            ->with('success', 'Booking berhasil ditambahkan.');
    }

    public function edit(RentalBooking $rentalBooking)
    {
        $rentalBooking->load('order.user');

        $orders = Order::with('user')
            ->latest()
            ->get();

        return view('admin.rental-booking.edit', compact('rentalBooking', 'orders'));
    }

    public function update(Request $request, RentalBooking $rentalBooking)
    {
        $validated = $this->validateBooking($request, $rentalBooking);

        /*
         * Saat admin hanya update status, data tanggal dari customer jangan sampai hilang.
         */
        foreach (['rental_start', 'rental_end', 'event_date', 'fitting_date', 'makeup_date'] as $dateField) {
            if (!$request->filled($dateField)) {
                $validated[$dateField] = $rentalBooking->{$dateField}
                    ? $rentalBooking->{$dateField}->format('Y-m-d')
                    : null;
            }
        }

        $oldStatus = $rentalBooking->booking_status;
        $newStatus = $validated['booking_status'];

        $rentalBooking->update($validated);

        /*
         * Jika admin memilih status Returned dari halaman edit,
         * sistem tetap memproses pengembalian dengan kondisi default baik.
         * Untuk input lengkap, gunakan tombol Pengembalian di halaman Data Booking.
         */
        if ($oldStatus !== 'returned' && $newStatus === 'returned') {
            $this->processReturn($rentalBooking->fresh(), [
                'returned_at' => now(),
                'return_condition' => 'baik',
                'return_stock_action' => 'restore_available',
                'late_fee' => 0,
                'damage_fee' => 0,
                'return_notes' => $validated['notes'] ?? null,
            ]);
        }

        return redirect()
            ->route('rental-bookings.index')
            ->with('success', 'Booking berhasil diperbarui.');
    }

    public function returnItems(Request $request, RentalBooking $rentalBooking)
    {
        $validated = $request->validate([
            'returned_at' => 'nullable|date',
            'return_condition' => 'required|in:baik,perlu_laundry,rusak_ringan,rusak_berat,hilang',
            'return_stock_action' => 'required|in:restore_available,hold_unavailable,reduce_total',
            'late_fee' => 'nullable|numeric|min:0',
            'damage_fee' => 'nullable|numeric|min:0',
            'return_notes' => 'nullable|string',
        ], [
            'return_condition.required' => 'Kondisi barang saat kembali wajib dipilih.',
            'return_stock_action.required' => 'Aksi stok pengembalian wajib dipilih.',
        ]);

        $this->processReturn($rentalBooking, $validated);

        return redirect()
            ->route('rental-bookings.index')
            ->with('success', 'Pengembalian barang berhasil diproses dan stok sudah diperbarui.');
    }

    public function destroy(RentalBooking $rentalBooking)
    {
        $rentalBooking->delete();

        return redirect()
            ->route('rental-bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }

    private function processReturn(RentalBooking $rentalBooking, array $data): void
    {
        DB::transaction(function () use ($rentalBooking, $data) {
            $booking = RentalBooking::query()
                ->whereKey($rentalBooking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($booking->returned_at) {
                throw ValidationException::withMessages([
                    'returned_at' => 'Booking ini sudah pernah diproses pengembaliannya pada ' . $booking->returned_at->format('d-m-Y H:i') . '.',
                ]);
            }

            $booking->loadMissing([
                'order.orderItems.orderItemVariants.itemVariant',
            ]);

            $returnStockAction = $data['return_stock_action'];

            $returnedAt = !empty($data['returned_at'])
                ? Carbon::parse($data['returned_at'])
                : now();

            foreach ($this->getOrderVariantRows($booking) as $row) {
                $variant = ItemVariant::query()
                    ->whereKey($row['variant_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$variant) {
                    continue;
                }

                $qty = (int) $row['qty'];
                $currentTotal = (int) $variant->stock;
                $currentAvailable = (int) $variant->available_stock;

                /*
                 * restore_available:
                 * Barang kembali normal, stok tersedia naik lagi.
                 */
                if ($returnStockAction === 'restore_available') {
                    $variant->update([
                        'available_stock' => min($currentTotal, $currentAvailable + $qty),
                    ]);
                }

                /*
                 * hold_unavailable:
                 * Barang kembali tetapi belum bisa disewakan lagi,
                 * misalnya perlu laundry/perbaikan.
                 */
                if ($returnStockAction === 'hold_unavailable') {
                    $variant->update([
                        'available_stock' => min($currentTotal, $currentAvailable),
                    ]);
                }

                /*
                 * reduce_total:
                 * Barang hilang/rusak berat, stok fisik dikurangi.
                 */
                if ($returnStockAction === 'reduce_total') {
                    $newTotal = max(0, $currentTotal - $qty);

                    $variant->update([
                        'stock' => $newTotal,
                        'available_stock' => min($newTotal, $currentAvailable),
                    ]);
                }
            }

            $lateDays = 0;

            if ($booking->rental_end && $returnedAt->copy()->startOfDay()->gt($booking->rental_end->copy()->startOfDay())) {
                $lateDays = $booking->rental_end
                    ->copy()
                    ->startOfDay()
                    ->diffInDays($returnedAt->copy()->startOfDay());
            }

            $lateFee = (float) ($data['late_fee'] ?? 0);
            $damageFee = (float) ($data['damage_fee'] ?? 0);

            $booking->update([
                'booking_status' => 'returned',
                'returned_at' => $returnedAt,
                'returned_by' => Auth::id(),

                'return_condition' => $data['return_condition'],
                'return_stock_action' => $returnStockAction,
                'return_notes' => $data['return_notes'] ?? null,

                'late_days' => $lateDays,
                'late_fee' => $lateFee,
                'damage_fee' => $damageFee,
                'total_return_fee' => $lateFee + $damageFee,
            ]);

            if ($booking->order && !in_array($booking->order->status, ['cancelled', 'completed'], true)) {
                $booking->order->update([
                    'status' => 'completed',
                ]);
            }
        });
    }

    private function getOrderVariantRows(RentalBooking $booking): array
    {
        $rows = [];

        foreach ($booking->order?->orderItems ?? [] as $orderItem) {
            foreach ($orderItem->orderItemVariants as $orderItemVariant) {
                if (!$orderItemVariant->item_variant_id || (int) $orderItemVariant->qty <= 0) {
                    continue;
                }

                $variantId = (int) $orderItemVariant->item_variant_id;

                if (!isset($rows[$variantId])) {
                    $rows[$variantId] = [
                        'variant_id' => $variantId,
                        'qty' => 0,
                    ];
                }

                $rows[$variantId]['qty'] += (int) $orderItemVariant->qty;
            }
        }

        return array_values($rows);
    }

    private function validateBooking(Request $request, ?RentalBooking $rentalBooking = null): array
    {
        return $request->validate([
            'order_id' => 'required|exists:orders,id',
            'booking_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('rental_bookings', 'booking_code')->ignore($rentalBooking?->id),
            ],
            'event_type' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'rental_start' => 'nullable|date',
            'rental_end' => 'nullable|date|after_or_equal:rental_start',
            'event_date' => 'nullable|date',
            'fitting_date' => 'nullable|date',
            'makeup_date' => 'nullable|date',
            'pickup_method' => 'nullable|string|max:255',
            'booking_status' => 'required|in:pending,scheduled,rescheduled,picked_up,done,cancelled,returned',
            'notes' => 'nullable|string',
        ]);
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BOOK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (RentalBooking::where('booking_code', $code)->exists());

        return $code;
    }
}
