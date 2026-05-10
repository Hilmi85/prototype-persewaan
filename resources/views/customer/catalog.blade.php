@extends('customer.layouts.master')

@section('title', 'Katalog Baju Adat - Quin Salon')

@section('content')
@php
    $rawItems = isset($items)
        ? collect(method_exists($items, 'items') ? $items->items() : $items)
        : collect();

    $fallbackGroups = $rawItems
        ->where('item_type', 'baju_adat')
        ->values()
        ->groupBy(function ($item) {
            return $item->category->cat_name
                ?? ($item->adat_category ? 'Baju Adat ' . $item->adat_category : 'Baju Adat Lainnya');
        })
        ->map(function ($groupItems, $groupName) {
            return [
                'label' => $groupName,
                'description' => 'Pilihan koleksi ' . strtolower($groupName) . ' yang tersedia di Quin Salon.',
                'items' => $groupItems->values(),
            ];
        })
        ->values();

    $sections = isset($catalogGroups) ? collect($catalogGroups) : $fallbackGroups;
    $displayTotal = $totalItems ?? $rawItems->where('item_type', 'baju_adat')->count();

    $hasActiveFilter = request('keyword') || request('category_id') || request('gender');
@endphp

<style>
    .catalog-product-card {
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .catalog-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .75rem 1.75rem rgba(0, 0, 0, .10) !important;
        border-color: rgba(33, 37, 41, .25) !important;
    }

    .catalog-product-img {
        transition: transform .25s ease;
    }

    .catalog-product-card:hover .catalog-product-img {
        transform: scale(1.04);
    }

    .catalog-soft-panel {
        background: linear-gradient(135deg, rgba(255, 193, 7, .16), rgba(255, 255, 255, .85));
        border: 1px solid rgba(255, 193, 7, .28);
    }

    .catalog-mini-info {
        background: rgba(33, 37, 41, .04);
        border: 1px dashed rgba(33, 37, 41, .18);
    }

    .catalog-action-btn {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .catalog-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .12);
    }

    @media (max-width: 991.98px) {
        .catalog-signature-card {
            margin-bottom: 1rem;
        }
    }
</style>

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Quin Salon • Katalog Baju Adat
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    Pilih Baju Adat Sesuai Momen Spesial Anda
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Koleksi baju adat ditampilkan dalam format collection boutique agar pelanggan
                    lebih mudah melihat pilihan utama dan koleksi lainnya secara elegan.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#katalog-baju-adat" class="btn btn-dark rounded-pill px-4 py-3 catalog-action-btn">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Katalog
                    </a>

                    <a href="{{ route('recommendation.index') }}" class="btn btn-outline-light rounded-pill px-4 py-3 catalog-action-btn">
                        <i class="fa fa-gift me-2"></i>Coba Rekomendasi Paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="katalog-baju-adat" class="container-fluid py-5 bg-cream">
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

        <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4 align-items-end">
                    <div class="col-lg-4">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Filter Katalog
                        </span>

                        <h4 class="fw-bold text-dark mb-2">
                            Temukan Koleksi
                        </h4>

                        <p class="text-muted mb-0">
                            Gunakan pencarian dan filter untuk memilih baju adat berdasarkan kategori
                            atau gender.
                        </p>
                    </div>

                    <div class="col-lg-8">
                        <form method="GET" action="{{ route('catalog') }}">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Cari Item</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fa fa-search text-muted"></i>
                                        </span>

                                        <input type="text"
                                               name="keyword"
                                               value="{{ request('keyword') }}"
                                               class="form-control"
                                               placeholder="Nama baju, adat, atau deskripsi...">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Kategori</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">Semua</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->cat_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>
                                        <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan
                                        </option>
                                        <option value="Unisex" {{ request('gender') == 'Unisex' ? 'selected' : '' }}>
                                            Unisex
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label d-none d-md-block">&nbsp;</label>
                                    <button class="btn btn-dark w-100 catalog-action-btn">
                                        <i class="fa fa-search me-1"></i>Filter
                                    </button>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('catalog') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                            <i class="fa fa-rotate-left me-1"></i>Reset
                                        </a>

                                        <a href="{{ route('rias.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                            <i class="fa fa-wand-magic-sparkles me-1"></i>Jasa Rias
                                        </a>

                                        <a href="{{ route('recommendation.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                            <i class="fa fa-gift me-1"></i>Rekomendasi
                                        </a>
                                    </div>

                                    @if($hasActiveFilter)
                                        <div class="alert alert-warning rounded-4 py-2 px-3 mt-3 mb-0">
                                            <small class="fw-semibold text-dark">
                                                <i class="fa fa-filter me-1"></i>
                                                Filter sedang aktif. Klik reset untuk melihat semua koleksi.
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($displayTotal > 0)
            <div class="row g-3 align-items-center mb-4">
                <div class="col-lg-6">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                        Luxury Collection
                    </span>

                    <h4 class="fw-bold text-dark mb-1">
                        {{ $displayTotal }} Baju Adat Ditemukan
                    </h4>

                    <p class="text-muted mb-0">
                        Pilih collection di bawah untuk melihat item unggulan dan pilihan lainnya.
                    </p>
                </div>

                <div class="col-lg-6 text-lg-end">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <span class="badge bg-dark rounded-pill px-3 py-2">
                            {{ $sections->count() }} collection tersedia
                        </span>

                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                            Klik detail untuk melihat stok dan informasi lengkap
                        </span>
                    </div>
                </div>
            </div>
        @endif

        @if($sections->count())
            <div class="accordion" id="catalogLuxuryAccordion">
                @foreach($sections as $group)
                    @php
                        $sectionItems = collect($group['items']);
                        $highlightItem = $sectionItems->first();
                        $otherItems = $sectionItems->slice(1)->values();

                        $initialOtherLimit = 3;
                        $visibleOtherItems = $otherItems->take($initialOtherLimit);
                        $hiddenOtherItems = $otherItems->slice($initialOtherLimit)->values();
                        $remainingOtherCount = $hiddenOtherItems->count();

                        $accordionId = 'collection-' . \Illuminate\Support\Str::slug($group['label']) . '-' . $loop->iteration;
                        $headingId = 'heading-' . \Illuminate\Support\Str::slug($group['label']) . '-' . $loop->iteration;
                        $collapseId = 'collapse-' . \Illuminate\Support\Str::slug($group['label']) . '-' . $loop->iteration;
                        $moreLooksCollapseId = 'more-looks-' . \Illuminate\Support\Str::slug($group['label']) . '-' . $loop->iteration;

                        $highlightVariants = $highlightItem
                            ? $highlightItem->itemVariants
                                ->where('is_active', true)
                                ->where('available_stock', '>', 0)
                            : collect();
                    @endphp

                    <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-4" id="{{ $accordionId }}">
                        <h2 class="accordion-header" id="{{ $headingId }}">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} bg-white shadow-none p-4"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ $collapseId }}"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-controls="{{ $collapseId }}">
                                <div class="w-100">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-lg">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 p-3">
                                                    <i class="fa fa-shirt"></i>
                                                </div>

                                                <div>
                                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-2">
                                                        Collection {{ $loop->iteration }}
                                                    </span>

                                                    <h3 class="fw-bold text-dark mb-1">
                                                        {{ $group['label'] }}
                                                    </h3>

                                                    <p class="text-muted mb-0">
                                                        {{ $group['description'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-auto text-lg-end">
                                            <span class="badge bg-dark rounded-pill px-3 py-2">
                                                {{ $sectionItems->count() }} item
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </h2>

                        <div id="{{ $collapseId }}"
                             class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                             aria-labelledby="{{ $headingId }}"
                             data-bs-parent="#catalogLuxuryAccordion">
                            <div class="accordion-body bg-light p-4 p-lg-5">
                                @if($highlightItem)
                                    <div class="row g-4 align-items-start">
                                        <div class="col-lg-6">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden catalog-signature-card catalog-product-card">
                                                <div class="position-relative">
                                                    <div class="ratio ratio-1x1 bg-light overflow-hidden">
                                                        <img src="{{ asset('img_item_upload/' . ($highlightItem->img ?? 'default.jpg')) }}"
                                                             class="w-100 h-100 object-fit-cover catalog-product-img"
                                                             alt="{{ $highlightItem->name }}"
                                                             loading="lazy"
                                                             onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                                                    </div>

                                                    <div class="position-absolute top-0 start-0 m-3">
                                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                            Signature Look
                                                        </span>
                                                    </div>

                                                    <div class="position-absolute top-0 end-0 m-3">
                                                        @if($highlightVariants->count())
                                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                                Tersedia
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                                Cek Stok
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="position-absolute bottom-0 start-0 end-0 p-4 bg-dark bg-opacity-75">
                                                        <div class="d-flex justify-content-between align-items-end gap-3">
                                                            <div>
                                                                <small class="text-white-50 d-block mb-1">
                                                                    Highlight Collection
                                                                </small>

                                                                <h3 class="fw-bold text-white mb-0">
                                                                    {{ $highlightItem->name }}
                                                                </h3>
                                                            </div>

                                                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                                                {{ $highlightItem->category->cat_name ?? 'Baju Adat' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-body p-4 p-lg-5">
                                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                                        @if($highlightItem->gender)
                                                            <span class="badge bg-light text-dark border rounded-pill">
                                                                <i class="fa fa-user me-1"></i>{{ $highlightItem->gender }}
                                                            </span>
                                                        @endif

                                                        @if($highlightVariants->count())
                                                            <span class="badge bg-success rounded-pill">
                                                                <i class="fa fa-circle-check me-1"></i>{{ $highlightVariants->count() }} varian tersedia
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning text-dark rounded-pill">
                                                                <i class="fa fa-circle-info me-1"></i>Konfirmasi stok
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <p class="text-muted mb-4">
                                                        {{ \Illuminate\Support\Str::limit($highlightItem->description, 180) }}
                                                    </p>

                                                    <div class="catalog-soft-panel rounded-4 p-3 mb-4">
                                                        <small class="text-muted d-block mb-1">
                                                            Mulai dari
                                                        </small>

                                                        <div class="fw-bold fs-4 text-dark">
                                                            Rp{{ number_format($highlightItem->price, 0, ',', '.') }}
                                                        </div>
                                                    </div>

                                                    <div class="d-grid gap-2">
                                                        <a href="{{ route('catalog.show', $highlightItem->id) }}" class="btn btn-dark rounded-pill px-4 py-3 catalog-action-btn">
                                                            <i class="fa fa-eye me-2"></i>Lihat Detail Signature Look
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="card border-0 shadow-sm rounded-4">
                                                <div class="card-body p-4 p-lg-5">
                                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                                        <div>
                                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                                                More Looks
                                                            </span>

                                                            <h4 class="fw-bold text-dark mb-2">
                                                                Pilihan Lainnya
                                                            </h4>

                                                            <p class="text-muted mb-0">
                                                                Koleksi lain dari kategori {{ $group['label'] }} yang bisa dipilih pelanggan.
                                                            </p>
                                                        </div>

                                                        @if($otherItems->count())
                                                            <span class="badge bg-dark rounded-pill px-3 py-2 flex-shrink-0">
                                                                {{ $otherItems->count() }} pilihan
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if($otherItems->count())
                                                        <div class="catalog-mini-info rounded-4 p-3 mb-4">
                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                <small class="text-muted">
                                                                    <i class="fa fa-layer-group me-1"></i>
                                                                    Ditampilkan awal {{ min($initialOtherLimit, $otherItems->count()) }} dari {{ $otherItems->count() }} pilihan.
                                                                </small>

                                                                @if($remainingOtherCount > 0)
                                                                    <small class="fw-semibold text-dark">
                                                                        {{ $remainingOtherCount }} produk lainnya tersedia
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="vstack gap-3">
                                                            @foreach($visibleOtherItems as $item)
                                                                @php
                                                                    $availableVariants = $item->itemVariants
                                                                        ->where('is_active', true)
                                                                        ->where('available_stock', '>', 0);
                                                                @endphp

                                                                <div class="card border rounded-4 shadow-sm overflow-hidden catalog-product-card">
                                                                    <div class="row g-0 align-items-stretch">
                                                                        <div class="col-4">
                                                                            <a href="{{ route('catalog.show', $item->id) }}" class="d-block h-100">
                                                                                <div class="ratio ratio-1x1 bg-light h-100 overflow-hidden">
                                                                                    <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                                                                         class="w-100 h-100 object-fit-cover catalog-product-img"
                                                                                         alt="{{ $item->name }}"
                                                                                         loading="lazy"
                                                                                         onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                                                                                </div>
                                                                            </a>
                                                                        </div>

                                                                        <div class="col-8">
                                                                            <div class="card-body p-3 p-md-4 h-100 d-flex flex-column">
                                                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                                                    <span class="badge bg-dark rounded-pill">
                                                                                        {{ $item->category->cat_name ?? 'Baju Adat' }}
                                                                                    </span>

                                                                                    @if($item->gender)
                                                                                        <span class="badge bg-light text-dark border rounded-pill">
                                                                                            {{ $item->gender }}
                                                                                        </span>
                                                                                    @endif

                                                                                    @if($availableVariants->count())
                                                                                        <span class="badge bg-success rounded-pill">
                                                                                            {{ $availableVariants->count() }} varian
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="badge bg-warning text-dark rounded-pill">
                                                                                            Cek stok
                                                                                        </span>
                                                                                    @endif
                                                                                </div>

                                                                                <h5 class="fw-bold text-dark mb-2">
                                                                                    {{ \Illuminate\Support\Str::limit($item->name, 48) }}
                                                                                </h5>

                                                                                <p class="text-muted small mb-3">
                                                                                    {{ \Illuminate\Support\Str::limit($item->description, 75) }}
                                                                                </p>

                                                                                <div class="d-flex justify-content-between align-items-center gap-3 mt-auto">
                                                                                    <div>
                                                                                        <small class="text-muted d-block">
                                                                                            Mulai dari
                                                                                        </small>

                                                                                        <strong class="text-dark">
                                                                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                                                                        </strong>
                                                                                    </div>

                                                                                    <a href="{{ route('catalog.show', $item->id) }}"
                                                                                       class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                                                                        Detail
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                            @if($hiddenOtherItems->count())
                                                                <div class="collapse" id="{{ $moreLooksCollapseId }}">
                                                                    <div class="vstack gap-3 mt-3">
                                                                        @foreach($hiddenOtherItems as $item)
                                                                            @php
                                                                                $availableVariants = $item->itemVariants
                                                                                    ->where('is_active', true)
                                                                                    ->where('available_stock', '>', 0);
                                                                            @endphp

                                                                            <div class="card border rounded-4 shadow-sm overflow-hidden catalog-product-card">
                                                                                <div class="row g-0 align-items-stretch">
                                                                                    <div class="col-4">
                                                                                        <a href="{{ route('catalog.show', $item->id) }}" class="d-block h-100">
                                                                                            <div class="ratio ratio-1x1 bg-light h-100 overflow-hidden">
                                                                                                <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                                                                                     class="w-100 h-100 object-fit-cover catalog-product-img"
                                                                                                     alt="{{ $item->name }}"
                                                                                                     loading="lazy"
                                                                                                     onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                                                                                            </div>
                                                                                        </a>
                                                                                    </div>

                                                                                    <div class="col-8">
                                                                                        <div class="card-body p-3 p-md-4 h-100 d-flex flex-column">
                                                                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                                                                <span class="badge bg-dark rounded-pill">
                                                                                                    {{ $item->category->cat_name ?? 'Baju Adat' }}
                                                                                                </span>

                                                                                                @if($item->gender)
                                                                                                    <span class="badge bg-light text-dark border rounded-pill">
                                                                                                        {{ $item->gender }}
                                                                                                    </span>
                                                                                                @endif

                                                                                                @if($availableVariants->count())
                                                                                                    <span class="badge bg-success rounded-pill">
                                                                                                        {{ $availableVariants->count() }} varian
                                                                                                    </span>
                                                                                                @else
                                                                                                    <span class="badge bg-warning text-dark rounded-pill">
                                                                                                        Cek stok
                                                                                                    </span>
                                                                                                @endif
                                                                                            </div>

                                                                                            <h5 class="fw-bold text-dark mb-2">
                                                                                                {{ \Illuminate\Support\Str::limit($item->name, 48) }}
                                                                                            </h5>

                                                                                            <p class="text-muted small mb-3">
                                                                                                {{ \Illuminate\Support\Str::limit($item->description, 75) }}
                                                                                            </p>

                                                                                            <div class="d-flex justify-content-between align-items-center gap-3 mt-auto">
                                                                                                <div>
                                                                                                    <small class="text-muted d-block">
                                                                                                        Mulai dari
                                                                                                    </small>

                                                                                                    <strong class="text-dark">
                                                                                                        Rp{{ number_format($item->price, 0, ',', '.') }}
                                                                                                    </strong>
                                                                                                </div>

                                                                                                <a href="{{ route('catalog.show', $item->id) }}"
                                                                                                   class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                                                                                    Detail
                                                                                                </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        @if($remainingOtherCount > 0)
                                                            <div class="text-center mt-4">
                                                                <button class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold js-toggle-more-looks catalog-action-btn"
                                                                        type="button"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#{{ $moreLooksCollapseId }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="{{ $moreLooksCollapseId }}"
                                                                        data-show-text="TAMPILKAN SEMUANYA ({{ $remainingOtherCount }} LAGI)"
                                                                        data-hide-text="TAMPILKAN LEBIH SEDIKIT">
                                                                    <i class="fa fa-chevron-down me-2"></i>
                                                                    <span>TAMPILKAN SEMUANYA ({{ $remainingOtherCount }} LAGI)</span>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="text-center py-5">
                                                            <div class="display-5 text-muted mb-3">
                                                                <i class="fa fa-shirt"></i>
                                                            </div>

                                                            <h5 class="fw-bold text-dark mb-2">
                                                                Hanya Ada Satu Koleksi
                                                            </h5>

                                                            <p class="text-muted mb-4">
                                                                Kategori ini masih memiliki satu koleksi utama.
                                                            </p>

                                                            <a href="{{ route('catalog.show', $highlightItem->id) }}" class="btn btn-outline-dark rounded-pill px-4">
                                                                Lihat Detail
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card border-0 shadow-sm rounded-4">
                                        <div class="card-body text-center p-5">
                                            <div class="display-5 text-muted mb-3">
                                                <i class="fa fa-box-open"></i>
                                            </div>

                                            <h4 class="fw-bold text-dark mb-2">
                                                Koleksi belum tersedia
                                            </h4>

                                            <p class="text-muted mb-0">
                                                Belum ada item pada collection ini.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center p-5">
                    <div class="display-5 text-muted mb-3">
                        <i class="fa fa-box-open"></i>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">
                        Baju adat tidak ditemukan
                    </h4>

                    <p class="text-muted mb-4">
                        Coba ubah kata kunci pencarian atau reset filter katalog.
                    </p>

                    <a href="{{ route('catalog') }}" class="btn btn-dark rounded-pill px-4">
                        Reset Filter
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('script')
<script>
    setTimeout(function () {
        const alertBox = document.querySelector('.alert.alert-success, .alert.alert-danger');

        if (alertBox) {
            bootstrap.Alert.getOrCreateInstance(alertBox).close();
        }
    }, 3000);

    document.querySelectorAll('.js-toggle-more-looks').forEach(function (button) {
        const targetSelector = button.getAttribute('data-bs-target');
        const target = document.querySelector(targetSelector);

        if (!target) {
            return;
        }

        target.addEventListener('shown.bs.collapse', function () {
            const icon = button.querySelector('i');
            const text = button.querySelector('span');

            button.setAttribute('aria-expanded', 'true');

            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }

            if (text) {
                text.textContent = button.dataset.hideText || 'TAMPILKAN LEBIH SEDIKIT';
            }
        });

        target.addEventListener('hidden.bs.collapse', function () {
            const icon = button.querySelector('i');
            const text = button.querySelector('span');

            button.setAttribute('aria-expanded', 'false');

            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }

            if (text) {
                text.textContent = button.dataset.showText || 'TAMPILKAN SEMUANYA';
            }
        });
    });
</script>
@endsection
