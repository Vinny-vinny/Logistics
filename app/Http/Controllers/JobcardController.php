<?php

namespace App\Http\Controllers;

use App\AssetCategory;
use App\Category;
use App\Charge;
use App\CustomerType;
use App\Http\Resources\JobcardResource;
use App\Http\Resources\MaintenanceResource;
use App\Jobcard;
use App\JobcardFile;
use App\JobType;
use App\Machine;
use App\Mechanic;
use App\Requisition;
use App\Part;
use App\Customer;
use App\JobcardCategory;
use App\ServiceType;
use App\TrackBy;
use App\Transaction;
use App\User;
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
      return response()->json(JobcardResource::collection(Jobcard::orderBy('created_at','DESC')->get()));
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
        $request['track_by_id'] = Machine::where('project_link',$request->machine_id)->first()->track_by_id;
        $request['maintenance'] = json_encode($request->maintenance);
        $jobcard_no = Jobcard::count()+1;
        $request['card_no'] = substr('LEWA-' . $jobcard_no . '-' . Machine::where('project_link',$request->machine_id)->first()->code, 0, 20);
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
   //InvoiceRepo::init()->generateInvoice($id);
    }
   //Invoice Jobcard Items
    public function invoiceJob(Request $request){
        $date = Carbon::now()->format('Y-m-d');
        $job_details =Jobcard::find($request->get('id'));
       $job_details->update(['invoiced' =>1]);
        $req_details =Requisition::find($job_details->requisition_id);
        $inv_code = $job_details->category->inv_item->transaction_id;
        $stk_code = $job_details->category->stk_item->transaction_id;
        $std_d = Charge::where('name','Standing Charge')->first();
        $labour_id = Charge::where('name','Labour Charge')->first();
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
         $invoice_items_details.= "<row>
                                  <STD_ID>$std_d->stock_link</STD_ID>
                                  <STD_COST>$job_details->standing_charge</STD_COST>
                                  </row>";
        $invoice_items_details.= "<row>
                                  <LABOUR_ID>$labour_id->stock_link</LABOUR_ID>
                                  <LABOUR_COST>$job_details->labour_cost</LABOUR_COST>
                                  </row>";
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
         //return response()->json($reverse_items_details);
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
        $jobcard->update($request->except(['service_types','files','driver','machine','costcode','project','make','customer_type','track_name','category','previous_readings','start_date','complete_date','inventory_items','fuel','plate_no','mechanic','checklist','cost_center','requisitions','inventory_items_reversal']));
          if ($request->machine_id) {
             Machine::where('project_link',$request->machine_id)->first()->update([
            'current_readings' => $request->get('current_readings'),
            'next_readings' => $request->get('next_readings'),
            'next_service_date' => $request->get('next_service_date')
        ]);
        }

      $request->requisition_id ? Requisition::find($request->requisition_id)->update(['used' => 1]) : '';
        return response()->json(new JobcardResource(Jobcard::find($id)));
    }

    public function closeJobcard($id)
    {
        $job = Jobcard::find($id);
        $job->update(['closed_at' => Carbon::now(),'status' =>0]);
        return response()->json(new JobcardResource($job));
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
