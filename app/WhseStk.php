<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WhseStk extends Model
{
    protected $connection ='sqlsrv2';
    protected $table = 'StkItem';
}
