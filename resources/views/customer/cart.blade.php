@extends('customer.layouts.master')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5" style="background: linear-gradient(rgba(60, 42, 33, 0.7), rgba(60, 42, 33, 0.7)), url('{{ asset('customer/img/carousel-1.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <h1 class="text-center text-white display-6">Keranjang Pesanan</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item active text-warning">Periksa item pilihan baju adat dan jasa rias Anda</li>
    </ol>
</div>
<!-- Header End -->

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (empty($cart))
            <div class="text-center py-5">
                <i class="fa fa-shopping-bag mb-3" style="font-size: 60px; color: #c8a97e;"></i>
                <h4 class="fw-semibold">Keranjang pesanan Anda masih kosong</h4>
                <p class="text-muted">Silakan pilih item baju adat, paket, atau layanan rias terlebih dahulu.</p>
            </div>
        @else
        <div class="table-responsive shadow-sm rounded-4 p-3" style="background-color: #ffffff; border: 1px solid #f1e3d3;">
            <table class="table align-middle">
                <thead style="background-color: #f8efe5;">
                    <tr>
                        <th scope="col">Gambar</th>
                        <th scope="col">Item / Layanan</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Total</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subTotal = 0;
                    @endphp
                    @foreach ($cart as $item)
                        @php
                            $itemTotal = $item['price'] * $item['qty'];
                            $subTotal += $itemTotal;
                        @endphp
                    <tr>
                        <th scope="row">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('img_item_upload/'. $item['image']) }}"
                                     class="img-fluid me-3 rounded-3"
                                     style="width: 85px; height: 85px; object-fit: cover;"
                                     alt=""
                                     onerror="this.onerror=null;this.src='{{  $item['image'] }}';">
                            </div>
                        </th>
                        <td>
                            <p class="mb-0 fw-semibold">{{ $item['name'] }}</p>
                        </td>
                        <td>
                            <p class="mb-0" style="color: #8b5e3c;">{{ 'Rp'. number_format($item['price'], 0, ',','.') }}</p>
                        </td>
                        <td>
                            <div class="input-group quantity" style="width: 110px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm rounded-circle border"
                                            style="background-color: #f8efe5;"
                                            onclick="updateQuantity('{{ $item['id'] }}', -1)">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input id="qty-{{ $item['id'] }}"
                                       type="text"
                                       class="form-control form-control-sm text-center border-0 bg-transparent fw-semibold"
                                       value="{{ $item['qty'] }}"
                                       readonly>
                                <div class="input-group-btn">
                                    <button class="btn btn-sm rounded-circle border"
                                            style="background-color: #f8efe5;"
                                            onclick="updateQuantity('{{ $item['id'] }}', 1)">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="mb-0 fw-semibold">{{ 'Rp'. number_format($item['price'] * $item['qty'], 0, ',','.') }}</p>
                        </td>
                        <td>
                            <button class="btn btn-md rounded-circle border"
                                    style="background-color: #fff;"
                                    onclick="if(confirm('Apakah anda yakin ingin menghapus item ini?')) { removeItemFromCart('{{ $item['id'] }}') }">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $tax = $subTotal * 0.1;
            $total = $subTotal + $tax;
        @endphp

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('cart.clear') }}"
               class="btn btn-outline-danger rounded-pill px-4"
               onclick=" return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">
               Kosongkan Keranjang
            </a>
        </div>

        <div class="row g-4 justify-content-end mt-1">
            <div class="col-8"></div>
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="rounded-4 shadow-sm" style="background-color: #ffffff; border: 1px solid #f1e3d3;">
                    <div class="p-4">
                        <h2 class="display-6 mb-4">Ringkasan <span class="fw-normal">Pesanan</span></h2>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Subtotal</h5>
                            <p class="mb-0">Rp{{ number_format($subTotal, 0, ',','.') }}</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="mb-0 me-4">Pajak (10%)</p>
                            <div>
                                <p class="mb-0">Rp{{ number_format($tax, 0, ',','.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-4 mb-2 border-top d-flex justify-content-between" style="background-color: #f8efe5; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                        <h4 class="mb-0 ps-4 me-4">Total</h4>
                        <h5 class="mb-0 pe-4" style="color: #8b5e3c;">Rp{{ number_format($total, 0, ',','.') }}</h5>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <div class="mb-0 mb-3">
                        <a href="{{ route('checkout') }}"
                           class="btn rounded-pill px-4 py-3 text-uppercase fw-semibold"
                           style="background-color: #8b5e3c; color: #fff; border: none;"
                           type="button">
                           Lanjut ke Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('script')
    <script>
        function updateQuantity(itemId, change) {
            var qtyInput = document.getElementById('qty-' + itemId);
            var currentQty = parseInt(qtyInput.value);
            var newQty = currentQty + change;

            if (newQty <= 0 ) {
                if(confirm('Apakah anda yakin ingin menghapus item ini?')) {
                    removeItemFromCart(itemId);
                }
                return;
            }

            fetch("{{ route('cart.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: itemId, qty: newQty })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    qtyInput.value = newQty;
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate keranjang');
            });
        }

        function removeItemFromCart(itemId) {
            fetch("{{ route('cart.remove') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: itemId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus item dari keranjang');
            });
        }
    </script>
@endsection
