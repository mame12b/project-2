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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_room_id')->constrained(table: 'message_rooms')->onDelete('cascade');
            $table->enum('sender_type', ['User', 'Internship']);
            $table->bigInteger('sender_id');
            $table->text('text')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_extension')->nullable();
            $table->string('file_name')->nullable();
            $table->dateTime('seen')->nullable();
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
        Schema::dropIfExists('messages');
    }
};
