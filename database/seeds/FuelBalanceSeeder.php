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
           'name' => 'Full'
        ]);
        \App\FuelBalance::create([
            'name' => '1/2'
        ]);
        \App\FuelBalance::create([
            'name' => '1/4'
        ]);
    }
}
