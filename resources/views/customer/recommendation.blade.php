@extends('customer.layouts.master')

@section('title', 'Rekomendasi Paket - Quin Salon')

@section('content')
@php
    $jenisAcaraList = collect($jenisAcaraOptions ?? [
        'Pernikahan',
        'Lamaran',
        'Wisuda',
        'Photoshoot',
        'Acara Adat',
        'Lainnya'
    ])->filter()->unique()->values();

    $kategoriAdatList = collect($kategoriAdatOptions ?? [
        'Jawa',
        'Sunda',
        'Bali',
        'Minang',
        'Batak',
        'Bugis',
        'Modern',
        'Lainnya'
    ])->filter()->unique()->values();

    $genderList = collect($genderOptions ?? [
        'Laki-laki',
        'Perempuan',
        'Unisex'
    ])->filter()->unique()->values();

    $budgetList = collect($budgetOptions ?? [
        'Rendah',
        'Sedang',
        'Tinggi'
    ])->filter()->unique()->values();

    $selectedJenisAcara = old('jenis_acara');
    $selectedKategoriAdat = old('kategori_adat');
    $selectedGender = old('gender');
    $selectedBudget = old('budget');
    $selectedButuhRias = old('butuh_rias', '1');
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Quin Salon • Rekomendasi Paket
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    Temukan Paket Bundling yang Paling Sesuai
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Isi kebutuhan acara Anda, lalu sistem akan mencocokkan pilihan dengan rule rekomendasi
                    untuk menampilkan paket yang paling cocok.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#form-rekomendasi" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Mulai Rekomendasi
                    </a>

                    <a href="{{ route('catalog') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-shirt me-2"></i>Lihat Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="form-rekomendasi" class="container-fluid py-5 bg-cream">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger rounded-4 shadow-sm mb-4">
                <strong>Data belum lengkap.</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Cara Kerja
                        </span>

                        <h3 class="fw-bold text-dark mb-3">
                            Rekomendasi Berdasarkan Kebutuhan Acara
                        </h3>

                        <p class="text-muted mb-4">
                            Sistem akan membaca input pelanggan dan mencocokkannya dengan rule aktif yang dibuat
                            oleh admin.
                        </p>

                        <div class="border border-warning rounded-4 p-3 bg-light mb-3">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">IF</span>
                                <p class="text-muted small mb-0">
                                    Jenis acara, kategori adat, gender, kebutuhan rias, dan budget cocok dengan rule.
                                </p>
                            </div>
                        </div>

                        <div class="border border-warning rounded-4 p-3 bg-light mb-4">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="badge bg-dark rounded-pill px-3 py-2">THEN</span>
                                <p class="text-muted small mb-0">
                                    Sistem menampilkan paket bundling yang paling sesuai untuk pelanggan.
                                </p>
                            </div>
                        </div>

                        <div class="alert alert-warning rounded-4 mb-0">
                            <strong>Catatan:</strong>
                            <div class="small mt-1">
                                Ukuran atau varian dicek setelah paket ditemukan, bukan sebagai rule utama.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Form Rekomendasi
                        </span>

                        <h3 class="fw-bold text-dark mb-2">
                            Lengkapi Kebutuhan Acara
                        </h3>

                        <p class="text-muted mb-4">
                            Pilih data berikut agar sistem dapat menentukan paket bundling yang paling sesuai.
                        </p>

                        <form action="{{ route('recommendation.process') }}" method="POST">
                            @csrf

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jenis Acara</label>
                                    <select name="jenis_acara" id="jenis_acara" class="form-select rounded-3" required>
                                        <option value="">-- Pilih Jenis Acara --</option>

                                        @foreach($jenisAcaraList as $jenisAcara)
                                            <option value="{{ $jenisAcara }}" {{ $selectedJenisAcara == $jenisAcara ? 'selected' : '' }}>
                                                {{ $jenisAcara }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kategori Adat</label>
                                    <select name="kategori_adat" id="kategori_adat" class="form-select rounded-3" required>
                                        <option value="">-- Pilih Kategori Adat --</option>

                                        @foreach($kategoriAdatList as $kategoriAdat)
                                            <option value="{{ $kategoriAdat }}" {{ $selectedKategoriAdat == $kategoriAdat ? 'selected' : '' }}>
                                                {{ $kategoriAdat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Gender</label>
                                    <select name="gender" id="gender" class="form-select rounded-3" required>
                                        <option value="">-- Pilih Gender --</option>

                                        @foreach($genderList as $gender)
                                            <option value="{{ $gender }}" {{ $selectedGender == $gender ? 'selected' : '' }}>
                                                {{ $gender }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Butuh Rias?</label>
                                    <select name="butuh_rias" id="butuh_rias" class="form-select rounded-3" required>
                                        <option value="1" {{ $selectedButuhRias == '1' ? 'selected' : '' }}>Ya</option>
                                        <option value="0" {{ $selectedButuhRias == '0' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Budget</label>
                                    <select name="budget" id="budget" class="form-select rounded-3" required>
                                        <option value="">-- Pilih Budget --</option>

                                        @foreach($budgetList as $budget)
                                            <option value="{{ $budget }}" {{ $selectedBudget == $budget ? 'selected' : '' }}>
                                                {{ $budget }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <div id="rule_preview" class="alert alert-light border border-warning rounded-4 mb-0">
                                        <i class="fa fa-circle-info me-2 text-warning"></i>
                                        Sistem akan mencocokkan kebutuhan Anda dengan aturan rekomendasi yang tersedia.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-warning rounded-4 mb-0">
                                        <strong>Aturan Sistem:</strong>
                                        <div class="mt-2 small">
                                            IF jenis acara, kategori adat, gender, kebutuhan rias, dan budget cocok dengan rule,
                                            THEN sistem menampilkan paket bundling.
                                            ELSE sistem menampilkan paket custom atau alternatif.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-dark rounded-pill px-4 py-3">
                                            <i class="fa fa-check-circle me-2"></i>Cari Rekomendasi Paket
                                        </button>

                                        <a href="{{ route('catalog') }}" class="btn btn-outline-dark rounded-pill px-4 py-3">
                                            <i class="fa fa-shirt me-2"></i>Lihat Katalog
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4 text-muted small">
                    Rekomendasi ditentukan dari rule aktif pada admin dan output-nya berupa paket bundling.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisAcaraInput = document.getElementById('jenis_acara');
    const kategoriAdatInput = document.getElementById('kategori_adat');
    const genderInput = document.getElementById('gender');
    const butuhRiasInput = document.getElementById('butuh_rias');
    const budgetInput = document.getElementById('budget');
    const rulePreview = document.getElementById('rule_preview');

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function butuhRiasLabel(value) {
        return value === '1' ? 'Ya' : 'Tidak';
    }

    function syncRulePreview() {
        const jenisAcara = jenisAcaraInput.value;
        const kategoriAdat = kategoriAdatInput.value;
        const gender = genderInput.value;
        const butuhRias = butuhRiasLabel(butuhRiasInput.value);
        const budget = budgetInput.value;

        if (!jenisAcara || !kategoriAdat || !gender || !budget) {
            rulePreview.innerHTML =
                '<i class="fa fa-circle-info me-2 text-warning"></i>' +
                'Sistem akan mencocokkan kebutuhan Anda dengan aturan rekomendasi yang tersedia.';
            return;
        }

        rulePreview.innerHTML =
            '<strong>Preview Rule-Based:</strong><br>' +
            '<span>IF jenis_acara = <strong>"' + escapeHtml(jenisAcara) + '"</strong></span><br>' +
            '<span>AND kategori_adat = <strong>"' + escapeHtml(kategoriAdat) + '"</strong></span><br>' +
            '<span>AND gender = <strong>"' + escapeHtml(gender) + '"</strong></span><br>' +
            '<span>AND butuh_rias = <strong>"' + escapeHtml(butuhRias) + '"</strong></span><br>' +
            '<span>AND budget = <strong>"' + escapeHtml(budget) + '"</strong></span><br>' +
            '<span>THEN tampilkan paket bundling yang sesuai.</span><br>' +
            '<span>ELSE tampilkan <strong>"Paket Custom"</strong> atau alternatif.</span>';
    }

    [
        jenisAcaraInput,
        kategoriAdatInput,
        genderInput,
        butuhRiasInput,
        budgetInput
    ].forEach(function (input) {
        input.addEventListener('change', syncRulePreview);
    });

    syncRulePreview();
});
</script>
@endsection
