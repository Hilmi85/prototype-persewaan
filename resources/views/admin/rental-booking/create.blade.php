@extends('admin.layouts.master')
@section('title', 'Tambah Rental Booking')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Tambah Booking</h3>
            <p class="text-muted mb-0">Tambahkan booking baru untuk order customer.</p>
        </div>
        <a href="{{ route('rental-bookings.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Form Tambah Booking</h4></div>
<div class="card-body">
<form action="{{ route('rental-bookings.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Order</label>
            <select name="order_id" class="form-select" required>
                <option value="">Pilih Order</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">{{ $order->order_code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Booking</label>
            <input type="text" name="booking_code" class="form-control" value="{{ old('booking_code') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Event Type</label>
            <input type="text" name="event_type" class="form-control" value="{{ old('event_type') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Gender</label>
            <input type="text" name="gender" class="form-control" value="{{ old('gender') }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Mulai Sewa</label>
            <input type="date" name="rental_start" class="form-control" value="{{ old('rental_start') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Selesai Sewa</label>
            <input type="date" name="rental_end" class="form-control" value="{{ old('rental_end') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Tanggal Acara</label>
            <input type="date" name="event_date" class="form-control" value="{{ old('event_date') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Fitting</label>
            <input type="date" name="fitting_date" class="form-control" value="{{ old('fitting_date') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Rias</label>
            <input type="date" name="makeup_date" class="form-control" value="{{ old('makeup_date') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Metode Pengambilan</label>
            <input type="text" name="pickup_method" class="form-control" value="{{ old('pickup_method') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Status Booking</label>
            <select name="booking_status" class="form-select" required>
                <option value="pending">Pending</option>
                <option value="scheduled">Scheduled</option>
                <option value="rescheduled">Rescheduled</option>
                <option value="done">Done</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('rental-bookings.index') }}" class="btn btn-light-secondary">Batal</a>
    </div>
</form>
</div>
</div>
</section>
</div>
@endsection
