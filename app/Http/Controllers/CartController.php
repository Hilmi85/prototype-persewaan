<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $key => $cartItem) {
            $item = Item::find($cartItem['item_id']);
            if (!$item) {
                continue;
            }

            $variant = null;
            if (!empty($cartItem['item_variant_id'])) {
                $variant = ItemVariant::find($cartItem['item_variant_id']);
            }

            $price = $variant?->daily_price ?? $item->price;
            $totalPrice = $price * $cartItem['quantity'];
            $subtotal += $totalPrice;

            $cartItems[] = [
                'key' => $key,
                'item' => $item,
                'variant' => $variant,
                'quantity' => $cartItem['quantity'],
                'price' => $price,
                'total_price' => $totalPrice,
            ];
        }

        return view('customer.cart', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'item_variant_id' => 'nullable|exists:item_variants,id',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $variantId = $validated['item_variant_id'] ?? null;

        $cart = session()->get('cart', []);

        $key = $item->id . '-' . ($variantId ?? 'no-variant');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'item_id' => $item->id,
                'item_variant_id' => $variantId,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Item berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $key)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(string $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
