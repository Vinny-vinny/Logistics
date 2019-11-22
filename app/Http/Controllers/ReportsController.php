<?php

namespace App\Http\Controllers;

use App\Fuel;
use App\Http\Resources\FuelReportResource;
use App\Http\Resources\FuelResource;
use App\Http\Resources\JobcardReportResource;
use App\Http\Resources\JobcardResource;
use App\Jobcard;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function JobReport(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay()->format('Y-m-d H:m:s');
        $to = Carbon::parse($request->to)->endOfDay()->format('Y-m-d H:m:s');
        return response()->json(JobcardReportResource::collection(Jobcard::whereBetween('created_at',[$from,$to])->get()));
    }

    public function FuelReport(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay()->format('Y-m-d H:m:s');
        $to = Carbon::parse($request->to)->endOfDay()->format('Y-m-d H:m:s');
        return response()->json(FuelReportResource::collection(Fuel::whereBetween('created_at',[$from,$to])->get()));
    }
}

