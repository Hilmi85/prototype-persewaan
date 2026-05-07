@extends('admin.layouts.master')
@section('title', 'Data Pembayaran')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Data Pembayaran</h3>
            <p class="text-muted mb-0">
                Kelola pembayaran cash dan pantau status QRIS Midtrans.
            </p>
        </div>

        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Pembayaran
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header">
    <h4 class="card-title">Daftar Pembayaran</h4>
</div>

<div class="card-body">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-light border">
        <strong>Catatan:</strong>
        Pembayaran cash dapat diverifikasi manual oleh admin. Pembayaran QRIS diperbarui otomatis melalui notifikasi Midtrans jika webhook sudah aktif.
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Payment</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Metode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Ref</th>
                    <th>Paid At</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td class="fw-semibold">{{ $payment->payment_code }}</td>

                        <td>
                            <div class="fw-semibold">
                                {{ $payment->order->order_code ?? '-' }}
                            </div>
                            <small class="text-muted">
                                {{ optional($payment->order?->created_at)->format('d-m-Y H:i') }}
                            </small>
                        </td>

                        <td>{{ $payment->order->user->fullname ?? '-' }}</td>

                        <td>
                            @if($payment->method === 'qris')
                                <span class="badge bg-primary">QRIS</span>
                            @else
                                <span class="badge bg-light text-dark border">CASH</span>
                            @endif
                        </td>

                        <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>

                        <td>
                            @if($payment->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($payment->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($payment->payment_status === 'expired')
                                <span class="badge bg-danger">Expired</span>
                            @elseif($payment->payment_status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($payment->payment_status) }}</span>
                            @endif
                        </td>

                        <td>
                            <div class="small">
                                <div>{{ $payment->transaction_ref ?? '-' }}</div>
                                @if($payment->midtrans_status)
                                    <span class="text-muted">Midtrans: {{ $payment->midtrans_status }}</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            {{ $payment->paid_at ? $payment->paid_at->format('d-m-Y H:i') : '-' }}
                        </td>

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
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            Belum ada data pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</section>
</div>
@endsection
