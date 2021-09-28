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
use App\Model\{Olditem,
    WebAdminSetting,
    Department,
    Category,
    Manufacturer,
    Unit,
    Supplier,
    Size,
    SubCategory,
    ItemGroup,
    AgeVerification,
    ItemMovement,
    Item,
    ReceivingOrder,
    Store,
    StoreSettings,
    Vendor};

class QuickInventoryUpdateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getForm()
    {

        return view('quickInventoryUpdate.quick_inventory_update');
    }

    public function searchItems(Request $request)
    {
        $input = $request->input();
        //$input = $request->all();
        $headers = $request->header();
        $request_ip = $request->ip();
        if (empty($input['barcode'])) {
            $return = ['status' => false, 'message' => 'Invalid Request', 'status_code' => 'AR001',
                'data' => []];
            $http_status = 400;
            return response()->json($return, $http_status);
        }
        $sid = session()->get('sid');
        $barcode = trim($input['barcode']);
        // $itemsData['items'] = Item::where('vbarcode','=',$barcode)->where('SID',$sid)->first();
        // $itemsData['items'] = Item::where('vbarcode','=',$barcode)->where('SID',$sid)->get(["iitemid","vitemname","vsuppliercode","iqtyonhand","npack"]);;
        $itemsData['items'] = Item::where('vbarcode', '=', $barcode)->get(["iitemid", "vbarcode", "vitemname", "vsuppliercode", "iqtyonhand", "npack"]);;
        if (empty($itemsData)) {
            $return = ['status' => false, 'message' => 'NO DATA FOUND', 'status_code' => 'AR001',
                'data' => []];
            $http_status = 400;
            return response()->json($return, $http_status);
        } else {
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
        if (empty($input['vbarcode'])) {
            Session::put('error_warning', 'Invalid Request Parameter');
            return view('quickInventoryUpdate.quick_inventory_update');
        }

        $updatedDate = $input['updatedDate'] ?? date('Y-m-d H:i:s');
        $updatedDate = date('Y-m-d H:i:s', strtotime($updatedDate));
        $newInvoice = $input['newInvoice'] ?? '';
        $newVendor = $input['newVendor'] ?? 'General Vendor';
        $invoiceFlag = 0;
        if (!empty($newInvoice)) {
            $query = DB::connection('mysql_dynamic')->select("SELECT vinvoiceno FROM trn_receivingorder WHERE vinvoiceno='" . $newInvoice . "'");
            if (count($query) > 0) {
                $invoiceFlag = 1; //'Invoice Already Exist'; have to create new

            } else {
                $invoiceFlag = 0; // will use the requested invoice
            }
        } else {
            $invoiceFlag = 1; //have to create new
        }

        if ($invoiceFlag == 1) {
            $temp_vponumber = ReceivingOrder::orderBy('iroid', 'desc')->first();
            if (!empty($temp_vponumber) && isset($temp_vponumber->iroid)) {
                $newInvoice = str_pad($temp_vponumber->iroid + 1, 9, "0", STR_PAD_LEFT);
            } else {
                $newInvoice = str_pad(1, 9, "0", STR_PAD_LEFT);
            }
        }

        //$data =$this->roInvoiceCheck($newInvoice);

        foreach ($input['vbarcode'] as $key => $datum) {
            $datum = trim($datum);
            Log::info('iitemid=> ' . $key . ' updated with new QTY =>' . $datum);
            $temp_item = Item::where('iitemid', $key)->first();
            if (!empty($temp_item) && isset($temp_item->iitemid)) {
                $iqtyonhand = $temp_item->iqtyonhand;
            } else {
                Session::put('error_warning', 'Unable to update itemID=>' . $key);
                return view('quickInventoryUpdate.quick_inventory_update');
            }
            $updatedResp[] = Item::where('iitemid', $key)->update(['iqtyonhand' => DB::raw("`iqtyonhand` + $datum")]);
            $iqtyafter = $iqtyonhand + $datum;
            DB::connection('mysql_dynamic')->table('trn_receivingorder as a')
                ->join('trn_receivingorderdetail as c', 'a.iroid', '=', 'c.iroid')
                ->where('c.vitemid', $key)
                ->update(['a.vvendorname' => "$newVendor", 'a.dreceiveddate' => "$updatedDate", 'a.vinvoiceno' => "$newInvoice",
                    'c.after_rece_qoh' => $iqtyafter, 'c.before_rece_qoh' => $iqtyonhand
                ]);

            // DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorder SET  vvendorname = '" . $newVendor . "', dreceiveddate = '" . $updatedDate . "', vinvoiceno= '" . $newInvoice . "'  WHERE iroid='". (int)$poid ."'");
        }
        if (!empty($updatedResp)) {
            return back()->with('success', 'Inventory Successfully Updated!');
            Session::put('success', 'Successfully Updated');
            return view('quickInventoryUpdate.quick_inventory_update');
        }
        Session::put('error_warning', 'Unable to update.');
        return view('quickInventoryUpdate.quick_inventory_update');
    }

    public function roInvoiceCheck($invoice)
    {
        $return = array();
        $query = DB::connection('mysql_dynamic')->select("SELECT vinvoiceno FROM trn_receivingorder WHERE vinvoiceno='" . $invoice . "'");

        if (count($query) > 0) {
            $return['error'] = 'Invoice Already Exist';
        } else {
            $return['success'] = 'Invoice Not Exist';
        }
        return $return;
    }

}

?>
