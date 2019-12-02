<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BtnLine extends Model
{
    protected $guarded = [];
    protected $table = '_btblInvoiceLines';
    protected $primaryKey = 'idInvoiceLines';
    protected $connection = 'sqlsrv2';
    public $timestamps = false;
}
