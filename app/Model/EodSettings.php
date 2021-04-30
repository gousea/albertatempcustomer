<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EodSettings extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'trn_manual_sales';
    
    // protected $primaryKey = 'Id';

    public $timestamps = false;
    protected $guarded = ['*'];

    protected $fillable = ['bid', 'type', 'non', 'ts1', 'ts2', 'ts3'];
}