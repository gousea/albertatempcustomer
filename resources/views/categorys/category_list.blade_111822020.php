@extends('layouts.master')
@section('title')
  Category
@endsection
@section('main-content')

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      
      <!-- <h1><?php //echo $heading_title; ?></h1> -->
      <ul class="breadcrumb">
        <?php //foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php //echo $breadcrumb['href']; ?>"><?php //echo 'testing'$breadcrumb['text']; ?></a></li>
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

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
              @endforeach
            </ul>
          </div>
        @endif
      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i>Category</h3>
      </div>
      <div class="panel-body">
        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
            <div class="">
              <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
            <button type="button" onclick="addCategory();" data-toggle="tooltip" title="<?php //echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</button>        
            <button type="button" class="btn btn-danger" id="delete_category_btn" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>        
            </div>
          </div>
        </div>
        <div class="clearfix"></div>

      <form action="{{route('category')}}" method="get" id="form_category_search">
          @csrf
          @method('get')
        <input type="hidden" name="searchbox" id="icategoryid">
        <div class="row">
            <div class="col-md-12">
                <input type="text" name="automplete-product" class="form-control" placeholder="Search Category..." id="automplete-product">
            </div>
        </div>
      </form>
       <br>

    <form action="" method="post" enctype="multipart/form-data" id="form-category">
          @csrf
          @method('post')
        <input type="hidden" name="MenuId" value="<?php //echo $filter_menuid; ?>"/>
          <div class="table-responsive">
            <table id="category" class="table table-bordered table-hover" style="width:60%; margin-top: 55px;">
            <?php if ($data['categories']) { ?>
              <thead>
                <tr>
                  <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                  <th style="width:1px;" class="text-left">Category Code</th>
                  
                  <th style="width:1px;" class="text-left">Category Name</th>
                  <th class="text-left">Description</th>
                  <th class="text-left">Category Type</th>
                  <th class="text-left">Department</th>
                  <th class="text-left">No. Of Sub Categories</th>
                  
                  <th class="text-left" style="display:none;"><?php //echo $column_sequence; ?></th>
                </tr>
              </thead>
              <tbody>
                  <?php 
                    // echo "<pre>";
                    // print_r($data['categories']);
                    // die;
                  ?>
                
                <?php $category_row = 1;$i=0; ?>
                <?php foreach ($data['categories'] as $category) { ?>
                <tr id="category-row<?php echo $category_row; ?>">
                  <td data-order="<?php echo $category->icategoryid; ?>" class="text-center">
                  <span style="display:none;"><?php echo $category->icategoryid; ?></span>
                  <?php if (in_array($category->icategoryid, $data['selected'])) { ?>
                    <input type="checkbox" name="selected[]" id="category[<?php echo $category_row; ?>][select]" value="<?php echo $category->icategoryid; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" id="category[<?php echo $category_row; ?>][select]"  value="<?php echo $category->icategoryid; ?>" />
                    <?php } ?>
                  </td>
                  
                  <td class="text-left">
                    <span style="display:none;"><?php echo $category->vcategorycode; ?></span>
                    <input type="hidden" maxlength="50" class="editable categories_c" name="category[<?php echo $i; ?>][vcategorycode]" id="category[<?php echo $i; ?>][vcategorycode]" value="<?php echo $category->vcategorycode; ?>" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");' />
        				   <?php echo $category->vcategorycode; ?>
        				    <input type="hidden" name="category[<?php echo $i; ?>][icategoryid]" value="<?php echo $category->icategoryid; ?>"/>
        				  </td>
                  
                  <td class="text-left">
                    <span style="display:none;"><?php echo $category->vcategoryname; ?></span>
                    <input type="text" maxlength="50" class="editable categories_c" name="category[<?php echo $i; ?>][vcategoryname]" id="category[<?php echo $i; ?>][vcategoryname]" value="<?php echo $category->vcategoryname; ?>" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");' />
        				    <input type="hidden" name="category[<?php echo $i; ?>][icategoryid]" value="<?php echo $category->icategoryid; ?>"/>
        				  </td>
                  <td class="text-left"><textarea maxlength="100" class="editable" name="category[<?php echo $i; ?>][vdescription]" id="category[<?php echo $i; ?>][vdescription]" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");'><?php echo $category->vdescription; ?></textarea></td>
                  
                  <td class="text-left">
                        <select name="category[<?php echo $i; ?>][vcategorttype]" id="category[<?php echo $i; ?>][vcategorttype]" class="form-control" onchange='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");'>
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
                    
                        <select name="category[<?php echo $i; ?>][dept_code]" id="category[<?php echo $i; ?>][dept_code]" class="form-control" onchange='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");'>
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
                    
                    <td class="text-center">
                      <input type="hidden" id="sub_cat" value="<?php echo $category->icategoryid; ?>|<?php echo $category->subcat_count; ?>" >
                      <a onclick="viewSubCategory('<?=$category->icategoryid?>', '<?=$category->vcategoryname?>');" data-toggle="tooltip" title="View Sub Categories" class="btn small-btn"><?php echo $category->subcat_count; ?></a>
                    </td>
                
                  <td class="text-left" style="display:none;"><input type="text" class="editable categories_s" name="category[<?php echo $i; ?>][isequence]" id="category[<?php echo $i; ?>][isequence]" value="<?php echo $category->isequence; ?>" onclick='document.getElementById("category[<?php echo $category_row; ?>][select]").setAttribute("checked","checked");' /></td>
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
            {{$data['categories']->links()}}
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
          <h4 class="modal-title">Add New Category</h4>
        </div>
        <div class="modal-body">
        <form action="{{route('category.store')}}" method="post" id="add_new_form">
          @csrf
          @method('post')
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
                <input class="btn btn-success" type="submit" value="Save">
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
  
{{-- <div class="modal fade" id="successModal" role="dialog">
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
  </div> --}}
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

@section('scripts')

<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>

<script type="text/javascript">
$('#button-filter').on('click', function() {
	
	var url = '{{$data['searchcategory']}}';
	alert(url);
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

$(document).on('submit', 'form#add_new_form', function(event) {
    
    if($('form#add_new_form #add_vcategoryname').val() == ''){
      // alert('Please enter name!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please enter name!", 
        callback: function(){}
      });
      return false;
    }
    
    if($('form#add_new_form #add_dept_code').val() == ''){
      // alert('Please enter name!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please select a Department!", 
        callback: function(){}
      });
      return false;
    }
    $("div#divLoading").addClass('show');
    
  });

  $(document).on('click', '#save_button', function(event) {
    event.preventDefault();
    var edit_url = "{{route('category.edit_list')}}";
    
    edit_url = edit_url.replace(/&amp;/g, '&');
    
    var all_category = true;
    
    $('.categories_c').each(function(){
      if($(this).val() == ''){
        // alert('Please Enter Category Name');
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
    

    if(all_done == true){
      $('#form-category').attr('action', edit_url);
      $('#form-category').submit();
      $("div#divLoading").addClass('show');
    }
  });

  $(function() {
        
        var url = '{{route('category.search')}}';
        
        // url = url.replace(/&amp;/g, '&');
        
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
              // console.log(e);
              // console.log(ui);
              // // alert('test')
              $('form#form_category_search #icategoryid').val(ui.item.id);
              //   console.log($('form#form_category_search #icategoryid').val(ui.item.id));
                
                // if($('#icategoryid').val() != ''){
                //     $('#form_category_search').submit();
                //     $("div#divLoading").addClass('show');
                // }
                // category?id=1
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

    $(window).load(function() {
      $("div#divLoading").removeClass('show');
    });


    $(document).on('click', '#delete_category_btn', function(event) {
    event.preventDefault();
    var delete_category_url = "{{route('category.delete')}}";
    delete_category_url = delete_category_url.replace(/&amp;/g, '&');
    var data = {};

    if($("input[name='selected[]']:checked").length == 0){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: 'Please Select Category to Delete!', 
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
        url : delete_category_url,
        data : data,
        type : 'get',
        contentType: "application/json",
        dataType: 'json',
      success: function(data) {
        var success_mess = data.success;
        // console.log("------------------");
        // console.log(success);
        // alert('Testing');
        if(success_mess){
          // $('#success_msg').html('<strong>'+ data.success +'</strong>');
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: 'Category Deleted!', 
            callback: function(){}
          });
          // return false;
          // $("div#divLoading").removeClass('show');
          // $('#successModal').modal('show');

          setTimeout(function(){
          //  $('#successModal').modal('hide');
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

  function viewSubCategory(id, name)
     {
         
         
         var category_name = name;
         var category_id = id;
        
        
        var url = '<?php //echo $view_sub_cat; ?>';
                url += "&category_id="+category_id;
                url = url.replace(/&amp;/g, '&');
         
         $("#cat_name").html(category_name);
         
        $.ajax({
                url : url,
                data : {category_id:category_id},
                type : 'POST',
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