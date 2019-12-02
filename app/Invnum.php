<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invnum extends Model
{
    protected $guarded = [];
    protected $table = 'InvNum';
    protected $primaryKey = 'AutoIndex';
    protected $connection = 'sqlsrv2';
    public $timestamps = false;
}
