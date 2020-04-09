<?php

use Illuminate\Database\Seeder;

class ChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $labour = \App\Part::where('service_item',1)->first();
       $std_charge = \App\Part::where('service_item',1)->skip(1)->first();
        \App\Charge::create([
            'stock_link' => $labour->stock_link,
            'name' => 'Labour Charge'
        ]);
        \App\Charge::create([
            'stock_link' => $std_charge->stock_link,
            'name' => 'Standing Charge'
        ]);
    }
}
