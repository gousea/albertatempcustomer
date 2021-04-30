<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\SalesHistoryReport;
use PDF;
use DateTime;
// use Session;
use Illuminate\Support\Facades\Session;

class SalesHistoryReportController extends Controller{
    public function index(Request $request) {
        return Self::getlist($request);
    }
    public static function getlist(Request $request){
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();
        // echo "<pre>";
        // print_r($input);
        // die;
        
        $saleshistoryreport = new SalesHistoryReport();
        $data = [];
    	$url = '';
    	$result = [];
        if ($request->isMethod('post')) {
            $data['recd_inputs'] = 'yes';
            if(isset($input['include_current_week'])){
                $data['include_current_week'] = $input['include_current_week'];
            }
            if(isset($input['include_current_month'])){
                $data['include_current_month'] = $input['include_current_month'];
            }
            if(isset($input['item_name']) && !empty(trim($input['item_name'])) ){
                $data['item_name'] = $input['item_name'];
            }
            if(isset($input['barcode']) && !empty(trim($input['barcode']))){
                $data['barcode'] = $input['barcode'];
            }
            if(isset($input['size']) && !empty(trim($input['size']))){
                $data['size'] = $input['size'];
            }
            if(isset($input['supplier_code']) && $input['supplier_code'] !== 'all'){
                $data['vendors'] = $input['supplier_code'];
                // $vendors = $input['supplier_code'];
            }            
            if(isset($input['dept_code']) && $input['dept_code'] !== 'all'){
                $data['departments'] = $input['dept_code'];
            }
            if(isset($input['category_code']) && $input['category_code'] !== 'all'){
                $data['categories'] = $input['category_code'];
            }
            if(isset($input['sub_category_id']) && $input['sub_category_id'] !== 'all'){
                $data['sub_categories'] = $input['sub_category_id'];
            }
            if(isset($input['price_select_by'])){
                // echo "between";
                if($input['price_select_by'] === 'between'){        
                    // echo "between1";
                    $data['price_select_by'] = $input['price_select_by'];                    
                    if(isset($input['select_by_value_1']) && (!empty(trim($input['select_by_value_1'])) || $input['select_by_value_1'] !== 0)){
                        // echo "select_by_value_1";
                        $data['select_by_value_1'] = $input['select_by_value_1'];
                    }                    
                    if(isset($input['select_by_value_2']) && !empty(trim($input['select_by_value_2']))){
                        // echo "select_by_value_2";
                        $data['select_by_value_2'] = $input['select_by_value_2'];
                    }
                } elseif(($input['price_select_by'] === 'greater') || ($input['price_select_by'] === 'less') || ($input['price_select_by'] === 'equal')){
                    // echo $input['price_select_by'];
                   $data['price_select_by'] = $input['price_select_by'];
                   if(isset($input['select_by_value_1']) && !empty(trim($input['select_by_value_1']))){
                    //   echo $input['select_by_value_1'];
                        $data['select_by_value_1'] = $input['select_by_value_1'];
                    }                    
                }                
            }
            // echo "<pre>";
            // print_r($data);
            // die;
            $data['selected_select_by']             = $input['select_by'];
            
            if($input['select_by'] === 'm'){
                $data['months'] = $input['input_month'];
                $result = $saleshistoryreport->getMonthlyBreakup($data);
            } elseif($input['select_by'] === 'w') {
                $data['weeks'] = $input['input_week'];
                $result = $saleshistoryreport->getWeeklyBreakup($data);
            } elseif($input['select_by'] === 'y') {
                $data['year'] = $input['input_year'];
                $result = $saleshistoryreport->getYearlyBreakup($data);
            } elseif($input['select_by'] === 'c'){
                $data['custom_date_range'] = $input['custom_date_range'];
                $date_range = explode('-', $input['custom_date_range']);
                $data['from'] = $from = trim($date_range[0]);
                $from_date = DateTime::createFromFormat('m/d/Y h:i a', $from);
                $data['from_date'] = $from_date->format('Y-m-d H:i:s');
                
                $data['to'] = $to = trim($date_range[1]);
                $to = DateTime::createFromFormat('m/d/Y h:i a', $to);
                $data['to_date'] = $to->format('Y-m-d H:i:s');
                $result = $saleshistoryreport->getCustomBreakup($data);
            }       
        }else{
            $data['selected_select_by']             = 'w';
        }
        
        //Drop down departments
        $department_data = $saleshistoryreport->getDepartments();
        $departments = json_decode(json_encode($department_data), true); 
        // echo "<pre>";
        // print_r($data);
        // die;
        $departments_html ="";
        $departments_html = "<select class='' name=dept_code id=dept_code style=width: 100px;><option value=all>All</option>";
        foreach($departments as $department){
            if(isset($data['departments']) && $data['departments'] == $department['id']){
                $departments_html .= "<option value=".$department['id']." selected=selected>".$department['name']."</option>";
            } else {
                $departments_html .= "<option value=".$department['id'].">".$department['name']."</option>";
            }
        }
        $departments_html .="</select>";
        $data['departments'] = $departments_html;
        //Drop down categories
        $categories_html = "<select class='' name=category_code id=category_code style=width: 100px;><option value=all>All</option>";
        
        if(isset($input['dept_code'])){
            $get_categories = $saleshistoryreport->get_categories($input['dept_code']);
            foreach($get_categories as $category){
                if(isset($input['category_code']) && $input['category_code'] == $category['id']){
                    $categories_html .= "<option value=".$category['id']." selected=selected>".$category['text']."</option>";
                } else {
                    $categories_html .= "<option value=".$category['id'].">".$category['text']."</option>";
                }
            }   
        }
        $categories_html .="</select>";
        $data['categories'] = $categories_html;
        //Drop down sub categories
        $subcategories_html = "<select class='' name=sub_category_id id=sub_category_id style=width: 100px;><option value=all>All</option>";
        if(isset($input['category_code'])) {
            $get_subcategories = $saleshistoryreport->get_subcategories($input['category_code']);        
            foreach($get_subcategories as $subcategory){
                if(isset($input['sub_category_id']) && $input['sub_category_id'] == $subcategory['id']){
                    $subcategories_html .= "<option value=".$subcategory['id']." selected=selected>".$subcategory['text']."</option>";
                } else {
                    $subcategories_html .= "<option value=".$subcategory['id'].">".$subcategory['text']."</option>";
                }
            }
        }
        $subcategories_html .="</select>";
        $data['subcategories'] = $subcategories_html;
        //Drop down vendors
        $suppliers_data = $saleshistoryreport->getVendors();
        $suppliers = json_decode(json_encode($suppliers_data), true); 
        $supplier_html ="";
        $supplier_html = "<select class='' name=supplier_code id=supplier_code style=width: 100px;><option value=all>All</option>";
        foreach($suppliers as $supplier){
            $supplier_html .= "<option value=".$supplier['id']."";
            // echo "<pre>";
            // print_r($supplier['id']);die;
            if(isset($data['vendors']) && $data['vendors'] == $supplier['id']){
                $supplier_html .= " selected=selected> ".$supplier['name']."</option>";
            }
            $supplier_html .= ">".$supplier['name']."</option>";
        }
        $supplier_html .="</select>";
        $data['suppliers'] = $supplier_html;        
        //Drop down size
        $data['select_by_list'] = array(
                        'w' => 'By Week',
                        'm' => 'By Month',
                        'y' => 'By Year',
                        'c' => 'Custom'
                     );
        $price_select_by_list = array(
                                    'greater'   => 'Greater than',
                                    'less'      => 'Less than',
                                    'equal'     => 'Equal to',
                                    'between'   => 'Between'
                                );        
        $price_select_by_html = "<select class='' id=price_select_by name=price_select_by style=width:40%;>";
        foreach($price_select_by_list as $k => $v){
            $price_select_by_html .= "<option value=$k";
            if(isset($data['price_select_by']) && $k === $data['price_select_by']){
                $price_select_by_html .= " selected";
            }
            $price_select_by_html .= ">".$v."</option>";
        }
        $price_select_by_html .= "</select>";
        $price_select_by_html .= "<span id=selectByValuesSpan>";
        // echo "<pre>";
        // print_r($data);
        // die;
        if(isset($input['price_select_by']) === 'between'){
            $price_select_by_html .= "<input type=text autocomplete=off name=select_by_value_1 id=select_by_value_1 class=search_text_box_if placeholder=Enter Amt value=".$data['select_by_value_1']."/>";
            $price_select_by_html .= "<input type=text autocomplete=off name=select_by_value_2 id=select_by_value_2 class=search_text_box_if placeholder=Enter Amt value=".$data['select_by_value_2']."/>";
        } else {
            if(isset($data['select_by_value_1'])){
                $price_select_by_html .= "<input type=text autocomplete=off name=select_by_value_1 id=select_by_value_1 class=search_text_box_else placeholder=EnterAmount value=".$data['select_by_value_1'].">";
            }else{
                $price_select_by_html .= "<input type=text autocomplete=off name=select_by_value_1 id=select_by_value_1 class=search_text_box_else placeholder=EnterAmount value=".isset($data['select_by_value_1']).">";
            }
        }
        $price_select_by_html .= "</span>"; 
        $data['price_select_by'] = $price_select_by_html;
        $data['reports'] = $result;
    	
    	$data['heading_title']      = 'Sales History Report';
    	$data['token']              = $request->session()->get('_token');
        $data['storename']          = $request->session()->get('storename');
        $data['storeaddress']       = $request->session()->get('storeaddress');
        $data['storephone']         = $request->session()->get('storephone');

        // echo "<pre>";
        // print_r($data);
        // die;
        
         session()->put('session_data',  $data);
        return view('saleshistoryreports.sales_history_report')->with($data);
    }
    public function batchdata(Request $request){
        $saleshistoryreport = new SalesHistoryReport();
        if ($request->isMethod('post')) {
            $start_date = date('Y-m-d H:i:s', strtotime($_POST['start_date']));
            $end_date   = date('Y-m-d H:i:s', strtotime($_POST['end_date'])); 
            $batchdata = $saleshistoryreport->getBatches($start_date,$end_date);
            echo json_encode($batchdata);exit;
        }
    }
    public function reportdata(Request $request){
        $return = array();
        $saleshistoryreport = new SalesHistoryReport(); 
        if(!empty($this->request->get['report_by'])){
          if($this->request->get['report_by'] == 1){
            $datas = $saleshistoryreport->getCategories();
          }elseif($this->request->get['report_by'] == 2){
            $datas = $saleshistoryreport->getDepartments_list();
          }elseif($this->request->get['report_by'] == 3){
            $datas = $saleshistoryreport->getGroups();
          }
          $return['code'] = 1;
          $return['data'] = $datas;
        }else{
          $return['code'] = 0;
        }
        echo json_encode($return);
        exit;  
    }
    public function report_ajax_data(Request $request) {
        $json =array();
        $input = $request->all();
        $saleshistoryreport = new SalesHistoryReport(); 
    if ($request->isMethod('post')) {
            $temp_arr = $input;
            if($temp_arr['report_by'] == 1){
            $data = $saleshistoryreport->ajaxDataReportCategory($temp_arr);
            }else if($temp_arr['report_by'] == 2){
            $data = $saleshistoryreport->ajaxDataReportDepartment($temp_arr);
            }else if($temp_arr['report_by'] == 3){
            $data = $saleshistoryreport->ajaxDataReportItemGroup($temp_arr);
            }else{
            $data = $saleshistoryreport->ajaxDataReportDepartment($temp_arr);
            }
            echo json_encode($data);
            exit;
        }
    }      
    public function get_categories(Request $request){   
        $input = $request->all(); 
        $department_id = $input['dept_code'];
        $saleshistoryreport = new SalesHistoryReport();
        $get_categories = $saleshistoryreport->get_categories($department_id);
        $categories_html ="";
        $categories_html = "<select class='' name='category_code' id='category_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach($get_categories as $category){
            if(isset($selected_dept) && $selected_dept == $category['id']){
                $categories_html .= "<option value='".$category['id']."' selected='selected'>".$category['text']."</option>";
            } else {
                $categories_html .= "<option value='".$category['id']."'>".$category['text']."</option>";
            }
        }
        $categories_html .="</select>";
        echo $categories_html;
        exit; 
    }
    public function get_subcategories(Request $request){
        $input = $request->all(); 
        $category_code = $input['category_code'];
        $saleshistoryreport = new SalesHistoryReport();
        $get_subcategories = $saleshistoryreport->get_subcategories($category_code);            
        $subcategories_html ="";
        $subcategories_html = "<select class='' name='sub_category_id' id='sub_category_id' style='width: 100px;'>'<option value='all'>All</option>";
        foreach($get_subcategories as $subcategory){
            if(isset($selected_dept) && $selected_dept == $subcategory['id']){
                $subcategories_html .= "<option value='".$subcategory['id']."' selected='selected'>".$subcategory['text']."</option>";
            } else {
                $subcategories_html .= "<option value='".$subcategory['id']."'>".$subcategory['text']."</option>";
            }
        }
        $subcategories_html .="</select>";
        echo $subcategories_html;
        exit;
    }
    public function get_skus(Request $request){
        $input = $request->all(); 
        $saleshistoryreport = new SalesHistoryReport();
        $get_skus = $saleshistoryreport->get_skus($input['ids']);
        echo json_encode($get_skus);
        exit;        
    }
    public function get_item_names(Request $request){
        $term = $request->all();        
        $saleshistoryreport = new SalesHistoryReport();
        $get_item_names = $saleshistoryreport->get_item_names($term);
        echo json_encode($get_item_names);
        exit;        
    }
        
    public function get_barcodes(Request $request){
        $term = $request->all(); 
        $saleshistoryreport = new SalesHistoryReport();
        $get_barcodes = $saleshistoryreport->get_barcodes($term);
        echo json_encode($get_barcodes);
        exit;        
    }
    public function get_sizes(Request $request){
        $term = $request->all();         
        $saleshistoryreport = new SalesHistoryReport();
        $get_sizes = $saleshistoryreport->getSizes($term);        
        echo json_encode($get_sizes);
        exit;        
    }

    public function pdf_save_page(Request $request) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        ini_set('max_execution_time', 0);
        $data['reports'] = $request->session()->get('reports');
        $data['tender_details'] = $request->session()->get('tender_details');
        $data['hourly_sales'] = $request->session()->get('hourly_sales');
        $data['sales_by_category'] = $request->session()->get('sales_by_category');
        $data['sales_by_department'] = $request->session()->get('sales_by_department');
        
        $data['cashpickup']=$request->session()->get('cashpickup');
        $data['paidout']  =$request->session()->get('paidout');
        $data['p_start_date'] = $request->session()->get('p_start_date');
        $data['p_end_date'] = $request->session()->get('p_end_date');
        $data['p_batch_id'] = $request->session()->get('p_batch_id');
        
        $data['storename'] = $request->session()->get('storename');
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');
        $image = DIR_IMAGE.'/logoreport.jpg';
        $image_handle = fopen ($image, 'rb');
        $size=filesize ($image);
        $contents= fread ($image_handle, $size);
        fclose ($image_handle);
        
        
        $data['image'] = base64_encode($contents);
        $data['heading_title'] = 'Z Report';
        // echo "197";die;
        $html =  view('saleshistoryreports.print_x_report_page',$data);        
        $this->dompdf->loadHtml($html);

        //(Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $this->dompdf->render();
        
        $this->dompdf->stream('Z-Report.pdf');
    }

    public function get_csv(Request $request) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        $data['reports'] = $request->session()->get('reports');
        $data['tender_details'] = $request->session()->get('tender_details');
        $data['hourly_sales'] = $request->session()->get('hourly_sales');
        $data['sales_by_category'] = $request->session()->get('sales_by_category');
        $data['sales_by_department'] = $request->session()->get('sales_by_department');
        
        $data['cashpickup']=$request->session()->get('cashpickup');
        $data['paidout']  =$request->session()->get('paidout');
        
        $p_start_date   = $request->session()->get('p_start_date');
        $p_end_date     = $request->session()->get('p_end_date');
        $p_end_date     = $request->session()->get('p_end_date');
        $p_batch_id     = $request->session()->get('p_batch_id');    
        
        $data_row = '';
        $data_row .= "Z Report".PHP_EOL;
        
        $data_row .= "Batch : ".$p_batch_id. ' ' . "From : [".str_replace('/','-',$p_start_date)."] To : [".str_replace('/','-',$p_end_date)."]".PHP_EOL;
        $data_row .= "Store Name: ".$request->session()->get('storename').PHP_EOL;
        $data_row .= "Store Address: ".$request->session()->get('storeaddress').PHP_EOL;
        $data_row .= "Store Phone: ".$request->session()->get('storephone').PHP_EOL;
        $data_row .= PHP_EOL;

        if(count($data['reports']) > 0){
            $data_row .= ''.PHP_EOL;
            
            if(isset($data['reports']) && !empty($data['reports'])) {
                $value = $data['reports'];
                
                $cashOnDrawer    = $value['OPENINGBAL'] + $value['NSUBTOTAL'];
                $cashShortOver   = $cashOnDrawer - $value['USERRCLOSINGBAL'];
                $totalSales      = $value['NNONTAXABLETOTAL'] + $value['NTAXABLETOTAL'];
                
              $data_row .= 'Opening Balance'.','.$value['OPENINGBAL'].PHP_EOL;
              $data_row .= 'Cash on Drawer'.','.$cashOnDrawer.PHP_EOL;
              $data_row .= 'User Closing Balance'.','.$value['USERRCLOSINGBAL'].PHP_EOL;
              $data_row .= 'Cash Short/Over'.','.$cashShortOver.PHP_EOL;
              $data_row .= 'Sales'.','.$value['NNETTOAL'].PHP_EOL;
              $data_row .= 'Cash Added'.','.$value['NNETTOAL'].PHP_EOL;
              $data_row .= 'Sub Total'.','.$value['NNETTOAL'].PHP_EOL;
              $data_row .= 'Closing Balance'.','.$value['CLOSINGBAL'].PHP_EOL;
              $data_row .= 'Total Tax'.','.$value['NTAXTOTAL'].PHP_EOL;
              $data_row .= 'Taxable Sales'.','.$value['NTAXABLETOTAL'].PHP_EOL;
              $data_row .= 'Nontaxable Sales'.','.$value['NNONTAXABLETOTAL'].PHP_EOL;
              $data_row .= 'Discount'.','.$value['NDISCOUNTAMT'].PHP_EOL;
              $data_row .= 'Sale Discount'.','.$value['NSALETOTALAMT'].PHP_EOL;
              $data_row .= 'Total Sales (Without Tax)'.','.$totalSales.PHP_EOL;
              $data_row .= 'Total Credit Amount'.','.$value['CREDITSALES'].PHP_EOL;
              $data_row .= 'Lottery Sales'.','.$value['NTOTALLOTTERY'].PHP_EOL;
              $data_row .= 'Instant Sales'.','.'0.00'.PHP_EOL;
              $data_row .= 'Lottery Redeem'.','.$value['NTOTALLOTTERYREDEEM'].PHP_EOL;
              $data_row .= 'Instant Redeem'.','.'0.00'.PHP_EOL;
              $data_row .= 'On Hand Sales'.','.'0.00'.PHP_EOL;
            }
            
            if(isset($data['tender_details']) && !empty($data['tender_details']))
            {
                $data_row .= PHP_EOL;
                $data_row .= "Tender Details".PHP_EOL;
                $tender_values = $data['tender_details'];
                foreach($tender_values as $val)
                {
                    $data_row .=$val['vtendertype'].','.$val['total'].'['.$val['count'].']'.PHP_EOL;
                }
            }
            
            if(isset($data['hourly_sales']) && !empty($data['hourly_sales']))
            {
                $data_row .= PHP_EOL;
                $data_row .= "Hourly Sales".PHP_EOL;
                $hourly_sales = $data['hourly_sales'];
                foreach($hourly_sales as $hour)
                {
                    $data_row .=$hour['Hours'].','.$hour['Total'].PHP_EOL;
                }
            }
            
            if(isset($data['sales_by_category']) && !empty($data['sales_by_category']))
            {
                $data_row .= PHP_EOL;
                $data_row .= "Sales by Category".PHP_EOL;
                $sales_by_category = $data['sales_by_category'];
                $sum = array_sum(array_column($sales_by_category,'namount'));
                foreach($sales_by_category as $category)
                {
                    $data_row .=$category['vcategoryame'].','.$category['namount'].','.number_format(($category['namount']/$sum)*100, 2).'%'.PHP_EOL;
                }
            }
            
            if(isset($data['sales_by_department']) && !empty($data['sales_by_department']))
            {
                $data_row .= PHP_EOL;
                $data_row .= "Sales by Department".PHP_EOL;
                $sales_by_department = $data['sales_by_department'];
                 $sum = array_sum(array_column($sales_by_department,'namount'));
                foreach($sales_by_department as $department)
                {
                    $data_row .=$department['vdepartname'].','.$department['namount'].','.number_format(($department['namount']/$sum)*100, 2).'%'.PHP_EOL;
                }
            }
            
        }else{
            $data_row = 'Sorry no data found!';
        }

        $file_name_csv = 'z-report.csv';
        $file_path =public_path('image/z-report.csv');
        $myfile = fopen(public_path('image/z-report.csv'), "w");
        fwrite($myfile,$data_row);
        fclose($myfile);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    }

    public function print_page(Request $request) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        ini_set('max_execution_time', 0);
        $data['reports']        = $request->session()->get('reports');
        $data['tender_details'] = $request->session()->get('tender_details');
        $data['hourly_sales'] = $request->session()->get('hourly_sales');
        $data['sales_by_category'] = $request->session()->get('sales_by_category');
        $data['sales_by_department'] = $request->session()->get('sales_by_department');
        $data['cashpickup']=$request->session()->get('cashpickup');
        $data['paidout']  =$request->session()->get('paidout');
        $data['p_start_date']   = $request->session()->get('p_start_date');
        $data['p_end_date']     = $request->session()->get('p_end_date');
        $data['p_batch_id']     = $request->session()->get('p_batch_id');    
        $data['storename']      = $request->session()->get('storename');
        $data['storeaddress']   = $request->session()->get('storeaddress');
        $data['storephone']     = $request->session()->get('storephone');
        $image = DIR_IMAGE.'/logoreport.jpg';
        $image_handle = fopen ($image, 'rb');
        $size=filesize ($image);
        $contents= fread ($image_handle, $size);
        fclose ($image_handle);
        $data['image'] = base64_encode($contents);
        $data['heading_title'] = 'Z Report';
        $this->response->setOutput($this->load->view('administration/print_x_report_page', $data));
    }

    // new code
     public function csv(){
    
        $data_row = '';
        
        $data_row .= PHP_EOL;

        $data_row = '';
        $data_row .= "Sales History Report Summary".PHP_EOL;
        $data= session()->get('session_data') ;
        
        $p_end_date= session()->get('session_edate') ;
        $p_start_date= session()->get('session_sdate') ;
        $store= session()->get('session_store') ;
        $data_row .= "item name".",";  
        $data_row .= "size".",";  
        foreach($data['reports']['header'] as $v){
            
        $data_row .= $v.",";
      
        }
        
        $data_row .= PHP_EOL;
      foreach($data['reports']['result'] as $r){
          foreach($r as $k => $v){ 
                 if($k === 'iitemid'){
                     continue;
                     
                 }    
                                    
                if($k !== 'vitemname'){
                    
                }
                    $data_row .= $v.",";
                    
                
          }  
           $data_row .= PHP_EOL;
    }   
        
       
       
        $data_row .= PHP_EOL;

      
        $file_name_csv = 'Sales_History_Report.csv';

        $file_path =public_path('image/Sales_History_Report.csv');

        $myfile = fopen(public_path('image/Sales_History_Report.csv'), "w");

        fwrite($myfile,$data_row);
        fclose($myfile);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    
      }
      public function send_email(){
            $data= session()->get('session_data') ;
            
            // $to = "h.g.batakurki@gmail.com";
            $to = session()->get('loggedin_username');
            $subject = "Sales History Report";
            $html='';
            $html.='<html><body>
            <table style="border: 1px solid #ddd";>
            <tr style="border: 1px solid #ddd";>
            <th style="border: 1px solid #ddd";>Item Name</th>
            <th style="border: 1px solid #ddd";>Size</th>';
            foreach($data['reports']['header'] as $v){
            
             $html.='<th style="border: 1px solid #ddd";> '.$v.'</th>';
          
            }
            foreach($data['reports']['result'] as $r){
                $html.='<tr style="border: 1px solid #ddd";>';
                  foreach($r as $k => $v){ 
                         if($k == 'iitemid'){
                             continue;
                             
                         }    
                                            
                       if($k != 'vitemname'){
                        
                       }
                       $html.='<td style="border: 1px solid #ddd";>'.$v.'</td>';
                            

                
                 } 
                  $html.='</tr>';     
            }     
          

            $html.='</tr>';
            
            $html.='
            </table>
            </body>
            </html>';
          
            
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
            // More headers
            $headers .= 'From: <admin@albertapayments.com>' . "\r\n";
           
            
            mail($to,$subject,$html,$headers);
          
          
      }
}


?>