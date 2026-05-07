@extends('admin.layouts.master')
@section('title', 'Tambah Item Variant')

@section('content')
@php
    $selectedItemId = old('item_id');
    $selectedSize = old('size');
    $selectedStatus = old('is_active', '1');

    $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'All Size'];
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Tambah Item Variant</h3>
            <p class="text-muted mb-0">
                Tambahkan ukuran, warna, stok, dan harga varian item.
            </p>
        </div>

        <a href="{{ route('item-variants.index') }}" class="btn btn-secondary">
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
    <h4 class="card-title">Form Tambah Varian</h4>
</div>

<div class="card-body">
<form action="{{ route('item-variants.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Item <span class="text-danger">*</span></label>
            <select name="item_id" id="item_id" class="form-select" required>
                <option value="">Pilih Item</option>

                @foreach($items as $item)
                    <option value="{{ $item->id }}"
                            data-price="{{ $item->price ?? 0 }}"
                            {{ (string) $selectedItemId === (string) $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                        @if($item->category)
                            - {{ $item->category->cat_name }}
                        @endif
                    </option>
                @endforeach
            </select>
            <small class="text-muted">
                Pilih item utama yang akan diberi varian.
            </small>
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Ukuran <span class="text-danger">*</span></label>
            <select name="size" class="form-select" required>
                <option value="">Pilih Ukuran</option>

                @foreach($sizes as $size)
                    <option value="{{ $size }}" {{ $selectedSize === $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Warna</label>
            <input type="text" name="color" class="form-control" value="{{ old('color') }}" placeholder="Contoh: Merah">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Stok Total <span class="text-danger">*</span></label>
            <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', 0) }}" required>
            <small class="text-muted">
                Stok tersedia otomatis mengikuti stok total saat varian dibuat.
            </small>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Harga Harian <span class="text-danger">*</span></label>
            <input type="number" name="daily_price" id="daily_price" class="form-control" min="0" value="{{ old('daily_price') }}" required>
            <small class="text-muted">
                Bisa mengikuti harga item atau disesuaikan khusus varian.
            </small>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="is_active" class="form-select" required>
                <option value="1" {{ $selectedStatus == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $selectedStatus == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="alert alert-light border">
        <strong>Informasi:</strong>
        SKU varian dibuat otomatis oleh sistem. Admin cukup mengisi item, ukuran, warna, stok, harga, dan status.
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('item-variants.index') }}" class="btn btn-light-secondary">Batal</a>
    </div>
</form>
</div>
</div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemSelect = document.getElementById('item_id');
    const dailyPriceInput = document.getElementById('daily_price');

    function syncDailyPrice() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            return;
        }

        if (!dailyPriceInput.value) {
            dailyPriceInput.value = selectedOption.dataset.price || 0;
        }
    }

    itemSelect.addEventListener('change', function () {
        dailyPriceInput.value = '';
        syncDailyPrice();
    });

    syncDailyPrice();
});
</script>
@endsection
