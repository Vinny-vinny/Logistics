<?php

namespace App\Http\Controllers;

use App\Part;
use App\StkItem;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class PartsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Part::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parts = Part::create($request->all());
        return response()->json($parts);
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
    public function importParts()
    {
        $parts = StkItem::select("StockLink","Code","Description_1","AveUCst","ItemGroup","iUOMStockingUnitID","iUOMDefPurchaseUnitID","iUOMDefSellUnitID","Qty_On_Hand","ServiceItem")->get();
        $existing = Part::get();
        $found_parts = [];
        if ($existing->count() < 1){
            return $this->storeParts($parts);
        }
        $ids = $existing->map(function($part){
            return  $part->stock_link;
        });

        foreach ($parts as $part){
            if (!in_array($part->StockLink,$ids->all())){
                $found_parts[] = $part;
            }
        }
        if(!$found_parts){
            return response()->json([]);
        }
        return $this->storeParts($found_parts);
    }
    function storeParts($assets){
        $faker = Faker::create();
         $inserted = [];
          foreach ($assets as $asset){
            $inserted[] = Part::create([
                'code' => $asset->Code,
                'description' => $asset->Description_1,
                'cost' => $asset->AveUCst==0 ? $faker->unique()->numberBetween(10,2000) : $asset->AveUCst,
                'stock_link' => $asset->StockLink,
                'item_group' => $asset->ItemGroup,
                'uom_stock_id' => $asset->iUOMStockingUnitID,
                'uom_purchase_id' => $asset->iUOMDefPurchaseUnitID,
                'uom_sellunit_id' => $asset->iUOMDefSellUnitID,
                'qty_on_hand' => $asset->Qty_On_Hand,
                'service_item' => $asset->ServiceItem
                ]);

        }
        return response()->json($inserted);
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
        Part::find($id)->update($request->all());
        return response()->json(Part::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Part::destroy($id);
        return response()->json($id);
    }
}
