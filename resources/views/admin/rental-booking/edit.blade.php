@extends('admin.layouts.master')
@section('title', 'Edit Rental Booking')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Edit Booking</h3>
            <p class="text-muted mb-0">Perbarui data booking.</p>
        </div>
        <a href="{{ route('rental-bookings.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Form Edit Booking</h4></div>
<div class="card-body">
<form action="{{ route('rental-bookings.update', $rentalBooking->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Order</label>
            <select name="order_id" class="form-select" required>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ old('order_id', $rentalBooking->order_id) == $order->id ? 'selected' : '' }}>
                        {{ $order->order_code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Booking</label>
            <input type="text" name="booking_code" class="form-control" value="{{ old('booking_code', $rentalBooking->booking_code) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Event Type</label>
            <input type="text" name="event_type" class="form-control" value="{{ old('event_type', $rentalBooking->event_type) }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Gender</label>
            <input type="text" name="gender" class="form-control" value="{{ old('gender', $rentalBooking->gender) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Mulai Sewa</label>
            <input type="date" name="rental_start" class="form-control" value="{{ old('rental_start', $rentalBooking->rental_start) }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Selesai Sewa</label>
            <input type="date" name="rental_end" class="form-control" value="{{ old('rental_end', $rentalBooking->rental_end) }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Tanggal Acara</label>
            <input type="date" name="event_date" class="form-control" value="{{ old('event_date', $rentalBooking->event_date) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Fitting</label>
            <input type="date" name="fitting_date" class="form-control" value="{{ old('fitting_date', $rentalBooking->fitting_date) }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Rias</label>
            <input type="date" name="makeup_date" class="form-control" value="{{ old('makeup_date', $rentalBooking->makeup_date) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Metode Pengambilan</label>
            <input type="text" name="pickup_method" class="form-control" value="{{ old('pickup_method', $rentalBooking->pickup_method) }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Status Booking</label>
            <select name="booking_status" class="form-select" required>
                <option value="pending" {{ old('booking_status', $rentalBooking->booking_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="scheduled" {{ old('booking_status', $rentalBooking->booking_status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="rescheduled" {{ old('booking_status', $rentalBooking->booking_status) == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                <option value="done" {{ old('booking_status', $rentalBooking->booking_status) == 'done' ? 'selected' : '' }}>Done</option>
                <option value="cancelled" {{ old('booking_status', $rentalBooking->booking_status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="notes" rows="4" class="form-control">{{ old('notes', $rentalBooking->notes) }}</textarea>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('rental-bookings.index') }}" class="btn btn-light-secondary">Batal</a>
    </div>
</form>
</div>
</div>
</section>
</div>
@endsection
