<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'mst_adjustmentreason';
    
    protected $primaryKey = 'ireasonid';

    public $timestamps = false;

    protected $fillable = ['vreasonename','estatus'];
}
