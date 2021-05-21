@extends('layouts.layout')
@section('title')
   Sales  Report
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase" style="font-size:15px"> Sales Report</span>
                </div>
                 <div class="nav-submenu">
                     <?php if(isset($p_start_date)){ ?> 
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" onclick="exportTableToCSV('Sales_Report.csv')" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu " href="{{route('salesreportprint_page')}}" id="btnPrint" >PRINT
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " id="pdf_export_btn" href="{{route('salesreportpdf_save_page')}}" > PDF
                            </a>
                     <?php } ?>        
                </div>
            </div> 
        </div>
    </nav>

    <section class="section-content py-6">
       
            <div class="row">
                <div class="col-md-12" style="padding-left: 60px;padding-right: 60px">
                    <h6><span>DATE SELECTION </span></h6>
                </div>    
            </div>
            <br>
            
          <form method="post" class="form-inline" style="padding-left:40px" id="filter_form">
              @csrf
              @method('post')
              <div class="form-group mx-sm-3 mb-2">
                <input type="text" style= "width :220px;" class="form-control rcorner" name="dates" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="dates" placeholder="Start Date" readonly>
              </div>
              
              <div class="form-group col-md-0">
              <input type="hidden" class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly>
              </div>
              <div class="form-group  col-md-0">
                <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" readonly>
              </div>
           
              <div class="form-group mx-sm-3 mb-2">
                <input type="submit" class="btn btn-success rcorner header-color" value="Generate">
              </div>
              
              <?php if(isset($p_start_date)){?>
                    <div class="form-group mx-sm-3 mb-2">
                         <?php $date = \DateTime::createFromFormat('m-d-Y' , $p_start_date);
                           $startdate=$date->format('d-m-Y'); ?>
                           
                           <?php $date = \DateTime::createFromFormat('m-d-Y' , $p_end_date);
                           $endtdate=$date->format('d-m-Y'); ?>
                           <h6 style="text-transform: uppercase;"><span> <?php  echo date(' l F d,Y', strtotime($startdate));?> - <?php  echo date(' l F d,Y', strtotime($endtdate));?></span></h6>
                    </div>   
               <?php } ?>
              
            </form>
             
            <br>
            <div class="row">
                    <div class="col-md-12" style="padding-left: 60px;padding-right: 60px">
                        <h6><span>SALES REPORT </span></h6>
                    </div>    
            </div> 
           

        <?php if(isset($reports) && count($reports) > 0){ ?>
       
        <div class="row" style="padding-left: 60px;display: none;">
              <div class="col-md-12">
                <p><b>Date Range: </b><?php echo $p_start_date; ?> to <?php echo $p_end_date; ?></p>
              </div>
              
              <div class="col-md-12">
                <p style="font-size: 14px;"><b>Store Name: </b><?php echo $storename; ?></p>
              </div>
              <div class="col-md-12">
                <p style="font-size: 14px;"><b>Store Address: </b><?php echo $storeaddress; ?></p>
              </div>
              <div class="col-md-12">
                <p style="font-size: 14px;"><b>Store Phone: </b><?php echo $storephone; ?></p>
              </div>
       </div>
         
          <div class="col-md-12 table-responsive">
          <br>
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
                    $tot_surchrges= 0;
                    $tot_EBT_exampted= 0;
                    
                    
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
                    $tot_surchrges=$tot_surchrges+ $value['Surcharges'] ;
                    $tot_EBT_exampted= $tot_EBT_exampted+isset($value['EbtTaxExempted'])?$value['EbtTaxExempted']:0;
                    
                     
                    
                  } ?>
             <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
                    data-pagination="true" data-click-to-select="true">
              <thead class='header'>
                  <tr style="display: none;"><th> Date Range: <?php echo $p_start_date; ?> to <?php echo $p_end_date; ?></th></tr>
                  <tr style="display: none;"><th> Store Name: <?php echo $storename; ?></th></tr>
                  <tr style="display: none;"><th> Store Address: <?php echo $storeaddress; ?></th></tr>
                  <tr style="display: none;"><th> Store Phone: <?php echo $storephone; ?> </th></tr>
                  
                <tr style="border-top: 1px solid #ddd;text-transform: uppercase;" class="th_color" >
                  <th class="text-center "> Date</th>
                  <th class="text-left " >Store Sales (Excluded Tax)</th>
                  <th class="text-left">Non-Taxable Sales</th>
                  <th class="text-left">Taxable Sales</th>
                  <?php if($tot_Tax1Sales!=0){ ?>
                      <th class="text-left">Tax1  Sales</th>
                  <?php } ?>
                  <?php if($tot_Tax2Sales!=0){ ?>
                       <th class="text-left">Tax2  Sales</th>
                  <?php } ?>
                  
                  <?php if($tot_Tax3Sales!=0){ ?>
                    <th class="text-left">Tax3  Sales</th>
                  <?php } ?>
                  
                  <th class="text-left">Sales Tax </th>
                  
                  <?php if($tot_Tax1!=0){ ?>
                    <th class="text-left">Tax1</th>
                  <?php } ?>
                  
                  <?php if($tot_Tax2!=0){ ?>
                    <th class="text-left">Tax2</th>
                  <?php } ?>
                  
                  <?php if($tot_Tax3!=0){ ?>
                     <th class="text-left">Tax3</th>
                  <?php } ?>
                  
                  <th class="text-left">Total Store Sales:</th>
                  
                  <?php if($tot_TotalFuelSales!=0){ ?>
                     <th class="text-left">Fuel Sales</th>
                  <?php } ?>
                  
                  <?php if($tot_TotalLottorySales!=0){ ?>
                    <th class="text-left">Lotto Sales</th>
                  <?php } ?>
                  
                  <?php if($tot_TotalLiabilitySales!=0){ ?>
                     <th class="text-left">Liablity Sales</th>
                  <?php } ?>
                  
                  <th class="text-left">Total Sales</th>
                   
                  <?php if($tot_HouseCharged!=0){ ?>
                  <th class="text-left">House Charged</th>
                  <?php } ?>
                  
                  <?php if($tot_HouseChargePayments!=0){ ?>
                  <th class="text-left">House Charge Payments</th>
                  <?php  } ?>
                  
                  <?php if($tot_BottleDeposit!=0){ ?>
                    <th class="text-left">Bottle Deposit</th>
                  <?php } ?>
                  
                  <?php if($tot_BottleDepositRedeem!=0){ ?>
                     <th class="text-left">Bottle Deposit Redeem</th>
                  <?php } ?>
                  
                  <?php if($tot_TotalPaidout!=0){ ?>
                    <th class="text-left">Total Paid out</th>
                  <?php } ?>
                  
                  <th class="text-left">Cash</th>
                  
                  <?php if($tot_CouponTender!=0){ ?>
                  <th class="text-left">Coupon</th>
                  <?php } ?>
                  
                  <?php if($tot_CheckTender!=0){ ?>
                  <th class="text-left">Check</th>
                  <?php } ?>
                  
                  <th class="text-left">Credit Card Total</th>
                  
                  <?php if($tot_EBTCash!=0){ ?>
                  <th class="text-left">EBT Cash</th>
                  <?php } ?>
                  
                  <?php if($tot_EBT!=0){ ?>
                  <th class="text-left">EBT</th>
                  <?php } ?>
                  
                  <?php if($tot_surchrges!=0){ ?>
                  <th class="text-left">Surcharges</th>
                  <?php } ?>
                  
                  <?php if($tot_EBT_exampted!=0){ ?>
                  <th class="text-left">EBT Tax Exempted</th>
                  <?php } ?>
                  
                  
                  
                  
                </tr>
              </thead>
              <tbody>
                  
                  
                    <tr class="header-color">
                    <td ><b style="padding: 18px;">TOTALS</b> </td>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_StoreSalesExclTax, 2, '.', '') ;?></b></td>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_NonTaxableSales, 2, '.', '') ;?></b></td>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_TaxableSales, 2, '.', '') ;?></b></td>
                    <?php if($tot_Tax1Sales!=0){ ?>
                      <td class="text-left"><b><?php echo "$",number_format((float)$tot_Tax1Sales, 2, '.', '') ;?></b></td>
                    <?php } ?>
                    <?php if($tot_Tax2Sales!=0){ ?>
                      <td class="text-left"><b><?php echo "$",number_format((float)$tot_Tax2Sales, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <?php if($tot_Tax3Sales!=0){ ?>
                       <td class="text-left"><b><?php echo "$",number_format((float)$tot_Tax3Sales, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_TotalTax, 2, '.', '') ;?></b></td>
                    
                    <?php if($tot_Tax1!=0){ ?>
                      <td class="text-left"><b><?php echo "$",number_format((float)$tot_Tax1, 2, '.', '') ;?></b></td>
                    <?php  } ?> 
                    
                    <?php if($tot_Tax2!=0){ ?>  
                       <td class="text-left"><b><?php echo "$",number_format((float)$tot_Tax2, 2, '.', '') ;?></b></td>
                     
                    <?php  } ?>
                    
                    <?php if($tot_Tax3!=0){ ?>
                       <td class="text-left"><b><?php echo "$",number_format((float)$tot_Tax3, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_TotalStoreSales, 2, '.', '') ;?></b></td>
                    
                    <?php if($tot_TotalFuelSales!=0){ ?>
                      <td class="text-left"><b><?php echo "$",number_format((float)$tot_TotalFuelSales, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <?php if($tot_TotalLottorySales!=0){ ?>  
                      <td class="text-left"><b><?php echo "$",number_format((float)$tot_TotalLottorySales, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <?php if($tot_TotalLiabilitySales!=0){ ?>
                      <td class="text-left"><b><?php echo "$",number_format((float)$tot_TotalLiabilitySales, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_TotalSales, 2, '.', '') ;?></b></td>
                    
                    <?php if($tot_HouseCharged!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_HouseCharged, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                   
                    <?php if( $tot_HouseChargePayments!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_HouseChargePayments, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <?php if($tot_BottleDeposit!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_BottleDeposit, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <?php if($tot_BottleDepositRedeem!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_BottleDepositRedeem, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <?php if($tot_TotalPaidout!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_TotalPaidout, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_CashTender, 2, '.', '') ;?></b></td>
                    
                    <?php if($tot_CouponTender!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_CouponTender, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <?php if($tot_CheckTender!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_CheckTender, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_CreditCardTender, 2, '.', '') ;?></b></td>
                    
                    
                    <?php if($tot_EBTCash!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_EBTCash, 2, '.', '') ;?></b></td>
                     <?php  } ?>
                     
                    <?php if($tot_EBT!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_EBT, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    <?php if($tot_surchrges!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_surchrges, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    
                    <?php if($tot_EBT_exampted!=0){ ?>
                    <td class="text-left"><b><?php echo "$",number_format((float)$tot_EBT_exampted, 2, '.', '') ;?></b></td>
                    <?php  } ?>
                    
                    </tr>
                    
                    
                    
                   
                  <?php foreach ($reports as $key => $value){ ?>
                   
                    <tr>
                      <td><?php echo $value['eoddate'];?></td>
                      <td class="text-left"><?php echo "$", number_format((float)$value['StoreSalesExclTax'], 2, '.', '') ;?></td>
                      <td class="text-left"><?php echo "$",number_format((float)$value['NonTaxableSales'], 2, '.', '') ;?></td>
                      <td class="text-left"><?php echo "$", number_format((float)$value['TaxableSales'], 2, '.', '') ;?></td>
                      
                      <?php if($tot_Tax1Sales!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['Tax1Sales'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if($tot_Tax2Sales!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['Tax2Sales'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if($tot_Tax3Sales!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['Tax3Sales'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <td class="text-left"><?php echo "$",number_format((float)$value['TotalTax'], 2, '.', '') ;?></td>
                      
                      <?php if($tot_Tax1!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['Tax1'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if($tot_Tax2!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['Tax2'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if($tot_Tax3!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['Tax3'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      
                      <td class="text-left"><?php echo "$",number_format((float)$value['TotalStoreSales'], 2, '.', '') ;?></td>
                      <?php if($tot_TotalFuelSales!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['TotalFuelSales'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if($tot_TotalLottorySales!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['TotalLottorySales'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if($tot_TotalLiabilitySales!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['TotalLiabilitySales'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <td class="text-left"><?php echo "$",number_format((float)$value['TotalSales'], 2, '.', '') ;?></td>
                      
                      <?php if($tot_HouseCharged!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['HouseCharged'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if( $tot_HouseChargePayments!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['HouseChargePayments'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if($tot_BottleDeposit!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['bottledeposit'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if($tot_BottleDepositRedeem!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['bottledepositredeem'], 2, '.', '') ;?></td>
                      <?php  } ?>
                      
                      <?php if($tot_TotalPaidout!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['TotalPaidout'], 2, '.', '') ;?></td>
                       <?php  } ?>
                       
                      <td class="text-left"><?php echo "$",number_format((float)$value['CashTender'], 2, '.', '') ;?></td>
                      
                      <?php if($tot_CouponTender!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['CouponTender'], 2, '.', '') ;?></td>
                       <?php  } ?>
                       
                      <?php if($tot_CheckTender!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['CheckTender'], 2, '.', '') ;?></td>
                       <?php  } ?>
                       
                      <td class="text-left"><?php echo "$",number_format((float)$value['CreditCardTender'], 2, '.', '') ;?></td>
                      
                      <?php if($tot_EBTCash!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['EBTCash'], 2, '.', '') ;?></td>
                       <?php  } ?>
                       
                      <?php if($tot_EBT!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['EBT'], 2, '.', '') ;?></td>
                       <?php  } ?>

                     
                      <?php if($tot_surchrges!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['Surcharges'], 2, '.', '') ;?></td>
                      <?php  } ?>

                     
                       <?php if($tot_EBT_exampted!=0){ ?>
                      <td class="text-left"><?php echo "$",number_format((float)$value['EbtTaxExempted'], 2, '.', '') ;?></td>
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




@endsection

@section('page-script')
<link type="text/css" href="{{ asset('javascript/bootstrap-datepicker.css')}}" rel="stylesheet" />
<script src="{{ asset('javascript/bootstrap-datepicker.js')}}"></script>

<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
<script type="text/javascript" src="{{ asset('javascript/table-fixed-header.js') }}"></script>
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">

<style type="text/css">
  .table.table-b.table-striped.table-hover thead > tr {
    background-color: #2486c6;
    color: #fff;
  }
</style>

<script type="text/javascript">
   $(function(){
    $("#start_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });

    $("#end_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });
  });
  
  
   
   $(document).ready(function() {
      $('input[name="dates"]').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'M-D-Y'
        }
      });
      
      $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('input[name="dates"]').html(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
        console.log(start.format('YYYY-MM-DD'));
        
        // $('input[name="start_date"]').val(start.format('YYYY-MM-DD'));
        // $('input[name="end_date"]').val(end.format('YYYY-MM-DD'));
    }

    $('input[name="dates"]').daterangepicker({
        // startDate: start,
        // endDate: end,
        startDate: "<?php echo isset($p_start_date) ? $p_start_date : date('m/d/Y');?>",
        endDate: "<?php echo isset($p_end_date) ? $p_end_date : date('m/d/Y');?>",
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    
    

});
      
      
      
    });


    $(document).on('change', '#report_by', function(event) {
    event.preventDefault();
    var reportdata_url = "{{route('salesreportgetreportdata')}}";
    
    reportdata_url = reportdata_url.replace(/&amp;/g, '&');

    var report_id = $(this).val();

    if($(this).val() == ''){
      $('#div_report_data').hide();
      // alert('Please Select Report');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Report", 
        callback: function(){}
      });
      $("div#divLoading").removeClass('show');
      return false;
    }

    $("div#divLoading").addClass('show');

    $.ajax({
        url : reportdata_url+'&report_by='+report_id,
        type : 'GET',
    }).done(function(response){
      
      var  response = $.parseJSON(response); //decode the response array

      if( response.code == 1 ) {//success
        $('#report_data').empty();
        var filtered_options = '';

        if(report_id == 1){
          filtered_options += '<option value="">Please Select Category</option>';
        }else if(report_id == 2){
          filtered_options += '<option value="">Please Select Department</option>';
        }else{
          filtered_options += '<option value="">Please Select Item Group</option>';
        }

        filtered_options += '<option value="ALL">All</option>';

        $.each(response.data, function(i, v) {
            filtered_options += '<option value="' + v.id + '">' + v.name + '</option>';
        });
        $('#report_data').append(filtered_options);
        if(report_id == 1){
          $('#report_data').select2({
            placeholder: "Please Select Category"
          });
        }else if(report_id == 2){
          $('#report_data').select2({
            placeholder: "Please Select Department"
          });
        }

        $('#report_data').next('span').css('width','100%');
        $('#report_data').next('span').find('input.select2-search__field').css('width','100%');
        
        $('#div_report_data').show();
        $("div#divLoading").removeClass('show');
       
        return false;
      }else if(response.code == 0){
        // alert('Something Went Wrong!!!');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Something Went Wrong!!!", 
          callback: function(){}
        });
        $("div#divLoading").removeClass('show');
        return false;
      }
      
    });
  });

$(document).on('submit', '#filter_form', function(event) {
  
  if($('#report_by').val() == ''){
    // alert('Please Select Report');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select Report", 
      callback: function(){}
    });
    return false;
  }

  if($('#report_data').val() == ''){
    if($('#report_by').val() == '1'){
      // alert('Please Select Category');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Category", 
        callback: function(){}
      });
      return false;
    }else if($('#report_by').val() == '2'){
      // alert('Please Select Department');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Department", 
        callback: function(){}
      });
      return false;
    }else{
      // alert('Please Select Item Group');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Item Group", 
        callback: function(){}
      });
      return false;
    }
  }

  if($('#dates').val() == ''){
    // alert('Please Select Start Date');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select Start Date", 
      callback: function(){}
    });
    return false;
  }

  if($('#dates').val() == ''){
    // alert('Please Select End Date');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select End Date", 
      callback: function(){}
    });
    return false;
  }

//  if($('input[name="dates"]').val() != ''){

//     var d1 = Date.parse($('input[name="start_date"]').val());
//     var d2 = Date.parse($('input[name="end_date"]').val()); 

//     if(d1 > d2){
//       bootbox.alert({ 
//         size: 'small',
//         title: "Attention", 
//         message: "Start date must be less then end date!", 
//         callback: function(){}
//       });
//     return false;
//     }
//   }
  
  
    var dates  = $("#dates").val();
    
    dates_array =dates.split("-");
    start_date = dates_array[0];
    end_date = dates_array[1];   
  
    //format the start date to month-date-year
    var formattedStartDate = new Date(start_date);
    var d = formattedStartDate.getDate();
    var m =  formattedStartDate.getMonth();
    m += 1;  // JavaScript months are 0-11
    m = ('0'+m).slice(-2);
    
    var y = formattedStartDate.getFullYear();
    
    var formattedStartDate = new Date(end_date);
    var ed = formattedStartDate.getDate();
    var em =  formattedStartDate.getMonth();
    em += 1;  // JavaScript months are 0-11
    em = ('0'+m).slice(-2);
    
    var ey = formattedStartDate.getFullYear();
  
    $('input[name="start_date"]').val(m+'-'+d+'-'+y);
    
    
    // $('input[name="end_date"]').val(end_date);
    $('input[name="end_date"]').val(em+'-'+ed+'-'+ey);
    
     var formattedendDate = new Date(end_date);
    var de = formattedendDate.getDate();
    var me =  formattedendDate.getMonth();
    me += 1;  // JavaScript months are 0-11
    me = ('0'+me).slice(-2);
    
    var ye = formattedStartDate.getFullYear();
    
    $('input[name="end_date"]').val(me+'-'+de+'-'+ye);
     console.log(start_date);    
  $("div#divLoading").addClass('show');
  
});

$(document).ready(function() {
  $("#btnPrint").printPage();
});
$(window).load(function() {
    $("div#divLoading").removeClass('show');
  });

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

  $(document).on("click", "#csv_export_btn", function (event) {

    event.preventDefault();

    $("div#divLoading").addClass('show');

      var csv_export_url = "{{route('salesreportcsv_export')}}";
    
      csv_export_url = csv_export_url.replace(/&amp;/g, '&');

      $.ajax({
        url : csv_export_url,
        type : 'GET',
      }).done(function(response){
        
        const data = response,
        fileName = "sales-report.csv";

        saveData(data, fileName);
        $("div#divLoading").removeClass('show');
        
      });
    
  });

  $(document).on("click", "#pdf_export_btn", function (event) {

    event.preventDefault();

    $("div#divLoading").addClass('show');

    var pdf_export_url = "{{route('salesreportpdf_save_page')}}";
  
    pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

    var req = new XMLHttpRequest();
    req.open("GET", pdf_export_url, true);
    req.responseType = "blob";
    req.onreadystatechange = function () {
      if (req.readyState === 4 && req.status === 200) {

        if (typeof window.navigator.msSaveBlob === 'function') {
          window.navigator.msSaveBlob(req.response, "Sales-Report.pdf");
        } else {
          var blob = req.response;
          var link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = "Sales-Report.pdf";

          // append the link to the document body

          document.body.appendChild(link);

          link.click();
        }
      }
      $("div#divLoading").removeClass('show');
    };
    req.send();
    
  });

//   $(document).on('change', 'input[name="start_date"],input[name="end_date"]', function(event) {
//     event.preventDefault();

//     if($('input[name="start_date"]').val() != '' && $('input[name="end_date"]').val() != ''){

//       var d1 = Date.parse($('input[name="start_date"]').val());
//       var d2 = Date.parse($('input[name="end_date"]').val()); 

//       if(d1 > d2){
//         bootbox.alert({ 
//           size: 'small',
//           title: "Attention", 
//           message: "Start date must be less then end date!", 
//           callback: function(){}
//         });
//       return false;
//       }
//     }
//   });
  $('.table').fixedHeader({
    topOffset: 0
});
</script>
<script>
  function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");
    
    for (var i = 0; i < rows.length-5; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
        csv.push(row.join(","));        
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}  

function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}
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
</style>

@endsection