@extends('customer.layouts.master')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 position-relative overflow-hidden"
     style="background-image: url('{{ asset('img_item_upload/indo.jpg') }}');
            background-position: center 90%;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 380px;">

    <!-- Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100"
         style="background: linear-gradient(rgba(43, 28, 22, 0.55), rgba(43, 28, 22, 0.55));">
    </div>

    <!-- Content -->
    <div class="container h-100 position-relative">
        <div class="d-flex flex-column justify-content-center align-items-center text-center h-100">
            <span class="px-4 py-2 mb-3 rounded-pill"
                  style="background-color: rgba(255,255,255,0.15); color: #f3d2a2; backdrop-filter: blur(4px); font-weight: 500; letter-spacing: 1px;">
                Quin Salon • Baju Adat & Jasa Rias
            </span>

            <h1 class="text-white display-4 fw-bold mb-3"
                style="text-shadow: 2px 2px 10px rgba(0,0,0,0.35);">
                Katalog Layanan
            </h1>

            <p class="mb-0 px-3"
               style="max-width: 760px; color: #f8e7d2; font-size: 18px; text-shadow: 1px 1px 8px rgba(0,0,0,0.35);">
                Pilihan baju adat, jasa rias, dan perlengkapan terbaik untuk melengkapi momen spesial Anda dengan tampilan yang elegan dan berkesan.
            </p>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Katalog Start -->
<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container py-5">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
            <h5 class="text-uppercase text-warning">Katalog Persewaan & Rias</h5>
            <h1 class="display-6">Temukan Pilihan yang Sesuai untuk Acara Anda</h1>
            <p class="mb-0 text-muted">
                Jelajahi berbagai pilihan baju adat, jasa rias, dan item pendukung yang dapat disesuaikan dengan kebutuhan acara Anda.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4 justify-content-center">
                    @foreach ($items as $item)
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <div class="rounded position-relative shadow-sm h-100" style="background-color: #ffffff; overflow: hidden; border: 1px solid #f1e3d3;">
                            <div class="position-relative">
                                <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                    class="img-fluid w-100 rounded-top"
                                    alt="{{ $item->name }}"
                                    style="height: 280px; object-fit: cover;"
                                    onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">


                                <div class="text-white px-3 py-1 rounded position-absolute
                                    @if ($item->category->cat_name == 'Makanan')
                                        bg-warning
                                    @elseif ($item->category->cat_name == 'Minuman')
                                        bg-info
                                    @else
                                        bg-dark
                                    @endif"
                                    style="top: 12px; left: 12px; font-size: 14px;">
                                    {{ $item->category->cat_name }}
                                </div>
                            </div>

                            <div class="p-4">
                                <h4 class="mb-2" style="min-height: 58px;">{{ $item->name }}</h4>
                                <p class="text-muted text-limited" style="min-height: 72px;">
                                    {{ $item->description }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                                    <p class="fs-5 fw-bold mb-0" style="color: #8b5e3c;">
                                        {{ 'Rp'. number_format($item->price, 0, ',','.') }}
                                    </p>

                                    <a href="#"
                                       onclick="addToCart({{ $item->id }})"
                                       class="btn rounded-pill px-3"
                                       style="border: 1px solid #c8a97e; color: #8b5e3c; background-color: #fff;">
                                        <i class="fa fa-calendar-check me-2"></i> Pilih Item
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Katalog End -->
@endsection

@section('script')
<script>
    function addToCart(menuId) {
        fetch("{{ route('cart.add') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: menuId })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message)
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>
@endsection
