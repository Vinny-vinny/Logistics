<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupTbl extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = 'GrpTbl';
}
