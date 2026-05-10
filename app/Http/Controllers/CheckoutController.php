<?php

namespace App\Http\Controllers;

use App\Services\MidtransSnapService;
use App\Services\RentalAvailabilityService;
use App\Models\Bundle;
use App\Models\ContactSetting;
use App\Services\RentalTermsService;
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
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function showBundleCheckout(Bundle $bundle)
    {
        abort_if(!$bundle->is_active, 404);

        $bundle->load([
            'bundleItems.item.category',
            'bundleItems.item.itemVariants' => fn ($query) => $query
                ->where('is_active', true)
                ->orderBy('size')
                ->orderBy('color'),
        ]);

        $contact = ContactSetting::where('is_active', true)->first();

        return view('customer.checkout-bundle', compact('bundle', 'contact'));
    }

    public function storeBundleCheckout(Request $request, Bundle $bundle)
    {
        abort_if(!$bundle->is_active, 404);

        $bundle->load([
            'bundleItems.item.category',
            'bundleItems.item.itemVariants' => fn ($query) => $query
                ->where('is_active', true)
                ->orderBy('size')
                ->orderBy('color'),
        ]);

        if ($bundle->bundleItems->isEmpty()) {
            return back()
                ->withInput()
                ->with('error', 'Isi paket belum diatur oleh admin.');
        }

        $validated = $this->validateCheckout($request);
        $selectedVariants = $request->input('bundle_variants', []);

        DB::beginTransaction();

        try {
            $bundleComponents = $this->prepareBundleComponents(
                $bundle,
                $selectedVariants,
                $validated['rental_start'],
                $validated['rental_end']
            );

            $user = $this->findOrCreateCustomer($validated);
            $total = (int) round($bundle->price);

            $order = Order::create([
                'order_code' => $this->generateOrderCode(),
                'user_id' => $user->id,
                'jenis_acara' => $validated['jenis_acara'] ?? $bundle->jenis_acara,
                'kategori_adat' => $validated['kategori_adat'] ?? $bundle->kategori_adat,
                'gender' => $validated['gender'] ?? $bundle->gender,
                'butuh_rias' => (bool) $validated['butuh_rias'],
                'budget' => $validated['budget'] ?? $bundle->budget_category,
                'subtotal' => $total,
                'tax' => 0,
                'grand_total' => $total,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'note' => $validated['notes'] ?? null,
                'terms_accepted_at' => now(),
                'terms_snapshot' => app(RentalTermsService::class)->snapshot(),
            ]);

            OrderBundle::create([
                'order_id' => $order->id,
                'bundle_id' => $bundle->id,
                'quantity' => 1,
                'price' => $total,
                'total_price' => $total,
            ]);

            foreach ($bundleComponents as $component) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $component['item']->id,
                    'quantity' => $component['quantity'],
                    'price' => 0,
                    'tax' => 0,
                    'total_price' => 0,
                ]);

                if ($component['variant']) {
                    OrderItemVariant::create([
                        'order_item_id' => $orderItem->id,
                        'item_variant_id' => $component['variant']->id,
                        'qty' => $component['quantity'],
                        'unit_price' => 0,
                        'subtotal_price' => 0,
                    ]);

                }
            }

            $this->createRentalBooking($order, $validated, [
                'event_type' => $validated['jenis_acara'] ?? $bundle->jenis_acara,
                'gender' => $validated['gender'] ?? $bundle->gender,
            ]);

            $this->createPayment(
                $order,
                $validated['payment_method'],
                $total,
                $this->buildBundleSnapItems($bundle, $total)
            );

            DB::commit();

            return redirect()
                ->route('checkout.success', $order->order_code)
                ->with('success', 'Checkout paket berhasil dibuat.');
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Checkout paket gagal diproses. ' . $e->getMessage());
        }
    }

    public function showCartCheckout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        [$cartItems, $subtotal, $cleanCart] = $this->buildCartSummary($cart);

        session()->put('cart', $cleanCart);

        if (empty($cartItems)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Tidak ada item valid di keranjang.');
        }

        $contact = ContactSetting::where('is_active', true)->first();

        $rentalDates = session('rental_dates');

        if (!$rentalDates) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Tanggal sewa belum dipilih. Silakan pilih tanggal sewa dari detail produk atau halaman keranjang.');
        }

        return view('customer.checkout-cart', compact('cartItems', 'subtotal', 'contact', 'rentalDates'));
    }

    public function storeCartCheckout(Request $request)
    {
        $validated = $this->validateCheckout($request);
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        [$cartItems, $subtotal] = $this->buildCartSummary($cart);

        if (empty($cartItems)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Tidak ada item valid di keranjang.');
        }

        DB::beginTransaction();

        try {
            $cartItems = $this->validateCartStockBeforeCheckout(
                $cartItems,
                $validated['rental_start'],
                $validated['rental_end']
            );
            $user = $this->findOrCreateCustomer($validated);

            $order = Order::create([
                'order_code' => $this->generateOrderCode(),
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
                'terms_accepted_at' => now(),
                'terms_snapshot' => app(RentalTermsService::class)->snapshot(),
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

            $this->createRentalBooking($order, $validated, [
                'event_type' => $validated['jenis_acara'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ]);

            $this->createPayment(
                $order,
                $validated['payment_method'],
                $subtotal,
                $this->buildCartSnapItems($cartItems)
            );

            session()->forget(['cart', 'rental_dates']);

            DB::commit();

            return redirect()
                ->route('checkout.success', $order->order_code)
                ->with('success', 'Checkout keranjang berhasil dibuat.');
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Checkout keranjang gagal diproses. ' . $e->getMessage());
        }
    }

    public function success(string $orderCode)
    {
        $order = Order::with([
            'user',
            'orderBundles.bundle.bundleItems.item',
            'orderItems.item.category',
            'orderItems.orderItemVariants.itemVariant',
            'payments',
            'rentalBookings',
        ])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $payment = $order->payments->first();

        $midtransSnap = app(MidtransSnapService::class);
        $snapJsUrl = $midtransSnap->snapJsUrl();
        $midtransClientKey = config('midtrans.client_key');

        $receiptUrl = $this->signedReceiptUrl($order);

        return view('customer.checkout-success', compact(
            'order',
            'payment',
            'snapJsUrl',
            'midtransClientKey',
            'receiptUrl'
        ));
    }

    private function validateCheckout(Request $request): array
    {
        return $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'jenis_acara' => 'nullable|string|max:255',
            'kategori_adat' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan,Unisex',
            'butuh_rias' => 'required|boolean',
            'budget' => 'nullable|in:Rendah,Sedang,Tinggi',
            'payment_method' => 'required|in:tunai,qris',

            'event_date' => 'required|date|after_or_equal:today|after_or_equal:rental_start',
            'rental_start' => 'required|date|after_or_equal:today',
            'rental_end' => 'required|date|after_or_equal:rental_start|after_or_equal:event_date',
            'makeup_date' => 'nullable|date|after_or_equal:today|before_or_equal:event_date',

            'agree_terms' => 'accepted',
            'notes' => 'nullable|string',
        ], [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp/HP wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',

            'event_date.required' => 'Tanggal acara wajib diisi.',
            'event_date.after_or_equal' => 'Tanggal acara tidak boleh tanggal yang sudah lewat dan harus berada di dalam rentang sewa.',

            'rental_start.required' => 'Tanggal mulai sewa wajib diisi.',
            'rental_start.after_or_equal' => 'Tanggal mulai sewa tidak boleh tanggal yang sudah lewat.',

            'rental_end.required' => 'Tanggal selesai sewa wajib diisi.',
            'rental_end.after_or_equal' => 'Tanggal selesai sewa tidak boleh sebelum tanggal mulai sewa atau sebelum tanggal acara.',

            'makeup_date.after_or_equal' => 'Tanggal rias tidak boleh tanggal yang sudah lewat.',
            'makeup_date.before_or_equal' => 'Tanggal rias tidak boleh setelah tanggal acara.',

            'agree_terms.accepted' => 'Anda wajib menyetujui aturan sewa sebelum membuat pesanan.',
        ]);
    }

    private function prepareBundleComponents(Bundle $bundle, array $selectedVariants, string $rentalStart, string $rentalEnd): array
    {
        $components = [];

        foreach ($bundle->bundleItems as $bundleItem) {
            $item = $bundleItem->item;

            if (!$item || !$item->is_active) {
                throw ValidationException::withMessages([
                    'bundle_items' => 'Salah satu item pada bundle tidak aktif atau tidak tersedia.',
                ]);
            }

            $quantity = max(1, (int) $bundleItem->quantity);

            $availableVariants = $item->itemVariants
                ->where('is_active', true)
                ->where('available_stock', '>', 0);

            $variant = null;

            if ($item->item_type !== 'jasa_rias') {
                if ($availableVariants->isEmpty()) {
                    throw ValidationException::withMessages([
                        'bundle_variants' => 'Item ' . $item->name . ' belum memiliki varian tersedia.',
                    ]);
                }

                $selectedVariantId = $selectedVariants[$item->id] ?? null;

                if (!$selectedVariantId) {
                    throw ValidationException::withMessages([
                        'bundle_variants.' . $item->id => 'Pilih varian untuk item ' . $item->name . '.',
                    ]);
                }

                $variant = ItemVariant::where('item_id', $item->id)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->find($selectedVariantId);

                if (!$variant || (int) $variant->available_stock <= 0) {
                    throw ValidationException::withMessages([
                        'bundle_variants.' . $item->id => 'Varian untuk item ' . $item->name . ' tidak tersedia.',
                    ]);
                }

                app(RentalAvailabilityService::class)->ensureAvailable(
                    $variant->loadMissing('item'),
                    $quantity,
                    $rentalStart,
                    $rentalEnd,
                    $item->name . ' (' . trim(($variant->size ?? '-') . ($variant->color ? ' / ' . $variant->color : '')) . ')'
                );
            }

            $components[] = [
                'item' => $item,
                'variant' => $variant,
                'quantity' => $quantity,
            ];
        }

        return $components;
    }

    private function validateCartStockBeforeCheckout(array $cartItems, string $rentalStart, string $rentalEnd): array
    {
        $validatedCartItems = [];

        foreach ($cartItems as $cartItem) {
            $variant = null;

            if ($cartItem['variant']) {
                $variant = ItemVariant::with('item')
                    ->where('item_id', $cartItem['item']->id)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->find($cartItem['variant']->id);

                if (!$variant || (int) $variant->available_stock <= 0) {
                    throw ValidationException::withMessages([
                        'cart' => 'Varian ' . $cartItem['item']->name . ' tidak tersedia.',
                    ]);
                }

                app(RentalAvailabilityService::class)->ensureAvailable(
                    $variant,
                    (int) $cartItem['quantity'],
                    $rentalStart,
                    $rentalEnd,
                    $cartItem['item']->name . ' (' . trim(($variant->size ?? '-') . ($variant->color ? ' / ' . $variant->color : '')) . ')'
                );
            }

            $cartItem['variant'] = $variant;
            $validatedCartItems[] = $cartItem;
        }

        return $validatedCartItems;
    }

    private function createRentalBooking(Order $order, array $validated, array $defaults = []): RentalBooking
    {
        return RentalBooking::create([
            'order_id' => $order->id,
            'booking_code' => $this->generateBookingCode(),
            'event_type' => $defaults['event_type'] ?? null,
            'gender' => $defaults['gender'] ?? null,
            'rental_start' => $validated['rental_start'],
            'rental_end' => $validated['rental_end'],
            'event_date' => $validated['event_date'],
            'makeup_date' => $validated['makeup_date'] ?? null,
            'booking_status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);
    }

    private function createPayment(Order $order, string $method, float|int $amount, array $itemDetails = []): Payment
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_code' => $this->generatePaymentCode(),
            'method' => $method,
            'gateway_ref' => $method === 'qris' ? 'midtrans_snap' : 'cash',
            'transaction_ref' => $method === 'qris' ? $order->order_code : null,
            'amount' => (int) round($amount),
            'payment_status' => 'pending',
            'expired_at' => $method === 'qris' ? now()->addDay() : null,
        ]);

        if ($method === 'qris') {
            $payment = app(MidtransSnapService::class)
                ->createQrisTransaction($order->loadMissing('user'), $payment, $itemDetails);
        }

        return $payment;
    }

    private function buildCartSummary(array $cart): array
    {
        $cartItems = [];
        $cleanCart = [];
        $subtotal = 0;

        foreach ($cart as $key => $cartItem) {
            $item = Item::with('category')
                ->where('is_active', true)
                ->find($cartItem['item_id'] ?? null);

            if (!$item) {
                continue;
            }

            $variant = null;
            $quantity = max(1, (int) ($cartItem['quantity'] ?? 1));

            if (!empty($cartItem['item_variant_id'])) {
                $variant = ItemVariant::where('item_id', $item->id)
                    ->where('is_active', true)
                    ->find($cartItem['item_variant_id']);

                if (!$variant || $variant->available_stock <= 0) {
                    continue;
                }

                if ($quantity > $variant->available_stock) {
                    $quantity = $variant->available_stock;
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

        return [$cartItems, $subtotal, $cleanCart];
    }

    private function findOrCreateCustomer(array $validated): User
    {
        $customerRoleId = Role::where('role_name', 'customer')->value('id');

        $user = User::query()
            ->where(function ($query) use ($validated) {
                $query->where('phone', $validated['phone']);

                if (!empty($validated['email'])) {
                    $query->orWhere('email', $validated['email']);
                }
            })
            ->first();

        if ($user) {
            $user->update([
                'fullname' => $validated['fullname'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? $user->email,
                'address' => $validated['address'] ?? $user->address,
                'role_id' => $user->role_id ?: $customerRoleId,
            ]);

            return $user;
        }

        return User::create([
            'fullname' => $validated['fullname'],
            'username' => $this->generateUsername($validated['fullname']),
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'role_id' => $customerRoleId,
            'password' => bcrypt(Str::random(16)),
        ]);
    }

    private function buildBundleSnapItems(Bundle $bundle, int $total): array
{
    return [[
        'id' => 'BUNDLE-' . $bundle->id,
        'price' => $total,
        'quantity' => 1,
        'name' => Str::limit($bundle->bundle_name, 45, ''),
    ]];
}

    private function buildCartSnapItems(array $cartItems): array
    {
        return collect($cartItems)
            ->map(function (array $cartItem) {
                $variantLabel = $cartItem['variant']
                    ? ' - ' . trim(($cartItem['variant']->size ?? '') . ' ' . ($cartItem['variant']->color ?? ''))
                    : '';

                return [
                    'id' => (string) $cartItem['item']->id . '-' . ($cartItem['variant']->id ?? 'default'),
                    'price' => (int) round($cartItem['price']),
                    'quantity' => (int) $cartItem['quantity'],
                    'name' => Str::limit($cartItem['item']->name . $variantLabel, 45, ''),
                ];
            })
            ->values()
            ->all();
    }

    private function generateOrderCode(): string
    {
        do {
            $code = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BOOK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (RentalBooking::where('booking_code', $code)->exists());

        return $code;
    }

    private function generatePaymentCode(): string
    {
        do {
            $code = 'PAY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Payment::where('payment_code', $code)->exists());

        return $code;
    }

    private function generateUsername(string $fullname): string
    {
        $base = Str::slug($fullname, '_') ?: 'customer';

        do {
            $username = Str::limit($base, 20, '') . '_' . strtolower(Str::random(5));
        } while (User::where('username', $username)->exists());

        return $username;
    }

    public function paymentStatus(string $orderCode)
    {
        $order = Order::with(['payments'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $payment = $order->payments->first();

        $paymentStatus = $payment->payment_status ?? 'pending';

        $statusLabel = match($paymentStatus) {
            'paid' => 'Pembayaran Berhasil',
            'failed' => 'Pembayaran Gagal',
            'expired' => 'Pembayaran Kedaluwarsa',
            'refunded' => 'Pembayaran Direfund',
            default => 'Menunggu Pembayaran',
        };

        $statusClass = match($paymentStatus) {
            'paid' => 'success',
            'failed', 'expired' => 'danger',
            'refunded' => 'info',
            default => 'warning text-dark',
        };

        return response()
            ->json([
                'order_code' => $order->order_code,
                'order_status' => $order->status,
                'payment_status' => $paymentStatus,
                'payment_method' => $payment->method ?? $order->payment_method,
                'payment_status_label' => $statusLabel,
                'payment_status_class' => $statusClass,
                'is_paid' => $paymentStatus === 'paid',
                'receipt_url' => $this->signedReceiptUrl($order),
                'checked_at' => now()->format('d-m-Y H:i:s'),
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function receipt(Request $request, string $orderCode)
    {
        if (!$request->hasValidSignature()) {
            return redirect()
                ->route('order.track.index')
                ->withErrors([
                    'order_code' => 'Akses struk tidak valid atau sudah kedaluwarsa. Silakan cek pesanan menggunakan kode order dan nomor WhatsApp/HP.',
                ]);
        }

        $order = Order::with([
            'user',
            'orderBundles.bundle.bundleItems.item',
            'orderItems.item.category',
            'orderItems.orderItemVariants.itemVariant',
            'payments',
            'rentalBookings',
        ])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $payment = $order->payments->first();
        $booking = $order->rentalBookings->first();

        return view('customer.receipt', compact('order', 'payment', 'booking'));
    }

    private function signedReceiptUrl(Order $order): string
    {
        return URL::temporarySignedRoute(
            'checkout.receipt',
            now()->addHours(24),
            ['orderCode' => $order->order_code]
        );
    }
}
