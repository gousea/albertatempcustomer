<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'menu_table';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['*'];
}