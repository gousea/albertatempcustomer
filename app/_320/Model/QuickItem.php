<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QuickItem extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_itemgroup';
    protected $primaryKey = 'iitemgroupid';
    public $timestamps = false;

    // protected $fillable = ['vname', 'vvalue'];
}

?>