<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Customer;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $customers = Customer::orderBy('icustomerid', 'DESC')->paginate(20);
        return view('customers.index', compact('customers'));
    }

    public function search(Request $request)
    {
        $input = $request->all();
        $customers = Customer::where('vcustomername','LIKE','%'.$input['automplete-product'].'%')->orWhere('vfname','LIKE','%'.$input['automplete-product'].'%')->orWhere('vlname','LIKE','%'.$input['automplete-product'].'%')->orWhere('vaccountnumber','LIKE','%'.$input['automplete-product'].'%')->orderBy('icustomerid', 'DESC')->paginate(20);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        //return view('customers.create');
        $version_latest= DB::connection('mysql_dynamic')->select("SELECT ver_no FROM mst_version ORDER BY ver_id DESC LIMIT 1");
        $version=$version_latest[0];
        //dd($version->ver_no);
        return view('customers.create', compact('version'));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $duplicateCust = Customer::where('vphone', '=', $input['vphone'])->get();

        if(count($duplicateCust) > 0){
            return redirect('customers/create')
                        ->withErrors("Customer is already exists.")
                        ->withInput();
        }else {
            if(isset($input['expire_dt'])){
                $expire_dt = explode("-", $input['expire_dt']);
                $expire_date = $expire_dt[0].'-'.$expire_dt[1].'-'.$expire_dt[2];
            }else{
                $expire_date = '';
            }
            if(isset($input['birth_dt'])){
                $birth_dt = explode("-",$input['birth_dt']);
                $birth_date = $birth_dt[0].'-'.$birth_dt[1].'-'.$birth_dt[2];
            }else{
                $birth_date = '';
            }

            $version_latest= DB::connection('mysql_dynamic')->select("SELECT ver_no FROM mst_version ORDER BY ver_id DESC LIMIT 1");
            $version=$version_latest[0];
            if($version->ver_no=='3.1.0'){

            Customer::create([
                'vcustomername' => $input['vcustomername'],
                'vaccountnumber' => $input['vaccountnumber'],
                'acct_pin' => $input['account_pin'],
                'vfname' => $input['vfname'],
                'vlname' => $input['vlname'],
                'vaddress1' => $input['vaddress1'],
                'vcity' => $input['vcity'],
                'vstate' => $input['vstate'],
                'vphone' => $input['vphone'],
                'vzip' => $input['vzip'],
                'vcountry' => $input['vcountry'],
                'vemail' => $input['vemail'],
                'pricelevel' => $input['pricelevel'],
                'vtaxable' => $input['vtaxable'],
                'estatus' => $input['estatus'],
                'debitlimit' => $input['debitlimit'],
                'id_expire_dt' => $expire_date,
                'birth_dt' => $birth_date,
                'id_type' => $input['id_type'],
                'id_number' => $input['id_number'],
                'creditday' => $input['creditday'],
                'note' => $input['note'],
                'SID' => session()->get('sid')
            ]);
    }else{
        Customer::create([
                'vcustomername' => $input['vcustomername'],
                'vaccountnumber' => $input['vaccountnumber'],
                'vfname' => $input['vfname'],
                'vlname' => $input['vlname'],
                'vaddress1' => $input['vaddress1'],
                'vcity' => $input['vcity'],
                'vstate' => $input['vstate'],
                'vphone' => $input['vphone'],
                'vzip' => $input['vzip'],
                'vcountry' => $input['vcountry'],
                'vemail' => $input['vemail'],
                'pricelevel' => $input['pricelevel'],
                'vtaxable' => $input['vtaxable'],
                'estatus' => $input['estatus'],
                'debitlimit' => $input['debitlimit'],
                'creditday' => $input['creditday'],
                'note' => $input['note'],
                'SID' => session()->get('sid')
        ]);
    }

            return redirect('customers')->with('message', 'customers created Successfully');
        }
    }

    public function edit(Request $request, $icustomerid)
    {
        $customers = Customer::where('icustomerid', '=', $icustomerid)->get();
        $customer = $customers[0];
        $version_latest= DB::connection('mysql_dynamic')->select("SELECT ver_no FROM mst_version ORDER BY ver_id DESC LIMIT 1");
        $version=$version_latest[0];

        return view('customers.edit', compact('customer','version'));
    }

    public function update(Request $request, Customer $customer, $icustomerid)
    {
        $input = $request->all();


        if(isset($input['expire_dt'])){
        $expire_dt = explode("-", $input['expire_dt']);
        $expire_date = $expire_dt[0].'-'.$expire_dt[1].'-'.$expire_dt[2];
        }else{
            $expire_date = '';
        }
        if(isset($input['birth_dt'])){
        $birth_dt = explode("-",$input['birth_dt']);
        $birth_date = $birth_dt[0].'-'.$birth_dt[1].'-'.$birth_dt[2];
        }else{
            $birth_date = '';
        }

        $version_latest= DB::connection('mysql_dynamic')->select("SELECT ver_no FROM mst_version ORDER BY ver_id DESC LIMIT 1");
        $version=$version_latest[0];
        if($version->ver_no=='3.1.0'){
        Customer::where('icustomerid', '=', $icustomerid)->update([
            'vcustomername' => $input['vcustomername'],
            'vaccountnumber' => $input['vaccountnumber'],
            'acct_pin' => $input['account_pin'],
            'vfname' => $input['vfname'],
            'vlname' => $input['vlname'],
            'vaddress1' => $input['vaddress1'],
            'vcity' => $input['vcity'],
            'vstate' => $input['vstate'],
            'vphone' => $input['vphone'],
            'vzip' => $input['vzip'],
            'vcountry' => $input['vcountry'],
            'vemail' => $input['vemail'],
            'pricelevel' => $input['pricelevel'],
            'vtaxable' => $input['vtaxable'],
            'estatus' => $input['estatus'],
            'debitlimit' => $input['debitlimit'],
            'creditday' => $input['creditday'],
            'id_expire_dt' => $expire_date,
            'birth_dt' => $birth_date,
            'id_type' => $input['id_type'],
            'id_number' => $input['id_number'],
            'note' => $input['note'],
            'SID' => session()->get('sid')
        ]);
        }
        else{
             Customer::where('icustomerid', '=', $icustomerid)->update([
            'vcustomername' => $input['vcustomername'],
            'vaccountnumber' => $input['vaccountnumber'],
            'vfname' => $input['vfname'],
            'vlname' => $input['vlname'],
            'vaddress1' => $input['vaddress1'],
            'vcity' => $input['vcity'],
            'vstate' => $input['vstate'],
            'vphone' => $input['vphone'],
            'vzip' => $input['vzip'],
            'vcountry' => $input['vcountry'],
            'vemail' => $input['vemail'],
            'pricelevel' => $input['pricelevel'],
            'vtaxable' => $input['vtaxable'],
            'estatus' => $input['estatus'],
            'debitlimit' => $input['debitlimit'],
            'creditday' => $input['creditday'],
            'note' => $input['note'],
            'SID' => session()->get('sid')
        ]);
        }
        return redirect('customers')->with('message', 'customers updated Successfully');

    }

    public function remove(Request $request)
    {
        $delId = $request->all();
        for($i = 0; $i < count($delId['selected']); $i++ ){
            Customer::where('icustomerid', '=', $delId['selected'][$i] )->delete();
        }
        return redirect('customers')->with('message', 'customers Deleted Successfully');
    }
}