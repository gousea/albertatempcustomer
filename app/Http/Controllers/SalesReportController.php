<?php
namespace App\Http\Controllers;

use App\Model\SalesReport;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use PDF;

class SalesReportController extends Controller {

	public function index(Request $request) {
		return Self::getlist($request);
    }

    protected static function getList(Request $request) {
        $input = $request->all();
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $salesReport = new SalesReport();
        $url = '';
        $data = array();
        if ($request->isMethod('post')) {
            //dd($input);
            $report_datas = $salesReport->getsalesdata($input);                
            $data['reports'] = $report_datas;            
            $data['p_start_date'] = $input['start_date'];
            $data['p_end_date'] = $input['end_date'];
        
            Session::put('reports', $data['reports']);
            Session::put('p_start_date', $data['p_start_date']);
            Session::put('p_end_date', $data['p_end_date']);        
        }
        
        if(isset($data['reports']['error'])=='Error'){
            $data['error_warning'] ="Please update POS to Version 3.0.7";
        } 
        
        $store_data=$salesReport->getStore();
        $store = $store_data[0];
        $request->session()->put('storename', $store['vstorename']);
        $request->session()->put('storeaddress', $store['vaddress1']);
        $request->session()->put('storephone', $store['vphone1']);
    
        $data['store_name'] = $request->session()->get('storename');
        $data['storename'] = $request->session()->get('storename');
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');

        // echo "<pre>";
        // print_r($data);
        // die;
        return view('salesreport.salesreport')->with($data);
    }
    
    public function csv_export(Request $request) {

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
    
            $data['reports'] = $request->session()->get('reports');
            
            $data_row = '';
            
            $data_row .= "Store Name: ".$request->session()->get('storename').PHP_EOL;
            $data_row .= "Store Address: ".$request->session()->get('storeaddress').PHP_EOL;
            $data_row .= "Store Phone: ".$request->session()->get('storephone').PHP_EOL;
            
            if(count($data['reports']) > 0){
            $data_row .= 'Date,Store Sales (Excluded Tax,Non-Taxable Sales,Taxable Sales,Tax1 Sales,Tax2 Sales,Tax3 Sales,Sales Tax,Tax1,Tax2,Tax3,Total Store Sales,Fuel Sales,Lotto Sales,Liablity Sales,Total Sales,House Charged,House Charge Payments,Bottle Deposit,Bottle Deposit Redeem,Total Paid out,Cash,Coupon,Check,Credit Card Total,EBT Cash,EBT'.PHP_EOL;
                        
                        $tot_StoreSalesExclTax = 0;
                        $tot_NonTaxableSales = 0;
                        $tot_TaxableSales = 0;
                        $tot_Tax1Sales = 0;
                        $tot_Tax2Sales = 0;
                        $tot_Tax3Sales = 0;
                        $tot_TotalTax = 0;
                        $tot_Tax1 = 0;
                        $tot_Tax2 = 0;
                        $tot_Tax3 = 0;
                        $tot_TotalStoreSales = 0;
                        $tot_TotalFuelSales= 0;
                        $tot_TotalLottorySales = 0;
                        $tot_TotalLiabilitySales = 0;
                        
                        $tot_TotalSales = 0;
                        $tot_HouseCharged = 0;
                        $tot_HouseChargePayments = 0;
                        $tot_BottleDeposit = 0;
                        $tot_BottleDepositRedeem = 0;
                        $tot_TotalPaidout =0;
                        $tot_CashTender = 0;
                        $tot_CouponTender = 0;
                        $tot_CheckTender= 0;
                        $tot_CreditCardTender= 0;
                        $tot_EBTCash = 0;
                        $tot_EBT= 0;
            foreach ($data['reports'] as $key => $value) {
                        $tot_StoreSalesExclTax = $tot_StoreSalesExclTax+$value['StoreSalesExclTax'];
                        $tot_NonTaxableSales = $tot_NonTaxableSales+$value['NonTaxableSales'];
                        $tot_TaxableSales = $tot_TaxableSales+$value['TaxableSales'];
                        $tot_Tax1Sales = $tot_Tax1Sales+$value['Tax1Sales'];
                        $tot_Tax2Sales = $tot_Tax2Sales+$value['Tax2Sales'];
                        $tot_Tax3Sales = $tot_Tax3Sales+$value['Tax3Sales'];
                        $tot_TotalTax = $tot_TotalTax+$value['TotalTax'];
                        $tot_Tax1 = $tot_Tax1+$value['Tax1'];
                        $tot_Tax2 = $tot_Tax2+$value['Tax2'];
                        $tot_Tax3 = $tot_Tax3+$value['Tax3'];
                        $tot_TotalStoreSales = $tot_TotalStoreSales+$value['TotalStoreSales'];
                        $tot_TotalFuelSales= $tot_TotalFuelSales+$value['TotalFuelSales'];
                        $tot_TotalLottorySales = $tot_TotalLottorySales+$value['TotalLottorySales'];
                        $tot_TotalLiabilitySales = $tot_TotalLiabilitySales+$value['TotalLiabilitySales'];
                        
                        $tot_TotalSales = $tot_TotalSales+$value['TotalSales'];
                        $tot_HouseCharged = $tot_HouseCharged+$value['HouseCharged'];
                        $tot_HouseChargePayments = $tot_HouseChargePayments+$value['HouseChargePayments'];
                        $tot_BottleDeposit = $tot_BottleDeposit+$value['bottledeposit'];
                        $tot_BottleDepositRedeem = $tot_BottleDepositRedeem+$value['bottledepositredeem'];
                        $tot_TotalPaidout = $tot_TotalPaidout+$value['TotalPaidout'];
                        $tot_CashTender = $tot_CashTender+$value['CashTender'];
                        $tot_CouponTender = $tot_CouponTender+$value['CouponTender'];
                        $tot_CheckTender= $tot_CheckTender+$value['CheckTender'];
                        $tot_CreditCardTender= $tot_CreditCardTender+$value['CreditCardTender'];
                        $tot_EBTCash = $tot_EBTCash+$value['EBTCash'];
                        $tot_EBT= $tot_EBT+$value['EBT'];
            }
                 $data_row .= 'Total,'.number_format((float)$tot_StoreSalesExclTax, 2, '.', '').','.number_format((float)$tot_NonTaxableSales, 2, '.', '').','.number_format((float)$tot_TaxableSales, 2, '.', '').','.number_format((float)$tot_Tax1Sales, 2, '.', '').','.number_format((float)$tot_Tax2Sales, 2, '.', '').','.number_format((float)$tot_Tax3Sales, 2, '.', '').','.number_format((float)$tot_TotalTax, 2, '.', '').','.number_format((float)$tot_Tax1, 2, '.', '').','.number_format((float)$tot_Tax2, 2, '.', '').','.number_format((float)$tot_Tax3, 2, '.', '').','.number_format((float)$tot_TotalStoreSales, 2, '.', '').','.number_format((float)$tot_TotalFuelSales, 2, '.', '').','.number_format((float)$tot_TotalLottorySales, 2, '.', '').','.number_format((float)$tot_TotalLiabilitySales, 2, '.', '').','.number_format((float)$tot_TotalSales, 2, '.', '').','.number_format((float)$tot_HouseChargePayments, 2, '.', '').','.number_format((float)$tot_BottleDeposit, 2, '.', '').','.number_format((float)$tot_BottleDepositRedeem, 2, '.', '').','.number_format((float)$tot_TotalPaidout, 2, '.', '').','.number_format((float)$tot_CashTender, 2, '.', '').','.number_format((float)$tot_CouponTender, 2, '.', '').','.number_format((float)$tot_CheckTender, 2, '.', '').','.number_format((float)$tot_CreditCardTender, 2, '.', '').','.number_format((float)$tot_EBTCash, 2, '.', '').','.number_format((float)$tot_EBT, 2, '.', '').PHP_EOL;
               
                foreach ($data['reports'] as $key => $value) {
    
                  
                  $data_row .= $value['eoddate'].',' .number_format((float)$value['StoreSalesExclTax'], 2, '.', '').','.number_format((float)$value['NonTaxableSales'], 2, '.', '').','.number_format((float)$value['TaxableSales'], 2, '.', '').','.number_format((float)$value['Tax1Sales'], 2, '.', '').','.number_format((float)$value['Tax2Sales'], 2, '.', '').','.number_format((float)$value['Tax3Sales'], 2, '.', '').','.number_format((float)$value['TotalTax'], 2, '.', '').','.number_format((float)$value['Tax1'], 2, '.', '').','.number_format((float)$value['Tax2'], 2, '.', '').','.number_format((float)$value['Tax3'], 2, '.', '').','.number_format((float)$value['TotalStoreSales'], 2, '.', '').','.number_format((float)$value['TotalFuelSales'], 2, '.', '').','.number_format((float)$value['TotalLottorySales'], 2, '.', '').','.number_format((float)$value['TotalLiabilitySales'], 2, '.', '').','.number_format((float)$value['TotalSales'], 2, '.', '').','.number_format((float)$value['HouseChargePayments'], 2, '.', '').','.number_format((float)$value['bottledeposit'], 2, '.', '').','.number_format((float)$value['bottledepositredeem'], 2, '.', '').','.number_format((float)$value['TotalPaidout'], 2, '.', '').','.number_format((float)$value['CashTender'], 2, '.', '').','.number_format((float)$value['CouponTender'], 2, '.', '').','.number_format((float)$value['CheckTender'], 2, '.', '').','.number_format((float)$value['CreditCardTender'], 2, '.', '').','.number_format((float)$value['EBTCash'], 2, '.', '').','.number_format((float)$value['EBT'], 2, '.', '').PHP_EOL;
    
                }    
    
               
    
            }else{
                $data_row = 'Sorry no data found!';
            }

            $file_name_csv  = 'sales-report.csv';
            $file_path =public_path('image/sales-report.csv');
            $myfile = fopen(public_path('image/sales-report.csv'), "w");

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
        
        $data['reports'] = $request->session()->get('reports');
        $data['p_start_date'] = $request->session()->get('p_start_date');
        $data['p_end_date'] = $request->session()->get('p_end_date');    
        $data['storename'] = $request->session()->get('storename');        
        $data['storename'] = $request->session()->get('storename');
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');
        $data['heading_title'] = 'Sales Report';
        return view('salesreport.print_sales_report_page',$data);  
    }
    
    public function pdf_save_page(Request $request) {

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
    
        $data['reports'] = $request->session()->get('reports');
        $data['p_start_date'] = $request->session()->get('p_start_date');
        $data['p_end_date'] = $request->session()->get('p_end_date');    
        $data['storename'] = $request->session()->get('storename');        
        $data['storename'] = $request->session()->get('storename');
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');
    
        $data['heading_title'] = 'Sales Report';

        $pdf = PDF::loadView('salesreport.print_sales_report_page',$data);
        return $pdf->download('Sales-Report.pdf');
    }   
    
    public function reportdata(Request $request){
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
        
}
