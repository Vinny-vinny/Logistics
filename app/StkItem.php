<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StkItem extends Model
{
    protected $connection ='sqlsrv';
    protected $table = 'StkItem';
}
