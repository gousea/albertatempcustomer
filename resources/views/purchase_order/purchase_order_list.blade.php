@extends('layouts.layout')
@section('title', 'Purchase Order')
@section('main-content')

<div id="content">
    
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Purchase Orders</span>
                </div>
                <div class="nav-submenu">
                    
                    <a type="button" href="<?php echo $data['add']; ?>" title="Add (Manual)" class="btn btn-gray headerblack buttons_menu add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New (Manual)</a>  
                    <a type="button" href="<?php echo $data['po_sales_history']; ?>" title="Add (Sales History)" class="btn btn-gray headerblack basic-button-medium add_new_btn_rotate" style="color:black;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New (Sales History)</a>  
                    <a type="button" href="<?php echo $data['po_par_level']; ?>" title="Add (PAR Level)" class="btn btn-gray headerblack basic-button-medium add_new_btn_rotate" style="color:black;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New (PAR Level)</a>  
                    <button class="btn btn-danger buttonred buttons_menu basic-button-small" id="delete_po_btn"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete PO</button>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
      </nav>

        <section class="section-content py-6">
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
                    
                    <div class="panel-body">
                        
                        <form action="" method="post" enctype="multipart/form-data" id="form-purchase-order">
                        
                            <div class="table-responsive">
                                <table id="purchase_order" class="table table-hover" data-classes="table table-hover table-condensed promotionview"
                                    data-row-style="rowColors" data-striped="true" data-click-to-select="true">
                                
                                    <thead>
                                        <tr class="header-color">
                                            <th style="width: 1px;" class="text-center"><input type="checkbox" id="main_checkbox" /></th>
                                            <th class="text-left">Status</th>
                                            <th class="text-left">PurchaseORD#</th>
                                            <th class="text-left">Invoice#</th>
                                            <th class="text-left sample">Total</th>
                                            <th class="text-left">Vendor Name</th>
                                            <th class="text-left">Order Type</th>
                                            <th class="text-left">Date Created</th>
                                            {{-- <th class="text-left">Date Received</th> --}}
                                            {{-- <th class="text-left"><a style="color: #fff;" href="<?php echo $data['sort_LastUpdate'];?>">Last Update</a></th> --}}
                                            <th class="text-left">Action</th>
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


@endsection

@section('page-script')

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/purchaseorder.css') }}">

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

    $(window).on('load', function() {
        $("div#divLoading").removeClass('show');
    });
</script>

<script type="text/javascript">
    $(document).on('click', '#main_checkbox', function(event) {
        console.log($(this).prop('checked')+"jhjh")
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

    <script>
        var url = "<?php echo $data['current_url'];?>";

        $('#purchase_order thead tr').clone(true).removeAttr('style').appendTo( '#purchase_order thead' );
        $('#purchase_order thead tr:eq(1) th').each( function (i) {
            
            var title = $(this).text();
            
            // console.log(title);
            if(i == 0)
            {
                $(this).html( '' );
            }
            
            else if(title == "Status")
            {
                $(this).html('<div class="form-group po-has-search"><span class="fa fa-search form-control-feedback"></span><input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" ></div>')
            }
            else if(title == "PurchaseORD#")
            {
                $(this).html('<div class="form-group po-has-search"><span class="fa fa-search form-control-feedback"></span><input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no"></div>')
            }
            else if(title == "Invoice#")
            {
                $(this).html('<div class="form-group po-has-search"><span class="fa fa-search form-control-feedback"></span><input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no"></div>')
            }
            else if(title == 'Total')
            {
                $(this).html('<div class="form-group po-has-search"><span class="fa fa-search form-control-feedback"></span><input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no"></div>')
            }
            else if(title == 'Vendor Name')
            {
                $(this).html('<div class="form-group po-has-search"><span class="fa fa-search form-control-feedback"></span><input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no"></div>')
            }
            else if(title == 'Order Type')
            {
                $(this).html('<div class="form-group po-has-search"><span class="fa fa-search form-control-feedback"></span><input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no"></div>')
            } 
            else
            {
                $(this).html( '' );
            }
            
            
            
        });
    

        var showPaginationPrevNextButtons = false;
        var table =   $("#purchase_order").DataTable({
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
                            data: "ipoid", render: function(data, type, row){
                                
                                return $("<input>").attr({
                                        // checked: !uncheckedBoxes[data],
                                        type: 'checkbox',
                                        class: "ipoid",
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
                                return "<a href="+data+" data-toggle='tooltip' title='View' class='btn btn-sm btn-info edit_btn_rotate' ><i class='fa fa-eye'></i>&nbsp;&nbsp;View</a>";
                          }
                      },
                        
                ],
                rowCallback: function(row, data, index){
                    
            },
            fnDrawCallback : function() {
                    if ($(this).find('tbody tr').length<=1) {
                        $(this).find('.dataTables_empty').hide();
                    }
                    
                    $(this).addClass('promotionview');
                    
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

        $("#purchase_order_paginate").addClass("pull-right");
    </script>
  
  @endsection