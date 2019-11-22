<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuelSupplier extends Model
{
    protected $guarded =[];

    public function fueltype()
    {
        return $this->belongsTo(FuelType::class,'fuel_type_id');
    }
}
