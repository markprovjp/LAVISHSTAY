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
        Schema::create('news_comments', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->unsignedBigInteger('news_id')->comment('ID bài viết (liên kết news)');
            $table->unsignedBigInteger('user_id')->comment('ID người dùng (liên kết users)');
            $table->text('content')->comment('Nội dung bình luận');
            $table->integer('likes')->default(0)->comment('Số lượt thích');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('ID bình luận cha (cho reply)');
            $table->timestamp('created_at')->useCurrent()->comment('Thời điểm tạo');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('Thời điểm cập nhật');
            
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('news_comments')->onDelete('cascade');
            
            $table->index(['news_id', 'created_at']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_comments');
    }
};
