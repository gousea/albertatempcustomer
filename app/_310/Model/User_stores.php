<?php

namespace Model\App;

use Illuminate\Database\Eloquent\Model;

class User_stores extends Model
{
    protected $table = 'user_stores';
    protected $fillable = ['user_id', 'store_id'];
}
