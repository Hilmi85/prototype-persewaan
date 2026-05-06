<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\RentalBooking;
use Illuminate\Http\Request;

class RentalBookingController extends Controller
{
    public function index()
    {
        $bookings = RentalBooking::with('order.user')->latest()->get();
        return view('admin.rental-booking.index', compact('bookings'));
    }

    public function create()
    {
        $orders = Order::latest()->get();
        return view('admin.rental-booking.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'booking_code' => 'required|string|max:255|unique:rental_bookings,booking_code',
            'event_type' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'rental_start' => 'nullable|date',
            'rental_end' => 'nullable|date|after_or_equal:rental_start',
            'event_date' => 'nullable|date',
            'fitting_date' => 'nullable|date',
            'makeup_date' => 'nullable|date',
            'pickup_method' => 'nullable|string|max:255',
            'booking_status' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        RentalBooking::create($validated);

        return redirect()->route('rental-bookings.index')->with('success', 'Booking berhasil ditambahkan.');
    }

    public function edit(RentalBooking $rentalBooking)
    {
        $orders = Order::latest()->get();
        return view('admin.rental-booking.edit', compact('rentalBooking', 'orders'));
    }

    public function update(Request $request, RentalBooking $rentalBooking)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'booking_code' => 'required|string|max:255|unique:rental_bookings,booking_code,' . $rentalBooking->id,
            'event_type' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'rental_start' => 'nullable|date',
            'rental_end' => 'nullable|date|after_or_equal:rental_start',
            'event_date' => 'nullable|date',
            'fitting_date' => 'nullable|date',
            'makeup_date' => 'nullable|date',
            'pickup_method' => 'nullable|string|max:255',
            'booking_status' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $rentalBooking->update($validated);

        return redirect()->route('rental-bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(RentalBooking $rentalBooking)
    {
        $rentalBooking->delete();

        return redirect()->route('rental-bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
}
