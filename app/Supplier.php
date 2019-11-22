<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'vendor';
    protected $connection = 'sqlsrv2';
}
