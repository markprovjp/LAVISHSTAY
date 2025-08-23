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
            if (!Schema::hasColumn('coupon_redemptions', 'applied_amount_vnd')) {
                $table->decimal('applied_amount_vnd', 15, 2)->nullable()->after('amount_saved_vnd');
            }

            if (!Schema::hasColumn('coupon_redemptions', 'meta')) {
                $table->json('meta')->nullable()->after('applied_amount_vnd');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_redemptions', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_redemptions', 'meta')) {
                $table->dropColumn('meta');
            }

            if (Schema::hasColumn('coupon_redemptions', 'applied_amount_vnd')) {
                $table->dropColumn('applied_amount_vnd');
            }
        });
    }
};
