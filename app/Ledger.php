<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $connection ='sqlsrv2';
    protected $table = 'Accounts';
}
