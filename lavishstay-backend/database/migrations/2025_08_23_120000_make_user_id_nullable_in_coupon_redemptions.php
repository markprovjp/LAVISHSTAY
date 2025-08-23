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
        Schema::table('coupon_redemptions', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_redemptions', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_redemptions', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_redemptions', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            }
        });
    }
};
