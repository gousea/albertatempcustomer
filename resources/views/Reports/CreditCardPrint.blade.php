
<?php 

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

$html = '<p><strong>Store Name: </strong> '.  session()->get('storeName')  .'</p>'.'<p><strong>Store Address: </strong>'.  $store_info['vaddress1']  .'</p>'.'<p><strong>Store Phone: </strong>'. $store_info['vphone1']  .'</p>';

$html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <table width="100%" border="0" cellspacing="5" cellpadding="0" style="margin-bottom:15px;">
        <tr>
            <td align="center">
            <h3>Credit Card Report</h3>
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
        <td width="50%" align="left"><strong>'.$store_info['vstorename'].'</strong></td>
        <td width="25%" align="right"><strong>Status</strong></td>
        <td>&nbsp;&nbsp;'.$sales_header['estatus'].'</td>
        </tr>
        <tr style="line-height:25px;">
        <td width="50%" align="left"><strong>'.$store_info['vaddress1'].'</strong></td>
        <td width="25%" align="right"><strong>Ordered</strong></td>
        <td>&nbsp;&nbsp;'.$trn_date.'</td>
        </tr>
        <tr style="line-height:25px;">
        <td width="50%" align="left"><strong>'.$store_info['vcity']." ".$store_info['vstate']." ".$store_info['vzip'].'</strong></td>
        <td width="25%" align="right"><strong>Invoiced</strong></td>
        <td>&nbsp;&nbsp;'.$trn_date.'</td>
        </tr>
        <tr style="line-height:25px;">
        <td width="50%" align="left"><strong>'.$store_info['vphone1'].'</strong></td>
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
        <td width="50%" align="left">&nbsp;</td>
        <td width="25%" align="right"><strong>Authcode</strong></td>
        <td>&nbsp;&nbsp;'.$vauthcode.'</td>
        </tr>
        <tr style="line-height:25px;">
        <td width="50%" align="left"><strong>Bill</strong>&nbsp;</td>
        <td width="25%" align="right"><strong>Ship To</strong></td>
        <td>&nbsp;&nbsp;</td>
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
    $html.='<th style="text-align:right;">Qty</th>';
    $html.='<th style="text-align:right;">Pack</th>';
    $html.='<th style="text-align:left;">SKU</th>';
    $html.='<th style="text-align:left;">Item Name</th>';
    $html.='<th style="text-align:left;">Size</th>';
    $html.='<th style="text-align:right;">Unit Price</th>';
    $html.='<th style="text-align:right;">Total Price</th>';
    $html.='</tr>';
    foreach($sales_detail as $sales)
    {
        $sub_total+=$sales->nextunitprice;
        $noofitems+=$sales->ndebitqty;
        $html.='
            
            <tr>
            <td style="text-align:right;">'.$sales->ndebitqty.'</td>
            <td style="text-align:right;">'.$sales->npack.'</td>
            <td style="text-align:left;">'.$sales->vitemcode.'</td>
            <td style="text-align:left;">'.$sales->vitemname.'</td>
            <td style="text-align:left;">'.$sales->vsize.'</td>
            <td style="text-align:right;">'.$sales->nunitprice.'</td>
            <td style="text-align:right;">'.$sales->nextunitprice.'</td>
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
  
  echo $html;

?>