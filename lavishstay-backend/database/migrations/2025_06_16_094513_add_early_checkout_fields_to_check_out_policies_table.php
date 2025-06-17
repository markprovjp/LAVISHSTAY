<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEarlyCheckoutFieldsToCheckOutPoliciesTable extends Migration
{
    public function up()
    {
        Schema::table('check_out_policies', function (Blueprint $table) {
            $table->integer('early_check_out_max_hours')->nullable()->after('late_check_out_max_hours');
        });
    }

    public function down()
    {
        Schema::table('check_out_policies', function (Blueprint $table) {
            $table->dropColumn(['early_check_out_max_hours', 'early_check_out_fee_vnd']);
        });
    }
}