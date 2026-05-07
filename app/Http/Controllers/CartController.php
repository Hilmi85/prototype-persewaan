<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        [$cartItems, $subtotal, $changed] = $this->buildCartSummary();

        if ($changed) {
            return view('customer.cart', compact('cartItems', 'subtotal'))
                ->with('warning', 'Beberapa item keranjang disesuaikan karena stok berubah atau item sudah tidak aktif.');
        }

        return view('customer.cart', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request, Item $item)
    {
        if (!$item->is_active) {
            return back()->with('error', 'Item tidak aktif atau tidak tersedia.');
        }

        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'item_variant_id' => 'nullable|exists:item_variants,id',
        ]);

        $quantity = max(1, (int) ($validated['quantity'] ?? 1));
        $variantId = $validated['item_variant_id'] ?? null;

        $activeVariants = $item->itemVariants()
            ->where('is_active', true)
            ->where('available_stock', '>', 0)
            ->get();

        $variant = null;

        if ($activeVariants->count() > 0) {
            if (!$variantId) {
                return back()->with('error', 'Silakan pilih varian ukuran/warna terlebih dahulu.');
            }

            $variant = ItemVariant::where('item_id', $item->id)
                ->where('is_active', true)
                ->where('available_stock', '>', 0)
                ->find($variantId);

            if (!$variant) {
                return back()->with('error', 'Varian yang dipilih tidak tersedia.');
            }

            if ($quantity > $variant->available_stock) {
                return back()->with('error', 'Jumlah melebihi stok varian yang tersedia.');
            }
        } elseif ($item->item_type !== 'jasa_rias') {
            return back()->with('error', 'Item ini belum memiliki varian/stok tersedia. Silakan hubungi admin.');
        }

        $cart = session()->get('cart', []);
        $key = $item->id . '-' . ($variant?->id ?? 'no-variant');

        $currentQuantity = (int) ($cart[$key]['quantity'] ?? 0);
        $newQuantity = $currentQuantity + $quantity;

        if ($variant && $newQuantity > $variant->available_stock) {
            return back()->with('error', 'Jumlah total di keranjang melebihi stok tersedia.');
        }

        $cart[$key] = [
            'item_id' => $item->id,
            'item_variant_id' => $variant?->id,
            'quantity' => $newQuantity,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Item berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $key)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->route('cart.index')->with('error', 'Item keranjang tidak ditemukan.');
        }

        $cartItem = $cart[$key];
        $item = Item::where('is_active', true)->find($cartItem['item_id'] ?? null);

        if (!$item) {
            unset($cart[$key]);
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('error', 'Item sudah tidak tersedia dan dihapus dari keranjang.');
        }

        if (!empty($cartItem['item_variant_id'])) {
            $variant = ItemVariant::where('item_id', $item->id)
                ->where('is_active', true)
                ->find($cartItem['item_variant_id']);

            if (!$variant || $variant->available_stock <= 0) {
                unset($cart[$key]);
                session()->put('cart', $cart);

                return redirect()->route('cart.index')->with('error', 'Varian sudah tidak tersedia dan dihapus dari keranjang.');
            }

            if ($validated['quantity'] > $variant->available_stock) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Jumlah melebihi stok tersedia. Stok tersedia: ' . $variant->available_stock);
            }
        }

        $cart[$key]['quantity'] = $validated['quantity'];
        session()->put('cart', $cart);

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

    private function buildCartSummary(): array
    {
        $cart = session()->get('cart', []);
        $cleanCart = [];
        $cartItems = [];
        $subtotal = 0;
        $changed = false;

        foreach ($cart as $key => $cartItem) {
            $item = Item::with('category')
                ->where('is_active', true)
                ->find($cartItem['item_id'] ?? null);

            if (!$item) {
                $changed = true;
                continue;
            }

            $variant = null;
            $quantity = max(1, (int) ($cartItem['quantity'] ?? 1));

            if (!empty($cartItem['item_variant_id'])) {
                $variant = ItemVariant::where('item_id', $item->id)
                    ->where('is_active', true)
                    ->find($cartItem['item_variant_id']);

                if (!$variant || $variant->available_stock <= 0) {
                    $changed = true;
                    continue;
                }

                if ($quantity > $variant->available_stock) {
                    $quantity = $variant->available_stock;
                    $changed = true;
                }
            }

            $price = (int) round($variant?->daily_price ?? $item->price);
            $totalPrice = $price * $quantity;
            $subtotal += $totalPrice;

            $cleanCart[$key] = [
                'item_id' => $item->id,
                'item_variant_id' => $variant?->id,
                'quantity' => $quantity,
            ];

            $cartItems[] = [
                'key' => $key,
                'item' => $item,
                'variant' => $variant,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $totalPrice,
                'max_quantity' => $variant?->available_stock,
            ];
        }

        if ($changed) {
            session()->put('cart', $cleanCart);
        }

        return [$cartItems, $subtotal, $changed];
    }
}
