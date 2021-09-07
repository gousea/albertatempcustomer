<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class UserDynamic extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_user';
    public $timestamps = false;
    protected $primaryKey = 'iuserid';
    protected $fillable = [
                            // 'iuserid',
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
                            'ssn',
                            'pay_rate',
                            'time_clock',
                            'time_email',
                            'pay_type',
                            'tc_password',
                            'over_time',
                            'start_dt',
                            'termination_dt',
                            'vacation_hours',
                            'sick_hours',
                            'available_hours',
                            'sun_hours',
                            'mon_hours',
                            'tue_hours',
                            'wed_hours',
                            'thu_hours',
                            'fri_hours',
                            'sat_hours',
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
