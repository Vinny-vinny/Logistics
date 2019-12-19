<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trcode extends Model
{
     protected $connection = 'sqlsrv2';
     protected $table = 'TRCODES';
}
