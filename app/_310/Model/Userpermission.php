<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Userpermission extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_userpermissions';
    public $timestamps = false;
    protected $fillable = [
                            'Id',
                            'userid',
                            'permission_id',
                            'status',
                            'created_id',
                            'created_at',
                            'updated_id',
                            'LastUpdate',
                            'SID',
                        ];
}
