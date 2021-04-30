@extends('layouts.master')
@section('title') @show
@section('main-content')

<?php 
// echo "<pre>";
// print_r($data);die;
    // echo $module_name;die;

?>

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
      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo 'Settings List'; ?></h3>
      </div>
      <div class="panel-body">

        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
            <div class="">
              <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
    
      <form action="{{route('itemlistdisplay.edit')}}" method="post" enctype="multipart/form-data" id="form-settings">
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

            <div class="row">
                
                <div class="col-md-5">
                      <div class="form-group">
                        <label class="control-label">Select Module</label>
                        <div class="">
                           <select class="col-md-8 form-control" name="module_name" id="module_name">
                                <option value="ItemListing" <?php echo $module_name == 'ItemListing' ? 'selected' : ''?>>Items</option>
                                <option value="Promotion" <?php echo $module_name == 'Promotion' ? 'selected' : ''?>>Promotion</option>
                                <option value="PurchaseOrder" <?php echo $module_name == 'PurchaseOrder' ? 'selected' : ''?>>Purchase Order</option>
                            </select>
                        </div>
                      </div>
                    </div>
            </div>
            <br>
          <div class="row">
            <div class="col-md-5">
              <div class="table-responsive" >
                <table class="table table-bordered table-hover" style="padding:0px; margin:0px;" >
                  <thead>
                    <tr>
                      <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'checkbox_itemsort1\']').prop('checked', this.checked);"/></td>
                      <td><input type="text" class="form-control itemsort1_search" placeholder="Available Columns" style="border:none;"></td>
                    </tr>
                  </thead>
                </table>
                <div class="div-table-content">
                  <table class="table table-bordered table-hover" id="itemsort1">
                    <tbody>
                      <?php if(isset($defualt_items_listings) && count($defualt_items_listings) > 0){ ?>
                        <?php foreach($defualt_items_listings as $defualt_items_listing){ ?>
                            <tr>
                              <td class="text-center" style="width:1px;"><input type="checkbox" name="checkbox_itemsort1[]" value="<?php echo $defualt_items_listing;?>"/></td>
                              <td><?php echo $title_arr[$defualt_items_listing];?></td>
                            <tr>
                          
                        <?php } ?>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-1 text-center" style="margin-top:12%;">
              <a class="btn btn-primary" style="cursor:pointer" id="add_item_btn"><i class="fa fa-arrow-right fa-3x"></i></a><br><br>
              <a class="btn btn-primary" style="cursor:pointer" id="remove_item_btn"><i class="fa fa-arrow-left fa-3x"></i></a>
              
            </div>
            <div class="col-md-5">
              <div class="table-responsive" >
                <table class="table table-bordered table-hover" style="padding:0px; margin:0px;" >
                  <thead>
                    <tr>
                      <td style="width:1px" class="text-center"><input type="checkbox" onclick="$('input[name*=\'checkbox_itemsort2\']').prop('checked', this.checked);" /></td>
                      <td><input type="text" class="form-control itemsort2_search" placeholder="Show column in this order" style="border:none;" readonly></td>
                    </tr>
                  </thead>
                </table>
                <div class="div-table-content" style="">
                  <table class="table table-bordered table-hover" id="itemsort2">
                    <tbody>
                      <?php if(isset($pre_items_listings)){ ?>
                        <?php foreach($pre_items_listings as $key => $pre_items_listing){ ?>
                          <tr>
                            <td class="text-center" style="width:1px;"><input type="checkbox" name="checkbox_itemsort2[]" value="<?php echo $key;?>"/></td>
                            <td><?php echo $pre_items_listing;?><input type="hidden" name="itemListings[<?php echo $key;?>]" value="<?php echo $pre_items_listing;?>"></td>
                          </tr>
                        <?php } ?>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div style="margin-top:15px;">
                <button type="button" id="move_up_btn" class="btn btn-info">Move Up</button>
                <button type="button" id="move_down_btn" class="btn btn-info">Move Down</button>
              </div>
            </div>
          </div>
        </form>
        
      </div>
    </div>
  </div>
</div>

@endsection



@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>

<style type="text/css">
  table#itemsort1 tr td, table#itemsort2 tr td{
    background-color: #fff !important;
  }
</style>

<script type="text/javascript">
 $(document).ready(function($) {
    <?php 
      $temp_titles = json_encode($title_arr);
      // $temp_titles = '';
    ?>
    var temp_titles = '<?php echo $temp_titles;?>';
    window.temp_titles = $.parseJSON(temp_titles);
  });

  $(document).on('click', '#save_button', function(event) {
    $('form#form-settings').submit();
   
  });

  $(document).on('submit', 'form#form-settings', function(event) {
    
    if($("#itemsort2").find('tr').length == 0){
      // alert('Please Add Columns!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Add Columns!", 
        callback: function(){}
      });
      return false;
    }

    if($("#itemsort2").find('tr').length > 10 && $("#module_name").val() == 'ItemListing'){
      // alert('Only 10 Columns Allowed to Add!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Only 10 Columns Allowed to Add!", 
        callback: function(){}
      });
      return false;
    }
     $("div#divLoading").addClass('show');
    
  });

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

  $(document).on('click', '#add_item_btn', function(event) {
    event.preventDefault();

    if($("input[name='checkbox_itemsort1[]']:checked").length == 0){
      // alert('Please Select Columns!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Columns!", 
        callback: function(){}
      });
      return false;
    }

    var right_items_html = '';
    $("[name='checkbox_itemsort1[]']:checked").each(function (i) {
      right_items_html += '<tr>';
      right_items_html += '<td class="text-center" style="width:1px;"><input type="checkbox" name="checkbox_itemsort2[]" value="'+ $(this).val() +'"/></td>';
      right_items_html += '<td>'+ window.temp_titles[$(this).val()] +'<input type="hidden" name="itemListings['+ $(this).val() +']" value="'+ window.temp_titles[$(this).val()] +'"></td>';
      right_items_html += '</tr>';

      $(this).closest('tr').remove();
    });
    $('#itemsort2 tbody').append(right_items_html);
  });

  $(document).on('click', '#remove_item_btn', function(event) {
    event.preventDefault();
    
    if($("input[name='checkbox_itemsort2[]']:checked").length == 0){
      // alert('Please Select Columns!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Columns!", 
        callback: function(){}
      });
      return false;
    }

    var left_items_html = '';
    $("[name='checkbox_itemsort2[]']:checked").each(function (i) {
      left_items_html += '<tr>';
      left_items_html += '<td class="text-center" style="width:1px;"><input type="checkbox" name="checkbox_itemsort1[]" value="'+ $(this).val() +'"/></td>';
      left_items_html += '<td>'+ window.temp_titles[$(this).val()] +'</td>';
      left_items_html += '</tr>';

      $(this).closest('tr').remove();
    });
    $('#itemsort1 tbody').append(left_items_html);

  });

  $(document).on('click', '#move_up_btn', function(event) {
    event.preventDefault();
    if($("input[name='checkbox_itemsort2[]']:checked").length == 0){
      // alert('Please Select Columns!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Columns!", 
        callback: function(){}
      });
      return false;
    }

    var first_checkbox_val = $('#itemsort2 input:checkbox:first').val();
    var move_up_html = '';
    var first_checked_checkbox = $("[name='checkbox_itemsort2[]']:checked:first");
    var first_element_exist = false;

    $("[name='checkbox_itemsort2[]']:checked").each(function (i) {
      if(first_checkbox_val == $(this).val()){
        // alert('You can\'t move up first column!');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "You can\'t move up first column!", 
          callback: function(){}
        });
        first_element_exist = true;
        return false;
      }

      move_up_html += '<tr>';
      move_up_html += '<td class="text-center" style="width:1px;"><input type="checkbox" name="checkbox_itemsort2[]" value="'+ $(this).val() +'"/></td>';
      move_up_html += '<td>'+ window.temp_titles[$(this).val()] +'<input type="hidden" name="itemListings['+ $(this).val() +']" value="'+ window.temp_titles[$(this).val()] +'"></td>';
      move_up_html += '</tr>';

    });

    if($("input[name='checkbox_itemsort2[]']:checked").length > 0 && first_element_exist == false){
      $(move_up_html).insertBefore(first_checked_checkbox.parent('td').parent('tr').prev('tr'));

      $("[name='checkbox_itemsort2[]']:checked").each(function (i) {
        $(this).closest('tr').remove();
      });
    }

  });

  $(document).on('click', '#move_down_btn', function(event) {
    event.preventDefault();
    if($("input[name='checkbox_itemsort2[]']:checked").length == 0){
      // alert('Please Select Columns!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Columns!", 
        callback: function(){}
      });
      return false;
    }

    var last_checkbox_val = $('#itemsort2 input:checkbox:last').val();
    var move_down_html = '';
    var last_checked_checkbox = $("[name='checkbox_itemsort2[]']:checked:last");
    var last_element_exist = false;

    $("[name='checkbox_itemsort2[]']:checked").each(function (i) {
      if(last_checkbox_val == $(this).val()){
        // alert('You can\'t move down last column!');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "You can\'t move down last column!", 
          callback: function(){}
        });
        last_element_exist = true;
        return false;
      }

      move_down_html += '<tr>';
      move_down_html += '<td class="text-center" style="width:1px;"><input type="checkbox" name="checkbox_itemsort2[]" value="'+ $(this).val() +'"/></td>';
      move_down_html += '<td>'+ window.temp_titles[$(this).val()] +'<input type="hidden" name="itemListings['+ $(this).val() +']" value="'+ window.temp_titles[$(this).val()] +'"></td>';
      move_down_html += '</tr>';
    });

    if($("input[name='checkbox_itemsort2[]']:checked").length > 0 && last_element_exist == false){
      $(move_down_html).insertAfter(last_checked_checkbox.parent('td').parent('tr').next('tr'));

      $("[name='checkbox_itemsort2[]']:checked").each(function (i) {
        $(this).closest('tr').remove();
      });
    }

  });
  
  $("#module_name").change(function(){
    if($(this).val() != ""){
        $("div#divLoading").addClass('show');
         var url = "{{ route('itemlistdisplay') }}";
            url = url.replace(/&amp;/g, '&');
            url = url+ "?module_name="+$(this).val();
        window.location.replace(url);
    }    
  });

  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>


@endsection