<?php

namespace App\Http\Controllers;
use App\Uom;
use App\SageUom;
use Illuminate\Http\Request;

class UomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Uom::all());
    }
      public function importUom()
    {
       $uoms = SageUom::select("idUnits","cUnitCode","cUnitDescription")->get();
        $existing = Uom::get();
        $found_uoms = [];
        if ($existing->count() < 1){
            return $this->storeUom($uoms);
        }
        $ids = $existing->map(function($uom){
            return  $uom->id_units;
        });

        foreach ($uoms as $uom){
            if (!in_array($uom->idUnits,$ids->all())){
                $found_uoms[] = $uom;
            }
        }
        if(!$found_uoms){
            return response()->json([]);
        }
        return $this->storeUom($found_uoms);
    }
    function storeUom($uoms){

        foreach ($uoms as $uom){
            $inserted[] = Uom::create([
                'id_units' => $uom->idUnits,
                'code' => $uom->cUnitCode,              
                'description' => $uom->cUnitDescription
            ]);

        }
        return response()->json($inserted);
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
