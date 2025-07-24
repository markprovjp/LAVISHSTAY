<?php

// database/migrations/xxxx_xx_xx_add_client_token_to_conversations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('conversations', function (Blueprint $table) {
            $table->string('client_token')->nullable()->unique()->after('user_id')->comment('Mã định danh của người không đăng nhập');
        });
    }

    public function down(): void {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('client_token');
        });
    }
};
