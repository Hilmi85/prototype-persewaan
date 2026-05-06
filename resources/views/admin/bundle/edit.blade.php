@extends('admin.layouts.master')
@section('title', 'Edit Bundle')

@section('content')
@php
    $selectedGender = old('gender', $bundle->gender);
    $selectedButuhRias = old('butuh_rias', $bundle->butuh_rias);
    $selectedBudget = old('budget_category', $bundle->budget_category);
    $selectedCustom = old('is_custom', $bundle->is_custom);
    $selectedStatus = old('is_active', $bundle->is_active);
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Edit Bundle</h3>
            <p class="text-muted mb-0">Perbarui data bundle.</p>
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
                <h4 class="card-title">Form Edit Bundle</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('bundles.update', $bundle->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Bundle</label>
                            <input type="text" name="bundle_name" id="bundle_name" class="form-control" value="{{ old('bundle_name', $bundle->bundle_name) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Bundle</label>
                            <input type="text" name="bundle_code" id="bundle_code" class="form-control" value="{{ old('bundle_code', $bundle->bundle_code) }}" readonly required>
                            <small class="text-muted">
                                Kode bundle otomatis mengikuti data bundle yang dipilih/diisi.
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Acara</label>
                            <input type="text" name="jenis_acara" id="jenis_acara" class="form-control" value="{{ old('jenis_acara', $bundle->jenis_acara) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori Adat</label>
                            <input type="text" name="kategori_adat" id="kategori_adat" class="form-control" value="{{ old('kategori_adat', $bundle->kategori_adat) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">Pilih Gender</option>
                                <option value="Laki-laki" {{ $selectedGender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ $selectedGender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Butuh Rias</label>
                            <select name="butuh_rias" id="butuh_rias" class="form-select" required>
                                <option value="1" {{ $selectedButuhRias == 1 ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ $selectedButuhRias == 0 ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kategori Budget</label>
                            <select name="budget_category" id="budget_category" class="form-select">
                                <option value="">Pilih Budget</option>
                                <option value="Rendah" {{ $selectedBudget == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="Sedang" {{ $selectedBudget == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Tinggi" {{ $selectedBudget == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" name="price" class="form-control" min="0" value="{{ old('price', $bundle->price) }}" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Custom</label>
                            <select name="is_custom" class="form-select" required>
                                <option value="0" {{ $selectedCustom == 0 ? 'selected' : '' }}>Tidak</option>
                                <option value="1" {{ $selectedCustom == 1 ? 'selected' : '' }}>Ya</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select" required>
                                <option value="1" {{ $selectedStatus == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $selectedStatus == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" rows="4" class="form-control">{{ old('description', $bundle->description) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('bundles.index') }}" class="btn btn-light-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bundleNameInput = document.getElementById('bundle_name');
    const bundleCodeInput = document.getElementById('bundle_code');
    const jenisAcaraInput = document.getElementById('jenis_acara');
    const kategoriAdatInput = document.getElementById('kategori_adat');
    const genderSelect = document.getElementById('gender');
    const budgetSelect = document.getElementById('budget_category');

    function normalizeCode(value, maxLength = 15) {
        if (!value) {
            return '';
        }

        return value
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toUpperCase()
            .replace(/[^A-Z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, maxLength);
    }

    function makeBundleCode() {
        const bundleName = normalizeCode(bundleNameInput.value, 20);
        const jenisAcara = normalizeCode(jenisAcaraInput.value, 12);
        const kategoriAdat = normalizeCode(kategoriAdatInput.value, 12);
        const gender = normalizeCode(genderSelect.value, 10);
        const budget = normalizeCode(budgetSelect.value, 10);

        const parts = ['BDL'];

        if (bundleName) {
            parts.push(bundleName);
        }

        if (jenisAcara) {
            parts.push(jenisAcara);
        }

        if (kategoriAdat) {
            parts.push(kategoriAdat);
        }

        if (gender) {
            parts.push(gender);
        }

        if (budget) {
            parts.push(budget);
        }

        if (parts.length === 1) {
            return '';
        }

        return parts.join('-');
    }

    function syncBundleCode() {
        bundleCodeInput.value = makeBundleCode();
    }

    bundleNameInput.addEventListener('input', syncBundleCode);
    jenisAcaraInput.addEventListener('input', syncBundleCode);
    kategoriAdatInput.addEventListener('input', syncBundleCode);
    genderSelect.addEventListener('change', syncBundleCode);
    budgetSelect.addEventListener('change', syncBundleCode);

    syncBundleCode();
});
</script>
@endsection
