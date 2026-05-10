<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ExpiredOrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(ExpiredOrderService $expiredOrderService)
    {
        $orders = Order::with(['user', 'orderItems.item', 'orderBundles.bundle', 'payments', 'rentalBookings'])
            ->latest()
            ->get();

        $expiredPendingCount = $expiredOrderService->countExpiredPendingQrisOrders();

        return view('admin.order.index', compact('orders', 'expiredPendingCount'));
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

        if ($validated['status'] === 'cancelled') {
            $order->rentalBookings()
                ->whereNotIn('booking_status', ['cancelled', 'returned'])
                ->update([
                    'booking_status' => 'cancelled',
                ]);
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Status order berhasil diperbarui.');
    }

    public function expirePendingOrders(ExpiredOrderService $expiredOrderService)
    {
        $result = $expiredOrderService->expirePendingQrisOrders();

        return redirect()
            ->route('orders.index')
            ->with('success', $result['expired_count'] . ' order QRIS pending berhasil di-expire otomatis.');
    }

    public function expireSingle(Order $order, ExpiredOrderService $expiredOrderService)
    {
        $result = $expiredOrderService->expireOrder($order);

        if (!$result) {
            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'Order ini belum bisa di-expire. Pastikan order masih pending, pembayaran QRIS masih pending, dan expired_at sudah lewat.');
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order ' . $result['order_code'] . ' berhasil di-expire dan booking terkait sudah dibatalkan.');
    }
}
