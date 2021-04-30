<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Department;
use App\Model\Category;
use App\Model\SalesAnalyticsReport;
use App\Model\ZeroMovement;
use PDF;
use DateTime;
use Illuminate\Support\Facades\Session;

class SalesAnalyticsReportController extends Controller
{
    public function index(Request $request) {
        return Self::getlist($request);
    }

    public static function getlist(Request $request){
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        $url = '';
        $input = $request->all();
        $SalesAnalyticsReport = new SalesAnalyticsReport();
        $category = new Category();
        $data['sid'] = $request->session()->get('sid');

        if ($request->isMethod('post')) {
            // print_r($input);die;
            $reports_data = $SalesAnalyticsReport->getSalesAnalyticsReport($input);
            $reportsdata = json_decode(json_encode($reports_data), true); 
            $out=array();
            foreach($reportsdata as  $x){
                if(isset($input['subcategory']) && !empty($input['subcategory']) && isset($input['category']) && !empty($input['category']) && isset($input['department']) && !empty($input['department']) ){
                    
                    $data['filters_selected'] = 'All';
                    
                    $out[$x['vdepartmentname']]['vdepname']=$x['vdepartmentname'];
                    $out[$x['vdepartmentname']][$x['vcategoryname']]['vcatname']=$x['vcategoryname'];
                    $out[$x['vdepartmentname']][$x['vcategoryname']][$x['subcat_name']]['subcat_name']=$x['subcat_name'];
                    $out[$x['vdepartmentname']][$x['vcategoryname']][$x['subcat_name']]['details'][]=array('vitemname'=>$x['vitemname'],'totalCost'=>$x['totalCost'], 'totalPrice'=>$x['totalPrice'], 'totalqty'=>$x['qty']);
                    
                }
                if((!isset($input['subcategory']) || $input['subcategory'] == 'null') && isset($input['category']) && !empty($input['category']) && isset($input['department']) && !empty($input['department'])){
                    
                    $data['filters_selected'] = 'NoSubcategory';
                    
                    $out[$x['vdepartmentname']]['vdepname']=$x['vdepartmentname'];
                    $out[$x['vdepartmentname']][$x['vcategoryname']]['vcatname']=$x['vcategoryname'];
                    $out[$x['vdepartmentname']][$x['vcategoryname']]['details'][]=array('vitemname'=>$x['vitemname'],'totalCost'=>$x['totalCost'], 'totalPrice'=>$x['totalPrice'], 'totalqty'=>$x['qty']);
                    
                }
                if((!isset($input['category']) || $input['category'] == 'null') && isset($input['department']) && !empty($input['department'])){
                    
                    $data['filters_selected'] = 'NoCategory';
                    
                    $out[$x['vdepartmentname']]['vdepname']=$x['vdepartmentname'];
                    $out[$x['vdepartmentname']]['details'][]=array('vitemname'=>$x['vitemname'],'totalCost'=>$x['totalCost'], 'totalPrice'=>$x['totalPrice'], 'totalqty'=>$x['qty']);
                    
                }
            }

            $data['out'] = $out;
            $data['reports'] = $reportsdata;
            $data['p_start_date'] = $input['start_date'];
            $data['p_end_date'] = $input['end_date'];            
            $data['department'] = $input['department'];        
            $dept_code['dept_code'] = $input['department'];
            // echo "<pre>";
            // print_r($dept_code);
            // die;
            
            if(isset($data['filters_selected'])){
                Session::put('filters_selected', $data['filters_selected']);
            }
            
            $store_data=$SalesAnalyticsReport->getStore();
            $store = $store_data[0];
            $request->session()->put('storename', $store['vstorename']);
            $request->session()->put('storeaddress', $store['vaddress1']);
            $request->session()->put('storephone', $store['vphone1']);
            
            Session::put('out', $data['out']);
            Session::put('reports', $data['reports']);
            Session::put('p_start_date', $input['start_date']);
            Session::put('p_end_date', $input['end_date']);
            

            $data['categories'] = $category->getCategoriesInDepartment1($dept_code);
            // echo "<pre>";
            // print_r($data['categories']);die;
             
        }else{
            $data['category'] = "All";
            $data['subcategory'] = "All";
        }    
            
        
        if(isset($input['category'])){
            // echo "<pre>";
            // print_r($input['category']);die;
            $data['category1'] = $input['category'];
            $cat_id['cat_id'] = $input['category'];
            $subcategories = $category->getSubCategories1($cat_id);
            $data['subcategories'] = $subcategories;
        }else{
            // $data['category'] = '';
            $data['category'] = array();
        }
        
        if(isset($input['subcategory'])){
            
            $data['subcategory1'] = $input['subcategory'];
            
        }else{
            // $data['subcategory'] = '';
            $data['category'] = array();
        }

        $department = new Department();
        $departments_data = $department->getDepartments();
        $data['departments'] = json_decode(json_encode($departments_data), true); 

        $data['store_name'] = $request->session()->get('storeName');        
        $data['storename'] = $request->session()->get('storeName');   
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');

        $data['totalamount1'] = 0;
        $data['total_qty_sold1'] = 0;
        $data['total_total_cost1'] = 0;
        $data['total_total_price1'] = 0;
        $data['total_gross_profit_percentage'] = 0;
        $data['total_gross_profit1'] = 0; 
        
        // echo "<pre>";
        // print_r($data);
        // die;
        return view('salesanalyticsreports.sales_analytics_report')->with($data);

    }

    public function get_subcategories(Request $request){
        $input = $request->all();
        $data = array();
        $zeromovement = new ZeroMovement();     
        $data = $zeromovement->getSubCategories1($input);
            $response = [];
            $obj= new \stdClass();
		    $obj->id = "All";
		    $obj->text = "All";
		    array_push($response, $obj);
			foreach($data as $k => $v){			    
			    $obj= new \stdClass();
			    $obj->id = $v['subcat_id'];
			    $obj->text = $v['subcat_name'];
			    array_push($response, $obj);
			}
	    echo json_encode($response);
    }
    
    public function getcategories(Request $request){
        $input = $request->all();
		$data = array();
        $category = new Category();          
        $data = $category->getCategoriesInDepartment1($input);			
            $response = [];
            $obj= new \stdClass();
            $obj->id = "All";
            $obj->text = "All";
		    array_push($response, $obj);                    
			foreach($data as $k => $v){
			    $obj= new \stdClass();
			    $obj->id = $v['vcategorycode'];
			    $obj->text = $v['vcategoryname'];
			    array_push($response, $obj);
			}
	       echo json_encode($response);
    }
    

    public function pdf_save_page(Request $request) {
        // echo "asdfasdf";die;
        error_reporting(0);
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        $data['out'] = Session::get('out');
        $data['reports'] = Session::get('reports');
        $data['storename'] = Session::get('storename');
        $data['storeaddress'] = Session::get('storeaddress');
        $data['storephone'] = Session::get('storephone');
        $data['filters_selected'] = Session::get('filters_selected');
        
        if(!empty(Session::get('p_start_date'))){
            $data['p_start_date'] = Session::get('p_start_date');
        }else{
            $data['p_start_date'] = date("m-d-Y");
        }

        if(!empty(Session::get('p_end_date'))){
            $data['p_end_date'] = Session::get('p_end_date');
        }else{
            $data['p_end_date'] = date("m-d-Y");
        }
        
        $data['heading_title'] = 'Sales Analytics Report';
        $data['totalamount1'] = 0;
        $data['total_qty_sold1'] = 0;
        $data['total_total_cost1'] = 0;
        $data['total_total_price1'] = 0;
        $data['total_gross_profit_percentage'] = 0;
        $data['total_gross_profit1'] = 0; 
        // ob_clean($data);
        // flush($data);
        // echo "<pre>";
        // print_r($data);
        // die;
        
        $pdf = PDF::loadView('salesanalyticsreports.print_anylitics',$data);
        return $pdf->download('Profit-and-loss-Report.pdf');
    }

 public function get_csv() {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        $report_data = Session::get('out');
        $filters_selected = Session::get('filters_selected');
        $data_row = '';
        $data_row .= "Sales Analytics".PHP_EOL;
        $data_row .= "From : ". Session::get('p_start_date'). ' ' . "To : ".Session::get('p_end_date').PHP_EOL;
        // $data_row .= "Store Name: ".Session::get('storename').PHP_EOL;
        // $data_row .= "Store Address: ".Session::get('storeaddress').PHP_EOL;
        // $data_row .= "Store Phone: ".Session::get('storephone').PHP_EOL;
        $data_row .= PHP_EOL;
        $data_row .= "Dep./Cat./Sub Cat. Name ".', '."Total Amount".',	'."Qty Sold".','."Ext Cost Price".','."Ext Unit Price".','."Gross Profit(%)".PHP_EOL;
        $total_qty_sold1 =0;
        $total_total_cost1 =0;
        $total_total_price1 =0;
        $total_gross_profit1 =0;
        $total_total_price_new =0;
        
        $total_qty = $total_ext_cost = $total_unit_price = 0;
        if(isset($report_data) && count($report_data) > 0){
            foreach($report_data as $data){
                
                if(isset($filters_selected) && $filters_selected == 'NoCategory') {
                    if(isset($data['vdepname'])){
                        
                    $data_row .= str_replace(',',' ',$data['vdepname']).PHP_EOL;
                    $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit =$total_total_pricesub= 0;
                    foreach($data['details'] as $items)
                    {
                       
                        $total_qty_sold += $items['totalqty']; $total_total_cost += $items['totalCost']; 
                        $total_total_pricesub+=$items['totalPrice'];
                             if($items['totalqty']!=0){
                                      $total_total_price += ($items['totalPrice']/$items['totalqty']);     
                                }
                                else{
                                    $total_total_price += 0;       
                                 }
                        
                        $data_row .= str_replace(',',' ',$items['vitemname']) . ', ';
                        $data_row .= str_replace(',',' ',$items['totalPrice']) . ', ';
                        $data_row .= str_replace(',',' ',$items['totalqty']) . ', ';
                        $data_row .= str_replace(',',' ',$items['totalCost']) . ', ';
                        $data_row .= str_replace(',',' ',$items['totalPrice']/$items['totalqty']) . ', ';
                        $data_row .= str_replace(',',' ',number_format(((($items['totalPrice'] - $items['totalCost'])/$items['totalPrice'])*100),2)) .PHP_EOL;
                    
                        }    
                        
                    
                    $subtotalgrossprofit = $total_total_pricesub - $total_total_cost;
                    
                    if($total_total_price!=0){
                    $subtotalgrossprofitpercentage=number_format(($subtotalgrossprofit/$total_total_pricesub)*100,2);    
                    }
                    else{
                        $subtotalgrossprofitpercentage=0;


                    }
                    $data_row .= "Sub Total : ".','.$total_total_pricesub.','.$total_qty_sold.', '.$total_total_cost.','.$total_total_price.','.str_replace( ',', '', $subtotalgrossprofitpercentage ).PHP_EOL;
                    
                    $total_qty_sold1+=$total_qty_sold;
                    $total_total_cost1+=$total_total_cost;
                    $total_total_price1+=$total_total_pricesub;
                    $total_total_price_new+=$total_total_price;
                    $total_gross_profit1+=$subtotalgrossprofit;
                    if($total_total_price1!=0){
                    $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
                    
                    }
                    else{
                        $total_gross_profit_percentage=0;
                    }
                }
                }
                
                if(isset($filters_selected) && $filters_selected == 'NoSubcategory') {
                    
                    if(isset($data['vdepname'])){
                        $data_row .= str_replace(',',' ',$data['vdepname']).PHP_EOL;
                            
                        $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit =$total_total_pricesub= 0;
                            
                        foreach($data as $cat){
                            
                            if(isset($cat['vcatname'])){
                                $data_row .= str_replace(',',' ',$cat['vcatname']).PHP_EOL;
                                    
                                foreach($cat['details'] as $items)
                                {
                                    $total_qty_sold += $items['totalqty']; $total_total_cost += $items['totalCost']; 
                                    // $total_total_price += $items['totalPrice'];
                                     $total_total_pricesub+=$items['totalPrice'];
                                     if($items['totalqty']!=0){
                                              $total_total_price += ($items['totalPrice']/$items['totalqty']);     
                                        }
                                        else{
                                            $total_total_price += 0;       
                                         }
                                    
                                    $data_row .= str_replace(',',' ',$items['vitemname']) . ', ';
                                    $data_row .= str_replace(',',' ',$items['totalPrice']) . ', ';
                                    $data_row .= str_replace(',',' ',$items['totalqty']) . ', ';
                                    $data_row .= str_replace(',',' ',$items['totalCost']) . ', ';
                                    $data_row .= str_replace(',',' ',$items['totalPrice']) . ', ';
                                    $data_row .= str_replace(',',' ',number_format(((($items['totalPrice'] - $items['totalCost'])/$items['totalPrice'])*100),2)) .PHP_EOL;
                                    
                                }
                                
                                $subtotalgrossprofit = $total_total_pricesub - $total_total_cost;
                                $subtotalgrossprofitpercentage=number_format(($subtotalgrossprofit/$total_total_pricesub)*100,2);    
                                
                                // $data_row .= "Sub Total : ".','.$total_qty_sold.','.$total_total_cost.','.$total_total_price.','.$subtotalgrossprofitpercentage.PHP_EOL;
                            
                                $data_row .= "Sub Total : ".','.$total_total_pricesub.','.$total_qty_sold.', '.$total_total_cost.','.$total_total_price.','.$subtotalgrossprofitpercentage.PHP_EOL;
                    
                            }
                        }
                            
                        $total_qty_sold1+=$total_qty_sold;
                        $total_total_cost1+=$total_total_cost;
                        
                        $total_total_price1+=$total_total_pricesub;
                        $total_total_price_new+=$total_total_price;
                          $total_gross_profit1+=$subtotalgrossprofit;
                        
                        $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
                    }
                }
                
                if(isset($filters_selected) && $filters_selected == 'All') {
                    
                    if(isset($data['vdepname'])){
                        $data_row .= str_replace(',',' ',$data['vdepname']).PHP_EOL;
                            
                        $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = $total_total_pricesub=0;
                            
                        foreach($data as $cat){
                            
                            if(isset($cat['vcatname'])){
                                $data_row .= str_replace(',',' ',$cat['vcatname']).PHP_EOL;
                                    
                                    foreach($cat as $subcat) {
                                        if(isset($subcat['subcat_name'])){
                                            
                                            $data_row .= str_replace(',',' ',$subcat['subcat_name']).PHP_EOL;
                                            foreach($subcat['details'] as $items)
                                            {
                                                $total_qty_sold += $items['totalqty']; $total_total_cost += $items['totalCost'];
                                                                                            
                                                $total_total_pricesub+=$items['totalPrice'];
                                                 if($items['totalqty']!=0){
                                                          $total_total_price += ($items['totalPrice']/$items['totalqty']);     
                                                    }
                                                    else{
                                                        $total_total_price += 0;       
                                                     }
                                                $data_row .= str_replace(',',' ',$items['vitemname']) . ', ';
                                                $data_row .= str_replace(',',' ',$items['totalPrice']) . ', ';
                                                $data_row .= str_replace(',',' ',$items['totalqty']) . ', ';
                                                $data_row .= str_replace(',',' ',$items['totalCost']) . ', ';
                                                $data_row .= str_replace(',',' ',$items['totalPrice']) . ', ';
                                                $data_row .= str_replace(',',' ',number_format(((($items['totalPrice'] - $items['totalCost'])/$items['totalPrice'])*100),2)) .PHP_EOL;
                                                
                                            }
                                            
                                            $subtotalgrossprofit = $total_total_pricesub - $total_total_cost;
                                            $subtotalgrossprofitpercentage=number_format(($subtotalgrossprofit/$total_total_pricesub)*100,2);    
                                            
                                            // $data_row .= "Sub Total : ".','.$total_qty_sold.','.$total_total_cost.','.$total_total_price.','.$subtotalgrossprofitpercentage.PHP_EOL;
                                        $data_row .= "Sub Total : ".','.$total_total_pricesub.','.$total_qty_sold.', '.$total_total_cost.','.$total_total_price.','.$subtotalgrossprofitpercentage.PHP_EOL;
                    
                                        }
                                    }
                            }
                        }
                            
                        $total_qty_sold1+=$total_qty_sold;
                        $total_total_cost1+=$total_total_cost;
                        $total_total_price1+=$total_total_pricesub;
                        $total_total_price_new+=$total_total_price;
                        $total_gross_profit1+=$subtotalgrossprofit;
                        
                        $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
                    }
                }
            } 
              $data_row .= "Total : ".','.$total_total_price1.','.$total_qty_sold1.', '.$total_total_cost1.', '.$total_total_price_new.', '.number_format($total_gross_profit_percentage,2).PHP_EOL;
              
        }else{
            $data_row .= 'Sorry no data found!'.PHP_EOL;
        }
        Session::put('data_row', $data_row);
        $file_name_csv  = 'sales-analytics-report.csv';
        $file_path =public_path('image/sales-analytics-report.csv');
        $myfile = fopen(public_path('image/sales-analytics-report.csv'), "w");

        fwrite($myfile,$data_row);
        fclose($myfile);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    }
    // public function get_csv() {
    //     ini_set('max_execution_time', 0);
    //     ini_set("memory_limit", "2G");

    //     $report_data = Session::get('out');
    //     $filters_selected = Session::get('filters_selected');
    //     $data_row = '';
    //     $data_row .= "Sales Analytics".PHP_EOL;
    //     $data_row .= "From : ". Session::get('p_start_date'). ' ' . "To : ".Session::get('p_end_date').PHP_EOL;
    //     $data_row .= "Store Name: ".Session::get('storename').PHP_EOL;
    //     $data_row .= "Store Address: ".Session::get('storeaddress').PHP_EOL;
    //     $data_row .= "Store Phone: ".Session::get('storephone').PHP_EOL;
    //     $data_row .= PHP_EOL;
    //     $data_row .= "Dep./Cat./Sub Cat. Name ".','."Qty Sold".','."Ext Cost Price".','."Ext Unit Price".','."Gross Profit(%)".PHP_EOL;
    //     $total_qty_sold1 =0;
    //     $total_total_cost1 =0;
    //     $total_total_price1 =0;
    //     $total_gross_profit1 =0;
        
    //     $total_qty = $total_ext_cost = $total_unit_price = 0;
    //     if(isset($report_data) && count($report_data) > 0){
    //         foreach($report_data as $data){
                
    //             if(isset($filters_selected) && $filters_selected == 'NoCategory') {
                    
    //                 $data_row .= str_replace(',',' ',$data['vdepname']).PHP_EOL;
    //                 $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = 0;
    //                 foreach($data['details'] as $items)
    //                 {
    //                     $total_qty_sold += $items['totalqty']; $total_total_cost += $items['totalCost']; $total_total_price += $items['totalPrice'];                        
    //                     $data_row .= str_replace(',',' ',$items['vitemname']) . ', ';
    //                     $data_row .= str_replace(',',' ',$items['totalqty']) . ', ';
    //                     $data_row .= str_replace(',',' ',$items['totalCost']) . ', ';
    //                     $data_row .= str_replace(',',' ',$items['totalPrice']) . ', ';
    //                     $data_row .= str_replace(',',' ',number_format(((($items['totalPrice'] - $items['totalCost'])/$items['totalPrice'])*100),2)) .PHP_EOL;
    //                 }
                    
    //                 $subtotalgrossprofit = $total_total_price - $total_total_cost;
    //                 $subtotalgrossprofitpercentage=number_format(($subtotalgrossprofit/$total_total_price)*100,2);    
                    
    //                 $data_row .= "Sub Total : ".','.$total_qty_sold.','.$total_total_cost.','.$total_total_price.','.$subtotalgrossprofitpercentage.PHP_EOL;
                    
    //                 $total_qty_sold1+=$total_qty_sold;
    //                 $total_total_cost1+=$total_total_cost;
    //                 $total_total_price1+=$total_total_price;
    //                 $total_gross_profit1+=$subtotalgrossprofit;
                    
    //                 $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
    //             }
                
    //             if(isset($filters_selected) && $filters_selected == 'NoSubcategory') {
                    
    //                 if(isset($data['vdepname'])){
    //                     $data_row .= str_replace(',',' ',$data['vdepname']).PHP_EOL;
                            
    //                     $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = 0;
                            
    //                     foreach($data as $cat){
                            
    //                         if(isset($cat['vcatname'])){
    //                             $data_row .= str_replace(',',' ',$cat['vcatname']).PHP_EOL;
                                    
    //                             foreach($cat['details'] as $items)
    //                             {
    //                                 $total_qty_sold += $items['totalqty']; $total_total_cost += $items['totalCost']; $total_total_price += $items['totalPrice'];
                                    
    //                                 $data_row .= str_replace(',',' ',$items['vitemname']) . ', ';
    //                                 $data_row .= str_replace(',',' ',$items['totalqty']) . ', ';
    //                                 $data_row .= str_replace(',',' ',$items['totalCost']) . ', ';
    //                                 $data_row .= str_replace(',',' ',$items['totalPrice']) . ', ';
    //                                 $data_row .= str_replace(',',' ',number_format(((($items['totalPrice'] - $items['totalCost'])/$items['totalPrice'])*100),2)) .PHP_EOL;
                                    
    //                             }
                                
    //                             $subtotalgrossprofit = $total_total_price - $total_total_cost;
    //                             $subtotalgrossprofitpercentage=number_format(($subtotalgrossprofit/$total_total_price)*100,2);    
                                
    //                             $data_row .= "Sub Total : ".','.$total_qty_sold.','.$total_total_cost.','.$total_total_price.','.$subtotalgrossprofitpercentage.PHP_EOL;
    //                         }
    //                     }
                            
    //                     $total_qty_sold1+=$total_qty_sold;
    //                     $total_total_cost1+=$total_total_cost;
    //                     $total_total_price1+=$total_total_price;
    //                     $total_gross_profit1+=$subtotalgrossprofit;
                        
    //                     $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
    //                 }
    //             }
                
    //             if(isset($filters_selected) && $filters_selected == 'All') {
                    
    //                 if(isset($data['vdepname'])){
    //                     $data_row .= str_replace(',',' ',$data['vdepname']).PHP_EOL;
                            
    //                     $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = 0;
                            
    //                     foreach($data as $cat){
                            
    //                         if(isset($cat['vcatname'])){
    //                             $data_row .= str_replace(',',' ',$cat['vcatname']).PHP_EOL;
                                    
    //                                 foreach($cat as $subcat) {
    //                                     if(isset($subcat['subcat_name'])){
                                            
    //                                         $data_row .= str_replace(',',' ',$subcat['subcat_name']).PHP_EOL;
    //                                         foreach($subcat['details'] as $items)
    //                                         {
    //                                             $total_qty_sold += $items['totalqty']; $total_total_cost += $items['totalCost']; $total_total_price += $items['totalPrice'];                                                
    //                                             $data_row .= str_replace(',',' ',$items['vitemname']) . ', ';
    //                                             $data_row .= str_replace(',',' ',$items['totalqty']) . ', ';
    //                                             $data_row .= str_replace(',',' ',$items['totalCost']) . ', ';
    //                                             $data_row .= str_replace(',',' ',$items['totalPrice']) . ', ';
    //                                             $data_row .= str_replace(',',' ',number_format(((($items['totalPrice'] - $items['totalCost'])/$items['totalPrice'])*100),2)) .PHP_EOL;
                                                
    //                                         }
                                            
    //                                         $subtotalgrossprofit = $total_total_price - $total_total_cost;
    //                                         $subtotalgrossprofitpercentage=number_format(($subtotalgrossprofit/$total_total_price)*100,2);    
                                            
    //                                         $data_row .= "Sub Total : ".','.$total_qty_sold.','.$total_total_cost.','.$total_total_price.','.$subtotalgrossprofitpercentage.PHP_EOL;
                                        
    //                                     }
    //                                 }
    //                         }
    //                     }
                            
    //                     $total_qty_sold1+=$total_qty_sold;
    //                     $total_total_cost1+=$total_total_cost;
    //                     $total_total_price1+=$total_total_price;
    //                     $total_gross_profit1+=$subtotalgrossprofit;
                        
    //                     $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
    //                 }
    //             }
    //         } 
    //           $data_row .= "Total : ".','.$total_qty_sold1.', '.$total_total_cost1.', '.$total_total_price1.', '.number_format($total_gross_profit_percentage,2).PHP_EOL;
              
    //     }else{
    //         $data_row .= 'Sorry no data found!'.PHP_EOL;
    //     }
    //     Session::put('data_row', $data_row);
    //     $file_name_csv  = 'sales-analytics-report.csv';
    //     $file_path =public_path('image/sales-analytics-report.csv');
    //     $myfile = fopen(public_path('image/sales-analytics-report.csv'), "w");

    //     fwrite($myfile,$data_row);
    //     fclose($myfile);

    //     $content = file_get_contents ($file_path);
    //     header ('Content-Type: application/octet-stream');
    //     header('Content-Disposition: attachment; filename='.basename($file_name_csv));
    //     echo $content;
    //     exit;
    // }

    public function print_page() {
    
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
            
        $data['out'] = Session::get('out');
        $data['reports'] = Session::get('reports');
        $data['storename'] = Session::get('storename');
        $data['storeaddress'] = Session::get('storeaddress');
        $data['storephone'] = Session::get('storephone');
        $data['filters_selected'] = Session::get('filters_selected');
        
        if(!empty(Session::get('p_start_date'))){
            $data['p_start_date'] = Session::get('p_start_date');
        }else{
            $data['p_start_date'] = date("m-d-Y");
        }
        if(!empty(Session::get('p_end_date'))){
            $data['p_end_date'] = Session::get('p_end_date');
        }else{
            $data['p_end_date'] = date("m-d-Y");
        }
        $data['heading_title'] = 'Sales Analytics';
        $data['totalamount1'] = 0;
        $data['total_qty_sold1'] = 0;
        $data['total_total_cost1'] = 0;
        $data['total_total_price1'] = 0;
        $data['total_gross_profit_percentage'] = 0;
        $data['total_gross_profit1'] = 0; 
        // echo "<pre>";
        // print_r($data);
        // die;
        return view('salesanalyticsreports.print_anylitics',$data);  
    }
}


?>