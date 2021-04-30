<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_subcategory';
    protected $primaryKey = 'subcat_id';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['cat_id', 'subcat_name', 'created_at', 'LastUpdate', 'SID'];
}
