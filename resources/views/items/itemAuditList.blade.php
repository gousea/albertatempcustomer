
@extends('layouts.master')
@section('title', 'Item Audit')
@section('main-content')

<div id="content">
    
    <div class="page-header">
        
      <div class="container-fluid">
        <ul class="breadcrumb">
          @foreach ($breadcrumbs as $breadcrumb)
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          @endforeach
        </ul>
      </div>
    </div>
    <div class="container-fluid">
      @if ($error_warning) 
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      @endif
      @if ($success)
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      @endif
      <div class="panel panel-default">
        <div class="panel-heading head_title">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
        </div>
        <div class="panel-body">
          <div class="panel panel-default">
            <div class="panel-body">
              <form action="<?php echo $current_url;?>" method="post" id="form_item_search">
                @csrf
                <div class="row">
                    <div class="col-md-2" style="width:12%;">
                        <input type="radio" name="search_radio" value="by_dates">&nbsp;&nbsp;<span style="margin-top:3px;postion:absolute;">By Date</span>
                    </div>
                    <div class="col-md-2" style="width:12%;">
                        <input type="radio" name="search_radio" value="search">&nbsp;&nbsp;<span style="margin-top:3px;postion:absolute;">Search</span>
                    </div>
                </div><br><br>
                <div class="row">
                    <div class="col-md-6" id="div_search_by_dates">
                      <span style="display:inline-block;width:15%;"><b>Start Date:</b></span>&nbsp;
                      <input type="" name="seach_start_date" id="seach_start_date" class="form-control" value="<?php echo isset($search_find_dates['seach_start_date']) ? $search_find_dates['seach_start_date'] : ''; ?>" style="display:inline-block;width:30%;" readonly>&nbsp;&nbsp;
                      <span style="display:inline-block;width:15%;"><b>End Date:</b></span>&nbsp;
                      <input type="" name="seach_end_date" id="seach_end_date" value="<?php echo isset($search_find_dates['seach_end_date']) ? $search_find_dates['seach_end_date'] : ''; ?>" class="form-control" style="display:inline-block;width:30%;" readonly>
                    </div>
                    <div class="col-md-4" id="div_search_box">
                      <span style="display:inline-block;width:25%;"><b>Search Item</b></span>&nbsp;&nbsp;<input autocomplete='false' name="search_item" id="search_item" value="" class="form-control" style="display:inline-block;width:60%;">
                    </div>
                    <div class="col-md-2">
                    <input type="submit" name="search_filter" value="Filter" class="btn btn-info">
                  </div>
                </div>
              </form>
            </div>
          </div>        
          <br>
            <div class="table-responsive">
              <table id="item" class="table table-bordered table-hover">
              <?php if ($items) { ?>
                <thead>
                  <tr>
                    <th style="width: 1px;" class="text-center"><!-- <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /> --></th>
                    <th class="text-left"><?php echo $column_itemtype; ?></th>
                    <th class="text-left"><?php echo $column_sku; ?>#</th>
                    <th class="text-left "><?php echo $column_itemname; ?></th>
                    <th class="text-left"><?php echo $column_categorycode; ?></th>
                    <th class="text-left"><?php echo $column_deptcode; ?></th>
                    <th class="text-right">QOH</th>
                    <th class="text-right">WQOH</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Cost</th>
                    <th class="text-left">Last Modified</th>
                  </tr>
                </thead>
                <tbody>
                  
                  <?php foreach ($items as $item) { ?>
                    <tr>
                      <td data-order="<?php echo $item['iitemid']; ?>" class="text-center">
                      <!-- <input type="checkbox" name="selected[]" id=""  value="" /> -->
                      </td>
                      
                      <td class="text-left">
                        <span><?php echo $item['vitemtype']; ?></span>
                      </td>
  
                      <td class="text-left">
                        <span><?php echo $item['vbarcode']; ?></span>
                      </td>
  
                      <td class="text-left">
                        <span><?php echo $item['VITEMNAME']; ?></span>
                      </td>
  
                      <td class="text-left">
                        <span><?php echo $item['vcategoryname']; ?></span>
                      </td>
  
                      <td class="text-left">
                        <span><?php echo $item['vdepartmentname']; ?></span>
                      </td>
  
                      <td class="text-right">
                        <span><?php echo $item['QOH'];?></span>
                      </td>
  
                      <td class="text-right">
                        <span>&nbsp;</span>
                      </td>
  
                      <td class="text-right">
                        <span><?php echo $item['dunitprice']; ?></span>
                      </td>
  
                      <td class="text-right">
                        <span><?php echo $item['dcostprice']; ?></span>
                      </td>
  
                      <td class="text-left">
                        <?php 
                          $last_update_date = DateTime::createFromFormat('Y-m-d H:i:s', $item['LastUpdate']);
                          $last_update_date = $last_update_date->format('m-d-Y H:i:s');
                        ?>
                        <span><?php echo $last_update_date; ?></span>
                      </td>
  
                    </tr>
      
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td colspan="7" class="text-center"><?php echo $text_no_results;?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          
          <div class="row">
            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
            <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            @if (!empty($items))
              {{$items->links()}}
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('styles')
<style type="text/css">
    .span_field span{
      display: inline-block;
    }
  </style>
@endsection

@section('scripts')

<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>

<script>

$(document).ready(function() {
  $('#div_search_by_dates').hide();
  $('#div_search_box').hide(); 
    
  <?php if(isset($search_radio) && $search_radio == 'by_dates'){ ?>
    $('#div_search_by_dates').show();
    $("input[name=search_radio][value='by_dates']").prop('checked', true);
  <?php }else if(isset($search_radio) && $search_radio == 'search'){ ?>
    $('#div_search_box').show();
    $("input[name=search_radio][value='search']").prop('checked', true);
    
    <?php if(isset($search_find) && !empty($search_find)){ ?>
  
      var search_find = '<?php echo $search_find; ?>';
      $('#search_item').val(search_find);
      
    <?php } ?>
  <?php }else{ ?>
    //$('#div_search_vcategorycode').show();
    //$("input[name=search_radio][value='category']").prop('checked', true);
  <?php } ?>
    setTimeout( function() {
       <?php if(isset($search_find) && empty($search_find)){ ?>
        $('#search_item').val('');
    <?php } ?>
    }, 1000);
});


$(document).on('change', 'input:radio[name="search_radio"]', function(event) {
  event.preventDefault();
  if ($(this).is(':checked') && $(this).val() == 'by_dates') {
      $('#div_search_by_dates').show();
      $('#div_search_box').hide();
  }else if ($(this).is(':checked') && $(this).val() == 'search') {
      $('#div_search_box').show();
      $('#div_search_by_dates').hide();
  }else {
      $('#div_search_by_dates').show();
      $('#div_search_box').hide();
  }

});

$(document).on('submit', 'form#form_item_search', function(event) {
  if($('input:radio[name="search_radio"]').is(':checked') == false){
    // alert('Please select filter type!');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please select filter type!", 
      callback: function(){}
    });
    return false;
  }

  if($('input:radio[name="search_radio"]:checked').val() == 'by_dates'){
    if($('#seach_start_date').val() == ''){
      // alert('Please select start date!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please select start date!", 
        callback: function(){}
      });
      return false;
    }

    if($('#seach_end_date').val() == ''){
      // alert('Please select end date!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please select end date!", 
        callback: function(){}
      });
      return false;
    }
  }

  if($('input:radio[name="search_radio"]:checked').val() == 'search'){
    if($('#search_item').val() == ''){
      // alert('Please enter item name!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please enter item name!", 
        callback: function(){}
      });
      return false;
    }
  }

  $("div#divLoading").addClass('show');
 
});
 </script>


<script>
  $(function(){
    $("#seach_start_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });

    $("#seach_end_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });
  });
</script>

<script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>

<script type="text/javascript">
  $(document).on('change', 'input[name="seach_start_date"],input[name="seach_end_date"]', function(event) {
    event.preventDefault();

    if($('input[name="seach_start_date"]').val() != '' && $('input[name="seach_end_date"]').val() != ''){

      var d1 = Date.parse($('input[name="seach_start_date"]').val());
      var d2 = Date.parse($('input[name="seach_end_date"]').val()); 

      if(d1 > d2){
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Start date must be less then end date!", 
          callback: function(){}
        });
      return false;
      }
    }
  });
</script>


<style>
    .disabled {
    pointer-events:none; //This makes it not clickable
 
    }

</style>

@endsection