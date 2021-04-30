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
<body style="margin-left:2%";>
 
<div class="row">
   <div class='col-md-12'>
    
        <table class="table table-bordered table-striped table-hover" bgcolor="#ffffff" >
           <tr > 
                        <th style=" text-align:center;border-style: none;background-color:white;">SALES DETAIL</th>
                        <td style=" text-align:center;border-left: none;background-color:white;"></td>
            </tr>            

                        <tr>
                            <th>Store Sales ( Excluding Tax)</th> 
                            <td class="text-right"><?php echo "$",$report_sale_new[0]->StoreSalesExclTax; ?></td>
                        </tr>
                        <tr>
                            <th>Taxable Sales</th>
                            <td class="text-right"><?php echo "$",$report_sale_new[0]->TaxableSales; ?></td>
                        </tr>
                         <tr>
                            <th>Non-Taxable Sales</th>
                            <td class="text-right"><?php echo "$",$report_sale_new[0]->NonTaxableSales; ?></td>
                        </tr>
                         <tr>
                           
                           <th>Total  Tax &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</th>
                           <td class="text-right"><?php echo "$",$report_sale_new[0]->TotalTax; ?></td>
                           
                       </tr>
                       
                            </tr>
                         <tr>
                            <th><b>Total Store Sales</b></th>
                            <td class="text-right"><?php echo "$",$report_sale_new[0]->StoreSalesInclTax; ?></td>
                        </tr>
                       <?php if($report_sale_new[0]->LottoSales!=0){?> 
                        <tr>
                            <th>Lotto Sales</th>
                           <td class="text-right"><?php echo "$",$report_sale_new[0]->LottoSales; ?></td>
                        </tr>
                         <?php }?>
                         
                        <?php if($report_sale_new[0]->LiabilitySales!=0){?>
                        <tr>
                            <th>Liablity Sales</th>
                            <td class="text-right"><?php echo "$",$report_sale_new[0]->LiabilitySales; ?></td>
                        </tr>
                        <?php }?>
                     
                         
                         <?php if($report_sale_new[0]->TotalBottleDeposit!=0){ ?> 
                        <tr>
                           
                            <th> Total Bottle Deposit</th>
                             <td class="text-right"><?php echo "$",$report_sale_new[0]->TotalBottleDeposit; ?></td>
                            
                        </tr>
                        <?php } ?> 
                        
                        <?php if($report_sale_new[0]->HouseAcctPayments!=0){ ?> 
                        <tr>
                            <th>HOUSE ACCOUNT PAYMENTS    </th>
                             <td class="text-right"><?php echo "$",$report_sale_new[0]->HouseAcctPayments; ?></td>
                            
                        </tr>
                        <?php } ?> 
                        
                     
                     <?php if($report_sale_new[0]->FuelSales!=0){?>
                        <tr>
                            <th>Fuel Sales</th>
                           <td class="text-right"><?php echo "$",$report_sale_new[0]->FuelSales; ?></td>
                        </tr>
                        <?php } ?> 
                        
                        <tr>
                            <th>Grand Total</th>
                            <td class="text-right"><?php echo "$",$report_sale_new[0]->GrandTotal; ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr > 
                        <td style=" text-align:center;border-style: none;background-color:white;"> LOTTERY SALES DETAILS </td>
                        <td style=" text-align:center;border-left: none;background-color:white;"></td>
                    </tr>
                    
                    
                    @if($report_sale_new[0]->LotterySales!=0)
                     
                            <tr>
                                <td>Lottery Sales</td>
                                <td class="text-right">{{'$'}}{{$report_sale_new[0]->LotterySales}}</td>
                            </tr>
                     @endif    
                        
                    @if($report_sale_new[0]->instantSales!=0)
                            <tr >
                                <td>Instant Sales</td>
                                <td class="text-right">{{'$'}}{{$report_sale_new[0]->instantSales}}</td>
                            </tr>
                     @endif   
                         
                        @if($report_sale_new[0]->LotteryRedeem!=0)
                            <tr >
                                <td>Lottery Redeem </td>
                               <td class="text-right">{{'$'}}{{$report_sale_new[0]->LotteryRedeem}}</td>
                            </tr>
                        @endif  
                        
                        
                        @if($report_sale_new[0]->InstantRedeem!=0)
                             <tr >
                                <td>Instant Redeem</td>
                                <td class="text-right">{{'$'}}{{$report_sale_new[0]->InstantRedeem}}</td>
                                
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
                        
                        <?php if($report_sale_new[0]->bottledeposit!=0){ ?> 
                        <tr >
                            <th>Bottle Deposit</th>
                             <td class="text-right"><?php echo "$",$report_sale_new[0]->bottledeposit; ?></td>
                            
                        </tr>
                        <?php } ?> 
                         
                        <?php if($report_sale_new[0]->bottledepositredeem!=0){ ?> 
                        <tr >
                            <th>Bottle Deposit Redeem</th>
                             <td class="text-right"><?php echo "$",$report_sale_new[0]->bottledepositredeem; ?></td>
                        </tr>  
                        <?php } ?> 
                        
                        <?php if($report_sale_new[0]->bottledeposittax!=0){ ?> 
                        <tr >
                            <th>Bottle Deposit Tax</th>
                             <td class="text-right"><?php echo "$",$report_sale_new[0]->bottledeposittax; ?></td>
                        </tr>  
                        <?php } ?> 
                        
                         <?php if($report_sale_new[0]->bottledepositredeemtax!=0){ ?> 
                        <tr >
                            <th>Bottle Deposit Redeem Tax</th>
                             <td class="text-right"><?php echo "$",$report_sale_new[0]->bottledepositredeemtax; ?></td>
                        </tr>  
                        <?php } ?> 
                        
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;"> TENDER DETAILS </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        
                            <?php if($report_sale_new[0]->CashTender !=0){?>
                            <tr>
                                <th>CASH </th>
                                <td class="text-right"><?php echo "$",$report_sale_new[0]->CashTender;?></td>
                            </tr>
                            <?php } ?>
                            
                             <?php if($report_sale_new[0]->CouponTender !=0){?>
                            <tr>
                                <th>Coupon </th>
                                <td class="text-right"><?php echo "$",$report_sale_new[0]->CouponTender;?></td>
                            </tr>
                            <?php } ?>
                               <?php if($report_sale_new[0]->CreditCardTender !=0){?>
                            <tr>
                                <th>CREDIT CARD </th>
                                <td class="text-right"><?php echo "$",$report_sale_new[0]->CreditCardTender;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($report_sale_new[0]->CheckTender !=0){?>
                            <tr>
                                <th>CHECK </th>
                                <td class="text-right"><?php echo "$",$report_sale_new[0]->CheckTender;?></td>
                            </tr>
                             <?php } ?>
                             
                            <?php if($report_sale_new[0]->EbtTender !=0){?>
                            <tr>
                                <th>EBT </th>
                                <td class="text-right"><?php echo "$",$report_sale_new[0]->EbtTender;?></td>
                            </tr>
                             <?php } ?>
                              
                              
                               <?php if($report_sale_new[0]->HouseAcctCash !=0){?>
                            <tr>
                                <th> HOUSE ACCT PAYMENT CASH  </th>
                                <td class="text-right"><?php echo "$",$report_sale_new[0]->HouseAcctCash;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($report_sale_new[0]->HouseAcctCard !=0){?>
                            <tr>
                                <th>HOUSE ACCT PAYMENT CREDITCARD</th>
                                <td class="text-right"><?php echo "$",$report_sale_new[0]->HouseAcctCard;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($report_sale_new[0]->HouseAcctCheck !=0){?>
                            <tr>
                                <th>  HOUSE ACCT PAYMENT CHECK </th>
                                <td class="text-right"><?php echo "$",$report_sale_new[0]->HouseAcctCheck;?></td>
                            </tr>
                             <?php } ?>
                             
                        <tr>
                            <th>&nbsp;</th>
                            <td><?php echo ""; ?></td>
                        </tr>
                       <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;"> Performance Statstic </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                         
                         <?php if($report_sale_new[0]->Paidouts!=0){ ?> 
                        <tr>
                            <th>Total Paidout</th>
                            <td class="text-right"><?php echo $report_sale_new[0]->Paidouts; ?></td>
                            
                        </tr>
                        
                        <?php }?>
                          <?php if($report_sale_new[0]->Discounted_Amount!=0){ ?> 
                        <tr>
                            <th>Discounted Amount </th>
                           <td class="text-right"><?php echo "$",$report_sale_new[0]->Discounted_Amount; ?></td>
                            
                        </tr>
                        <?php }?>
                        
                        <?php if($report_sale_new[0]->Discounted_Trns!=0){ ?> 
                        <tr>
                            <th>Discounted Transactions</th>
                            <td class="text-right"><?php echo $report_sale_new[0]->Discounted_Trns; ?></td>
                            
                        </tr>
                        <?php }?>
                        
                      
                        
                        <?php if($report_sale_new[0]->Void_Trns!=0){ ?> 
                        <tr>
                            <th>Voided Transactions</th>
                           <td class="text-right"><?php echo $report_sale_new[0]->Void_Trns; ?></td>
                        </tr>
                        <?php }?>
                         
                        <?php if($report_sale_new[0]->Void_Amount!=0){ ?>  
                        <tr>
                            <th>Voided Amount</th>
                           <td class="text-right"><?php echo $report_sale_new[0]->Void_Amount; ?></td>
                        </tr>
                        <?php }?>
                        
                        <?php if($report_sale_new[0]->Deleted_Trns!=0){ ?> 
                        <tr>
                            <th>Deleted Transactions</th>
                            <td class="text-right"><?php echo $report_sale_new[0]->Deleted_Trns; ?></td>
                            
                        </tr>
                        <?php } ?>
                        
                        <?php if($report_sale_new[0]->Deleted_Amount!=0){ ?> 
                        <tr>
                            <th>Deleted Total  </th>
                           <td class="text-right"><?php echo "$",$report_sale_new[0]->Deleted_Amount; ?></td>
                            
                        </tr>
                        <?php }?>
                        
                        <tr>
                            <th>Return Transactions</th>
                           <td class="text-right"><?php echo $report_sale_new[0]->Return_Trns; ?></td>
                        </tr>
                        <tr>
                            <th>Return Amount </th>
                            <td class="text-right"><?php echo "$",$report_sale_new[0]->Return_Amount; ?></td>
                            
                        </tr>
                        
                        
                        <?php if($report_sale_new[0]->NoSale_Count!=0){ ?> 
                        <tr>
                            <th>No Sale Transactions</th>
                           <td class="text-right"><?php echo $report_sale_new[0]->NoSale_Count; ?></td>
                        </tr>
                        <?php }?>
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
                            <td class="text-right"><?php echo $report_sale_new[0]->Trns_Count; ?></td>
                            
                        </tr>
                        
                        <tr>
                            <th>Average Sales: </th>
                           <td class="text-right"><?php echo "$",$report_sale_new[0]->Avg_Sales; ?></td>
                            
                        </tr>
                        
                        <tr>
                            <th>Gross Cost: </th>
                           <td class="text-right"><?php echo "$", $report_sale_new[0]->Gross_Cost; ?></td>
                        </tr>
                         
                        <tr>
                            <th>Gross Profit: </th>
                           <td class="text-right"><?php echo "$",$report_sale_new[0]->Gross_Profit; ?></td>
                        </tr>
                         
                        <tr>
                            <th>Gross Profit(%): </th>
                            <td class="text-right"><?php echo $report_sale_new[0]->Gross_Profit_Per; ?></td>
                            
                        </tr>
                        
                     
        
                
            </tbody>
    </table>        
    </div>
</div>

<div class="row">
    <div class='col-md-12'>
    </div>
</div>
<div class="row">
    <div class='col-md-12'>
       

                    <table class="table table-bordered table-striped table-hover">
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
                       
                       <?php $htotal=0; ?>
                        <?php foreach($report_new_hourly as $v){
                            
                           
                                $htotal=$htotal+$v->Amount;
                             
                            
                             }
                        ?>
                        
                        <tr> 
                          <th>Hourly Sales</th>
                          <th><?php echo "$",isset($htotal) ? $htotal: 0.00; ?></th>
                            
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
                 
                        <tr> 
                          <td> DEPARTMENT SALES SUMMARY </td>
                          <td></td>
                            
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
                           <td><?php echo '$', isset($v->cost) ?  $v->cost: 0.00; ?></td>
                           <td><?php echo isset($v->gpp) ? number_format($v->gpp,2): 0.00; ?></td>
                       </tr>
                       <?php }?>
                    </table>
</div>                    
</div>              

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