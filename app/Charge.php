<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $guarded = [];

    public function item()
    {
      return $this->belongsTo(Part::class,'stock_link','stock_link');
    }
}
