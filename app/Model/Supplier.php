<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_supplier';
    protected $primaryKey = 'isupplierid';
    public $timestamps = false;
    
    protected $fillable = [
                            'isupplierid',
                            'vsuppliercode',
                            'vcompanyname',
                            'vfnmae',
                            'vlname',
                            'vaddress1',
                            'vcity',
                            'vstate',
                            'vzip',
                            'vcountry',
                            'vphone',
                            'vemail',
                            'estatus',
                            'vvendortype',
                            'vvendorterm',
                            'icreditday',
                            'vsalesperson1',
                            'vsalesperson2',
                            'vsalesperson3',
                            'vsalesperson4',
                            'vsalesperson5',
                            'vsalesphone1',
                            'vsalesphone2',
                            'vsalesphone3',
                            'vsalesphone4',
                            'vsalesphone5',
                            'etransferstatus',
                            'vcode',
                            'vftpserver',
                            'vftpuser',
                            'vftppass',
                            'LastUpdate',
                            'SID',
                            'plcbtype',
                            'edi'
                            ];
}