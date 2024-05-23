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
        Schema::table('servers', function (Blueprint $table) {
            $table->bigInteger('space_free')->nullable()->change();
        });
        Schema::table('servers', function (Blueprint $table) {
            $table->bigInteger('space_total')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->integer('space_free')->nullable()->change();
        });
        Schema::table('servers', function (Blueprint $table) {
            $table->integer('space_total')->nullable()->change();
        });
    }
};
