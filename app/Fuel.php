<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    protected $guarded =[];

    public function vehicle()
    {
        return $this->belongsTo(Machine::class,'vehicle_id','project_link');
    }

    public function fueltype()
    {
        return $this->belongsTo(Part::class,'fuel_type_id');
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function user()
    {
     return $this->belongsTo(User::class,'authorized_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
