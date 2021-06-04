@extends('layouts.layout')

@section('title')
  Sub Category
@endsection

@section('main-content')
<div id="content">
  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
      <div class="container">
          <div class="collapse navbar-collapse" id="main_nav">
              <div class="menu">
                  <span class="font-weight-bold text-uppercase" style="font-size: 22px">Sub Category</span>
              </div>
              <div class="nav-submenu">
                  <button type="button" id="save_button"  class="btn btn-gray headerblack  buttons_menu" title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                  <button type="button" onclick="addCategory();" class="btn btn-gray headerblack  buttons_menu" href="#"> <i class="fa fa-plus"></i>&nbsp;&nbsp; Add New</button>
                  <button type="button" id="delete_category_btn" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> <i class="fa fa-trash"></i>&nbsp;&nbsp; Delete</button>
              </div>
          </div> <!-- navbar-collapse.// -->
      </div>
  </nav>
  <section class="section-content py-6">
      <div class="container">
        <div class="panel panel-default">
        
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
          <div class="panel-body">

          <form action="{{route('subcategory')}}" method="get" id="form_subcategory_search">
            @csrf
            @method('post')
            <input type="hidden" name="searchbox" id="subcat_id">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" style="height: 33px !important; font-size: 12px !important; font-weight: 600;" name="automplete-product" class="form-control" placeholder="Search SubCategory..." id="automplete-product">
                </div>
            </div>
          </form>
            <br>

          <form action="{{route('subcategory.edit_list')}}" method="post" enctype="multipart/form-data" id="form-category">
              @csrf
              @method('post')
              
              @if(session()->get('hq_sid') == 1)
                    <input type="hidden" id="edit_hidden_store_hq_val" name="stores_hq" value="">
                @endif
              
            <input type="hidden" name="MenuId" value="<?php //echo $filter_menuid; ?>"/>
              <div class="table-responsive">
                
              <table id="subcategory" class="table  table-hover" style="width:100%; margin-top: 10px; border-collapse: separate; border-spacing:0 5px !important;">
                <?php if ($data['categories']) { ?>
                  <thead style="background-color: #286fb7!important;">
                    <tr>
                      <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                      {{-- <th style="width:1px;" class="text-left">{{$data['column_category_code'] }}</th> --}}
                      <th class="col-xs-1 headername text-uppercase text-light" data-field="supplier_code">Sub Category Name</th>
                                            
                      {{-- <th class="text-left">{{$data['column_description']}}</th> --}}
                      {{-- <th class="text-left">{{$data['column_category_type']}}</th> --}}
                      <th style="border-bottom-right-radius: 9px; border-top-right-radius: 9px " class="col-xs-1 headername text-uppercase text-light">Category</th>
                      
                      <th class="text-left" style="display:none;"><?php //echo $column_sequence; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <?php $category_row = 1;$i=0; ?>
                    <?php foreach ($data['sub_categories'] as $subcategory) { ?>
                    <tr id="category-row<?php echo $category_row; ?>">
                      <td data-order="<?php echo $subcategory->subcat_id; ?>" class="text-center">
                      <span style="display:none;"><?php echo $subcategory->subcat_id; ?></span>
                      <?php if (in_array($subcategory->subcat_id, $data['selected'])) { ?>
                        <input type="checkbox" name="selected[]" id="category[<?php echo $category_row; ?>][select]" value="<?php echo $subcategory->subcat_id; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" id="category[<?php echo $category_row; ?>][select]"  value="<?php echo $subcategory->subcat_id; ?>" />
                        <?php } ?>
                      </td>
                      
                      <td class="text-left">
                        <span style="display:none;"><?php echo $subcategory->subcat_name; ?></span>
                        <input type="text" style="height: 33px; border:none; font-size: 12px !important; font-weight: 600;" maxlength="50" class="editable subcategories_c" name="subcategory[<?php echo $i; ?>][subcat_name]" id="category[<?php echo $i; ?>][vcategoryname]" value="<?php echo $subcategory->subcat_name; ?>" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");' />
                        <input type="hidden" name="subcategory[<?php echo $i; ?>][subcat_id]" value="<?php echo $subcategory->subcat_id; ?>"/>
                      </td>
                      <?php 
                      // echo "<pre>";
                      // print_r($data['categories']);
                      // die;
                      ?>
                        <td style="border-bottom-right-radius: 9px; border-top-right-radius: 9px "> 
                            <select style="height: 33px; font-size: 12px !important; font-weight: 600;" name="subcategory[<?php echo $i; ?>][cat_id]" id="category[<?php echo $i; ?>][dept_code]" class="form-control categories_c" onchange='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");'>
                                <option value="">--Select Category--</option>
                                <?php if(isset($data['categories']) && count($data['categories']) > 0){?>
                                    <?php foreach($data['categories'] as $category){ ?>
                                        <?php if(isset($subcategory->cat_id) && $subcategory->cat_id == $category->icategoryid){?>
                                            <option value="<?php echo $category->icategoryid;?>" selected="selected"><?php echo $category->vcategoryname;?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $category->icategoryid;?>"><?php echo $category->vcategoryname;?></option>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    
                      <td class="text-left" style="display:none;">
                        <input type="text" class="editable categories_s" name="category[<?php echo $i; ?>][isequence]" id="category[<?php echo $i; ?>][isequence]" value="<?php echo $category->isequence; ?>" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");' />
                      </td>
                    </tr>
                    <?php $category_row++; $i++;?>
                    <?php } ?>
                    <?php } else { ?>
                      <tr>
                      <td colspan="7" class="text-center"><?php //echo $text_no_results;?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <div class="pull-right">
                  {{ $data['sub_categories']->links()}}
                </div>
              </div>
            </form>
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
          <h5 class="modal-title">Add New Sub Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form action="{{route('subcategory.add_list')}}" method="post" id="add_new_form">
           @csrf
          <!--@method('post')-->
            @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
            @endif
          <input type="hidden" name="subcategory[0][subcat_id]" value="0">
          <input type="hidden" name="subcategory[0][isequence]" value="0">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">  
                  <input type="text" maxlength="50" class="form-control" id="add_vsubcategoryname" name="subcategory[0][subcat_name]" required>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Category</label>
                </div>
                <div class="col-md-10">  
                  <select name="subcategory[0][icategoryid]" id="add_cat_code" class="form-control" required>
                    <option value="" selected="selected">--Select Category--</option>
                    
                      <?php if(isset($data['categories']) && count($data['categories']) > 0){?>
                          <?php foreach($data['categories'] as $category){ ?>
                              <option value="<?php echo $category->icategoryid;?>"><?php echo $category->vcategoryname;?></option>
                          <?php } ?>
                      <?php } ?>
                    
                  </select>
                  
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="button" class="btn btn-success" id="save_subcategory" value="Save">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    
  </div>
</div>
<!-- Modal Add-->


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
            <h4 class="modal-title">Select the stores in which you want to add the SubCategory :</h4>
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
                <tbody id="add_subcat_stores">
                    
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="save_btn_category" class="btn btn-danger" data-dismiss="modal">ADD</button>
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
            <h4 class="modal-title">Select the stores in which you want to Edit the SubCategory :</h4>
            <span style="color: #03A9F4">(Please Note: If a SubCategory not exists in any of the stores those subcategory will be created)</span>
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
                <tbody id="edit_cat_stores">
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
            <button id="Edit_btn_subcategory" class="btn btn-danger" data-dismiss="modal">Update</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
    
      </div>
    </div>
    
     <div id="DeleteModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         Modal content
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to delete the Sub Category :</h4>
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
                <tbody id="deletecat_stores"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="delete_btn_category" class="btn btn-danger" data-dismiss="modal">Delete</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
      </div>
    </div>
    
<?php } ?>



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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
  
<script type="text/javascript">

$(document).ready(function(){
    
    $('#add_vsubcategoryname, .subcategories_c').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9. _]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    });
    
})



 $('#button-filter').on('click', function() {
    url = '';
    var filter_menuid = $('select[name=\'MenuId\']').val();
    if (filter_menuid) {
      url += '&filter_menuid=' + encodeURIComponent(filter_menuid);
    }
    location = url;
  });
 
 function filterpage(){
    url = '';
    
    var filter_menuid = $('select[name=\'MenuId\']').val();
    
    if (filter_menuid) {
      url += '&filter_menuid=' + encodeURIComponent(filter_menuid);
    }
    
    location = url;
  }
  
  function addCategory() {
     $('#addModal').modal('show');
  }
  
  $("#save_subcategory").click(function(){
    $("#addModal").modal('hide');
    var subcat_name = $("#add_vsubcategoryname").val();
    var cat_code = $("#add_cat_code").val();
    
    
    if(subcat_name == ''){
        alert("Subcat Name is required");
    }else if(cat_code == ''){
        alert("Select Category");
    }else {
        <?php if(session()->get('hq_sid') == 1){ ?>
            
            $('#selectAllCheckbox').attr('disabled', false);
            
            $.ajax({
                url: "<?php echo url('/subcategory/duplicatesubcategory'); ?>",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                data: {vcategorycode:cat_code, status:'new_add'},
                success: function(result){
                        var popup = '';
                        @foreach (session()->get('stores_hq') as $stores)
                            if(result.includes({{ $stores->id }})){
                                var data = '<tr>'+
                                                '<td>'+
                                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                        '<input type="checkbox" class="checks check custom-control-input stores" disabled id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Category does not exist)</span></td>'+
                                            '</tr>';
                                        $('#selectAllCheckbox').attr('disabled', true);
                                  
                            } else {
                                var data = '<tr>'+
                                                '<td>'+
                                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                        '<input type="checkbox" class="checks check custom-control-input stores"  id="else_hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                            '</tr>';
                            }
                            popup = popup + data;
                        @endforeach
                $('#add_subcat_stores').html(popup);
                }
            });
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

$('#save_btn_category').click(function(){
    $.each($("input[name='stores']:checked"), function(){            
        stores_hq.push($(this).val());
    });
    console.log(stores_hq);
    $("#hidden_store_hq_val").val(stores_hq.join(','));
    $("#add_new_form").submit();
});

$("#closeBtn").click(function(){
    $("div#divLoading").removeClass('show');
});

// ========================== Edit ========================================== 
  
  $(document).on('click', '#save_button', function(event) {
    event.preventDefault();
    
    var edit_url = '{{route('subcategory.edit_list')}}';
    edit_url = edit_url.replace(/&amp;/g, '&');
    var all_category = true;
    var subcatids = [];
    
    if($("input[name='selected[]']:checked").length == 0){
      $('#warning_msg').html('Please Select SubCategory to Edit!');
      $("div#divLoading").removeClass('show');
      $('#warningModal').modal('show');
      return false;
    }
    
    $('.subcategories_c').each(function(){
        subcatids.push($(this).val());
      if($(this).val() == ''){
        $('#warning_msg').html('Please Enter Sub Category Name');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        all_category = false;
        return false;
      }else{
        all_category = true;
      }
    });

    var numericReg = /^[0-9]*(?:\.\d{1,2})?$/;

    if(all_category == true){
      var all_done = true;
      $('.categories_s').each(function(){
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
    
    // if(all_done == true){
    //   $('#form-category').attr('action', edit_url);
    //   $('#form-category').submit();
    //   $("div#divLoading").addClass('show');
    // }
    
    var avArr = [];
    $("#subcategory input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var catcode = $(this).closest('tr').find('.categories_c').val();
      var catname = $(this).closest('tr').find('.categoriesname_c').text();
      avArr.push({
        subcat_id:id,
        vcategorycode:catcode,
        vcategoryname: catname
      });
    });
    
    if(all_done == true){
        <?php if(session()->get('hq_sid') == 1){ ?>
            
            $('#editSelectAllCheckbox').attr('disabled', false);
            
            $.ajax({
                url: "<?php echo url('/subcategory/duplicatesubcategory'); ?>",
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
                                                '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Category does not exist)</span></td>'+
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
                $('#edit_cat_stores').html(popup);
                }
            });
            $("#EditModal").modal('show');
        <?php } else { ?>
          $('#form-category').attr('action', edit_url);
          $('#form-category').submit();
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
    
    $('#Edit_btn_subcategory').click(function(){
        $.each($("input[name='editstores']:checked"), function(){            
            edit_stores.push($(this).val());
        });
        $("#edit_hidden_store_hq_val").val(edit_stores.join(","));
        
        var edit_url = '{{route('subcategory.edit_list')}}';
        edit_url = edit_url.replace(/&amp;/g, '&');
        
        // console.log(edit_stores);
        $('#form-category').attr('action', edit_url);
        $('#form-category').submit();
        $("div#divLoading").addClass('show');
    });
    
</script>

<script>
  $(function() {
      
    var url = '{{route('subcategory.search')}}';
      
      url = url.replace(/&amp;/g, '&');
      
      $( "#automplete-product" ).autocomplete({
          minLength: 2,
          source: function(req, add) {
              $.getJSON(url, req, function(data) {
                  var suggestions = [];
                  $.each(data, function(i, val) {
                      suggestions.push({
                          label: val.subcat_name,
                          value: val.subcat_name,
                          id: val.subcat_id
                      });
                  });
                  add(suggestions);
              });
          },
          select: function(e, ui) {
              $('form#form_subcategory_search #subcat_id').val(ui.item.id);
              
              if($('#subcat_id').val() != ''){
                  $('#form_subcategory_search').submit();
                  $("div#divLoading").addClass('show');
              }
          }
      });
  });

  $(function() { $('input[name="automplete-product"]').focus(); });
</script>

<script type="text/javascript">
$(document).ready(function($) {
  $("div#divLoading").addClass('show');
});


</script>

<script type="text/javascript">

// ============================= Delete =======================================================

   $(document).on('click', '#delete_category_btn', function(event) {
    event.preventDefault();
    
    if($("input[name='selected[]']:checked").length == 0){
      
      $('#warning_msg').html('Please Select SubCategory to Delete!');
      $("div#divLoading").removeClass('show');
      $('#warningModal').modal('show');
      
      return false;
    }
    
    var avArr = [];
    $("#subcategory input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var name = $(this).closest('tr').find('.subcategories_c').val();
      avArr.push({
        subcat_id:id,
        subcat_name: name
      });
    });
    
    <?php if(session()->get('hq_sid') == 1){ ?>
        $.ajax({
              url: "<?php echo url('/subcategory/duplicatesubcategory'); ?>",
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
                                                '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Sub Category does not exist)</span></td>'+
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
                $('#deletecat_stores').html(popup);
            }
        });
        $("#DeleteModal").modal('show');
    <?php } else { ?>
    
     var delete_category_url = "{{route('subcategory.delete')}}";
     delete_category_url = delete_category_url.replace(/&amp;/g, '&');
     var data = {};

        $("div#divLoading").addClass('show');
    
        $.ajax({
            url : delete_category_url,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify({data:avArr}),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                if(data['success_msg']){
                    $('#success_msg').html('<strong>Sub category Deleted successfully</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#successModal').modal('show');
                    setTimeout(function(){
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

$('#delete_btn_category').click(function(){
    var avArr = [];
    $("#subcategory input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var name = $(this).closest('tr').find('.subcategories_c').val();
      avArr.push({
        subcat_id:id,
        subcat_name: name
      });
    });
    $.each($("input[name='deletestores']:checked"), function(){            
        deletestores.push($(this).val());
    });
    $("#subcategory input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var name = $(this).closest('tr').find('.subcategories_c').val();
      avArr.push({
        subcat_id:id,
        subcat_name: name,
      });
    });
    
    var delete_category_url = "{{route('subcategory.delete')}}";
    delete_category_url = delete_category_url.replace(/&amp;/g, '&');
    var data = {};
    $("div#divLoading").addClass('show');
    $.ajax({
            url : delete_category_url,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify({data:avArr, stores_hq: deletestores}),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                if(data.success){
                  var successMsg ='';
                    $('#success_msg').html('<strong>Sub category Deleted successfully</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#successModal').modal('show');
                    setTimeout(function(){
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