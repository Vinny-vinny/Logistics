<?php

use Illuminate\Database\Seeder;

class ServiceProviderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ServiceProviderType::create([
           'name' => 'Internal'
        ]);
        \App\ServiceProviderType::create([
            'name' => 'External'
        ]);
    }
}
