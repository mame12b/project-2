<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value')->nullable();
            $table->timestamps();
        });

        DB::table('configs')->insert([
            [
                'name' => 'is_node_on',
                'value' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'name',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'mode',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'status',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
};
