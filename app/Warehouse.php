<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $connection ='sqlsrv2';
    protected $table = 'whsemst';
}
