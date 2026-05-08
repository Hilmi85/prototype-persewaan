@extends('customer.layouts.master')

@section('title', 'Katalog Baju Adat - Quin Salon')

@section('content')
@php
    $sections = collect($catalogGroups ?? []);
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
                    Koleksi baju adat ditampilkan berdasarkan kategori agar pelanggan lebih mudah
                    menemukan pilihan yang sesuai dengan kebutuhan acara.
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
                                        <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        <option value="Unisex" {{ request('gender') == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label d-none d-md-block">&nbsp;</label>
                                    <button class="btn btn-dark w-100">
                                        Filter
                                    </button>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('catalog') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                            <i class="fa fa-rotate-left me-1"></i>Reset
                                        </a>

                                        <a href="{{ route('accessories.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                            <i class="fa fa-crown me-1"></i>Aksesoris
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

        @if(($totalItems ?? 0) > 0)
            <div class="row g-3 align-items-center mb-4">
                <div class="col-lg-5">
                    <h5 class="fw-bold text-dark mb-1">
                        {{ $totalItems }} Baju Adat Ditemukan
                    </h5>

                    <p class="text-muted mb-0">
                        Produk dipisahkan berdasarkan kategori agar tampilan katalog lebih rapi.
                    </p>
                </div>

                <div class="col-lg-7">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        @foreach($sections as $group)
                            <a href="#section-{{ \Illuminate\Support\Str::slug($group['label']) }}"
                               class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                {{ $group['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @forelse($sections as $group)
            <section id="section-{{ \Illuminate\Support\Str::slug($group['label']) }}" class="mb-5">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 p-3">
                                        <i class="fa fa-shirt"></i>
                                    </div>

                                    <div>
                                        <h3 class="fw-bold text-dark mb-1">
                                            {{ $group['label'] }}
                                        </h3>

                                        <p class="text-muted mb-0">
                                            {{ $group['description'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-auto">
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                    {{ $group['items']->count() }} item
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach($group['items'] as $item)
                        @php
                            $availableVariants = $item->itemVariants
                                ->where('is_active', true)
                                ->where('available_stock', '>', 0);
                        @endphp

                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="ratio ratio-3x4 bg-light border-bottom overflow-hidden">
                                    <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                         class="w-100 h-100 object-fit-contain p-2"
                                         alt="{{ $item->name }}"
                                         onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                                </div>

                                <div class="card-body d-flex flex-column p-4">
                                    <div class="d-flex flex-wrap gap-2 mb-3">
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
                                        {{ \Illuminate\Support\Str::limit($item->name, 46) }}
                                    </h5>

                                    <p class="text-muted small mb-3">
                                        {{ \Illuminate\Support\Str::limit($item->description, 86) }}
                                    </p>

                                    <div class="bg-light border rounded-3 p-3 mt-auto mb-3">
                                        <small class="text-muted d-block mb-1">Mulai dari</small>

                                        <div class="fw-bold text-dark">
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="row g-2 justify-content-end">
                                        <div class="col-7">
                                            <a href="{{ route('catalog.show', $item->id) }}" class="btn btn-outline-dark w-100 rounded-pill">
                                                <i class="fa fa-eye me-1"></i>Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
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
        @endforelse
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
