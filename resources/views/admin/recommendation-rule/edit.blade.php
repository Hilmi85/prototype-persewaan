@extends('admin.layouts.master')

@section('title', 'Edit Recommendation Rule')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3>Edit Rule Rekomendasi</h3>
            <p class="text-muted mb-0">
                Perbarui aturan IF-THEN untuk rekomendasi paket bundling.
            </p>
        </div>

        <a href="{{ route('recommendation-rules.index') }}" class="btn btn-secondary">
            Kembali
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
                <h4 class="card-title mb-0">Form Edit Rule</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('recommendation-rules.update', $recommendationRule->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Rule</label>
                            <input type="text" name="rule_code" class="form-control" value="{{ old('rule_code', $recommendationRule->rule_code) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Rule <span class="text-danger">*</span></label>
                            <input type="text" name="rule_name" class="form-control" value="{{ old('rule_name', $recommendationRule->rule_name) }}" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Output Bundle <span class="text-danger">*</span></label>
                            <select name="bundle_id" class="form-select" required>
                                <option value="">Pilih Bundle sebagai hasil rekomendasi</option>
                                @foreach($bundles as $bundle)
                                    <option value="{{ $bundle->id }}" {{ old('bundle_id', $recommendationRule->bundle_id) == $bundle->id ? 'selected' : '' }}>
                                        {{ $bundle->bundle_name }} - Rp{{ number_format($bundle->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-light border">
                                <strong>Kondisi IF:</strong>
                                kosongkan salah satu field jika rule ingin berlaku untuk semua nilai pada field tersebut.
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Acara</label>
                            <input type="text" name="jenis_acara" class="form-control" value="{{ old('jenis_acara', $recommendationRule->jenis_acara) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori Adat</label>
                            <input type="text" name="kategori_adat" class="form-control" value="{{ old('kategori_adat', $recommendationRule->kategori_adat) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Semua Gender</option>
                                <option value="Laki-laki" {{ old('gender', $recommendationRule->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender', $recommendationRule->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                <option value="Unisex" {{ old('gender', $recommendationRule->gender) === 'Unisex' ? 'selected' : '' }}>Unisex</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Butuh Rias</label>
                            <select name="butuh_rias" class="form-select">
                                <option value="">Semua</option>
                                <option value="1" {{ old('butuh_rias', is_null($recommendationRule->butuh_rias) ? '' : (string) (int) $recommendationRule->butuh_rias) === '1' ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ old('butuh_rias', is_null($recommendationRule->butuh_rias) ? '' : (string) (int) $recommendationRule->butuh_rias) === '0' ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Budget</label>
                            <select name="budget" class="form-select">
                                <option value="">Semua Budget</option>
                                <option value="Rendah" {{ old('budget', $recommendationRule->budget) === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="Sedang" {{ old('budget', $recommendationRule->budget) === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Tinggi" {{ old('budget', $recommendationRule->budget) === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <input type="number" name="priority" class="form-control" min="1" value="{{ old('priority', $recommendationRule->priority) }}" required>
                            <small class="text-muted">Angka lebih kecil akan dicek lebih dulu.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-select" required>
                                <option value="1" {{ old('is_active', $recommendationRule->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $recommendationRule->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" rows="4" class="form-control">{{ old('notes', $recommendationRule->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Update Rule
                        </button>

                        <a href="{{ route('recommendation-rules.index') }}" class="btn btn-light-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
