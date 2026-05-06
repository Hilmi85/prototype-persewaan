@extends('customer.layouts.master')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container-fluid py-5 d-flex justify-content-center" style="background-color: #fffaf5; min-height: 100vh;">
    <div class="receipt border p-4 bg-white shadow rounded-4" style="width: 520px; margin-top: 5rem">
        <h4 class="text-center mb-2">Pesanan berhasil dibuat!</h4>

        @php
            $payment = $order->payments->first();
        @endphp

        @if ($payment && $payment->payment_status == 'pending')
            <p class="text-center"><span class="badge bg-warning text-dark">Menunggu Pembayaran / Verifikasi</span></p>
        @elseif ($payment && $payment->payment_status == 'paid')
            <p class="text-center"><span class="badge bg-success">Pembayaran Berhasil</span></p>
        @else
            <p class="text-center"><span class="badge bg-secondary">Pesanan Tersimpan</span></p>
        @endif

        <hr>
        <h5 class="fw-bold text-center">
            Kode Order:
            <br>
            <span class="text-primary">{{ $order->order_code }}</span>
        </h5>
        <hr>

        <h5 class="mb-3 text-center">Detail Pesanan</h5>

        <table class="table table-borderless">
            <tbody>
                @foreach ($order->orderBundles as $orderBundle)
                    <tr>
                        <td>{{ $orderBundle->bundle->bundle_name }} ({{ $orderBundle->quantity }})</td>
                        <td class="text-end">Rp{{ number_format($orderBundle->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td>Subtotal</td>
                    <td class="text-end">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pajak</td>
                    <td class="text-end">Rp{{ number_format($order->tax, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td class="text-end fw-bold">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <p class="small text-center text-muted">
            Simpan kode order ini untuk konfirmasi dengan admin jika diperlukan.
        </p>

        <hr>
        <a href="{{ route('home') }}" class="btn btn-primary w-100">Kembali ke Beranda</a>
    </div>
</div>
@endsection
