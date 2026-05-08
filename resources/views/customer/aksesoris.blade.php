@extends('customer.layouts.master')

@section('title', 'Aksesoris - Quin Salon')

@section('content')
<div class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5 text-center">
        <span class="badge badge-glass rounded-pill px-4 py-2 mb-3">
            Quin Salon • Aksesoris
        </span>

        <h1 class="display-4 text-white fw-bold">Katalog Aksesoris</h1>

        <p class="text-white mb-0">
            Pilih aksesoris pendukung agar tampilan baju adat semakin serasi dan elegan.
        </p>
    </div>
</div>

<div class="container-fluid py-5 section-cream">
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

        <div class="catalog-toolbar-card rounded-4 p-4 p-lg-5 mb-5">
            <div class="row g-4 align-items-end">
                <div class="col-lg-4">
                    <h4 class="fw-bold text-brand-dark mb-2">Cari Aksesoris</h4>
                    <p class="text-muted mb-0 lh-comfy">
                        Halaman ini khusus untuk aksesoris, tidak dicampur dengan baju adat atau jasa rias.
                    </p>
                </div>

                <div class="col-lg-8">
                    <form method="GET" action="{{ route('accessories.index') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cari Aksesoris</label>
                                <input type="text"
                                       name="keyword"
                                       value="{{ request('keyword') }}"
                                       class="form-control rounded-3"
                                       placeholder="Nama aksesoris atau deskripsi...">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Kategori</label>
                                <select name="category_id" class="form-select rounded-3">
                                    <option value="">Semua</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->cat_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <button class="btn btn-brand w-100 rounded-3 mt-md-4 py-2">
                                    <i class="fa fa-search me-1"></i>Filter
                                </button>
                            </div>

                            <div class="col-12">
                                <a href="{{ route('accessories.index') }}" class="btn btn-brand-outline rounded-pill px-4 py-2">
                                    <i class="fa fa-rotate-left me-1"></i>Reset Filter
                                </a>
                                <a href="{{ route('catalog') }}" class="btn btn-brand-outline rounded-pill px-4 py-2 ms-2">
                                    <i class="fa fa-shirt me-1"></i>Baju Adat
                                </a>
                                <a href="{{ route('rias.index') }}" class="btn btn-brand-outline rounded-pill px-4 py-2 ms-2">
                                    <i class="fa fa-wand-magic-sparkles me-1"></i>Jasa Rias
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4 catalog-grid">
            @forelse ($items as $item)
                @php
                    $availableVariants = $item->itemVariants
                        ->where('is_active', true)
                        ->where('available_stock', '>', 0);
                @endphp

                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card catalog-card catalog-card-compact border-0 h-100 overflow-hidden">
                        <div class="catalog-card-media position-relative overflow-hidden">
                            <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                 alt="{{ $item->name }}"
                                 onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">

                            <span class="badge catalog-type-pill rounded-pill px-3 py-2">
                                Aksesoris
                            </span>
                        </div>

                        <div class="card-body catalog-card-body d-flex flex-column">
                            <div class="catalog-meta-chips">
                                <span class="catalog-chip">{{ $item->category->cat_name ?? 'Aksesoris' }}</span>

                                @if($availableVariants->count())
                                    <span class="catalog-chip catalog-chip-success">
                                        {{ $availableVariants->count() }} varian
                                    </span>
                                @else
                                    <span class="catalog-chip catalog-chip-warning">
                                        Konfirmasi stok
                                    </span>
                                @endif
                            </div>

                            <h5 class="catalog-title-compact fw-bold mb-2">
                                {{ $item->name }}
                            </h5>

                            <p class="catalog-desc-compact mb-3">
                                {{ \Illuminate\Support\Str::limit($item->description, 90) }}
                            </p>

                            <div class="catalog-price-box rounded-4 p-3 mb-3 mt-auto">
                                <small class="text-muted d-block mb-1">Mulai dari</small>
                                <div class="price-text fw-bold">
                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="catalog-actions row g-2">
                                <div class="col-6">
                                    <a href="{{ route('catalog.show', $item->id) }}" class="btn btn-brand w-100 rounded-pill">
                                        <i class="fa fa-eye me-1"></i>Detail
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="{{ route('catalog.show', $item->id) }}" class="btn btn-brand-outline w-100 rounded-pill">
                                        <i class="fa fa-list me-1"></i>Pilih
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="catalog-empty-state rounded-4 p-5 text-center">
                        <i class="fa fa-crown empty-icon mb-3"></i>
                        <h4 class="fw-bold text-brand-dark">Aksesoris belum tersedia</h4>
                        <p class="text-muted mb-0">Silakan tambahkan item aksesoris aktif melalui halaman admin.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
