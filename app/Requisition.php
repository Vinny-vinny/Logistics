<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'requested_by');
    }

    public function project()
    {
        return $this->belongsTo(Machine::class,'subproject_id','project_link');
    }
}
