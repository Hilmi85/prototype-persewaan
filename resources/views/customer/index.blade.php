@extends('customer.layouts.master')

@section('title', 'Beranda - Quin Salon')

@section('content')
@php
    $previewBundles = $featuredBundles->take(2);
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Quin Salon • Baju Adat & Jasa Rias
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    Tampil Anggun, Serasi, dan Percaya Diri di Momen Spesial
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Quin Salon menyediakan persewaan baju adat, aksesoris, jasa rias, dan paket rekomendasi
                    untuk membantu pelanggan tampil lebih berkesan dalam acara wisuda, lamaran, pernikahan,
                    maupun kebutuhan acara adat lainnya.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#koleksi-unggulan" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Katalog
                    </a>

                    <a href="{{ route('recommendation.index') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-gift me-2"></i>Coba Rekomendasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid py-5 bg-cream">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Tentang Quin Salon
                        </span>

                        <h2 class="fw-bold text-dark mb-3">
                            Solusi Penampilan Adat yang Lebih Praktis dan Elegan
                        </h2>

                        <p class="text-muted mb-4">
                            Quin Salon hadir untuk membantu pelanggan menemukan tampilan terbaik melalui
                            koleksi baju adat, aksesoris, layanan rias, hingga rekomendasi paket.
                            Semua ditata agar proses memilih produk menjadi lebih nyaman, rapi, dan mudah dipahami.
                        </p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="fa fa-location-dot text-warning me-2"></i>Lokasi
                                    </h6>
                                    <p class="text-muted mb-0">
                                        Jombang, Jawa Timur
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100 bg-light">
                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="fa fa-star text-warning me-2"></i>Layanan Utama
                                    </h6>
                                    <p class="text-muted mb-0">
                                        Baju adat, aksesoris, jasa rias, dan paket rekomendasi
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('catalog') }}" class="btn btn-dark rounded-pill px-4">
                                <i class="fa fa-shirt me-2"></i>Baju Adat
                            </a>

                            <a href="{{ route('accessories.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="fa fa-crown me-2"></i>Aksesoris
                            </a>

                            <a href="{{ route('rias.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="fa fa-wand-magic-sparkles me-2"></i>Jasa Rias
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Alur Pemesanan
                        </span>

                        <h2 class="fw-bold text-dark mb-4">
                            Proses Pemilihan Dibuat Lebih Sederhana
                        </h2>

                        <div class="d-flex gap-3 mb-4">
                            <span class="badge bg-dark rounded-pill align-self-start px-3 py-2">01</span>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Pilih Kebutuhan</h6>
                                <p class="text-muted mb-0">
                                    Jelajahi baju adat, aksesoris, jasa rias, atau gunakan fitur rekomendasi paket.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-4">
                            <span class="badge bg-dark rounded-pill align-self-start px-3 py-2">02</span>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Lihat Detail Produk</h6>
                                <p class="text-muted mb-0">
                                    Periksa deskripsi, harga, varian, dan ketersediaan item sebelum memesan.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <span class="badge bg-dark rounded-pill align-self-start px-3 py-2">03</span>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Checkout dengan Mudah</h6>
                                <p class="text-muted mb-0">
                                    Tambahkan ke keranjang, isi data pesanan, lalu lanjutkan ke pembayaran.
                                </p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="fa fa-cart-shopping me-2"></i>Lihat Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="koleksi-unggulan" class="container-fluid py-5">
    <div class="container">
        <div class="row g-4 align-items-center mb-4">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Koleksi Unggulan
                </span>

                <h2 class="fw-bold text-dark mb-2">
                    Preview Baju Adat Quin Salon
                </h2>

                <p class="text-muted mb-0">
                    Beberapa koleksi ditampilkan sebagai gambaran awal sebelum pelanggan membuka katalog lengkap.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('catalog') }}" class="btn btn-dark rounded-pill px-4 py-3">
                    Lihat Semua Katalog
                </a>
            </div>
        </div>

        <div id="featuredItemCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @forelse ($featuredItems->take(6)->chunk(3) as $chunk)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}" data-bs-interval="4000">
                        <div class="row g-4">
                            @foreach($chunk as $item)
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                        <div class="ratio ratio-4x3 bg-light border-bottom">
                                            <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                                 class="w-100 h-100 object-fit-cover"
                                                 alt="{{ $item->name }}"
                                                 onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                                        </div>

                                        <div class="card-body p-4">
                                            <span class="badge bg-dark rounded-pill mb-3">
                                                {{ $item->category->cat_name ?? 'Baju Adat' }}
                                            </span>

                                            <h5 class="fw-bold text-dark mb-2">
                                                {{ \Illuminate\Support\Str::limit($item->name, 45) }}
                                            </h5>

                                            <p class="text-muted small mb-3">
                                                {{ \Illuminate\Support\Str::limit($item->description, 75) }}
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center gap-3">
                                                <div>
                                                    <small class="text-muted d-block">Mulai dari</small>
                                                    <strong class="text-dark">
                                                        Rp{{ number_format($item->price, 0, ',', '.') }}
                                                    </strong>
                                                </div>

                                                <a href="{{ route('catalog.show', $item->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                                    Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="carousel-item active">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body text-center p-5">
                                <div class="display-5 text-muted mb-3">
                                    <i class="fa fa-shirt"></i>
                                </div>

                                <h4 class="fw-bold text-dark mb-2">Koleksi belum tersedia</h4>

                                <p class="text-muted mb-0">
                                    Silakan tambahkan data baju adat aktif melalui halaman admin.
                                </p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($featuredItems->take(6)->count() > 3)
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button class="btn btn-outline-dark rounded-circle" type="button" data-bs-target="#featuredItemCarousel" data-bs-slide="prev">
                        <i class="fa fa-chevron-left"></i>
                    </button>

                    <button class="btn btn-outline-dark rounded-circle" type="button" data-bs-target="#featuredItemCarousel" data-bs-slide="next">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="container-fluid py-5 bg-light">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <div class="card bg-dark text-white border-0 shadow rounded-4 h-100">
                    <div class="card-body p-4 p-lg-5 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                Rekomendasi Paket
                            </span>

                            <h2 class="fw-bold mb-3">
                                Bingung Menentukan Kombinasi yang Serasi?
                            </h2>

                            <p class="text-white-50 mb-4">
                                Gunakan fitur rekomendasi untuk membantu memilih kombinasi baju adat,
                                aksesoris, dan jasa rias sesuai kebutuhan acara Anda.
                            </p>
                        </div>

                        <a href="{{ route('recommendation.index') }}" class="btn btn-light rounded-pill px-4 py-3 align-self-start">
                            <i class="fa fa-gift me-2"></i>Coba Rekomendasi
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                            <div>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                    Paket Pilihan
                                </span>

                                <h3 class="fw-bold text-dark mb-0">
                                    Preview Paket Quin Salon
                                </h3>
                            </div>

                            <a href="{{ route('recommendation.index') }}" class="btn btn-outline-dark rounded-pill px-4 align-self-start">
                                Lihat Rekomendasi
                            </a>
                        </div>

                        @forelse($previewBundles as $bundle)
                            <div class="border rounded-4 p-3 p-md-4 mb-3 bg-light">
                                <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center">
                                    <div>
                                        <h5 class="fw-bold text-dark mb-2">
                                            {{ $bundle->bundle_name }}
                                        </h5>

                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-white text-dark border rounded-pill px-3 py-2">
                                                <i class="fa fa-box me-1"></i>Paket Bundling
                                            </span>

                                            <span class="badge bg-white text-dark border rounded-pill px-3 py-2">
                                                <i class="fa fa-tag me-1"></i>
                                                Rp{{ number_format($bundle->bundle_price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    <a href="{{ route('bundle.show', $bundle->id) }}" class="btn btn-dark rounded-pill px-4">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="card border-0 bg-light rounded-4">
                                <div class="card-body text-center p-5">
                                    <div class="display-5 text-muted mb-3">
                                        <i class="fa fa-gift"></i>
                                    </div>

                                    <h4 class="fw-bold text-dark mb-2">
                                        Paket belum tersedia
                                    </h4>

                                    <p class="text-muted mb-0">
                                        Silakan tambahkan paket aktif melalui halaman admin.
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid py-5">
    <div class="container">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5 text-center">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Siap Tampil Lebih Berkesan?
                </span>

                <h2 class="fw-bold text-dark mb-3">
                    Mulai Pilih Tampilan Terbaik untuk Acara Anda
                </h2>

                <p class="text-muted mx-auto mb-4 max-w-760">
                    Jelajahi koleksi baju adat, lengkapi dengan aksesoris dan jasa rias,
                    atau gunakan fitur rekomendasi agar pilihan layanan terasa lebih mudah dan terarah.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="{{ route('catalog') }}" class="btn btn-dark rounded-pill px-4 px-lg-5 py-3">
                        <i class="fa fa-shirt me-2"></i>Buka Katalog
                    </a>

                    <a href="{{ route('recommendation.index') }}" class="btn btn-outline-dark rounded-pill px-4 px-lg-5 py-3">
                        <i class="fa fa-gift me-2"></i>Coba Rekomendasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
