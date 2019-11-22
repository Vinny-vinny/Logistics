<?php

namespace App\Http\Controllers;

use App\ServiceProvider;
use App\Supplier;
use Illuminate\Http\Request;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(ServiceProvider::all());
    }

    public function importSuppliers()
    {
          $suppliers = Supplier::select("Name","Account","Telephone","Tax_Number")->get();
          $existing = ServiceProvider::get();
          $found_suppliers = [];
          if (!$existing){
             return $this->storeSuppliers($suppliers);
          }
           $accounts = $existing->map(function($account){
           return  $account->account;
        });

       foreach ($suppliers as $supplier){
           if (!in_array($supplier->Account,$accounts->all())){
               $found_suppliers[] = $supplier;
           }
       }
       if(!$found_suppliers){
          return response()->json([]);
       }
     return $this->storeSuppliers($found_suppliers);
     }
    function storeSuppliers($suppliers){
        $inserted = [];
        foreach ($suppliers as $supplier){
            $inserted[] = ServiceProvider::create([
                'name' => $supplier->Name,
                'account' => $supplier->Account,
                'phone' => $supplier->Telephone,
                'tax_no' => $supplier->Tax_Number
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
       $provider=ServiceProvider::create($request->all());
       return response()->json($provider);
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
        ServiceProvider::find($id)->update($request->all());
        return response()->json($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ServiceProvider::destroy($id);
        return response()->json($id);
    }
}
