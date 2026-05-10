<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index()
    {
        $contact = ContactSetting::where('is_active', true)->first();

        return view('customer.order-status', [
            'order' => null,
            'payment' => null,
            'booking' => null,
            'contact' => $contact,
        ]);
    }

    public function check(Request $request)
    {
        $validated = $request->validate([
            'order_code' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
        ], [
            'order_code.required' => 'Kode order wajib diisi.',
            'phone.required' => 'Nomor WhatsApp/HP wajib diisi.',
        ]);

        $orderCode = trim($validated['order_code']);
        $inputPhone = trim($validated['phone']);

        $order = Order::with([
                'user',
                'orderBundles.bundle.bundleItems.item',
                'orderItems.item.category',
                'orderItems.orderItemVariants.itemVariant',
                'payments',
                'rentalBookings',
            ])
            ->where('order_code', $orderCode)
            ->first();

        if (!$order || !$this->phoneMatches($inputPhone, $order->user->phone ?? null)) {
            return back()
                ->withInput()
                ->withErrors([
                    'order_code' => 'Data pesanan tidak ditemukan. Pastikan kode order dan nomor WhatsApp/HP sesuai dengan data saat checkout.',
                ]);
        }

        $contact = ContactSetting::where('is_active', true)->first();
        $payment = $order->payments->first();
        $booking = $order->rentalBookings->first();

        return view('customer.order-status', compact(
            'order',
            'payment',
            'booking',
            'contact'
        ));
    }

    private function phoneMatches(string $inputPhone, ?string $storedPhone): bool
    {
        if (!$storedPhone) {
            return false;
        }

        $input = $this->normalizePhone($inputPhone);
        $stored = $this->normalizePhone($storedPhone);

        if (!$input || !$stored) {
            return false;
        }

        if ($input === $stored) {
            return true;
        }

        /*
         * Toleransi format nomor:
         * 08123456789
         * 628123456789
         * +628123456789
         *
         * Sistem membandingkan 8 digit terakhir agar tetap cocok
         * meskipun format awal nomor berbeda.
         */
        return strlen($input) >= 8
            && strlen($stored) >= 8
            && substr($input, -8) === substr($stored, -8);
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '62')) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }
}
