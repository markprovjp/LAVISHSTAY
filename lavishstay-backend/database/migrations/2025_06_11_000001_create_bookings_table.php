<?php
// File: database/migrations/xxxx_create_bookings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // LAVISH12345678
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->json('rooms_data'); // Lưu thông tin phòng đã chọn
            $table->decimal('total_amount', 15, 2);
            $table->enum('payment_method', ['vietqr', 'pay_at_hotel']);
            $table->enum('payment_status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->timestamp('payment_confirmed_at')->nullable();
            $table->date('check_in');
            $table->date('check_out');
            $table->text('special_requests')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['payment_status', 'payment_method']);
            $table->index('booking_code');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
