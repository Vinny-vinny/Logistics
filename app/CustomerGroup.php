<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = 'GrpTbl';

}
