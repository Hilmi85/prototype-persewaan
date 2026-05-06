@extends('customer.layouts.master')

@section('content')
<div class="container-fluid page-header py-5"
     style="background: linear-gradient(rgba(60, 42, 33, 0.7), rgba(60, 42, 33, 0.7)), url('{{ asset('customer/img/carousel-1.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <h1 class="text-center text-white display-6">Checkout Paket</h1>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container py-5">
        <form action="{{ route('checkout.bundle.store', $bundle->id) }}" method="POST">
            @csrf
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="p-4 rounded-4 shadow-sm bg-white" style="border: 1px solid #f1e3d3;">
                        <h4 class="mb-4" style="color: #8b5e3c;">Data Customer</h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="fullname" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nomor WhatsApp</label>
                                <input type="text" name="phone" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Gender</label>
                                <select name="gender" class="form-select rounded-3">
                                    <option value="">Pilih Gender</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="address" class="form-control rounded-3" rows="3"></textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h4 class="mb-4" style="color: #8b5e3c;">Detail Kebutuhan</h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jenis Acara</label>
                                <input type="text" name="jenis_acara" value="{{ $bundle->jenis_acara }}" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Kategori Adat</label>
                                <input type="text" name="kategori_adat" value="{{ $bundle->kategori_adat }}" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Budget</label>
                                <select name="budget" class="form-select rounded-3">
                                    <option value="">Pilih Budget</option>
                                    <option value="Rendah">Rendah</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Butuh Rias?</label>
                                <select name="butuh_rias" class="form-select rounded-3">
                                    <option value="1" {{ $bundle->butuh_rias ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ !$bundle->butuh_rias ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Tanggal Acara</label>
                                <input type="date" name="event_date" class="form-control rounded-3">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Mulai Sewa</label>
                                <input type="date" name="rental_start" class="form-control rounded-3">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Selesai Sewa</label>
                                <input type="date" name="rental_end" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal Rias</label>
                                <input type="date" name="makeup_date" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="notes" class="form-control rounded-3" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="rounded-4 shadow-sm p-4 bg-white" style="border: 1px solid #f1e3d3;">
                        <h4 class="mb-4" style="color: #8b5e3c;">Ringkasan Paket</h4>

                        <h5 class="fw-bold">{{ $bundle->bundle_name }}</h5>
                        <p class="text-muted">{{ $bundle->description }}</p>

                        <ul class="mb-4">
                            @foreach ($bundle->bundleItems as $bundleItem)
                                <li>{{ $bundleItem->item->name }} ({{ $bundleItem->quantity }})</li>
                            @endforeach
                        </ul>

                        <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between" style="background-color: #f8efe5;">
                            <h4 class="mb-0 ps-2 me-4">Total</h4>
                            <h5 class="mb-0 pe-2" style="color: #8b5e3c;">Rp{{ number_format($bundle->price, 0, ',', '.') }}</h5>
                        </div>

                        <div class="py-2 mb-4">
                            <h5 class="mb-3">Metode Pembayaran</h5>

                            <div class="form-check mb-3 p-3 rounded-3" style="background-color: #fffaf5; border: 1px solid #ead7c0;">
                                <input type="radio" class="form-check-input" id="qris" name="payment_method" value="qris">
                                <label class="form-check-label fw-semibold" for="qris">QRIS</label>
                            </div>

                            <div class="form-check p-3 rounded-3" style="background-color: #fffaf5; border: 1px solid #ead7c0;">
                                <input type="radio" class="form-check-input" id="tunai" name="payment_method" value="tunai" checked>
                                <label class="form-check-label fw-semibold" for="tunai">Tunai</label>
                            </div>
                        </div>

                        <button type="submit" class="btn rounded-pill px-4 py-3 text-uppercase fw-semibold w-100"
                                style="background-color: #8b5e3c; color: #fff; border: none;">
                            Konfirmasi Pesanan
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
