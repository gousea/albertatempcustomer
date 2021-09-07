@extends('layouts.layout')
@section('title')
End of Shift Report
@endsection
@section('main-content')
<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> End of Shift Report</span>
                </div>
                <div class="nav-submenu">
                   
                </div>
            </div> 
        </div>
    </nav>

<section class="section-content py-6"> 


    <div class="container-fluid">
           
                <form method="post" action="{{ route('ShiftForm') }}" id="filter_form" class="form-inline">
                @csrf
              
                            <div class="form-group mx-sm-4 mb-2">
                                   
                                                <div class='input-group date' id='start_date_container'>
                                                    
                                                    <input type='text' class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                  
                           </div>
                            <div class="form-group mx-sm-4 mb-3">
                           
                            <select class="form-control" name="batch_id" id="batch_id"  placeholder="Select Batch..." required  style="width:170px;">
                                
                                <option value="<?php echo isset($p_batch_id) ? $p_batch_id : ''; ?>" selected ></option>
                            </select>
                            </div>
                            
                            <div class="form-group mx-sm-4 mb-2">
                                <input type="submit" class="btn btn-success" value="Generate">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>

            <?php if(isset($data) && count($data) > 0){ ?>
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
            <?php } ?>


            <?php if(isset($data) && count($data) > 0){ ?>
                <div class="row" style="margin: 10px;">
                  <div class="col-md-12">
                    <p><b> Store Name: </b>{{ session()->get('storeName') }}</p>
                    </div>
                </div>
                <div class="row" style="margin: 10px;">
                  <div class="col-md-12">
                    <p><b> SID: </b> {{ session()->get('sid') }} </p>
                  </div>
                </div>
                <div class="row" style="margin: 10px;">
                    <div class="col-md-12">
                    <p><b> Batch :</b><?php echo $p_batch_id;?> </p>
                  </div>
                </div>
                <div class="row" style="margin: 10px;">
                <?php 
                    if(isset($data[0]->BatchStartTime)){
                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data[0]->BatchStartTime);
                        $startdate=$date->format('m-d-Y H:i:s');
                    } else {
                        $startdate = 'NULL';
                    }
                    
                    if(isset($data[0]->BatchStartTime)){
                        $edate = \DateTime::createFromFormat('Y-m-d H:i:s', $data[0]->BatchEndTime);
                        $endtdate=$edate->format('m-d-Y H:i:s');
                    } else {
                        $endtdate = 'NULL';
                    }
        ?>
          <div class="col-md-12">
                    <p><b>SHIFT START: </b><?php echo $startdate; ?></p>
                  </div>
          </div>
                <div class="row" style="margin: 10px;">
                  <div class="col-md-12">
                    <p><b>SHIFT END: </b><?php echo $endtdate; ?></p>
                  </div>
          </div>
                <div class="row" style="margin: 10px;">
                  <div class="col-md-12">
                    <p><b>Register No: </b><?php echo isset($data[0]->TerminalId)?$data[0]->TerminalId:'NULL'; ?></p>
                  </div>
    </div>
                <div class="row" style="margin: 10px;">
                    <div class="col-md-4 table-responsive table  table-striped table-hover">
                        <br>
                        
                        <table class="table" style="border:none;">
                            <h4 style="text-align: center;"><b>SALES TOTALS </b></h4>
                            <tr>
                                <td >SALES(excluding Tax)</td>
                                <td class="text-right"><?php echo"$", number_format($data[0]->SalesExclTax,2);?></th>
                            </tr>
                            <tr>
                                <td>TAXABLE SALES</td>
                                
                                <td class="text-right"><?php echo"$", $data[0]->TotalTaxable;?></th>
                                
                            </tr>
                            <tr>
                                <td>NON-TAXABLE</td>
                                <td class="text-right"><?php echo"$", $data[0]->TotalNonTaxable;?></td>
                            </tr>
                            <tr>
                                <td>TOTAL TAX</td>
                               
                                <td class="text-right"><?php echo"$", $data[0]->TotalTax;?></td>
                                
                            </tr>
                            
                            <?php if($data[0]->TotalLottery !=0){?>
                            <tr>
                                <td>TOTAL LOTTO SALES</td>
                                <td class="text-right"><?php echo "$",$data[0]->TotalLottery;?></td>
                            </tr>
                             <?php } ?>
                            <?php if($data[0]->liabilitysales !=0){?>
                            <tr>
                                <td>LIABILITY SALES</td>
                                <td class="text-right"><?php echo "$",$data[0]->liabilitysales;?></td>
                            </tr>
                            <?php } ?>
                            <?php if($data[0]->BottleDeposit !=0){?>
                            <tr>
                                <td>BOTTLE DEPOSITE</td>
                                <td class="text-right"><?php echo "$",$data[0]->BottleDeposit;?></td>
                            </tr>
                            <?php } ?>
                             <?php if($data[0]->BottleDepositRedeem !=0){?>
                            <tr>
                                <td>BOTTLE DEPOSITE REDEEM</td>
                                <td class="text-right"><?php echo "$",$data[0]->BottleDepositRedeem;?></td>
                            </tr>
                            <?php } ?>
                              <?php if($data[0]->BottleDepositTax !=0){?>
                            <tr>
                                <td>BOTTLE DEPOSITE TAX</td>
                                <td class="text-right"><?php echo "$",$data[0]->BottleDepositTax;?></td>
                            </tr>
                            <?php } ?>
                            
                              <?php if($data[0]->BottleDepositRedeemTax !=0){?>
                            <tr>
                                <td> BOTTLE DEPOSITE REDEEM TAX</td>
                                <td class="text-right"><?php echo "$",$data[0]->BottleDepositRedeemTax;?></td>
                            </tr>
                            <?php } ?>
                            
                            
                             <?php if($data[0]->HouseAcctPay !=0){?>
                            <tr>
                                <td>HOUSE ACCOUNT PAYMENTS </td>
                                <td class="text-right"><?php echo "$",$data[0]->HouseAcctPay;?></td>
                            </tr>
                            <?php } ?>
                            
                            
                            <tr>
                                <b><td> <b>GRAND TOTAL</b></td>
                                
                                <td class="text-right"><b><?php echo "$",number_format($data[0]->NetSales,2);?> </b></td>
                                </b>
                            </tr>
                            
                            
                           
                        </table>
                    </div>
        </div>
                <div class="row" style="margin: 10px;">
                    <div class="col-md-4 table-responsive table  table-striped table-hover">
                        <table width="100%" style="border:none;">
                          <h4 style="text-align: center;"><b>LOTTO SALES DETAILS </b></h4>
                              
                            <tbody>
                           <?php if($data[0]->LotterySales !=0){?>
                            <tr>
                                <td>LOTTERY SALES  </td>
                                <td class="text-right"><?php echo "$",$data[0]->LotterySales;?></td>
                            </tr>
                             <?php } ?>
                              <?php if($data[0]->InstantSales !=0){?>
                            <tr>
                                <td>INSTANT SALES </td>
                                <td class="text-right"><?php echo "$",$data[0]->InstantSales;?></td>
                            </tr>
                             <?php } ?>
                              <?php if($data[0]->LotteryRedeem !=0){?>
                            <tr>
                                <td> LOTTERY REDEEM  </td>
                                <td class="text-right"><?php echo "$",$data[0]->LotteryRedeem;?></td>
                            </tr>
                             <?php } ?>
                              <?php if($data[0]->InstantRedeem !=0){?>
                            <tr>
                                <td>INSTANT REDEEM </td>
                                <td class="text-right"><?php echo "$",$data[0]->InstantRedeem;?></td>
                            </tr>
                             <?php } ?>
                          </tbody>
                        </table>
                   
                    </div>
        </div>
                <br/>
                 <div class="row" style="margin: 10px;">
                    <div class="col-md-4 table-responsive table  table-striped table-hover">
                        <table width="100%" style="border:none;">
                            
                             <b> <h4 style="text-align: center;"><b>TENDER DETAILS</b></h4> </b>
                            
                            <tbody>
                                  <?php if($data[0]->CashTender !=0){?>
                            <tr>
                                <td>CASH </td>
                                <td class="text-right"><?php echo "$",$data[0]->CashTender;?></td>
                            </tr>
                             <?php } ?>
                             
                                 <?php if($data[0]->Coupon !=0){?>
                            <tr>
                                <td>Coupon </td>
                                <td class="text-right"><?php echo "$",$data[0]->Coupon;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data[0]->CreditCardTender !=0){?>
                            <tr>
                                <td>CREDIT CARD </td>
                                <td class="text-right"><?php echo "$",$data[0]->CreditCardTender;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data[0]->CheckTender !=0){?>
                            <tr>
                                <td>CHECK </td>
                                <td class="text-right"><?php echo "$",$data[0]->CheckTender;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data[0]->EBTTender !=0){?>
                            <tr>
                                <td> EBT</td>
                                <td class="text-right"><?php echo "$",$data[0]->EBTTender;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data[0]->HouseAcctTender !=0){?>
                            <tr>
                                <td>ON ACCT  </td>
                                <td class="text-right"><?php echo "$",$data[0]->HouseAcctTender;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data[0]->HouseAcctCash !=0){?>
                            <tr>
                                <td> HOUSE ACCT PAYMENT CASH  </td>
                                <td class="text-right"><?php echo "$",$data[0]->HouseAcctCash;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data[0]->HouseAcctCard !=0){?>
                            <tr>
                                <td>HOUSE ACCT PAYMENT CREDITCARD</td>
                                <td class="text-right"><?php echo "$",$data[0]->HouseAcctCard;?></td>
                            </tr>
                             <?php } ?>
                               <?php if($data[0]->HouseAcctCheck !=0){?>
                            <tr>
                                <td>  HOUSE ACCT PAYMENT CHECK </td>
                                <td class="text-right"><?php echo "$",$data[0]->HouseAcctCheck;?></td>
                            </tr>
                             <?php } ?>
                           
                             
                           
                              </tbody>
                        </table>
                   
                    </div>
        </div> 
                <br>
                 <div class="row" style="margin: 10px;">
                    <div class="col-md-4 table-responsive table  table-striped table-hover">
                        <table width="100%" style="border:none;">
                            <h4 style="text-align: center;"><b>PERFORMANCE STATISTICS</b></h4>
                            <tbody>
                                <tr>
                               <?php if($data[0]->TotalReturns !=0){ ?>
                                <td>#RETURNED ITEMS</td>
                                <td class="text-right"><?php 
                                if($data[0]->TotalReturns<0){
                                
                                echo "(","$",number_format(abs($data[0]->TotalReturns),2),")";
                                }
                            
                                else{
                                echo "$",$data[0]->TotalReturns;
                                }
                                
                                ?>
                                </td>
                                <?php } ?>
                            </tr>
                                <tr>
                                <td>#OF TRANSACTION </td>
                                <td class="text-right"><?php echo $data[0]->NoOfTransactions;?></td>
                            </tr>
                                <tr>
                                <td>#AVG TRANSACTIONS</td>
                                <td class="text-right"><?php echo "$",number_format($data[0]->AvgSaleTrn,2);?></td>
                            </tr>
                            
                            <?php if(isset($data[0]->Surcharges ) && $data[0]->Surcharges !=0){ ?>
                            <tr>
                                <td>#Surcharges Collected </td>
                                <td class="text-right"><?php echo $data[0]->Surcharges;?></td>
                            </tr>
                            <?php } ?>
                            
                             <?php if(isset($data[0]->EbtTaxExempted ) && $data[0]->EbtTaxExempted !=0){ ?>
                            <tr>
                                <td>#EBT Tax Exempted </td>
                                <td class="text-right"><?php echo $data[0]->EbtTaxExempted;?></td>
                            </tr>
                            <?php } ?>
                            
                             <?php if(isset($data[0]->ConvCharge ) && $data[0]->ConvCharge !=0){ ?>
                            <tr>
                                <td>#CONVENIENCE CHARGE </td>
                                <td class="text-right"><?php echo $data[0]->ConvCharge;?></td>
                            </tr>
                            <?php } ?>
                            
                            
                            </tbody>
                        </table>
                    </div>
        </div> 
                <br>
                 <div class="row" style="margin: 10px;">
                    <div class="col-md-4 table-responsive table  table-striped table-hover">
                        <table width="100%" style="border:none;">
                            
                               <h4 style="text-align: center;"><b>CASH COUNT</b></h4>
                            
                             
                            <tbody>
                            <tr>
                                <td>OPENING CASH</td>
                                <td class="text-right" ><?php echo "$",$data[0]->OpeningBalance;?></td>
                            </tr>
                             <tr>
                                <td>+CASH SALES</td>
                                <td class="text-right"><?php echo "$",$data[0]->CashTender;?></td>
                            </tr>
                            <tr>
                                <td>+CASH ADD</td>
                                <td class="text-right"><?php echo "$",$data[0]->NetAddCash;?></td>
                            </tr>
                            <?php if($data[0]->HouseAcctPay!=0){ ?>
                             <tr>
                                <td>+ON ACCOUNT</td>
                                <td class="text-right"><?php echo "$",$data[0]->HouseAcctPay;?></td>
                            </tr>
                            <?php } ?>
                            
                             <tr>
                                <td>-CASH PAIDOUT</td>
                                <td class="text-right"><?php echo "$",$data[0]->NetPaidout;?></td>
                            </tr>
                            <tr>
                                <td>-SAFE DROP</td>
                                <td class="text-right"><?php echo "$",$data[0]->NetCashPickup;?></td>
                            </tr>
                            
                            
                              </tbody>
                        </table>
              
                <br>
                <hr>
                </div>
        </div>
                <div class="row" style="margin: 10px;">
                    <div class="col-md-4 table-responsive table  table-striped table-hover">
                        <table width="100%" style="border:none;">
                          
                            <tbody>
                            <tr>
                                <td> EXPECTED CASH </td>
                                <td class="text-right"><?php echo "$",$excash=$data[0]->ClosingBalance;?></td>
                            </tr>
                            <tr>
                                <td> ACTUAL CASH </td>
                                <td class="text-right"><?php echo "$",$data[0]->UserClosingBalance;?></td>
                            </tr>
                            <tr>
                                <td>CASH SHORT</td>
                                <?php $cashshort=$data[0]->CashShortOver;?>
                                <td class="text-right"><?php if($cashshort<0){
                                 echo "(", "$",number_format(abs($cashshort),2),")";
                                }
                             
                                else {
                                echo "$",number_format($cashshort,2);
                                
                                }?></td>
                            </tr>
                              </tbody>
                        </table>
              
                <br>
                </div>
        </div>
       <?php if(isset($cashier_data))  {?>
        <div class="row" style="margin: 10px;">
                    <div class="col-md-4">
                        <table width="100%" style="border:none;">
                            
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
         <?php }?> 
                <div class="row">
            @php 
            $totaqty=$totaqty=  $totalsale=$totalcost=$totalgpp=0;
            @endphp
            <div class='col-md-12'>
                          <?php foreach($report_new_dept as $v){
                          $totaqty=$totaqty+$v->qty;
                          $totalsale=$totalsale+$v->saleamount;
                          $totalcost=$totalcost+$v->cost;
                          $totalgpp= $totalgpp+$v->gpp;
                          
                          
                           }?>
                        
        
                            <table class="table table-bordered table-striped table-hover">
                               
                                <tr> 
                                  <td data-toggle="collapse" data-target=".dept" style="width:370px;"> DEPARTMENT SALES SUMMARY  &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i onclick="myFunction(this)" class="fa fa-angle-double-up"></i>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($report_new_dept) && !empty($report_new_dept)) { ?><button class="btn btn-primary Pull-left" id="print_button">Print</button><?php }?></td>
                                   <td style="width:120px;"class='text-right'> <?php echo $totaqty; ?> </td>
                                   <td style="width:198px;"class='text-right'> <?php echo $totalsale; ?> </td>
                                   <td style="width:172px;" class='text-right'> <?php echo $totalcost; ?> </td>
                                   <td style="width:151px;"class='text-right'> <?php echo number_format($totalgpp,2); ?> </td>
                                    
                                </tr>
                                 </table>
                       
                        </div>
                    </div>
                    <div class=" row collapse dept">
                     <div class='col-md-12' style=" margin-top: -20px;">
                        
                        <div  id="print_data">
        
                            <table class="table table-bordered table-striped table-hover">
                                 <tr> 
                                    <td style="width:370px;">Department Name</td>
                                    <td style="width:120px;"class='text-right'> Quantity</td>
                                     
                                    <td class='text-right'>Sales</td>
                                    <td class='text-right'>Cost</td>
                                    <td class='text-right'>GP%</td> 
                                
                                </tr>
                               <?php foreach($report_new_dept as $v){?>
                              
                               <tr>
                                   <td><?php echo isset($v->vdepatname) ? $v->vdepatname: ""; ?></td>
                                   <td class='text-right'><?php echo isset($v->qty) ? $v->qty: 0.00; ?></td>
                                   <td class='text-right'><?php echo isset($v->saleamount) ? $v->saleamount: 0.00; ?></td>
                                   <td class='text-right'><?php echo isset($v->cost) ? $v->cost: 0.00; ?></td>
                                   <td class='text-right'><?php echo isset($v->gpp) ? number_format($v->gpp,2): 0.00; ?></td>
                               </tr>
                               <?php }?>
                            </table>
                       
                          
                            </table>
                        </div>
                    </div>
                </div>   
            
   </div> 
            <?php } ?>
        </div>
        </div> 
    </div>    
</section>
@endsection

@section('page-script')

<link type="text/css" href="{{ asset('javascript/bootstrap-datepicker.css')}}" rel="stylesheet" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
<script type="text/javascript" src="{{ asset('javascript/table-fixed-header.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<!-- <style type="text/css">
  .select2-container--default .select2-selection--single{
    border-radius: 0px !important;
    height: 35px !important;
    width
  }
  .select2.select2-container.select2-container--default{
    width: 100% !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 35px !important;
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

  
$(document).ready(function(){
   
    
    function cb(start, end) {
        
        var start_date = '<?= $p_start_date ??''; ?>';
        if(start_date != '' && typeof(start_date) != 'undefined'){
             
            $('input[name="start_date"]').val(start_date);
        }else{
            $('input[name="start_date"]').html(start.format('MMMM D, YYYY'));
        }
        
    }
    
    var start = moment().startOf('day');
    var end = moment().endOf('day');

        $('input[name="start_date"]').daterangepicker({
            "singleDatePicker": true,
            startDate: start,
            endDate: end,
            locale: {
              format: 'MM/DD/YYYY'
            },
          
        cb});
        
      $(function() {


    cb(start, end);

});
      
      
    var reportdata_url = '<?php echo route('EodBatch'); ?>';
        reportdata_url  = reportdata_url.replace(/&amp;/g, '&');
        var dates  = $("#start_date").val();
        var end_date    = $("#end_date").val();
    
        var start_date = '<?= $p_start_date ??''; ?>';
        if(start_date != '' && typeof(start_date) != 'undefined'){
             
            start_date =start_date;
        }else{
            
            start_date = $('input[name="start_date"]').val();
        }
        var selected_batch_id = '<?= $p_batch_id ??''; ?>';
        
        if(start_date != "")
        {
            $.ajax({
                url     : reportdata_url,
                data    : {start_date:start_date},
                type    : 'GET',
            }).done(function(response){
                if(response)
                {
                    $('#batch_id').find('option').remove();
                    var obj = response;
                    $.each(obj,function(key,val){
                        if(selected_batch_id != '' && typeof(selected_batch_id) != 'undefined' && selected_batch_id == val['ibatchid']){
                            
                            $('#batch_id').append($("<option></option>")
                            .attr("value",val['ibatchid'])
                            .attr("selected", "selected")
                            .text(val['ibatchid']));
                        }else{
                            
                            $('#batch_id').append($("<option></option>")
                                .attr("value",val['ibatchid'])
                                .text(val['ibatchid']));
                        }
                    })
                    $('#batch_id').select2();
                }
            });
        }
        
        $(document).on('change', '#start_date',function(){ 
            
            var start_date1 = $('input[name="start_date"]').val();
            // console.log(start_date1);
            if(start_date != "")
            {
                $.ajax({
                    url     : reportdata_url,
                    data    : {start_date:start_date1},
                    type    : 'GET',
                }).done(function(response){
                    if(response)
                    {
                        $('#batch_id').find('option').remove();
                        var obj = response;
                        $.each(obj,function(key,val){
                            $('#batch_id').append($("<option></option>")
                                .attr("value",val['ibatchid'])
                                .text(val.ibatchid)); 
                        })
                        $('#batch_id').select2();
                    }
                });
            }
        });
});
    $(document).on('submit', '#filter_form', function(event) 
    {
        if($('#start_date').val() == '')
        {
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: "Please Select Start Date", 
                callback: function(){}
            });
            return false;
        }

        if($('#end_date').val() == '')
        {
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: "Please Select End Date", 
                callback: function(){}
            });
            return false;
        }

        if($('input[name="start_date"]').val() != '' && $('input[name="end_date"]').val() != ''){

            var d1 = Date.parse($('input[name="start_date"]').val());
            var d2 = Date.parse($('input[name="end_date"]').val()); 

            if(d1 > d2){
                bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: "Start date must be less then end date!", 
                    callback: function(){}
                });
                return false;
            }
        }
        
        if($('#batch_id').val() == '')
        {
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: "Batch Should not be empty!", 
                callback: function(){}
            });
            return false;
        }
        $("div#divLoading").addClass('show');
    });
</script>
<script>
    $(document).on('click', '#print_button',function(){
        
        var prtContent = document.getElementById("print_data");
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close(); 
        
    
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

        var pdf_export_url = '<?php echo route('Eodshiftpdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "End-of-shift-Report.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "End-of-shift-Report.pdf";

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
                url: '/eodshift/print',
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

                    // console.log('Error:', data);
                }
            });
    });


    $(document).on("click", "#csv_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('Eodshiftcsv'); ?>';
        
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