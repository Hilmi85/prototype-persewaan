@extends('admin.layouts.master')
@section('title', 'Data Order')

@section('content')
@php
    $whatsappService = app(\App\Services\WhatsappMessageService::class);
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Data Order</h3>
            <p class="text-muted mb-0">
                Kelola seluruh pesanan customer pada sistem persewaan baju adat dan jasa rias.
            </p>
        </div>

        @if(Route::has('orders.expirePending'))
            <form action="{{ route('orders.expirePending') }}"
                  method="POST"
                  onsubmit="return confirm('Jalankan auto expire untuk semua order QRIS pending yang sudah melewati batas pembayaran?')">
                @csrf

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-clock-history me-1"></i>
                    Expire QRIS Pending
                    @if(($expiredPendingCount ?? 0) > 0)
                        <span class="badge bg-light text-danger ms-1">{{ $expiredPendingCount }}</span>
                    @endif
                </button>
            </form>
        @endif
    </div>
</div>

<div class="page-content">
    <section class="section">
        @if(($expiredPendingCount ?? 0) > 0)
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Ada <strong>{{ $expiredPendingCount }}</strong> order QRIS pending yang sudah melewati batas pembayaran.
                Klik tombol <strong>Expire QRIS Pending</strong> untuk membatalkan order dan booking terkait.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Order</h4>
            </div>

            <div class="card-body">
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

                <div class="alert alert-light border">
                    <strong>Info WhatsApp:</strong>
                    Tombol WhatsApp akan membuka chat customer dengan pesan otomatis sesuai data order.
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Order</th>
                                <th>Customer</th>
                                <th>Jenis Acara</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Status Order</th>
                                <th>Expired At</th>
                                <th>Tanggal</th>
                                <th style="width: 210px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $index => $order)
                                @php
                                    $payment = $order->payments->first();

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

                                    $canExpire = $order->status === 'pending'
                                        && $payment
                                        && $payment->method === 'qris'
                                        && $payment->payment_status === 'pending'
                                        && $payment->expired_at
                                        && $payment->expired_at->lte(now());

                                    $generalWaUrl = $whatsappService->customerGeneralOrder($order);
                                    $paymentWaUrl = $whatsappService->customerPaymentInstruction($order);
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="fw-semibold">
                                        {{ $order->order_code }}
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $order->user->fullname ?? '-' }}</div>
                                        <small class="text-muted">{{ $order->user->phone ?? '-' }}</small>
                                    </td>

                                    <td>{{ $order->jenis_acara ?? '-' }}</td>

                                    <td>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</td>

                                    <td>
                                        @if($payment)
                                            <span class="badge bg-{{ $paymentColor }}">
                                                {{ strtoupper($payment->method ?? '-') }} • {{ ucfirst($payment->payment_status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Belum Ada</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($payment && $payment->expired_at)
                                            <div>{{ $payment->expired_at->format('d-m-Y H:i') }}</div>

                                            @if($canExpire)
                                                <small class="text-danger fw-semibold">
                                                    Sudah lewat
                                                </small>
                                            @elseif($payment->payment_status === 'pending')
                                                <small class="text-muted">
                                                    Belum lewat
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>

                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @if($generalWaUrl)
                                                <a href="{{ $generalWaUrl }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-success"
                                                   title="Hubungi Customer">
                                                    <i class="bi bi-whatsapp"></i>
                                                </a>
                                            @endif

                                            @if($paymentWaUrl)
                                                <a href="{{ $paymentWaUrl }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="Kirim Instruksi Pembayaran">
                                                    <i class="bi bi-send"></i>
                                                </a>
                                            @endif

                                            @if($canExpire && Route::has('orders.expireSingle'))
                                                <form action="{{ route('orders.expireSingle', $order->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Expire order ini sekarang? Order dan booking terkait akan dibatalkan.')">
                                                    @csrf

                                                    <button type="submit" class="btn btn-sm btn-danger" title="Expire Order">
                                                        <i class="bi bi-clock-history"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        Belum ada data order.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-light border mt-4 mb-0">
                    <strong>Keterangan tombol:</strong>
                    <ul class="mb-0 mt-2">
                        <li><span class="badge bg-primary"><i class="bi bi-eye"></i></span> melihat detail order.</li>
                        <li><span class="badge bg-success"><i class="bi bi-whatsapp"></i></span> menghubungi customer dengan ringkasan order.</li>
                        <li><span class="badge bg-light text-success border"><i class="bi bi-send"></i></span> mengirim instruksi pembayaran.</li>
                        <li><span class="badge bg-danger"><i class="bi bi-clock-history"></i></span> expire order QRIS pending yang sudah lewat batas waktu.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
