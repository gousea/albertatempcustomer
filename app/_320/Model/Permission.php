<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_permission';
}
