@extends('layouts.master')
@section('title', 'End Of Day')
@section('main-content')

<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <!-- <h1><?php echo $heading_title; ?></h1> -->
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
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
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
        </div>
        <div class="panel-body">
  
          
          <div class="clearfix"></div>
            
          <form action="/end_of_day" method="post" enctype="multipart/form-data" id="form-end-of-day-shift">
            @csrf
            <div class="row">
              <div class="col-md-3">
                <input type="" class="form-control" name="start_date" value="<?php echo isset($start_date) ? $start_date : ''; ?>" id="select_date" placeholder="Select Start Date">
              </div>
              <div class="col-md-4">
                <select name="batch[]" class="form-control" id="batch" multiple="true">
                  <option value="">-- Please Select Batch --</option>
                  <?php if(isset($batches) && count($batches) > 0){?>
                    <?php foreach($batches as $batch){?>
                      <?php if(isset($selected_batch_ids) && in_array($batch['ibatchid'], $selected_batch_ids)){?>
                        <option selected="selected" value="<?php echo $batch['ibatchid']?>"><?php echo $batch['vbatchname']?></option>
                      <?php } else { ?>
                        <option value="<?php echo $batch['ibatchid']?>"><?php echo $batch['vbatchname']?></option>
                      <?php } ?>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-4">
                <input type="submit" class="btn btn-success" name="add_end_of_shift" value="Add End of Day Shift">
              </div>
            </div>
          </form>
          <br>
          <h4><b>Baches already associated with : <span id="selected_date"></span></b></h4>
          <div id="batch_data" class="col-md-4 table-responsive">
                <table class="table" style="border:none;" id="batch_table">
                    
                    <tr>
                        <th style="width: 1px;" class="text-center">
                            <input type="checkbox" name="selected_batchid[]" id="main_checkbox" value=" " onclick="$('input[name*=\'selected_batchid\']').prop('checked', this.checked);" style="color:black;" />
                        </th>
                        <th> Batch Id</th>
                    </tr>
                    
                    <?php if(isset($batchdata_assoicited) && !empty($batchdata_assoicited)) { ?>
                        <?php foreach($batchdata_assoicited as $val) { ?>
                            <tr class="row_data">
                                <td><input type="checkbox" name="selected_batchid[]" value="<?=$val['batchid'];?>" data-eodid="<?=$val['eodid'];?>"></td>
                                <td><?=$val['batchid'];?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                        
                </table>
                
                <div class="row" id="reopen_btn">
                    <button class="btn btn-success" id="reopen_batch">Re-Open Selected Close Btaches</button>
                </div>
                
                <div class="row" id="info_msg">
                    <span class="text-danger">No Data Found..</button>
                </div>
          </div>
          
        </div>
      </div>
        
    </div>
  </div>
@endsection


@section('scripts')

<!--<link type="text/css" href="view/javascript/bootstrap-datepicker.css" rel="stylesheet" />
<script src="view/javascript/bootstrap-datepicker.js" defer></script>
<script src="view/javascript/bootbox.min.js" defer></script>-->

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});
  $(document).on('submit', '#form-end-of-day-shift', function(event) {

    if($('#select_date').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Date", 
        callback: function(){}
      });
      return false;
    }

    if($('#batch').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Batch", 
        callback: function(){}
      });
      return false;
    }

    if($('#batch :selected').length == 0){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Batch", 
        callback: function(){}
      });
      return false;
    }

    $("div#divLoading").addClass('show');
    

  });
</script>

<script>
  $(function(){
    $("#select_date, #batch_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });
  });
</script>

<?php echo $footer; ?>

<script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>
<script>
    $(document).ready(function() {
        
        <?php if(isset($batchdata_assoicited) && !empty($batchdata_assoicited)) { ?>
            $('#reopen_btn').show();
            $('#info_msg').hide();
        <?php }else{ ?>
            $('#reopen_btn').hide();
            $('#info_msg').show();
        <?php } ?>
        
        var select_date = $('#select_date').val(); console.log(select_date);
        $('#selected_date').text(select_date);
        
        $(document).on('change', '#select_date',function(){ 
           var start_date;
            var reportdata_url = '<?php echo $reportdata_associated; ?>';
            reportdata_url  = reportdata_url.replace(/&amp;/g, '&');
            var start_date1 = $('input[name="start_date"]').val();
            
            
            console.log(start_date1);
            if(start_date != "")
            {
                $.ajax({
                    url     : reportdata_url,
                    data    : {start_date:start_date1},
                    type    : 'POST',
                }).done(function(response){
                    if(response)
                    {   
                        var obj = $.parseJSON(response);
                        if(obj.length > 0){
                            $('#reopen_btn').show();
                            $('#info_msg').hide();
                        }else{
                            $('#info_msg').show();
                            $('#reopen_btn').hide();
                        }
                        
                        $('#batch_table tr.row_data').remove();
                        
                        var select_date = $('#select_date').val();
                        $('#selected_date').text(select_date);
                        
                        $.each(obj,function(key,val){
                            
                            var row= '<tr class="row_data"><td><input type="checkbox" name="selected_batchid[]" value="'+val.batchid+'" data-eodid="'+val.eodid+'"></td><td>'+val.batchid+'</td></tr>';
                            $('#batch_table').append(row);
                        });
                        // $('#batch_id').select2();
                        // $('#batch_data').text(response);
                        // $.each(response, function(index, value){
                            
                        //     console.log(value); 
                        // });
                    }
                });
            }
        });
        
        
        $(document).on('click', '#reopen_batch', function(event) {
            event.preventDefault();
            
            
            var url = '<?php echo $reopen_close_batch; ?>';
            url  = url.replace(/&amp;/g, '&');
            var batch_date = $('input[name="start_date"]').val();
            
            var reopen_batches = [];
            $('input[name="selected_batchid[]"]:checked').each(function(i){
                if($(this).val() != ' '){
                    // reopen_batches[i]['batchid'] = $(this).val();
                    // reopen_batches[i]['eodid'] = $(this).attr("data-eodid")
                    reopen_batches.push({batchid:$(this).val(), eodid:$(this).attr("data-eodid")});
                }
            });
            reopen_batches = reopen_batches.filter(item => item);
            console.log(reopen_batches);
            
            if(reopen_batches != "")
            {
                $.ajax({
                    url     : url,
                    data    : {batch_date:batch_date, reopen_batches:reopen_batches},
                    type    : 'POST',
                }).done(function(response){
                    
                    if(response.error){
                        $('#error_alias').html('<strong>'+ response.error +'</strong>');
                        $('#errorAliasModal').modal('show');
                    }else if(response.success){
                        $('#success_alias').html('<strong>'+ response.success +'</strong>');
                        $('#successAliasModal').modal('show'); 
                        // window.location.reload(false);
                        
                        var redirect_url = "<?php echo $current_url; ?>";
                        redirect_url = redirect_url.replace(/&amp;/g, '&');
                        
                        window.location.href = redirect_url;
                    }
                    
                    
                });
                
            }
            
        });
        
    });
    
</script>    

<!-- Modal -->
  <div class="modal fade" id="successAliasModal" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success text-center">
            <p id="success_alias"></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <div class="modal fade" id="errorAliasModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger text-center">
            <p id="error_alias"></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  
@endsection