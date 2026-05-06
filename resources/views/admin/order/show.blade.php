@extends('admin.layouts.master')
@section('title', 'Detail Order')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Detail Order</h3>
            <p class="text-muted mb-0">
                Informasi lengkap pesanan customer, bundle, booking, dan pembayaran.
            </p>
        </div>
        <div>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Informasi Order</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="220">Kode Order</th>
                                <td>{{ $order->order_code }}</td>
                            </tr>
                            <tr>
                                <th>Nama Customer</th>
                                <td>{{ $order->user->fullname ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No. WhatsApp</th>
                                <td>{{ $order->user->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $order->user->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Acara</th>
                                <td>{{ $order->jenis_acara ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kategori Adat</th>
                                <td>{{ $order->kategori_adat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ $order->gender ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Butuh Rias</th>
                                <td>{{ $order->butuh_rias ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <th>Budget</th>
                                <td>{{ $order->budget ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td>{{ ucfirst($order->payment_method ?? '-') }}</td>
                            </tr>
                            <tr>
                                <th>Status Order</th>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $order->note ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Order</th>
                                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Bundle yang Dipesan</h4>
                    </div>
                    <div class="card-body">
                        @if($order->orderBundles->count())
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Bundle</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderBundles as $orderBundle)
                                            <tr>
                                                <td>{{ $orderBundle->bundle->bundle_name ?? '-' }}</td>
                                                <td>{{ $orderBundle->quantity }}</td>
                                                <td>Rp{{ number_format($orderBundle->price, 0, ',', '.') }}</td>
                                                <td>Rp{{ number_format($orderBundle->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">Belum ada bundle pada order ini.</p>
                        @endif
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Item Tambahan</h4>
                    </div>
                    <div class="card-body">
                        @if($order->orderItems->count())
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $orderItem)
                                            <tr>
                                                <td>{{ $orderItem->item->name ?? '-' }}</td>
                                                <td>{{ $orderItem->quantity }}</td>
                                                <td>Rp{{ number_format($orderItem->price, 0, ',', '.') }}</td>
                                                <td>Rp{{ number_format($orderItem->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">Tidak ada item tambahan pada order ini.</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Status Order</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-8 mb-3">
                                    <label for="status" class="form-label">Status Order</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="booked" {{ $order->status == 'booked' ? 'selected' : '' }}>Booked</option>
                                        <option value="in_progress" {{ $order->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-save me-1"></i>Update Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Ringkasan Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $payment = $order->payments->first();
                        @endphp

                        @if($payment)
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th>Kode Payment</th>
                                    <td>{{ $payment->payment_code }}</td>
                                </tr>
                                <tr>
                                    <th>Metode</th>
                                    <td>{{ ucfirst($payment->method ?? '-') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ $payment->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah</th>
                                    <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Paid At</th>
                                    <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d-m-Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                        @else
                            <p class="text-muted mb-0">Belum ada data pembayaran.</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Booking</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $booking = $order->rentalBookings->first();
                        @endphp

                        @if($booking)
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th>Kode Booking</th>
                                    <td>{{ $booking->booking_code }}</td>
                                </tr>
                                <tr>
                                    <th>Event Type</th>
                                    <td>{{ $booking->event_type ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Acara</th>
                                    <td>{{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('d-m-Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Mulai Sewa</th>
                                    <td>{{ $booking->rental_start ? \Carbon\Carbon::parse($booking->rental_start)->format('d-m-Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Selesai Sewa</th>
                                    <td>{{ $booking->rental_end ? \Carbon\Carbon::parse($booking->rental_end)->format('d-m-Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Rias</th>
                                    <td>{{ $booking->makeup_date ? \Carbon\Carbon::parse($booking->makeup_date)->format('d-m-Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status Booking</th>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($booking->booking_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <p class="text-muted mb-0">Belum ada data booking.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
