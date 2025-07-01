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
        Schema::table('booking_rooms', function (Blueprint $table) {
            $table->string('booking_code', 55)->nullable()->after('booking_id');
        });
        
        Schema::table('representatives', function (Blueprint $table) {
            $table->string('booking_code', 55)->nullable()->after('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_rooms', function (Blueprint $table) {
            $table->dropColumn('booking_code');
        });
        
        Schema::table('representatives', function (Blueprint $table) {
            $table->dropColumn('booking_code');
        });
    }
};
