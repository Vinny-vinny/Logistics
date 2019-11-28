<?php

namespace App\Http\Controllers;

use App\FuelBalance;
use App\FuelType;
use App\Http\Resources\MachineResource;
use App\Machine;
use App\SageAsset;
use App\TrackBy;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(MachineResource::collection(Machine::all()));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request['asset_no'] = Machine::count()+1;
        $request['type'] = 'Internal';
        $request['warranty'] = Carbon::parse($request->warranty)->format('Y-m-d');
        $m = Machine::create($request->except(['service_type_id','service_types']));
        $m->service_types()->attach(json_decode($request->get('service_type_id')));
        if($request->get('insurance_file'))
        {
            $image = $request->get('insurance_file');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('insurance_file'))->save(public_path('uploads/').$name);
            $m->insurance_file = $name;
            $m->save();
        }
        return response()->json(new MachineResource($m));
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
        $machine = Machine::find($id);
        $request['warranty'] = Carbon::parse($request->warranty)->format('Y-m-d');
        $machine->update($request->except(['track_name','assign_to_id','service_type_id','service_types','insurance_file']));
        $machine->service_types()->sync(json_decode($request->get('service_type_id')));
        if($request->get('insurance_file') && $request->get('insurance_file')!='undefined')
        {
            $image = $request->get('insurance_file');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('insurance_file'))->save(public_path('uploads/').$name);
            $machine->insurance_file = $name;
            $machine->save();
        }
        return response()->json(new MachineResource(Machine::find($id)));
    }
    public function importMachines()
    {
         $assets = SageAsset::join('_btblFAAssetType','_btblFAAsset.iAssetTypeNo','=','_btblFAAssetType.idAssetTypeNo')
            ->join('_btblFAAssetSerialNo','_btblFAAsset.cAssetCode','=','_btblFAAssetSerialNo.cAssetCode')
        ->select("_btblFAAsset.idAssetNo","_btblFAAsset.cAssetCode","_btblFAAsset.cAssetDesc","_btblFAAsset.ufFACurrentKMReading","_btblFAAsset.ufFAMachineHours","_btblFAAsset.ulFATrackBy","_btblFAAsset.ucFAChasisnumber","_btblFAAsset.ucFAEnginenumber"
,"_btblFAAsset.ucFAYearofmake","_btblFAAsset.ucFARegyear","_btblFAAsset.ucFAMake","_btblFAAsset.fInsuredValue",'_btblFAAssetSerialNo.cSerialNo')
            ->where("_btblFAAsset.iAssetTypeNo", 2)
            ->orWhere("_btblFAAsset.iAssetTypeNo", 7)
            ->get();
        $existing = Machine::get();
        $found_assets = [];
        if ($existing->count() < 1){
          return $this->storeMachines($assets);
        }
        $ids = $existing->map(function($asset){
            return  $asset->asset_no;
        });

        foreach ($assets as $asset){
            if (!in_array($asset->idAssetNo,$ids->all())){
                $found_assets[] = $asset;
            }
        }
        if(!$found_assets){
            return response()->json([]);
        }
        return $this->storeMachines($found_assets);
    }
    function storeMachines($assets){
        $faker = Faker::create();
        $warranty = new Carbon('5 years');
        $inserted = [];
        foreach ($assets as $asset){
                $inserted[] = Machine::create([
                'code' => $asset->cAssetCode,
                'asset_no' => $asset->idAssetNo,
                'description' => $asset->cAssetDesc,
                'make' => $asset->ucFAMake ? $asset->ucFAMake : $faker->randomElement(['Nissan Murano','Kia Sorento','Honda CR-V','Lexus RX 350','Infiniti QX60']),
                'warranty' => $warranty->format('Y-m-d'),
                'current_readings' => $asset->ufFACurrentKMReading ?$asset->ufFACurrentKMReading : 0,
                'assign_to' => User::all()->random()->id,
                'year_of_make' => date('Y') - 5,
                'engine_no' => $asset->ucFAEnginenumber ? $asset->ucFAEnginenumber : $faker->randomDigit,
                'year_of_reg' => $asset->ucFARegyear ? $asset->ucFARegyear : date('Y')- 5,
                'chasis_no' => $asset->ucFAChasisnumber ? $asset->ucFAChasisnumber : $faker->word,
                'status' => $asset->fInsuredValue,
                'next_service_date' => Carbon::now()->addMonths(5)->format('Y-m-d'),
                'track_by_id' => TrackBy::all()->random()->id,
                'fuel_type_id' => FuelType::all()->random()->id,
                'type' => 'Internal',
                'fuel_balance_id' => FuelBalance::all()->random()->id,
                 'plate_no' => $asset->cSerialNo ? $asset->cSerialNo : $faker->word
            ]);

        }
           return response()->json($inserted);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Machine::destroy($id);
        return response()->json($id);
    }
}
