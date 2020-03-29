<?php

namespace App\Http\Controllers;

use App\Account;
use App\Ledger;
use Illuminate\Http\Request;


class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Account::get()->chunk(100));
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

      public function importAccounts()
    {
       $accounts = Ledger::select("AccountLink","Account","Description","Master_Sub_Account")->get();
        $existing = Account::get();
        $found_acounts = [];
        if ($existing->count() < 1){
            return $this->storeAccounts($accounts);
        }
        $ids = $existing->map(function($account){
            return  $account->account_link;
        });
        foreach ($accounts as $account){
            if (!in_array($account->AccountLink,$ids->all())){
                $found_acounts[] = $account;
            }
        }
        if(!$found_acounts){
            return response()->json([]);
        }
        return $this->storeAccounts($found_acounts);
    }
    function storeAccounts($accounts){

        foreach ($accounts as $account){
            $inserted[] = Account::create([
                'account_link' => $account->AccountLink,
                'account' => $account->Account,
                'sub_account' => $account->Master_Sub_Account,
                'description' => $account->Description
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
