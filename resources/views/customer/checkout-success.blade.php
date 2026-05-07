@extends('customer.layouts.master')

@section('title', 'Pesanan Berhasil - Quin Salon')

@section('content')
@php
    $paymentStatus = $payment->payment_status ?? 'pending';

    $statusLabel = match($paymentStatus) {
        'paid' => 'Pembayaran Berhasil',
        'failed' => 'Pembayaran Gagal',
        'expired' => 'Pembayaran Kedaluwarsa',
        'refunded' => 'Pembayaran Direfund',
        default => 'Menunggu Pembayaran',
    };

    $statusClass = match($paymentStatus) {
        'paid' => 'success',
        'failed', 'expired' => 'danger',
        'refunded' => 'info',
        default => 'warning text-dark',
    };

    $isQrisPending = $payment
        && $payment->method === 'qris'
        && $payment->payment_status === 'pending'
        && filled($payment->snap_token);
@endphp

<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.68), rgba(60, 42, 33, 0.68)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <span class="badge rounded-pill px-4 py-2 mb-3"
              style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
            Pesanan Berhasil
        </span>

        <h1 class="display-4 text-white fw-bold">
            Terima Kasih
        </h1>

        <p class="text-white mb-0">
            Pesanan Anda sudah tersimpan. Silakan selesaikan pembayaran jika memilih QRIS.
        </p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        @if(session('success'))
            <div id="auto-payment-success-alert"
                class="alert alert-success rounded-4 shadow-sm mb-4 d-none">
                <strong>Pembayaran berhasil!</strong>
                Status pembayaran sudah dikonfirmasi. Anda sekarang bisa mengunduh atau mencetak struk transaksi.
            </div>
        @endif

        <div class="row g-5">
            <div class="col-lg-8">
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4" style="border: 1px solid #f1e3d3;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                        <div>
                            <small class="text-muted d-block">Kode Order</small>
                            <h3 class="fw-bold mb-0" style="color: #8b5e3c;">
                                {{ $order->order_code }}
                            </h3>
                        </div>

                        <div class="text-md-end">
                            <small class="text-muted d-block">Status Pembayaran</small>
                            <span id="payment-status-badge" class="badge bg-{{ $statusClass }} px-3 py-2">
                                {{ $statusLabel }}
                            </span>

                            <div id="payment-status-updated" class="small text-muted mt-2">
                                Status terakhir dicek saat halaman dibuka.
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td style="width: 220px;">Nama Customer</td>
                                    <td class="fw-semibold">{{ $order->user->fullname ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>No. WhatsApp</td>
                                    <td class="fw-semibold">{{ $order->user->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td class="fw-semibold">{{ $order->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Metode Pembayaran</td>
                                    <td class="fw-semibold text-uppercase">{{ $payment->method ?? $order->payment_method ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kode Payment</td>
                                    <td class="fw-semibold">{{ $payment->payment_code ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Status Order</td>
                                    <td class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $order->status) }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Order</td>
                                    <td class="fw-semibold">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($isQrisPending)
                    <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4" style="border: 1px solid #f1e3d3;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold mb-1" style="color: #8b5e3c;">
                                    Pembayaran QRIS Midtrans
                                </h4>
                                <p class="text-muted mb-0">
                                    Scan barcode QRIS yang muncul di bawah ini menggunakan aplikasi pembayaran yang mendukung QRIS.
                                </p>
                            </div>

                            <button type="button"
                                    id="reload-qris"
                                    class="btn rounded-pill px-4 py-2"
                                    style="background-color: #8b5e3c; color: #fff;">
                                Tampilkan Ulang
                            </button>
                        </div>

                        <div id="snap-container"
                             class="rounded-4 p-2"
                             style="background-color: #fffaf5; border: 1px solid #f1e3d3; min-height: 560px;">
                        </div>

                        @if($payment->redirect_url)
                            <div class="text-center mt-3">
                                <a href="{{ $payment->redirect_url }}"
                                   target="_blank"
                                   class="btn btn-outline-secondary rounded-pill px-4">
                                    Buka Halaman Pembayaran Midtrans
                                </a>
                            </div>
                        @endif

                        <div class="alert alert-light border rounded-4 mt-4 mb-0">
                            <strong>Catatan:</strong>
                            Jika pembayaran sudah berhasil tetapi status belum berubah, tunggu beberapa saat lalu refresh halaman.
                            Status final akan diperbarui melalui notifikasi Midtrans.
                        </div>
                    </div>
                @elseif($payment && $payment->method === 'tunai')
                    <div class="alert alert-info rounded-4 shadow-sm mb-4">
                        <strong>Pembayaran Cash:</strong>
                        Silakan lakukan pembayaran tunai saat konfirmasi dengan admin atau saat pengambilan.
                    </div>
                @elseif($payment && $payment->method === 'qris' && $payment->payment_status === 'paid')
                    <div class="alert alert-success rounded-4 shadow-sm mb-4">
                        <strong>QRIS berhasil dibayar.</strong>
                        Admin akan memproses konfirmasi order Anda.
                    </div>
                @elseif($payment && $payment->method === 'qris')
                    <div class="alert alert-warning rounded-4 shadow-sm mb-4">
                        <strong>QRIS belum tersedia.</strong>
                        Snap token belum terbentuk. Pastikan konfigurasi Midtrans di file .env sudah benar.
                    </div>
                @endif

                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4" style="border: 1px solid #f1e3d3;">
                    <h4 class="fw-bold mb-4" style="color: #8b5e3c;">
                        Detail Pesanan
                    </h4>

                    @if($order->orderBundles->count())
                        <h5 class="fw-bold mb-3">Paket Bundling</h5>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle mb-0">
                                <thead style="background-color: #fff7ef;">
                                    <tr>
                                        <th>Paket</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($order->orderBundles as $orderBundle)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $orderBundle->bundle->bundle_name ?? '-' }}</div>
                                                @if($orderBundle->bundle && $orderBundle->bundle->bundleItems->count())
                                                    <small class="text-muted">
                                                        {{ $orderBundle->bundle->bundleItems->map(fn ($bundleItem) => $bundleItem->item->name ?? null)->filter()->implode(', ') }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>{{ $orderBundle->quantity }}</td>
                                            <td>Rp{{ number_format($orderBundle->price, 0, ',', '.') }}</td>
                                            <td>Rp{{ number_format($orderBundle->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($order->orderItems->count())
                        <h5 class="fw-bold mb-3">
                            {{ $order->orderBundles->count() ? 'Item / Varian yang Dipilih' : 'Item Pesanan' }}
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead style="background-color: #fff7ef;">
                                    <tr>
                                        <th>Item</th>
                                        <th>Varian</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($order->orderItems as $orderItem)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $orderItem->item->name ?? '-' }}</div>
                                                <small class="text-muted">
                                                    {{ $orderItem->item->category->cat_name ?? '-' }}
                                                </small>
                                            </td>

                                            <td>
                                                @forelse($orderItem->orderItemVariants as $orderItemVariant)
                                                    <span class="badge bg-light text-dark border mb-1">
                                                        {{ $orderItemVariant->itemVariant->size ?? '-' }}
                                                        @if($orderItemVariant->itemVariant?->color)
                                                            / {{ $orderItemVariant->itemVariant->color }}
                                                        @endif
                                                    </span>
                                                @empty
                                                    <span class="text-muted">Tanpa varian</span>
                                                @endforelse
                                            </td>

                                            <td>{{ $orderItem->quantity }}</td>

                                            <td>
                                                @if($orderItem->price > 0)
                                                    Rp{{ number_format($orderItem->price, 0, ',', '.') }}
                                                @else
                                                    <span class="text-muted">Termasuk paket</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($orderItem->total_price > 0)
                                                    Rp{{ number_format($orderItem->total_price, 0, ',', '.') }}
                                                @else
                                                    <span class="text-muted">Termasuk paket</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                @if($order->rentalBookings->count())
                    <div class="bg-white rounded-4 shadow-sm p-4 p-md-5" style="border: 1px solid #f1e3d3;">
                        <h4 class="fw-bold mb-4" style="color: #8b5e3c;">
                            Data Booking
                        </h4>

                        @foreach($order->rentalBookings as $booking)
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 220px;">Kode Booking</td>
                                            <td class="fw-semibold">{{ $booking->booking_code }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Acara</td>
                                            <td class="fw-semibold">{{ optional($booking->event_date)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Mulai Sewa</td>
                                            <td class="fw-semibold">{{ optional($booking->rental_start)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Selesai Sewa</td>
                                            <td class="fw-semibold">{{ optional($booking->rental_end)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Rias</td>
                                            <td class="fw-semibold">{{ optional($booking->makeup_date)->format('d-m-Y') ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status Booking</td>
                                            <td class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $booking->booking_status) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4" style="border: 1px solid #f1e3d3;">
                    <h4 class="fw-bold mb-4" style="color: #8b5e3c;">
                        Ringkasan Pembayaran
                    </h4>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Pajak</span>
                        <strong>Rp{{ number_format($order->tax, 0, ',', '.') }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Total</strong>
                        <strong class="fs-4" style="color: #8b5e3c;">
                            Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>

                <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                    <div class="d-grid gap-2">
                        <a id="receipt-button"
                            href="{{ route('checkout.receipt', $order->order_code) }}"
                            target="_blank"
                            class="btn rounded-pill py-3 {{ ($payment && $payment->payment_status === 'paid') ? '' : 'disabled' }}"
                            style="background-color: #198754; color: #fff;">
                            <i class="fa fa-file-invoice me-2"></i>Unduh / Cetak Struk
                        </a>

                        <a href="{{ route('catalog') }}"
                           class="btn btn-outline-secondary rounded-pill py-3">
                            Lihat Katalog Lagi
                        </a>

                        <a href="{{ route('recommendation.index') }}"
                           class="btn btn-outline-secondary rounded-pill py-3">
                            Coba Rekomendasi Paket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($isQrisPending)
    <script type="text/javascript" src="{{ $snapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const snapToken = @json($payment->snap_token);
            const containerId = 'snap-container';
            const reloadButton = document.getElementById('reload-qris');

            function renderQris() {
                const container = document.getElementById(containerId);

                if (!container || !window.snap || !snapToken) {
                    return;
                }

                container.innerHTML = '';

                window.snap.embed(snapToken, {
                    embedId: containerId,

                    /*
                    * Paksa Snap menampilkan QR mode jika memungkinkan.
                    * Cocok untuk desktop/laptop agar barcode QRIS tampil.
                    */
                    uiMode: 'qr',

                    onSuccess: function () {
                        window.location.reload();
                    },

                    onPending: function () {
                        // Status final menunggu webhook Midtrans.
                    },

                    onError: function () {
                        alert('Pembayaran gagal diproses. Silakan coba tampilkan ulang QRIS.');
                    },

                    onClose: function () {
                        // Customer bisa klik tampilkan ulang.
                    }
                });
            }

            renderQris();

            if (reloadButton) {
                reloadButton.addEventListener('click', renderQris);
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const statusUrl = @json(route('checkout.payment.status', $order->order_code));
            const statusBadge = document.getElementById('payment-status-badge');
            const statusUpdated = document.getElementById('payment-status-updated');
            const successAlert = document.getElementById('auto-payment-success-alert');
            const receiptButton = document.getElementById('receipt-button');
            const snapContainer = document.getElementById('snap-container');

            let alreadyPaid = @json($payment && $payment->payment_status === 'paid');

            function updatePaymentUI(data) {
                if (!statusBadge) {
                    return;
                }

                statusBadge.className = 'badge bg-' + data.payment_status_class + ' px-3 py-2';
                statusBadge.textContent = data.payment_status_label;

                if (statusUpdated) {
                    statusUpdated.textContent = 'Status terakhir dicek: ' + data.checked_at;
                }

                if (data.is_paid) {
                    if (successAlert) {
                        successAlert.classList.remove('d-none');
                    }

                    if (receiptButton) {
                        receiptButton.classList.remove('disabled');
                        receiptButton.href = data.receipt_url;
                    }

                    if (snapContainer) {
                        snapContainer.innerHTML =
                            '<div class="alert alert-success rounded-4 m-0">' +
                            '<strong>Pembayaran berhasil.</strong><br>' +
                            'Status pembayaran sudah dikonfirmasi oleh sistem/admin.' +
                            '</div>';
                    }

                    alreadyPaid = true;
                }
            }

            function checkPaymentStatus() {
                if (alreadyPaid) {
                    return;
                }

                fetch(statusUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-store'
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Gagal mengecek status pembayaran.');
                        }

                        return response.json();
                    })
                    .then(updatePaymentUI)
                    .catch(function () {
                        // Diamkan agar tidak mengganggu customer.
                    });
            }

            checkPaymentStatus();
            setInterval(checkPaymentStatus, 5000);
        });
    </script>
@endif
@endsection
