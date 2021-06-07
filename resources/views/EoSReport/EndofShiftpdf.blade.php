<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}"  alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "End of Shift Report" ?></h3>
    <hr style="height:1px; background-color:black;">
   
</div> 
<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {

  padding: 0px;
  border-top: 1px solid DarkGrey;

}
 

#customers th {
  padding-top: 0px;
  padding-bottom: 0px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
<div class="row" >
                  <div class="col-md-12">
                    <p><b> Store Name: </b>{{ session()->get('storeName') }}</p>
                  </div>
                </div>
                  <div class="row" >
                  <div class="col-md-12">
                    <p><b> SID: </b> {{ session()->get('sid') }} </p>
                  </div>
                </div>
                 <div class="row" >
                  <div class="col-md-12">
                    <p><b> Batch :</b><?php echo $p_batch_id;?> </p>
                  </div>
                </div>
                <div class="row" >
                <?php $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data['BatchStartTime']);
                 $startdate=$date->format('m-d-Y H:i:s');  
                 $edate = \DateTime::createFromFormat('Y-m-d H:i:s', $data['BatchEndTime']);
                 $endtdate=$edate->format('m-d-Y H:i:s');  
                 ?>
                    
                          <div class="col-md-12">
                            <p><b>SHIFT START: </b><?php echo $startdate; ?></p>
                          </div>
                     </div>
                  <div class="row">
                      <div class="col-md-12">
                        <p><b>SHIFT END: </b><?php echo $endtdate ?></p>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                        <p><b>Register No: </b><?php echo $data['TerminalId']; ?></p>
                   </div>   
                 </div>
                 <div class="row" >
                    <div class="col-md-4 table-responsive">
                        
                        <h4 style="text-align: center;">SALES TOTALS</h4>
                        <table id="customers" style="border:none;">
                            
                            <tr>
                                <td >SALES(excluding Tax)</td>
                                <td class="text-right"><?php echo"$", number_format($data['SalesExclTax'],2);?></td>
                            </tr>
                            <tr>
                                <td>TAXABLE SALES</td>
                                
                                <td class="text-right"><?php echo"$", $data['TotalTaxable'];?></td>
                                
                            </tr>
                            <tr>
                                <td>NON-TAXABLE</td>
                                <td class="text-right"><?php echo"$", $data['TotalNonTaxable'];?></td>
                            </tr>
                            <tr>
                                <td>TOTAL TAX</td>
                               
                                <td class="text-right"><?php echo"$", $data['TotalTax'];?></td>
                                
                            </tr>
                            <?php if($data['TotalLottery'] !=0){?>
                            <tr>
                                <td>TOTAL LOTTO SALES</td>
                                <td class="text-right"><?php echo "$",$data['TotalLottery'];?></td>
                            </tr>
                            <?php } ?>
                            
                             <?php if($data['liabilitysales'] !=0){?>
                            <tr>
                                <td>LIABILITY SALES</td>
                                <td class="text-right"><?php echo "$",$data['liabilitysales'];?></td>
                            </tr>
                            <?php } ?>
                            
                            <?php if($data['BottleDeposit'] !=0){?>
                            <tr>
                                <td>BOTTLE DEPOSITE</td>
                                <td class="text-right"><?php echo "$",$data['BottleDeposit'];?></td>
                            </tr>
                            <?php } ?>
                            
                            <?php if($data['BottleDepositRedeem'] !=0){?>
                            <tr>
                                <td>BOTTLE DEPOSITE REDEEM</td>
                                <td class="text-right"><?php echo "$",$data['BottleDepositRedeem'];?></td>
                            </tr>
                            <?php } ?>
                            
                            <?php if($data['BottleDepositTax'] !=0){?>
                             <tr>
                                <td>BOTTLE DEPOSITE TAX</td>
                                <td class="text-right"><?php echo "$",$data['BottleDepositTax'];?></td>
                            </tr>
                            <?php } ?>

                            <?php if($data['BottleDepositRedeemTax'] !=0){?>
                             <tr>
                                <td>BOTTLE DEPOSITE REDEEM TAX</td>
                                <td class="text-right"><?php echo "$",$data['BottleDepositRedeemTax'];?></td>
                            </tr>
                            <?php } ?>
                            
                            <?php if($data['HouseAcctPay'] !=0){?>
                             <tr>
                                <td>HOUSE ACCOUNT PAYMENTS </td>
                                <td class="text-right"><?php echo "$",$data['HouseAcctPay'];?></td>
                            </tr>
                            <?php } ?>
                            
                            <tr>
                                <td>GRAND TOTAL</td>
                                <td class="text-right"><?php echo "$",number_format($data['NetSales'],2);?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-4 table-responsive">
                          
                        <h4 style="text-align: center;">LOTTO SALES DETAILS</h4>
                      
                        <table id="customers" style="border:none;">
                            <tbody>
                                    <?php if($data['LotterySales'] !=0){?>
                            <tr>
                                <td>LOTTERY SALES  </td>
                                <td class="text-right"><?php echo "$",$data['LotterySales'];?></td>
                            </tr>
                             <?php } ?>
                              <?php if($data['InstantSales'] !=0){?>
                            <tr>
                                <td>INSTANT SALES </td>
                                <td class="text-right"><?php echo "$",$data['InstantSales'];?></td>
                            </tr>
                             <?php } ?>
                              <?php if($data['LotteryRedeem'] !=0){?>
                            <tr>
                                <td> LOTTERY REDEEM  </td>
                                <td class="text-right"><?php echo "$",$data['LotteryRedeem'];?></td>
                            </tr>
                             <?php } ?>
                              <?php if($data['InstantRedeem'] !=0){?>
                            <tr>
                                <td>INSTANT REDEEM </td>
                                <td class="text-right"><?php echo "$",$data['InstantRedeem'];?></td>
                            </tr>
                             <?php } ?>
                            
                              </tbody>
                        </table>
                   
                    </div>
                </div>
                
     
                  <div class="row" >
                    <div class="col-md-4 table-responsive">
                       
                               <h4 style="text-align:center;">TENDER DETAILS</h4>
                       
                     <table id="customers" style="border:none;">
                        
                          
                            <tbody>
                                 
                            <?php if($data['CashTender'] !=0){?>
                            <tr>
                                <td>CASH </td>
                                <td class="text-right"><?php echo "$",$data['CashTender'];?></td>
                            </tr>
                             <?php } ?>
                                 <?php if($data['Coupon'] !=0){?>
                            <tr>
                                <td>COUPON </td>
                                <td class="text-right"><?php echo "$",$data['Coupon'];?></td>
                            </tr>
                             <?php } ?>
                             
                             
                              <?php if($data['CreditCardTender'] !=0){?>
                            <tr>
                                <td>CREDIT CARD </td>
                                <td class="text-right"><?php echo "$",$data['CreditCardTender'];?></td>
                            </tr>
                             <?php } ?>
                             
                              
                               <?php if($data['CheckTender'] !=0){?>
                            <tr>
                                <td>CHECK </td>
                                <td class="text-right"><?php echo "$",$data['CheckTender'];?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data['EBTTender'] !=0){?>
                            <tr>
                                <td> EBT</td>
                                <td class="text-right"><?php echo "$",$data['EBTTender'];?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data['HouseAcctTender'] !=0){?>
                            <tr>
                                <td>ON ACCT  </td>
                                <td class="text-right"><?php echo "$",$data['HouseAcctTender'];?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data['HouseAcctCash'] !=0){?>
                            <tr>
                                <td> HOUSE ACCT PAYMENT CASH  </td>
                                <td class="text-right"><?php echo "$",$data['HouseAcctCash'];?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data['HouseAcctCard'] !=0){?>
                            <tr>
                                <td>HOUSE ACCT PAYMENT CREDITCARD</td>
                                <td class="text-right"><?php echo "$",$data['HouseAcctCard'];?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data['HouseAcctCheck'] !=0){?>
                            <tr>
                                <td>  HOUSE ACCT PAYMENT CHECK </td>
                                <td class="text-right"><?php echo "$",$data['HouseAcctCheck'];?></td>
                            </tr>
                             <?php } ?>
                           
                              </tbody>
                        </table>
                   
                    </div>
                </div> 

               
                <div class="row" style="margin-top:0%;">
                    <div class="col-md-4">
                         <h4 style="text-align: center;">PERFORMANCE STATISTICS</h3>
                        <table id="customers" style="border:none;">
                         
                            <tbody>
                        <?php if($data['TotalReturns'] !=0){ ?>    
                           <tr>
                            <td>#RETURNED ITEMS</td>
                            <td class="text-right"><?php 
                                if($data['TotalReturns']<0){
                                
                                echo "(","$",abs($data['TotalReturns']),")";
                                }
                                else{
                                echo "$",$data['TotalReturns'];
                                }
                                
                                ?>
                                </td>
                            </tr>
                        <?php } ?>    
                             <tr>
                                <td>#OF TRANSACTION </td>
                                <td class="text-right"><?php echo "$",$data['NoOfTransactions'];?></td>
                            </tr>
                             <tr>
                                <td>#AVG TRANSACTIONS</td>
                                <td class="text-right"><?php echo "$",number_format($data['AvgSaleTrn'],2);?></td>
                            </tr>
                              <?php if(isset($data['Surcharges'] ) && $data['Surcharges'] !=0){ ?>
                            <tr>
                                <td>#Surcharges Collected </td>
                                <td class="text-right"><?php echo $data['Surcharges'];?></td>
                            </tr>
                            <?php } ?>
                            
                             <?php if(isset($data['EbtTaxExempted']) && $data['EbtTaxExempted'] !=0){ ?>
                            <tr>
                                <td>#EBT Tax Exempted </td>
                                <td class="text-right"><?php echo $data['EbtTaxExempted'];?></td>
                            </tr>
                            <?php } ?>
                              </tbody>
                        </table>
                   
                    </div>
                </div> 
               <div class="row">
                    <div class="col-md-4">
                        <h4 style="text-align: center;">CASH COUNT</h4>
                       
                  <table id="customers" style="border:none;">
                            
                               
                            <tbody>
                            <tr>
                                <td>OPENING CASH</td>
                                <td class="text-right"><?php echo "$",$data['OpeningBalance'];?></td>
                            </tr>
                              <tr>
                                <td>+CASH SALES</td>
                                <td class="text-right"><?php echo "$",$data['CashTender'];?></td>
                            </tr>
                             <tr>
                                <td>+CASH ADD</td>
                                <td class="text-right"><?php echo "$",$data['NetAddCash'];?></td>
                            </tr>
                            
                            
                            <?php if($data['HouseAcctPay']!=0){ ?>
                             <tr>
                                <td>+ON ACCOUNT</td>
                                <td class="text-right"><?php echo "$",$data['HouseAcctPay'];?></td>
                            </tr>
                            <?php } ?>
                            
                             <tr>
                                <td>-CASH PAIDOUT</td>
                                <td class="text-right"><?php echo "$",$data['NetPaidout'];?></td>
                            </tr>
                            <tr>
                                <td>-SAFE DROP</td>
                                <td class="text-right"><?php echo "$",$data['NetCashPickup'];?></td>
                            </tr>
                            
                            
                              </tbody>
                        </table>
              
                </div>
                </div>
            
                <div class="row" >
                    <div class="col-md-4">
                       <table id="customers"style="border:none ;">
                          
                            <tbody>
                            <tr>
                                <td> EXPECTED CASH</td>
                                <td class="text-right"><?php echo "$",$excash=$data['ClosingBalance'];?></td>
                            </tr>
                            <tr>
                                <td> ACTUAL CASH </td>
                                <td class="text-right"><?php echo "$",$data['UserClosingBalance'];?></td>
                            </tr>
                            <tr>
                                <td>CASH SHORT</td>
                                <?php $cashshort=$data['CashShortOver'];?>
                                <td class="text-right"><?php if($cashshort<0){
                                 echo "(", "$",abs($cashshort),")";
                                }
                             
                                else {
                                echo "$",$cashshort;
                                
                                }?></td>
                            </tr>
                              </tbody>
                        </table>
             
                </div>
                </div>
                <br>
                <?php if(isset($cashier_data)){ ?>
                <div class="row">
                    <div class="col-md-4">
                        <table id="customers" style="border:none;">
                            
                            <tr>
                                <td>CASHIER DETAILS </td>
                                <td class="text-right">TIMINGS</td>
                               </tr>
                            <tbody>
                              <?php foreach($cashier_data as $v){?>
                                <tr>
                                <td><?php echo $v->iuserid;?> </td>
                                <td class="text-right"><?php echo $v->Timing;?></td>
                               </tr>
                            <?php } ?>    
                            </tbody>
                        </table>
                    </div>
                </div>
                <?} ?>
                <br> 
                <div class="row" >
                     <div class="col-md-12">
                         <h4 style="text-align: center;">DEPARTMENT SALES SUMMARY </h4>
                       <table id="customers"style="border:none ;">
                    
                         <tr> 
                            <td>Department Name</td>
                            <td> Quantity</td>
                             
                            <td>Sales</td>
                            <td>Cost</td>
                            <td>GP%</td> 
                        
                        </tr>
                       <?php foreach($report_new_dept as $v){?>
                      
                       <tr>
                       <td><?php echo isset($v->vdepatname) ? $v->vdepatname: ""; ?></td>
                           <td><?php echo isset($v->qty) ? $v->qty: 0.00; ?></td>
                           <td><?php echo isset($v->saleamount) ? $v->saleamount: 0.00; ?></td>
                           <td><?php echo isset($v->cost) ? $v->cost: 0.00; ?></td>
                           <td><?php echo isset($v->gpp) ? number_format($v->gpp,2): 0.00; ?></td>
                       </tr>
                       <?php }?>
                    </table>
                    
                    </div>
                    </div>



 