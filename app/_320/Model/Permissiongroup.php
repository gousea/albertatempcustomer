<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permissiongroup extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_permissiongroup';
    public $timestamps = false;
    protected $fillable = [
                            'ipermissiongroupid',
                            'ipgroupid',
                            'vgroupname',
                            'estatus',
                            'etransferstatus',
                            'LastUpdate',
                            'SID',
                        ];
}
