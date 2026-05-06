@extends('customer.layouts.master')

@section('title', 'Checkout Keranjang - Quin Salon')

@section('content')
<div class="container-fluid py-5" style="background-color: #fffaf5; min-height: 100vh;">
    <div class="container py-5">
        <form action="{{ route('checkout.cart.store') }}" method="POST">
            @csrf
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                        <h4 class="mb-4" style="color: #8b5e3c;">Data Customer</h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="fullname" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. WhatsApp</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Acara</label>
                                <input type="text" name="jenis_acara" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori Adat</label>
                                <input type="text" name="kategori_adat" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Budget</label>
                                <select name="budget" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="Rendah">Rendah</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Butuh Rias?</label>
                                <select name="butuh_rias" class="form-select">
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Acara</label>
                                <input type="date" name="event_date" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mulai Sewa</label>
                                <input type="date" name="rental_start" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Selesai Sewa</label>
                                <input type="date" name="rental_end" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Rias</label>
                                <input type="date" name="makeup_date" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="tunai">Tunai</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                        <h4 class="mb-4" style="color: #8b5e3c;">Ringkasan Keranjang</h4>

                        @foreach($cartItems as $cartItem)
                            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                <div>
                                    <div class="fw-semibold">{{ $cartItem['item']->name }}</div>
                                    <small class="text-muted">
                                        Qty: {{ $cartItem['quantity'] }}
                                        @if($cartItem['variant'])
                                            | {{ $cartItem['variant']->size ?? '-' }} / {{ $cartItem['variant']->color ?? '-' }}
                                        @endif
                                    </small>
                                </div>
                                <div class="fw-semibold">
                                    Rp{{ number_format($cartItem['total_price'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <strong>Total</strong>
                            <strong style="color: #8b5e3c;">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>

                        <button type="submit"
                                class="btn rounded-pill w-100 py-3 mt-4"
                                style="background-color: #8b5e3c; color: #fff;">
                            Konfirmasi Checkout
                        </button>

                        @if($contact)
                            <div class="mt-4 small text-muted">
                                Butuh bantuan? Hubungi admin:
                                <a href="{{ $contact->whatsapp_url }}" target="_blank">{{ $contact->contact_name }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
