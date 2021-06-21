@extends('layouts.layout')
@section('title')
Below Cost Report
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase">Below Cost Report</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($reports) && count($reports) > 0){ ?>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu "  href="{{route('BelowCostReportprint')}}}" id="btnPrint">PRINT
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " id="pdf_export_btn" href="{{route('BelowCostReportpdf')}}" > PDF
                            </a>
                        <?php } ?>
                </div>
            </div> 
        </div>
    </nav>

    
    <div class="container">
  
            <?php if(isset($reports_ccs) && count($reports_css) > 0){ ?>
                        <div class="row" style="padding-bottom: 10px;float: right;">
                            <div class="col-md-12">
                            
                                <a id="pdf_export_btn" href="" class="" style="margin-right:10px;">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                                </a>
                                <a  id="btnPrint" href="{{route('BelowCostReportprint')}}"  class="" style="margin-right:10px;">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                <a id="csv_export_btn" href="" class="" style="margin-right:10px;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV
                                </a>
                        
                            </div>
                        </div>
                      
            <?php } ?>
      <br>
          <h6><span>DEPARTMENT FILTER </span></h6>
                <br>
    
              <form method="get" id="filter_form"  action="{{ route('BelowCostReportForm') }}" >
               <div class="row" >
              
                <div class="col-md-3">
                  <select name="report_by[]" class="form-control" id="report_by" multiple="true">
    
                  <?php if(isset($selected_byreports) && count($selected_byreports) > 0){ ?>
                        <option value="">Please Select Department</option>
                        <?php if(in_array('ALL', $selected_byreports)){ ?>
                          <option value="ALL" selected="selected">ALL</option>
                        <?php } else { ?>
                          <option value="ALL">ALL</option>
                        <?php } ?>
                            
                        <?php foreach($byreports as $k => $v){ ?>
                          <?php $sel_report = false; ?>
                          <?php foreach($selected_byreports as $ks => $selected_byreport){ ?>
                            <?php if($selected_byreport == $v->vdepcode){ ?>
                                <option value="<?php echo $v->vdepcode; ?>" selected="selected"><?php echo $v->vdepartmentname; ?></option>
                                <?php 
                                  $sel_report = true;
                                  break;
                                ?>
                            <?php } ?>
                          <?php } ?>
                          <?php if($sel_report == false){ ?>
                            <option value="<?php echo $v->vdepcode; ?>"><?php echo $v->vdepartmentname; ?></option>
                          <?php } ?>
                        <?php } ?>
                        
                  <?php } else { ?>
                    <option value="">Please Select Department</option>
                    <option value="ALL">ALL</option>
                    <?php foreach ($byreports as $key => $value){ ?>
                      <option value="<?php echo $value->vdepcode; ?>"><?php echo $value->vdepartmentname; ?></option>
                    <?php } ?>
                  <?php } ?>
    
                  </select>
                </div>
            
                <div class="col-md-2">&nbsp;&nbsp;&nbsp;
                  <input type="submit" class="btn btn-success rcorner header-color" value="Generate">
                </div>
                 </div>   
              </form>
              <br>
               <br>
                 <h6><span> BELOW COST ITEMS</span></h6>
               
                
        
                    <?php if(isset($reports_css) && count($reports_css) > 0){ ?>
            
                      
                        <div class="row" style="margin: 10px;">  
                          <div class="col-md-6 pull-left">
                            <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
                          </div>
                        </div>
                        
                        <div class="row" style="margin: 10px;">
                          
                          <div class="col-md-6 pull-left">
                            <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
                          </div>
                        </div>
                        
                        <div class="row" style="margin: 10px;">
                          
                          <div class="col-md-6 pull-left">
                            <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
                          </div>
                        </div>
                      <?php }?>
                
          
              <br>
        <?php if(isset($reports) && count($reports) > 0){ ?>
                <table data-toggle="table" data-classes="table  table-condensed promotionview"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
                   data-click-to-select="true">
                  <thead class="header">
                    <tr class="headermenublue text-uppercas">
                      <th>Item Name</th>
                      <th>SKU</th>
                      
                      <th class="text-right">Cost</th>
                      <th class="text-right">Price</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $tot_cost = 0; ?>
                      <?php $tot_price = 0; ?>
                      <?php foreach ($reports as $key => $value){ ?>
                      <tr>
                        
                        <td><a href="<?php echo $value['item_link'];?>"><?php echo $value['itemname']; ?></a></td>
                        
                        <td><?php echo $value['vbarcode']; ?></td>  
                        <td class="text-right"><?php echo "$",number_format((float)$value['cost'], 2, '.', '') ; ?></td>
                        <td class="text-right"><?php echo "$",number_format((float)$value['price'], 2, '.', '') ; ?></td>
                        <?php $tot_cost = $tot_cost + $value['cost']; ?>
                        <?php $tot_price = $tot_price + $value['price']; ?>
                      </tr>
                      <?php } ?>
                  </tbody>
                </table>
              
            
            <?php }else{ ?>
              <?php if(isset($reports)){ ?>
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


@endsection   
@section('page-script')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" ></script>

<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>

<script src="{{ asset('javascript/table-fixed-header.js') }}" ></script>
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/reportline.css') }}">

<script>

$('.table').fixedHeader({
    topOffset: 0
});

</script>
<script type="text/javascript">

$(document).on('submit', '#filter_form', function(event) {
  
  if($('#report_by > option:selected').length == 0){
    // alert('Please Select Department');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select Department", 
      callback: function(){}
    });
    return false;
  }

  if($('#report_by').val() == ''){
    // alert('Please Select Department');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select Department", 
      callback: function(){}
    });
    return false;
  }

  $("div#divLoading").addClass('show');

});
</script>

<style type="text/css">

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
//   $(window).load(function() {
//     $("div#divLoading").removeClass('show');
//   });
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

        var pdf_export_url = '<?php echo route('BelowCostReportpdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "BelowCostReportpdf.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "BelowCostReportpdf.pdf";

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
    //             url: '/paidoutreport/print',
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

          var csv_export_url = '<?php echo route('BelowCostReportcsv'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "Paidoutcsv.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });

</script>

<style type="text/css">

/*.table.table-bordered.table-striped.table-hover thead > tr {*/
/*    background-color: #2486c6;*/
/*    color: #fff;*/
/*}*/
.select2-selection__rendered {
    line-height: 31px !important;
}
.select2-container .select2-selection--single {
    height: 35px !important;
}
.select2-selection__arrow {
    height: 34px !important;
}
</style>
<script>
    $('select[name="report_by[]"]').select2();
 
    
    
</script>
<style>
.rcorner {
  border-radius:9px;
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
@endsection 
