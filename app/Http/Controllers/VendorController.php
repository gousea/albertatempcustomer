<?php

namespace App\Http\Controllers;

use App\Model\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laravel\Ui\Presets\React;
use DB;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vendors = Vendor::orderBy('isupplierid', 'DESC')->get();
        $data['edit_list'] = url('vendors/edit_list');
        return view('vendors.index', compact('vendors', 'data'));
    }

    public function edit_list(Request $request)
    {
        $input = $request->all();
        if(isset($input['stores_hq'])){
            if($input['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $input['stores_hq']);
            }
            foreach($input['vendor'] as $vendor){
                if(in_array($vendor['isupplierid'], $input['selected'])){
                    $vcompany_name_data = Vendor::select('vcompanyname')->where('isupplierid', '=', $vendor['isupplierid'] )->get();
                    if(isset($vcompany_name_data[0])){
                        $vcompany_name = $vcompany_name_data[0]->vcompanyname;
                    }
                    foreach($stores as $store){
                        $selectcompanyname = DB::connection("mysql")->select("select * from u".$store.".mst_supplier where vcompanyname = '".$vcompany_name."' ");
                        if(count($selectcompanyname) != 0){
                            DB::connection('mysql')->update("Update u".$store.".mst_supplier set vcompanyname = '".$vendor['vcompanyname']."', vphone = '".$vendor['vphone']."', vemail = '".$vendor['vemail']."' where isupplierid = '".$selectcompanyname[0]->isupplierid."' ");
                        }
                    }
                }
            }
            $data['edit_list'] = url('vendors/edit_list');
            $vendors = Vendor::orderBy('isupplierid', 'DESC')->paginate(20);

            return redirect('vendors');
        }else{
            foreach($input['vendor'] as $vendor){
                if(in_array($vendor['isupplierid'], $input['selected'])){
                    $ven =  Vendor::where('isupplierid', '=', $vendor['isupplierid'])->update([
                        'vcompanyname'  =>$vendor['vcompanyname'],
                        'vcode'         =>$vendor['vcode'],
                        'vphone'        =>$vendor['vphone'],
                        'vemail'        =>$vendor['vemail'],
                        'vsuppliercode' =>$vendor['vsuppliercode'],
                    ]);
                }
            }
            $data['edit_list'] = url('vendors/edit_list');
            $vendors = Vendor::orderBy('isupplierid', 'DESC')->paginate(20);

            return redirect('vendors');
            // return view('vendors.index', compact('vendors', 'data'));
        }


        // $vendor = $input['vendor'];
        // $i = 0;
        // foreach($vendor as $ven){
        //     $vendor =  Vendor::where('isupplierid', '=', $ven['isupplierid'])->update([
        //         'vcompanyname'  =>$ven['vcompanyname'],
        //         'vcode'         =>$ven['vcode'],
        //         'vphone'        =>$ven['vphone'],
        //         'vemail'        =>$ven['vemail'],
        //         'vsuppliercode' =>$ven['vsuppliercode'],
        //     ]);
        //     $i++;
        // }



    }

    public function search(Request $request)
    {
        $input = $request->all();
        $vendors = Vendor::where('vcompanyname', 'LIKE','%'.$input['automplete-product'].'%')->orderBy('isupplierid', 'DESC')->paginate(20);
        $data['edit_list'] = url('vendors/edit_list');
        return view('vendors.index', compact('vendors', 'data'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {

        $input = $request->all();

        $data = Vendor::where('vcompanyname', '=', $input['vcompanyname'])->get();

        if(count($data) > 0){
            return redirect('vendors/create')
                        ->withErrors("Vendor id is already exists.")
                        ->withInput();
        }else {

            if(isset($input['stores_hq'])){

                if($input['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $input['stores_hq']);
                }
                for($i=0;$i<count($stores); $i++){
                    if($stores[$i] != ""){

                        //=====update vendor_format column====
                        $check_column = DB::connection("mysql")->Select(DB::connection("mysql")->raw("SHOW COLUMNS FROM u".$stores[$i].".mst_supplier LIKE 'vendor_format'"));
                        if(count($check_column) === 0){

                            $alter_query = "ALTER TABLE u".$stores[$i].".mst_supplier ADD vendor_format varchar(25) NOT NULL";
                            DB::connection("mysql")->statement($alter_query);
                        }

                        $dup_query = "Select vcompanyname from u".$stores[$i].".mst_supplier where  vcompanyname = '".$input['vcompanyname']."' ";
                        $dup_entry = DB::connection("mysql")->select($dup_query);

                        $vendor_format = isset($input['vendor_format']) ? $input['vendor_format'] : 'OTHERS';

                        if(count($dup_entry) > 0){
                            $update_vendor_sql = "UPDATE u".$stores[$i].".mst_supplier SET
                                                vcompanyname    = '".$input['vcompanyname']."',
                                                vvendortype     = '".$input['vvendortype']."',
                                                vfnmae          = '".$input['vfnmae']."',
                                                vlname          = '".$input['vlname']."',
                                                vcode           = '".$input['vcode']."',
                                                vaddress1       = '".$input['vaddress1']."',
                                                vcity           = '".$input['vcity']."',
                                                vstate          = '".$input['vstate']."',
                                                vphone          = '".$input['vphone']."',
                                                vzip            = '".$input['vzip']."',
                                                vcountry        = '".$input['vcountry']."',
                                                vemail          = '".$input['vemail']."',
                                                plcbtype        = '".$input['plcbtype']."',
                                                edi             = '".$input['edi']."',
                                                upc_convert     = '".$input['upc_convert']."',

                                                remove_first_digit= '".$input['remove_first_digit']."',
                                                remove_last_digit= '".$input['remove_last_digit']."',
                                                check_digit     = '".$input['check_digit']."',
                                                vendor_format   = '".$vendor_format."',
                                                estatus         = '".$input['estatus']."',
                                                SID             = '".$stores[$i]."' where vcompanyname = '".$input['vcompanyname']."' ";

                                $vendor = DB::connection("mysql")->statement($update_vendor_sql);

                        }else {
                            // dd($input);
                            if(isset($input['remove_last_digit']) && !empty($input['remove_last_digit'])) {
                                $remove_last_digit = $input['remove_last_digit'];
                            }else {
                                $remove_last_digit = 0;
                            }
                            
                            if(isset($input['check_digit']) && !empty($input['check_digit'])) {
                                $check_digit = $input['check_digit'];
                            }else {
                                $check_digit = 0;
                            }
                            
                            if(isset($input['upc_convert']) && !empty($input['upc_convert'])) {
                                $upc_convert = $input['upc_convert'];
                            }else {
                                $upc_convert = 0;
                            }
                            
                            if(isset($input['remove_first_digit']) && !empty($input['remove_first_digit'])) {
                                $remove_first_digit = $input['remove_first_digit'];
                            }else {
                                $remove_first_digit = 0;
                            }
                            
                            
                             $insert_vendor_sql = "INSERT INTO u".$stores[$i].".mst_supplier (vcompanyname, vvendortype, vfnmae, vlname, vcode,
                                vaddress1, vcity, vstate, vphone, vzip, vcountry, vemail, plcbtype, edi, estatus,upc_convert, remove_first_digit,
                                remove_last_digit,check_digit, vendor_format, SID)
                                VALUES ( '".$input['vcompanyname']."',
                                '".$input['vvendortype']."', '".$input['vfnmae']."', '".$input['vlname']."',
                                '".$input['vcode']."', '".$input['vaddress1']."',
                                '".$input['vcity']."', '".$input['vstate']."', '".$input['vphone']."', '".$input['vzip']."', '".$input['vcountry']."', 
                                '".$input['vemail']."', '".$input['plcbtype']."',
                                '".$input['edi']."', '".$input['estatus']."',
                                '".$upc_convert."',
                                '".$remove_first_digit."',
                                '". $remove_last_digit."',  
                                '".$check_digit."', 
                                '".$vendor_format."',  '".$stores[$i]."' ) ";
                            // dd($insert_vendor_sql);

                            $vendor = DB::connection("mysql")->statement($insert_vendor_sql);
                            $sup_query = "Select isupplierid from u".$stores[$i].".mst_supplier ORDER BY  isupplierid DESC LIMIT 1";
                            $sup_id = DB::connection("mysql")->select($sup_query);
                            // dd($sup_id);
                            for($j=0; $j<count($sup_id); $j++){
                                $isuppliercode = $sup_id[$j]->isupplierid;
                            }
                            $update_query = "UPDATE u".$stores[$i].".mst_supplier SET vsuppliercode = '".$isuppliercode."' WHERE isupplierid = '".$isuppliercode."' ";
                            DB::connection("mysql")->statement($update_query);
                        }
                    }

                }
            }else {

                $upc_convert   = isset($input['upc_convert']) ? $input['upc_convert']:0;

                if(isset($input['remove_first_digit']) )
                {
                    $remove_first_digit=$input['remove_first_digit'];

                }
                else{

                    $remove_first_digit='N';
                }

                if(isset($input['remove_last_digit']) )
                {
                    $remove_last_digit=$input['remove_last_digit'];
                }
                else{
                    $remove_last_digit='N';
                }

                if(isset($input['check_digit']) && $input['upc_convert']!='A'  )
                {
                    $check_digit=$input['check_digit'];
                }
                else{
                    $check_digit='N';
                }

                $vendor_format = isset($input['vendor_format']) ? $input['vendor_format'] : 'OTHERS';

                //=====update vendor_format column====
                $check_column = DB::connection("mysql_dynamic")->Select(DB::connection("mysql_dynamic")->raw("SHOW COLUMNS FROM mst_supplier LIKE 'vendor_format'"));

                if(count($check_column) === 0){

                    $alter_query = "ALTER TABLE mst_supplier ADD vendor_format varchar(25) NOT NULL";
                    DB::connection("mysql_dynamic")->statement($alter_query);
                }

                // $vendor =  Vendor::create([
                //             'vcompanyname' => $input['vcompanyname'],
                //             'vvendortype' => $input['vvendortype'],
                //             'vfnmae' => $input['vfnmae'],
                //             'vlname' => $input['vlname'],
                //             'vcode' => $input['vcode'],
                //             'vaddress1' => $input['vaddress1'],
                //             'vcity' => $input['vcity'],
                //             'vstate' => $input['vstate'],
                //             'vphone' => $input['vphone'],
                //             'vzip' => $input['vzip'],
                //             'vcountry' => $input['vcountry'],
                //             'vemail' => $input['vemail'],
                //             'plcbtype' => $input['plcbtype'],
                //             'edi'=> $input['edi'],
                //             'estatus'=> $input['estatus'],
                //             'upc_convert'=>$upc_convert,
                //             'upc_type'=> $upc_type,
                //             'remove_first_digit'=>$remove_first_digit,
                //             'remove_last_digit'=>$remove_last_digit,
                //             'check_digit'=>$check_digit,
                //             'SID' => session()->get('sid'),
                //         ]);


                          $insert_vendor_sql = "INSERT INTO u".session()->get('sid').".mst_supplier (vcompanyname, vvendortype, vfnmae, vlname, vcode,
                                vaddress1, vcity, vstate, vphone, vzip, vcountry, vemail, plcbtype,edi,  estatus,
                                upc_convert, remove_first_digit,
                                remove_last_digit,check_digit, vendor_format, SID)
                                VALUES ( '".$input['vcompanyname']."',
                                '".$input['vvendortype']."', '".$input['vfnmae']."', '".$input['vlname']."', '".$input['vcode']."', '".$input['vaddress1']."',
                                '".$input['vcity']."', '".$input['vstate']."', '".$input['vphone']."', '".$input['vzip']."', '".$input['vcountry']."', '".$input['vemail']."', '".$input['plcbtype']."',
                                '".$input['edi']."',
                                '".$input['estatus']."',
                                '".$upc_convert."',

                                '".$remove_first_digit."',
                                '".$remove_last_digit."',
                                '".$check_digit."',
                                '".$vendor_format."',
                                '".session()->get('sid')."' ) ";


                    $vendor = DB::connection("mysql")->statement($insert_vendor_sql);

                    $isuppliercode=DB::getPdo()->lastInsertId();
                    Vendor::where('isupplierid', '=', $isuppliercode  )->update(['vsuppliercode' => $isuppliercode]);

            }
            return redirect('vendors')->with('message', 'Vendor created successfully');

        }

    }

    // public function store(Request $request)
    // {
    //     $input = $request->all();
    //     $data = Vendor::where('vcompanyname', '=', $input['vcompanyname'])->get();

    //     if(count($data) > 0){

    //         return redirect('vendors/create')
    //                     ->withErrors("Vendor id is already exists.")
    //                     ->withInput();

    //     }else {
    //       $vendor =  Vendor::create([
    //                     'vcompanyname' => $input['vcompanyname'],
    //                     'vvendortype' => $input['vvendortype'],
    //                     'vfnmae' => $input['vfnmae'],
    //                     'vlname' => $input['vlname'],
    //                     'vcode' => $input['vcode'],
    //                     'vaddress1' => $input['vaddress1'],
    //                     'vcity' => $input['vcity'],
    //                     'vstate' => $input['vstate'],
    //                     'vphone' => $input['vphone'],
    //                     'vzip' => $input['vzip'],
    //                     'vcountry' => $input['vcountry'],
    //                     'vemail' => $input['vemail'],
    //                     'plcbtype' => $input['plcbtype'],
    //                     'edi' => $input['edi'],
    //                     'estatus' => $input['estatus'],
    //                     'SID' => session()->get('sid'),
    //                 ]);


    //         $isuppliercode = $vendor->id;
    //         // dd( $isuppliercode);
    //         Vendor::where('isupplierid', '=', $isuppliercode  )->update(['vsuppliercode' => $isuppliercode]);

    //         return redirect('vendors')->with('message', 'Vendor created successfully');
    //     }

    // }

    public function edit(Vendor $vendor, $isupplierid)
    {
        $vendors = Vendor::where('isupplierid', '=', $isupplierid)->get();
        $vendor = $vendors[0];
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor, $isupplierid)
    {
        $input = $request->all();
        

        $vcompany_name_data = Vendor::select('vcompanyname')->where('isupplierid', '=', $isupplierid )->get();
        $vcompany_name = $vcompany_name_data[0]->vcompanyname;

        if(isset($input['stores_hq'])){
                // $stores = explode(",", $input['stores_hq']);
                if($input['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $input['stores_hq']);
                }
                for($i=0;$i<count($stores); $i++){
                    if($stores[$i] != ""){

                        //=====update vendor_format column====
                        $check_column = DB::connection("mysql")->Select(DB::connection("mysql")->raw("SHOW COLUMNS FROM u".$stores[$i].".mst_supplier LIKE 'vendor_format'"));
                        if(count($check_column) === 0){

                            $alter_query = "ALTER TABLE u".$stores[$i].".mst_supplier ADD vendor_format varchar(25) NOT NULL";
                            DB::connection("mysql")->statement($alter_query);
                        }

                        $vendor_format = isset($input['vendor_format']) ? $input['vendor_format'] : 'OTHERS';
                        
                        

                        if(isset($input['upc_convert']) )
                        {
                            $upc_convert=$input['upc_convert'];
        
                        }
                        else{
        
                            $upc_convert= 0;
                        }
                        
                        if(isset($input['remove_first_digit']) )
                        {
                            $remove_first_digit=$input['remove_first_digit'];
        
                        }
                        else{
        
                            $remove_first_digit='N';
                        }
        
                        if(isset($input['remove_last_digit']) )
                        {
                            $remove_last_digit=$input['remove_last_digit'];
                        }
                        else{
                            $remove_last_digit='N';
                        }
        
                        if(isset($input['check_digit']) && $input['upc_convert']!='A'  )
                        {
                            $check_digit=$input['check_digit'];
                        }
                        else{
                            $check_digit='N';
                        }
                        
                        

                        $update_vendor_sql = "UPDATE u".$stores[$i].".mst_supplier SET
                                                vcompanyname    = '".$input['vcompanyname']."',
                                                vvendortype     = '".$input['vvendortype']."',
                                                vfnmae          = '".$input['vfnmae']."',
                                                vlname          = '".$input['vlname']."',
                                                vcode           = '".$input['vcode']."',
                                                vaddress1       = '".$input['vaddress1']."',
                                                vcity           = '".$input['vcity']."',
                                                vstate          = '".$input['vstate']."',
                                                vphone          = '".$input['vphone']."',
                                                vzip            = '".$input['vzip']."',
                                                vcountry        = '".$input['vcountry']."',
                                                vemail          = '".$input['vemail']."',
                                                plcbtype        = '".$input['plcbtype']."',
                                                edi             = '".$input['edi']."',
                                                upc_convert     = '".$upc_convert."',
                                                remove_first_digit= '".$remove_first_digit."',
                                                remove_last_digit= '".$remove_last_digit."',
                                                check_digit     = '".$check_digit."',
                                                vendor_format   = '".$vendor_format."',
                                                estatus         = '".$input['estatus']."',
                                                SID             = '".$stores[$i]."' where vcompanyname = '".$vcompany_name."' ";

                        $vendor = DB::connection("mysql")->statement($update_vendor_sql);
                    }

                }
        }else{

              if(isset($input['remove_first_digit']))
              {
                  $remove_first_digit=$input['remove_first_digit'];

              }
              else{

                  $remove_first_digit='N';
              }
              if(isset($input['remove_last_digit']))
              {
                  $remove_last_digit=$input['remove_last_digit'];
              }
              else{
                  $remove_last_digit='N';
              }

              if(isset($input['check_digit']) && isset($input['upc_convert']) && $input['upc_convert']!='A'  )
              {
                  $check_digit=$input['check_digit'];
              }
              else{
                  $check_digit='N';
              }

                $vendor_format = isset($input['vendor_format']) ? $input['vendor_format'] : 'OTHERS';

                //=====update vendor_format column====
                $check_column = DB::connection("mysql_dynamic")->Select(DB::connection("mysql_dynamic")->raw("SHOW COLUMNS FROM mst_supplier LIKE 'vendor_format'"));

                if(count($check_column) === 0){

                    $alter_query = "ALTER TABLE mst_supplier ADD vendor_format varchar(25) NOT NULL";
                    DB::connection("mysql_dynamic")->statement($alter_query);
                }

                $vendor =  Vendor::where('isupplierid', '=', $isupplierid )->update([
                                'vcompanyname' => $input['vcompanyname'],
                                'vvendortype' => $input['vvendortype'],
                                'vfnmae' => $input['vfnmae'],
                                'vlname' => $input['vlname'],
                                'vcode' => $input['vcode'],
                                'vaddress1' => $input['vaddress1'],
                                'vcity' => $input['vcity'],
                                'vstate' => $input['vstate'],
                                'vphone' => $input['vphone'],
                                'vzip' => $input['vzip'],
                                'vcountry' => $input['vcountry'],
                                'vemail' => $input['vemail'],
                                'plcbtype' => $input['plcbtype'],
                                'edi' => $input['edi'],
                                'upc_convert'    => isset($input['upc_convert']) ? $input['upc_convert']:'N',

                                // 'remove_first_digit'=>isset($input['remove_first_digit']) && $input['upc_convert']!=0 ?$input['remove_first_digit']:'N',
                                // 'remove_last_digit'=> isset($input['remove_last_digit'])?$input['remove_last_digit'] :'N',
                                // 'check_digit'=>isset($input['check_digit'])?$input['check_digit']:'N',

                                'remove_first_digit'=>$remove_first_digit,
                                'remove_last_digit'=>$remove_last_digit,
                                'check_digit'=>$check_digit,
                                'vendor_format'=> $vendor_format,
                                'estatus' => $input['estatus'],
                                'SID' => session()->get('sid'),
                            ]);
        }
         return redirect('vendors')->with('message', 'Vendor Updated Successfully');
    }

    



    public function remove(Request $request)
    {
        $delId = $request->all();
        if(isset($delId['stores_hq']) ){
            foreach($delId['vendor_ids'] as $vendor){
                $vcompany_name_data = Vendor::select('vcompanyname')->where('isupplierid', '=', $vendor )->get();
                if(isset($vcompany_name_data[0])){
                    $vcompany_name = $vcompany_name_data[0]->vcompanyname;
                }
                foreach($delId['stores_hq'] as $store){
                    $isup_query = "Select isupplierid from u".$store.".mst_supplier WHERE vcompanyname = '".$vcompany_name."' ";
                    $isup_id =  DB::connection('mysql')->select($isup_query);
                    if(isset($isup_id[0])){
                        $delete_query = "DELETE FROM u".$store.".mst_supplier WHERE isupplierid = '".$isup_id[0]->isupplierid."' ";
                        DB::connection('mysql')->statement($delete_query);
                    }
                }
            }
            $success = "Vendor Deleted Successfully";
        }else{
            foreach($delId['vendor_ids'] as $vendor){
                Vendor::where('isupplierid', '=', $vendor  )->delete();
            }
            $success = "Vendor Deleted Successfully";
        }
        return $success;

        // return redirect('vendors')->with('message', 'Vendor Deleted Successfully');
    }

    public function hqVendors(Request $request){
        $input = $request->all();
        $avalable = array();
        $stores = session()->get('stores_hq');
        foreach($stores as $store){

           for($i=0; $i<count($input['vendor_ids']); $i++){
                $vcompany_name_data = Vendor::select('vcompanyname')->where('isupplierid', '=', $input['vendor_ids'][$i] )->get();

                for($a=0; $a<count($vcompany_name_data); $a++){
                    $dup_query = "Select vcompanyname from u".$store->id.".mst_supplier where  vcompanyname = '".$vcompany_name_data[$a]->vcompanyname."' ";
                    $dup_entry = DB::connection("mysql")->select($dup_query);
                    // dd($dup_entry);
                    if(count($dup_entry) == 0){
                        // dd($store->id);
                        array_push($avalable, $store->id);
                    }
                }
           }

        }
       return $avalable;
    }

    public function dupilcateHQvendor(Request $request){
        $input = $request->all();
        $data = Vendor::where('vcompanyname', '=', $input['vcompanyname'])->get();
        $msg = '';
        if(count($data) > 0){
            $msg = "Vendor is already exists."  ;
        }else {
            $msg = "success";
        }

        return $msg;

    }
}