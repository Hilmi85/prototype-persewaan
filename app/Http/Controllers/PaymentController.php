<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['order.user'])
            ->latest()
            ->get();

        return view('admin.payment.index', compact('payments'));
    }

    public function create()
    {
        $orders = Order::with('user')
            ->latest()
            ->get();

        return view('admin.payment.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayment($request);

        if (empty($validated['payment_code'])) {
            $validated['payment_code'] = $this->generatePaymentCode();
        }

        if (($validated['payment_status'] ?? 'pending') === 'paid' && empty($validated['paid_at'])) {
            $validated['paid_at'] = now();
        }

        $payment = Payment::create($validated);

        $this->syncOrderStatus($payment->fresh('order'));

        return redirect()
            ->route('payments.index')
            ->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    public function edit(Payment $payment)
    {
        $payment->load('order.user');

        $orders = Order::with('user')
            ->latest()
            ->get();

        return view('admin.payment.edit', compact('payment', 'orders'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $this->validatePayment($request, $payment);

        if (($validated['payment_status'] ?? $payment->payment_status) === 'paid' && empty($validated['paid_at'])) {
            $validated['paid_at'] = now();
        }

        if (($validated['payment_status'] ?? $payment->payment_status) !== 'paid') {
            $validated['paid_at'] = $validated['paid_at'] ?? $payment->paid_at;
        }

        $payment->update($validated);

        $this->syncOrderStatus($payment->fresh('order'));

        return redirect()
            ->route('payments.index')
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()
            ->route('payments.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }

    public function midtransNotification(Request $request)
    {
        $payload = $request->all();
        $serverKey = (string) config('midtrans.server_key');

        if (!empty($payload['signature_key'])) {
            $expectedSignature = hash(
                'sha512',
                ($payload['order_id'] ?? '') .
                ($payload['status_code'] ?? '') .
                ($payload['gross_amount'] ?? '') .
                $serverKey
            );

            if (!hash_equals($expectedSignature, (string) $payload['signature_key'])) {
                Log::warning('Midtrans notification rejected: invalid signature.', $payload);

                return response()->json(['message' => 'Invalid signature.'], 403);
            }
        }

        $orderCode = $payload['order_id'] ?? null;

        if (!$orderCode) {
            return response()->json(['message' => 'Order ID is required.'], 422);
        }

        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            Log::warning('Midtrans notification ignored: order not found.', $payload);

            return response()->json(['message' => 'Order not found.'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? 'pending';
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentStatus = $this->mapMidtransStatus($transactionStatus, $fraudStatus);

        $payment = Payment::where('order_id', $order->id)
            ->where('method', 'qris')
            ->first();

        if (!$payment) {
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_code' => $this->generatePaymentCode(),
                'method' => 'qris',
                'gateway_ref' => 'midtrans_snap',
                'amount' => (int) round($order->grand_total),
                'payment_status' => 'pending',
            ]);
        }

        $payment->update([
            'gateway_ref' => 'midtrans_snap',
            'transaction_ref' => $payload['transaction_id'] ?? $payment->transaction_ref,
            'amount' => isset($payload['gross_amount']) ? (int) round((float) $payload['gross_amount']) : $payment->amount,
            'payment_status' => $paymentStatus,
            'paid_at' => $paymentStatus === 'paid' ? now() : $payment->paid_at,
            'expired_at' => $paymentStatus === 'expired' ? now() : $payment->expired_at,
            'midtrans_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $payload['payment_type'] ?? $payment->payment_type,
            'notification_payload' => $payload,
        ]);

        $this->syncOrderStatus($payment->fresh('order'));

        return response()->json(['message' => 'Notification processed.']);
    }

    private function validatePayment(Request $request, ?Payment $payment = null): array
    {
        $paymentId = $payment?->id;

        return $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_code' => 'nullable|string|max:255|unique:payments,payment_code,' . $paymentId,
            'method' => 'required|in:tunai,qris',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed,expired,refunded',
            'paid_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'proof_url' => 'nullable|string|max:255',
        ]);
    }

    private function mapMidtransStatus(?string $transactionStatus, ?string $fraudStatus): string
    {
        return match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => $fraudStatus === 'challenge' ? 'pending' : 'paid',
            'pending' => 'pending',
            'deny', 'cancel', 'failure' => 'failed',
            'expire' => 'expired',
            'refund', 'partial_refund' => 'refunded',
            default => 'pending',
        };
    }

    private function syncOrderStatus(Payment $payment): void
    {
        if (!$payment->order) {
            return;
        }

        $orderStatus = match ($payment->payment_status) {
            'paid' => 'confirmed',
            'failed', 'expired' => 'cancelled',
            default => $payment->order->status,
        };

        $payment->order->update([
            'status' => $orderStatus,
        ]);
    }

    private function generatePaymentCode(): string
    {
        do {
            $code = 'PAY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Payment::where('payment_code', $code)->exists());

        return $code;
    }
}
