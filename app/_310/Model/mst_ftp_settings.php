<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class mst_ftp_settings extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_ftp_settings';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['*'];
}
