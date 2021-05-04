<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WebStoreSettings extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'web_store_settings';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['*'];
}
