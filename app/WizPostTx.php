<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WizPostTx extends Model
{
     protected $connection = 'sqlsrv2';
     protected $table = 'tblXML';
     protected $primaryKey = 'SNo';
     protected $guarded = [];
     public $timestamps = false;
}
