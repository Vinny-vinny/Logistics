<?php

use Illuminate\Database\Seeder;

class StkCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\StkCategory::truncate();
        $categories = \App\GroupTbl::all();

        foreach ($categories as $category){
            \App\StkCategory::create([
               'name' => $category->StGroup,
               'description' => $category->Description,
               'account_link' => $category->StockAccLink
            ]);
        }
    }
}
