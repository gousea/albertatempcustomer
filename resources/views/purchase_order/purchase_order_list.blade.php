@extends('layouts.master')
@section('title', 'Purchase Order')
@section('main-content')

<div id="content">
    
    <div class="container-fluid">
        @if ($data['error_warning'])
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ $data['error_warning'] }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif
        @if ($data['success'])
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ $data['success'] }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif
        <br>
        <br>
        <div class="panel panel-default">
            <div class="panel-heading head_title">
                <h3 class="panel-title"><i class="fa fa-list"></i>Purchase Order</h3>
            </div>
            
            <div class="panel-body">
                <div class="row" style="padding-bottom:15px;float: right;">
                    <div class="col-md-12">
                        <div class="">
                            <a href="<?php echo $data['add']; ?>" title="Add (Manual)" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New (Manual)</a>  
                            <a href="<?php echo $data['po_sales_history']; ?>" title="Add (Sales History)" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New (Sales History)</a>  
                            <a href="<?php echo $data['po_par_level']; ?>" title="Add (PAR Level)" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New (PAR Level)</a>  
                            <button class="btn btn-danger" id="delete_po_btn" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete PO</button>
                        </div>
                    </div>
                </div>
                <div class="row" style="clear:both;">
                    <form action="<?php echo $data['current_url'];?>" method="post" id="form_order_search">
                        @csrf
                        <div class="col-md-12">
                            <div style="display: inline-block;width:92%;">
                                <input type="text" name="searchbox" value="<?php echo isset($data['searchbox']) ? $data['searchbox']: ''; ?>" class="form-control" placeholder="Search..." autocomplete="off" required>
                            </div>
                          
                            <div style="display: inline-block;">
                                <input type="submit" name="Filter" value="Search" class="btn btn-info">
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <form action="" method="post" enctype="multipart/form-data" id="form-purchase-order">
                  
                    <div class="table-responsive">
                        <table id="purchase_order" class="table table-bordered table-hover">
                        <?php if ($purchase_orders) { ?>
                        <thead>
                            <tr>
                              <th style="width: 1px;" class="text-center"><input type="checkbox" id="main_checkbox" /></th>
                              <th class="text-left"><a style="color: #fff;" href="<?php echo $data['sort_estatus'];?>">Status</a></th>
                              <th class="text-right"><a style="color: #fff;" href="<?php echo $data['sort_vponumber'];?>">PurchaseORD#</a></th>
                              <th class="text-left">Invoice#</th>
                              <th class="text-right sample">Total</th>
                              <th class="text-left"><a style="color: #fff;" href="<?php echo $data['sort_vvendorname'];?>">Vendor Name</a></th>
                              <th class="text-left"><a style="color: #fff;" href="<?php echo $data['sort_vordertype'];?>">Order Type</a></th>
                              <th class="text-left"><a style="color: #fff;" href="<?php echo $data['sort_dcreatedate'];?>">Date Created</a></th>
                              <th class="text-left"><a style="color: #fff;" href="<?php echo $data['sort_dreceiveddate'];?>">Date Received</a></th>
                              <th class="text-left"><a style="color: #fff;" href="<?php echo $data['sort_LastUpdate'];?>">Last Update</a></th>
                              <th class="text-left">Action</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            
                            <?php $purchase_order_row = 1;$i=0; $bg = '#fff';?>
                            <?php foreach ($purchase_orders as $purchase_order) { ?>
                                
                            <?php 
                                if($purchase_order['estatus'] == 'Close'){
                                    $bg = '#FCEBCF';
                                } else {
                                    $bg = '#fff';
                                } 
                            ?>
                            
                            <tr id="purchase_order-row<?php echo $purchase_order_row; ?>">
                                <td data-order="<?php echo $purchase_order['ipoid']; ?>" class="text-center" style="background:<?php echo $bg; ?>">
                                    <span style="display:none;"><?php echo $purchase_order['ipoid']; ?></span>
                                    <input type="checkbox" name="selected[]" id="purchase_order[<?php echo $purchase_order_row; ?>][select]"  value="<?php echo $purchase_order['ipoid']; ?>" <?php if($purchase_order['estatus'] == 'Close'){?> disabled <?php } ?> />
                                </td>
                              
                                <td class="text-left" style="background:<?php echo $bg; ?>">
                                    <span><?php echo $purchase_order['estatus']; ?></span>
                                </td>
                                
                                <td class="text-right" style="background:<?php echo $bg; ?>">
                                    <span><?php echo $purchase_order['vponumber']; ?></span>
                                </td>
                                
                                <td class="text-left" style="background:<?php echo $bg; ?>">
                                    <span><?php echo $purchase_order['vinvoiceno']; ?></span>
                                </td>
                                
                                <td class="text-right" style="background:<?php echo $bg; ?>">
                                    <span><?php echo $purchase_order['nnettotal']; ?></span>
                                </td>
                                
                                <td class="text-left" style="background:<?php echo $bg; ?>">
                                    <span><?php echo $purchase_order['vvendorname']; ?></span>
                                </td>
                                
                                <td class="text-left" style="background:<?php echo $bg; ?>">
                                    <span><?php echo $purchase_order['vordertype']; ?></span>
                                </td>
                                
                                <td class="text-left" style="background:<?php echo $bg; ?>">
                                    <span><?php echo $purchase_order['dcreatedate']; ?></span>
                                </td>
                                
                                <td class="text-left" style="background:<?php echo $bg; ?>">
                                    <span><?php echo $purchase_order['dreceiveddate']; ?></span>
                                </td>
                                
                                <td class="text-left" style="background:<?php echo $bg; ?>">
                                    <span><?php echo $purchase_order['dlastupdate']; ?></span>
                                </td>
                                
                                <td class="text-left" style="background:<?php echo $bg; ?>">
                                    <?php if($purchase_order['estatus'] == 'Close'){?>
                                        <a href="<?php echo $purchase_order['edit']; ?>" data-toggle="tooltip" title="View" class="btn btn-sm btn-info edit_btn_rotate" ><i class="fa fa-eye"></i>&nbsp;&nbsp;View
                                        </a>
                                    <?php } else {?>
                                        <a href="<?php echo $purchase_order['edit']; ?>" data-toggle="tooltip" title="Edit" class="btn btn-sm btn-info edit_btn_rotate" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php $purchase_order_row++; $i++;?>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td colspan="7" class="text-center">Sorry no data found!</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                </form>
                <div class="row">
                  {{$data['results']->links()}}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')

<script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>
<script type="text/javascript">
    $(function() { $('input[name="searchbox"]').focus(); });

    $(document).on('keyup', '#itemsort_search', function(event) {
        event.preventDefault();
    
        $('#missing_item_table tbody tr').hide();
        var txt = $(this).val();
    
        if(txt != ''){
            $('#missing_item_table tbody tr').each(function(){
                if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                    $(this).show();
                }
            });
        }else{
            $('#missing_item_table tbody tr').show();
        }
    });

  
</script>

<!-- Modal -->
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
    <div class="modal-dialog modal-sm">
        
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
<!-- Modal -->

<script type="text/javascript">
    $(document).on('submit', '#form_order_search', function(event) {
        $("div#divLoading").addClass('show');
    });

    $(document).ready(function($) {
        $("div#divLoading").addClass('show');
    });

    $(window).load(function() {
        $("div#divLoading").removeClass('show');
    });
</script>

<script type="text/javascript">
    $(document).on('click', '#main_checkbox', function(event) {
        if ($(this).prop('checked')==true){ 
            $('input[name="selected[]"]').not(":disabled").prop('checked', true);
        }else{
            $('input[name="selected[]"]').not(":disabled").prop('checked', false);
        }
    });


    $(document).on('click', '#delete_po_btn', function(event) {
        event.preventDefault();
        var delete_url = '<?php echo $data['delete']; ?>';
        delete_url = delete_url.replace(/&amp;/g, '&');
        var data = {};
        
        if($("input[name='selected[]']:checked").length == 0){
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: 'Please Select PO to Delete!', 
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
            url : delete_url,
            headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : JSON.stringify(data),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                
                $('#success_msg').html('<strong>'+data.success+'</strong>');
                $("div#divLoading").removeClass('show');
                $('#successModal').modal('show');
                
                setTimeout(function(){
                    $('#successModal').modal('hide');
                    window.location.reload();
                }, 3000);
            },
            error: function(xhr) { // if error occured
                var  response_error = $.parseJSON(xhr.responseText); //decode the response array
                
                var error_show = '';
        
                if(response_error.error){
                  error_show = response_error.error;
                }else if(response_error.validation_error){
                  error_show = response_error.validation_error[0];
                }
        
                $('#error_msg').html('<strong>'+ error_show +'</strong>');
                $('#errorModal').modal('show');
                return false;
            }
        });
    });
</script>


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
  
  <style>
      .floatBlock {
          margin: 0 1.81em 0 0;
        }
        
    .checkdigitOptions {
    	border: none;
    	display: flex;
    	flex-direction: row;
    	justify-content: flex-start;
    	break-before: always;
    }
  </style>
  
  @endsection