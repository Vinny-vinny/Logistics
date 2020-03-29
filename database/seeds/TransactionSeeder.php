<?php

use Illuminate\Database\Seeder;
use App\Transaction;
use App\Trcode;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Transaction::truncate();
        $transactions = Trcode::get();

        foreach ($transactions as $value) {
        	Transaction::create([
             'transaction_id' => $value->idTrCodes,
             'code' => $value->Code,
             'description' => $value->Description
        	]);
        }

    }
}
