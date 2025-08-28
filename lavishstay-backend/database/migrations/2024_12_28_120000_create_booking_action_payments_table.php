<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_action_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            $table->unsignedBigInteger('booking_id');
            $table->enum('action_type', ['cancel', 'extend', 'reschedule']);
            $table->decimal('amount', 15, 2);
            $table->string('booking_code');
            $table->json('action_params')->nullable();
            $table->enum('status', ['pending', 'completed', 'expired'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->index(['payment_id', 'status']);
            $table->index(['booking_id', 'action_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_action_payments');
    }
};
