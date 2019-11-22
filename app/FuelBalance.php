<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuelBalance extends Model
{
    protected $guarded =[];
  protected static function boot()
  {
      parent::boot();
      static::deleting(function ($balance){
      $relationMethods = ['machines'];
      foreach ($relationMethods as $relationMethod){
          if ($balance->$relationMethod()->count() > 0){
              return false;
          }
      }
      });
  }

    public function machines()
    {
    return $this->hasMany(Machine::class);
    }
}
