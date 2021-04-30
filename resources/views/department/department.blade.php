@extends('layouts.master')
@section('title', 'Department')
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
      
      @if (session()->has('message'))
      <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>      
    @endif
    @if (session()->has('error_message'))
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{session()->get('error_message')}}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>      
    @endif

    <!--@if ($errors->any())-->
    <!--  <div class="alert alert-danger">-->
    <!--    <ul>-->
    <!--      @foreach ($errors->all() as $error)-->
    <!--        <li>{{$error}}</li>-->
    <!--      @endforeach-->
    <!--    </ul>-->
    <!--  </div>-->
    <!--@endif-->
    
    <div id='errorDiv'>
    </div>
    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
          <i class="fa fa-exclamation-circle"></i>{{$error}}
        @endforeach
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div> 
    @endif

      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Department"; ?></h3>
      </div>
      <div class="panel-body">

        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
            <div class="">
              <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
              <button type="button" onclick="addDepartment();" data-toggle="tooltip" title="<?php //echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</button>
              <button type="button" class="btn btn-danger" id="delete_department_btn" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>      
            </div>
          </div>
        </div>
        <div class="clearfix"></div>

      <form action="/departmentsearch" method="post" id="form_department_search">
        @csrf
        <input type="hidden" name="searchbox" id="idepartmentid">
        <div class="row">
            <div class="col-md-12">
                <input type="text" name="autocomplete-product" class="form-control ui-autocomplete-input" placeholder="Search Department..." id="autocomplete-product" autocomplete="off">
            </div>
        </div>
      </form>
       <br>
        
        <form action="" method="post" enctype="multipart/form-data" id="form-department">
          @csrf
          @method('post')
           @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="edit_hidden_store_hq_val" name="stores_hq" value="">
            @endif
          <input type="hidden" name="MenuId" value="<?php echo $filter_menuid; ?>"/>
          <div class="table-responsive">
            <table id="department" class="table table-bordered table-hover" style="width:100%;">
            <?php if ($departments) { ?>
              <thead>
                <tr>
                  <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                  <th style="width:1px;" class="text-left"><?php echo "Department Code"; ?></th>
                  <th style="width:1px;" class="text-left"><?php echo "Department Name"; ?></th>
                  <th class="text-left"><?php echo "Description"; ?></th>
                  <th class="text-left">Start Time</th>
                  <th class="text-left">End Time</th>
                  <th class="text-left" style="display:none;">Categories</th>
                  <th class="text-left" style="display:none;"><?php //echo $column_sequence; ?></th>
                </tr>
              </thead>
              <tbody id="searchData">
                
                <?php $department_row = 1;$i=0; ?>
                <?php foreach ($departments as $department) { ?>
                <tr id="department-row<?php echo $department_row; ?>">
                  <td data-order="<?php echo $department['idepartmentid']; ?>" class="text-center">
                  <span style="display:none;"><?php echo $department['idepartmentid']; ?></span>
                  <?php if (in_array($department['idepartmentid'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" id="department[<?php echo $department_row; ?>][select]" value="<?php echo $department['idepartmentid']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" id="department[<?php echo $department_row; ?>][select]"  value="<?php echo $department['idepartmentid']; ?>" />
                    <?php } ?></td>
                  
                  <td class="text-left">
                    <span style="display:none;"><?php echo $department['vdepcode']; ?></span>
                    <input type="text" maxlength="50" class="editable department_c" name="department[<?php echo $i; ?>][vdepcode]" id="department[<?php echo $i; ?>][vdepcode]" value="<?php echo $department['vdepcode']; ?>" onclick='document.getElementById("department[<?php echo $department_row; ?>][select]").setAttribute("checked","checked");' />
          			<input type="hidden" name="department[<?php echo $i; ?>][idepartmentid]" value="<?php echo $department['idepartmentid']; ?>"/>
        		  </td>
                  
                  <td class="text-left">
                    <span style="display:none;"><?php echo $department['vdepartmentname']; ?></span>
                    <input type="text" maxlength="50" class="editable departmentname_c" name="department[<?php echo $i; ?>][vdepartmentname]" id="department[<?php echo $i; ?>][vdepartmentname]" value="<?php echo $department['vdepartmentname']; ?>" onclick='document.getElementById("department[<?php echo $department_row; ?>][select]").setAttribute("checked","checked");' />
          			<input type="hidden" name="department[<?php echo $i; ?>][idepartmentid]" value="<?php echo $department['idepartmentid']; ?>"/>
        		</td>
                  <td class="text-left">
                    <textarea class="editable" maxlength="100" name="department[<?php echo $i; ?>][vdescription]" id="department[<?php echo $i; ?>][vdescription]" onclick='document.getElementById("department[<?php echo $department_row; ?>][select]").setAttribute("checked","checked");'><?php echo $department['vdescription']; ?></textarea>
                  </td>
                  <td class="text-left">
                    <?php
                      if(isset($department['starttime']) && !empty($department['starttime'])){
                        $starttime_string = explode(':', $department['starttime']);
                        $start_hour = $starttime_string[0];
                        $start_minute = $starttime_string[1];
                      }else{
                        $start_hour = '';
                        $start_minute = '';
                      }

                      if(isset($department['endtime']) && !empty($department['endtime'])){
                          $endtime_string = explode(':', $department['endtime']);
                          $end_hour = $endtime_string[0];
                          $end_minute = $endtime_string[1];
                        }else{
                          $end_hour = '';
                          $end_minute = '';
                        }

                    ?>

                    <select class="form-control" name="department[<?php echo $i; ?>][start_hour]" style="width:45%;display:inline-block;">
                      <option value="">hour</option>
                      <?php if(isset($hours) && count($hours) > 0) {?>
                        <?php foreach($hours as $k => $hour) { ?>
                          <?php if($start_hour == $k){?>
                            <option value="<?php echo $k;?>" selected="selected"><?php echo $hour;?></option>
                          <?php }else{ ?>
                            <option value="<?php echo $k;?>"><?php echo $hour;?></option>
                          <?php } ?>
                        <?php } ?>
                      <?php } ?>
                    </select>
                    <select class="form-control" name="department[<?php echo $i; ?>][start_minute]" style="width:45%;display:inline-block;">
                      <option value="">minute</option>
                      <?php for($m=0;$m<60;$m++) { ?>
                        <?php if($start_minute == str_pad($m,2,"0",STR_PAD_LEFT)){ ?>
                          <option selected="selected" value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                        <?php }else{ ?>
                          <option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </td>
                  <td class="text-left">
                    <select class="form-control" name="department[<?php echo $i; ?>][end_hour]" style="width:45%;display:inline-block;">
                      <option value="">hour</option>
                      <?php if(isset($hours) && count($hours) > 0) {?>
                        <?php foreach($hours as $k => $hour) { ?>
                          <?php if($end_hour == $k){ ?>
                            <option value="<?php echo $k;?>" selected="selected"><?php echo $hour;?></option>
                          <?php }else{ ?>
                            <option value="<?php echo $k;?>"><?php echo $hour;?></option>
                          <?php } ?>
                        
                        <?php } ?>
                      <?php } ?>
                    </select>
                    <select class="form-control" name="department[<?php echo $i; ?>][end_minute]" style="width:45%;display:inline-block;">
                      <option value="">minute</option>
                      <?php for($m=0;$m<60;$m++) { ?>
                        <?php if($end_minute == str_pad($m,2,"0",STR_PAD_LEFT)){ ?>
                          <option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>" selected="selected"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                        <?php }else{ ?>
                          <option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </td>
                  <td style="display:none;"><span class='view_categories' id='<?php echo $department['idepartmentid']; ?>'>View</span></td>
                  <td class="text-left" style="display:none;">
                      <input type="text" class="editable department_s" name="department[<?php echo $i; ?>][isequence]" id="department[<?php echo $i; ?>][isequence]" value="<?php echo $department['isequence']; ?>" onclick='document.getElementById("department[<?php echo $department_row; ?>][select]").setAttribute("checked","checked");' />
                    </td>
                </tr>
                <?php $department_row++; $i++;?>
                <?php } ?>
                <?php } else { ?>
                  <tr>
                  <td colspan="7" class="text-center"><?php //echo $text_no_results;?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            {{ $departments->links() }}
          </div>
        </form>        
      </div>
    </div>
  </div>
</div>



<!-- Modal Add -->
<div class="modal fade" id="addModal" role="dialog">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Department</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('department.add_list')}}" method="post" id="add_new_form">
            @csrf
            <!--@method('post')-->
            @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
            @endif
          <input type="hidden" name="department[0][idepartmentid]" value="0">
          <input type="hidden" name="department[0][isequence]" value="0">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">  
                  <input type="text" maxlength="50" name="department[0][vdepartmentname]" id="add_vdepartmentname" class="form-control" required>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Description</label>
                </div>
                <div class="col-md-10">  
                  <textarea maxlength="100" name="department[0][vdescription]" id="add_vdescription" class="form-control"></textarea>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Start Time</label>
                </div>
                <div class="col-md-10">  
                  <select class="form-control" name="department[0][start_hour]" style="width:45%;display:inline-block;">
                    <option value="">hour</option>
                    <?php if(isset($hours) && count($hours) > 0) {?>
                      <?php foreach($hours as $k => $hour) { ?>
                      <option value="<?php echo $k;?>"><?php echo $hour;?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                  <select class="form-control" name="department[0][start_minute]" style="width:45%;display:inline-block;">
                    <option value="">minute</option>
                    <?php for($m=0;$m<60;$m++) { ?>
                    <option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="col-md-2">
                  <label>End Time</label>
                </div>
                <div class="col-md-10">  
                  <select class="form-control" name="department[0][end_hour]" style="width:45%;display:inline-block;">
                    <option value="">hour</option>
                    <?php if(isset($hours) && count($hours) > 0) {?>
                      <?php foreach($hours as $k => $hour) { ?>
                      <option value="<?php echo $k;?>"><?php echo $hour;?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                  <select class="form-control" name="department[0][end_minute]" style="width:45%;display:inline-block;">
                    <option value="">minute</option>
                    <?php for($m=0;$m<60;$m++) { ?>
                    <option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 text-center">
              <button type="button" class="btn btn-success" id="save_department" >Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    
  </div>
</div>
<!-- Modal Add-->

<!-- Modal Add -->
<div class="modal fade" id="categoryListModal" role="dialog">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">List of Categories under </h4>
      </div>
      <div class="modal-body">
      
          Categories
      
      </div>
    </div>
    
  </div>
</div>
<!-- Modal Add-->


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

<?php if(session()->get('hq_sid') == 1){ ?>
<!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to add the Department:</h4>
          </div>
        
          <div class="modal-body">
             <table class="table table-bordered">
                <thead id="table_green_header_tag">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="selectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check custom-control-input stores" id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">
                                </div>
                            </td>
                            <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="save_btn_department" class="btn btn-danger" data-dismiss="modal">ADD</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
    
      </div>
    </div>
    
    <div id="EditModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to Edit the Department:</h4>
            <span style="color: #03A9F4">(Please Note: If a Department not exists in any of the stores those department will be created)</span>
          </div>
        
          <div class="modal-body">
             <table class="table table-bordered">
                <thead id="table_green_header_tag">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="editSelectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check custom-control-input editstores" id="hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">
                                </div>
                            </td>
                            <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="Edit_btn_department" class="btn btn-danger" data-dismiss="modal">Update</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
    
      </div>
    </div>
    
    <div id="DeleteModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to delete the Department:</h4>
          </div>
        
          <div class="modal-body">
             <table class="table table-bordered">
                <thead id="table_green_header_tag">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="deleteSelectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody id="deletedep_stores"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="delete_btn_department" class="btn btn-danger" data-dismiss="modal">Delete</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
      </div>
    </div>
    
<?php } ?>

@endsection

@section('scripts')

<script src="{{ asset('javascript/bootbox.min.js')}}"></script>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    
    $('#add_vdepartmentname, .departmentname_c ').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9. _]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    });
    
})

$("#save_department").click(function(){
    var dep_name = $("#add_vdepartmentname").val();
    if(dep_name == ''){
        alert("dep_name is required");
    }else {
        <?php if(session()->get('hq_sid') == 1){ ?>
            $("#addModal").modal('hide');
            $("#myModal").modal('show'); 
        <?php } else { ?>
            $("#add_new_form").submit();
        <?php } ?>
    }
})

var stores_hq = [];
stores_hq.push("{{ session()->get('sid') }}");
$('#selectAllCheckbox').click(function(){
    if($('#selectAllCheckbox').is(":checked")){
        $(".stores").prop( "checked", true );
    }else{
        $( ".stores" ).prop("checked", false );
    }
});
        
$('#save_btn_department').click(function(){
    $.each($("input[name='stores']:checked"), function(){            
        stores_hq.push($(this).val());
    });
    $("#hidden_store_hq_val").val(stores_hq);
    $("#add_new_form").submit();
});


$("#closeBtn").click(function(){
    $("div#divLoading").removeClass('show');
});


  $('#button-filter').on('click', function() {
	url = "{{route('department.search')}}";
	
	var filter_menuid = $('select[name=\'MenuId\']').val();
	
	if (filter_menuid) {
		url += '&filter_menuid=' + encodeURIComponent(filter_menuid);
	}
	
	location = url;
});
function filterpage(){
	url = "{{route('department.search')}}";
	
	var filter_menuid = $('select[name=\'MenuId\']').val();
	
	if (filter_menuid) {
		url += '&filter_menuid=' + encodeURIComponent(filter_menuid);
	}
	
	location = url;
}

var department_row = <?php echo $department_row; ?>;

function addDepartment() {
  $('#addModal').modal('show');
}


$(document).on('submit', 'form#add_new_form', function(event) {
    
    if($('form#add_new_form #add_vdepartmentname').val() == ''){
      // alert('Please enter name!');
      bootbox.alert({ 
        size: 'small',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        title: "Attention", 
        message: "Please enter Department Name!", 
        callback: function(){}
      });
      return false;
    }

    $("div#divLoading").addClass('show');
    
  });


  $(document).on('click', '#save_button', function(event) {
    event.preventDefault();

    var edit_url = '{{route('department.edit_list')}}';
    edit_url = edit_url.replace(/&amp;/g, '&');
    
    if($("input[name='selected[]']:checked").length == 0){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: 'Please Select Department to Edit!', 
        callback: function(){}
      });
      return false;
    }
    
    var all_department = true;
    $('.department_c').each(function(){
        
      if($(this).val() == ''){
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please Enter Department Code", 
          callback: function(){}
        });
        all_department = false;
        return false;
      }else{
        all_department = true;
      }
    });

    var numericReg = /^[0-9]*(?:\.\d{1,2})?$/;
    if(all_department == true){
      var all_done = true;
      $('.department_s').each(function(){
        if($(this).val() != ''){
          if(!numericReg.test($(this).val())){
            bootbox.alert({ 
              size: 'small',
              title: "Attention", 
              message: "Please Enter Only Number", 
              callback: function(){}
            });
            all_done = false;
            return false;
          }else{
            all_done = true;
          }
        }
      });
    }else{
      all_done = false;
    }

    if(all_done == true){
        <?php if(session()->get('hq_sid') == 1){ ?>
            $("#EditModal").modal('show');
        <?php } else { ?>
          $('#form-department').attr('action', edit_url);
          $('#form-department').submit();
          $("div#divLoading").addClass('show');
        <?php } ?>
    }
  });
  
    var edit_stores = [];
    edit_stores.push("{{ session()->get('sid') }}");
    $('#editSelectAllCheckbox').click(function(){
        if($('#editSelectAllCheckbox').is(":checked")){
            $(".editstores").prop( "checked", true );
        }else{
            $( ".editstores" ).prop("checked", false );
        }
    });
            
    $('#Edit_btn_department').click(function(){
        $.each($("input[name='editstores']:checked"), function(){            
            edit_stores.push($(this).val());
        });
        $("#edit_hidden_store_hq_val").val(edit_stores);
        
        var edit_url = '{{route('department.edit_list')}}';
    
        edit_url = edit_url.replace(/&amp;/g, '&');
        
        console.log(edit_stores);
        $('#form-department').attr('action', edit_url);
        $('#form-department').submit();
        $("div#divLoading").addClass('show');
    });
    
    
// ============================= View Categories ===================================
  
  $(document).on('click', '.view_categories', function(event) {
    event.preventDefault();
    var id = this.id;
   // console.log(id);
    //console.log('delete command');
    
    get_categories_url = <?php ?>
    
    $('#categoryListModal').modal('show');
    
        $.ajax({
            url : delete_department_url,
            data : JSON.stringify(data),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
    
                if(data.success){
                  $('#success_msg').html('<strong>'+ data.success +'</strong>');
                  $("div#divLoading").removeClass('show');
                  $('#successModal').modal('show');
        
                  setTimeout(function(){
                   $('#successModal').modal('hide');
                   window.location.reload();
                  }, 3000);
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

    
  });
  
  // ====================== Search code ===============================================
  
  $(document).on('submit', '#form_department_search', function (e) {
      e.preventDefault();
  });
  
  $(document).on('keyup', '#autocomplete-product', function (e) {
      e.preventDefault();

      var q = $(this).val();
      
      if(q.length == 0){
          window.location.reload();
      }
      console.log(q);
      if(q.length != 0){
          if(q.length < 3){
               
          return false;
        }else{
          $('#divLoading').addClass('show');
        }
      }else{
        $('#divLoading').addClass('show');
      }
      //console.log('test');
      $.ajax({
          type: 'POST',
          dataType: 'json',
          url: '/departmentsearch',
          headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
          data: {
              q: q
          },
          
         success: function (departmenT) {
              // Do some nice animation to show results
              //let ageVeri = data['ageveri'];

              let html = '';
              $.each(departmenT.department, function(k, v){
                
                console.log(v);
                
              html +='<tr>';
              html +='<td class="text-center">';
              html +='<input type="checkbox" name="selected[]" id="department['+v.idepartmentid+'][select]" class="checkboxId" value="'+v.idepartmentid+'" >';
              html +='</td>';
              html +='<td class="text-left">';
              html +='<span style="display:none;">'+v.vdepcode+'</span>';     
              html +='<input type="text" maxlength="50" class="editable department_c" name="department['+v.idepartmentid+'][vdepcode]" id="department['+v.idepartmentid+'][vdepcode]" value="'+v.vdepcode+'">';
          	  html +='<input type="hidden" name="department['+v.idepartmentid+'][idepartmentid]" value="'+v.vdepcode+'">';
        	  html +='</td>';

              html +='<td class="text-left">';
              html +='<span style="display:none;">'+v.vdepartmentname+'</span>';
              html += '<input type="text" maxlength="50" class="editable departmentname_c" name="department['+v.idepartmentid+'][vdepartmentname]" id="department['+v.idepartmentid+'][vdepartmentname]" value="'+v.vdepartmentname+'">';
          	  html += '<input type="hidden" name="department['+v.idepartmentid+'][isequence]" value="'+v.isequence+'">';
        	  html +='</td>';  
        	  
              html +='<td class="text-left">';
              html +='<textarea class="editable" maxlength="100" name="department['+v.idepartmentid+'][vdescription]" id="department['+v.idepartmentid+'][vdescription]" value="'+v.vdescription+'"></textarea>';
              html +='</td>';
              
              html +='<td class="text-left">';
                   
            //   html +='if(isset($department[\'starttime\']) && !empty($department[\'starttime\'])){';
              
            //   if(typeof(v.starttime) != "undefined" && v.starttime !== null && v.starttime !== '') {
            //       var starttime_string = split(':', $department['starttime']);
            //   // html +='$starttime_string = explode(':', $department['starttime'])';
            //     html +='$start_hour = $starttime_string[0]';
            //     html +='$start_minute = $starttime_string[1]';
            //   }else{
            //     var $start_hour = '';
            //     var $start_minute = '';
            //   }
           
              
            //   if(typeof(v.endtime) != "undefined" && v.endtime !== null && v.endtime !== '') {
            //       var endtime_string = split(':', $department['endtime']);
            //       html +='$end_hour = $endtime_string[0]';
            //       html +='$end_minute = $endtime_string[1]';
            //       }else{
            //       var $end_hour = '';
            //       var $end_minute = '';
            //   }

              html +='<select class="form-control" name="department['+v.idepartmentid+'][start_hour]" style="width:45%;display:inline-block;">';
              html +='<option value="">hour</option>';
              html +='if(isset($hours) && count($hours) > 0)';
              html +='foreach($hours as $k => $hour)';
              html +='if($start_hour == $k)';
              html +='<option value="<?php echo $k;?>" selected="selected"><?php echo $hour;?></option>';
              html +=' }else{';
              html +='<option value="<?php echo $k;?>"><?php echo $hour;?></option>';
              html +='}';
              html +='}';
              html +='}';
              html +='</select>';
              html +='<select class="form-control" name="department['+v.idepartmentid+'][start_minute]" style="width:45%;display:inline-block;">';
              html +='<option value="">minute</option>';
              html +='<?php for($m=0;$m<60;$m++) { ?>';
              html +='<?php if($start_minute == str_pad($m,2,"0",STR_PAD_LEFT)){ ?>';
              html +='<option selected="selected" value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>';
              html +='<?php }else{ ?>';
              html +='<option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>';
              html +='<?php } ?>';
              html +='<?php } ?>';
              html +='</select>';
              html +='</td>';
              
              
              html +='<td class="text-left">';
              html +='<select class="form-control" name="department['+v.idepartmentid+'][end_hour]" style="width:45%;display:inline-block;">';
              html +='<option value="">hour</option>';
              html +='<?php if(isset($hours) && count($hours) > 0) {?>';
              html +='<?php foreach($hours as $k => $hour) { ?>';
              html +='<?php if($end_hour == $k){ ?>';
              html +='<option value="<?php echo $k;?>" selected="selected"><?php echo $hour;?></option>';
              html +='<?php }else{ ?>';
              html +='<option value="<?php echo $k;?>"><?php echo $hour;?></option>';
              html +='<?php } ?>';
              html +='<?php } ?>';
              html +='<?php } ?>';
              html +='</select>';
              html +='<select class="form-control" name="department['+v.idepartmentid+'][end_minute]" style="width:45%;display:inline-block;">';
              html +='<option value="">minute</option>';
              html +='<?php for($m=0;$m<60;$m++) { ?>';
              html +='<?php if($end_minute == str_pad($m,2,"0",STR_PAD_LEFT)){ ?>';
              html +='<option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>" selected="selected"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>';
              html +='<?php }else{ ?>';
              html +='<option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>';
              html +='<?php } ?>';
              html +='<?php } ?>';
              html +='</select>';
              html +='</td>';
                  
              html +='</tr>';
              
              });

              $('#searchData').html(html);
             $('.pagination').hide();
              $('#divLoading').removeClass('show');
          },
          done: function(){
            $('#divLoading').removeClass('show');
           
            
           
            
          }
          
      });
      
  });
  
// $(function() {
        
//         var url = "{{route('department.search')}}";
        
//         url = url.replace(/&amp;/g, '&');
        
//         $( "#automplete-product" ).autocomplete({
//             minLength: 2,
//             source: function(req, add) {
//                 $.getJSON(url, req, function(data) {
//                     var suggestions = [];
//                     $.each(data, function(i, val) {
//                         suggestions.push({
//                             label: val.vdepartmentname,
//                             value: val.vdepartmentname,
//                             id: val.idepartmentid
//                         });
//                     });
//                     add(suggestions);
//                 });
//             },
//             select: function(e, ui) {
//                 $('form#form_department_search #idepartmentid').val(ui.item.id);
                
//                 if($('#idepartmentid').val() != ''){
//                     $('#form_department_search').submit();
//                     $("div#divLoading").addClass('show');
//                 }
//             }
//         });
//     });

//     $(function() { $('input[name="automplete-product"]').focus(); });

//   $(document).ready(function($) {
//     $("div#divLoading").addClass('show');
//   });

//   $(window).load(function() {
//     $("div#divLoading").removeClass('show');
//   });
  
//  ================ Delete Code =======================

$(document).on('click', '#delete_department_btn', function(event) {
    event.preventDefault();
    if($("input[name='selected[]']:checked").length == 0){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: 'Please Select Department to Delete!', 
        callback: function(){}
      });
      return false;
    }
     var avArr = [];
    $("input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var name = $(this).closest('tr').find('.departmentname_c').val();
      avArr.push({
        vdepcode:id,
        vdepartmentname: name
      });
    });
    console.log(avArr);
    <?php if(session()->get('hq_sid') == 1){ ?>
        $.ajax({
              url: "<?php echo url('/department/duplicatedepartment'); ?>",
              method: 'post',
              headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
              },
              data: {avArr},
              success: function(result){
                        var popup = '';
                        @foreach (session()->get('stores_hq') as $stores)
                            if(result.includes({{ $stores->id }})){
                                var data = '<tr>'+
                                                '<td>'+
                                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                        '<input type="checkbox" class="checks check custom-control-input deletestores" disabled id="hq_sid_{{ $stores->id }}" name="deletestores" value="{{ $stores->id }}">'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Department does not exist)</span></td>'+
                                            '</tr>';
                                        $('#deleteSelectAllCheckbox').attr('disabled', true);
                                  
                            } else {
                                var data = '<tr>'+
                                                '<td>'+
                                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                        '<input type="checkbox" class="checks check custom-control-input deletestores"  id="else_hq_sid_{{ $stores->id }}" name="deletestores" value="{{ $stores->id }}">'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                            '</tr>';
                            }
                            popup = popup + data;
                        @endforeach
                $('#deletedep_stores').html(popup);
            }
        });
        $("#DeleteModal").modal('show');
    <?php } else { ?>
        var delete_department_url = "{{route('department.delete')}}";
        delete_department_url = delete_department_url.replace(/&amp;/g, '&');
        var data = {};
        
        $("div#divLoading").addClass('show');
    
        $.ajax({
            url: "<?php echo url('/department/delete'); ?>",
            method: 'post',
            headers: {
               'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data: {data:avArr },
            success: function(data) {
                if(data['success']){
                  setTimeout(function(){
                    bootbox.alert({ 
                        size: 'small',
                        title: "Attention", 
                        message: 'Deleted Successfully',
                        callback: function(){}
                    });
                   $('#successModal').modal('hide');
                   window.location.reload();
                  }, 3000);
                }else{
                  var errorMsg = '';
                  
                  $.each(data.error_msg, function (k, v){
                      errorMsg += v+'<br/>';
                  });
        
                  $('#error_msg').html('<strong>'+ errorMsg +'</strong>');
                  $("div#divLoading").removeClass('show');
                  $('#errorModal').modal('show');
                  
                  setTimeout(function(){
                      $('#errorModal').modal('hide');
                      window.location.reload();
                      }, 4000);
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
    <?php } ?>
});


var deletestores = [];
deletestores.push("{{ session()->get('sid') }}");
$('#deleteSelectAllCheckbox').click(function(){
    if($('#deleteSelectAllCheckbox').is(":checked")){
        $(".deletestores").prop( "checked", true );
    }else{
        $( ".deletestores" ).prop("checked", false );
    }
});
        
$('#delete_btn_department').click(function(){
    var avArr = [];
    $.each($("input[name='deletestores']:checked"), function(){            
        deletestores.push($(this).val());
    });
    $("input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var name = $(this).closest('tr').find('.departmentname_c').val();
      avArr.push({
        vdepcode:id,
        vdepartmentname: name,
      });
    });
        
        $("div#divLoading").addClass('show');
    
        $.ajax({
            url: "<?php echo url('/department/delete'); ?>",
            method: 'post',
            headers: {
               'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data: {data:avArr, stores_hq:deletestores },
            success: function(data) {
                if(data['success']){
                  setTimeout(function(){
                    bootbox.alert({ 
                        size: 'small',
                        title: "Attention", 
                        message: 'Deleted Successfully',
                        callback: function(){}
                    });
                   $('#successModal').modal('hide');
                   window.location.reload();
                  }, 3000);
                }else{
                  var errorMsg = '';
                  
                  $.each(data.error_msg, function (k, v){
                      errorMsg += v+'<br/>';
                  });
        
                  $('#error_msg').html('<strong>'+ errorMsg +'</strong>');
                  $("div#divLoading").removeClass('show');
                  $('#errorModal').modal('show');
                  
                  setTimeout(function(){
                      $('#errorModal').modal('hide');
                      window.location.reload();
                      }, 4000);
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
});


</script>
<style>
    .disabled {
        pointer-events:none; //This makes it not clickable
    }
</style>
@endsection