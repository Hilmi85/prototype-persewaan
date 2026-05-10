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

    $shouldCheckPaymentStatus = $payment && $payment->payment_status !== 'paid';

    $whatsappService = app(\App\Services\WhatsappMessageService::class);
    $adminWhatsappUrl = $whatsappService->customerAskAdminFromOrder($order);

    $termsSnapshot = app(\App\Services\RentalTermsService::class)->normalizeSnapshot($order->terms_snapshot);
    $acceptedTerms = $termsSnapshot['rules'] ?? [];
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Quin Salon • Pesanan Berhasil
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    Terima Kasih
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Pesanan Anda sudah berhasil dibuat. Silakan cek detail pesanan dan selesaikan pembayaran
                    apabila memilih metode QRIS Midtrans.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#checkout-success-content" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Pesanan
                    </a>

                    <a href="{{ route('order.track.index') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-magnifying-glass me-2"></i>Cek Status Pesanan
                    </a>

                    @if($adminWhatsappUrl)
                        <a href="{{ $adminWhatsappUrl }}" target="_blank" class="btn btn-outline-light rounded-pill px-4 py-3">
                            <i class="fa-brands fa-whatsapp me-2"></i>Chat Admin
                        </a>
                    @endif

                    <a href="{{ route('catalog') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-shirt me-2"></i>Lihat Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="checkout-success-content" class="container-fluid py-5 bg-cream">
    <div class="container">
        @if(session('success'))
            <div id="auto-payment-success-alert" class="alert alert-success rounded-4 shadow-sm mb-4">
                <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
            </div>
        @else
            <div id="auto-payment-success-alert" class="alert alert-success rounded-4 shadow-sm mb-4 d-none">
                <strong>Pembayaran berhasil!</strong>
                Status pembayaran sudah dikonfirmasi. Anda sekarang bisa mengunduh atau mencetak struk transaksi.
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger rounded-4 shadow-sm mb-4">
                <i class="fa fa-circle-exclamation me-2"></i>{{ session('error') }}
            </div>
        @endif

        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                            <div>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                    Informasi Order
                                </span>

                                <small class="text-muted d-block mb-1">
                                    Kode Order
                                </small>

                                <h3 class="fw-bold text-dark mb-0">
                                    {{ $order->order_code }}
                                </h3>
                            </div>

                            <div class="text-md-end">
                                <small class="text-muted d-block mb-1">
                                    Status Pembayaran
                                </small>

                                <span id="payment-status-badge" class="badge bg-{{ $statusClass }} px-3 py-2 rounded-pill">
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
                                        <td class="table-label-wide">Nama Customer</td>
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
                                        <td class="fw-semibold text-uppercase">
                                            {{ $payment->method ?? $order->payment_method ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Kode Payment</td>
                                        <td class="fw-semibold">
                                            {{ $payment->payment_code ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Status Order</td>
                                        <td class="fw-semibold text-capitalize">
                                            {{ str_replace('_', ' ', $order->status) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tanggal Order</td>
                                        <td class="fw-semibold">
                                            {{ $order->created_at->format('d-m-Y H:i') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($isQrisPending)
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                <div>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                        QRIS Midtrans
                                    </span>

                                    <h4 class="fw-bold text-dark mb-1">
                                        Pembayaran QRIS Midtrans
                                    </h4>

                                    <p class="text-muted mb-0">
                                        Scan barcode QRIS yang muncul di bawah ini menggunakan aplikasi pembayaran
                                        yang mendukung QRIS.
                                    </p>
                                </div>

                                <button type="button" id="reload-qris" class="btn btn-dark rounded-pill px-4 py-2">
                                    <i class="fa fa-rotate me-2"></i>Tampilkan Ulang
                                </button>
                            </div>

                            <div id="snap-container" class="snap-box rounded-4 p-2"></div>

                            @if($payment->redirect_url)
                                <div class="text-center mt-3">
                                    <a href="{{ $payment->redirect_url }}"
                                       target="_blank"
                                       class="btn btn-outline-dark rounded-pill px-4">
                                        Buka Halaman Pembayaran Midtrans
                                    </a>
                                </div>
                            @endif

                            <div class="alert alert-warning rounded-4 mt-4 mb-0">
                                <strong>Catatan:</strong>
                                <div class="small mt-1">
                                    Jika pembayaran sudah berhasil tetapi status belum berubah, tunggu beberapa saat.
                                    Halaman ini akan mengecek status pembayaran secara otomatis.
                                </div>
                            </div>
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

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Aturan Sewa
                        </span>

                        <h4 class="fw-bold text-dark mb-2">
                            Persetujuan Aturan Sewa Digital
                        </h4>

                        <p class="text-muted mb-4">
                            Customer telah menyetujui aturan sewa berikut saat membuat pesanan.
                        </p>

                        <div class="alert alert-success rounded-4">
                            <i class="fa fa-circle-check me-2"></i>
                            Disetujui pada:
                            <strong>
                                {{ $order->terms_accepted_at ? $order->terms_accepted_at->format('d-m-Y H:i') : $order->created_at->format('d-m-Y H:i') }}
                            </strong>
                        </div>

                        <ol class="text-muted mb-0">
                            @foreach($acceptedTerms as $term)
                                <li class="mb-2">
                                    <strong class="text-dark">{{ $term['title'] }}</strong><br>
                                    <span>{{ $term['description'] }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Detail Pesanan
                        </span>

                        <h4 class="fw-bold text-dark mb-4">
                            Rincian Item dan Paket
                        </h4>

                        @if($order->orderBundles->count())
                            <h5 class="fw-bold text-dark mb-3">
                                Paket Bundling
                            </h5>

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
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
                                                    <div class="fw-semibold text-dark">
                                                        {{ $orderBundle->bundle->bundle_name ?? '-' }}
                                                    </div>

                                                    @if($orderBundle->bundle && $orderBundle->bundle->bundleItems->count())
                                                        <small class="text-muted">
                                                            {{ $orderBundle->bundle->bundleItems->map(fn ($bundleItem) => $bundleItem->item->name ?? null)->filter()->implode(', ') }}
                                                        </small>
                                                    @endif
                                                </td>

                                                <td>{{ $orderBundle->quantity }}</td>

                                                <td>
                                                    Rp{{ number_format($orderBundle->price, 0, ',', '.') }}
                                                </td>

                                                <td>
                                                    Rp{{ number_format($orderBundle->total_price, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($order->orderItems->count())
                            <h5 class="fw-bold text-dark mb-3">
                                {{ $order->orderBundles->count() ? 'Item / Varian yang Dipilih' : 'Item Pesanan' }}
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
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
                                                    <div class="fw-semibold text-dark">
                                                        {{ $orderItem->item->name ?? '-' }}
                                                    </div>

                                                    <small class="text-muted">
                                                        {{ $orderItem->item->category->cat_name ?? '-' }}
                                                    </small>
                                                </td>

                                                <td>
                                                    @forelse($orderItem->orderItemVariants as $orderItemVariant)
                                                        <span class="badge bg-light text-dark border rounded-pill mb-1">
                                                            {{ $orderItemVariant->itemVariant->size ?? '-' }}

                                                            @if($orderItemVariant->itemVariant?->color)
                                                                / {{ $orderItemVariant->itemVariant->color }}
                                                            @endif
                                                        </span>
                                                    @empty
                                                        <span class="text-muted">
                                                            Tanpa varian
                                                        </span>
                                                    @endforelse
                                                </td>

                                                <td>{{ $orderItem->quantity }}</td>

                                                <td>
                                                    @if($orderItem->price > 0)
                                                        Rp{{ number_format($orderItem->price, 0, ',', '.') }}
                                                    @else
                                                        <span class="text-muted">
                                                            Termasuk paket
                                                        </span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($orderItem->total_price > 0)
                                                        Rp{{ number_format($orderItem->total_price, 0, ',', '.') }}
                                                    @else
                                                        <span class="text-muted">
                                                            Termasuk paket
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                @if($order->rentalBookings->count())
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-lg-5">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                Data Booking
                            </span>

                            <h4 class="fw-bold text-dark mb-4">
                                Jadwal Booking
                            </h4>

                            @foreach($order->rentalBookings as $booking)
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered align-middle mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="table-label-wide">Kode Booking</td>
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
                                                <td class="fw-semibold text-capitalize">
                                                    {{ str_replace('_', ' ', $booking->booking_status) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Ringkasan Pembayaran
                        </span>

                        <h4 class="fw-bold text-dark mb-4">
                            Total Pembayaran
                        </h4>

                        <div class="border border-warning rounded-4 bg-light p-3 mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <strong class="text-dark">
                                    Rp{{ number_format($order->subtotal, 0, ',', '.') }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Pajak</span>
                                <strong class="text-dark">
                                    Rp{{ number_format($order->tax, 0, ',', '.') }}
                                </strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-dark">Total</strong>
                                <strong class="fs-4 text-dark">
                                    Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>

                        <div class="alert alert-warning rounded-4 mb-0">
                            <small>
                                Struk dapat dicetak setelah pembayaran dinyatakan berhasil.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Aksi
                        </span>

                        <div class="d-grid gap-2">
                            @if($adminWhatsappUrl)
                                <a href="{{ $adminWhatsappUrl }}" target="_blank" class="btn btn-success rounded-pill py-3">
                                    <i class="fa-brands fa-whatsapp me-2"></i>Chat Admin
                                </a>
                            @endif
                            <a id="receipt-button"
                                href="{{ $receiptUrl }}"
                                target="_blank"
                                class="btn btn-success rounded-pill py-3 {{ ($payment && $payment->payment_status === 'paid') ? '' : 'disabled' }}">
                                <i class="fa fa-file-invoice me-2"></i>Unduh / Cetak Struk
                            </a>

                            <a href="{{ route('catalog') }}" class="btn btn-outline-dark rounded-pill py-3">
                                <i class="fa fa-shirt me-2"></i>Lihat Katalog Lagi
                            </a>

                            <a href="{{ route('recommendation.index') }}" class="btn btn-outline-dark rounded-pill py-3">
                                <i class="fa fa-gift me-2"></i>Coba Rekomendasi Paket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    @if($isQrisPending)
        <script type="text/javascript" src="{{ $snapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if($isQrisPending)
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
                        uiMode: 'qr',

                        onSuccess: function () {
                            checkPaymentStatus();
                        },

                        onPending: function () {},

                        onError: function () {
                            alert('Pembayaran gagal diproses. Silakan coba tampilkan ulang QRIS.');
                        },

                        onClose: function () {}
                    });
                }

                renderQris();

                if (reloadButton) {
                    reloadButton.addEventListener('click', renderQris);
                }
            @endif

            const shouldCheckPaymentStatus = @json($shouldCheckPaymentStatus);
            const statusUrl = @json(route('checkout.payment.status', $order->order_code));
            const statusBadge = document.getElementById('payment-status-badge');
            const statusUpdated = document.getElementById('payment-status-updated');
            const successAlert = document.getElementById('auto-payment-success-alert');
            const receiptButton = document.getElementById('receipt-button');
            const snapContainer = document.getElementById('snap-container');

            let alreadyPaid = @json($payment && $payment->payment_status === 'paid');
            let statusInterval = null;

            function updatePaymentUI(data) {
                if (!statusBadge) {
                    return;
                }

                statusBadge.className = 'badge bg-' + data.payment_status_class + ' px-3 py-2 rounded-pill';
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

                    if (statusInterval) {
                        clearInterval(statusInterval);
                    }
                }
            }

            function checkPaymentStatus() {
                if (!shouldCheckPaymentStatus || alreadyPaid) {
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
                    .catch(function () {});
            }

            window.checkPaymentStatus = checkPaymentStatus;

            if (shouldCheckPaymentStatus && !alreadyPaid) {
                checkPaymentStatus();
                statusInterval = setInterval(checkPaymentStatus, 5000);
            }
        });
    </script>
@endsection
