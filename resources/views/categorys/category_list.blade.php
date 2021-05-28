@extends('layouts.layout')

@section('title')
  Category
@stop


@section('main-content')
<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase" style="font-size: 22px"> Category</span>
            </div>
            <div class="nav-submenu">
                <button type="button" id="save_button"  class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                <button type="button" onclick="addCategory();" class="btn btn-gray headerblack  buttons_menu " href="#"> <i class="fa fa-plus"></i>&nbsp;&nbsp; Add New</button>
                <button type="button" id="delete_category_btn" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> <i class="fa fa-trash"></i>&nbsp;&nbsp; Delete</button>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
</nav>
<section class="section-content py-6">
    <div id="content">
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

                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
                <form action="{{route('category')}}" method="get" id="form_category_search" style="width: 100%">
                    @csrf
                    @method('get')
                    <input type="hidden" name="searchbox" id="icategoryid">
                    <div class="row">
                        <div class="col-md-12">
                            <input style="height: 33px" type="text" name="automplete-product"  class="form-control" placeholder="Search Category..." id="automplete-product">
                        </div>
                    </div>
                </form>
                <div class="panel-body">
                    <form action="" method="post" enctype="multipart/form-data" id="form-category">
                        @csrf
                        @method('post')
                        @if(session()->get('hq_sid') == 1)
                            <input type="hidden" id="edit_hidden_store_hq_val" name="stores_hq" value="">
                        @endif
                        <input type="hidden" name="MenuId" value="<?php //echo $filter_menuid; ?>"/>
                        <div class="table-responsive">
                            <table id="category" class="table  table-hover" style="width:100%; margin-top: 10px; border-collapse: separate; border-spacing:0 5px !important;">
                                <?php if ($data['categories']) { ?>
                                    <thead>
                                        <tr style="background-color: #286fb7!important;" >
                                            <th style="width: 1px;color:black; border-bottom-left-radius: 9px" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                                            <th class="col-xs-1 headername text-uppercase text-light">Category Code</th>
                                            <th class="col-xs-1 headername text-uppercase  text-light" data-field="supplier_code">Category Name</th>
                                            <th class="col-xs-1 headername text-uppercase  text-light" data-field="supplier_code">Description</th>
                                            <th class="col-xs-1 headername text-uppercase  text-light" data-field="supplier_code">Category Type</th>
                                            <th class="col-xs-1 headername text-uppercase  text-light" data-field="supplier_code">Department</th>
                                            <th style="border-bottom-right-radius: 9px; border-top-right-radius: 9px " class="col-xs-1 headername text-uppercase  text-light" data-field="supplier_code">No. Of Sub Categories</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $category_row = 1;$i=0; ?>
                                        <?php foreach ($data['categories'] as $category) { ?>
                                            <tr id="category-row<?php echo $category_row; ?>">
                                                <td style="border-bottom-left-radius: 9px !important; " data-order="<?php echo $category->icategoryid; ?>" class="text-center">
                                                    <span style="display:none;"><?php echo $category->icategoryid; ?></span>
                                                    <?php if (in_array($category->icategoryid, $data['selected'])) { ?>
                                                    <input type="checkbox" name="selected[]" id="category[<?php echo $category_row; ?>][select]" value="<?php echo $category->icategoryid; ?>" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="checkbox" name="selected[]" id="category[<?php echo $category_row; ?>][select]"  value="<?php echo $category->icategoryid; ?>" />
                                                    <?php } ?>
                                                </td>

                                                <td class="text-left">
                                                    <span style="display:none;"><?php echo $category->vcategorycode; ?></span>
                                                    <input type="hidden" maxlength="50" style="border:none;" class="editable categories_c" name="category[<?php echo $i; ?>][vcategorycode]" id="category[<?php echo $i; ?>][vcategorycode]" value="<?php echo $category->vcategorycode; ?>" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");' />
                                    				        <?php echo $category->vcategorycode; ?>
                                    			          <input type="hidden" name="category[<?php echo $i; ?>][icategoryid]" value="<?php echo $category->icategoryid; ?>"/>
                                    			      </td>

                                                <td class="text-left">
                                                    <span style="display:none;"><?php echo $category->vcategoryname; ?></span>
                                                    <input type="text" maxlength="50" style="border:none;"  class="editable categoriesname_c" name="category[<?php echo $i; ?>][vcategoryname]" id="category[<?php echo $i; ?>][vcategoryname]" value="<?php echo $category->vcategoryname; ?>" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");' />
                                    				        <input type="hidden" name="category[<?php echo $i; ?>][icategoryid]" value="<?php echo $category->icategoryid; ?>"/>
                                    			      </td>

                                                <td class="text-left"><textarea maxlength="100" style="border:none; height: 33px;"  class="editable" name="category[<?php echo $i; ?>][vdescription]" id="category[<?php echo $i; ?>][vdescription]" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");'><?php echo $category->vdescription; ?></textarea></td>

                                                <td class="text-left">
                                                    <select style="height: 33px; font-size: 12px !important; font-weight: 600" name="category[<?php echo $i; ?>][vcategorttype]" id="category[<?php echo $i; ?>][vcategorttype]" class="form-control" onchange='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");'>
                                                        <?php  if ($category->vcategorttype=="Sales") { ?>
                                                        <option value="<?php echo "Sales"; ?>" selected="selected"><?php echo "Sales"; ?></option>
                                                        <option value="<?php echo "MISC"; ?>" ><?php echo "MISC"; ?></option>
                                                        <?php } else { ?>
                                                        <option value="<?php echo "Sales"; ?>"><?php echo "Sales"; ?></option>
                                                        <option value="<?php echo "MISC"; ?>" selected="selected"><?php echo "MISC"; ?></option>
                                                        <?php } ?>
                                                    </select>
                                               </td>

                                               <td>
                                                    <select style="height: 33px; font-size: 12px !important; font-weight: 600" name="category[<?php echo $i; ?>][dept_code]" id="category[<?php echo $i; ?>][dept_code]" class="form-control" onchange='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");'>
                                                        <option value="0">--Select Department--</option>
                                                        <?php if(isset($data['department']) && count($data['department']) > 0){?>
                                                            <?php foreach($data['department'] as $department){ ?>
                                                                <?php if(isset($category->dept_code) && $category->dept_code == $department['vdepcode']){?>
                                                                    <option value="<?php echo $department['vdepcode'];?>" selected="selected"><?php echo $department['vdepartmentname'];?></option>
                                                                <?php } else { ?>
                                                                    <option value="<?php echo $department['vdepcode'];?>"><?php echo $department['vdepartmentname'];?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </td>

                                                <td class="text-center" style="border-bottom-right-radius: 9px; border-top-right-radius: 9px ">
                                                    <input type="hidden" id="sub_cat" value="<?php echo $category->icategoryid; ?>|<?php echo $category->subcat_count; ?>" >
                                                    <a onclick="viewSubCategory('<?=$category->icategoryid?>', '<?=$category->vcategoryname?>');" data-toggle="tooltip" title="View Sub Categories" class="btn small-btn"><?php echo $category->subcat_count; ?></a>
                                                </td>

                                                <td class="text-left" style="display:none;"><input type="text" style="border:none;"  class="editable categories_s" name="category[<?php echo $i; ?>][isequence]" id="category[<?php echo $i; ?>][isequence]" value="<?php echo $category->isequence; ?>" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");' /></td>
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
                            <div class="pull-right" style="margin-right: 29px !important">
                              {{$data['categories']->links()}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Modal Add -->
<div class="modal fade bd-example-modal-lg"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add New Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <div class="modal-body">
        <form action="{{route('category.add_list')}}" method="post" id="add_new_form">
          @csrf
          <!--@method('post')-->
          @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
            @endif
            <input type="hidden" name="category[0][icategoryid]" value="0">
            <input type="hidden" name="category[0][isequence]" value="0">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">
                    <input type="text" maxlength="50" class="form-control" id="add_vcategoryname" name="category[0][vcategoryname]" required>
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
                    <textarea maxlength="100" name="category[0][vdescription]" class="form-control" required></textarea>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Type</label>
                  </div>
                  <div class="col-md-10">
                    <select name="category[0][vcategorttype]" id="" class="form-control " required>
                      <option value="<?php echo "Sales"; ?>" selected="selected"><?php echo "Sales"; ?></option>
                      <option value="<?php echo "MISC"; ?>" ><?php echo "MISC"; ?></option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Department</label>
                  </div>
                  <div class="col-md-10">
                    <select name="category[0][dept_code]" id="add_dept_code" class="form-control" required>
                      <option value="" selected="selected">--Select Department--</option>

                        <?php if(isset($data['department']) && count($data['department']) > 0){?>
                            <?php foreach($data['department'] as $department){ ?>
                                <option value="<?php echo $department['vdepcode'];?>"><?php echo $department['vdepartmentname'];?></option>
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
                <input type="button" class="btn btn-success" id="save_category" value="Save">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>

<!-- Modal Add-->

  <div class="modal fade" id="viewSubCatModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="font-size:17px;">List of Sub Categories under <span id="cat_name"></span></h4>
        </div>
        <div class="modal-body">
            <!--<h4><label id="cat_name"></label></h4>-->
          <div class="alert" id="subcat">
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
            <h4 class="modal-title">Select the stores in which you want to add the Category :</h4>
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
            <h4 class="modal-title">Select the stores in which you want to Edit the Category :</h4>
            <span style="color: #03A9F4">(Please Note: If a Category not exists in any of the stores those category will be created)</span>
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
                <tbody id="edit_cat_stores"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="Edit_btn_category" class="btn btn-danger" data-dismiss="modal">Update</button>
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
            <h4 class="modal-title">Select the stores in which you want to delete the Category :</h4>
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

@endsection

@section('page-script')



</style>

<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){

    $('#add_vcategoryname, .categoriesname_c').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9. _]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    });

})

$('#button-filter').on('click', function() {

	var url = '{{$data['searchcategory']}}';

	var filter_menuid = $('select[name=\'MenuId\']').val();

	if (filter_menuid) {
		url += '&filter_menuid=' + encodeURIComponent(filter_menuid);
	}

	location = url;
});
function filterpage(){
	alert('filter');
	url="Route('category.search')";

	var filter_menuid = $('select[name=\'MenuId\']').val();

	if (filter_menuid) {
		url += '&filter_menuid=' + encodeURIComponent(filter_menuid);
	}

	location = url;
}

var category_row = <?php echo $category_row; ?>;

function addCategory() {
	 $('#addModal').modal('show');
}

$("#save_category").click(function(){
    $("#addModal").modal('hide');
    var cat_name = $("#add_vcategoryname").val();
    var dep_code = $("#add_dept_code").val();

    if(cat_name == ''){
        alert("cat_name is required");
    }else if(dep_code == ''){
        alert("dep_code is required");
    }else {
        <?php if(session()->get('hq_sid') == 1){ ?>
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

$(document).on('click', '#save_button', function(event) {
    event.preventDefault();
    var edit_url = '{{route('category.edit_list')}}';
    edit_url = edit_url.replace(/&amp;/g, '&');
    var all_category = true;
    var catids = [];
    // categories
    $.each($("input[name='categories']:checked"), function(){
        catids.push($(this).val());
        if($(this).val() == ''){
            bootbox.alert({
              size: 'small',
              title: "Attention",
              message: "Please Enter Category Name",
              callback: function(){}
            });
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
            // alert('Please Enter Only Number');
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

    var avArr = [];
    $("#category input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var code = $(this).closest('tr').find('.categories_c').val();
      var name = $(this).closest('tr').find('.categoriesname_c').val();
      avArr.push({
        icategoryid:id,
        vcategorycode:id,
        vcategoryname: name
      });
    });


    if(avArr.length > 0){
        <?php if(session()->get('hq_sid') == 1){ ?>
            $.ajax({
              url: "<?php echo url('/category/duplicatecategory'); ?>",
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
    }else{
        bootbox.alert({
            size: 'small',
            title: "Attention",
            message: 'Please Select Category to Edit!',
            callback: function(){}
        });
        return false;
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
    $('#Edit_btn_category').click(function(){
        $.each($("input[name='editstores']:checked"), function(){
            edit_stores.push($(this).val());
        });
        $("#edit_hidden_store_hq_val").val(edit_stores.join(","));

        var edit_url = '{{route('category.edit_list')}}';
        edit_url = edit_url.replace(/&amp;/g, '&');

        $('#form-category').attr('action', edit_url);
        $('#form-category').submit();
        $("div#divLoading").addClass('show');
    });


    $(function() {
        var url = '{{route('category.search')}}';
        $( "#automplete-product" ).autocomplete({
            minLength: 2,
            source: function(req, add) {
                $.getJSON(url, req, function(data) {
                    var suggestions = [];
                    $.each(data, function(i, val) {
                        suggestions.push({
                            label: val.vcategoryname,
                            value: val.vcategoryname,
                            id: val.icategoryid
                        });
                    });
                    add(suggestions);
                });
            },
            select: function(e, ui) {
                $('form#form_category_search #icategoryid').val(ui.item.id);
                if($('#icategoryid').val() != ''){
                    $('#form_category_search').submit();
                    $("div#divLoading").addClass('show');
                }
            }
        });
    });

    $(function() { $('input[name="automplete-product"]').focus(); });


    $(document).ready(function($) {
        $("div#divLoading").addClass('show');
    });

    // $(window).load(function() {
    //     $("div#divLoading").removeClass('show');
    // });


// ===================== Delete Code =================================

    $(document).on('click', '#delete_category_btn', function(event) {
        event.preventDefault();
        if($("input[name='selected[]']:checked").length == 0){
            bootbox.alert({
                size: 'small',
                title: "Attention",
                message: 'Please Select Category to Delete!',
                callback: function(){}
            });
            return false;
        }

        var avArr = [];
        $("#category input[name='selected[]']:checked").each(function () {
            var id =$(this).val();
            var code = $(this).closest('tr').find('.categories_c').val();
            var name = $(this).closest('tr').find('.categoriesname_c').val();
            avArr.push({
                icategoryid:id,
                vcategorycode:id,
                vcategoryname: name
            });
        });

    <?php if(session()->get('hq_sid') == 1){ ?>
        $.ajax({
              url: "<?php echo url('/category/duplicatecategory'); ?>",
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
                                                '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Category does not exist)</span></td>'+
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
                                    $('#deleteSelectAllCheckbox').attr('disabled', false);
                            }
                            popup = popup + data;
                        @endforeach
                $('#deletecat_stores').html(popup);
            }
        });
        $("#DeleteModal").modal('show');
    <?php } else { ?>

     var delete_category_url = "{{route('category.delete')}}";
     delete_category_url = delete_category_url.replace(/&amp;/g, '&');
     var data = {};

        $("div#divLoading").addClass('show');

        $.ajax({
            url : delete_category_url,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify(avArr),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                // if(data['success_msg']){
                //   setTimeout(function(){
                //     var successMsg ='';
                //     // $('#success_msg').html('<strong>'+ data['success_msg'] +'</strong>');
                //     $("div#divLoading").removeClass('show');
                //     $('#successModal').modal('show');
                //     bootbox.alert({
                //         size: 'small',
                //         title: "Attention",
                //         message:  data['success_msg'],
                //         callback: function(){window.location.reload()}
                //     });
                //    $('#successModal').modal('hide');
                //    window.location.reload();
                //   }, 4000);
                // }else{
                //   var errorMsg = '';

                //   $.each(data.error_msg, function (k, v){
                //       errorMsg += v+'<br/>';
                //   });

                //   $('#error_msg').html('<strong>'+ errorMsg +'</strong>');
                //   $("div#divLoading").removeClass('show');
                //   $('#errorModal').modal('show');

                //   setTimeout(function(){
                //       $('#errorModal').modal('hide');
                //       window.location.reload();
                //       }, 4000);
                // }


                if(data['success_msg']){
                    $('#success_msg').html('<strong>Deleted Successfully</strong>');
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
    $.each($("input[name='deletestores']:checked"), function(){
        deletestores.push($(this).val());
    });
    $("#category input[name='selected[]']:checked").each(function () {
      var id =$(this).val();
      var code = $(this).closest('tr').find('.categories_c').val();
      var name = $(this).closest('tr').find('.categoriesname_c').val();
      avArr.push({
        icategoryid:id,
        vcategorycode:code,
        vcategoryname: name,
        stores_hq: deletestores
      });
    });
    console.log(avArr);
     var delete_category_url = "{{route('category.delete')}}";
     delete_category_url = delete_category_url.replace(/&amp;/g, '&');
     var data = {};

        $("div#divLoading").addClass('show');

        $.ajax({
            url : delete_category_url,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify(avArr),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                if(data['success_msg']){
                    var successMsg ='';
                    // $('#success_msg').html('<strong>'+ data['success_msg'] +'</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#successModal').modal('show');
                    bootbox.alert({
                        size: 'small',
                        title: "Attention",
                        message:  data['success_msg'],
                        callback: function(){window.location.reload()}
                    });
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

  function viewSubCategory(id, name)
     {
         var category_name = name;
         var category_id = id;


        var url = '<?php echo $data["view_sub_cat"]; ?>';
                // url += "?category_id="+category_id;
                url = url.replace(/&amp;/g, '&');

         $("#cat_name").html(category_name);

        $.ajax({
                url : url,
                data : {category_id:category_id},
                type : 'get',
                contentType: "application/json",
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    // $.each(data, function (i, obj) {
                    //     console.log(obj);

                    // });

                    $("#subcat").html(data);
                    $('#viewSubCatModal').modal('show');

                },
                error: function(xhr, status, error) {

                  console.log(xhr);
                  console.log(error);
                }
        });
     }

</script>
<style>
    .disabled {
    pointer-events:none; //This makes it not clickable

    }

</style>
@endsection
