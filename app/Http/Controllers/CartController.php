<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVariant;
use App\Services\RentalAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index()
    {
        [$cartItems, $subtotal, $changed] = $this->buildCartSummary();
        $rentalDates = session('rental_dates');

        if ($changed) {
            return view('customer.cart', compact('cartItems', 'subtotal', 'rentalDates'))
                ->with('warning', 'Beberapa item keranjang disesuaikan karena stok berubah atau item sudah tidak aktif.');
        }

        return view('customer.cart', compact('cartItems', 'subtotal', 'rentalDates'));
    }

    public function add(Request $request, Item $item, RentalAvailabilityService $availabilityService)
    {
        if (!$item->is_active) {
            return back()->with('error', 'Item tidak aktif atau tidak tersedia.');
        }

        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'item_variant_id' => 'nullable|exists:item_variants,id',
            'rental_start' => 'required|date|after_or_equal:today',
            'rental_end' => 'required|date|after_or_equal:rental_start',
        ], [
            'rental_start.required' => 'Tanggal mulai sewa wajib dipilih sebelum menambahkan item ke keranjang.',
            'rental_start.after_or_equal' => 'Tanggal mulai sewa tidak boleh tanggal yang sudah lewat.',
            'rental_end.required' => 'Tanggal selesai sewa wajib dipilih sebelum menambahkan item ke keranjang.',
            'rental_end.after_or_equal' => 'Tanggal selesai sewa tidak boleh sebelum tanggal mulai sewa.',
        ]);

        $quantity = max(1, (int) ($validated['quantity'] ?? 1));
        $variantId = $validated['item_variant_id'] ?? null;
        $rentalStart = Carbon::parse($validated['rental_start'])->toDateString();
        $rentalEnd = Carbon::parse($validated['rental_end'])->toDateString();

        $currentDates = session('rental_dates');

        if (!empty(session('cart', [])) && $currentDates) {
            $sameDates = ($currentDates['rental_start'] ?? null) === $rentalStart
                && ($currentDates['rental_end'] ?? null) === $rentalEnd;

            if (!$sameDates) {
                return back()
                    ->withInput()
                    ->with('error', 'Keranjang sudah memakai tanggal sewa ' .
                        Carbon::parse($currentDates['rental_start'])->format('d-m-Y') . ' sampai ' .
                        Carbon::parse($currentDates['rental_end'])->format('d-m-Y') .
                        '. Jika ingin mengubah tanggal, ubah dari halaman keranjang terlebih dahulu.');
            }
        }

        $activeVariants = $item->itemVariants()
            ->where('is_active', true)
            ->where('available_stock', '>', 0)
            ->get();

        $variant = null;

        if ($activeVariants->count() > 0) {
            if (!$variantId) {
                return back()->withInput()->with('error', 'Silakan pilih varian ukuran/warna terlebih dahulu.');
            }

            $variant = ItemVariant::with('item')
                ->where('item_id', $item->id)
                ->where('is_active', true)
                ->where('available_stock', '>', 0)
                ->find($variantId);

            if (!$variant) {
                return back()->withInput()->with('error', 'Varian yang dipilih tidak tersedia.');
            }

            $cart = session()->get('cart', []);
            $key = $item->id . '-' . ($variant->id ?? 'no-variant');
            $currentQuantity = (int) ($cart[$key]['quantity'] ?? 0);
            $newQuantity = $currentQuantity + $quantity;

            try {
                $availabilityService->ensureAvailable(
                    $variant,
                    $newQuantity,
                    $rentalStart,
                    $rentalEnd,
                    $item->name . ' (' . trim(($variant->size ?? '-') . ($variant->color ? ' / ' . $variant->color : '')) . ')'
                );
            } catch (ValidationException $e) {
                return back()->withInput()->withErrors($e->errors());
            }
        } elseif ($item->item_type !== 'jasa_rias') {
            return back()->withInput()->with('error', 'Item ini belum memiliki varian/stok tersedia. Silakan hubungi admin.');
        }

        $cart = session()->get('cart', []);
        $key = $item->id . '-' . ($variant?->id ?? 'no-variant');

        $currentQuantity = (int) ($cart[$key]['quantity'] ?? 0);
        $newQuantity = $currentQuantity + $quantity;

        $cart[$key] = [
            'item_id' => $item->id,
            'item_variant_id' => $variant?->id,
            'quantity' => $newQuantity,
        ];

        session()->put('cart', $cart);
        session()->put('rental_dates', [
            'rental_start' => $rentalStart,
            'rental_end' => $rentalEnd,
        ]);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item berhasil ditambahkan ke keranjang untuk tanggal sewa ' .
                Carbon::parse($rentalStart)->format('d-m-Y') . ' sampai ' .
                Carbon::parse($rentalEnd)->format('d-m-Y') . '.');
    }

    public function update(Request $request, string $key, RentalAvailabilityService $availabilityService)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $rentalDates = session('rental_dates');

        if (!isset($cart[$key])) {
            return redirect()->route('cart.index')->with('error', 'Item keranjang tidak ditemukan.');
        }

        if (!$rentalDates) {
            return redirect()->route('cart.index')->with('error', 'Tanggal sewa belum dipilih. Silakan pilih tanggal sewa terlebih dahulu.');
        }

        $cartItem = $cart[$key];
        $item = Item::where('is_active', true)->find($cartItem['item_id'] ?? null);

        if (!$item) {
            unset($cart[$key]);
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('error', 'Item sudah tidak tersedia dan dihapus dari keranjang.');
        }

        if (!empty($cartItem['item_variant_id'])) {
            $variant = ItemVariant::with('item')
                ->where('item_id', $item->id)
                ->where('is_active', true)
                ->find($cartItem['item_variant_id']);

            if (!$variant || $variant->available_stock <= 0) {
                unset($cart[$key]);
                session()->put('cart', $cart);

                return redirect()->route('cart.index')->with('error', 'Varian sudah tidak tersedia dan dihapus dari keranjang.');
            }

            try {
                $availabilityService->ensureAvailable(
                    $variant,
                    (int) $validated['quantity'],
                    $rentalDates['rental_start'],
                    $rentalDates['rental_end'],
                    $item->name . ' (' . trim(($variant->size ?? '-') . ($variant->color ? ' / ' . $variant->color : '')) . ')'
                );
            } catch (ValidationException $e) {
                return redirect()->route('cart.index')->withErrors($e->errors());
            }
        }

        $cart[$key]['quantity'] = $validated['quantity'];
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function updateDates(Request $request, RentalAvailabilityService $availabilityService)
    {
        $validated = $request->validate([
            'rental_start' => 'required|date|after_or_equal:today',
            'rental_end' => 'required|date|after_or_equal:rental_start',
        ], [
            'rental_start.required' => 'Tanggal mulai sewa wajib diisi.',
            'rental_start.after_or_equal' => 'Tanggal mulai sewa tidak boleh tanggal yang sudah lewat.',
            'rental_end.required' => 'Tanggal selesai sewa wajib diisi.',
            'rental_end.after_or_equal' => 'Tanggal selesai sewa tidak boleh sebelum tanggal mulai sewa.',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $rentalStart = Carbon::parse($validated['rental_start'])->toDateString();
        $rentalEnd = Carbon::parse($validated['rental_end'])->toDateString();

        [$cartItems] = $this->buildCartSummary();

        try {
            foreach ($cartItems as $cartItem) {
                if (!$cartItem['variant']) {
                    continue;
                }

                $availabilityService->ensureAvailable(
                    $cartItem['variant']->loadMissing('item'),
                    (int) $cartItem['quantity'],
                    $rentalStart,
                    $rentalEnd,
                    $cartItem['item']->name . ' (' . trim(($cartItem['variant']->size ?? '-') . ($cartItem['variant']->color ? ' / ' . $cartItem['variant']->color : '')) . ')'
                );
            }
        } catch (ValidationException $e) {
            return redirect()->route('cart.index')->withErrors($e->errors());
        }

        session()->put('rental_dates', [
            'rental_start' => $rentalStart,
            'rental_end' => $rentalEnd,
        ]);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Tanggal sewa keranjang berhasil diperbarui.');
    }

    public function remove(string $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        if (empty($cart)) {
            session()->forget('rental_dates');
        }

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget(['cart', 'rental_dates']);

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }

    private function buildCartSummary(): array
    {
        $cart = session()->get('cart', []);
        $rentalDates = session('rental_dates');
        $cleanCart = [];
        $cartItems = [];
        $subtotal = 0;
        $changed = false;
        $availabilityService = app(RentalAvailabilityService::class);

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
            $maxQuantity = null;
            $dateAvailableStock = null;

            if (!empty($cartItem['item_variant_id'])) {
                $variant = ItemVariant::with('item')
                    ->where('item_id', $item->id)
                    ->where('is_active', true)
                    ->find($cartItem['item_variant_id']);

                if (!$variant || $variant->available_stock <= 0) {
                    $changed = true;
                    continue;
                }

                $maxQuantity = (int) $variant->available_stock;

                if ($rentalDates) {
                    try {
                        $availability = $availabilityService->availability(
                            $variant,
                            $rentalDates['rental_start'],
                            $rentalDates['rental_end']
                        );

                        $dateAvailableStock = (int) $availability['available_stock'];
                        $maxQuantity = $dateAvailableStock;
                    } catch (\Throwable $e) {
                        $dateAvailableStock = null;
                    }
                }

                if ($maxQuantity <= 0) {
                    $changed = true;
                    continue;
                }

                if ($quantity > $maxQuantity) {
                    $quantity = $maxQuantity;
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
                'max_quantity' => $maxQuantity,
                'date_available_stock' => $dateAvailableStock,
            ];
        }

        if ($changed) {
            session()->put('cart', $cleanCart);

            if (empty($cleanCart)) {
                session()->forget('rental_dates');
            }
        }

        return [$cartItems, $subtotal, $changed];
    }
}
