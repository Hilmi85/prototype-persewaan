@extends('admin.layouts.master')
@section('title', 'Detail Order')

@section('content')
@php
    $whatsappService = app(\App\Services\WhatsappMessageService::class);
    $payment = $order->payments->first();
    $booking = $order->rentalBookings->first();

    $generalWaUrl = $whatsappService->customerGeneralOrder($order);
    $paymentWaUrl = $whatsappService->customerPaymentInstruction($order);

    $canExpire = $order->status === 'pending'
        && $payment
        && $payment->method === 'qris'
        && $payment->payment_status === 'pending'
        && $payment->expired_at
        && $payment->expired_at->lte(now());

    $statusColor = match($order->status) {
        'pending' => 'warning',
        'confirmed' => 'info',
        'booked' => 'primary',
        'in_progress' => 'secondary',
        'completed' => 'success',
        'cancelled' => 'danger',
        'success' => 'success',
        default => 'dark'
    };

    $paymentColor = match($payment->payment_status ?? null) {
        'paid' => 'success',
        'pending' => 'warning',
        'expired' => 'danger',
        'failed' => 'danger',
        'refunded' => 'info',
        default => 'secondary'
    };
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Detail Order</h3>
            <p class="text-muted mb-0">
                Informasi lengkap pesanan customer, bundle, booking, pembayaran, dan tombol WhatsApp otomatis.
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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Catatan</th>
                                <td>{{ $order->note ?? $order->notes ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Tanggal Order</th>
                                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        </table>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @if($generalWaUrl)
                                <a href="{{ $generalWaUrl }}" target="_blank" class="btn btn-success">
                                    <i class="bi bi-whatsapp me-1"></i>Hubungi Customer
                                </a>
                            @endif

                            @if($paymentWaUrl)
                                <a href="{{ $paymentWaUrl }}" target="_blank" class="btn btn-outline-success">
                                    <i class="bi bi-send me-1"></i>Kirim Instruksi Pembayaran
                                </a>
                            @endif
                        </div>
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
                                            <th>Varian</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($order->orderItems as $orderItem)
                                            <tr>
                                                <td>{{ $orderItem->item->name ?? '-' }}</td>

                                                <td>
                                                    @forelse($orderItem->orderItemVariants as $orderItemVariant)
                                                        <span class="badge bg-light text-dark border mb-1">
                                                            {{ $orderItemVariant->itemVariant->size ?? '-' }}
                                                            @if($orderItemVariant->itemVariant?->color)
                                                                / {{ $orderItemVariant->itemVariant->color }}
                                                            @endif
                                                        </span>
                                                    @empty
                                                        <span class="text-muted">-</span>
                                                    @endforelse
                                                </td>

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
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h4 class="card-title text-white mb-0">Aksi WhatsApp</h4>
                    </div>

                    <div class="card-body">
                        <p class="text-muted">
                            Gunakan tombol ini agar admin tidak mengetik pesan manual ke customer.
                        </p>

                        <div class="d-grid gap-2">
                            @if($generalWaUrl)
                                <a href="{{ $generalWaUrl }}" target="_blank" class="btn btn-success">
                                    <i class="bi bi-whatsapp me-1"></i>Hubungi Customer
                                </a>
                            @endif

                            @if($paymentWaUrl)
                                <a href="{{ $paymentWaUrl }}" target="_blank" class="btn btn-outline-success">
                                    <i class="bi bi-send me-1"></i>Kirim Instruksi Pembayaran
                                </a>
                            @endif

                            @if(!$generalWaUrl && !$paymentWaUrl)
                                <div class="alert alert-warning mb-0">
                                    Nomor WhatsApp customer belum tersedia.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Ringkasan Pembayaran</h4>
                    </div>

                    <div class="card-body">
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
                                        <span class="badge bg-{{ $paymentColor }}">
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

                                <tr>
                                    <th>Expired At</th>
                                    <td>
                                        {{ $payment->expired_at ? \Carbon\Carbon::parse($payment->expired_at)->format('d-m-Y H:i') : '-' }}

                                        @if($canExpire)
                                            <div class="small text-danger fw-semibold mt-1">
                                                Pembayaran sudah melewati batas waktu.
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            @if($canExpire && Route::has('orders.expireSingle'))
                                <form action="{{ route('orders.expireSingle', $order->id) }}"
                                      method="POST"
                                      class="mt-3"
                                      onsubmit="return confirm('Expire order ini sekarang? Order dan booking terkait akan dibatalkan.')">
                                    @csrf

                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-clock-history me-1"></i>Expire Order Ini
                                    </button>
                                </form>
                            @endif
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
