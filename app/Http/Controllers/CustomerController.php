<?php

namespace App\Http\Controllers;

use App\Customer;
use App\SageCustomer;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Customer::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function importCustomers()
    {
        $customers = SageCustomer::select("DCLink","Account","Name","Contact_Person","EMail")->get();
        $existing = Customer::get();
        $found_customers = [];
        if ($existing->count() < 1){
            return $this->storeCustomers($customers);
        }
        $ids = $existing->map(function($customer){
            return  $customer->dc_link;
        });

        foreach ($customers as $customer){
            if (!in_array($customer->DCLink,$ids->all())){
                $found_customers[] = $customer;
            }
        }
        if(!$found_customers){
            return response()->json([]);
        }
        return $this->storeCustomers($found_customers);
    }
    function storeCustomers($customers){
        $faker = Faker::create();
        foreach ($customers as $customer){
            $inserted[] = Customer::create([
                'dc_link' => $customer->DCLink,
                'account' => $customer->Account ? $customer->Account : $faker->word,
                'name' => $customer->Name ? $customer->Name : $faker->name,
                'contact_person' => $customer->Contact_Person ?  $customer->Contact_Person : $faker->name,
                'email' => $customer->EMail ? $customer->EMail : $faker->email
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
