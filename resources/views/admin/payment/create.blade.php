@extends('admin.layouts.master')
@section('title', 'Tambah Pembayaran')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Tambah Pembayaran</h3>
            <p class="text-muted mb-0">Tambahkan atau verifikasi data pembayaran order.</p>
        </div>

        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
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
    <h4 class="card-title">Form Tambah Pembayaran</h4>
</div>

<div class="card-body">
<form action="{{ route('payments.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Order <span class="text-danger">*</span></label>
            <select name="order_id" id="order_id" class="form-select" required>
                <option value="">Pilih Order</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}"
                            data-total="{{ $order->grand_total }}"
                            {{ old('order_id') == $order->id ? 'selected' : '' }}>
                        {{ $order->order_code }} - {{ $order->user->fullname ?? '-' }} - Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Payment</label>
            <input type="text" name="payment_code" class="form-control" value="{{ old('payment_code') }}" placeholder="Kosongkan agar otomatis">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Metode <span class="text-danger">*</span></label>
            <select name="method" class="form-select" required>
                <option value="tunai" {{ old('method', 'tunai') == 'tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                <option value="qris" {{ old('method') == 'qris' ? 'selected' : '' }}>QRIS Midtrans</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
            <input type="number" name="amount" id="amount" class="form-control" min="0" value="{{ old('amount') }}" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="payment_status" class="form-select" required>
                <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="expired" {{ old('payment_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Paid At</label>
            <input type="datetime-local" name="paid_at" class="form-control" value="{{ old('paid_at') }}">
            <small class="text-muted">Boleh dikosongkan. Jika status paid, sistem akan mengisi otomatis.</small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Expired At</label>
            <input type="datetime-local" name="expired_at" class="form-control" value="{{ old('expired_at') }}">
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Proof URL</label>
            <input type="text" name="proof_url" class="form-control" value="{{ old('proof_url') }}" placeholder="Opsional">
        </div>
    </div>

    <div class="alert alert-light border">
        <strong>Catatan:</strong>
        Untuk QRIS, biasanya data payment dibuat otomatis saat customer checkout. Form ini lebih sering dipakai admin untuk verifikasi pembayaran cash atau koreksi data.
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('payments.index') }}" class="btn btn-light-secondary">Batal</a>
    </div>
</form>
</div>
</div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const orderSelect = document.getElementById('order_id');
    const amountInput = document.getElementById('amount');

    function syncAmount() {
        const option = orderSelect.options[orderSelect.selectedIndex];

        if (!option || !option.value) {
            return;
        }

        if (!amountInput.value) {
            amountInput.value = option.dataset.total || 0;
        }
    }

    orderSelect.addEventListener('change', function () {
        amountInput.value = '';
        syncAmount();
    });

    syncAmount();
});
</script>
@endsection
