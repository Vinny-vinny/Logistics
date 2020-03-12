<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POSTGL extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = 'POSTGL';
}
