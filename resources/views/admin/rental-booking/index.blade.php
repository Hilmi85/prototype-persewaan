@extends('admin.layouts.master')
@section('title', 'Rental Booking')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Booking</h3>
            <p class="text-muted mb-0">Kelola booking penyewaan dan jadwal layanan.</p>
        </div>
        <a href="{{ route('rental-bookings.create') }}" class="btn btn-primary">Tambah Booking</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Daftar Booking</h4></div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
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
                    <th>Status</th>
                    <th style="width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $index => $booking)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $booking->booking_code }}</td>
                        <td>{{ $booking->order->order_code ?? '-' }}</td>
                        <td>{{ $booking->order->user->fullname ?? '-' }}</td>
                        <td>{{ $booking->event_type ?? '-' }}</td>
                        <td>{{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $booking->rental_start ? \Carbon\Carbon::parse($booking->rental_start)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $booking->rental_end ? \Carbon\Carbon::parse($booking->rental_end)->format('d-m-Y') : '-' }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($booking->booking_status) }}</span></td>
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
                    <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data booking.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</section>
</div>
@endsection
