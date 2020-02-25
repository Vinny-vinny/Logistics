<?php

use Illuminate\Database\Seeder;
use App\FuelCategory;
class FuelCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FuelCategory::create(['name' => 'Invoice to Customer']);
        FuelCategory::create(['name' => 'Stock Issue']);
    }
}
