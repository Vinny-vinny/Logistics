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
        \App\JobcardCategory::create(['name' => 'Building Repairs']);
        \App\JobcardCategory::create(['name' => 'Project']);
        \App\JobcardCategory::create(['name' => 'Workshop']);
        \App\JobcardCategory::create(['name' => 'General']);
    }
}
