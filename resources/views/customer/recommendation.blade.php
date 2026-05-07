@extends('customer.layouts.master')

@section('title', 'Rekomendasi Paket - Quin Salon')

@section('content')
@php
    /*
        Form rekomendasi ini disesuaikan kembali dengan proposal TA.

        IF jenis_acara
        AND kategori_adat
        AND gender
        AND butuh_rias
        AND budget
        THEN tampilkan bundle yang sesuai
        ELSE tampilkan Paket Custom / alternatif

        Catatan:
        kategori_item dan size tidak dipakai sebagai rule utama.
        Size/varian dicek setelah paket ditemukan.
    */

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

<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.65), rgba(60, 42, 33, 0.65)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <span class="badge rounded-pill px-4 py-2 mb-3"
              style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
            Quin Salon • Rule-Based Recommendation
        </span>

        <h1 class="display-4 text-white fw-bold">Temukan Paket Bundling yang Cocok</h1>

        <p class="text-white mb-0">
            Isi kebutuhan acara Anda, lalu sistem akan mencocokkan input dengan aturan rekomendasi yang tersedia.
        </p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger rounded-4 mb-4">
                <strong>Data belum lengkap.</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="bg-white rounded-4 shadow-sm overflow-hidden" style="border: 1px solid #f1e3d3;">
                    <div class="row g-0">
                        <div class="col-lg-4">
                            <div class="h-100 p-4 p-lg-5"
                                 style="background: linear-gradient(145deg, #8b5e3c 0%, #a47148 55%, #b37e55 100%); color: #fff;">
                                <div class="mb-4 d-flex align-items-center justify-content-center rounded-circle"
                                     style="width: 72px; height: 72px; background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.18);">
                                    <i class="fa fa-gift fa-lg"></i>
                                </div>

                                <h4 class="fw-bold mb-3 text-white">Cara Kerja Rekomendasi</h4>

                                <p class="mb-4" style="line-height: 1.9; color: rgba(255,255,255,0.88);">
                                    Sistem membaca kebutuhan customer, lalu mencocokkannya dengan rule aktif yang dibuat oleh admin.
                                </p>

                                <div class="p-3 rounded-4"
                                     style="background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.18);">
                                    <strong>IF</strong>
                                    <div class="small mt-2" style="line-height: 1.8;">
                                        Jenis acara, kategori adat, gender, kebutuhan rias, dan budget cocok.
                                    </div>

                                    <hr style="border-color: rgba(255,255,255,0.25);">

                                    <strong>THEN</strong>
                                    <div class="small mt-2" style="line-height: 1.8;">
                                        Sistem menampilkan paket bundling yang sesuai.
                                    </div>
                                </div>

                                <div class="mt-4 small" style="color: rgba(255,255,255,0.78); line-height: 1.8;">
                                    Ukuran atau varian tidak dipakai sebagai rule utama. Varian dicek setelah paket ditemukan.
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="p-4 p-md-5">
                                <h4 class="fw-bold mb-2" style="color: #3f2c22;">
                                    Form Rekomendasi Paket
                                </h4>

                                <p class="text-muted mb-4">
                                    Lengkapi data berikut agar sistem dapat menentukan paket bundling yang paling sesuai.
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
                                            <div id="rule_preview"
                                                 class="p-3 rounded-4"
                                                 style="background-color: #fff7ef; border: 1px solid #f0dfcf; color: #7a6456;">
                                                <i class="fa fa-circle-info me-2"></i>
                                                Sistem akan mencocokkan kebutuhan Anda dengan aturan rekomendasi yang tersedia.
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="p-3 rounded-4"
                                                 style="background-color: #fdf2e7; border: 1px dashed #e6c7a8; color: #7a4d2c;">
                                                <strong>Aturan Sistem:</strong>
                                                <div class="mt-2 small">
                                                    IF jenis acara, kategori adat, gender, kebutuhan rias, dan budget cocok dengan rule,
                                                    THEN sistem menampilkan paket bundling.
                                                    ELSE sistem menampilkan paket custom atau alternatif.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit"
                                                    class="btn rounded-pill px-4 py-3"
                                                    style="background-color: #8b5e3c; color: #fff; border: none; font-weight: 600;">
                                                <i class="fa fa-check-circle me-2"></i>
                                                Cari Rekomendasi Paket
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 text-muted small">
                    Rekomendasi ditentukan dari rule aktif pada admin dan output-nya berupa paket bundling.
                </div>
            </div>
        </div>
    </div>
</div>
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
                '<i class="fa fa-circle-info me-2"></i>' +
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
