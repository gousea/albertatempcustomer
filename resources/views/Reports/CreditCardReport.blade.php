@extends('layouts.master')

@section('title', 'Credit Card Report')
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
                <h3 class="panel-title"><i class="fa fa-list"></i>Credit Card Report</h3>
            </div>
               <?php if(isset($reports) && count($reports) > 0){ ?>
                    <div class="row" style="padding-bottom: 10px;float: right;">
                        <div class="col-md-12">
                            <a id="pdf_export_btn" href="" class="" style="margin-right:10px;">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                            </a>
                            <a  id="btnPrint" href="{{route('ccprint')}}" class="" style="margin-right:10px;">
                                <i class="fa fa-print" aria-hidden="true"></i> Print
                            </a>
                            <a id="csv_export_btn" href="" class="" style="margin-right:10px;">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV
                            </a>
                        </div>
                    </div>
                    <br>
                <?php } ?>
            <div class="panel-body">
        <div class="row" style="margin: 10px;">
          <form method="GET" id="filter_form"  action="{{ route('CardReportForm') }}">
            <div class="col-md-2">
                <input type='text' class="form-control" name="dates" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="dates" placeholder="Select Date Range" autocomplete="off" readonly/>
                <input type='hidden' class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly/>                
            </div>
          <!--  <div class="col-md-2"> -->
              <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" autocomplete="off">
          <!--  </div> -->
            <div class="col-md-2">
              <input type="text" class="form-control" name="credit_card_number" value="<?php echo isset($credit_card_number) ? $credit_card_number : ''; ?>" id="credit_card_number" maxlength="4" placeholder="Credit Card Number" autocomplete="off" >
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" name="credit_card_amount" value="<?php echo isset($credit_card_amount) ? $credit_card_amount : ''; ?>" id="credit_card_amount" placeholder="Credit Card Amount" autocomplete="off">
            </div>
            <div class="col-md-2">
              <input type="submit" class="btn btn-success" value="Generate">
            </div>
          </form>
        </div>
        <?php if(isset($reports) && count($reports) > 0){ ?>
            <div class="row" style="margin: 10px;">
           <div class="row" style="margin: 10px;">
          <div class="col-md-12">
            <p>From: <?php echo $p_start_date; ?> To <?php echo $p_end_date; ?></p>
          </div>
        </div>
        
        <div class="row" style="margin: 10px;">
          <div class="col-md-12">
            <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
          </div>
          <div class="col-md-12">
            <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
          </div>
          <div class="col-md-12">
            <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
          </div>
        </div>
          <div class="col-md-12 table-responsive"style="margin: 10px;">
          <br>
          <?php 
                  $grand_total_transaction_number= 0;
                  $grand_total_nauthamount= 0;

                ?>
          <?php foreach($reports as $report){ ?>
                  <?php  $grand_total_transaction_number = $grand_total_transaction_number + $report->transaction_number;
                  $grand_total_nauthamount = $grand_total_nauthamount + $report->nauthamount; ?>
                  
          <?php } ?>          
          
            <table class="table table-striped table-hover" style="width:100%;">
              
                <thead>
                <tr>
                <th style="width: 50%;">Card Type</th>
                <th class="text-right" style="width: 30%;">Transactions</th>
                <th class="text-right" style="width: 20%;">Amount</th>
                </tr>
                
                <tr>
                <th style="width: 50%;">Grand Total</th>
                <th class="text-right" style="width: 30%;"><?php echo $grand_total_transaction_number;?></th>
                <th class="text-right" style="width: 20%;"><?php echo number_format((float)$grand_total_nauthamount, 2) ;?></th>
                </tr>
                </thead>
              
              <tbody>
                
                
                <?php foreach($reports as $report){ ?>
                
                   
                  <tr >
                    <td colspan="3" style="padding: 0px;">
                      <table class="table" style="width: 100%;margin-bottom: 0px;">
                        <thead>
                            
                          <tr class="search_header" style="cursor: pointer;" data-cardtype="<?php echo $report->vcardtype;?>">
                            <th class="text-uppercase" style="width: 50%;"><?php echo $report->vcardtype;?> TOTAL</th>
                            <th class="text-right" style="width: 30%;"><?php echo $report->transaction_number;?></th>
                            <th class="text-right" style="width: 20%;">$<?php echo $report->nauthamount;?></th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                      </table>
                    </td>

                  </tr>
                  <?php 

                    
                  ?>
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
@section('scripts')   
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>   -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" ></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>

<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->



<script type="text/javascript">

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
        locale: {
          format: 'M/DD/YYYY'
        },
        
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

$(document).on('submit', '#filter_form', function(event) {



  if($('#dates').val() == ''){
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select the Date Range!", 
      callback: function(){}
    });
    return false;
  }

     var dates  = $("#dates").val();
        dates_array =dates.split("-");
        start_date = dates_array[0];
        end_date = dates_array[1];
     
     
  
  if(start_date != '' && end_date != ''){

    var d1 = Date.parse(start_date);
    var d2 = Date.parse(end_date); 

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
  
  console.log("Start Date: "+start_date+" End Date: "+end_date);
  
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
    
    
    $('input[name="start_date"]').val(m+'-'+d+'-'+y);
    $('input[name="end_date"]').val(me+'-'+de+'-'+ye);
    
  $("div#divLoading").addClass('show');
  
  
  
});
</script>
<script type="text/javascript">
  $(document).on('click', '.search_header', function(event) {
      
  event.preventDefault();
  $(this).toggleClass('expand');

  var current_header = $(this);

  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  var credit_card_number = $('#credit_card_number').val();
  var credit_card_amount = $('#credit_card_amount').val();
  var report_pull_by = $(this).attr('data-cardtype');
  
  var data_post = {start_date:start_date,end_date:end_date,report_pull_by:report_pull_by,credit_card_number:credit_card_number,credit_card_amount:credit_card_amount};

  data_post= JSON.stringify(data_post);

  var reportdata_url = '<?php echo route('Cardvalue'); ?>';
    reportdata_url  = reportdata_url.replace(/&amp;/g, '&');

  var print_receipt_url = '<?php echo route('Cardprint'); ?>';
  print_receipt_url = print_receipt_url.replace(/&amp;/g, '&');

  if($(this).hasClass('expand')){
    $.ajax({
      url : reportdata_url,
      data : {start_date:start_date,end_date:end_date,report_pull_by:report_pull_by,credit_card_number:credit_card_number,credit_card_amount:credit_card_amount},
      type : 'GET',
      contentType: "application/json",
      dataType: 'json',
      }).done(function(response){
        
        current_header.parent().parent().find('tbody').empty();
        var html = '';
        if(response){

          html += '';
          html += '<tr>';
          html += '<td colspan="3" style="padding: 0px;">';
          html += '<table class="table" style="margin-bottom: 0px;">';
          html += '<thead>';
          html += '<tr>';
          html += '<th>DATE</th>';
          html += '<th>TIME</th>';
          html += '<th class="text-right">LAST FOUR OF CC</th>';
          html += '<th class="text-right">APPROVAL CODE</th>';
          html += '<th class="text-right">AMOUNT</th>';
          html += '<th>CARD TYPE</th>';
          html += '<th>ACTION</th>';
          html += '</tr>';
          html += '</thead>';
          html += '<tbody>';
          $.each(response,function(i,v){
    
            console.log(v);
           var receipt_url = print_receipt_url+'?id='+v.id+'&by=mpstender';

            html += '<tr>';
            html += '<td>';
            html += v.date;
            html += '</td>';
            html += '<td>';
            html += v.time;
            html += '</td>';
            html += '<td class="text-right">';
            html += v.last_four_of_cc;
            html += '</td>';
            html += '<td class="text-right">';
            html += v.approvalcode;
            html += '</td>';
            html += '<td class="text-right">';
            html += v.amount;
            html += '</td>';
            html += '<td>';
            html += v.vcardtype;
            html += '</td>';
            html += '<td>';
            html += '<a href="'+ receipt_url +'" class="btn btn-info printMe"><i class="fa fa-print"></i> Print</a>';
            html += '</td>';
            html += '</tr>';
          });

          html += '</tbody>';
          html += '</table>';
          html += '</td>';
          html += '</tr>';

          current_header.parent().parent().find('tbody').append(html);
          current_header.parent().parent().find('tbody').show();
        }
    });
  }
  
  if(!$(this).hasClass('expand')){
    $(this).parent().parent().find('tbody').empty();
  }

});

  $(document).on('click', '.printMe', function(event) {
    event.preventDefault();
    
    var href = $(this).attr('href');
    

    $.ajax({
        url : href,
        type : 'GET',
    }).done(function(response){
      
        $('#printme_modal').html(response);
        $('#popupbtnPrint').attr('data-href',href);
        
        $('#view_salesdetail_model').modal('show');
         
    });
   
  });

   $(document).on('click', '#popupbtnPrint', function(event) {
     event.preventDefault();
    
     var href = $(this).attr('data-href');
    
     $('#btnPrint').attr('href', href);

     $('#btnPrint').trigger('click');
   });
</script>
// <script>
//  $(document).on('click', '#popupbtnPrint', function(e){
//         e.preventDefault();
//         $("div#divLoading").addClass('show');
//         $.ajax({
//                 type: 'GET',
//                 url: '/cardreport/printview',
//                 // data: formData,
//                 dataType: 'html',
//                 success: function (reponse) {
//                     $("div#divLoading").removeClass('show');
                    
//                     var originalContents = document.body.innerHTML;

//                     document.body.innerHTML = reponse;

//                     window.print();

//                     document.body.innerHTML = originalContents;
//                 },
//                 error: function (data) {
//                     $("div#divLoading").removeClass('show');

                   
//                 }
//             });
//     });

// </script>
<!-- Modal -->
  <div class="modal fade" id="view_salesdetail_model" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">        
        <div class="modal-body" id="printme_modal">          
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" id="closeprint" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="popupbtnPrint">Print</button>
      </div>
      </div>
    </div>
  </div>
// <script>
//  $(document).on('click', '#closeprint', function(event) {
//     event.preventDefault();
//     console.log("test_close");
//     //$('#view_salesdetail_model').removeData();
//     $('#view_salesdetail_model').modal('hide');

// });
// </script>
<script>  
$(document).ready(function() {
  $("#btnPrint").printPage();
  
});
</script>
<a href="" id="btnPrint" style="display: hidden;"></a>
<script type="text/javascript">
   $(document).on('keypress keyup blur', 'input[name="credit_card_number"]', function(event) {

    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    
  });

  $(document).on('keypress keyup blur', 'input[name="credit_card_amount"]', function(event) {

    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
    
  });  
</script>
<script>  
$(document).ready(function() {
  $("#btnPrint").printPage();
  
});
</script>
<a href="" id="btnPrint" style="display: hidden;"></a>
<script type="text/javascript">
   $(document).on('keypress keyup blur', 'input[name="credit_card_number"]', function(event) {

    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    
  });

  $(document).on('keypress keyup blur', 'input[name="credit_card_amount"]', function(event) {

    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
    
  });  
</script>
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
 $(document).on("click", "#csv_export_btn", function (event) {

        event.preventDefault();

        // $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('cccsv'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "cccsv.csv";

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

    $(document).on("click", "#pdf_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('ccpdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "Credit_Card_Report.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "Credit_Card_Report.pdf";

              // append the link to the document body

              document.body.appendChild(link);

              link.click();
               
            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();
        
    });
</script> 
@endsection
