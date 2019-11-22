<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'assign_to');
    }
    public function track(){
        return $this->belongsTo(TrackBy::class,'track_by_id');
    }

    public function service_types()
    {
        return $this->belongsToMany(ServiceType::class,'machine_service_type');
    }

}
