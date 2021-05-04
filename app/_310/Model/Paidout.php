<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Paidout extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'mst_paidout';
    
    protected $primaryKey = 'ipaidoutid';

    public $timestamps = false;
    protected $fillable = ['vpaidoutname','estatus'];
}
