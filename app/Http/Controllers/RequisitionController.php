<?php

namespace App\Http\Controllers;

use App\Http\Resources\RequisitionResource;
use App\Requisition;
use App\WhseStk;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(RequisitionResource::collection(Requisition::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $req = Requisition::count() +1;
        $request['req_no'] = 'REQ00'.$req;
        $request['inventory_items_internal'] = json_encode($request->inventory_items_internal);
        $request['inventory_items_external'] = json_encode($request->inventory_items_external);
        $requisition = Requisition::create($request->all());
        return response()->json(new RequisitionResource($requisition));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(new RequisitionResource(Requisition::find($id)));
    }

    public function reverseReq(Request $request){
        $request['inventory_items_reversal'] = json_encode($request->get('inventory_items_reversal'));
        $request['reversal_ref'] = 'REV00'.$request->get('id');
        Requisition::find($request->get('id'))->update($request->all());
        return response()->json(new RequisitionResource(Requisition::find($request->get('id'))));
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



        $request['inventory_items_internal'] = json_encode($request->inventory_items_internal);
        $request['inventory_items_external'] = json_encode($request->inventory_items_external);
        Requisition::find($id)->update($request->except(['date_requested','project','person_requested','user']));
        return response()->json(new RequisitionResource(Requisition::find($id)));
    }
    //auto generate requisition
    public function autoGenerate(Request $request)
    {

        $request['inventory_items_internal'] = json_encode($request->inventory_items_internal);
        $request['inventory_items_external'] = json_encode($request->inventory_items_external);
        $req = Requisition::count() +1;
        $request['req_no'] = 'REQ00'.$req;
        $rq = Requisition::create($request->all());
        return response()->json($rq);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Requisition::destroy($id);
    }
}
