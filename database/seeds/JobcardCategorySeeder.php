<?php

use Illuminate\Database\Seeder;

class JobcardCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\JobcardCategory::create(['name' => 'Master']);
        \App\JobcardCategory::create(['name' => 'Workshop']);
    }
}
