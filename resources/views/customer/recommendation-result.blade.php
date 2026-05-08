@extends('customer.layouts.master')

@section('title', 'Hasil Rekomendasi - Quin Salon')

@section('content')
@php
    $hasBundle = filled($bundle);
    $targetBundle = $bundle ?? $customBundle;
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    {{ $hasBundle ? 'Rekomendasi Ditemukan' : 'Paket Custom' }}
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    {{ $hasBundle ? 'Paket yang Cocok Untuk Anda' : 'Belum Ada Rule yang Cocok' }}
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    {{ $hasBundle ? 'Hasil berikut berasal dari pencocokan rule aktif.' : 'Sistem mengarahkan Anda ke paket custom atau alternatif.' }}
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#hasil-rekomendasi" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Hasil
                    </a>

                    <a href="{{ route('recommendation.index') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-rotate-left me-2"></i>Cari Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="hasil-rekomendasi" class="container-fluid py-5 bg-cream">
    <div class="container">
        <div class="card border-0 shadow-sm rounded-4 mb-5">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4 align-items-center mb-4">
                    <div class="col-lg">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Input Customer
                        </span>

                        <h3 class="fw-bold text-dark mb-2">
                            Kriteria yang Diproses Sistem
                        </h3>

                        <p class="text-muted mb-0">
                            Data berikut digunakan untuk mencocokkan rule rekomendasi paket bundling.
                        </p>
                    </div>

                    <div class="col-lg-auto">
                        @if($selectedRule)
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                Rule Cocok
                            </span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                Fallback Paket
                            </span>
                        @endif
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border border-warning rounded-4 p-3 h-100 bg-light">
                            <small class="text-muted d-block">Jenis Acara</small>
                            <strong class="text-dark">{{ $criteria['jenis_acara'] ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border border-warning rounded-4 p-3 h-100 bg-light">
                            <small class="text-muted d-block">Kategori Adat</small>
                            <strong class="text-dark">{{ $criteria['kategori_adat'] ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border border-warning rounded-4 p-3 h-100 bg-light">
                            <small class="text-muted d-block">Gender</small>
                            <strong class="text-dark">{{ $criteria['gender'] ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100 bg-light">
                            <small class="text-muted d-block">Butuh Rias</small>
                            <strong class="text-dark">{{ !empty($criteria['butuh_rias']) ? 'Ya' : 'Tidak' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100 bg-light">
                            <small class="text-muted d-block">Budget</small>
                            <strong class="text-dark">{{ $criteria['budget'] ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                @if($selectedRule)
                    <div class="alert alert-success rounded-4 mt-4 mb-0">
                        <strong>Rule yang cocok:</strong>
                        {{ $selectedRule->rule_name }}
                        <span class="text-muted">({{ $selectedRule->rule_code }})</span>
                    </div>
                @else
                    <div class="alert alert-warning rounded-4 mt-4 mb-0">
                        <strong>Rule utama tidak ditemukan.</strong>
                        Sistem menampilkan paket yang paling mendekati atau paket custom.
                    </div>
                @endif
            </div>
        </div>

        @if($targetBundle)
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4 p-lg-5">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                {{ $targetBundle->is_custom ? 'Paket Custom' : 'Paket Rekomendasi' }}
                            </span>

                            <h2 class="fw-bold text-dark mb-3">
                                {{ $targetBundle->bundle_name }}
                            </h2>

                            <p class="text-muted mb-4">
                                {{ $targetBundle->description ?: 'Paket bundling sesuai kebutuhan acara customer.' }}
                            </p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100 bg-light">
                                        <small class="text-muted d-block">Jenis Acara</small>
                                        <strong class="text-dark">{{ $targetBundle->jenis_acara ?? '-' }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100 bg-light">
                                        <small class="text-muted d-block">Kategori Adat</small>
                                        <strong class="text-dark">{{ $targetBundle->kategori_adat ?? '-' }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100 bg-light">
                                        <small class="text-muted d-block">Gender</small>
                                        <strong class="text-dark">{{ $targetBundle->gender ?? '-' }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100 bg-light">
                                        <small class="text-muted d-block">Budget</small>
                                        <strong class="text-dark">{{ $targetBundle->budget_category ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4 p-lg-5 d-flex flex-column justify-content-between">
                            <div>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                    Ringkasan Harga
                                </span>

                                <div class="border border-warning rounded-4 p-4 bg-light mb-4">
                                    <small class="text-muted d-block mb-1">
                                        Estimasi Harga Paket
                                    </small>

                                    <div class="fw-bold display-5 text-dark">
                                        Rp{{ number_format($targetBundle->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                <p class="text-muted mb-4">
                                    Harga checkout mengikuti harga paket. Detail item dan varian dapat dicek
                                    pada halaman detail paket.
                                </p>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('bundle.show', $targetBundle->id) }}" class="btn btn-outline-dark rounded-pill py-3">
                                    <i class="fa fa-eye me-2"></i>Lihat Detail Paket
                                </a>

                                <a href="{{ route('checkout.bundle.show', $targetBundle->id) }}" class="btn btn-dark rounded-pill py-3">
                                    <i class="fa fa-cart-shopping me-2"></i>Lanjut Checkout
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
                                Isi Paket
                            </span>

                            <h3 class="fw-bold text-dark mb-2">
                                Item dan Ketersediaan Varian
                            </h3>

                            <p class="text-muted mb-0">
                                Cek item yang termasuk dalam paket serta status ketersediaan variannya.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        @forelse($targetBundle->bundleItems as $bundleItem)
                            @php
                                $item = $bundleItem->item;
                                $availableVariants = $item
                                    ? $item->itemVariants->where('is_active', true)->where('available_stock', '>', 0)
                                    : collect();
                            @endphp

                            <div class="col-md-6">
                                <div class="border rounded-4 p-4 h-100 bg-light">
                                    <div class="d-flex justify-content-between gap-3 mb-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">
                                                {{ $item->name ?? '-' }}
                                            </h6>

                                            <small class="text-muted">
                                                {{ $item->category->cat_name ?? '-' }} • Qty {{ $bundleItem->quantity }}
                                            </small>
                                        </div>

                                        @if($availableVariants->count())
                                            <span class="badge bg-success align-self-start rounded-pill">
                                                Tersedia
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark align-self-start rounded-pill">
                                                Konfirmasi
                                            </span>
                                        @endif
                                    </div>

                                    @if($availableVariants->count())
                                        <small class="text-muted d-block mb-1">Varian tersedia:</small>
                                        @foreach($availableVariants->take(3) as $variant)
                                            <span class="badge bg-white text-dark border rounded-pill me-1 mb-1">
                                                {{ $variant->size ?? '-' }} / {{ $variant->color ?? '-' }}
                                                • stok {{ $variant->available_stock }}
                                            </span>
                                        @endforeach
                                    @else
                                        <small class="text-muted">
                                            Stok varian belum tersedia atau perlu diverifikasi admin.
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning rounded-4 mb-0">
                                    Isi item pada paket ini belum diatur oleh admin.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center p-5">
                    <div class="display-5 text-muted mb-3">
                        <i class="fa fa-gift"></i>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">
                        Belum Ada Paket Custom
                    </h4>

                    <p class="text-muted mb-4">
                        Admin perlu membuat bundle dengan status custom agar sistem punya fallback rekomendasi.
                    </p>

                    <a href="{{ route('recommendation.index') }}" class="btn btn-dark rounded-pill px-4 py-3">
                        Ubah Kriteria
                    </a>
                </div>
            </div>
        @endif

        @if($alternativeBundles->count())
            <div class="mt-5">
                <div class="row justify-content-center text-center mb-4">
                    <div class="col-lg-8">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Alternatif Paket
                        </span>

                        <h3 class="fw-bold text-dark mb-2">
                            Pilihan Paket Lainnya
                        </h3>

                        <p class="text-muted mb-0">
                            Beberapa alternatif paket yang masih berkaitan dengan kebutuhan acara Anda.
                        </p>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($alternativeBundles as $alternative)
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold text-dark mb-2">
                                        {{ $alternative->bundle_name }}
                                    </h6>

                                    <small class="text-muted d-block mb-3">
                                        {{ $alternative->jenis_acara ?? '-' }} • {{ $alternative->budget_category ?? '-' }}
                                    </small>

                                    <div class="fw-bold text-dark mb-3">
                                        Rp{{ number_format($alternative->price, 0, ',', '.') }}
                                    </div>

                                    <a href="{{ route('bundle.show', $alternative->id) }}" class="btn btn-outline-dark rounded-pill w-100">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="text-center mt-5">
            <a href="{{ route('recommendation.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-3">
                <i class="fa fa-rotate-left me-2"></i>Cari Rekomendasi Lagi
            </a>
        </div>
    </div>
</section>
@endsection
