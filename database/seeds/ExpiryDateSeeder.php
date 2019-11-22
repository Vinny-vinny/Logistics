<?php

use Illuminate\Database\Seeder;

class ExpiryDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ExpiryDate::create([
           'name' => 'Days'
        ]);
        \App\ExpiryDate::create([
            'name' => 'Weeks'
        ]);
        \App\ExpiryDate::create([
            'name' => 'Months'
        ]);
        \App\ExpiryDate::create([
            'name' => 'Years'
        ]);
    }
}
