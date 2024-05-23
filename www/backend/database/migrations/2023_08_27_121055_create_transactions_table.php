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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('token_id_from')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('token_id_to')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('amount', 8, 5)->nullable();
            $table->float('fee', 8, 5)->nullable();
            $table->string('hash')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
