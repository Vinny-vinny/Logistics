<?php

use Illuminate\Database\Seeder;
use App\Machine;
use App\User;
use App\SageProject;
use App\AssetCategory;
class AssetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
 
    public function run()
    {
  //   Machine::truncate();
     $categories = AssetCategory::all();
     $projects = SageProject::all();
      $found_assets = [];        
        $ids = $categories->map(function($category){
            return  $category->project_link;
        });

        foreach ($projects as $asset){
            if (in_array($asset->SubProjectOfLink,$ids->all())){
                $found_assets[] = $asset;
            }
        }
       if(!$found_assets){
            return response()->json([]);
       }        
       return $this->storeProjects($found_assets);
    }
      public function storeProjects($assets){
         foreach ($assets as $asset){
          Machine::create([
          'code' => $asset->ProjectCode,
          'description' =>$asset->ProjectDescription,
          'make' => 'TOYOTA',
          'model' => 'LANDCRUI',
          'project_link' => $asset->ProjectLink,
          'asset_category_id' => $asset->SubProjectOfLink,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);

        }
     //return response()->json($inserted);
    }
}
