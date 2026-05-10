@extends('admin.layouts.master')
@section('title', 'Rental Booking & Pengembalian')

@section('content')
@php
    $summary = $returnSummary ?? [
        'total' => $bookings->count(),
        'active' => $bookings->whereNotIn('booking_status', ['returned', 'cancelled'])->count(),
        'late' => 0,
        'returned' => $bookings->where('booking_status', 'returned')->count(),
    ];

    $statusBadges = [
        'pending' => 'warning text-dark',
        'scheduled' => 'primary',
        'rescheduled' => 'info',
        'picked_up' => 'secondary',
        'done' => 'success',
        'cancelled' => 'danger',
        'returned' => 'success',
    ];

    $statusLabels = [
        'pending' => 'Pending',
        'scheduled' => 'Scheduled',
        'rescheduled' => 'Rescheduled',
        'picked_up' => 'Barang Diambil',
        'done' => 'Done',
        'cancelled' => 'Cancelled',
        'returned' => 'Returned',
    ];

    $conditionLabels = [
        'baik' => 'Baik',
        'perlu_laundry' => 'Perlu Laundry',
        'rusak_ringan' => 'Rusak Ringan',
        'rusak_berat' => 'Rusak Berat',
        'hilang' => 'Hilang',
    ];

    $stockActionLabels = [
        'restore_available' => 'Stok kembali tersedia',
        'hold_unavailable' => 'Stok ditahan / belum tersedia',
        'reduce_total' => 'Stok total dikurangi',
    ];

    $whatsappService = app(\App\Services\WhatsappMessageService::class);
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Data Booking & Pengembalian</h3>
            <p class="text-muted mb-0">
                Kelola jadwal sewa, status booking, dan proses pengembalian barang customer.
            </p>
        </div>

        <a href="{{ route('rental-bookings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Booking
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Proses gagal.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted d-block">Total Booking</small>
                    <h4 class="mb-0">{{ number_format($summary['total'] ?? 0, 0, ',', '.') }}</h4>
                    <small class="text-muted">Seluruh data booking</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted d-block">Booking Aktif</small>
                    <h4 class="mb-0">{{ number_format($summary['active'] ?? 0, 0, ',', '.') }}</h4>
                    <small class="text-muted">Belum dikembalikan / belum dibatalkan</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted d-block">Terlambat</small>
                    <h4 class="mb-0">{{ number_format($summary['late'] ?? 0, 0, ',', '.') }}</h4>
                    <small class="text-muted">Lewat tanggal selesai sewa</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted d-block">Sudah Kembali</small>
                    <h4 class="mb-0">{{ number_format($summary['returned'] ?? 0, 0, ',', '.') }}</h4>
                    <small class="text-muted">Booking selesai dikembalikan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-1">Daftar Booking</h4>
            <p class="text-muted mb-0">
                Klik tombol <strong>Pengembalian</strong> untuk mencatat barang kembali dan memperbarui stok.
            </p>
        </div>

        <div class="card-body">
            <div class="alert alert-light border">
                <strong>Catatan sistem pengembalian:</strong>
                <ul class="mb-0 mt-2">
                    <li><strong>Stok kembali tersedia</strong>: digunakan jika barang kembali normal dan bisa disewakan lagi.</li>
                    <li><strong>Stok ditahan</strong>: digunakan jika barang perlu laundry/perbaikan sehingga belum bisa disewakan.</li>
                    <li><strong>Stok total dikurangi</strong>: digunakan jika barang hilang atau rusak berat.</li>
                    <li><strong>Tombol WhatsApp</strong>: digunakan untuk mengirim reminder pengembalian ke customer.</li>
                </ul>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Booking</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Periode Sewa</th>
                            <th>Barang</th>
                            <th>Status</th>
                            <th>Pengembalian</th>
                            <th style="width: 230px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $index => $booking)
                            @php
                                $order = $booking->order;
                                $customer = $order?->user;

                                $status = $booking->booking_status ?? 'pending';
                                $statusBadge = $statusBadges[$status] ?? 'secondary';
                                $statusLabel = $statusLabels[$status] ?? ucwords(str_replace('_', ' ', $status));

                                $isReturned = $booking->booking_status === 'returned' || $booking->returned_at;
                                $isCancelled = $booking->booking_status === 'cancelled';

                                $returnReminderWaUrl = null;

                                if (!$isReturned && !$isCancelled) {
                                    $returnReminderWaUrl = $whatsappService->customerReturnReminder($booking);
                                }

                                $estimatedLateDays = 0;

                                if (!$isReturned && !$isCancelled && $booking->rental_end && now()->startOfDay()->gt($booking->rental_end->copy()->startOfDay())) {
                                    $estimatedLateDays = $booking->rental_end
                                        ->copy()
                                        ->startOfDay()
                                        ->diffInDays(now()->startOfDay());
                                }

                                $orderedVariantRows = collect();

                                if ($order) {
                                    $orderedVariantRows = $order->orderItems->flatMap(function ($orderItem) {
                                        return $orderItem->orderItemVariants->map(function ($orderItemVariant) use ($orderItem) {
                                            return [
                                                'item_name' => $orderItem->item->name ?? '-',
                                                'variant' => $orderItemVariant->itemVariant,
                                                'qty' => $orderItemVariant->qty,
                                            ];
                                        });
                                    });
                                }
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $booking->booking_code }}
                                    </div>

                                    <small class="text-muted">
                                        {{ optional($booking->created_at)->format('d-m-Y H:i') }}
                                    </small>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $order->order_code ?? '-' }}
                                    </div>

                                    <small class="text-muted">
                                        Status:
                                        {{ $order ? ucwords(str_replace('_', ' ', $order->status)) : '-' }}
                                    </small>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $customer->fullname ?? '-' }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $customer->phone ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    <div>
                                        <small class="text-muted d-block">Mulai</small>
                                        <span>{{ $booking->rental_start ? $booking->rental_start->format('d-m-Y') : '-' }}</span>
                                    </div>

                                    <div class="mt-1">
                                        <small class="text-muted d-block">Selesai</small>
                                        <span>{{ $booking->rental_end ? $booking->rental_end->format('d-m-Y') : '-' }}</span>
                                    </div>

                                    @if($estimatedLateDays > 0)
                                        <span class="badge bg-danger mt-2">
                                            Terlambat {{ $estimatedLateDays }} hari
                                        </span>
                                    @endif
                                </td>

                                <td style="min-width: 220px;">
                                    @if($orderedVariantRows->count())
                                        <div class="vstack gap-1">
                                            @foreach($orderedVariantRows as $row)
                                                <div>
                                                    <span class="fw-semibold">{{ $row['item_name'] }}</span>

                                                    <small class="text-muted d-block">
                                                        Varian:
                                                        {{ $row['variant']?->size ?? '-' }}
                                                        @if($row['variant']?->color)
                                                            / {{ $row['variant']->color }}
                                                        @endif
                                                        • Qty {{ $row['qty'] }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Tidak ada varian barang.</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-{{ $statusBadge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td style="min-width: 220px;">
                                    @if($isReturned)
                                        <div class="fw-semibold text-success">
                                            <i class="bi bi-check-circle me-1"></i>Sudah Kembali
                                        </div>

                                        <small class="text-muted d-block">
                                            {{ $booking->returned_at ? $booking->returned_at->format('d-m-Y H:i') : '-' }}
                                        </small>

                                        @if($booking->return_condition)
                                            <small class="d-block">
                                                Kondisi:
                                                {{ $conditionLabels[$booking->return_condition] ?? ucwords(str_replace('_', ' ', $booking->return_condition)) }}
                                            </small>
                                        @endif

                                        @if($booking->return_stock_action)
                                            <small class="d-block">
                                                Aksi Stok:
                                                {{ $stockActionLabels[$booking->return_stock_action] ?? ucwords(str_replace('_', ' ', $booking->return_stock_action)) }}
                                            </small>
                                        @endif

                                        @if($booking->late_days > 0)
                                            <span class="badge bg-danger mt-1">
                                                Telat {{ $booking->late_days }} hari
                                            </span>
                                        @endif

                                        @if($booking->total_return_fee > 0)
                                            <small class="d-block mt-1">
                                                Denda:
                                                <strong>Rp{{ number_format($booking->total_return_fee, 0, ',', '.') }}</strong>
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">Belum dikembalikan</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(!$isReturned && !$isCancelled)
                                            <button type="button"
                                                    class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#returnModal{{ $booking->id }}"
                                                    title="Proses Pengembalian">
                                                <i class="bi bi-box-arrow-in-left me-1"></i>Pengembalian
                                            </button>
                                        @endif

                                        @if($returnReminderWaUrl)
                                            <a href="{{ $returnReminderWaUrl }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-success"
                                               title="Reminder Pengembalian via WhatsApp">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('rental-bookings.edit', $booking->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit Booking">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('rental-bookings.destroy', $booking->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Booking">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Belum ada data booking.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @foreach($bookings as $booking)
                @php
                    $order = $booking->order;
                    $customer = $order?->user;

                    $isReturned = $booking->booking_status === 'returned' || $booking->returned_at;
                    $isCancelled = $booking->booking_status === 'cancelled';

                    $estimatedLateDays = 0;

                    if (!$isReturned && !$isCancelled && $booking->rental_end && now()->startOfDay()->gt($booking->rental_end->copy()->startOfDay())) {
                        $estimatedLateDays = $booking->rental_end
                            ->copy()
                            ->startOfDay()
                            ->diffInDays(now()->startOfDay());
                    }

                    $orderedVariantRows = collect();

                    if ($order) {
                        $orderedVariantRows = $order->orderItems->flatMap(function ($orderItem) {
                            return $orderItem->orderItemVariants->map(function ($orderItemVariant) use ($orderItem) {
                                return [
                                    'item_name' => $orderItem->item->name ?? '-',
                                    'variant' => $orderItemVariant->itemVariant,
                                    'qty' => $orderItemVariant->qty,
                                ];
                            });
                        });
                    }
                @endphp

                @if(!$isReturned && !$isCancelled)
                    <div class="modal fade"
                         id="returnModal{{ $booking->id }}"
                         tabindex="-1"
                         aria-labelledby="returnModalLabel{{ $booking->id }}"
                         aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('rental-bookings.return', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="returnModalLabel{{ $booking->id }}">
                                            Proses Pengembalian Barang
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="alert alert-light border">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Kode Booking</small>
                                                    <strong>{{ $booking->booking_code }}</strong>
                                                </div>

                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Customer</small>
                                                    <strong>{{ $customer->fullname ?? '-' }}</strong>
                                                </div>

                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Order</small>
                                                    <strong>{{ $order->order_code ?? '-' }}</strong>
                                                </div>

                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Batas Selesai Sewa</small>
                                                    <strong>{{ $booking->rental_end ? $booking->rental_end->format('d-m-Y') : '-' }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                        @if($estimatedLateDays > 0)
                                            <div class="alert alert-danger">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Booking ini terlambat sekitar <strong>{{ $estimatedLateDays }} hari</strong>.
                                                Isi denda terlambat jika diperlukan.
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">Barang yang Dikembalikan</label>

                                            @if($orderedVariantRows->count())
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered align-middle mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Varian</th>
                                                                <th>Qty</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($orderedVariantRows as $row)
                                                                <tr>
                                                                    <td>{{ $row['item_name'] }}</td>
                                                                    <td>
                                                                        {{ $row['variant']?->size ?? '-' }}
                                                                        @if($row['variant']?->color)
                                                                            / {{ $row['variant']->color }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $row['qty'] }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="alert alert-warning mb-0">
                                                    Tidak ada data varian barang pada booking ini.
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tanggal & Jam Kembali</label>
                                                <input type="datetime-local"
                                                       name="returned_at"
                                                       class="form-control"
                                                       value="{{ now()->format('Y-m-d\TH:i') }}">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Kondisi Barang <span class="text-danger">*</span></label>
                                                <select name="return_condition" class="form-select" required>
                                                    <option value="baik">Baik</option>
                                                    <option value="perlu_laundry">Perlu Laundry</option>
                                                    <option value="rusak_ringan">Rusak Ringan</option>
                                                    <option value="rusak_berat">Rusak Berat</option>
                                                    <option value="hilang">Hilang</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Aksi Stok <span class="text-danger">*</span></label>
                                                <select name="return_stock_action" class="form-select" required>
                                                    <option value="restore_available">
                                                        Stok kembali tersedia - barang sudah bisa disewakan lagi
                                                    </option>
                                                    <option value="hold_unavailable">
                                                        Stok ditahan - barang kembali tapi belum bisa disewakan
                                                    </option>
                                                    <option value="reduce_total">
                                                        Stok total dikurangi - barang hilang/rusak berat
                                                    </option>
                                                </select>

                                                <small class="text-muted">
                                                    Pilih sesuai kondisi barang agar stok tidak salah.
                                                </small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Denda Terlambat</label>
                                                <input type="number"
                                                       name="late_fee"
                                                       class="form-control"
                                                       min="0"
                                                       step="1000"
                                                       value="0">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Denda Kerusakan / Hilang</label>
                                                <input type="number"
                                                       name="damage_fee"
                                                       class="form-control"
                                                       min="0"
                                                       step="1000"
                                                       value="0">
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label class="form-label">Catatan Pengembalian</label>
                                                <textarea name="return_notes"
                                                          rows="4"
                                                          class="form-control"
                                                          placeholder="Contoh: Barang kembali lengkap, perlu laundry, ada noda kecil, aksesoris kurang, dll."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            Batal
                                        </button>

                                        <button type="submit"
                                                class="btn btn-success"
                                                onclick="return confirm('Pastikan data pengembalian sudah benar. Proses ini akan memperbarui stok dan tidak bisa dilakukan dua kali. Lanjutkan?')">
                                            <i class="bi bi-check-circle me-1"></i>Proses Pengembalian
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
</div>
@endsection
