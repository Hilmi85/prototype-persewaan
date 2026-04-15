@extends('customer.layouts.master')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5" style="background: linear-gradient(rgba(60, 42, 33, 0.7), rgba(60, 42, 33, 0.7)), url('{{ asset('customer/img/carousel-1.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <h1 class="text-center text-white display-6">Checkout Pesanan</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item active text-warning">Lengkapi data pemesanan baju adat dan jasa rias Anda</li>
    </ol>
</div>
<!-- Header End -->

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="mb-2">Detail Pemesanan</h1>
            <p class="text-muted">Silakan isi data diri dan periksa kembali item yang akan Anda pesan.</p>
        </div>

        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="row g-5">
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <div class="p-4 rounded-4 shadow-sm" style="background-color: #ffffff; border: 1px solid #f1e3d3;">
                        <h4 class="mb-4" style="color: #8b5e3c;">Data Customer</h4>

                        <div class="row">
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3 fw-semibold">Nama Lengkap<sup class="text-danger">*</sup></label>
                                    <input type="text" name="fullname" class="form-control rounded-3" placeholder="Masukkan nama Anda" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3 fw-semibold">Nomor WhatsApp<sup class="text-danger">*</sup></label>
                                    <input type="text" name="phone" class="form-control rounded-3" placeholder="Masukkan nomor WhatsApp" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3 fw-semibold">Nomor Meja<sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control rounded-3" value="{{ $tableNumber ?? 'Tidak ada nomor meja' }}" disabled required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-item">
                                    <label class="form-label my-3 fw-semibold">Catatan Tambahan</label>
                                    <textarea name="note" class="form-control rounded-3" spellcheck="false" cols="30" rows="5" placeholder="Catatan pemesanan, jadwal, atau kebutuhan khusus (opsional)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4 p-4 rounded-4 shadow-sm" style="background-color: #ffffff; border: 1px solid #f1e3d3;">
                        <h4 class="mb-4" style="color: #8b5e3c;">Detail Item Pesanan</h4>
                        <table class="table align-middle">
                            <thead style="background-color: #f8efe5;">
                                <tr>
                                    <th scope="col">Gambar</th>
                                    <th scope="col">Item / Layanan</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subTotal = 0;
                                @endphp
                                @foreach (session('cart') as $item)
                                    @php
                                        $itemTotal = $item['price'] * $item['qty'];
                                        $subTotal += $itemTotal;
                                    @endphp
                                <tr>
                                    <th scope="row">
                                        <div class="d-flex align-items-center mt-2">
                                            <img src="{{ asset('img_item_upload/'. $item['image']) }}"
                                                 class="img-fluid me-3 rounded-3"
                                                 style="width: 80px; height: 80px; object-fit: cover;"
                                                 alt=""
                                                 onerror="this.onerror=null;this.src='{{  $item['image'] }}';">
                                        </div>
                                    </th>
                                    <td class="py-4 fw-semibold">{{ $item['name'] }}</td>
                                    <td class="py-4">{{ 'Rp'. number_format($item['price'], 0, ',','.') }}</td>
                                    <td class="py-4">{{ $item['qty'] }}</td>
                                    <td class="py-4 fw-semibold">{{ 'Rp'. number_format($item['price'] * $item['qty'], 0, ',','.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @php
                    $tax = $subTotal * 0.1;
                    $total = $subTotal + $tax;
                @endphp

                <div class="col-md-12 col-lg-6 col-xl-6">
                    <div class="rounded-4 shadow-sm p-4" style="background-color: #ffffff; border: 1px solid #f1e3d3;">
                        <h3 class="display-6 mb-4">Ringkasan <span class="fw-normal">Pembayaran</span></h3>

                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Subtotal</h5>
                            <p class="mb-0">Rp{{ number_format($subTotal, 0, ',','.') }}</p>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <p class="mb-0 me-4">Pajak (10%)</p>
                            <p class="mb-0">Rp{{ number_format($tax, 0, ',','.') }}</p>
                        </div>

                        <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between" style="background-color: #f8efe5;">
                            <h4 class="mb-0 ps-2 me-4">Total</h4>
                            <h5 class="mb-0 pe-2" style="color: #8b5e3c;">Rp{{ number_format($total, 0, ',','.') }}</h5>
                        </div>

                        <div class="py-2 mb-4">
                            <h5 class="mb-3">Metode Pembayaran</h5>

                            <div class="form-check mb-3 p-3 rounded-3" style="background-color: #fffaf5; border: 1px solid #ead7c0;">
                                <input type="radio" class="form-check-input border-0" id="qris" name="payment_method" value="qris" style="background-color: #8b5e3c;">
                                <label class="form-check-label fw-semibold" for="qris">QRIS</label>
                                <div class="small text-muted">Bayar secara digital dengan QRIS.</div>
                            </div>

                            <div class="form-check p-3 rounded-3" style="background-color: #fffaf5; border: 1px solid #ead7c0;">
                                <input type="radio" class="form-check-input border-0" id="cash" name="payment_method" value="tunai" style="background-color: #8b5e3c;">
                                <label class="form-check-label fw-semibold" for="cash">Tunai</label>
                                <div class="small text-muted">Pembayaran dilakukan secara langsung.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" id="pay-button" class="btn rounded-pill px-4 py-3 text-uppercase fw-semibold" style="background-color: #8b5e3c; color: #fff; border: none;">
                                Konfirmasi Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const payButton = document.getElementById("pay-button");
        const form = document.querySelector("form");

        payButton.addEventListener("click", function () {
            let paymentMethod = document.querySelector('input[name="payment_method"]:checked');

            if(!paymentMethod) {
                alert("Pilih Metode Pembayaran Terlebih Dahulu!");
                return;
            }

            paymentMethod = paymentMethod.value;
            let formData = new FormData(form);

            if(paymentMethod === "tunai") {
                form.submit();
            } else {
                fetch("{{ route('checkout.store') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.snap_token) {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = "/checkout/success/" + data.order_code;
                            },
                            onPending: function(result) {
                                alert("Menunggu Pembayaran");
                            },
                            onError: function(result) {
                                alert("Pembayaran Gagal");
                            }
                        });
                    } else {
                        alert("Terjadi kesalahan, silakan coba lagi.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan, silakan coba lagi ya.");
                });
            }
        })
    })
</script>

@endsection
