@extends('admin.layouts.master')
@section('title', 'Tambah Bundle')

@section('content')
@php
    $items = collect($items ?? []);

    $selectedItems = collect(old('bundle_items', []))
        ->map(fn ($value) => (int) $value)
        ->values();

    $jenisAcaraOptions = ['Pernikahan', 'Lamaran', 'Wisuda', 'Photoshoot', 'Acara Adat', 'Lainnya'];
    $kategoriAdatOptions = ['Jawa', 'Sunda', 'Bali', 'Minang', 'Batak', 'Bugis', 'Modern', 'Lainnya'];
    $genderOptions = ['Laki-laki', 'Perempuan', 'Unisex'];
    $budgetOptions = ['Rendah', 'Sedang', 'Tinggi'];

    $selectedButuhRias = old('butuh_rias', '1');
    $selectedCustom = old('is_custom', '0');
    $selectedStatus = old('is_active', '1');
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Tambah Bundle</h3>
            <p class="text-muted mb-0">
                Buat paket bundling berisi beberapa item layanan.
            </p>
        </div>

        <a href="{{ route('bundles.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Data belum valid.</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
<div class="card-header">
    <h4 class="card-title">Form Tambah Bundle</h4>
</div>

<div class="card-body">
<form action="{{ route('bundles.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Bundle <span class="text-danger">*</span></label>
            <input type="text" name="bundle_name" id="bundle_name" class="form-control" value="{{ old('bundle_name') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Bundle <span class="text-danger">*</span></label>
            <input type="text" name="bundle_code" id="bundle_code" class="form-control" value="{{ old('bundle_code') }}" required>
            <small class="text-muted">Boleh diisi manual atau otomatis dari nama bundle.</small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Jenis Acara</label>
            <select name="jenis_acara" id="jenis_acara" class="form-select">
                <option value="">Pilih Jenis Acara</option>
                @foreach($jenisAcaraOptions as $jenisAcara)
                    <option value="{{ $jenisAcara }}" {{ old('jenis_acara') == $jenisAcara ? 'selected' : '' }}>
                        {{ $jenisAcara }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kategori Adat</label>
            <select name="kategori_adat" id="kategori_adat" class="form-select">
                <option value="">Pilih Kategori Adat</option>
                @foreach($kategoriAdatOptions as $kategoriAdat)
                    <option value="{{ $kategoriAdat }}" {{ old('kategori_adat') == $kategoriAdat ? 'selected' : '' }}>
                        {{ $kategoriAdat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-select">
                <option value="">Pilih Gender</option>
                @foreach($genderOptions as $gender)
                    <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>
                        {{ $gender }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Butuh Rias <span class="text-danger">*</span></label>
            <select name="butuh_rias" class="form-select" required>
                <option value="1" {{ $selectedButuhRias == '1' ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ $selectedButuhRias == '0' ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Kategori Budget</label>
            <select name="budget_category" class="form-select">
                <option value="">Pilih Budget</option>
                @foreach($budgetOptions as $budget)
                    <option value="{{ $budget }}" {{ old('budget_category') == $budget ? 'selected' : '' }}>
                        {{ $budget }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Harga Bundle <span class="text-danger">*</span></label>
            <input type="number" name="price" class="form-control" min="0" value="{{ old('price', 0) }}" required>
            <small class="text-muted">Harga checkout mengikuti harga bundle ini.</small>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Paket Custom <span class="text-danger">*</span></label>
            <select name="is_custom" class="form-select" required>
                <option value="0" {{ $selectedCustom == '0' ? 'selected' : '' }}>Tidak</option>
                <option value="1" {{ $selectedCustom == '1' ? 'selected' : '' }}>Ya</option>
            </select>
            <small class="text-muted">Paket custom dipakai sebagai fallback jika rule tidak cocok.</small>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="is_active" class="form-select" required>
                <option value="1" {{ $selectedStatus == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $selectedStatus == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
        </div>
    </div>

    <div class="alert alert-light border">
        <strong>Isi Bundle:</strong>
        pilih item yang masuk ke paket. Varian/ukuran tidak dipilih di sini, karena varian dicek setelah paket direkomendasikan.
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 70px;">Pilih</th>
                    <th>Item</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Adat</th>
                    <th>Gender</th>
                    <th style="width: 120px;">Qty</th>
                    <th style="width: 130px;">Wajib</th>
                    <th>Varian Tersedia</th>
                </tr>
            </thead>

            <tbody>
                @forelse($items as $item)
                    @php
                        $isChecked = $selectedItems->contains((int) $item->id);
                        $oldQty = old("item_quantities.{$item->id}", 1);
                        $oldRequired = old("item_required.{$item->id}", 1);
                        $availableVariantCount = $item->itemVariants
                            ->where('is_active', true)
                            ->where('available_stock', '>', 0)
                            ->count();
                    @endphp

                    <tr>
                        <td class="text-center">
                            <input type="checkbox"
                                   name="bundle_items[]"
                                   value="{{ $item->id }}"
                                   class="form-check-input bundle-item-check"
                                   {{ $isChecked ? 'checked' : '' }}>
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $item->name }}</div>
                            <small class="text-muted">
                                Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}
                            </small>
                        </td>

                        <td>{{ $item->category->cat_name ?? '-' }}</td>
                        <td>{{ str_replace('_', ' ', $item->item_type ?? '-') }}</td>
                        <td>{{ $item->adat_category ?? '-' }}</td>
                        <td>{{ $item->gender ?? '-' }}</td>

                        <td>
                            <input type="number"
                                   name="item_quantities[{{ $item->id }}]"
                                   class="form-control form-control-sm"
                                   min="1"
                                   value="{{ $oldQty }}">
                        </td>

                        <td>
                            <input type="hidden" name="item_required[{{ $item->id }}]" value="0">

                            <select name="item_required[{{ $item->id }}]" class="form-select form-select-sm">
                                <option value="1" {{ $oldRequired == 1 ? 'selected' : '' }}>Wajib</option>
                                <option value="0" {{ $oldRequired == 0 ? 'selected' : '' }}>Opsional</option>
                            </select>
                        </td>

                        <td>
                            @if($availableVariantCount > 0)
                                <span class="badge bg-success">{{ $availableVariantCount }} varian tersedia</span>
                            @else
                                <span class="badge bg-warning text-dark">Perlu konfirmasi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            Belum ada item aktif. Tambahkan data item terlebih dahulu.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
    const bundleNameInput = document.getElementById('bundle_name');
    const bundleCodeInput = document.getElementById('bundle_code');

    function normalizeCode(value) {
        return String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toUpperCase()
            .replace(/[^A-Z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, 40);
    }

    function syncCode() {
        if (!bundleCodeInput.value.trim()) {
            const code = normalizeCode(bundleNameInput.value);

            if (code) {
                bundleCodeInput.value = 'BDL-' + code;
            }
        }
    }

    bundleNameInput.addEventListener('blur', syncCode);
});
</script>
@endsection
