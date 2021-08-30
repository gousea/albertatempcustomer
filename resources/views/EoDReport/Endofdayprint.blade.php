<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src=" " widtd="120" height="50"></p>

<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "End Of Day Report" ?></h3>
    <!--<hr style="height:0.2px; background-color:black;">-->

</div> 
<br>
<body style="margin-left:2%;";>
  <div class="row" >
          <div class="col-md-12">
            <div class='col-md-6' style="font-size:20px";>
                <p><b>Store Name:</b> {{ session()->get('storeName') }}</p>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <div class='col-md-6' style="font-size:20px";>
                <p><b>Store Address: </b><?php echo $store[0]->vaddress1; ?></p>
            </div>    
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <div class='col-md-6' style="font-size:20px";>
                <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
            </div>
          </div>
        </div>
            <div class="row">
          <div class="col-md-12">
            <div class='col-md-6' style="font-size:20px";>
                <p><b>Date: </b><?php echo $date ; ?></p>
            </div>
          </div>
        </div>
        
<div class="row">
   <div class='col-md-12'>
    
        <table class="table table-bordered table-striped table-hover" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="font-size:24px";>
           <tr > 
                        <th style=" text-align:center;border-style: none;background-color:white;padding: 0px 10px;">SALES DETAIL</th>
                        <td style=" text-align:center;border-left: none;background-color:white;padding: 0px 10px;"></td>
            </tr>            

                        <tr>
                            <td style="padding: 0px 10px;">Store Sales ( Excluding Tax)</td> 
                            <td style="padding: 0px 10px;" class="text-right" ><?php echo "$",$report_sale_new[0]->StoreSalesExclTax; ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 0px 10px;">Taxable Sales</td>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->TaxableSales; ?></td>
                        </tr>
                         <tr>
                            <td style="padding: 0px 10px;">Non-Taxable Sales</td>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->NonTaxableSales; ?></td>
                        </tr>
                         <tr>
                           
                           <td style="padding: 0px 10px;">Total  Tax &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->TotalTax; ?></td>
                           
                       </tr>
                       <?php if($report_sale_new[0]->Tax1!=0){?> 
                         <tr>
                           <td style="padding: 0px 10px;">Tax1 &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->Tax1; ?></td>
                        </tr>
                        <?php }?>
                        
                        <?php if($report_sale_new[0]->Tax2!=0){?> 
                         <tr>
                           <td style="padding: 0px 10px;">Tax2  &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->Tax2; ?></td>
                        </tr>
                        <?php }?>
                        
                        <?php if($report_sale_new[0]->Tax3!=0){?> 
                         <tr>
                           <td style="padding: 0px 10px;">Tax3&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->Tax3; ?></td>
                        </tr>
                       <?php }?>
                       
                            </tr>
                         <tr>
                            <td style="padding: 0px 10px;">Total Store Sales</td>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->StoreSalesInclTax; ?></td>
                        </tr>
                       <?php if($report_sale_new[0]->LottoSales!=0){?> 
                        <tr>
                            <td style="padding: 0px 10px;">Lotto Sales</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->LottoSales; ?></td>
                        </tr>
                         <?php }?>
                         
                        <?php if($report_sale_new[0]->LiabilitySales!=0){?>
                        <tr>
                            <td style="padding: 0px 10px;">Liablity Sales</td>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->LiabilitySales; ?></td>
                        </tr>
                        <?php }?>
                     
                         
                         <?php if($report_sale_new[0]->TotalBottleDeposit!=0){ ?> 
                        <tr>
                           
                            <td style="padding: 0px 10px;"> Total Bottle Deposit</td>
                             <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->TotalBottleDeposit; ?></td>
                            
                        </tr>
                        <?php } ?> 
                        
                        <?php if($report_sale_new[0]->HouseAcctPayments!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">House Account Payments   </td>
                             <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->HouseAcctPayments; ?></td>
                            
                        </tr>
                        <?php } ?> 
                        
                     
                     <?php if($report_sale_new[0]->FuelSales!=0){?>
                        <tr>
                            <td style="padding: 0px 10px;">Fuel Sales</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->FuelSales; ?></td>
                        </tr>
                        <?php } ?> 
                        
                        <tr>
                            <td style="padding: 0px 10px;"><b>Grand Total </b></td>
                            <td style="padding: 0px 10px;" class="text-right"><b><?php echo "$",$report_sale_new[0]->GrandTotal; ?><b></td>
                        </tr>
                     
                        <!--<td style="padding: 0px 10px;">&nbsp;</td>-->
                        <!--<td style="padding: 0px 10px;">&nbsp;</td>-->
                    </tr>
                    <tr > 
                        <th style=" text-align:center;border-style: none;background-color:white;padding: 0px 10px;"> LOTTERY SALES DETAILS </th>
                        <td style=" text-align:center;border-left: none;background-color:white;padding: 0px 10px;"></td>
                    </tr>
                    
                    
                    @if($report_sale_new[0]->LotterySales!=0)
                     
                            <tr>
                                <td style="padding: 0px 10px;">Lottery Sales</td>
                                <td style="padding: 0px 10px;" class="text-right">{{'$'}}{{$report_sale_new[0]->LotterySales}}</td>
                            </tr>
                     @endif    
                        
                    @if($report_sale_new[0]->instantSales!=0)
                            <tr >
                                <td style="padding: 0px 10px;">Instant Sales</td>
                                <td style="padding: 0px 10px;" class="text-right">{{'$'}}{{$report_sale_new[0]->instantSales}}</td>
                            </tr>
                     @endif   
                         
                        @if($report_sale_new[0]->LotteryRedeem!=0)
                            <tr >
                                <td style="padding: 0px 10px;">Lottery Redeem </td>
                               <td style="padding: 0px 10px;" class="text-right">{{'$'}}{{$report_sale_new[0]->LotteryRedeem}}</td>
                            </tr>
                        @endif  
                        
                        
                        @if($report_sale_new[0]->InstantRedeem!=0)
                             <tr >
                                <td style="padding: 0px 10px;">Instant Redeem</td>
                                <td style="padding: 0px 10px;" class="text-right">{{'$'}}{{$report_sale_new[0]->InstantRedeem}}</td>
                                
                             </tr>
                        @endif 
                    
                    <!--<tr>-->
                    <!--    <td style="padding: 0px 10px;">&nbsp;</td>-->
                    <!--    <td style="padding: 0px 10px;">&nbsp;</td>-->
                    <!--</tr>-->
                    
                       <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;padding: 0px 10px;"> BOTTLE DEPOSIT </th>
                            <td  style=" text-align:center;border-left: none;background-color:white;padding: 0px 10px;"></td>
                        </tr>
                        
                        <?php if($report_sale_new[0]->bottledeposit!=0){ ?> 
                        <tr >
                            <td style="padding: 0px 10px;">Bottle Deposit</td>
                             <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->bottledeposit; ?></td>
                            
                        </tr>
                        <?php } ?> 
                         
                        <?php if($report_sale_new[0]->bottledepositredeem!=0){ ?> 
                        <tr >
                            <td style="padding: 0px 10px;">Bottle Deposit Redeem</td>
                             <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->bottledepositredeem; ?></td>
                        </tr>  
                        <?php } ?> 
                        
                        <?php if($report_sale_new[0]->bottledeposittax!=0){ ?> 
                        <tr >
                            <td style="padding: 0px 10px;">Bottle Deposit Tax</td>
                             <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->bottledeposittax; ?></td>
                        </tr>  
                        <?php } ?> 
                        
                         <?php if($report_sale_new[0]->bottledepositredeemtax!=0){ ?> 
                        <tr >
                            <td style="padding: 0px 10px;">Bottle Deposit Redeem Tax</td>
                             <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->bottledepositredeemtax; ?></td>
                        </tr>  
                        <?php } ?> 
                        
                        <!--<tr>-->
                        <!--    <td style="padding: 0px 10px;">&nbsp;</td>-->
                        <!--    <td style="padding: 0px 10px;">&nbsp;</td>-->
                        <!--</tr>-->
                        <tr > 
                            <th  style=" text-align:center;border-style: none;background-color:white;padding: 0px 10px;"> TENDER DETAILS </th>
                            <td style="padding: 0px 10px;" style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        
                            <?php if($report_sale_new[0]->CashTender !=0){?>
                            <tr>
                                <td style="padding: 0px 10px;">Cash </td>
                                <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->CashTender;?></td>
                            </tr>
                            <?php } ?>
                            
                             <?php if($report_sale_new[0]->CouponTender !=0){?>
                            <tr>
                                <td style="padding: 0px 10px;">Coupon </td>
                                <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->CouponTender;?></td>
                            </tr>
                            <?php } ?>
                               <?php if($report_sale_new[0]->CreditCardTender !=0){?>
                            <tr>
                                <td style="padding: 0px 10px;">Credt Card </td>
                                <td style="padding: 0px 10px;"class="text-right"><?php echo "$",$report_sale_new[0]->CreditCardTender;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($report_sale_new[0]->CheckTender !=0){?>
                            <tr>
                                <td style="padding: 0px 10px;">Check</td>
                                <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->CheckTender;?></td>
                            </tr>
                             <?php } ?>
                             
                            <?php if($report_sale_new[0]->EbtTender !=0){?>
                            <tr>
                                <td style="padding: 0px 10px;">EBT </td>
                                <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->EbtTender;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($report_sale_new[0]->HouseAcctCharged !=0){?>
                            <tr>
                                <td style="padding: 0px 10px;"> House Account Charged</td>
                                <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->HouseAcctCharged;?></td>
                            </tr>
                             <?php } ?>
                              
                               <?php if($report_sale_new[0]->HouseAcctCash !=0){?>
                            <tr>
                                <td style="padding: 0px 10px;"> House Acct Payment Cash  </td>
                                <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->HouseAcctCash;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($report_sale_new[0]->HouseAcctCard !=0){?>
                            <tr>
                                <td style="padding: 0px 10px;">House Acct Payment Creditcard</td>
                                <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->HouseAcctCard;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($report_sale_new[0]->HouseAcctCheck !=0){?>
                            <tr>
                                <td style="padding: 0px 10px;">  House Acct Payment Check </td>
                                <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->HouseAcctCheck;?></td>
                            </tr>
                             <?php } ?>
                             
                        <!--<tr>-->
                        <!--    <td style="padding: 0px 10px;">&nbsp;</td>-->
                        <!--    <td style="padding: 0px 10px;"><?php echo ""; ?></td>-->
                        <!--</tr>-->
                       <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;padding: 0px 10px;"> PERFORMANCE STATSTIC</th>
                            <td style="padding: 0px 10px;" style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                         
                         <?php if($report_sale_new[0]->Paidouts!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">Total Paidout</td>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->Paidouts; ?></td>
                            
                        </tr>
                        
                        <?php }?>
                          <?php if($report_sale_new[0]->Discounted_Amount!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">Discounted Amount </td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->Discounted_Amount; ?></td>
                            
                        </tr>
                        <?php }?>
                        
                        <?php if($report_sale_new[0]->Discounted_Trns!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">Discounted Transactions</td>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->Discounted_Trns; ?></td>
                            
                        </tr>
                        <?php }?>
                        
                      
                        
                        <?php if($report_sale_new[0]->Void_Trns!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">Voided Transactions</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->Void_Trns; ?></td>
                        </tr>
                        <?php }?>
                         
                        <?php if($report_sale_new[0]->Void_Amount!=0){ ?>  
                        <tr>
                            <td style="padding: 0px 10px;">Voided Amount</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->Void_Amount; ?></td>
                        </tr>
                        <?php }?>
                        
                        <?php if($report_sale_new[0]->Deleted_Trns!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">Deleted Transactions</td>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->Deleted_Trns; ?></td>
                            
                        </tr>
                        <?php } ?>
                        
                        <?php if($report_sale_new[0]->Deleted_Amount!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">Deleted Total  </td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->Deleted_Amount; ?></td>
                            
                        </tr>
                        <?php }?>
                        
                        <tr>
                            <td style="padding: 0px 10px;">Return Transactions</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->Return_Trns; ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 0px 10px;">Return Amount </td>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->Return_Amount; ?></td>
                            
                        </tr>
                        
                        
                        <?php if($report_sale_new[0]->NoSale_Count!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">No Sale Transactions</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->NoSale_Count; ?></td>
                        </tr>
                        <?php }?>
                        <!--<tr>-->
                        <!--    <td style="padding: 0px 10px;">&nbsp;</td>-->
                        <!--    <td style="padding: 0px 10px;"><?php echo ""; ?></td>-->
                        <!--</tr>-->
                         <?php if(isset($report_sale_new[0]->Surcharges) && $report_sale_new[0]->Surcharges!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">Surcharges Collected</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->Surcharges; ?></td>
                        </tr>
                        <?php }?>
                        <?php if(isset($report_sale_new[0]->EbtTaxExempted) && $report_sale_new[0]->EbtTaxExempted!=0){ ?> 
                        <tr>
                            <td style="padding: 0px 10px;">EBT Tax Exempted</td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->EbtTaxExempted; ?></td>
                        </tr>
                        <?php }?>
                         
                         
                        <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;padding: 0px 10px;">PRODUCTIVITY </th>
                            <td style="padding: 0px 10px;" style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        
                        <tr>
                            <td style="padding: 0px 10px;">Transaction Count</td>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->Trns_Count; ?></td>
                            
                        </tr>
                        
                        <tr>
                            <td style="padding: 0px 10px;">Average Sales: </td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->Avg_Sales; ?></td>
                            
                        </tr>
                        
                        <tr>
                            <td style="padding: 0px 10px;">Gross Cost: </td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$", $report_sale_new[0]->Gross_Cost; ?></td>
                        </tr>
                         
                        <tr>
                            <td style="padding: 0px 10px;">Gross Profit: </td>
                           <td style="padding: 0px 10px;" class="text-right"><?php echo "$",$report_sale_new[0]->Gross_Profit; ?></td>
                        </tr>
                         
                        <tr>
                            <td style="padding: 0px 10px;">Gross Profit(%): </th>
                            <td style="padding: 0px 10px;" class="text-right"><?php echo $report_sale_new[0]->Gross_Profit_Per; ?></td>
                            
                        </tr>
                        
                     
        
                
            </tbody>
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

@page { size : portrait }
</style>
