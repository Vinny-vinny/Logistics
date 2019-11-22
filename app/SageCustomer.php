<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SageCustomer extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = 'Client';
}
