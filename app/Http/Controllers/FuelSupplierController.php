<?php

namespace App\Http\Controllers;

use App\FuelSupplier;
use App\Http\Resources\FuelSupplierResource;
use Illuminate\Http\Request;

class FuelSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(FuelSupplierResource::collection(FuelSupplier::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supplier = FuelSupplier::create($request->except('fuel_type'));
        return response()->json(new FuelSupplierResource($supplier));
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
     FuelSupplier::find($id)->update($request->except('fuel_type'));
     return response()->json(new FuelSupplierResource(FuelSupplier::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FuelSupplier::find($id);
        return response()->json($id);
    }
}
