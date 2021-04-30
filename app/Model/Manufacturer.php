<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_manufacturer';
    protected $primaryKey = 'mfr_id';
    public $timestamps = false;
    protected $fillable = ['mfr_code','mfr_name','status'];
}

?>