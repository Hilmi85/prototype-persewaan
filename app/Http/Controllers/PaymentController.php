<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('order.user')->latest()->get();
        return view('admin.payment.index', compact('payments'));
    }

    public function create()
    {
        $orders = Order::latest()->get();
        return view('admin.payment.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_code' => 'required|string|max:255|unique:payments,payment_code',
            'method' => 'nullable|string|max:50',
            'gateway_ref' => 'nullable|string|max:255',
            'transaction_ref' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|string|max:255',
            'paid_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'proof_url' => 'nullable|string|max:255',
        ]);

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    public function edit(Payment $payment)
    {
        $orders = Order::latest()->get();
        return view('admin.payment.edit', compact('payment', 'orders'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_code' => 'required|string|max:255|unique:payments,payment_code,' . $payment->id,
            'method' => 'nullable|string|max:50',
            'gateway_ref' => 'nullable|string|max:255',
            'transaction_ref' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|string|max:255',
            'paid_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'proof_url' => 'nullable|string|max:255',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
