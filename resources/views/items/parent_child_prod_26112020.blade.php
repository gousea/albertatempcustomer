@extends('layouts.master')

@section('title')
Parent Child
@endsection

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
        <br>
        @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>      
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        @if(!empty($error))
                            <li>{{$error}}</li>
                        @endif
                    @endforeach
                </ul>
                
            </div>
            
        @endif

        @if (isset($data['error_warning']))
            <div class="alert alert-warning">
                <ul>
                    <li>{{$data['error_warning']}}</li>
                </ul>
                
            </div>
            
        @endif

        <div class="panel panel-default">
            <div class="panel-heading head_title">
              <h3 class="panel-title"><i class="fa fa-list"></i>Parent Child</h3>
            </div>
        </div>
        
        <div class="panel-body">
            <div>
                <table class="table table-bordered table-hover" id="tab_logic">
                    <thead>
                        <tr >
                                
                            <th class="text-center"  style="width:390px;;">
                                Parent Item
                            </th>
                            
                            <th class="text-center"  style="width:460px;">
                                Child Item
                            </th>
                            
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <table  style="width:100%;">
            <tbody>
                <tr>
                    <form action="{{ $dataUrl['add'] }}" method="post" id="filter_form">
                        @csrf
                        <td class="text-left" style="width:400px;height:70px;background-color: #fff;">
                            <input type="text" name="report_by_p" class="form-control" placeholder="Search parent item..." id="automplete-product" value="<?php echo isset($data['report_by_p']) ? $data['report_by_p'] : ''; ?>" required style="width:340px;height:35px;padding-left:10px;">
                            
                            <input type="hidden" name="Psearch_iitemid" id="search_iitemid" value="<?php echo isset($data['parent_item_id']) ? $data['parent_item_id'] : ''; ?>">
                            <input type="hidden" name="Psearch_vbarcode" id="search_vbarcode" value="<?php echo isset($Psearch_vbarcode) ? $Psearch_vbarcode : ''; ?>">
                            
                        </td>  
                        
                        <td style="width:90px;height:70px;background-color: #fff;">
                            <input type="number" name="cpack" id="c_pack" class="form-control" value="<?php echo isset($data['cnpack']) ? $data['cnpack'] : ''; ?>" placeholder="Child pack" style="width:80px;height:35px;padding-left:4px;" required >
                        </td>
                        
                        <td style="width:330px;height:70px;background-color: #fff;">
                            <input type="text" name="report_by_c" class="form-control" placeholder="Search child item..." id="automplete-product_child" value="<?php echo isset($data['report_by_c']) ? $data['report_by_c']: ''; ?>" required  style="width :320px;height:35px;padding-left:10px;">
                            <input type="hidden" name="Csearch_iitemid" id="csearch_iitemid" value="<?php echo isset($data['child_item_id']) ? $data['child_item_id'] : ''; ?>">
                            <input type="hidden" name="Csearch_vbarcode" id="csearch_vbarcode" value="<?php echo isset($Csearch_vbarcode) ? $Csearch_vbarcode : ''; ?>">
                        </td>
                        <td class="text-left" style="background-color: #fff;"  style="width:37px;height:70px;background-color: #fff;" >
                            <input type="submit" class="btn btn-success" value="Add New" id="addmore">
                        </td>
                    </form>
                    
                    <td style="background-color: #fff;">
                        <button type="button" class="btn btn-danger" id="parent_delete" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i> Delete</button>
                    </td>
                       
                    <td style="background-color: #fff;">
                        <button type="button" class="btn btn-primary" id="save_button" title="Save" style="border-radius: 0px;"><i class="fa fa-save"></i> Save</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="table-responsive">
            <table id="Parent_head" class="text-center table table-bordered table-hover" style="margin-bottom:0px;">
                <thead>
                    <tr bgcolor="#1e91cf">
                        
                        <td style="width: 1px;color:black;"class="text-left"><input type="checkbox"  onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                        
                        <td style="width:200px;bg-color:#f9f9f9" class="text-left"><?php echo "Parent Name"; ?></td>
                        <td style="width:150px;bg-color:#f9f9f9" class="text-left"><?php echo "SKU"; ?></td>
                        <td style="width:100px" class="text-left"><?php echo "Child Pack"; ?></td>
                        <td style="width:200px"  class="text-left"><?php echo "Child Name"; ?></td>
                        <td style="width:150px"  class="text-left"><?php echo "SKU"; ?></td>
                    
                    </tr>

                    <tr bgcolor="#1e91cf">

                        <td style="width: 1px;"class="text-left">  
                            <input type="hidden"   id="automplete-product_check">
                        </td>

                        <td style="width:200px;"class="text-left">
                        <input style="color:#000000;" type="text" name="parent_item"  placeholder="Search parent item." id="parent-products">
                        </td>

                        <td style="width:150px;"class="text-left">
                        <input type="hidden" style="color:#000000;;" type="text" name=""  >
                        </td>

                        <td tyle="width:100px;"class="text-left">  
                            <input type="hidden"   id="automplete-product_check_qoh">
                        </td>

                        <td style="width:200px;"class="text-left">
                            <input style="color:#000000; " type="text" name="child_item"  placeholder="Search child item." id="child-product">
                        </td>
                        
                        <td style="width:150px;"class="text-left">
                            <input type="hidden" style="color:#000000;" type="text" name=""  >
                        </td>
                                        
                    </tr>
                </thead>
            </table>
        </div>

        <form action="" method="post" enctype="multipart/form-data" id="form-cpack">
            @csrf
            <div class="table-responsive">
                <table id="Parent" class="text-center table table-bordered table-hover">
                    <thead style='display:none;'> <th></th><th></th> <th></th> <th></th> <th></th><th></th>
                    </thead>
                        
                    @foreach ($pitemncitem as $k=>$pitem)
                    
                        <tr>
                            <td style="width:1px;"class="text-left">
                                <input type="checkbox" class="itemid" name="selected[]" id="pitem[<?php echo $k; ?>][select]"  value="<?php echo $pitem['pid'] ."-". $pitem['cid']; ?>" />
                            </td>
                                                
                            <td style="width:200px;"class="text-left">
                                <a href="<?php echo $pitem['item_link'];?>"><?php echo $pitem['pname']; ?></a>
                            </td>

                            <td style="width:150px;"class="text-left">
                                <span ><?php echo $pitem['psku']; ?></span>
                            </td>
                        
                            <td style="width:100px;" class="text-left">
                                <input style="width:70px;"type="text"  class="editable cpack" name="pitem[<?php echo $k; ?>][cpack]" id="cpack_<?php echo $pitem['pid'] ."-". $pitem['cid']; ?>" value="<?php echo $pitem['cpack']; ?>" onclick='document.getElementById("pitem[<?php echo $k; ?>][select]").setAttribute("checked","checked");' />
                                <input type="hidden" name="pitem[<?php echo $k; ?>][cid]"  id="cid_<?php echo $pitem['pid'] ."-". $pitem['cid']; ?>" value="<?php echo $pitem['cid']; ?>"/>
                                <input type="hidden" name="pitem2[<?php echo $k; ?>][pid]"  id="pid_<?php echo $pitem['pid'] ."-". $pitem['cid']; ?>" value="<?php echo $pitem['pid']; ?>"/>
                            </td>

                            <td style="width:200px;"class="text-left">
                                <a href="<?php echo $pitem['citem_link'];?>"><?php echo $pitem['cname']; ?></a>
                            </td>
                        
                            <td style="width:150px;"class="text-left">
                                <span ><?php echo $pitem['csku']; ?></span>
                            </td>
    
                        </tr>

                    @endforeach
               </table>
            </div>
        </form>

    </div>
</div>
    
@endsection

@section('scripts')

<style>
    #Parent_filter{
        display:none;
    }
</style>

<script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<br><br>

<script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>

<script>
    $(function() {
        
        var url = "<?php echo $dataUrl['searchitem'];?>";
        //console.log(url);
        url = url.replace(/&amp;/g, '&');
        
        $( "#automplete-product" ).autocomplete({
            minLength: 2,
            source: function(req, add) {
                $.getJSON(url, req, function(data) {
                    window.suggestions = [];
                    $.each(data, function(i, val) {
                        suggestions.push({
                            label: val.vitemname,
                            value: val.vitemname,
                            vbarcode: val.vbarcode,
                            id: val.iitemid
                        });
                    });
                    add(window.suggestions);
                });
            },
            select: function(e, ui) {
              $('#search_iitemid').val(ui.item.id);
              $('#search_vbarcode').val(ui.item.vbarcode);
            }
        });
    });
</script>

<script>
    $(function() {
        
        var url = '<?php echo $dataUrl['searchitem_child'];?>';
        //console.log(url);
        url = url.replace(/&amp;/g, '&');
        
        $( "#automplete-product_child" ).autocomplete({
            minLength: 2,
            source: function(req, add) {
                $.getJSON(url, req, function(data) {
                    window.suggestions = [];
                    $.each(data, function(i, val) {
                        suggestions.push({
                            label: val.vitemname,
                            value: val.vitemname,
                            vbarcode: val.vbarcode,
                            id: val.iitemid
                        });
                    });
                    add(window.suggestions);
                });
            },
            select: function(e, ui) {
              $('#csearch_iitemid').val(ui.item.id);
              $('#csearch_vbarcode').val(ui.item.vbarcode);
            }
        });
    });
</script>


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
  
<script>
    $(function() {
        
        var url = "<?php echo $dataUrl['searchitem'];?>";
        //console.log(url);
        url = url.replace(/&amp;/g, '&');
        
        $( "#parent-product" ).autocomplete({
            minLength: 2,
            source: function(req, add) {
                $.getJSON(url, req, function(data) {
                    window.suggestions = [];
                    $.each(data, function(i, val) {
                        suggestions.push({
                            label: val.vitemname,
                            value: val.vitemname,
                            vbarcode: val.vbarcode,
                            id: val.iitemid
                        });
                    });
                    add(window.suggestions);
                });
            },
            select: function(e, ui) {
              $('#csearch_iitemid').val(ui.item.id);
              $('#csearch_vbarcode').val(ui.item.vbarcode);
              $('form#form_parent_search #Id').val(ui.item.id);
               if($('#Id').val() != ''){
                    $('#form_parent_search').submit();
                    //$("div#divLoading").addClass('show');
                }
            }
            
        });
    });
</script>

<!-- DataTables -->
<script src="{{ asset('javascript/jquery.dataTables.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('javascript/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('javascript/select2/js/select2.min.js') }}" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        
      var table = $('#Parent').DataTable({
        
        "searching":true,
        "destroy": true,
        "bLengthChange": false,
        "pageLength":20
        
      });

    
        $('#parent-products').on('input', function () {
            table.columns(1).search( this.value ).draw();
        } );
        $('#child-product').on('input', function () {
            table.columns(4).search( this.value ).draw();
        } );

  
});
</script>


<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance:textfield;
}
.dataTables_paginate {
  width: 100%;
  text-align: right;
}
</style>           
<script>
    $(document).ready(function(){
        var selected_item = [];
        $(document).on("change, click", "input[name='selected[]']", function(){
        
            $("input[name='selected[]']:checked").each(function (i)
            {
                var index = selected_item.indexOf($(this).val());
                    if (index == -1) {
                        selected_item.push($(this).val());
                    }
                    
                
            });
            
            $("input[name='selected[]']:not(:checked)").each(function (i)
            {
                var index = selected_item.indexOf($(this).val());
                if (index !== -1) {selected_item.splice(index, 1); }
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
            var result = confirm("Do you want to delete parent child relationship ?");
            if (result) {
            
                var delete_url = "<?php echo $dataUrl['delete']; ?>";
                delete_url = delete_url.replace(/&amp;/g, '&');
                // var data = {};
            
            

                $("div#divLoading").addClass('show');
                
                console.log(selected_item);
                
                $.ajax({
                    url : delete_url,
                    headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    data : JSON.stringify(selected_item),
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
                        
                        var refresh_url = '<?php echo $dataUrl['refresh_url']; ?>';
                        refresh_url = refresh_url.replace(/&amp;/g, '&');
                        window.location = refresh_url;
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
            
            }
        });


    });
</script>



<script type="text/javascript">
  $(document).on('click', '#save_button', function(event) {
    event.preventDefault();
    console.log("test save");
    var data=[];
    var edit_url = "<?php echo $dataUrl['save']; ?>";
    
    edit_url = edit_url.replace(/&amp;/g, '&');
    
    $("input[name='selected[]']:checked").each(function (i)
        {
            
            // selected_item.push($(this).val());
            var itemid = $(this).val();
            var cpack_values = $('#cpack_'+itemid).val();
            var cpack_cid=$('#cid_'+itemid).val();
            var cpack_pid=$('#pid_'+itemid).val();
            console.log(cpack_pid);
            data.push(cpack_values+'-'+cpack_cid+'-'+cpack_pid);
        });
    
        $("div#divLoading").addClass('show');
        
        console.log(data);
        
        $.ajax({
            url : edit_url,
            headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
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
               
               var refresh_url = '<?php echo $dataUrl['refresh_url']; ?>';
               refresh_url = refresh_url.replace(/&amp;/g, '&');
               window.location = refresh_url;
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
</script>
<style>
    .disabled {
    pointer-events:none; //This makes it not clickable
 
    }

</style>
@endsection