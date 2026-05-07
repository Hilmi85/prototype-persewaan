<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\RentalBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RentalBookingController extends Controller
{
    public function index()
    {
        $bookings = RentalBooking::with('order.user')
            ->latest()
            ->get();

        return view('admin.rental-booking.index', compact('bookings'));
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
         * Penting:
         * Saat admin hanya update status, data tanggal dari customer jangan sampai hilang.
         * Kalau field tanggal tidak terkirim / kosong, gunakan data lama.
         */
        foreach (['rental_start', 'rental_end', 'event_date', 'fitting_date', 'makeup_date'] as $dateField) {
            if (!$request->filled($dateField)) {
                $validated[$dateField] = $rentalBooking->{$dateField}
                    ? $rentalBooking->{$dateField}->format('Y-m-d')
                    : null;
            }
        }

        $rentalBooking->update($validated);

        return redirect()
            ->route('rental-bookings.index')
            ->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(RentalBooking $rentalBooking)
    {
        $rentalBooking->delete();

        return redirect()
            ->route('rental-bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
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
            'booking_status' => 'required|in:pending,scheduled,rescheduled,done,cancelled',
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
