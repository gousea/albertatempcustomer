@extends('layouts.layout')

@section('title')
    Promotion
@endsection

@section('main-content')

<div id="content">
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase" > Promotion</span>
                </div>
                <div class="nav-submenu">
                    <a href="/addpromotion" title="Add New" class="btn btn-gray headerblack  buttons_menu"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a> 
                    <button type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" id="delete_btn" style="border-radius: 0px;"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;Delete</button>
                </div>
            </div> 
        </div>
    </nav>
    
    <section class="section-content py-6">
        <div class="container">
            @if (session()->has('message'))
                <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>      
            @endif
            <table id="table_promotions" class="table table-hover promotionview" style="width: 100%;">
                <thead>
                    <tr class="header-color">
                        <th data-field="state" data-checkbox="true">
                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                        </th>
                        
                        <th class="text-left text-uppercase">&nbsp;&nbsp;Promotion Name
                            <div class="po-has-search">
                              <span class="fa fa-search form-control-feedback"></span>
                              <input type="text" class="form-control table-heading-fields text-center search_text_box" name="prom_name" id="id="check"" placeholder="SEARCH"  >
                            </div>
                        </th>
                        
                        <th class="text-left text-uppercase">&nbsp;&nbsp;Code
                            <div class="po-has-search">
                              <span class="fa fa-search form-control-feedback"></span>
                              <input type="text" class="form-control table-heading-fields text-center search_text_box" name="prom_code" placeholder="SEARCH" >
                            </div>
                        </th>
                        
                        <th class="text-left text-uppercase" style="width:20% !important;">Type
                            <div class="adjustment-has-search" id="header_type">
                              
                            </div>
                        </th>
                        
                        <th class="text-left text-uppercase">Category
                            <div class="adjustment-has-search">
                                <select class='table-heading-fields' name="prom_category" id="prom_category">
                                    <option value="">All</option>
                                    <option value="Time Bound"  >Time Bound</option>
                                    <option value="Stock Bound" >Stock Bound</option>
                                    <option value="Open Ended" >Open Ended (On Going)</option>
                                </select>
                            </div>
                        </th>
                        
                        <th class="text-left text-uppercase">Status
                            <div class="adjustment-has-search">
                                <select class='table-heading-fields' name="prom_status" id="prom_status">
                                    <option value="">All</option>
                                    <option value="Active"  >Active</option>
                                    <option value="Closed" >Closed</option>
                                    
                                </select>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
  

<style>

    .select2-container .select2-selection--single{
        height:33px !important;
        font-size:10px;!important;
    }
      
    .select2-selection{
        border-radius: 7px !important;
    }
</style>

<?php if(session()->get('hq_sid') == 1){ ?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h5 class="modal-title">Select the stores in which you want to delete the items:</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
         <table class="table promotionview">
            <thead id="table_green_header_tag">
                <tr class="header-color">
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
        <button type="submit" id="save_btn" class="btn btn-danger buttonred buttons_menu basic-button-small" data-dismiss="modal">Delete</button>
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
    </div>
  </div>
</div>
<?php } ?>
@endsection

@section('page-script')
    
    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/purchaseorder.css') }}">
    
    <link type="text/css" href="/stylesheet/select2/css/select2.min.css" rel="stylesheet" />
    <script src="/javascript/select2/js/select2.min.js"></script>

    <script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>


<script type="text/javascript">
  
  
    $(document).ready( function () {
        var search_url = '/searchpromotion';
        search_url = search_url.replace(/&amp;/g, '&');
        
        var edit_url = '/editpromotion';
        edit_url = edit_url.replace(/&amp;/g, '&');
        
        var promotion_types = {"1":"Buy N get discount","2":"Buy X get Y at discount","3":"Buy X Get Y Free","4":"Buy first X at discount during a period","5":"X $ off on Y $ Sale Value","6":"N% off on entire sale Value with a limit on the total invoice \/ sale value","7":"N% off on sale value","8":"Product X is discounted on Total Bill Value is > Y","9":"Buy 3 different products get least priced one free","10":"Slab Price","11":"Grouped Item with Customised Price.","12":"Manufacturer Buy Down", "13": "RJR Scan Data", "14": "Altria Scan Data"};
        var types_length  = Object.keys(promotion_types).length;
        
        $('#table_promotions thead tr th').each( function (i) {
            
            var title = $(this).text();
            title = title.trim();
            
            if(title == "Type")
            { 
                var filterList = "";
                for(var j=1; j<=types_length; j++)
                {
                    filterList += '<option value="'+j+'" >'+promotion_types[j]+'</option>';
                }
                
                $('#header_type').html('<select class="table-heading-fields" name="prom_type" id="prom_type">'+
                                '<option value="">All</option>'+
                                filterList+
                            '</select>');
            }
            
            
     
            $( '.search_text_box', this ).on( 'keyup change', function () {
                
                var self = this;
                
                if(self.value != ''){
                    $(this).closest('div').find('.fa-search').hide();
                    
                }else{
                    $(this).closest('div').find('.fa-search').show();
                }
                
                if ( table.column(i).search() !== this.value ) {
                    table
                    .column(i)
                    .search( this.value )
                    .draw();
                }
            });
            
            $( 'select', this ).on( 'change', function () {
                console.log('check');
                if ( table.column(i).search() !== this.value) {
                    table
                    .column(i)
                    .search( this.value )
                    .draw();
                }
            });

        });
     
        var table =   $("#table_promotions").DataTable({
            "bLengthChange" : false,
            "serverSide": true,
            "iDisplayLength": 20,
            "ordering": false,
            // "scrollX": true,
            // "searching": false,
            "autoWidth": false,
            // "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0,1,2, 3,4,5 ] }],
            "dom": 't<"bottom col-md-12 row"<"col-md-3"i><"col-md-9"p>>',
            "ajax": {
                url: search_url,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                type: 'POST',
                "dataSrc": function ( json ) {
                    $("div#divLoading").removeClass('show');
                    return json.data;
                } 
            },
                
            columns: [
                    { 
                        data: "prom_id", render: function(data, type, row){
                            return $("<input>").attr({
                            type: 'checkbox',
                            class: "prom_id",
                            value: data,
                            name: "selected[]",
                            "data-order": data
                        })[0].outerHTML;
                    }
                    },
                    { "data": "prom_name", render: function(data, type, row){
                        appended_url = edit_url+'?prom_id='+row.prom_id;
                        return '<a href="'+appended_url+'">'+data+'</a>';
                    }},
                    { "data": "prom_code"},
                    
                    { 
                        data: 'prom_type_id', 
                        render: function(data) {
                        for(var i = 1; i <= types_length; i++)
                        {
                            if(data == i)
                            {
                                // console.log(promotion_types);
                                return promotion_types[i];break;
                            }
                        }
                        },
                },
                    { "data": "category"},
                    { "data": "status"},
                ],
                
                
                //  dom: 'Bfrtip',
                // buttons: [
                //     {
                //         extend: 'print',
                //         customize: function ( win ) {
                //             $(win.document.body)
                //                 .css( 'font-size', '10pt' )
                //             $(win.document.body).find( 'table' )
                //                 .addClass( 'compact' )
                //                 .css( 'font-size', 'inherit' );
                //         },header: true
                //     },
                    
                //     { extend: 'excelHtml5', header: true },
                //     { extend: 'csvHtml5', header: true },
                //     { extend: 'pdfHtml5', exportoptions: {
                //           header: true,
                //           footer: true
                //         }}
                // ]
        });
        $("#items_status").select2();
        $("#prom_type").select2();
        $("#prom_category").select2();
        $("#prom_status").select2();
        $("#table_promotions_filter").hide();
        $("#table_promotions_paginate").addClass("pull-right");
    });
    var dataItemOrders = [];
    
    $(document).on('click', '#delete_btn', function(event) {
        event.preventDefault();
        
        if($("input[name='selected[]']:checked").length == 0){
            bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: 'Please Select to Delete!', 
                callback: function(){}
            });
            return false;
        }
        
        $('#deleteItemModal').modal('show');
    });
    
    $(document).on('click', 'input[name="deleteItems"]', function(event) {
        event.preventDefault();
        $('.prom_id').filter(':checked').each(function(){
             dataItemOrders.push($(this).data('order'));
         });
        <?php if(session()->get('hq_sid') == 1) { ?>
            $('#deleteItemModal').modal('hide');
            $.ajax({
                    url : "<?php echo url('/duplicatehqPermission'); ?>",
                    data : JSON.stringify(dataItemOrders),
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    type : 'POST',
                    contentType: "application/json",
                    dataType: 'json',
                    success: function(result){
                            var popup = '';
                            @foreach (session()->get('stores_hq') as $stores)
                                if(result.includes({{ $stores->id }})){
                                    var data = '<tr>'+
                                                    '<td>'+
                                                        '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                            '<input type="checkbox" class="checks check stores" disabled id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
                                                '</tr>';
                                            $('#selectAllCheckbox').attr('disabled', true);
                                      
                                } else {
                                    var data = '<tr>'+
                                                    '<td>'+
                                                        '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                            '<input type="checkbox" class="checks check stores"  id="else_hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                '</tr>';
                                }
                                popup = popup + data;
                            @endforeach
                            // alert(popup);
                    $('#data_stores').html(popup);
                }
            });
            $("#myModal").modal('show');
        <?php } else { ?>
         
                $('.prom_id').filter(':checked').each(function(){
                     dataItemOrders.push($(this).data('order'));
                 });
                var delete_url = '/deletepromotion';
                delete_url = delete_url.replace(/&amp;/g, '&');
                
                $('#deleteItemModal').modal('hide');
                $("div#divLoading").addClass('show');
                
                 $.ajax({
                    url : delete_url,
                    data : JSON.stringify(dataItemOrders),
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    type : 'POST',
                    contentType: "application/json",
                    dataType: 'json',
                    success: function(data) {
                        $('#success_msg').html('<strong>'+ data.success +'</strong>');
                        $("div#divLoading").removeClass('show');
                        $('#successModal').modal('show');
                         window.location.reload();
                        // setTimeout(function(){
                        //  $('#successModal').modal('hide');
                        // }, 3000);
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
        <?php }?>
    });
</script>

<script>
        var stores = [];
        
        stores.push("{{ session()->get('sid') }}");
        $('#selectAllCheckbox').click(function(){
            if($('#selectAllCheckbox').is(":checked")){
                $(".stores").prop( "checked", true );
            }else{
                $( ".stores" ).prop("checked", false );
            }
        });
        
        $("#save_btn").click(function(){
            var dataItemOrders = [];
            $.each($("input[name='stores']:checked"), function(){            
                stores.push($(this).val());
            });
            
            $('.prom_id').filter(':checked').each(function(){
                     dataItemOrders.push($(this).data('order'));
            });
            
            var delete_url = '/deletepromotion';
            delete_url = delete_url.replace(/&amp;/g, '&');
            
            var d = JSON.stringify({ data: dataItemOrders, stores_hq:stores});
            $('#deleteItemModal').modal('hide');
            $("div#divLoading").addClass('show');
            
            $.ajax({
                url : delete_url,
                data : d,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                type : 'POST',
                contentType: "application/json",
                dataType: 'json',
                success: function(data) {
                    $('#success_msg').html('<strong>'+ data.success +'</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#successModal').modal('show');
                     window.location.reload();
                    // setTimeout(function(){
                    //  $('#successModal').modal('hide');
                    // }, 3000);
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

<div id="deleteItemModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you Sure</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <input type="submit" class="btn btn-danger buttonred buttons_menu basic-button-small" name="deleteItems" value="Delete">
      </div>
    </div>

  </div>
</div>

<div class="modal fade" id="SuccessModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="alert alert-success text-center">
            <p><b>Promotion Deleted Successfully</b></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  
@endsection