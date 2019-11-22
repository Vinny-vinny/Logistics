<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SageProject extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = 'Project';
}
