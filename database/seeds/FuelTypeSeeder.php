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
            'name' => 'Petrol',
            'rate' => 450,
            'currency' => 'KSH'
        ]);
        \App\FuelType::create([
            'name' => 'Diesel',
            'rate' => 530,
            'currency' => 'USD'
        ]);

    }
}
