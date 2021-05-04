<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgeVerification extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_ageverification';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = ['vname', 'vvalue'];
}
