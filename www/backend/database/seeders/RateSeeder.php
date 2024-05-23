<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rates')->insert([
            'carbon_rate_id' => 1139,
            'name' => 'B',
            'speed' => '10',
            'channels' => '150',
            'hd_channels' => '40',
            'price' => 390.00,
        ]);
        DB::table('rates')->insert([
            'carbon_rate_id' => 1140,
            'name' => 'C',
            'speed' => '30',
            'channels' => '150',
            'hd_channels' => '40',
            'price' => 490.00,
        ]);
        DB::table('rates')->insert([
            'carbon_rate_id' => 1140,
            'name' => 'D',
            'speed' => '50',
            'channels' => '150',
            'hd_channels' => '40',
            'price' => 590.00,
        ]);
        DB::table('rates')->insert([
            'carbon_rate_id' => 1141,
            'name' => 'Z',
            'speed' => '100',
            'channels' => '200',
            'hd_channels' => '100',
            'price' => 690.00,
        ]);
    }
}
