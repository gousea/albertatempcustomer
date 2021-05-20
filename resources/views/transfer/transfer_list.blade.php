@extends('layouts.layout')
@section('title', 'Item Adjustment')
@section('main-content')
<div id="content">
  
  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase"><?php echo $text_list; ?></span>
            </div>
            <div class="nav-submenu">
                <button id="save_button_transfer" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-gray headerblack  buttons_menu" ><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                <a style="pointer-events:all;" href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
  </nav>

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
          <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_list; ?></h3>
        </div>
        <div class="panel-body">
          
          <div class="clearfix"></div>
  
          <form action="<?php echo $edit; ?>" method="post" enctype="multipart/form-data" id="form-transfer" class="form-horizontal">
            @csrf
            <input type="hidden" name="transfer[vvendortype]" value="Vendor">
            <input type="hidden" name="transfer[estatus]" value="Open">
            <input type="hidden" name="transfer[vwhcode]" value="WH101">

            <div class="container section-content">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-transfer"><?php echo $text_transfer_type; ?></label>
                    <div class="col-sm-8">
                      <select name="transfer[vtransfertype]" id="transfer_vtransfertype" class="form-control" >
                        <?php foreach($transfer_types as $transfer_type){ ?>
                          <?php if(isset($vtransfertype) && $vtransfertype == $transfer_type['value_transfer']){ ?>
                            <option value="<?php echo $transfer_type['value_transfer']; ?>" selected="selected"><?php echo $transfer_type['text_transfer']; ?></option>
                          <?php }else{ ?>
                            <option value="<?php echo $transfer_type['value_transfer']; ?>" ><?php echo $transfer_type['text_transfer']; ?></option>
                          <?php } ?>
                        <?php } ?>
                      </select>
    
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-transfer"><?php echo $text_transfer_date; ?></label>
                    <div class="col-sm-8">
                      <input type="text" name="transfer[dreceivedate]" value="<?php echo date('m-d-Y');?>" placeholder="<?php echo $text_transfer_date; ?>" class="form-control" id="transfer_date"/>
                    </div>
                  </div>
                </div>
              </div>
    
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-template"><?php echo $text_vendor; ?></label>
                    <div class="col-sm-8">
                      <select name="transfer[vvendorid]" id="transfer_vvendorid" class="form-control" >
                        <?php foreach($vendors as $vendor){ ?>
                        <?php if(isset($vendor_id) && $vendor_id == $vendor['isupplierid']){?>
                        <option value="<?php echo $vendor['isupplierid']; ?>" selected="selected"><?php echo $vendor['vcompanyname']; ?></option>
                        <?php }else{ ?>
                          <option value="<?php echo $vendor['isupplierid']; ?>"><?php echo $vendor['vcompanyname']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group required" id="invoice_id">
                    <label class="col-sm-4 control-label" for="input-template"><?php echo $text_invoice; ?></label>
                    <div class="col-sm-8">
                      <input type="text" maxlength="50" name="transfer[vinvnum]" value="<?php echo isset($vinvnum) ? $vinvnum : ''; ?>" placeholder="<?php echo $text_invoice; ?>" class="form-control" id="transfer_vinvnum"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <br><br>
            <div class="row">
              <div class="col-md-4" id="table_list_items_left">
                <div class="table-responsive" >
                  <table class="table table-bordered table-hover" style="padding:0px; margin:0px;" >
                    <thead>
                      <tr class="header-color">
                        <td style="width: 1px;" class="text-center"><input type="checkbox" id="selectAll"/></td>
                        <td style="width:150px;"><input type="text" class="form-control itemsort1_search" placeholder="SKU#" style="border:none;"></td>
                        <td style="width:242px;"><input type="text" class="form-control itemsort1_search" placeholder="Item Name" style="border:none;"></td>
                      </tr>
                    </thead>
                  </table>
                  <div class="div-table-content">
                    <table class="table table-bordered table-hover" id="itemsort1" style="table-layout: fixed;">
                      <tbody style="display: block; height:400px; overflow-y : scroll;">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-md-1 text-center" style="margin-top:12%;" id="table_list_move_btn">
                <a class="btn btn-primary" style="cursor:pointer" id="add_item_btn"><i class="fa fa-arrow-right fa-3x"></i></a><br><br>
                <a class="btn btn-primary" style="cursor:pointer" id="remove_item_btn"><i class="fa fa-arrow-left fa-3x"></i></a>
                
              </div>
              <div class="col-md-7" id="table_list_items_right">
                <div class="table-responsive" >
                  <table class="table table-bordered table-hover" style="padding:0px; margin:0px;" >
                    <thead>
                      <tr class="header-color">
                        <td style="width:1%" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" id="selectedcheck"/></td>
                        <td style="width:25%;"><input type="text" class="form-control itemsort2_search" placeholder="SKU#" style="border:none;width:100px;"></td>
                        <td style="width:35%;"><input type="text" class="form-control itemsort2_search" placeholder="Item Name" style="border:none;width:100px;"></td>
                        <td class="text-right" style="width:10%;">Case Qty</td>
                        <td style="width:10%;">Size</td>
                        <td class="text-right" style="width:10%;">Pack Qty</td>
                        <td class="text-right" style="width:10%;">Transfer Qty</td>
                      </tr>
                    </thead>
                  </table>
                  <div class="div-table-content" style="">
                    <table class="table table-bordered table-hover" id="itemsort2">
                      <tbody style="display: block; height:400px; overflow-y : scroll;">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
   
          </form>
        </div>
      </div>
    </div>
    
  </div>

  
<!-- Modal -->
<div class="modal fade" id="successTransferModal" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert-success text-center">
            <p id="success_alias"></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <div class="modal fade" id="errorTransferModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert-danger text-center">
            <p id="error_alias"></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
@endsection

@section('page-script')


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>

<link type="text/css" href="{{ asset('javascript/bootstrap-datepicker.css')}}" rel="stylesheet" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
{{-- <script src="{{ asset('javascript/bootstrap-datepicker.js')}}"></script> --}}
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});
//Item Filter
  $(document).on('keyup', '.itemsort1_search', function(event) {
    event.preventDefault();
    
    $('#itemsort1 tbody tr').hide();
    var txt = $(this).val();

    if(txt != ''){
      $('#itemsort1 tbody tr').each(function(){
        if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
          $(this).show();
        }
      });
    }else{
      $('#itemsort1 tbody tr').show();
    }
  });

  $(document).on('keyup', '.itemsort2_search', function(event) {
    event.preventDefault();
    
    $('#itemsort2 tbody tr').hide();
    var txt = $(this).val();

    if(txt != ''){
      $('#itemsort2 tbody tr').each(function(){
        if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
          $(this).show();
        }
      });
    }else{
      $('#itemsort2 tbody tr').show();
    }
  });
//Item Filter

//Items add and remove

$(document).on('click', '#add_item_btn', function(event) {
  event.preventDefault();

  window.checked_items2 = []; 

  var add_items_url = '<?php echo $add_items; ?>';
    
  add_items_url = add_items_url.replace(/&amp;/g, '&');

  if($("[name='checkbox_itemsort1[]']:checked").length == 0) {
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please select item to add", 
      callback: function(){}
    });
    return false;
  }

  $("div#divLoading").addClass('show');

  $("[name='checkbox_itemsort1[]']:checked").each(function (i) {
      
      if($.inArray($(this).val(), window.checked_items1) !== -1){
      }else{
        window.checked_items1.push($(this).val());
      }

      window.checked_items2.push($(this).val());

  });

  if(window.checked_items1.length > 0){
    $.ajax({
        url : add_items_url,
        data : {checkbox_itemsort1:window.checked_items1,checkbox_itemsort2:window.checked_items2},
        type : 'POST',
    }).done(function(response){

      // var  response = $.parseJSON(response); //decode the response array
      
      if(response.right_items_arr){
        var right_items_html = '';
        $.each(response.right_items_arr, function(i, v) {
          right_items_html += '<tr>';
          right_items_html += '<td class="text-center" style="width:1%;"><input type="checkbox" name="checkbox_itemsort2[]" value="'+v.iitemid+'"/></td>';
          right_items_html += '<td style="width:25%;">'+v.vbarcode+'<input type="hidden" name="items['+window.index_item+'][vbarcode]" value="'+v.vbarcode+'"></td>';
          right_items_html += '<td style="width:35%;">'+v.vitemname+'<input type="hidden" name="items['+window.index_item+'][vitemname]" value="'+v.vitemname+'"></td>';
          right_items_html += '<td class="text-right" style="width:10%;">'+v.iqtyonhand+'<input type="hidden" name="items['+window.index_item+'][nitemqoh]" value="'+v.iqtyonhand+'"></td>';
          right_items_html += '<td style="width:10%;"><input type="text" maxlength="50" class="editable editable_all_selected" name="items['+window.index_item+'][vsize]" id="" style="width:35px;"></td>';
          right_items_html += '<td class="text-right" style="width:10%;"><input type="text" class="editable_all_selected npackqty_class" name="items['+window.index_item+'][npackqty]" value="'+v.npack+'" id="" style="width:35px;text-align:right;"></td>';
          right_items_html += '<td class="text-right" style="width:10%;"><input type="text" maxlength="10" class="editable_all_selected ntransferqty_class" name="items['+window.index_item+'][ntransferqty]" value="" id="" style="width:35px;text-align:right;"></td>';
          right_items_html += '</tr>';
          window.index_item++;
        });

        $('#itemsort2 tbody').append(right_items_html);
      }

      if(response.left_items_arr){
        var left_items_html = '';
        $.each(response.left_items_arr, function(m, n) {
          left_items_html += '<tr>';
          left_items_html += '<td class="text-center" style="width:30px;"><input type="checkbox" name="checkbox_itemsort1[]" value="'+n.iitemid+'"/></td>';
          left_items_html += '<td style="width:105px;">'+n.vbarcode+'</td>';
          left_items_html += '<td style="width:242px;">'+n.vitemname+'</td>';
          left_items_html += '</tr>';
        });

        $('#itemsort1 tbody').html('');
        $('#itemsort1 tbody').append(left_items_html);
      }

      $("div#divLoading").removeClass('show');
      
    });
  }
});

$(document).on('click', '#remove_item_btn', function(event) {
  event.preventDefault();
  
  var remove_items_url = '<?php echo $remove_items; ?>';
    
  remove_items_url = remove_items_url.replace(/&amp;/g, '&');

  if($("[name='checkbox_itemsort2[]']:checked").length == 0) {
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please select item to remove", 
      callback: function(){}
    });
    return false;
  }

  $("div#divLoading").addClass('show');

  $("[name='checkbox_itemsort2[]']:checked").each(function (i) {
      
      if($.inArray($(this).val(), window.checked_items1) !== -1){
        window.checked_items1.splice( $.inArray($(this).val(), window.checked_items1), 1 );
        $(this).closest('tr').remove();
      }

  });

    $.ajax({
        url : remove_items_url,
        data : {checkbox_itemsort1:window.checked_items1},
        type : 'POST',
    }).done(function(response){

      // var  response = $.parseJSON(response); //decode the response array

      if(response.left_items_arr){
        var left_items_html = '';
        $.each(response.left_items_arr, function(m, n) {
          left_items_html += '<tr>';
          left_items_html += '<td class="text-center" style="width:30px;"><input type="checkbox" name="checkbox_itemsort1[]" value="'+n.iitemid+'"/></td>';
          left_items_html += '<td style="width:105px;">'+n.vbarcode+'</td>';
          left_items_html += '<td style="width:242px;">'+n.vitemname+'</td>';
          left_items_html += '</tr>';
        });

        $('#itemsort1 tbody').html('');
        $('#itemsort1 tbody').append(left_items_html);
      }

      $("div#divLoading").removeClass('show');
      
    });

});

$('#selectAll').click(function(e){
	var table= $('#itemsort1').closest('table');
	$('td input:checkbox',table).prop('checked',e.target.checked);
});

$('#selectedcheck').click(function(e){
	var table= $('#itemsort2').closest('table');
	$('td input:checkbox',table).prop('checked',e.target.checked);
});

//form submit
$(document).on('click', '#save_button_transfer', function(event) {

  if($('#itemsort2 > tbody > tr').length == 0){
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "please add Items", 
      callback: function(){}
    });
    return false;
  }

  if($('#transfer_vtransfertype').val() == 'WarehouseToStore'){

    if($('#transfer_vinvnum').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "please enter invoice", 
        callback: function(){}
      });
      return false;
    }

    if(($.trim($('#transfer_vinvnum').val())).length==0){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Enter Invoice!", 
        callback: function(){}
      });
      return false;
    }

    var check_invoice_url = '<?php echo $check_invoice; ?>';

    var transfer_vinvnum = $('#transfer_vinvnum').val();
    
    check_invoice_url = check_invoice_url.replace(/&amp;/g, '&');

    $.ajax({
    url : check_invoice_url,
    data : { invoice : transfer_vinvnum },
    dataType: 'json',
    type : 'POST',
    success: function(data) {
      if(data.error){
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Invoice Already Exist!", 
          callback: function(){}
        });
        return false;
      }else{
        $('form#form-transfer').submit();
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
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: error_show, 
        callback: function(){}
      });
    }
  });
  }else{
    $('form#form-transfer').submit();
  }

});

$(document).on('submit', 'form#form-transfer', function(event) {
  event.preventDefault();
  var edit_url = '<?php echo $edit; ?>';
    
  edit_url = edit_url.replace(/&amp;/g, '&');

  $("div#divLoading").addClass('show');

   $.ajax({
    url : edit_url,
    data : $('form#form-transfer').serialize(),
    type : 'POST',
    success: function(data) {

      setTimeout(function(){
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Successfully Updated Transfer!!!", 
          callback: function(){}
        });
      }, 3000);

      var cancel_url = '<?php echo $cancel; ?>';
    
      cancel_url = cancel_url.replace(/&amp;/g, '&');
      window.location.href = cancel_url;
      $("div#divLoading").addClass('show');
      
    },
    error: function(xhr) { // if error occured
      var  response_error = $.parseJSON(xhr.responseText); //decode the response array
      
      var error_show = '';

      if(response_error.error){
        error_show = response_error.error;
      }else if(response_error.validation_error){
        error_show = response_error.validation_error[0];
      }
      $("div#divLoading").removeClass('show');

      $('#error_alias').html('<strong>'+ error_show +'</strong>');
      $('#errorTransferModal').modal('show');
      return false;
    }
  });

});
</script>


<script type="text/javascript">
  $(function(){

    $('#transfer_date').each(function(){
        $(this).datepicker({
          dateFormat: 'mm-dd-yy',
          todayHighlight: true,
          autoclose: true,
        });
    });
  
  });
</script>

<script type="text/javascript">
  $(document).on('click', '#filter_btn', function(event) {
    event.preventDefault();
    
    var filter_url = '<?php echo $filter; ?>';
    
    filter_url = filter_url.replace(/&amp;/g, '&');

    var vtransfertype = $('#transfer_vtransfertype').val();
    var vvendorid = $('#transfer_vvendorid').val();

    filter_url = filter_url+'&vtransfertype='+vtransfertype+'&vvendorid='+vvendorid;
    window.location = filter_url;
    $("div#divLoading").addClass('show');

  });

  $(document).on('change', '#transfer_vtransfertype', function(event) {
    event.preventDefault();
    
    if($(this).val() == 'WarehouseToStore'){
      $('#invoice_id').show();

      $('#table_list_items_right').show();
      $('#table_list_items_right').removeClass('col-md-7');
      $('#table_list_items_right').addClass('col-md-12');
      $('#table_list_move_btn').hide();
      $('#table_list_items_left').hide();

    }else{
      $('#invoice_id').hide();
      $('#table_list_items_right').show();
      $('#table_list_items_right').removeClass('col-md-12');
      $('#table_list_items_right').addClass('col-md-7');
      $('#table_list_move_btn').show();
      $('#table_list_items_left').show();
    }

    var vtransfertype = $(this).val();
    var vvendorid = $('#transfer_vvendorid').val();

    var display_items_url = '<?php echo $display_items; ?>';
    display_items_url = display_items_url.replace(/&amp;/g, '&');

    display_items_url = display_items_url+'?vtransfertype='+vtransfertype+'&vvendorid='+vvendorid;

    $("div#divLoading").addClass('show');
    getDatas(display_items_url);

    // $('#filter_btn').trigger('click');
    
  });

  $(document).on('change', '#transfer_vvendorid', function(event) {
    event.preventDefault();
    
    var vtransfertype = $('#transfer_vtransfertype').val();
    var vvendorid = $(this).val();

    var display_items_url = '<?php echo $display_items; ?>';
    display_items_url = display_items_url.replace(/&amp;/g, '&');

    display_items_url = display_items_url+'?vtransfertype='+vtransfertype+'&vvendorid='+vvendorid;

    $("div#divLoading").addClass('show');
    getDatas(display_items_url);
    
    // $('#filter_btn').trigger('click');
    
  });
</script>

<script type="text/javascript">

  $(document).on('keypress keyup blur', '.npackqty_class, .ntransferqty_class', function(event) {

    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
    
  }); 

  $(document).on('focusout', '.npackqty_class, .ntransferqty_class', function(event) {
    event.preventDefault();

    if($(this).val() != ''){
      if($(this).val().indexOf('.') == -1){
        var new_val = $(this).val();
        $(this).val(new_val+'.00');
      }else{
        var new_val = $(this).val();
        if(new_val.split('.')[1].length == 0){
          $(this).val(new_val+'00');
        }
      }
    }
  }); 
</script>

<script type="text/javascript">
  $(document).ready(function(){

    var display_items_url = '<?php echo $display_items; ?>';
    display_items_url = display_items_url.replace(/&amp;/g, '&');

    $("div#divLoading").addClass('show');

    $('#table_list_items_right').show();
    $('#table_list_items_right').removeClass('col-md-7');
    $('#table_list_items_right').addClass('col-md-12');
    $('#table_list_move_btn').hide();
    $('#table_list_items_left').hide();

    getDatas(display_items_url);
    

  });

  
  var getDatas = function(display_items_url) {
            $.getJSON(display_items_url, function(result){
              if((result.previous_items) && (result.previous_items.length) > 0){
                window.checked_items1 = result.previous_items;
                window.index_item = parseInt(result.previous_items.length);
              }else{
                window.checked_items1 = []; 
                window.index_item = 0; 
              }

              // if(result.vinvnum){
              //   $('#transfer_vinvnum').val(result.vinvnum);
              // }

              if(result.items){
                var left_items_html = '';
                $.each(result.items, function(m, n) {
                  left_items_html += '<tr>';
                  left_items_html += '<td class="text-center" style="width:30px;"><input type="checkbox" name="checkbox_itemsort1[]" value="'+n.iitemid+'"/></td>';
                  left_items_html += '<td style="width:105px;">'+n.vbarcode+'</td>';
                  left_items_html += '<td style="width:242px;">'+n.vitemname+'</td>';
                  left_items_html += '</tr>';
                });

                $('#itemsort1 tbody').html('');
                $('#itemsort1 tbody').append(left_items_html);
              }
              if($('#transfer_vtransfertype').val() == 'WarehouseToStore'){
                if(result.edit_right_items){
                  var right_items_html = '';
                  $.each(result.edit_right_items, function(i, v) {
                    right_items_html += '<tr>';
                    right_items_html += '<td class="text-center" style="width:1%;"><input type="checkbox" name="checkbox_itemsort2[]" value="'+v.iitemid+'"/></td>';
                    right_items_html += '<td style="width:25%;">'+v.vbarcode+'<input type="hidden" name="items['+window.index_item+'][vbarcode]" value="'+v.vbarcode+'"></td>';
                    right_items_html += '<td style="width:35%;">'+v.vitemname+'<input type="hidden" name="items['+window.index_item+'][vitemname]" value="'+v.vitemname+'"></td>';
                    if(v.onhandcaseqty && v.onhandcaseqty !=0){
                      right_items_html += '<td class="text-right" style="width:10%;">'+parseInt(v.onhandcaseqty)+'<input type="hidden" name="items['+window.index_item+'][nitemqoh]" value="'+parseInt(v.onhandcaseqty)+'"></td>';
                    }else{
                      right_items_html += '<td class="text-right" style="width:10%;"><input type="hidden" name="items['+window.index_item+'][nitemqoh]" value=""></td>';
                    }
                    

                    right_items_html += '<td style="width:10%;"><input type="text" maxlength="50" class="editable" name="items['+window.index_item+'][vsize]" value="'+v.vsize+'" id="" style="width:35px;"></td>';
                    right_items_html += '<td class="text-right" style="width:10%;"><input type="text" class="editable_all_selected npackqty_class" name="items['+window.index_item+'][npackqty]" value="'+v.npackqty+'" id="" style="width:35px;text-align:right;"></td>';
                    right_items_html += '<td class="text-right" style="width:10%;"><input type="text" maxlength="10" class="editable_all_selected ntransferqty_class" name="items['+window.index_item+'][ntransferqty]" value="" id="" style="width:35px;text-align:right;"></td>';
                    right_items_html += '</tr>';
                    window.index_item++;
                  });

                  $('#itemsort2 tbody').html('');
                  $('#itemsort2 tbody').append(right_items_html);
                }
              }else{
                $('#itemsort2 tbody').html('');
              }

              $("div#divLoading").removeClass('show');
                
            });
        }
</script>
@endsection