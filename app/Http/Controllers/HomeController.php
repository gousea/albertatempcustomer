<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

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
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone', 'stores.state', 'stores.city', 'stores.zip', 'stores.hq_sid')
                    ->distinct('stores.id')
                    ->get();
            } elseif (Auth::user()->sid == 0) {
                $data = DB::table('store_mw_users')
                    ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                    ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone', 'stores.state', 'stores.city', 'stores.zip', 'stores.hq_sid')
                    ->where('store_mw_users.iuserid', '=', Auth::user()->iuserid)
                    ->distinct('stores.id')
                    ->get();
            } else {
                $data = DB::table('store_mw_users')
                    ->join('stores', 'store_mw_users.sid', '=', 'stores.id')
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone', 'stores.state', 'stores.city', 'stores.zip', 'stores.hq_sid')
                    ->where([['store_mw_users.iuserid', '=', Auth::user()->iuserid], ['store_mw_users.vemail', '=', Auth::user()->vemail]])
                    ->distinct('stores.id')
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

            if (Auth::user()->user_role && Auth::user()->sid == 0) {
                $user_id = Auth::user()->iuserid;
                $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' ");
            } else {
                $user_id = Auth::user()->iuserid;
                $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = " . $user_id);
            }
            $permsData = array();
            for ($i = 0; $i < count($userPermsData); $i++) {
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
            session()->put('city', $data[0]->city);
            session()->put('state', $data[0]->state);
            session()->put('zip', $data[0]->zip);

            // added for HQ
            session()->put('hq_sid', $data[0]->hq_sid);

            //for the version setting into session
            $version_latest = DB::connection('mysql_dynamic')->select("SELECT ver_id FROM mst_version ORDER BY ver_id DESC LIMIT 1 ");
            $version_db = $version_latest[0];
            session()->put('version',  $version_db->ver_id);
            //==end of version  code

            // list of stores for HQ
            if ($data[0]->hq_sid == 1) {
                $stores_hq = DB::table('stores')
                    ->where('hq_sid', '=', $data[0]->id)
                    ->distinct('id')
                    ->get();


                session()->put('stores_hq', $stores_hq);
            }

            //========check store version =========

            $check_table_query = "SELECT * FROM information_schema.tables WHERE table_schema = '{$data[0]->db_name}' AND table_name = 'mst_version' LIMIT 1";

            $check_table = DB::connection('mysql_dynamic')->select($check_table_query);
            //dd($check_table);

            if (count($check_table) === 0) {

                session()->put('new_database', false);
            } else {

                $result = DB::connection('mysql_dynamic')->table('mst_version')->orderBy('LastUpdate', 'desc')->first();

                if ($result->ver_no >= 3) {
                    session()->put('new_database', true);
                } else {
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
                ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone', 'stores.state', 'stores.city', 'stores.zip', 'stores.hq_sid')
                ->distinct('stores.id')
                ->get();
        } elseif (Auth::user()->sid == 0) {
            $data = DB::table('store_mw_users')
                ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone', 'stores.state', 'stores.city', 'stores.zip', 'stores.hq_sid')
                ->where('store_mw_users.iuserid', '=', Auth::user()->iuserid)
                ->distinct('stores.id')
                ->get();
        } else {
            $data = DB::table('store_mw_users')
                ->join('stores', 'store_mw_users.sid', '=', 'stores.id')
                ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname', 'stores.webadmin', 'stores.plcb_report', 'stores.address', 'stores.phone', 'stores.state', 'stores.city', 'stores.zip', 'stores.hq_sid')
                ->where([['store_mw_users.iuserid', '=', Auth::user()->iuserid], ['store_mw_users.vemail', '=', Auth::user()->vemail]])
                ->distinct('stores.id')
                ->get();
        }

        for ($i = 0; $i < count($data); $i++) {

            if ((int) $data[$i]->id == $sid) {
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


                if (Auth::user()->user_role != "SuperAdmin") {
                    $table_exists = DB::connection('mysql')->select("SELECT * FROM information_schema.tables WHERE table_schema = '" . $data[$i]->db_name . "'  AND table_name = 'mst_permission'");
                    $check_mst_userpermission = DB::connection('mysql')->select("SELECT * FROM information_schema.tables WHERE table_schema = '" . $data[$i]->db_name . "'  AND table_name = 'mst_userpermissions'");
                    if (count($table_exists) > 0 && count($check_mst_userpermission) > 0) {
                        if (Auth::user()->user_role == "StoreAdmin" && Auth::user()->sid == 0) {
                            $user_id = Auth::user()->iuserid;
                            $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM " . $data[$i]->db_name . ".mst_permission mp join " . $data[$i]->db_name . ".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active'  and mup.userid = '" . $user_id . "' ");
                            if (count($userPermsData) <= 0) {
                                // dd(__LINE__);
                                $permissions = DB::connection('mysql')->select("SELECT  distinct(vpermissioncode) FROM " . $data[$i]->db_name . ".mst_permission where vpermissiontype = 'WEB' ");
                                foreach ($permissions as $permission) {
                                    $user_perms_insert = "INSERT INTO " . $data[$i]->db_name . ".mst_userpermissions(userid, permission_id, status, created_id, updated_id) values( '" . Auth::user()->iuserid . "', '" . $permission->vpermissioncode . "', 'Active', '" . Auth::user()->iuserid . "', '" . Auth::user()->iuserid . "' )";
                                    $insert = DB::connection('mysql')->statement($user_perms_insert);
                                }
                                $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM " . $data[$i]->db_name . ".mst_permission mp join " . $data[$i]->db_name . ".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '" . $user_id . "' ");
                            } else {
                                $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM " . $data[$i]->db_name . ".mst_permission mp join " . $data[$i]->db_name . ".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '" . $user_id . "' ");
                            }
                        } else {
                            $user_id = Auth::user()->iuserid;
                            $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM " . $data[$i]->db_name . ".mst_permission mp join " . $data[$i]->db_name . ".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '" . $user_id . "' ");
                        }
                        $permsData = array();
                        for ($j = 0; $j < count($userPermsData); $j++) {
                            $permsData[] = $userPermsData[$j]->vpermissioncode;
                        }
                        session()->put('userPermsData', $permsData);
                    } else {
                        if (Auth::user()->sid == 0) {
                            $this->DatabaseAction($data[$i]->db_name, $data[$i]->id);
                            if (Auth::user()->user_role == "StoreAdmin") {
                                $user_id = Auth::user()->iuserid;
                                $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM " . $data[$i]->db_name . ".mst_permission mp join " . $data[$i]->db_name . ".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '" . $user_id . "' ");
                            } else {
                                $user_id = Auth::user()->iuserid;
                                $userPermsData = DB::connection('mysql')->select("SELECT  distinct(mp.vpermissioncode) FROM " . $data[$i]->db_name . ".mst_permission mp join " . $data[$i]->db_name . ".mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = '" . $user_id . "' ");
                            }
                            $permsData = array();
                            for ($k = 0; $k < count($userPermsData); $k++) {
                                $permsData[] = $userPermsData[$k]->vpermissioncode;
                            }
                            session()->put('userPermsData', $permsData);
                        } else {
                            Auth::logout();
                            Session::flush();
                            session()->put('error',  "You don't have any permissions for this Store. Please Contact Store Admin.");
                            return redirect('/');
                        }
                    }
                } else {
                    $table_exists = DB::connection('mysql')->select("SELECT * FROM information_schema.tables WHERE table_schema = '" . $data[$i]->db_name . "'  AND table_name = 'mst_permission'");
                    if (count($table_exists) > 0) {
                        $user_id = Auth::user()->iuserid;
                        $userPermsData = DB::connection('mysql')->select("SELECT  distinct(vpermissioncode) FROM " . $data[$i]->db_name . ".mst_permission where vpermissiontype = 'WEB'  ");
                        $permsData = array();
                        for ($l = 0; $l < count($userPermsData); $l++) {
                            $permsData[] = $userPermsData[$l]->vpermissioncode;
                        }
                        session()->put('userPermsData', $permsData);
                    } else {
                        $this->DatabaseActionSuperAdmin($data[$i]->db_name, $data[$i]->id);
                        $user_id = Auth::user()->iuserid;
                        $userPermsData = DB::connection('mysql')->select("SELECT  distinct(vpermissioncode) FROM " . $data[$i]->db_name . ".mst_permission where vpermissiontype = 'WEB'  ");

                        $permsData = array();
                        for ($m = 0; $m < count($userPermsData); $m++) {
                            $permsData[] = $userPermsData[$m]->vpermissioncode;
                        }
                        session()->put('userPermsData', $permsData);
                    }
                }

                session()->put('dbhost',  $data[$i]->db_hostname);
                session()->put('dbname',  $data[$i]->db_name);
                session()->put('dbuser',  $data[$i]->db_username);
                session()->put('dbpassword',  $data[$i]->db_password);

                session()->put('sid', $data[$i]->id);
                session()->put('storeName', $data[$i]->name);
                session()->put('webadmin', $data[$i]->webadmin);
                session()->put('plcb_report', $data[$i]->plcb_report);

                // dd($data[$i]);
                session()->put('address', $data[$i]->address);
                session()->put('phone', $data[$i]->phone);
                session()->put('city', $data[$i]->city);
                session()->put('state', $data[$i]->state);
                session()->put('zip', $data[$i]->zip);


                // added for HQ
                session()->put('hq_sid', $data[$i]->hq_sid);

                //for the version setting into session
                $version_latest = DB::connection('mysql')->select("SELECT ver_id FROM " . $data[$i]->db_name . ".mst_version ORDER BY ver_id DESC LIMIT 1 ");
                $version_db = $version_latest[0];
                session()->put('version',  $version_db->ver_id);
                //====end of version code

                // added to get list HQ Stores
                if ($data[$i]->hq_sid == 1) {
                    $stores_hq = DB::table('stores')
                        ->where('hq_sid', '=', $data[$i]->id)
                        ->distinct('id')
                        ->get();

                    session()->put('stores_hq', $stores_hq);
                }
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


    private function get_dashboard_data($request, $store_id)
    {
        // dd("adfasdf");
        // dd(date('Y-m-d H:i:s'));
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
        $url = $api . '/authenticate_new?email=' . $username . '&password=' . urlencode($pass);
        // dd($url);
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_POST => 1
            )
        );
        $data_curl = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        $data_curl = json_decode($data_curl);


        // dd($data_curl);

        if (isset($data_curl->error)) {
            $output['sales']['today'] = 0;
            $output['sales']['yesterday'] = 0;
            $output['sales']['week'] = 0;

            $output['customers']['today'] = 0;
            $output['customers']['yesterday'] = 0;
            $output['customers']['week'] = 0;

            $output['void']['today'] = 0;
            $output['void']['yesterday'] = 0;
            $output['void']['week'] = 0;

            // $output['total_item']['item'] = 0;

            // $output['news_update'] = 0;

            $select_query = "SELECT * FROM inslocdb.news_update ORDER BY news_sequence";
            $output['news_update'] = DB::connection()->select($select_query);

            $sid = $request->session()->get('sid');
            $trn_sales_query = "SELECT isalesid AS transaction_id, dtrandate as sales_timestamp, ntaxabletotal as sales_amount, vtendertype as tender_type FROM u" . $sid . ".trn_sales limit 100";
            $output['trn_sales_data'] = DB::connection()->select($trn_sales_query);


            $mst_item_query = "SELECT count(iitemid) as totalitem FROM u" . $sid . ".mst_item";
            $total_item = DB::connection()->select($mst_item_query);

            $output['total_item'] = $total_item[0];


            $output['date'] = $fdate;

            $output['sevendaysales'] = [];

            $output['sevendaysCustomer'] = [];

            $output['dailySummary'] = [];

            $output['topItem'] = [];

            $output['topCategory'] = [];

            $output['customer'] = [];

            // $output['total_item'] = [];
        } else {
            $token = $data_curl->token;

            //EST time Zone
            date_default_timezone_set('US/Eastern');

            // $store_id = $data[0]->id;

            // sales, customer, void
            $output['sales'] = $this->getSales($date, $request);
            $output['customers'] = $this->getCustomers($date, $request);
            $output['void'] = $this->getVoid($date, $request);

            // dd($output);
            //seven day sales
            $url_7daysales = $api . '/api/admin/7daysSales?fdate=' . $fdate . '&tdate=' . $tdate . '&token=' . $token . '&sid=' . $store_id;
            $output['sevendaysales'] = $this->getchartsValues($url_7daysales);

            // seven day customer
            $url_7daysCustomer = $api . '/api/admin/7daysCustomer?fdate=' . $fdate . '&tdate=' . $tdate . '&token=' . $token . '&sid=' . $store_id;
            $output['sevendaysCustomer'] = $this->getchartsValues($url_7daysCustomer);

            // sales summary
            $url_dailySummary = $api . '/api/admin/dailySummary?fdate=' . $date . '&tdate=' . $date . '&token=' . $token . '&sid=' . $store_id;

            $daily_summary = $this->getchartsValues($url_dailySummary);

            $output['dailySummary'] = isset($daily_summary[0]) ? $daily_summary[0] : [];

            // top category
            $url_topCategory = $api . '/api/admin/topCategory?fdate=' . $fdate . '&tdate=' . $tdate . '&token=' . $token . '&sid=' . $store_id;
            $output['topCategory'] = $this->getchartsValues($url_topCategory);

            //top product / item
            $url_topItem = $api . '/api/admin/topItem?fdate=' . $fdate . '&tdate=' . $tdate . '&token=' . $token . '&sid=' . $store_id;
            $output['topItem'] = $this->getchartsValues($url_topItem);

            // 24 hours customer
            $url_customer = $api . '/api/admin/customer?fdate=' . $fdate . '&tdate=' . $tdate . '&token=' . $token . '&sid=' . $store_id;
            $output['customer'] = $this->getchartsValues($url_customer);

            $select_query = "SELECT * FROM inslocdb.news_update ORDER BY news_sequence";
            $output['news_update'] = DB::connection()->select($select_query);

            $sid = $request->session()->get('sid');
            // $trn_sales_query = "SELECT isalesid AS transaction_id, dtrandate as sales_timestamp, ntaxabletotal, nnettotal as sales_amount, vtendertype as tender_type, isalesid, dtrandate FROM u" . $sid . ".trn_sales ORDER BY dtrandate DESC limit 1000";
            $trn_sales_query = "SELECT isalesid AS transaction_id, dtrandate as sales_timestamp, ntaxabletotal, nnettotal as sales_amount, vtendertype as tender_type, isalesid, dtrandate FROM u" . $sid . ".trn_sales where nnettotal > 0.00 ORDER BY dtrandate DESC limit 1000";
            $output['trn_sales_data'] = DB::connection()->select($trn_sales_query);

            $mst_item_query = "SELECT count(*) as totalitem from u" . $sid . ".mst_item where dcreated >= DATE_ADD(current_date(), INTERVAL -6 DAY)";
            // $mst_item_query = "SELECT count(iitemid) as totalitem FROM u".$sid.".mst_item";
            $total_item = DB::connection()->select($mst_item_query);
            // dd($total_item);
            $output['total_item'] = $total_item[0];
            $output['date'] = $date;
        }
        return $output;
    }


    public function getsaleid(Request $request)
    {

        ini_set('max_execution_time', -1);

        $selectvalue = session()->get('session_selectvalue');
        $amounttype = session()->get('session_amounttype');
        $amount = session()->get('session_amount');
        $input = $request->all();

        $salesid = $input['salesid'];
        $Reports = new Reports;
        $sales_header_data = $Reports->getSalesById($salesid);
        $sales_header = (array)$sales_header_data[0];


        $trn_date = DateTime::createFromFormat('m-d-Y h:i A', $sales_header['trandate']);
        $trn_date = $trn_date->format('m-d-Y');

        $sales_detail_data = $Reports->getSalesPerview($salesid);
        $sales_detail = $sales_detail_data;

        $sales_tender_data = $Reports->getSalesByTender($salesid);
        $sales_tender = $sales_tender_data;

        $sales_customer_data = $Reports->getSalesByCustomer($sales_header['icustomerid']);
        $sales_customer = (array)$sales_customer_data;

        $store_info_data = $Reports->getStore();
        $store_info = (array)$store_info_data[0];


        if (count($sales_customer) > 0) {
            // $c_name = $sales_customer['vfname'] . ' ' . $sales_customer['vlname'];
            // $c_address = $sales_customer['vaddress1'];
            // $c_city = $sales_customer['vcity'] . ',';
            // $c_state = $sales_customer['vstate'];
            // $c_zip = $sales_customer['vzip'];
            // $c_phone = 'Phone: ' . $sales_customer['vphone'];
            if(empty($sales_customer['vfname'])){
                $c_name = '';
            }else{
                $c_name = $sales_customer['vfname'] . ' ' . $sales_customer['vlname'];
            }
            
            if(empty($sales_customer['vaddress1'])){
                $c_address = '';
            }else{
                $c_address = $sales_customer['vaddress1'];
            }
            
            if(empty($sales_customer['vcity'])){
                $c_city = '';
            }else{
                $c_city = $sales_customer['vcity'] . ',';
            }
            
            if(empty($sales_customer['vstate'])){
                $c_state = '';
            }else{
                $c_state = $sales_customer['vstate'];
            }
            
            if(empty($sales_customer['vzip'])){
                $c_zip = '';
            }else{
                $c_zip = $sales_customer['vzip'];
            }
            
            if(empty($sales_customer['vphone'])){
                $c_phone = '';
            }else{
                $c_phone = 'Phone: ' . $sales_customer['vphone'];
            }
        } else {
            $c_name = '';
            $c_address = '';
            $c_city = '';
            $c_state = '';
            $c_zip = '';
            $c_phone = '';
        }

        $html = '';

        $html = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <table width="100%" border="0" cellspacing="5" cellpadding="0" style="margin-bottom:15px;">
                        <tr>
                        <!-- <p style="text-align:left"><img src="image/logoreport.jpg"  alt="Pos logo" width="120" height="50"></p>-->
                              <td align="center">
                                <h3>Sales Transaction</h3>
                              </td>
                      </tr>
                    </table>
                </tr>
              <tr style="border-bottom:1px solid #999;">
                <td valign="top">
                    <table width="100%" border="0" cellspacing="5" cellpadding="0" style="border-bottom:1px solid #999;">
                      <tr style="line-height:25px;">
                          <td width="50%" align="left">
                          <strong>Date</strong> ' . date('m-d-Y') . '
                          </td>
                          <td width="25%" align="right">
                              <strong>Order Number</strong>
                          </td>
                          <td>&nbsp;&nbsp;' . $sales_header['isalesid'] . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong>Store Name: </strong>' . $store_info['vstorename'] . '</td>
                        <td width="25%" align="right"><strong>Status</strong></td>
                        <td>&nbsp;&nbsp;' . $sales_header['estatus'] . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong>Store Address: </strong>' . $store_info['vaddress1'] . '</td>
                        <td width="25%" align="right"><strong>Ordered</strong></td>
                        <td>&nbsp;&nbsp;' . $trn_date . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ' . $store_info['vcity'] . " " . $store_info['vstate'] . " " . $store_info['vzip'] . '
                        </td>
                        <td width="25%" align="right"><strong>Invoiced</strong></td>
                        <td>&nbsp;&nbsp;' . $trn_date . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong>Store Phone: </strong></td>
                        <td width="25%" align="right"><strong>Sale Number</strong></td>
                        <td>&nbsp;&nbsp;' . $sales_header['isalesid'] . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>Sales Person</strong></td>
                        <td>&nbsp;&nbsp;' . $sales_header['vusername'] . ' (' . $sales_header['iuserid'] . ')</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>Tender Type</strong></td>
                        <td>&nbsp;&nbsp;' . $sales_header['vtendertype'] . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>TRN</strong></td>
                        <td>&nbsp;&nbsp;' . $sales_header['isalesid'] . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>TRN Time</strong></td>
                        <td>&nbsp;&nbsp;' . $sales_header['trandate'] . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>Register</strong></td>
                        <td>&nbsp;&nbsp;' . $sales_header['vterminalid'] . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong>Bill</strong>&nbsp;</td>
                        <td width="25%" align="right"><strong>Ship To</strong></td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong></strong>&nbsp;</td>
                        <td width="25%" align="right"><strong>Check No</strong></td>
                        <td>&nbsp;&nbsp;' . $sales_header['checkno'] . '</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">' . $c_name . '</td>
                        <td width="25%" align="right">&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">' . $c_address . '</td>
                        <td width="25%" align="right">&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">' . $c_city . ' ' . $c_state . ' ' . $c_zip . '</td>
                        <td width="25%" align="right">&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">' . $c_phone . '</td>
                        <td width="25%" align="right">&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                    </table>
                </td>
              </tr>
              <tr style="border-bottom:1px solid #999;">
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="margin:15px 0px;">';
        if (count($sales_detail) > 0) {
            $sub_total = 0;
            $tax = 0;
            $total = 0;
            $noofitems = 0;
            $html .= '<tr>';
            $html .= '<th>Qty</th>';
            $html .= '<th>Pack</th>';
            $html .= '<th>SKU</th>';
            $html .= '<th>Item Name</th>';
            $html .= '<th>Size</th>';
            $html .= '<th>Unit Price</th>';
            $html .= '<th>Total Price</th>';
            $html .= '</tr>';

            foreach ($sales_detail as $sales) {
                $sub_total += $sales->nextunitprice;
                $noofitems += $sales->ndebitqty;

                if (isset($selectvalue) && $selectvalue == 'Price') {
                    if ($amounttype == 'less' && $sales->nunitprice < $amount) {

                        $html .= '

                        <tr>
                            <td><b>' . $sales->ndebitqty . '</b></td>
                            <td><b>' . $sales->npack . '</b></td>
                            <td><b>' . $sales->vitemcode . '</b></td>
                            <td><b>' . $sales->vitemname . '</b></td>
                            <td><b>' . $sales->vsize . '</b></td>
                            <td><b>' . $sales->nunitprice . '</b></td>
                            <td><b>' . $sales->nextunitprice . '</b></td>
                        </tr>';
                    } elseif ($amounttype == 'greater' && $sales->nunitprice > $amount) {

                        $html .= '

                        <tr>
                            <td><b>' . $sales->ndebitqty . '</b></td>
                            <td><b>' . $sales->npack . '</b></td>
                            <td><b>' . $sales->vitemcode . '</b></td>
                            <td><b>' . $sales->vitemname . '</b></td>
                            <td><b>' . $sales->vsize . '</b></td>
                            <td><b>' . $sales->nunitprice . '</b></td>
                            <td><b>' . $sales->nextunitprice . '</b></td>
                        </tr>';
                    } elseif ($amounttype == 'equal' && $sales->nunitprice == $amount) {

                        $html .= '

                        <tr>
                            <td><b>' . $sales->ndebitqty . '</b></td>
                            <td><b>' . $sales->npack . '</b></td>
                            <td><b>' . $sales->vitemcode . '</b></td>
                            <td><b>' . $sales->vitemname . '</b></td>
                            <td><b>' . $sales->vsize . '</b></td>
                            <td><b>' . $sales->nunitprice . '</b></td>
                            <td><b>' . $sales->nextunitprice . '</b></td>
                        </tr>';
                    } else {
                        $html .= '

                        <tr>
                            <td>' . $sales->ndebitqty . '</td>
                            <td>' . $sales->npack . '</td>
                            <td>' . $sales->vitemcode . '</td>
                            <td>' . $sales->vitemname . '</td>
                            <td>' . $sales->vsize . '</td>
                            <td>' . $sales->nunitprice . '</td>
                            <td>' . $sales->nextunitprice . '</td>
                        </tr>';
                    }
                } else {
                    $html .= '

                        <tr>
                            <td>' . $sales->ndebitqty . '</td>
                            <td>' . $sales->npack . '</td>
                            <td>' . $sales->vitemcode . '</td>
                            <td>' . $sales->vitemname . '</td>
                            <td>' . $sales->vsize . '</td>
                            <td>' . $sales->nunitprice . '</td>
                            <td>' . $sales->nextunitprice . '</td>
                        </tr>';
                }
            }
            $html .= '</table></td>
              </tr><hr style="border-top:1px solid #999;">
              <tr style="border-bottom:1px solid #999;">
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:5px 0px;">
                  <tr>
                    <td width="70%" align="right" style="padding-bottom:5px;"><strong>Sub Total</strong></td>
                    <td width="10%" style="padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-bottom:5px;">' . $sub_total . '</td>
                  </tr>
                  <tr style="border-top:1px solid #999;">
                    <td width="70%" align="right" style="padding-top:5px;padding-bottom:5px;"><strong>Tax</strong></td>
                    <td width="10%" style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-top:5px;padding-bottom:5px;">' . $sales_header['ntaxtotal'] . '</td>
                  </tr>
                  <tr style="border-top:1px solid #999;border-bottom:1px solid #999;">
                    <td width="70%" align="right" style="padding-top:5px;padding-bottom:5px;"><strong>Total</strong></td>
                    <td width="10%" style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-top:5px;padding-bottom:5px;">';
            $total = $sub_total + $sales_header['ntaxtotal'];
            $html .= $total . '</td>
                  </tr>
                </table></td>
              </tr>
              <tr style="border-top:1px solid #999;border-bottom:1px solid #999;">
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:5px 0px;">';

            foreach ($sales_tender as $tender) {
                if ($tender->itenerid != "121") {
                    $html .= '<tr>
                            <td width="70%" align="right"><strong>' . $tender->vtendername . '</strong></td>
                            <td width="10%" align="right">&nbsp;</td>
                            <td width="20%" align="right">' . $tender->namount . '</td>
                          </tr>';
                }
            }

            $html .= '</table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr style="border-top:1px solid #999;border-bottom:1px solid #999;">
                    <td width="70%" align="right" style="padding-top:5px;padding-bottom:5px;"><strong>Tendered</strong></td>
                    <td width="10%" align="right" style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-top:5px;padding-bottom:5px;">' . $total . '</td>
                  </tr>
                  <tr style="border-bottom:1px solid #999;">
                    <td width="70%" align="right" style="padding-top:5px;padding-bottom:5px;"><strong>Your Change</strong></td>
                    <td width="10%" align="right" style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-top:5px;padding-bottom:5px;">' . $sales_header['nchange'] . '</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:5px 0px;display:none;">
                  <tr>
                    <td width="49%" align="left"><strong>Cashier ID : </strong>' . $sales_header['iuserid'] . '</td>
                    <td width="8%">&nbsp;</td>
                    <td width="43%" align="right"><strong>Register : </strong>' . $sales_header['vterminalid'] . '</td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Tender Type : </strong>' . $sales_header['vtendertype'] . '</td>
                    <td>&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left"><strong>TRN : </strong>' . $sales_header['isalesid'] . '</td>
                    <td>&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left"><strong>TRN Time : </strong>' . $sales_header['trandate'] . '</td>
                    <td>&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              ';
            if (strlen($sales_header['licnumber']) > 0) {
                $html .= '
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:5px 0px;">
                          <tr style="border-top:1px solid #999;border-bottom:1px solid #999;line-height:30px;">
                            <td align="center"><strong>Customer Licence Detail</strong></td>
                          </tr>
                          <tr>
                            <td><strong>Name :</strong> ' . $sales_header['liccustomername'] . '</td>
                          </tr>
                          <tr>
                            <td><strong>Birth Date : </strong>' . $sales_header['liccustomerbirthdate'] . '</td>
                          </tr>
                          <tr>
                            <td><strong>Address : </strong>' . $sales_header['licaddress'] . '</td>
                          </tr>
                          <tr>
                            <td><strong>Licence # : </strong>' . $sales_header['licnumber'] . '</td>
                          </tr>
                          <tr>
                            <td><strong>Licence Expiry Date : </strong>' . $sales_header['licexpireddate'] . '</td>
                          </tr>
                        </table></td>
                  </tr>';
            }
            $html .= '
            </table>';

            $file = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><div id="content">
            <div class="container-fluid">
                <div class="" style="margin-top:0%;">
                <div class="panel-body">
                    <div class="row">
                    <div class="col-md-12" id="printappend">' . $html . '
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>';


            $myfile = fopen(resource_path('views/sale.blade.php'), "w");

            fwrite($myfile, $file);
            fclose($myfile);

            $return['code'] = 1;
            $return['data'] = $html;

            echo json_encode($return);
            exit;
        }
    }

    public function getchartsValues($url)
    {
        $return_data = array();
        if (!empty($url)) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url
            ));
            $data = curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);

            $return_data = json_decode($data);

            if (isset($return_data->data) && count($return_data->data) > 0) {
                foreach ($return_data as $key => $value) {
                    if ($key == 'data') {
                        $return_data = $value;
                    }
                }
            }
        }
        return $return_data;
    }

    public function getSales($date = null, Request $request)
    {

        if ($date === null || empty($date)) {
            $date = date('Y-m-d');
        }
        // 		$store_id = $this->session->data['sid'];

        $store_id = $request->session()->get('sid');

        //EST time Zone
        date_default_timezone_set('US/Eastern');

        $fdate = date("Y-m-d", (strtotime($date)) - (7 * 24 * 60 * 60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24 * 60 * 60));

        $ydate =  date("m-d-Y", (strtotime($date)) - (24 * 60 * 60));

        $sdate = date("m-d-Y", (strtotime($date)) - (7 * 24 * 60 * 60));
        $date2 = date('m-d-Y');
        $edate = $date2 . ' 23:59:59';

        $return = array();

        // 		$sql="SELECT sum(nnettotal) AS total FROM trn_sales WHERE
        //         vtrntype='Transaction' AND SID='".(int)$store_id."'
        //         and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid
        //         where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$date2."')";


        //         $query1 = $this->db2->query($sql)->row;


        // dd("SELECT sum(nnettotal) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59') AND vtrntype='Transaction'");

        $today_query = "SELECT sum(nnettotal) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59') AND vtrntype='Transaction'";
        $query1 = DB::connection('mysql_dynamic')->select($today_query);

        if ($query1[0]->total > 0) {
            $return['today'] = $query1[0]->total;
        } else {
            $return['today'] = 0;
        }


        $sql = "SELECT sum(nnettotal) AS total FROM trn_sales WHERE
                vtrntype='Transaction' AND SID='" . (int)$store_id . "'
                and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid
                where date_format(e.dstartdatetime,'%m-%d-%Y') ='" . $ydate . "')";



        //old query without EOD

        // 		$sql = "SELECT sum(nnettotal) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$tdate." 00:00:00' AND '".$tdate." 23:59:59') AND vtrntype='Transaction'";

        $query2 = DB::connection('mysql_dynamic')->select($sql);


        if ($query2[0]->total > 0) {
            $return['yesterday'] = $query2[0]->total;
        } else {
            $return['yesterday'] = 0;
        }

        $fdate1 = $fdate . ' 00:00:00';
        $date1 = $date . ' 23:59:59';


        $sql = "SELECT sum(nnettotal) AS total FROM trn_sales WHERE

        vtrntype='Transaction' AND SID='" . (int)$store_id . "'
        and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid
        where e.dstartdatetime  BETWEEN '" . $fdate1 . "' AND '" . $date1 . "')";
        // echo  $sql;die;

        $query3 = DB::connection('mysql_dynamic')->select($sql);


        //	$query3 = $this->db2->query("SELECT sum(nnettotal) AS total FROM trn_sales WHERE date_format(dtrandate,'%Y-%m-%d %H:%i:%s') >= '".$fdate1."' and date_format(dtrandate,'%Y-%m-%d %H:%i:%s') <= '".$date1."' AND vtrntype='Transaction' AND SID='".(int)$store_id."'")->row;

        if ($query3[0]->total > 0) {
            $return['week'] = $query3[0]->total;
        } else {
            $return['week'] = 0;
        }


        return $return;
    }

    public function getCustomers($date = null, $request)
    {

        if (empty($date)) {
            $date = date('Y-m-d');
        }
        // 		$store_id = $this->session->data['sid'];

        $store_id = $request->session()->get('sid');

        $fdate = date("Y-m-d", (strtotime($date)) - (7 * 24 * 60 * 60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24 * 60 * 60));

        $ydate = date("m-d-Y", (strtotime($date)) - (24 * 60 * 60));
        $date2 = date('m-d-Y');

        $return = array();

        //	$sql="SELECT count(isalesid)AS total FROM trn_sales WHERE
        //     vtrntype='Transaction' AND SID='".(int)$store_id."'
        //     and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid
        //     where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$date2."')";

        //     $query1 = $this->db2->query($sql)->row;

        $query1 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59') AND SID='" . (int)$store_id . "'");

        if (isset($query1[0]) && $query1[0]->total > 0) {
            $return['today'] = $query1[0]->total;
        } else {
            $return['today'] = 0;
        }

        // $sql="SELECT count(isalesid)AS total FROM trn_sales WHERE
        // vtrntype='Transaction' AND SID='".(int)$store_id."'
        // and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid
        // where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$ydate."')";

        // $query2 = $this->db2->query($sql)->row;

        //echo "SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '".$tdate." 00:00:00' AND '".$tdate." 23:59:59') AND SID='".(int)$store_id."'";die;
        $query2 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $tdate . " 00:00:00' AND '" . $tdate . " 23:59:59') AND SID='" . (int)$store_id . "'");

        if (isset($query2[0]) && $query2[0]->total > 0) {
            $return['yesterday'] = $query2[0]->total;
        } else {
            $return['yesterday'] = 0;
        }

        $fdate1 = $fdate . ' 00:00:00';
        $date1 = $date . ' 23:59:59';


        // 		$sql="SELECT count(isalesid) AS total FROM trn_sales WHERE
        //         vtrntype='Transaction' AND SID='".(int)$store_id."'
        //         and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid
        //         where e.dstartdatetime  BETWEEN '".$fdate1."' AND '".$date1."')";


        //         $query3 = $this->db2->query($sql)->row;

        $query3 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE date_format(dtrandate,'%Y-%m-%d %H:%i:%s') >= '" . $fdate1 . "' and date_format(dtrandate,'%Y-%m-%d %H:%i:%s') <= '" . $date1 . "' AND SID='" . (int)$store_id . "'");

        if (isset($query3[0]) && $query3[0]->total > 0) {
            $return['week'] = $query3[0]->total;
        } else {
            $return['week'] = 0;
        }

        return $return;
    }

    public function getVoid($date = null, $request)
    {

        if (empty($date)) {
            $date = date('Y-m-d');
        }

        // 		$store_id = $this->session->data['sid'];
        $store_id = $request->session()->get('sid');

        $fdate = date("Y-m-d", (strtotime($date)) - (7 * 24 * 60 * 60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24 * 60 * 60));

        $ydate = date("m-d-Y", (strtotime($date)) - (24 * 60 * 60));
        $date2 = date('m-d-Y');

        $return = array();

        // 		$sql="SELECT count(isalesid)AS total FROM trn_sales WHERE
        //         vtrntype='Void' AND SID='".(int)$store_id."'
        //         and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid
        //         where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$date2."')";

        //         $query1 = $this->db2->query($sql)->row;

        $query1 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59') AND vtrntype='Void'");

        if (isset($query1[0]) && $query1[0]->total > 0) {
            $return['today'] = $query1[0]->total;
        } else {
            $return['today'] = 0;
        }


        // $sql="SELECT count(isalesid)AS total FROM trn_sales WHERE
        // vtrntype='Void' AND SID='".(int)$store_id."'
        // and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid
        // where date_format(e.dstartdatetime,'%m-%d-%Y') ='".$ydate."')";

        // $query2 = $this->db2->query($sql)->row;

        $query2 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $tdate . " 00:00:00' AND '" . $tdate . " 23:59:59') AND vtrntype='Void'");


        if (isset($query2[0]) && $query2[0]->total > 0) {
            $return['yesterday'] = $query2[0]->total;
        } else {
            $return['yesterday'] = 0;
        }

        $fdate1 = $fdate . ' 00:00:00';
        $date1 = $date . ' 23:59:59';

        // $sql="SELECT count(isalesid) AS total FROM trn_sales WHERE
        // vtrntype='Void' AND SID='".(int)$store_id."'
        // and ibatchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid
        // where e.dstartdatetime  BETWEEN '".$fdate1."' AND '".$date1."')";

        // $query3 = $this->db2->query($sql)->row;

        $query3 = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE date_format(dtrandate,'%Y-%m-%d %H:%i:%s') >= '" . $fdate1 . "' and date_format(dtrandate,'%Y-%m-%d %H:%i:%s') <= '" . $date1 . "' AND vtrntype='Void'");

        if (isset($query3[0]) && $query3[0]->total > 0) {
            $return['week'] = $query3[0]->total;
        } else {
            $return['week'] = 0;
        }

        return $return;
    }

    public function dashboard_quick_links(Request $request)
    {

        $data = [];

        $data['webadmin'] = $request->session()->get('webadmin') == 1 ? true : false;
        $data['plcb_reports_check'] = $request->session()->get('plcb_report') == 'Y' ? true : false;

        return view('layouts.dashboard_quick_links', compact('data'));
    }


    /*******************For Store Admin Inserting Function********************/
    public function DatabaseAction($sid, $store_id)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $sid . ".mst_permission (
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
                                      SID int(11) NOT NULL  DEFAULT " . $store_id . ",
                                      PRIMARY KEY (id)
                )";
        $sql_user_permissions = "CREATE TABLE IF NOT EXISTS " . $sid . ".mst_userpermissions (
              Id int(11) NOT NULL AUTO_INCREMENT,
              userid int(11) NOT NULL,
              permission_id varchar(255) NOT NULL,
              status enum('Active','Inactive') NOT NULL DEFAULT 'Active',
              created_id int(11) NOT NULL,
              created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              updated_id int(11) DEFAULT NULL,
              LastUpdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              SID int(11) NOT NULL DEFAULT " . $store_id . ",
              PRIMARY KEY (Id)
            )";
        $sql_excute = DB::connection('mysql')->statement($sql);
        $sql_user_permissions_excute = DB::connection('mysql')->statement($sql_user_permissions);

        $insert_data = "INSERT INTO " . $sid . ".mst_permission
                            (vpermissioncode, vpermissionname, vmenuname, vpermissiontype, vppercode, ivorder, vdesc, etransferstatus, LastUpdate, SID)
                        VALUES
                            ('PER1001','DASHBOARD','DASHBOARD','WEB','',1,'Dashborad ','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1002','VENDOR','VENDORS','WEB','',2,'Vendors','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1003','CUSTOMER','CUSTOMER','WEB','',1,'Customer','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1004','USERS','USER','WEB','',1,'USER','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1005','STORE','STORE','WEB','',1,'STORE','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1006','ITEMS','ITEM','WEB','',1,'Item','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1007','INVENTORY','INVENTORY','WEB','',1,'Inventory','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1008','ADMINISTRATION','ADMINISTRATION','WEB','',1,'Administration','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1009','REPORTS','REPORTS','WEB','',1,'Reports','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1010','SETTINGS','SETTINGS','WEB','',1,'Settings','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1011','LOYALITY','LOYALITY','WEB','',1,'Loyality','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1012','GENERAL','GENERAL','WEB','',1,'General','', CURRENT_TIMESTAMP, " . $store_id . ")";

        $insert_data_excute = DB::connection('mysql')->statement($insert_data);

        $permissions = DB::connection('mysql')->statement("SELECT  distinct(vpermissioncode) FROM " . $sid . ".mst_permission where vpermissiontype = 'WEB' ");

        foreach ($permissions as $permission) {
            $user_perms_insert = "INSERT INTO " . $sid . ".mst_userpermissions(userid, permission_id, status, created_id, updated_id) values('" . Auth::user()->iuserid . "', '" . $permission->vpermissioncode . "', 'Active', '" . Auth::user()->iuserid . "', '" . Auth::user()->iuserid . "' )";
            $insert = DB::connection('mysql')->statement($user_perms_insert);
        }
    }
    /*******************For Super Admin Inserting Function********************/
    public function DatabaseActionSuperAdmin($sid, $store_id)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $sid . ".mst_permission (
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
                                      SID int(11) NOT NULL  DEFAULT " . $store_id . ",
                                      PRIMARY KEY (id)
                )";

        $sql_excute = DB::connection('mysql')->statement($sql);

        $insert_data = "INSERT INTO " . $sid . ".mst_permission
                            (vpermissioncode, vpermissionname, vmenuname, vpermissiontype, vppercode, ivorder, vdesc, etransferstatus, LastUpdate, SID)
                        VALUES
                            ('15','REPORTS','Reports','POS_Item','',20,'Reports','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00071','NT_NOSALE','Item','POS_ACTION','',3,'No Sale','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00072','NT_PAIDOUT','Item','POS_ACTION','',13,'Paidout','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00073','NT_CASHPICKUP','Item','POS_ACTION','',14,'cash pickup','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00074','NT_DELETEITEM','User','POS_ACTION','',1,'Delete Item','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00075','NT_DISCOUNT','User','POS_ACTION','',4,'Discount','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00077','NT_PRICEMATCH','Group','POS_ACTION','',6,'Price Match','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00080','NT_SETTING','Group','POS_ACTION','',12,'Setting','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00081','NT_ZREPORT','Group','POS_ACTION','',11,'Z Report','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00083','NT_VOID','Group','POS_ACTION','',2,'Void Transaction','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00084','NT_HOLD','Group','POS_ACTION','',5,'Hold Transaction','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00086','NT_XREPORT','Group','POS_ACTION','',10,'X Report','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00087','NT_PRICECHECK','Group','POS_ACTION','',7,'Price Check','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1001','DASHBOARD','DASHBOARD','WEB','',1,'Dashborad ','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1002','VENDOR','VENDORS','WEB','',2,'Vendors','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1003','CUSTOMER','CUSTOMER','WEB','',1,'Customer','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1004','USERS','USER','WEB','',1,'USER','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1005','STORE','STORE','WEB','',1,'STORE','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1006','ITEMS','ITEM','WEB','',1,'Item','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1007','INVENTORY','INVENTORY','WEB','',1,'Inventory','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1008','ADMINISTRATION','ADMINISTRATION','WEB','',1,'Administration','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1009','REPORTS','REPORTS','WEB','',1,'Reports','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1010','SETTINGS','SETTINGS','WEB','',1,'Settings','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1011','LOYALITY','LOYALITY','WEB','',1,'Loyality','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER1012','GENERAL','GENERAL','WEB','',1,'General','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER2001','QUICK REPORT','QUICK REPORT','MOB','',3,'Quick Report','library-books', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER2002','REPORTS','REPORTS','MOB','#52c6d8, #45c0d4, #3',1,'Reports','library-books', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER2003','ADD/EDIT ITEM','ADD/EDIT ITEM','MOB','#f4d60c, #edc425, #e',2,'Add/Edit Item', 'library-books', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER2004','CHANGE PRICE','CHANGE PRICE','MOB','#f4d60c, #edc425, #e',2,'Change Price','library-books', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER2005','UPDATE QUANTITY','UPDATE QUANTITY','MOB','#f4d60c, #edc425, #e',2,'Update Quantity','library-books', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER2006','NOTIFICATIONS','NOTIFICATIONS','MOB','#f4d60c, #edc425, #e',1,'Notifications','library-books', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER2007','RECEIVE ORDER','RECEIVE ORDER','MOB','#52c6d8, #45c0d4, #3',1,'Receive Order','library-books', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER2008','PRINT LABEL','PRINT LABEL','MOB','#f58120, #f4771f, #f',1,'Print Label','library-books', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('5','CONV.CHARGE($)','CONV.CHARGE($)','POS_Item','',15,'Conv. Charge($)','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('20','ALL LOTERY FUNCTIONS','ALL LOTERY FUNCTIONS','POS_Item','',16,'All Lottery Functions','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('9','CREDIT PAYMENT','CREDIT PAYMENT','POS_Item','',17,'Credit Payment','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('1','BOTTLE DEPOSIT','BOTTLE DEPOSIT','POS_Item','',18,'Bottle Deposit','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('17','STORE SETTING','STORE SETTING','POS_Item','',23,'Store Setting','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00082','NT_RETURNITEM','Group','POS_ACTION','',2,'Return Item','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00085','SHOWADMIN','Show Admin','POS_ACTION','PER050',5,'Show admin','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('10','BOTTLE DEPOSIT REDEEM','BOTTLE DEPOSIT REDEE','POS_Item','',19,'Bottle Deposit Redeem','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('16','EDIT PERMISSIONS','EDIT PERMISSIONS','POS_Item','',21,'Edit Permissions','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('18','COUPON','COUPON','POS_Item','',22,'Coupon','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER00088','NT_OVERWRITE','Group','POS_ACTION','',12,'Age Check Overwrite','1|', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER3011','NT_PRICEUPDATE','PRICE UPDATE','POS_ACTION','',9,'Price Update','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER3012','NT_EDITITEM','EDIT ITEM','POS_ACTION','',8,'Edit Item','', CURRENT_TIMESTAMP, " . $store_id . "),
                            ('PER2009','PROMOTION','Promotion','MOB','',1,'Promotion','', CURRENT_TIMESTAMP, " . $store_id . ")";

        $insert_data_excute = DB::connection('mysql')->statement($insert_data);
    }
}