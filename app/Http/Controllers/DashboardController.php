<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Order;
use App\Models\Payment;
use App\Models\RentalBooking;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $totalOrders = Order::count();

        $totalRevenue = Payment::where('payment_status', 'paid')
            ->sum('amount');

        $todayOrders = Order::whereDate('created_at', $today)
            ->count();

        $todayRevenue = Payment::where('payment_status', 'paid')
            ->whereDate('created_at', $today)
            ->sum('amount');

        $monthlyRevenue = Payment::where('payment_status', 'paid')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('amount');

        $activeItems = Item::where('is_active', true)
            ->count();

        $activeBundles = Bundle::where('is_active', true)
            ->count();

        $pendingOrders = Order::whereIn('status', [
                'pending',
                'confirmed',
                'booked',
            ])
            ->count();

        $qrisPendingPayments = Payment::where('method', 'qris')
            ->where('payment_status', 'pending')
            ->count();

        $expiredPendingCount = Payment::where('method', 'qris')
            ->where('payment_status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->whereHas('order', function ($query) {
                $query->where('status', 'pending');
            })
            ->count();

        $activeBookings = RentalBooking::whereNotIn('booking_status', [
                'returned',
                'cancelled',
            ])
            ->count();

        $todayReturnBookings = RentalBooking::whereDate('rental_end', $today)
            ->whereNotIn('booking_status', [
                'returned',
                'cancelled',
            ])
            ->count();

        $todayStartBookings = RentalBooking::whereDate('rental_start', $today)
            ->whereNotIn('booking_status', [
                'returned',
                'cancelled',
            ])
            ->count();

        $lateBookings = RentalBooking::whereDate('rental_end', '<', $today)
            ->whereNotIn('booking_status', [
                'returned',
                'cancelled',
            ])
            ->count();

        $totalVariants = ItemVariant::count();

        $totalStock = ItemVariant::sum('stock');

        $availableStock = ItemVariant::sum('available_stock');

        $rentedStock = max(0, (int) $totalStock - (int) $availableStock);

        $lowStockVariants = ItemVariant::where('is_active', true)
            ->where('available_stock', '>', 0)
            ->where('available_stock', '<=', 2)
            ->count();

        $emptyStockVariants = ItemVariant::where('is_active', true)
            ->where('available_stock', '<=', 0)
            ->count();

        $recentOrders = Order::with(['user', 'payments', 'rentalBookings'])
            ->latest()
            ->take(6)
            ->get();

        $priorityBookings = RentalBooking::with([
                'order.user',
                'order.payments',
            ])
            ->whereNotIn('booking_status', [
                'returned',
                'cancelled',
            ])
            ->whereNotNull('rental_end')
            ->orderBy('rental_end')
            ->take(6)
            ->get();

        $stockAlerts = ItemVariant::with('item.category')
            ->where('is_active', true)
            ->where('available_stock', '<=', 2)
            ->orderBy('available_stock')
            ->take(8)
            ->get();

        $pendingPaymentOrders = Order::with(['user', 'payments'])
            ->whereHas('payments', function ($query) {
                $query->where('payment_status', 'pending');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'todayOrders',
            'todayRevenue',
            'monthlyRevenue',
            'activeItems',
            'activeBundles',
            'pendingOrders',
            'qrisPendingPayments',
            'expiredPendingCount',
            'activeBookings',
            'todayReturnBookings',
            'todayStartBookings',
            'lateBookings',
            'totalVariants',
            'totalStock',
            'availableStock',
            'rentedStock',
            'lowStockVariants',
            'emptyStockVariants',
            'recentOrders',
            'priorityBookings',
            'stockAlerts',
            'pendingPaymentOrders'
        ));
    }
}
