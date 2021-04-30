<?php

namespace App\Http\Controllers;

use App\Model\ProductListing;
use Illuminate\Http\Request;

class ProductListingController extends Controller {
    
    public function index(Request $request) {
        return Self::getlist($request);
    }
    
    protected static function getList(Request $request) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        if ($request->isMethod('post')) {
            $productlisting = new ProductListing();
            $reports = $productlisting->getProductListingReport();

            $store_data=$productlisting->getStore();
            $store = $store_data[0];
            $request->session()->put('storename', $store['vstorename']);
            $request->session()->put('storeaddress', $store['vaddress1']);
            $request->session()->put('storephone', $store['vphone1']);
            

            $data_row = '';
            
            $data_row .= "Store Name: ".$request->session()->get('storename').PHP_EOL;
            $data_row .= "Store Address: ".$request->session()->get('storeaddress').PHP_EOL;
            $data_row .= "Store Phone: ".$request->session()->get('storephone').PHP_EOL;
            $data_row .= PHP_EOL;

            if(isset($reports) && count($reports) > 0){
                $data_row .= 'Item Type,SKU,Item Name,QOH,Selling Price,Unit Per Case,Department,Category,Size'.PHP_EOL;

                foreach ($reports as $key => $value) {
                $data_row .= $value['vitemtype'].','.$value['vbarcode'].','.str_replace(',',' ',$value['vitemname']).','.$value['qoh'].','.$value['dunitprice'].','.$value['npack'].','.$value['vdepartmentname'].','.$value['vcategoryname'].','.$value['vsize'].PHP_EOL;
                }

            }else{
                $data_row = 'Sorry no data found!';
            }

            $file_name_csv = $request->session()->get('storename') . '-product-list.csv';

            $file_path =public_path('image/product-list.csv');
            $myfile = fopen(public_path('image/product-list.csv'), "w");

            fwrite($myfile,$data_row);
            fclose($myfile);

            $content = file_get_contents ($file_path);
            header ('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file_name_csv));
            echo $content;
            exit;
        }
        $data['store_name'] = $request->session()->get('storename'); 

        return view('productlisting.product_listing')->with($data);
    }    

    public function check_password(Request $request){     
        $input = $request->all();
        if ($request->isMethod('post')) {
            if(isset($input['password']) && $input['password'] != ""){
                if($input['password'] === "Alberta@POS"){
                    echo "true";
                }
                else{
                    echo "false";
                }
            }
        }
    }
}
