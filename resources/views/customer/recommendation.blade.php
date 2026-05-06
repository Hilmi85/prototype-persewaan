@extends('customer.layouts.master')

@section('content')
@php
    /*
        Form recommendation customer ini disesuaikan dengan rule:

        IF jenis_acara
        AND kategori_item
        AND kategori_adat
        AND gender
        AND butuh_rias
        AND budget_category
        THEN tampilkan bundle / item-varian yang cocok
        ELSE tampilkan Paket Custom
    */

    $jenisAcaraList = collect($jenisAcaraOptions ?? [
        'Pernikahan',
        'Lamaran',
        'Wisuda',
        'Photoshoot',
        'Acara Adat',
        'Lainnya'
    ])->filter()->unique()->values();

    $kategoriItemList = collect($kategoriItemOptions ?? [
        'Baju Adat',
        'Aksesoris',
        'Jasa Rias'
    ])->filter()->unique()->values();

    $kategoriAdatList = collect($kategoriAdatOptions ?? [
        'Jawa',
        'Sunda',
        'Bali',
        'Minang',
        'Batak',
        'Bugis',
        'Lainnya'
    ])->filter()->unique()->values();

    $genderList = collect($genderOptions ?? [
        'Laki-laki',
        'Perempuan',
        'Pasangan'
    ])->filter()->unique()->values();

    $budgetList = collect($budgetOptions ?? [
        'Rendah',
        'Sedang',
        'Tinggi'
    ])->filter()->unique()->values();

    $selectedJenisAcara = old('jenis_acara');
    $selectedKategoriItem = old('kategori_item');
    $selectedKategoriAdat = old('kategori_adat');
    $selectedGender = old('gender');
    $selectedBudget = old('budget_category', old('budget'));
    $selectedButuhRias = old('butuh_rias', '1');
@endphp

<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.65), rgba(60, 42, 33, 0.65)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <span class="badge rounded-pill px-4 py-2 mb-3"
              style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
            Quin Salon • Rekomendasi Paket
        </span>

        <h1 class="display-4 text-white fw-bold">Temukan Paket yang Cocok</h1>

        <p class="text-white">
            Isi kebutuhan Anda, lalu sistem akan mencocokkan dengan data paket, item, dan item-varian yang tersedia.
        </p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5" style="border: 1px solid #f1e3d3;">
                    <form action="{{ route('recommendation.process') }}" method="POST">
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Acara</label>
                                <select name="jenis_acara" id="jenis_acara" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Jenis Acara --</option>

                                    @foreach ($jenisAcaraList as $jenisAcara)
                                        <option value="{{ $jenisAcara }}" {{ $selectedJenisAcara == $jenisAcara ? 'selected' : '' }}>
                                            {{ $jenisAcara }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kategori Item</label>
                                <select name="kategori_item" id="kategori_item" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Kategori Item --</option>

                                    @foreach ($kategoriItemList as $kategoriItem)
                                        <option value="{{ $kategoriItem }}" {{ $selectedKategoriItem == $kategoriItem ? 'selected' : '' }}>
                                            {{ $kategoriItem }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    Kategori ini mengikuti kategori pada Data Item.
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kategori Adat</label>
                                <select name="kategori_adat" id="kategori_adat" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Kategori Adat --</option>

                                    @foreach ($kategoriAdatList as $kategoriAdat)
                                        <option value="{{ $kategoriAdat }}" {{ $selectedKategoriAdat == $kategoriAdat ? 'selected' : '' }}>
                                            {{ $kategoriAdat }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    Kategori adat mengikuti data adat pada Data Item.
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Gender</label>
                                <select name="gender" id="gender" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Gender --</option>

                                    @foreach ($genderList as $gender)
                                        <option value="{{ $gender }}" {{ $selectedGender == $gender ? 'selected' : '' }}>
                                            {{ $gender }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Budget</label>
                                <select name="budget_category" id="budget_category" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Budget --</option>

                                    @foreach ($budgetList as $budget)
                                        <option value="{{ $budget }}" {{ $selectedBudget == $budget ? 'selected' : '' }}>
                                            {{ $budget }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Untuk menjaga kompatibilitas kalau controller lama masih membaca request('budget') --}}
                                <input type="hidden" name="budget" id="budget" value="{{ $selectedBudget }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Butuh Rias?</label>

                                <div class="d-flex gap-4 pt-2">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="butuh_rias"
                                               id="butuh_rias_ya"
                                               value="1"
                                               {{ $selectedButuhRias == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="butuh_rias_ya">
                                            Ya
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="butuh_rias"
                                               id="butuh_rias_tidak"
                                               value="0"
                                               {{ $selectedButuhRias == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="butuh_rias_tidak">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
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
                                        IF jenis acara, kategori item, kategori adat, gender, kebutuhan rias, dan budget cocok dengan data,
                                        THEN sistem menampilkan paket yang sesuai.
                                        ELSE sistem menampilkan Paket Custom.
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit"
                                        class="btn rounded-pill px-4 py-3"
                                        style="background-color: #8b5e3c; color: #fff; border: none;">
                                    <i class="fa fa-check-circle me-2"></i>Konfirmasi Rekomendasi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-4 text-muted small">
                    Rekomendasi hanya akan menampilkan paket berdasarkan data yang tersedia pada Bundle, Data Item, dan Item-Varian.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisAcaraInput = document.getElementById('jenis_acara');
    const kategoriItemInput = document.getElementById('kategori_item');
    const kategoriAdatInput = document.getElementById('kategori_adat');
    const genderInput = document.getElementById('gender');
    const budgetCategoryInput = document.getElementById('budget_category');
    const budgetLegacyInput = document.getElementById('budget');
    const rulePreview = document.getElementById('rule_preview');

    const riasInputs = document.querySelectorAll('input[name="butuh_rias"]');

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getButuhRiasValue() {
        const selected = document.querySelector('input[name="butuh_rias"]:checked');

        if (!selected) {
            return '1';
        }

        return selected.value;
    }

    function getButuhRiasLabel() {
        return getButuhRiasValue() === '1' ? 'Ya' : 'Tidak';
    }

    function syncBudgetCompatibility() {
        budgetLegacyInput.value = budgetCategoryInput.value;
    }

    function syncRulePreview() {
        syncBudgetCompatibility();

        const jenisAcara = jenisAcaraInput.value;
        const kategoriItem = kategoriItemInput.value;
        const kategoriAdat = kategoriAdatInput.value;
        const gender = genderInput.value;
        const budget = budgetCategoryInput.value;
        const butuhRias = getButuhRiasLabel();

        if (!jenisAcara || !kategoriItem || !kategoriAdat || !gender || !budget) {
            rulePreview.innerHTML =
                '<i class="fa fa-circle-info me-2"></i>' +
                'Sistem akan mencocokkan kebutuhan Anda dengan aturan rekomendasi yang tersedia.';
            return;
        }

        rulePreview.innerHTML =
            '<strong>Preview Rule-Based:</strong><br>' +
            '<span>IF jenis_acara = <strong>"' + escapeHtml(jenisAcara) + '"</strong></span><br>' +
            '<span>AND kategori_item = <strong>"' + escapeHtml(kategoriItem) + '"</strong></span><br>' +
            '<span>AND kategori_adat = <strong>"' + escapeHtml(kategoriAdat) + '"</strong></span><br>' +
            '<span>AND gender = <strong>"' + escapeHtml(gender) + '"</strong></span><br>' +
            '<span>AND butuh_rias = <strong>"' + escapeHtml(butuhRias) + '"</strong></span><br>' +
            '<span>AND budget = <strong>"' + escapeHtml(budget) + '"</strong></span><br>' +
            '<span>THEN tampilkan paket yang sesuai dengan data.</span><br>' +
            '<span>ELSE tampilkan <strong>"Paket Custom"</strong>.</span>';
    }

    [
        jenisAcaraInput,
        kategoriItemInput,
        kategoriAdatInput,
        genderInput,
        budgetCategoryInput
    ].forEach(function (input) {
        input.addEventListener('change', syncRulePreview);
    });

    riasInputs.forEach(function (input) {
        input.addEventListener('change', syncRulePreview);
    });

    syncRulePreview();
});
</script>
@endsection
