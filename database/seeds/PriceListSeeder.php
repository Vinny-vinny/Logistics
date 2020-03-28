<?php

use App\CustomerPriceList;
use App\PriceList;
use Illuminate\Database\Seeder;

class PriceListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prices = PriceList::select("IDPriceListPrices","iPriceListNameID","iStockID","fExclPrice","fInclPrice")->get();
        foreach ($prices as $price){
            $inserted[] = CustomerPriceList::create([
                'id_price_list' => $price->IDPriceListPrices,
                'price_list_name_id' => $price->iPriceListNameID,
                'stock_id' => $price->iStockID,
                'exclusive_price' => $price->fExclPrice,
                'inclusive_price' => $price->fInclPrice
            ]);

        }
    }
}
