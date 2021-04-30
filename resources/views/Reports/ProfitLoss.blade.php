@extends('layouts.master')

@section('title', 'Profit & Loss Report')
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
                <h3 class="panel-title"><i class="fa fa-list"></i>Profit & Loss Report</h3>
            </div>
            <?php if(isset($reports) && count($reports) > 0){ ?>
                <div class="row" style="padding-bottom: 10px;float: right;">
                            <div class="col-md-12">
                            
                                <a id="pdf_export_btn" href="" class="" style="margin-right:10px;">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                                </a>
                                <a  id="btnPrint" href="{{route('Profitprint')}}" class="" style="margin-right:10px;">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                <a id="csv_export_btn" href="" class="" style="margin-right:10px;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV
                                </a>
                        
                            </div>
                        </div>
            <?php } ?>
            <div class="panel-body">
                <div class="clearfix"></div>
                <div class="row" style="margin: 10px;">
                    <form method="post" id="filter_form" action="{{ route('ProfitForm') }}">
              @csrf
                <div class="col-md-3">
                  <select name="report_by" class="form-control" id="report_by" required>
                    <option value="">Please Select Report</option>
                    <?php foreach ($byreports as $key => $value){ ?>
                      <?php if(isset($selected_report_by) && ($selected_report_by == $value)){ ?>
                      <option value="<?php echo $value; ?>" selected="selected"><?php echo $value; ?></option>
                      <?php }else{ ?>
                      <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <input type="text" class="form-control" name="dates" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="dates" placeholder="Start Date" readonly>
                </div>
              
                <div class="col-md-0">
                  <input type="hidden" class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly>
                </div>
                <div class="col-md-0">
                  <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" readonly>
                </div>
                <div class="col-md-2">
                  <input type="submit" class="btn btn-success" value="Generate">
                </div>
              </form>
                </div>
                <?php if(isset($reports) && count($reports) > 0){ ?>
                    <div class="row" style="margin: 10px;">
                    <div class="col-md-12">
                    <p><b>Date Range: </b><?php echo $p_start_date; ?> to <?php echo $p_end_date; ?></p>
                       <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
                       <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
                       <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
                    </div>
                </div>
                    <div class="row">
                        
                        <div class="table-responsive">
                            
                          
                          <div class="col-md-12">
                              
                              <div class="col-md-10">
                              
                                  <table class="table table-hover table_display">
                                      <thead class='header' id="table_header">
                                      <tr>
                                          <th>Name</th>
                                          <th class='text-right'>Unit Cost</th>
                                          <th class='text-right'>Selling Price</th>
                                          <th class='text-right'>Qty Sold</th>
                                          <th class='text-right'>Total Cost</th>
                                          <th class='text-right'>Total Price</th>
                                          <th class='text-right'>Mark Up(%)</th>
                                          <th class='text-right'>Gross Profit</th>
                                          <th class='text-right'>Gross Profit(%)</th>
                                      </tr>
                                      </thead>
                                      <?php 
                                      
                                      $total_qty_sold1=$total_total_cost1=$total_total_price1=$total_markup1=$total_gross_profit1=$total_gross_profit_percentage=0;
                                      ?>
                                      <tr>
                                          <th> Total:</th>
                                          <th></th>
                                          <th></th>
                                          <th class='text-right' id="top_total_qty"> <?php echo $total_qty_sold1 ; ?> </th>
                                          <th class='text-right' id="top_total_cost"> <?php echo "$",number_format($total_total_cost1,2); ?> </th>
                                          <th class='text-right' id="top_total_price"> <?php echo "$",number_format($total_total_price1,2); ?> </th>
                                          <th class='text-right' id="top_total_markup"> <?php echo number_format($total_markup1,2),"%"; ?></th>
                                          <th class='text-right' id="top_total_geoss_profit"> <?php echo "$",number_format($total_gross_profit1,2); ?> </th>
                                          <th class='text-right' id="top_total_geoss_profit_percent"> <?php echo number_format($total_gross_profit_percentage,2),"%"; ?> </th>
                                      </tr>
                                      
                                      <?php $i=0;?>
                                      <?php foreach($out as $r) { ?>
                                          
                                          <?php $i++;?>
                                          <tr class="first_row" id="child_<?php echo $i;?>">
                                              <th><?php echo isset($r['vname']) ? $r['vname']: ''; ?></th>
                                              <th></th>
                                              <th></th>
                                              <th></th>
                                              <th></th>
                                              <th></th>
                                              <th></th>
                                              <th></th>
                                              <th></th>
                                          </tr>
                                          <?php $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = $counter =$subtotalgrossprofit= 0;?>
                                          <?php foreach($r['details'] as $itmes) { ?>
                                              <?php $total_qty_sold += $itmes['TotalQty']; $total_total_cost += $itmes['TotCostPrice']; $total_total_price += $itmes['TOTUNITPRICE']; $total_markup += $itmes['AmountPer']; $total_gross_profit += $itmes['Amount']
                                                ;$subtotalgrossprofit +=$itmes['TOTUNITPRICE']-$itmes['TotCostPrice']; ?>
                                              <tr>
                                                  <td style="display:none;" class="child_<?php echo $i;?>"><?php echo isset($itmes['vITemName']) ? $itmes['vITemName']: ''; ?></td>
                                                  <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo "$",isset($itmes['DCOSTPRICE']) ? number_format($itmes['DCOSTPRICE'],2): ''; ?></td>
                                                  <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo "$",isset($itmes['dUnitPrice']) ?  number_format($itmes['dUnitPrice'],2): ''; ?></td>
                                                  <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo isset($itmes['TotalQty']) ? $itmes['TotalQty']: ''; ?></td>
                                                  <!-- <?php $Total_Cost=$itmes['DCOSTPRICE']*$itmes['TotalQty'];?>
                                                   <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo "$",number_format($Total_Cost,2); ?></td>
                                                  <?php $Total_pice=($itmes['dUnitPrice']) *$itmes['TotalQty'];?>
                                                  <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo "$",number_format($Total_pice,2); ?></td>
                                                  -->
                                                  <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo isset($itmes['TotCostPrice']) ? $itmes['TotCostPrice']: ''; ?>
                                                  <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo "$", isset($itmes['TOTUNITPRICE']) ? number_format($itmes['TOTUNITPRICE'],2): ''; ?></td>
                                              
                                                  <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo isset($itmes['AmountPer']) ? number_format($itmes['AmountPer'],2): '',"%"; ?></td>
                                                  <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo "$",isset($itmes['Amount']) ? number_format($itmes['Amount'],2): ''; ?></td>
                                                 
                                                 <?php if($itmes['TOTUNITPRICE']!=0){?>
                                                  <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo number_format(($itmes['Amount']/$itmes['TOTUNITPRICE'])*100,2),"%"; ?></td>
                                                 <?php } else {?>
                                                     <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php //echo number_format(($itmes['Amount']/$itmes['TOTUNITPRICE'])*100,2),"%"; ?></td>
                                                 <?php } ?>
                                                
                                                 </tr>
                                              
                                          <?php } ?>
                                          <tr> 
                                              <th> Sub Total:</th>
                                              <th></th>
                                              <th></th>
                                              <th class='text-right'> <?php echo $total_qty_sold ;?> </th>
                                              <th class='text-right'> <?php echo "$",number_format($total_total_cost,2); ?> </th>
                                              <th class='text-right'> <?php echo "$",number_format($total_total_price,2); ?> </th>
                                              <!--<th class='text-right'> <?php echo number_format($total_markup,2); ?> </th>-->
                                              <?php if($total_total_cost != '0.00'){
                                              $total_markup = (($subtotalgrossprofit/$total_total_cost) * 100);
                                              }
                                              else{
                                              $total_markup=100.00;
                                              }?>
                                             
                                              <th class='text-right'> <?php echo number_format($total_markup,2),"%"; ?></th>
                                              <th class='text-right'> <?php echo "$",number_format($subtotalgrossprofit,2); ?> </th>
                                              <?php if($total_total_price!=0){ ?>
                                              <th class='text-right'> <?php echo number_format(($subtotalgrossprofit/$total_total_price)*100,2),"%"; ?> </th>
                                              <?php } else {?> 
                                              <th class='text-right'> <?php echo number_format((0)*100,2),"%"; ?> </th>
                                              <?php } ?>
                                              
                                             <?php
                                             $total_qty_sold1+=$total_qty_sold;
                                             $total_total_cost1+=$total_total_cost;
                                             $total_total_price1+=$total_total_price;
                                             
                                             $total_gross_profit1+=$subtotalgrossprofit;
                                             if($total_total_cost1 !=0 &&  $total_total_cost1 !=0)
                                             {
                                                $total_markup1=(($total_gross_profit1)/$total_total_cost1)*100;
                                             }
                                             else{
                                                $total_markup1=0;
                                             }
                                             if($total_gross_profit1 !=0 &&  $total_total_price1 !=0)
                                             {
                                             $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
                                             }
                                             else{
                                                $total_gross_profit_percentage=0;
                                             }
                                             ?>
                                      <?php } ?>
                                      </tr>
                                      <tr style="display:none;">
                                      <br>
                                      <br>
                                      
                                          <th> Total:</th>
                                          <th></th>
                                          <th></th>
                                          <th class='text-right' id="bottom_total_qty"> <?php echo $total_qty_sold1 ; ?> </th>
                                          <th class='text-right' id="bottom_total_cost"> <?php echo "$",number_format($total_total_cost1,2); ?> </th>
                                          <th class='text-right' id="bottom_total_price"> <?php echo "$",number_format($total_total_price1,2); ?> </th>
                                          <th class='text-right' id="bottom_total_markup"> <?php echo number_format($total_markup1,2),"%"; ?></th>
                                          <th class='text-right' id="bottom_total_geoss_profit"> <?php echo "$",number_format($total_gross_profit1,2); ?> </th>
                                          <th class='text-right' id="bottom_total_geoss_profit_percent"> <?php echo number_format($total_gross_profit_percentage,2),"%"; ?> </th>
                                      </tr>
                                  </table>
                              </div>
                          </div>
                          
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
@section('scripts')  
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> 
<script type="text/javascript" src="{{ asset('javascript/table-fixed-header.js') }}"></script>    
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<script>
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
        
        
    }

    $('input[name="dates"]').daterangepicker({
       
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

</script>
<script>
$('.first_row').click(function(){
    var trclass = $(this).attr('id');
    $("."+trclass).toggle();
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

  if($('#report_by').val() == '1'){
      if($('#report_data > option:selected').length == 0){
        // alert('Please Select Category');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please Select Category", 
          callback: function(){}
        });
        return false;
      }
  }else if($('#report_by').val() == '2'){
    if($('#report_data > option:selected').length == 0){
       // alert('Please Select Department');
       bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please Select Department", 
          callback: function(){}
        });
      return false;
    }
  }else{
    
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
    //   bootbox.alert({ 
    //     size: 'small',
    //     title: "Attention", 
    //     message: "Please Select Item Group", 
    //     callback: function(){}
    //   });
    //   return false;
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

  if($('input[name="dates"]').val() != ''){

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
  
    $('input[name="start_date"]').val(m+'-'+d+'-'+y);
    
    
    $('input[name="end_date"]').val(end_date);
    
     var formattedendDate = new Date(end_date);
    var de = formattedendDate.getDate();
    var me =  formattedendDate.getMonth();
    me += 1;  // JavaScript months are 0-11
    me = ('0'+me).slice(-2);
    
    var ye = formattedendDate.getFullYear();
    
    $('input[name="end_date"]').val(me+'-'+de+'-'+ye);
  
  $("div#divLoading").addClass('show');
  
});
</script>

<!-- <script src="view/javascript/table-fixed-header.js" ></script> -->
<script>

$(document).ready(function(){
    
    var bottom_total_qty = $('#bottom_total_qty').text();
    var bottom_total_cost = $('#bottom_total_cost').text();
    var bottom_total_price = $('#bottom_total_price').text();
    var bottom_total_markup = $('#bottom_total_markup').text();
    var bottom_total_geoss_profit = $('#bottom_total_geoss_profit').text();
    var bottom_total_geoss_profit_percent = $('#bottom_total_geoss_profit_percent').text();
    
    $('#top_total_qty').text(bottom_total_qty);
    $('#top_total_cost').text(bottom_total_cost);
    $('#top_total_price').text(bottom_total_price);
    $('#top_total_markup').text(bottom_total_markup);
    $('#top_total_geoss_profit').text(bottom_total_geoss_profit);
    $('#top_total_geoss_profit_percent').text(bottom_total_geoss_profit_percent);
    
});


</script>
<style type="text/css">
  tr.first_row{
    cursor:pointer;
  }

  tr.first_row > th{
    background-color: #585858;
    color: #fff;
    /*border: 1px solid #808080 !important;*/
  }

  tr.header > th, tr.header > th > span{
    font-size: 15px;
  }

  tr.header > th > span{
    float: right;
  }

  .header .sign:after{
    content:"+";
    display:inline-block;      
  }
  .header.expand .sign:after{
    content:"-";
  }

  tr.add_space th {
    border: none !important;
    padding: 2px !important;
  }
</style>
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


</script>
<script>  

    $(document).on("click", "#pdf_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('Profitpdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "Profit_Report.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "Profit_Report.pdf";

              // append the link to the document body

              document.body.appendChild(link);

              link.click();
               
            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();
        
    });

    // $(document).on('click', '#btnPrint', function(e){
    //     e.preventDefault();
    //     $("div#divLoading").addClass('show');
    //     $.ajax({
    //             type: 'GET',
    //             url: '/profitreport/print',
    //             // data: formData,
    //             dataType: 'html',
    //             success: function (reponse) {
    //                 $("div#divLoading").removeClass('show');

    //                 var originalContents = document.body.innerHTML;

    //                 document.body.innerHTML = reponse;

    //                 window.print();

    //                 document.body.innerHTML = originalContents;
    //             },
    //             error: function (data) {
    //                 $("div#divLoading").removeClass('show');

    //                 console.log('Error:', data);
    //             }
    //         });
    // });


    $(document).on("click", "#csv_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('Profitcsv'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "Profit_Report.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });

</script>

<script>  
$(document).ready(function() {
  $("#btnPrint").printPage();
});
</script> 
<script>
     $(document).ready(function(){
        $('.table').fixedHeader({
          topOffset: 0
        }); 
  });
</script>
@endsection