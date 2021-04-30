<?php 

namespace App\Http\Controllers;

use App\Model\ItemSummary;
use Illuminate\Http\Request;
// use App\Model\mst_department;
// use App\Model\mst_category;
// use App\Model\mst_item;
// use App\Model\SalesAnalyticsReport;
use PDF;
use DateTime;
// use Session;
use Illuminate\Support\Facades\Session;

class ItemSummaryController extends Controller
{
    public function index(Request $request) {
        return Self::getlist($request);
    }

    public static function getlist(Request $request){
        $input = $request->all();
        error_reporting(E_ALL); ini_set('display_errors', 1); 
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $url = '';
        $itemsummary = new ItemSummary();

        if ($request->isMethod('post')) {
            $data['item_summary'] = $itemsummary->getItemSummaryReport($input);
           if(empty($data['item_summary'])){
               $data['data_error'] = "No data found!!!";
               return view('itemsummary.itemsummary')->with($data);
           }
            foreach($data['item_summary'] as  $x){
              $out[$x['catagoryname']]['catagoryname']=$x['catagoryname'];
              $out[$x['catagoryname']]['details'][]=array('sku'=>$x['sku'],'itemname'=>$x['itemname'],'qtysold'=>$x['qtysold'],'amount'=>$x['amount'],'avgprice'=>$x['avgprice'],'sodate'=>$x['sodate'],'eodate'=>$x['eodate']);
            }
            $data['out'] = $out;            
            $data['p_start_date'] = $input['start_date'];
            $data['p_end_date'] = $input['end_date'];                     
            Session::put('p_start_date', $data['p_start_date']);
            Session::put('p_end_date', $data['p_end_date']);  
            Session::put('item_summary', $data['item_summary']);
            Session::put('out', $data['out']);     
        }
        
        $store_data=$itemsummary->getStore();
        $store = $store_data[0];
        $request->session()->put('storename', $store['vstorename']);
        $request->session()->put('storeaddress', $store['vaddress1']);
        $request->session()->put('storephone', $store['vphone1']);
        
        $data['store_name'] = $request->session()->get('storeName');        
        $data['storename'] = $request->session()->get('storeName');   
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');

        return view('itemsummary.itemsummary')->with($data);
    }

    public function reportdata(){
        $return = array();
    
        $this->load->model('administration/cash_sales_summary');
    
        if(!empty($this->request->get['report_by'])){
          if($this->request->get['report_by'] == 1){
            $datas = $this->model_administration_cash_sales_summary->getCategories();
          }elseif($this->request->get['report_by'] == 2){
            $datas = $this->model_administration_cash_sales_summary->getDepartments();
          }
    
          $return['code'] = 1;
          $return['data'] = $datas;
        }else{
          $return['code'] = 0;
        }
        echo json_encode($return);
        exit;  
      }

    public function csv_export(Request $request) {

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        $report_data = $request->session()->get('out');
        $data_row = '';
        $data_row .= "Item Summary".PHP_EOL;
        $data_row .= "From : ".$request->session()->get('p_start_date'). ' ' . "To : ".$request->session()->get('p_end_date').PHP_EOL;
        $data_row .= "Store Name: ".$request->session()->get('storename').PHP_EOL;
        $data_row .= "Store Address: ".$request->session()->get('storeaddress').PHP_EOL;
        $data_row .= "Store Phone: ".$request->session()->get('storephone').PHP_EOL;
        $data_row .= PHP_EOL;
        
        $data_row .= "Sku ".','."Item Name".','." ".','."Qty Sold".','."Avg. Price".','."Amount".PHP_EOL;
        $grand_total_qty = $grand_total_amount = 0;
        if(isset($report_data) && count($report_data) > 0){
            foreach($report_data as $data){
                $data_row .= str_replace(',',' ',$data['catagoryname']).PHP_EOL;
                $total_qty = $total_amount = 0;
                foreach($data['details'] as $items)
                {
                    $total_qty      += $items['qtysold']; 
                    $total_amount   += $items['amount'];
                    
                    $data_row .= str_replace(',',' ',$items['sku']) . ', ';
                    $data_row .= str_replace(',',' ',$items['itemname']) . ', ';
                    $data_row .= ', '.str_replace(',',' ',$items['qtysold']) . ', ';
                    $data_row .= str_replace(',',' ',$items['avgprice']) . ', ';
                    $data_row .= str_replace(',',' ',$items['amount']).PHP_EOL;
                    
                }
                $data_row .= "".','."".','."Sub Total : ".','.str_replace(',',' ',$total_qty)."".','."".','.str_replace(',',' ',$total_amount).PHP_EOL;
                $grand_total_qty    += $total_qty;
                $grand_total_amount += $total_amount;
            }
            $data_row .= "".','."".','."Grand Total : ".','.str_replace(',',' ',$grand_total_qty)."".','."".','.str_replace(',',' ',$grand_total_amount).PHP_EOL;
        }else{
            $data_row .= 'Sorry no data found!'.PHP_EOL;
        }

        $file_name_csv = 'item-summary-report.csv';

        $file_path = public_path('image/item-summary-report.csv');

        $myfile = fopen(public_path('image/item-summary-report.csv'), "w");

        fwrite($myfile,$data_row);
        fclose($myfile);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    }


  public function print_page(Request $request) {
        $input = $request->all();
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");   
        $data['item_summary'] = $request->session()->get('item_summary'); 
        $data['out'] = $request->session()->get('out');
        $data['storename'] = $request->session()->get('storename');
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');
        
        if(!empty($request->session()->get('p_start_date'))){
            $data['p_start_date'] = $request->session()->get('p_start_date');
            $data['p_end_date'] = $request->session()->get('p_end_date');
        }else{
            $data['p_start_date'] = date("m-d-Y");
        }
        $data['heading_title'] = 'Item Summary';
        return view('itemsummary.print_item_summary_page',$data);    
  }

  public function pdf_save_page(Request $request) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    ini_set('max_execution_time', 0);
    ini_set("memory_limit", "2G");
   
    $data['item_summary'] = $request->session()->get('item_summary'); 
    $data['out'] = $request->session()->get('out');
    $data['storename'] = $request->session()->get('storename');
    $data['storeaddress'] = $request->session()->get('storeaddress');
    $data['storephone'] = $request->session()->get('storephone');

    if(!empty($request->session()->get('p_start_date'))){
        $data['p_start_date'] = $request->session()->get('p_start_date');
        $data['p_end_date'] = $request->session()->get('p_end_date');
    }else{
        $data['p_start_date'] = date("m-d-Y");
    }
    $data['heading_title'] = 'Item Summary Report';
    $pdf = PDF::loadView('itemsummary.print_item_summary_page',$data);
    return $pdf->download('Items-summary-report.pdf');
  }
      
}


?>