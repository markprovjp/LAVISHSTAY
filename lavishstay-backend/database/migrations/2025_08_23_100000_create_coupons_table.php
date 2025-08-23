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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Mã giảm giá (uppercase)');
            $table->enum('type', ['percent', 'fixed'])->comment('Loại giảm giá: phần trăm hoặc số tiền cố định');
            $table->decimal('value', 10, 2)->comment('Giá trị giảm (% hoặc VND)');
            $table->string('currency', 3)->default('VND')->comment('Đơn vị tiền tệ');
            $table->text('description')->nullable()->comment('Mô tả mã giảm giá');
            $table->timestamp('start_at')->comment('Thời gian bắt đầu có hiệu lực');
            $table->timestamp('end_at')->comment('Thời gian hết hiệu lực');
            $table->integer('usage_limit')->nullable()->comment('Giới hạn số lần sử dụng toàn cục');
            $table->integer('per_user_limit')->nullable()->comment('Giới hạn số lần sử dụng mỗi user');
            $table->decimal('min_booking_amount_vnd', 15, 2)->nullable()->comment('Số tiền booking tối thiểu để áp dụng');
            $table->json('applicable_room_type_ids')->nullable()->comment('Danh sách ID loại phòng áp dụng');
            $table->boolean('stackable')->default(false)->comment('Có thể chồng với mã khác không');
            $table->json('combinable_with')->nullable()->comment('Danh sách mã có thể kết hợp');
            $table->boolean('active')->default(true)->comment('Trạng thái hoạt động');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Người tạo mã');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['code', 'active']);
            $table->index(['start_at', 'end_at']);
            $table->index('type');
            
            // Foreign key
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
