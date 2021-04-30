<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class promotiontype extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'mst_prom_type';
    protected $primaryKey = 'prom_type_id';
    public $timestamps = false;
    
}
