@extends('layouts.master')

@section('title', 'Items')

@section('main-content')


<div id="content">
    <style>
     span.select2-container{
        width: 100% !important;  
        min-width:125px; !important;
    }
    #items_status + span.select2-container{
        max-width: 20%;  
    }
    thead input {
        width: 100%;
    }
    
     .table.table-bordered.table-striped.table-hover thead > tr{
     	background: #03a9f4 none repeat scroll 0 0 !important;
     }
     
     table tbody tr:nth-child(even) td{
    	background-color: #f05a2814;
    }
    
    
    
</style>
  <div class="page-header">
    <div class="container-fluid">
     
      
      <ul class="breadcrumb">
        @foreach ($data['breadcrumbs'] as $breadcrumb)
            <li><a href="{{ $breadcrumb['href'] }}">{{ $breadcrumb['text'] }} </a></li>
        @endforeach
      </ul>
    </div>
  </div>
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
    <div class="panel panel-default itemsData">
      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i> Items </h3>
        
      </div>
      <div class="panel-body">
        <div class="row" style="padding-bottom: 15px;">
            
            <div class="col-md-8 pull-right">
                <b>Show &nbsp</b> <select name="items_status" id="items_status">
                        <option value="All" <?php echo ($data['show_items']=='ALL')? "selected" : "";?>>All</option>
                        <option value="Active" <?php echo ($data['show_items']=='Active')? "selected" : "";?>>Active</option>
                        <option value="Inactive" <?php echo ($data['show_items']=='Inactive')? "selected" : "";?>>Inactive</option>
                    </select>
                <span>
                    
                    <!--<input type="checkbox" name="disable_checkbox" @if($show_items ?? '' == 'Inactive') checked @endif >&nbsp;&nbsp;<b>Show Disable Items</b>-->
                </span>&nbsp;&nbsp;
                <!--<a href="<?php echo $data['current_url']; ?>" title="Show All Items" class="btn btn-primary show_all_btn_rotate"><i class="fa fa-eye"></i>&nbsp;&nbsp;Show All Items</a>-->
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importItemModal"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;Import Items</button>
              <a href="{{ $data['add'] }}" title="Add New Item" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a> 
                
                <!--<button type="button" class="btn btn-primary" id="calculate_reorderpoint" style="border-radius: 0px;"><i class="fa fa-calculator"></i>&nbsp;Reorder Points</button>-->
                <button type="button" class="btn btn-danger" id="delete_btn" style="border-radius: 0px;"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;Delete Items</button>

            </div>
          

        </div>
        
               
    
         <div class="box-body table-responsive">
		            <table id="item_listing" class="table table-bordered table-striped table-hover" style="font-size:11px;">
		                <thead>
                            <!--<tr style="background:#03a9f4;">
                                <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" style="color: black;"/></th>
                                <th>ITEM NAME</th>
                                <th>ITEM TYPE</th>
                                <th>SKU</th>
                                <th>DEPT.</th>
                                <th>CATEGORY</th>
                                <th>PRICE</th>
                                <th>QTY. ON HAND</th>
                            </tr>-->
                            <?php $dynamic_data = [];?>
                            <tr>
                              <th style="width: 1px;color:black;" class="text-center no-sort"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                              <!-- <th class="text-left text-uppercase no-sort"><a href="{{ $data['item_sorting'] }}" style="color: #fff;" class="show_all_btn_rotate">Item Name</a></th> -->
                              <th class="text-left text-uppercase"><a class="btn" id="sorting" style="color:white;">Item Name</a></th>
                              <th class="text-left text-uppercase no-sort">Item Type</th>
                              <?php $dynamic_data[] = "vitemtype";?>
                              <th class="text-left text-uppercase no-sort">SKU</th>
                              <?php $dynamic_data[] = "vbarcode";?>

                              
                              <?php if(isset($data['itemListings'])){ ?>
                              
                                <?php foreach($data['itemListings'] as $m => $itemListing){ ?>
                                   <th class="text-left text-uppercase no-sort"><?php echo $data['title_arr'][$m];?></th>
                                   <?php if($m == 'vcategorycode'){
                                        $dynamic_data[] = "vcategoryname";
                                        }else if($m ==  'vdepcode'){
                                            $dynamic_data[] = "vdepartmentname";
                                        }else if($m ==  'vsuppliercode'){
                                            $dynamic_data[] = "vcompanyname";
                                        }
                                        else if($m ==  'vitemcode'){
                                            $dynamic_data[] = "iitemid_v";
                                        }
                                        else if($m =='dcostprice'){
                                            $dynamic_data[] = "dcostprice";
                                        }
                                        else if($m == 'gross_profit'){
                                            $dynamic_data[] = "gross_profit";
                                        }
                                        else{
                                            $dynamic_data[] = $m;
                                        } ?>
                                  <?php } ?>
                              <?php } else { ?>
                                <th class="text-left text-uppercase no-sort">Dept.</th>
                                <?php $dynamic_data[] = "vdepartmentname";?>
                                
                                <th class="text-left text-uppercase">Category</th>
                                <?php $dynamic_data[] = "vcategoryname";?>
                                <th class="text-left text-uppercase">Supplier</th>
                                <?php $dynamic_data[] = "vcompanyname";?>
                                <th class="text-right text-uppercase no-sort">Price</th>
                                <?php $dynamic_data[] = "dunitprice";?>
                                <th class="text-right text-uppercase no-sort">Qty. on Hand</th>
                                <?php $dynamic_data[] = "iqtyonhand";?>
                              <?php } ?>
                              
                              
                            </tr>
                        </thead>
                        
	                    
		            </table>
		        </div><!-- /.box-body -->
                <input type="hidden" id = "sorting_value" value="">
      </div>
    </div>
  </div>
</div>

@endsection


@section('scripts')

<style>
    .disabled, #item_ellipsis{
        pointer-events:none;
    }
</style>

<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<link type="text/css" href="{{ asset('stylesheet/select2/css/select2.min.css') }}" rel="stylesheet" />


<!-- DataTables -->
<script src="{{ asset('javascript/jquery.dataTables.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('javascript/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('javascript/select2/js/select2.min.js') }}" type="text/javascript"></script>

<script>
    
    $(document).ready(function() {
        
        var sorting_count = 0;
        var sorting_value = 'asc';
        
        $(document).on("change","#dept_code",function(){
            var get_category_ajax;
            if($(this).val() != "")
            {
                $('#category_code').attr("placeholder", "Loading...");
                var get_categories_url = "<?php echo $data['get_categories_url']; ?>";
                get_categories_url = get_categories_url.replace(/&amp;/g, '&');
                
                var get_department_items_url = "<?php echo $data['get_department_items_url']; ?>";
                // get_department_items_url = get_department_items_url.replace(/&amp;/g, '&');
                var dep_code = [$(this).val()];
                
                if(get_category_ajax && get_category_ajax.readyState != 4 ){
                    get_category_ajax.abort();
                }
                
                get_category_ajax = $.ajax({
                    url: get_categories_url,
                    headers: {
                                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                      },
                    type: 'post',
                    data : {dep_code : dep_code},
                    success:function(data){
                        if(data)
                        {
                            $('#category_code').attr("placeholder", "Select Category");
                            $( '#category_code' ).html( data );
                            $('#category_code').prop("disabled", false);
                        }
                        else
                        {
                            $( '#category_code' ).html( '' );
                            $('#category_code').prop("disabled", true);
                        }
                        
                    }
                })
            }
        });
        
        var url = "<?php echo $data['searchitem']; ?>";
        url = url.replace(/&amp;/g, '&');
        
        var edit_url = "<?php echo $data['edit']; ?>";
        edit_url = edit_url.replace(/&amp;/g, '&');
        
        var departments = "<?php echo $data['departments'];?>";
        
        $('#item_listing thead tr').clone(true).appendTo( '#item_listing thead' );
        $('#item_listing thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            if(title == "Item Name"){
                $(this).html( '<input type="text" name="item_search" class="search_text_box" placeholder="Search" style="color:black;border-radius: 4px;height:28px;"/>' );
            }
            else if(title == "SKU")
            {
                $(this).html( '<input type="text" name="sku_search" class="search_text_box" placeholder="Search" style="color:black;border-radius: 4px;height:28px;"/>' );
            }
            
            else if(title == "Dept.")
            {
                $(this).html(departments)
            }
            else if(title == "Category")
            {
                $(this).html('<select class="form-control" name="category_code" id="category_code" style="width: 100px;"></select>')
            }
            
            else if(title == "Supplier")
            {
                  $(this).html( '<input type="text" name="supplier_search" class="search_text_box" placeholder="Search" style="color:black;border-radius: 4px;height:28px;"/>' );
            }
            else{
                $(this).html( '' );
            }
                
     
            // $( '.search_text_box', this ).on( 'keyup change', function () {
            //     if ( table.column(i).search() !== this.value ) {
            //         table
            //             .column(i)
            //             .search( this.value )
            //             .draw();
            //     }
            // } );
            
            
            var timer;
            
            $( '.search_text_box', this ).on( 'change', function () {  //=====for correct working order this should be work on "onchange" only==
                
                var self = this
                clearTimeout(timer);
                timer = setTimeout(function () {
                    if ( table.column(i).search() !== self.value ) {
                    table
                        .column(i)
                        .search( self.value )
                        .draw();
                }
                },100);
                sorting_count = 0;
                sorting_value = 'asc';
                $('#sorting_value').val('default');
            } );
            
            $( 'select', this ).on( 'change', function () {
                if(i == 4)
                {
                    table
                        .column(5)
                        .search( '' )
                        .draw();
                }
                if ( table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
                sorting_count = 0;
                sorting_value = 'asc';
                $('#sorting_value').val('default');
            } );
            
        } );
        
        //======1st click - asc, 2nd click - desc, 3rd click - default ==========
        $('#sorting').on('click', function() {
            
            sorting_count = sorting_count + 1; console.log(sorting_count % 3 != 0);
            if(sorting_count % 3 != 0){
                $('#sorting_value').val(sorting_value);
                table
                    // .order([1, sorting_value])
                    .draw();
            }else{
                $('#sorting_value').val('default');
                table
                    // .order([1, 'default'])
                    .draw();
            }
            
            if(sorting_value == 'asc'){
                sorting_value = 'desc';
            }else{
                sorting_value = 'asc';
            }
            
            if(sorting_count % 3 == 0){
                sorting_value = 'asc';
            }
        });
            
        $("#dept_code").select2({closeOnSelect:true,placeholder: 'Select Department'});
        $("#category_code").select2({closeOnSelect:true,placeholder: 'Select Category'});
        $("#items_status").select2();
            
        var dynamic_data = JSON.parse('<?php echo json_encode($dynamic_data);?>');
        var data_array = [];
        $.each(dynamic_data, function(key,value) {    
             data_array.push({ "data": value });
          });  
         data_array.unshift({data: "iitemid", render: function(data, type, row){
                                return $("<input>").attr({
                                    type: 'checkbox',
                                    class: "iitemid",
                                    value: data,
                                    name: "selected[]",
                                    "data-order": data
                                })[0].outerHTML;
                            }},
                            { "data": "vitemname", render: function(data, type, row){
                                new_edit_url = edit_url+'/'+row.iitemid;
                                return '<a href="'+new_edit_url+'">'+data+'</a>';
                            }});
        var table =   $("#item_listing").DataTable({
                    "bSort": false,
                    // "ordering": true,
                    //     columnDefs: [{
                    //     orderable: false,
                    //     targets: "no-sort"
                    // }],
                    "autoWidth": false,
                    "fixedHeader": true,
                    "processing": true,
                    "iDisplayLength": 20,
                    "serverSide": true,
                    "bLengthChange": false,
                    "language": {
                        search: "_INPUT_",
                        searchPlaceholder: "Search..."
                    },
                    "dom": '<"mysearch"lf>rt<"bottom"ip>',
                    "ajax": {
                      url: url,
                      headers: {
                                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                      },
                      type: 'POST',
                      data: function(d){
                            d.sorting_value = $('#sorting_value').val();
                        },
                      "dataSrc": function ( json ) {
                            $("div#divLoading").removeClass('show');
                            return json.data;
                        } 
                    },
                    columns : data_array,
                    
                });
            
        $("#item_listing_processing").remove();
        $("#item_listing_filter").hide();
    });
    
</script>

<!-- Modal -->
<div id="importItemModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Items</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="{{ $data['import_items'] }}" id="form_item_import">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <span style="display:inline-block;width:15%;">Separated By: </span> <span style="display:inline-block;width:80%;">
                <input type="radio" name="separated_by" value="comma" checked="checked">&nbsp;&nbsp;Comma&nbsp;&nbsp;
                <input type="radio" name="separated_by" value="pipe">&nbsp;&nbsp;Pipe
              </span>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <span style="display:inline-block;width:15%;">File: </span> <span style="display:inline-block;width:80%;"><input type="file" name="import_item_file" class="form-control" required></span>
            </div>
          </div>
          <div class="col-md-12 text-center">
            <div class="form-group">
              <input type="submit" value="Import" class="btn btn-success">&nbsp;
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success text-center">
          <p id="success_msg"><strong>Items Imported Successfully!</strong></p>
        </div>
        <div class="text-center" style="display:none;">
          <a id="status_file" href="{{ url("/item/get_status_import") }}" download="import-item-status-report.txt">Status of Imported File</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="errorModal" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger text-center">
          <p id="error_msg"><strong>Items Imported Successfully!</strong></p>
        </div>
       
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).on('submit', 'form#form_item_import', function(event) {
    event.preventDefault();

    $("div#divLoading").addClass('show');
    $('#importItemModal').modal('hide');

    var import_form_id = $('form#form_item_import');
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
        }).done(function(response){ console.log(response.code);
        //   var  response = $.parseJSON(response); //decode the response array
          if( response.code == 1 ) {//success

            $("div#divLoading").removeClass('show');
            $('#successModal').modal('show');
            $('#status_file')[0].click();
            
            /*setTimeout(function(){
              window.location.reload();
            }, 3000);*/
            
          } else if( response.code == 0 ) {//error
            $("div#divLoading").removeClass('show');
            alert(response.error);
            return;
          }
      
      });
  });


$('#items_status').change(function() {
      var current_url = "<?php echo $data['current_url']; ?>";
      current_url = current_url.replace(/&amp;/g, '&');
      
      var val = $(this).val();
          current_url = current_url+'/'+val+'/DESC';
          window.location.href = current_url;
          $("div#divLoading").addClass('show');
        
    });

  $(document).on('keyup, keypress', '#automplete-product', function(event) {
    if(event.keyCode == 13){

      $("div#divLoading").addClass('show');

      var get_barcode_url = "<?php echo $data['get_barcode']; ?>";
      get_barcode_url = get_barcode_url.replace(/&amp;/g, '&');

      var search_item = $('#automplete-product').val();

      if(search_item != ''){
        get_barcode_url = get_barcode_url+'&vbarcode='+search_item;
        $.getJSON(get_barcode_url, function(result){
          var edit_url = "<?php echo $data['edit']; ?>";
          edit_url = edit_url.replace(/&amp;/g, '&');
          edit_url = edit_url+'&iitemid='+result.iitemid;

          if(result.iitemid){
            window.location.href = edit_url;
            $("div#divLoading").addClass('show');
            return false;
          }
          $("div#divLoading").removeClass('show');
          return false;
        });
      }
      return false;

    }
    
  });

  $(function() { $('[name="automplete-search-box"]').focus(); });
</script>
    <!-- Delete items -->

<script type="text/javascript">
    // $(document).on('click', 'input[name=deleteItems]', function(){
        
    //     var dataItemOrders = [];
        
    //      $('.iitemid').filter(':checked').each(function(){
    //          dataItemOrders.push($(this).parents('td').data('order'));
    //      })
    //     // console.log(dataItemOrders);
    // })
 
</script>


<script type="text/javascript">
  $(document).on('click', '#delete_btn', function(event) {
    event.preventDefault();
    
    $('#deleteItemModal').modal('show');

  });
  
  $(document).on('click', '#calculate_reorderpoint', function(event) {
    event.preventDefault();
    
    $('#calculateReorderPointModal').modal('show');

  });
</script>

<div id="deleteItemModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete Item</h4>
      </div>
      <div class="modal-body">
        <p>Are you Sure?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <input type="submit" class="btn btn-danger" name="deleteItems" value="Delete">
      </div>
    </div>

  </div>
</div>

<div id="calculateReorderPointModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Generate Reorder Point</h4>
      </div>
      <div class="modal-body">
        <p>This will generate Reorder points for each item based on the last two weeks sales and it will replace the existing reorder point.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No, Thanks</button>
        <input type="submit" class="btn btn-success" id="calculateROP" name="calculateROP" value="Continue">
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">

    var dataItemOrders = [];

     $(document).on('click', 'input[name=deleteItems]', function(){
        
        
        
         $('.iitemid').filter(':checked').each(function(){
             dataItemOrders.push($(this).data('order'));
         });
        // console.log(dataItemOrders);
    });

    $(document).on('click', 'input[name="deleteItems"]', function(event) {
        event.preventDefault();
        var delete_url = "<?php echo $data['delete']; ?>";
        delete_url = delete_url.replace(/&amp;/g, '&');
        
        $('#deleteItemModal').modal('hide');
        $("div#divLoading").addClass('show');
        
         $.ajax({
             
        url : delete_url,
        headers: {
                  'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        data : JSON.stringify(dataItemOrders),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
      success: function(data) {
        console.log(data);
        if(data.success){
            $('#success_msg').html('<strong>'+ data.success +'</strong>');
            $("div#divLoading").removeClass('show');
            $('#successModal').modal('show');
        }
        if(data.error){
            $('#error_msg').html('<strong>'+ data.error +'</strong>');
            $('#errorModal').modal('show');
            
        }
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
  
  $(document).on('click', '#calculateROP', function(event) {
        event.preventDefault();
        var ROP_url = "<?php echo $data['reorder_point_url']; ?>";
        ROP_url = ROP_url.replace(/&amp;/g, '&');
        
        $('#calculateReorderPointModal').modal('hide');
        // $("div#divLoading").addClass('show');
        
         $.ajax({
            url : ROP_url,
            headers: {
                  'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            type : 'POST',
            success: function(data) {
                
            },
        });
    });
</script>

<div class="modal fade" id="deleteItemSuccessModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success text-center">
            <p><b>Item Deleted Successfully</b></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>

<!-- Delete items -->

<script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>

@endsection