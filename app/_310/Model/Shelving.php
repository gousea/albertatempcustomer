<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shelving extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'mst_shelving';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['shelvingname'];
}
