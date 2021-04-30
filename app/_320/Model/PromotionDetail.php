<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PromotionDetail extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'trn_prom_details';
    protected $primaryKey = 'prom_detail_id';
    public $timestamps = false;
}
