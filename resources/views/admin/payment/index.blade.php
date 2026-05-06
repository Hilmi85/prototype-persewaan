@extends('admin.layouts.master')
@section('title', 'Payment')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Pembayaran</h3>
            <p class="text-muted mb-0">Kelola pembayaran order customer.</p>
        </div>
        <a href="{{ route('payments.create') }}" class="btn btn-primary">Tambah Payment</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Daftar Pembayaran</h4></div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Payment</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Metode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Paid At</th>
                    <th style="width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payment->payment_code }}</td>
                        <td>{{ $payment->order->order_code ?? '-' }}</td>
                        <td>{{ $payment->order->user->fullname ?? '-' }}</td>
                        <td>{{ strtoupper($payment->method ?? '-') }}</td>
                        <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>
                            @if($payment->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($payment->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($payment->payment_status) }}</span>
                            @endif
                        </td>
                        <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d-m-Y H:i') : '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data pembayaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</section>
</div>
@endsection
