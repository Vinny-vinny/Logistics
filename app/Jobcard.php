<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobcard extends Model
{
    protected $guarded = [];

    public function machine()
    {
        return $this->belongsTo(Machine::class,'machine_id','project_link');
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
        return $this->belongsTo(AssetCategory::class,'asset_category_id','project_link');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class,'id','job_card_id');
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    public function checklist()
    {
        return $this->belongsTo(AssignChecklist::class,'id','jobcard_id');
    }
    public function account(){
        return $this->belongsTo(Account::class,'cost_code');
    }


}
