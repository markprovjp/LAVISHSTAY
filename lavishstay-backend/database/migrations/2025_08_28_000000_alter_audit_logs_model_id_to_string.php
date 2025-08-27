<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * We modify audit_logs.model_id from bigint to varchar(36) so UUID primary keys
     * (used by some models like Notification) can be stored without errors.
     */
    public function up(): void
    {
        // Use raw statement to avoid requiring doctrine/dbal
        DB::statement('ALTER TABLE `audit_logs` MODIFY `model_id` VARCHAR(36) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `audit_logs` MODIFY `model_id` BIGINT UNSIGNED NOT NULL');
    }
};
