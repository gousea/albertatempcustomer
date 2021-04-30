<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'mst_tax';
    
    protected $primaryKey = 'Id';

    public $timestamps = false;
}
