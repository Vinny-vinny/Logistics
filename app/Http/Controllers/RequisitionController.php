<?php

namespace App\Http\Controllers;

use App\Http\Resources\RequisitionResource;
use Carbon\Carbon;
use App\Requisition;
use App\WhseStk;
use App\Part;
use Illuminate\Http\Request;
use DB;
use App\WizPostTx;

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

   //Issue Requisition Items
    public function IssueRequisition($id){
     $req = Requisition::find($id);
     $date = Carbon::now()->format('Y-m-d');
     $req->update(['used'=>1]);
     $journal_details = "<root>";
     foreach (json_decode($req->inventory_items_internal) as $key => $value) {
          $stk_id = Part::find($value->part)->stock_link;

          $journal_details.="<row>
                          <TX_DATE>$date</TX_DATE>
                          <TR_CODE>0</TR_CODE>
                          <DR_CODE>$req->where_to_charge</DR_CODE>
                          <CR_CODE>$req->credit_account_id</CR_CODE>
                          <STK_ID>$stk_id</STK_ID>
                          <COST>$value->unit_cost</COST>
                          <QTY>$value->quantity</QTY>
                          <WHSE_ID>1</WHSE_ID>
                          <PROJECT>$req->subproject_id</PROJECT>
                          <DESCRIPTION>$req->description</DESCRIPTION>
                          <REFERENCE>$req->req_no</REFERENCE>
                         </row>";

        }
         $journal_details.= "</root>" ;
          $details = WizPostTx::CREATE(['XMLText' => $journal_details]);
         DB::connection('sqlsrv2')->statement('exec WIZ_STOCK_JOURNAL @STK_XML_SNO= "'.$details->SNo.'"');
    return response(new RequisitionResource($req));
    }
//Reverse Requisition Items
    public function reverseReq(Request $request){
       $request['inventory_items_reversal'] = json_encode($request->get('inventory_items_reversal'));
        $request['reversal_ref'] = 'REV00'.$request->get('id');
        $ref = $request['reversal_ref'];
        $date = Carbon::now()->format('Y-m-d');
        $req_details =Requisition::find($request->get('id'));
         $req_details->update($request->all());
         $reverse_items_details="<root>";
        foreach ((array)json_decode($request->get('inventory_items_reversal')) as $key => $invoice) {
        $stk_id = Part::find($invoice->part)->stock_link;
         $reverse_items_details.="<row>
                          <INV_TYPE>C</INV_TYPE>
                          <INV_TRCODE>0</INV_TRCODE>
                          <DR_CODE>$req_details->credit_account_id</DR_CODE>
                          <CR_CODE>$request->where_to_charge</CR_CODE>
                          <CUST_ID>0</CUST_ID>
                          <INV_DATE>$date</INV_DATE>
                          <ORDER_NO>$ref</ORDER_NO>
                          <PROJ_ID>$req_details->subproject_id</PROJ_ID>
                          </row>";
         }
         $reverse_items_details.="</root>";
         $details = WizPostTx::CREATE(['XMLText' => $reverse_items_details]);
         DB::connection('sqlsrv2')->statement('exec WIZ_STOCK_JOURNAL @STK_XML_SNO= "'.$details->SNo.'"');
       return response()->json(new RequisitionResource($req_details));
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
        Requisition::find($id)->update($request->except(['date_requested','project','person_requested','user','vehicle']));
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
        return response()->json(new RequisitionResource($rq));
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
