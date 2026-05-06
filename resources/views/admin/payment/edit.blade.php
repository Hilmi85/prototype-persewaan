@extends('admin.layouts.master')
@section('title', 'Edit Payment')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Edit Pembayaran</h3>
            <p class="text-muted mb-0">Perbarui data pembayaran.</p>
        </div>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Form Edit Payment</h4></div>
<div class="card-body">
<form action="{{ route('payments.update', $payment->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Order</label>
            <select name="order_id" class="form-select" required>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ old('order_id', $payment->order_id) == $order->id ? 'selected' : '' }}>
                        {{ $order->order_code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Payment</label>
            <input type="text" name="payment_code" class="form-control" value="{{ old('payment_code', $payment->payment_code) }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Metode</label>
            <select name="method" class="form-select">
                <option value="tunai" {{ old('method', $payment->method) == 'tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="qris" {{ old('method', $payment->method) == 'qris' ? 'selected' : '' }}>QRIS</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Gateway Ref</label>
            <input type="text" name="gateway_ref" class="form-control" value="{{ old('gateway_ref', $payment->gateway_ref) }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Transaction Ref</label>
            <input type="text" name="transaction_ref" class="form-control" value="{{ old('transaction_ref', $payment->transaction_ref) }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" name="amount" class="form-control" min="0" value="{{ old('amount', $payment->amount) }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="payment_status" class="form-select" required>
                <option value="pending" {{ old('payment_status', $payment->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ old('payment_status', $payment->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ old('payment_status', $payment->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="expired" {{ old('payment_status', $payment->payment_status) == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="refunded" {{ old('payment_status', $payment->payment_status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Paid At</label>
            <input type="datetime-local" name="paid_at" class="form-control" value="{{ old('paid_at', $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Expired At</label>
            <input type="datetime-local" name="expired_at" class="form-control" value="{{ old('expired_at', $payment->expired_at ? \Carbon\Carbon::parse($payment->expired_at)->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Proof URL</label>
            <input type="text" name="proof_url" class="form-control" value="{{ old('proof_url', $payment->proof_url) }}">
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('payments.index') }}" class="btn btn-light-secondary">Batal</a>
    </div>
</form>
</div>
</div>
</section>
</div>
@endsection
