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
        Schema::create('exordes', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('location');
            $table->string('address');
            $table->integer('previous_reputation')->nullable();
            $table->integer('current_reputation')->nullable();
            $table->integer('rank')->nullable();
            $table->boolean('is_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exordes');
    }
};
