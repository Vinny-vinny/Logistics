<?php

use Illuminate\Database\Seeder;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\FuelType::create([
            'name' => 'Petrol'
        ]);
        \App\FuelType::create([
            'name' => 'Diesel'
        ]);
    }
}
