<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_customer';
    public $timestamps = false;
    protected $fillable = [
        'icustomerid',
        'vfname',
        'vlname',
        'vaddress1',
        'vcity',
        'vstate',
        'vzip',
        'vcountry',
        'vphone',
        'vemail',
        'vaccountnumber',
        'estatus',
        'vcustomername',
        'ntaxexemption',
        'vtaxable',
        'ncustbalance',
        'etransferstatus',
        'LastUpdate',
        'SID',
        'pricelevel',
        'debitlimit',
        'creditday',
        'note',
        'loyalty_points'
    ];
}
