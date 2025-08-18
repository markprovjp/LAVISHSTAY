<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('audit_id');
            
            // User information
            $table->unsignedBigInteger('user_id')->nullable()->comment('Người thực hiện hành động');
            $table->string('session_id', 40)->nullable()->comment('Session ID để track theo phiên');
            
            // Action details
            $table->enum('action', ['create', 'update', 'delete', 'restore', 'login', 'logout', 'bulk_update', 'bulk_delete', 'other'])
                  ->comment('Loại hành động');
            
            // Model information
            $table->string('model', 255)->comment('Tên model/bảng tác động');
            $table->unsignedBigInteger('model_id')->comment('ID bản ghi tác động');
            
            // Data changes
            $table->json('old_values')->nullable()->comment('Dữ liệu trước khi thay đổi');
            $table->json('new_values')->nullable()->comment('Dữ liệu sau khi thay đổi');
            $table->text('changes_summary')->nullable()->comment('Tóm tắt thay đổi (human readable)');
            
            // Additional context
            $table->text('description')->nullable()->comment('Mô tả hành động hoặc lý do');
            $table->string('ip_address', 45)->nullable()->comment('IP address');
            $table->text('user_agent')->nullable()->comment('User agent string');
            $table->string('url', 500)->nullable()->comment('URL được truy cập');
            $table->string('method', 10)->nullable()->comment('HTTP method');
            
            // Metadata
            $table->json('metadata')->nullable()->comment('Thông tin bổ sung (tags, categories, etc.)');
            $table->boolean('is_sensitive')->default(false)->comment('Có chứa dữ liệu nhạy cảm không');
            
            // Timestamps
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes for performance
            $table->index(['user_id', 'created_at'], 'idx_user_time');
            $table->index(['model', 'model_id'], 'idx_model_record');
            $table->index(['action', 'created_at'], 'idx_action_time');
            $table->index(['created_at'], 'idx_created_at');
            $table->index(['session_id'], 'idx_session');
            $table->index(['ip_address'], 'idx_ip');
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
        
        // Add comment to table
        DB::statement("ALTER TABLE `audit_logs` COMMENT = 'Bảng lưu trữ lịch sử thay đổi dữ liệu hệ thống'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};