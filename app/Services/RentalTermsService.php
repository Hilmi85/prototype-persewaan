<?php

namespace App\Services;

class RentalTermsService
{
    public const VERSION = 'quin-salon-rental-terms-v1';

    public function rules(): array
    {
        return [
            [
                'title' => 'Data pemesanan harus benar',
                'description' => 'Customer wajib mengisi nama, nomor WhatsApp, tanggal acara, dan tanggal sewa dengan benar agar admin dapat memproses pesanan.',
            ],
            [
                'title' => 'Pesanan diproses setelah pembayaran/konfirmasi',
                'description' => 'Pesanan akan diproses setelah pembayaran berhasil atau setelah admin melakukan konfirmasi untuk metode pembayaran tunai.',
            ],
            [
                'title' => 'Pembayaran QRIS memiliki batas waktu',
                'description' => 'Pembayaran QRIS yang melewati batas waktu akan otomatis kedaluwarsa dan pesanan dapat dibatalkan oleh sistem.',
            ],
            [
                'title' => 'Pengembalian wajib sesuai tanggal',
                'description' => 'Customer wajib mengembalikan barang sesuai tanggal selesai sewa yang telah dipilih pada saat checkout.',
            ],
            [
                'title' => 'Keterlambatan dapat dikenakan denda',
                'description' => 'Jika customer terlambat mengembalikan barang, Quin Salon berhak mengenakan denda keterlambatan sesuai ketentuan admin.',
            ],
            [
                'title' => 'Kerusakan atau kehilangan menjadi tanggung jawab customer',
                'description' => 'Barang yang rusak, hilang, kotor berat, atau tidak lengkap saat dikembalikan dapat dikenakan biaya ganti rugi.',
            ],
            [
                'title' => 'Perubahan jadwal wajib dikonfirmasi',
                'description' => 'Perubahan tanggal sewa, tanggal acara, atau kebutuhan lainnya wajib dikonfirmasi kepada admin terlebih dahulu.',
            ],
            [
                'title' => 'Admin berhak membatalkan pesanan bermasalah',
                'description' => 'Quin Salon berhak membatalkan pesanan jika data tidak valid, pembayaran tidak selesai, atau customer tidak dapat dihubungi.',
            ],
        ];
    }

    public function snapshot(): array
    {
        return [
            'version' => self::VERSION,
            'accepted_label' => 'Saya menyetujui aturan sewa, pengembalian barang, denda keterlambatan, dan tanggung jawab kerusakan/hilang.',
            'rules' => $this->rules(),
        ];
    }

    public function normalizeSnapshot(?array $snapshot): array
    {
        if (!$snapshot || !isset($snapshot['rules'])) {
            return $this->snapshot();
        }

        return $snapshot;
    }
}
