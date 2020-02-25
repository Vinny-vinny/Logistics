<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wheretocharge extends Model
{
    protected $guarded =[];

    public function account(){
    	return $this->belongsTo(Account::class,'account_id');
    }
}
