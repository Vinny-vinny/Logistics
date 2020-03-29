<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SageUom extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = '_etblUnits';
}
