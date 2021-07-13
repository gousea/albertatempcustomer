@extends('layouts.layout')
@section('title')
Sales History Report
@endsection
@section('main-content')
<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase">Sales History Report</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($reports['result']) && count($reports['result']) > 0){ ?>
                       
                            
                            <a type="button" class="btn btn-gray headerblack  buttons_menu btn_width "  href="#" id="csv_export_btn_excel" > EXCEL
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu btn_width"  href="#" id="csv_export_btn">CSV
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu btn_width " href="#" id="export_btn_email"  > SEND EMAIL
                            </a>
                            
                        <?php } ?>
                </div>
            </div> 
        </div>
    </nav>


      <div class="container">
            <br>
               <h6><span>SEARCH PARAMETERS </span></h6>
               
        <br>
    
                <form method="post" id="filter_form">
                @csrf
                @method('post')
                <div class="row">
                        <div class='col-sm-2'>
                            
                            <select id='selectBy' class='form-control' name='select_by'>
                                <?php 
                                    foreach($select_by_list as $k => $v) {
                                        echo "<option value='{$k}'";
                                        
                                        if($k === $selected_select_by){
                                            echo 'selected';
                                        }
                                        
                                        echo ">{$v}</option>";
                                    } 
                                ?>
                            </select>
                            
                        </div>
                        
                        
                        <div class='inputBox col-sm-9' id='divWeek' style=''>
                           <label style=" width: 160px;">
                                <input type='number' name='input_week' class='form-control' id='inputWeek' placeholder='Weeks (1 - 13)' min='1' max='13' onKeyUp="if(this.value > 13) $(this).val(13)" value="<?php if(isset($weeks)){ echo $weeks; }?>">
                            </label>
                                <input type=checkbox name='include_current_week' value='yes' <?php if(isset($include_current_week) && ($include_current_week === 'yes')){ echo 'checked';} ?> > Include Current Week (Week No. <?php echo date('W', strtotime('today')); ?>)
                            
                        </div>
                        
                        
                        
                        <div class=' col-sm-9 inputBox ' id='divMonth' style='display: none'>
                            <label style=" width: 160px;">
                                <input type='number'  name='input_month' class='form-control' id='inputMonth' placeholder='Months (1 - 12)' min='1' max='12'  onKeyUp="if(this.value>=12) { $(this).val(12) }"  value="<?php if(isset($months)){ echo $months; }?>">
                         
                            </label>
                                  <input type=checkbox name='include_current_month' value='yes' <?php if(isset($include_current_month) && ($include_current_month === 'yes')){ echo 'checked';} ?> > Include Current Month (<?php echo date('F', strtotime('today')); ?>)
                            
                        </div>
                        
                        
                        <div class='col-sm-3 inputBox' id='divYear' style='display: none'>
                            <select class='form-control' id='inputYear' placeholder='Enter the Year Number' name='input_year'>
                                <option value='<?php echo date("Y",strtotime("-1 year")); ?>' <?php if(isset($year) === date("Y",strtotime("-1 year"))){ echo 'selected'; } ?> ><?php echo date("Y",strtotime("-1 year")); ?></option>
                                <option value='<?php echo date("Y"); ?>' <?php if(isset($year) === date("Y")){ echo 'selected'; } ?> ><?php echo date("Y").' (YTD)'; ?></option>
                            </select>
                            
                        </div>
                        
                        <div class='col-sm-3 inputBox' id='divCustom' style='display: none'>
                            
                                <input class='form-control' id='customDateRange' placeholder='Enter Date Range' name='custom_date_range' value='<?php echo isset($custom_date_range);?>' style='width:111%;'>
                          
                        </div>
                        
                    </div>

                    <div class='row'>&nbsp;</div>                    
                       
                    <h6><span>ITEM FILTER </span></h6>
                    <br>
                    <div class="row" id="div_item_listing">
                        <div class="col-md-12 ">
                            <div class="box-body table-responsive" style="overflow-x: unset;">
                            <table id="item_listing" class="table  table-striped t_body rcorner"  cellspacing="0" cellpadding="0" border="0">
                                <thead class="rcorner">
                                    <tr class="headermenublue"  cellspacing="0" cellpadding="0" border="0"  style="border-bottom-color:red">
                                            <th style="width: 15%;position: relative;border-bottom-color:#286fb7 " >ITEM NAME</th>
                                           
                                            <th  style="border-bottom-color:#286fb7 ">SKU</th>
                                            
                                            <th style="width: 22%;border-bottom-color:#286fb7 ">PRICE</th>
                                            <th  style="border-bottom-color:#286fb7 ">DEPARTMENT</th>
                                            <th style="border-bottom-color:#286fb7 ">CATEGORY</th>
                                            <th style="border-bottom-color:#286fb7 ">SUBCATEGORY</th>
                                            <th  style="border-bottom-color:#286fb7 ">VENDOR</th>
                                             <th style="width: 5%;position: relative ;border-bottom-color:#286fb7 ;">SIZE</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                        </div>
                    </div> 
                    
                     <div class="row" >
                          <div class='col-sm-2'>
                            <input type='submit' class="btn btn-success rcorner header-color" value='Generate'>
                        </div>

                     </div>
                   
                    
                </form>            
                    
                    
           <br>
               <h6><span>SALES HISTORY </span></h6>
               
        <br>            
                    
               <?php if(isset($reports_css['result']) && count($reports_css['result']) > 0){ ?>
               
               <div class="row" style="float: right;">   
                    <!--<i class="fa fa-file-excel-o" aria-hidden="true" style = "padding-top: 10px;"></i>-->
                     <a id="csv_export_btn_excel" href="" class="csvt" style="margin-right: 38px;margin: 20px;">
                       EXCEL  <i class="fa fa-file-excel-o" aria-hidden="true" style = "padding-top: 10px;"></i> 
                    </a>
                    
                    <a id="csv_export_btn" href="" class="csvt" style="margin-right: 38px;margin: 20px;">
                       CSV  <i class="fa fa-file-excel-o" aria-hidden="true" style = "padding-top: 10px;"></i> 
                    </a>
                    
                    <a id="export_btn_email" href="" class="" style="margin-right: 38px;">
                        Send Email  <i class="fa fa-envelope-o" aria-hidden="true" style = "padding-top: 10px;"></i>
                    </a>
                </div>
                <?php }?>
                <?php if(isset($reports['result']) && count($reports['result']) > 0){ ?>
                    
                    <table class="table-stripe promotionview" id="sorttest" style="width:100%" >
                        <thead>
                            <tr style="height: 47px;" >
                                <td  style="width: 54px;" class="th_color text-uppercase" ><b> &nbsp; &nbsp;&nbsp;Item Name <i class="fa fa-filter" aria-hidden="true"></i></b></td>
                                <td style="width: 54px;" class="th_color text-uppercase" ><b>Size <i class="fa fa-filter" aria-hidden="true"></i></b></td>
                                <?php foreach($reports['header'] as $h){ ?>
                                    <td style="width: 54px;" class="th_color text-uppercase" ><b><?php echo $h; ?>  <i class="fa fa-filter" aria-hidden="true"></i></b></td>
                                <?php } ?>                              
                            </tr>
                        </thead>
                        
                        <tbody>
                            
                            <?php foreach($reports['result'] as $r){ ?>
                                <tr style="height: 47px;">
                                    <?php foreach($r as $k => $v){ 
                                            if($k === 'iitemid'){ continue;}    
                                    ?>
                                        <td <?php if($k !== 'vitemname'){?>
                                        <?php } ?> >
                                            <?php echo $v;?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>                        
                            
                        </tbody>
                        
                    </table>

                <?php }elseif(isset($recd_inputs) && $recd_inputs === 'yes'){ ?>
                        <div class="row">
                            <div class="col-md-12"><br><br>
                                <div class="alert alert-info text-center">
                                    <strong>Sorry no data found!</strong>
                                </div>
                            </div>
                        </div>
                <?php } ?>
            
        
      </div>
  </div>


@endsection

@section('page-script')

<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">-->

<!--<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" defer></script>-->
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<!--<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>-->

 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js"></script>
 <link href="https://mottie.github.io/tablesorter/css/theme.default.css" rel="stylesheet">
 
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
    

<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">-->
 
  <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

<script src= "//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"> 

<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/reportline.css') }}">
<!--</script> -->
<script>
    $(document).ready(function(){
        var url = '<?php echo isset($searchitem);?>';
        var departments_data = "{{$departments}}";
        var departments = unEntity(departments_data);
        var categories_data = "{{$categories}}";
        var categories = unEntity(categories_data);
        var subcategories_data = "{{$subcategories}}";
        var subcategories = unEntity(subcategories_data);
        var suppliers_data = "{{$suppliers}}";
        var suppliers = unEntity(suppliers_data);
        var price_select_by_data = "{{$price_select_by}}"; 
        var price_select_by = unEntity(price_select_by_data);
        $('#item_listing thead tr').clone(true).appendTo( '#item_listing thead' );
        $('#item_listing thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            if(title == 'DEPARTMENT'){
                $(this).html(departments)
            }else if(title == "CATEGORY"){
                $(this).attr('id', 'thCategory');
                $(this).html(categories);
            }else if(title == "SUBCATEGORY"){
                $(this).attr('id', 'thSubCategory');
                $(this).html(subcategories);
            }else if(title == 'VENDOR'){
                $(this).html(suppliers)
            }else if(title == 'SIZE'){
                $(this).html('<input type="text" autocomplete="off" id="search_size" name="size" class="search_text_box1" placeholder="Size" value="<?php echo isset($size) ? $size : ''; ?>"/>')
            }else if(title == 'ITEM NAME'){
                $(this).html( '<input type="text" autocomplete="off" id="search_item_name" name="item_name" class="search_text_box1" placeholder="Search" value="<?php echo isset($item_name) ? $item_name : ''; ?>"/>' );
            }else if(title == 'SKU'){
                $(this).html( '<input type="text" autocomplete="off" id="search_sku" name="barcode" class="search_text_box1" placeholder="Search" value="<?php echo isset($barcode) ? $barcode : ''; ?>"/>' );
            }else if(title == 'PRICE') {
                $(this).html(price_select_by);
            }else{
                $(this).html( '' );
            }
        })
        $("div#divLoading").removeClass('show');        
        $("#item_listing_filter").hide();
        $("#item_listing_processing").remove();
        $(".dataTables_scrollBody").remove();
    });
</script>
<style type="text/css">
  .table.table-bordered.table-striped.table-hover thead > tr{
         	background: #03a9f4 none repeat scroll 0 0 !important;
        }
        
        select{
            color:black;
            border-radius: 4px;
            height:30px;
            font-size: 10px;
            background-color: #fff;
        }

        .autocomplete {
          /*the container must be positioned relative:*/
          position: relative;
          display: inline-block;
        }
        
        .autocomplete-items {
          position: absolute;
          border: 1px solid #d4d4d4;
          border-bottom: none;
          border-top: none;
          z-index: 99;
          /*position the autocomplete items to be the same width as the container:*/
          top: 100%;
          left: 0;
          right: 0;
        }
        .autocomplete-items div {
          padding: 10px;
          cursor: pointer;
          background-color: #fff;
          color: #000;
          border-bottom: 1px solid #d4d4d4;
        }
        .autocomplete-items div:hover {
          /*when hovering an item:*/
          background-color: #e9e9e9;
        }
        .autocomplete-active {
          /*when navigating through the items using the arrow keys:*/
          background-color: DodgerBlue !important;
          color: #ffffff;
        }
        
        
        .ui-menu-item-wrapper{
            font-family:Liberation Sans;
            font-size:9px;
            font-weight:normal;
        }
        .ui-menu-item-wrapper:hover{
            font-size:10px;
            font-weight:bold;
        }
        
        thead > tr, tbody{
            /*display:block;*/
        }
      .table.table-bordered.table-striped.table-hover thead > tr {
          /*background-color: #2486c6;*/
          color: #fff;
          overflow-x: none;
      }
      tr.header{
            cursor:pointer;
        }
        
        tr.header > th{
            /*background-color: #DCDCDC;*/
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
        .search_text_box1{
            width:100%;
            color:black;
            border-radius: 4px;
            height:28px;
        }
        .search_text_box{
            width:27%;
            color:black;
            border-radius: 4px;
            height:28px;
            padding-left: 1px;
            padding-right: 1px;
            margin-left:5px;
        }
        .search_text_box2{
            width:56%;
            color:black;
            border-radius: 4px;
            height:28px;
            margin-left:5px;
        }
        .search_text_box_if {
            width:27%;
            color:black;
            border-radius: 4px !important;
            height:28px;
            padding-left: 1px;
            padding-right: 1px;
            margin-left:5px;
        }

        .search_text_box_else {
            width:56%;
            color:black;
            border-radius: 4px !important;
            height:28px;
            margin-left:5px;
        }
</style>
<script type="text/javascript">
     $(document).ready(function() {
    //      $('#sorttest').DataTable( {
    //             dom: 'Bfrtip',
    //             bPaginate: false,
    //     "buttons": [
    //       'excel'
          
    //     ]
        
    // } ); 
         
    $('#sorttest_filter').hide();
    
        $("#btnPrint").printPage();
        $('.header').toggleClass('expand').nextUntil('tr.add_space').slideUp(100);
        //  $("#sorttest")..tablesorter({widgets: ['zebra']});
        
    $("#sorttest").tablesorter({
           theme: 'blue',
          sortInitialOrder: "desc",
      
    });
    // $("#sorttest").tablesorter({widgets: ['zebra']});
        var c=0
        $('td').click(function(e) {
         c++;   
        var txt = $(e.target).text();
        
            if(c%3==0){
              $('table').trigger('sortReset');
              return false;
            }
      });
      
      
  
  //
     });
     
     
    $('.header').click(function(){
        $(this).toggleClass('expand').nextUntil('tr.add_space').slideToggle(100);
    });
    $(document).ready(function() {
      $('#inputSKU').select2({
        placeholder: "Enter SKU",
        minimumInputLength: 6
      });
      $('#inputItemName').select2({
        placeholder: "Enter Item Name",
      });
      $('#inputVendor').select2({
        placeholder: "Vendors",
      });
      $('#inputDepartment').select2({
        placeholder: "Departments",
      });
      $('#inputCategory').select2({
        placeholder: "Categories",
      });
      $('#inputSubCategory').select2({
        placeholder: "Sub Categories",
      });
      $('#inputSize').select2({
        placeholder: "Size",
      });
       var selectBy = '<?php if(isset($selected_select_by)){ echo $selected_select_by; } else { echo 'w'; }?>';
        $('.inputBox').hide();
        switch(selectBy) {
            case 'm':
                $('#divMonth').show();
                break;
                
            case 'y':
                $('#divYear').show();
                break;
                
            case 'c':
                $('#divCustom').show();
                break;
                
            default:
                $('#divWeek').show();
                break;
        }
            var is_custom_date = false;
            
            var p_start_date = "<?php echo isset($from) ? $from : '';?>";
            var p_end_date = "<?php echo isset($to) ? $to : '';?>";
            
            if(p_start_date !== '' && p_end_date !== ''){
                var start = p_start_date;
                var end = p_end_date;
                is_custom_date = true;
            } else {
                var start = moment().subtract(1, 'days');
    		    var end = moment();
            }
            
            var today = moment();
            minDate = new Date(today);
            var pastYear = minDate.getFullYear() - 1;
            minDate = '01/01/'+pastYear;
        function cb(start, end) {
            if(is_custom_date !== true){
                $('#customDateRange').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            } else {
                $('#customDateRange').html(start+' - '+end);
            }
        }
        $('#daterange').daterangepicker({ startDate: '03/05/2005', endDate: '03/06/2005' });
        $('#customDateRange').daterangepicker({
            timePicker: true,
            startDate: start,
            endDate: end,
            maxDate: today,
            minDate: minDate, 
            locale: {
              format: 'M/D/YYYY hh:mm A'
            },
            ranges: {
                'Today': [moment().format('M/D/YYYY'), moment()],
                'Yesterday': [moment().subtract(1, 'days').format('M/D/YYYY'), moment().subtract(1, 'days').format('M/D/YYYY 11:59 PM')],
                'Last 7 Days': [moment().subtract(6, 'days').format('M/D/YYYY'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days').format('M/D/YYYY'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);
      });
    
      $(document).on('keyup', '#divInputSku .select2-search__field', function(){
          if($(this).val().length < 6){
              return false;
          }
          var get_sku_url = "{{route('saleshistoryreport_gettingsku')}}";
          get_sku_url  = get_sku_url.replace(/&amp;/g, '&');
          var input = {ids: $(this).val()};
          $.ajax({
              url: get_sku_url,
              type: 'POST',
              data: input,
              dataType: 'json',
              success:function(data) {
                  $('#inputCategory').prop("disabled", false); 
                  $('#inputSKU').select2().empty().select2({data: data});
                  $('#inputSKU').trigger('change');
              }
          });
      });
      $('#inputDepartment').on('change', function(){     
        var get_categories_url = "{{route('saleshistoryreport_getcategory')}}";
        get_categories_url  = get_categories_url.replace(/&amp;/g, '&');
        var input = {ids: $(this).val()};                
        $.ajax({
            url: get_categories_url,
            type: 'GET',
            data: input,
            dataType: 'json',
            success:function(data) {
                $('#inputCategory').prop("disabled", false);
                $('#inputCategory').select2('destroy').empty().select2({placeholder: "Select Categories (If not selected all the categories will be considered)", data: data});
            }
        });
      });
    $('#inputCategory').on('change', function(){
        var get_subcategories_url = "{{route('saleshistoryreport_getsubcategory')}}";
        $get_subcategories_url  = get_subcategories_url.replace(/&amp;/g, '&');
        var input = {ids: $(this).val()};
        $.ajax({            
            url: $get_subcategories_url,
            type: 'GET',
            data: input,
            dataType: 'json',
            success:function(data) {
                $('#inputSubCategory').prop("disabled", false);
                $('#inputSubCategory').select2('destroy').empty().select2({placeholder: "Select Sub Categories (If not selected all the subcategories will be considered)", data: data});
            }
        });
    });    
    $(document).on('change',"#end_date, #start_date",function(){
      var reportdata_url = "{{route('saleshistoryreport_getreportdata')}}";
      reportdata_url  = reportdata_url.replace(/&amp;/g, '&');
      var dates  = $("#start_date").val();
      var end_date    = $("#end_date").val();    
      dates_array =dates.split("-");
      start_date = dates_array[0];
      end_date = dates_array[1];
      if(start_date != "" && end_date != ""){
          $.ajax({
              url     : reportdata_url,
              data    : {start_date:start_date, end_date:end_date},
              type    : 'GET',
          }).done(function(response){
              if(response)
              {
                  $('#batch_id').find('option').remove();
                  var obj = jQuery.parseJSON(response);
                  $.each(obj,function(key,val){
                      $('#batch_id').append($("<option></option>")
                          .attr("value",val['ibatchid'])
                          .text(val['vbatchname'])); 
                  })
                  $('#batch_id').select2();
              }
          });
      }
    });
    $(document).on('submit', '#filter_form', function(event) {
        var selectBy = $('#selectBy').val();
        if(selectBy === 'm' && $('#inputMonth').val() === ''){
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: "Please Enter the Number of Months", 
                callback: function(){}
            });
            return false;
        }
        if(selectBy === 'w' && $('#inputWeek').val() == ''){
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: "Please Enter the Number of Weeks", 
                callback: function(){}
            });
            return false;
        }
        if(selectBy === 'y' && $('#inputYear').val() === ''){
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: "Please Select the Year", 
                callback: function(){}
            });
            return false;
        }
        if(selectBy === 'c' && $('#customDateRange').val() === ''){
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: "Please Select the Date Range", 
                callback: function(){}
            });
            return false;
        } else {            
            var itemName = $('#search_item_name').val();
            var size = $('#search_size').val();
            var sku = $('#search_sku').val();
            var value1 = $('#select_by_value_1').val();
            var value2 = $('#select_by_value_2').val();
            var dept_code = $("#dept_code").val();
            var category_code = $('#category_code').val();
            var sub_category_id = $('#sub_category_id').val();supplier_code
            var supplier_code = $('#supplier_code').val();
            var price_select_by = $('#price_select_by').val();
            if(itemName == '' && size == '' && sku == '' && value1 == '' && dept_code == 'all' && category_code == 'all' && sub_category_id == 'all' && supplier_code == 'all'){
                bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: "Please fill / select at least one of the filters", 
                    callback: function(){}
                });
                return false;
            }            
            if(price_select_by == 'between' && (value1 == '' || value2 == '')){
                bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: "Between needs two prices", 
                    callback: function(){}
                });
                return false;
            }
            var dateBreakup = $('#customDateRange').val().split('-');
            var from = dateBreakup[0];
            var to = dateBreakup[2];            
            if(moment(from, 'MM/DD/YYYY').isValid() !== true || moment(to , 'MM/DD/YYYY').isValid()){                
                bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: "Please Select the Date Range", 
                    callback: function(){}
                });
                return false;   
            }
            var from = Date.parse(from);
            var to = Date.parse(to); 
            if(from > to){
                bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: "From date must be <b>less than</b> To date!", 
                    callback: function(){}
                });
                return false;
            }
            $('#inputFrom').val(from);
            $('#inputTo').val(to);   
        }        
        $("div#divLoading").addClass('show');
    });
    var item_name_list = [];
    // $(window).load(function() {
    //     $("div#divLoading").removeClass('show');
    // });    
    
    
    function unEntity(str){
        return str.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">");
    }
    $(document).ready(function(){
        var get_item_names_url = "{{route('saleshistoryreport_getiteamname')}}";
        get_item_names_url  = get_item_names_url.replace(/&amp;/g, '&');
        $( "#search_item_name" ).autocomplete({
            
            source: function( request, response ) {
                console.log($('#search_item_name').val());
                var input = {term: $('#search_item_name').val()};
                $.ajax( {
                  url: get_item_names_url,
                  type: 'GET',
                  dataType: "json",
                  data: input,
                  success: function( data ) {
                    response( data );
                  }
                });
          },
          minLength: 1,
          select: function( event, ui ) {
            var newString = ui.item.value.replace(/\s+/g,' ').trim();
            $( "#search_item_name" ).val(newString);
          }
        } );
        var get_barcodes_url = "{{route('saleshistoryreport_getbarcode')}}";
        get_barcodes_url  = get_barcodes_url.replace(/&amp;/g, '&');        

        $( "#search_sku" ).autocomplete({
            source: function( request, response ) {
                var input = {term: $('#search_sku').val()};
                $.ajax( {
                  url: get_barcodes_url,
                  type: 'GET',
                  dataType: "json",
                  data: input,
                  success: function( data ) {
                    response( data );
                  }
                });
          },
          minLength: 1,
          select: function( event, ui ) {
            var newString = ui.item.value.replace(/\s+/g,' ').trim();
            $( "#search_sku").val(newString);
          }
        });
        // Search Size
        var get_sizes_url = "{{route('saleshistoryreport_getsize')}}";
        get_sizes_url  = get_sizes_url.replace(/&amp;/g, '&');
        $( "#search_size" ).autocomplete({
            source: function( request, response ) {
                var input = {term: $('#search_size').val()};
                $.ajax( {
                  url: get_sizes_url,
                  type: 'GET',
                  dataType: "json",
                  data: input,
                  success: function( data ) {
                    response( data );
                  }
                } );
          },
          minLength: 1,
          select: function( event, ui ) {
            var newString = ui.item.value.replace(/\s+/g,' ').trim();
            $( "#search_size" ).val(newString);
          }
        });        
        
        var select_by = $('#price_select_by').val(); console.log(select_by);
        var html='';
        if(select_by === 'between'){
            html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box" placeholder="Enter Amt" value="<?php echo ($select_by_value_1 ?? 0); ?>"/>';
            html += '<input type="text" autocomplete="off" name="select_by_value_2" id="select_by_value_2" class="search_text_box" placeholder="Enter Amt" value="<?php echo ($select_by_value_2 ?? 0); ?>"/>'
        } else {
            
            html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box2" placeholder="Enter Amt" value="<?php echo ($select_by_value_1 ?? 0); ?>"/>'
        }
        $('#selectByValuesSpan').html(html);
    }); 
    $(document).on('change', '#price_select_by', function(){
        var select_by = $(this).val();
        var html='';
        if(select_by === 'between'){
            html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box" placeholder="Enter Amt" value="<?php echo isset($select_by_value_1); ?>"/>';
            html += '<input type="text" autocomplete="off" name="select_by_value_2" id="select_by_value_2" class="search_text_box" placeholder="Enter Amt" value="<?php echo isset($select_by_value_2); ?>"/>'
        } else {
            
            html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box2" placeholder="Enter Amt" value="<?php echo isset($select_by_value_1); ?>"/>'
        }
        $('#selectByValuesSpan').html(html); 
    });
    $(document).on('change', '#dept_code', function(){
        var get_categories_url = "{{route('saleshistoryreport_getcategory')}}";
        get_categories_url  = get_categories_url.replace(/&amp;/g, '&');
        var input = {dept_code: $(this).val()};
        $.ajax({            
            url: get_categories_url,
            type: 'GET',
            data: input,
            success:function(data) {
                $('#thCategory').html(data);   
            }     
        });
    });
    $(document).on('change', '#category_code', function(){
        var get_subcategories_url = "{{route('saleshistoryreport_getsubcategory')}}";
        get_subcategories_url  = get_subcategories_url.replace(/&amp;/g, '&');
        var input = {category_code: $(this).val()};
        $.ajax({
            url: get_subcategories_url,
            type: 'GET',
            data: input,
            success:function(data) {
                $('#thSubCategory').html(data);   
            }
        });
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
    // $(document).on("click", "#csv_export_btn", function (event) {
    //     event.preventDefault();
    //     $("div#divLoading").addClass('show');
    //     var csv_export_url = "{{route('saleshistoryreport_gettingCSV')}}";
    //     csv_export_url = csv_export_url.replace(/&amp;/g, '&');
    //     $.ajax({
    //         url : csv_export_url,
    //         type : 'GET',
    //     }).done(function(response){
    //         const data = response,
    //         fileName = "Z-report.csv";
    //         saveData(data, fileName);
    //         $("div#divLoading").removeClass('show');
    //     });
    // });
    $(document).on("click", "#pdf_export_btn", function (event) {
        event.preventDefault();
        $("div#divLoading").addClass('show');
        var pdf_export_url = "{{route('saleshistoryreport_savePDF')}}";
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');
        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
            if (req.readyState === 4 && req.status === 200) {
                if (typeof window.navigator.msSaveBlob === 'function') {
                    window.navigator.msSaveBlob(req.response, "Z-Report.pdf");
                } else {
                    var blob = req.response;
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Z-Report.pdf";
                    document.body.appendChild(link);
                    link.click();
                }
            }
            $("div#divLoading").removeClass('show');
        };
        req.send();
    });
    $('#selectBy').on('change', function(){
        var selectBy = $(this).val();        
        $('.inputBox').hide();
        switch(selectBy) {
        
            case 'm':
                $('#divMonth').show();
                break;
                
            case 'y':
                $('#divYear').show();
                break;
                
            case 'c':
                $('#divCustom').show();
                break;
                
            default:
                $('#divWeek').show();
                break;
        } 
    });   
</script>
<script>
    $(document).on("click", "#csv_export_btn_excel", function (event) {
    
         event.preventDefault();
         $("#sorttest").table2excel({
            exclude:".sorttest",
 
            filename: "Sales_History_Report",
             fileext:".xlsx",

          
        });
    
    });
        
</script>
   
 <script>  
 $(document).on("click", "#csv_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('salehistorycsv'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "sales.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });
</script>
<script>
       $(document).on("click", "#export_btn_email", function (event) {

        event.preventDefault();
        
        var get_email_url = "{{route('salehistoryemail')}}";
        get_email_url  = get_email_url.replace(/&amp;/g, '&');
        var input = {dept_code: $(this).val()};
        $.ajax({            
            url: get_email_url,
            type: 'GET',
            data: input,
            success:function(data) {
                // $('#thCategory').html(data);   
                alert("If you have not yet received an email from Alberta, check your Spam folder,");
            }     
        });
    });
</script>
<style>
.dataTables_wrapper .dt-buttons {
  float:right;  
  text-align:center;
  padding-top:0px;
  
  font-size:15px;
    
 
}
button.dt-button, div.dt-button, a.dt-button{
    border:0px;
    background:none;
    color: #2a6496;
    font-size:15px;
    outline:none;
    
    
}
button.dt-button:hover, div.dt-button:hover, a.dt-button:hover{
    text-decoration: underline; 
    border:0px;
    background:none;
   
   
}

button.dt-button:hover:not(.disabled), div.dt-button:hover:not(.disabled), a.dt-button:hover:not(.disabled){
   text-decoration: underline; 
    border:0px;
    background:none;  
    
}
/*button.dt-button:active:not(.disabled), div.dt-button:active:not(.disabled), a.dt-button:active:not(.disabled){*/
/*   background:none;   */
/*}*/
button.dt-button:active, div.dt-button:active, a.dt-button:active{
    border:0px;
    background-color: none;
     
   
}



</style>
<style>
/*.tablesorter-header {*/
/*    background-image: url(data:image/gif;base64,R0lGODlhFQAJAIAAACMtMP///yH5BAEAAAEALAAAAAAVAAkAAAIXjI+AywnaYnhUMoqt3gZXPmVg94yJVQAAOw==);*/
/*    background-position: center right;*/
/*    background-repeat: no-repeat;*/
/*    cursor: pointer;*/
/*    white-space: normal;*/
/*    padding-right:0px; */
/*    border-spacing: 0;*/
/*    text-align:left;*/
/*}*/
/*.tablesorter-headerAsc {*/
/*    padding-right:0px; */
/*    background-image: url(data:image/gif;base64,R0lGODlhFQAEAIAAACMtMP///yH5BAEAAAEALAAAAAAVAAQAAAINjI8Bya2wnINUMopZAQA7);*/
/*}*/
/*.tablesorter-headerDesc {*/
/*    background-image: url(data:image/gif;base64,R0lGODlhFQAEAIAAACMtMP///yH5BAEAAAEALAAAAAAVAAQAAAINjB+gC+jP2ptn0WskLQA7);*/
/*    padding-right:0px; */
    
/*}*/
/*.tablesorter .sorter-false {*/
/*    background-image: none;*/
/*    cursor: default;*/
/*    padding: 0px;*/
/*}    */

.th_class{
  border-spacing:0; /* Removes the cell spacing via CSS */
  border-collapse: collapse;  /* Optional - if you don't want to have double border where cells touch */
}    
.t_body {background-color: #286fb7;}
</style>

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
.tborder{
    border-width: 0px;
}
#category_code ,#sub_category_id,#search_size,#search_sku{
    width:97px;
}
</style>
@endsection