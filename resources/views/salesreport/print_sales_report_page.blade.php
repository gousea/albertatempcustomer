<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style type="text/css">
  @page { size: landscape; }
  @page :left {
     margin-left: -1px;
     margin-right: -1px;
  }

  @page :right {
     margin-left: 0px;
     margin-right: 0px;
  }
</style>
<div id="content">
 <div class="container-fluid">
   <div class="" style="margin-top:2%;">
     <div class="text-center">
       <h2 class="panel-title" style="font-weight:bold;font-size:24px;"><?php echo $heading_title; ?></h2>
     </div>
     <div class="panel-body">
       <?php if(isset($reports) && count($reports) > 0){ ?>
       <br><br><br>
       <div class="row">
         <div class="col-md-6 pull-left">
           <p><b>Date Range: </b><?php echo $p_start_date; ?> to <?php echo $p_end_date; ?></p>
         </div>
         <div class="col-md-6 pull-right">
           <p><b>Store Name: </b><?php echo $storename; ?></p>
         </div>
       </div>
       <div class="row">
         <div class="col-md-6 pull-right">
           <p><b>Store Address: </b><?php echo $storeaddress; ?></p>
         </div>
       </div>
       <div class="row">
         <div class="col-md-6 pull-right">
           <p><b>Store Phone: </b><?php echo $storephone; ?></p>
         </div>
         <br>
         <hr>
         
         <?php 
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
                   
                 ?>
                 <?php foreach ($reports as $key => $value){ 
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
                   
                    
                   
                 } ?>
         <div class="col-md-12">
         
           <table class="table table-bordered" style="border:none;font-size:1vw;">
             <thead>
               <tr style="border-top: 1px solid #ddd;">
                 <th style="font-size:6px;"> Date</th>
                 <th style="font-size:6px;">Store Sales (Excluded Tax)</th>
                 <th style="font-size:6px;">Non-Taxable Sales</th>
                 <th style="font-size:6px;">Taxable Sales</th>
                 <?php if($tot_Tax1Sales!=0){ ?>
                     <th style="font-size:6px;">Tax1  Sales</th>
                 <?php } ?>
                 <?php if($tot_Tax2Sales!=0){ ?>
                      <th style="font-size:6px;">Tax2  Sales</th>
                 <?php } ?>
                 
                 <?php if($tot_Tax3Sales!=0){ ?>
                   <th style="font-size:6px;">Tax3  Sales</th>
                 <?php } ?>
                 
                 <th style="font-size:6px;">Sales Tax </th>
                 
                 <?php if($tot_Tax1!=0){ ?>
                   <th style="font-size:6px;">Tax1</th>
                 <?php } ?>
                 
                 <?php if($tot_Tax2!=0){ ?>
                   <th style="font-size:6px;">Tax2</th>
                 <?php } ?>
                 
                 <?php if($tot_Tax3!=0){ ?>
                    <th style="font-size:6px;">Tax3</th>
                 <?php } ?>
                 
                 <th style="font-size:6px;">Total Store Sales:</th>
                 
                 <?php if($tot_TotalFuelSales!=0){ ?>
                    <th style="font-size:6px;">Fuel Sales</th>
                 <?php } ?>
                 
                 <?php if($tot_TotalLottorySales!=0){ ?>
                   <th style="font-size:6px;">Lotto Sales</th>
                 <?php } ?>
                 
                 <?php if($tot_TotalLiabilitySales!=0){ ?>
                    <th style="font-size:6px;">Liablity Sales</th>
                 <?php } ?>
                 
                 <th style="font-size:6px;">Total Sales</th>
                  
                 <?php if($tot_HouseCharged!=0){ ?>
                 <th style="font-size:6px;">House Charged</th>
                 <?php } ?>
                 
                 <?php if($tot_HouseChargePayments!=0){ ?>
                 <th style="font-size:6px;">House Charge Payments</th>
                 <?php  } ?>
                 
                 <?php if($tot_BottleDeposit!=0){ ?>
                   <th style="font-size:6px;">Bottle Deposit</th>
                 <?php } ?>
                 
                 <?php if($tot_BottleDepositRedeem!=0){ ?>
                    <th style="font-size:6px;">Bottle Deposit Redeem</th>
                 <?php } ?>
                 
                 <?php if($tot_TotalPaidout!=0){ ?>
                   <th style="font-size:6px;">Total Paid out</th>
                 <?php } ?>
                 
                 <th style="font-size:6px;">Cash</th>
                 
                 <?php if($tot_CouponTender!=0){ ?>
                 <th style="font-size:6px;">Coupon</th>
                 <?php } ?>
                 
                 <?php if($tot_CheckTender!=0){ ?>
                 <th style="font-size:6px;">Check</th>
                 <?php } ?>
                 
                 <th style="font-size:6px;">Credit Card Total</th>
                 
                 <?php if($tot_EBTCash!=0){ ?>
                 <th style="font-size:6px;">EBT Cash</th>
                 <?php } ?>
                 
                 <?php if($tot_EBT!=0){ ?>
                 <th style="font-size:6px;">EBT</th>
                 <?php } ?>
                 
                     
               </tr>
             </thead>
             <tbody>
                 
                 
                   <tr>
                   <td style="font-size:6px;"><b>Total</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_StoreSalesExclTax, 2, '.', '') ;?></b></td>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_NonTaxableSales, 2, '.', '') ;?></b></td>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_TaxableSales, 2, '.', '') ;?></b></td>
                   <?php if($tot_Tax1Sales!=0){ ?>
                     <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_Tax1Sales, 2, '.', '') ;?></b></td>
                   <?php } ?>
                   <?php if($tot_Tax2Sales!=0){ ?>
                     <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_Tax2Sales, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <?php if($tot_Tax3Sales!=0){ ?>
                      <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_Tax3Sales, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_TotalTax, 2, '.', '') ;?></b></td>
                   
                   <?php if($tot_Tax1!=0){ ?>
                     <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_Tax1, 2, '.', '') ;?></b></td>
                   <?php  } ?> 
                   
                   <?php if($tot_Tax2!=0){ ?>  
                      <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_Tax2, 2, '.', '') ;?></b></td>
                    
                   <?php  } ?>
                   
                   <?php if($tot_Tax3!=0){ ?>
                      <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_Tax3, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_TotalStoreSales, 2, '.', '') ;?></b></td>
                   
                   <?php if($tot_TotalFuelSales!=0){ ?>
                     <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_TotalFuelSales, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <?php if($tot_TotalLottorySales!=0){ ?>  
                     <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_TotalLottorySales, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <?php if($tot_TotalLiabilitySales!=0){ ?>
                     <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_TotalLiabilitySales, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_TotalSales, 2, '.', '') ;?></b></td>
                   
                   <?php if($tot_HouseCharged!=0){ ?>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_HouseCharged, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                  
                   <?php if( $tot_HouseChargePayments!=0){ ?>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_HouseChargePayments, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <?php if($tot_BottleDeposit!=0){ ?>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_BottleDeposit, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <?php if($tot_BottleDepositRedeem!=0){ ?>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_BottleDepositRedeem, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <?php if($tot_TotalPaidout!=0){ ?>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_TotalPaidout, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_CashTender, 2, '.', '') ;?></b></td>
                   
                   <?php if($tot_CouponTender!=0){ ?>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_CouponTender, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <?php if($tot_CheckTender!=0){ ?>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_CheckTender, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_CreditCardTender, 2, '.', '') ;?></b></td>
                   
                   
                   <?php if($tot_EBTCash!=0){ ?>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_EBTCash, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                   <?php if($tot_EBT!=0){ ?>
                   <td style="font-size:6px;"><b><?php echo "$",number_format((float)$tot_EBT, 2, '.', '') ;?></b></td>
                   <?php  } ?>
                   </tr>
                
                 <?php foreach ($reports as $key => $value){ ?>
                   <tr>
                    <td style="font-size:6px;"><?php echo $value['eoddate'];?></td>
                     <td style="font-size:6px;"><?php echo "$", number_format((float)$value['StoreSalesExclTax'], 2, '.', '') ;?></td>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['NonTaxableSales'], 2, '.', '') ;?></td>
                     <td style="font-size:6px;"><?php echo "$", number_format((float)$value['TaxableSales'], 2, '.', '') ;?></td>
                     
                     <?php if($tot_Tax1Sales!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['Tax1Sales'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if($tot_Tax2Sales!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['Tax2Sales'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if($tot_Tax3Sales!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['Tax3Sales'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['TotalTax'], 2, '.', '') ;?></td>
                     
                     <?php if($tot_Tax1!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['Tax1'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if($tot_Tax2!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['Tax2'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if($tot_Tax3!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['Tax3'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['TotalStoreSales'], 2, '.', '') ;?></td>
                     <?php if($tot_TotalFuelSales!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['TotalFuelSales'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if($tot_TotalLottorySales!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['TotalLottorySales'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if($tot_TotalLiabilitySales!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['TotalLiabilitySales'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['TotalSales'], 2, '.', '') ;?></td>
                     
                     <?php if($tot_HouseCharged!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['HouseCharged'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if( $tot_HouseChargePayments!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['HouseChargePayments'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if($tot_BottleDeposit!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['bottledeposit'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if($tot_BottleDepositRedeem!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['bottledepositredeem'], 2, '.', '') ;?></td>
                     <?php  } ?>
                     
                     <?php if($tot_TotalPaidout!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['TotalPaidout'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['CashTender'], 2, '.', '') ;?></td>
                     
                     <?php if($tot_CouponTender!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['CouponTender'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                     <?php if($tot_CheckTender!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['CheckTender'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['CreditCardTender'], 2, '.', '') ;?></td>
                     
                     <?php if($tot_EBTCash!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['EBTCash'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                     <?php if($tot_EBT!=0){ ?>
                     <td style="font-size:6px;"><?php echo "$",number_format((float)$value['EBT'], 2, '.', '') ;?></td>
                      <?php  } ?>

                     
                   </tr>
                 <?php } ?>
               
             </tbody>
           </table>
         </div>
       </div>
       <?php }else{ ?>
         <?php if(isset($p_start_date)){ ?>
           <div class="row">
             <div class="col-md-12"><br><br>
               <div class="alert alert-info text-center">
                 <strong>Sorry no data found!</strong>
               </div>
             </div>
           </div>
         <?php } ?>
       <?php } ?>
     </div>
   </div>
 </div>
</div>