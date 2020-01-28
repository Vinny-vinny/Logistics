<?php

namespace App\Http\Controllers;
use App\PriceList;
use App\CustomerPriceList;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(CustomerPriceList::all());
    }

      public function importPrices()
    {
       $prices = PriceList::select("IDPriceListPrices","iPriceListNameID","iStockID","fExclPrice","fInclPrice")->get();
        $existing = CustomerPriceList::get();
        $found_prices = [];
        if ($existing->count() < 1){
            return $this->storePrices($prices);
        }
        $ids = $existing->map(function($price){
            return  $price->id_price_list;
        });
        foreach ($prices as $price){
            if (!in_array($price->IDPriceListPrices,$ids->all())){
                $found_prices[] = $price;
            }
        }
        if(!$found_prices){
            return response()->json([]);
        }
        return $this->storePrices($found_prices);
    }
    function storePrices($prices){

        foreach ($prices as $price){
            $inserted[] = CustomerPriceList::create([
                'id_price_list' => $price->IDPriceListPrices,
                'price_list_name_id' => $price->iPriceListNameID,
                'stock_id' => $price->iStockID,
                'exclusive_price' => $price->fExclPrice,
                'inclusive_price' => $price->fInclPrice
            ]);

        }
        return response()->json($inserted);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
