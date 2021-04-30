@extends('layouts.master')

@section('title', 'End Of Day Report')
@section('main-content')

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
          
          <!-- <h1><?php //echo $heading_title; ?></h1> -->
          <ul class="breadcrumb">
            <?php //foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php //echo $breadcrumb['href']; ?>"><?php //echo $breadcrumb['text']; ?></a></li>
            <?php //} ?>
          </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> End Of Day Report</h3>
        </div>
    
        <div class="row" style="padding-bottom: 10px;float: right;">
          <div class="col-md-12">
           
            <a id="pdf_export_btn" href="" class="" style="margin-right:10px;">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
            </a>
            <a  id="btnPrint" href="" class="" style="margin-right:10px;">
                <i class="fa fa-print" aria-hidden="true"></i> Print
            </a>
            <a id="csv_export_btn" href="" class="" style="margin-right:10px;">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV
            </a>
            

           
          </div>
    </div>
        <form method="post" action="{{ route('EodForm') }}" id="filter_form">
          @csrf
        <div class="row">
            <div class='col-md-12'>
                
                <div class="col-md-2">
                    <input type="text" class="date form-control"  name="start_date" value="{{ $date ?? '' }}" id="start_date" placeholder="Date" autocomplete="off">
                </div>
                <!-- {{ session()->get('storeName') }}  -->
                <div class="col-md-2">
                    <input type="submit" class="btn btn-success" value="Generate">
                </div> 
                
            </div>
        </div> 
           
    </form>
        <br>
        <div class="row">
              <div class="col-md-12">
                <div class='col-md-6'>
                    <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
                </div>
              </div>
        </div>
        <div class="row">
              <div class="col-md-12">
                <div class='col-md-6'>
                    <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
                </div>    
              </div>
        </div>
        <div class="row">
              <div class="col-md-12">
                <div class='col-md-6'>
                    <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
                </div>
              </div>
        </div>
        <div class="row">
              <div class="col-md-12">
                <div class='col-md-6'>
                    <p><b>Date: </b>{{$date}}</p>
                </div>
              </div>
        </div>
        <div class="row">
              <div class="table-responsive">
                <div class="col-md-12">
                    <div class="col-md-6">
                    <table class="table table-bordered table-striped table-hover">
                            <th style=" text-align:center;border-style: none;background-color:white;">SALES DETAIL</th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                            <tr>
                            <td>Store Sales ( Excluding Tax)</td> 
                            <td class="text-right"><?php echo "$",$data[0]->StoreSalesExclTax; ?></td>
                        </tr>
                            <tr>
                            <td>Taxable Sales</td>
                            <td class="text-right">{{'$'}}{{ $data[0]->TaxableSales}}</td>
                        </tr>
                            <tr>
                            <td>Non-Taxable Sales</td>
                            <td class="text-right">{{'$'}}{{$data[0]->NonTaxableSales}}</td>
                        </tr>
                            <tr>
                           
                           <td>Total  Tax &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                           <td class="text-right">{{'$'}}{{$data[0]->TotalTax}}</td>
                           
                       </tr>
                       
                        @if($data[0]->Tax1!=0)
                              <tr>
                               <td>Tax1&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                               <td class="text-right">{{'$'}}{{$data[0]->Tax1}}</td>
                              </tr>
                          @endif
                          
                          @if($data[0]->Tax2!=0)
                               <tr>
                               <td>Tax2&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                               <td class="text-right">{{'$'}}{{$data[0]->Tax2}}</td>
                               </tr>
                           @endif
                           
                           @if($data[0]->Tax3!=0)
                           <tr>
                           <td>Tax2&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                           <td class="text-right">{{'$'}}{{$data[0]->Tax3}}</td>
                           </tr>
                           @endif
                           
                            <tr>
                            <td>Total Store Sales</td>
                            <td class="text-right">{{'$'}}{{$data[0]->StoreSalesInclTax}}</td>
                        </tr>
                        @if($data[0]->LottoSales!=0)
                            <tr>
                            <td>Lotto Sales</td>
                           <td class="text-right">{{'$'}}{{$data[0]->LottoSales}}</td>
                        </tr>
                        @endif
                        @if($data[0]->LiabilitySales!=0)
                            <tr>
                            <td>Liablity Sales</td>
                            <td class="text-right">{{'$'}}{{$data[0]->LiabilitySales}}</td>
                        </tr>
                        @endif
                        @if($data[0]->TotalBottleDeposit!=0)
                            <tr>
                           
                            <td> Total Bottle Deposit</td>
                             <td class="text-right">{{'$'}}{{$data[0]->TotalBottleDeposit}}</td>
                            
                        </tr>
                        @endif 
                        @if($data[0]->HouseAcctPayments!=0)
                            <tr>
                            <td>HOUSE ACCOUNT PAYMENTS    </td>
                             <td class="text-right">{{'$'}}{{$data[0]->HouseAcctPayments}}</td>
                            
                        </tr>
                        @endif 
                        @if($data[0]->FuelSales!=0)
                            <tr>
                            <td>Fuel Sales</td>
                           <td class="text-right">{{'$'}}{{$data[0]->FuelSales}}</td>
                        </tr>
                        @endif 
                            <tr>
                                <td>Grand Total</td>
                                <td class="text-right">{{'$'}}{{$data[0]->GrandTotal}}</td>
                        </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                        </tr>
                        @if($data[0]->LotterySales!=0 || $data[0]->instantSales!=0 || $data[0]->LotteryRedeem!=0 || $data[0]->InstantRedeem!=0)
                            <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;"> LOTTERY SALES DETAILS </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        @if($data[0]->LotterySales!=0)
                            <tr>
                                    <td>Lottery Sales</td>
                                    <td class="text-right">{{'$'}}{{$data[0]->LotterySales}}</td>
                                </tr>
                        @endif   
                        @if($data[0]->instantSales!=0)
                            <tr >
                                    <td>Instant Sales</td>
                                    <td class="text-right">{{'$'}}{{$data[0]->instantSales}}</td>
                                </tr>
                        @endif   
                        @if($data[0]->LotteryRedeem!=0)
                            <tr >
                                    <td>Lottery Redeem </td>
                                   <td class="text-right">{{'$'}}{{$data[0]->LotteryRedeem}}</td>
                                </tr>
                        @endif  
                        @if($data[0]->InstantRedeem!=0)
                            <tr >
                                    <td>Instant Redeem</td>
                                    <td class="text-right">{{'$'}}{{$data[0]->InstantRedeem}}</td>
                                    
                                 </tr>
                        @endif 
                        @endif 
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @if($data[0]->bottledeposit!=0 || $data[0]->bottledepositredeem!=0 || $data[0]->bottledeposittax!=0||  $data[0]->bottledepositredeemtax!=0)
                            <tr > 
                                <th style=" text-align:center;border-style: none;background-color:white;"> BOTTLE DEPOSIT </th>
                                <td style=" text-align:center;border-left: none;background-color:white;"></td>
                            </tr>
                            
                        @if($data[0]->bottledeposit!=0)
                            <tr >
                            <td>Bottle Deposit</td>
                             <td class="text-right">{{'$'}}{{$data[0]->bottledeposit}}</td>
                            
                        </tr>
                        @endif 
                        @if($data[0]->bottledepositredeem!=0)
                            <tr >
                            <td>Bottle Deposit Redeem</td>
                             <td class="text-right">{{'$'}}{{$data[0]->bottledepositredeem}}</td>
                        </tr>  
                        @endif 
                        @if($data[0]->bottledeposittax!=0)
                            <tr>
                            <td>Bottle Deposit Tax</td>
                             <td class="text-right">{{'$'}}{{$data[0]->bottledeposittax}}</td>
                        </tr>  
                        @endif 
                         @if($data[0]->bottledepositredeemtax!=0)
                            <tr >
                            <td>Bottle Deposit Redeem Tax</td>
                             <td class="text-right">{{'$'}}{{$data[0]->bottledepositredeemtax}}</td>
                        </tr>  
                        @endif 
                          <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                     @endif 
                          
                          
                    @if($data[0]->CashTender !=0 || $data[0]->CouponTender !=0 || $data[0]->CreditCardTender !=0 || $data[0]->CheckTender !=0 || $data[0]->EbtTender !=0 || $data[0]->HouseAcctCharged !=0 || $data[0]->HouseAcctCash !=0 || $data[0]->HouseAcctCard !=0 || $data[0]->HouseAcctCheck !=0)  
                            <tr > 
                                <th style=" text-align:center;border-style: none;background-color:white;"> TENDER DETAILS </th>
                                <td style=" text-align:center;border-left: none;background-color:white;"></td>
                            </tr>
                        
                        @if($data[0]->CashTender !=0)
                            <tr>
                                <td>CASH </td>
                                <td class="text-right">{{'$'}}{{$data[0]->CashTender}}</td>
                            </tr>
                        @endif
                            
                        @if($data[0]->CouponTender !=0)
                            <tr>
                                <td>COUPON</td>
                                <td class="text-right">{{'$'}}{{$data[0]->CouponTender}}</td>
                            </tr>
                        @endif
                        @if($data[0]->CreditCardTender !=0)
                            <tr>
                                <td>CREDIT CARD </td>
                                <td class="text-right">{{'$'}}{{$data[0]->CreditCardTender}}</td>
                            </tr>
                        @endif
                        @if($data[0]->CheckTender !=0)
                            <tr>
                                <td>CHECK </td>
                                <td class="text-right">{{'$'}}{{$data[0]->CheckTender}}</td>
                            </tr>
                        @endif
                        @if($data[0]->EbtTender !=0)
                            <tr>
                                <td>EBT </td>
                                <td class="text-right">{{'$'}}{{$data[0]->EbtTender}}</td>
                            </tr>
                        @endif
                        @if($data[0]->HouseAcctCharged !=0)
                            <tr>
                                <td> HOUSE ACCTOUNT CHARGED </td>
                                <td class="text-right">{{'$'}}{{$data[0]->HouseAcctCharged}}</td>
                            </tr>
                        @endif
                        @if($data[0]->HouseAcctCash !=0)
                            <tr>
                                <td> HOUSE ACCT PAYMENT CASH  </td>
                                <td class="text-right">{{'$'}}{{$data[0]->HouseAcctCash}}</td>
                            </tr>
                        @endif
                        @if($data[0]->HouseAcctCard !=0)
                            <tr>
                                <td>HOUSE ACCT PAYMENT CREDITCARD</td>
                                <td class="text-right">{{'$'}}{{$data[0]->HouseAcctCard}}</td>
                            </tr>
                        @endif
                        @if($data[0]->HouseAcctCheck !=0)
                            <tr>
                                <td>  HOUSE ACCT PAYMENT CHECK </td>
                                <td class="text-right">{{'$'}}{{$data[0]->HouseAcctCheck}}</td>
                            </tr>
                        @endif
                            <tr>
                            <td>&nbsp;</td>
                            <td><?php echo ""; ?></td>
                            
                       @endif        
                        </tr>
                            <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;"> Performance Statstic </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                        @if($data[0]->Paidouts!=0)
                            <tr>
                            <td>Total Paidout</td>
                            <td class="text-right">{{'$'}}{{$data[0]->Paidouts}}</td>
                            
                        </tr>
                        @endif
                        @if($data[0]->Discounted_Amount!=0)
                            <tr>
                            <td>Discounted Amount </td>
                           <td class="text-right">{{'$'}}{{$data[0]->Discounted_Amount}}</td>
                            
                        </tr>
                        @endif
                        @if($data[0]->Discounted_Trns!=0)
                            <tr>
                            <td>Discounted Transactions</td>
                            <td class="text-right">{{'$'}}{{$data[0]->Discounted_Trns}}</td>
                            
                        </tr>
                        @endif
                        @if($data[0]->Void_Trns!=0)
                            <tr>
                            <td>Voided Transactions</td>
                           <td class="text-right">{{$data[0]->Void_Trns}}</td>
                        </tr>
                        @endif
                        @if($data[0]->Void_Amount!=0) 
                            <tr>
                            <td>Voided Amount</td>
                           <td class="text-right">{{'$'}}{{$data[0]->Void_Amount}}</td>
                        </tr>
                        @endif
                        @if($data[0]->Deleted_Trns!=0)
                            <tr>
                            <td>Deleted Transactions</td>
                            <td class="text-right">{{$data[0]->Deleted_Trns}}</td>
                            
                        </tr>
                        @endif
                        @if($data[0]->Deleted_Amount!=0)
                            <tr>
                            <td>Deleted Total  </td>
                           <td class="text-right">{{'$'}}{{$data[0]->Deleted_Amount}}</td>
                            
                        </tr>
                        @endif
                            <tr>
                            <td>Return Transactions</td>
                           <td class="text-right">{{$data[0]->Return_Trns}}</td>
                        </tr>
                            <tr>
                            <td>Return Amount </td>
                            <td class="text-right">{{'$'}}{{$data[0]->Return_Amount}}</td>
                            
                        </tr>
                        @if($data[0]->NoSale_Count!=0)
                            <tr>
                            <td>No Sale Transactions</td>
                           <td class="text-right">{{'$'}}{{$data[0]->NoSale_Count}}</td>
                        </tr>
                        @endif
                        @if(isset($data[0]->Surcharges) && $data[0]->Surcharges!=0)
                            <tr>
                            <td>Surcharges Collected</td>
                           <td class="text-right">{{'$'}}{{$data[0]->Surcharges}}</td>
                        </tr>
                        @endif
                        @if(isset($data[0]->EbtTaxExempted) && $data[0]->EbtTaxExempted!=0)
                            <tr>
                            <td>EBT Tax Exempted</td>
                           <td class="text-right">{{'$'}}{{$data[0]->EbtTaxExempted}}</td>
                        </tr>
                        @endif
                            <tr>
                            <td>&nbsp;</td>
                            <td><?php echo ""; ?></td>
                        </tr>
                            <tr > 
                            <th style=" text-align:center;border-style: none;background-color:white;">Productivity </th>
                            <td style=" text-align:center;border-left: none;background-color:white;"></td>
                        </tr>
                            <tr>
                            <td>Transaction Count</td>
                            <td class="text-right">{{$data[0]->Trns_Count}}</td>
                            
                        </tr>
                            <tr>
                            <td>Average Sales: </td>
                           <td class="text-right">{{'$'}}{{$data[0]->Avg_Sales}}</td>
                            
                        </tr>
                            <tr>
                            <td>Gross Cost: </td>
                           <td class="text-right">{{'$'}}{{$data[0]->Gross_Cost}}</td>
                        </tr>
                            <tr>
                            <td>Gross Profit: </td>
                           <td class="text-right">{{'$'}}{{$data[0]->Gross_Profit}}</td>
                        </tr>
                            <tr>
                            <td>Gross Profit(%): </td>
                            <td class="text-right">{{$data[0]->Gross_Profit_Per}}</td>
                            
                        </tr>
                    </table>                
                </div> 
                </div> 
             </div>
        </div>  
                    <!--  Paid out section -->
        <div class="row">
              <div class="table-responsive">
                  
                <div class="col-md-12">
                    
                   <div class="col-md-6">
                    
    
                        <table class="table table-bordered table-striped table-hover">
                          @foreach($paidout as $v)
                                 @if($v->Vendor==="Total")
                                 @php
                                   $ptotal=$v->Amount;
                                 @endphp  
                                 
                                 @endif
                          @endforeach
    
                          
                            <tr> 
                              <th data-toggle="collapse" data-target=".paidout" style="width:395px;">Payouts Total &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i onclick="myFunction(tdis)" class="fa fa-angle-double-up"></i>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @if(isset($paidout) && !empty($paidout))<button class="btn btn-primary Pull-left" id="print_button_po">Print</button>@endif</th>
                              <td class='text-right'>{{'$'}}{{$ptotal ?? '0.00'}}</td>
                                
                            </tr>
                             </table>
                   
                      
                        </table>
                    </div>
                </div>
                <div class="collapse paidout">
                 <div class='col-md-12' style=" margin-top: -20px;">
                    
                    <div class='col-md-12'id="print_data_po">
    
                        <table class="table table-bordered table-striped table-hover">
                             <tr> 
                                <td style="width:370px;">Vendor Name</td>
                                <td style="width:123px;"class='text-right'> Amount</td>
                                 
                                <td  style="width:198px;" style="width:120px;"class='text-right'>Register No</td>
                                <td  style="width:172x;"class='text-right'>Time</td>
                                <td  style="width:151px;"class='text-right'> User ID</td>
                            
                            </tr>
                           @foreach($paidout as $v)
                             @if($v->Vendor ==="Total")
                           
                             @continue
                             
                             @endif
                           <tr>
                               <td style="width:370px;" >{{ $v->Vendor ?? '' }}</td>
                                <td style="width:123px;" class='text-right'>{{'$'}}{{$v->Amount ?? '' }}</td>
                               <td  style="width:198px;"class='text-right'>{{ $v->RegNo ?? '' }}</td>
                               <td  style="width:172px;"class='text-right'>{{ $v->ttime ?? '' }}</td>
                               <td  style="width:151px;"class='text-right'>{{ $v->UserId ?? '' }}</td> 
                           </tr>
                         @endforeach
                        </table>
                   
                      
                        </table>
                    </div>
                </div>
            </div>    
           </div>     
         </div>  
        <!--  Paid out section end -->
        <div class="row">
                <div class='col-md-12'>
                    
                    <div class='col-md-6'>
    
                        <table class="table table-bordered table-striped table-hover">
                           
                            @php 
                            $htotal= $trntotal=0;
                            @endphp
    
                            @foreach($hourly as $v)
                                    
                                    @php
                                        $htotal=$htotal+$v->Amount;
                                        $trntotal=$trntotal+$v->Number;
                                    @endphp
                                
                                
                            @endforeach
                                
                                    
                            <tr> 
                              <th data-toggle="collapse" data-target=".hourly" style="width:230px;">Hourly Sales <i onclick="myFunction(tdis)" class="fa fa-angle-double-up"></i>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @if (isset($hourly) && !empty($hourly)) <button class="btn btn-primary Pull-left" id="print_button_hr">Print</button>@endif</th>
                              <td style="width:120px;" class='text-right'>{{'$'}}{{ number_format((float)$data[0]->StoreSalesExclTax, 2, '.', '') ?? '0.00'}}</td>
                             <td  style="width:120px;"class='text-right'>{{ $trntotal ??  '0.00'}}</td>
                            </tr>
                           
                   
                      
                        </table>
                    </div>
                </div>
                    <div class="collapse hourly">
                        <div class='col-md-12' style=" margin-top: -20px;">
                            
                            <div class='col-md-6' id="print_data_hr"> 
    
                                <table class="table table-bordered table-striped table-hover">
                                    <tr> 
                                        <th style="width:230px;">Hourly Sales </th>
                                        <td style="width:120px;"class='text-right'> Amount</td>
                                        
                                        <td class='text-right' style="width:120px;">Sales Transactions</td>
                                    
                                    
                                    </tr>
                                @foreach($hourly as $v)
                                
                                    <tr>
                                        <td style="width:230px;">{{ $v->Hours ?? ''}}</td>
                                        <td style="width:120px;" class='text-right'>{{'$'}}{{ $v->Amount ?? ''}}</td>
                                        <td  style="width:120px;"class='text-right'>{{ $v->Number ?? ''}}</td>
                                        
                                    </tr>
                                @endforeach
                                </table>
                        
                            
                                </table>
                            </div>
                        </div>
                    </div>    
        </div>
        <!-- Hourly sales end -->
        <div class="row">
                
        @php 
        $totaqty=$totaqty=  $totalsale=$totalcost=$totalgpp=0;
        @endphp
        <div class='col-md-12'>
            
                    <?php
                        foreach($report_new_dept as $v){
                          
                            $totaqty=$totaqty+ (isset($v->qty)?$v->qty:0);
                            $totalsale=$totalsale+(isset($v->saleamount)?$v->saleamount:0);
                            $totalcost=$totalcost+(isset($v->cost)?$v->cost:0);
                            // $totalgpp= $totalgpp+$v->gpp;
                            if(isset($totalsale) && $totalsale!=0){
                            $totalgpp=(($totalsale-$totalcost)/$totalsale)*100;
                            }
                            else{
                                $totalgpp=0;
                            }
                        }
                    ?>
                    <div class='col-md-12'>
    
                        <table class="table table-bordered table-striped table-hover">
                           
                            <tr> 
                              <th data-toggle="collapse" data-target=".dept" style="width:370px;"> DEPARTMENT SALES SUMMARY  &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i onclick="myFunction(tdis)" class="fa fa-angle-double-up"></i>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($report_new_dept) && !empty($report_new_dept)) { ?><button class="btn btn-primary Pull-left" id="print_button">Print</button><?php }?></th>
                               <td style="width:120px;"class='text-right'> <?php echo (int)$totaqty; ?> </td>
                               <td style="width:198px;"class='text-right'> <?php echo '$',number_format((float)$data[0]->StoreSalesExclTax, 2, '.', ''); ?> </td>
                               <td style="width:172px;" class='text-right'> <?php echo'$', number_format((float)$totalcost, 2, '.', ''); ?> </td>
                              <td style="width:151px;"class='text-right'> <?php echo number_format($totalgpp,2),'%'; ?> </td>
                                
                            </tr>
                             </table>
                   
                      
                        </table>
                    </div>
                </div>
                <div class="collapse dept">
                 <div class='col-md-12' style=" margin-top: -20px;">
                    
                    <div class='col-md-12' id="print_data">
    
                        <table class="table table-bordered table-striped table-hover">
                             <tr> 
                                <td style="width:370px;">Department Name</td>
                                <td style="width:120px;"class='text-right'> Quantity</td>
                                 
                                <td style="width:198px;" class='text-right'>Sales</td>
                                <td  style="width:172px;" class='text-right'>Cost</td>
                                <td  style="width:151px;" class='text-right'>GP%</td> 
                            
                            </tr>
                           <?php foreach($report_new_dept as $v){?>
                           <?php 
                        //   if($v->vdepatname === "BOTTLE DEPOSIT"){
                        //              continue;
                                     
                                    //  }
                                     ?>
                           <tr>
                               <td style="width:370px;"><?php echo isset($v->vdepatname) ? $v->vdepatname: ""; ?></td>
                               <td style="width:120px;"class='text-right'><?php echo isset($v->qty) ? (int)$v->qty: 0.00; ?></td>
                               <td style="width:198px;" class='text-right'><?php echo '$', isset($v->saleamount) ? $v->saleamount: 0.00; ?></td>
                               <td style="width:172px;" class='text-right'><?php echo '$', isset($v->cost) ? $v->cost: 0.00; ?></td>
                      
                               <td style="width:151px;" class='text-right'><?php echo isset($v->gpp) ? number_format($v->gpp*100,2).'%': 0.00; ?></td>
                           </tr>
                           <?php }?>
                        </table>
                   
                      
                        </table>
                    </div>
                </div>
            </div>   
        
       </div> 
    </div>    
    </div>
</div>

@endsection
@section('scripts')

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js.map"></script>-->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js.map" integrity="undefined" crossorigin="anonymous"></script>-->

    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"></script>



<!-- <style>
.panel-default {
	border: 1px solid #e8e8e8;
	border-top: 2px solid #bfbfbf;
    margin: 10px;
}

.panel-default .panel-heading {
	color: #595959;
	/*border-color: #e8e8e8;*/
	/*background: #fcfcfc;*/
	border-color: #DCDCDC;
	background: #DCDCDC;
    
}
.panel-heading > h3.panel-title{
	margin-left: 45%;
}

.panel-heading > h3.panel-title > i{
	display: none;
}
</style> -->


<script>

const saveData = (function () {
    const a = document.createElement("a");
    document.body.appendChild(a);
    a.style = "display: none";
    return function (data, fileName) {
        const blob = new Blob([data], {type: "octet/stream"}),
            url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = fileName;
        a.click();
        window.URL.revokeObjectURL(url);
    };
  }());


  $(function(){
    $("#start_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
      
      
    });
  });

  $(document).on('submit', '#filter_form', function(event) {

    if($('#start_date').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Date", 
        callback: function(){}
      });
      return false;
    }

    $("div#divLoading").addClass('show');
  });
</script>
<script>
    $(document).on('click', '#print_button',function(){
        
        // var prtContent = document.getElementById("print_data");
        // var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        // WinPrint.document.write(prtContent.innerHTML);
        // WinPrint.document.close();
        // WinPrint.focus();
        // WinPrint.print();
        // WinPrint.close(); 
        var printContents = document.getElementById("print_data").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + printContents + "</body>";
        window.print();
        document.body.innerHTML = originalContents;
        
    
    });
</script>
<script>
    $(document).on('click', '#print_button_hr',function(){
        
        // var prtContent = document.getElementById("print_data_hr");
        // var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        // WinPrint.document.write(prtContent.innerHTML);
        // WinPrint.document.close();
        // WinPrint.focus();
        // WinPrint.print();
        // WinPrint.close(); 
        var printContents = document.getElementById("print_data_hr").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + printContents + "</body>";
        window.print();
        document.body.innerHTML = originalContents;
        
    
    });
</script>

<script>
    $(document).on('click', '#print_button_po',function(){
        
        // var prtContent = document.getElementById("print_data_po");
        // var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        // WinPrint.document.write(prtContent.innerHTML);
        // WinPrint.document.close();
        // WinPrint.focus();
        // WinPrint.print();
        // WinPrint.close(); 
        
        var printContents = document.getElementById("print_data_po").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + printContents + "</body>";
        window.print();
        document.body.innerHTML = originalContents;
        
    
    });
</script>


<script>
function myFunction(x) {
  x.classList.toggle("fa-angle-double-down");
}
</script>

<script>  

    $(document).on("click", "#pdf_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('Eodpdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "End-of-Day-Report.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "End-of-Day-Report.pdf";

              // append the link to the document body

              document.body.appendChild(link);

              link.click();
               
            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();
        
    });

    $(document).on('click', '#btnPrint', function(e){
        e.preventDefault();
        $("div#divLoading").addClass('show');
        $.ajax({
                type: 'GET',
                url: '/eodreport/print',
                // data: formData,
                dataType: 'html',
                success: function (reponse) {
                    $("div#divLoading").removeClass('show');

                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = reponse;

                    window.print();

                    document.body.innerHTML = originalContents;
                },
                error: function (data) {
                    $("div#divLoading").removeClass('show');

                    console.log('Error:', data);
                }
            });
    });


    $(document).on("click", "#csv_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('Eodcsv'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "end-of-day-report.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });

</script>


@endsection

