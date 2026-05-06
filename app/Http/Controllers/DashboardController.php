<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');
        $todayOrders = Order::whereDate('created_at', now())->count();
        $todayRevenue = Payment::where('payment_status', 'paid')
            ->whereDate('created_at', now())
            ->sum('amount');

        $activeItems = Item::where('is_active', true)->count();
        $activeBundles = Bundle::where('is_active', true)->count();
        $pendingOrders = Order::whereIn('status', ['pending', 'confirmed', 'booked'])->count();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'todayOrders',
            'todayRevenue',
            'activeItems',
            'activeBundles',
            'pendingOrders'
        ));
    }
}
