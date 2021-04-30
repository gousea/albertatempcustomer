<?php 

namespace App\Http\Controllers;

use App\Model\web_admin_setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Session;

class ItemListDisplaysController extends Controller{
    
    public function index(Request $request){
        $webadminsettings = new web_admin_setting();
        $url = '';
        
		$data['settings'] = array();
		
		
		$data['sid'] = $request->session()->get('sid');
        
		$data['defualt_items_listings'] = array();
		$data['pre_items_listings'] = array();
		$not_displays = array('iitemid', 'vitemname', 'vitemtype', 'vbarcode', 'itemimage', 'LastUpdate', 'SID');
       
        //         $input = $request->request->all();
        // 		echo "<pre>";
        // 		print_r($input);
        // 		die;
        		
        // 		echo $request->request->get('module_name');die;
        // 		echo $request->post('module_name');die;
        $data['module_name'] = $request->get('module_name') ? $request->get('module_name') : 'ItemListing';
        
		$defualt_items_listings = $webadminsettings->defaultListings();
		
        $pre_items_listing = $webadminsettings->getItemListings($data['module_name']);
        
        if(!empty($pre_items_listing)){
            $pre_items_listings = $pre_items_listing[0];
        }
        
        if(isset($pre_items_listings)){
            if(count($pre_items_listings) > 0){
                $pre_items_listings = json_decode($pre_items_listings['variablevalue']);
                $data['pre_items_listings'] = $pre_items_listings;
                
                foreach ($pre_items_listings as $keys => $pre_items_listing) {
                	array_push($not_displays, $keys);
                }
            }
        }
        
		if(count($defualt_items_listings) > 0){
			foreach ($defualt_items_listings as $defualt_items_listing) {
				if(!in_array($defualt_items_listing['Field'], $not_displays)){
					$data['defualt_items_listings'][] = $defualt_items_listing['Field'];
				}
			}
		}
        
        //===Added gross profit column by hard column because gross_profit column is not present in mst_item table(Jira ticket AP-706)=====
        if(!in_array('gross_profit', $not_displays)){
            $data['defualt_items_listings'][] = 'gross_profit';
        }
        
        //========remove iquantity column from display===
        if (($key = array_search('iquantity', $data['defualt_items_listings'])) !== false) {
            unset($data['defualt_items_listings'][$key]);
        }
        
		$data['title_arr'] = array(
            'webstore' => 'Web Store',
            'vitemtype' => 'Item Type',
            'vitemcode' => 'Item Code',
            'vitemname' => 'Item Name',
            'vunitcode' => 'Unit',
            'vbarcode' => 'SKU',
            'vpricetype' => 'Price Type',
            'vcategorycode' => 'Category',
            'vdepcode' => 'Dept.',
            'vsuppliercode' => 'Supplier',
            'iqtyonhand' => 'Qty. on Hand',
            'ireorderpoint' => 'Reorder Point',
            'dcostprice' => 'Avg. Case Cost',
            'dunitprice' => 'Price',
            'nsaleprice' => 'Sale Price',
            'nlevel2' => 'Level 2 Price',
            'nlevel3' => 'Level 3 Price',
            'nlevel4' => 'Level 4 Price',
            'iquantity' => 'Quantity',
            'ndiscountper' => 'Discount(%)',
            'ndiscountamt' => 'Discount Amt',
            'vtax1' => 'Tax 1',
            'vtax2' => 'Tax 2',
            'vtax3' => 'Tax 3',
            'last_costprice' => 'Last CostPrice',
            'new_costprice' => 'New CostPrice',
            'subcat_id' => 'Sub CatID',
            'manufacturer_id' => 'Manufacture ID',
            'reorder_duration' => 'Reorder Duration',
            'vfooditem' => 'Food Item',
            'vdescription' => 'Description',
            'dlastsold' => 'Last Old',
            'visinventory' => 'Inventory Item',
            'dpricestartdatetime' => 'Price Start Time',
            'dpriceenddatetime' => 'Price End Time',
            'estatus' => 'Status',
            'nbuyqty' => 'Buy Qty',
            'ndiscountqty' => 'Discount Qty',
            'nsalediscountper' => 'Sales Discount',
            'vshowimage' => 'Show Image',
            'itemimage' => 'Image',
            'vageverify' => 'Age Verification',
            'ebottledeposit' => 'Bottle Deposit',
            'nbottledepositamt' => 'Bottle Deposit Amt',
            'vbarcodetype' => 'Barcode Type',
            'ntareweight' => 'Tare Weight',
            'ntareweightper' => 'Tare Weight Per',
            'dcreated' => 'Created',
            'dlastupdated' => 'Last Updated',
            'dlastreceived' => 'Last Received',
            'dlastordered' => 'Last Ordered',
            'nlastcost' => 'Last Cost',
            'nonorderqty' => 'On Order Qty',
            'vparentitem' => 'Parent Item',
            'nchildqty' => 'Child Qty',
            'vsize' => 'Size',
            'npack' => 'Unit Per Case',
            'nunitcost' => 'Unit Cost',
            'ionupload' => 'On upload',
            'nsellunit' => 'Selling Unit',
            'ilotterystartnum' => 'Lottery Start Num',
            'ilotteryendnum' => 'Lottery End Num',
            'etransferstatus' => 'Transfer status',
            'vsequence' => 'Sequence',
            'vcolorcode' => 'Color Code',
            'vdiscount' => 'Discount',
            'norderqtyupto' => 'Order Qty Upto',
            'vshowsalesinzreport' => 'Sales Item',
            'iinvtdefaultunit' => 'Invt. Default Unit',
            'stationid' => 'Station',
            'shelfid' => 'Shelf',
            'aisleid' => 'Aisle',
            'shelvingid' => 'Shelving',
            'rating' => 'Rating',
            'vintage' => 'Vintage',
            'PrinterStationId' => 'Printer Station Id',
            'liability' => 'Liability',
            'isparentchild' => 'Is Parent Child',
            'parentid' => 'Parent Id',
            'parentmasterid' => 'Parent Master Id',
            'wicitem' => 'WIC Item',
            'gross_profit'=>'Gross Profit',
            
        );
        // echo "dsafsdf";
        // echo "<pre>";
        // print_r($data);
        // die;
        return view('itemlistdisplays.itemlistdisplay')->with($data);
    }

    public function edit(Request $request){
        $input = $request->request->all();
        $webadminsettings = new web_admin_setting();
        
        $sid = $request->session()->get('sid');
			if(count($request->post('itemListings')) > 0 ){
				$webadminsettings->editlistSettings($request->post(), $sid);
			}	
        $url = '';
        
		$data['settings'] = array();
		$data['sid'] = $request->session()->get('sid');
		
		$data['defualt_items_listings'] = array();
		$data['pre_items_listings'] = array();
		$not_displays = array('iitemid', 'vitemname', 'vitemtype', 'vbarcode', 'itemimage', 'LastUpdate', 'SID');
       
        $data['module_name'] = $request->get('module_name') ? $request->get('module_name') : 'ItemListing';

		$defualt_items_listings = $webadminsettings->defaultListings();
        $pre_items_listing = $webadminsettings->getItemListings($data['module_name']);
        
        if(!empty($pre_items_listing)){
            $pre_items_listings = $pre_items_listing[0];
        }
        
        if(isset($pre_items_listings)){
    		if(count($pre_items_listings) > 0){
    			$pre_items_listings = json_decode($pre_items_listings['variablevalue']);
    			$data['pre_items_listings'] = $pre_items_listings;
    
    			foreach ($pre_items_listings as $keys => $pre_items_listing) {
    				array_push($not_displays, $keys);
    			}
    		}
        }

		if(count($defualt_items_listings) > 0){
			foreach ($defualt_items_listings as $defualt_items_listing) {
				if(!in_array($defualt_items_listing['Field'], $not_displays)){
					$data['defualt_items_listings'][] = $defualt_items_listing['Field'];
				}
			}
		}
		
		//===Added gross profit column by hard column because gross_profit column is not present in mst_item table(Jira ticket AP-706)=====
        if(!in_array('gross_profit', $not_displays)){
            $data['defualt_items_listings'][] = 'gross_profit';
        }
        
        //========remove iquantity column from display===
        if (($key = array_search('iquantity', $data['defualt_items_listings'])) !== false) {
            unset($data['defualt_items_listings'][$key]);
        }
        
		$data['title_arr'] = array(
            'webstore' => 'Web Store',
            'vitemtype' => 'Item Type',
            'vitemcode' => 'Item Code',
            'vitemname' => 'Item Name',
            'vunitcode' => 'Unit',
            'vbarcode' => 'SKU',
            'vpricetype' => 'Price Type',
            'vcategorycode' => 'Category',
            'vdepcode' => 'Dept.',
            'vsuppliercode' => 'Supplier',
            'iqtyonhand' => 'Qty. on Hand',
            'ireorderpoint' => 'Reorder Point',
            'dcostprice' => 'Avg. Case Cost',
            'dunitprice' => 'Price',
            'nsaleprice' => 'Sale Price',
            'nlevel2' => 'Level 2 Price',
            'nlevel3' => 'Level 3 Price',
            'nlevel4' => 'Level 4 Price',
            'iquantity' => 'Quantity',
            'ndiscountper' => 'Discount(%)',
            'ndiscountamt' => 'Discount Amt',
            'vtax1' => 'Tax 1',
            'vtax2' => 'Tax 2',
            'vtax3' => 'Tax 3',
            'last_costprice' => 'Last CostPrice',
            'new_costprice' => 'New CostPrice',
            'subcat_id' => 'Sub CatID',
            'manufacturer_id' => 'Manufacture ID',
            'reorder_duration' => 'Reorder Duration',
            'vfooditem' => 'Food Item',
            'vdescription' => 'Description',
            'dlastsold' => 'Last Old',
            'visinventory' => 'Inventory Item',
            'dpricestartdatetime' => 'Price Start Time',
            'dpriceenddatetime' => 'Price End Time',
            'estatus' => 'Status',
            'nbuyqty' => 'Buy Qty',
            'ndiscountqty' => 'Discount Qty',
            'nsalediscountper' => 'Sales Discount',
            'vshowimage' => 'Show Image',
            'itemimage' => 'Image',
            'vageverify' => 'Age Verification',
            'ebottledeposit' => 'Bottle Deposit',
            'nbottledepositamt' => 'Bottle Deposit Amt',
            'vbarcodetype' => 'Barcode Type',
            'ntareweight' => 'Tare Weight',
            'ntareweightper' => 'Tare Weight Per',
            'dcreated' => 'Created',
            'dlastupdated' => 'Last Updated',
            'dlastreceived' => 'Last Received',
            'dlastordered' => 'Last Ordered',
            'nlastcost' => 'Last Cost',
            'nonorderqty' => 'On Order Qty',
            'vparentitem' => 'Parent Item',
            'nchildqty' => 'Child Qty',
            'vsize' => 'Size',
            'npack' => 'Unit Per Case',
            'nunitcost' => 'Unit Cost',
            'ionupload' => 'On upload',
            'nsellunit' => 'Selling Unit',
            'ilotterystartnum' => 'Lottery Start Num',
            'ilotteryendnum' => 'Lottery End Num',
            'etransferstatus' => 'Transfer status',
            'vsequence' => 'Sequence',
            'vcolorcode' => 'Color Code',
            'vdiscount' => 'Discount',
            'norderqtyupto' => 'Order Qty Upto',
            'vshowsalesinzreport' => 'Sales Item',
            'iinvtdefaultunit' => 'Invt. Default Unit',
            'stationid' => 'Station',
            'shelfid' => 'Shelf',
            'aisleid' => 'Aisle',
            'shelvingid' => 'Shelving',
            'rating' => 'Rating',
            'vintage' => 'Vintage',
            'PrinterStationId' => 'Printer Station Id',
            'liability' => 'Liability',
            'isparentchild' => 'Is Parent Child',
            'parentid' => 'Parent Id',
            'parentmasterid' => 'Parent Master Id',
            'wicitem' => 'WIC Item',
            'gross_profit'=>'Gross Profit',
        );

        Session::flash('message', 'Success: You have modified Settings!');
		return view('itemlistdisplays.itemlistdisplay')->with($data);
        // return redirect('itemlistdisplay')->with('message','Success: You have modified Settings!');
    }
}