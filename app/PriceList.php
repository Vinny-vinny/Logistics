<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = '_etblPriceListPrices';
}
