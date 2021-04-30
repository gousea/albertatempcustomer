<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class mst_delete_table extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_delete_table';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['*'];
}
