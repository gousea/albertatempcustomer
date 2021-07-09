@extends('layouts.layout')
@section('title', 'Unit')
@section('main-content')
<div id="content">
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase" > Unit</span>
                </div>
                <div class="nav-submenu">
                    <button type="button" id="save_button"  class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                    <button type="button" onclick="addUnit();" data-toggle="tooltip" class="btn btn-gray headerblack  buttons_menu " href="#"> <i class="fa fa-plus"></i>&nbsp;&nbsp; Add New</button>
                    <button type="button" id="unit_delete" onclick="myFunction()" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> <i class="fa fa-trash"></i>&nbsp;&nbsp; Delete</button>
                </div>
            </div> <!-- navbar-collapse.// -->
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

        <div class="panel panel-default">
        
          <div class="panel-body">
              <form action="<?php echo $current_url;?>" method="post" id="form_unit_search">
                @csrf
                
                <input type="hidden" name="searchbox" id="iunitid">
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" name="automplete-product" class="form-control" placeholder="Search Unit..." id="automplete-product">
                    </div>
                </div>
              </form>
              <br>
              <form action="" method="post" enctype="multipart/form-data" id="form-unit">
                @csrf
                @method('post')
                @if(session()->get('hq_sid') == 1)
                    <input type="hidden" id="edit_hidden_store_hq_val" name="stores_hq" value="">
                @endif
                <div class="table-responsive">
                  <table id="unit" class="table table-hover" style="width:100%; margin-top: 10px; border-collapse: separate; border-spacing:0 5px !important;">
                  <?php if ($units) { ?>
                    <thead style="background-color: #286fb7!important;">
                      <tr>
                        <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                        <th class="col-xs-1 headername text-uppercase text-light"><?php echo $column_unit_code; ?></th>
                        <th class="col-xs-1 headername text-uppercase text-light"><?php echo $column_unit_name; ?></th>
                        <th class="col-xs-1 headername text-uppercase text-light"><?php echo $column_unit_description; ?></th>
                        <th class="col-xs-1 headername text-uppercase text-light"><?php echo $column_unit_status; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <?php $unit_row = 1;$i=0; ?>
                      <?php foreach ($units as $unit) { ?>
                      <tr id="unit-row<?php echo $unit_row; ?>">
                        <td data-order="<?php echo $unit['iunitid']; ?>" class="text-center">
                            <span style="display:none;"><?php echo $unit['iunitid']; ?></span>
                            <?php if (in_array($unit['iunitid'], $selected)) { ?>
                              <input type="checkbox" name="selected[]" id="unit[<?php echo $unit_row; ?>][select]" value="<?php echo $unit['iunitid']; ?>" checked="checked" />
                            <?php } else { ?>
                              <input type="checkbox" name="selected[]" id="unit[<?php echo $unit_row; ?>][select]"  value="<?php echo $unit['iunitid']; ?>" />
                            <?php } ?>
                        </td>
                        
                        <td class="text-left">
                            <span style="display:none;"><?php echo $unit['vunitcode']; ?></span>
                            <input type="text" style="border:none;" maxlength="20" class="editable unitcode_c" name="unit[<?php echo $i; ?>][vunitcode]" id="unit[<?php echo $i; ?>][vunitcode]" value="<?php echo $unit['vunitcode']; ?>" onclick='document.getElementById("unit[<?php echo $unit_row; ?>][select]").setAttribute("checked","checked");' />
          
                            <input type="hidden" name="unit[<?php echo $i; ?>][iunitid]" value="<?php echo $unit['iunitid']; ?>" />
      
                        </td>
      
                        <td class="text-left">
                        <span style="display:none;"><?php echo $unit['vunitname']; ?></span>
                          <input type="text" maxlength="50" style="border:none;" class="editable unit_c" name="unit[<?php echo $i; ?>][vunitname]" id="unit[<?php echo $i; ?>][vunitname]" value="<?php echo $unit['vunitname']; ?>" onclick='document.getElementById("unit[<?php echo $unit_row; ?>][select]").setAttribute("checked","checked");' />
      
                        </td>
      
                        <td class="text-left">
                          <textarea class="editable" style="border:none;" maxlength="100" name="unit[<?php echo $i; ?>][vunitdesc]" id="unit[<?php echo $i; ?>][vunitdesc]" onclick='document.getElementById("unit[<?php echo $unit_row; ?>][select]").setAttribute("checked","checked");'><?php echo $unit['vunitdesc']; ?></textarea>
                        </td>
                      
                        <td class="text-left">
                          <select name="unit[<?php echo $i; ?>][estatus]" id="unit[<?php echo $i; ?>][estatus]" class="form-control" onchange='document.getElementById("unit[<?php echo $unit_row; ?>][select]").setAttribute("checked","checked");'>
                              <?php  if ($unit['estatus']==$Active) { ?>
                              <option value="<?php echo $Active; ?>" selected="selected"><?php echo $Active; ?></option>
                              <option value="<?php echo $Inactive; ?>" ><?php echo $Inactive; ?></option>
                              <?php } else if($unit['estatus']==$Inactive){ ?>
                                <option value="<?php echo $Active; ?>"><?php echo $Active; ?></option>
                              <option value="<?php echo $Inactive; ?>" selected="selected"><?php echo $Inactive; ?></option>
                              <?php } else { ?>
                              <option value="<?php echo $Active; ?>"><?php echo $Active; ?></option>
                              <option value="<?php echo $Inactive; ?>" selected="selected"><?php echo $Inactive; ?></option>
                              <?php } ?>
                            </select>
                          </td>
      
                      </tr>
                      <?php $unit_row++; $i++;?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td colspan="7" class="text-center"><?php echo $text_no_results;?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </form>
              <?php if ($units) { ?>
                <div class="row">
                  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
              <?php } ?>
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
            <h5 class="modal-title">Add New Unit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
          <form class="form-inline"  action="<?php echo $edit_list; ?>" method="post" id="add_new_form">
            @csrf
            @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
            @endif
            <input type="hidden" name="unit[0][iunitid]" value="0">
            <div class="row" style="width:100%">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Code</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" style="width: 100%" name="unit[0][vunitcode]" id="add_vunitcode" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row" style="width: 100%">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" style="width: 100%" name="unit[0][vunitname]" id="add_vunitname" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row" style="width: 100%">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Description</label>
                  </div>
                  <div class="col-md-10">  
                    <textarea name="unit[0][vunitdesc]" style="width: 100%" id="add_vunitdesc" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row" style="width: 100%">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Status</label>
                  </div>
                  <div class="col-md-10">  
                    <select name="unit[0][estatus]" class="form-control" style="width: 100%">
                      <option value="<?php echo $Active; ?>" selected="selected"><?php echo $Active; ?></option>
                      <option value="<?php echo $Inactive; ?>" ><?php echo $Inactive; ?></option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row" style="width: 100%">
              <div class="col-md-12 text-center">
                <button type="button" class="btn btn-success" id="save_unit" > Save</button>
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
<!-- Modal Add-->

<div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert-danger text-center">
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
            <h4 class="modal-title">Select the stores in which you want to add the Unit:</h4>
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
            <button id="save_btn_unit" class="btn btn-danger" data-dismiss="modal">ADD</button>
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
            <h4 class="modal-title">Select the stores in which you want to Edit the Unit:</h4>
            <span style="color: #03A9F4">(Please Note: If a Unit not exists in any of the stores those unit will be created)</span>
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
                <tbody id="edit_unit_stores"></tbody>
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
            <h4 class="modal-title">Select the stores in which you want to delete the Unit:</h4>
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
            <button id="delete_btn_unit" class="btn btn-danger" data-dismiss="modal">Delete</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
      </div>
    </div>
    
<?php } ?>


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


@endsection

@section('page-script')

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});

 // ==================================== Add Unit ==========================================

$("#save_unit").click(function(){
    var unit_code = $("#add_vunitcode").val();
     var unit_name = $("#add_vunitname").val();
    if(unit_code == ''){
        alert("unit_code is required");
    }else if(unit_name == ''){
        alert("unit_name is required");
    }else {
        <?php if(session()->get('hq_sid') == 1){ ?>
            $("#myModal").modal('show'); 
            $("#addModal").modal('hide');
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
 
    
$('#save_btn_unit').click(function(){
    $.each($("input[name='stores']:checked"), function(){            
        stores_hq.push($(this).val());
    });
    $("#hidden_store_hq_val").val(stores_hq);
    $("#add_new_form").submit();
});

$("#closeBtn").click(function(){
    $("div#divLoading").removeClass('show');
});

// ======================= Edit Unit ===================================

 $(document).on('click', '#save_button', function(event) {
    event.preventDefault();
    
    var edit_url = '{{route('unit.edit_list')}}';
    edit_url = edit_url.replace(/&amp;/g, '&');
    var all_code_unit = true;
    var all_name_unit = true;
    
    if($("input[name='selected[]']:checked").length == 0){
      $('#warning_msg').html('Please Select Unit to Edit!');
      $("div#divLoading").removeClass('show');
      $('#warningModal').modal('show');
      return false;
    }
    
    $('.units_code_c').each(function(){
      if($(this).val() == ''){
        $('#warning_msg').html('Please Enter Unit Code');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        all_code_unit = false;
        return false;
      }else{
        all_code_unit = true;
      }
    });

    if(all_code_unit == true){
      $('.units_name_c').each(function(){
        if($(this).val() == ''){
          $('#warning_msg').html('Please Enter Unit Name');
          $("div#divLoading").removeClass('show');
          $('#warningModal').modal('show');
          all_name_unit = false;
          return false;
        }else{
          all_name_unit = true;
        }
      });
    }else{
      all_code_unit = false;
      return false;
    }
    
    if(all_code_unit == true && all_name_unit == true){
      var all_unit = true;
    }else{
      var all_unit = false;
    }
    
    var numericReg = /^[0-9]*(?:\.\d{1,2})?$/;
    if(all_code_unit == true && all_name_unit == true){
      var all_done = true;
      $('.units_name_c').each(function(){
        if($(this).val() != ''){
          if(!numericReg.test($(this).val())){
            $('#warning_msg').html('Please Enter Only Number');
            $("div#divLoading").removeClass('show');
            $('#warningModal').modal('show');
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
        var avArr = [];
        $("#unit input[name='selected[]']:checked").each(function () {
          var id =$(this).val();
          var name = $(this).closest('tr').find('.unit_c').val();
          var code = $(this).closest('tr').find('.unitcode_c').val();
          avArr.push({
            iunitid:id,
            vunitcode:code,    
            vunitname: name
          });
        });
        <?php if(session()->get('hq_sid') == 1){ ?>
            if(avArr.length > 0){
                $.ajax({
                      url: "<?php echo url('/unit/duplicateunitforedit'); ?>",
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
                                                                '<input type="checkbox" class="checks check custom-control-input editstores" disabled id="hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                            '</div>'+
                                                        '</td>'+
                                                        '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Unit does not exist)</span></td>'+
                                                    '</tr>';
                                                $('#editSelectAllCheckbox').attr('disabled', true);
                                    } else {
                                        var data = '<tr>'+
                                                        '<td>'+
                                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                '<input type="checkbox" class="checks check custom-control-input editstores"  id="else_hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                            '</div>'+
                                                        '</td>'+
                                                        '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                    '</tr>';
                                    }
                                    popup = popup + data;
                                @endforeach
                        $('#edit_unit_stores').html(popup);
                    }
                });
                $("#EditModal").modal('show');
            }else{
                alert("Please select unit first");
            }
            
        <?php } else { ?>
          $('#form-unit').attr('action', edit_url);
          $('#form-unit').submit();
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
        
        var edit_url = '{{route('unit.edit_list')}}';
    
        edit_url = edit_url.replace(/&amp;/g, '&');
        
        console.log(edit_stores);
        $('#form-unit').attr('action', edit_url);
        $('#form-unit').submit();
        $("div#divLoading").addClass('show');
    });

</script>

<script type="text/javascript">
var unit_row = <?php echo $unit_row; ?>;

function addUnit() {
    $('#addModal').modal('show');
}
</script>


<?php echo $footer; ?>

<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>
<script>
    $(function() {
        
        var url = '<?php echo $searchunit;?>';
        
        url = url.replace(/&amp;/g, '&');
        
        $( "#automplete-product" ).autocomplete({
            minLength: 2,
            source: function(req, add) {
                $.getJSON(url, req, function(data) {
                    var suggestions = [];
                    $.each(data, function(i, val) {
                        suggestions.push({
                            label: val.vunitname,
                            value: val.vunitname,
                            id: val.iunitid
                        });
                    });
                    add(suggestions);
                });
            },
            select: function(e, ui) {
                $('form#form_unit_search #iunitid').val(ui.item.id);
                
                if($('#iunitid').val() != ''){
                    $('#form_unit_search').submit();
                    $("div#divLoading").addClass('show');
                }
            }
        });
    });

    $(function() { $('input[name="automplete-product"]').focus(); });
</script>



<script>

// ================================== Delete Methode =====================================

 $(document).on('click', '#unit_delete', function(event) {
    event.preventDefault();
    
    if($("input[name='selected[]']:checked").length == 0){
      $('#warning_msg').html('Please Select Unit to Delete!');
      $("div#divLoading").removeClass('show');
      $('#warningModal').modal('show');
      return false;
    }
     var avArr = [];
    $("#unit input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var name = $(this).closest('tr').find('.unit_c').val();
      var code = $(this).closest('tr').find('.unitcode_c').val();
      avArr.push({
        iunitid:id,
        vunitcode:code,    
        vunitname: name
      });
    });
    console.log(avArr);
    <?php if(session()->get('hq_sid') == 1){ ?>
        $.ajax({
              url: "<?php echo url('/unit/duplicateunit'); ?>",
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
                                                '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Unit does not exist)</span></td>'+
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
        
        $("div#divLoading").addClass('show');
    
        $.ajax({
            url :  "<?php echo url('/unit/delete'); ?>",
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify({data:avArr}),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                if(data['success']){
                  setTimeout(function(){
                    $('#success_msg').html('<strong>Unit is deleted successfully</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#successModal').modal('show');
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

$('#delete_btn_unit').click(function(){
    var avArr = [];
    $.each($("input[name='deletestores']:checked"), function(){            
        deletestores.push($(this).val());
    });
    $("input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var name = $(this).closest('tr').find('.unit_c').val();
      var code = $(this).closest('tr').find('.unitcode_c').val();
      avArr.push({
        iunitid:id,
        vunitname: name,
        vunitcode:code,
      });
      
    });
        
        $.ajax({
            url: "<?php echo url('/unit/delete'); ?>",
            method: 'post',
            headers: {
                  'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : {data:avArr, stores_hq: deletestores},
            success: function(data) {
                if(data['success']){
                  setTimeout(function(){
                    $('#success_msg').html('<strong>Unit is deleted successfully</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#successModal').modal('show');
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

<script type="text/javascript">
  $(document).on('click', '#add_button', function(event) {
    event.preventDefault();
    var edit_url = '<?php echo $add; ?>';
    
    edit_url = edit_url.replace(/&amp;/g, '&');
    
    var all_code_unit = true;
    var all_name_unit = true;
    
    $('.units_code_c').each(function(){
      if($(this).val() == ''){
        $('#warning_msg').html('Please Enter Unit Code');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        all_code_unit = false;
        return false;
      }else{
        all_code_unit = true;
      }
    });

    if(all_code_unit == true){
      $('.units_name_c').each(function(){
        if($(this).val() == ''){
          $('#warning_msg').html('Please Enter Unit Name');
          $("div#divLoading").removeClass('show');
          $('#warningModal').modal('show');
          all_name_unit = false;
          return false;
        }else{
          all_name_unit = true;
        }
      });
    }else{
      all_code_unit = false;
      return false;
    }
    

    if(all_code_unit == true && all_name_unit == true){
      var all_unit = true;
    }else{
      var all_unit = false;
    }
    
    if(all_unit == true){
      $('#form-unit').attr('action', edit_url);
      $('#form-unit').submit();
    }
  });
</script>

@endsection

@section('styles')
<style>
  .ui-autocomplete
{
    position:absolute;
    cursor:default;
    z-index:1001 !important
}
.ui-front {
    z-index: 1500 !important;
}
</style>
@endsection