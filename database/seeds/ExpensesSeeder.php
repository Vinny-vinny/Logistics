<?php

use Illuminate\Database\Seeder;

class ExpensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Expense::create([
            'name' => 'Greasing',
            'description' => 'Simply greasing',
            'cost' => 800,
        ]);
        \App\Expense::create([
            'name' => 'Washing',
            'description' => 'Simply washing',
            'cost' => 200,
        ]);
        \App\Expense::create([
            'name' => 'fine',
            'description' => 'Simply fine',
            'cost' => 5000,
        ]);
    }
}
