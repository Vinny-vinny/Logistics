<?php

namespace App\Http\Controllers;

use App\ExternalService;
use App\Http\Resources\ExternalServiceResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExternalServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(ExternalServiceResource::collection(ExternalService::all()));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['labours'] = json_encode($request->get('labours'));
        $request['parts'] =  json_encode($request->get('parts'));
        $service = ExternalService::create($request->all());
        return response()->json(new ExternalServiceResource($service));
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
        $request['labours'] = json_encode($request->get('labours'));
        $request['parts'] = json_encode($request->get('parts'));
        ExternalService::find($id)->update($request->except(['job_card','vehicle','make','supplier']));
        return response()->json(new ExternalServiceResource(ExternalService::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ExternalService::destroy($id);
        return response()->json($id);
    }
}
