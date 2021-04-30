<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->session()->has('sid')) {
            $sid = $request->session()->get('sid');
        } else {
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
            
            //========check store version =========
                
                $check_table_query = "SELECT * FROM information_schema.tables WHERE table_schema = '{$data[0]->db_name}' AND table_name = 'mst_version' LIMIT 1";
        
                $check_table = DB::connection('mysql_dynamic')->select($check_table_query);
                //dd($check_table);
        
                if(count($check_table) === 0){
                    
                    session()->put('new_database', false);
                    
                } else {
                
                    $result = DB::connection('mysql_dynamic')->table('mst_version')->orderBy('LastUpdate', 'desc')->first();
                    
                    if($result->ver_no >= 3){
                        session()->put('new_database', true);
                    }else{
                        session()->put('new_database', false);
                    }
                }
            
            $sid = $data[0]->id;
        }
        $date = date('Y-m-d');
        $fdate = date("Y-m-d", (strtotime($date)) - (7 * 24 * 60 * 60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24 * 60 * 60));

        $ydate = date("m-d-Y", (strtotime($date)) - (24 * 60 * 60));


        $sdate = date("m-d-Y", (strtotime($date)) - (7 * 24 * 60 * 60));
        $date2 = date('m-d-Y');
        $edate = $date2 . ' 23:59:59';
        // get data for sales
        /*$salesData = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59') AND SID='" . (int) $data[0]->id . "'");
        $sales = $salesData[0]->total;
        if ($sales > 0) {
            $output['today'] = $sales;
        } else {
            $output['today'] = 0;
        }

        $salesYesterday = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $tdate . " 00:00:00' AND '" . $tdate . " 23:59:59') AND SID='" . (int) $data[0]->id . "'");

        $salesYes = $salesYesterday[0]->total;

        if ($salesYes > 0) {
            $output['yesterday'] = $salesYes;
        } else {
            $output['yesterday'] = 0;
        }*/

        // $fdate1 = $fdate . ' 00:00:00';
        // $date1 = $date . ' 23:59:59';

        // $salesWeek = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE date_format(dtrandate,'%Y-%m-%d %H:%i:%s') >= '" . $fdate1 . "' and date_format(dtrandate,'%Y-%m-%d %H:%i:%s') <= '" . $date1 . "' AND SID='" . (int) $data[0]->id . "'");
        // $salesWee = $salesWeek[0]->total;
        // if ($salesWee > 0) {
        //     $output['week'] = $salesWee;
        // } else {
        //     $output['week'] = 0;
        // }
        // return compact('output');
        // return __LINE__;
        
        $output = $this->get_dashboard_data($request, $sid);

        
        // dd(compact('output'));
        

        return view('dashboard', compact('output'));
    }
    
    
    
    public function dashContent(Request $request)
    {
        $input = $request->all();
        $sid  = (int) $input['sid'];

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

        for ($i = 0; $i < count($data); $i++) {

            if ((int) $data[$i]->id == $sid) {
                // echo $i. "<br />". $data[$i]->id ;
                // dd($data[$i]->id,$sid);
                config(['database.connections.mysql_dynamic' => [
                    'driver'    => 'mysql',
                    'host'      =>  $data[$i]->db_hostname,
                    'database'  =>  $data[$i]->db_name,
                    'username'  =>  $data[$i]->db_username,
                    'password'  =>  $data[$i]->db_password,
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'strict'    => false,
                ]]);

                session()->put('dbhost',  $data[$i]->db_hostname);
                session()->put('dbname',  $data[$i]->db_name);
                session()->put('dbuser',  $data[$i]->db_username);
                session()->put('dbpassword',  $data[$i]->db_password);



                session()->put('sid', $data[$i]->id);
                session()->put('storeName', $data[$i]->name);
                // dd($data[$i]->id);
                session()->put('webadmin', $data[$i]->webadmin);
                session()->put('plcb_report', $data[$i]->plcb_report);
                session()->put('address', $data[$i]->address);
                session()->put('phone', $data[$i]->phone);

            } else {
                // echo $i. "<br />". $data[$i]->id;
                // echo  "Sid is not equla";
            }
        }
        session()->put('stores', $data);

        // $data = DB::table('stores')
        //         ->select('id', 'name', 'db_name', 'db_username', 'db_password', 'db_hostname')
        //         ->where('id', '=', $sid)
        //         ->get();




        // $date = date('Y-m-d');
        // $fdate = date("Y-m-d", (strtotime($date)) - (7 * 24 * 60 * 60));
        // $tdate = date("Y-m-d", (strtotime($date)) - (24 * 60 * 60));

        // $ydate = date("m-d-Y", (strtotime($date)) - (24 * 60 * 60));


        // $sdate = date("m-d-Y", (strtotime($date)) - (7 * 24 * 60 * 60));
        // $date2 = date('m-d-Y');
        // $edate = $date2 . ' 23:59:59';
        
        $output = $this->get_dashboard_data($request, $sid);
        
        // dd($data);
        return view('dashboard', compact('output'));
    }
    

    private function get_dashboard_data($request, $store_id){
        
        $date = date('Y-m-d');
        
        $fdate = date("Y-m-d", (strtotime($date)) - (7 * 24 * 60 * 60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24 * 60 * 60));

        $ydate = date("m-d-Y", (strtotime($date)) - (24 * 60 * 60));


        $sdate = date("m-d-Y", (strtotime($date)) - (7 * 24 * 60 * 60));
        $date2 = date('m-d-Y');
        $edate = $date2 . ' 23:59:59';
        
        
        //==================================== url and parameters to get data for graphs =========================================
        
        $username = $request->session()->get('loggedin_username');
		$pass = $request->session()->get('loggedin_password');
        
        $api = 'https://portal.albertapayments.com';
		$url = $api.'/authenticate_new?email='.$username.'&password='.urlencode($pass);
        // dd($url);
		$curl = curl_init();
		curl_setopt_array($curl, array(
                            		CURLOPT_RETURNTRANSFER => 1,
                            		CURLOPT_URL => $url,
                            		CURLOPT_POST => 1
                            	)
        );
		$data_curl = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);

		$data_curl = json_decode($data_curl);
		
// 		dd($data_curl);
		
        if(isset($data_curl->error)){
            $output['sales']['today'] = 0;
            $output['sales']['yesterday'] = 0;
            $output['sales']['week'] = 0;
            
            $output['customers']['today'] = 0;
            $output['customers']['yesterday'] = 0;
            $output['customers']['week'] = 0;
            
            $output['void']['today'] = 0;
            $output['void']['yesterday'] = 0;
            $output['void']['week'] = 0;
            
            $output['date'] = $fdate;
            
            $output['sevendaysales'] = [];
    
            $output['sevendaysCustomer'] = [];
            
            $output['dailySummary'] = [];
            
            $output['topItem'] = [];
            
            $output['topCategory'] = [];
    
            $output['customer'] = [];
        }else {
            $token = $data_curl->token;

          	//EST time Zone
          	date_default_timezone_set('US/Eastern');
        
            // $store_id = $data[0]->id;
            
            // sales, customer, void
            $output['sales'] = $this->getSales($date, $request);
            $output['customers'] = $this->getCustomers($date, $request);
            $output['void'] = $this->getVoid($date, $request);
    
            
            //seven day sales
            $url_7daysales = $api.'/api/admin/7daysSales?fdate='.$fdate.'&tdate='.$tdate.'&token='.$token.'&sid='.$store_id;
    		$output['sevendaysales'] = $this->getchartsValues($url_7daysales);
    
            // seven day customer
            $url_7daysCustomer = $api.'/api/admin/7daysCustomer?fdate='.$fdate.'&tdate='.$tdate.'&token='.$token.'&sid='.$store_id;
            $output['sevendaysCustomer'] = $this->getchartsValues($url_7daysCustomer); 
            
            // sales summary
            $url_dailySummary = $api.'/api/admin/dailySummary?fdate='.$date.'&tdate='.$date.'&token='.$token.'&sid='.$store_id;
            
            $daily_summary = $this->getchartsValues($url_dailySummary);
            
            $output['dailySummary'] = isset($daily_summary[0])?$daily_summary[0]:[];
    
            // top category
            $url_topCategory = $api.'/api/admin/topCategory?fdate='.$fdate.'&tdate='.$tdate.'&token='.$token.'&sid='.$store_id;
            $output['topCategory'] = $this->getchartsValues($url_topCategory);
            
            //top product / item
            $url_topItem = $api.'/api/admin/topItem?fdate='.$fdate.'&tdate='.$tdate.'&token='.$token.'&sid='.$store_id;
            $output['topItem'] = $this->getchartsValues($url_topItem);  
            
            // 24 hours customer 
            $url_customer = $api.'/api/admin/customer?fdate='.$fdate.'&tdate='.$tdate.'&token='.$token.'&sid='.$store_id;
            $output['customer'] = $this->getchartsValues($url_customer);
            
            $output['date'] = $date;
        }
		
        
        return $output;
    }
    
    public function getchartsValues($url) {
		$return_data = array();
		if(!empty($url)){
			$curl = curl_init();
	        curl_setopt_array($curl, array(
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_URL => $url
	        ));
	        $data = curl_exec($curl);
	        $info = curl_getinfo($curl);
	        curl_close($curl);

	        $return_data = json_decode($data);

	        if(isset($return_data->data) && count($return_data->data) > 0){
	        	foreach ($return_data as $key => $value) {
		        	if($key == 'data'){
		        		$return_data = $value;
		        	}
		        }
	        }
		}
		return $return_data;
	}
	
	public function getSales($date = null, Request $request) {

		if($date === null || empty($date)){
			$date = date('Y-m-d');
		}
// 		$store_id = $this->session->data['sid'];
		
		$store_id = $request->session()->get('sid');
		
		//EST time Zone
      	date_default_timezone_set('US/Eastern');
		
		$fdate = date("Y-m-d", (strtotime($date)) - (7*24*60*60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24*60*60));
        
        $ydate=  date("m-d-Y", (strtotime($date)) - (24*60*60));

        $sdate=date("m-d-Y", (strtotime($date)) - (7*24*60*60));
        $date2 = date('m-d-Y');
        $edate=$date2.' 23:59:59';
        
		$return = array();
		
        // 		$sql="SELECT sum(nnettotal) AS total FROM trn_sales WHERE 
        //         vtrntype='Transaction' AND SID='".(int)$store_id."'
        //         and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
        //         where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$date2."')";
               
                
        //         $query1 = $this->db2->query($sql)->row;


        // dd("SELECT sum(nnettotal) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59') AND vtrntype='Transaction'");
		
		$today_query = "SELECT sum(nnettotal) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59') AND vtrntype='Transaction'";
 		$query1 = DB::connection('mysql_dynamic')->select($today_query);

		if($query1[0]->total > 0){
			$return['today'] = $query1[0]->total;
		}else{
			$return['today'] = 0;
		}
     

        $sql="SELECT sum(nnettotal) AS total FROM trn_sales WHERE 
                vtrntype='Transaction' AND SID='".(int)$store_id."'
                and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
                where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$ydate."')";
       
        
        
        //old query without EOD
         
// 		$sql = "SELECT sum(nnettotal) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$tdate." 00:00:00' AND '".$tdate." 23:59:59') AND vtrntype='Transaction'";

        $query2 = DB::connection('mysql_dynamic')->select($sql);


		if($query2[0]->total > 0){
			$return['yesterday'] = $query2[0]->total;
		}else{
			$return['yesterday'] = 0;
		}

		$fdate1 = $fdate.' 00:00:00';
		$date1 = $date.' 23:59:59';
		
		
         $sql="SELECT sum(nnettotal) AS total FROM trn_sales WHERE 

        vtrntype='Transaction' AND SID='".(int)$store_id."'
        and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
        where e.dstartdatetime  BETWEEN '".$fdate1."' AND '".$date1."')";
        // echo  $sql;die;
        
        $query3 = DB::connection('mysql_dynamic')->select($sql);


    	//	$query3 = $this->db2->query("SELECT sum(nnettotal) AS total FROM trn_sales WHERE date_format(dtrandate,'%Y-%m-%d %H:%i:%s') >= '".$fdate1."' and date_format(dtrandate,'%Y-%m-%d %H:%i:%s') <= '".$date1."' AND vtrntype='Transaction' AND SID='".(int)$store_id."'")->row;

		if($query3[0]->total > 0){
			$return['week'] = $query3[0]->total;
		}else{
			$return['week'] = 0;
		}
		

		return $return;
	}
	
	public function getCustomers($date = null, $request) {

		if(empty($date)){
			$date = date('Y-m-d');
		}
// 		$store_id = $this->session->data['sid'];

        $store_id = $request->session()->get('sid');
        
		$fdate = date("Y-m-d", (strtotime($date)) - (7*24*60*60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24*60*60));
        
        $ydate= date("m-d-Y", (strtotime($date)) - (24*60*60));
        $date2 = date('m-d-Y');

		$return = array();
		
    	   //	$sql="SELECT count(isalesid)AS total FROM trn_sales WHERE 
        //     vtrntype='Transaction' AND SID='".(int)$store_id."'
        //     and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
        //     where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$date2."')";
           
        //     $query1 = $this->db2->query($sql)->row;

		$query1 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59') AND SID='".(int)$store_id."'");

		if(isset($query1[0]) && $query1[0]->total > 0){
			$return['today'] = $query1[0]->total;
		}else{
			$return['today'] = 0;
		}

       	// $sql="SELECT count(isalesid)AS total FROM trn_sales WHERE 
        // vtrntype='Transaction' AND SID='".(int)$store_id."'
        // and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
        // where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$ydate."')";
       
        // $query2 = $this->db2->query($sql)->row;
        
        //echo "SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$tdate." 00:00:00' AND '".$tdate." 23:59:59') AND SID='".(int)$store_id."'";die;
	   $query2 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$tdate." 00:00:00' AND '".$tdate." 23:59:59') AND SID='".(int)$store_id."'");
		
		if(isset($query2[0]) && $query2[0]->total > 0){
			$return['yesterday'] = $query2[0]->total;
		}else{
			$return['yesterday'] = 0;
		}

		$fdate1 = $fdate.' 00:00:00';
		$date1 = $date.' 23:59:59';
		
		
// 		$sql="SELECT count(isalesid) AS total FROM trn_sales WHERE 
//         vtrntype='Transaction' AND SID='".(int)$store_id."'
//         and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
//         where e.dstartdatetime  BETWEEN '".$fdate1."' AND '".$date1."')";
        
        
//         $query3 = $this->db2->query($sql)->row;

		$query3 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE date_format(dtrandate,'%Y-%m-%d %H:%i:%s') >= '".$fdate1."' and date_format(dtrandate,'%Y-%m-%d %H:%i:%s') <= '".$date1."' AND SID='".(int)$store_id."'");

		if(isset($query3[0]) && $query3[0]->total > 0){
			$return['week'] = $query3[0]->total;
		}else{
			$return['week'] = 0;
		}

		return $return;
	}
	
	public function getVoid($date = null, $request) {

		if(empty($date)){
			$date = date('Y-m-d');
		}
		
// 		$store_id = $this->session->data['sid'];
		$store_id = $request->session()->get('sid');
		
		$fdate = date("Y-m-d", (strtotime($date)) - (7*24*60*60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24*60*60));
        
        $ydate= date("m-d-Y", (strtotime($date)) - (24*60*60));
        $date2 = date('m-d-Y');

		$return = array();
		
// 		$sql="SELECT count(isalesid)AS total FROM trn_sales WHERE 
//         vtrntype='Void' AND SID='".(int)$store_id."'
//         and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
//         where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$date2."')";
       
//         $query1 = $this->db2->query($sql)->row;

		$query1 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59') AND vtrntype='Void'");

		if(isset($query1[0]) && $query1[0]->total > 0){
			$return['today'] = $query1[0]->total;
		}else{
			$return['today'] = 0;
		}


        // $sql="SELECT count(isalesid)AS total FROM trn_sales WHERE 
        // vtrntype='Void' AND SID='".(int)$store_id."'
        // and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
        // where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$ydate."')";
       
        // $query2 = $this->db2->query($sql)->row;
        
		$query2 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$tdate." 00:00:00' AND '".$tdate." 23:59:59') AND vtrntype='Void'");

	
		if(isset($query2[0]) && $query2[0]->total > 0){
			$return['yesterday'] = $query2[0]->total;
		}else{
			$return['yesterday'] = 0;
		}

		$fdate1 = $fdate.' 00:00:00';
		$date1 = $date.' 23:59:59';

        // $sql="SELECT count(isalesid) AS total FROM trn_sales WHERE 
        // vtrntype='Void' AND SID='".(int)$store_id."'
        // and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
        // where e.dstartdatetime  BETWEEN '".$fdate1."' AND '".$date1."')";
        
        // $query3 = $this->db2->query($sql)->row;
        
		$query3 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE date_format(dtrandate,'%Y-%m-%d %H:%i:%s') >= '".$fdate1."' and date_format(dtrandate,'%Y-%m-%d %H:%i:%s') <= '".$date1."' AND vtrntype='Void'");

		if(isset($query3[0]) && $query3[0]->total > 0){
			$return['week'] = $query3[0]->total;
		}else{
			$return['week'] = 0;
		}
		
		return $return;
	}

    public function dashboard_quick_links(Request $request){
        
        $data = [];
        
        $data['webadmin'] = $request->session()->get('webadmin') == 1?true:false;
        $data['plcb_reports_check'] = $request->session()->get('plcb_report') == 'Y'?true:false;
        
        return view('layouts.dashboard_quick_links', compact('data'));
    }
}
