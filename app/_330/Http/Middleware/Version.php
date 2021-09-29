<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Closure;


class Version
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $version)
    {
        // $check_table_query = "SELECT * FROM information_schema.tables WHERE table_schema = '{$data[0]->db_name}' AND table_name = 'mst_version' LIMIT 1";
        $check_table = DB::connection('mysql_dynamic')->select("SHOW TABLES LIKE 'mst_version' ");
        
        if ($check_table) {
            // SELECT ver_no FROM mst_version ORDER BY ver_id DESC LIMIT 1
            $version_latest= DB::connection('mysql_dynamic')->select("SELECT ver_id  FROM mst_version ORDER BY ver_id DESC LIMIT 1  ");
            // dd($version_latest);
            $version_db = $version_latest[0];
        }else {
            $version_latest = "";
        }
        
        // dd($version_db->ver_id.PHP_EOL.$version);
        // dd($version);
        if ($version_db->ver_id == $version){
            return $next($request);
        }else {
            return redirect('dashboard');
        }


    }
}
