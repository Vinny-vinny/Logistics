<?php

use Illuminate\Database\Seeder;

class FuelSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\FuelSupplier::create([
            'name' => 'Inc Oil LTD',
            'code' => '0001',
            'phone_no' => '0705678999',
            'price' => 500,
            'fuel_type_id' => \App\FuelType::all()->random()->id,
            'currency' => 'KSH'
        ]);
        \App\FuelSupplier::create([
            'name' => 'Kebab LTD',
            'code' => '0599',
            'phone_no' => '0783838300',
            'price' => 250,
            'fuel_type_id' => \App\FuelType::all()->random()->id,
            'currency' => 'USD'
        ]);
        \App\FuelSupplier::create([
            'name' => 'Vinnyvinny Associates LTD',
            'code' => '1455',
            'phone_no' => '0704522671',
            'price' => 650,
            'fuel_type_id' => \App\FuelType::all()->random()->id,
            'currency' => 'KSH'
        ]);
    }
}
