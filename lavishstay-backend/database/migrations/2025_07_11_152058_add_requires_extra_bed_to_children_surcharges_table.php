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
        Schema::table('children_surcharges', function (Blueprint $table) {
            $table->boolean('requires_extra_bed')->default(false)->after('count_as_adult');
        });
    }

    public function down(): void
    {
        Schema::table('children_surcharges', function (Blueprint $table) {
            $table->dropColumn('requires_extra_bed');
        });
    }
};
