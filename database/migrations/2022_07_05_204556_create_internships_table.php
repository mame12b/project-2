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
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained(table:'departments')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('minimum_cgpa', 3, 2)->nullable();
            $table->integer('quota')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('status')->comment('0: internship closed, 1: accepting applicants, 2: internship started, 3: internship is waiting to start, 4: internship aborted')->default(1);
            $table->text('avatar')->nullable();
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
        Schema::dropIfExists('internships');
    }
};
