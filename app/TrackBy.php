<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackBy extends Model
{
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function ($machine){
            $relationMethods = ['machines','service_type'];
            foreach ($relationMethods as $relationMethod){
                if ($machine->$relationMethod()->count() > 0){
                    return false;
                }
            }
        });
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function service_type()
    {
       return $this->belongsTo(ServiceType::class,'id','track_by_id');
    }
}
