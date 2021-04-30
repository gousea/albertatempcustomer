<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Model\ScanDataReport;
use Illuminate\Http\Request;

class ScanDataReportController extends Controller
{
   
	private $error = array();

	public function index(Request $request) {
		// $this->load->language('administration/scan_data_report');

		// $this->document->setTitle($this->language->get('heading_title'));

    	// $this->load->model('api/scan_data_report');
		
		return $this->getList($request);
	}

	public function print_page() {

		ini_set('max_execution_time', 0);
    	ini_set("memory_limit", "2G");

	    $data['reports'] = $this->session->data['vendor_reports'];
	    $data['p_start_date'] = $this->session->data['vendor_p_start_date'];
	    $data['p_end_date'] = $this->session->data['vendor_p_end_date'];
	    
	    $data['storename'] = $this->session->data['storename'];
        $data['storeaddress'] = $this->session->data['storeaddress'];
        $data['storephone'] = $this->session->data['storephone'];

	    $data['heading_title'] = 'Vendor Report';

	    $this->response->setOutput($this->load->view('administration/print_page_vendor_list', $data));
	}

	public function pdf_save_page() {

		ini_set('max_execution_time', 0);
    	ini_set("memory_limit", "2G");

	    $data['reports'] = $this->session->data['vendor_reports'];
	    $data['p_start_date'] = $this->session->data['vendor_p_start_date'];
	    $data['p_end_date'] = $this->session->data['vendor_p_end_date'];
	    
	    $data['storename'] = $this->session->data['storename'];
        $data['storeaddress'] = $this->session->data['storeaddress'];
        $data['storephone'] = $this->session->data['storephone'];

	    $data['heading_title'] = 'Vendor Report';

	    $html = $this->load->view('administration/print_page_vendor_list', $data);
	    
	    $this->dompdf->loadHtml($html);

	    //(Optional) Setup the paper size and orientation
	    // $this->dompdf->setPaper('A4', 'landscape');

	    // Render the HTML as PDF
	    $this->dompdf->render();

	    // Output the generated PDF to Browser
	    $this->dompdf->stream('vendor-report.pdf');
	 }
	  
	protected function getList(Request $request) {

		ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();
    	$model = new ScanDataReport();
		if($request->isMethod('post')){
			
			$filter_data = array(
				'week_ending_date' => $input['week_ending_date'],
				'department_id' => isset($input['department_id']) ? $input['department_id'] : "",
				'category_id' => isset($input['category_id']) ? $input['category_id'] : "",
				'manufacturer' => isset($input['manufacturer']) ? $input['manufacturer'] : "",
				'limit' => 20,
			);
		    
		    if($input['manufacturer'] == 1){
		        $report_datas = $model->getScanDadtaReport($filter_data);
		    } elseif($input['manufacturer'] == 2) {
		        //08 Dec 2020: included this part because Vijay said the RJR should work as it does in old customer
		        $report_datas = $model->get_rjr_data($filter_data);
		    } else {
		        return 'Invalid Manufacturer';
		    }
			
			
// 			echo '<pre>';print_r($report_datas);echo '</pre>';die;
			
			
            $report_datas = json_decode(json_encode($report_datas), true);
			$data_row = '';

            // $data_row = [];
			$data_first_row = '';
			
			$total_qty = 0;
			$total_price = 0;
			$filter_data['week_ending_date'] = str_replace('/','-',$filter_data['week_ending_date']);
			$week_ending_date = \DateTime::createFromFormat('m-d-Y', $filter_data['week_ending_date']);
    		$week_ending_date = $week_ending_date->format('Ymd');
            
            $week_starting_date = date('Ymd', strtotime('-1 week', strtotime($week_ending_date)));
			
			if(count($report_datas) > 0){
				$total_qty_sold = 0;
				$total_sales_price = 0.00;
				
				$track_sale_id = [];

                if($filter_data['manufacturer'] == 1){
                    
                    $number_of_rows = 0;
                    
                    $total_multipack_sold = $total_consumer_units = 0;
                    $total_MFG_Deal_Discount_Amount_THREE = 0.00;
                    
                    //For Marlboro
                    $counter = 0;
                    foreach($report_datas as $report_data){
                        
                        // ==================== For Marlboro ================================================
    					$management_account_number = $input['management_account_number'];
    					$week_ending_date = \DateTime::createFromFormat('m-d-Y', $input['week_ending_date']);
    					$week_ending_date = $week_ending_date->format('Ymd');
    
    					$transaction_date = \DateTime::createFromFormat('Y-m-d H:i:s', $report_data['dtrandate']);
    					$transaction_date = $transaction_date->format('Ymd');
    
    					$transaction_time = \DateTime::createFromFormat('Y-m-d H:i:s', $report_data['dtrandate']);
    					$transaction_time = $transaction_time->format('H:i:s');
    
    					$transaction_id_code = $report_data['isalesid'];
    					
    				    // keep track of isalesid to fill col 29, 30 and 37
    				    // display blank if more than buyqty
    				    if(isset($sales_id) &&  $sales_id == $report_data['isalesid']){
    				        $sales_id = $report_data['isalesid'];
    				        $counter++;
    				    } else {
    				        $counter = 1;
    				    }
    
    					$store_number = $report_data['istoreid'];
    					$store_name = $report_data['vstorename'];
    					$store_address = $report_data['vaddress1'];
    					$store_city = $report_data['vcity'];
    					$store_state = $report_data['vstate'];
    					$store_zip = (string)$report_data['vzip'].'-0000';
    
    					$category = $report_data['vcatname'];
    					$manufacturer_name = $report_data['vcompanyname'];
    					$SKU_Code = $report_data['upc_code'];
    					$UPC_Code = $report_data['upc_code'];
    					$SKU_Description = $report_data['vitemname'];
    					$Unit_of_Measure = $report_data['vunitname'];
    				
    				
    				    $Quantity_Sold = $report_data['ndebitqty'];
    				
    				
    				    $Consumer_Units = str_replace(['.0','-'], "", $report_data['npack']);

    				
    					$Multi_Pack_Required_Quantity = 0;
    					$Multi_Pack_Discount_Amount = '';
    					
    					

                        $Multi_Pack_Indicator = $report_data['is_multiple'];
    					 
    					 
                        $Multi_Pack_Required_Quantity = (isset($report_data['nbuyqty']) && $report_data['nbuyqty'] != "" && $report_data['nbuyqty'] != 1 && $report_data['nbuyqty'] != -1 && $report_data['nbuyqty'] != 0) ? (int)  $report_data['nbuyqty'] : "";
                        $Multi_Pack_Discount_Amount = "";
                        if(isset($report_data['vsaleby']) && $report_data['vsaleby'] != "" && $Multi_Pack_Indicator != "")
                        {
                            if( strtolower($report_data['vsaleby']) == 'price' || $report_data['vsaleby'] == 2)
                            {
                                
                                $Multi_Pack_Discount_Amount = (isset($report_data['sale_ndiscountper']) && $report_data['sale_ndiscountper'] != "") ? $report_data['sale_ndiscountper'] : "";
                                
                                
                            }
                            else if(strtolower($report_data['vsaleby']) == 'discount' || $report_data['vsaleby'] == 1)
                            {
                                
                                $Multi_Pack_Discount_Amount = (isset($report_data['sale_ndiscountper']) && $report_data['sale_ndiscountper'] != "") ? ( $report_data['sale_ndiscountper'] / 100 ) * $report_data['nunitprice'] : "";
                            
                                
                            }
                        } 
                        
    					$Retailer_Funded_Discount_Name = '';
    					$Retailer_Funded_Discount_Amount = ''; //if 0.00 then Blank
    					$MFG_Deal_Name_ONE = '';
    					$MFG_Deal_Discount_Amount_ONE = ''; //if 0.00 then Blank
    					$MFG_Deal_Name_TWO = '';
    					$MFG_Deal_Discount_Amount_TWO = ''; //if 0.00 then Blank
    					
    					

    					
    					if(isset($report_data['cust_acct_no']) && trim($report_data['cust_acct_no']) !== ''){
    					    
    					    if($counter > $report_data['nbuyqty']){
    					        
    					        $MFG_Deal_Name_THREE = '';
    					        $MFG_Deal_Discount_Amount_THREE = '';
    					    }
    					    
    					    preg_match('#\((.*?)\)#', $report_data['sale_name'], $match);
                            // print $match[1];
                            $MFG_Deal_Name_THREE = isset($match[1])?$match[1]:'';
    					    $MFG_Deal_Discount_Amount_THREE = $report_data['regd_cust_disc'] > 0?number_format((float)$report_data['regd_cust_disc'], 2, '.', ''):''; //if 0.00 then Blank
    					    
    					} else {
    					    
    					    $MFG_Deal_Name_THREE = '';
    					    $MFG_Deal_Discount_Amount_THREE = '';
    					}
    					
    					
    				
    					$Final_Sales_Price = $report_data['ndebitamt'];
    					
    					$Final_Sales_Price = sprintf("%0.2f", (float)$Final_Sales_Price  );
    					
    					
    					$Store_Telephone = $report_data['vphone1'];
    					$Store_Contact_Name = $report_data['vcontactperson'];
    					$Store_Contact_Email = $report_data['vemail'];
    					$Product_Grouping_Code = '';
    					$Product_Grouping_Name = '';
    					$Loyalty_ID_Number = ($counter > $report_data['nbuyqty'])?"":$report_data['cust_acct_no'];
    
                        
                        if($Multi_Pack_Indicator === 'N'){
                            $MFG_Deal_Name_THREE = $MFG_Deal_Discount_Amount_THREE = $Multi_Pack_Required_Quantity = $Multi_Pack_Discount_Amount = '';
                        }
                        
                        if($Multi_Pack_Discount_Amount != ''){
                            $Multi_Pack_Discount_Amount = $Quantity_Sold * $Multi_Pack_Discount_Amount;
                            $Multi_Pack_Discount_Amount = number_format(floatval($Multi_Pack_Discount_Amount), 2);
                        }
                        
                        if($MFG_Deal_Discount_Amount_THREE != ''){
                            $MFG_Deal_Discount_Amount_THREE = $Quantity_Sold * $MFG_Deal_Discount_Amount_THREE;
                            
                            $MFG_Deal_Discount_Amount_THREE = number_format($MFG_Deal_Discount_Amount_THREE, 2);
                            if($MFG_Deal_Discount_Amount_THREE > 1){
                                $MFG_Deal_Discount_Amount_THREE = '1.00';
                            }
                        }
                        
                        
                            $total_MFG_Deal_Discount_Amount_THREE += (float)$MFG_Deal_Discount_Amount_THREE;
                        // }
                        if(!isset($disc_by_qty_sold) && floatval($total_MFG_Deal_Discount_Amount_THREE) > 0){
                            $disc_by_qty_sold = floatval($MFG_Deal_Discount_Amount_THREE)/intval($Quantity_Sold);
                        }
                        
                        if($Multi_Pack_Indicator == 'Y'){
                            $total_multipack_sold += abs($Quantity_Sold);
                        }
                        if($MFG_Deal_Discount_Amount_THREE == ''){
                            $Loyalty_ID_Number='';
                            $MFG_Deal_Name_THREE='';
                        }
                        
                        
                        
    					$data_row .= trim($management_account_number).'|'.trim($week_ending_date).'|'.trim($transaction_date).'|'.trim($transaction_time).'|'.trim($transaction_id_code).'|'.trim($store_number).'|'.trim($store_name).'|'; //7
    					$data_row .= trim($store_address).'|'.trim($store_city).'|'.trim($store_state).'|'.trim($store_zip).'|'.trim($category).'|'.trim($manufacturer_name).'|'.trim($SKU_Code).'|'.trim($UPC_Code).'|'.trim($SKU_Description).'|'; //16
    					$data_row .= trim($Unit_of_Measure).'|'.abs($Quantity_Sold).'|'.trim($Consumer_Units).'|'.trim($Multi_Pack_Indicator).'|'.trim($Multi_Pack_Required_Quantity).'|'.trim($Multi_Pack_Discount_Amount).'|'; //22
    					$data_row .= trim($Retailer_Funded_Discount_Name).'|'.trim($Retailer_Funded_Discount_Amount).'|'.trim($MFG_Deal_Name_ONE).'|'.trim($MFG_Deal_Discount_Amount_ONE).'|'.trim($MFG_Deal_Name_TWO).'|'; //27
    					$data_row .= trim($MFG_Deal_Discount_Amount_TWO).'|'.trim($MFG_Deal_Name_THREE).'|'.trim($MFG_Deal_Discount_Amount_THREE).'|'.trim($Final_Sales_Price ).'|'.trim($Store_Telephone).'|'; //32
    				
    					$data_row .= trim($Store_Contact_Name).'|'.trim($Store_Contact_Email).'|'.trim($Product_Grouping_Code).'|'.trim($Product_Grouping_Name).'|'.trim($Loyalty_ID_Number); //37
    					
    					//On Vijay's request on 6 April, 2020
    					$data_row .= '||||||||'.PHP_EOL;
    
    					$total_qty_sold = $total_qty_sold + abs($Quantity_Sold);
    					$total_sales_price += $Final_Sales_Price;
    					
    					$total_consumer_units += $Consumer_Units;
    					
                        $number_of_rows++;
                    
                    }
                    
                    // echo json_encode($data_row); die;
                    
                    // echo env('DIR_TEMPLATE');
                    
                    $scan_data_reconcile_string_head = 'Week Ending Date,Total Multipack Sold,Total Multipack Discount,Total Loyalty Pack Sold,Total Loyalty Discount'.PHP_EOL;
                    
                    /*$total_loyalty_pack_sold = ($total_MFG_Deal_Discount_Amount_THREE/$disc_by_qty_sold);
                    
                    if(is_nan($total_loyalty_pack_sold)){
                        $total_loyalty_pack_sold = 0;
                    }*/
                    
                    if(!isset($disc_by_qty_sold) || $disc_by_qty_sold == 0){
                        $total_loyalty_pack_sold = $disc_by_qty_sold = 0;
                    } else {
                        $total_loyalty_pack_sold = ($total_MFG_Deal_Discount_Amount_THREE/$disc_by_qty_sold);
                    }
                    
                    $scan_data_reconcile_string_body = $total_multipack_sold.','.($total_multipack_sold*0.25).','.$total_loyalty_pack_sold.','.$total_MFG_Deal_Discount_Amount_THREE.PHP_EOL;
                    $total_sales_price = sprintf("%0.2f", (float)$total_sales_price  );
                    
                    $data_first_row .= $number_of_rows.'|'.$total_qty_sold.'|'.$total_sales_price.'|Alberta POS'.PHP_EOL; 
                   
                    
                } else {
                    
                    //================================== For RJR =================================================
                    
                    $data_first_row .= "Outlet Name|Outlet Number|Outlet Address 1|Outlet Address 2|Outlet City|Outlet State|Outlet Zip Code|Transaction Date/Time|Market Basket Transaction ID|Scan Transaction ID|Register ID|Quantity|Price|UPC Code|UPC Description|Unit of Measure|Promotion Flag|Outlet Multi-Pack Flag|Outlet Multi-Pack Quantity|Outlet Multi-Pack Discount Amount|Account Promotion Name|Account Discount Amount|Manufacturer Discount Amount|Coupon PID|Coupon Amount|Manufacturer Multi-pack Flag|Manufacturer Multi-pack Quantity|Manufacturer Multi-pack Discount Amount|Manufacturer Promotion Description|Manufacturer Buy-down Description|Manufacturer Buy-down Amount|Manufacturer Multi-Pack Description|Account Loyalty ID Number|Coupon Description".PHP_EOL;
    				// echo json_encode($report_datas);
    				// die;
    				
    				foreach($report_datas as $report_data){

    					// For RJR
                        $outlet_name = Session::get('storeName');
                        $outlet_number = Session::get('sid');
                        $address1 = Session::get('address');
                        $address2 = " ";
                        $city = Session::get('city');
                        $state = Session::get('state');
                        $zip = (string)Session::get('zip');
                        
                        
                        
                        
                        //$transaction_date = DateTime::createFromFormat('Y-m-d H:i:s', $report_data['dtrandate']);
    					//$transaction_date = $transaction_date->format('Y-m-d H:i:s');
    				    //$transaction_date = $report_data['dtrandate'];
    				
    				    $transaction_date = \DateTime::createFromFormat('Y-m-d H:i:s', $report_data['dtrandate']);
    					$transaction_date = $transaction_date->format('Y-m-d H:i:s');
    				    
    				// 	$market_basket_id = $this->request->post['management_account_number'];
    				
    				
    				    /*if(isset($report_data['cust_acct_no']) && !empty(trim($report_data['cust_acct_no']))){
    				        $market_basket_id = $report_data['cust_acct_no'].$report_data['isalesid'];
    				    } else {
    				        
    				        // ======== When the customer account is not present ===========================
    				        
    				        // Get only the date from the transaction id
    				        $date = substr($report_data['isalesid'], 4, 2);
    				        
    				        // get the modulus after dividing the $date with 26
    				        $modulus = $date%26;
    				        
    				        // get the nth ($date) english character
    				        $nth_character = chr(65+$modulus);
    				        
    				        $market_basket_id = $nth_character.$report_data['isalesid'];
    				    }*/
    				    $market_basket_id = $report_data['isalesid'];
    				    
    				    if(array_key_exists($report_data['isalesid'], $track_sale_id) === false){
    				        $track_sale_id[$report_data['isalesid']] = 1;
    				    } else {
    				        $frequency = $track_sale_id[$report_data['isalesid']];
    				        $frequency++;
    				        $track_sale_id[$report_data['isalesid']] = $frequency;
    				    }
    				    
    					$scan_id = $track_sale_id[$report_data['isalesid']];
    					
                        // $scan_id = $report_data['idettrnid']; -- Adarsh's code
                        $register_id = $report_data['vterminalid'];
                        $quantity = (int)$report_data['ndebitqty'];
                        $price = $report_data['nunitprice'];
                        $upc_code = $report_data['upc_code'];
                        $upc_description = $report_data['vitemname'];
                        // $unit_of_measure = $report_data['vsize'];
                        $unit_of_measure = $report_data['vunitname'];
                        
                        $promotion_flag = $report_data['nbuyqty']> 0?"Y":"N";
                        
                        
                        
                        //get the $mfg_disc_amt from Mfr Promo Desc
                        $promo_desc = $report_data['aisle_id'] != ""?$model->getAisle($report_data['aisle_id']):"";
                        
                        if(trim($promo_desc) !== ""){
                            $description_array = explode("$", $promo_desc);
                            $mfg_disc_amt = (count($description_array) > 1)?(string)$description_array[1]:"";
                        } else {
                           $mfg_disc_amt = ""; 
                        }
                        
                        
                        
                        $outlet_multipack_flag = "N";
                        $outlet_multipack_qty = "";
                        $outlet_multipack_disc_amt = "";
                        $acct_promo_name = "";
                        $acct_disc_amt = "";
                        // $mfg_disc_amt = "";
                        $pid_coupon = "";
                        $pid_coupon_disc = "";
                        
                        $pid_coupon_amt = "";
                        
                        $mfg_multipack_flag = $report_data['nbuyqty'] > 1?"Y":"N"; //Because we cannot track the mfg multipack qty there we consider the nbuyqty which should be greater than 1
                        $mfg_multipack_qty = $report_data['nbuyqty'];
                        
                        /*if($report_data['ndiscountper'] != null && $report_data['nbuyqty'] != null) {
                            // $mfg_multipack_disc_amt = (float)$report_data['ndiscountper']/(float)$report_data['nbuyqty'];
                            $mfg_multipack_disc_amt = (float)$report_data['ndiscountper'];
                        } else {
                            $mfg_multipack_disc_amt = "";
                        }*/
                        
                        
                        $mfg_multipack_desc = $model->getShelving($report_data['shelving_id']);

                        
                        if($report_data['sale_ndiscountper'] != null && $report_data['nbuyqty'] != null) {
                            // $mfg_multipack_disc_amt = (float)$report_data['ndiscountper']/(float)$report_data['nbuyqty'];
                            
                            
                            /*$mfg_multipack_disc_amt = (float)number_format((float)$report_data['sale_ndiscountper'], 2, '.', '');

                            
                            if($report_data['cust_acct_no'] != null && !empty(trim($report_data['cust_acct_no']))){
                                $mfg_multipack_disc_amt = $mfg_multipack_disc_amt + $report_data['regd_cust_disc']; 
                            }*/
                            
                            $mfg_multipack_disc_amt = (string)number_format((float)$report_data['sale_ndiscountper'], 2, '.', '');
                            // $mfg_multipack_disc_amt = (string)sprintf("%.2f", $report_data['sale_ndiscountper']);

                            
                            if($report_data['cust_acct_no'] != null && !empty(trim($report_data['cust_acct_no']))){
                                
                                $regd_cust_disc = number_format((float)$report_data['regd_cust_disc'], 2, '.', '');
                                
                                // $mfg_multipack_disc_amt = $mfg_multipack_disc_amt + $regd_cust_disc; <--- To display customer disc and addl discount separately
                                
                                // $mfg_multipack_disc_amt = (float)number_format((float)$report_data['mfg_multipack_disc_amt'], 2, '.', '');
                                
                                 $mfg_multipack_disc_amt = (string)number_format((float)$mfg_multipack_disc_amt, 2, '.', '');
                                // $mfg_multipack_disc_amt = (string)sprintf("%.2f", $report_data['sale_ndiscountper']);
                            }
                        } else {
                            $mfg_multipack_disc_amt = "";
                            
                            // If Column 28 = 0 then Col 28, 27 should be blank and Col 26 should have N
                            $mfg_multipack_qty = '';
                            $mfg_multipack_flag = 'N';
                        }
                        
                        
                        if($mfg_multipack_disc_amt == '0'){
                            
                            $mfg_multipack_disc_amt = "";
                            
                            // If Column 28 = 0 then Col 28, 27 should be blank and Col 26 should have N
                            $mfg_multipack_qty = '';
                            $mfg_multipack_flag = 'N';
                            $mfg_multipack_desc = '';
                        }
                        
// dd(__LINE__);                        
                        $mfg_promo_desc = $model->getAisle($report_data['aisle_id']);
// dd(__LINE__); 
// if($report_data['aisle_id'] !== NULL && $report_data['aisle_id'] != ''){dd($report_data['aisle_id']);}
// dd($report_data['aisle_id']);                        
                        if(trim($mfg_promo_desc) !== ""){
// dd(__LINE__);
                            $description_array = explode("$", $mfg_promo_desc);

                            $mfg_disc_amt = (count($description_array) > 1)?number_format((float)$description_array[1], 2, '.', ''):"";

                        } else {
                           $mfg_disc_amt = ""; 
                        }
                        
                        
                        
                        $mfg_buydown_desc = $model->getShelf($report_data['shelf_id']);
                        
                        
                        // echo "Discount per: ".$report_data['ndiscountper']; die;
                        
                        // $mfg_buydown_amt = ($report_data['ndiscountper'] != "" && (($report_data['ndiscountper']*100)/$report_data['nunitcost']) != 0)?(($report_data['ndiscountper']*100)/$report_data['nunitcost']):"";
                        
                        //  $mfg_buydown_amt = (int)$report_data['buy_down'] > 0 ?(string)$report_data['buy_down']:''; Disabled on 13 July 2020 as per tkt no 542
                        $mfg_buydown_amt = '';
                        
                        
                        $acct_loyalty_id = "";
                        $coupon_desc = "";
                        // $Loyalty_ID_Number = ($report_data['icustomerid'] == 0)?"":$report_data['icustomerid'];
                                                    
                        $Loyalty_ID_Number = $report_data['cust_acct_no'];

                        
                        
                        // $price = $quantity * $price;
                        $total_qty = $quantity + $total_qty;
                        $total_price = $price + $total_price;
                    
                        /*if(trim($mfg_promo_desc) !== "" || trim($mfg_multipack_desc) !== "" && trim($mfg_multipack_disc_amt) !== "" ){
                            
                            $promotion_flag = "Y";
                        } else {
                            $promotion_flag = "N";
                        }*/
                        
                        $promotion_flag = $report_data['is_multiple'];
                        
                        if($promotion_flag === 'N'){
                            $mfg_multipack_flag = 'N';
                            $mfg_multipack_qty = $mfg_multipack_disc_amt = $mfg_multipack_desc = '';
                        }
                        
                        
                        if(trim($mfg_promo_desc) !== ''){
                            $promotion_flag = 'Y';
                        }
                        
                        if($mfg_multipack_flag == 'N'){
                            $regd_cust_disc = $mfg_multipack_disc_amt = '';
                        }
                        
                        // $data_row .= $report_data['ndiscountper'];
                        
                        if(!isset($regd_cust_disc) || empty(trim($regd_cust_disc)) || $regd_cust_disc == '0'){
                            $regd_cust_disc = '';
                            $pid_coupon_disc = '';
                        } else {
                            // $regd_cust_disc = strval($regd_cust_disc);
                            // $regd_cust_disc = number_format(floatval($regd_cust_disc), 2, '.', '');
                            
                            // echo gettype($regd_cust_disc); 
                            
                            // echo trim($acct_disc_amt).'|'.trim($mfg_disc_amt).'|'.trim($pid_coupon).'|'.$regd_cust_disc.'|'.trim($mfg_multipack_flag).'|'.trim($mfg_multipack_qty).'|'; //27
                            // die;
                            $pid_coupon_disc = 'Loyalty';
                        }
                        
                        $data_row .= trim($outlet_name).'|'.trim($outlet_number).'|'.trim($address1).'|'.trim($address2).'|'.trim($city).'|'.trim($state).'|'.trim($zip).'|'.trim($transaction_date).'|'; //8
    					
                        // $data_row .= trim($market_basket_id).'|'.trim($scan_id).'|'.trim($register_id).'|'.trim($quantity).'|'.trim($price).'|'.trim($upc_code).'|'.trim($upc_description).'|'; //15

                        $data_row .= trim($market_basket_id).'|'.trim($scan_id).'|'.trim($register_id).'|'.trim($quantity).'|'.number_format((float)$price, 2, '.', '').'|'.trim($upc_code).'|'.trim($upc_description).'|'; //15

                        
                        $data_row .= trim($unit_of_measure).'|'.trim($promotion_flag).'|'.trim($outlet_multipack_flag).'|'.trim($outlet_multipack_qty).'|'.trim($outlet_multipack_disc_amt).'|'.trim($acct_promo_name).'|'; //21

                        // $data_row .=  trim($acct_disc_amt).'|'.trim($mfg_disc_amt).'|'.trim($pid_coupon).'|'.trim($pid_coupon_amt).'|'.trim($mfg_multipack_flag).'|'.trim($mfg_multipack_qty).'|'; //27
                        
                        $data_row .=  trim($acct_disc_amt).'|'.trim($mfg_disc_amt).'|'.trim($pid_coupon).'|'.$regd_cust_disc.'|'.trim($mfg_multipack_flag).'|'.trim($mfg_multipack_qty).'|'; //27
                        
                        $data_row .= trim($mfg_multipack_disc_amt).'|'.trim($mfg_promo_desc).'|'.trim($mfg_buydown_desc).'|'.trim($mfg_buydown_amt).'|'.trim($mfg_multipack_desc).'|'.trim($Loyalty_ID_Number).'|'.trim($pid_coupon_disc).'|';
                        
                        
                        
                        /*$data_row .= '|'.$scan_id.'|'.$register_id.'|'.$quantity.'|'.$price.'|'.$upc_code.'|'.$upc_description.'|'.$unit_of_measure;
    
                        $data_row .= '|'.$promo_flag.'|'.$outlet_multipack_flag.'|'.$outlet_multipack_qty.'|'.$outlet_multipack_disc_amt.'|'.$acct_promo_name.'|'.$acct_disc_amt.'|'.$mfg_disc_amt.'|'.$pid_coupon.'|'.$pid_coupon_disc;
    
                        $data_row .= '|'.$mfg_multipack_flag.'|'.$mfg_multipack_qty.'|'.$mfg_multipack_disc_amt.'|'.$mfg_promo_desc;
                        
                        $data_row .= 
                        
                        $data_row .= '|'.$mfg_buydown_desc.'|'.$mfg_buydown_amt;
                        
                        $data_row .= '|'.$mfg_multipack_desc.'|'.$acct_loyalty_id.'|'.$coupon_desc;*/
                        
                        $data_row .= PHP_EOL;
                        
    				}                   
                }


				
				
				$data_row = $data_first_row.''.$data_row;

			}else{
				$data_row = 'Sorry no data found!';
			}
			
			$store = $model->getStore();
			
// 			echo '<pre>';print_r($store);echo '<pre>'; die;
			
// 			$store = json_decode(json_encode($store), true);
			$store_name_txt = isset($store['name']) ? $store['name'] : "";
			
			//remove all spaces from the store name
			$store_name_txt = str_replace(' ', '', $store_name_txt);
			
			$input['week_ending_date'] = str_replace('/','-',$input['week_ending_date']);
            $week_ending_date_format = \DateTime::createFromFormat('m-d-Y', $input['week_ending_date']);
            
			$week_ending_date_txt = $week_ending_date_format->format('Ymd');
           
			$management_account_number_txt = $input['management_account_number'];
            $file_name_txt = $store_name_txt.'_'.$management_account_number_txt.'_'.$week_ending_date_txt.'.txt';
            
// echo $file_name_txt; die;			
			$file_path = 'scan-data-report.txt';

			// $myfile = fopen( env('DIR_TEMPLATE')."/scan-data-report.txt", "w");
			
// 			echo $data_row; die;

			// fwrite($myfile,$data_row);
            // fclose($myfile);
            Storage::disk('scan_data')->put($file_path, $data_row);
			
			/*if(session()->has('reconcile_file_path')){
                Session::forget('success');
			}*/
			
// 			echo '<pre>';print_r($scan_data_reconcile_string_body);echo '</pre>';die;
			
			if(isset($scan_data_reconcile_string_body)){
			    
			 //   echo __LINE__; die;
			    
			    // $this->session->data['reconcile_file_path'] = $reconcile_file_path = env('DIR_TEMPLATE')."scan-data-reconcilation-report-".session()->get('sid').".txt";
                
                // session()->put('reconcile_file_path', "scan-data-reconcilation-report-".session()->get('sid').".txt");
                $reconcile_file_path = "scan-data-reconcilation-report-".session()->get('sid').".txt";
                $data['reconcile_file_path'] = $reconcile_file_path;
                \Session::put('reconcile_file_path', $reconcile_file_path); 
                Storage::disk('scan_data')->put('scan-data-report.txt', $data_row);
    			
    			// $reconcile_file = fopen($reconcile_file_path, "w");
    			$scan_data_reconcile_string = $scan_data_reconcile_string_head.$week_ending_date_format->format('m/d/Y').','.$scan_data_reconcile_string_body;
    			// fwrite($reconcile_file,$scan_data_reconcile_string);
    			// fclose($reconcile_file);
    			
    			
    			
    // 			echo $scan_data_reconcile_string; die;
    			Storage::disk('scan_data')->put($reconcile_file_path, $scan_data_reconcile_string);
    			unset($scan_data_reconcile_string);
			}
// echo __LINE__; die;			
			unset($data_row);
            $file_url = Storage::disk('scan_data')->getAdapter()->getPathPrefix().'/scan-data-report.txt';
			$content = file_get_contents ($file_url);
			header ('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($file_name_txt));
            echo $content;
            // return Storage::download($file_path);
			exit;
			
		}

		$url = '';
			
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => url('/dashbord')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Scan Data Report",
			'href' => url('/scan_data_report')
		);

		$data['current_url'] = url('/scan_data_report');
		$data['print_page'] = url('/scan_data_report/print_page');
		$data['pdf_save_page'] = url('/scan_data_report/pdf_save_page'); 
		$data['reconcile_scan_data'] = url('/scan_data_report/reconcile_scan_data'); 
		$data['get_categories_url'] = url('/scan_data_report/get_categories'); 
		
		$data['heading_title'] = "Scan Data Report";

		$data['text_list'] = "Scan Data Report";
		$data['text_no_results'] = "No Results Found";
		$data['text_confirm'] = "";
		
		$data['button_remove'] = "Remove";
        $data['button_save'] = "Save";
        $data['button_view'] = "View";
        $data['button_add'] = "Add";
        $data['button_edit'] = "Edit";
        $data['button_delete'] = "Delete";
        $data['button_rebuild'] = "Rebuild";
		
		$data['token'] = "";

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['success'] = '';

        $data['departments'] = json_decode(json_encode($model->getDepartments()), true);
        
		
		$data['manufacturer_list'] = json_decode(json_encode($model->getManufacturers()), true);
		
// 		print_r($data['manufacturers']); die;
		
		$data['new_database'] = session()->get('new_database');

		$data['sid'] = session()->get('sid');
		
		$data['store_name'] = session()->get('storename');
		$data['store_name_without_space'] = str_replace(' ', '', session()->get('storename'));
		
		
		$data['storename'] = session()->get('storename');
        $data['storeaddress'] = session()->get('storeaddress');
        $data['storephone'] = session()->get('storephone');
        
        $store = $model->getStore();
			

// 			$store = json_decode(json_encode($store), true);
			$store_name_txt = isset($store['name']) ? $store['name'] : "";
			
			//remove all spaces from the store name
			$data['store_name_without_space'] = str_replace(' ', '', $store_name_txt);
        

		$data['header'] = "";
		$data['column_left'] = "";
		$data['footer'] = "";
		
		$data['categories'] = json_decode(json_encode($model->getCategories()), true); 
		
		return view('ScanDataReport.scan_data_report_list', $data);
		// $this->response->setOutput($this->load->view('administration/scan_data_report_list', $data));
	}
	
	
	public function reconcile_scan_data(Request $request){
        $content = '';
        $input = $request->all();
        $reconcile_file_path = storage_path("app/public/scan_data/scan-data-reconcilation-report-".session()->get('sid').".txt");
        // echo '<pre>';print_r($reconcile_file_path);echo '</pre>'; die;
        
        $error = '';
        
        try{
            $temp_contents = file_get_contents($reconcile_file_path);
        }
        catch(\Exception $e){
            $error = $e->getMessage();
        }
        
        if(empty(trim($error))){
            $length = strlen($temp_contents);
        
            if($length > 115){
    	       // $file_path = session()->get('reconcile_file_path');
    	       // $content = file_get_contents ($file_path);
    	       
    	       $content = $temp_contents;
    	       unlink($reconcile_file_path);
    	    }
        }
        
	    
	    $file_name_text = 'scan_data_reconciliation.txt';
	    header ('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($file_name_text));
		echo $content;
		exit;
	}
	
	
	public function get_categories(Request $request){
        $input = $request->all();
        $model = new ScanDataReport();
        $html = '<option value="" disabled="disabled">-- Select Category(s) --</option>';
	    if(isset($input['department_ids']) && count($input['department_ids']) > 0){
	        
	        $report_datas = $model->get_categories($input['department_ids']);
	        $report_datas = json_decode(json_encode($report_datas), true); 
	        foreach($report_datas as $category){
	            $html .= "<option value='".$category['value']."'>".$category['name']."</option>";
	        }
	    }
	    echo $html;
	}


}
