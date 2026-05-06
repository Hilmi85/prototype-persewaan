<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\ContactSetting;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Order;
use App\Models\OrderBundle;
use App\Models\OrderItem;
use App\Models\OrderItemVariant;
use App\Models\Payment;
use App\Models\RentalBooking;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function showBundleCheckout(Bundle $bundle)
    {
        $bundle->load('bundleItems.item');
        $contact = ContactSetting::where('is_active', true)->first();

        return view('customer.checkout-bundle', compact('bundle', 'contact'));
    }

    public function storeBundleCheckout(Request $request, Bundle $bundle)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'jenis_acara' => 'nullable|string|max:255',
            'kategori_adat' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'butuh_rias' => 'required|boolean',
            'budget' => 'nullable|in:Rendah,Sedang,Tinggi',
            'payment_method' => 'required|in:tunai,qris',
            'event_date' => 'nullable|date',
            'rental_start' => 'nullable|date',
            'rental_end' => 'nullable|date|after_or_equal:rental_start',
            'makeup_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $user = $this->findOrCreateCustomer($validated);

            $order = Order::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'jenis_acara' => $validated['jenis_acara'] ?? $bundle->jenis_acara,
                'kategori_adat' => $validated['kategori_adat'] ?? $bundle->kategori_adat,
                'gender' => $validated['gender'] ?? $bundle->gender,
                'butuh_rias' => (bool) $validated['butuh_rias'],
                'budget' => $validated['budget'] ?? $bundle->budget_category,
                'subtotal' => $bundle->price,
                'tax' => 0,
                'grand_total' => $bundle->price,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'note' => $validated['notes'] ?? null,
            ]);

            OrderBundle::create([
                'order_id' => $order->id,
                'bundle_id' => $bundle->id,
                'quantity' => 1,
                'price' => $bundle->price,
                'total_price' => $bundle->price,
            ]);

            RentalBooking::create([
                'order_id' => $order->id,
                'booking_code' => 'BOOK-' . strtoupper(Str::random(8)),
                'event_type' => $validated['jenis_acara'] ?? $bundle->jenis_acara,
                'gender' => $validated['gender'] ?? $bundle->gender,
                'rental_start' => $validated['rental_start'] ?? null,
                'rental_end' => $validated['rental_end'] ?? null,
                'event_date' => $validated['event_date'] ?? null,
                'makeup_date' => $validated['makeup_date'] ?? null,
                'booking_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'payment_code' => 'PAY-' . strtoupper(Str::random(8)),
                'method' => $validated['payment_method'],
                'amount' => $bundle->price,
                'payment_status' => 'pending',
            ]);

            DB::commit();

            return redirect()
                ->route('checkout.success', $order->order_code)
                ->with('success', 'Checkout paket berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Checkout paket gagal diproses. ' . $e->getMessage());
        }
    }

    public function showCartCheckout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        [$cartItems, $subtotal] = $this->buildCartSummary($cart);
        $contact = ContactSetting::where('is_active', true)->first();

        return view('customer.checkout-cart', compact('cartItems', 'subtotal', 'contact'));
    }

    public function storeCartCheckout(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'jenis_acara' => 'nullable|string|max:255',
            'kategori_adat' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'butuh_rias' => 'required|boolean',
            'budget' => 'nullable|in:Rendah,Sedang,Tinggi',
            'payment_method' => 'required|in:tunai,qris',
            'event_date' => 'nullable|date',
            'rental_start' => 'nullable|date',
            'rental_end' => 'nullable|date|after_or_equal:rental_start',
            'makeup_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        [$cartItems, $subtotal] = $this->buildCartSummary($cart);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada item valid di keranjang.');
        }

        DB::beginTransaction();

        try {
            $user = $this->findOrCreateCustomer($validated);

            $order = Order::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'jenis_acara' => $validated['jenis_acara'] ?? null,
                'kategori_adat' => $validated['kategori_adat'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'butuh_rias' => (bool) $validated['butuh_rias'],
                'budget' => $validated['budget'] ?? null,
                'subtotal' => $subtotal,
                'tax' => 0,
                'grand_total' => $subtotal,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'note' => $validated['notes'] ?? null,
            ]);

            foreach ($cartItems as $cartItem) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $cartItem['item']->id,
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['price'],
                    'tax' => 0,
                    'total_price' => $cartItem['total_price'],
                ]);

                if ($cartItem['variant']) {
                    OrderItemVariant::create([
                        'order_item_id' => $orderItem->id,
                        'item_variant_id' => $cartItem['variant']->id,
                        'qty' => $cartItem['quantity'],
                        'unit_price' => $cartItem['price'],
                        'subtotal_price' => $cartItem['total_price'],
                    ]);
                }
            }

            RentalBooking::create([
                'order_id' => $order->id,
                'booking_code' => 'BOOK-' . strtoupper(Str::random(8)),
                'event_type' => $validated['jenis_acara'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'rental_start' => $validated['rental_start'] ?? null,
                'rental_end' => $validated['rental_end'] ?? null,
                'event_date' => $validated['event_date'] ?? null,
                'makeup_date' => $validated['makeup_date'] ?? null,
                'booking_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'payment_code' => 'PAY-' . strtoupper(Str::random(8)),
                'method' => $validated['payment_method'],
                'amount' => $subtotal,
                'payment_status' => 'pending',
            ]);

            session()->forget('cart');

            DB::commit();

            return redirect()
                ->route('checkout.success', $order->order_code)
                ->with('success', 'Checkout keranjang berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Checkout keranjang gagal diproses. ' . $e->getMessage());
        }
    }

    public function success($orderCode)
    {
        $order = Order::with([
            'user',
            'orderBundles.bundle',
            'orderItems.item',
            'payments',
            'rentalBookings',
        ])->where('order_code', $orderCode)->firstOrFail();

        return view('customer.checkout-success', compact('order'));
    }

    private function buildCartSummary(array $cart): array
    {
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $key => $cartItem) {
            $item = Item::find($cartItem['item_id'] ?? null);

            if (!$item) {
                continue;
            }

            $variant = null;

            if (!empty($cartItem['item_variant_id'])) {
                $variant = ItemVariant::find($cartItem['item_variant_id']);
            }

            $quantity = (int) ($cartItem['quantity'] ?? 1);
            $price = $variant?->daily_price ?? $item->price;
            $totalPrice = $price * $quantity;
            $subtotal += $totalPrice;

            $cartItems[] = [
                'key' => $key,
                'item' => $item,
                'variant' => $variant,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $totalPrice,
            ];
        }

        return [$cartItems, $subtotal];
    }

    private function findOrCreateCustomer(array $validated): User
    {
        $customerRoleId = Role::where('role_name', 'customer')->value('id') ?? 2;

        $user = User::where('phone', $validated['phone'])->first();

        if ($user) {
            $user->update([
                'fullname' => $validated['fullname'],
                'email' => $validated['email'] ?? $user->email,
                'address' => $validated['address'] ?? $user->address,
                'role_id' => $user->role_id ?? $customerRoleId,
            ]);

            return $user;
        }

        return User::create([
            'fullname' => $validated['fullname'],
            'username' => 'cust_' . strtolower(Str::random(6)),
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'role_id' => $customerRoleId,
            'password' => bcrypt('password123'),
        ]);
    }
}
