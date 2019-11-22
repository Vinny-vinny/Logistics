<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    protected $guarded =[];

    public function vehicle()
    {
        return $this->belongsTo(Machine::class,'vehicle_id');
    }

    public function fueltype()
    {
        return $this->belongsTo(FuelType::class,'fuel_type_id');
    }

    public function supplier()
    {
        return $this->belongsTo(FuelSupplier::class,'supplier_id');
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

}
