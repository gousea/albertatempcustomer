<?php

namespace App\Http\Controllers;

use App\Model\Menu;
use App\User as AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $date = date('Y-m-d');
        $fdate = date("Y-m-d", (strtotime($date)) - (7 * 24 * 60 * 60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24 * 60 * 60));

        $ydate = date("m-d-Y", (strtotime($date)) - (24 * 60 * 60));


        $sdate = date("m-d-Y", (strtotime($date)) - (7 * 24 * 60 * 60));
        $date2 = date('m-d-Y');
        $edate = $date2 . ' 23:59:59';
        // get data for sales
        $sales = Sales::whereBetween(DB::raw('DATE_FORMAT(dtrandate, "%Y-%m-%d %H:%i:%s")'), [$date . " 00:00:00", $date . " 23:59:59"])
            ->where('vtrntype', '=', 'Transaction')
            ->sum('nnettotal');

        if ($sales > 0) {
            $data['today'] = $sales;
        } else {
            $data['today'] = 0;
        }

        $salesYesterday  = DB::table('trn_sales')
            ->select(DB::raw('SUM(nnettotal) AS total'))
            ->where([['vtrntype', '=', 'Transaction'], ['sid', '=', '1001']])
            ->whereIn('ibatchid', function ($query, $ydate = "") {
                $query->select('trn_endofdaydetail.batchid')
                    ->from('trn_endofday')
                    ->join('trn_endofdaydetail', 'trn_endofday.id', '=', 'trn_endofdaydetail.eodid')
                    ->whereDate(DB::raw('DATE_FORMAT(trn_endofday.dstartdatetime, "%m-%d-%Y")'), '=', $ydate);
            })
            ->get();
        $yesterday = $salesYesterday[0]->total;

        if ($yesterday > 0) {
            $data['yesterday'] = $yesterday;
        } else {
            $data['yesterday'] = 0;
        }

        $fdate1 = $fdate . ' 00:00:00';
        $date1 = $date . ' 23:59:59';

        $salesWeekly = DB::table('trn_sales')
            ->select(DB::raw('SUM(nnettotal) AS total'))
            ->where([['vtrntype', '=', 'Transaction'], ['sid', '=', '1001']])
            ->whereIn('ibatchid', function ($query, $fdate1 = "",  $date1 = "") {
                $query->select('trn_endofdaydetail.batchid')
                    ->from('trn_endofday')
                    ->join('trn_endofdaydetail', 'trn_endofday.id', '=', 'trn_endofdaydetail.eodid')
                    ->whereBetween('trn_endofday.dstartdatetime', [$fdate1, $date1]);
            })
            ->get();
        $weekly = $salesWeekly[0]->total;
        if ($weekly > 0) {
            $data['weekly'] = $weekly;
        } else {
            $data['weekly'] = 0;
        }
        // get data for customer

        // get data for void

        return view('dashboard', compact('data'));
    }

    public function dashboard_layout()
    {
        $sid = "u" . session()->get('sid');
        $store_id = $sid;
        $session_logged_in_emailid = session()->get('loggedin_username');
        $user_data = AppUser::where([['vemail', '=', $session_logged_in_emailid]])->get();
        $iuserid = $user_data[0]->iuserid;

        // $selected_user_menu = "SELECT t1.usermenu_id, t1.status, t2.menu_name as menuname, t2.menu_des as menudes, t2.menu_link as menulink
        // FROM $sid.user_menu t1
        // INNER JOIN $sid.menu_table t2 ON t1.menu_id = t2.menu_id
        // WHERE t1.status = 'Active' AND t1.iuserid = '$iuserid'";

        $selected_user_menu = "select * from $sid.menu_table m left join $sid.user_menu u on m.menu_id = u.menu_id and iuserid = '$iuserid' and status = 'Active'";
        // die;
        $data = DB::connection('mysql')->select($selected_user_menu);

        // $datas = "select * from $sid.menu_table";
        // $data = DB::connection('mysql')->select($datas);
        // $data = array_merge($selected_user_id, $data1);
        // Menu::select()->get();
        // echo "<pre>";
        // print_r($selected_user_id);
        // die;
        return view('dashboard_layout', compact('data'));
    }

    public function dashboard_menulist(Request $request)
    {
        // ini_set('memory_limit', '-1');

        $input = $request->all();

        // echo "<pre>";
        // print_r($input);

        // echo "==========================";
        // die;
        $data = array();
        $data['sid'] = session()->get('sid');
        $sid = "u" . session()->get('sid');
        $store_id = $sid;
        $session_logged_in_emailid = session()->get('loggedin_username');
        $user_data = AppUser::where([['vemail', '=', $session_logged_in_emailid]])->get();
        $menu_data = Menu::select()->get();
        // DB::table('users')
        //     ->select('users.id', 'users.name', 'profiles.photo')
        //     ->join('profiles', 'profiles.id', '=', 'users.id')
        //     ->where(['something' => 'something', 'otherThing' => 'otherThing'])
        //     ->get();
        // echo "here is user data";
        // echo "<pre>";
        // print_r($menu_data);
        // die;
        // return view('dashboard_layout',compact('data'));
        // return view('dashboard_layout')->compact($menu_data);
        $iuserid = $user_data[0]->iuserid;

        $convert_uppercase_data = array();

        DB::connection('mysql')->statement("UPDATE " . $sid . ".user_menu SET
        status =  'InActive'
        where  iuserid = '" . $iuserid . "' ");

        foreach ($input['menu'] as $menu_name) {
            // $menu_des = $menu_name;
            $user_menu_selected_data = Menu::where([['menu_des', '=', $menu_name]])->get();
            $user_menu_id = $user_menu_selected_data[0]->menu_id;
            $user_menu_selected_data = Menu::where([['menu_des', '=', $menu_name]])->get();

            $insert_data = "INSERT INTO " . $sid . ".user_menu
                            (menu_id, iuserid, status, LastUpdate, SID)
                        VALUES
                            ('$user_menu_id','$iuserid','Active', CURRENT_TIMESTAMP, " . $data['sid'] . ")";
            $sql_excute = DB::connection('mysql')->statement($insert_data);
        }
        return redirect('dashboardlayout')->with('message', 'Saved Successfully');
    }

    public function footer_links(Request $request)
    {
        $data = array();
        $data['sid'] = session()->get('sid');
        $sid = "u" . session()->get('sid');
        $store_id = $sid;
        $session_logged_in_emailid = session()->get('loggedin_username');
        $user_data = AppUser::where([['vemail', '=', $session_logged_in_emailid]])->get();
        $iuserid = $user_data[0]->iuserid;

        // $user_menu = "select * from " . $sid . ".user_menu where iuserid = '$iuserid' AND status = 'Active'";

        // $user_menu = DB::table('user_menu')
        //     ->select(DB::raw('*'))
        //     ->from('user_menu')
        //     ->join('menu_table', 'menu_table.menu_id', '=', 'user_menu.menu_id')
        //     ->where(DB::raw('user_menu.iuserid'), '=', $iuserid)
        //     ->where(DB::raw('user_menu.status'), '=', 'Active');
        $select_query = "SELECT t1.status as usermenu_status, t1.usermenu_id, t2.menu_name as menu_name, t2.menu_des as menu_des, t2.menu_link as menu_link
        FROM $sid.user_menu t1
        INNER JOIN $sid.menu_table t2 ON t1.menu_id = t2.menu_id
        WHERE t1.status = 'Active'";
        $sql_excute = DB::connection('mysql_dynamic')->select($select_query);
        $return = json_decode(json_encode($sql_excute), true);
        return $return;
    }
}
