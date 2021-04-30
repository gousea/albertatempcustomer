<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Aisle extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'mst_aisle';
    
    protected $primaryKey = 'Id';

    public $timestamps = false;
    protected $guarded = ['*'];

    protected $fillable = ['aislename'];
}
