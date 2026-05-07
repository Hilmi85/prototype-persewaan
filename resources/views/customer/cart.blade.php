@extends('customer.layouts.master')

@section('title', 'Keranjang - Quin Salon')

@section('content')
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.68), rgba(60, 42, 33, 0.68)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <span class="badge rounded-pill px-4 py-2 mb-3"
              style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
            Keranjang Customer
        </span>

        <h1 class="display-4 text-white fw-bold">Keranjang</h1>

        <p class="text-white mb-0">
            Cek item pilihan sebelum melanjutkan checkout.
        </p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
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

        @if(!empty($warning))
            <div class="alert alert-warning alert-dismissible fade show rounded-4 shadow-sm mb-4">
                <i class="fa fa-triangle-exclamation me-2"></i>{{ $warning }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(count($cartItems))
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead style="background-color: #fff7ef;">
                                    <tr>
                                        <th>Item</th>
                                        <th>Varian</th>
                                        <th>Harga</th>
                                        <th style="width: 190px;">Qty</th>
                                        <th>Total</th>
                                        <th style="width: 110px;">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($cartItems as $cartItem)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $cartItem['item']->name }}</div>
                                                <small class="text-muted">
                                                    {{ $cartItem['item']->category->cat_name ?? '-' }}
                                                </small>
                                            </td>

                                            <td>
                                                @if($cartItem['variant'])
                                                    <div class="fw-semibold">
                                                        {{ $cartItem['variant']->size ?? '-' }}
                                                        @if($cartItem['variant']->color)
                                                            / {{ $cartItem['variant']->color }}
                                                        @endif
                                                    </div>

                                                    <small class="text-muted">
                                                        Stok tersedia: {{ $cartItem['variant']->available_stock }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">Tanpa varian</span>
                                                @endif
                                            </td>

                                            <td>
                                                Rp{{ number_format($cartItem['price'], 0, ',', '.') }}
                                            </td>

                                            <td>
                                                <form action="{{ route('cart.update', $cartItem['key']) }}" method="POST" class="d-flex gap-2">
                                                    @csrf

                                                    <input type="number"
                                                           name="quantity"
                                                           min="1"
                                                           @if($cartItem['max_quantity'])
                                                               max="{{ $cartItem['max_quantity'] }}"
                                                           @endif
                                                           value="{{ $cartItem['quantity'] }}"
                                                           class="form-control form-control-sm">

                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        Update
                                                    </button>
                                                </form>
                                            </td>

                                            <td>
                                                <strong style="color: #8b5e3c;">
                                                    Rp{{ number_format($cartItem['total_price'], 0, ',', '.') }}
                                                </strong>
                                            </td>

                                            <td>
                                                <form action="{{ route('cart.remove', $cartItem['key']) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <form action="{{ route('cart.clear') }}" method="POST" class="mt-4" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                <i class="fa fa-trash me-2"></i>Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                        <h4 class="fw-bold mb-4" style="color: #8b5e3c;">Ringkasan Belanja</h4>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Jumlah Item</span>
                            <strong>{{ collect($cartItems)->sum('quantity') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong>Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>

                        <div class="p-3 rounded-4 mb-4" style="background-color: #fff7ef; border: 1px solid #f0dfcf;">
                            <small class="text-muted">
                                Data customer, tanggal sewa, booking, dan pembayaran akan diisi pada halaman checkout.
                            </small>
                        </div>

                        <a href="{{ route('checkout.cart.show') }}"
                           class="btn rounded-pill w-100 py-3"
                           style="background-color: #8b5e3c; color: #fff;">
                            <i class="fa fa-credit-card me-2"></i>Lanjut Checkout
                        </a>

                        <a href="{{ route('catalog') }}"
                           class="btn btn-outline-secondary rounded-pill w-100 py-3 mt-2">
                            Tambah Item Lagi
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-4 shadow-sm p-5 text-center" style="border: 1px solid #f1e3d3;">
                <i class="fa fa-cart-shopping mb-3" style="font-size: 52px; color: #b88352;"></i>

                <h4 class="fw-bold mb-2">Keranjang masih kosong</h4>

                <p class="text-muted mb-4">
                    Silakan pilih item dari katalog atau gunakan fitur rekomendasi paket.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="{{ route('catalog') }}"
                       class="btn rounded-pill px-4 py-3"
                       style="background-color: #8b5e3c; color: #fff;">
                        Ke Katalog
                    </a>

                    <a href="{{ route('recommendation.index') }}"
                       class="btn btn-outline-secondary rounded-pill px-4 py-3">
                        Cari Rekomendasi
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
