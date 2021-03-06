@extends('layouts.layout')
@section('title')
RO History Report
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> RO History Report</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($reports) && count($reports) > 0){ ?>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu "  href="{{route('POprint')}}" id="btnPrint">PRINT
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " id="pdf_export_btn" href="{{route('salesreportpdf_save_page')}}" > PDF
                            </a>
                        <?php } ?>
                </div>
            </div> 
        </div>
    </nav>

<section class="section-content py-6"> 
    
    <div class="container">
       
        
                <h6><span>SEARCH PARAMETERS </span></h6>
                <br> 
                
                <form method="post" id="filter_form" action="{{ route('POForm') }}">
                    <div class="row"> 
                      @csrf
                        <div class="col-md-3">
                          <select name="report_by[]" class="form-control select_new" id="report_by" multiple="true">
            
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
                        <div class="col-md-3">
                          <input type="" class="form-control rcorner" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" required  autocomplete="off" >
                        </div>
                         
                        <div class="col-md-3">
                          <input type="" class="form-control rcorner" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" required  autocomplete="off" >
                        </div>
                        <div class="col-md-2">
                          <input type="submit" class="btn btn-success header-color rcorner" value="Generate">
                        </div>
                    </div>    
                </form>
                <br><br>
                
                <h6><span> RO HISTORY </span></h6>
    
                <?php if(isset($reports) && count($reports) > 0){ ?>
                <div class="row" style="margin: 10px; display:none">
                    <div class="col-md-12">
                    <p><b>Date Range: </b><?php echo $p_start_date; ?> to <?php echo $p_end_date; ?></p>
                       <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
                       <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
                       <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
                    </div>
                </div>
                 
                  <br>
                    <table data-toggle="table" data-classes="table  table-condensed promotionview"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
                   data-click-to-select="true">
                      <thead class="header">
                        <tr class="header-color  text-uppercase">
                          <th>Vendor</th>
                          <th>Date</th>
                          <th >Net Total</th>
                          <th>Action</th>
                          <th>RIP Total </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $total_nnettotal = 0;
                            $rip_tot=0;
                          ?>
                          <?php foreach ($reports as $key => $value){ ?>
                          <tr class="th_color">
                            <td><?php echo $value['vvendorname']; ?></td>
                            <td><?php echo $value['dcreatedate']; ?></td>
                            <td ><?php echo "$",number_format((float)$value['nnettotal'], 2, '.', '') ; ?></td>
                            <td ><button data-id="<?php echo $value['vvendorid']; ?>" data-name="<?php echo $value['vvendorname']; ?>" data-date="<?php echo $value['date2'] ;?>" data-ipoid="<?php echo $value['ipoid'] ;?>" class="btn btn-info btn-sm view_item_btn"><i class="fa fa-eye" aria-hidden="true"></i> view</button></td>
                            <?php 
                              $total_nnettotal = $total_nnettotal + $value['nnettotal'];
                              $rip_tot=$rip_tot+$value['rip_total'];
                            ?>
                            <td ><?php echo "$",number_format((float)$value['rip_total'], 2, '.', '') ; ?></td>
                          </tr>
                          <?php } ?>
                          <tr class="header-color  text-uppercase">
                            <td>&nbsp;</td>
                            <td><b> GRAND TOTAL</b></td>
                            <td><b>$<?php echo number_format((float)$total_nnettotal, 2, '.', '') ?? ''; ?></b></td>
                            <td>&nbsp;</td>
                            <td><b>$<?php echo number_format((float)$rip_tot, 2, '.', '') ??''; ?></b></td>
                          </tr>
                      </tbody>
                    </table>
                  
                
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
    

    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.js"></script>
    
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"></script>
    
    <script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/reportline.css') }}">

<script>
  $(function(){
    $("#start_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left" 
    });

    $("#end_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left" 
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
    $('.select_new').select2();
    $selectElement2 = $('#report_by').select2({
    placeholder: "VENDOR",
    allowClear: true
    });
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
            
            html_item_table += '<td class="text-right">'+"$"+''+parseFloat(v.nordextprice).toFixed(2)+'</td>';
            html_item_table += '<td class="text-right">'+"$"+''+parseFloat(v.nunitcost).toFixed(2)+'</td>';
            html_item_table += '<td class="text-right">'+"$"+''+parseFloat(v.nripamount).toFixed(2)+'</td>';
            html_item_table += '<td>'+v.before_rece_qoh+'</td>';
            html_item_table += '<td>'+v.after_rece_qoh+'</td>';
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
    <div class="modal-dialog modal-lg" style="background:#f8f9fa;">
      <!-- Modal content-->
      <div class="modal-content" style="background:#f8f9fa;">
        <div class="modal-header">
        <h4 class="modal-title text-uppercase" id="modal_title" style="float: left;">Modal Header</h4>    
          <button type="button" class="close" data-dismiss="modal" style="float: right;">&times;</button>
          
        </div>
        <div class="modal-body" style="overflow-x:scroll !important;">
          <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
                   data-click-to-select="true">
            <thead>
              <tr class="header-color  text-uppercase">
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
                <th class="text-right">Before QOH</th>
                <th class="text-right">After QOH</th>
              </tr>
            </thead>
            <tbody id="item_table_body">
            
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
<style>
.rcorner {
  border-radius:9px;
  height: 42px;
}
.th_color{
    background-color: #474c53 !important;
    color: #fff;
    
  
}


[class^='select2'] {
  border-radius: 9px !important;
}
table, .promotionview {
    width: 100% !important;
    position: relative;
    left: 0%;
}
</style>
<style type="text/css">

/*.table.table-bordered.table-striped.table-hover thead > tr {*/
/*    background-color: #2486c6;*/
/*    color: #fff;*/
/*}*/
.select2-selection__rendered {
    line-height: 31px !important;
}
.select2-selection .select2-selection--multiple {
    height: 30px !important;
}
.selection{
    height: 35px !important;
}
.select2-selection__arrow {
    height: 34px !important;
}

</style>
<script>
    $('select[name="report_by[]"]').select2();
    
    
</script>
@endsection   