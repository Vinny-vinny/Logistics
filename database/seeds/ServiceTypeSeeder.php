<?php

use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ServiceType::create([
           'name' => 'A',
           'description' => 'Description for service A',
           'track_by_id' => \App\TrackBy::all()->random()->id,
           'service_after' => 1000,
        ]);
        \App\ServiceType::create([
            'name' => 'B',
            'description' => 'Description for service B',
            'track_by_id' => \App\TrackBy::all()->random()->id,
            'service_after' => 2000,
        ]);
        \App\ServiceType::create([
            'name' => 'C',
            'description' => 'Description for service C',
            'track_by_id' => \App\TrackBy::all()->random()->id,
            'service_after' => 3000,
        ]);
    }
}
