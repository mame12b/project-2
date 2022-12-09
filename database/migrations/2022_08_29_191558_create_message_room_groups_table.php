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
        Schema::create('message_room_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_room_id')->constrained(table: 'message_rooms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained(table:'users')->onDelete('cascade');
            $table->enum('permissions', ['all', 'send_only', 'read_only'])->default('all');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_room_groups');
    }
};
