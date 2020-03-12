<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Part;
use App\Fuel;
use App\Http\Resources\FuelPrintResource;
use App\Http\Resources\FuelResource;
use App\Http\Resources\FuelSupplierResource;
use App\Machine;
use App\Jobcard;
use App\WizPostTx;
use DB;
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
        $fuel_no = Fuel::count()+1;
        $request['fuel_no'] = 'Fuel00'.$fuel_no;
        $fuel = Fuel::create($request->all());
        $request->get('vehicle_id') ? Machine::where('project_link',$request->get('vehicle_id'))->update(['odometer_readings' => $request->get('odometer_readings')]) : '';
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

    //Issue Fuel
    public function IssueFuel(Request $request){
     $fuel = Fuel::find($request->id);
     $fuel->update(['status'=>0]);
     $date = Carbon::now()->format('Y-d-m');
          $stk_id = Part::find($fuel->fuel_type_id)->stock_link; 
          $price_excl = $fuel->rate*$fuel->litres; 
          $price_incl = $fuel->rate*$fuel->litres*(116/100);    
          $journal_xml = "<root>
                         <row>
                          <TX_DATE>$date</TX_DATE>
                          <TR_CODE>0</TR_CODE>
                          <DR_CODE>$fuel->where_to_charge</DR_CODE>
                          <CR_CODE>$fuel->credit_account_id</CR_CODE>
                          <STK_ID>$stk_id</STK_ID>
                          <COST>$fuel->rate</COST>
                          <QTY>$fuel->litres</QTY>
                          <WHSE_ID>1</WHSE_ID>
                          <PROJECT>$fuel->vehicle_id</PROJECT>
                          <DESCRIPTION>$fuel->external_reference</DESCRIPTION>
                          <REFERENCE>$fuel->fuel_no</REFERENCE>
                         </row>
                         </root>";
         $details = WizPostTx::CREATE(['XMLText' => $journal_details]);
         DB::connection('sqlsrv2')->statement('exec WIZ_STOCK_JOURNAL @STK_XML_SNO= "'.$details->SNo.'"');                   
        return response()->json(new FuelResource($fuel));     
        
    return response()->json($request->all());
    }

    //invoice Fuel
    public function invoiceFuel(Request $request){
        $date = Carbon::now()->format('Y-m-d');
        $fuel = Fuel::find($request->id);
        $fuel->update(['invoiced'=>1]);
        $job_details = Jobcard::find($fuel->job_card_id);
        $inv_code = $job_details->category->inv_item->transaction_id;
        $stk_code = $job_details->category->stk_item->transaction_id;
        $price_excl = $fuel->rate*$fuel->litres; 
        $price_incl = $fuel->rate*$fuel->litres*(116/100);  
  
         $stk_id = Part::find($fuel->fuel_type_id)->stock_link;      
         $invoice_xml = "<root>
                         <row>
                          <INV_TYPE>I</INV_TYPE>
                          <INV_TRCODE>$inv_code</INV_TRCODE>
                          <STK_TRCODE>$stk_code</STK_TRCODE>
                          <CUST_ID>$fuel->customer_id</CUST_ID>
                          <INV_DATE>$date</INV_DATE>
                          <ORDER_NO>$fuel->external_reference</ORDER_NO>
                          <PROJ_ID>$fuel->vehicle_id</PROJ_ID>
                          </row>
                          </root>";
                                                    
         $invoice_items_details="<root>
                              <row>
                              <STK_ID>$stk_id</STK_ID>
                              <EXCL_PRICE>$price_excl</EXCL_PRICE>
                              <INCL_PRICE>$price_incl</INCL_PRICE>
                              <QTY>$fuel->litres</QTY>
                              <VAT_APPLICABLE>True</VAT_APPLICABLE>
                              <VAT_RATE>16</VAT_RATE>
                              <LINE_DISC>0</LINE_DISC>
                              </row>
                              </root>";
         $invoice = WizPostTx::CREATE(['XMLText' => $invoice_xml]);
         $details = WizPostTx::CREATE(['XMLText' => $invoice_items_details]);
         DB::connection('sqlsrv2')->statement('exec WIZ_PostTx_With_XML @SNo_Hdr= "'.$invoice->SNo.'",@SNo_Det = "'.$details->SNo.'"');
           return response()->json(new FuelResource($fuel));
         
    return response()->json($request->all());
    }

    //reverse fuel
    public function reverseFuel(Request $request){
         $request['reversal_ref'] = 'REV00'.$request->get('id');
        //Fuel::find($request->get('id'))->update($request->all());
        $ref = $request['reversal_ref']; 
        $date = Carbon::now()->format('Y-m-d');
        $fuel = Fuel::find($request->id);            
        $reverse_xml = "<root>
                         <row>
                          <INV_TYPE>C</INV_TYPE>
                          <INV_TRCODE>0</INV_TRCODE>
                          <DR_CODE>$request->credit_account_id</DR_CODE>
                          <CR_CODE>$request->where_to_charge</CR_CODE>
                          <CUST_ID>0</CUST_ID>
                          <INV_DATE>$date</INV_DATE>
                          <ORDER_NO>$ref</ORDER_NO>
                          <PROJ_ID>$fuel->vehicle_id</PROJ_ID>
                          </row>
                          </root>";      
         $details = WizPostTx::CREATE(['XMLText' => $reverse_xml]);
         DB::connection('sqlsrv2')->statement('exec WIZ_STOCK_JOURNAL @STK_XML_SNO= "'.$details->SNo.'"');
         return response()->json($reverse_xml);  
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

        Fuel::find($id)->update($request->except(['vehicle','fuel_type','previous_odometer','customer_type','asset_type','date_fueled','person_authorizing','track_by','current_readings']));
        Machine::where('project_link',$request->get('vehicle_id'))->update(['odometer_readings' => $request->get('odometer_readings')]);
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
