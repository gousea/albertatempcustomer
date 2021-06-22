@extends('layouts.layout')
@section('title', 'End Of Day')
@section('main-content')
<link rel="stylesheet" href="{{ asset('asset/css/promotion.css') }}">

<div id="content">
    
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Perform End of Day  </span>
                </div>
                
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>

    <section class="section-content py-6">
        <div class="container">
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

            <div class="mytextdiv mb-3">
                <div class="mytexttitle font-weight-bold text-uppercase">
                Perform End of Day
                </div>
                <div class="divider font-weight-bold"></div>
            </div>
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
                    <input type="submit" style="background-color: #286fb7 !important; color: #fff;" class="btn" name="add_end_of_shift" value="Add End of Day Shift">
                </div>
                </div>
            </form>

            <div class="mytextdiv mb-3">
                <div class="mytexttitle font-weight-bold text-uppercase">
                Baches already associated with : <span id="selected_date"></span>
                </div>
                <div class="divider font-weight-bold"></div>
            </div>
            <!-- <h4><b>Baches already associated with : <span id="selected_date"></span></b></h4> -->
            <div id="batch_data" class="table-responsive">
                <table class="table" style="border:none; width: 100%; border-collapse: separate; border-spacing:0 5px !important;" id="batch_table">
                    
                    <!-- <tr>
                        <th style="width: 1px;" class="text-center">
                            <input type="checkbox" name="selected_batchid[]" id="main_checkbox" value=" " onclick="$('input[name*=\'selected_batchid\']').prop('checked', this.checked);" style="color:black;" />
                        </th>
                        <th> Batch Id</th>
                    </tr> -->
                    
                    <?php if(isset($batchdata_assoicited) && !empty($batchdata_assoicited)) { ?>
                        <?php foreach($batchdata_assoicited as $val) { ?>
                            <tr class="row_data">
                                <td style="width: 1px;"><input type="checkbox" name="selected_batchid[]" value="<?=$val['batchid'];?>" data-eodid="<?=$val['eodid'];?>"></td>
                                <td style="color: #286fb7 !important;"><?=$val['batchid'];?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                        
                </table>
                
                <div class="row ml-1" id="reopen_btn">
                    <button style="background-color: #286fb7 !important; color: #fff;" class="btn" id="reopen_batch">Re-Open Selected Close Btaches</button>
                </div>
                
                <div class="row ml-1" id="info_msg">
                    <span class="text-danger">No Data Found..</button>
                </div>
            </div>

        </div>
    </section>
  </div>



<div class="modal fade" id="successModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
     Modal content
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


<div class="modal fade" id="sucModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning text-center">
          <p id="success_msg"></p>
        </div>
      </div>
    </div>
  </div>
</div>

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

<!-- <link type="text/css" href="view/javascript/bootstrap-datepicker.css" rel="stylesheet" />
<script src="view/javascript/bootstrap-datepicker.js" defer></script>
<script src="view/javascript/bootbox.min.js" defer></script> -->

<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet"> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> -->
    
<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>
<script src=" https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>


<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});
  $(document).on('submit', '#form-end-of-day-shift', function(event) {

    if($('#select_date').val() == ''){
        $('#warning_msg').html('Please Select Date');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        return false;
    }

    if($('#batch').val() == ''){
        $('#warning_msg').html('Please Select Batch');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        return false;
    }

    if($('#batch :selected').length == 0){
      
        $('#warning_msg').html('Please Select Batch');
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
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
                            
                            var row= `<tr class="row_data">
                                        <td style="width: 1px;"><input type="checkbox" name="selected_batchid[]" value="${val.batchid}" data-eodid="${val.eodid}"></td>
                                        <td style="color: #286fb7 !important;">${val.batchid}</td>
                                      </tr>`;
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
            
            
            if(reopen_batches != "")
            {
                $.ajax({
                    url     : url,
                    data    : {batch_date:batch_date, reopen_batches:reopen_batches},
                    type    : 'POST',
                    success: function (e) {
                        $('#success_msg').html('<strong>You have modified End of Day!</strong>');
                        $("div#divLoading").removeClass('show');
                        // alert("You have modified End of Day!");
                        $('#successModal').modal('show');
                        setTimeout(function(){
                            $('#successModal').modal('hide');
                            
                            window.location.href = '{{ route('end_of_day') }}';
                        }, 2000);
                    },
                    error: function (msg) {
                        $('#error_msg').html(" End of Day modification failed !");
                        $("div#divLoading").removeClass('show');
                        $('#errorModal').modal('show');
                    },
                    
                    
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