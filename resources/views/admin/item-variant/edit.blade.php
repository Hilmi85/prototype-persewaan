@extends('admin.layouts.master')
@section('title', 'Edit Item Variant')

@section('content')
@php
    $selectedItemId = old('item_id', $itemVariant->item_id);
    $selectedSize = old('size', $itemVariant->size);
    $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Edit Item Variant</h3>
            <p class="text-muted mb-0">Perbarui data varian item.</p>
        </div>
        <a href="{{ route('item-variants.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header">
    <h4 class="card-title">Form Edit Varian</h4>
</div>

<div class="card-body">
<form action="{{ route('item-variants.update', $itemVariant->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Item</label>
            <select name="item_id" id="item_id" class="form-select" required>
                <option value="">Pilih Item</option>

                @foreach($items as $item)
                    @php
                        $categoryName = $item->category->cat_name ?? '-';
                        $itemPrice = $item->price ?? 0;
                    @endphp

                    <option value="{{ $item->id }}"
                        data-item-name="{{ $item->name }}"
                        data-category="{{ $categoryName }}"
                        data-price="{{ $itemPrice }}"
                        {{ (string) $selectedItemId === (string) $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">
                Pilih item, lalu kategori dan SKU akan menyesuaikan otomatis.
            </small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kategori Item</label>
            <input type="text" id="category_name" class="form-control" readonly>
            <small class="text-muted">
                Kategori otomatis mengikuti item yang dipilih.
            </small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">SKU Code</label>
            <input type="text" name="sku_code" id="sku_code" class="form-control" value="{{ old('sku_code', $itemVariant->sku_code) }}" readonly required>
            <small class="text-muted">
                SKU otomatis dibuat dari item yang dipilih.
            </small>
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Ukuran</label>
            <select name="size" id="size" class="form-select" required>
                <option value="">Pilih Size</option>

                @foreach($sizes as $size)
                    <option value="{{ $size }}" {{ $selectedSize === $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Warna</label>
            <input type="text" name="color" class="form-control" value="{{ old('color', $itemVariant->color) }}">
        </div>

        <div class="col-md-2 mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', $itemVariant->stock) }}" required>
        </div>

        <div class="col-md-2 mb-3">
            <label class="form-label">Stok Tersedia</label>
            <input type="number" name="available_stock" class="form-control" min="0" value="{{ old('available_stock', $itemVariant->available_stock) }}" required>
        </div>

        <div class="col-md-2 mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-select" required>
                <option value="1" {{ old('is_active', $itemVariant->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_active', $itemVariant->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Harga Harian</label>
            <input type="number" name="daily_price" id="daily_price" class="form-control" min="0" value="{{ old('daily_price', $itemVariant->daily_price) }}" required>
            <small class="text-muted">
                Harga harian bisa disesuaikan untuk varian ini.
            </small>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update</button>
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
    const categoryInput = document.getElementById('category_name');
    const skuInput = document.getElementById('sku_code');
    const sizeSelect = document.getElementById('size');
    const dailyPriceInput = document.getElementById('daily_price');

    function getSelectedItem() {
        return itemSelect.options[itemSelect.selectedIndex] || null;
    }

    function makeSkuCode(itemName, itemId, size) {
        let baseName = itemName || 'ITEM';

        baseName = baseName
            .toUpperCase()
            .replace(/[^A-Z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

        let idPart = String(itemId || '').padStart(3, '0');
        let sizePart = size ? '-' + size : '';

        return baseName + '-' + idPart + sizePart;
    }

    function syncItemData(fillPrice = false) {
        const selectedItem = getSelectedItem();

        if (!selectedItem || !selectedItem.value) {
            categoryInput.value = '';
            skuInput.value = '';
            return;
        }

        const itemName = selectedItem.dataset.itemName || '';
        const itemId = selectedItem.value;
        const categoryName = selectedItem.dataset.category || '-';
        const itemPrice = selectedItem.dataset.price || 0;
        const size = sizeSelect.value || '';

        categoryInput.value = categoryName;
        skuInput.value = makeSkuCode(itemName, itemId, size);

        if (fillPrice && dailyPriceInput) {
            dailyPriceInput.value = itemPrice;
        }
    }

    itemSelect.addEventListener('change', function () {
        syncItemData(true);
    });

    sizeSelect.addEventListener('change', function () {
        syncItemData(false);
    });

    syncItemData(false);
});
</script>
@endsection
