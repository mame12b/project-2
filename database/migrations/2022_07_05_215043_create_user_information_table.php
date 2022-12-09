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
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('student_id')->nullable();
            $table->decimal('cgpa', 3, 2)->nullable();
            $table->integer('year_of_study')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('city')->nullable();
            $table->string('university')->nullable();
            $table->string('department')->nullable();
            $table->string('degree')->nullable();
            $table->text('about_me')->nullable();
            $table->string('application_letter_file_path')->nullable();
            $table->string('application_acceptance_file_path')->nullable();
            $table->string('student_id_file_path')->nullable();
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
        Schema::dropIfExists('user_information');
    }
};
