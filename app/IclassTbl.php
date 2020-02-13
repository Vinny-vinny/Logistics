<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IclassTbl extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = 'CliClass';
}
