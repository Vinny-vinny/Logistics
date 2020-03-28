<?php

use App\Part;
use App\StkItem;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parts = StkItem::select("StockLink","Code","Description_1","AveUCst","ItemGroup","iUOMStockingUnitID","iUOMDefPurchaseUnitID","iUOMDefSellUnitID","Qty_On_Hand","ServiceItem")->get();
        $faker = Faker::create();
        $inserted = [];
        foreach ($parts as $asset){
            $inserted[] = Part::create([
                'code' => $asset->Code,
                'description' => $asset->Description_1,
                'cost' => $asset->AveUCst==0 ? $faker->unique()->numberBetween(10,200000) : $asset->AveUCst,
                'stock_link' => $asset->StockLink,
                'item_group' => $asset->ItemGroup,
                'uom_stock_id' => $asset->iUOMStockingUnitID,
                'uom_purchase_id' => $asset->iUOMDefPurchaseUnitID,
                'uom_sellunit_id' => $asset->iUOMDefSellUnitID,
                'qty_on_hand' => (float)$asset->Qty_On_Hand,
                'service_item' => $asset->ServiceItem
            ]);

        }
    }
}
