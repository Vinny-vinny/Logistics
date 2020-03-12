<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobcardResource;
use App\Http\Resources\MaintenanceResource;
use App\Jobcard;
use App\JobcardFile;
use App\Machine;
use App\Requisition;
use App\Part;
use App\Customer;
use App\JobcardCategory;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Repo\InvoiceRepo;
use App\WizPostTx;
use DB;


class JobcardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return response()->json(JobcardResource::collection(Jobcard::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $request['maintenance'] = json_encode($request->get('maintenance'));
        $request->time_in !== '' && $request->time_in !== null ? $request['time_in'] = Carbon::parse($request->time_in)->format('H:i') : '';
          $request->time_out !== '' && $request->time_out !== null ? $request['time_out'] = Carbon::parse($request->time_out)->format('H:i') : '';
        $card_no = Jobcard::count()+1;
        $request['card_no'] = 'Job00'.$card_no;
        $jobcard = Jobcard::create($request->except(['files']));
        if ($jobcard->machine_id) {
            Machine::where('project_link',$jobcard->machine_id)->update([
            'current_readings' => $request->get('current_readings'),
            'next_readings' => $request->get('next_readings'),
            'next_service_date' => $request->get('next_service_date'),
            'fuel_balance_id' => $request->get('fuel_balance_id'),
        ]);
        }

        $request->requisition_id ? Requisition::find($request->requisition_id)->update(['used' => 1]) : '';
        return response()->json(new JobcardResource($jobcard));
    }
    //Auto generate Jobcard
    public function generateJobcard(Request $request)
    {
        $request['track_by_id'] = Machine::find($request->machine_id)->track_by_id;
        $jobcard_no = Jobcard::count()+1;
        $request['card_no'] = substr('LEWA-' . $jobcard_no . '-' . Machine::find($request->machine_id)->code, 0, 20);
        $card = Jobcard::create($request->all());
        return response()->json($card);
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(new MaintenanceResource(Jobcard::find($id)));
    }

    public function addInvoice($id)
    {
    InvoiceRepo::init()->generateInvoice($id);
    }
   //Invoice Jobcard Items
    public function invoiceJob(Request $request){
        $date = Carbon::now()->format('Y-d-m');
        $job_details =Jobcard::find($request->get('id'));
        $job_details->update(['invoiced' =>1]);
        $req_details =Requisition::find($job_details->requisition_id);
        $inv_code = $job_details->category->inv_item->transaction_id;
        $stk_code = $job_details->category->stk_item->transaction_id;
           $invoice_xml = "<root>
                         <row>
                          <INV_TYPE>I</INV_TYPE>
                          <INV_TRCODE>$inv_code</INV_TRCODE>
                          <STK_TRCODE>$stk_code</STK_TRCODE>
                          <CUST_ID>$job_details->customer_id</CUST_ID>
                          <INV_DATE>$date</INV_DATE>
                          <ORDER_NO>$job_details->card_no</ORDER_NO>
                          <PROJ_ID>$job_details->machine_id</PROJ_ID>
                          </row>
                          </root>";
                          $invoice_items_details="<root>";
        foreach (json_decode($req_details->inventory_items_external) as $key => $invoice) {
        $stk_id = Part::find($invoice->part)->stock_link;
         $invoice_items_details.="<row>
                              <STK_ID>$stk_id</STK_ID>
                              <EXCL_PRICE>$invoice->total_price</EXCL_PRICE>
                              <INCL_PRICE>$invoice->total_price_inclusive</INCL_PRICE>
                              <QTY>$invoice->quantity</QTY>
                              <VAT_APPLICABLE>True</VAT_APPLICABLE>
                              <VAT_RATE>16</VAT_RATE>
                              <LINE_DISC>0</LINE_DISC>
                              </row>";

             }
          $invoice_items_details.="</root>";
         $invoice = WizPostTx::CREATE(['XMLText' => $invoice_xml]);
         $details = WizPostTx::CREATE(['XMLText' => $invoice_items_details]);
         DB::connection('sqlsrv2')->statement('exec WIZ_PostTx_With_XML @SNo_Hdr= "'.$invoice->SNo.'",@SNo_Det = "'.$details->SNo.'"');
      return response()->json(new JobcardResource($job_details ));
    }

   //reverse jobcard
    public function reverseJob(Request $request){
        $request['inventory_items_reversal'] = json_encode($request->get('inventory_items_reversal'));
         $request['reversal_ref'] = 'REV00'.$request->get('id');
         Jobcard::find($request->get('id'))->update($request->all());
        $ref=$request['reversal_ref'];
        $date = Carbon::now()->format('Y-m-d');
        $job_details = Jobcard::find($request->id);
        $inv_code = $job_details->category->inv_item->transaction_id;
        $stk_code = $job_details->category->stk_item->transaction_id;
         $reverse_xml = "<root>
                         <row>
                          <INV_TYPE>C</INV_TYPE>
                          <INV_TRCODE>$inv_code</INV_TRCODE>
                          <STK_TRCODE>$stk_code</STK_TRCODE>
                          <CUST_ID>$job_details->customer_id</CUST_ID>
                          <INV_DATE>$date</INV_DATE>
                          <ORDER_NO>$ref</ORDER_NO>
                          <PROJ_ID>$job_details->machine_id</PROJ_ID>
                          </row>
                          </root>";
         $reverse_items_details="<root>";
         foreach ((array)json_decode($request->get('inventory_items_reversal')) as $key => $invoice) {
        $stk_id = Part::find($invoice->part)->stock_link;

          $reverse_items_details.="<row>
                              <STK_ID>$stk_id</STK_ID>
                              <EXCL_PRICE>$invoice->total_price</EXCL_PRICE>
                              <INCL_PRICE>$invoice->total_price_inclusive</INCL_PRICE>
                              <QTY>$invoice->quantity</QTY>
                              <VAT_APPLICABLE>True</VAT_APPLICABLE>
                              <VAT_RATE>16</VAT_RATE>
                              <LINE_DISC>0</LINE_DISC>
                              </row>";
         return response()->json($reverse_items_details);
         }
         $reverse_items_details.="</root>";
        $invoice_reversal = WizPostTx::CREATE(['XMLText' => $reverse_xml]);
         $details = WizPostTx::CREATE(['XMLText' => $reverse_items_details]);
         DB::connection('sqlsrv2')->statement('exec WIZ_PostTx_With_XML @SNo_Hdr= "'.$invoice_reversal->SNo.'",@SNo_Det = "'.$details->SNo.'"');
        return response()->json(['rverseral he== '=>$reverse_xml,'details == '=>$reverse_items_details]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->time_in !== '' && $request->time_in !== null ? $request['time_in'] = Carbon::parse($request->time_in)->format('H:i') : '';
          $request->time_out !== '' && $request->time_out !== null ? $request['time_out'] = Carbon::parse($request->time_out)->format('H:i') : '';

        $request['maintenance'] = json_encode($request->get('maintenance'));
        $jobcard = Jobcard::find($id);
        $request['card_no'] = 'Job00'.$id;
        $jobcard->update($request->except(['service_types','files','driver','machine','project','make','customer_type','track_name','category','previous_readings','start_date','complete_date','inventory_items','fuel','plate_no','mechanic','checklist','cost_center','requisitions','costcode']));
        if ($request->machine_id) {
             Machine::find($request->machine_id)->update([
            'current_readings' => $request->get('current_readings'),
            'next_readings' => $request->get('next_readings'),
            'next_service_date' => $request->get('next_service_date'),
            'fuel_balance_id' => $request->get('fuel_balance_id'),
        ]);
        }

      $request->requisition_id ? Requisition::find($request->requisition_id)->update(['used' => 1]) : '';
        return response()->json(new JobcardResource(Jobcard::find($id)));
    }

    public function closeJobcard($id)
    {
        $job = Jobcard::find($id);
        $job->update(['closed_at' => Carbon::now(),'status' =>0]);
        if ($job->requisition_id) {
        $job_cat = JobcardCategory::find($job->jobcard_category_id);
        $inv_date = date('Y-m-d');
        $inv_id =  $job_cat->inv_item ? $job_cat->inv_item->transaction_id : 0;
        $stk_id =  $job_cat->stk_item ? $job_cat->stk_item->transaction_id : 0;
        $line_desc =$job->requisition->description;
        $customer = Customer::find($job->customer_id);


        if ($job->requisition->type =='Internal') {
            foreach (json_decode($job->requisition->inventory_items_internal) as $value) {
             $stk = Part::find($value->part)->stock_link;
             $incl_price = $value->unit_cost/16*100;

             $xml_data_in ="<root><row><INV_TRCODE>$inv_id</INV_TRCODE><STK_TRCODE>$stk_id</STK_TRCODE><CUST_ID>$customer->dc_link</CUST_ID><STK_ID>$stk</STK_ID><EXCL_PRICE>$value->unit_cost</EXCL_PRICE><INCL_PRICE>$incl_price</INCL_PRICE><QTY>$value->quantity</QTY><VAT_APPLICABLE>True</VAT_APPLICABLE><VAT_RATE>16</VAT_RATE><LINE_DISC>0</LINE_DISC><INV_DATE> $inv_date</INV_DATE><ORDER_NO>$job->card_no</ORDER_NO><PROJ_ID>0</PROJ_ID></row></root>";
         $wiz_in = WizPostTx::CREATE(['XMLText' => $xml_data_in]);
         DB::connection('sqlsrv2')->statement('exec WIZ_PostTx_With_XML @SNo='."'".$wiz_in->SNo."'");
          //DB::connection('sqlsrv2')->statement('exec WIZ_PostTx_With_XML @XML='."'". $xml_data_in."'");
         }
        }
       if ($job->requisition->type=='External') {
             foreach (json_decode($job->requisition->inventory_items_external) as $value) {
             $stk = Part::find($value->part)->stock_link;
             $incl_price = $value->unit_price/16*100;
             $xml_data_ex ="<root><row><INV_TRCODE>$inv_id</INV_TRCODE><STK_TRCODE>$stk_id</STK_TRCODE><CUST_ID>$customer->dc_link</CUST_ID><STK_ID>$stk</STK_ID><EXCL_PRICE>$value->unit_price</EXCL_PRICE><INCL_PRICE>$incl_price</INCL_PRICE><QTY>$value->quantity</QTY><VAT_APPLICABLE>True</VAT_APPLICABLE><VAT_RATE>16</VAT_RATE><LINE_DISC>0</LINE_DISC><INV_DATE>$inv_date</INV_DATE><ORDER_NO>$job->card_no</ORDER_NO><PROJ_ID>0</PROJ_ID></row></root>";

        //DB::connection('sqlsrv2')->statement('exec WIZ_PostTx_With_XML @XML='."'". $xml_data_ex."'");
         $wiz_ex = WizPostTx::CREATE(['XMLText' => $xml_data_ex]);
         DB::connection('sqlsrv2')->statement('exec WIZ_PostTx_With_XML @SNo='."'".$wiz_ex->SNo."'");
        }
        }

        return response('success');
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Jobcard::destroy($id);
        return response()->json($id);
    }
}
