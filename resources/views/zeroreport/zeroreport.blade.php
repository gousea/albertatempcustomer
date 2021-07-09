@extends('layouts.layout')
@section('title')
ZERO MOVEMENT Report
@endsection
@section('main-content')
<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase">ZERO MOVEMENT Report</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($zeromovement_reports) && count($zeromovement_reports) > 0){ ?>
                       
                            <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small btn_width"
                            id="delete_all" ><i class="fa fa-trash"></i>&nbsp;&nbsp;DELETE ALL</a>
                            
                                 <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small btn_width"
                            id="parent_delete" ><i class="fa fa-trash"></i>&nbsp;&nbsp;DELETE </a>
                            
                                 <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small btn_width"
                            id="save_button" ><i class="fa fa-trash"></i>&nbsp;&nbsp; DEACTIVATE</a>
                            
                            <a type="button" class="btn btn-gray headerblack  buttons_menu btn_width " href="{{route('zeromovementreportcsv_export')}}"  id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu btn_width"  href="{{route('zeromovementreportprint_page')}}" id="btnPrint">PRINT
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu btn_width " id="pdf_export_btn" href="{{route('zeromovementreportpdf_save_page')}}" > PDF
                            </a>
                            
                        <?php } ?>
                </div>
            </div> 
        </div>
    </nav>


  <div class="container">    

        <?php if(isset($zeromovement_reports_css)){ ?>
        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
          <a id="csv_export_btn" href="{{route('zeromovementreportcsv_export')}}" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</a>
            <a href="{{route('zeromovementreportprint_page')}}" id="btnPrint" class="pull-right" style="margin-right:10px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
           <?php if($pdfcount < 501){ ?>
            <a id="pdf_export_btn" href="{{route('zeromovementreportpdf_save_page')}}" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
          <?php } ?>
          </div>
        </div>
        <?php } ?>
 
 
    <br>
    <h6><span>SEARCH PARAMETERS </span></h6>
    <br>
        <form method="GET" id="filter_form" action="/zeromovementreport">
                 <div class="row">
                        <div class="col" style="width:250px;">
                            <input type="text" class="form-control rcorner" name="dates" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="dates" placeholder="mm/dd/yyyy - mm/dd/yyyy" readonly>
                        </div>
                        
                        <div class="col-md-0">
                          <input type="hidden" class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly>
                        </div>
                        <div class="col-md-0">
                          <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" readonly>
                        </div>
                      
                        <div class="col" style="width:200px;padding-left:1px;">
                            <select name="vdepcode" class="form-control " id="dept_code" style="display: inline-block;width: 87% !important;">
                                <option value="" >--Select Department--</option>
                                      <?php if(isset($departments) && count($departments) > 0){?>
                                  <option value="All" <?php if(isset($vdepcode) && $vdepcode == 'All') { ?> selected="selected" <?php } ?> >All</option>
                                    <?php foreach($departments as $department){ ?>
                                    <?php if(isset($vdepcode) && $vdepcode == $department['vdepcode']){?>
                                      <option value="<?php echo $department['vdepcode'];?>" selected="selected"><?php echo $department['vdepartmentname'];?></option>
                                    <?php } else { ?>
                                      <option value="<?php echo $department['vdepcode'];?>"><?php echo $department['vdepartmentname'];?></option>
                                    <?php } ?>
                                    <?php } ?>
                                  <?php } ?>
                            </select>
                        </div>
                          
                        <div class="col" style="width:200px;" >
                            <select name="vcategorycode" class="form-control" id="category_code" style="display: inline-block;width: 87% !important;">
                                            <option value="" >--Select Category--</option>
                                            <?php if(isset($categories) && count($categories) > 0){?>
                                                 <option value="All" <?php if(isset($vcategorycode) && $vcategorycode == 'All') { ?> selected="selected" <?php } ?> >All</option>
                                                    <?php if(isset($categories) && count($categories)){?>
                                                        <?php foreach($categories as $category){ ?>
                                                           <option value="<?php echo $category['vcategorycode'];?>" <?php if(isset($vcategorycode) && $vcategorycode == $category['vcategorycode']){ ?> selected <?php }?> ><?php echo $category['vcategoryname'];?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                            <?php } ?>
                                                  
                            </select>
                        </div>
                          
                        <div class="col" style="width:200px;padding:0px;">
                            <select name="subcat_id" class="form-control" id="subcat_id">
                                <option value="">--Select Sub Category--</option>
                                <?php if(isset($subcategories) && count($subcategories) ){?>
                                        <?php foreach($subcategories as $subcategory){ ?>
                                         <option value="<?php echo $subcategory['subcat_id'];?>" <?php if(isset($subcat_id) && $subcat_id == $subcategory['subcat_id']){ ?> selected <?php }?> ><?php echo $subcategory['subcat_name'];?></option>
                                         <?php } ?>
                                <?php } ?>
                                         
                            </select>
                        </div>
                          
                        <!--<div class="col-md-3">
                          <select name="report_by" class="form-control" id="report_by">
                            <option value="">Please Select Report</option>
                            <?php foreach ($byreports as $key => $value){ ?>
                              <?php if(isset($selected_report_by) && ($selected_report_by == $key)){ ?>
                                <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
                              <?php } else { ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                        <?php if(isset($selected_report_data) && count($selected_report_data) > 0){ ?>
                        <div class="col-md-3" id="div_report_data">
                          <select name="report_data[]" class="form-control" id="report_data" multiple="true">
                            <?php if($selected_report_by == 1) { ?>
                              <option value="">Please Select Category</option>
                            <?php }else if($selected_report_by == 2) { ?>
                              <option value="">Please Select Department</option>
                            <?php } else { ?>
                              <option value="">Please Select Item Group</option>
                            <?php } ?>
                            <?php if(in_array('ALL', $selected_report_data)){ ?>
                              <option value="ALL" selected="selected">ALL</option>
                            <?php } else { ?>
                              <option value="ALL">ALL</option>
                            <?php } ?>
                            <?php if(isset($drop_down_datas)){ ?>
                              <?php foreach($drop_down_datas as $drop_down_data){ ?>
                                <?php $sel_report = false; ?>
                                <?php foreach($selected_report_data as $selected_report_d){ ?>
                                  <?php if($drop_down_data['id'] == $selected_report_d){ ?>
                                    <option value="<?php echo $drop_down_data['id']?>" selected="selected"><?php echo $drop_down_data['name']?></option>
                                    <?php 
                                      $sel_report = true;
                                      break;
                                    ?>
                                   <?php } ?>
                                <?php } ?>
                                <?php if($sel_report == false){ ?>
                                  <option value="<?php echo $drop_down_data['id']?>"><?php echo $drop_down_data['name']?></option>
                                <?php } ?>
                              <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                        <?php } else { ?>
                          <div class="col-md-3" style="display:none;" id="div_report_data">
                            <select name="report_data[]" class="form-control" id="report_data" multiple="true">
                              <option value="">Please Select</option>
                            </select>
                          </div>
                        <?php } ?>-->
                        
                        <div class="col">
                          <input type="submit" class="btn btn-success rcorner header-color" value="Generate">
                        </div>
                        
                     </div>   
                     
        </form>
        
    <br><br>
    <h6><span>ZERO MOVEMENT</span></h6>
        
        <?php if(isset($zeromovement_reports) && count($zeromovement_reports) > 0){ ?>
        <br>
       
       
      
        <!-- <div class="row">-->
        <!--    <div class="col-md-12">-->
        <!--    <div class="col-md-8">-->
                
        <!--    </div>   -->
        <!--    <div class="col-md-4">-->
        <!--        <tr>-->
        <!--         <button type="button" class="btn btn-danger" id="delete_all" title="Delete All" style="border-radius: 0px;"><i class="fa fa-trash"></i>Delete All</button>    -->
        <!--          <button type="button" class="btn btn-danger" id="parent_delete" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>Delete</button>-->
            
        <!--          <button type="button" class="btn btn-primary" id="save_button" title="Save" style="border-radius: 0px;"><i class="fa fa-save"></i>Deactivate</button>-->
        <!--         </tr>    -->
        <!--    </div>-->
        <!--    </div>       -->
        <!--</div>-->

          <br>
            <table class="table   promotionview" >
          
              <thead>
                <tr class="th_color text-uppercas">
                  <th style="width: 1px;color:black;"class="text-left">
                      <input type="checkbox"  class="selectAll" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                  </th>    
                  <th style="width: 20%;">Item Name</th>
                  <th style="width: 15%;">SKU</th>
                  <th style="width: 10%;">QOH</th>
                  <th style="width: 10%;" >Cost</th>
                  <th style="width: 10%;" >Price</th>
                  <th style="width: 10%;" >Total cost</th>
                  <th style="width: 10%;">Total Price</th>
                  <th style="width: 10%;">GP%</th>
                 
                </tr>
              </thead>
              
              <tbody>
                  <?php if (is_array($zeromovement_reports) || is_object($zeromovement_reports)){ ?>
                      <?php foreach ($zeromovement_reports as $key => $value){ ?>
                            <tr>
                        <td style="width:1px;"class="text-left">
                          <input type="checkbox" class="itemid" name="selected[]" id="pitem[<?php echo $key; ?>][select]"  value="<?php echo $value['itemid']; ?>" />
                        </td>  
                        <td style="width: 20%;"><a href="<?php echo $value['item_link'];?>"><?php echo $value['ItemName'];?></a></td>
                       
                        <td style="width: 15%;"><?php echo $value['SKU'];?></td>
                        <td style="width: 10%;"><?php echo $value['QOH'];?></td>
                        <td style="width: 10%;"><?php echo $value['Cost'];?></td>
                        <td style="width: 10%;"><?php echo $value['Price'];?></td>
                        <td style="width: 10%;"><?php echo $value['TotalCost'];?></td>
                        <td style="width: 10%;"><?php echo $value['TotalPrice'];?></td>
                        <td style="width: 10%;"><?php echo $value['GPP'];?></td>
                        
                        
                  </tr>
                      <?php } ?>
                  <?php } ?>
              </tbody>
            </table>
            
            
            <?php if(isset($zeromovement_reports)){ ?>
                {{$zeromovement_reports->links()}}
            <?php } ?>
          
            <div class="row">
              <div class="col-sm-6 text-left"><?php //echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php //echo $results; ?></div>
            </div>
        
        
        <?php }else{ ?>
          
            <div class="row">
              <div class="col-md-12"><br><br>
                <div class="alert alert-info text-center">
                  <strong>Sorry no data found!</strong>
                </div>
              </div>
            </div>
       
        <?php } ?>
      
  </div>



<div class="modal fade" id="successModal" role="dialog">
  <div class="modal-dialog modal-sm">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success text-center">
          <p id="success_msg"></p>
        </div>
      </div>
    </div>
    
  </div>
</div>
<div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger text-center">
          <p id="error_msg"></p>
        </div>
      </div>
      <div class="modal-footer" style="border-top: none;">
      <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
    </div>
    </div>
    
  </div>
</div>



@endsection

@section('page-script')
<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="{{ asset('javascript/bootbox.min.js')}}"></script>
<script src="{{ asset('javascript/table-fixed-header.js')}}" ></script>

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/reportline.css') }}">

<style type="text/css">
  .table.table-bordered.table-striped.table-hover thead > tr {
    background-color: #2486c6;
    color: #fff;
  }
  .select2-container--default .select2-selection--single{
    border-radius: 9px !important;
    height: 35px !important;
    width
  }
  .select2.select2-container.select2-container--default{
  width: 100% !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 35px !important;
  }
</style>

<script type="text/javascript">
   $(function() {
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
        startDate: moment().subtract(3, 'month'),
        endDate: moment(),
        locale: {
            format: 'M-D-Y'
        }
    });

    $(function() {
        
        var p_start_date = "<?php echo isset($p_start_date) ? $p_start_date : '';?>";
        var p_end_date = "<?php echo isset($p_end_date) ? $p_end_date : '';?>";
        
        if(p_start_date != '' && p_end_date != ''){
            var start = p_start_date;
            var end = p_end_date;
        } else {
            var start = moment().subtract(3, 'month');
            var end = moment();
        }
        
        

        function cb(start, end) {
            $('input[name="dates"]').html(start + '-' + end);
            $('input[name="start_date"]').val(start);
            $('input[name="end_date"]').val(end);
        }

        $('input[name="dates"]').daterangepicker({
            
            
            startDate: start,
            endDate: end,
            ranges: {

                'Last 3 Months': [moment().subtract(3, 'month'), moment()],
                'Last 6 Months': [moment().subtract(6, 'month'), moment()],
                'Last 1 Year': [moment().subtract(12, 'month'), moment()]
            }
        }, cb);

        cb(start, end);
    });
});


$(document).on('submit', '#filter_form', function(event) {
    if ($('#dept_code').val() == 'null' || $('#dept_code').val() == '') {
        bootbox.alert({
            size: 'small',
            title: "Attention",
            message: "Please Select Department ",
            callback: function() {}
        });
        return false;

    } 
    
    // if ($('#category_code').val() == 'null' || $('#category_code').val().trim() == '') {

    //     bootbox.alert({
    //         size: 'small',
    //         title: "Attention",
    //         message: "Please Select Category ",
    //         callback: function() {}
    //     });
    //     return false;

    // }


    if ($('#dates').val() == '') {
        // alert('Please Select Start Date');
        bootbox.alert({
            size: 'small',
            title: "Attention",
            message: "Please Select Start Date",
            callback: function() {}
        });
        return false;
    }

    if ($('#dates').val() == '') {
        // alert('Please Select End Date');
        bootbox.alert({
            size: 'small',
            title: "Attention",
            message: "Please Select End Date",
            callback: function() {}
        });
        return false;
    }

    if ($('input[name="dates"]').val() != '') {

        var d1 = Date.parse($('input[name="start_date"]').val());
        var d2 = Date.parse($('input[name="end_date"]').val());

        if (d1 > d2) {
            bootbox.alert({
                size: 'small',
                title: "Attention",
                message: "Start date must be less then end date!",
                callback: function() {}
            });
            return false;
        }
    }


    var dates = $("#dates").val();
    dates_array = dates.split("-");
    start_date = dates_array[0];
    end_date = dates_array[1];


    //format the start date to month-date-year
    var formattedStartDate = new Date(start_date);
    var d = formattedStartDate.getDate();
    var m = formattedStartDate.getMonth();
    m += 1; // JavaScript months are 0-11
    m = ('0' + m).slice(-2);

    var y = formattedStartDate.getFullYear();

    $('input[name="start_date"]').val(m + '-' + d + '-' + y);
    $('input[name="end_date"]').val(end_date);

    var formattedendDate = new Date(end_date);
    var de = formattedendDate.getDate();
    var me = formattedendDate.getMonth();
    me += 1; // JavaScript months are 0-11
    me = ('0' + me).slice(-2);
    var ye = formattedendDate.getFullYear();
    $('input[name="end_date"]').val(me + '-' + de + '-' + ye);


    $("div#divLoading").addClass('show');

});

$(document).ready(function() {
    var selected_item = [];
    $(document).on("change, click", "input[name='selected[]'], .selectAll", function() {
        $("input[name='selected[]']:checked").each(function(i) {
            var index = selected_item.indexOf($(this).val());
            if (index == -1) {
                selected_item.push($(this).val());
            }
        });

        $("input[name='selected[]']:not(:checked)").each(function(i) {
            var index = selected_item.indexOf($(this).val());
            if (index !== -1) {
                selected_item.splice(index, 1);
            }
        });

    });

    $(document).on('click', '#parent_delete', function(event) {
    event.preventDefault();
      if($("input[name='selected[]']:checked").length == 0){
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: 'Please Select Checkbox to Delete!', 
            callback: function(){}
          });
          return false;
        }
    var result = confirm("Do you want to delete Item ?");
    if (result) {
        var delete_url = "{{route('zeromovementreportdelete')}}";
        delete_url = delete_url.replace(/&amp;/g, '&');
        $("div#divLoading").addClass('show');
        
        console.log(selected_item);
        
        $.ajax({
            url : delete_url,
            data : JSON.stringify({selected_items: selected_item}),
            type : 'post',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            contentType: "application/json",
            dataType: 'json',
          success: function(data) {
    
            if(data.success){
              setTimeout(function(){
                   bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: 'Deleted Successfully!!', 
                    callback: function(){}
                  });
               
               var refresh_url = "{{route('zeromovementreport')}}";
               refresh_url = refresh_url.replace(/&amp;/g, '&');
                 location.reload(true);
              }, 1000);
            }else{
    
              $('#error_msg').html('<strong>'+ data.error +'</strong>');
              $("div#divLoading").removeClass('show');
              $('#errorModal').modal('show');
    
            }
    
    
          },
          error: function(xhr) { // if error occured
            var  response_error = $.parseJSON(xhr.responseText); //decode the response array
            
            var error_show = '';
    
            if(response_error.error){
              error_show = response_error.error;
            }else if(response_error.validation_error){
              error_show = response_error.validation_error[0];
            }
    
            $('#error_alias').html('<strong>'+ error_show +'</strong>');
            $('#errorModal').modal('show');
            return false;
          }
        });
    
    }
  });

 $(document).on('click', '#delete_all', function(event) {
    // event.preventDefault();
    //   if($("input[name='selected[]']:checked").length == 0){
    //       bootbox.alert({ 
    //         size: 'small',
    //         title: "Attention", 
    //         message: 'Please Select Checkbox to Delete!', 
    //         callback: function(){}
    //       });
    //       return false;
    //     }
    var result = confirm("Do you want to delete all Item ?");
    if (result) {
        var delete_url = "{{route('zeromovement_DeleteAll')}}";
        delete_url = delete_url.replace(/&amp;/g, '&');
        $("div#divLoading").addClass('show');
        
        console.log(selected_item);
        
        $.ajax({
            url : delete_url,
            //data : JSON.stringify({selected_items: selected_item}),
            type : 'GET',
            //headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            contentType: "application/json",
            dataType: 'json',
          success: function(data) {
    
            if(data.success){
              setTimeout(function(){
                   bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: 'Deleted All Item Successfully!!', 
                    callback: function(){}
                  });
               
               var refresh_url = "{{route('zeromovementreport')}}";
               refresh_url = refresh_url.replace(/&amp;/g, '&');
                 location.reload(true);
              }, 1000);
            }else{
    
              $('#error_msg').html('<strong>'+ data.error +'</strong>');
              $("div#divLoading").removeClass('show');
              $('#errorModal').modal('show');
    
            }
    
    
          },
          error: function(xhr) { // if error occured
            var  response_error = $.parseJSON(xhr.responseText); //decode the response array
            
            var error_show = '';
    
            if(response_error.error){
              error_show = response_error.error;
            }else if(response_error.validation_error){
              error_show = response_error.validation_error[0];
            }
    
            $('#error_alias').html('<strong>'+ error_show +'</strong>');
            $('#errorModal').modal('show');
            return false;
          }
        });
    
    }
  });
    $(document).on('click', '#save_button', function(event) {
        event.preventDefault();
        if ($("input[name='selected[]']:checked").length == 0) {
            bootbox.alert({
                size: 'small',
                title: "Attention",
                message: 'Please Select Checkbox to Delete!',
                callback: function() {}
            });
            return false;
        }
        var result = confirm("Do you want to Deactivate Item ?");
        if (result) {
            var delete_url = "{{route('zeromovementreportupdate_dectivate')}}";
            delete_url = delete_url.replace(/&amp;/g, '&');
            $("div#divLoading").addClass('show');
            $.ajax({
                url: delete_url,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data : JSON.stringify({selected_items: selected_item}),
                type: 'POST',
                contentType: "application/json",
                dataType: 'json',
                success: function(data) {

                    if (data.success) {
                        setTimeout(function() {
                            bootbox.alert({
                                size: 'small',
                                title: "Attention",
                                message: 'Deactivated Successfully!',
                                callback: function() {}
                            });

                            var refresh_url = "{{route('zeromovementreport')}}";
                            refresh_url = refresh_url.replace(/&amp;/g, '&');
                            location.reload(true);
                        }, 3000);
                    } else {

                        $('#error_msg').html('<strong>' + data.error + '</strong>');
                        $("div#divLoading").removeClass('show');
                        $('#errorModal').modal('show');

                    }


                },
                error: function(xhr) { // if error occured
                    var response_error = $.parseJSON(xhr.responseText); //decode the response array

                    var error_show = '';

                    if (response_error.error) {
                        error_show = response_error.error;
                    } else if (response_error.validation_error) {
                        error_show = response_error.validation_error[0];
                    }

                    $('#error_alias').html('<strong>' + error_show + '</strong>');
                    $('#errorModal').modal('show');
                    return false;
                }
            });

        }
    });

    $("#btnPrint").printPage();
});
// $(window).load(function() {
//     $("div#divLoading").removeClass('show');
// });
const saveData = (function() {
    const a = document.createElement("a");
    document.body.appendChild(a);
    a.style = "display: none";
    return function(data, fileName) {
        const blob = new Blob([data], {
                type: "octet/stream"
            }),
            url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = fileName;
        a.click();
        window.URL.revokeObjectURL(url);
    };
}());

$(document).on("click", "#csv_export_btn", function(event) {

    event.preventDefault();

    $("div#divLoading").addClass('show');

    var csv_export_url = "{{route('zeromovementreportcsv_export')}}";

    csv_export_url = csv_export_url.replace(/&amp;/g, '&');

    $.ajax({
        url: csv_export_url,
        type: 'GET',
    }).done(function(response) {

        const data = response,
            fileName = "zero-movement-report.csv";

        saveData(data, fileName);
        $("div#divLoading").removeClass('show');

    });

});

$(document).on("click", "#pdf_export_btn", function(event) {

    event.preventDefault();

    $("div#divLoading").addClass('show');

    var pdf_export_url = "{{route('zeromovementreportpdf_save_page')}}";

    pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

    var req = new XMLHttpRequest();
    req.open("GET", pdf_export_url, true);
    req.responseType = "blob";
    req.onreadystatechange = function() {
        if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
                window.navigator.msSaveBlob(req.response, "Zero-Movement-Report.pdf");
            } else {
                var blob = req.response;
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Zero-Movement-Report.pdf";

                // append the link to the document body

                document.body.appendChild(link);

                link.click();
            }
        }
        $("div#divLoading").removeClass('show');
    };
    req.send();

});

$(document).on('change', 'input[name="start_date"],input[name="end_date"]', function(event) {
    event.preventDefault();

    if ($('input[name="start_date"]').val() != '' && $('input[name="end_date"]').val() != '') {

        var d1 = Date.parse($('input[name="start_date"]').val());
        var d2 = Date.parse($('input[name="end_date"]').val());

        if (d1 > d2) {
            bootbox.alert({
                size: 'small',
                title: "Attention",
                message: "Start date must be less then end date!",
                callback: function() {}
            });
            return false;
        }
    }
});
$(document).ready(function() {

    $('select[name="vdepcode"]').select2();
    $('select[name="vcategorycode"]').select2();
    $('select[name="subcat_id"]').select2();
    
    // if ($("#dept_code").val() === "") {
    //     $("#dept_code").prop("selectedIndex", -1);    
    // }
    
});

$("#dept_code").change(function() {
    var deptCode = $(this).val();
    var input = {};
    input['dept_code'] = deptCode;
    console.log("check" + deptCode);
    if (deptCode != "") {
        var url = "{{route('zeromovementreportgetcategories')}}";
        url = url.replace(/&amp;/g, '&');

        $.ajax({
            url: url,
            data: input,
            type: 'GET',
            contentType: "application/json",
            dataType: 'text json',
            success: function(response) {

                // console.log(response);
                if (response.length > 0) {

                    $('select[name="vcategorycode"]').select2().empty().select2({
                        data: response
                    });

                }

                setTimeout(function() {
                    $('#successAliasModal').modal('hide');
                }, 3000);
            },
            error: function(xhr) {
                return false;
            }
        });
    }
});

$("#category_code").change(function() {
    var cat_id = $(this).val();
    var input = {};
    input['cat_id'] = cat_id;

    if (cat_id != "") {

        var url = "{{route('zeromovementreportget_subcategories')}}";
        url = url.replace(/&amp;/g, '&');

        $.ajax({
            url: url,
            data: input,
            type: 'GET',
            contentType: "application/json",
            dataType: 'text json',
            success: function(response) {
                // console.log(response);
                if (response.length > 0) {
                    $('select[name="subcat_id"]').select2().empty().select2({
                        data: response
                    });
                }

                setTimeout(function() {
                    $('#successAliasModal').modal('hide');
                }, 3000);
            },
            error: function(xhr) {
                return false;
            }
        });
    }
});

$('.table').fixedHeader({
    topOffset: 0
});

$( document ).ready(function() {
    $("select#dept_code option").filter(function() {
        return $(this).val() == "0"; 
    }).prop('selected', true);
});        
</script>
<style>
    .disabled {
    pointer-events:none; //This makes it not clickable
 
    }

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
.btn_width{
    width:110px;
}
</style>
@endsection