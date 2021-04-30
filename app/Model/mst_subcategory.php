<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class mst_subcategory extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_subcategory';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['icategoryid', 'subcat_name', 'created_at', 'LastUpdate', 'SID'];
}
