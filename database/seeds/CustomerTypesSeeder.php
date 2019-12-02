<?php

use Illuminate\Database\Seeder;

class CustomerTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\CustomerType::create(['name' => 'Trade']);
        \App\CustomerType::create(['name' => 'Internal']);
        \App\CustomerType::create(['name' => 'External']);
        \App\CustomerType::create(['name' => 'Others']);
    }
}
