@extends('admin.layouts.master')
@section('title', 'Dashboard Operasional')

@section('content')
@php
    $orderStatusBadges = [
        'pending' => 'warning text-dark',
        'confirmed' => 'info',
        'booked' => 'primary',
        'in_progress' => 'secondary',
        'completed' => 'success',
        'cancelled' => 'danger',
        'success' => 'success',
    ];

    $paymentStatusBadges = [
        'pending' => 'warning text-dark',
        'paid' => 'success',
        'failed' => 'danger',
        'expired' => 'danger',
        'refunded' => 'info',
    ];

    $bookingStatusBadges = [
        'pending' => 'warning text-dark',
        'scheduled' => 'primary',
        'rescheduled' => 'info',
        'picked_up' => 'secondary',
        'done' => 'success',
        'cancelled' => 'danger',
        'returned' => 'success',
    ];

    $statCards = [
        [
            'title' => 'Order Hari Ini',
            'value' => number_format($todayOrders ?? 0, 0, ',', '.'),
            'subtitle' => 'Pesanan masuk hari ini',
            'icon' => 'bi-cart-check-fill',
            'color' => 'purple',
            'value_class' => '',
        ],
        [
            'title' => 'Pendapatan Hari Ini',
            'value' => 'Rp' . number_format($todayRevenue ?? 0, 0, ',', '.'),
            'subtitle' => 'Pembayaran lunas hari ini',
            'icon' => 'bi-wallet2',
            'color' => 'blue',
            'value_class' => '',
        ],
        [
            'title' => 'Pendapatan Bulan Ini',
            'value' => 'Rp' . number_format($monthlyRevenue ?? 0, 0, ',', '.'),
            'subtitle' => 'Total pembayaran bulan ini',
            'icon' => 'bi-bar-chart-fill',
            'color' => 'green',
            'value_class' => '',
        ],
        [
            'title' => 'Order Pending',
            'value' => number_format($pendingOrders ?? 0, 0, ',', '.'),
            'subtitle' => 'Menunggu proses admin',
            'icon' => 'bi-clock-fill',
            'color' => 'red',
            'value_class' => '',
        ],
        [
            'title' => 'QRIS Pending',
            'value' => number_format($qrisPendingPayments ?? 0, 0, ',', '.'),
            'subtitle' => 'Belum dibayar customer',
            'icon' => 'bi-qr-code-scan',
            'color' => 'blue',
            'value_class' => '',
        ],
        [
            'title' => 'QRIS Expired',
            'value' => number_format($expiredPendingCount ?? 0, 0, ',', '.'),
            'subtitle' => 'Perlu dibatalkan sistem',
            'icon' => 'bi-clock-history',
            'color' => 'red',
            'value_class' => 'text-danger',
        ],
        [
            'title' => 'Booking Aktif',
            'value' => number_format($activeBookings ?? 0, 0, ',', '.'),
            'subtitle' => 'Belum selesai/dibatalkan',
            'icon' => 'bi-calendar-check-fill',
            'color' => 'green',
            'value_class' => '',
        ],
        [
            'title' => 'Pengembalian Hari Ini',
            'value' => number_format($todayReturnBookings ?? 0, 0, ',', '.'),
            'subtitle' => 'Barang dijadwalkan kembali',
            'icon' => 'bi-box-arrow-in-left',
            'color' => 'purple',
            'value_class' => '',
        ],
        [
            'title' => 'Mulai Sewa Hari Ini',
            'value' => number_format($todayStartBookings ?? 0, 0, ',', '.'),
            'subtitle' => 'Booking mulai hari ini',
            'icon' => 'bi-calendar-event-fill',
            'color' => 'blue',
            'value_class' => '',
        ],
        [
            'title' => 'Booking Terlambat',
            'value' => number_format($lateBookings ?? 0, 0, ',', '.'),
            'subtitle' => 'Lewat tanggal kembali',
            'icon' => 'bi-hourglass-split',
            'color' => 'red',
            'value_class' => 'text-danger',
        ],
        [
            'title' => 'Stok Menipis',
            'value' => number_format($lowStockVariants ?? 0, 0, ',', '.'),
            'subtitle' => 'Sisa 1 sampai 2',
            'icon' => 'bi-box-seam-fill',
            'color' => 'green',
            'value_class' => 'text-warning',
        ],
        [
            'title' => 'Stok Habis',
            'value' => number_format($emptyStockVariants ?? 0, 0, ',', '.'),
            'subtitle' => 'Tidak tersedia',
            'icon' => 'bi-box2-x-fill',
            'color' => 'red',
            'value_class' => 'text-danger',
        ],
    ];
@endphp

<style>
    .dashboard-stat-card {
        min-height: 178px;
        border: 0;
        border-radius: 18px;
        overflow: hidden;
    }

    .dashboard-stat-card .card-body {
        height: 100%;
        padding: 1.35rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .dashboard-stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex: 0 0 auto;
        margin-bottom: 1rem;
    }

    .dashboard-stat-icon i {
        font-size: 1.65rem;
        line-height: 1;
    }

    .dashboard-stat-icon.purple {
        background: #8b7cf6;
    }

    .dashboard-stat-icon.blue {
        background: #56c6e7;
    }

    .dashboard-stat-icon.green {
        background: #5ed2ad;
    }

    .dashboard-stat-icon.red {
        background: #ff7070;
    }

    .dashboard-stat-title {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: .35rem;
        min-height: 2.5rem;
        display: flex;
        align-items: flex-end;
    }

    .dashboard-stat-value {
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: .35rem;
    }

    .dashboard-stat-subtitle {
        font-size: .86rem;
        line-height: 1.35;
    }

    .dashboard-table-card {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
    }

    @media (min-width: 1200px) {
        .dashboard-stat-grid > [class*="col-"] {
            display: flex;
        }

        .dashboard-stat-grid .dashboard-stat-card {
            width: 100%;
        }
    }

    @media (max-width: 575.98px) {
        .dashboard-stat-card {
            min-height: 160px;
        }

        .dashboard-stat-card .card-body {
            padding: 1rem;
        }

        .dashboard-stat-icon {
            width: 48px;
            height: 48px;
            margin-bottom: .85rem;
        }

        .dashboard-stat-title {
            font-size: .92rem;
            min-height: auto;
        }

        .dashboard-stat-value {
            font-size: 1.08rem;
        }

        .dashboard-stat-subtitle {
            font-size: .78rem;
        }
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Selamat Datang, {{ Auth::user()->fullname }}!</h3>
            <p class="text-muted mb-0">
                Ringkasan operasional harian sistem persewaan baju adat dan jasa rias Quin Salon.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-receipt-cutoff me-1"></i>Order
            </a>

            <a href="{{ route('rental-bookings.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-calendar-check me-1"></i>Booking
            </a>

            <a href="{{ route('item-variants.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-box-seam me-1"></i>Stok
            </a>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        @if(($expiredPendingCount ?? 0) > 0)
            <div class="alert alert-danger alert-dismissible fade show">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Ada <strong>{{ $expiredPendingCount }}</strong> pembayaran QRIS pending yang sudah melewati batas waktu.
                    </div>

                    @if(Route::has('orders.expirePending'))
                        <form action="{{ route('orders.expirePending') }}"
                              method="POST"
                              onsubmit="return confirm('Jalankan auto expire untuk semua order QRIS pending yang sudah melewati batas pembayaran?')">
                            @csrf

                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-clock-history me-1"></i>Expire Sekarang
                            </button>
                        </form>
                    @endif
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(($lateBookings ?? 0) > 0)
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="bi bi-hourglass-split me-1"></i>
                Ada <strong>{{ $lateBookings }}</strong> booking yang melewati tanggal selesai sewa dan belum dikembalikan.
                <a href="{{ route('rental-bookings.index') }}" class="alert-link">Lihat data booking</a>.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4 dashboard-stat-grid mb-4">
            @foreach($statCards as $card)
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card dashboard-stat-card shadow-sm h-100">
                        <div class="card-body">
                            <div>
                                <div class="dashboard-stat-icon {{ $card['color'] }}">
                                    <i class="bi {{ $card['icon'] }}"></i>
                                </div>

                                <div class="dashboard-stat-title">
                                    {{ $card['title'] }}
                                </div>
                            </div>

                            <div>
                                <div class="dashboard-stat-value {{ $card['value_class'] }}">
                                    {{ $card['value'] }}
                                </div>

                                <div class="dashboard-stat-subtitle text-muted">
                                    {{ $card['subtitle'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="card dashboard-table-card shadow-sm">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1">Order Terbaru</h4>
                                <p class="text-muted mb-0">Pesanan terakhir yang masuk ke sistem.</p>
                            </div>

                            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Pembayaran</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($recentOrders as $order)
                                        @php
                                            $payment = $order->payments->first();
                                            $orderBadge = $orderStatusBadges[$order->status] ?? 'secondary';
                                            $paymentBadge = $paymentStatusBadges[$payment->payment_status ?? null] ?? 'secondary';
                                        @endphp

                                        <tr>
                                            <td>
                                                <a href="{{ route('orders.show', $order->id) }}" class="fw-semibold">
                                                    {{ $order->order_code }}
                                                </a>
                                            </td>

                                            <td>
                                                <div class="fw-semibold">{{ $order->user->fullname ?? '-' }}</div>
                                                <small class="text-muted">{{ $order->user->phone ?? '-' }}</small>
                                            </td>

                                            <td>
                                                Rp{{ number_format($order->grand_total ?? 0, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                @if($payment)
                                                    <span class="badge bg-{{ $paymentBadge }}">
                                                        {{ strtoupper($payment->method ?? '-') }} • {{ ucfirst($payment->payment_status) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Belum Ada</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge bg-{{ $orderBadge }}">
                                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $order->created_at->format('d-m-Y H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                Belum ada order.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card dashboard-table-card shadow-sm">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1">Booking Prioritas</h4>
                                <p class="text-muted mb-0">Booking terdekat yang perlu dipantau admin.</p>
                            </div>

                            <a href="{{ route('rental-bookings.index') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Booking
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode Booking</th>
                                        <th>Customer</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($priorityBookings as $booking)
                                        @php
                                            $bookingBadge = $bookingStatusBadges[$booking->booking_status] ?? 'secondary';
                                            $isLate = $booking->rental_end && $booking->rental_end->lt(now()->startOfDay());
                                            $isReturnToday = $booking->rental_end && $booking->rental_end->isSameDay(now());
                                        @endphp

                                        <tr>
                                            <td class="fw-semibold">{{ $booking->booking_code }}</td>

                                            <td>
                                                <div class="fw-semibold">{{ $booking->order->user->fullname ?? '-' }}</div>
                                                <small class="text-muted">{{ $booking->order->user->phone ?? '-' }}</small>
                                            </td>

                                            <td>
                                                <div>
                                                    {{ $booking->rental_start ? $booking->rental_start->format('d-m-Y') : '-' }}
                                                </div>
                                                <small class="text-muted">
                                                    sampai {{ $booking->rental_end ? $booking->rental_end->format('d-m-Y') : '-' }}
                                                </small>
                                            </td>

                                            <td>
                                                <span class="badge bg-{{ $bookingBadge }}">
                                                    {{ ucwords(str_replace('_', ' ', $booking->booking_status)) }}
                                                </span>
                                            </td>

                                            <td>
                                                @if($isLate)
                                                    <span class="badge bg-danger">Terlambat</span>
                                                @elseif($isReturnToday)
                                                    <span class="badge bg-warning text-dark">Kembali Hari Ini</span>
                                                @else
                                                    <span class="badge bg-light text-dark border">Terjadwal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Tidak ada booking prioritas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card dashboard-table-card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-1">Aksi Cepat</h4>
                        <p class="text-muted mb-0">Shortcut halaman penting admin.</p>
                    </div>

                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-receipt-cutoff me-1"></i>Data Order
                            </a>

                            @if(Route::has('orders.expirePending'))
                                <form action="{{ route('orders.expirePending') }}"
                                      method="POST"
                                      onsubmit="return confirm('Jalankan auto expire untuk semua order QRIS pending yang sudah melewati batas pembayaran?')">
                                    @csrf

                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-clock-history me-1"></i>Expire QRIS Pending
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('payments.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-wallet2 me-1"></i>Data Pembayaran
                            </a>

                            <a href="{{ route('rental-bookings.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-calendar-check me-1"></i>Data Booking
                            </a>

                            <a href="{{ route('item-variants.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-box-seam me-1"></i>Kelola Stok Varian
                            </a>

                            <a href="{{ route('items.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-bag-fill me-1"></i>Data Item
                            </a>

                            <a href="{{ route('bundles.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-box2-heart-fill me-1"></i>Data Bundle
                            </a>

                            <a href="{{ route('contact-settings.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-whatsapp me-1"></i>Contact Setting
                            </a>

                            @if(Auth::user()->role && Auth::user()->role->role_name === 'admin')
                                <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-people-fill me-1"></i>Data User
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card dashboard-table-card shadow-sm">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1">Stok Perlu Perhatian</h4>
                                <p class="text-muted mb-0">Varian habis atau hampir habis.</p>
                            </div>

                            <a href="{{ route('item-variants.index') }}" class="btn btn-sm btn-outline-primary">
                                Stok
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @forelse($stockAlerts as $variant)
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                                <div>
                                    <div class="fw-semibold">
                                        {{ $variant->item->name ?? '-' }}
                                    </div>

                                    <small class="text-muted d-block">
                                        {{ $variant->item->category->cat_name ?? '-' }}
                                    </small>

                                    <small class="text-muted d-block">
                                        Varian:
                                        {{ $variant->size ?? '-' }}
                                        @if($variant->color)
                                            / {{ $variant->color }}
                                        @endif
                                    </small>
                                </div>

                                <div class="text-end">
                                    @if((int) $variant->available_stock <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Sisa {{ $variant->available_stock }}
                                        </span>
                                    @endif

                                    <small class="text-muted d-block mt-1">
                                        Total {{ $variant->stock }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                Tidak ada stok menipis.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card dashboard-table-card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-1">Ringkasan Stok</h4>
                        <p class="text-muted mb-0">Gambaran stok fisik dan tersedia.</p>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Varian</span>
                            <strong>{{ number_format($totalVariants ?? 0, 0, ',', '.') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Stok Total</span>
                            <strong>{{ number_format($totalStock ?? 0, 0, ',', '.') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Stok Tersedia</span>
                            <strong class="text-success">{{ number_format($availableStock ?? 0, 0, ',', '.') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tersewa/Dipesan</span>
                            <strong class="text-warning">{{ number_format($rentedStock ?? 0, 0, ',', '.') }}</strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Item Aktif</span>
                            <strong>{{ number_format($activeItems ?? 0, 0, ',', '.') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Bundle Aktif</span>
                            <strong>{{ number_format($activeBundles ?? 0, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card dashboard-table-card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-1">Pembayaran Pending</h4>
                        <p class="text-muted mb-0">Order yang menunggu pembayaran.</p>
                    </div>

                    <div class="card-body">
                        @forelse($pendingPaymentOrders as $order)
                            @php
                                $payment = $order->payments->first();
                                $isExpired = $payment && $payment->expired_at && $payment->expired_at->lte(now()) && $payment->payment_status === 'pending';
                            @endphp

                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <a href="{{ route('orders.show', $order->id) }}" class="fw-semibold">
                                            {{ $order->order_code }}
                                        </a>

                                        <small class="text-muted d-block">
                                            {{ $order->user->fullname ?? '-' }}
                                        </small>
                                    </div>

                                    @if($isExpired)
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </div>

                                <small class="text-muted d-block mt-1">
                                    Total: Rp{{ number_format($order->grand_total ?? 0, 0, ',', '.') }}
                                </small>

                                @if($payment && $payment->expired_at)
                                    <small class="text-muted d-block">
                                        Batas bayar: {{ $payment->expired_at->format('d-m-Y H:i') }}
                                    </small>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                Tidak ada pembayaran pending.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
@endsection
