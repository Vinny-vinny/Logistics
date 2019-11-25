<?php

use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\JobType::create([
            'name' => 'Job Test 1',
            'description' => 'Simply Job Test 1',
            'hourly_rate' => '2000',
            'currency' => 'USD',
        ]);
        \App\JobType::create([
            'name' => 'Job Test 2',
            'description' => 'Simply Job Test 2',
            'hourly_rate' => '200',
            'currency' => 'KSH',
        ]);
        \App\JobType::create([
            'name' => 'Job Test 3',
            'description' => 'Simply Job Test 3',
            'hourly_rate' => '3500',
            'currency' => 'KSH',
        ]);
    }
}
