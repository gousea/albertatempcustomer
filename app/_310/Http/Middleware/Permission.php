<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Closure;


class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        
         if (Auth::user()->user_role == "SuperAdmin") {
                $data = DB::table('store_mw_users')
                    ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                    ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report')
                    ->distinct('stores.id')
                    ->get();
            } elseif( Auth::user()->sid == 0) {
                $data = DB::table('store_mw_users')
                    ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                    ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report')
                    ->where('store_mw_users.iuserid', '=', Auth::user()->iuserid)
                    ->get();
            }else {
                $data = DB::table('store_mw_users')
                ->join('stores', 'store_mw_users.sid', '=', 'stores.id')
                ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report')
                ->where([['store_mw_users.iuserid', '=', Auth::user()->iuserid], ['store_mw_users.vemail', '=', Auth::user()->vemail]])
                ->get();
            }

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
            $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' ");
        }else{
            $user_id = Auth::user()->iuserid;
            $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = ".$user_id);
        }
        $permsData = array();
        for($i = 0; $i < count($userPermsData); $i++ ){
            $permsData[] = $userPermsData[$i]->vpermissioncode;
        }

        if (in_array($role, $permsData)){
            return $next($request);
        }else {
            return redirect('dashboard');
        }


    }
}
