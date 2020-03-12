<?php

use Illuminate\Database\Seeder;
use App\AssetCategory;
use App\SageProject;

class AssetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssetCategory::truncate();
        $projects = SageProject::select('ProjectLink','ProjectName')->where('SubProjectOfLink',0)->where('ProjectLevel',1)->get();
        collect($projects)->map(function($project){
       AssetCategory::create([
            'name' => $project->ProjectName,
            'project_link' => $project->ProjectLink
         ]);
        });        
    }
}
