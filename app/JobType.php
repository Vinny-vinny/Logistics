<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function ($job){
            $relationsMethods = ['jobcard'];
            foreach ($relationsMethods as $relationsMethod){
                if ($job->$relationsMethod()->count() > 0){
                    return false;
                }
            }
        });
    }
    public function jobcard()
    {
        return $this->belongsTo(Jobcard::class,'id','job_type_id');
    }
}
