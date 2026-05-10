<?php

namespace App\Services;

use App\Models\ContactSetting;
use App\Models\Order;
use App\Models\RentalBooking;
use Carbon\Carbon;

class WhatsappMessageService
{
    public function adminContact(): ?ContactSetting
    {
        return ContactSetting::where('is_active', true)->first();
    }

    public function adminLink(?string $message = null): ?string
    {
        $contact = $this->adminContact();

        if (!$contact) {
            return null;
        }

        if ($contact->whatsapp_number) {
            return $this->linkToPhone($contact->whatsapp_number, $message ?: $contact->message_template);
        }

        if ($contact->whatsapp_url) {
            return $contact->whatsapp_url;
        }

        return null;
    }

    public function linkToPhone(?string $phone, ?string $message = null): ?string
    {
        $phone = $this->normalizePhone($phone);

        if (!$phone) {
            return null;
        }

        $url = 'https://wa.me/' . $phone;

        if (filled($message)) {
            $url .= '?text=' . urlencode($message);
        }

        return $url;
    }

    public function customerGeneralOrder(Order $order): ?string
    {
        $order->loadMissing(['user', 'payments', 'rentalBookings']);

        $payment = $order->payments->first();
        $booking = $order->rentalBookings->first();

        $message = "Halo Kak " . ($order->user->fullname ?? 'Customer') . ",\n\n"
            . "Kami dari Quin Salon ingin menginformasikan detail pesanan Kakak:\n\n"
            . "Kode Order: " . $order->order_code . "\n"
            . "Status Order: " . ucwords(str_replace('_', ' ', $order->status)) . "\n"
            . "Status Pembayaran: " . ucfirst($payment->payment_status ?? '-') . "\n"
            . "Total: Rp" . number_format($order->grand_total ?? 0, 0, ',', '.') . "\n";

        if ($booking) {
            $message .= "Tanggal Sewa: "
                . ($booking->rental_start ? Carbon::parse($booking->rental_start)->format('d-m-Y') : '-')
                . " sampai "
                . ($booking->rental_end ? Carbon::parse($booking->rental_end)->format('d-m-Y') : '-')
                . "\n";
        }

        $message .= "\nSilakan balas pesan ini jika ada yang ingin ditanyakan. Terima kasih.";

        return $this->linkToPhone($order->user->phone ?? null, $message);
    }

    public function customerPaymentInstruction(Order $order): ?string
    {
        $order->loadMissing(['user', 'payments']);

        $payment = $order->payments->first();

        $message = "Halo Kak " . ($order->user->fullname ?? 'Customer') . ",\n\n"
            . "Berikut informasi pembayaran pesanan Quin Salon:\n\n"
            . "Kode Order: " . $order->order_code . "\n"
            . "Kode Pembayaran: " . ($payment->payment_code ?? '-') . "\n"
            . "Metode Pembayaran: " . strtoupper($payment->method ?? $order->payment_method ?? '-') . "\n"
            . "Status Pembayaran: " . ucfirst($payment->payment_status ?? '-') . "\n"
            . "Total Pembayaran: Rp" . number_format($order->grand_total ?? 0, 0, ',', '.') . "\n";

        if ($payment && $payment->expired_at) {
            $message .= "Batas Pembayaran: " . Carbon::parse($payment->expired_at)->format('d-m-Y H:i') . "\n";
        }

        if ($payment && $payment->redirect_url && $payment->payment_status === 'pending') {
            $message .= "\nLink Pembayaran:\n" . $payment->redirect_url . "\n";
        }

        $message .= "\nMohon lakukan pembayaran agar pesanan dapat segera diproses. Terima kasih.";

        return $this->linkToPhone($order->user->phone ?? null, $message);
    }

    public function customerReturnReminder(RentalBooking $booking): ?string
    {
        $booking->loadMissing(['order.user']);

        $order = $booking->order;
        $user = $order?->user;

        $message = "Halo Kak " . ($user->fullname ?? 'Customer') . ",\n\n"
            . "Kami dari Quin Salon ingin mengingatkan jadwal pengembalian barang sewa:\n\n"
            . "Kode Booking: " . $booking->booking_code . "\n"
            . "Kode Order: " . ($order->order_code ?? '-') . "\n"
            . "Tanggal Selesai Sewa: "
            . ($booking->rental_end ? Carbon::parse($booking->rental_end)->format('d-m-Y') : '-') . "\n\n"
            . "Mohon barang dikembalikan tepat waktu dan dalam kondisi baik. Terima kasih.";

        return $this->linkToPhone($user->phone ?? null, $message);
    }

    public function customerAskAdminFromOrder(Order $order): ?string
    {
        $message = "Halo Admin Quin Salon, saya ingin menanyakan pesanan saya.\n\n"
            . "Kode Order: " . $order->order_code . "\n"
            . "Nama: " . ($order->user->fullname ?? '-') . "\n"
            . "Status Order: " . ucwords(str_replace('_', ' ', $order->status)) . "\n\n"
            . "Mohon bantuannya. Terima kasih.";

        return $this->adminLink($message);
    }

    public function customerAskAdminGeneric(): ?string
    {
        return $this->adminLink(
            "Halo Admin Quin Salon, saya ingin bertanya mengenai layanan sewa baju adat / jasa rias."
        );
    }

    public function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        if (!$digits) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '620')) {
            $digits = '62' . substr($digits, 3);
        }

        return $digits;
    }
}
