<?php

// database/migrations/xxxx_xx_xx_create_conversations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Người dùng khởi tạo cuộc trò chuyện');
            $table->boolean('is_bot_only')->default(true)->comment('Chỉ là chat với bot');
            $table->unsignedBigInteger('handover_to_user_id')->nullable()->comment('Chuyển tiếp cho nhân viên nếu cần');
            $table->enum('status', ['open', 'active', 'closed'])->default('open')->comment('Trạng thái hội thoại');
            $table->timestamps();

            // Ràng buộc
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('handover_to_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('conversations');
    }
};
