@extends('layouts.layout')
@section('title', 'Receiving Order')
@section('main-content')

<div id="content">

  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase"> Receiving Order</span>
            </div>
            <div class="nav-submenu">
              
              <a type="button" href="<?php echo $data['add']; ?>" title="Add" class="btn btn-gray headerblack  buttons_menu add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>  
                          
              <button class="btn btn-dark headerwhite buttons_menu basic-button-small" data-toggle="modal" data-target="#myModalImportNew"><i class="fa fa-file"></i>&nbsp;&nbsp;Import EDI Invoice</button>
            
              <button class="btn btn-dark headerwhite buttons_menu basic-button-medium" data-toggle="modal" data-target="#myModalImportMissingItem"><i class="fa fa-gift"></i>&nbsp;Import Missing Items</button>
              <?php if(isset($error_import_barcode) && !empty($error_import_barcode)){ ?>
                  <button class="btn btn-warning buttonred buttons_menu basic-button-small" data-toggle="modal" data-target="#error_import_modal"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;Import Error Occurs</button>
              <?php } ?>
              <button class="btn btn-danger buttonred buttons_menu basic-button-small" id="delete_po_btn"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete RO</button>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
  </nav>
  
  <section class="section-content py-6">
    <div class="container">
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
        
        <div class="panel panel-default">
            
            <div class="panel-body">
            
                
                <form action="" method="post" enctype="multipart/form-data" id="form-purchase-order">
                
                    <div class="table-responsive col-xl-12 col-md-12">
                      
                        <table id="receiving_order" class="table table-hover" data-classes="table table-hover promotionview">
                        
                            <thead>
                                <tr class="header-color">
                                    <th style="width: 1px;" class="text-center no-filter-checkbox"><input type="checkbox" id="main_checkbox" /></th>
                                    <th class="text-left text-uppercase">&nbsp;&nbsp;Status
                                      <div class="po-has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                                      </div>
                                    </th>
                                    <th class="text-left text-uppercase">&nbsp;&nbsp;PurchaseORD#
                                      <div class="po-has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                                      </div>
                                    </th>
                                    <th class="text-left text-uppercase">&nbsp;&nbsp;Invoice#
                                      <div class="po-has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                                      </div>
                                    </th>
                                    <th class="text-left text-uppercase sample">&nbsp;&nbsp;Total
                                      <div class="po-has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                                      </div>
                                    </th>
                                    <th class="text-left text-uppercase">&nbsp;&nbsp;Vendor
                                      <div class="po-has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                                      </div>
                                    </th>
                                    <th class="text-left text-uppercase">&nbsp;&nbsp;Order Type
                                      <div class="po-has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                                      </div>
                                    </th>
                                    <th class="text-left text-uppercase no-filter">Date Created</th>
                                    {{-- <th class="text-left">Date Received</th> --}}
                                    {{-- <th class="text-left"><a style="color: #fff;" href="<?php echo $data['sort_LastUpdate'];?>">Last Update</a></th> --}}
                                    <th class="text-left no-filter">Action</th>
                                </tr>
                            </thead>
                        
                        
                        </table>
                    
                    </div>
                </form>
                
            </div>
        </div>
    </div>
  </section>
</div>

<!-- Modal -->
<!-- changes in import EDI -->
<div id="myModalImportNew" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center">Import Invoice</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?php echo $data['import_invoice_new'];?>" method="post" enctype="multipart/form-data" id="form_import_invoice_new">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-danger" style="font-size: 10px;">* After import, any items in this invoice that are inactive will become active.</p>
                            <p class="text-danger" style="font-size: 10px;">* Only the first 100 items will be considered Per invoice.</p>
                            <!--<a href="/view/template/administration/import-edi-invoice-multiple-barcode.txt" download id="edi_invoice_download"></a>-->
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <span style="display:inline-block;width:8%;">File: </span> <span style="display:inline-block;width:85%;"><input type="file" name="import_invoice_file" id="import_edi_file" class="form-control adjustment-fields" required></span>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                          <div class="form-group">
                            <span style="display:inline-block;width:15%;">Vendor: </span> <span style="display:inline-block;width:75%;">
                            <select name="vvendorid" class="form-control adjustment-fields" id="vvendors" required>
                                <option></option>
                              <?php if(isset($data['vendors']) && count($data['vendors']) > 0){?>
                                <?php foreach($data['vendors'] as $vendor){?>
                                  <option value="<?php echo $vendor['isupplierid']; ?>"><?php echo $vendor['vcompanyname']; ?></option>
                                <?php } ?>
                              <?php } ?>
                            </select>
                            </span>
                          </div>
                        </div>
                        <div class="col-md-12" id="display_vendor_options">
                            
                        </div>
                        <div class="col-md-12 text-center">
                          <div class="form-group">
                            <input type="submit" class="btn btn-success buttons_menu" name="import_invoice" value="Import Invoice">&nbsp;<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                          </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

@endsection

@section('page-script')

<style>
    .disabled, #item_ellipsis{
        pointer-events:none;
    }
</style>

<style>
  .no-filter{
      padding-bottom: 45px !important;
  }

  .no-filter-checkbox{
      padding-bottom: 20px !important;
  }
</style>
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/purchaseorder.css') }}">

<script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>
<script type="text/javascript">

    $(document).on('submit', 'form#form_import_invoice_new', function(event) {
        event.preventDefault();
    
        $("div#divLoading").addClass('show');
        $('#myModalImportNew').modal('hide');
    
        var import_form_id = $('form#form_import_invoice_new');
        var import_form_action = import_form_id.attr('action');
        
        var import_formdata = false;
            
        if (window.FormData){
            import_formdata = new FormData(import_form_id[0]);
        }

        $.ajax({
            url : import_form_action,
            headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : import_formdata ? import_formdata : import_form_id.serialize(),
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
        }).done(function(response){
          
          var  response = $.parseJSON(response); //decode the response array

          if( response.code == 3 ) {//error
            $("div#divLoading").removeClass('show');
            // document.getElementById('edi_invoice_download').click();
            // alert(response.error);
            bootbox.alert({ 
              size: 'small',
              title: "Attention", 
              message: response.error, 
              callback: function(){}
            });
            
            return;
          }
            
          if( response.code == 1 ) {//success
            
            $("div#divLoading").removeClass('show');
            $('#successModal').modal('show');
            if(response.file_download == 1){
            //   document.getElementById('edi_invoice_download').click();
            //   document.getElementById('log_file_upc').click();
            }
            setTimeout(function(){
              window.location.reload();
              $("div#divLoading").addClass('show');
            }, 3000);
            
          } else if( response.code == 0 ) {//error
            $("div#divLoading").removeClass('show');
            // alert(response.error);
            bootbox.alert({ 
              size: 'small',
              title: "Attention", 
              message: response.error, 
              callback: function(){}
            });
            // return;
            
            setTimeout(function(){
              window.location.reload();
              $("div#divLoading").addClass('show');
            }, 3000);
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
          <p id="success_msg"><strong>Successfully Imported Invoice!</strong></p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="myModalImportMissingItem" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content bg-light">
      <div class="modal-header">
        <h4 class="modal-title text-center">Import Missing Items</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="<?php echo $data['import_missing_items'];?>" method="post" enctype="multipart/form-data" id="form_import_missing_items">
            @csrf
          <div class="row">
            <div class="col-md-8">
              <input name="itemsort_search" id="itemsort_search" placeholder="Search Item..." type="text" class="form-control">
            </div>
            <div class="col-md-3 text-right">
              <button class="btn btn-success basic-button-small  header-color" id="import_missing_item_btn">Import Items</button>
            </div>
          </div><br>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive" style="height:450px;">
                <?php if(isset($missing_items) && count($missing_items) > 0){?>
                  <table class="table tabler-hover promotionview" data-classes="table table-hover promotionview" id="missing_item_table">
                    <thead>
                      <tr class="header-color">
                        <th style="width: 1px; color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected_missing_items\']').prop('checked', this.checked);" /></th>
                        <th class="text-left">SKU#</th>
                        <th class="text-left">Item Name</th>
                        <th class="text-left">Invoice#</th>
                        <th class="text-left">Vendor</th>
                        <th class="text-left">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($missing_items as $k => $missing_item){?>
                        <tr>
                          <td>
                            <input type="checkbox" name="selected_missing_items[]" id="$missing_item[<?php echo $k; ?>][select]"  value="<?php echo $missing_item['imitemid']; ?>" />
                          </td>
                          <td class="text-left"><?php echo $missing_item['vbarcode']; ?></td>
                          <td class="text-left"><?php echo $missing_item['vitemname']; ?></td>
                          <td class="text-left"><?php echo $missing_item['vponumber']; ?></td>
                          <td class="text-left"><?php echo $missing_item['vvendorname']; ?></td>
                          <td class="text-left"><?php echo $missing_item['estatus']; ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                <?php }else{ ?>
                  <div class="alert alert-info text-center">
                    <strong>Sorry no data found!</strong>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default headerwhite basic-button-small" data-dismiss="modal" >Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="error_import_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">The following list of Barcodes items can not be import</h4>
      </div>
      <div class="modal-body">
        
          
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive" >
                <?php if(isset($error_import_barcode) && count($error_import_barcode) > 0){?>
                  <table class="table table-bordered tabler-hover" id="missing_item_table">
                    <thead>
                      <tr>
                        <th class="text-left">Invoice</th>
                        <th class="text-left">SKU#</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php foreach($error_import_barcode as $k => $error_barcode){ ?>
                            <?php if(!empty($error_barcode['error_barcode'])){ ?>
                                <tr>
                                    <td>{{ $error_barcode['invoice'] }}</td>
                                    <td>{{ $error_barcode['error_barcode'] }}</td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                  </table>
                <?php }else{ ?>
                  <div class="alert alert-info text-center">
                    <strong>Sorry no data found!</strong>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

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

  $(document).on('click', '#import_missing_item_btn', function(event) {
    event.preventDefault();

    var url = $('form#form_import_missing_items').attr('action');
    
    if($("input[name='selected_missing_items[]']:checked").length == 0){
      // alert('Please select items for import!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please select items for import!", 
        callback: function(){}
      });
      return false;
    }

    var data = {};
    $("input[name='selected_missing_items[]']:checked").each(function (i){
      data[i] = parseInt($(this).val());
    });

    $('#myModalImportMissingItem').modal('hide');
    $("div#divLoading").addClass('show');

    $.ajax({
      url : url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
      data : JSON.stringify(data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
    success: function(data) {
      $('#myModalImportMissingItem').modal('hide');
      $("div#divLoading").removeClass('show');
      $('#success_msg').html('<strong>'+ data.success +'</strong>');
      $('#successModal').modal('show');
      setTimeout(function(){
       window.location.reload();
       $("div#divLoading").addClass('show');
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
      $('#myModalImportMissingItem').modal('hide');
      $("div#divLoading").removeClass('show');
      $('#error_msg').html('<strong>'+ error_show +'</strong>');
      $('#errorModal').modal('show');
      return false;
    }
  });

  });
</script>


<script type="text/javascript">
  $(document).on('submit', '#form_order_search', function(event) {
    $("div#divLoading").addClass('show');
  });
</script>
<script type="text/javascript">
  $(document).ready(function($) {
    $("div#divLoading").addClass('show');
  });

  // $(window).load(function() {
  //   $("div#divLoading").removeClass('show');
  // });
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
       
        $('#success_msg').html('<strong>'+ data.success +'</strong>');
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
  
    $(document).on('change', '#vvendors', function(){
        var vendor = $('#vvendors').val();
        var url = '<?php echo $data["get_vendor_data"] ; ?>';
        url = url.replace(/&amp;/g, '&');
        $.ajax({
            url : url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : {vendor:vendor},
            type : 'get',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                
                let html =  "<h5>Selected Vendor Feture List</h5>";
                                html += "<ul>";
                                
                                if(data.vendor_format == 'FEDWAY'){
                                    html += "<li>FEDWAY</li>";
                                    
                                }else if(data.vendor_format == 'ALLEN BROTHERS'){
                                    html += "<li>ALLEN BROTHERS</li>";
                                    
                                }else if(data.vendor_format == 'CORE MARK'){
                                    html += "<li>CORE MARK</li>";
                                    
                                }else if(data.vendor_format == 'RESNICK'){
                                    html += "<li>RESNICK</li>";
                                }else{
                                    html += "<li>OTHERS</li>";
                                }
                                
                                if(data.upc_convert == 'A'){
                                    
                                    html += "<li>UPC-E to UPC-A</li>";
                                    
                                    if(data.remove_first_digit == 'Y'){
                                        
                                        html += "<li>Remove First Digit</li>";
                                        
                                    }
                                    
                                    if(data.remove_last_digit == 'Y'){
                                        
                                        html += "<li>Remove Last Digit</li>";
                                        
                                    }
                                    
                                }else if(data.upc_convert == 'E'){
                                    
                                    html += "<li>UPC-A to UPC-E</li>";
                                    
                                    if(data.check_digit == 'Y'){
                                        
                                        html += "<li>With Check Digit</li>";
                                        
                                    }
                                    if(data.remove_first_digit == 'Y'){
                                        
                                        html += "<li>Remove First Digit</li>";
                                        
                                    }
                                    
                                    if(data.remove_last_digit == 'Y'){
                                        
                                        html += "<li>Remove Last Digit</li>";
                                        
                                    }
                                    
                                }else{
                                    html += "<li>NONE</li>";
                                    
                                    if(data.check_digit == 'Y'){
                                        
                                        html += "<li>With Check Digit</li>";
                                        
                                    }
                                    if(data.remove_first_digit == 'Y'){
                                        
                                        html += "<li>Remove First Digit</li>";
                                        
                                    }
                                    
                                    if(data.remove_last_digit == 'Y'){
                                        
                                        html += "<li>Remove Last Digit</li>";
                                        
                                    }
                                }
                                
                             html += "</ul>";
                if(vendor == ''){
                    $('#display_vendor_options').html('');
                }else{
                    $('#display_vendor_options').html(html);
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

    .edit_btn_rotate{
      line-height: 0.5;
      border-radius: 6px;
    }
    
  </style>

<script>
  var url = "<?php echo $data['current_url'];?>";

  $('#receiving_order thead tr th').each( function (i) {
      
      $( this ).on( 'keyup', '.table-heading-fields', function () {

            var self = this;
            if ( table.column(i).search() !== self.value ) {
                
                
                table
                    .column(i)
                    .search( self.value )
                    .draw();
                    
                $("div#divLoading").addClass('show');
                
            }
      });
      
  });

  

  var showPaginationPrevNextButtons = false;
  var table =   $("#receiving_order").DataTable({
      "bSort": false,
      // "scrollY":"300px",
      // "autoWidth": true,
      "fixedHeader": true,
      "processing": true,
      "iDisplayLength": 20,
      "serverSide": true,
      "bLengthChange": false,
      "aoColumnDefs": [
          { "sWidth": "190px", "aTargets": [ 7 ] },
          
      ],
      //"autoWidth": true,

      "language": {
          search: "_INPUT_",
          searchPlaceholder: "Search..."
      },
      // "fnPreDrawCallback": function( oSettings ) {
      
      //     main_checkbox = $('#main_checkbox').is(":checked");
  
      // },
      "dom": 't<"bottom col-md-12 row"<"col-md-3"i><"col-md-9"p>>',
      "ajax": {
      url: url,
      headers: {
              'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
      },
      "data": function(d){
          d["m_check"] = $('#main_checkbox').is(":checked");
          
      },
      type: 'POST',
      "dataSrc": function ( json ) {
              
              if(json.data.length != 0){
              $(".bottom").show();  
              
              } else {
              $(".bottom").hide(); 
              
              }
              
              return json.data;
          } 
      },
      
      columns :  [
                  {
                      data: "iroid", render: function(data, type, row){
                          
                          return $("<input>").attr({
                                  // checked: !uncheckedBoxes[data],
                                  type: 'checkbox',
                                  class: "iroid",
                                  value: data,
                                  name: "selected[]",
                                  "data-order": data,
                          })[0].outerHTML;
                          
                      }
                  },
                  { "data": "estatus"},
                  
                  { "data": "vponumber"},
                  { "data": "vinvoiceno"},
                  { "data": "nnettotal"},
                  { "data": "vvendorname"},
                  {"data": "vordertype"},
                  {"data": "dcreatedate"},
                  
                  { "data": "view_edit", render: function(data, type, row){
                          return "<a href="+data+" data-toggle='tooltip' title='View' class='btn btn-sm btn-info edit_btn_rotate header-color ' ><i class='fa fa-eye'></i>&nbsp;&nbsp;View</a>";
                    }
                },
                  
          ],
          rowCallback: function(row, data, index){
              
      },
      fnDrawCallback : function() {
              if ($(this).find('tbody tr').length<=1) {
                  $(this).find('.dataTables_empty').hide();
              }
              
              // $(this).removeClass('promotionview');
              $(this).addClass('promotionview');
              console.log("check")
      }
  }).on('draw', function(){
              if($('#main_checkbox').prop("checked") == true){
                  
                  $('.iitemid').prop('checked', true);
              } else{ 
                  $('.iitemid').prop('checked', false);   
              }
              // console.log($(this).find('tbody tr .iitemid').length);
          if ($(this).find('tbody tr .iitemid').length>0) {
              $('#buttonEditMultipleItems').prop('disabled', false);
          }
          $("div#divLoading").removeClass('show');
          
  });

  $("#receiving_order_paginate").addClass("pull-right");
</script>
  
@endsection