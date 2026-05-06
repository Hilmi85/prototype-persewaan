@extends('customer.layouts.master')

@section('title', 'Keranjang - Quin Salon')

@section('content')
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.68), rgba(60, 42, 33, 0.68)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <h1 class="display-4 text-white fw-bold">Keranjang</h1>
        <p class="text-white">Item pilihan Anda sebelum melanjutkan checkout.</p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(count($cartItems))
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Varian</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $cartItem)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $cartItem['item']->name }}</div>
                                                <small class="text-muted">{{ $cartItem['item']->category->cat_name ?? '-' }}</small>
                                            </td>
                                            <td>
                                                @if($cartItem['variant'])
                                                    {{ $cartItem['variant']->size ?? '-' }} / {{ $cartItem['variant']->color ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>Rp{{ number_format($cartItem['price'], 0, ',', '.') }}</td>
                                            <td>
                                                <form action="{{ route('cart.update', $cartItem['key']) }}" method="POST" class="d-flex gap-2">
                                                    @csrf
                                                    <input type="number" name="quantity" min="1" value="{{ $cartItem['quantity'] }}" class="form-control" style="width: 90px;">
                                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                </form>
                                            </td>
                                            <td>Rp{{ number_format($cartItem['total_price'], 0, ',', '.') }}</td>
                                            <td>
                                                <form action="{{ route('cart.remove', $cartItem['key']) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill mt-3">
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                        <h4 class="fw-bold mb-4" style="color: #8b5e3c;">Ringkasan Belanja</h4>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong>Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>

                        <a href="{{ route('checkout.cart.show') }}"
                           class="btn rounded-pill w-100 py-3"
                           style="background-color: #8b5e3c; color: #fff;">
                            Lanjut Checkout
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-4 shadow-sm p-5 text-center" style="border: 1px solid #f1e3d3;">
                <h4 class="fw-bold mb-2">Keranjang masih kosong</h4>
                <p class="text-muted mb-4">Silakan pilih item dari katalog terlebih dahulu.</p>
                <a href="{{ route('catalog') }}"
                   class="btn rounded-pill px-4 py-3"
                   style="background-color: #8b5e3c; color: #fff;">
                    Ke Katalog
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
