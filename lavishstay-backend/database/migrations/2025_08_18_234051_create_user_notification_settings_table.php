<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('notification_type_id')->constrained('notification_types')->onDelete('cascade');
            $table->boolean('is_enabled')->default(true); // Bật/tắt loại thông báo này
            $table->boolean('email_enabled')->default(false); // Gửi qua email
            $table->boolean('push_enabled')->default(true); // Gửi qua push notification
            $table->timestamps();
            
            $table->unique(['user_id', 'notification_type_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_notification_settings');
    }
};