<?php

use Illuminate\Database\Seeder;

class FuelBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\FuelBalance::create([
           'litres' => 10.5
        ]);
        \App\FuelBalance::create([
            'litres' => 23.7
        ]);
        \App\FuelBalance::create([
            'litres' => 16
        ]);
    }
}
