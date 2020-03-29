<?php

namespace App\Http\Controllers;
use App\Wheretocharge;
use App\Http\Resources\WhereTochargeResource;
use Illuminate\Http\Request;

class WheretochargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(WhereTochargeResource::collection(Wheretocharge::get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $charge = Wheretocharge::create($request->all());
        return response()->json(new WhereTochargeResource($charge));
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
        Wheretocharge::find($id)->update($request->except('account'));
        return response()->json(new WhereTochargeResource(Wheretocharge::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        WhereTocharge::destroy($id);
        return response()->json($id);
    }
}
