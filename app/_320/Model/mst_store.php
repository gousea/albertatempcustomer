<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class mst_store extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_store';
    public $timestamps = false;

    protected $fillable = ['vcompanycode', 
        'vstoreabbr', 
        'vaddress1', 
        'vstoredesc', 
        'vcity', 
        'vstate',     
        'vzip', 
        'vcountry', 
        'vphone1', 
        'vfax1',
        'vemail', 
        'vwebsite', 
        'vcontactperson', 
        'isequence', 
        'vmessage1', 
        'vmessage2'
    ];
}
