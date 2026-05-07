@extends('customer.layouts.master')

@section('title', 'Hasil Rekomendasi - Quin Salon')

@section('content')
@php
    $hasBundle = filled($bundle);
    $targetBundle = $bundle ?? $customBundle;
@endphp

<style>
    .result-page {
        background: linear-gradient(135deg, #fffaf5 0%, #f8efe5 100%);
        min-height: 100vh;
        padding-top: 7rem;
        padding-bottom: 4rem;
    }

    .result-card {
        background: white;
        border: 1px solid rgba(139, 94, 60, .16);
        border-radius: 28px;
        box-shadow: 0 22px 60px rgba(60, 42, 33, .10);
        overflow: hidden;
    }

    .result-header {
        background: linear-gradient(135deg, #8b5e3c, #c79358);
        color: white;
        padding: 2rem;
    }

    .criteria-box {
        background: #fffaf5;
        border: 1px solid #ead7c0;
        border-radius: 18px;
        padding: 1rem;
    }

    .bundle-item-box {
        border: 1px solid #ead7c0;
        border-radius: 18px;
        padding: 1rem;
        background: #fffaf5;
        transition: .25s ease;
    }

    .bundle-item-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(139, 94, 60, .12);
    }

    .btn-brown {
        background: linear-gradient(135deg, #8b5e3c, #c79358);
        color: #fff;
        border: 0;
        transition: .25s ease;
    }

    .btn-brown:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 16px 32px rgba(139, 94, 60, .22);
    }
</style>

<div class="result-page">
    <div class="container">
        <div class="result-card">
            <div class="result-header text-center">
                <span class="badge bg-light text-dark rounded-pill px-3 py-2 mb-3">
                    {{ $hasBundle ? 'Rekomendasi Ditemukan' : 'Paket Custom' }}
                </span>
                <h2 class="fw-bold mb-2">
                    {{ $hasBundle ? 'Paket yang Cocok Untuk Anda' : 'Belum Ada Rule yang Cocok' }}
                </h2>
                <p class="mb-0 opacity-75">
                    {{ $hasBundle ? 'Hasil berikut berasal dari pencocokan rule aktif.' : 'Sistem mengarahkan Anda ke paket custom atau alternatif.' }}
                </p>
            </div>

            <div class="p-4 p-lg-5">
                <h5 class="fw-bold mb-3">Input Customer</h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="criteria-box h-100">
                            <small class="text-muted d-block">Jenis Acara</small>
                            <strong>{{ $criteria['jenis_acara'] ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="criteria-box h-100">
                            <small class="text-muted d-block">Kategori Adat</small>
                            <strong>{{ $criteria['kategori_adat'] ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="criteria-box h-100">
                            <small class="text-muted d-block">Gender</small>
                            <strong>{{ $criteria['gender'] ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="criteria-box h-100">
                            <small class="text-muted d-block">Butuh Rias</small>
                            <strong>{{ !empty($criteria['butuh_rias']) ? 'Ya' : 'Tidak' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="criteria-box h-100">
                            <small class="text-muted d-block">Budget</small>
                            <strong>{{ $criteria['budget'] ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                @if($selectedRule)
                    <div class="alert alert-success rounded-4">
                        <strong>Rule yang cocok:</strong>
                        {{ $selectedRule->rule_name }}
                        <span class="text-muted">({{ $selectedRule->rule_code }})</span>
                    </div>
                @else
                    <div class="alert alert-warning rounded-4">
                        <strong>Rule utama tidak ditemukan.</strong>
                        Sistem menampilkan paket yang paling mendekati atau paket custom.
                    </div>
                @endif

                @if($targetBundle)
                    <div class="row g-4 align-items-stretch">
                        <div class="col-lg-7">
                            <div class="criteria-box h-100">
                                <h3 class="fw-bold mb-2" style="color:#3c2a21;">
                                    {{ $targetBundle->bundle_name }}
                                </h3>

                                <p class="text-muted">
                                    {{ $targetBundle->description ?: 'Paket bundling sesuai kebutuhan acara customer.' }}
                                </p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Jenis Acara</small>
                                        <strong>{{ $targetBundle->jenis_acara ?? '-' }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Kategori Adat</small>
                                        <strong>{{ $targetBundle->kategori_adat ?? '-' }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Gender</small>
                                        <strong>{{ $targetBundle->gender ?? '-' }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Budget</small>
                                        <strong>{{ $targetBundle->budget_category ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="criteria-box h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <small class="text-muted d-block">Estimasi Harga Paket</small>
                                    <div class="fw-bold display-6" style="color:#8b5e3c;">
                                        Rp{{ number_format($targetBundle->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <a href="{{ route('bundle.show', $targetBundle->id) }}" class="btn btn-outline-secondary rounded-pill py-3">
                                        Lihat Detail Paket
                                    </a>

                                    <a href="{{ route('checkout.bundle.show', $targetBundle->id) }}" class="btn btn-brown rounded-pill py-3 fw-bold">
                                        Lanjut Checkout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h5 class="fw-bold mb-3">Isi Paket dan Ketersediaan Varian</h5>

                        <div class="row g-3">
                            @forelse($targetBundle->bundleItems as $bundleItem)
                                @php
                                    $item = $bundleItem->item;
                                    $availableVariants = $item
                                        ? $item->itemVariants->where('is_active', true)->where('available_stock', '>', 0)
                                        : collect();
                                @endphp

                                <div class="col-md-6">
                                    <div class="bundle-item-box h-100">
                                        <div class="d-flex justify-content-between gap-3 mb-2">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $item->name ?? '-' }}</h6>
                                                <small class="text-muted">
                                                    {{ $item->category->cat_name ?? '-' }} • Qty {{ $bundleItem->quantity }}
                                                </small>
                                            </div>

                                            @if($availableVariants->count())
                                                <span class="badge bg-success align-self-start">Tersedia</span>
                                            @else
                                                <span class="badge bg-danger align-self-start">Perlu Konfirmasi</span>
                                            @endif
                                        </div>

                                        @if($availableVariants->count())
                                            <small class="text-muted d-block mb-1">Varian tersedia:</small>
                                            @foreach($availableVariants->take(3) as $variant)
                                                <span class="badge bg-light text-dark border me-1 mb-1">
                                                    {{ $variant->size ?? '-' }} / {{ $variant->color ?? '-' }}
                                                    stok {{ $variant->available_stock }}
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
                @else
                    <div class="text-center py-5">
                        <h4 class="fw-bold mb-2">Belum Ada Paket Custom</h4>
                        <p class="text-muted">
                            Admin perlu membuat bundle dengan status custom agar sistem punya fallback rekomendasi.
                        </p>
                        <a href="{{ route('recommendation.index') }}" class="btn btn-brown rounded-pill px-4 py-3">
                            Ubah Kriteria
                        </a>
                    </div>
                @endif

                @if($alternativeBundles->count())
                    <div class="mt-5">
                        <h5 class="fw-bold mb-3">Alternatif Paket</h5>

                        <div class="row g-3">
                            @foreach($alternativeBundles as $alternative)
                                <div class="col-md-6 col-lg-3">
                                    <div class="criteria-box h-100">
                                        <h6 class="fw-bold">{{ $alternative->bundle_name }}</h6>
                                        <small class="text-muted d-block mb-2">
                                            {{ $alternative->jenis_acara ?? '-' }} • {{ $alternative->budget_category ?? '-' }}
                                        </small>
                                        <strong style="color:#8b5e3c;">
                                            Rp{{ number_format($alternative->price, 0, ',', '.') }}
                                        </strong>
                                        <a href="{{ route('bundle.show', $alternative->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill w-100 mt-3">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="text-center mt-5">
                    <a href="{{ route('recommendation.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-3">
                        Cari Rekomendasi Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
