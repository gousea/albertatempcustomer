@extends('layouts.layout')
@section('title')
ITEM SUMMARY REPORT
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase">ITEM SUMMARY REPORT</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($item_summary) && count($item_summary) > 0){ ?>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu "  href="{{route('itemsummaryprint_page')}}" id="btnPrint">PRINT
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
        
          <form method="post" id="filter_form">
            @csrf
           
            <div class="row">
             
                  <div class="col-md-3">
                      <input type="" autocomplete="off" class="form-control rcorner" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" autocomplete="off">
                  </div> 
                  
                  <div class="col-md-3">
                      <input type="" autocomplete="off" class="form-control rcorner" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" autocomplete="off">
                  </div>
              
                  <div class="col-md-2">
                    <input type="submit" class="btn btn-success align-bottom rcorner header-color" value="Generate">
                  </div> 
              
            </div>  
        </form>
        
        <br><br>
        <h6><span> ITEM SUMMARY  </span></h6>
        <br>
     
      
      <?php if(!empty($item_summary)) { ?>
         
    
              
            <div class="table-responsive">
           
                  
                      <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
                   data-click-to-select="true">
                          <thead class="header">
                          <tr class="header-color  text-uppercase">
                              <th>Sku</th>
                              <th>Item Name</th>
                              
                              <th class='text-right'>Qty Sold</th>
                              <th class='text-right'>Avg. Price</th>
                              <th class='text-right'>Amount</th>
                              
                          </tr>
                          </thead>
                          <?php $grand_total_qty = $grand_total_amount = 0;?>
                          <?php foreach($out as $r) { ?>
                              
                              <tr class="th_color text-uppercase">
                                  <th><?php echo isset($r['catagoryname']) ? $r['catagoryname']: ''; ?></th>
                                  <th></th>
                                  <th></th>
                                  
                                  <th></th>
                                  <th></th>
                                  
                              </tr>
                              <?php $total_qty = $total_amount = 0;?>
                              <?php foreach($r['details'] as $itmes) { ?>
                                  <?php $total_qty += $itmes['qtysold']; $total_amount += $itmes['amount']; ?>
                                  <tr class="th_color">
                                      <td><?php echo isset($itmes['sku']) ? $itmes['sku']: 0; ?></td>
                                      <td><?php echo isset($itmes['itemname']) ? $itmes['itemname']: ''; ?></td>
                                      
                                      <td class='text-right'><?php echo isset($itmes['qtysold']) ? $itmes['qtysold']: 0; ?></td>
                                      <td class='text-right'><?php echo "$",isset($itmes['avgprice']) ? number_format($itmes['avgprice'],2): 0; ?></td>
                                      <td class='text-right'><?php echo "$",isset($itmes['amount']) ? $itmes['amount']: 0; ?></td>
                                  </tr>
                              <?php } ?>
                              <tr class="header-color  text-uppercase"> 
                                  <th></th>
                                   <th> Sub Total</th>
                                 
                                  <th class='text-right'> <?php echo number_format($total_qty,2); ?> </th>
                                  <th>  </th>
                                  <th class='text-right'> <?php echo "$",number_format($total_amount,2); ?> </th>
                              </tr>
                              <?php $grand_total_qty    += $total_qty;
                                  $grand_total_amount += $total_amount; ?>

                          <?php } ?>
                          <tr class="header-color  text-uppercase"> 
                              <th></th>
                              <th > Grand Total</th>
                              
                              
                              <th class='text-right'> <?php echo number_format($grand_total_qty,2); ?> </th>
                              <th>  </th>
                              <th class='text-right'> <?php echo "$",number_format($grand_total_amount,2); ?> </th>
                          </tr>
                      </table>
                  
              
              
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
</div>
</section>
@endsection

@section('page-script')
<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>

<!--<script src="{{ asset('javascript/chart/highcharts.js')}}"></script>-->
<!--<script src="{{ asset('javascript/chart/exporting.js')}}"></script>-->
<!--<script src="{{ asset('javascript/chart/export-data.js')}}"></script>-->

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('javascript/bootbox.min.js')}}"></script>
<script src="{{ asset('javascript/table-fixed-header.js')}}" ></script>
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">


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
      orientation: "bottom left" 
    });
    
    $("#end_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left" 
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
   margin: 0px 0 20px; 
   color:#286fb7;
} 

h6 span { 
    background:#f8f9fa!important; 
    padding:10px 0px; 
    color:#286fb7;
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
@endsection