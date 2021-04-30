<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BuyDownDetail extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'trn_buydown_details';
    protected $primaryKey = 'buydown_details_id';
    public $timestamps = false;
    protected $fillable = [ 'buydown_id', 'vitemname', 'vbarcode', 'invoice_cost', 'price_before_buydown', 'buydown_amount', 'cost_after_buydown', 'price_after_buydown', 'created_at', 'LastUpdate', 'SID'];

}
