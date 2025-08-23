<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupon_redemptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id');
            $table->unsignedBigInteger('user_id');
            // booking.booking_id is a signed INT in the existing schema, use signed integer to match
            $table->integer('booking_id');
            $table->decimal('amount_saved_vnd', 15, 2);
            $table->timestamp('redeemed_at')->useCurrent();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('booking_id')->references('booking_id')->on('booking')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['coupon_id', 'user_id']);
            $table->index(['booking_id']);
            $table->index('redeemed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_redemptions');
    }
};
