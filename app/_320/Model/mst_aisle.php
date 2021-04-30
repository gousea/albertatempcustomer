<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class mst_aisle extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_aisle';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['*'];
}
