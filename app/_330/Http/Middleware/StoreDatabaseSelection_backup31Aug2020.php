<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Config;
use Session;
use DB;

class StoreDatabaseSelection
{

    public function handle($request, Closure $next )
    {
        
        if(Session::has('dbhost') && Session::has('dbname') && Session::has('dbuser') && Session::has('dbpassword')){
            
            //if the store credentials are there in session         
            Config::set('database.connections.mysql_dynamic', array(
                
                'driver' => 'mysql',
                'host' =>  Session::get('dbhost'),
                'port' => '3306',
                'database' => Session::get('dbname'),
                'username' => Session::get('dbuser'),
                'password' => Session::get('dbpassword'),
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => null,
            
            ));
            
            if(Auth::user()->user_role && Auth::user()->sid == 0){
                $user_id = Auth::user()->iuserid;
                $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' ");
            }else{
                $user_id = Auth::user()->iuserid;
                $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = ".$user_id);
            }
            $permsData = array();
            for($i = 0; $i < count($userPermsData); $i++ ){
                $permsData[] = $userPermsData[$i]->vpermissioncode;
            }
        

        session()->put('userPermsData', $permsData);
            
        } elseif(isset(Auth::user()->id)) {           
            //if the store credentials are not there in session
            
            if (Auth::user()->user_role == "SuperAdmin") {
                $data = DB::table('store_mw_users')
                    ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                    ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone')
                    ->distinct('stores.id')
                    ->get();
            } elseif( Auth::user()->sid == 0) {
                $data = DB::table('store_mw_users')
                    ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                    ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone')
                    ->where('store_mw_users.iuserid', '=', Auth::user()->iuserid)
                    ->get();
            }else {
                $data = DB::table('store_mw_users')
                ->join('stores', 'store_mw_users.sid', '=', 'stores.id')
                ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone')
                ->where([['store_mw_users.iuserid', '=', Auth::user()->iuserid], ['store_mw_users.vemail', '=', Auth::user()->vemail]])
                ->get();
            }
            
            if(isset($data[0]) && count((array)$data[0]) > 0){
                
                config(['database.connections.mysql_dynamic' => [
                    'driver'    => 'mysql',
                    'host'      =>  $data[0]->db_hostname,
                    'database'  =>  $data[0]->db_name,
                    'username'  =>  $data[0]->db_username,
                    'password'  =>  $data[0]->db_password,
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'strict'    => false,
                ]]);
                
                if(Auth::user()->user_role && Auth::user()->sid == 0){
                    $user_id = Auth::user()->iuserid;
                    $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' ");
                }else{
                    $user_id = Auth::user()->iuserid;
                    $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = ".$user_id);
                }
                $permsData = array();
                for($i = 0; $i < count($userPermsData); $i++ ){
                    $permsData[] = $userPermsData[$i]->vpermissioncode;
                }
        

        session()->put('userPermsData', $permsData);
        
                session()->put('dbhost',  $data[0]->db_hostname);
                session()->put('dbname',  $data[0]->db_name);
                session()->put('dbuser',  $data[0]->db_username);
                session()->put('dbpassword',  $data[0]->db_password);
        
                session()->put('sid', $data[0]->id);
                session()->put('storeName', $data[0]->name);
                session()->put('stores', $data);
                session()->put('webadmin', $data[0]->webadmin);
                session()->put('plcb_report', $data[0]->plcb_report);
                session()->put('address', $data[0]->address);
                session()->put('phone', $data[0]->phone);
                 $check_table_query = "SELECT * FROM information_schema.tables WHERE table_schema = '{$data[0]->db_name}' AND table_name = 'mst_version' LIMIT 1";
        
                $check_table = DB::connection('mysql_dynamic')->select($check_table_query);
                
        
                if(count($check_table) === 0){
                    
                    session()->put('new_database', false);
                    
                } else {
                
                    //========check store version =========
                    $result = DB::connection('mysql_dynamic')->table('mst_version')->orderBy('LastUpdate', 'desc')->first();
                    
                    if($result->ver_no >= 3){
                        session()->put('new_database', true);
                    }else{
                        session()->put('new_database', false);
                    }
                    
                }
                
            } else {
                $request->session()->flush();
                return redirect('/login'); 
            }
    
            
        } else {
            $request->session()->flush();
            return redirect('/login');
        }
        
        
        
        
        return $next($request);
    }
}
