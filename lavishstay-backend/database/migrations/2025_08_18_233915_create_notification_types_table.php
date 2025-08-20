<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notification_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // booking_new, payment_failed, review_new, etc.
            $table->string('title'); // Tiêu đề template
            $table->text('message_template'); // Template tin nhắn với placeholders
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->string('icon')->default('📣'); // Icon hiển thị
            $table->string('color')->default('#3B82F6'); // Màu thông báo
            $table->json('target_roles'); // Array các role sẽ nhận thông báo này
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_types');
    }
};