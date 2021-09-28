<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "End Of Day Report" ?></h3>
    <hr style="height:1px; background-color:black;">
   
</div> 
<div class="a"> 
<div class="row">
          <div class="col-md-12">
            <div class='col-md-6'>
                <p><b>Store Name:</b> {{ session()->get('storeName') }}</p>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <div class='col-md-6'>
                <p><b>Store Address: </b><?php echo $store['vaddress1']; ?></p>
            </div>    
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <div class='col-md-6'>
                <p><b>Store Phone: </b><?php echo $store['vphone1']; ?></p>
            </div>
          </div>
        </div>
           <div class="row">
          <div class="col-md-12">
            <div class='col-md-6'>
                <p><b>Date: </b><?php echo $date ; ?></p>
            </div>
          </div>
        </div>
       
    <div class="row">
         
              
            <div class="col-md-12">
                
               <div class="col-md-6">
                
                    <table class="table table-bordered table-striped table-hover">
                        
                        <tr > 
                        <th style=" text-align:center;border-style: none;background-color:white;">SALES DETAIL</th>
                        <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        <tr>
                            <th>Store Sales ( Excluding Tax)</th> 
                            <td class="text-right">{{'$'}}{{$data['StoreSalesExclTax']}} </td>
                        </tr>
                        <tr>
                            <th>Taxable Sales</th>
                            <td class="text-right">{{'$'}}{{$data['TaxableSales']}}</td>
                        </tr>
                         <tr>
                            <th>Non-Taxable Sales</th>
                            <td class="text-right">{{'$'}}{{$data['NonTaxableSales']}}</td>
                        </tr>
                         <tr>
                           
                           <th>Total  Tax &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</th>
                           <td class="text-right">{{'$'}}{{$data['TotalTax']}}</td>
                           
                       </tr>
                        @if($data['Tax1']!=0)
                              <tr>
                               <th>Tax1&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                               <td class="text-right">{{'$'}}{{$data['Tax1']}}</td>
                              </tr>
                          @endif
                          
                          @if($data['Tax2']!=0)
                               <tr>
                               <th>Tax2&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                               <td class="text-right">{{'$'}}{{$data['Tax2']}}</td>
                               </tr>
                           @endif
                           
                           @if($data['Tax3']!=0)
                           <tr>
                           <th>Tax2&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                           <td class="text-right">{{'$'}}{{$data['Tax3']}}</td>
                           </tr>
                           @endif
                       
                         <tr>
                            <th><b>Total Store Sales</b></th>
                            <td class="text-right">{{'$'}}{{$data['StoreSalesInclTax']}}</td>
                        </tr>

                        @if($data['LottoSales']!=0)
                        <tr>
                            <th>Lotto Sales</th>
                           <td class="text-right">{{'$'}}{{$data['LottoSales']}}</td>
                        </tr>
                           @endif
                         
                        @if($data['LiabilitySales']!=0)
                        <tr>
                            <th>Liablity Sales</th>
                            <td class="text-right">{{'$'}}{{$data['LiabilitySales']}}</td>
                        </tr>
                         @endif
                     
                         
                         @if($data['TotalBottleDeposit']!=0)
                        <tr>
                           
                            <th> Total Bottle Deposit</th>
                             <td class="text-right">{{'$'}}{{$data['TotalBottleDeposit']}}</td>
                            
                        </tr>
                        @endif 
                        
                        @if($data['HouseAcctPayments']!=0)
                        <tr>
                            <th>HOUSE ACCOUNT PAYMENTS    </th>
                             <td class="text-right">{{'$'}}{{$data['HouseAcctPayments']}}</td>
                            
                        </tr>
                        @endif 
                        
                     
                        @if($data['FuelSales']!=0)
                        <tr>
                            <th>Fuel Sales</th>
                           <td class="text-right">{{'$'}}{{$data['FuelSales']}}</td>
                        </tr>
                        @endif 
                        
                        <tr>
                            <th>Grand Total</th>
                            <td class="text-right">{{'$'}}{{$data['GrandTotal']}}</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;"> LOTTERY SALES DETAILS </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        
                        
                        @if($data['LotterySales']!=0)
                         
                                <tr>
                                    <th>Lottery Sales</th>
                                    <td class="text-right">{{'$'}}{{$data['LotterySales']}}</td>
                                </tr>
                         @endif    
                            
                        @if($data['instantSales']!=0)
                                <tr >
                                    <th>Instant Sales</th>
                                    <td class="text-right">{{'$'}}{{$data['instantSales']}}</td>
                                </tr>
                         @endif   
                             
                            @if($data['LotteryRedeem']!=0)
                                <tr >
                                    <th>Lottery Redeem </th>
                                   <td class="text-right">{{'$'}}{{$data['LotteryRedeem']}}</td>
                                </tr>
                            @endif  
                            
                            
                            @if($data['InstantRedeem']!=0)
                                 <tr >
                                    <th>Instant Redeem</th>
                                    <td class="text-right">{{'$'}}{{$data['InstantRedeem']}}</td>
                                    
                                 </tr>
                            @endif 
                        
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        
                        <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;"> BOTTLE DEPOSIT </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        
                        @if($data['bottledeposit']!=0)
                        <tr >
                            <th>Bottle Deposit</th>
                             <td class="text-right">{{'$'}}{{$data['bottledeposit']}}</td>
                            
                        </tr>
                        @endif 
                         
                        @if($data['bottledepositredeem']!=0)
                        <tr >
                            <th>Bottle Deposit Redeem</th>
                             <td class="text-right">{{'$'}}{{$data['bottledepositredeem']}}</td>
                        </tr>  
                        @endif 
                        
                        @if($data['bottledeposittax']!=0)
                        <tr >
                            <th>Bottle Deposit Tax</th>
                             <td class="text-right">{{'$'}}{{$data['bottledeposittax']}}</td>
                        </tr>  
                        @endif 
                        
                         @if($data['bottledepositredeemtax']!=0)
                        <tr >
                            <th>Bottle Deposit Redeem Tax</th>
                             <td class="text-right">{{'$'}}{{$data['bottledepositredeemtax']}}</td>
                        </tr>  
                        @endif 
                        
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;"> TENDER DETAILS </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        
                            @if($data['CashTender']!=0)
                            <tr>
                                <th>CASH </th>
                                <td class="text-right">{{'$'}}{{$data['CashTender']}}</td>
                            </tr>
                            @endif
                            
                             @if($data['CouponTender']!=0)
                            <tr>
                                <th>COUPON </th>
                                <td class="text-right">{{'$'}}{{$data['CouponTender']}}</td>
                            </tr>
                             @endif
                               @if($data['CreditCardTender']!=0)
                            <tr>
                                <th>CREDIT CARD </th>
                                <td class="text-right">{{'$'}}{{$data['CreditCardTender']}}</td>
                            </tr>
                            @endif
                               @if($data['CheckTender']!=0)
                            <tr>
                                <th>CHECK </th>
                                <td class="text-right">{{'$'}}{{$data['CheckTender']}}</td>
                            </tr>
                           @endif
                              @if($data['EbtTender']!=0)
                            <tr>
                                <th>EBT </th>
                                <td class="text-right">{{'$'}}{{$data['EbtTender']}}</td>
                            </tr>
                           @endif
                           
                            
                            @if($data['HouseAcctCharged']!=0)
                            <tr>
                                <th> HOUSE ACCTOUNT CHARGED </th>
                                <td class="text-right">{{'$'}}{{$data['HouseAcctCharged']}}</td>
                            </tr>
                            @endif
                            @if($data['HouseAcctCash']!=0)
                            <tr>
                                <th> HOUSE ACCT PAYMENT CASH  </th>
                                <td class="text-right">{{'$'}}{{$data['HouseAcctCash']}}</td>
                            </tr>
                             @endif
                             @if($data['HouseAcctCard']!=0)
                            <tr>
                                <th>HOUSE ACCT PAYMENT CREDITCARD</th>
                                <td class="text-right">{{'$'}}{{$data['HouseAcctCard']}}</td>
                            </tr>
                            @endif
                            @if($data['HouseAcctCheck']!=0)
                            <tr>
                                <th>  HOUSE ACCT PAYMENT CHECK </th>
                                <td class="text-right">{{'$'}}{{$data['HouseAcctCheck']}}</td>
                            </tr>
                            @endif
                           
                 
                        <tr>
                            <th>&nbsp;</th>
                            <td><?php echo ""; ?></td>
                        </tr>
           
                        <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;"> Performance Statstic </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                         
                         @if($data['Paidouts']!=0)
                        <tr>
                            <th>Total Paidout</th>
                            <td class="text-right">{{'$'}}{{$data['Paidouts']}}</td>
                            
                        </tr>
                        
                          @endif
                          @if($data['Discounted_Amount']!=0)
                        <tr>
                            <th>Discounted Amount </th>
                           <td class="text-right">{{'$'}}{{$data['Discounted_Amount']}}</td>
                            
                        </tr>
                          @endif
                        
                        @if($data['Discounted_Trns']!=0)
                        <tr>
                            <th>Discounted Transactions</th>
                            <td class="text-right">{{'$'}}{{$data['Discounted_Trns']}}</td>
                            
                        </tr>
                          @endif
                        
                      
                        
                        @if($data['Void_Trns']!=0)
                        <tr>
                            <th>Voided Transactions</th>
                           <td class="text-right">{{$data['Void_Trns']}}</td>
                        </tr>
                          @endif
                         
                        @if($data['Void_Amount']!=0) 
                        <tr>
                            <th>Voided Amount</th>
                           <td class="text-right">{{'$'}}{{$data['Void_Amount']}}</td>
                        </tr>
                          @endif
                        
                        @if($data['Deleted_Trns']!=0)
                        <tr>
                            <th>Deleted Transactions</th>
                            <td class="text-right">{{'$'}}{{$data['Deleted_Trns']}}</td>
                            
                        </tr>
                        @endif
                        
                        @if($data['Deleted_Amount']!=0)
                        <tr>
                            <th>Deleted Total  </th>
                           <td class="text-right">{{'$'}}{{$data['Deleted_Amount']}}</td>
                            
                        </tr>
                          @endif
                        
                        <tr>
                            <th>Return Transactions</th>
                           <td class="text-right">{{'$'}}{{$data['Return_Trns']}}</td>
                        </tr>
                        <tr>
                            <th>Return Amount </th>
                            <td class="text-right">{{'$'}}{{$data['Return_Amount']}}</td>
                            
                        </tr>
                        
                        
                        @if($data['NoSale_Count']!=0)
                        <tr>
                            <th>No Sale Transactions</th>
                           <td class="text-right">{{'$'}}{{$data['NoSale_Count']}}</td>
                        </tr>
                          @endif
                           @if(isset($data['Surcharges']) && $data['Surcharges']!=0)
                        <tr>
                            <th>Surcharges Collected</th>
                           <td class="text-right">{{'$'}}{{$data['Surcharges']}}</td>
                        </tr>
                          @endif
                           @if(isset($data['EbtTaxExempted']) && $data['EbtTaxExempted']!=0)
                        <tr>
                            <th>EBT Tax Exempted</th>
                           <td class="text-right">{{'$'}}{{$data['EbtTaxExempted']}}</td>
                        </tr>
                          @endif
                          
                             @if(isset($data['ConvCharge']) && $data['ConvCharge']!=0)
                        <tr>
                            <th>Convenience Charge</th>
                           <td class="text-right">{{'$'}}{{$data['ConvCharge']}}</td>
                        </tr>
                          @endif
                          
                          
                        @if(isset($data['EnvtFee']) && $data['EnvtFee']!=0)
                        <tr>
                            <th>Enveronment Fee</th>
                           <td class="text-right">{{'$'}}{{$data['EnvtFee']}}</td>
                        </tr>
                          @endif
                          
                        <tr>
                            <th>&nbsp;</th>
                            <td><?php echo ""; ?></td>
                        </tr>
           
                         
                        <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;">Productivity </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        
                        <tr>
                            <th>Transaction Count</th>
                            <td class="text-right">{{$data['Trns_Count']}}</td>
                            
                        </tr>
                        
                        <tr>
                            <th>Average Sales: </th>
                           <td class="text-right">{{'$'}}{{$data['Avg_Sales']}}</td>
                            
                        </tr>
                        
                        <tr>
                            <th>Gross Cost: </th>
                           <td class="text-right">{{'$'}}{{$data['Gross_Cost']}}</td>
                        </tr>
                         
                        <tr>
                            <th>Gross Profit: </th>
                           <td class="text-right">{{'$'}}{{$data['Gross_Profit']}}</td>
                        </tr>
                         
                        <tr>
                            <th>Gross Profit(%): </th>
                            <td class="text-right">{{'$'}}{{$data['Gross_Profit_Per']}}</td>
                            
                        </tr>
                        
 </tbody>
</table>  
</div>                    
</div>              
</div>
  <div class="row">
    <div class='col-md-12'>
       

                    <table class="table table-bordered table-striped table-hover">                   
                        <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;"></th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        <?php foreach($report_paidout_new as $v){?>
                             <?php if($v->Vendor ==="Total"){
                           
                                $ptotal=$v->Amount;
                             
                             }
                             }
                        ?>
                        <tr>
                          <th style="width:250px;">Payouts Total</th>
                          <th style="width:120px;"><?php echo "$",isset($ptotal) ? $ptotal: 0.00; ?></th>
                            
                        </tr>
                         <tr> 
                            <td>Vendor Name</td>
                            <td> Amount</td>
                             
                            <td>Register No</td>
                            <td>Time</td>
                            <td> User ID</td>
                        
                        </tr>
                       <?php foreach($report_paidout_new as $v){?>
                       <?php if($v->Vendor ==="Total"){
                       
                         continue;
                         
                         }?>
                       <tr>
                           <td><?php echo isset($v->Vendor) ? $v->Vendor: ""; ?></td>
                           <td><?php echo "$",isset($v->Amount) ? $v->Amount: 0.00; ?></td>
                           <td><?php echo isset($v->RegNo) ? $v->RegNo: 0.00; ?></td>
                           <td><?php echo isset($v->ttime) ? $v->ttime: 0.00; ?></td>
                           <td><?php echo isset($v->UserId) ? $v->UserId: 0.00; ?></td>
                       </tr>
                       
                       <?php }?>
                       
                       <?php $htotal=$num=0; ?>
                        <?php foreach($report_new_hourly as $v){
                            
                           
                                $htotal=$htotal+$v->Amount;
                                $num=$num+$v->Number;
                             
                            
                             }
                        ?>
                        
                        <tr> 
                          <th>Hourly Sales</th>
                          <th><?php echo "$",isset($htotal) ? $htotal: 0.00; ?></th>
                          <th><?php echo isset($num) ? $num: 0.00; ?></th>
                            
                        </tr>
                        <tr> 
                            <td>Hourly Sales</td>
                            <td> Amount</td>
                             
                            <td>Sales Transactions</td>
                         
                        
                        </tr>
                       <?php foreach($report_new_hourly as $v){?>
                       
                       <tr>
                           <td><?php echo isset($v->Hours) ? $v->Hours: ""; ?></td>
                           <td><?php echo "$",isset($v->Amount) ? $v->Amount: 0.00; ?></td>
                           <td><?php echo isset($v->Number) ? $v->Number: 0.00; ?></td>
                           
                       </tr>
                 <?php }?>
                 <?php $QUANTITY=$SALES=$COST=$GP=0 ?>
                  <?php foreach($report_new_dept as $v){
                         if($v->vdepatname === "BOTTLE DEPOSIT"){
                                 continue;
                                 }
                           $QUANTITY=+$QUANTITY+$v->qty;
                           $SALES=+$SALES+$v->saleamount;
                           $COST=+$COST+$v->cost;
                        //   $GP=+$GP+$v->gpp;
                        $totalgpp=(($COST-$SALES)/$SALES)*100;
                           
                           
                      }?>
                      
                      
                        <tr> 
                          <th> DEPARTMENT SALES SUMMARY </th>
                          <th><?php echo isset($QUANTITY) ? $QUANTITY: 0.00; ?></th>
                          <th><?php echo  "$",isset($SALES) ? $SALES: 0.00; ?></th>
                          <th><?php echo "$",isset($COST) ? $COST: 0.00; ?></th>
                          <th><?php echo  isset($totalgpp) ? number_format(abs($totalgpp),2): 0.00 ,"%"; ?></th>
                            
                            
                        </tr>
                        
                         <tr> 
                            <td>Department Name</td>
                            <td> Quantity</td>
                             
                            <td>Sales</td>
                            <td>Cost</td>
                            <td>GP%</td> 
                        
                        </tr>
                       <?php foreach($report_new_dept as $v){
                         if($v->vdepatname === "BOTTLE DEPOSIT"){
                                 continue;
                                 }
                      ?>
                       <tr>
                           <td><?php echo isset($v->vdepatname) ? $v->vdepatname: ""; ?></td>
                           <td><?php echo isset($v->qty) ? $v->qty: 0.00; ?></td>
                           <td><?php echo '$', isset($v->saleamount) ? $v->saleamount: 0.00; ?></td>
                           <td><?php echo'$',  isset($v->cost) ? $v->cost: 0.00; ?></td>
                           <td><?php echo isset($v->gpp) ? number_format($v->gpp*100,2): 0.00,"%"; ?></td>
                       </tr>
                       <?php }?>
                    </table>
</div>                    
</div>              
</div>
<style>
div.a {
  text-transform: uppercase;
}
</style>  
<style type="text/css">
  
  
  .table {            
        page-break-after: always;
        page-break-inside: always;
        /*break-inside: avoid;*/
    }
 
 .tr{border:1pt solid;
    background-color: #ffffff;
 }

.column {
  float: left;
  width: 70%;
  
}
</style>
