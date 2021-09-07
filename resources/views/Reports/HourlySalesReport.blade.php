@extends('layouts.layout')
@section('title')
Hourly Sales Report
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase">Hourly Sales Report</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($report_hourly) && count($report_hourly) > 0){ ?>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu "  href="{{route('HourlySalesprint')}}" id="btnPrint">PRINT
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " id="pdf_export_btn" href="#" > PDF
                            </a>
                        <?php } ?>
                </div>
            </div> 
        </div>
    </nav>

    
    <div class="container">
      
      
                <?php if(isset($report_hourly_css) && count($report_hourly_css) > 0){ ?>
                    <div class="row" style="padding-bottom: 10px;float: right;">
                        <div class="col-md-12">
                        
                            <a id="pdf_export_btn" href="" class="" style="margin-right:10px;">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                            </a>
                            <a  id="btnPrint" href="{{route('HourlySalesprint')}}" class="" style="margin-right:10px;">
                                <i class="fa fa-print" aria-hidden="true"></i> Print
                            </a>
                            <a id="csv_export_btn" href="" class="" style="margin-right:10px;">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV
                            </a>
                    
                        </div>
                </div>
                    <br>
                <?php } ?>
                <br>
               <h6><span>SEARCH PARAMETERS </span></h6>
               
                <br>
                    <form method="post" id="filter_form" action="{{ route('HourlySalesForm') }}">
          @csrf
                <div class="row">
                    
                        
                        <div class="col" >
                            
                            <select name="vdepcode[]" class="sample-class"  id="dept_code"  multiple="true" placeholder="Select Department" style="width:275px" required >
                                                
                                            <option value="All">All</option>
                                              <?php if(isset($departments) && count($departments) > 0){?>
                                                <?php foreach($departments as $department){ ?>
                                                <?php if(isset($vdepcode) && in_array($department['vdepcode'], $vdepcode)){?>
                                                  <option value="<?php echo $department['vdepcode'];?>" selected="selected"><?php echo $department['vdepartmentname'];?></option>
                                                <?php } else { ?>
                                                  <option value="<?php echo $department['vdepcode'];?>"><?php echo $department['vdepartmentname'];?></option>
                                                <?php } ?>
                                                <?php } ?>
                                              <?php } ?>
                            </select>
                     </div>
                     <div class="col"  >
                        <select name="vcategorycode[]"  class="sample-class1" id="category_code" multiple="true"  style="width:275px" >
                                                
                                            <option value="All">All</option>     
                                            
                                            <?php if(isset($categories) && count($categories)){?>
                                                    <?php foreach($categories as $category){ ?>
                                                    <?php if(isset($category_list) && in_array($category['vcategorycode'], $category_list)){?>
                                                      <option value="<?php echo $category['vcategorycode'];?>" selected="selected"><?php echo $category['vcategoryname'];?></option>
                                                    <?php }  else { ?>
                                                      <option value="<?php echo $category['vcategorycode'];?>"><?php echo $category['vcategorycode'];?></option>
                                                    <?php } ?>
                                                       
                                                    <?php } ?>
                                                <?php } ?>
                                              
                        </select>
                  </div>
                    <div class="col">
                         <select name="subcat_id[]" class="sample-class2" id="subcat_id" multiple="true"  style="width:275px" >
                            
                             <option value="All">All</option>
                           
                               <?php if(isset($subcategories) && count($subcategories)){?>
                                                    <?php foreach($subcategories as $subcategory){ ?>
                                                    <?php if(isset($subcategory_list) && in_array($subcategory['subcat_id'], $subcategory_list)){?>
                                                      <option value="<?php echo $subcategory['subcat_id'];?>" selected="selected"><?php echo $subcategory['subcat_name'];?></option>
                                                    <?php } else { ?>
                                                      <option value="<?php echo $subcategory['subcat_id'];?>"><?php echo $subcategory['subcat_id'];?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                 <?php } ?>
                                              
                            
                            
                        </select>
                  </div>
                    <div class="col" >
                              <input type="submit" class="btn btn-success rcorner header-color"  value="Generate" style="width:150px">
                        </div>     
                        
                 
                </div>
                
                <br>
                <div class="row">
                  
                        <div class="col">    
                            <select name="manufacturer_id[]" class="sample-class3"  multiple="true"  id="manuf" style="width:275px">
                                        
                                        <option value="All">All</option>
                                          <?php if(isset($manufacturers) && count($manufacturers) > 0){?>
                                            <?php foreach($manufacturers as $manufacturer){ ?>
                                             <?php if(isset($manu_list) && in_array( $manufacturer['mfr_id'], $manu_list)){?>
                                                      <option value="<?php echo  $manufacturer['mfr_id'];?>" selected="selected"><?php echo $manufacturer['mfr_name'];?></option>
                                             <?php } else { ?>
                                              <option value="<?php echo $manufacturer['mfr_id'];?>"><?php echo $manufacturer['mfr_name'];?></option>
                                            <?php } ?>
                                            <?php } ?>
                                          <?php } ?>
                            </select>
                                        
                        </div>
                        <div class="col">   
                            <select name="ivendorid[]" class="sample-class4"  multiple="true"  id="supl"  style="width:275px">
                              
                                <option value="All">All</option>
                                <?php if(isset($suppliers) && count($suppliers) > 0){?>
                                  <?php foreach($suppliers as $supplier){ ?>
                                  <?php if(isset($sup_list) && in_array( $supplier['vsuppliercode'], $sup_list)){?>
                                      <option value="<?php echo $supplier['vsuppliercode'];?>" selected="selected"><?php echo $supplier['vcompanyname'];?></option>
                                  <?php } else { ?>
                                      <option value="<?php echo $supplier['vsuppliercode'];?>"><?php echo $supplier['vcompanyname'];?></option>
                                   <?php } ?>      
                                  <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col"  >
                                
                                <input type='text' class="form-control rcorner" name="dates" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="dates" placeholder="Select Date Range" autocomplete="off" readonly style="width:286px"/>
                                <input type='hidden' class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly/>                
                                 <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" autocomplete="off">
                              
                        </div>
                        <div class="col" style="width:195">
                            
                        </div>
                       
                    
                </div> 
                <br>
                
          </form>
             <br>
               <h6><span>HOURLY SALES REPORT </span></h6>
               
            <br>
            <?php if(!empty($report_hourly_css)) { ?>
                <div class="row">
                  <div class="col-md-12">
                    <div class='col-md-6'>
                        <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
                    </div>
                  </div>
            </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class='col-md-6'>
                        <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
                    </div>    
                  </div>
            </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class='col-md-6'>
                        <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
                    </div>
                  </div>
            </div>
          
           
                <div class="row">
                  <div class="col-md-12">
                    <div class='col-md-6'>
                        <p>
                            <span>
                                <b>Date: </b>
                                <?php echo isset($dates_selected) ? $dates_selected : date("m-d-Y"); ?>
                            </span>
                        </p>
                    </div>
                  </div>
            </div>
            
              <?php } ?>
              
               <?php if(!empty($report_hourly)) { ?>
              
                          
                                <?php if($graph_data) { ?>
                                    
                                            <div id="graphContainer" style="height: 300px;"></div>
                                  
                                <?php } ?>
                        
            <br>  <br>         
             <table data-toggle="table" data-classes="table  table-condensed promotionview"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
                   data-click-to-select="true">
                                    
                                    <tr class="th_color text-uppercas">
                                        <th class="text-left">DATE</th>
                                        <th class="text-center">HOURLY SALES</th>
                                        <th class="text-center"> AMOUNT</th>
                                        <th class="text-right">TRANSACTION</th>
                                        <!--<td class="text-center"><b>Time Slot</b></td>-->
                                    </tr>
            
                                    <?php foreach($report_hourly as $r) { ?>
                                        
                                        <tr>
                                            <th class="text-left"><?php echo isset($r['trn_date']) ? $r['trn_date']: 0; ?></th>
                                            <th class="text-center"><?php echo isset($r['Hours']) ? $r['Hours']: 0; ?></th>
                                            <th class="text-center"><?php echo "$",isset($r['Amount']) ? $r['Amount']: 0.00; ?></th>
                                            
                                            <th class="text-right" style=" padding-right: 40px;"><a href="javascript:void(0)" onclick="itemlist('<?= $r['salesids'] ?>', '<?= $r['Hours'] ?>', '<?= $r['trn_date'] ?>');" title="View"><?php echo isset($r['Number']) ? $r['Number']: 0; ?></a></th>
                                            
                                        </tr>
        
                                    <?php } ?>
                                </table>
                            
                        
                        
                      
        
            <?php } else if(isset($p_start_date)){ ?>
                    <div class="col-md-12">  
                        <table class="table table-bordered" style="width:50%;">
                            <tbody>
                                <tr><td><b>Sorry no data found!</b></td></tr> 
                            </tbody>  
                        </table>
        </div>
            <?php } ?>
            <div class="row">
              <div class="col-md-12" style="display:none;">
                <div class="title_div" style="width:50%;background-color: #2486c6;color: #fff;">
                  <b>Hourly Sales</b>
                </div>
                <table class="table table-bordered" style="width:50%;">
                  <tbody>
                    <?php if(isset($report_hourly_sales) && count($report_hourly_sales) > 0){ ?>
                      <?php foreach($report_hourly_sales as $report_hourly_sale){ ?>
                      <tr>
                        <td><?php echo $report_hourly_sale['TODATE']; ?></td>
                        <td class="text-right"><?php echo $report_hourly_sale['AMT']; ?></td>
                      </tr>
                      <?php } ?>
                    <?php } else { ?>
                    <tr>
                      <td><b>Sorry no data found!</b></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
          
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

<script type="text/javascript" src="{{ asset('javascript/chart/highcharts.js') }}"></script>
<script type="text/javascript" src="{{ asset('javascript/chart/exporting.js') }}"></script>
<script type="text/javascript" src="{{ asset('javascript/chart/export-data.js') }}"></script>

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/reportline.css') }}">

<script>
//   $(function(){

//     $("div#divLoading").addClass('show');
//   });
  
  window.onload = function () {
Highcharts.chart('graphContainer', {
    chart: {
        type: 'area',
        spacingBottom: 30
    },
    title: {
        text: 'Hourly Sales Report'
    },
    subtitle: {
        text: '',
        floating: true,
        align: 'right',
        verticalAlign: 'bottom',
        y: 15
    },
    legend: {
        layout: 'vertical',
        align: 'left',
        verticalAlign: 'top',
        x: 100,
        y: 70,
        floating: true,
        borderWidth: 1,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
    },
    xAxis: {
        // categories: ['Apples', 'Pears', 'Oranges', 'Bananas', 'Grapes', 'Plums', 'Strawberries', 'Raspberries']
        categories: <?php echo json_encode($graph_data["lable"] ??'') ?>,
        title: {
            text: 'Time Frame'
        }
    },
    yAxis: {
         min: 0,
            // max: 140,
            // tickInterval: 1000,
        title: {
            text: 'Amount ($)'
        },
        labels: {
            formatter: function () {
                return this.value;
            }
        }
    },
    tooltip: {
        formatter: function () {
            return '<b>' + this.series.name + '</b><br/>' +
                this.x + ': ' + this.y;
        }
    },
    plotOptions: {
        area: {
            fillOpacity: 0.5
        }
    },
    credits: {
        enabled: false
    },
    series: [{
        name: "",
        // data: [1, 0, 3, 0, 3, 1, 2, 1]
        data: <?php echo json_encode($graph_data["data"] ??'',JSON_NUMERIC_CHECK ) ?>
    }],
    exporting: {
    buttons: {
      contextButton: {
        menuItems: [
            "printChart",
                    "separator",
                    "downloadPNG",
                    "downloadJPEG",
                    "downloadPDF",
                    "downloadSVG",
                    "separator",
                    "downloadCSV",
                    "downloadXLS",
                    //"viewData",
                    // "openInCloud"
                    ]
      }
    }
    }
});

}
</script>

<style type="text/css">



  .title_div{
    border: 1px solid #ddd;
    padding: 5px;
  }
  
  .align-self {position: relative; margin-top: 17px;}

</style>
<script>
    $(document).ready(function() {
    $('.sample-class').select2();
    $selectElement = $('#dept_code').select2({
    placeholder: "Please select Department",
    allowClear: true
    });
    
    $('.sample-class1').select2();
    $selectElement2 = $('#category_code').select2({
    placeholder: "Please select  Category",
    allowClear: true
    });
  
   $('.sample-class2').select2();
    $selectElement3 = $('#subcat_id').select2({
    placeholder: "Please select  SubCategory",
    allowClear: true
  }); 
  
  $('.sample-class3').select2();
    $selectElement3 = $('#manuf').select2({
    placeholder: "--Select Manufacturer--  ",
    allowClear: true
  });
  $('.sample-class4').select2();
    $selectElement3 = $('#supl').select2({
    placeholder: " --Select Vendor--",
    allowClear: true
  });
   
});
</script>

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

<script type="text/javascript">

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

      var csv_export_url = '<?php echo route('HourlySalescsv'); ?>';
      
    
      csv_export_url = csv_export_url.replace(/&amp;/g, '&');

      $.ajax({
        url : csv_export_url,
        type : 'GET',
      }).done(function(response){
        
        const data = response,
        fileName = "hourly-sales-report.csv";

        saveData(data, fileName);
        $("div#divLoading").removeClass('show');
        
      });
    
  });



    $(document).on("click", "#pdf_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('HourlySalespdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "HourlySalesReportPdf.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "HourlySalesReportPdf.pdf";

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


<script>
    
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
    
    
</script>


<script type="text/javascript">

  
    $("#dept_code").change(function(){
      
        // console.log($(this).text);
        var deptCode = $(this).val();
      console.log(deptCode);
        
        var input = {};
        input['dept_code'] = deptCode;
        
        if(deptCode != ""){
    //      console.log(deptCode);
    
            
            var url = '<?php echo route('Hourlygetcategories'); ?>';
                
                url = url.replace(/&amp;/g, '&');
    
    
    
            $.ajax({
                url : url,
               // data : JSON.stringify(input),
               data : input,
                type : 'GET',
                contentType: "application/json",
                dataType: 'text json',
                success: function(response) {
            
                    // console.log(response);
                    if(response.length > 0){
                        
                        $('select[name="vcategorycode[]"]').select2().empty().select2({
                            placeholder: "Please select Category",
                            allowClear: true,
                            data: response
                            
                        });
                       
                    }
            
                    setTimeout(function(){
                        $('#successAliasModal').modal('hide');
                    }, 3000);
                },
                error: function(xhr) { 
                    // if error occured
                    // console.log(xhr);
                    return false;
                }
            });
        }
    });
    
     $("#category_code").change(function(){
      
        // console.log($(this).text);
        var cat_id = $(this).val();
    
        
        var input = {};
        input['cat_id'] = cat_id;
        
        if(cat_id != ""){
    //      console.log(deptCode);
    
               var url = '<?php echo route('Hourlyget_subcategories'); ?>';
                
                url = url.replace(/&amp;/g, '&');
            
               
    
    
    
            $.ajax({
                url : url,
                // data : JSON.stringify(input),
                data:input,
                type : 'GET',
                contentType: "application/json",
                dataType: 'text json',
                success: function(response) {
            
                    // console.log(response);
                    if(response.length > 0){
                        
                       $('select[name="subcat_id[]"]').select2().empty().select2({
                            placeholder: "Please select Sub Category",
                            allowClear: true,
                            data: response
                           
                       });
                       
                    }
            
                    setTimeout(function(){
                        $('#successAliasModal').modal('hide');
                    }, 3000);
                },
                error: function(xhr) { 
                    // if error occured
                    // console.log(xhr);
                    return false;
                }
            });
        }
    });
</script>
<style>
    .select2-container--default .select2-selection--single{
    border-radius: 0px !important;
    height: 35px !important;
   
  }
  .select2.select2-container.select2-container--default{
  /*width: 100% !important;*/
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 35px !important;
  }
  
</style>

<script type="text/javascript">

    $(document).ready(function() {
        var start = moment().startOf('day');
        //var start = moment().subtract(29, 'days');
        var end = moment().endOf('day');
        console.log(end.format('MM/DD/YYYY HH:mm:ss'));
    
        function cb(start, end) {
            
            start_date = start.format('MM/DD/YYYY  HH:mm:ss');
            end_date = end.format('MM/DD/YYYY  HH:mm:ss');
            
            // $('input[name="dates"]').html(start.format('MM/DD/YYYY HH:mm:ss') + '-' + end.format('MM/DD/YYYY HH:mm:ss'));
            
            var sDate = "<?php echo isset($start_date) ? $start_date :date('m/d/Y');?>";
            var eDate = "<?php echo isset($end_date) ? $end_date : date('m/d/Y');?>";
            
            if(typeof(sDate) != "undefined" && sDate !== null) {
                $('input[name="dates"]').html(start.format('MM/DD/YYYY HH:mm:ss') + '-' + end.format('MM/DD/YYYY HH:mm:ss'));
            }else{
                $('input[name="dates"]').val(start_date + ' - ' + end_date);
            }
            
            
            console.log(start_date);
            
            $('input[name="start_date"]').val(start.format('YYYY-MM-DD HH:mm:ss'));
            $('input[name="end_date"]').val(end.format('YYYY-MM-DD HH:mm:ss' ));
            
            
        }    
        
        
      $('input[name="dates"]').daterangepicker({
         timePicker: true,
         timePicker24Hour: true,
         timePickerSeconds: true,
       
        
        startDate: "<?php echo isset($start_date) ? $start_date :date('m/d/Y');?>",
        endDate: "<?php echo isset($end_date) ? $end_date : date('m/d/Y');?>",
        locale: {
          format: 'MM/DD/YYYY HH:mm:ss'
          
        },
        ranges: {
        
        'Today': [moment().startOf('day'), moment()],
           'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
           'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
           'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);
      
      $(function() {

   

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
  
   $("div#divLoading").addClass('show');
  
  
  
});
</script>    

<script>
    function itemlist(salesids, time_frame, trn_date){
        
        // var start_date = '<?php echo $p_start_date ??''; ?>';
        // var end_date = '<?php echo $p_end_date ??''; ?>';
         var url = '<?php echo route('Getslotitem'); ?>';
      
            url = url.replace(/&amp;/g, '&');
            
        $('#trn_date').text(trn_date);
        $('#time_frame').text(time_frame);
        
        $.ajax({
            
            url: url,
            type : 'GET',
            // data : {start_date: start_date, end_date: end_date, time: time},
            data: {salesids: salesids},
            success: function(response) {
                
                // console.log(response);
                var list;
                $.each(response, function(index, element){
                    
                    list += '<tr>';
                    list += '<td>'+element.vitemname+'</td>';
                    list += '<td>'+element.no_of_times+'</td>';
                    list += '</tr>';
                });
                
                
                $("#itemlist").empty();
                $("#itemlist").append(list);
                $("#itemModal").modal('show');
            }
        });
    }
</script>

<!-- Modal -->
<div class="modal fade" id="itemModal" role="dialog">
    <div class="modal-dialog">
        
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                <h3 class="modal-title">Item List</h3>
            </div>
            <div class="modal-body" id="print_item">
                <center><h4>Hourly Sales Report</h4></center>
                <div id="trn_detail">
                    <div class="row">
                      <div class="col-md-12">
                        <div class='col-md-6'>
                            <p><b>Store Name: </b>{{ session()->get('storeName') }} </p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class='col-md-6'>
                            <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ??'';?></p>
                        </div>    
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class='col-md-6'>
                            <p><b>Store Phone: </b><?php echo $store[0]->vphone1??''; ?></p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class='col-md-6'>
                            <p>
                                <span>
                                    <b>Date: </b>
                                    <span id="trn_date"></span>
                                </span>
                            </p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class='col-md-6'>
                            <p>
                                <span>
                                    <b>Time: </b>
                                    <span id="time_frame"></span>
                                </span>
                            </p>
                        </div>
                      </div>
                    </div>
                    
                </div>
                <!--<p id="itemlist"></p>-->
                <table class="table table-hover">
                    <thead>
                        <th>Item Name</th>
                        <th>Sold(No. Of Times)</th>
                    </thead>
                    <tbody id="itemlist">
                        
                    </tbody>
                </table>
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary Pull-left" id="print_button">Print</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        
    </div>
</div>

<script>
    $(document).on('click', '#print_button',function(){
        
        var prtContent = document.getElementById("print_item");
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close(); 
        
    
    });
</script>
    
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