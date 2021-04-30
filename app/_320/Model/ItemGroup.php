<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ItemGroup extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'itemgroup';
    protected $primaryKey = 'iitemgroupid';
    public $timestamps = false;
    protected $fillable = ['vitemgroupname'];
}
