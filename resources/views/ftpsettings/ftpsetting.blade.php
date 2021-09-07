@extends('layouts.layout')
@section('title')
  FTP Settings
@endsection
@section('main-content')

<style>
    .levelpricingFtp{
        display: none;
    }
    .levelpricingEditFtp{
        display: none;
    }
</style>
<div id="content">
  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
      <div class="container">
          <div class="collapse navbar-collapse" id="main_nav">
              <div class="menu">
                  <span class="font-weight-bold text-uppercase" >FTP Settings </span>
              </div>
              <div class="nav-submenu">
                  <!-- <button type="button" onclick="addAisle();" data-toggle="tooltip" title="<?php //echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</button>   
                  <button type="button" class="btn btn-danger" id="aisle_delete" onclick="myFunction()" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
             -->
                  <button type="button" onclick="addAisle();" data-toggle="tooltip" class="btn btn-gray headerblack  buttons_menu " href="#"> <i class="fa fa-plus"></i>&nbsp;&nbsp; Add New</button>
                  <button type="button" id="aisle_delete" onclick="myFunction()" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> <i class="fa fa-trash"></i>&nbsp;&nbsp; Delete</button>
              </div>
          </div> <!-- navbar-collapse.// -->
      </div>
  </nav>
  <section class="section-content py-6">
    <div class="container">
      <div class="panel panel-default">
        <div class="panel-body">
            <form action="{{ route('ftpsetting') }}" method="post" id="form_aisle_search">
              @csrf
              @method('post')
              
                @if (session()->has('message'))
              <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>      
              @endif

              @if (session()->has('error'))
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
              <input type="hidden" name="searchbox" id="Id">
              
            </form>
            <br>
            
            <form action="" method="post" enctype="multipart/form-data" id="form-aisle">
              <div class="table-responsive">
              <?php if ($ftp_settings) { ?>
                <table id="aisle" class="text-center table table-hover" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                  <thead style="background-color: #286fb7!important;">
                    <tr>
                        <td style="width: 1px;color:black;"class="text-center"><input type="checkbox"  onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                        <td style="" class="col-xs-1 headername text-uppercase pull-left text-light"><?php echo 'Manufacturer'; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $aisle_row = 1;$i=0; ?>
                    <?php foreach ($ftp_settings as $k => $ftp) { ?>
                      <tr>
                        <td class="text-center">
                          <input type="checkbox" name="selected[]" id="aisle[<?php echo $k; ?>][select]"  value="<?php echo $ftp['ftp_id']; ?>" />
                        </td>
                        
                        <td class="manufacturer text-left" style="cursor: pointer;" id="<?php echo $ftp['ftp_id']; ?>">
                            <span style="display:none;"><?php echo $ftp['manufacturer']; ?></span>
                            <!-- <input type="text" maxlength="45" class="editable aisle_c" name="aisle[<?php echo $k; ?>][aislename]" id="aisle[<?php echo $k; ?>][aislename]" value="<?php echo $ftp['manufacturer']; ?>" onclick='document.getElementById("aisle[<?php echo $k; ?>][select]").setAttribute("checked","checked");' /> -->
                      
                            <span>
                          <?php // echo $ftp['manufacturer'] == 1?'Phillip Morris':'RJ Reynolds'; ?>
                          <?php 
                          switch($ftp['manufacturer']){
                                    case '1':
                                        echo 'Phillip Morris';
                                        break;
                                    case '2':
                                        echo 'RJ Reynolds';
                                        break;
                                    case '3':
                                        echo 'Drizzly';
                                        break;
                                    case '4':
                                        echo 'Minibar';
                                        break;                                    
                                    default:
                                        echo 'No Manufacturer Selected';
                                }
                          
                          ?>
                      </span>
                      
                      
                      <input type="hidden" name="aisle[<?php echo $k; ?>][Id]" value="<?php echo isset($aisle['Id']); ?>"/>
                      </td>
                      </tr>
                    <?php $aisle_row++; $i++;?>
                    <?php } ?>
                  </tbody>
                </table>
                <?php } else { ?>
                <table class="text-center table table-bordered table-hover">
                  <tr>
                    <td colspan="7" class="text-center"><?php echo 'Sorry no data found!';?></td>
                  </tr>
                </table>
                <?php } ?>
              </div>
            </form>
            <div class="row">
              <div class="col-sm-6 text-left"><?php //echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php //echo $results; ?></div>
            </div>
        </div>
      </div>
    </div>
  </section>
</div>


<!-- Modal Add -->
  <div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New FTP Settings</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="{{route('ftpsetting.create') }}" method="post" id="add_new_form">
            @csrf
            @method('post')
            <input type="hidden" name="ftp[0][Id]" value="0">
            

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Management A/c No.</label>
                <div class="col-sm-10">
                  <input name="ftp[0][mfr_retail_no]" maxlength="45" id="add_mfr_retail_no" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Manufacturer</label>
                <div class="col-sm-10">
                    <select name="ftp[0][manufacturer]" id="add_manufacturer" class="form-control">
                        <option value = 1>Phillip Morris</option>
                        <option value = 2>RJ Reynolds</option>
                        <option value = 3>Drizzly</option>
                        <option value = 4>Minibar</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Host</label>
                <div class="col-sm-10">
                  <input name="ftp[0][host]" maxlength="45" id="add_host" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                  <input name="ftp[0][ftp_username]" maxlength="45" id="add_ftp_username" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                  <input name="ftp[0][ftp_password]" maxlength="45" id="add_ftp_password" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Purpose</label>
                <div class="col-sm-10">
                    <select name="ftp[0][purpose]" id="add_purpose" class="form-control">
                        <option value = 1>Scan Data</option>
                        <option value = 2>Others</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3 levelpricingFtp" id="divAddLevelpricing" >
                <label for="inputEmail3" class="col-sm-2 col-form-label">Level Pricing</label>
                <div class="col-sm-10">
                    <select name="ftp[0][level_pricing]" id="level_pricing" class="form-control">
                        <option value = "">Select Level Pricing</option>
                        <option value = "no_level_pricing">No Level Pricing</option>
                        <option value = "level_price_2">Level 2 Price</option>
                        <option value = "level_price_3">Level 3 Price</option>
                        <option value = "level_price_4">Level 4 Price</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Departments</label>
                <div class="col-sm-10">
                    <select name="ftp[0][dept_code][]" id="add_departments" class="form-control dropdown-select2" multiple="multiple">
                        <?php 
                            foreach($departments as $d){
                                echo "<option value='".$d['vdepcode']."'>".$d['vdepartmentname']."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Categories</label>
                <div class="col-sm-10">
                <select name="ftp[0][cat_code][]" id="add_categories" class="form-control dropdown-select2" multiple="multiple">
                        <?php 
                            foreach($categories as $c){
                                echo "<option value='".$c['vcategorycode']."'>".$c['vcategoryname']."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            
            
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn btn-success" type="submit" value="Save">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
<!-- Modal Add Ends-->

<!-- Modal Edit Starts -->
  <div class="modal fade" id="editModal" role="dialog">
    <div class="modal-dialog">
        
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit FTP Settings</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="{{route('ftpsetting.edit')}}" method="post" id="edit_new_form" class="">
            @csrf
            @method('post')
            <input type="hidden" name="ftp_id" id="edit_ftp_id">
            

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Management A/c No.</label>
                <div class="col-sm-10">
                  <input name="mfr_retail_no" maxlength="45" id="edit_mfr_retail_no" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Manufacturer</label>
                <div class="col-sm-10">
                    <select name="manufacturer" id="edit_manufacturer" class="form-control">
                        <option value = 1>Phillip Morris</option>
                        <option value = 2>RJ Reynolds</option>
                        <option value = 3>Drizzly</option>
                        <option value = 4>Minibar</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Host</label>
                <div class="col-sm-10">
                  <input name="host" maxlength="45" id="edit_host" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                  <input name="ftp_username" maxlength="45" id="edit_ftp_username" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                  <input name="ftp_password" maxlength="45" id="edit_ftp_password" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Purpose</label>
                <div class="col-sm-10">
                    <select name="purpose" id="edit_purpose" class="form-control">
                        <option value=1>Scan Data</option>
                        <option value=2>Others</option>
                    </select>
                </div>
            </div>
            
            
            <div class="row mb-3 levelpricingEditFtp" id="divEditLevelpricing">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Level Pricing</label>
                <div class="col-sm-10">
                    <select name="level_pricing" id="edit_level_pricing" class="form-control">
                        <option value = "">Select Level Pricing</option>
                        <option value = "no_level_pricing">No Level Pricing</option>
                        <option value = "level_price_2">Level 2 Price</option>
                        <option value = "level_price_3">Level 3 Price</option>
                        <option value = "level_price_4">Level 4 Price</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Departments</label>
                <div class="col-sm-10">
                    <select name="dept_code[]" id="edit_dept_code" class="form-control dropdown-select2" multiple="multiple">
                        <?php 
                            foreach($departments as $d){
                                echo "<option value='".$d['vdepcode']."'>".$d['vdepartmentname']."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Categories</label>
                <div class="col-sm-10">
                    <select name="cat_code[]" id="edit_cat_code" class="form-control dropdown-select2" multiple="multiple">
                        <?php 
                            foreach($categories as $c){
                                echo "<option value='".$c['vcategorycode']."'>".$c['vcategoryname']."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn btn-success" type="submit" value="Save">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
<!-- Modal Edit-->


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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>

<style>
 .select2 {
        width:100%!important;
    }
    
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline {
        width: 100% !important;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline .select2-search__field {
        width: 100% !important;
    }
    
  
</style>

<script type="text/javascript">

    $("#edit_dept_code").change(function(){
        var deptCode = $(this).val();
        var input = {};
        input['dept_code'] = deptCode;
        input
        
        if(deptCode != ""){
            var url = '<?php echo route('ftpsetting.getcategories'); ?>';
            url = url.replace(/&amp;/g, '&');
            $.ajax({
                url : url,
               // data : JSON.stringify(input),
               data : input,
                type : 'GET',
                contentType: "application/json",
                dataType: 'text json',
                success: function(response) {
                    if(response.length > 0){
                        
                        $('select[name="cat_code[]"]').select2().empty().select2({
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
    
    $("#add_departments").change(function(){
        var deptCode = $(this).val();
        var input = {};
        input['dept_code'] = deptCode;
        
        if(deptCode != ""){
            var url = '<?php echo route('ftpsetting.getcategories'); ?>';
            url = url.replace(/&amp;/g, '&');
            $.ajax({
                url : url,
               // data : JSON.stringify(input),
               data : input,
                type : 'GET',
                contentType: "application/json",
                dataType: 'text json',
                success: function(response) {
                    if(response.length > 0){
                        
                        $('select[name="ftp[0][cat_code][]"]').select2().empty().select2({
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



function addAisle() {
    $('#divAddLevelpricing').addClass("levelpricingFtp");
    $('#addModal').modal('show');
}

 $(document).on('submit', 'form#add_new_form', function(event) {
    
    if($('form#add_new_form #add_mfr_retail_no').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please enter Manufacturer Retail Number!", 
        callback: function(){}
      });
      return false;
    }
    
    if($('form#add_new_form #add_manufacturer').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please enter Manufacturer!", 
        callback: function(){}
      });
      return false;
    }
    
    if($('form#add_new_form #add_host').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please enter Host!", 
        callback: function(){}
      });
      return false;
    }
    
    if($('form#add_new_form #add_ftp_username').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please enter Username!", 
        callback: function(){}
      });
      return false;
    }
    
    if($('form#add_new_form #add_ftp_password').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please enter Password!", 
        callback: function(){}
      });
      return false;
    }

    $("div#divLoading").addClass('show');
    
  });
  
  $(document).on('click', '.manufacturer', function(event) {
        
        $('#divEditLevelpricing').addClass("levelpricingEditFtp");
        
        
        var data = {ftp_id : parseInt(this.id)};
        // data['ftp_id'] = parseInt(this.id);
        
        var get_ftp_settings = "{{route('ftpsetting.getftpsettings')}}";
    
        get_ftp_settings = get_ftp_settings.replace(/&amp;/g, '&');
    
      $.ajax({
            url : get_ftp_settings,
            data : data,
            type : 'GET',
            dataType: 'json',
          success: function(response) {
            $("#edit_ftp_id").val(response.ftp_id);
            $("#edit_mfr_retail_no").val(response.mfr_retail_no);
            // $("#edit_manufacturer").val(response.manufacturer);
            
            document.getElementById('edit_manufacturer').value=response.manufacturer;
            document.getElementById('edit_purpose').value=response.purpose;
            document.getElementById('edit_level_pricing').value=response.level_pricing;
            
            $("#edit_host").val(response.host);
            $("#edit_ftp_username").val(response.ftp_username);
            $("#edit_ftp_password").val(response.ftp_password);
            
            $("#edit_purpose").val(response.purpose);
            $("#edit_level_pricing").val(response.level_pricing);
            
            if(response.purpose == '2'){
                $('#divEditLevelpricing').removeClass("levelpricingEditFtp");
            }
            
            $('#edit_dept_code').val(response.dept_code).change();
                    setTimeout(function(){
                        $('#edit_cat_code').val(response.cat_code).change();
                    }, 1000);
            $('#edit_cat_code').val(response.cat_code).change();
            document.getElementById('edit_cat_code').value=response.cat_code;
            
            
            $('#editModal').modal('show');
            
          },
          error: function(xhr) { // if error occured
            console.log(xhr);
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
    
    
    
    
  });


//   $(document).on('click', '#save_button', function(event) {
//     event.preventDefault();

//     var edit_url = "{{route('ftpsetting.edit')}}";
    
//     edit_url = edit_url.replace(/&amp;/g, '&');
    
//     var all_aisle = true;
//     $('.aisle_c').each(function(){
//       if($(this).val() == ''){
//         // alert('Please Enter Aisle Name');
//         bootbox.alert({ 
//           size: 'small',
//           title: "Attention", 
//           message: "Please Enter Aisle Name!", 
//           callback: function(){}
//         });
//         all_aisle = false;
//         return false;
//       }else{
//         all_aisle = true;
//       }
//     });
    
//     if(all_aisle == true){
//       $('#form-aisle').attr('action', edit_url);
//       $('#form-aisle').submit();
//       $("div#divLoading").addClass('show');
//     }
//   });
  
  $(function() {
        
        $('.dropdown-select2').select2();
        
        $('#add_departments').select2({width: 'resolve', placeholder: "Please select at least one department", allowClear: true });
        
        $('#add_categories').select2({ placeholder: "Please select at least one category" });
        
        var url = "{{route('ftpsetting.search')}}";
        
        url = url.replace(/&amp;/g, '&');
        
        $( "#automplete-product" ).autocomplete({
            minLength: 2,
            source: function(req, add) {
                $.getJSON(url, req, function(data) {
                    var suggestions = [];
                    $.each(data, function(i, val) {
                        suggestions.push({
                            label: val.aislename,
                            value: val.aislename,
                            id: val.Id
                        });
                    });
                    add(suggestions);
                });
            },
            select: function(e, ui) {
                $('form#form_aisle_search #Id').val(ui.item.id);
                
                if($('#Id').val() != ''){
                    $('#form_aisle_search').submit();
                    $("div#divLoading").addClass('show');
                }
            }
        });
    });

    $(function() { $('input[name="automplete-product"]').focus(); });
    
  
    
    function myFunction() {
        var result = confirm("Want to delete?");
        if (result) {
        
          $(document).on('click', '#aisle_delete', function(event) {
            event.preventDefault();
            var delete_aisle_url = "{{route('ftpsetting.delete')}}";
            delete_aisle_url = delete_aisle_url.replace(/&amp;/g, '&');
            var data = {};
        
            if($("input[name='selected[]']:checked").length == 0){
              bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: 'Please Select FTP to Delete!', 
                callback: function(){}
              });
              return false;
            }
        
            $("input[name='selected[]']:checked").each(function (i)
            {
              data[i] = parseInt($(this).val());
            });
            
            $("div#divLoading").addClass('show');
        
            $.ajax({
                url : delete_aisle_url,
                data : data,
                type : 'get',
                contentType: "application/json",
                dataType: 'json',
              success: function(data) {
                    bootbox.alert({ 
                        size: 'small',
                        title: "Attention", 
                        message: 'FTP Settings Deleted  Successfully!', 
                        callback: function(){}
                    });
                    setTimeout(function(){
                        window.location.reload();
                    }, 3000);
              
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
          });
        }
    }
    
    // $(document).on("change", "#add_purpose", function(){
        
    // });
    $("#add_purpose").change(function(){
        let purpose = $('#add_purpose').val();
        if(purpose == 2){
            $('#divAddLevelpricing').removeClass("levelpricingFtp");
        }else{
            $('#divAddLevelpricing').addClass("levelpricingFtp");
        }
    })
    
    
    $("#edit_purpose").change(function(){
        let edit_purpose = $('#edit_purpose').val();
        if(edit_purpose == 2){
             $('#divEditLevelpricing').removeClass("levelpricingEditFtp");
        }else{
            $('#divEditLevelpricing').addClass("levelpricingEditFtp");
        }
    })
</script>


@endsection