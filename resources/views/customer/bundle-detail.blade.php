@extends('customer.layouts.master')

@section('title', 'Detail Paket - Quin Salon')

@section('content')
@php
    $bundleItems = $bundle->bundleItems ?? collect();
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Detail Paket Bundling
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    {{ $bundle->bundle_name }}
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Lihat detail isi paket, ketersediaan item, dan ringkasan harga sebelum melanjutkan checkout.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#detail-bundle" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Detail
                    </a>

                    <a href="{{ route('recommendation.index') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-left me-2"></i>Kembali ke Rekomendasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="detail-bundle" class="container-fluid py-5 bg-cream">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            {{ $bundle->is_custom ? 'Paket Custom' : 'Paket Rekomendasi' }}
                        </span>

                        <h2 class="fw-bold text-dark mb-3">
                            {{ $bundle->bundle_name }}
                        </h2>

                        <p class="text-muted mb-4">
                            {{ $bundle->description ?: 'Paket bundling ini berisi kombinasi layanan yang dapat dipilih customer sesuai kebutuhan acara.' }}
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="border border-warning rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block">Kode Bundle</small>
                                    <strong class="text-dark">{{ $bundle->bundle_code ?? '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border border-warning rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block">Jenis Acara</small>
                                    <strong class="text-dark">{{ $bundle->jenis_acara ?? '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block">Kategori Adat</small>
                                    <strong class="text-dark">{{ $bundle->kategori_adat ?? '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block">Gender</small>
                                    <strong class="text-dark">{{ $bundle->gender ?? '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block">Butuh Rias</small>
                                    <strong class="text-dark">{{ $bundle->butuh_rias ? 'Ya' : 'Tidak' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <small class="text-muted d-block">Budget</small>
                                    <strong class="text-dark">{{ $bundle->budget_category ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning rounded-4 mb-0">
                            <strong>Catatan Sistem:</strong>
                            <div class="small mt-2">
                                Paket ini dipakai sebagai output rekomendasi. Pengaturan kecocokan input customer
                                tetap dilakukan melalui data Recommendation Rule oleh admin.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row g-4 align-items-center mb-4">
                            <div class="col-lg">
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                    Isi Paket
                                </span>

                                <h3 class="fw-bold text-dark mb-2">
                                    Item di Dalam Paket
                                </h3>

                                <p class="text-muted mb-0">
                                    Cek item, kategori, jumlah, varian tersedia, dan status ketersediaannya.
                                </p>
                            </div>
                        </div>

                        @if($bundleItems->count())
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Kategori</th>
                                            <th>Jenis</th>
                                            <th>Qty</th>
                                            <th>Varian Tersedia</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($bundleItems as $bundleItem)
                                            @php
                                                $item = $bundleItem->item;
                                                $availableVariants = $item
                                                    ? $item->itemVariants
                                                        ->where('is_active', true)
                                                        ->where('available_stock', '>', 0)
                                                    : collect();
                                            @endphp

                                            <tr>
                                                <td>
                                                    <div class="fw-semibold text-dark">
                                                        {{ $item->name ?? '-' }}
                                                    </div>

                                                    @if($item?->description)
                                                        <small class="text-muted">
                                                            {{ \Illuminate\Support\Str::limit($item->description, 70) }}
                                                        </small>
                                                    @endif
                                                </td>

                                                <td>{{ $item->category->cat_name ?? '-' }}</td>
                                                <td>{{ str_replace('_', ' ', $item->item_type ?? '-') }}</td>
                                                <td>{{ $bundleItem->quantity ?? 1 }}</td>

                                                <td>
                                                    @if($availableVariants->count())
                                                        @foreach($availableVariants->take(3) as $variant)
                                                            <span class="badge bg-light text-dark border rounded-pill mb-1">
                                                                {{ $variant->size ?? '-' }} / {{ $variant->color ?? '-' }}
                                                                • stok {{ $variant->available_stock }}
                                                            </span>
                                                        @endforeach

                                                        @if($availableVariants->count() > 3)
                                                            <span class="badge bg-light text-dark border rounded-pill">
                                                                +{{ $availableVariants->count() - 3 }} varian
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted small">
                                                            Belum ada varian tersedia.
                                                        </span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($availableVariants->count())
                                                        <span class="badge bg-success rounded-pill">
                                                            Tersedia
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark rounded-pill">
                                                            Konfirmasi
                                                        </span>
                                                    @endif

                                                    @if(!$bundleItem->is_required)
                                                        <span class="badge bg-secondary rounded-pill">
                                                            Opsional
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning rounded-4 mb-0">
                                Isi paket belum diatur oleh admin.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Ringkasan Harga
                        </span>

                        <div class="border border-warning rounded-4 p-4 bg-light mb-4">
                            <small class="text-muted d-block mb-1">
                                Harga Paket
                            </small>

                            <div class="fw-bold display-6 text-dark">
                                Rp{{ number_format($bundle->price ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="alert alert-warning rounded-4 mb-4">
                            <small>
                                Harga checkout mengikuti harga bundle. Harga item di dalam paket hanya sebagai data pendukung.
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.bundle.show', $bundle->id) }}" class="btn btn-dark rounded-pill px-4 py-3">
                                <i class="fa fa-cart-shopping me-2"></i>Lanjut Checkout
                            </a>

                            <a href="{{ route('recommendation.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-3">
                                <i class="fa fa-arrow-left me-2"></i>Kembali ke Rekomendasi
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Alur Sistem
                        </span>

                        <h5 class="fw-bold text-dark mb-3">
                            Cara Paket Ini Diproses
                        </h5>

                        <ol class="text-muted mb-0">
                            <li>Customer mengisi form rekomendasi.</li>
                            <li>Sistem mencocokkan input dengan rule aktif.</li>
                            <li>Rule menghasilkan bundle yang sesuai.</li>
                            <li>Detail bundle menampilkan isi item dan varian tersedia.</li>
                            <li>Customer lanjut checkout dan membuat booking.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
