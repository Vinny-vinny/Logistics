<?php

use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = \App\Ledger::get();

        foreach ($transactions as $account) {
            \App\Account::create([
                'account_link' => $account->AccountLink,
                'account' => $account->Account,
                'sub_account' => $account->Master_Sub_Account,
                'description' => $account->Description
            ]);
        }
    }
}
