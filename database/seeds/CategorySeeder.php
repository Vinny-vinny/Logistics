<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Category::create(['name' => 'Category A']);
        \App\Category::create(['name' => 'Category B']);
        \App\Category::create(['name' => 'Category C']);

    }
}
