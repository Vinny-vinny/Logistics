<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SageAsset extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = '_btblFAAsset';
}
