<?php

use Illuminate\Database\Seeder;

class TrackBySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\TrackBy::create([
           'name' => 'KM'
        ]);
        \App\TrackBy::create([
           'name' => 'Miles'
        ]);
        \App\TrackBy::create([
           'name' => 'Hours'
        ]);
       }
}
