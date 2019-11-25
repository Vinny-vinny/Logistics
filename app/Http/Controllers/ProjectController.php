<?php

namespace App\Http\Controllers;

use App\Project;
use App\SageProject;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Project::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function importProjects()
    {
        $assets = SageProject::select("ProjectLink","ProjectCode","ProjectName","ProjectDescription")->get();
        $existing = Project::get();
        $found_assets = [];
        if ($existing->count() < 1){
            return $this->storeProjects($assets);
        }
        $ids = $existing->map(function($asset){
            return  $asset->project_link;
        });

        foreach ($assets as $asset){
            if (!in_array($asset->ProjectLink,$ids->all())){
                $found_assets[] = $asset;
            }
        }
        if(!$found_assets){
            return response()->json([]);
        }
        return $this->storeProjects($found_assets);
    }
    function storeProjects($assets){

        foreach ($assets as $asset){
            $inserted[] = Project::create([
                'project_link' => $asset->ProjectLink,
                'code' => $asset->ProjectCode,
                'name' => $asset->ProjectName,
                'description' => $asset->ProjectDescription
            ]);

        }
        return response()->json($inserted);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
