<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use RuntimeException;

class MidtransSnapService
{
    public function createQrisTransaction(Order $order, Payment $payment, array $itemDetails = []): Payment
    {
        $this->configure();

        $grossAmount = (int) round($order->grand_total);

        $params = [
            /*
             * PENTING:
             * Jangan pakai ['qris'] di Snap karena bisa menyebabkan:
             * "No payment channels available".
             *
             * Untuk menampilkan QRIS di Snap desktop, gunakan GoPay/ShopeePay
             * karena Snap akan menampilkan QRIS scan flow pada desktop.
             */
            'enabled_payments' => ['gopay'],

            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => $grossAmount,
            ],

            'customer_details' => [
                'first_name' => $order->user->fullname ?? 'Customer',
                'email' => $order->user->email,
                'phone' => $order->user->phone,
                'billing_address' => [
                    'first_name' => $order->user->fullname ?? 'Customer',
                    'phone' => $order->user->phone,
                    'address' => $order->user->address,
                ],
            ],

            'item_details' => $this->normalizeItemDetails($itemDetails, $order),

            'callbacks' => [
                'finish' => route('checkout.success', $order->order_code),
                'unfinish' => route('checkout.success', $order->order_code),
                'error' => route('checkout.success', $order->order_code),
            ],

            'expiry' => [
                'start_time' => now('Asia/Jakarta')->format('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24,
            ],
        ];

        $snap = Snap::createTransaction($params);

        $payment->update([
            'gateway_ref' => 'midtrans_snap',
            'transaction_ref' => $order->order_code,
            'snap_token' => $snap->token ?? null,
            'redirect_url' => $snap->redirect_url ?? null,
            'response_payload' => json_decode(json_encode($snap), true),
            'expired_at' => now()->addDay(),
        ]);

        return $payment->fresh();
    }

    public function snapJsUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    private function configure(): void
    {
        if (blank(config('midtrans.server_key'))) {
            throw new RuntimeException('MIDTRANS_SERVER_KEY belum diisi di file .env.');
        }

        if (blank(config('midtrans.client_key'))) {
            throw new RuntimeException('MIDTRANS_CLIENT_KEY belum diisi di file .env.');
        }

        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = (bool) config('midtrans.is_production');
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;
    }

    private function normalizeItemDetails(array $itemDetails, Order $order): array
    {
        if (!empty($itemDetails)) {
            return collect($itemDetails)
                ->map(function (array $item) {
                    return [
                        'id' => (string) ($item['id'] ?? Str::random(8)),
                        'price' => (int) round($item['price'] ?? 0),
                        'quantity' => (int) ($item['quantity'] ?? 1),
                        'name' => Str::limit((string) ($item['name'] ?? 'Item'), 45, ''),
                    ];
                })
                ->values()
                ->all();
        }

        return [[
            'id' => 'ORDER-' . $order->id,
            'price' => (int) round($order->grand_total),
            'quantity' => 1,
            'name' => Str::limit('Pembayaran ' . $order->order_code, 45, ''),
        ]];
    }
}
