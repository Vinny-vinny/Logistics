<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalService extends Model
{
    protected $guarded = [];

    public function service_provider()
    {
        return $this->belongsTo(FuelSupplier::class,'service_provider_id');
    }

    public function supplier()
    {
        return $this->belongsTo(ServiceProvider::class,'supplier_id');
    }

    public function job_card()
    {
        return $this->belongsTo(Jobcard::class,'job_card_id');
    }
}
