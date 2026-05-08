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
        'aksesoris' => route('accessories.index'),
        default => route('catalog'),
    };

    $backLabel = match ($item->item_type) {
        'jasa_rias' => 'Kembali ke Jasa Rias',
        'aksesoris' => 'Kembali ke Aksesoris',
        default => 'Kembali ke Katalog',
    };
@endphp

<section class="container-fluid bg-light border-bottom mt-5 pt-5 pb-4">
    <div class="container pt-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-dark">Beranda</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ $backRoute }}" class="text-decoration-none text-dark">
                        {{ $typeLabels[$item->item_type] ?? 'Katalog' }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Detail Item
                </li>
            </ol>
        </nav>

        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Detail {{ $typeLabels[$item->item_type] ?? 'Item' }}
                </span>

                <h1 class="display-5 fw-bold text-dark mb-3">
                    {{ $item->name }}
                </h1>

                <p class="lead text-muted mb-0">
                    Lihat informasi produk, varian, stok, harga, dan pilihan pemesanan sebelum
                    menambahkan item ke keranjang.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid py-5">
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
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="bg-light p-3">
                        <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                             class="img-fluid w-100 rounded-3"
                             alt="{{ $item->name }}"
                             onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3">
                            Ringkasan Item
                        </h5>

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-muted d-block mb-1">Jenis</small>
                                    <strong>{{ $typeLabels[$item->item_type] ?? 'Item' }}</strong>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-muted d-block mb-1">Kategori</small>
                                    <strong>{{ $item->category->cat_name ?? '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-muted d-block mb-1">Gender</small>
                                    <strong>{{ $item->gender ?? '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-muted d-block mb-1">Adat</small>
                                    <strong>{{ $item->adat_category ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
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
                                    Perlu konfirmasi stok
                                </span>
                            @endif
                        </div>

                        <h2 class="fw-bold text-dark mb-3">
                            {{ $item->name }}
                        </h2>

                        <p class="text-muted mb-4">
                            {{ $item->description ?: 'Belum ada deskripsi untuk item ini.' }}
                        </p>

                        <div class="bg-dark text-white rounded-3 p-4 mb-4">
                            <small class="d-block text-white-50 mb-1">Harga mulai dari</small>
                            <div class="fw-bold fs-2">
                                Rp{{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="alert alert-light border rounded-3 mb-0">
                            <div class="d-flex gap-3">
                                <div class="text-dark">
                                    <i class="fa fa-circle-info"></i>
                                </div>
                                <div>
                                    <strong class="d-block text-dark mb-1">Informasi Pemesanan</strong>
                                    <span class="text-muted">
                                        @if($hasVariants)
                                            Silakan pilih varian ukuran atau warna sebelum menambahkan item ke keranjang.
                                        @elseif($item->item_type === 'jasa_rias')
                                            Layanan rias dapat langsung ditambahkan ke keranjang.
                                        @else
                                            Item ini belum memiliki varian/stok tersedia. Silakan hubungi admin untuk konfirmasi.
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="fw-bold text-dark mb-2">
                            Tambah ke Keranjang
                        </h4>

                        <p class="text-muted mb-4">
                            Pastikan jumlah dan varian sudah sesuai sebelum melanjutkan ke checkout.
                        </p>

                        @if($hasVariants || $canOrderWithoutVariant)
                            <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                @csrf

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
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        {{-- <a href="{{ route('catalog.show', $item->id) }}" class="btn btn-outline-dark w-100 rounded-pill">
                                            <i class="fa fa-eye me-1"></i>Detail
                                        </a> --}}
                                        <button type="submit" class="btn btn-outline-dark rounded-pill">
                                            <i class="fa fa-cart-plus me-1"></i>Tambah ke Keranjang
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('recommendation.index') }}" class="btn btn-outline-secondary rounded-pill">
                                            <i class="fa fa-gift me-1"></i>Coba Rekomendasi Paket
                                        </a>
                                    </div>
                                </div>

                            </form>
                        @else
                            <div class="alert alert-warning rounded-3 mb-4">
                                Item belum bisa dimasukkan ke keranjang karena varian atau stok belum tersedia.
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            <a href="{{ $backRoute }}" class="btn btn-outline-danger rounded-pill py-3">
                                <i class="fa fa-arrow-left me-2"></i>{{ $backLabel }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mt-5">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4 align-items-center mb-4">
                    <div class="col-lg">
                        <h3 class="fw-bold text-dark mb-2">
                            Varian & Ketersediaan
                        </h3>
                        <p class="text-muted mb-0">
                            Cek ukuran, warna, stok, dan harga harian item.
                        </p>
                    </div>

                    <div class="col-lg-auto">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
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
                    <div class="alert alert-info rounded-3 mb-0">
                        <i class="fa fa-circle-info me-2"></i>
                        Layanan rias tidak wajib menggunakan varian ukuran atau warna.
                    </div>
                @else
                    <div class="alert alert-warning rounded-3 mb-0">
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
    const variantSelect = document.getElementById('item_variant_id');
    const quantityInput = document.getElementById('quantity');

    if (!variantSelect || !quantityInput) {
        return;
    }

    function syncMaxQuantity() {
        const selectedOption = variantSelect.options[variantSelect.selectedIndex];
        const stock = selectedOption ? Number(selectedOption.dataset.stock || 1) : 1;

        quantityInput.max = stock;

        if (Number(quantityInput.value || 1) > stock) {
            quantityInput.value = stock;
        }
    }

    variantSelect.addEventListener('change', syncMaxQuantity);
    syncMaxQuantity();
});
</script>
@endsection
