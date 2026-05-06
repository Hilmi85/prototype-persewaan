@extends('admin.layouts.master')
@section('title', 'Tambah Payment')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Tambah Pembayaran</h3>
            <p class="text-muted mb-0">Tambahkan data pembayaran order.</p>
        </div>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Form Tambah Payment</h4></div>
<div class="card-body">
<form action="{{ route('payments.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Order</label>
            <select name="order_id" class="form-select" required>
                <option value="">Pilih Order</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">{{ $order->order_code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Payment</label>
            <input type="text" name="payment_code" class="form-control" value="{{ old('payment_code') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Metode</label>
            <select name="method" class="form-select">
                <option value="tunai">Tunai</option>
                <option value="qris">QRIS</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Gateway Ref</label>
            <input type="text" name="gateway_ref" class="form-control" value="{{ old('gateway_ref') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Transaction Ref</label>
            <input type="text" name="transaction_ref" class="form-control" value="{{ old('transaction_ref') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" name="amount" class="form-control" min="0" value="{{ old('amount') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="payment_status" class="form-select" required>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="failed">Failed</option>
                <option value="expired">Expired</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Paid At</label>
            <input type="datetime-local" name="paid_at" class="form-control" value="{{ old('paid_at') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Expired At</label>
            <input type="datetime-local" name="expired_at" class="form-control" value="{{ old('expired_at') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Proof URL</label>
            <input type="text" name="proof_url" class="form-control" value="{{ old('proof_url') }}">
        </div>
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
@endsection
