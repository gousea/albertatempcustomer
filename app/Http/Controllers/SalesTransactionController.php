<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;
class SalesTransactionController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        
        ini_set('max_execution_time', -1);
        $Reports = new Reports;
        $userid_list = $Reports->getSalesReportuserid();
        
	      $data['userid_list']=$userid_list;
       
        return view('Reports.SalesTransaction',$data);
        
    }

   

    public function getlist(Request $request){
        ini_set('max_execution_time', -1);

        $input = $request->all();

        $selectvalue = $input['report_by']??'';
        $amounttype =  $input['amount_by'] ?? '';
        $amount = $input['amount'] ?? '';
        $registerid = $input['iuserid'] ?? '';

        $start_date=$input['start_date'] ?? '' ;
        $end_date=$input['end_date']?? '';
        
        
        $Reports = new Reports;
        $store=$Reports->getStore();

        $data_data = $Reports->getSalesReport($request,$start_date,$end_date, $selectvalue,$amounttype,$amount,$registerid);
        
        session()->put('session_sdate',  $start_date);
        session()->put('session_edate',  $end_date);
        session()->put('session_store',  $store);
        
        
        //for new functinality price match
        session()->put('session_selectvalue',$selectvalue);
        session()->put('session_amounttype',  $amounttype);
        session()->put('session_amount',  $amount);

        $reports = $data_data;
        $userid_list = $Reports->getSalesReportuserid();
        
	    
        return view('Reports.SalesTransaction', compact('reports','store','start_date','end_date','userid_list'));
       
    }
    
    
    public function getsaleid(Request $request){
        
        ini_set('max_execution_time', -1);
        
        $selectvalue= session()->get('session_selectvalue');
        $amounttype=session()->get('session_amounttype');
        $amount=session()->get('session_amount');
        
        $input = $request->all();

        $salesid=$input['salesid'];
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
            //     foreach($sales_detail as $sales)
            //     {
            //         $sub_total+=$sales->nextunitprice;
            //         $noofitems+=$sales->ndebitqty;
            //         $html.='
                        
            //             <tr>
            //                 <td>'.$sales->ndebitqty.'</td>
            //                 <td>'.$sales->npack.'</td>
            //                 <td>'.$sales->vitemcode.'</td>
            //                 <td>'.$sales->vitemname.'</td>
            //                 <td>'.$sales->vsize.'</td>
            //                 <td>'.$sales->nunitprice.'</td>
            //                 <td>'.$sales->nextunitprice.'</td>
            //             </tr>';
    
            // }
            
            foreach($sales_detail as $sales)
                {
                    $sub_total+=$sales->nextunitprice;
                    $noofitems+=$sales->ndebitqty;
                    
            if(isset($selectvalue) && $selectvalue =='Price'){
               if($amounttype=='less'&& $sales->nunitprice<$amount){
                   
                    $html.='
         
                        <tr>
                            <td><b>'.$sales->ndebitqty.'</b></td>
                            <td><b>'.$sales->npack.'</b></td>
                            <td><b>'.$sales->vitemcode.'</b></td>
                            <td><b>'.$sales->vitemname.'</b></td>
                            <td><b>'.$sales->vsize.'</b></td>
                            <td><b>'.$sales->nunitprice.'</b></td>
                            <td><b>'.$sales->nextunitprice.'</b></td>
                        </tr>';
              
                }
                 elseif($amounttype=='greater'&& $sales->nunitprice>$amount){
                   
                    $html.='
         
                        <tr>
                            <td><b>'.$sales->ndebitqty.'</b></td>
                            <td><b>'.$sales->npack.'</b></td>
                            <td><b>'.$sales->vitemcode.'</b></td>
                            <td><b>'.$sales->vitemname.'</b></td>
                            <td><b>'.$sales->vsize.'</b></td>
                            <td><b>'.$sales->nunitprice.'</b></td>
                            <td><b>'.$sales->nextunitprice.'</b></td>
                        </tr>';
              
                }
                 elseif($amounttype=='equal'&& $sales->nunitprice==$amount){
                   
                    $html.='
         
                        <tr>
                            <td><b>'.$sales->ndebitqty.'</b></td>
                            <td><b>'.$sales->npack.'</b></td>
                            <td><b>'.$sales->vitemcode.'</b></td>
                            <td><b>'.$sales->vitemname.'</b></td>
                            <td><b>'.$sales->vsize.'</b></td>
                            <td><b>'.$sales->nunitprice.'</b></td>
                            <td><b>'.$sales->nextunitprice.'</b></td>
                        </tr>';
              
                }
                 else{   
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
            else{   
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
    
    
        $myfile = fopen(resource_path('views/sale.blade.php'), "w");
        
        fwrite($myfile,$file);
        fclose($myfile);
    
        $return['code'] = 1;
        $return['data'] = $html;
       
        echo json_encode($return);
        exit;
      }

    }
    

    public function print()
    {
        
        ini_set('max_execution_time', -1);
        return view('sale');  

    }
    
     
}
