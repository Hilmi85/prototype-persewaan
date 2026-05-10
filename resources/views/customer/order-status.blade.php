@extends('customer.layouts.master')

@section('title', 'Cek Status Pesanan - Quin Salon')

@section('content')
@php
    $paymentStatus = $payment->payment_status ?? null;
    $orderStatus = $order->status ?? null;
    $bookingStatus = $booking->booking_status ?? null;

    $paymentLabels = [
        'pending' => 'Menunggu Pembayaran',
        'paid' => 'Pembayaran Berhasil',
        'failed' => 'Pembayaran Gagal',
        'expired' => 'Pembayaran Kedaluwarsa',
        'refunded' => 'Pembayaran Direfund',
    ];

    $paymentBadges = [
        'pending' => 'warning text-dark',
        'paid' => 'success',
        'failed' => 'danger',
        'expired' => 'danger',
        'refunded' => 'info',
    ];

    $orderLabels = [
        'pending' => 'Menunggu Konfirmasi',
        'confirmed' => 'Terkonfirmasi',
        'booked' => 'Sudah Dibooking',
        'in_progress' => 'Sedang Berjalan',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'success' => 'Berhasil',
    ];

    $orderBadges = [
        'pending' => 'warning text-dark',
        'confirmed' => 'primary',
        'booked' => 'info',
        'in_progress' => 'secondary',
        'completed' => 'success',
        'cancelled' => 'danger',
        'success' => 'success',
    ];

    $bookingLabels = [
        'pending' => 'Menunggu Jadwal',
        'scheduled' => 'Terjadwal',
        'rescheduled' => 'Dijadwalkan Ulang',
        'picked_up' => 'Barang Diambil',
        'done' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'returned' => 'Sudah Dikembalikan',
    ];

    $bookingBadges = [
        'pending' => 'warning text-dark',
        'scheduled' => 'primary',
        'rescheduled' => 'info',
        'picked_up' => 'secondary',
        'done' => 'success',
        'cancelled' => 'danger',
        'returned' => 'success',
    ];

    $paymentLabel = $paymentLabels[$paymentStatus] ?? 'Belum Ada Pembayaran';
    $paymentBadge = $paymentBadges[$paymentStatus] ?? 'secondary';

    $orderLabel = $orderLabels[$orderStatus] ?? ($orderStatus ? ucwords(str_replace('_', ' ', $orderStatus)) : '-');
    $orderBadge = $orderBadges[$orderStatus] ?? 'secondary';

    $bookingLabel = $bookingLabels[$bookingStatus] ?? ($bookingStatus ? ucwords(str_replace('_', ' ', $bookingStatus)) : '-');
    $bookingBadge = $bookingBadges[$bookingStatus] ?? 'secondary';

    $orderedRows = collect();

    if ($order) {
        $orderedRows = $order->orderItems->flatMap(function ($orderItem) {
            if ($orderItem->orderItemVariants->count()) {
                return $orderItem->orderItemVariants->map(function ($orderItemVariant) use ($orderItem) {
                    return [
                        'name' => $orderItem->item->name ?? '-',
                        'category' => $orderItem->item->category->cat_name ?? '-',
                        'variant' => $orderItemVariant->itemVariant,
                        'qty' => $orderItemVariant->qty,
                        'unit_price' => $orderItemVariant->unit_price,
                        'subtotal_price' => $orderItemVariant->subtotal_price,
                    ];
                });
            }

            return collect([[
                'name' => $orderItem->item->name ?? '-',
                'category' => $orderItem->item->category->cat_name ?? '-',
                'variant' => null,
                'qty' => $orderItem->quantity,
                'unit_price' => $orderItem->price,
                'subtotal_price' => $orderItem->total_price,
            ]]);
        });

        foreach ($order->orderBundles as $orderBundle) {
            $orderedRows->push([
                'name' => $orderBundle->bundle->bundle_name ?? 'Paket',
                'category' => 'Paket Rekomendasi',
                'variant' => null,
                'qty' => $orderBundle->quantity,
                'unit_price' => $orderBundle->price,
                'subtotal_price' => $orderBundle->total_price,
            ]);
        }
    }

    $adminWhatsappUrl = null;

    if ($contact) {
        $phoneDigits = preg_replace('/\D+/', '', $contact->whatsapp_number ?? '');

        if ($phoneDigits) {
            if (str_starts_with($phoneDigits, '0')) {
                $phoneDigits = '62' . substr($phoneDigits, 1);
            }

            $message = $order
                ? 'Halo Admin Quin Salon, saya ingin menanyakan pesanan dengan kode ' . $order->order_code . '.'
                : 'Halo Admin Quin Salon, saya ingin menanyakan pesanan saya.';

            $adminWhatsappUrl = 'https://wa.me/' . $phoneDigits . '?text=' . urlencode($message);
        } elseif ($contact->whatsapp_url) {
            $adminWhatsappUrl = $contact->whatsapp_url;
        }
    }

    $whatsappService = app(\App\Services\WhatsappMessageService::class);
    $adminWhatsappUrl = $order
        ? $whatsappService->customerAskAdminFromOrder($order)
        : $whatsappService->customerAskAdminGeneric();
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Quin Salon • Status Pesanan
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    Cek Status Pesanan
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Masukkan kode order dan nomor WhatsApp/HP yang digunakan saat checkout untuk melihat status pesanan Anda.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#order-status-content" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-search me-2"></i>Cek Pesanan
                    </a>

                    <a href="{{ route('catalog') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-shirt me-2"></i>Lihat Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="order-status-content" class="container-fluid py-5 bg-cream">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4">
                <strong>Pesanan tidak ditemukan.</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Form Pencarian
                        </span>

                        <h4 class="fw-bold text-dark mb-3">
                            Cari Pesanan Anda
                        </h4>

                        <p class="text-muted mb-4">
                            Gunakan kode order dan nomor WhatsApp/HP yang sama seperti saat checkout.
                        </p>

                        <form action="{{ route('order.track.check') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Kode Order <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="order_code"
                                       value="{{ old('order_code', $order->order_code ?? '') }}"
                                       class="form-control rounded-3 text-uppercase"
                                       placeholder="Contoh: ORD-20260510-ABC123"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    No. WhatsApp/HP <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       class="form-control rounded-3"
                                       placeholder="Contoh: 08123456789"
                                       required>
                                <small class="text-muted">
                                    Nomor ini digunakan untuk memastikan pesanan benar milik Anda.
                                </small>
                            </div>

                            <button type="submit" class="btn btn-dark rounded-pill w-100 py-3">
                                <i class="fa fa-magnifying-glass me-2"></i>Cek Status Pesanan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Bantuan
                        </span>

                        <p class="text-muted mb-3">
                            Kode order bisa dilihat pada halaman checkout berhasil atau struk pesanan.
                        </p>

                        @if($adminWhatsappUrl)
                            <a href="{{ $adminWhatsappUrl }}"
                               target="_blank"
                               class="btn btn-outline-dark rounded-pill w-100">
                                <i class="fa-brands fa-whatsapp me-2"></i>Hubungi Admin
                            </a>
                        @else
                            <a href="#footer" class="btn btn-outline-dark rounded-pill w-100">
                                <i class="fa fa-phone me-2"></i>Lihat Kontak Admin
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @if($order)
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                                <div>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                        Detail Status
                                    </span>

                                    <small class="text-muted d-block mb-1">
                                        Kode Order
                                    </small>

                                    <h3 class="fw-bold text-dark mb-0">
                                        {{ $order->order_code }}
                                    </h3>
                                </div>

                                <div class="text-lg-end">
                                    <small class="text-muted d-block mb-1">
                                        Tanggal Order
                                    </small>

                                    <strong class="text-dark">
                                        {{ optional($order->created_at)->format('d-m-Y H:i') }}
                                    </strong>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 h-100 bg-light">
                                        <small class="text-muted d-block mb-1">
                                            Status Order
                                        </small>
                                        <span id="tracking-order-status" class="badge bg-{{ $orderBadge }} rounded-pill px-3 py-2">
                                            {{ $orderLabel }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 h-100 bg-light">
                                        <small class="text-muted d-block mb-1">
                                            Status Pembayaran
                                        </small>
                                        <span id="tracking-payment-status" class="badge bg-{{ $paymentBadge }} rounded-pill px-3 py-2">
                                            {{ $paymentLabel }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 h-100 bg-light">
                                        <small class="text-muted d-block mb-1">
                                            Status Booking
                                        </small>
                                        <span class="badge bg-{{ $bookingBadge }} rounded-pill px-3 py-2">
                                            {{ $bookingLabel }}
                                        </span>
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
                                            <td>Kode Pembayaran</td>
                                            <td class="fw-semibold">
                                                {{ $payment->payment_code ?? '-' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Total Pembayaran</td>
                                            <td class="fw-bold text-dark">
                                                Rp{{ number_format($order->grand_total ?? 0, 0, ',', '.') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Tanggal Sewa</td>
                                            <td class="fw-semibold">
                                                @if($booking && $booking->rental_start && $booking->rental_end)
                                                    {{ $booking->rental_start->format('d-m-Y') }}
                                                    sampai
                                                    {{ $booking->rental_end->format('d-m-Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Tanggal Acara</td>
                                            <td class="fw-semibold">
                                                {{ $booking && $booking->event_date ? $booking->event_date->format('d-m-Y') : '-' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Catatan</td>
                                            <td>{{ $order->note ?? $order->notes ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div id="tracking-status-updated" class="small text-muted mt-3">
                                Status terakhir dicek saat halaman dibuka.
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                <div>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                        Item Pesanan
                                    </span>

                                    <h4 class="fw-bold text-dark mb-1">
                                        Produk yang Disewa
                                    </h4>

                                    <p class="text-muted mb-0">
                                        Berikut item, varian, jumlah, dan subtotal pesanan Anda.
                                    </p>
                                </div>

                                <span class="badge bg-dark rounded-pill px-3 py-2">
                                    {{ $orderedRows->count() }} item
                                </span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Varian</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($orderedRows as $row)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold text-dark">
                                                        {{ $row['name'] }}
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ $row['category'] }}
                                                    </small>
                                                </td>

                                                <td>
                                                    @if($row['variant'])
                                                        {{ $row['variant']->size ?? '-' }}
                                                        @if($row['variant']->color)
                                                            / {{ $row['variant']->color }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>{{ $row['qty'] }}</td>

                                                <td>
                                                    Rp{{ number_format($row['unit_price'] ?? 0, 0, ',', '.') }}
                                                </td>

                                                <td>
                                                    <strong>
                                                        Rp{{ number_format($row['subtotal_price'] ?? 0, 0, ',', '.') }}
                                                    </strong>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    Tidak ada item pesanan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-lg-5">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                Aksi Pesanan
                            </span>

                            <h4 class="fw-bold text-dark mb-3">
                                Langkah Berikutnya
                            </h4>

                            @if($payment && $payment->method === 'qris' && $payment->payment_status === 'pending')
                                <div class="alert alert-warning rounded-4">
                                    <strong>Pembayaran QRIS belum selesai.</strong>
                                    <div class="small mt-1">
                                        Silakan lanjutkan pembayaran. Jika sudah membayar, tunggu beberapa saat lalu klik cek ulang status.
                                    </div>
                                </div>
                            @elseif($payment && $payment->payment_status === 'paid')
                                <div class="alert alert-success rounded-4">
                                    <strong>Pembayaran sudah berhasil.</strong>
                                    <div class="small mt-1">
                                        Admin akan memproses jadwal sewa sesuai data pesanan Anda.
                                    </div>
                                </div>
                            @elseif($payment && $payment->method === 'tunai')
                                <div class="alert alert-info rounded-4">
                                    <strong>Pembayaran tunai.</strong>
                                    <div class="small mt-1">
                                        Silakan konfirmasi dengan admin untuk jadwal pengambilan dan pembayaran.
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex flex-wrap gap-2">
                                @if($payment && $payment->method === 'qris' && $payment->redirect_url && $payment->payment_status === 'pending')
                                    <a href="{{ $payment->redirect_url }}"
                                       target="_blank"
                                       class="btn btn-dark rounded-pill px-4">
                                        <i class="fa fa-qrcode me-2"></i>Lanjut Bayar QRIS
                                    </a>
                                @endif

                                <button type="button"
                                        id="refreshTrackingStatus"
                                        class="btn btn-outline-dark rounded-pill px-4"
                                        data-status-url="{{ route('checkout.payment.status', $order->order_code) }}">
                                    <i class="fa fa-rotate me-2"></i>Cek Ulang Status
                                </button>

                                <a href="{{ route('checkout.receipt', $order->order_code) }}"
                                   target="_blank"
                                   class="btn btn-outline-dark rounded-pill px-4">
                                    <i class="fa fa-receipt me-2"></i>Lihat Struk
                                </a>

                                @if($adminWhatsappUrl)
                                    <a href="{{ $adminWhatsappUrl }}"
                                    target="_blank"
                                    class="btn btn-success rounded-pill px-4">
                                        <i class="fa-brands fa-whatsapp me-2"></i>Chat Admin
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center p-5">
                            <div class="display-5 text-muted mb-3">
                                <i class="fa fa-magnifying-glass"></i>
                            </div>

                            <h4 class="fw-bold text-dark mb-2">
                                Belum Ada Pesanan yang Ditampilkan
                            </h4>

                            <p class="text-muted mb-4">
                                Masukkan kode order dan nomor WhatsApp/HP pada form di samping untuk melihat detail pesanan.
                            </p>

                            <a href="{{ route('catalog') }}" class="btn btn-dark rounded-pill px-4">
                                <i class="fa fa-shirt me-2"></i>Lihat Katalog
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
@if($order)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const refreshButton = document.getElementById('refreshTrackingStatus');
    const paymentBadge = document.getElementById('tracking-payment-status');
    const orderBadge = document.getElementById('tracking-order-status');
    const updatedText = document.getElementById('tracking-status-updated');

    if (!refreshButton) {
        return;
    }

    refreshButton.addEventListener('click', function () {
        const url = refreshButton.dataset.statusUrl;

        if (!url) {
            return;
        }

        const originalText = refreshButton.innerHTML;
        refreshButton.disabled = true;
        refreshButton.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Mengecek...';

        fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Gagal mengecek status.');
                }

                return response.json();
            })
            .then(function (data) {
                if (paymentBadge) {
                    paymentBadge.className = 'badge bg-' + data.payment_status_class + ' rounded-pill px-3 py-2';
                    paymentBadge.textContent = data.payment_status_label;
                }

                if (orderBadge && data.order_status) {
                    orderBadge.textContent = data.order_status
                        .replaceAll('_', ' ')
                        .replace(/\b\w/g, function (letter) {
                            return letter.toUpperCase();
                        });
                }

                if (updatedText) {
                    updatedText.textContent = 'Status terakhir dicek pada ' + data.checked_at + '.';
                }
            })
            .catch(function () {
                if (updatedText) {
                    updatedText.textContent = 'Gagal mengecek ulang status. Silakan coba beberapa saat lagi.';
                }
            })
            .finally(function () {
                refreshButton.disabled = false;
                refreshButton.innerHTML = originalText;
            });
    });
});
</script>
@endif
@endsection
