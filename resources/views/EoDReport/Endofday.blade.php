@extends('layouts.layout')
@section('title')
  EOD Report
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> End Of Day Reportt</span>
                </div>
                <div class="nav-submenu">
                   
                </div>
            </div> 
        </div>
    </nav>

<section class="section-content py-6"> 
        <div class="row" style="padding-bottom: 10px;float: right;display:none;">
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

      
        <div class="row" style="padding-left: 60px;padding-right: 60px">
                <div class="col-md-3" >
                    <button  class="form-control headermenublue rcorner"style="height: 60px; "> <b> $100000 <br>YEAR TO DATE SALES </b></button>

                </div>  
                <div class="col-md-3" >
                    <button  class="form-control headermenublue rcorner"style="height: 60px; "> <b> $100000 <br>MONTH TO DATE SALES </b></button>

                </div>    
                <div class="col-md-3" >
                    <button  class="form-control headermenublue rcorner"style="height: 60px; "> <b> $100000 <br>WEEK TO DATE SALES </b></button>

                </div>    
                <div class="col-md-3" >
                    <button  class="form-control headermenublue rcorner"style="height: 60px; "> <b> $100000 <br>TODAYS TO DATE SALES </b></button>

                </div>      
        </div>
        <br>
            

        <div class="row" style="padding-left: 60px;padding-right: 60px">
                <div class="col-md-12" >
                    <h6><span>DATE SELECTION </span></h6>
                </div>    
        </div>

    <form method="post" action="{{ route('EodForm') }}" id="filter_form" class="form-inline" style="padding-left:40px">
          @csrf
               <div class="form-group mx-sm-4 mb-2">
                    <input type="text" class="date form-control rcorner"  name="start_date" value="{{ $date ?? '' }}" id="start_date" placeholder="Date" autocomplete="off">
                </div>
                
                <div class="form-group mx-sm-4 mb-2">
                    <input type="submit" class="btn btn-success rcorner" value="Generate">
                </div> 
       
           
    </form>
        <br>
    <div class="container-fluid">
        <div class="row" style="padding-left:40px;display:none">
              <div class="col-md-12">
                <div class='col-md-6'>
                    <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
                </div>
              </div>
        </div>
        <div class="row" style="padding-left:40px;display:none">
              <div class="col-md-12">
                <div class='col-md-6'>
                    <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
                </div>    
              </div>
        </div>
        <div class="row" style="padding-left:40px;display:none">
              <div class="col-md-12">
                <div class='col-md-6'>
                    <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
                </div>
              </div>
        </div>
        <div class="row" style="padding-left:40px;display:none">
              <div class="col-md-12">
                <div class='col-md-6'>
                    <p><b>Date: </b>{{$date}}</p>
                </div>
              </div>
        </div>
        
        <divs class="row" style="padding-left: 40px;padding-right: 60px">
                             <div class="col-md-4  text-uppercase">
                                  <h6><span><i class="far fa-square"> &nbsp;&nbsp;&nbsp;</i>SALES DETAIL </span></h6>
                                  <table class="tcolor">
                                      <tr >
                                          <td class="text-right">Store Sales ( Excluding Tax)</td> 
                                          <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px "><?php echo "$",$data[0]->StoreSalesExclTax; ?> </button></td>
                                      </tr>
                                      
                                        <tr>
                                           <td class="text-right">Taxable Sales</td>
                                           <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{ $data[0]->TaxableSales}} </button></td>
                                        </tr>
                                        
                                        <tr>
                                       <td class="text-right">Non-Taxable Sales</td>
                                       <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->NonTaxableSales}} </button></td>
                                       </tr>
                                    
                                       <tr>
                                       
                                      <td class="text-right">Total  Tax &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                      <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->TotalTax}} </button></td>
                                       
                                   </tr>
                                   
                                    @if($data[0]->Tax1!=0)
                                          <tr>
                                          <td class="text-right">Tax1&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                          <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Tax1}} </button> </button></td>
                                          </tr>
                                      @endif
                                      
                                      @if($data[0]->Tax2!=0)
                                           <tr>
                                          <td class="text-right">Tax2&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                          <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Tax2}} </button></td>
                                           </tr>
                                       @endif
                                       
                                       @if($data[0]->Tax3!=0)
                                       <tr>
                                      <td class="text-right">Tax2&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                      <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Tax3}} </button></td>
                                       </tr>
                                       @endif
                                       
                                        <tr>
                                       <td class="text-right">Total Store Sales</td>
                                       <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->StoreSalesInclTax}} </button></td>
                                    </tr>
                                    @if($data[0]->LottoSales!=0)
                                        <tr>
                                       <td class="text-right">Lotto Sales</td>
                                      <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->LottoSales}} </button></td>
                                    </tr>
                                    @endif
                                    @if($data[0]->LiabilitySales!=0)
                                        <tr>
                                       <td class="text-right">Liablity Sales</td>
                                       <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->LiabilitySales}} </button></td>
                                    </tr>
                                    @endif
                                    @if($data[0]->TotalBottleDeposit!=0)
                                        <tr>
                                       
                                       <td class="text-right"> Total Bottle Deposit</td>
                                        <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->TotalBottleDeposit}} </button></td>
                                        
                                    </tr>
                                    @endif 
                                    @if($data[0]->HouseAcctPayments!=0)
                                        <tr>
                                       <td class="text-right">HOUSE ACCOUNT PAYMENTS    </td>
                                        <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctPayments}} </button></td>
                                        
                                    </tr>
                                    @endif 
                                    @if($data[0]->FuelSales!=0)
                                        <tr>
                                       <td class="text-right">Fuel Sales</td>
                                      <td class="text-right"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->FuelSales}} </button></td>
                                    </tr>
                                    @endif 
                                        <tr>
                                           <td class="text-right">Grand Total</td>
                                           <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->GrandTotal}} </button></td>
                                 
                                  </table>
                             </div>
                             
                             
                             <div class="col-md-4">
                                  <h6><span> <i class="far fa-square"> &nbsp;&nbsp;&nbsp;</i>TENDER DETAIL </span></h6>
                                  
                                    @if($data[0]->CashTender !=0)
                                        <tr>
                                            <td class="text-right">CASH </td>
                                            <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->CashTender}}</button></td>
                                        </tr>
                                    @endif
                                        
                                    @if($data[0]->CouponTender !=0)
                                        <tr>
                                            <td class="text-right">COUPON</td>
                                            <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->CouponTender}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->CreditCardTender !=0)
                                        <tr>
                                            <td class="text-right">CREDIT CARD </td>
                                            <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->CreditCardTender}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->CheckTender !=0)
                                        <tr>
                                            <td class="text-right">CHECK </td>
                                            <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->CheckTender}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->EbtTender !=0)
                                        <tr>
                                            <td class="text-right">EBT </td>
                                           <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->EbtTender}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->HouseAcctCharged !=0)
                                        <tr>
                                            <td class="text-right"> HOUSE ACCTOUNT CHARGED </td>
                                            <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctCharged}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->HouseAcctCash !=0)
                                        <tr>
                                            <td class="text-right"> HOUSE ACCT PAYMENT CASH  </td>
                                            <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctCash}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->HouseAcctCard !=0)
                                        <tr>
                                            <td class="text-right">HOUSE ACCT PAYMENT CREDITCARD</td>
                                            <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctCard}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->HouseAcctCheck !=0)
                                        <tr>
                                            <td class="text-right">  HOUSE ACCT PAYMENT CHECK </td>
                                            <td class="text-right"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctCheck}}</button></td>
                                        </tr>
                                    @endif
                                   
                             </div>
                             
                             
                             <div class="col-md-4">
                                  <h6><span> <i class="far fa-square"> &nbsp;&nbsp;&nbsp;</i>PERFORMANCE STATISTICS</span></h6>
                             </div>
        </div>      
        
        
        <div class="row">
              <div class="table-responsive">
                <div class="col-md-12">
                    <div class="col-md-6">
                    <table class="table table-bordered table-striped table-hover">
                          
                        
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
                            //   dd($v);
                            //   if($v->vdepatname === "BOTTLE DEPOSIT"){
                            //              continue;
                            //              }
                            $totaqty=$totaqty+$v->qty;
                            $totalsale=$totalsale+$v->saleamount;
                            $totalcost=$totalcost+$v->cost;
                            // $totalgpp= $totalgpp+$v->gpp;
                            $totalgpp=(($totalsale-$totalcost)/$totalsale)*100;
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
    
</section>

@endsection

@section('page-script')

   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
  
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"></script>


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

<style>
.th_color{
    background-color: #474c53 !important;
    color: #fff;

}
h6 {
   width: 100%; 
   text-align: left; 
   border-bottom: 2px solid; 
   line-height: 0.1em;
   margin: 10px 0 20px; 
   color:#286fb7;
} 

h6 span { 
    background:#f8f9fa!important; 
    padding:0 10px; 
    color:#286fb7;
}
.rcorner {
  border-radius:9px;
}


ul.b {list-style-type: square;}

.list_total{
    background-color: #286fb7 !important;
    border-radius:9px;
    color: #fff;
}
.data_list{
    background-color: #474c53 !important;
    border-radius:9px;
    color: #fff;
}
table, th, td ,tr{
 background-color:#f8f9fa!important;
}


</style>
@endsection

