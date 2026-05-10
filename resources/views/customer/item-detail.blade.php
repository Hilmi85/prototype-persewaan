@extends('customer.layouts.master')

@section('title', $item->name . ' - Quin Salon')

@section('content')
@php
    $typeLabels = [
        'baju_adat' => 'Baju Adat',
        'aksesoris' => 'Aksesoris',
        'jasa_rias' => 'Jasa Rias',
    ];

    $availableVariants = $item->itemVariants
        ->where('is_active', true)
        ->where('available_stock', '>', 0);

    $hasVariants = $availableVariants->count() > 0;
    $canOrderWithoutVariant = $item->item_type === 'jasa_rias' && !$hasVariants;

    $backRoute = match ($item->item_type) {
        'jasa_rias' => route('rias.index'),
        default => route('catalog'),
    };

    $backLabel = match ($item->item_type) {
        'jasa_rias' => 'Kembali ke Jasa Rias',
        'aksesoris' => 'Kembali ke Katalog',
        default => 'Kembali ke Katalog Baju Adat',
    };

    $rentalDates = session('rental_dates');
    $defaultRentalStart = old('rental_start', $rentalDates['rental_start'] ?? '');
    $defaultRentalEnd = old('rental_end', $rentalDates['rental_end'] ?? '');
    $todayDate = now()->toDateString();
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Detail {{ $typeLabels[$item->item_type] ?? 'Item' }}
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    {{ $item->name }}
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Lihat detail item, varian, ketersediaan, harga, dan pilihan pemesanan sebelum
                    menambahkan produk ke keranjang.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#detail-item" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Detail
                    </a>

                    <a href="{{ $backRoute }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-left me-2"></i>{{ $backLabel }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="detail-item" class="container-fluid py-5 bg-cream">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4">
                <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4">
                <i class="fa fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="ratio ratio-3x4 bg-light">
                        <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                             class="w-100 h-100 object-fit-contain p-3"
                             alt="{{ $item->name }}"
                             onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3">
                            Ringkasan Item
                        </h5>

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block mb-1">Jenis</small>
                                    <strong class="text-dark">{{ $typeLabels[$item->item_type] ?? 'Item' }}</strong>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block mb-1">Kategori</small>
                                    <strong class="text-dark">{{ $item->category->cat_name ?? '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block mb-1">Gender</small>
                                    <strong class="text-dark">{{ $item->gender ?? '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block mb-1">Adat</small>
                                    <strong class="text-dark">{{ $item->adat_category ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="badge bg-dark rounded-pill px-3 py-2">
                                {{ $typeLabels[$item->item_type] ?? 'Item' }}
                            </span>

                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                {{ $item->category->cat_name ?? 'Kategori' }}
                            </span>

                            @if($item->gender)
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                    {{ $item->gender }}
                                </span>
                            @endif

                            @if($availableVariants->count())
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    {{ $availableVariants->count() }} varian tersedia
                                </span>
                            @elseif($item->item_type === 'jasa_rias')
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    Layanan tersedia
                                </span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                    Konfirmasi stok
                                </span>
                            @endif
                        </div>

                        <h2 class="fw-bold text-dark mb-3">
                            {{ $item->name }}
                        </h2>

                        <p class="text-muted mb-4">
                            {{ $item->description ?: 'Belum ada deskripsi untuk item ini.' }}
                        </p>

                        <div class="bg-dark text-white rounded-4 p-4 mb-4">
                            <small class="d-block text-white-50 mb-1">
                                Harga mulai dari
                            </small>

                            <div class="fw-bold fs-2">
                                Rp{{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="alert alert-light border rounded-4 mb-0">
                            <div class="d-flex gap-3 align-items-start">
                                <i class="fa fa-circle-info text-dark mt-1"></i>
                                <div>
                                    <strong class="d-block text-dark mb-1">
                                        Informasi Pemesanan
                                    </strong>

                                    <span class="text-muted">
                                        @if($hasVariants)
                                            Pilih varian ukuran atau warna terlebih dahulu sebelum menambahkan item ke keranjang.
                                        @elseif($item->item_type === 'jasa_rias')
                                            Layanan rias dapat langsung ditambahkan ke keranjang tanpa memilih varian.
                                        @else
                                            Item ini belum memiliki varian atau stok tersedia. Silakan hubungi admin untuk konfirmasi.
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="fw-bold text-dark mb-2">
                            Tambah ke Keranjang
                        </h4>

                        <p class="text-muted mb-4">
                            Pastikan varian dan jumlah sudah sesuai sebelum melanjutkan ke checkout.
                        </p>

                        @if($hasVariants || $canOrderWithoutVariant)
                            <form action="{{ route('cart.add', $item->id) }}" method="POST" id="addToCartForm">
                            @csrf

                                <div class="alert alert-light border rounded-4 mb-4">
                                    <div class="d-flex gap-3 align-items-start">
                                        <i class="fa fa-calendar-check text-dark mt-1"></i>
                                        <div class="w-100">
                                            <strong class="d-block text-dark mb-1">
                                                Tanggal Sewa Produk
                                            </strong>

                                            <span class="text-muted small d-block mb-3">
                                                Tanggal ini akan disimpan ke keranjang dan otomatis terisi di checkout.
                                            </span>

                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold">
                                                        Mulai Sewa <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date"
                                                        name="rental_start"
                                                        id="availability_rental_start"
                                                        value="{{ $defaultRentalStart }}"
                                                        min="{{ $todayDate }}"
                                                        class="form-control rounded-3"
                                                        required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold">
                                                        Selesai Sewa <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date"
                                                        name="rental_end"
                                                        id="availability_rental_end"
                                                        value="{{ $defaultRentalEnd }}"
                                                        min="{{ $todayDate }}"
                                                        class="form-control rounded-3"
                                                        required>
                                                </div>
                                            </div>

                                            <div id="variantAvailabilityResult" class="alert rounded-4 mt-3 mb-0 d-none"></div>
                                        </div>
                                    </div>
                                </div>

                                @if($hasVariants)
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Pilih Varian <span class="text-danger">*</span>
                                        </label>

                                        <select name="item_variant_id" id="item_variant_id" class="form-select" required>
                                            <option value="">-- Pilih Ukuran / Warna --</option>

                                            @foreach($availableVariants as $variant)
                                                <option value="{{ $variant->id }}"
                                                        data-stock="{{ $variant->available_stock }}">
                                                    Ukuran {{ $variant->size ?? '-' }}
                                                    @if($variant->color)
                                                        / Warna {{ $variant->color }}
                                                    @endif
                                                    • Stok {{ $variant->available_stock }}
                                                    • Rp{{ number_format($variant->daily_price ?? $item->price, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Jumlah</label>
                                    <input type="number"
                                           name="quantity"
                                           id="quantity"
                                           min="1"
                                           value="1"
                                           class="form-control">
                                </div>

                                <button type="submit" id="addToCartButton" class="btn btn-dark rounded-pill w-100 py-3 mb-3">
                                    <i class="fa fa-cart-plus me-2"></i>Cek & Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning rounded-4 mb-4">
                                Item belum bisa dimasukkan ke keranjang karena varian atau stok belum tersedia.
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            <a href="{{ $backRoute }}" class="btn btn-outline-dark rounded-pill py-3">
                                <i class="fa fa-arrow-left me-2"></i>{{ $backLabel }}
                            </a>

                            <a href="{{ route('recommendation.index') }}" class="btn btn-outline-dark rounded-pill py-3">
                                <i class="fa fa-gift me-2"></i>Coba Rekomendasi Paket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mt-5">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4 align-items-center mb-4">
                    <div class="col-lg">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Varian Item
                        </span>

                        <h3 class="fw-bold text-dark mb-2">
                            Varian & Ketersediaan
                        </h3>

                        <p class="text-muted mb-0">
                            Cek ukuran, warna, stok, dan harga harian item.
                        </p>
                    </div>

                    <div class="col-lg-auto">
                        <span class="badge bg-dark rounded-pill px-3 py-2">
                            {{ $item->itemVariants->count() }} varian
                        </span>
                    </div>
                </div>

                @if($item->itemVariants->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ukuran</th>
                                    <th>Warna</th>
                                    <th>Stok Tersedia</th>
                                    <th>Stok Total</th>
                                    <th>Harga Harian</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($item->itemVariants as $variant)
                                    <tr>
                                        <td>{{ $variant->size ?? '-' }}</td>
                                        <td>{{ $variant->color ?? '-' }}</td>
                                        <td>
                                            <strong>{{ $variant->available_stock ?? 0 }}</strong>
                                        </td>
                                        <td>{{ $variant->stock ?? 0 }}</td>
                                        <td>
                                            Rp{{ number_format($variant->daily_price ?? $item->price, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($variant->is_active && $variant->available_stock > 0)
                                                <span class="badge bg-success rounded-pill">
                                                    Tersedia
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">
                                                    Tidak tersedia
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif($item->item_type === 'jasa_rias')
                    <div class="alert alert-info rounded-4 mb-0">
                        <i class="fa fa-circle-info me-2"></i>
                        Layanan rias tidak wajib menggunakan varian ukuran atau warna.
                    </div>
                @else
                    <div class="alert alert-warning rounded-4 mb-0">
                        <i class="fa fa-triangle-exclamation me-2"></i>
                        Varian item belum tersedia. Silakan hubungi admin untuk konfirmasi stok.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('addToCartForm');
    const variantSelect = document.getElementById('item_variant_id');
    const quantityInput = document.getElementById('quantity');
    const startInput = document.getElementById('availability_rental_start');
    const endInput = document.getElementById('availability_rental_end');
    const resultBox = document.getElementById('variantAvailabilityResult');
    const submitButton = document.getElementById('addToCartButton');

    let lastAvailabilityStatus = null;

    function syncMaxQuantity() {
        if (!variantSelect || !quantityInput) {
            return;
        }

        const selectedOption = variantSelect.options[variantSelect.selectedIndex];
        const stock = selectedOption ? Number(selectedOption.dataset.stock || 1) : 1;

        quantityInput.max = stock;

        if (Number(quantityInput.value || 1) > stock) {
            quantityInput.value = stock;
        }
    }

    function showAvailabilityMessage(type, message) {
        if (!resultBox) {
            return;
        }

        resultBox.className = 'alert rounded-4 mt-3 mb-0 alert-' + type;
        resultBox.innerHTML = message;
        resultBox.classList.remove('d-none');
    }

    function hideAvailabilityMessage() {
        if (!resultBox) {
            return;
        }

        resultBox.classList.add('d-none');
        resultBox.innerHTML = '';
    }

    function setButtonDisabled(disabled) {
        if (!submitButton) {
            return;
        }

        submitButton.disabled = disabled;
    }

    function checkAvailability() {
        lastAvailabilityStatus = null;

        if (!startInput || !endInput || !quantityInput) {
            setButtonDisabled(false);
            return;
        }

        const rentalStart = startInput.value;
        const rentalEnd = endInput.value;
        const quantity = quantityInput.value || 1;

        if (!rentalStart || !rentalEnd) {
            hideAvailabilityMessage();
            setButtonDisabled(false);
            return;
        }

        if (!variantSelect) {
            showAvailabilityMessage(
                'success',
                '<i class="fa fa-circle-check me-2"></i>Tanggal sewa sudah dipilih dan akan dibawa ke checkout.'
            );
            setButtonDisabled(false);
            return;
        }

        const variantId = variantSelect.value;

        if (!variantId) {
            hideAvailabilityMessage();
            setButtonDisabled(false);
            return;
        }

        showAvailabilityMessage('light', '<i class="fa fa-spinner fa-spin me-2"></i>Mengecek ketersediaan tanggal...');

        const url = new URL("{{ route('availability.variant') }}", window.location.origin);
        url.searchParams.set('item_variant_id', variantId);
        url.searchParams.set('rental_start', rentalStart);
        url.searchParams.set('rental_end', rentalEnd);
        url.searchParams.set('quantity', quantity);

        fetch(url.toString(), {
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Gagal mengecek ketersediaan.');
                }

                return response.json();
            })
            .then(function (data) {
                lastAvailabilityStatus = data.available ? 'available' : 'unavailable';

                if (data.available) {
                    showAvailabilityMessage(
                        'success',
                        '<i class="fa fa-circle-check me-2"></i>' +
                        '<strong>Tersedia.</strong> ' +
                        data.message +
                        '<br><small>Tanggal ini akan tersimpan di keranjang.</small>'
                    );
                    setButtonDisabled(false);
                } else {
                    showAvailabilityMessage(
                        'danger',
                        '<i class="fa fa-circle-exclamation me-2"></i>' +
                        '<strong>Tidak tersedia.</strong> ' +
                        data.message
                    );
                    setButtonDisabled(true);
                }
            })
            .catch(function () {
                lastAvailabilityStatus = null;
                showAvailabilityMessage(
                    'warning',
                    '<i class="fa fa-triangle-exclamation me-2"></i>Gagal mengecek ketersediaan. Sistem tetap akan mengecek ulang saat item ditambahkan.'
                );
                setButtonDisabled(false);
            });
    }

    if (variantSelect && quantityInput) {
        variantSelect.addEventListener('change', function () {
            syncMaxQuantity();
            checkAvailability();
        });

        quantityInput.addEventListener('input', checkAvailability);
        syncMaxQuantity();
    }

    if (startInput) {
        startInput.addEventListener('change', checkAvailability);
    }

    if (endInput) {
        endInput.addEventListener('change', checkAvailability);
    }

    if (form) {
        form.addEventListener('submit', function (event) {
            if (variantSelect && lastAvailabilityStatus === 'unavailable') {
                event.preventDefault();
                showAvailabilityMessage(
                    'danger',
                    '<i class="fa fa-circle-exclamation me-2"></i>Varian tidak tersedia pada tanggal yang dipilih. Silakan pilih tanggal lain.'
                );
            }
        });
    }

    checkAvailability();
});
</script>
@endsection
