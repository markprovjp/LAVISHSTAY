<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeColumnInCheckoutRequests extends Migration
{
    public function up()
    {
        Schema::table('check_out_requests', function (Blueprint $table) {
            $table->string('type')->nullable()->change(); // Thay enum thành varchar, cho phép NULL
        });
    }

    public function down()
    {
        Schema::table('check_out_requests', function (Blueprint $table) {
            $table->enum('type', ['early', 'late'])->default('early')->change();
        });
    }
}
