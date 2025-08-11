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
        Schema::create('news_user_actions', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->unsignedBigInteger('news_id')->comment('ID bài viết (liên kết news)');
            $table->unsignedBigInteger('user_id')->comment('ID người dùng (liên kết users)');
            $table->boolean('is_liked')->default(false)->comment('Có thích hay không');
            $table->boolean('is_bookmarked')->default(false)->comment('Có bookmark hay không');
            $table->float('rating')->nullable()->comment('Đánh giá 1-5 sao');
            $table->timestamp('created_at')->useCurrent()->comment('Thời điểm tạo');
            
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unique(['news_id', 'user_id'], 'unique_user_news_action');
            $table->index('is_liked');
            $table->index('is_bookmarked');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_user_actions');
    }
};
