<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class mst_item extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_item';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['*'];
}
