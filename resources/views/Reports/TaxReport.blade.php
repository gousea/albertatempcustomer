@extends('layouts.layout')
@section('title')
Tax Collection Summary
@endsection
@section('main-content')
<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> TAX REPORT</span>
                </div>
                 <div class="nav-submenu">
                         <?php if(isset($reports) && count($reports) > 0){ ?>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu " href="/taxreport/print" id="btnPrint">PRINT
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
    <form method="post" action="{{ route('TaxReportForm') }}"  class="form-inline" style="padding-left:40px"id="filter_form">
                        @csrf
                      
                            <?php
                            $today_start = strtotime(date('d-m-Y'));
                            $today_end = strtotime(date('d-m-Y'));
                            ?>
            
                            <div class="form-group mx-sm-4 mb-2">
                            <input type="text"  class="form-control rcorner"  style= "width :220px;" name="dates" value="<?php echo isset($p_end_date) ? $p_end_date : date('m-d-Y', $today_end); ?>" id="dates" placeholder="End Date" readonly>
                            </div>
                            
            
                            <div class="form-group col-md-0 ">
                            <input type="hidden" class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : date('m-d-Y', $today_start); ?>" id="start_date" placeholder="Start Date" readonly>
                            </div>
            
                            <div class="form-group  col-md-0">
                            <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : date('m-d-Y', $today_end); ?>" id="end_date" placeholder="End Date" readonly>
                            </div>
                            
                            <div class="form-group mx-sm-3 mb-2 ">
                            <input type="submit" class="btn btn-success rcorner header-color"  value="Generate">
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
                        <h6><span>TAX REPORT </span></h6>
            </div>             
    </div>         
            
                       
                        <?php if(isset($reports) && count($reports) > 0){ ?>
                            
                           <div style="padding-left:40px;padding-right:40px">
                                <div class="col-md-12">
                                    <p>From: <?php echo $p_start_date; ?> To <?php echo $p_end_date; ?></p>
                                </div>
                            
                           
                                <div class="col-md-12">
                                    <p>Store Address: <?php echo $store[0]->vaddress1 ?></p>
                                </div>
                                
                                <div class="col-md-12">
                                    <p>Store Name: {{ session()->get('storeName') }}</p>
                                </div>
                                
                                <div class="col-md-12">
                                    <p>Store Phone:<?php echo $store[0]->vphone1; ?></p>
                                </div>
                           
                            
                            </div>
                            
                            <div style="padding-left:12px;padding-right:40px">
                            
                            <div class="row" style="margin-left: 2%;">
                                <div class="col-md-6 col-sm-6">
                                 <p><p style="float: left;">Non-Taxable Sales</p>
                                    <span style="float: right;"><?php echo "$",number_format((float)$reports['NONTAX'], 2) ; ?></span></p>
                                </div>
                            </div>
                            <div class="row" style="margin-left: 2%;">
                                <div class="col-md-6 col-sm-6">
                                    <p><p style="float: left;">  Taxable Sales (Tax1)</p>
                                    <span style="float: right;"><?php echo "$",number_format((float)$reports['Tax1Sales'], 2) ; ?></span></p>
                                </div>
                            </div>
                            <div class="row" style="margin-left: 2%;">
                                <div class="col-md-6 col-sm-6">
                                    <p><p style="float: left;"> Taxable Sales (Tax2)</p>
                                    <span style="float: right;"><?php echo  "$",number_format((float)$reports['Tax2Sales'], 2) ; ?></span></p>
                                </div>
                            </div>
                            <?php  if(isset($reports['Tax3Sales'])){ ?>
                                <div class="row" style="margin-left: 2%;">
                                    <div class="col-md-6 col-sm-6">
                                        <p><p style="float: left;"> Taxable Sales (Tax3)</p>
                                        <span style="float: right;"><?php echo  "$",number_format((float)$reports['Tax3Sales'], 2) ; ?></span></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row" style="margin-left: 2%;">
                                <div class="col-md-6 col-sm-6 total_col">
                                    <b> <p><p style="float: left;"> Total Taxable Sales </p>
                                    <span style="float: right;"><?php echo "$",number_format((float)$reports['Tax1Sales'] + (float)$reports['Tax2Sales'] + (float)$reports['Tax3Sales'], 2) ; ?></span></p></b>
                                     <br><hr style="border-top: 2px solid #ccc;">
                                </div>
                            </div>
                           
                               
                            
                            <div class="row" style="margin-left: 2%;margin-top: -16px;">
                                <div class="col-md-6 col-sm-6">
                                    <p><p style="float: left;"><b>Net Sales</b></p>
                                    <span style="float: right;"><b><?php echo "$",$netsale = number_format($reports['Tax1Sales']+$reports['Tax2Sales']+$reports['Tax3Sales']+$reports['NONTAX'], 2) ; ?></b></span></p>
                                </div>
                            </div>
                            <div class="row" style="margin-left: 2%;">
                                <div class="col-md-6 col-sm-6">
                                    <p><p style="float: left;">Tax 1</p>
                                    <span style="float: right;"><?php echo "$",number_format((float)$reports['tax1'], 2) ; ?></span></p>
                                </div>
                            </div>
                            <div class="row" style="margin-left: 2%;">
                                <div class="col-md-6 col-sm-6">
                                    <p><p style="float: left;">Tax 2</p>
                                    <span style="float: right;"><?php echo "$",number_format((float)$reports['tax2'], 2); ?></span></p>
                                </div>
                            </div>
                            <?php  if(isset($reports['tax3'])){ ?> 
                                <div class="row" style="margin-left: 2%;">
                                    <div class="col-md-6 col-sm-6">
                                        <p><p style="float: left;">Tax 3</p>
                                        <span style="float: right;"><?php echo "$",number_format((float)$reports['tax3'], 2); ?></span></p> 
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row" style="margin-left: 2%;">
                                <div class="col-md-6 col-sm-6 total_col">
                                    <p style="float: left;"><b>Total Tax</p>
                                    <span style="float: right;"><?php echo "$",number_format((float)$reports['TAX'], 2) ; ?></span></p>
                                    </b>
                                </div>
                            </div>
                            <div class="row" style="margin-left: 2%;margin-top: -16px;">
                                <div class="col-md-6 col-sm-12">
                                    <hr style="border-top: 2px solid #ccc;">
                                </div>
                            </div>
                            <?php if(isset($reports['LiabilitySales']) && $reports['LiabilitySales']!=0) { ?>
                            <div class="row" style="margin-left: 2%;">
                                <div class="col-md-6 col-sm-6">
                                    <p><b>LiabilitySales <span style="float: right;"><?php echo "$",number_format((float)$reports['LiabilitySales'], 2) ; ?></span></b></p>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="row" style="margin-left: 2%;">
                                <div class="col-md-6 col-sm-6">
                                    <p><b>Gross Sales <span style="float: right;"><?php echo "$",number_format((float)$reports['NNETTOTAL'], 2) ; ?></span></b></p>
                                </div>
                            </div>
                            <br><br>
                            
                            
                            
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

</section>
@endsection
@section('page-script')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<script>
$(document).ready(function() {
  $("#btnPrint").printPage();
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


</script>
<script>
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
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           
        }
    }, cb);

    cb(start, end);
    
    

});
      
      
      
    });

  
  
  
  
  
</script>

<script type="text/javascript">
  $(document).ready(function() {
    // $('#report_by').select2({
    //   placeholder: "Please Select Department"
    // });
  });

  $(document).on('change', '#report_by', function(event) {
    event.preventDefault();
    
    if($(this).val() == 'today'){
      var start_date = moment().format('MM-DD-YYYY');
      var end_date = moment().format('MM-DD-YYYY');
    //   var end_date   = moment().subtract('days', 1).format('MM-DD-YYYY');

      $('#start_date').val(start_date);
      $('#end_date').val(end_date);
     
    }
    else if($(this).val() == 'yesterday'){
      var start_date =  moment().subtract('days', 1).format('MM-DD-YYYY');
      var end_date   = moment().subtract('days', 1).format('MM-DD-YYYY');

      $('#start_date').val(start_date);
      $('#end_date').val(end_date);
     
    }
    else if($(this).val() == 'cweek'){
      var start_date = moment().startOf('isoweek').format('MM-DD-YYYY');
      var end_date   = moment().startOf('week').add('days', 7).format('MM-DD-YYYY');

      $('#start_date').val(start_date);
      $('#end_date').val(end_date);
     
    }else if($(this).val() == 'pweek'){
      
      var start_date = moment().subtract(1, 'weeks').startOf('isoweek').format('MM-DD-YYYY');
      var end_date   = moment().subtract(1, 'weeks').startOf('week').add('days', 7).format('MM-DD-YYYY');

      $('#start_date').val(start_date);
      $('#end_date').val(end_date);

    }else if($(this).val() == 'cmonth'){
      var start_date = moment().startOf('month').format('MM-DD-YYYY');
      var end_date   = moment().endOf('month').format('MM-DD-YYYY');

      $('#start_date').val(start_date);
      $('#end_date').val(end_date);

    }else if($(this).val() == 'pmonth'){
      var start_date = moment().subtract(1, 'months').startOf('month').format('MM-DD-YYYY');
      var end_date   = moment().subtract(1, 'months').endOf('month').format('MM-DD-YYYY');

      $('#start_date').val(start_date);
      $('#end_date').val(end_date);

    }else{
      var start_date = moment().startOf('isoweek').format('MM-DD-YYYY');
      var end_date   = moment().startOf('week').add('days', 7).format('MM-DD-YYYY');

      $('#start_date').val(start_date);
      $('#end_date').val(end_date);
    }

  });

$(document).on('submit', '#filter_form', function(event) {

  if($('#start_date').val() == ''){
    // alert('Please Select Start Date');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select Start Date", 
      callback: function(){}
    });
    return false;
  }

  if($('#end_date').val() == ''){
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


<script>  

    $(document).on("click", "#pdf_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('Taxpdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "Tax Report.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "Tax Report.pdf";

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
    //             url: '/taxreport/print',
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

          var csv_export_url = '<?php echo route('Taxcsv'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "TAX_Report.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });

</script>
<style>
.rcorner {
  border-radius:9px;
}
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
.total_col{
    color:#286fb7;
}
</style>

@endsection   