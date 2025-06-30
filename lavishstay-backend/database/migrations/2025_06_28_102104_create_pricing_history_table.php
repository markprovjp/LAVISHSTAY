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
        Schema::create('pricing_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->unsignedInteger('room_type_id')->nullable()->comment('Khóa ngoại, mã loại phòng');
            $table->unsignedInteger('option_id')->nullable()->comment('Khóa ngoại, mã tùy chọn phòng');
            $table->date('applied_date')->comment('Ngày áp dụng giá');
            $table->decimal('base_price', 12, 2)->comment('Giá gốc');
            $table->decimal('adjusted_price', 12, 2)->comment('Giá sau điều chỉnh');
            $table->json('applied_rules')->nullable()->comment('Danh sách quy tắc đã áp dụng (JSON)');
            $table->decimal('occupancy_rate', 5, 2)->nullable()->comment('Tỷ lệ lấp đầy tại thời điểm áp dụng (%)');
            $table->enum('pricing_mechanism', ['cumulative', 'highest_priority', 'exclusive'])->default('cumulative')->comment('Cơ chế tính giá');
            $table->text('notes')->nullable()->comment('Ghi chú bổ sung');
            $table->timestamps();

            // Indexes
            $table->index(['applied_date', 'room_type_id'], 'idx_pricing_history_date_room');
            $table->index(['room_type_id', 'applied_date'], 'idx_pricing_history_room_date');
            $table->index('applied_date', 'idx_pricing_history_date');
            $table->index('adjusted_price', 'idx_pricing_history_price');
            $table->index('created_at', 'idx_pricing_history_created');

            // Foreign keys
            $table->foreign('room_type_id')->references('room_type_id')->on('room_types')->onDelete('set null');
            $table->foreign('option_id')->references('option_id')->on('room_option')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_history');
    }
};
