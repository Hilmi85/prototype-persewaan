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
@endphp

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
                    <a href="#katalog-baju-adat" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Katalog
                    </a>

                    <a href="{{ route('recommendation.index') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
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

        <div class="card border-0 shadow-sm rounded-4 mb-5">
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
                                    <button class="btn btn-dark w-100">
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
                    <span class="badge bg-dark rounded-pill px-3 py-2">
                        {{ $sections->count() }} collection tersedia
                    </span>
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

                        $accordionId = 'collection-' . \Illuminate\Support\Str::slug($group['label']);
                        $headingId = 'heading-' . \Illuminate\Support\Str::slug($group['label']);
                        $collapseId = 'collapse-' . \Illuminate\Support\Str::slug($group['label']);

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
                                    <div class="row g-4 align-items-stretch">
                                        <div class="col-lg-6">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                                <div class="position-relative">
                                                    <div class="ratio ratio-1x1 bg-light overflow-hidden">
                                                        <img src="{{ asset('img_item_upload/' . ($highlightItem->img ?? 'default.jpg')) }}"
                                                             class="w-100 h-100 object-fit-cover"
                                                             alt="{{ $highlightItem->name }}"
                                                             onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                                                    </div>

                                                    <div class="position-absolute top-0 start-0 m-3">
                                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                            Signature Look
                                                        </span>
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
                                                                {{ $highlightItem->gender }}
                                                            </span>
                                                        @endif

                                                        @if($highlightVariants->count())
                                                            <span class="badge bg-success rounded-pill">
                                                                {{ $highlightVariants->count() }} varian tersedia
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning text-dark rounded-pill">
                                                                Konfirmasi stok
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <p class="text-muted mb-4">
                                                        {{ \Illuminate\Support\Str::limit($highlightItem->description, 180) }}
                                                    </p>

                                                    <div class="border border-warning rounded-4 bg-light p-3 mb-4">
                                                        <small class="text-muted d-block mb-1">
                                                            Mulai dari
                                                        </small>

                                                        <div class="fw-bold fs-4 text-dark">
                                                            Rp{{ number_format($highlightItem->price, 0, ',', '.') }}
                                                        </div>
                                                    </div>

                                                    <a href="{{ route('catalog.show', $highlightItem->id) }}" class="btn btn-dark rounded-pill px-4 py-3">
                                                        <i class="fa fa-eye me-2"></i>Lihat Detail Signature Look
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="card border-0 shadow-sm rounded-4 h-100">
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
                                                    </div>

                                                    @if($otherItems->count())
                                                        <div class="vstack gap-3">
                                                            @foreach($otherItems as $item)
                                                                @php
                                                                    $availableVariants = $item->itemVariants
                                                                        ->where('is_active', true)
                                                                        ->where('available_stock', '>', 0);
                                                                @endphp

                                                                <div class="card border rounded-4 shadow-sm overflow-hidden">
                                                                    <div class="row g-0 align-items-stretch">
                                                                        <div class="col-4">
                                                                            <div class="ratio ratio-1x1 bg-light h-100">
                                                                                <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                                                                     class="w-100 h-100 object-fit-cover"
                                                                                     alt="{{ $item->name }}"
                                                                                     onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                                                                            </div>
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
                                                                                            Konfirmasi stok
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
</script>
@endsection
