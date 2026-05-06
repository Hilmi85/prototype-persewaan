@extends('admin.layouts.master')
@section('title', 'Data Order')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Data Order</h3>
            <p class="text-muted mb-0">
                Kelola seluruh pesanan customer pada sistem persewaan baju adat dan jasa rias.
            </p>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Order</h4>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Order</th>
                                <th>Customer</th>
                                <th>Jenis Acara</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Status Order</th>
                                <th>Tanggal</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $index => $order)
                                @php
                                    $payment = $order->payments->first();
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $order->order_code }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->user->fullname ?? '-' }}</div>
                                        <small class="text-muted">{{ $order->user->phone ?? '-' }}</small>
                                    </td>
                                    <td>{{ $order->jenis_acara ?? '-' }}</td>
                                    <td>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($payment)
                                            <span class="badge bg-{{ $payment->payment_status === 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($payment->payment_status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColor = match($order->status) {
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'booked' => 'primary',
                                                'in_progress' => 'secondary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'dark'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        Belum ada data order.
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
