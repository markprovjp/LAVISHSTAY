<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Enhance cancellation_policies
        Schema::table('cancellation_policies', function (Blueprint $table) {
            $table->integer('priority')->default(0)->after('description');
            $table->json('conditions')->nullable()->after('priority'); // Điều kiện phức tạp
            $table->boolean('applies_to_weekend')->default(false)->after('conditions');
            $table->boolean('applies_to_holiday')->default(false)->after('applies_to_weekend');
            $table->decimal('min_booking_amount', 15, 2)->nullable()->after('applies_to_holiday');
            $table->decimal('max_booking_amount', 15, 2)->nullable()->after('min_booking_amount');
        });

        // Enhance deposit_policies
        Schema::table('deposit_policies', function (Blueprint $table) {
            $table->integer('priority')->default(0)->after('description');
            $table->json('conditions')->nullable()->after('priority');
            $table->boolean('applies_to_weekend')->default(false)->after('conditions');
            $table->boolean('applies_to_holiday')->default(false)->after('applies_to_weekend');
            $table->integer('min_days_before_checkin')->nullable()->after('applies_to_holiday');
            $table->decimal('min_booking_amount', 15, 2)->nullable()->after('min_days_before_checkin');
        });

        // Enhance check_out_policies
        Schema::table('check_out_policies', function (Blueprint $table) {
            $table->integer('priority')->default(0)->after('description');
            $table->json('conditions')->nullable()->after('priority');
            $table->boolean('applies_to_weekend')->default(false)->after('conditions');
            $table->boolean('applies_to_holiday')->default(false)->after('applies_to_weekend');
            $table->time('standard_check_out_time')->default('12:00:00')->after('applies_to_holiday');
        });
    }

    public function down()
    {
        Schema::table('cancellation_policies', function (Blueprint $table) {
            $table->dropColumn(['priority', 'conditions', 'applies_to_weekend', 'applies_to_holiday', 'min_booking_amount', 'max_booking_amount']);
        });

        Schema::table('deposit_policies', function (Blueprint $table) {
            $table->dropColumn(['priority', 'conditions', 'applies_to_weekend', 'applies_to_holiday', 'min_days_before_checkin', 'min_booking_amount']);
        });

        Schema::table('check_out_policies', function (Blueprint $table) {
            $table->dropColumn(['priority', 'conditions', 'applies_to_weekend', 'applies_to_holiday', 'standard_check_out_time']);
        });
    }
};
