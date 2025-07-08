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
        Schema::table('booking_rooms', function (Blueprint $table) {
            // Thêm cột option_id để lưu thông tin gói dịch vụ/option đã chọn
            $table->string('option_id', 50)->nullable()->after('room_id');
            $table->string('option_name', 100)->nullable()->after('option_id');
            $table->decimal('option_price', 15, 2)->nullable()->after('option_name');
            
            // Thêm foreign key constraint nếu cần
            $table->foreign('option_id')->references('option_id')->on('room_option')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_rooms', function (Blueprint $table) {
            // Drop foreign key trước khi drop column
            $table->dropForeign(['option_id']);
            $table->dropColumn(['option_id', 'option_name', 'option_price']);
        });
    }
};
