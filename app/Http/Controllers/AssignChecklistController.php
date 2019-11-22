<?php

namespace App\Http\Controllers;

use App\AssignChecklist;
use App\Http\Resources\AssignChecklistResource;
use Illuminate\Http\Request;

class AssignChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     return response()->json(AssignChecklistResource::collection(AssignChecklist::all()));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checklist = AssignChecklist::create($request->all());
        return response()->json(new AssignChecklistResource($checklist));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        AssignChecklist::find($id)->update($request->except(['checklist','vehicle','date_type','counter']));
        return response()->json(new AssignChecklistResource(AssignChecklist::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AssignChecklist::destroy($id);
        return response()->json($id);
    }
}
