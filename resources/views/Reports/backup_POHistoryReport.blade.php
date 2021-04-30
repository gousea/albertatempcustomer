@extends('layouts.master')

@section('title', 'PO History Report')
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
                <h3 class="panel-title"><i class="fa fa-list"></i>PO History Report</h3>
            </div>
            <div class="panel-body">
                <?php if(isset($reports) && count($reports) > 0){ ?>
                    <div class="row" style="padding-bottom: 10px;float: right;">
                        <div class="col-md-12">
                        
                            <a id="pdf_export_btn" href="" class="" style="margin-right:10px;">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                            </a>
                            <a  id="btnPrint" href="{{route('POprint')}}" class="" style="margin-right:10px;">
                                <i class="fa fa-print" aria-hidden="true"></i> Print
                            </a>
                            <a id="csv_export_btn" href="" class="" style="margin-right:10px;">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV
                            </a>
                    
                        </div>
        </div>
                <?php } ?>
                <div class="row" style="margin: 10px;">
                      <form method="post" id="filter_form" action="{{ route('POForm') }}">
                      @csrf
                        <div class="col-md-3">
                          <select name="report_by[]" class="form-control" id="report_by" multiple="true">
            
                          <?php if(isset($selected_byreports) && count($selected_byreports) > 0){ ?>
                                <option value="">Please Select Vendor</option>
                                <?php if(in_array('ALL', $selected_byreports)){ ?>
                                  <option value="ALL" selected="selected">ALL</option>
                                <?php } else { ?>
                                  <option value="ALL">ALL</option>
                                <?php } ?>
            
                                <?php foreach($byreports as $k => $v){ ?>
                                  <?php $sel_report = false; ?>
                                  <?php foreach($selected_byreports as $ks => $selected_byreport){ ?>
                                    <?php if($selected_byreport == $v->vsuppliercode){ ?>
                                        <option value="<?php echo $v->vsuppliercode; ?>" selected="selected"><?php echo $v->vcompanyname; ?></option>
                                        <?php 
                                          $sel_report = true;
                                          break;
                                        ?>
                                    <?php } ?>
                                  <?php } ?>
                                  <?php if($sel_report == false){ ?>
                                    <option value="<?php echo $v->vsuppliercode; ?>"><?php echo $v->vcompanyname; ?></option>
                                  <?php } ?>
                                <?php } ?>
                                
                          <?php } else { ?>
                            <option value="">Please Select Vendor</option>
                            <option value="ALL">ALL </option>
                              <?php foreach ($byreports as $key => $value){ ?>
                              <option value="<?php echo $value->vsuppliercode; ?>"><?php echo $value->vcompanyname; ?></option>
                              <?php } ?>
                          <?php } ?>
                          </select>
                        </div>
                        <div class="col-md-2">
                          <input type="" class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" required readonly autocomplete="off" >
                        </div>
                        <div class="col-md-2">
                          <input type="" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" required readonly autocomplete="off" >
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
                <div class="row" style="margin: 10px;">    
                  <div class="col-md-12 table-responsive">
                  <br>
                    <table class="table table-bordered table-striped table-hover" style="border:none;width: 50%;">
                      <thead class="header">
                        <tr style="border-top: 1px solid #ddd;">
                          <th>Vendor</th>
                          <th>Date</th>
                          <th class="text-right">Net Total</th>
                          <th>View Item</th>
                          <th class="text-right">RIP Total Amt</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $total_nnettotal = 0;
                            $rip_tot=0;
                          ?>
                          <?php foreach ($reports as $key => $value){ ?>
                          <tr>
                            <td class="text-left"><?php echo $value['vvendorname']; ?></td>
                            <td style="width:90px;" class="text-left"><?php echo $value['dcreatedate']; ?></td>
                            <td class="text-right"><?php echo "$",number_format((float)$value['nnettotal'], 2, '.', '') ; ?></td>
                            <td class="text-left"><button data-id="<?php echo $value['vvendorid']; ?>" data-name="<?php echo $value['vvendorname']; ?>" data-date="<?php echo $value['dcreatedate'] ;?>" data-ipoid="<?php echo $value['ipoid'] ;?>" class="btn btn-info btn-sm view_item_btn"><i class="fa fa-eye" aria-hidden="true"></i> view</button></td>
                            <?php 
                              $total_nnettotal = $total_nnettotal + $value['nnettotal'];
                              $rip_tot=$rip_tot+$value['rip_total'];
                            ?>
                            <td class="text-right"><?php echo "$",number_format((float)$value['rip_total'], 2, '.', '') ; ?></td>
                          </tr>
                          <?php } ?>
                          <tr>
                            <td>&nbsp;</td>
                            <td><b>Total</b></td>
                            <td class="text-right"><b>$<?php echo number_format((float)$total_nnettotal, 2, '.', '') ?? ''; ?></b></td>
                            <td>&nbsp;</td>
                            <td class="text-right"><b>$<?php echo number_format((float)$rip_tot, 2, '.', '') ??''; ?></b></td>
                          </tr>
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
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.js"></script>
    
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"></script>
    
    <script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>

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
</script>

<script type="text/javascript">
  $(document).ready(function() {
    // $('#report_by').select2({
    //   placeholder: "Please Select Vendor"
    // });
  });

$(document).on('submit', '#filter_form', function(event) {
  
  if($('#report_by > option:selected').length == 0){
    // alert('Please Select Vendor');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select Vendor", 
      callback: function(){}
    });
    return false;
  }

  if($('#report_by').val() == ''){
    // alert('Please Select Vendor');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select Vendor", 
      callback: function(){}
    });
    return false;
  }

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

  $("div#divLoading").addClass('show');
  
});
</script>

<style type="text/css">
  .modal-body {
    max-height: calc(100vh - 210px);
    overflow-y: auto;
  }

  .table.table-bordered.table-striped.table-hover thead > tr {
    background-color: #2486c6;
    color: #fff;
  }

</style>

<script>  
$(document).ready(function() {
  $("#btnPrint").printPage();
});
</script> 

<script type="text/javascript">
  $(document).on('click', '.view_item_btn', function(event) {
    event.preventDefault();

    var view_item_url =  '<?php echo route('view_item_url'); ?>';
    
    view_item_url = view_item_url.replace(/&amp;/g, '&');

    var vendor_id = $(this).attr('data-id');
    var vendor_name = $(this).attr('data-name');
    var vendor_date = $(this).attr('data-date');
    var vendor_ipoid = $(this).attr('data-ipoid');

    $("div#divLoading").addClass('show');

    $.ajax({
        url : view_item_url+'?vendor_id='+vendor_id+'&vendor_date='+vendor_date+'&vendor_ipoid='+vendor_ipoid,
        type : 'GET',
    }).done(function(response){
      
      var  response = $.parseJSON(response); //decode the response array
      
      if( response.code == 1 ) {//success
        $('#item_table_body').empty();

        var html_item_table = '';

        $.each(response.data, function(i, v) {
            html_item_table += '<tr>';
            html_item_table += '<td>'+v.vbarcode+'</td>';
            html_item_table += '<td>'+v.vitemname+'</td>';
            html_item_table += '<td>'+v.vvendorname+'</td>';
            html_item_table += '<td>'+v.vsize+'</td>';
            html_item_table += '<td>'+v.nordqty+'</td>';
            html_item_table += '<td>'+v.npackqty+'</td>';
            html_item_table += '<td>'+v.itotalunit+'</td>';
            html_item_table += '<td>'+"$"+''+v.nordextprice+'</td>';
            html_item_table += '<td class="text-right">'+"$"+''+parseFloat(v.nunitcost).toFixed(2)+'</td>';
            html_item_table += '<td class="text-right">'+"$"+''+parseFloat(v.nripamount).toFixed(2)+'</td>';
            html_item_table += '</tr>';
        });

        $('#item_table_body').append(html_item_table);

        $('#modal_title').html('Items of '+vendor_name);
        $("div#divLoading").removeClass('show');
        $('#view_item_modal').modal('show');
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
</script>


<!-- Modal -->
  <div class="modal fade" id="view_item_modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-uppercase" id="modal_title">Modal Header</h4>
        </div>
        <div class="modal-body" style="overflow-x:scroll !important;">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>SKU#</th>
                <th>Item Name</th>
                <th>Vendor Code</th>
                <th>Size</th>
                <th>Total Case</th>
                <th>Case Qty</th>
                <th>Total Unit</th>
                <th>Total Amt</th>
                <th class="text-right">Unit Cost</th>
                <th class="text-right">RIP Amt</th>
              </tr>
            </thead>
            <tbody id="item_table_body">
              <tr>
                <td>test</td>
                <td>10.00</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>

<script type="text/javascript">
  $(document).on('change', 'input[name="start_date"],input[name="end_date"]', function(event) {
    event.preventDefault();

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
  });
</script>

<!-- <script src="view/javascript/table-fixed-header.js" ></script> -->
<script>

// $('.table').fixedHeader({
//     topOffset: 0
// });

$(document).ready(function(){
  $('.header').trigger('click');
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

    $(document).on("click", "#pdf_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('POpdf'); ?>';
      
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
              link.download = "PO_History_Report.pdf";

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
    //             url: '/poreport/print',
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

          var csv_export_url = '<?php echo route('POcsv'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "PO_History_Report.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });

</script>
@endsection   