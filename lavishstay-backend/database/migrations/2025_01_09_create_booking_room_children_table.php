<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_room_children', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('booking_room_id');
            $table->integer('age'); // Tuổi của trẻ em
            $table->integer('child_index')->default(0); // Vị trí trẻ em trong phòng (0, 1, 2, ...)
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('booking_room_id')->references('id')->on('booking_rooms')->onDelete('cascade');
            
            // Index
            $table->index(['booking_room_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_room_children');
    }
};
