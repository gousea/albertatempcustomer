<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'mst_size';
    
    protected $primaryKey = 'isizeid';

    public $timestamps = false;

    protected $fillable = ['vsize'];

}
