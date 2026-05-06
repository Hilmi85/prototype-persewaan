<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\CoreApi;

class MidtransPaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = (bool) config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createQrisCharge(Order $order, Payment $payment): Payment
    {
        if (!config('midtrans.server_key')) {
            throw new \RuntimeException('MIDTRANS_SERVER_KEY belum diatur pada file .env.');
        }

        $grossAmount = (int) round((float) $payment->amount);

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $payment->payment_code,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->user->fullname ?? $order->user->name ?? 'Customer',
                'email' => $order->user->email,
                'phone' => $order->user->phone,
                'billing_address' => [
                    'address' => $order->user->address,
                ],
            ],
        ];

        $response = CoreApi::charge($params);
        $responseArray = json_decode(json_encode($response), true);

        $qrCodeUrl = collect($responseArray['actions'] ?? [])
            ->firstWhere('name', 'generate-qr-code')['url'] ?? null;

        $payment->update([
            'gateway_ref' => $responseArray['transaction_id'] ?? null,
            'transaction_ref' => $responseArray['order_id'] ?? $payment->payment_code,
            'payment_status' => $this->mapMidtransStatus($responseArray['transaction_status'] ?? 'pending'),
            'expired_at' => isset($responseArray['expiry_time']) ? Carbon::parse($responseArray['expiry_time']) : now()->addMinutes(15),
            'proof_url' => $qrCodeUrl,
            'qr_code_url' => $qrCodeUrl,
            'raw_response' => $responseArray,
        ]);

        return $payment->refresh();
    }

    public function applyNotification(array $payload): ?Payment
    {
        $orderId = $payload['order_id'] ?? null;
        $status = $payload['transaction_status'] ?? null;
        $fraud = $payload['fraud_status'] ?? null;
        $signature = $payload['signature_key'] ?? null;

        if (!$orderId || !$status) {
            return null;
        }

        $payment = Payment::where('payment_code', $orderId)
            ->orWhere('transaction_ref', $orderId)
            ->first();

        if (!$payment) {
            return null;
        }

        if (!$this->validSignature($payload, $signature)) {
            Log::warning('Midtrans notification ignored because signature is invalid.', [
                'order_id' => $orderId,
            ]);

            return null;
        }

        $mappedStatus = $this->mapMidtransStatus($status, $fraud);

        $payment->update([
            'payment_status' => $mappedStatus,
            'paid_at' => $mappedStatus === 'paid' ? now() : $payment->paid_at,
            'raw_response' => $payload,
            'transaction_ref' => $orderId,
            'gateway_ref' => $payload['transaction_id'] ?? $payment->gateway_ref,
        ]);

        if ($mappedStatus === 'paid') {
            $payment->order?->update(['status' => 'confirmed']);
        }

        if (in_array($mappedStatus, ['expired', 'failed', 'cancelled'], true)) {
            $payment->order?->update(['status' => 'cancelled']);
        }

        return $payment->refresh();
    }

    public function mapMidtransStatus(?string $status, ?string $fraudStatus = null): string
    {
        return match ($status) {
            'capture' => $fraudStatus === 'challenge' ? 'challenge' : 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'expire' => 'expired',
            'cancel' => 'cancelled',
            'deny' => 'failed',
            'failure' => 'failed',
            'refund', 'partial_refund' => 'refunded',
            default => 'pending',
        };
    }

    private function validSignature(array $payload, ?string $signature): bool
    {
        if (!$signature || !config('midtrans.server_key')) {
            return true;
        }

        $raw = ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            config('midtrans.server_key');

        return hash_equals(hash('sha512', $raw), $signature);
    }
}
