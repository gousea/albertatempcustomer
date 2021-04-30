@extends('layouts.master')
@section('title', 'Scan Data Report')
@section('main-content')

<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        
        <!-- <h1><?php echo $heading_title; ?></h1> -->
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div class="container-fluid">
      <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <div class="panel panel-default">
        <div class="panel-heading head_title">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
          <div class="top_button">
          </div>
        </div>
        <div class="panel-body">
  
            <form action="<?php echo $current_url;?>" method="post" id="form_scan_data_search">
                @csrf
              <div class="row">
                  <div class="col-md-12">
                      <div class="col-md-4">
                          <?php $manufacturers = array('1' => 'Phillip Morris(Marlboro)', '2' => 'RJ Reynolds');?>
                          <select name="manufacturer" id="manufacturer" style="padding-left: 0px;" class="form-control">
                            <option value="">-- Manufacturer --</option>
                              <?php foreach($manufacturers as $key => $value){?>
                                <option value="<?php echo $key;?>"><?php echo $value;?></option>
                              <?php } ?>
                          </select>
                      </div>
                      <div class="col-md-4">
                          <input type="tel" class="form-control" name="management_account_number" maxlength="10" placeholder="Mgmt A/c No./Retail Ctrl No.">
                      </div>
                      <div class="col-md-4">
                         <input type="" class="form-control" name="week_ending_date" value="<?php echo isset($week_ending_date) ? $week_ending_date : ''; ?>" id="week_ending_date" placeholder="Week Ending Date" readonly>
                      </div>
                  </div>
              </div>
              
              <div class="row" style="margin-top: 25px;">
                  <div class="col-md-12">
                      <div class="col-md-2">Filter By:</div>
                      <div class="col-md-3"><label><input type="radio" class="" name="filter_by" value="" id="radioDeptCat" checked> Departments & Categories</label></div>
                      <div class="col-md-2" style="display: none"><label><input type="radio" class="" name="filter_by" value="" id="radioMfr" style="vertical-align: baseline;"> Manufacturers</label></div>
                      <div class="col-md-2" style=""><label><input type="radio" class="" name="filter_by" value="" id="radioNone" style="vertical-align: baseline;"> None</label></div>
                  </div>
              </div>
              
              
              <div class="row" style="margin-top: 25px;" id="divDeptCat">
                  <div class="col-md-12">
                      <div class="col-md-6">
                          <select name="department_id[]" id="department_id" class="form-control" multiple="true" style="padding-left: 2px;">
                            <option value="" >-- Select Department(s) --</option>
                            <?php if(isset($departments) && count($departments) > 0){?>
                              <?php foreach($departments as $department){?>
                                <option value="<?php echo $department['vdepcode'];?>"><?php echo $department['vdepartmentname'];?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                      </div>
                      <div class="col-md-6">
                          <select name="category_id[]" id="category_id" class="form-control" multiple="true" style="padding-left: 2px;">
                            <option value="" disabled="disabled">-- Select Category(s) --</option>
                            <?php if(isset($categories) && count($categories) > 0 && $new_database !== true){?>
                              <?php foreach($categories as $category){?>
                                <option value="<?php echo $category['vcategorycode'];?>"><?php echo $category['vcategoryname'];?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                      </div>
                  </div>
              </div>
              
              <div class="row" style="margin-top: 25px; display: none;" id="divMfr">
                  <div class="col-md-12">
                      
                      <?php if(is_array($manufacturer_list)) { ?>
                      
                      <div class="col-md-6">
                          <select name="mfr_id[]" id="mfr_id" class="form-control" multiple="true" style="padding-left: 2px;">
                            <option value="">-- Select Manufacturer(s) --</option>
                            <?php if(isset($manufacturer_list) && count($manufacturer_list) > 0){?>
                              <?php foreach($manufacturer_list as $manufacturer){?>
                                <option value="<?php echo $manufacturer['mfr_id'];?>"><?php echo $manufacturer['mfr_name'];?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                      </div>
                      
                      <?php } else { ?>
                          <div class="col-md-6"><?php echo $manufacturer_list; ?></div>
                      <?php }?>
                  </div>
              </div>
              
              <div class="row" style="margin-top: 25px;" id="">
                  <div class="col-md-12">
                      <div class="col-md-1 pull-right" style="padding-left: 5px;">
                          <input type="submit" name="Export" value="Generate" class="btn btn-success">
                      </div>
                  </div>
              </div>
              
            </form>
        </div>
      </div>
    </div>
  </div>
  
  
    <div class="modal fade" id="reconcileModal" role="dialog">
      <div class="modal-dialog">
      
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Scan Data Reconciliation Report</h4>
          </div>
          <div class="modal-body">
              <div class='row'>
                  <div class='col-md-10 col-md-offset-1'>
                      Scan Data Reconciliation Report is Ready. Would you like to download it ??
                  </div>
              </div>
              <div class='row'>&nbsp;</div>
              <div class='row'>
                  <div class='col-md-4 col-md-offset-7'>
                      <input type='hidden' id='reconcileFileName' value="{{ $reconcile_file_path ?? '' }}">
                      <input type='button' id='getReconciliationReport' class='btn btn-success' value='Yes'>
                      <input type='button' class='btn btn-danger' value='No' data-dismiss="modal">
                  </div>
              </div>
          </div>
        </div>
        
      </div>
    </div>

@endsection

@section('scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>
<script>
     $(document).ready(function(){
        $('#category_id').html('<option>Loading Categories...</option>');
        var deptIds = $("#department_id").val();
        var getCatUrl = '<?php echo $get_categories_url; ?>';
        getCatUrl = getCatUrl.replace(/&amp;/g, '&');
        $.ajax({
          url : getCatUrl,
          data : {department_ids: deptIds},
          type : 'POST',
        }).done(function(response){
            $('#category_id').html(response);
        });
      });
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
  $(document).on('click', '#radioMfr', function(){
      $('#divDeptCat').hide();
      $('#divMfr').show();
  });
  
  $(document).on('click', '#radioDeptCat', function(){
      $('#divMfr').hide();
      $('#divDeptCat').show();
  });
  
  $(document).on('click', '#radioNone', function(){
      $('#divDeptCat').hide();
      $('#divMfr').hide();
  });

  $(function(){
      
    $('#manufacturer').change(function(){
        
        var manufacturer = $(this).val();
        var dateElem = window.$.datepicker._getInst(document.getElementById("week_ending_date"));
        
        if(dateElem){
            $("#week_ending_date").datepicker( "destroy" );
            $("#week_ending_date").val("");
        }
        
        if(manufacturer == 1){
            $("#week_ending_date").datepicker({
              format: 'mm-dd-yyyy',
              autoclose: true,
              daysOfWeekDisabled: [0,1,2,3,4,5],
            });  
        } 
        
        if(manufacturer == 2){
            $("#week_ending_date").datepicker({
              format: 'mm-dd-yyyy',
              autoclose: true,
              daysOfWeekDisabled: [1,2,3,4,5,6]
            });
        }
    });
    
  });
  
  $(document).on('click', '#getReconciliationReport', function(e){
      $('#reconcileModal').modal('hide');
      $("div#divLoading").addClass('show');

      var reconcile_scan_data = '<?php echo $reconcile_scan_data; ?>';
      reconcile_scan_data = reconcile_scan_data.replace(/&amp;/g, '&');
      
    //   input = $('#reconcileFileName').serialize();
      
      $.ajax({
        url : reconcile_scan_data,
        type : 'POST',
      }).done(function(response){
        console.log(response);
        if($.trim(response) == ''){
            data = 'No data found.'
        } else {
            data = response;
        }
        var sid = '<?php echo $sid; ?>';
        var fileName = 'scan_data_reconciliation_'+'<?php echo $sid; ?>'+'.csv';
        
        saveData(data, fileName);
        $("div#divLoading").removeClass('show');
      });
      

  });
  
    $(document).on('click', '#week_ending_date', function(e){
        event.preventDefault();
        if($('#manufacturer :selected').length == 0 || $('#manufacturer :selected').val() == ''){

            bootbox.alert({ 
              size: 'small',
              title: "Attention", 
              message: "Please select the manufacturer before selecting the date!", 
              callback: function(){}
            });
            return false;
        }
    });

  $(document).on('submit', 'form#form_scan_data_search', function(event) {
    
    if($('#manufacturer :selected').length == 0 || $('#manufacturer :selected').val() == ''){
        // alert('Please select department!');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please select manufacturer!", 
          callback: function(){}
        });
        return false;
    }
      
      
    if($('input[name="management_account_number"]').val() == ''){
        // alert('Please enter management account number Or Retail Control Number!');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please enter management account number Or Retail Control Number!", 
          callback: function(){}
        });
        return false;
    }

    if($('#week_ending_date').val() == ''){
        // alert('Please select week ending date!');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please select week ending date!", 
          callback: function(){}
        });
        return false;
    }

    if(!$('#radioNone').is(':checked') && $('#department_id :selected').length == 0 || $('#department_id :selected').val() == ''){
        // alert('Please select department!');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please select department!", 
          callback: function(){}
        });
        return false;
    }
    
    if(!$('#radioNone').is(':checked') && $('#category_id :selected').length == 0 || $('#category_id :selected').val() == ''){
        // alert('Please select department!');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please select category!", 
          callback: function(){}
        });
        return false;
    }


    event.preventDefault();

    var ac = $('input[name="management_account_number"]').val();
    var week_e_d = $('#week_ending_date').val();
    var store = '<?php echo $store_name_without_space;?>';

    var dateAr = week_e_d.split('-');

    week_e_d = dateAr['2']+''+dateAr['0']+''+dateAr['1'];

    var file_name = store+'_'+ac+'_'+week_e_d;

    $("div#divLoading").addClass('show');

    var csv_export_url = '<?php echo $current_url; ?>';
  
    csv_export_url = csv_export_url.replace(/&amp;/g, '&');

    $.ajax({
      url : csv_export_url,
      data : $('#form_scan_data_search').serialize(),
      type : 'POST',
    }).done(function(response){
      
      const data = response,
      fileName = file_name+".txt";
      saveData(data, fileName);
      
      $('#reconcileModal').modal('show');
      $("div#divLoading").removeClass('show');
      
    });

  });

  /*$('input[name="management_account_number"]').keypress(function(event) {
      $(this).val($(this).val().replace(/[^\d].+/, ""));
      if ((event.which < 48 || event.which > 57)) {
          event.preventDefault();
      }
  });*/
  
  $('input[name="management_account_number"]').keypress(function(e) {
        
        var regex = new RegExp("[^a-zA-Z0-9]+");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            e.preventDefault();
            return false;
        }
    
        return true;
    });
  
  
</script>

<script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
  
  
      $(document).on('change', '#department_id', function(){
        $('#category_id').html('<option>Loading Categories...</option>');
    
        var deptIds = $(this).val();
        var getCatUrl = '<?php echo $get_categories_url; ?>';
        getCatUrl = getCatUrl.replace(/&amp;/g, '&');
        $.ajax({
          url : getCatUrl,
          data : {department_ids: deptIds},
          type : 'POST',
        }).done(function(response){
            $('#category_id').html(response);
        });
        
      });
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

</script>

@endsection