<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_user';
    public $timestamps = false;
    protected $fillable = [
                            'iuserid',
                            'vfname',
                            'vlname',
                            'vaddress1',
                            'vaddress2',
                            'vcity',
                            'vstate',
                            'vzip',
                            'vcountry',
                            'vphone',
                            'vemail',
                            'mwpassword',
                            'pos_user',
                            'lb_user',
                            'web_user',
                            'mob_user',
                            'vuserid',
                            'vpassword',
                            'vusertype',
                            'vpasswordchange',
                            'dfirstlogindatetime',
                            'dlastlogindatetime',
                            'estatus',
                            'vuserbarcode',
                            'dlockoutdatetime',
                            'vlocktype',
                            'etransferstatus',
                            'LastUpdate',
                            'SID',
                        ];
}
