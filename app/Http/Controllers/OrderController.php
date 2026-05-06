<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.item', 'orderBundles.bundle', 'payments', 'rentalBookings'])
            ->latest()
            ->get();

        return view('admin.order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.item', 'orderBundles.bundle', 'payments', 'rentalBookings']);

        return view('admin.order.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,booked,in_progress,completed,cancelled',
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Status order berhasil diperbarui.');
    }
}
