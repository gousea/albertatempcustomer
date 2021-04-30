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
                    ->distinct('stores.id')
                    ->get();
            }else {
                $data = DB::table('store_mw_users')
                ->join('stores', 'store_mw_users.sid', '=', 'stores.id')
                ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone')
                ->where([['store_mw_users.iuserid', '=', Auth::user()->iuserid], ['store_mw_users.vemail', '=', Auth::user()->vemail]])
                ->distinct('stores.id')
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
                
                
                if(Auth::user()->user_role != "SuperAdmin"){
                    for($i=0; $i < count($data); $i++){
                        $table_exists = DB::connection('mysql')->select("SELECT * FROM information_schema.tables WHERE table_schema = '".$data[$i]->db_name."'  AND table_name = 'mst_permission'");
                        $check_mst_userpermission = DB::connection('mysql')->select("SELECT * FROM information_schema.tables WHERE table_schema = '".$data[$i]->db_name."'  AND table_name = 'mst_userpermissions'");
                        if (count($table_exists) > 0 && count($check_mst_userpermission) > 0) {
                            if(Auth::user()->user_role == "StoreAdmin" && Auth::user()->sid == 0){
                                $user_id = Auth::user()->iuserid;
                                $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM ".$data[$i]->db_name.".mst_permission mp join ".$data[$i]->db_name.".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active'  and mup.userid = '".$user_id."' ");
                                if(count($userPermsData) <= 0){
                                    // dd(__LINE__);
                                    $permissions = DB::connection('mysql')->select("SELECT  distinct(vpermissioncode) FROM ".$data[$i]->db_name.".mst_permission where vpermissiontype = 'WEB' ");
                                    foreach ($permissions as $permission) {
                                        $user_perms_insert = "INSERT INTO ".$data[$i]->db_name.".mst_userpermissions(userid, permission_id, status, created_id, updated_id) values( '".Auth::user()->iuserid."', '".$permission->vpermissioncode."', 'Active', '".Auth::user()->iuserid."', '".Auth::user()->iuserid."' )";
                                        $insert = DB::connection('mysql')->select($user_perms_insert);
                                    } 
                                    $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM ".$data[$i]->db_name.".mst_permission mp join ".$data[$i]->db_name.".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '".$user_id."' ");
                                }else {
                                    // dd(__LINE__);
                                    $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM ".$data[$i]->db_name.".mst_permission mp join ".$data[$i]->db_name.".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '".$user_id."' ");
                                }
                            }else{
                                $user_id = Auth::user()->iuserid;
                                $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM ".$data[$i]->db_name.".mst_permission mp join ".$data[$i]->db_name.".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '".$user_id."' ");
                            }
                            $permsData = array();
                            for($i = 0; $i < count($userPermsData); $i++ ){
                                $permsData[] = $userPermsData[$i]->vpermissioncode;
                            }
                            session()->put('userPermsData', $permsData);
                        }else {
                            if(Auth::user()->sid == 0){
                                $this->DatabaseAction($data[$i]->db_name, $data[$i]->id );
                                if(Auth::user()->user_role == "StoreAdmin"){
                                    $user_id = Auth::user()->iuserid;
                                    $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM ".$data[$i]->db_name.".mst_permission mp join ".$data[$i]->db_name.".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '".$user_id."' ");
                                }else{
                                    $user_id = Auth::user()->iuserid;
                                    $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM ".$data[$i]->db_name.".mst_permission mp join ".$data[$i]->db_name.".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '".$user_id."' ");
                                }
                                $permsData = array();
                                for($i = 0; $i < count($userPermsData); $i++ ){
                                    $permsData[] = $userPermsData[$i]->vpermissioncode;
                                }
                                session()->put('userPermsData', $permsData);
                            }else {
                                Auth::logout();
                                Session::flush();
                                session()->put('error',  "You don't have any permissions for this Store. Please Contact Store Admin.");
                                return redirect('/');
                            }
                        }
                    }
                }else{
                    if( Auth::user()->user_role == "StoreAdmin" && Auth::user()->sid == 0){
                        $user_id = Auth::user()->iuserid;
                        $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  distinct(mp.vpermissioncode) FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '".$user_id."' ");
                    }else{
                        $user_id = Auth::user()->iuserid;
                        $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  distinct(mp.vpermissioncode) FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '".$user_id."' ");
                    }
                   
                    $permsData = array();
                    for($i = 0; $i < count($userPermsData); $i++ ){
                        $permsData[] = $userPermsData[$i]->vpermissioncode;
                    }
                    session()->put('userPermsData', $permsData);
                }
                
                // if(Auth::user()->user_role && Auth::user()->sid == 0){
                //     $user_id = Auth::user()->iuserid;
                //     $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' ");
                // }else{
                //     $user_id = Auth::user()->iuserid;
                //     $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = ".$user_id);
                // }
                // $permsData = array();
                // for($i = 0; $i < count($userPermsData); $i++ ){
                //     $permsData[] = $userPermsData[$i]->vpermissioncode;
                // }
                // session()->put('userPermsData', $permsData);
        
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
    
    public function DatabaseAction($sid, $store_id)
    {
        $sql ="CREATE TABLE IF NOT EXISTS ".$sid.".mst_permission (
                                      Id  int(11) NOT NULL AUTO_INCREMENT,  
                                      vpermissioncode varchar(20) NOT NULL,
                                      vpermissionname varchar(50) NOT NULL,
                                      vmenuname varchar(20) NOT NULL,
                                      vpermissiontype varchar(20) DEFAULT NULL ,
                                      vppercode varchar(20) NOT NULL,
                                      ivorder int(11) NOT NULL,
                                      vdesc varchar(100) NOT NULL,
                                      etransferstatus varchar(50) DEFAULT NULL,
                                      LastUpdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      SID int(11) NOT NULL  DEFAULT ".$store_id.",
                                      PRIMARY KEY (id)
                )";
        $sql_user_permissions= "CREATE TABLE IF NOT EXISTS ".$sid.".mst_userpermissions (
              Id int(11) NOT NULL AUTO_INCREMENT,
              userid int(11) NOT NULL,
              permission_id varchar(255) NOT NULL,
              status enum('Active','Inactive') NOT NULL DEFAULT 'Active',
              created_id int(11) NOT NULL,
              created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              updated_id int(11) DEFAULT NULL,
              LastUpdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              SID int(11) NOT NULL DEFAULT ".$store_id.",
              PRIMARY KEY (Id)
            )";
        $sql_excute = DB::connection('mysql_dynamic')->select($sql);
        $sql_user_permissions_excute = DB::connection('mysql_dynamic')->select($sql_user_permissions);
        
        $insert_data = "INSERT INTO ".$sid.".mst_permission
                            (vpermissioncode, vpermissionname, vmenuname, vpermissiontype, vppercode, ivorder, vdesc, etransferstatus, LastUpdate, SID)  
                        VALUES 
                            ('PER1001','DASHBOARD','DASHBOARD','WEB','',1,'Dashborad ','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1002','VENDOR','VENDORS','WEB','',2,'Vendors','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1003','CUSTOMER','CUSTOMER','WEB','',1,'Customer','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1004','USERS','USER','WEB','',1,'USER','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1005','STORE','STORE','WEB','',1,'STORE','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1006','ITEMS','ITEM','WEB','',1,'Item','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1007','INVENTORY','INVENTORY','WEB','',1,'Inventory','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1008','ADMINISTRATION','ADMINISTRATION','WEB','',1,'Administration','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1009','REPORTS','REPORTS','WEB','',1,'Reports','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1010','SETTINGS','SETTINGS','WEB','',1,'Settings','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1011','LOYALITY','LOYALITY','WEB','',1,'Loyality','', CURRENT_TIMESTAMP, ".$store_id."),
                            ('PER1012','GENERAL','GENERAL','WEB','',1,'General','', CURRENT_TIMESTAMP, ".$store_id.")";
                            
        $insert_data_excute = DB::connection('mysql_dynamic')->select($insert_data);
        
        $permissions = DB::connection('mysql_dynamic')->select("SELECT  distinct(vpermissioncode) FROM ".$sid.".mst_permission where vpermissiontype = 'WEB' ");
       
        foreach ($permissions as $permission) {
            $user_perms_insert = "INSERT INTO ".$sid.".mst_userpermissions(userid, permission_id, status, created_id, updated_id) values('".Auth::user()->iuserid."', '".$permission->vpermissioncode."', 'Active', '".Auth::user()->iuserid."', '".Auth::user()->iuserid."' )";
            $insert = DB::connection('mysql')->select($user_perms_insert);
                
        } 
    }
}
