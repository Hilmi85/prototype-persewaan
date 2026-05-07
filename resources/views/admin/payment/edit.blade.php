@extends('admin.layouts.master')
@section('title', 'Edit Pembayaran')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Edit Pembayaran</h3>
            <p class="text-muted mb-0">Perbarui status pembayaran order.</p>
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
    <h4 class="card-title">Form Edit Pembayaran</h4>
</div>

<div class="card-body">
<form action="{{ route('payments.update', $payment->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Order <span class="text-danger">*</span></label>
            <select name="order_id" class="form-select" required>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ old('order_id', $payment->order_id) == $order->id ? 'selected' : '' }}>
                        {{ $order->order_code }} - {{ $order->user->fullname ?? '-' }} - Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Payment</label>
            <input type="text" name="payment_code" class="form-control" value="{{ old('payment_code', $payment->payment_code) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Metode <span class="text-danger">*</span></label>
            <select name="method" class="form-select" required>
                <option value="tunai" {{ old('method', $payment->method) == 'tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                <option value="qris" {{ old('method', $payment->method) == 'qris' ? 'selected' : '' }}>QRIS Midtrans</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
            <input type="number" name="amount" class="form-control" min="0" value="{{ old('amount', $payment->amount) }}" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="payment_status" class="form-select" required>
                <option value="pending" {{ old('payment_status', $payment->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ old('payment_status', $payment->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ old('payment_status', $payment->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="expired" {{ old('payment_status', $payment->payment_status) == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="refunded" {{ old('payment_status', $payment->payment_status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Paid At</label>
            <input type="datetime-local"
                   name="paid_at"
                   class="form-control"
                   value="{{ old('paid_at', $payment->paid_at ? $payment->paid_at->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Expired At</label>
            <input type="datetime-local"
                   name="expired_at"
                   class="form-control"
                   value="{{ old('expired_at', $payment->expired_at ? $payment->expired_at->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Proof URL</label>
            <input type="text" name="proof_url" class="form-control" value="{{ old('proof_url', $payment->proof_url) }}">
        </div>
    </div>

    @if($payment->method === 'qris')
        <div class="alert alert-light border">
            <strong>Data QRIS Midtrans:</strong>
            <div class="small mt-2">
                <div>Transaction Ref: {{ $payment->transaction_ref ?? '-' }}</div>
                <div>Midtrans Status: {{ $payment->midtrans_status ?? '-' }}</div>
                <div>Payment Type: {{ $payment->payment_type ?? '-' }}</div>
                <div>Redirect URL: {{ $payment->redirect_url ?? '-' }}</div>
            </div>
        </div>
    @endif

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
