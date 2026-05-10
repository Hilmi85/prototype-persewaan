<?php

use App\Services\ExpiredOrderService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('orders:expire-pending {--limit=100}', function (ExpiredOrderService $expiredOrderService) {
    $limit = (int) $this->option('limit');

    $result = $expiredOrderService->expirePendingQrisOrders($limit > 0 ? $limit : null);

    $this->info('Auto expire order pending selesai.');
    $this->line('Order expired: ' . $result['expired_count']);
    $this->line('Order dilewati: ' . $result['skipped_count']);

    if (!empty($result['expired_orders'])) {
        $this->table(
            ['Kode Order', 'Customer', 'Kode Payment', 'Expired At', 'Booking Cancelled'],
            collect($result['expired_orders'])->map(function ($row) {
                return [
                    $row['order_code'],
                    $row['customer'],
                    $row['payment_code'],
                    $row['expired_at'],
                    $row['cancelled_bookings'],
                ];
            })->toArray()
        );
    }
})->purpose('Expire order QRIS pending yang sudah melewati batas pembayaran');

Schedule::command('orders:expire-pending --limit=100')
    ->everyTenMinutes()
    ->withoutOverlapping();
