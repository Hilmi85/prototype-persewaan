@extends('admin.layouts.master')
@section('title', 'Edit Rental Booking')

@section('content')
@php
    $statusOptions = [
        'pending' => 'Pending',
        'scheduled' => 'Scheduled',
        'rescheduled' => 'Rescheduled',
        'picked_up' => 'Barang Diambil',
        'done' => 'Done',
        'cancelled' => 'Cancelled',
        'returned' => 'Returned',
    ];

    $selectedOrderId = old('order_id', $rentalBooking->order_id);
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Edit Booking</h3>
            <p class="text-muted mb-0">
                Perbarui status booking tanpa menghilangkan data tanggal dari customer.
            </p>
        </div>

        <a href="{{ route('rental-bookings.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Data belum valid.</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
<div class="card-header">
    <h4 class="card-title">Form Edit Booking</h4>
</div>

<div class="card-body">
<form action="{{ route('rental-bookings.update', $rentalBooking->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="alert alert-light border">
        <strong>Catatan:</strong>
        Data tanggal di bawah berasal dari input customer saat checkout.
        Admin tetap bisa mengubahnya, tetapi data tidak akan hilang saat hanya mengubah status booking.
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Order <span class="text-danger">*</span></label>
            <select name="order_id" class="form-select" required>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ $selectedOrderId == $order->id ? 'selected' : '' }}>
                        {{ $order->order_code }}
                        @if($order->user)
                            - {{ $order->user->fullname }}
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Booking</label>
            <input type="text"
                   name="booking_code"
                   class="form-control"
                   value="{{ old('booking_code', $rentalBooking->booking_code) }}"
                   readonly>
            <small class="text-muted">Kode booking dibuat otomatis oleh sistem.</small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Jenis Acara</label>
            <input type="text"
                   name="event_type"
                   class="form-control"
                   value="{{ old('event_type', $rentalBooking->event_type) }}"
                   placeholder="Contoh: Pernikahan, Wisuda, Lamaran">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option value="">Pilih Gender</option>
                <option value="Laki-laki" {{ old('gender', $rentalBooking->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('gender', $rentalBooking->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                <option value="Unisex" {{ old('gender', $rentalBooking->gender) == 'Unisex' ? 'selected' : '' }}>Unisex</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Tanggal Acara</label>
            <input type="date"
                   name="event_date"
                   class="form-control"
                   value="{{ old('event_date', optional($rentalBooking->event_date)->format('Y-m-d')) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Mulai Sewa</label>
            <input type="date"
                   name="rental_start"
                   class="form-control"
                   value="{{ old('rental_start', optional($rentalBooking->rental_start)->format('Y-m-d')) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Selesai Sewa</label>
            <input type="date"
                   name="rental_end"
                   class="form-control"
                   value="{{ old('rental_end', optional($rentalBooking->rental_end)->format('Y-m-d')) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Tanggal Fitting</label>
            <input type="date"
                   name="fitting_date"
                   class="form-control"
                   value="{{ old('fitting_date', optional($rentalBooking->fitting_date)->format('Y-m-d')) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Tanggal Rias</label>
            <input type="date"
                   name="makeup_date"
                   class="form-control"
                   value="{{ old('makeup_date', optional($rentalBooking->makeup_date)->format('Y-m-d')) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Status Booking <span class="text-danger">*</span></label>
            <select name="booking_status" class="form-select" required>
                @foreach($statusOptions as $value => $label)
                    <option value="{{ $value }}" {{ old('booking_status', $rentalBooking->booking_status) == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Metode Pengambilan</label>
            <input type="text"
                   name="pickup_method"
                   class="form-control"
                   value="{{ old('pickup_method', $rentalBooking->pickup_method) }}"
                   placeholder="Ambil di tempat / diantar / lainnya">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Customer</label>
            <input type="text"
                   class="form-control"
                   value="{{ $rentalBooking->order->user->fullname ?? '-' }}"
                   readonly>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="notes" rows="4" class="form-control">{{ old('notes', $rentalBooking->notes) }}</textarea>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            Update Booking
        </button>

        <a href="{{ route('rental-bookings.index') }}" class="btn btn-light-secondary">
            Batal
        </a>
    </div>
</form>
</div>
</div>
</section>
</div>
@endsection
