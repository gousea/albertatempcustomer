@extends('layouts.layout')
@section('title', 'Scan Data Report')
@section('main-content')
<link rel="stylesheet" href="{{ asset('asset/css/promotion.css') }}">

<div id="content">

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Scan Data Report  </span>
                </div>
            </div> 
        </div>
    </nav>
    <section class="section-content py-6">
        <div class="container">
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
            <div class="mytextdiv mb-3">
                <div class="mytexttitle font-weight-bold text-uppercase">
                Search Parameters
                </div>
                <div class="divider font-weight-bold"></div>
            </div>
            <form action="<?php echo $current_url;?>" method="post" id="form_scan_data_search">
                @csrf
                <div class="form-group row">
                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                        <?php $manufacturers = array('1' => 'Phillip Morris(Marlboro)', '2' => 'RJ Reynolds');?>
                        <select name="manufacturer" id="manufacturer" style="padding-left: 0px;" class="form-control">
                        <option value="">Manufacturer</option>
                            <?php foreach($manufacturers as $key => $value){?>
                            <option value="<?php echo $key;?>"><?php echo $value;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                        <input type="tel" class="form-control" name="management_account_number" maxlength="10" placeholder="Mgmt A/c No./Retail Ctrl No.">
                    </div>
                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                        <input type="" class="form-control" name="week_ending_date" value="<?php echo isset($week_ending_date) ? $week_ending_date : ''; ?>" id="week_ending_date" placeholder="Week Ending Date" readonly>
                    </div>
                </div>
                    


                <div class="mytextdiv mb-3">
                    <div class="mytexttitle font-weight-bold text-uppercase">
                    Filter Report
                    </div>
                    <div class="divider font-weight-bold"></div>
                </div>

                <div class="form-group row">
                    <div class="col-12 col-md-6  col-lg-6 p-form">
                        <input type="radio" class="" name="filter_by" value="" id="radioDeptCat" checked> &nbsp; &nbsp; &nbsp;Departments & Categories
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 p-form">
                        <input type="radio" class="" name="filter_by" value="" id="radioNone" style="vertical-align: baseline;"> &nbsp; &nbsp; &nbsp;None
                    </div>
                </div>

                <div class="form-group row" id="divDeptCat">
                    <div class="col-12 col-md-6  col-lg-6 p-form">
                        <select name="department_id[]" id="department_id" class="form-control" multiple="true" style="padding-left: 2px;">
                            <option value="" >-- Select Department(s) --</option>
                            <?php if(isset($departments) && count($departments) > 0){?>
                                <?php foreach($departments as $department){?>
                                <option value="<?php echo $department['vdepcode'];?>"><?php echo $department['vdepartmentname'];?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 p-form">
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
                            <input type="submit" name="Export" style="background-color: #286fb7 !important; color: #fff;" value="Generate" class="btn">
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </section>
  </div>
  
  
    <div class="modal fade" id="reconcileModal" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Scan Data Reconciliation Report</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
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



<div class="modal fade" id="successModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
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


<div class="modal fade" id="warningModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning text-center">
          <p id="warning_msg"></p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="errorModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
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
    </div>
  </div>
</div>
@endsection

@section('page-script')

<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>

<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> -->
 
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
        var wdate = document.getElementById("week_ending_date");
        var dateElem = window.$.datepicker._getInst(wdate);
        
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

           
            
            $('#warning_msg').html('Please select the manufacturer before selecting the date!');
            $("div#divLoading").removeClass('show');
            $('#warningModal').modal('show');
            return false;
        }
    });

  $(document).on('submit', 'form#form_scan_data_search', function(event) {
    
    if($('#manufacturer :selected').length == 0 || $('#manufacturer :selected').val() == ''){
        
        
        $('#warning_msg').html('Please select manufacturer!');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        return false;
    }
      
      
    if($('input[name="management_account_number"]').val() == ''){
       
        $('#warning_msg').html('Please enter management account number Or Retail Control Number!');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        return false;
    }

    if($('#week_ending_date').val() == ''){
       
        $('#warning_msg').html('Please select week ending date!');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        return false;
    }

    if(!$('#radioNone').is(':checked') && $('#department_id :selected').length == 0 || $('#department_id :selected').val() == ''){
        
        $('#warning_msg').html('Please select department!');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        return false;
    }
    
    if(!$('#radioNone').is(':checked') && $('#category_id :selected').length == 0 || $('#category_id :selected').val() == ''){
        
        $('#warning_msg').html('Please select category!');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
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