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
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['notification_type_id']);
            
            // Modify the column to be nullable
            $table->unsignedBigInteger('notification_type_id')->nullable()->change();
            
            // Re-add the foreign key constraint
            $table->foreign('notification_type_id')
                  ->references('id')
                  ->on('notification_types')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['notification_type_id']);
            
            // Make the column not nullable again
            $table->unsignedBigInteger('notification_type_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('notification_type_id')
                  ->references('id')
                  ->on('notification_types')
                  ->onDelete('cascade');
        });
    }
};