@extends('admin.layouts.master')
@section('title', 'Tambah Bundle')

@section('content')
@php
    $itemCollection = collect($items ?? []);
    $variantCollection = collect($itemVariants ?? $variants ?? []);

    $jenisAcaraOptions = [
        'Pernikahan',
        'Lamaran',
        'Wisuda',
        'Photoshoot',
        'Acara Adat',
        'Lainnya'
    ];

    $kategoriItemOptions = $itemCollection
        ->map(function ($item) {
            return $item->category->cat_name ?? null;
        })
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    if (count($kategoriItemOptions) === 0) {
        $kategoriItemOptions = ['Baju Adat', 'Aksesoris', 'Jasa Rias'];
    }

    $kategoriAdatOptions = $itemCollection
        ->pluck('adat_category')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    if (count($kategoriAdatOptions) === 0) {
        $kategoriAdatOptions = ['Jawa', 'Sunda', 'Bali', 'Minang', 'Batak', 'Bugis', 'Lainnya'];
    }

    $genderOptions = $itemCollection
        ->pluck('gender')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    if (count($genderOptions) === 0) {
        $genderOptions = ['Laki-laki', 'Perempuan', 'Pasangan', 'Unisex'];
    }

    $budgetOptions = ['Rendah', 'Sedang', 'Tinggi'];

    $variantOptions = $variantCollection
        ->map(function ($variant) {
            $item = $variant->item ?? null;

            return [
                'id' => $variant->id,
                'item_id' => $item->id ?? $variant->item_id ?? null,
                'item_name' => $item->name ?? '-',
                'kategori_item' => $item->category->cat_name ?? '-',
                'item_type' => $item->item_type ?? '-',
                'kategori_adat' => $item->adat_category ?? '-',
                'gender' => $item->gender ?? '-',
                'sku_code' => $variant->sku_code ?? '-',
                'size' => $variant->size ?? '-',
                'color' => $variant->color ?? '-',
                'stock' => $variant->stock ?? 0,
                'available_stock' => $variant->available_stock ?? 0,
                'daily_price' => $variant->daily_price ?? $item->price ?? 0,
                'is_active' => $variant->is_active ?? 0,
                'item_is_active' => $item->is_active ?? 0,
            ];
        })
        ->values();

    $selectedJenisAcara = old('jenis_acara');
    $selectedKategoriItem = old('kategori_item');
    $selectedKategoriAdat = old('kategori_adat');
    $selectedGender = old('gender');
    $selectedButuhRias = old('butuh_rias', '1');
    $selectedBudget = old('budget_category');
    $selectedStatus = old('is_active', '1');
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Tambah Bundle</h3>
            <p class="text-muted mb-0">
                Tambahkan bundle berdasarkan output data Item dan Item-Varian.
            </p>
        </div>

        <a href="{{ route('bundles.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header">
    <h4 class="card-title">Form Tambah Bundle Rule-Based</h4>
</div>

<div class="card-body">
<form action="{{ route('bundles.store') }}" method="POST">
    @csrf

    <input type="hidden" name="is_custom" id="is_custom" value="0">
    <input type="hidden" name="recommended_variant_ids" id="recommended_variant_ids" value="{{ old('recommended_variant_ids') }}">
    <div id="recommended_variant_inputs"></div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Jenis Acara</label>
            <select name="jenis_acara" id="jenis_acara" class="form-select" required>
                <option value="">Pilih Jenis Acara</option>
                @foreach($jenisAcaraOptions as $jenisAcara)
                    <option value="{{ $jenisAcara }}" {{ $selectedJenisAcara == $jenisAcara ? 'selected' : '' }}>
                        {{ $jenisAcara }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Kategori Item</label>
            <select name="kategori_item" id="kategori_item" class="form-select" required>
                <option value="">Pilih Kategori Item</option>
                @foreach($kategoriItemOptions as $kategoriItem)
                    <option value="{{ $kategoriItem }}" {{ $selectedKategoriItem == $kategoriItem ? 'selected' : '' }}>
                        {{ $kategoriItem }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Mengikuti kategori pada Data Item.</small>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Kategori Adat</label>
            <select name="kategori_adat" id="kategori_adat" class="form-select" required>
                <option value="">Pilih Kategori Adat</option>
                @foreach($kategoriAdatOptions as $kategoriAdat)
                    <option value="{{ $kategoriAdat }}" {{ $selectedKategoriAdat == $kategoriAdat ? 'selected' : '' }}>
                        {{ $kategoriAdat }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Mengikuti data adat pada Data Item.</small>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-select" required>
                <option value="">Pilih Gender</option>
                @foreach($genderOptions as $gender)
                    <option value="{{ $gender }}" {{ $selectedGender == $gender ? 'selected' : '' }}>
                        {{ $gender }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Mengikuti gender pada Data Item.</small>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Butuh Rias</label>
            <select name="butuh_rias" id="butuh_rias" class="form-select" required>
                <option value="1" {{ $selectedButuhRias == '1' ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ $selectedButuhRias == '0' ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Kategori Budget</label>
            <select name="budget_category" id="budget_category" class="form-select" required>
                <option value="">Pilih Budget</option>
                @foreach($budgetOptions as $budget)
                    <option value="{{ $budget }}" {{ $selectedBudget == $budget ? 'selected' : '' }}>
                        {{ $budget }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Bundle</label>
            <input type="text" name="bundle_name" id="bundle_name" class="form-control" value="{{ old('bundle_name') }}" readonly required>
            <small class="text-muted">Otomatis dibuat dari hasil rule rekomendasi.</small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Bundle</label>
            <input type="text" name="bundle_code" id="bundle_code" class="form-control" value="{{ old('bundle_code') }}" readonly required>
            <small class="text-muted">Otomatis dibuat dari rule dan data output.</small>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Harga Bundle</label>
            <input type="number" name="price" id="price" class="form-control" min="0" value="{{ old('price') }}" readonly required>
            <small class="text-muted">Otomatis dihitung dari harga item-varian yang cocok.</small>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-select" required>
                <option value="1" {{ $selectedStatus == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $selectedStatus == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="col-md-8 mb-3">
            <label class="form-label">Preview Rule</label>
            <div id="rule_preview" class="alert alert-light border mb-0">
                Rule akan muncul otomatis setelah form rekomendasi diisi.
            </div>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Output Data Item & Item-Varian</label>
            <div id="output_preview" class="border rounded p-3 bg-light">
                <span class="text-muted">Data output akan muncul otomatis sesuai rule.</span>
            </div>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Deskripsi Bundle</label>
            <textarea name="description" id="description" rows="4" class="form-control" readonly>{{ old('description') }}</textarea>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('bundles.index') }}" class="btn btn-light-secondary">Batal</a>
    </div>
</form>
</div>
</div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const variants = @json($variantOptions);

    const jenisAcaraInput = document.getElementById('jenis_acara');
    const kategoriItemInput = document.getElementById('kategori_item');
    const kategoriAdatInput = document.getElementById('kategori_adat');
    const genderInput = document.getElementById('gender');
    const butuhRiasInput = document.getElementById('butuh_rias');
    const budgetInput = document.getElementById('budget_category');

    const bundleNameInput = document.getElementById('bundle_name');
    const bundleCodeInput = document.getElementById('bundle_code');
    const priceInput = document.getElementById('price');
    const descriptionInput = document.getElementById('description');
    const isCustomInput = document.getElementById('is_custom');
    const recommendedVariantIdsInput = document.getElementById('recommended_variant_ids');
    const recommendedVariantInputs = document.getElementById('recommended_variant_inputs');

    const rulePreview = document.getElementById('rule_preview');
    const outputPreview = document.getElementById('output_preview');

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function normalizeCode(value, maxLength = 16) {
        if (!value) return '';

        return value
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toUpperCase()
            .replace(/[^A-Z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, maxLength);
    }

    function formatRupiah(value) {
        return 'Rp' + Number(value || 0).toLocaleString('id-ID');
    }

    function isRiasVariant(variant) {
        const category = String(variant.kategori_item || '').toLowerCase();
        const type = String(variant.item_type || '').toLowerCase();
        const name = String(variant.item_name || '').toLowerCase();

        return category.includes('rias') || type.includes('rias') || name.includes('rias');
    }

    function genderMatch(variantGender, selectedGender) {
        variantGender = String(variantGender || '').toLowerCase();
        selectedGender = String(selectedGender || '').toLowerCase();

        if (!selectedGender) return false;
        if (selectedGender === 'pasangan') return true;
        if (variantGender === selectedGender) return true;
        if (variantGender === 'unisex') return true;

        return false;
    }

    function filterVariantsByRule() {
        const kategoriItem = kategoriItemInput.value;
        const kategoriAdat = kategoriAdatInput.value;
        const gender = genderInput.value;
        const butuhRias = butuhRiasInput.value;

        return variants.filter(function (variant) {
            const isActive = Number(variant.is_active) === 1 && Number(variant.item_is_active) === 1;
            const stockReady = Number(variant.available_stock || 0) > 0;
            const kategoriMatch = variant.kategori_item === kategoriItem;
            const adatMatch = variant.kategori_adat === kategoriAdat;
            const genderIsMatch = genderMatch(variant.gender, gender);
            const riasVariant = isRiasVariant(variant);

            if (!isActive || !stockReady || !kategoriMatch || !adatMatch || !genderIsMatch) {
                return false;
            }

            if (butuhRias === '0' && riasVariant) {
                return false;
            }

            return true;
        });
    }

    function applyBudgetRule(list) {
        const budget = budgetInput.value;
        const sorted = [...list].sort(function (a, b) {
            return Number(a.daily_price || 0) - Number(b.daily_price || 0);
        });

        if (sorted.length <= 3) {
            return sorted;
        }

        if (budget === 'Rendah') {
            return sorted.slice(0, 3);
        }

        if (budget === 'Sedang') {
            const start = Math.max(0, Math.floor(sorted.length / 2) - 1);
            return sorted.slice(start, start + 3);
        }

        if (budget === 'Tinggi') {
            return sorted.slice(-3);
        }

        return sorted;
    }

    function makeBundleName(selectedVariants) {
        const acara = jenisAcaraInput.value;
        const kategoriItem = kategoriItemInput.value;
        const adat = kategoriAdatInput.value;
        const gender = genderInput.value;

        if (selectedVariants.length === 0) {
            return 'Paket Custom';
        }

        return ['Paket', acara, kategoriItem, adat, gender]
            .filter(Boolean)
            .join(' ');
    }

    function makeBundleCode(bundleName) {
        const parts = [
            'BDL',
            normalizeCode(jenisAcaraInput.value, 10),
            normalizeCode(kategoriItemInput.value, 10),
            normalizeCode(kategoriAdatInput.value, 10),
            normalizeCode(genderInput.value, 8),
            normalizeCode(budgetInput.value, 8),
            normalizeCode(bundleName, 12)
        ].filter(Boolean);

        return parts.join('-');
    }

    function makeRulePreview(bundleName, selectedVariants) {
        if (
            !jenisAcaraInput.value ||
            !kategoriItemInput.value ||
            !kategoriAdatInput.value ||
            !genderInput.value ||
            !budgetInput.value
        ) {
            rulePreview.innerHTML = 'Rule akan muncul otomatis setelah form rekomendasi diisi.';
            return;
        }

        const rias = butuhRiasInput.value === '1' ? 'Ya' : 'Tidak';

        rulePreview.innerHTML =
            '<strong>IF</strong> jenis_acara = <strong>"' + escapeHtml(jenisAcaraInput.value) + '"</strong>' +
            ' AND kategori_item = <strong>"' + escapeHtml(kategoriItemInput.value) + '"</strong>' +
            ' AND kategori_adat = <strong>"' + escapeHtml(kategoriAdatInput.value) + '"</strong>' +
            ' AND gender = <strong>"' + escapeHtml(genderInput.value) + '"</strong>' +
            ' AND butuh_rias = <strong>"' + escapeHtml(rias) + '"</strong>' +
            ' AND budget = <strong>"' + escapeHtml(budgetInput.value) + '"</strong>' +
            '<br><strong>THEN</strong> tampilkan <strong>"' + escapeHtml(bundleName) + '"</strong>' +
            '<br><strong>ELSE</strong> tampilkan <strong>"Paket Custom"</strong>';
    }

    function renderOutputPreview(selectedVariants) {
        if (selectedVariants.length === 0) {
            outputPreview.innerHTML =
                '<div class="alert alert-warning mb-0">' +
                '<strong>Tidak ada item-varian yang cocok.</strong><br>' +
                'Output rekomendasi menjadi <strong>Paket Custom</strong> karena data belum tersedia pada Item / Item-Varian.' +
                '</div>';
            return;
        }

        let html = '<div class="table-responsive">' +
            '<table class="table table-sm table-bordered mb-0">' +
            '<thead>' +
            '<tr>' +
            '<th>Item</th>' +
            '<th>Kategori</th>' +
            '<th>Adat</th>' +
            '<th>Gender</th>' +
            '<th>SKU</th>' +
            '<th>Size</th>' +
            '<th>Warna</th>' +
            '<th>Stok Tersedia</th>' +
            '<th>Harga</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>';

        selectedVariants.forEach(function (variant) {
            html += '<tr>' +
                '<td>' + escapeHtml(variant.item_name) + '</td>' +
                '<td>' + escapeHtml(variant.kategori_item) + '</td>' +
                '<td>' + escapeHtml(variant.kategori_adat) + '</td>' +
                '<td>' + escapeHtml(variant.gender) + '</td>' +
                '<td>' + escapeHtml(variant.sku_code) + '</td>' +
                '<td>' + escapeHtml(variant.size) + '</td>' +
                '<td>' + escapeHtml(variant.color) + '</td>' +
                '<td>' + escapeHtml(variant.available_stock) + '</td>' +
                '<td>' + formatRupiah(variant.daily_price) + '</td>' +
                '</tr>';
        });

        html += '</tbody></table></div>';
        outputPreview.innerHTML = html;
    }

    function syncHiddenVariants(selectedVariants) {
        const ids = selectedVariants.map(function (variant) {
            return variant.id;
        });

        recommendedVariantIdsInput.value = ids.join(',');

        recommendedVariantInputs.innerHTML = ids.map(function (id) {
            return '<input type="hidden" name="item_variant_ids[]" value="' + escapeHtml(id) + '">';
        }).join('');
    }

    function syncBundleForm() {
        const ruleComplete =
            jenisAcaraInput.value &&
            kategoriItemInput.value &&
            kategoriAdatInput.value &&
            genderInput.value &&
            budgetInput.value;

        if (!ruleComplete) {
            bundleNameInput.value = '';
            bundleCodeInput.value = '';
            priceInput.value = '';
            descriptionInput.value = '';
            isCustomInput.value = '0';
            recommendedVariantIdsInput.value = '';
            recommendedVariantInputs.innerHTML = '';
            rulePreview.innerHTML = 'Rule akan muncul otomatis setelah form rekomendasi diisi.';
            outputPreview.innerHTML = '<span class="text-muted">Data output akan muncul otomatis sesuai rule.</span>';
            return;
        }

        const matchedVariants = filterVariantsByRule();
        const selectedVariants = applyBudgetRule(matchedVariants);
        const bundleName = makeBundleName(selectedVariants);
        const bundleCode = makeBundleCode(bundleName);
        const totalPrice = selectedVariants.reduce(function (total, variant) {
            return total + Number(variant.daily_price || 0);
        }, 0);

        bundleNameInput.value = bundleName;
        bundleCodeInput.value = bundleCode;
        priceInput.value = totalPrice;
        isCustomInput.value = selectedVariants.length === 0 ? '1' : '0';

        descriptionInput.value =
            'Rule-Based Bundle: IF jenis_acara = "' + jenisAcaraInput.value + '"' +
            ' AND kategori_item = "' + kategoriItemInput.value + '"' +
            ' AND kategori_adat = "' + kategoriAdatInput.value + '"' +
            ' AND gender = "' + genderInput.value + '"' +
            ' AND butuh_rias = "' + (butuhRiasInput.value === '1' ? 'Ya' : 'Tidak') + '"' +
            ' AND budget = "' + budgetInput.value + '"' +
            ' THEN tampilkan "' + bundleName + '".';

        makeRulePreview(bundleName, selectedVariants);
        renderOutputPreview(selectedVariants);
        syncHiddenVariants(selectedVariants);
    }

    [
        jenisAcaraInput,
        kategoriItemInput,
        kategoriAdatInput,
        genderInput,
        butuhRiasInput,
        budgetInput
    ].forEach(function (input) {
        input.addEventListener('change', syncBundleForm);
    });

    syncBundleForm();
});
</script>
@endsection
