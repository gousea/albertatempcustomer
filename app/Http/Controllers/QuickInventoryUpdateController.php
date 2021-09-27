<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Model\{Olditem,WebAdminSetting,Department,Category,Manufacturer,Unit,Supplier,Size,SubCategory,ItemGroup,AgeVerification,ItemMovement,Item,ReceivingOrder,Store,StoreSettings,Vendor };

class QuickInventoryUpdateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getForm(){

        return view('quickInventoryUpdate.quick_inventory_update');
    }
    public function searchItems(Request $request){
        $input = $request->input();
        //$input = $request->all();
        $headers = $request->header();
        $request_ip = $request->ip();
        if(empty($input['barcode'])){
            $return = ['status' => false, 'message' => 'Invalid Request', 'status_code' => 'AR001',
                'data' => []];
            $http_status = 400;
            return response()->json($return, $http_status);
        }
        $sid = session()->get('sid');
        $barcode = trim($input['barcode']);
       // $itemsData['items'] = Item::where('vbarcode','=',$barcode)->where('SID',$sid)->first();
       // $itemsData['items'] = Item::where('vbarcode','=',$barcode)->where('SID',$sid)->get(["iitemid","vitemname","vsuppliercode","iqtyonhand","npack"]);;
        $itemsData['items'] = Item::where('vbarcode','=',$barcode)->get(["iitemid","vbarcode","vitemname","vsuppliercode","iqtyonhand","npack"]);;
        if (empty($itemsData)) {
            $return = ['status' => false, 'message' => 'NO DATA FOUND', 'status_code' => 'AR001',
                'data' => []];
            $http_status = 400;
            return response()->json($return, $http_status);
        }
        else{
            return response()->json($itemsData);
           /* $return = ['status' => false, 'message' => 'NO DATA FOUND', 'status_code' => 'AR001',
                'data' => [$itemsData]];
            $http_status = 400;
            return response()->json($return, $http_status);*/
        }

    }
    public function quickInventoryUpdate(Request $request)
    {
        $input = $request->input();
        $inputHeader = $request->header();
        $inputIP = $request->ip();
        /**
         * Checking the required parameter
         */
        Session::forget('error_warning');
        Session::forget('success');
        if(empty($input['vbarcode'])){
            Session::put('error_warning', 'Invalid Request Parameter');
            return view('quickInventoryUpdate.quick_inventory_update');
        }
        /*$validator = Validator::make($input, [
            'vbarcode' => 'required|string|max:30|min:3',
        ]);
        if ($validator->fails()) {
            $message = $validator->errors()->first();
            Session::put('error_warning', $message);
            return view('quickInventoryUpdate.quick_inventory_update');
        }*/

        foreach ($input['vbarcode'] as $key => $datum){
            $datum = trim($datum);
            Log::info('iitemid=> '. $key.' updated with new QTY =>'.$datum);
            $updatedResp[]=Item::where('iitemid', $key)->update(['iqtyonhand'=>DB::raw("`iqtyonhand` + $datum")]);
        }
        if(!empty($updatedResp))
        {
            return back()->with('success','Inventory Successfully Updated!');
            Session::put('success', 'Successfully Updated');
            return view('quickInventoryUpdate.quick_inventory_update');
        }
        Session::put('error_warning', 'Unable to update.');
        return view('quickInventoryUpdate.quick_inventory_update');
    }

}

?>
