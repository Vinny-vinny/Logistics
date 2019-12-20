<?php

namespace App\Http\Controllers;

use App\JobcardCategory;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;

class JobcardCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(TransactionResource::collection(JobcardCategory::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $category = JobcardCategory::create($request->all());     
        return response()->json(new TransactionResource($category));
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
        $category = JobcardCategory::find($id);
        $category->update($request->all());      
        return response()->json(new TransactionResource(JobcardCategory::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        JobcardCategory::destroy($id);
        return response()->json($id);
    }
}
