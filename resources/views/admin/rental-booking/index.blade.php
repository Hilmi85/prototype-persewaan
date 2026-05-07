@extends('admin.layouts.master')
@section('title', 'Rental Booking')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Booking</h3>
            <p class="text-muted mb-0">
                Kelola booking penyewaan dan jadwal layanan dari customer.
            </p>
        </div>

        <a href="{{ route('rental-bookings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Booking
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header">
    <h4 class="card-title">Daftar Booking</h4>
</div>

<div class="card-body">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-light border">
        <strong>Catatan:</strong>
        Data tanggal acara, mulai sewa, selesai sewa, dan tanggal rias berasal dari form checkout customer.
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Booking</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Event</th>
                    <th>Tanggal Acara</th>
                    <th>Mulai Sewa</th>
                    <th>Selesai Sewa</th>
                    <th>Tanggal Rias</th>
                    <th>Status</th>
                    <th style="width:150px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bookings as $index => $booking)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td class="fw-semibold">
                            {{ $booking->booking_code }}
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $booking->order->order_code ?? '-' }}
                            </div>

                            <small class="text-muted">
                                {{ optional($booking->order?->created_at)->format('d-m-Y H:i') }}
                            </small>
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $booking->order->user->fullname ?? '-' }}
                            </div>

                            <small class="text-muted">
                                {{ $booking->order->user->phone ?? '-' }}
                            </small>
                        </td>

                        <td>{{ $booking->event_type ?? '-' }}</td>

                        <td>
                            {{ $booking->event_date ? $booking->event_date->format('d-m-Y') : '-' }}
                        </td>

                        <td>
                            {{ $booking->rental_start ? $booking->rental_start->format('d-m-Y') : '-' }}
                        </td>

                        <td>
                            {{ $booking->rental_end ? $booking->rental_end->format('d-m-Y') : '-' }}
                        </td>

                        <td>
                            {{ $booking->makeup_date ? $booking->makeup_date->format('d-m-Y') : '-' }}
                        </td>

                        <td>
                            @if($booking->booking_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($booking->booking_status === 'scheduled')
                                <span class="badge bg-primary">Scheduled</span>
                            @elseif($booking->booking_status === 'rescheduled')
                                <span class="badge bg-info">Rescheduled</span>
                            @elseif($booking->booking_status === 'done')
                                <span class="badge bg-success">Done</span>
                            @elseif($booking->booking_status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($booking->booking_status) }}</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('rental-bookings.edit', $booking->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('rental-bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">
                            Belum ada data booking.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</section>
</div>
@endsection
