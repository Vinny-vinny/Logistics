<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = '_rtblAgents';
    protected $guarded = [];
    public $timestamps = false;
}
