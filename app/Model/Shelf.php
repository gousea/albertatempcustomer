<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'mst_shelf';
    
    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = ['shelfname'];
}
