@extends('layouts.layout')

@section('title')
Parent Child
@endsection

@section('main-content')

<div id="content">
    
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Parent Child</span>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
      
    <section class="section-content py-6">
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
            
            <div class="panel-body">
                <div>
                    <table class="table table-bordered table-hover" id="tab_logic">
                        <thead>
                            <tr >
                                    
                                <th class="text-center text-uppercase text-white"  style="width:390px; background-color:grey;">
                                    Parent Item
                                </th>
                                
                                <th class="text-center text-uppercase text-white"  style="width:460px; background-color:grey;">
                                    Child Item
                                </th>
                                
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <table  style="width:100%;">
                <tbody>
                    <tr class="clearfix">
                        <form action="{{ $dataUrl['add'] }}" method="post" id="filter_form">
                            @csrf
                            @if(session()->get('hq_sid') == 1)
                                <input type="hidden" name="stores_hq" id="add_store_hq" value="">
                            @endif
                            <td class="text-left" style="width:455px;height:70px;background-color: #fff;">
                                <input type="text" name="report_by_p" class="form-control" placeholder="Search parent item..." id="automplete-product" value="<?php echo isset($data['report_by_p']) ? $data['report_by_p'] : ''; ?>" required style="width:360px;height:35px;padding-left:10px;">
                                
                                <input type="hidden" name="Psearch_iitemid" id="search_iitemid" value="<?php echo isset($data['parent_item_id']) ? $data['parent_item_id'] : ''; ?>">
                                <input type="hidden" name="Psearch_vbarcode" id="search_vbarcode" value="<?php echo isset($Psearch_vbarcode) ? $Psearch_vbarcode : ''; ?>">
                                
                            </td>  
                            
                            <td style="width:120px;height:70px;background-color: #fff;">
                                <input type="number" name="cpack" id="c_pack" class="form-control" value="<?php echo isset($data['cnpack']) ? $data['cnpack'] : ''; ?>" placeholder="Child pack" style="width:110px;height:35px;padding-left:4px;" required >
                            </td>
                            
                            <td style="width:330px;height:70px;background-color: #fff;">
                                <input type="text" name="report_by_c" class="form-control" placeholder="Search child item..." id="automplete-product_child" value="<?php echo isset($data['report_by_c']) ? $data['report_by_c']: ''; ?>" required  style="width :320px;height:35px;padding-left:10px;">
                                <input type="hidden" name="Csearch_iitemid" id="csearch_iitemid" value="<?php echo isset($data['child_item_id']) ? $data['child_item_id'] : ''; ?>">
                                <input type="hidden" name="Csearch_vbarcode" id="csearch_vbarcode" value="<?php echo isset($Csearch_vbarcode) ? $Csearch_vbarcode : ''; ?>">
                            </td>
                            <td class="text-left" style="background-color: #fff;"  style="width:37px;height:70px;background-color: #fff;" >
                                <input type="button" class="btn btn-success float-right" value="Add New" id="addmore" >
                            </td>
                        </form>
                        
                        <td style="background-color: #fff;">
                            <button type="button" class="btn btn-danger float-right" id="parent_delete" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                        
                        <td style="background-color: #fff;">
                            <button type="button" class="btn btn-primary float-right" id="save_button" title="Save" style="border-radius: 0px; margin-right:5px;"><i class="fa fa-save"></i> Save</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="table-responsive">
                <table id="Parent_head" class="text-center table table-bordered table-hover" style="margin-bottom:0px;">
                    <thead>
                        <tr class="header-color">
                            
                            <td style="width: 1px;color:black;"class="text-left"><input type="checkbox"  onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                            
                            <td style="width:200px;" class="text-left headername text-uppercase font-weight-bold"><?php echo "Parent Name"; ?></td>
                            <td style="width:150px;" class="text-left headername text-uppercase font-weight-bold"><?php echo "SKU"; ?></td>
                            <td style="width:100px" class="text-left headername text-uppercase font-weight-bold"><?php echo "Child Pack"; ?></td>
                            <td style="width:200px"  class="text-left headername text-uppercase font-weight-bold"><?php echo "Child Name"; ?></td>
                            <td style="width:150px"  class="text-left headername text-uppercase font-weight-bold"><?php echo "SKU"; ?></td>
                        
                        </tr>

                        <tr class="header-color">

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
                    <table id="Parent" class="text-center table table-bordered table-hover display" style="width:100%">
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
    </section>
</div>

<?php if(session()->get('hq_sid') == 1){ ?>
        <div id="addModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want to Add Parent Child relation</h4>
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
                    </thead >
                    <tbody id="data_stores"></tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button id="adding_btn" class="btn btn-danger" data-dismiss="modal">Add New</button>
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
              </div>
            </div>
          </div>
        </div>
        
        
        
        <div id="editModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want to update the items:</h4>
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
                    </thead >
                    <tbody id="edit_data_stores"></tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button id="edit_btn" class="btn btn-danger" data-dismiss="modal">Update</button>
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
              </div>
            </div>
          </div>
        </div>
        
        
        <div id="deleteModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want to Delete parent child relation:</h4>
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
                    </thead >
                    <tbody id="delete_data_stores"></tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button id="delete_btn" class="btn btn-danger" data-dismiss="modal">Delete</button>
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
              </div>
            </div>
          </div>
        </div>
<?php } ?>
    
@endsection

@section('page-script')

<style>
    #Parent_filter{
        display:none;
    }

    
</style>

{{-- <script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>--}}

<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<br><br> 

<script type="text/javascript">
  $(window).on(function() {
    $("div#divLoading").removeClass('show');
  });
</script>

<script>
$("#addmore").click(function(){
    
    <?php if(session()->get('hq_sid') == 1){  ?>
        var search_iitemid = $("#search_iitemid").val();
        var search_vbarcode = $("#search_vbarcode").val();
        var csearch_iitemid = $("#csearch_iitemid").val();
        var csearch_vbarcode = $("#csearch_vbarcode").val();
        $.ajax({
            url : "<?php echo url('/item/parent_child/checkduplicateparentchild'); ?>",
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : JSON.stringify({parentItemid:search_iitemid, parentItembarcode: search_vbarcode, childitemid:csearch_iitemid, childitembarcode: csearch_vbarcode}),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(result) {
                var popup = '';
                @foreach (session()->get('stores_hq') as $stores)
                    if(result.includes({{ $stores->id }})){
                        var data = '<tr>'+
                                        '<td>'+
                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                '<input type="checkbox" class="checks check custom-control-input stores" disabled id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
                                            '</div>'+
                                        '</td>'+
                                        '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
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
                $('#data_stores').html(popup);
                
                $("#addModal").modal("show");
                
            }
        });
    <?php } else { ?>
        $("#filter_form").submit();
    <?php } ?>
});
var add_stores = [];
add_stores.push("{{ session()->get('sid') }}");
$('#selectAllCheckbox').click(function(){
    if($('#selectAllCheckbox').is(":checked")){
        $(".stores").prop( "checked", true );
    }else{
        $( ".stores" ).prop("checked", false );
    }
});

$('#adding_btn').click(function(){
    $.each($("input[name='stores']:checked"), function(){            
        add_stores.push($(this).val());
    });
    $("#add_store_hq").val(add_stores);
    $("#filter_form").submit();
})




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
// <script src="{{ asset('javascript/jquery.dataTables.min.js') }}" type="text/javascript"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="http://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        
      var table = $('#Parent').DataTable({
        "dom": 't<"bottom"i<"float-right"p>><"clear">',
        "searching":true,
        "destroy": true,
        "bLengthChange": false,
        "pageLength":20,
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
                var selected_item = [];
                $.each($("input[name='selected[]']:checked"), function(){            
                    selected_item.push($(this).val());
                });
                
                <?php if(session()->get('hq_sid') == 1){  ?>      
                        $.ajax({
                            url : "<?php echo url('/item/parent_child/parentChildDuplicationfordelete'); ?>",
                            headers: {
                                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                            },
                            data : JSON.stringify(selected_item),
                            type : 'POST',
                            contentType: "application/json",
                            dataType: 'json',
                            success: function(result) {
                                var popup = '';
                                @foreach (session()->get('stores_hq') as $stores)
                                    if(result.includes({{ $stores->id }})){
                                        var data = '<tr>'+
                                                        '<td>'+
                                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                '<input type="checkbox" class="checks check custom-control-input deletestores" disabled id="hq_sid_{{ $stores->id }}" name="deletestores" value="{{ $stores->id }}">'+
                                                            '</div>'+
                                                        '</td>'+
                                                        '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
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
                                $('#delete_data_stores').html(popup);
                                
                                
                    
                            },
                        });
                        $("#deleteModal").modal("show");
                <?php } else { ?>
                        $("div#divLoading").addClass('show');
                        $.ajax({
                                url : delete_url,
                                headers: {
                                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                                },
                                data : JSON.stringify({data:selected_item}),
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
                <?php } ?>
            }
        });
        var delete_stores = [];
        delete_stores.push("{{ session()->get('sid') }}");
        $('#deleteSelectAllCheckbox').click(function(){
            if($('#deleteSelectAllCheckbox').is(":checked")){
                $(".deletestores").prop( "checked", true );
            }else{
                $( ".deletestores" ).prop("checked", false );
            }
        });
        
        $('#delete_btn').click(function(){
            $.each($("input[name='deletestores']:checked"), function(){            
                delete_stores.push($(this).val());
            });
            var delete_url = "<?php echo $dataUrl['delete']; ?>";
            delete_url = delete_url.replace(/&amp;/g, '&');
            
            var selected_item = [];
            $.each($("input[name='selected[]']:checked"), function(){            
                selected_item.push($(this).val());
            });
            
            $.ajax({
                    url : delete_url,
                    headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    data : JSON.stringify({data:selected_item, stores_hq: delete_stores }),
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
    
    });
</script>


<script type="text/javascript">
  $(document).on('click', '#save_button', function(event) {
    event.preventDefault();
    var data=[];
    var edit_url = "<?php echo $dataUrl['save']; ?>";
    
    edit_url = edit_url.replace(/&amp;/g, '&');
    
    $("input[name='selected[]']:checked").each(function (i) {
            // selected_item.push($(this).val());
            var itemid = $(this).val();
            var cpack_values = $('#cpack_'+itemid).val();
            var cpack_cid=$('#cid_'+itemid).val();
            var cpack_pid=$('#pid_'+itemid).val();
           
            data.push(cpack_values+'-'+cpack_cid+'-'+cpack_pid);
    });
    
    <?php if(session()->get('hq_sid') == 1){  ?>      
        $.ajax({
            url : "<?php echo url('/item/parent_child/parentChildDuplication'); ?>",
            headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : JSON.stringify(data),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(result) {
                var popup = '';
                @foreach (session()->get('stores_hq') as $stores)
                    if(result.includes({{ $stores->id }})){
                        var data = '<tr>'+
                                        '<td>'+
                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                '<input type="checkbox" class="checks check custom-control-input editstores" disabled id="editstores" name="editstores" value="{{ $stores->id }}">'+
                                            '</div>'+
                                        '</td>'+
                                        '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
                                    '</tr>';
                                $('#editSelectAllCheckbox').attr('disabled', true);
                    } else {
                        var data = '<tr>'+
                                        '<td>'+
                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                '<input type="checkbox" class="checks check custom-control-input editstores"  id="editstores" name="editstores" value="{{ $stores->id }}">'+
                                            '</div>'+
                                        '</td>'+
                                        '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                    '</tr>';
                    }
                    popup = popup + data;
                @endforeach
                $('#edit_data_stores').html(popup);
                
                $("#editModal").modal("show");
    
            },
        });
    <?php } else { ?>
        $("div#divLoading").addClass('show');
        $.ajax({
            url : edit_url,
            headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : JSON.stringify({data}),
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
    <?php } ?>
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
    
    $('#edit_btn').click(function(){
         $("div#divLoading").addClass('show');
        $.each($("input[name='editstores']:checked"), function(){            
            edit_stores.push($(this).val());
        });
        
        var data=[];
        var edit_url = "<?php echo $dataUrl['save']; ?>";
        
        edit_url = edit_url.replace(/&amp;/g, '&');
        
        $("input[name='selected[]']:checked").each(function (i) {
                // selected_item.push($(this).val());
                var itemid = $(this).val();
                var cpack_values = $('#cpack_'+itemid).val();
                var cpack_cid=$('#cid_'+itemid).val();
                var cpack_pid=$('#pid_'+itemid).val();
               
                data.push(cpack_values+'-'+cpack_cid+'-'+cpack_pid);
        });
        
        $.ajax({
            url : edit_url,
            headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : JSON.stringify({data: data, stores_hq: edit_stores}),
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
    })
    
    $("#closeBtn").click(function(){
        $("div#divLoading").removeClass('show');
    })
  
  
  
</script>

<style>
    .disabled {
    pointer-events:none; //This makes it not clickable
 
    }

</style>
@endsection