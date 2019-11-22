<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobcard extends Model
{
    protected $guarded = [];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function internal_supplier()
    {
       return $this->belongsTo(FuelSupplier::class,'supplier_id');
    }
    public function external_supplier()
    {
        return $this->belongsTo(ServiceProvider::class,'service_provider_id');
    }
    public function trackby()
    {
        return $this->belongsTo(TrackBy::class,'track_by_id');
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class,'service_type_id');
    }

    public function fuelbalance()
    {
        return $this->belongsTo(FuelBalance::class,'fuel_balance_id');
    }


}
