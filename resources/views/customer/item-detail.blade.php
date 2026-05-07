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
@endphp

<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.68), rgba(60, 42, 33, 0.68)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <span class="badge rounded-pill px-4 py-2 mb-3"
              style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
            Detail Item
        </span>

        <h1 class="display-4 text-white fw-bold">{{ $item->name }}</h1>

        <p class="text-white mb-0">
            Informasi item, varian, stok, dan harga layanan Quin Salon.
        </p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4">
                <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4">
                <i class="fa fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                    <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                         alt="{{ $item->name }}"
                         class="img-fluid rounded-4 w-100"
                         style="max-height: 520px; object-fit: cover;"
                         onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 h-100" style="border: 1px solid #f1e3d3;">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge rounded-pill px-3 py-2" style="background-color: #8b5e3c; color: #fff;">
                            {{ $item->category->cat_name ?? 'Layanan' }}
                        </span>

                        <span class="badge rounded-pill px-3 py-2" style="background-color: #f8efe5; color: #8b5e3c;">
                            {{ $typeLabels[$item->item_type] ?? 'Item' }}
                        </span>

                        @if($item->adat_category)
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #f8efe5; color: #8b5e3c;">
                                Adat {{ $item->adat_category }}
                            </span>
                        @endif

                        @if($item->gender)
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #f8efe5; color: #8b5e3c;">
                                {{ $item->gender }}
                            </span>
                        @endif
                    </div>

                    <h2 class="fw-bold mb-3" style="color: #3f2c22;">
                        {{ $item->name }}
                    </h2>

                    <div class="mb-4">
                        <small class="text-muted d-block mb-1">Harga Dasar</small>
                        <div class="fw-bold fs-3" style="color: #8b5e3c;">
                            Rp{{ number_format($item->price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2" style="color: #8b5e3c;">Deskripsi</h5>
                        <p class="text-muted mb-0" style="line-height: 1.8;">
                            {{ $item->description ?: 'Belum ada deskripsi untuk item ini.' }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3" style="color: #8b5e3c;">Varian & Ketersediaan</h5>

                        @if($item->itemVariants->count())
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead style="background-color: #fff7ef;">
                                        <tr>
                                            <th>Ukuran</th>
                                            <th>Warna</th>
                                            <th>Stok</th>
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
                                                    {{ $variant->available_stock ?? 0 }}
                                                    <span class="text-muted">/ {{ $variant->stock ?? 0 }}</span>
                                                </td>
                                                <td>
                                                    Rp{{ number_format($variant->daily_price ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @if($variant->is_active && $variant->available_stock > 0)
                                                        <span class="badge bg-success">Tersedia</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Tersedia</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif($item->item_type === 'jasa_rias')
                            <div class="alert alert-info rounded-4 mb-0">
                                Layanan rias tidak wajib memakai varian ukuran. Silakan tambahkan ke keranjang sesuai kebutuhan.
                            </div>
                        @else
                            <div class="alert alert-warning rounded-4 mb-0">
                                Varian item belum tersedia. Silakan hubungi admin untuk konfirmasi stok.
                            </div>
                        @endif
                    </div>

                    <div class="p-3 rounded-4 mb-4" style="background-color: #fff7ef; border: 1px solid #f0dfcf;">
                        <h5 class="fw-bold mb-3" style="color: #8b5e3c;">Tambah ke Keranjang</h5>

                        @if($hasVariants || $canOrderWithoutVariant)
                            <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                @csrf

                                @if($hasVariants)
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Pilih Varian <span class="text-danger">*</span></label>
                                        <select name="item_variant_id" id="item_variant_id" class="form-select rounded-3" required>
                                            <option value="">-- Pilih Ukuran / Warna --</option>

                                            @foreach($availableVariants as $variant)
                                                <option value="{{ $variant->id }}"
                                                        data-stock="{{ $variant->available_stock }}"
                                                        data-price="{{ $variant->daily_price }}">
                                                    {{ $variant->size ?? '-' }}
                                                    @if($variant->color)
                                                        / {{ $variant->color }}
                                                    @endif
                                                    • Stok {{ $variant->available_stock }}
                                                    • Rp{{ number_format($variant->daily_price, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Jumlah</label>
                                        <input type="number" name="quantity" id="quantity" min="1" value="1" class="form-control rounded-3">
                                    </div>

                                    <div class="col-md-8">
                                        <button type="submit"
                                                class="btn rounded-pill px-4 py-3 w-100"
                                                style="background-color: #8b5e3c; color: #fff; border: none;">
                                            <i class="fa fa-cart-plus me-2"></i>Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning mb-0 rounded-4">
                                Item belum bisa dimasukkan ke keranjang karena varian/stok belum tersedia.
                            </div>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('catalog') }}"
                           class="btn rounded-pill px-4 py-3"
                           style="border: 1px solid #d8b892; color: #8b5e3c; background-color: #fff;">
                            <i class="fa fa-arrow-left me-2"></i>Kembali ke Katalog
                        </a>

                        <a href="{{ route('recommendation.index') }}"
                           class="btn rounded-pill px-4 py-3"
                           style="background-color: #8b5e3c; color: #fff; border: none;">
                            <i class="fa fa-gift me-2"></i>Coba Rekomendasi Paket
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($item->item_type === 'jasa_rias')
            <div class="row mt-5">
                <div class="col-12">
                    <div class="bg-white rounded-4 shadow-sm p-4 p-md-5" style="border: 1px solid #f1e3d3;">
                        <h4 class="fw-bold mb-3" style="color: #8b5e3c;">Informasi Tambahan</h4>
                        <p class="text-muted mb-0">
                            Layanan jasa rias dapat dikombinasikan dengan paket bundling maupun dipesan sesuai kebutuhan acara.
                            Untuk hasil yang lebih sesuai, gunakan fitur rekomendasi paket.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
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
        const option = variantSelect.options[variantSelect.selectedIndex];
        const stock = option ? Number(option.dataset.stock || 1) : 1;

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
