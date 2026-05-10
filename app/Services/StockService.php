<?php

namespace App\Services;

use App\Models\Order;

class StockService
{
    /*
     * Setelah Batch 1, stok booking tidak lagi dikurangi secara global saat checkout.
     * Ketersediaan dihitung berdasarkan:
     * - total stok siap sewa di item_variants.available_stock
     * - booking aktif yang tanggalnya overlap
     *
     * Karena itu perubahan status order tidak perlu menambah/mengurangi available_stock.
     * Pengurangan stok fisik tetap dilakukan dari fitur:
     * - update stok admin
     * - pengembalian barang rusak/hilang
     */
    public function syncForOrderStatusChange(Order $order, string $oldStatus, string $newStatus): void
    {
        return;
    }

    public function restoreOrderStock(Order $order): void
    {
        return;
    }

    public function reserveOrderStock(Order $order): void
    {
        return;
    }
}
