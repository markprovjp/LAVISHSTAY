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
        Schema::create('children_surcharges', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('min_age'); // Tuổi tối thiểu (VD: 0)
            $table->unsignedTinyInteger('max_age'); // Tuổi tối đa (VD: 5)
            $table->boolean('is_free')->default(false); // Miễn phí hay không
            $table->boolean('count_as_adult')->default(false); // Tính như người lớn
            $table->unsignedInteger('surcharge_amount_vnd')->nullable(); // Mức phụ thu cố định (nếu có)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children_surcharges');
    }
};
