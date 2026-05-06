@extends('customer.layouts.master')

@section('content')
<div class="container-fluid page-header position-relative overflow-hidden py-5 mb-0"
     style="margin-top: -55px !important; padding-top: 180px !important; padding-bottom: 120px !important; background:
     linear-gradient(rgba(52, 36, 29, 0.55), rgba(52, 36, 29, 0.72)),
     url('{{ asset('img_item_upload/indo.jpg') }}');
     background-position: center center;
     background-repeat: no-repeat;
     background-size: cover;">

    <div class="position-absolute top-0 start-0 w-100 h-100"
         style="background:
         radial-gradient(circle at top left, rgba(255,255,255,0.10), transparent 30%),
         radial-gradient(circle at bottom right, rgba(245,210,166,0.12), transparent 28%);
         pointer-events: none;">
    </div>

    <div class="container position-relative py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10 col-xl-9">
                <span class="badge rounded-pill px-4 py-2 mb-4"
                      style="background: rgba(255,255,255,0.10); color: #f5d2a6; font-weight: 600; letter-spacing: 1px; border: 1px solid rgba(255,255,255,0.14); backdrop-filter: blur(6px);">
                    Quin Salon • Persewaan Baju Adat & Jasa Rias
                </span>

                <h1 class="text-white fw-bold mb-4"
                    style="font-size: clamp(2.2rem, 5vw, 4.2rem); line-height: 1.18; letter-spacing: 0.2px;">
                    Tampil Anggun, Serasi,
                    <br>
                    dan Berkesan di Setiap Momen
                </h1>

                <p class="text-white mx-auto mb-5"
                   style="max-width: 760px; font-size: 1.05rem; line-height: 1.9; opacity: 0.94;">
                    Quin Salon menghadirkan pengalaman yang lebih praktis untuk memilih baju adat, jasa rias,
                    dan paket rekomendasi yang sesuai dengan kebutuhan acara Anda—dengan tampilan yang nyaman,
                    elegan, dan mudah digunakan.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="{{ route('catalog') }}"
                       class="btn rounded-pill px-4 px-lg-5 py-3"
                       style="background: linear-gradient(90deg, #8b5e3c, #a47148); color: #fff; border: none; font-weight: 600; box-shadow: 0 12px 28px rgba(139,94,60,0.24);">
                        <i class="fa fa-shirt me-2"></i>Lihat Katalog
                    </a>

                    <a href="{{ route('recommendation.index') }}"
                       class="btn rounded-pill px-4 px-lg-5 py-3"
                       style="background: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.28); font-weight: 600; backdrop-filter: blur(6px);">
                        <i class="fa fa-gift me-2"></i>Coba Rekomendasi Paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5"
     style="background:
     linear-gradient(180deg, #fffaf5 0%, #fff7ef 48%, #fffaf5 100%);">
    <div class="container">

        <div class="text-center mx-auto mb-5" style="max-width: 780px;">
            <h6 class="text-uppercase mb-2"
                style="color: #b88352; letter-spacing: 2px; font-weight: 700;">
                Layanan Utama
            </h6>
            <h2 class="fw-bold mb-3" style="color: #3f2c22; font-size: clamp(1.7rem, 3vw, 2.5rem);">
                Pilihan Layanan yang Disusun
                <span style="color: #8b5e3c;">Lebih Elegan</span>
            </h2>
            <p class="text-muted mb-0" style="line-height: 1.9;">
                Dari koleksi baju adat, layanan rias profesional, hingga sistem rekomendasi paket,
                setiap layanan dirancang untuk membantu pelanggan mendapatkan pengalaman yang nyaman,
                praktis, dan tetap berkelas.
            </p>
        </div>

        <div id="layanan" class="row g-4 g-lg-5 mb-5">
            <div class="col-md-6 col-xl-4">
                <div class="h-100 rounded-4 shadow-sm overflow-hidden"
                     style="background: rgba(255,255,255,0.88); border: 1px solid rgba(240,223,207,0.95); backdrop-filter: blur(4px); box-shadow: 0 12px 28px rgba(101,74,56,0.06) !important;">
                    <div class="p-4 p-lg-5 h-100 d-flex flex-column">
                        <div class="mb-4 d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 72px; height: 72px; background: linear-gradient(135deg, #fbf1e7, #f4e2cd); color: #8b5e3c; box-shadow: inset 0 1px 0 rgba(255,255,255,0.9);">
                            <i class="fa fa-shirt fa-lg"></i>
                        </div>

                        <h4 class="fw-bold mb-3" style="color: #3f2c22;">Katalog Baju Adat</h4>

                        <p class="text-muted mb-4" style="line-height: 1.9;">
                            Beragam pilihan busana adat yang cocok untuk wisuda, pernikahan,
                            lamaran, dan acara spesial lainnya dengan tampilan elegan dan serasi.
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('catalog') }}"
                               class="btn rounded-pill px-4 py-2"
                               style="background-color: #8b5e3c; color: #fff; border: none; font-weight: 600;">
                                Jelajahi Katalog
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="h-100 rounded-4 shadow-sm overflow-hidden"
                     style="background: rgba(255,255,255,0.88); border: 1px solid rgba(240,223,207,0.95); backdrop-filter: blur(4px); box-shadow: 0 12px 28px rgba(101,74,56,0.06) !important;">
                    <div class="p-4 p-lg-5 h-100 d-flex flex-column">
                        <div class="mb-4 d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 72px; height: 72px; background: linear-gradient(135deg, #fbf1e7, #f4e2cd); color: #8b5e3c; box-shadow: inset 0 1px 0 rgba(255,255,255,0.9);">
                            <i class="fa fa-wand-magic-sparkles fa-lg"></i>
                        </div>

                        <h4 class="fw-bold mb-3" style="color: #3f2c22;">Jasa Rias Profesional</h4>

                        <p class="text-muted mb-4" style="line-height: 1.9;">
                            Layanan rias yang membantu menyempurnakan penampilan agar tampak lebih
                            anggun, harmonis, dan sesuai dengan karakter acara Anda.
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('rias.index') }}"
                               class="btn rounded-pill px-4 py-2"
                               style="background-color: #8b5e3c; color: #fff; border: none; font-weight: 600;">
                                Lihat Layanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xl-4">
                <div class="h-100 rounded-4 shadow-sm overflow-hidden"
                     style="background: linear-gradient(145deg, #8b5e3c 0%, #a47148 55%, #b37e55 100%); border: 1px solid rgba(139,94,60,0.22); box-shadow: 0 18px 36px rgba(139,94,60,0.16) !important;">
                    <div class="p-4 p-lg-5 h-100 d-flex flex-column position-relative">
                        <div class="position-absolute top-0 end-0"
                             style="width: 140px; height: 140px; background: radial-gradient(circle, rgba(255,255,255,0.14), transparent 70%);">
                        </div>

                        <div class="mb-4 d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 72px; height: 72px; background: rgba(255,255,255,0.14); color: #fff; border: 1px solid rgba(255,255,255,0.18); backdrop-filter: blur(4px);">
                            <i class="fa fa-gift fa-lg"></i>
                        </div>

                        <h4 class="fw-bold mb-3 text-white">Paket Rekomendasi</h4>

                        <p class="mb-4" style="line-height: 1.9; color: rgba(255,255,255,0.9);">
                            Sistem rekomendasi membantu pelanggan memilih kombinasi layanan yang paling sesuai
                            dengan kebutuhan acara secara cepat, praktis, dan lebih terarah.
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('recommendation.index') }}"
                               class="btn rounded-pill px-4 py-2"
                               style="background-color: #fff; color: #8b5e3c; border: none; font-weight: 700;">
                                Coba Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mx-auto mb-5 pt-2" style="max-width: 760px;">
            <h6 class="text-uppercase mb-2"
                style="color: #b88352; letter-spacing: 2px; font-weight: 700;">
                Preview Katalog
            </h6>
            <h2 class="fw-bold mb-3" style="color: #3f2c22; font-size: clamp(1.7rem, 3vw, 2.4rem);">
                Pilihan Item Unggulan Quin Salon
            </h2>
            <p class="text-muted mb-0" style="line-height: 1.9;">
                Beberapa item pilihan kami tampilkan sebagai gambaran koleksi terbaik yang tersedia
                di Quin Salon.
            </p>
        </div>

        <div class="row g-4">
            @foreach ($featuredItems->take(6) as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden"
                         style="border: 1px solid #f1e3d3 !important; background-color: #fff;">
                        <div class="position-relative">
                            <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                 class="img-fluid w-100"
                                 alt="{{ $item->name }}"
                                 style="height: 250px; object-fit: cover;"
                                 onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                            <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill"
                                  style="background-color: #8b5e3c; color: #fff;">
                                {{ $item->category->cat_name ?? 'Layanan' }}
                            </span>
                        </div>

                        <div class="card-body d-flex flex-column p-4">
                            <h4 class="fw-bold mb-2" style="min-height: 58px; color: #3f2c22;">
                                {{ $item->name }}
                            </h4>

                            <p class="text-muted mb-3" style="min-height: 72px;">
                                {{ $item->description }}
                            </p>

                            <div class="mt-auto pt-3 border-top">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Harga</small>
                                    <div class="fw-bold" style="color: #8b5e3c; font-size: 1.1rem;">
                                        Rp{{ number_format($item->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-12">
                                        <a href="{{ route('catalog.show', $item->id) }}"
                                           class="btn w-100 rounded-pill py-2"
                                           style="background-color: #8b5e3c; color: #fff; border: none; font-weight: 600;">
                                            <i class="fa fa-eye me-1"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5 pt-2">
            <a href="{{ route('catalog') }}"
               class="btn rounded-pill px-4 px-lg-5 py-3"
               style="background: linear-gradient(90deg, #8b5e3c, #a47148); color: #fff; border: none; font-weight: 600; box-shadow: 0 12px 26px rgba(139,94,60,0.18);">
                <i class="fa fa-arrow-right me-2"></i>Lihat Semua Katalog
            </a>
        </div>
    </div>
</div>
@endsection
