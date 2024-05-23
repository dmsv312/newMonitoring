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
        DB::table('rates')->insert([
            'name' => 'B+',
            'speed' => '10',
            'channels' => '140',
            'hd_channels' => '40',
            'price' => 390,
        ]);
        DB::table('rates')->insert([
            'name' => 'C+',
            'speed' => '30',
            'channels' => '150',
            'hd_channels' => '40',
            'price' => 490,
        ]);
        DB::table('rates')->insert([
            'name' => 'D+',
            'speed' => '50',
            'channels' => '150',
            'hd_channels' => '40',
            'price' => 590,
        ]);
        DB::table('rates')->insert([
            'name' => 'Z+',
            'speed' => '70',
            'channels' => '150',
            'hd_channels' => '40',
            'price' => 690,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('rates')->delete();
    }
};
