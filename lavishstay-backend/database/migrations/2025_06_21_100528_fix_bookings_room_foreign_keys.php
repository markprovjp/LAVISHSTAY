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
        Schema::table('bookings', function (Blueprint $table) {
            // Drop rooms_data JSON column if exists
            if (Schema::hasColumn('bookings', 'rooms_data')) {
                $table->dropColumn('rooms_data');
            }
            
            // Change room_id and room_type_id to match target tables without foreign keys for now
            if (Schema::hasColumn('bookings', 'room_id')) {
                $table->unsignedInteger('room_id')->nullable()->change();
            }
            if (Schema::hasColumn('bookings', 'room_type_id')) {
                $table->unsignedInteger('room_type_id')->nullable()->change();
            }
        });
        
        // Note: Foreign keys will be added later when data types are compatible
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Change back to bigint
            $table->unsignedBigInteger('room_id')->nullable()->change();
            $table->unsignedBigInteger('room_type_id')->nullable()->change();
            
            // Add back rooms_data JSON column
            $table->json('rooms_data')->nullable()->after('customer_phone');
        });
    }
};
