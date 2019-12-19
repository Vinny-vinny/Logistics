<?php

use Illuminate\Database\Seeder;
use App\AssetCategory;

class AssetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssetCategory::create(['name' => 'Security']);
        AssetCategory::create(['name' => 'Logistic']);
        AssetCategory::create(['name' => 'Community']);
        AssetCategory::create(['name' => 'Wildlife']);
        AssetCategory::create(['name' => 'Education']);
        AssetCategory::create(['name' => 'Conservation Overhead']);
    }
}
