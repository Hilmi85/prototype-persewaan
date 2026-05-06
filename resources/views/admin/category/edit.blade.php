@extends('admin.layouts.master')
@section('title', 'Edit Kategori')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Edit Kategori</h3>
            <p class="text-muted mb-0">
                Perbarui data kategori sesuai kebutuhan sistem terbaru.
            </p>
        </div>
        <div>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Kategori</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="cat_name" class="form-label">Nama Kategori</label>
                        <input type="text"
                               name="cat_name"
                               id="cat_name"
                               class="form-control @error('cat_name') is-invalid @enderror"
                               value="{{ old('cat_name', $category->cat_name) }}"
                               required>
                        @error('cat_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Masukkan deskripsi kategori">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Update
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-light-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
