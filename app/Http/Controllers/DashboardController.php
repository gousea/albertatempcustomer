<?php

namespace App\Http\Controllers;

use App\Model\Menu;
use App\User as AppUser;
use Illuminate\Http\Request;

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
        return view('dashboard_layout');
    }

    public function dashboard_menulist(Request $request)
    {
        // ini_set('memory_limit', '-1');
        $data = array();
        $sid = session()->get('sid');
        $session_logged_in_emailid = session()->get('loggedin_username');
        $user_data = AppUser::where([['vemail', '=', $session_logged_in_emailid]])->get();
        $menu_data = Menu::select()->get();
        // echo "<pre>";
        // print_r($menu_data);
        // die;
        // return view('dashboard_layout',compact('data'));
        return view('dashboard_layout')->compact($menu_data);
        $iuserid = $user_data[0]->iuserid;
        $input = $request->all();
        $convert_uppercase_data = array();
        foreach ($input['menu'] as $menu_name) {
            $result = str_replace('_', ' ', $menu_name);
            $convert_uppercase_data = strtoupper($result);
        }
        // $menu_table_data = Menu::where('iuserid', '=', $iuserid);
        // echo "<pre>";
        // print_r($menu_table_data);
    }
}