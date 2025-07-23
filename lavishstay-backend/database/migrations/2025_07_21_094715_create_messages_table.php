<?php

// database/migrations/xxxx_xx_xx_create_messages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->enum('sender_type', ['user', 'staff', 'bot']);
            $table->unsignedBigInteger('sender_id')->nullable()->comment('user_id nếu là người dùng, staff_id nếu là nhân viên, null nếu là bot');
            $table->text('message')->comment('Nội dung tin nhắn');
            $table->boolean('is_from_bot')->default(false);
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('messages');
    }
};
