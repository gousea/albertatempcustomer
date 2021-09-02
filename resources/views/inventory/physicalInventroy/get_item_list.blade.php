@extends('layouts.layout')

@section('title')
    Physical Inventory
@stop

@section('styles')


@endsection

@section('main-content')

<div id="content">

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Items</span>
                </div>
                <div class="nav-submenu">
                    <button class="btn btn-gray headerblack  buttons_menu" id="scanned_items_model">View All Scanned Data</button>
                    <button class="btn btn-gray headerblack  buttons_menu" id="next_btn"><i class="fa fa-plus"></i>&nbsp;Next</button>
                    <a href="<?php echo $data['cancel']; ?>" class="btn btn-danger buttonred buttons_menu basic-button-small"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
      </nav>

    <style>

        table#item_listing tr th{
            table-layout: fixed !important;
        }

        .padding-left-right{
            padding: 0 2% 0 2%;
        }

        span.select2-container{
            width: 90% !important;
            min-width:100px; !important;
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

        /* table tbody tr:nth-child(even) td{
            background-color: #f05a2814;
        } */

        .select2-search input {
            color:black;
        }
        .select2-selection__choice {
            color:black;
        }
        
    </style>
    
  <div class="container-fluid section-content">
    @if (isset($data['error_warning']))
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ $data['error_warning'] }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    @if (isset($data['success']))
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ $data['success'] }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    <div class="panel panel-default itemsData">
      
      <div class="panel-body padding-left-right">
        
        <div class="form-group">
                <input type="checkbox" id="for_scanning" >
                <label class="control-label" for="input-template">Add Data One By One Using Scanner </label>
        </div>
        <div class="box-body table-responsive">
            <div class="listed_item_table" id="listed_item_table">
                <form method="post" action="{{ $data['url_next'] }}" id="itemlistform">
                    @csrf
                    <input type="hidden" name="conditions" id="conditions" >
                    <table id="item_listing" class="table table-striped table-hover promotionview" style="width:100%; font-size:11px;">
                        <thead>
                            <?php $dynamic_data = [];?>
                            <tr class="header-color">
                              <th style="width: 20px;color:black;" class="text-center"><input type="checkbox" name="selected" id="parent_selected" onchange="$('input[name*=\'selected\']').prop('checked', this.checked);" checked /></th>
                              
                              <th class="text-left text-uppercase no-sort" style="width: 15%;">&nbsp;&nbsp;Item Name
                                <div class="po-has-search">
                                  <span class="fa fa-search form-control-feedback"></span>
                                  <input type="text" class="form-control table-heading-fields text-center search_text_box" name="item_search" id="item_search" placeholder="SEARCH"  style="width: 100%; padding-left: 5px;">
                                </div>
                              </th>
                              <?php $dynamic_data[] = "vitemname";?>
                              
                              <th class="text-left text-uppercase no-sort" style="width: 12%;">&nbsp;&nbsp;SKU
                                <div class="po-has-search">
                                  <span class="fa fa-search form-control-feedback"></span>
                                  <input type="text" name="sku_search" id="sku_search" class="form-control table-heading-fields text-center search_text_box" placeholder="SEARCH"  style="width: 110%; padding-left: 5px;">
                                </div>
                              </th>
                              <?php $dynamic_data[] = "vbarcode";?>
                              
                              <th style="width: 20%;">PRICE
                                <div class="adjustment-has-search">
                                  
                                  <select class='table-heading-fields' id='price_select_by' name='price_select_by' style='width:40%; padding-left: 5px;'>
                                    <option value="greater" selected>Greater than</option>
                                    <option value="less">Less than</option>
                                    <option value="equal">Equal to</option>
                                    <option value="between">Between</option>
                                  </select>
                                  <span id='selectByValuesSpan'>
                                    <input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box1 table-heading-fields' placeholder='Enter Amount' style='width:50%;color:black;height:28px; padding-left: 1px;' value=''/>
                                  </span>
                                </div>
                              </th>
                              <?php $dynamic_data[] = "dunitprice";?>

                              <th class="text-left text-uppercase no-filter" style="width:38px;" >COST</th>
                              <?php $dynamic_data[] = "unitcost";?>
                              
                              <?php if(isset($data['itemListings']) && count($data['itemListings'])){ ?>
                                <?php foreach($data['itemListings'] as $m => $itemListing){ ?>
                                   
                                   <?php if($m == 'vcategorycode'){
                                        $dynamic_data[] = "vcategoryname";
                                        ?>
                                            <th class="text-uppercase no-sort" style="width: 12%;">Category
                                            <div class="adjustment-has-search">
                                                <select class='table-heading-fields' multiple='true' name='category_code[]' id='category_code'>
                                                <option value='all'>All</option>
                                                
                                                </select>
                                            </div>
                                            </th>
                                        <?php continue;
                                        }else if($m ==  'vdepcode'){
                                            $dynamic_data[] = "vdepartmentname";
                                        ?>
                                            <th class="text-uppercase no-sort" style="width: 12%;">Dept.
                                            <div class="adjustment-has-search">
                                                <select class='table-heading-fields' multiple='true'  name='dept_code[]' id='dept_code'>
                                                    <option value='all'>All</option>";
                                                    <?php foreach($data['departments'] as $department){ ?>  
                                                        <option value='<?=$department['vdepcode']?>' ><?=$department['vdepartmentname'] ?></option>;
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            </th>
                                        <?php continue;
                                        }else if($m ==  'subcat_id'){
                                            $dynamic_data[] = "subcat_name";
                                        ?>
                                            <th class="text-uppercase no-sort" style="width: 12%;">Sub Category
                                            <div class="adjustment-has-search">
                                                <select class='table-heading-fields' multiple='true' name='subcat_id[]' id='subcat_id'>
                                                <option value='all'>All</option>
                                                
                                                </select>
                                            </div>
                                            </th>
                                        <?php continue;
                                        }else if($m ==  'vsuppliercode'){
                                            $dynamic_data[] = "vcompanyname";
                                        ?>
                                            <th class="text-left text-uppercase no-sort" style="width: 12%;">&nbsp;&nbsp;Supplier
                                                <div class="adjustment-has-search">
                                                    <select class='table-heading-fields'  multiple='true' name='supplier_code[]' id='supplier_code'>
                                                    <option value='all'>All</option>";
                                                        <?php 
                                                            foreach($data['supplier'] as $supplier){
                                                        ?>  
                                                            <option value='<?=$supplier['vsuppliercode']?>'><?=$supplier['vcompanyname'] ?></option>;
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </th>
                                        <?php continue;
                                        }else{
                                            $dynamic_data[] = $m;
                                        } ?>

                                        <th class="text-left text-uppercase no-sort" style="width:72px !important">{{  $data['title_arr'][$m] }}</th>

                                  <?php } ?>
                              <?php } else { ?>
                                <th class="text-left text-uppercase no-sort" style="width:65px";><?php echo $column_deptcode; ?></th>
                                <?php $dynamic_data[] = "vdepartmentname";?>
                                <th class="text-left text-uppercase" style="width:65px";><?php echo $column_categorycode; ?></th>
                                <?php $dynamic_data[] = "vcategoryname";?>
                                <th class="text-right text-uppercase no-sort" style="width:65px";><?php echo $column_price; ?></th>
                                <?php $dynamic_data[] = "subcat_name";?>
                              <?php } ?>
                              <th class="text-right text-uppercase no-sort" style="width:30px";>QTY. ON HAND</th>
                                <?php $dynamic_data[] = "iqtyonhand";?>
                            </tr>
                        </thead>
		            </table>
		        </form>
		    </div>
		</div>  <!-- /.box-body -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')

    <style>
        .no-filter{
            padding-bottom: 44px !important;
        }
    
    </style>

    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/purchaseorder.css') }}">

    <link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
    <script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link type="text/css" href="{{ asset('stylesheet/select2/css/select2.min.css') }}" rel="stylesheet" />
    
    <script src="{{ asset('javascript/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>
    <!----  Session ----->
    <!--<script src="{{ asset('javascript/jquery/jquery.session.js') }}"></script>-->


    <script>
        // $(document).on('change', '#price_select_by', function(){
        //     var select_by = $(this).val();
        //     console.log(select_by);
        //     var html='';
        //     if(select_by == 'between'){
        //         // $('#price_select_by').css('width', '55px');
        //         html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box1" placeholder="Enter Amt" style="width:25%;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value=""/>';
        //         html += '<input type="text" autocomplete="off" name="select_by_value_2" id="select_by_value_2" class="search_text_box1" placeholder="Enter Amt" style="width:25%;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value="" required/>'
        //     } else {
        //         // $('#price_select_by').css('width', '70px');
        //         html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box1" placeholder="Enter Amt" style="width:50%;color:black;border-radius: 4px;height:28px;margin-left:5px;" value=""/>'
        //         // $('#selectByValuesSpan').html('not between');
        //     }
        //     $('#selectByValuesSpan').html(html);
        // });
    </script>

<script>
    var table;  //declare table variable global
    
    $(document).ready(function() {

        var url = "<?php echo $data['searchitem'];?>";
        url = url.replace(/&amp;/g, '&');
        
        $(document).on('input', '#select_by_value_2, #select_by_value_1', function(){
                
                select_by_val1 = parseFloat($('#select_by_value_1').val());
                select_by_val2 = parseFloat($('#select_by_value_2').val());
                setTimeout(function(){
                    if($('#price_select_by').val() == 'between' && typeof(select_by_val1) != "undefined" && select_by_val1 !== null && typeof(select_by_val2) != "undefined" && select_by_val2 !== null && select_by_val1 >= select_by_val2){ 
                            bootbox.alert({ 
                                        size: 'small',
                                        title: "  ", 
                                        message: "Second value must be greater than first value!", 
                                      });
                    }
                }, 1400);
        });
        
        
        // $('#item_listing thead tr').clone(true).appendTo( '#item_listing thead' );
        $('#item_listing thead tr th').each( function (i) {
            var title = $(this).text();
            
            
            var timer;
     
            $( '.search_text_box', this ).off().on( 'keyup change', function () {
                var self = this;

                if(self.value != ''){
                    $(this).closest('div').find('.fa-search').hide();
                    
                }else{
                    $(this).closest('div').find('.fa-search').show();
                }

                clearTimeout(timer);
                timer = setTimeout(function () {
                    if ( table.column(i).search() !== self.value ) {
                    table
                        .column(i)
                        .search( self.value )
                        .draw();
                        
                    $("div#divLoading").addClass('show');
                }
                },0);
                
            } );
            
            //========filter for price==============
            $(document).off().on( 'input', '.search_text_box1', function () {
                
                var selectBy = $("#price_select_by").val();
                var select_by_value_1 = $('#select_by_value_1').val();
                
                var select_by_value_2 = $('#select_by_value_2').val();
                
                // console.log($('#price_select_by').val());
                
                
                var searchVal = ''
                if(selectBy){
                    searchVal += selectBy
                } 
                if(select_by_value_1){
                    searchVal += ('|' + select_by_value_1)
                }
                if(select_by_value_2){
                    searchVal += ('|'+select_by_value_2)
                }
                
                
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        
                        table
                            .column(3)
                            .search( searchVal)
                            .draw();
                            
                        $("div#divLoading").addClass('show');
                    
                    },0);
                
                
            } );
            
            
            $( '#price_select_by', this ).on( 'change', function () { 
                var self = this;
                
                var selectBy = $("#price_select_by").val();
                var html='';
                if(selectBy == 'between'){
                    // $('#price_select_by').css('width', '55px');
                    html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box1" placeholder="Enter Amt" style="width:25%;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value=""/>';
                    html += '<input type="text" autocomplete="off" name="select_by_value_2" id="select_by_value_2" class="search_text_box1" placeholder="Enter Amt" style="width:25%;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value="" required/>'
                } else {
                    // $('#price_select_by').css('width', '70px');
                    html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box1" placeholder="Enter Amt" style="width:50%;color:black;border-radius: 4px;height:28px;margin-left:5px;" value=""/>'
                    // $('#selectByValuesSpan').html('not between');
                }
                $('#selectByValuesSpan').html(html);
                
                clearTimeout(timer);
                timer = setTimeout(function () {
                    if ( table.column(i).search() !== self.value ) {
                    table
                        .column(i)
                        .search( self.value )
                        .draw();
                        
                    $("div#divLoading").addClass('show');
                }
                },0);
            } );
         
            $( '#dept_code', this ).on( 'change', function () { 
                var search = [];
                        $.each($('#dept_code option:selected'), function(){
                            search.push(this.value);
                        });
                          
                        search = search.join("','"); 
                    table
                        .column(5)
                        .search( search )
                        .draw();
                        
                    $("div#divLoading").addClass('show');
            } );
            $( '#category_code', this ).on( 'change', function () {
                var search = [];
                $.each($('#category_code option:selected'), function(){
                            search.push(this.value);
                        });
                          
                        search = search.join("','"); 
                    table
                        .column(6)
                        .search( search )
                        .draw();
                        
                    $("div#divLoading").addClass('show');
            } );
            
            $( '#supplier_code', this ).on( 'change', function () { 
                var search = [];
                $.each($('#supplier_code option:selected'), function(){
                            search.push(this.value);
                        });
                          
                        search = search.join("','"); 
                    table
                        .column(8)
                        .search( search )
                        .draw();
                        
                    $("div#divLoading").addClass('show');
            } );
            $( '#subcat_id', this ).on( 'change', function () { 
                var search = [];
                $.each($('#subcat_id option:selected'), function(){
                            search.push(this.value);
                        });
                          
                        search = search.join("','");
                    table
                        .column(7)
                        .search( search )
                        .draw();
                        
                    $("div#divLoading").addClass('show');
            } ); 
            
        } );
        
        $(document).on("change","#dept_code",function(){
            var get_category_ajax;
            if($(this).val() != "")
            {
                $('#category_code').attr("placeholder", "Loading...");
                var get_categories_url = '<?php echo $data['get_categories_url']; ?>';
                get_categories_url = get_categories_url.replace(/&amp;/g, '&');
                
                var dep_code = [$(this).val()];
                console.log(dep_code);
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
        
        $(document).on("change","#category_code",function(){
            var get_subcategory_ajax;
            if($(this).val() != "")
            {
                $('#subcat_id').attr("placeholder", "Loading...");
                var get_subcategories_url = '<?php echo $data['get_subcategories_url']; ?>';
                get_subcategories_url = get_subcategories_url.replace(/&amp;/g, '&');
                
                var category_code = [$(this).val()];
                
                if(get_subcategory_ajax && get_subcategory_ajax.readyState != 4 ){
                    get_subcategory_ajax.abort();
                }
                
                get_category_ajax = $.ajax({
                    url: get_subcategories_url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'post',
                    data : {category_code : category_code},
                    success:function(data){ console.log(data);
                        if(data)
                        {
                            $('#subcat_id').attr("placeholder", "Select Sub Category");
                            $( '#subcat_id' ).html( data );
                            $('#subcat_id').prop("disabled", false);
                        }
                        else
                        {
                            $( '#subcat_id' ).html( '' );
                            $('#subcat_id').prop("disabled", true);
                        }
                        
                    }
                })
            }
        });
        
        $("#dept_code").select2({closeOnSelect:true,placeholder: 'Select Department'});
        $("#category_code").select2({closeOnSelect:true,placeholder: 'Select Category'});
        $("#subcat_id").select2({closeOnSelect:true,placeholder: 'Select Sub Category'});
        $("#supplier_code").select2({closeOnSelect:true,placeholder: 'Select Supplier'});
        // $("#price_select_by").select2();
        
        
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
                "data-order": data,
                checked:"checked"
            })[0].outerHTML;
        }});
                        
            table =   $("#item_listing").DataTable({
            
                "bSort": false,
                "autoWidth": false,
                "fixedHeader": true,
                "processing": true,
                "iDisplayLength": 20,
                "serverSide": true,
                
                "bLengthChange": false,
                // "aoColumns": [
                //     { "sWidth": "30px" },
                //     { "sWidth": "100px" },
                //     { "sWidth": "100px" },
                //     { "sWidth": "310px" },
                //     { "sWidth": "50px" },
                //     { "sWidth": "100px" },
                //     { "sWidth": "100px" },
                //     { "sWidth": "100px" },
                //     { "sWidth": "100px" },
                //     { "sWidth": "50px" }
                    
                // ],
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
                   
                  "dataSrc": function ( json ) {
                        
                        $("div#divLoading").removeClass('show');
                        return json.data;
                    } 
                },
                columns : data_array,
                
            }).on('draw', function(){
                if($('#parent_selected').prop("checked") == true){
                    
                    $('.iitemid').prop('checked', true);
                } else{ 
                 $('.iitemid').prop('checked', false);   
                } 
                
                setTimeout(function(){
                    $("div#divLoading").removeClass('show');
                }, 1000);
                
            });
        
        var totalDisplayRecord = table.page.info().recordsDisplay;
        console.log(totalDisplayRecord);
        
        $("#item_listing_processing").remove();
        $("#item_listing_filter").hide();

        $("#item_listing_paginate").addClass("pull-right");
    });
        var itemId = [];
        var scanned_iitemid = [];
        var selected = [];
        $(document).ready(function(event){

            var session_url = "<?php echo $data['session_url']; ?>" ;
            session_url = session_url.replace(/&amp;/g, '&');

            var scanned_session_url = "<?php echo $data['scanned_session_url']; ?>" ;
            scanned_session_url = scanned_session_url.replace(/&amp;/g, '&');

            $(document).on('click', '.iitemid', function () {

                if($('#for_scanning').prop("checked") == false){

                    $('.iitemid').filter(':checked').each(function(){
                        itemId.push($(this).data('order'));
                    });

                        $.ajax({
                            url: session_url,
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                            },
                            type: 'post',
                            data : {itemid : itemId},
                            success:function(data){
                                if(data.success == true)
                                {
                                    console.log(data.success_msg);
                                    console.log(data.item_id);
                                }
                                else
                                {
                                    console.log(data.item_id);
                                    console.log('failed');
                                }

                            }
                        })
                    itemId = [];

                }

                if($('#for_scanning').prop("checked") == true && $('#sku_search').val() != ''){
                }
            });


            //========scanning sku oninput event==========
            $('#sku_search').on('input', function(){

                if($('#for_scanning').prop("checked") == true && $('#sku_search').val() != ''){

                    setTimeout(function(){
                        $('.iitemid').prop("checked", true);

                    // scanned_iitemid.push($('#sku_search').val());
                        $('.iitemid').filter(':checked').each(function(){
                        scanned_iitemid.push($(this).data('order'));
                    });
                        $.ajax({
                                    url: scanned_session_url,
                                    headers: {
                                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                                    },
                                    type: 'post',
                                    data : {itemid : scanned_iitemid},
                                    success:function(data){
                                        if(data.success == true)
                                        {
                                            console.log(data.success_msg);
                                            console.log(data.item_id);
                                        }
                                        else if(data.success == false)
                                        {
                                            console.log(data.item_id);
                                            console.log('failed');
                                        }

                                    }
                            });
                    },2000);
                    setTimeout(function(){
                        $('#sku_search').val('');
                    },3000);
                }
            });

            //========validation on next button =============
            $('#next_btn', this).on('click', function(){
                var count =0;
                var parentcount = 0;
                $('input[name="selected[]"]:checked').each(function(i){
                    count = count + 1;
                    selected[i] = $(this).val();
                });
                if($('#parent_selected').prop("checked") == true){
                    parentcount = parentcount + 1;
                }
                var item_search = $('#item_search').val();
                var sku_search = $('#sku_search').val();
                // var price_select_by = $('#price_select_by').val();
                var price_select_by = $('#select_by_value_1').val();
                var department = $('#dept_code').val();
                var category = $('#category_code').val();
                var subcategory = $('#subcat_id').val();
                var supplier = $('#supplier_code').val();
                var no_of_rows = $('#item_listing tr').length;
                var empty = $('.dataTables_empty').text();
                
                
                if(empty == 'No data available in table'){
                    bootbox.confirm({
                                    size: 'small',
                                    title: "  ",
                                    message: "Not any Item are there, Table is blank",
                                    callback: function(result){
                                    }
                                });
                }
                else if((count <= 0 || parentcount > 0) && $('#for_scanning').prop("checked") == false && item_search === "" && sku_search === "" && price_select_by == "" && department.length == 0 && supplier.length == 0 && category.length == 0 && subcategory.length == 0 ){
                    bootbox.confirm({
                                    size: 'small',
                                    message: "No filters have been selected which implies that you intend to include all the items.<br><br> Do you want to proceed ?",
                                    title: "  ",
                                    callback: function(result){
                                        if(result){
                                            $('#conditions').val('all');
                                            $("div#divLoading").addClass('show');
                                            $('#itemlistform').submit();
                                        }
                                    }
                                });
                }else if($('#for_scanning').prop("checked") == true && scanned_iitemid.length <= 0 ){
                    bootbox.confirm({
                                    size: 'small',
                                    title: "  ",
                                    message: "Not any Item is Scanned, Table is blank",
                                    callback: function(result){
                                    }
                                });
                }else if($('#for_scanning').prop("checked") == true && scanned_iitemid.length > 0 ){
                    $('#conditions').val('scanned_data');
                    $('#itemlistform').submit();
                }else if($('#for_scanning').prop("checked") == false){
                    $('#conditions').val('session_filters_data'); //==this is for both session and filters data===
                    $('#itemlistform').submit();
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            var get_scanned_data = "<?php echo $data['get_scanned_data']; ?>";
            get_scanned_data = get_scanned_data.replace(/&amp;/g, '&');
            $('#scanned_items_model').on('click', function(){
                $("#scanned_data_table tbody").empty();
                $.ajax({
                    url: get_scanned_data,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'get',
                    success:function(data){
                        if(data.success == true)
                        {
                            var newRowContent = '';
                            $(data.scanned_data).each(function(index, e){
                                newRowContent += "<tr id='"+index+"'>";
                                newRowContent += "<td><center><input type='checkbox' name='itemid_selected' class='itemid' id='itemid_"+index+"' data-itemid='"+e.iitemid+"' data-row='"+index+"'></center></td>";
                                newRowContent += "<td>"+ e.vbarcode +"</td>";
                                newRowContent += "<td>"+ e.vitemname +"</td>";
                                newRowContent += "<td>"+ e.iqtyonhand +"</td>";
                                newRowContent += "<td>"+ e.unitcost +"</td>";
                                newRowContent += "</tr>";
                                $("#scanned_data_table tbody").append(newRowContent);
                                $('#viewModal').modal('show');
                                newRowContent = '';
                            });
                        }
                        else
                        {
                            $('#errorModal').modal('show');
                            console.log(data);
                            console.log('failed');
                        }
                    }
                });
            });

            var remove_scanned_itemid = [];
            var remove_rows = [];
            var remove_session_scanned_data = "<?php echo $data['remove_session_scanned_data']; ?>";
            remove_session_scanned_data = remove_session_scanned_data.replace(/&amp;/g, '&');
            $("#viewModal").on('click','#remove_button',function(){
                $('.itemid').filter(':checked').each(function(){
                        remove_scanned_itemid.push($(this).data('itemid'));
                        remove_rows.push($(this).data('row'));
                });
                console.log(remove_scanned_itemid);
                $.ajax({
                    url: remove_session_scanned_data,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'post',
                    data : {itemid : remove_scanned_itemid},
                    success:function(data){
                        if(data.success == true)
                        {
                            console.log(data.success_msg);
                            console.log(data.item_id);
                        }
                        else if(data.success == false)
                        {
                            console.log(data.item_id);
                            console.log('failed');
                        }
                    }
                });
                $(remove_rows).each(function(index, e){
                    $('#'+e).remove();
                });
                $(remove_scanned_itemid).each(function(index, e){
                    scanned_iitemid = $.grep(scanned_iitemid, function(value) {
                    return value != e;
                    });
                });
            });
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="errorModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success text-center">
                        <p id="success_msg"><strong>No data Found</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="viewModal" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h4 class="modal-title">Scanned Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                            <button type="button" class="btn button-blue basic-button-small pull-right" id="remove_button">Remove</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                            <div class="table-responsive">
                            <table class="table table-hover promotionview" id="scanned_data_table">

                                <thead id="txt" style="font-size:12px;">
                                    <tr class="header-color">
                                        <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" name="itemid_selected" onchange="$('input[name*=\'itemid_selected\']').prop('checked', this.checked);" /></th>
                                        <th class="text-left" style="vertical-align : middle;">SKU</th>
                                        <th class="text-left" style="vertical-align : middle;">Item Name</th>
                                        <th class="text-left" >QOH</th>
                                        <th class="text-left" >Price</td>
                                    </tr>
                                </thead>
                                <tbody id="cal_post_table">

                                </tbody>
                            </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default basic-button-small text-dark" data-dismiss="modal" style="border-color: black;" onclick="$(this).closest('tr').remove();">Close</button>
            </div>
        </div>

        </div>
    </div>

    <script type="text/javascript">
        $(function() { $('[name="automplete-search-box"]').focus(); });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#scanned_items_model').attr("disabled", true);
            
            $('#for_scanning').on('click', function(){
                if($('#for_scanning').prop("checked") == true){
                    $('#parent_selected').prop("checked", false);
                    $('.iitemid').prop("checked", false);
                    $('#scanned_items_model').attr("disabled", false);
                    $('#sku_search').focus();
                }else{
                    $('#parent_selected').prop("checked", true);
                    $('.iitemid').prop("checked", true);
                    $('#scanned_items_model').attr("disabled", true);
                }
            });
        });
    </script>

    <!-- unset sesion url ajax -->
    <script type="text/javascript">
        $(document).ready(function(){
            
            var unset_session_scanned_data = "<?php echo $data['unset_session_scanned_data']; ?>";
            unset_session_scanned_data = unset_session_scanned_data.replace(/&amp;/g, '&');
            $.ajax({
                url: unset_session_scanned_data,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                type: 'get',
                success:function(data){
                    if(data.success == true)
                    {
                        console.log(data);
                    }
                    else
                    {
                        console.log(data);
                    }
                }
            });
        });
    </script>
@endsection
