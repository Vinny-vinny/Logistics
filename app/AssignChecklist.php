<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignChecklist extends Model
{
    protected $guarded= [];

    public function checklist()
    {
     return $this->belongsTo(CheckList::class);
    }

    public function jobcard()
    {
       return $this->belongsTo(Jobcard::class);
    }
}
