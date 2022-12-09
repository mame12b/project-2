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
        Schema::create('user_prerequisite_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_application_id')->constrained(table:'user_applications')->onDelete('cascade');
            $table->foreignId('prerequisite_id')->constrained(table:'internship_prerequisites')->onDelete('cascade');
            $table->text('response')->nullable();
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
        Schema::dropIfExists('user_prerequisite_resposes');
    }
};
