<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobcardResource;
use App\Http\Resources\MaintenanceResource;
use App\Jobcard;
use App\JobcardFile;
use App\Machine;
use App\Requisition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Repo\InvoiceRepo;


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
        $request['time_in'] = Carbon::parse($request->time_in)->format('H:i');
        $request->time_out !== '' && $request->time_out !== null ? $request['time_out'] = Carbon::parse($request->time_out)->format('H:i') : '';
        $card_no = Jobcard::count()+1;
        $request['card_no'] =  substr('LEWA-' . $card_no . '-' . Machine::find($request->machine_id)->code, 0, 20);
        $jobcard = Jobcard::create($request->except(['files']));
        Machine::find($jobcard->machine_id)->update([
            'current_readings' => $request->get('current_readings'),
            'next_readings' => $request->get('next_readings'),
            'next_service_date' => $request->get('next_service_date'),
            'fuel_balance_id' => $request->get('fuel_balance_id'),
        ]);
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

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request['time_in'] = Carbon::parse($request->time_in)->format('H:i');
        $request['time_out'] = Carbon::parse($request->time_out)->format('H:i');
        $request['card_no'] = substr('LEWA-' . $id . '-' . Machine::find($request->machine_id)->code, 0, 20);

        $jobcard = Jobcard::find($id);
        $jobcard->update($request->except(['service_types','files','driver','machine','make','customer_type','track_name','category','previous_readings','start_date','complete_date','inventory_items','fuel']));
        Machine::find($request->machine_id)->update([
            'current_readings' => $request->get('current_readings'),
            'next_readings' => $request->get('next_readings'),
            'next_service_date' => $request->get('next_service_date'),
            'fuel_balance_id' => $request->get('fuel_balance_id'),
        ]);
      $request->requisition_id ? Requisition::find($request->requisition_id)->update(['used' => 1]) : '';
        return response()->json(new JobcardResource(Jobcard::find($id)));
    }

    public function closeJobcard($id)
    {
        Jobcard::find($id)->update(['closed_at' => date('Y-m-d H:i'), 'status' => 0]);
        return response('closed');
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
