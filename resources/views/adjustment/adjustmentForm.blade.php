@extends('layouts.layout')
@section('title', 'Item Adjustment')

@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase"><?php echo $text_form; ?></span>
            </div>
            <div class="nav-submenu">
                
                <button id="save_button_adjustment_detail" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-gray headerblack  buttons_menu" <?php if(isset($estatus) && $estatus == 'Close'){?> disabled <?php } ?> ><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                <button id="calculate_post_button" data-toggle="tooltip" title="Calculate/Post" class="btn btn-gray headerblack  buttons_menu" <?php if(isset($estatus) && $estatus == 'Close'){?> disabled <?php } ?> ><i class="fa fa-calculator"></i>&nbsp;&nbsp;Calculate/Post</button>
                <a style="pointer-events:all;" href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
</nav>


<div id="content">
    
    <div class="container-fluid">
      <?php if ($error_warning) { ?>
        <div class="alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
      
        <div class="panel panel-default">
        
            <div class="clearfix"></div>
            <div class="panel-body" <?php if(isset($estatus) && $estatus == 'Close'){?> style="pointer-events:none;" <?php } ?> >
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-adjustment-detail" class="form-horizontal">
                @csrf
                  <?php if(isset($ipiid)){?>
                  <input type="hidden" name="ipiid" value="<?php echo $ipiid;?>">
                  <?php } ?>
                    
                    <div class="container section-content">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="col-sm-4 control-label" for="input-template"><?php echo $text_number; ?></label>
                              <div class="col-sm-8">
                                <input type="text" name="vrefnumber" maxlength="30" value="<?php echo isset($vrefnumber) ? $vrefnumber : ''; ?>" placeholder="<?php echo $text_number; ?>" class="form-control" required id="vrefnumber" readonly/>
              
                                <?php if ($error_vrefnumber) { ?>
                                  <div class="text-danger"><?php echo $error_vrefnumber; ?></div>
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group required">
                              <label class="col-sm-4 control-label" for="input-template"><?php echo $text_created; ?></label>
                              <?php
                              if(isset($dcreatedate) && !empty($dcreatedate) && $dcreatedate != '0000-00-00 00:00:00'){
                                $dcreatedate =  DateTime::createFromFormat('Y-m-d H:i:s', $dcreatedate)->format('m-d-Y');
                              }else{
                                $dcreatedate = '';
                              }
                              
                              ?>
                              <div class="col-sm-8" >
                                <?php if(isset($ipiid) && $ipiid != ''){?>
                                  <input type="text" name="dcreatedate" value="<?php echo $dcreatedate;?>" placeholder="<?php echo $text_created; ?>" class="form-control" id="dcreatedate" readonly style="pointer-events: none;" data-provide="datepicker"/>
                                <?php } else { ?>
                                  <input type="text" name="dcreatedate" value="<?php echo date('m-d-Y');?>" placeholder="<?php echo $text_created; ?>" class="form-control" id="dcreatedate" required/>
                                <?php } ?>
                                
              
                                <?php if ($error_dcreatedate) { ?>
                                  <div class="text-danger"><?php echo $error_dcreatedate; ?></div>
                                <?php } ?>
              
                              </div>
                            </div>
                          </div>
                        </div>
              
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group required">
                              <label class="col-sm-4 control-label" for="input-template"><?php echo $text_title; ?></label>
                              <div class="col-sm-8">
                                <input type="text" name="vordertitle" maxlength="50" value="<?php echo isset($vordertitle) ? $vordertitle : ''; ?>" placeholder="<?php echo $text_title; ?>" class="form-control" id="vordertitle" required/>
              
                                <?php if ($error_vordertitle) { ?>
                                  <div class="text-danger"><?php echo $error_vordertitle; ?></div>
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="col-sm-4 control-label" for="input-template"><?php echo $text_status; ?></label>
                              <div class="col-sm-8">
                                <input type="text" name="estatus" maxlength="10" value="<?php echo isset($estatus) ? $estatus : 'Open'; ?>" placeholder="<?php echo $text_status; ?>" class="form-control" readonly />
                              </div>
                            </div>
                          </div>
                        </div>
              
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="col-sm-4 control-label" for="input-template"><?php echo $text_notes; ?></label>
                              <div class="col-sm-8">
                                <textarea name="vnotes" maxlength="1000" placeholder="<?php echo $text_notes; ?>" class="form-control" ><?php echo isset($vnotes) ? $vnotes : ''; ?></textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    
                    <br><br><br>
                    <div class="row">
                      <div class="col-md-4" style="pointer-events: all;">
                        <div class="table-responsive" >
                          <table class="table table-bordered table-hover" style="padding:0px; margin:0px;" >
                            <thead>
                              <tr class="header-color">
                                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'checkbox_itemsort1\']').prop('checked', this.checked);"/></td>
                                <td style="width:185px;"><input type="text" class="form-control" id="itemsort1_search_sku" placeholder="SKU#" style="border:none;"></td>
                                <td style="width:242px;"><input type="text" class="form-control" id="itemsort1_search_item_name" placeholder="Item Name" style="border:none;"></td>
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
                      <div class="col-md-1 text-center" style="margin-top:12%;">
                        <a class="btn btn-primary" style="cursor:pointer" id="add_item_btn"><i class="fa fa-arrow-right fa-3x"></i></a><br><br>
                        <a class="btn btn-primary" style="cursor:pointer" id="remove_item_btn"><i class="fa fa-arrow-left fa-3x"></i></a>
                        
                      </div>
                      <div class="col-md-7" style="pointer-events: all;">
                        <div class="table-responsive" >
                          <table class="table table-bordered table-hover" style="padding:0px; margin:0px;" >
                            <thead>
                              <tr class="header-color">
                                <td style="width:1%" class="text-center"><input type="checkbox" onclick="$('input[name*=\'checkbox_itemsort2\']').prop('checked', this.checked);"/></td>
                                <td style="width:18%;"><input type="text" class="form-control itemsort2_search" placeholder="SKU#" style="border:none;"></td>
                                <td style="width:21%;"><input type="text" class="form-control itemsort2_search" placeholder="Item Name" style="border:none;"></td>
                                <td class="text-right" style="width:10%;">Unit Cost</td>
                                <td class="text-right" style="width:10%;">Pack Qty</td>
                                <td class="text-right" style="width:10%;">QOH</td>
                                <td class="text-right" style="width:10%;">Adj.Qty</td>
                                <td style="width:10%;">Reason</td>
                                <td class="text-right" style="width:10%;">Total Unit</td>
                                <td class="text-right" style="width:10%;">Total Amt</td>
                              </tr>
                            </thead>
                          </table>
                          <div class="div-table-content" style="">
                            <table class="table table-bordered table-hover" id="itemsort2" style="table-layout: fixed;">
                              <tbody style="display: block; height:400px; overflow-y : scroll;">
                                <?php if(isset($edit_right_items) && count($edit_right_items) > 0){?>
                                  <?php foreach($edit_right_items as $k => $edit_right_item){?>
                                    <tr>
                                        
                                      <td class="text-center" style="width:1%;">
                                        <input type="checkbox" name="checkbox_itemsort2[]" value="<?php echo $edit_right_item['iitemid']; ?>"/>
                                        <input type="hidden" name="items[<?php echo $k; ?>][vitemid]" value="<?php echo $edit_right_item['iitemid']; ?>">
                                      </td>
                                      
                                      <td style="width:15%;">
                                      <?php echo $edit_right_item['vbarcode']; ?>
                                      <input type="hidden" name="items[<?php echo $k; ?>][vbarcode]" value="<?php echo $edit_right_item['vbarcode']; ?>">
                                      </td>
                                      
                                      <td style="width:18%;">
                                      <?php echo $edit_right_item['vitemname']; ?>
                                      <input type="hidden" name="items[<?php echo $k; ?>][vitemname]" value="<?php echo $edit_right_item['vitemname']; ?>">
                                      </td>
                                      
                                      <td class="text-right" style="width:9%;">
                                        <input type="text" class="editable nunitcost_class" name="items[<?php echo $k; ?>][nunitcost]" value="<?php echo $edit_right_item['nunitcost']; ?>" style="width:30px;text-align: right;">
                                      </td>
                                      
                                      <td class="text-right" style="width:9%;">
                                        <input type="text" class="editable npackqty_class" name="items[<?php echo $k; ?>][npackqty]" value="<?php echo $edit_right_item['npackqty']; ?>" style="width:30px;text-align: right;">
                                      </td>
                                      
                                       <td class="text-right" style="width:9%;">
                                        <input type="text" class="editable iqtyonhand_class" name="items[<?php echo $k; ?>][iqtyonhand]" value="<?php echo $edit_right_item['iqtyonhand']; ?>" style="width:30px;text-align: right;">
                                      </td>
                                      
                                      <td class="text-right" style="width:9%;">
                                        <input type="text" class="editable ndebitqty_class" name="items[<?php echo $k; ?>][ndebitqty]" value="<?php echo $edit_right_item['ndebitqty']; ?>" maxlength = "5" style="width:30px;text-align: right;">
                                      </td>
                                      
                                      <td style="width:9%;">
                                          <select name="items[<?php echo $k; ?>][vreasoncode]" style="width:35px;">
                                          <option value="">Reason</option>
                                            <?php foreach($reasons as $reason){ ?>
                                              <?php if(isset($edit_right_item['vreasoncode']) && $reason['vreasoncode'] == $edit_right_item['vreasoncode']){ ?>
                                                <option value="<?php echo $reason['vreasoncode']; ?>" selected="selected"><?php echo $reason['vreasonename']; ?></option>
                                              <?php }else{ ?>
                                                <option value="<?php echo $reason['vreasoncode']; ?>" ><?php echo $reason['vreasonename']; ?></option>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
          
                                      </td>
                                      
                                      <td class="text-right" style="width:9%;">
                                        <input type="text" class="editable itotalunit_class" name="items[<?php echo $k; ?>][itotalunit]" value="<?php echo $edit_right_item['itotalunit']; ?>" style="width:30px;text-align: right;">
                                      </td>
                                      
                                      <td class="text-right" style="width:10%;">
                                        <span class="text_nnettotal_class"><?php echo $edit_right_item['nunitcost'] * $edit_right_item['itotalunit']; ?></span>
                                        <input type="hidden" class="nnettotal_class" name="items[<?php echo $k; ?>][nnettotal]" value="<?php echo $edit_right_item['nunitcost'] * $edit_right_item['itotalunit']; ?>">
                                      </td>
                                      
                                    </tr>
                                  <?php } ?>
                                <?php } ?>
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
    
  
<div class="modal fade" id="successModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <a href="<?php echo $cancel; ?>" class="close" >&times;</a>
        </div>
        <div class="modal-body alert-success ">
          <div class="text-center">
            <p id="success_alias"></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  
 <div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
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
{{--<script src="{{ asset('javascript/bootstrap-datepicker.js')}}"></script> --}}

 <script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
     --}}

<script type="text/javascript">
  $(function(){

    // $('#dcreatedate').each(function(){
        $('#dcreatedate').datepicker({
          dateFormat: 'mm-dd-yy',
          todayHighlight: true,
          autoclose: true,
        });
    // });
  
  });
  
</script>


<script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
      }
  });
//Item Filter
  $(document).on('keyup', '#itemsort1_search_sku', function(event) {
    event.preventDefault();
    $('#itemsort1_search_item_name').val('');

    var display_items_search_url = '<?php echo $display_items_search; ?>';
    display_items_search_url = display_items_search_url.replace(/&amp;/g, '&');

    var search_val = $(this).val();

    var data = {search_val:search_val,search_by:'vbarcode',right_items:window.checked_items2};

    if(search_val != ''){

      $.ajax({
        url : display_items_search_url,
        data : JSON.stringify(data),
        type : 'POST',
        success: function(response) {
          
          if(response.items){
            var left_items_html = [];

            for(var i=0;i<response.items.length;i++){

              left_items_html[i] ='<tr><td class="text-center" style="width:30px;"><input type="checkbox" name="checkbox_itemsort1[]" value="'+response.items[i]['iitemid']+'"/></td><td style="width:105px;">'+response.items[i]['vbarcode']+'</td><td style="width:242px;">'+response.items[i]['vitemname']+'</td></tr>';
            }

            $('#itemsort1 tbody').html('');
            $('#itemsort1 tbody').html(left_items_html.join(''));
          }
          
        },
        error: function(xhr) { // if error occured
        }
      });

    }else{
      $('#itemsort1 tbody').html('');
    }

  });


  $(document).on('keyup', '#itemsort1_search_item_name', function(event) {
    event.preventDefault();

    $('#itemsort1_search_sku').val('');

    var display_items_search_url = '<?php echo $display_items_search; ?>';
    display_items_search_url = display_items_search_url.replace(/&amp;/g, '&');

    var search_val = $(this).val();

    var data = {search_val:search_val,search_by:'vitemname',right_items:window.checked_items2};

    if(search_val != ''){

      $.ajax({
        url : display_items_search_url,
        data : JSON.stringify(data),
        type : 'POST',
        success: function(response) {
          
          if(response.items){
            var left_items_html = [];

            for(var i=0;i<response.items.length;i++){

              left_items_html[i] ='<tr><td class="text-center" style="width:30px;"><input type="checkbox" name="checkbox_itemsort1[]" value="'+response.items[i]['iitemid']+'"/></td><td style="width:105px;">'+response.items[i]['vbarcode']+'</td><td style="width:242px;">'+response.items[i]['vitemname']+'</td></tr>';
            }

            $('#itemsort1 tbody').html('');
            $('#itemsort1 tbody').html(left_items_html.join(''));
          }
          
        },
        error: function(xhr) { // if error occured
        }
      });

    }else{
      $('#itemsort1 tbody').html('');
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

</script>

<script type="text/javascript">
//Items add and remove

  $(document).on('click', '#add_item_btn', function(event) {
    event.preventDefault();

    // window.checked_items2 = []; 

    var data = {};

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
        data[i] = parseInt($(this).val());
    });

    if(data){
      $.ajax({
          url : add_items_url,
          data : {checkbox_itemsort1:data},
          type : 'POST',
      }).done(function(response){

        // var  response = $.parseJSON(response); //decode the response array
        
        if(response.right_items_arr){
          var right_items_html = '';
          $.each(response.right_items_arr, function(i, v) {

            window.checked_items2.push(v.iitemid);
              
            right_items_html += '<tr>';
            right_items_html += '<td class="text-center" style="width:30px;"><input type="checkbox" name="checkbox_itemsort2[]" value="'+v.iitemid+'"/><input type="hidden" name="items['+window.index_item+'][vitemid]" value="'+v.iitemid+'"></td>';
            right_items_html += '<td style="width:16%px;">'+v.vbarcode+'<input type="hidden" name="items['+window.index_item+'][vbarcode]" value="'+v.vbarcode+'"></td>';
            right_items_html += '<td style="width:15%;">'+v.vitemname+'<input type="hidden" name="items['+window.index_item+'][vitemname]" value="'+v.vitemname+'"></td>';
            right_items_html += '<td class="text-right" style="width:9%;">'+v.nunitcost+'<input type="hidden" class="editable nunitcost_class" name="items['+window.index_item+'][nunitcost]" value="'+v.nunitcost+'" id="" style="width:40px;text-align:right;"></td>';
            right_items_html += '<td class="text-right" style="width:9%;">'+v.npack+'<input type="hidden" class="editable npackqty_class" name="items['+window.index_item+'][npackqty]" id="" value="'+v.npack+'" style="width:40px;text-align:right;"></td>';
            right_items_html += '<td class="text-right" style="width:9%;">'+v.iqtyonhand+'<input type="hidden" class="editable iqtyonhand_class" name="items['+window.index_item+'][iqtyonhand]" value="'+v.iqtyonhand+'" id="" style="width:30px;text-align: right;"></td>';
            right_items_html += '<td class="text-right" style="width:11%;"><input type="text" class="editable_all_selected ndebitqty_class" name="items['+window.index_item+'][ndebitqty]" id="" style="width:40px;text-align:right;" maxlength = "5" value="0.00"></td>';
            
            right_items_html += '<td style="width:9%;">';
            
            right_items_html += '<select name="items['+window.index_item+'][vreasoncode]" style="width:40px;">';
            right_items_html += '<option value="">Reason</option>';
            
            <?php if(isset($reasons) && count($reasons) > 0){?>
              <?php foreach($reasons as $reason){?>
                right_items_html += '<option value="<?php echo $reason['vreasoncode'];?>"><?php echo $reason['vreasonename'];?></option>';
              <?php } ?>
            <?php } ?>
            right_items_html += '</select>';
            right_items_html += '</td>';
            right_items_html += '<td class="text-right" style="width:9%;"><span class="text_itotalunit_class">0.00</span><input type="hidden" class="editable itotalunit_class" name="items['+window.index_item+'][itotalunit]" id="" value="0.00" style="width:40px;text-align:right;"></td>';
            
            right_items_html += '<td class="text-right" style="width:9%;">';
            right_items_html += '<span class="text_nnettotal_class">0.00</span><input type="hidden" class="nnettotal_class" name="items['+window.index_item+'][nnettotal]" id="" value="0.00" >';
            right_items_html += '</td>';
          // right_items_html += '<td><input type="hidden" class="iqtyonhand_class" name="items['+window.index_item+'][iqtyonhand]"  value="'+v.iqtyonhand+'"></td>';
            right_items_html += '</tr>';
            window.index_item++;
          });

          $('#itemsort2 tbody').append(right_items_html);
        }


        $('#itemsort1 tbody').html('');

        $("div#divLoading").removeClass('show');
        
      });
    }
  });
</script>

<script type="text/javascript">
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
    
        if($.inArray( parseInt($(this).val()), window.checked_items2) !== -1){
          window.checked_items2.splice( $.inArray($(this).val(), window.checked_items2), 1 );
          $(this).closest('tr').remove();
          
        }

    });

      $.ajax({
          url : remove_items_url,
          data : {checkbox_itemsort1:window.checked_items1},
          type : 'POST',
      }).done(function(response){


        $('#itemsort1 tbody').html('');

        $("div#divLoading").removeClass('show');
        
      });

  });

//form submit
  $(document).on('click', '#save_button_adjustment_detail', function(event) {
    if($('form#form-adjustment-detail #vrefnumber').val() == ''){
      // alert('please enter Number');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "please enter number", 
        callback: function(){}
      });
      $('form#form-adjustment-detail #vrefnumber').focus();
      return false;
    }else if($('form#form-adjustment-detail #dcreatedate').val() == ''){
      // alert('please select Date');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "please select date", 
        callback: function(){}
      });
      $('form#form-adjustment-detail #dcreatedate').focus();
      return false;
    }else if($('form#form-adjustment-detail #vordertitle').val() == ''){
      // alert('please enter title');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "please enter title", 
        callback: function(){}
      });
      $('form#form-adjustment-detail #vordertitle').focus();
      return false;
    }else{
      $("div#divLoading").addClass('show');
      $('form#form-adjustment-detail').submit();
    }
  });
</script>



<script type="text/javascript">
  $(document).on('keyup', '.itotalunit_class', function(event) {
    event.preventDefault();
    
    if($(this).closest('tr').find('.nunitcost_class').val() != ''){
       var total_amt = $(this).closest('tr').find('.nunitcost_class').val() * $(this).val();
       $(this).closest('tr').find('.nnettotal_class').val(total_amt)
       $(this).closest('tr').find('.text_nnettotal_class').html(total_amt)
    }else{
      var total_amt = 0 * $(this).val();
      $(this).closest('tr').find('.nnettotal_class').val(total_amt)
      $(this).closest('tr').find('.text_nnettotal_class').html(total_amt)
    }
  });
</script>

<script type="text/javascript">
  $(document).on('keyup', '.nunitcost_class', function(event) {
    event.preventDefault();
    
    if($(this).closest('tr').find('.itotalunit_class').val() != ''){
       var total_amt = $(this).closest('tr').find('.itotalunit_class').val() * $(this).val();
       $(this).closest('tr').find('.nnettotal_class').val(total_amt)
       $(this).closest('tr').find('.text_nnettotal_class').html(total_amt)
    }else{
      var total_amt = 0 * $(this).val();
      $(this).closest('tr').find('.nnettotal_class').val(total_amt)
      $(this).closest('tr').find('.text_nnettotal_class').html(total_amt)
    }
  });
</script>

<script type="text/javascript">
  $(document).on('keypress keyup blur', 'input[name="vrefnumber"], .npackqty_class, .itotalunit_class', function(event) {

    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    
  }); 

  $(document).on('keypress keyup', '.nunitcost_class, .ndebitqty_class', function(event) {
      
    //   alert("hi");
      this.value = this.value.match(/^\d+\.?\d{0,2}/);
      
      

    console.log(event.which);
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57) && (event.which != 45 || $(this).val().indexOf('-') != -1)) {
      event.preventDefault();
    }

    $(this).closest('tr').find('input[type="checkbox"]').prop('checked', true);
    
    var adj_qty = $(this).val();
    var pack_size = $(this).closest('tr').find('.npackqty_class').val();
    var unit_cost = $(this).closest('tr').find('.nunitcost_class').val();
    var qoh = $(this).closest('tr').find('.iqtyonhand_class').val();

    if($(this).val() != ''){
        var total_unit = qoh - adj_qty;
        if(total_unit < 0 || isNaN(total_unit))
        {
            
            // total_unit = 0;
            // $(this).val('0');
            // adj_qty = 0;
        }
        
    //   var total_unit = pack_size * adj_qty;
      var total_amt = total_unit * unit_cost;
      total_amt = total_amt.toFixed(2);
      $(this).closest('tr').find('.itotalunit_class').val(total_unit);
      $(this).closest('tr').find('.text_itotalunit_class').html(adj_qty)
    //   $(this).closest('tr').find('.text_nnettotal_class').html(total_amt);
      $(this).closest('tr').find('.text_nnettotal_class').html(adj_qty * unit_cost);
      $(this).closest('tr').find('.nnettotal_class').val(total_amt);
    }else{
      $(this).closest('tr').find('.itotalunit_class').val(pack_size);
      $(this).closest('tr').find('.text_itotalunit_class').html(pack_size);
      $(this).closest('tr').find('.text_nnettotal_class').html('0.00');
      $(this).closest('tr').find('.nnettotal_class').val('0.00');
      //$(this).val('0');
    }
    
  });
//adding decimal zeros (.00)
  $(document).on('focusout', '.nunitcost_class, .ndebitqty_class', function(event) {
    event.preventDefault();

    if($(this).val() != ''){
      if($(this).val().indexOf('.') == -1){
        var new_val = $(this).val();
        $(this).val(new_val+'.00');
      }else{
        var new_val = $(this).val();
        if(new_val.split('.')[1].length == 0){
          $(this).val(new_val+'00');
        }else if(new_val.split('.')[1].length == 1){
            $(this).val(new_val+'0');
        }
      }
    }else{
        $(this).val('0');
    }
  });  
</script>

<script type="text/javascript">
  $(document).ready(function(){

    window.reasons = $.parseJSON('<?php echo json_encode($reasons); ?>');

    window.checked_items2 = [];
    

    var display_items_url = '<?php echo $display_items; ?>';
    display_items_url = display_items_url.replace(/&amp;/g, '&');

    $("div#divLoading").addClass('show');

    <?php if(isset($ipiid)){?>

      var ipiid = '<?php echo $ipiid; ?>';

      display_items_url = display_items_url+'/'+ipiid;
      
    <?php } ?>
    
    $.getJSON(display_items_url, function(result){
      if((result.previous_items) && (result.previous_items.length) > 0){
        window.checked_items1 = result.previous_items;
        window.index_item = parseInt(result.previous_items.length);
      }else{
        window.checked_items1 = []; 
        window.index_item = 0; 
      }

      if(result.edit_right_items){
        var right_items_html = '';
        $.each(result.edit_right_items, function(i, v) {

          window.checked_items2.push(v.iitemid);
            
            
          right_items_html += '<tr>';
          right_items_html += '<td class="text-center" style="width:30px;"><input type="checkbox" name="checkbox_itemsort2[]" value="'+v.iitemid+'"/><input type="hidden" name="items['+window.index_item+'][vitemid]" value="'+v.iitemid+'"></td>';
          right_items_html += '<td style="width:16%;">'+v.vbarcode+'<input type="hidden" name="items['+window.index_item+'][vbarcode]" value="'+v.vbarcode+'"></td>';
          right_items_html += '<td style="width:15%;">'+v.vitemname+'<input type="hidden" name="items['+window.index_item+'][vitemname]" value="'+v.vitemname+'"></td>';
          right_items_html += '<td class="text-right" style="width:9%;">'+v.nunitcost+'<input type="hidden" class="editable nunitcost_class" name="items['+window.index_item+'][nunitcost]" value="'+v.nunitcost+'" id="" style="width:40px;text-align:right;"></td>';
          right_items_html += '<td class="text-right" style="width:9%;">'+v.npackqty+'<input type="hidden" class="editable npackqty_class" name="items['+window.index_item+'][npackqty]" value="'+v.npackqty+'" id="" style="width:40px;text-align:right;"></td>';
          right_items_html += '<td class="text-right" style="width:9%;">'+v.iqtyonhand+'<input type="hidden" class="editable iqtyonhand_class" name="items['+window.index_item+'][iqtyonhand]" value="'+v.iqtyonhand+'" id="" style="width:30px;text-align:right;"></td>';
          right_items_html += '<td class="text-right" style="width:11%;"><input type="text" class="editable_all_selected ndebitqty_class" name="items['+window.index_item+'][ndebitqty]" maxlength = "5" value="'+v.ndebitqty+'" id="" style="width:48px;text-align:right;"></td>';
          right_items_html += '<td style="width:9%;">';
          right_items_html += '<select name="items['+window.index_item+'][vreasoncode]" style="width:40px;">';
          right_items_html += '<option value="">Reason</option>';

          $.each(window.reasons, function(index, value) {
            if(value.vreasoncode == v.vreasoncode){
              right_items_html += '<option value="'+ value.vreasoncode +'" selected="selected">'+ value.vreasonename +'</option>';
            }else{
              right_items_html += '<option value="'+ value.vreasoncode +'">'+ value.vreasonename +'</option>';
            }
          });
          
          right_items_html += '</select>';
          right_items_html += '</td>';
        //   right_items_html += '<td class="text-right" style="width:10%;"><span class="text_itotalunit_class">'+ v.itotalunit +'</span><input type="hidden" class="editable itotalunit_class" name="items['+window.index_item+'][itotalunit]" value="'+v.itotalunit+'" id="" style="width:40px;text-align:right;"></td>';
          right_items_html += '<td class="text-right" style="width:9%;"><span class="text_itotalunit_class">'+ v.ndebitqty +'</span><input type="hidden" class="editable itotalunit_class" name="items['+window.index_item+'][itotalunit]" value="'+v.itotalunit+'" id="" style="width:40px;text-align:right;"></td>';
          
          right_items_html += '<td class="text-right" style="width:9%;">';
        //   right_items_html += '<span class="text_nnettotal_class">'+ (v.nunitcost * v.itotalunit).toFixed(2) +'</span><input type="hidden" class="nnettotal_class" name="items['+window.index_item+'][nnettotal]" value="'+ (v.nunitcost * v.itotalunit).toFixed(2) +'" id="" >';
          right_items_html += '<span class="text_nnettotal_class">'+ (v.nunitcost * v.ndebitqty).toFixed(2) +'</span><input type="hidden" class="nnettotal_class" name="items['+window.index_item+'][nnettotal]" value="'+ (v.nunitcost * v.itotalunit).toFixed(2) +'" id="" >';
          right_items_html += '</td>';
          right_items_html += '</tr>';
          window.index_item++;
        });
        
        $('#itemsort2 tbody').html('');
        $('#itemsort2 tbody').append(right_items_html);
      }

      $("div#divLoading").removeClass('show');
        
    });

  });
</script>

<script type="text/javascript">
  $(document).on('click', '#calculate_post_button', function(event) {
    event.preventDefault();

    if($('form#form-adjustment-detail #vrefnumber').val() == ''){
      // alert('please enter Number');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "please enter number", 
        callback: function(){}
      });
      $('form#form-adjustment-detail #vrefnumber').focus();
      return false;
    }
    if($('form#form-adjustment-detail #dcreatedate').val() == ''){
      // alert('please select Date');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "please select date", 
        callback: function(){}
      });
      $('form#form-adjustment-detail #dcreatedate').focus();
      return false;
    }
    if($('form#form-adjustment-detail #vordertitle').val() == ''){
      // alert('please enter title');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "please enter title", 
        callback: function(){}
      });
      $('form#form-adjustment-detail #vordertitle').focus();
      return false;
    }

    if($('#itemsort2 > tbody > tr').length == 0){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "please add items!", 
        callback: function(){}
      });
    
      return false;
    }
   
    var calculate_post_url = '<?php echo $calculate_post; ?>';
    calculate_post_url = calculate_post_url.replace(/&amp;/g, '&');
    $("div#divLoading").addClass('show');

    $.ajax({
      url : calculate_post_url,
      data : $('form#form-adjustment-detail').serialize(),
      type : 'POST',
      success: function(data) {

        $("div#divLoading").removeClass('show');
        $('#success_alias').html('<strong>'+ data.success +'</strong>');
        $('#successModal').modal('show');
        setTimeout(function(){
            window.location = '<?php echo $cancel;  ?>'; 
        }, 3000);
        
     


        var adjustment_list_url = '<?php echo $adjustment_list; ?>';
        adjustment_list_url = adjustment_list_url.replace(/&amp;/g, '&');
        
        <?php if(!isset($ipiid)){?>
          /*setTimeout(function(){
           window.location.href = adjustment_list_url;
           $("div#divLoading").addClass('show');
          }, 3000);*/
        <?php }else{ ?>
          /*setTimeout(function(){
           window.location.reload();
           $("div#divLoading").addClass('show');
          }, 3000);*/
        <?php } ?>
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
        check_invoice_number = false;
        $("div#divLoading").removeClass('show');
        return false;
      }
    });
  });
</script>
@endsection