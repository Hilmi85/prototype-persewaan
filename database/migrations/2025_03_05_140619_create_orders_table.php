<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->unsignedBigInteger('user_id');

            $table->string('jenis_acara')->nullable();
            $table->string('kategori_adat')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('butuh_rias')->default(false);
            $table->enum('budget', ['Rendah', 'Sedang', 'Tinggi'])->nullable();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            $table->enum('status', [
                'pending',
                'confirmed',
                'booked',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->integer('table_number')->nullable();
            $table->enum('payment_method', ['tunai', 'qris'])->nullable();
            $table->text('note')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
