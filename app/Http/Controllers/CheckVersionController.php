<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;
use App\User;
// use Redirect;

class CheckVersionController extends Controller
{
    /* To check the version and do necessary redirection */
    protected function checkVersion(Request $request){
        $input = $request->all();
        
        $dataUser = User::where('vemail', $input['vemail'])->get();
        
        if ($dataUser[0]->user_role == "SuperAdmin") {
            $ver_id = 320;
            return response(json_encode($ver_id), 200)
                  ->header('Content-Type', 'application/json');
        } elseif( $dataUser[0]->sid == 0) {
            $data = DB::table('store_mw_users')
                    ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                    ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone', 'stores.state', 'stores.city', 'stores.zip','stores.hq_sid')
                    ->where('store_mw_users.iuserid', '=', $dataUser[0]->iuserid)
                    ->distinct('stores.id')
                    ->get();
            $versions_array = [];
            $ver_id = 310;
            foreach ($data as $store){
                $version_latest= DB::connection('mysql')->select("SELECT ver_id FROM u".$store->id.".mst_version ORDER BY ver_id DESC LIMIT 1 ");
                $version_db = $version_latest[0];
                
                array_push($versions_array, $version_db);
                if($version_db->ver_id == 320){
                    // return Redirect::to('/');
                    $ver_id = 320;
                }
            }
            
            return response(json_encode($ver_id), 200)
                  ->header('Content-Type', 'application/json');
            
        }else {
            
             $data = DB::table('store_mw_users')
                        ->join('stores', 'store_mw_users.sid', '=', 'stores.id')
                        ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone',  'stores.state', 'stores.city', 'stores.zip', 'stores.hq_sid')
                        ->where([['store_mw_users.iuserid', '=', $dataUser[0]->iuserid], ['store_mw_users.vemail', '=', $dataUser[0]->vemail]])
                        ->distinct('stores.id')
                        ->get();
                        
            $versions_array = [];
            $ver_id = 310;
            foreach ($data as $store){
                $version_latest= DB::connection('mysql')->select("SELECT ver_id FROM u".$store->id.".mst_version ORDER BY ver_id DESC LIMIT 1 ");
                $version_db = $version_latest[0];
                array_push($versions_array, $version_db);
                if($version_db->ver_id == 320){
                    // return Redirect::to('/');
                     $ver_id = 320;
                }
            }
            
            // $url = 'https://tempcustomer.albertapayments.com/';
            // return Redirect::to($url);
            
            return response(json_encode($ver_id), 200)
                  ->header('Content-Type', 'application/json');
        }   
        
    }
}
