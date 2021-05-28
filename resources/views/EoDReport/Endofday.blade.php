@extends('layouts.layout')
@section('title')
  EOD Report
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"  style="font-size:15px"> End Of Day Report</span>
                </div>
                <div class="nav-submenu">
                    
                   
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" id="btnPrint" >PRINT
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " id="pdf_export_btn" href="#" > PDF
                            </a>
                       
                
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
               <div class="form-group mx-sm-4 mb-2 " >
                    <input type="text" class="date form-control rcorner"  name="start_date" value="{{ $date ?? '' }}" id="start_date" placeholder="Date" autocomplete="off">
                </div>
                
                <div class="form-group mx-sm-4 mb-2">
                    <input type="submit" class="btn btn-success rcorner" value="Generate">
                </div> 
                
                <div class="form-group mx-sm-2 mb-2">
                 
                         <?php 
                              $date_eod = \DateTime::createFromFormat('m-d-Y' , $date);
                              $startdate=$date_eod->format('d-m-Y'); 
                          ?>

                         <h6 style="text-transform: uppercase;"><span> <?php  echo date(' l F d,Y', strtotime($startdate));?></span></h6>   
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
        
                    <div class="row" style="padding-left: 40px;padding-right: 60px">
                             <div class="col-md-4  text-uppercase">
                                  <h6><span><i class="far fa-square "> &nbsp;&nbsp;&nbsp;</i>SALES DETAIL </span></h6>
                                  <table class"tcolor">
                                      <tr >
                                          <td class="text-right bg_table">Store Sales ( Excluding Tax)</td> 
                                          <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px "><?php echo "$",$data[0]->StoreSalesExclTax; ?> </button></td>
                                      </tr>
                                      
                                        <tr>
                                           <td class="text-right bg_table">Taxable Sales</td>
                                           <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{ $data[0]->TaxableSales}} </button></td>
                                        </tr>
                                        
                                        <tr>
                                       <td class="text-right bg_table">Non-Taxable Sales</td>
                                       <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->NonTaxableSales}} </button></td>
                                       </tr>
                                    
                                       <tr>
                                       
                                      <td class="text-right bg_table">Total  Tax </td>
                                      <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->TotalTax}} </button></td>
                                       
                                   </tr>
                                   
                                    @if($data[0]->Tax1!=0)
                                          <tr>
                                          <td class="text-right bg_table">Tax1&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                          <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Tax1}} </button> </button></td>
                                          </tr>
                                      @endif
                                      
                                      @if($data[0]->Tax2!=0)
                                           <tr>
                                          <td class="text-right bg_table">Tax2&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                          <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Tax2}} </button></td>
                                           </tr>
                                       @endif
                                       
                                       @if($data[0]->Tax3!=0)
                                       <tr>
                                      <td class="text-right bg_table">Tax2&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                      <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Tax3}} </button></td>
                                       </tr>
                                       @endif
                                       
                                        <tr>
                                       <td class="text-right bg_table">Total Store Sales</td>
                                       <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->StoreSalesInclTax}} </button></td>
                                    </tr>
                                    @if($data[0]->LottoSales!=0)
                                        <tr>
                                       <td class="text-right bg_table">Lotto Sales</td>
                                      <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->LottoSales}} </button></td>
                                    </tr>
                                    @endif
                                    @if($data[0]->LiabilitySales!=0)
                                        <tr>
                                       <td class="text-right bg_table">Liablity Sales</td>
                                       <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->LiabilitySales}} </button></td>
                                    </tr>
                                    @endif
                                    @if($data[0]->TotalBottleDeposit!=0)
                                        <tr>
                                       
                                       <td class="text-right bg_table"> Total Bottle Deposit</td>
                                        <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->TotalBottleDeposit}} </button></td>
                                        
                                    </tr>
                                    @endif 
                                    @if($data[0]->HouseAcctPayments!=0)
                                        <tr>
                                       <td class="text-right bg_table">HOUSE ACCOUNT PAYMENTS    </td>
                                        <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctPayments}} </button></td>
                                        
                                    </tr>
                                    @endif 
                                    @if($data[0]->FuelSales!=0)
                                        <tr>
                                       <td class="text-right bg_table">Fuel Sales</td>
                                      <td class="text-right bg_table"> <button  class="data_list"style="height: 30px;width:150px ">{{'$'}}{{$data[0]->FuelSales}} </button></td>
                                    </tr>
                                    @endif 
                                        <tr>
                                           <td class="text-right bg_table">Grand Total</td>
                                           <td class="text-right bg_table"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->GrandTotal}} </button></td>
                                        </tr> 
                                  </table>
                             </div>
                             
                             
                             <div class="col-md-4 text-uppercase">
                                  <h6><span> <i class="far fa-square"> &nbsp;&nbsp;&nbsp;</i>TENDER DETAIL </span></h6>
                                  
                                    @if($data[0]->CashTender !=0)
                                    <table class="tcolor">
                                        <tr>
                                            <td class="text-right bg_table">  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;CASH  </td>
                                            <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->CashTender}}</button></td>
                                        </tr>
                                    @endif
                                        
                                    @if($data[0]->CouponTender !=0)
                                        <tr>
                                            <td class="text-right bg_table">COUPON</td>
                                            <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->CouponTender}}</button></td>
                                        </tr>
                                    @endif  
                                    @if($data[0]->CreditCardTender !=0)
                                        <tr>
                                            <td class="text-right bg_table">CREDIT CARD </td>
                                            <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->CreditCardTender}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->CheckTender !=0)
                                        <tr>
                                            <td class="text-right bg_table">CHECK </td>
                                            <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->CheckTender}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->EbtTender !=0)
                                        <tr>
                                            <td class="text-right bg_table">EBT </td>
                                           <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->EbtTender}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->HouseAcctCharged !=0)
                                        <tr>
                                            <td class="text-right bg_table"> HOUSE ACCTOUNT CHARGED </td>
                                            <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctCharged}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->HouseAcctCash !=0)
                                        <tr>
                                            <td class="text-right bg_table"> HOUSE ACCT PAYMENT CASH  </td>
                                            <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctCash}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->HouseAcctCard !=0)
                                        <tr>
                                            <td class="text-right bg_table">HOUSE ACCT PAYMENT CREDITCARD</td>
                                            <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctCard}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->HouseAcctCheck !=0)
                                        <tr>
                                            <td class="text-right bg_table">  HOUSE ACCT PAYMENT CHECK </td>
                                            <td class="text-right bg_table"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->HouseAcctCheck}}</button></td>
                                        </tr>
                                    @endif
                                    @if($data[0]->CashTender+$data[0]->CouponTender+$data[0]->CreditCardTender !=0)
                                      <tr>
                                           <td class="text-right bg_table"> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;TENDER TOTAL</td>
                                           <td class="text-right bg_table"> <button  class="list_total" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->CashTender+$data[0]->CouponTender+$data[0]->CreditCardTender}} </button></td>
                                        </tr> 
                                     @endif    
                                   </table>
                             </div>
                             
                             
                             <div class="col-md-4 text-uppercase">
                                  <h6><span> <i class="far fa-square"> &nbsp;&nbsp;&nbsp;</i>PERFORMANCE STATISTICS</span></h6>
                                  <table class="tcolor">
                                    @if($data[0]->Paidouts!=0)
                                  
                                    <tr>
                                      <td class="text-right bg_table">Total Paidout</td>
                                     <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Paidouts}}</button></td>
                                        
                                    </tr>
                                    @endif
                                    @if($data[0]->Discounted_Amount!=0)
                                        <tr>
                                      <td class="text-right bg_table">Discounted Amount </td>
                                        <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Discounted_Amount}}</button></td>
                                        
                                    </tr>
                                    @endif
                                    @if($data[0]->Discounted_Trns!=0)
                                        <tr>
                                      <td class="text-right bg_table">Discounted Transactions</td>
                                         <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Discounted_Trns}}</button></td>
                                        
                                    </tr>
                                    @endif
                                    @if($data[0]->Void_Trns!=0)
                                        <tr>
                                      <td class="text-right bg_table">Voided Transactions</td>
                                        <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{$data[0]->Void_Trns}}</button></td>
                                    </tr>
                                    @endif
                                    @if($data[0]->Void_Amount!=0) 
                                        <tr>
                                      <td class="text-right bg_table">Voided Amount</td>
                                        <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Void_Amount}}</button></td>
                                    </tr>
                                    @endif
                                    @if($data[0]->Deleted_Trns!=0)
                                        <tr>
                                      <td class="text-right bg_table">Deleted Transactions</td>
                                         <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{$data[0]->Deleted_Trns}}</button></td>
                                        
                                    </tr>
                                    @endif
                                    @if($data[0]->Deleted_Amount!=0)
                                        <tr>
                                        <td class="text-right bg_table">Deleted Total  </td>
                                        <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Deleted_Amount}}</button></td>
                                        
                                    </tr>
                                    @endif
                                    
                                        <tr>
                                        <td class="text-right bg_table">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Return Transactions</td>
                                        <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{$data[0]->Return_Trns}}</button></td>
                                    </tr>
                                    
                                    <tr>
                                      <td class="text-right bg_table">Return Amount </td>
                                      <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Return_Amount}}</button></td>
                                        
                                    </tr>
                                    @if($data[0]->NoSale_Count!=0)
                                        <tr>
                                      <td class="text-right bg_table">No Sale Transactions</td>
                                        <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->NoSale_Count}}</button></td>
                                    </tr>
                                    @endif
                                    @if(isset($data[0]->Surcharges) && $data[0]->Surcharges!=0)
                                        <tr>
                                      <td class="text-right bg_table">Surcharges Collected</td>
                                        <td class="text-right bg_table"> <button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Surcharges}}</button></td>
                                    </tr>
                                    @endif
                                    @if(isset($data[0]->EbtTaxExempted) && $data[0]->EbtTaxExempted!=0)
                                        <tr>
                                      <td class="text-right bg_table">EBT Tax Exempted</td>
                                        <td class="text-right bg_table"><button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->EbtTaxExempted}}</button></td>
                                    </tr>
                                    @endif
                                  </table>
                             </div>
                             
                             
        </div>      
        
        
                     <div class="row" style="padding-left: 40px;padding-right: 60px">
                            <div class="col-md-4  text-uppercase">
                                  <h6><span><i class="far fa-square"> &nbsp;&nbsp;&nbsp;</i>Productivity</span></h6>
                                        <table class"tcolor">
                                          <tr>
                                           
                                            <td class="text-right bg_table">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Transaction Count</td>
                                            <td class="text-right bg_table"><button  class="data_list" style="height: 30px;width:150px ">{{$data[0]->Trns_Count}}</button></td>
                                            
                                        </tr>
                                            <tr>
                                            <td class="text-right bg_table">Average Sales: </td>
                                           <td class="text-right bg_table"><button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Avg_Sales}}</button></td>
                                            
                                        </tr>
                                            <tr>
                                            <td class="text-right bg_table">Gross Cost: </td>
                                           <td class="text-right bg_table"><button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Gross_Cost}}</button></td>
                                        </tr>
                                            <tr>
                                            <td class="text-right bg_table">Gross Profit: </td>
                                            <td class="text-right bg_table"><button  class="data_list" style="height: 30px;width:150px ">{{'$'}}{{$data[0]->Gross_Profit}}</button></td>
                                        </tr>
                                            <tr>
                                            <td class="text-right bg_table">Gross Profit(%): </td>
                                            <td class="text-right bg_table"><button  class="data_list" style="height: 30px;width:150px ">{{$data[0]->Gross_Profit_Per}}</button></td>
                                            
                                        </tr>
                                       
                                        </table> 
                            </div>
                            
                             <div class="col-md-8  text-uppercase">
                                  <h6><span><i class="far fa-square"> &nbsp;&nbsp;&nbsp;</i> PAID OUTS</span></h6>
                                        <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"
                                        data-row-style="rowColors" data-striped="true">
                                        <thead>
                                             <tr class="th_color" >
                                             <th>VENDOR</th>
                                             <th>AMOUNT</th>
                                             <th>REGISTER#</th>
                                             <th>TIME STAMP</th>
                                             <th>USER</th>
                                         </tr>  
                                        </thead>    
                                         
                                        
                                            @foreach($paidout as $v)
                                                     @if($v->Vendor ==="Total")
                                                   
                                                     @continue
                                                     
                                                     @endif
                                                   <tr>
                                                        <td>{{ $v->Vendor ?? '' }}</td>
                                                        <td>{{'$'}}{{$v->Amount ?? '' }}</td>
                                                        <td>{{ $v->RegNo ?? '' }}</td>
                                                        <td>{{ $v->ttime ?? '' }}</td>
                                                        <td>{{ $v->UserId ?? '' }}</td> 
                                                   </tr>
                                                 @endforeach
                                       
                                        </table> 
                            </div>
                    </div>    
                    
                    
                      <div class="row" style="padding-left: 40px;padding-right: 60px">
                           
                                  <h6><span><i class="far fa-square"> &nbsp;&nbsp;&nbsp;</i>HOURLY SALES</span></h6>
                                   <div class="col-md-4  text-uppercase">
                                            <table class"tcolor">
                                              @foreach($hourly as $v)    
                                              <tr>
                                               
                                                <td class="text-right bg_table">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $v->Hours ?? ''}}</td>
                                                <td class="text-right bg_table"><button  class="data_list" style="height: 30px;width:100px ">{{'$'}}{{ $v->Amount ?? ''}}</button></td>
                                                <td class="text-right bg_table"><button  class="data_list" style="height: 30px;width:50px ">{{ $v->Number ?? ''}}</button></td>
                                                
                                              </tr>
                                              @endforeach
                                            </table>
                                    </div>
                                    <div class="col-md-6  text-uppercase">
                                        
                                    </div>    
                      </div>   
                      
                      
                      <div class="row" style="padding-left: 40px;padding-right:60px">
                           
                                  <h6><span><i class="far fa-square"> &nbsp;&nbsp;&nbsp;</i>DEPARTMENT SALES</span></h6>
                                   <div class="col-md-8  text-uppercase">
                                     <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"data-row-style="rowColors" data-striped="true">
                                               <thead>
                                                 <tr class="th_color" >
                                                 <th>DEPARTMENT</th>
                                                 <th>QTY SOLD</th>
                                                 <th>SALES </th>
                                                 <th>CAST</th>
                                                 <th>GP%</th>
                                                 </tr>  
                                               </thead> 
                                                <?php

                                                    $totaqty=$totaqty=  $totalsale=$totalcost=$totalgpp=0;
      
                                                        foreach($report_new_dept as $v){
                                                          
                                                            $totaqty=$totaqty+$v->qty;
                                                            $totalsale=$totalsale+$v->saleamount;
                                                            $totalcost=$totalcost+$v->cost;
                                                            // $totalgpp= $totalgpp+$v->gpp;
                                                            $totalgpp=(($totalsale-$totalcost)/$totalsale)*100;
                                                        }
                                                    ?>
                                               <tr class="headermenublue">
                                                       <td class="bg_table" style="border: none;"> </td>
                                                       <td class="leftcorner "> <?php echo (int)$totaqty; ?> </td>
                                                       <td> <?php echo '$',number_format((float)$data[0]->StoreSalesExclTax, 2, '.', ''); ?> </td>
                                                       <td> <?php echo'$', number_format((float)$totalcost, 2, '.', ''); ?> </td>
                                                       <td> <?php echo number_format($totalgpp,2),'%'; ?> </td>
                                                        
                                               </tr>
                                               <?php foreach($report_new_dept as $v){?>
                         
                                                   <tr>
                                                       <td><?php echo isset($v->vdepatname) ? $v->vdepatname: ""; ?></td>
                                                       <td><?php echo isset($v->qty) ? (int)$v->qty: 0.00; ?></td>
                                                       <td><?php echo '$', isset($v->saleamount) ? $v->saleamount: 0.00; ?></td>
                                                       <td><?php echo '$', isset($v->cost) ? $v->cost: 0.00; ?></td>
                                              
                                                       <td><?php echo isset($v->gpp) ? number_format($v->gpp*100,2).'%': 0.00; ?></td>
                                                   </tr>
                                                <?php }?>
                                    </table>
                                    </div>
                                    <div class="col-md-4  text-uppercase">
                                    
                                    </div>    
                      </div>   
       
    
</section>

@endsection

@section('page-script')
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>

    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">


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
         widgetPositioning:{
                                horizontal: 'auto',
                                vertical: 'bottom'
                            }
      
      
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
.leftcorner{
    border-top-left-radius: 9px 9px;
    border-bottom-left-radius: 9px 9px;
    
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
    padding-bottom:1px;
}
.bg_table{
 background-color:#f8f9fa!important;
 padding-bottom:10px;
}
.no-records-found{
    display:none;
}
.fa-square {
  color: black;
 
  
}

</style>
@endsection

