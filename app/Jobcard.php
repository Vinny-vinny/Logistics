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

    public function customer()
    {
     return $this->belongsTo(Customer::class);
    }

    public function category()
    {
        return $this->belongsTo(JobcardCategory::class,'jobcard_category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

}
