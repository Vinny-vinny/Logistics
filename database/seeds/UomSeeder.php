<?php

use App\SageUom;
use App\Uom;
use Illuminate\Database\Seeder;

class UomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $uoms = SageUom::select("idUnits","cUnitCode","cUnitDescription")->get();

        foreach ($uoms as $uom){
            Uom::create([
                'id_units' => $uom->idUnits,
                'code' => $uom->cUnitCode,
                'description' => $uom->cUnitDescription
            ]);

        }
    }
}
