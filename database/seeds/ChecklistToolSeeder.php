<?php

use Illuminate\Database\Seeder;

class ChecklistToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ChecklistTool::create(['name' =>'JACK']);
        \App\ChecklistTool::create(['name' =>'TOOLS']);
        \App\ChecklistTool::create(['name' =>'SPARE WHEEL']);
        \App\ChecklistTool::create(['name' =>'HUB CUPS']);
        \App\ChecklistTool::create(['name' =>'RADIO/TAPE']);
    }
}
