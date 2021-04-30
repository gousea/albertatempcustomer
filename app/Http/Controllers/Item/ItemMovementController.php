<?php

namespace App\Http\Controllers\Item;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use PDF;
use App\Model\Reports;
use DateTime;

use App\Model\Item;
use App\Model\ItemMovement;
use App\Model\Store;

class ItemMovementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $data['reportdata'] = url('/item/item_movement_report/reportdata');
        $data['item_movement_data'] = url('/item/item_movement_report/item_movement_data');
        $data['print_page'] = url('/item/item_movement_report/print_page');
        $data['pdf_save_page'] = url('/item/item_movement_report/pdf_save_page');
        $data['csv_export'] = url('/item/item_movement_report/csv_export');
        $data['searchitem'] = url('/item/item_movement_report/searchitem');
        $data['item_movement_print_data'] = url('/item/item_movement_report/item_movement_print_data');
        $data['printpage'] = url('/item/item_movement_report/printpage');
		
		
        // 		if (isset($this->error['warning'])) {
        // 			$data['error_warning'] = $this->error['warning'];
        // 		} else {
        // 			$data['error_warning'] = '';
        // 		}
        
        $data['store_name'] = session()->get('storename');
        
		return view('items.item_movement', compact('data'));
    }
    
    protected function getList(Request $request) {
        
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
		$reports = '';
		
		$check = $request->all();
		
        if($check['search_iitemid'] == '' && $check['search_vbarcode'] == '' ){
            
            $data['reportdata'] = url('/item/item_movement_report/reportdata');
            $data['item_movement_data'] = url('/item/item_movement_report/item_movement_data');
            $data['print_page'] = url('/item/item_movement_report/print_page');
            $data['pdf_save_page'] = url('/item/item_movement_report/pdf_save_page');
            $data['csv_export'] = url('/item/item_movement_report/csv_export');
            $data['searchitem'] = url('/item/item_movement_report/searchitem');
            $data['item_movement_print_data'] = url('/item/item_movement_report/item_movement_print_data');
            $data['printpage'] = url('/item/item_movement_report/printpage');
    		
    		
            // 		if (isset($this->error['warning'])) {
            // 			$data['error_warning'] = $this->error['warning'];
            // 		} else {
            // 			$data['error_warning'] = '';
            // 		}
            
            $data['store_name'] = session()->get('storename');
            
            // 		return view('items.item_movement', compact('data'));
		    return view('items.item_movement',  compact('data'))->with('message', "No item matching with the search");
		}
        
        if (($request->isMethod('post'))) {
          
            $input = $request->all();
            
            $ItemMovement = new ItemMovement;
            $reports = $ItemMovement->getItemMovementReport($input);
            // dd($reports);
            $data['report_by'] = $input['report_by'];
            $data['search_iitemid'] = $input['search_iitemid'];
            $data['search_vbarcode'] = $input['search_vbarcode'];
            
            session()->put('reports', $reports);
            session()->put('search_iitemid', $data['search_iitemid']);
            session()->put('search_vbarcode', $data['search_vbarcode']);
            session()->put('report_by', $data['report_by']);
            
            $child_detail="select iitemid , vbarcode,vitemname from mst_item where parentid='". $data['search_iitemid'] ."'" ;     
            $Child_id = DB::connection('mysql_dynamic')->select($child_detail);
            
            
            $parent_detail="SELECT p.iitemid ,p.vbarcode FROM mst_item c
                            join mst_item p ON c.parentid = p.iitemid
                            where  c.iitemid='". $data['search_iitemid'] ."'" ;
            $parent_id = DB::connection('mysql_dynamic')->select($parent_detail);   
           
            
            if(isset($Child_id) && !empty($Child_id)){
              $childreports= $ItemMovement->getItemMovementReport_child($Child_id);
              
            }
            
            else{
              $childreports=array();
              
            }
            
            //parent data start--------------------------
            if(isset($parent_id) && !empty($parent_id)){
                $parentreports= $ItemMovement->getItemMovementReport_child($parent_id);
               
            }
            else{
                $parentreports=array();
                
            }
           //------------------------------------ 
                         
        }
        
        
        $data['item_movement_data'] = url('/item/item_movement_report/item_movement_data');
        $data['print_page'] = url('/item/item_movement_report/print_page');
        $data['pdf_save_page'] = url('/item/item_movement_report/pdf_save_page');
        $data['csv_export'] = url('/item/item_movement_report/csv_export');
        $data['searchitem'] = url('/item/item_movement_report/searchitem');
        $data['item_movement_print_data'] = url('/item/item_movement_report/item_movement_print_data');
        $data['printpage'] = url('/item/item_movement_report/printpage');
		
		
        // 		if (isset($this->error['warning'])) {
        // 			$data['error_warning'] = $this->error['warning'];
        // 		} else {
        // 			$data['error_warning'] = '';
        // 		}
        
        $data['store_name'] = session()->get('storename');
        
		return view('items.item_movement', compact('data', 'reports','childreports','parentreports'));
	}
	
	public function searchitem(Request $request) 
	{
        $return = array();
        $input = $request->all();
        
        if ($request->isMethod('get') && isset($input['term'])) {
            
            $search = $input['term'];
            $datas = DB::connection('mysql_dynamic')->select("SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname FROM mst_item as mi WHERE mi.estatus='Active' AND (mi.vitemname LIKE  '%" .($search). "%' OR mi.vbarcode LIKE  '%" .($search). "%')");
            
            foreach ($datas as $key => $value) {
                $temp = array();
                $temp['iitemid'] = $value->iitemid;
                $temp['vbarcode'] = $value->vbarcode;
                $temp['vitemname'] = $value->vitemname;
                $return[] = $temp;
            }
        }
        return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
         
    }
    
    public function item_movement_data(Request $request) 
    {
        $return = array();
        $input = $request->all();
        
        if((isset($input['vbarcode']) && !empty($input['vbarcode'])) && (isset($input['start_date']) && !empty($input['start_date'])) && (isset($input['end_date']) && !empty($input['end_date'])) && (isset($input['data_by']) && !empty($input['data_by']))){
            
            $ItemMovement = new ItemMovement;
            $return = $ItemMovement->getItemMovementData($input['vbarcode'],$input['start_date'],$input['end_date'],$input['data_by']);
      
        }
        // dd($return);
        return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
        
    }
    
    public function pdf_save_page() {

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
            
        $data['reports'] = session()->get('reports') ;
        
        $data['search_iitemid'] = session()->get('search_iitemid') ;
        $data['search_vbarcode'] = session()->get('search_vbarcode') ;
        $data['report_by'] = session()->get('report_by');
        
        $data['storename'] = session()->get('storename');
        
        $data['heading_title'] = 'Item Movement Report';
        
        // $html = $this->load->view('administration/print_item_movement_report_page', $data);
        
        $pdf = PDF::loadView('items.print_item_movement_report_page',$data);
        
        return $pdf->download('taxreport.pdf');
    }
    
    public function print_page()
    {
        
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
            
        $data['reports'] = session()->get('reports') ;
        
        $data['search_iitemid'] = session()->get('search_iitemid') ;
        $data['search_vbarcode'] = session()->get('search_vbarcode') ;
        $data['report_by'] = session()->get('report_by');
        
        $data['storename'] = session()->get('storename');
        
        $data['heading_title'] = 'Item Movement Report';
        
       
        return view('items.print_item_movement_report_page',$data);  

    }
    
    public function item_movement_print_data(Request $request) 
    {
        $input = $request->all();
        
        $data['heading_title'] = 'Item Movement';
        
        $salesid = ($input['isalesid'])?$input['isalesid']:'0';
        $idettrnid = ($input['idettrnid'])?$input['idettrnid']:'0';
        
        $Reports = new Reports;
        $sales_header_data= $Reports->getSalesById($salesid);
        $sales_header =(array)$sales_header_data[0];
        

        $trn_date = DateTime::createFromFormat('m-d-Y h:i A', $sales_header['trandate']);
        $trn_date = $trn_date->format('m-d-Y');
        
        $sales_detail_data= $Reports->getSalesPerview($salesid);
        $sales_detail=$sales_detail_data;

        $sales_tender_data=$Reports->getSalesByTender($salesid);
        $sales_tender=$sales_tender_data;

        $sales_customer_data=$Reports->getSalesByCustomer($sales_header['icustomerid']);
        $sales_customer=(array)$sales_customer_data;

        $store_info_data=$Reports->getStore();
        $store_info=(array)$store_info_data[0];


        if(count($sales_customer) > 0){
            $c_name = $sales_customer['vfname'].' '.$sales_customer['vlname'];
            $c_address = $sales_customer['vaddress1'];
            $c_city = $sales_customer['vcity'].',';
            $c_state = $sales_customer['vstate'];
            $c_zip = $sales_customer['vzip'];
            $c_phone = 'Phone: '.$sales_customer['vphone'];
        }else{
            $c_name = '';
            $c_address = '';
            $c_city = '';
            $c_state = '';
            $c_zip = '';
            $c_phone = '';
        }
        
        $html='';
        
        $html='<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <table width="100%" border="0" cellspacing="5" cellpadding="0" style="margin-bottom:15px;">
                        <tr>
                        <!-- <p style="text-align:left"><img src="image/logoreport.jpg"  alt="Pos logo" width="120" height="50"></p>-->
                              <td align="center">
                                <h3>Sales Transaction</h3>
                              </td>
                      </tr>
                    </table>
                </tr>
              <tr style="border-bottom:1px solid #999;">
                <td valign="top">
                    <table width="100%" border="0" cellspacing="5" cellpadding="0" style="border-bottom:1px solid #999;">
                      <tr style="line-height:25px;">
                          <td width="50%" align="left">
                          <strong>Date</strong> '. date('m-d-Y') .'
                          </td>
                          <td width="25%" align="right">
                              <strong>Order Number</strong>
                          </td>
                          <td>&nbsp;&nbsp;'.$sales_header['isalesid'].'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong>Store Name: </strong>'.$store_info['vstorename'].'</td>
                        <td width="25%" align="right"><strong>Status</strong></td>
                        <td>&nbsp;&nbsp;'.$sales_header['estatus'].'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong>Store Address: </strong>'.$store_info['vaddress1'].'</td>
                        <td width="25%" align="right"><strong>Ordered</strong></td>
                        <td>&nbsp;&nbsp;'.$trn_date.'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        '.$store_info['vcity']." ".$store_info['vstate']." ".$store_info['vzip'].'
                        </td>
                        <td width="25%" align="right"><strong>Invoiced</strong></td>
                        <td>&nbsp;&nbsp;'.$trn_date.'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong>Store Phone: </strong></td>
                        <td width="25%" align="right"><strong>Sale Number</strong></td>
                        <td>&nbsp;&nbsp;'.$sales_header['isalesid'].'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>Sales Person</strong></td>
                        <td>&nbsp;&nbsp;'.$sales_header['vusername'].' ('.$sales_header['iuserid'].')</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>Tender Type</strong></td>
                        <td>&nbsp;&nbsp;'.$sales_header['vtendertype'].'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>TRN</strong></td>
                        <td>&nbsp;&nbsp;'.$sales_header['isalesid'].'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>TRN Time</strong></td>
                        <td>&nbsp;&nbsp;'.$sales_header['trandate'].'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">&nbsp;</td>
                        <td width="25%" align="right"><strong>Register</strong></td>
                        <td>&nbsp;&nbsp;'.$sales_header['vterminalid'].'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong>Bill</strong>&nbsp;</td>
                        <td width="25%" align="right"><strong>Ship To</strong></td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left"><strong></strong>&nbsp;</td>
                        <td width="25%" align="right"><strong>Check No</strong></td>
                        <td>&nbsp;&nbsp;'.$sales_header['checkno'].'</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">'.$c_name.'</td>
                        <td width="25%" align="right">&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">'.$c_address.'</td>
                        <td width="25%" align="right">&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">'.$c_city.' '.$c_state.' '.$c_zip.'</td>
                        <td width="25%" align="right">&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                      <tr style="line-height:25px;">
                        <td width="50%" align="left">'.$c_phone.'</td>
                        <td width="25%" align="right">&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                      </tr>
                    </table>
                </td>
              </tr>		 
              <tr style="border-bottom:1px solid #999;">
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="margin:15px 0px;">';
            if(count($sales_detail)>0)
            {
                $sub_total=0;
                $tax=0;
                $total=0;
                $noofitems =0;
                $html.='<tr>';
                $html.='<th>Qty</th>';
                $html.='<th>Pack</th>';
                $html.='<th>SKU</th>';
                $html.='<th>Item Name</th>';
                $html.='<th>Size</th>';
                $html.='<th>Unit Price</th>';
                $html.='<th>Total Price</th>';
                $html.='</tr>';
                foreach($sales_detail as $sales)
                {
                    $sub_total+=$sales->nextunitprice;
                    $noofitems+=$sales->ndebitqty;
                    if($idettrnid==$sales->idettrnid)
                        {
                    $html.='
                        
                        <tr>
                            <th style="background-color:;">'.$sales->ndebitqty.'</th>
                            <th style="background-color:;">'.$sales->npack.'</th>
                            <th style="background-color:;">'.$sales->vitemcode.'</th>
                            <th style="background-color:;">'.$sales->vitemname.'</th>
                            <th style="background-color:;">'.$sales->vsize.'</th>
                            <th style="background-color:;">'.$sales->nunitprice.'</th>
                            <th style="background-color:;">'.$sales->nextunitprice.'</th></b>
                        </tr>';
                      }else{
                          
                           $html.='
                        
                        <tr>
                            <td>'.$sales->ndebitqty.'</td>
                            <td>'.$sales->npack.'</td>
                            <td>'.$sales->vitemcode.'</td>
                            <td>'.$sales->vitemname.'</td>
                            <td>'.$sales->vsize.'</td>
                            <td>'.$sales->nunitprice.'</td>
                            <td>'.$sales->nextunitprice.'</td>
                        </tr>';
                      }
                      
            }
          $html.='</table></td>
              </tr><hr style="border-top:1px solid #999;">
              <tr style="border-bottom:1px solid #999;">
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:5px 0px;">
                  <tr>
                    <td width="70%" align="right" style="padding-bottom:5px;"><strong>Sub Total</strong></td>
                    <td width="10%" style="padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-bottom:5px;">'.$sub_total.'</td>
                  </tr>
                  <tr style="border-top:1px solid #999;">
                    <td width="70%" align="right" style="padding-top:5px;padding-bottom:5px;"><strong>Tax</strong></td>
                    <td width="10%" style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-top:5px;padding-bottom:5px;">'.$sales_header['ntaxtotal'].'</td>
                  </tr>
                  <tr style="border-top:1px solid #999;border-bottom:1px solid #999;">
                    <td width="70%" align="right" style="padding-top:5px;padding-bottom:5px;"><strong>Total</strong></td>
                    <td width="10%" style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-top:5px;padding-bottom:5px;">';
                    $total=$sub_total+$sales_header['ntaxtotal'];				
                    $html.=$total.'</td>
                  </tr>
                </table></td>
              </tr>
              <tr style="border-top:1px solid #999;border-bottom:1px solid #999;">
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:5px 0px;">';
                  
                  foreach($sales_tender as $tender)
                  {
                      if($tender->itenerid!="121")
                      {
                          $html.='<tr>
                            <td width="70%" align="right"><strong>'.$tender->vtendername.'</strong></td>
                            <td width="10%" align="right">&nbsp;</td>
                            <td width="20%" align="right">'.$tender->namount.'</td>
                          </tr>';
                      }
                  } 
                 
                  $html.='</table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr style="border-top:1px solid #999;border-bottom:1px solid #999;">
                    <td width="70%" align="right" style="padding-top:5px;padding-bottom:5px;"><strong>Tendered</strong></td>
                    <td width="10%" align="right" style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-top:5px;padding-bottom:5px;">'.$total.'</td>
                  </tr>
                  <tr style="border-bottom:1px solid #999;">
                    <td width="70%" align="right" style="padding-top:5px;padding-bottom:5px;"><strong>Your Change</strong></td>
                    <td width="10%" align="right" style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
                    <td width="20%" align="right" style="padding-top:5px;padding-bottom:5px;">'.$sales_header['nchange'].'</td>
                  </tr>
                </table></td>
              </tr>		  
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:5px 0px;display:none;">
                  <tr>
                    <td width="49%" align="left"><strong>Cashier ID : </strong>'.$sales_header['iuserid'].'</td>
                    <td width="8%">&nbsp;</td>
                    <td width="43%" align="right"><strong>Register : </strong>'.$sales_header['vterminalid'].'</td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Tender Type : </strong>'.$sales_header['vtendertype'].'</td>
                    <td>&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left"><strong>TRN : </strong>'.$sales_header['isalesid'].'</td>
                    <td>&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left"><strong>TRN Time : </strong>'.$sales_header['trandate'].'</td>
                    <td>&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              ';
              if(strlen($sales_header['licnumber']) >0)
              {
                  $html.='
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:5px 0px;">
                          <tr style="border-top:1px solid #999;border-bottom:1px solid #999;line-height:30px;">					  
                            <td align="center"><strong>Customer Licence Detail</strong></td>
                          </tr>
                          <tr>
                            <td><strong>Name :</strong> '.$sales_header['liccustomername'].'</td>
                          </tr>
                          <tr>
                            <td><strong>Birth Date : </strong>'.$sales_header['liccustomerbirthdate'].'</td>
                          </tr>
                          <tr>
                            <td><strong>Address : </strong>'.$sales_header['licaddress'].'</td>
                          </tr>
                          <tr>
                            <td><strong>Licence # : </strong>'.$sales_header['licnumber'].'</td>
                          </tr>
                          <tr>
                            <td><strong>Licence Expiry Date : </strong>'.$sales_header['licexpireddate'].'</td>
                          </tr>
                        </table></td>
                  </tr>';
              }
              $html.='
            </table>';
            
            $file='<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><div id="content">
            
      <div class="container-fluid">
        <div class="" style="margin-top:0%;">
          <div class="panel-body"> 
            <div class="row">
              <div class="col-md-12" id="printappend">'.$html.'          
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>';
    
    
        $myfile = fopen(resource_path('views/sale2.blade.php'), "w");
        
        fwrite($myfile,$file);
        fclose($myfile);
    
        $return['code'] = 1;
        $return['data'] = $html;
       
        echo json_encode($return);
        exit;
      }

        
        // $store_info= Store::All()->toArray();
        // $store_info = isset($store_info[0])?(array)$store_info[0]:[];
        
        // $ItemMovement = new ItemMovement;
        // $sales_header= $ItemMovement->getSalesById($isalesid);
        
        // $trn_date = \DateTime::createFromFormat('m-d-Y h:i A', $sales_header['trandate']);
        // $trn_date = $trn_date->format('m-d-Y');
        
        // $sales_detail= $ItemMovement->getSalesPerview($idettrnid);

        // $sales_customer= $ItemMovement->getSalesByCustomer($sales_header['icustomerid']);
        
        // if(count($sales_customer) > 0){
        //     $c_name = $sales_customer['vfname'].' '.$sales_customer['vlname'];
        //     $c_address = $sales_customer['vaddress1'];
        //     $c_city = $sales_customer['vcity'].',';
        //     $c_state = $sales_customer['vstate'];
        //     $c_zip = $sales_customer['vzip'];
        //     $c_phone = 'Phone: '.$sales_customer['vphone'];
        // }else{
        //     $c_name = '';
        //     $c_address = '';
        //     $c_city = '';
        //     $c_state = '';
        //     $c_zip = '';
        //     $c_phone = '';
        // }
  
        // $html='';
  
        // $html='<table width="100%" border="0" cellspacing="0" cellpadding="0">
        //             <tr>
        //                 <table width="100%" border="0" cellspacing="5" cellpadding="0" style="margin-bottom:15px;">
        //                     <tr>
        //                       <td align="center">
        //                       <h3>Sales Transaction Receipt</h3>
        //                       </td>
        //                     </tr>
        //                 </table>
        //             </tr>
        //             <tr style="border-bottom:1px solid #999;">
        //                 <td valign="top">
        //                     <table width="100%" border="0" cellspacing="5" cellpadding="0" style="border-bottom:1px solid #999;">
        //                         <tr style="line-height:25px;">
        //                             <td width="50%" align="left">
        //                                 <strong>Date</strong> '. date('m-d-Y') .'
        //                             </td>
        //                             <td width="25%" align="right">
        //                                 <strong>Order Number</strong>
        //                             </td>
        //                             <td>&nbsp;&nbsp;'.$sales_header['isalesid'].'</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                             <td width="50%" align="left"><strong>'.$store_info['vstorename'].'</strong></td>
        //                             <td width="25%" align="right"><strong>Status</strong></td>
        //                             <td>&nbsp;&nbsp;'.$sales_header['estatus'].'</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                             <td width="50%" align="left"><strong>'.$store_info['vaddress1'].'</strong></td>
        //                             <td width="25%" align="right"><strong>Ordered</strong></td>
        //                             <td>&nbsp;&nbsp;'.$trn_date.'</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                             <td width="50%" align="left"><strong>'.$store_info['vcity']." ".$store_info['vstate']." ".$store_info['vzip'].'</strong></td>
        //                             <td width="25%" align="right"><strong>Invoiced</strong></td>
        //                             <td>&nbsp;&nbsp;'.$trn_date.'</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                             <td width="50%" align="left"><strong>'.$store_info['vphone1'].'</strong></td>
        //                             <td width="25%" align="right"><strong>Sale Number</strong></td>
        //                             <td>&nbsp;&nbsp;'.$sales_header['isalesid'].'</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                             <td width="50%" align="left">&nbsp;</td>
        //                             <td width="25%" align="right"><strong>Sales Person</strong></td>
        //                             <td>&nbsp;&nbsp;'.$sales_header['vusername'].' ('.$sales_header['iuserid'].')</td>
        //                         </tr>
        //                             <tr style="line-height:25px;">
        //                             <td width="50%" align="left">&nbsp;</td>
        //                             <td width="25%" align="right"><strong>Tender Type</strong></td>
        //                             <td>&nbsp;&nbsp;'.$sales_header['vtendertype'].'</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                             <td width="50%" align="left">&nbsp;</td>
        //                             <td width="25%" align="right"><strong>TRN</strong></td>
        //                             <td>&nbsp;&nbsp;'.$sales_header['isalesid'].'</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                           <td width="50%" align="left">&nbsp;</td>
        //                           <td width="25%" align="right"><strong>TRN Time</strong></td>
        //                           <td>&nbsp;&nbsp;'.$sales_header['trandate'].'</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                           <td width="50%" align="left">&nbsp;</td>
        //                           <td width="25%" align="right"><strong>Register</strong></td>
        //                           <td>&nbsp;&nbsp;'.$sales_header['vterminalid'].'</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                           <td width="50%" align="left"><strong>Bill</strong>&nbsp;</td>
        //                           <td width="25%" align="right"><strong>Ship To</strong></td>
        //                           <td>&nbsp;&nbsp;</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                           <td width="50%" align="left">'.$c_name.'</td>
        //                           <td width="25%" align="right">&nbsp;</td>
        //                           <td>&nbsp;&nbsp;</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                           <td width="50%" align="left">'.$c_address.'</td>
        //                           <td width="25%" align="right">&nbsp;</td>
        //                           <td>&nbsp;&nbsp;</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                           <td width="50%" align="left">'.$c_city.' '.$c_state.' '.$c_zip.'</td>
        //                           <td width="25%" align="right">&nbsp;</td>
        //                           <td>&nbsp;&nbsp;</td>
        //                         </tr>
        //                         <tr style="line-height:25px;">
        //                           <td width="50%" align="left">'.$c_phone.'</td>
        //                           <td width="25%" align="right">&nbsp;</td>
        //                           <td>&nbsp;&nbsp;</td>
        //                         </tr>
        //                     </table>
        //                 </td>
        //             </tr>    
        //             <tr style="border-bottom:1px solid #999;">
        //                 <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="margin:15px 0px;">';

        //                     if(count($sales_detail)>0)
        //                     {
        //                       $sub_total=0;
        //                       $tax=0;
        //                       $total=0;
        //                       $noofitems =0;
        //                       $html.='<tr>';
        //                       $html.='<th>Qty</th>';
        //                       $html.='<th>Pack</th>';
        //                       $html.='<th>SKU</th>';
        //                       $html.='<th>Item Name</th>';
        //                       $html.='<th>Size</th>';
        //                       $html.='<th>Unit Price</th>';
        //                       $html.='<th>Total Price</th>';
        //                       $html.='</tr>';
                        
        //                       $sub_total = $sales_detail['nextunitprice'];
        //                       $noofitems = $sales_detail['ndebitqty'];
        //                       $html.='
                                
        //                                 <tr>
        //                                   <td>'.$sales_detail['ndebitqty'].'</td>
        //                                   <td>'.$sales_detail['npack'].'</td>
        //                                   <td>'.$sales_detail['vitemcode'].'</td>
        //                                   <td>'.$sales_detail['vitemname'].'</td>
        //                                   <td>'.$sales_detail['vsize'].'</td>
        //                                   <td>'.$sales_detail['nunitprice'].'</td>
        //                                   <td>'.$sales_detail['nextunitprice'].'</td>
        //                                 </tr>';
                              
        //                     }

        //     $html.='</table></td>
        //       </tr><hr style="border-top:1px solid #999;">
        //       <tr style="border-bottom:1px solid #999;">
        //       <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:5px 0px;">
        //         <tr>
        //         <td width="70%" align="right" style="padding-bottom:5px;"><strong>Sub Total</strong></td>
        //         <td width="10%" style="padding-bottom:5px;">&nbsp;</td>
        //         <td width="20%" align="right" style="padding-bottom:5px;">'.$sub_total.'</td>
        //         </tr>
        //         <tr style="border-top:1px solid #999;">
        //         <td width="70%" align="right" style="padding-bottom:5px;"><strong>Total</strong></td>
        //         <td width="10%" style="padding-bottom:5px;">&nbsp;</td>
        //         <td width="20%" align="right" style="padding-top:5px;padding-bottom:5px;">';
        //         $total=$sub_total;       
        //         $html.=$total.'</td>
        //         </tr>
        //       </table></td>
        //       </tr>
              
              
        //       </table></td>
        //       </tr>';
        //       $html.='
        //         </table>';
            
        //             $file='<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><div id="content">
        //             <div class="container-fluid">
        //                 <div class="" style="margin-top:0%;">
        //                     <div class="panel-body"> 
        //                         <div class="row">
        //                           <div class="col-md-12" id="printappend">'.$html.'          
        //                           </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>';
        
        // //$filepath = storage_path("logs/items/print_item_movement_report.blade.php");
        //  $myfile = fopen(resource_path('views/sale2.blade.php'), "w");
        // //$myfile = fopen($filepath, "w");
        // fwrite($myfile,$file);
        // fclose($myfile);

        // $return['code'] = 1;
        // $return['data'] = $html;
   
        // echo json_encode($return);
        // exit;
    }
    
    public function printpage()
    {
       // dd("hi");
        // $filepath = storage_path("logs/items/print_item_movement_report.blade.php");
        //return view('custom_view::logs.items.print_item_movement_report'); 
         return view('sale2');  
    }
}

?>