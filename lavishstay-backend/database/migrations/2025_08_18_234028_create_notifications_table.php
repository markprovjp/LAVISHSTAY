<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop existing table if exists
        Schema::dropIfExists('notifications');
        
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('notification_type_id')->constrained('notification_types')->onDelete('cascade');
            $table->morphs('notifiable'); // user_id và user_type (App\Models\User)
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Dữ liệu bổ sung (booking_id, amount, etc.)
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->string('icon')->default('📣');
            $table->string('color')->default('#3B82F6');
            $table->string('url')->default('#'); // Link khi click vào thông báo
            $table->timestamp('read_at')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamps();
            

            $table->index('read_at');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};