<?php

namespace App\Http\Controllers;

use App\Fuel;
use App\Http\Resources\FuelPrintResource;
use App\Http\Resources\FuelResource;
use App\Http\Resources\FuelSupplierResource;
use App\Machine;
use Illuminate\Http\Request;

class FuelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(FuelResource::collection(Fuel::all()));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['other_expenses'] =  json_encode($request->get('other_expenses'));
        $fuel = Fuel::create($request->all());
        $request->get('vehicle_id') ? Machine::find($request->get('vehicle_id'))->update(['odometer_readings' => $request->get('odometer_readings')]) : '';
        return response()->json(new FuelResource($fuel));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(new FuelPrintResource(Fuel::find($id)));
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
        $request->get('other_expenses') ? $request['other_expenses'] = json_encode($request->get('other_expenses')) : '';
        Fuel::find($id)->update($request->except(['vehicle','fuel_type','price','previous_odometer']));
        Machine::find($request->get('vehicle_id'))->update(['odometer_readings' => $request->get('odometer_readings')]);
        return response()->json(new FuelResource(Fuel::find($id)));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Fuel::destroy($id);
        return response()->json($id);
    }
}
