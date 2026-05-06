<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('booking_code')->unique();

            $table->string('event_type')->nullable();
            $table->string('gender')->nullable();

            $table->date('rental_start')->nullable();
            $table->date('rental_end')->nullable();
            $table->date('event_date')->nullable();
            $table->date('fitting_date')->nullable();
            $table->date('makeup_date')->nullable();

            $table->string('pickup_method')->nullable();
            $table->string('booking_status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_bookings');
    }
};
