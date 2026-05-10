<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi {{ $order->order_code }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #222;
            margin: 0;
            padding: 24px;
        }

        .receipt-wrapper {
            max-width: 820px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            padding: 32px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            border-bottom: 3px solid #8b5e3c;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .brand h1 {
            margin: 0;
            color: #8b5e3c;
            font-size: 28px;
        }

        .brand p {
            margin: 6px 0 0;
            color: #666;
        }

        .status {
            text-align: right;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            font-weight: bold;
            font-size: 13px;
        }

        .status-paid {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-pending {
            background: #fff3cd;
            color: #664d03;
        }

        .status-failed {
            background: #f8d7da;
            color: #842029;
        }

        .section {
            margin-bottom: 24px;
        }

        .section h3 {
            color: #8b5e3c;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table.info td {
            padding: 6px 0;
            vertical-align: top;
        }

        table.info td:first-child {
            width: 190px;
            color: #666;
        }

        table.items th,
        table.items td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        table.items th {
            background: #fff7ef;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-left: auto;
            width: 320px;
        }

        .summary td {
            padding: 8px 0;
        }

        .summary tr.total td {
            border-top: 2px solid #8b5e3c;
            font-size: 18px;
            font-weight: bold;
            color: #8b5e3c;
        }

        .actions {
            max-width: 820px;
            margin: 18px auto 0;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .btn {
            border: 0;
            padding: 12px 18px;
            border-radius: 999px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }

        .btn-primary {
            background: #8b5e3c;
            color: #fff;
        }

        .btn-secondary {
            background: #e9ecef;
            color: #333;
        }

        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px dashed #ccc;
            color: #666;
            font-size: 13px;
            text-align: center;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .receipt-wrapper {
                border: none;
                max-width: 100%;
                padding: 0;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>
@php
    $whatsappService = app(\App\Services\WhatsappMessageService::class);
    $adminWhatsappUrl = $whatsappService->customerAskAdminFromOrder($order);

    $paymentStatus = $payment->payment_status ?? 'pending';

    $statusLabel = match($paymentStatus) {
        'paid' => 'LUNAS',
        'failed' => 'GAGAL',
        'expired' => 'KEDALUWARSA',
        'refunded' => 'REFUND',
        default => 'PENDING',
    };

    $statusClass = $paymentStatus === 'paid'
        ? 'status-paid'
        : ($paymentStatus === 'pending' ? 'status-pending' : 'status-failed');

    $termsSnapshot = app(\App\Services\RentalTermsService::class)->normalizeSnapshot($order->terms_snapshot);
    $acceptedTerms = $termsSnapshot['rules'] ?? [];
@endphp

<div class="receipt-wrapper">
    <div class="header">
        <div class="brand">
            <h1>Quin Salon</h1>
            <p>Struk / Bukti Transaksi Customer</p>
        </div>

        <div class="status">
            <div class="status-badge {{ $statusClass }}">
                {{ $statusLabel }}
            </div>
            <p style="margin-bottom: 0;">
                {{ now()->format('d-m-Y H:i') }}
            </p>
        </div>
    </div>

    <div class="section">
        <h3>Data Transaksi</h3>

        <table class="info">
            <tr>
                <td>Kode Order</td>
                <td><strong>{{ $order->order_code }}</strong></td>
            </tr>
            <tr>
                <td>Kode Payment</td>
                <td>{{ $payment->payment_code ?? '-' }}</td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td>{{ strtoupper($payment->method ?? $order->payment_method ?? '-') }}</td>
            </tr>
            <tr>
                <td>Status Order</td>
                <td>{{ strtoupper(str_replace('_', ' ', $order->status)) }}</td>
            </tr>
            <tr>
                <td>Tanggal Order</td>
                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Data Customer</h3>

        <table class="info">
            <tr>
                <td>Nama</td>
                <td>{{ $order->user->fullname ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. WhatsApp</td>
                <td>{{ $order->user->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $order->user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>{{ $order->user->address ?? '-' }}</td>
            </tr>
        </table>
    </div>

    @if($booking)
        <div class="section">
            <h3>Data Booking</h3>

            <table class="info">
                <tr>
                    <td>Kode Booking</td>
                    <td>{{ $booking->booking_code }}</td>
                </tr>
                <tr>
                    <td>Jenis Acara</td>
                    <td>{{ $booking->event_type ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tanggal Acara</td>
                    <td>{{ optional($booking->event_date)->format('d-m-Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Mulai Sewa</td>
                    <td>{{ optional($booking->rental_start)->format('d-m-Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Selesai Sewa</td>
                    <td>{{ optional($booking->rental_end)->format('d-m-Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tanggal Rias</td>
                    <td>{{ optional($booking->makeup_date)->format('d-m-Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Status Booking</td>
                    <td>{{ strtoupper(str_replace('_', ' ', $booking->booking_status)) }}</td>
                </tr>
            </table>
        </div>
    @endif

    <div class="section">
        <h3>Detail Pesanan</h3>

        <table class="items">
            <thead>
                <tr>
                    <th>Item / Paket</th>
                    <th>Varian</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($order->orderBundles as $orderBundle)
                    <tr>
                        <td>
                            <strong>{{ $orderBundle->bundle->bundle_name ?? '-' }}</strong>
                            @if($orderBundle->bundle && $orderBundle->bundle->bundleItems->count())
                                <br>
                                <small>
                                    {{ $orderBundle->bundle->bundleItems->map(fn ($bundleItem) => $bundleItem->item->name ?? null)->filter()->implode(', ') }}
                                </small>
                            @endif
                        </td>
                        <td>Paket</td>
                        <td>{{ $orderBundle->quantity }}</td>
                        <td>Rp{{ number_format($orderBundle->price, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($orderBundle->total_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                @foreach($order->orderItems as $orderItem)
                    <tr>
                        <td>{{ $orderItem->item->name ?? '-' }}</td>
                        <td>
                            @forelse($orderItem->orderItemVariants as $orderItemVariant)
                                {{ $orderItemVariant->itemVariant->size ?? '-' }}
                                @if($orderItemVariant->itemVariant?->color)
                                    / {{ $orderItemVariant->itemVariant->color }}
                                @endif
                                <br>
                            @empty
                                -
                            @endforelse
                        </td>
                        <td>{{ $orderItem->quantity }}</td>
                        <td>
                            @if($orderItem->price > 0)
                                Rp{{ number_format($orderItem->price, 0, ',', '.') }}
                            @else
                                Termasuk Paket
                            @endif
                        </td>
                        <td>
                            @if($orderItem->total_price > 0)
                                Rp{{ number_format($orderItem->total_price, 0, ',', '.') }}
                            @else
                                Termasuk Paket
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <table class="summary">
            <tr>
                <td>Subtotal</td>
                <td class="text-right">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pajak</td>
                <td class="text-right">Rp{{ number_format($order->tax, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>Total</td>
                <td class="text-right">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Persetujuan Aturan Sewa</h3>

        <table class="info">
            <tr>
                <td>Waktu Persetujuan</td>
                <td>
                    <strong>
                        {{ $order->terms_accepted_at ? $order->terms_accepted_at->format('d-m-Y H:i') : $order->created_at->format('d-m-Y H:i') }}
                    </strong>
                </td>
            </tr>
        </table>

        <ol style="margin-top: 12px; padding-left: 20px;">
            @foreach($acceptedTerms as $term)
                <li style="margin-bottom: 8px;">
                    <strong>{{ $term['title'] }}</strong><br>
                    <span>{{ $term['description'] }}</span>
                </li>
            @endforeach
        </ol>
    </div>

    <div class="footer">
        Struk ini dibuat otomatis oleh sistem Quin Salon.
        Simpan struk ini sebagai bukti transaksi.
    </div>
</div>

<div class="actions">
    <button onclick="window.print()" class="btn btn-primary">
        Unduh / Cetak PDF
    </button>

    @if($adminWhatsappUrl)
        <a href="{{ $adminWhatsappUrl }}" target="_blank" class="btn btn-primary">
            Chat Admin
        </a>
    @endif

    <a href="{{ route('checkout.success', $order->order_code) }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

</body>
</html>
