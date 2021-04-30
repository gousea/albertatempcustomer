@extends('layouts.master')
@section('title')
  Item Summary Report
@endsection
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
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Item Summary"; ?></h3>
      </div>
      <div class="panel-body">
          
            <?php if(isset($data_error)){ ?>
                    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> Sorry no data found!
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>  
            <?php } ?>
          
            @if(Session::has('success'))
                <div class="alert alert-success">
                      {{ Session::get('success') }}
                </div>
            @endif
          
        @if(session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>      
        @endif
        @if(session()->has('error'))
            <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> {{session()->get('error')}}
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>      
        @endif

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
              @endforeach
            </ul>
          </div>                
        @endif

    
      <div class="row" style="padding-bottom: 15px;float: right;">
        <div class="col-md-12">
          <a id="csv_export_btn" href="{{route('itemsummarycsv_export')}}" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</a>
          <a href="{{route('itemsummaryprint_page')}}" id="btnPrint" class="pull-right" style="margin-right:10px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
        <a id="pdf_export_btn" href="{{route('itemsummarypdf_save_page')}}" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
        </div>
      </div>
      
      <div class="clearfix"></div>

      <div class="row">
          <form method="post" id="filter_form">
            @csrf
            @method('post')
              <div class='col-md-12'>
                  <div class="col-md-2">
                      <input type="" autocomplete="off" class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" autocomplete="off">
                  </div> 
                  <div class="col-md-2">
                      <input type="" autocomplete="off" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" autocomplete="off">
                  </div>
              
                  <div class="col-md-2">
                    <input type="submit" class="btn btn-success align-bottom" value="Generate">
                  </div> 
              </div>
        </form>
      </div>
      <br>
      <?php if(!empty($item_summary)) { ?>
          
          <!--<div class="row">
            <div class="col-md-12">
              <div class='col-md-6'>
                  <p><b>Store Name: </b><?php echo $storename; ?></p>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class='col-md-6'>
                  <p><b>Store Address: </b><?php echo $storeaddress; ?></p>
              </div>    
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class='col-md-6'>
                  <p><b>Store Phone: </b><?php echo $storephone; ?></p>
              </div>
            </div>
          </div> -->
          
          <div class="row">
                <div class="col-md-12">
                  <p>From: <?php echo $p_start_date; ?> To <?php echo $p_end_date; ?></p>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-12">
                  <p><b>Store Name: </b><?php echo $storename; ?></p>
                </div>
                <div class="col-md-12">
                  <p><b>Store Address: </b><?php echo $storeaddress; ?></p>
                </div>
                <div class="col-md-12">
                  <p><b>Store Phone: </b><?php echo $storephone; ?></p>
                </div>
              </div>
          
          <div class="row">
              
            <div class="table-responsive">
                
              
              <div class="col-md-12">
                  
                  <div class="col-md-10">
                  
                      <table class="table <!--table-bordered table-striped --> table-hover">
                          <thead class="header">
                          <tr>
                              <th>Sku</th>
                              <th>Item Name</th>
                              <th></th>
                              <th class='text-right'>Qty Sold</th>
                              <th class='text-right'>Avg. Price</th>
                              <th class='text-right'>Amount</th>
                              
                          </tr>
                          </thead>
                          <?php $grand_total_qty = $grand_total_amount = 0;?>
                          <?php foreach($out as $r) { ?>
                              
                              <tr>
                                  <th><?php echo isset($r['catagoryname']) ? $r['catagoryname']: ''; ?></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  
                              </tr>
                              <?php $total_qty = $total_amount = 0;?>
                              <?php foreach($r['details'] as $itmes) { ?>
                                  <?php $total_qty += $itmes['qtysold']; $total_amount += $itmes['amount']; ?>
                                  <tr>
                                      <td><?php echo isset($itmes['sku']) ? $itmes['sku']: 0; ?></td>
                                      <td><?php echo isset($itmes['itemname']) ? $itmes['itemname']: ''; ?></td>
                                      <td></td>
                                      <td class='text-right'><?php echo isset($itmes['qtysold']) ? $itmes['qtysold']: 0; ?></td>
                                      <td class='text-right'><?php echo "$",isset($itmes['avgprice']) ? number_format($itmes['avgprice'],2): 0; ?></td>
                                      <td class='text-right'><?php echo "$",isset($itmes['amount']) ? $itmes['amount']: 0; ?></td>
                                  </tr>
                              <?php } ?>
                              <tr> 
                                  <th></th>
                                  <th></th>
                                  <th class='text-right'> Sub Total:</th>
                                  <th class='text-right'> <?php echo number_format($total_qty,2); ?> </th>
                                  <th>  </th>
                                  <th class='text-right'> <?php echo "$",number_format($total_amount,2); ?> </th>
                              </tr>
                              <?php $grand_total_qty    += $total_qty;
                                  $grand_total_amount += $total_amount; ?>

                          <?php } ?>
                          <tr> 
                              <th></th>
                              <th></th>
                              <th class='text-right'> Grand Total:</th>
                              <th class='text-right'> <?php echo number_format($grand_total_qty,2); ?> </th>
                              <th>  </th>
                              <th class='text-right'> <?php echo "$",number_format($grand_total_amount,2); ?> </th>
                          </tr>
                      </table>
                  </div>
              </div>
              
            </div>
          </div>
      <?php } else if(isset($p_start_date)){ ?>
          <div class="col-md-12">  
              <table class="table table-bordered" style="width:50%;">
                  <tbody>
                      <tr><td><b>Sorry no data found!</b></td></tr> 
                  </tbody>  
              </table>
          </div>
      <?php } ?>
      
      
    </div>
  </div>
</div>
</div>

@endsection

@section('scripts')
<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>

<script src="{{ asset('javascript/chart/highcharts.js')}}"></script>
<script src="{{ asset('javascript/chart/exporting.js')}}"></script>
<script src="{{ asset('javascript/chart/export-data.js')}}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('javascript/bootbox.min.js')}}"></script>
<script src="{{ asset('javascript/table-fixed-header.js')}}" ></script>


<style type="text/css">
   .title_div{
    border: 1px solid #ddd;
    padding: 5px;
  }
  .align-self {position: relative; margin-top: 17px;}
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

      var csv_export_url = "{{route('itemsummarycsv_export')}}";
    
      csv_export_url = csv_export_url.replace(/&amp;/g, '&');

      $.ajax({
        url : csv_export_url,
        type : 'GET',
      }).done(function(response){
        
        const data = response,
        fileName = "item-summary-report.csv";

        saveData(data, fileName);
        $("div#divLoading").removeClass('show');
        
      });
    
  });

  $(document).on("click", "#pdf_export_btn", function (event) {

    event.preventDefault();

    $("div#divLoading").addClass('show');

    var pdf_export_url = "{{route('itemsummarypdf_save_page')}}";
  
    pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

    var req = new XMLHttpRequest();
    req.open("GET", pdf_export_url, true);
    req.responseType = "blob";
    req.onreadystatechange = function () {
      if (req.readyState === 4 && req.status === 200) {

        if (typeof window.navigator.msSaveBlob === 'function') {
          window.navigator.msSaveBlob(req.response, "Items-Summary-Report.pdf");
        } else {
          var blob = req.response;
          var link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = "Items-Summary-Report.pdf";

          // append the link to the document body

          document.body.appendChild(link);

          link.click();
           
        }
      }
      $("div#divLoading").removeClass('show');
    };
    req.send();
    
  });

  $("#pdf_export_btn1").click(function(e){
        e.preventDefault();
        var date=$("#start_date").val();
        $.ajax({
           url:"index.php?route=administration/end_of_day_report/get_pdf_day/",
           data:{date:date},
           type:"POST",
           dataType:"JSON",
           success:function(data){
               alert(data);
           },
           error:function(xhr){
               alert(xhr.responseText);
           }
        });
    });
    
    $('.table').fixedHeader({
      topOffset: 0
    });

    $(document).ready(function(){
      $('.header').trigger('click');
    });

</script>

@endsection