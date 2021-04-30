@extends('layouts.master')

@section('title')
  Buy Down
@endsection

@section('main-content')
<div id="content">
    <style type="text/css">
    .error {
        color:#f56b6b;
        border-color: #f56b6b;
    }
 .nav.nav-tabs .active a{
    background-color: #f05a28 !important;
    color: #fff !important;
  }

  .nav.nav-tabs li a{
    color: #fff !important;
    background-color: #03A9F4;
  }

  .nav.nav-tabs li a:hover{
    color: #fff !important;
    background-color: #f05a28 !important;
  }

  .nav.nav-tabs li a:hover{
    color: #fff !important;
  }

  .add_new_administrations{
    float: right;
    margin-right: -35px;
    margin-top: -30px;
    cursor: pointer !important;
    position: relative;
    z-index: 10;
  }
  .add_new_administrations i{
    cursor: pointer !important;
  }

  .datetime-block{background:gainsboro;};
  .row{margin-left:0px !important;margin-right:0px !important;};

  .panel-default .panel-heading {background:#F05430;!important}

    .select2-container .select2-selection--single {
        height: 32px !important;
    }
    /*******select2******/
    .select2-results__options{
        font-size:11px !important;
    }
    .select2-selection__rendered {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
    }
    /********************/
    .form-control{border-radius: 4px !important;height: 32px !important;}
    label {font-size: 12px !important;padding-right: 0px !important;}
    .radio-group{padding-top:6px;}
    .table.table-bordered.table-striped.table-hover thead > tr{
     	background: #03a9f4 none repeat scroll 0 0 !important;
     }

    table#item_listing th td{
        table-layout: fixed;
        word-wrap:break-word;
    }

    /* table tbody tr:nth-child(even) td{*/
    /*	background-color: #f05a2814;*/
    /*}*/



</style>
    <div class="page-header">
        <div class="container-fluid">

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

<div class="panel panel-default">
    <div class="panel-heading head_title" >
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <b>Buy Down</b></h3>
    </div>
    <div class="panel-body">
        <form action="{{ route('buydown.update') }}" method="post" enctype="multipart/form-data" id="form-buydown" class="form-horizontal">
            @csrf
                <input type="hidden" name="buydown_id" value="{{ $buydown_info->buydown_id }}"/>

                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="required form-group">

                                        <label class="col-md-4 control-label" for="input-promotion-name">BuyDown Name</label>
                                        <div class="col-md-8">
                                            <input type="text" name="buydown_name" autocomplete="off" id="buydown_name" maxlength="100" value="{{ $buydown_info->buydown_name }}" class="form-control disabled-text" />

                                            <div id="buydown_name_validate"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="required form-group">
                                        <label class="col-md-4 control-label" for="input-promotion-code">BuyDown Code</label>
                                        <div class="col-md-8">
                                            <input type="text" name="buydown_code" autocomplete="off" id="buydown_code" disabled maxlength="10" value=" {{ $buydown_info->buydown_code }}" class="form-control disabled-text" />
                                            <div id="buydown_code_validate"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="required form-group">
                                        <label class="col-md-4 control-label" for="entry_promotion_type">From Date</label>
                                        <div class="col-md-8">
                                            <input type="text" name="buydown_from_date" autocomplete="off" id="buydown_from_date"   value="{{ date("m-d-Y", strtotime($buydown_info->start_date)) }}"   class="form-control disabled-text" />
                                            <div id="buydown_from_date_validate"></div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="required form-group">
                                        <label class="col-md-4 control-label" for="entry_promotion_category">To Date</label>
                                        <div class="col-md-8">
                                            <input type="text" name="buydown_to_date" autocomplete="off" id="buydown_to_date"  value="{{ date("m-d-Y", strtotime($buydown_info->end_date)) }}" class="form-control disabled-text" />
                                            <div id="buydown_to_date_validate"></div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="entry_promotion_category">Buy Down Amount</label>
                                        <div class="col-md-8">
                                            <input type="text" name="buydown_to_amt" autocomplete="off" id="buydown_amount"  maxlength="10" value="{{ number_format((float)$buydown_info->buydown_amount, 2, '.', '') }}"  class="form-control disabled-text" />
                                            <div id="buydown_amt_validate"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="status">Status</label>
                                        <div class="col-md-8">
                                            <select name="status" id="status" class="form-control">
                                                <option value="Active" <?php  if(isset($buydown_info->status)  && $buydown_info->status == "Active" ){ echo "selected"; } else { echo ""; } ?> >Active</option>
                                                <option value="Inactive" <?php  if(isset($buydown_info->status)  && $buydown_info->status == "Inactive" ){ echo "selected"; } else { echo ""; } ?> >Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row" id="div_item_listing">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                    <b> <span style="float:right;color:red"><lable class="required control-label">NOTE: New search will clear existing search and selected items</lable></span></b>
                		            <table id="item_listing" class="table table-bordered table-striped table-hover" style="font-size:9px;">
                		                <thead>
                		                    <tr>
                		                        <!--<th style="width: 1px;" class="text-center"><input type="checkbox" id="parent_select" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" style="color: black;"/></th>-->
                		                        <th style="width: 1px;" class="text-center">
                		                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" style="color: black;"/>
                		                        </th>
                                                <th style="width: 70px;">ITEM NAME</th>
                                                <th style="width: 70px">SKU</th>
                                                <th style="width:195px;">PRICE</th>
                                                <th style="width:55px;">UNIT</th>
                                                <th style="width:50px;">SIZE</th>
                                                <!--<th>TYPE</th>-->
                                                <th style="width:90px;">DEPT.</th>
                                                <th style="width:90px;">CATEGORY</th>
                                                <th style="width:90px;">SUB CAT</th>
                                                <!--<th>SUPPLIER</th>
                                                <th>MFR</th>-->

                		                    </tr>
                		                </thead>
                		            </table>
                		        </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="text-center">
                                    <span>
                                        <input type="button" id="add_to_buy_items" class="btn btn-primary" value="Add to BuyDown">
                                    </span>
                                    <span style="padding-left:5px;"></span>
                                    <span style="padding-left:5px;"></span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="item_listing2" class="table table-bordered table-striped table-hover" style="font-size:11px;">
                                            <thead>
                                                <tr>
                                                    <th style="vertical-align: inherit;" style="width: 1px;" class="text-center">
                                                        <input type="checkbox" id="check_all_buy_items" onclick="$('input[name*=\'added_buydown_items\']').prop('checked', this.checked);" style="color: black;"/>
                                                    </th>
                                                    <th style="vertical-align: inherit;">ITEM NAME</th>
                                                    <th style="vertical-align: inherit;">SKU</th>
                                                    <th style="vertical-align: inherit;">Live COST</th>
                                                    <th style="vertical-align: inherit;">Live PRICE</th>
                                                    <th style="vertical-align: inherit;">INVOICE COST</th>
                                                    <th style="vertical-align: inherit;">PRICE BEFORE BUYDOWN</th>
                                                    <th style="vertical-align: inherit;">BUYDOWN AMOUNT</th>
                                                    <th style="vertical-align: inherit;">COST AFTER BUYDOWN</th>
                                                    <th style="vertical-align: inherit;" >PRICE AFTER BUYDOWN</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="padding-bottom:10px;"><input type="button" class="btn btn-danger" id="remove_buy_items" value="Remove Buydown Items"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" form="form-customer" data-toggle="tooltip" id="submit_budown" title="save" class="btn btn-primary"><i class="fa fa-save"> Save</i></button>
                        <a href="/buydown" data-toggle="tooltip" title="cancel" class="btn btn-default"><i class="fa fa-reply"></i> Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!--- ERROR MODAL STARTS -->
    <div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger text-center">
                    <p id="error_msg">The selected items have already been added to another buy down.</p>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
<!-- ERROR MODAL ENDS -->

@endsection

@section ('script_files')

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
<link type="text/css" href="{{ asset('javascript/bootstrap-datepicker.css') }}" rel="stylesheet" />
<link type="text/css" href="{{ asset('stylesheet/select2/css/select2.min.css') }}" rel="stylesheet" />

<script src="{{ asset('javascript/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('javascript/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>
<script src="{{ asset('javascript/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('javascript/bootstrap-datepicker.js') }}" defer></script>
<script src="{{ asset('javascript/fancyTable/fancyTable.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
@endsection

@section('scripts')



<script type="text/javascript">
    var get_department_item_ajax,get_category_ajax,get_category_items_ajax,get_group_items_ajax,get_subcategory_ajax,get_sub_category_items_ajax,get_selected_buy_items_ajax ;
    var itemsAddedToBuyItems = new Array();itemsAddedToDiscountedItems = new Array();
    var existingBuydownItems = new Array();
    var check_existingBuydownItems = new Array();
    var get_items_url = "<?php echo $data['get_items_url']; ?>";
        get_items_url = get_items_url.replace(/&amp;/g, '&');

    var get_customers_url = "<?php echo $data['get_customers_url']; ?>";
        get_customers_url = get_customers_url.replace(/&amp;/g, '&');

    var get_selected_buy_items_url = "<?php echo $data['get_selected_buy_items_url']; ?>";
        get_selected_buy_items_url = get_selected_buy_items_url.replace(/&amp;/g, '&');

    var get_saved_buy_items_ajax_url = "<?php echo $data['get_saved_buy_items_ajax_url']; ?>";
        get_saved_buy_items_ajax_url = get_saved_buy_items_ajax_url.replace(/&amp;/g, '&');

    var get_selected_discounted_items_url = "<?php echo $data['get_selected_discounted_items_url']; ?>";
        get_selected_discounted_items_url = get_selected_discounted_items_url.replace(/&amp;/g, '&');

    var get_saved_discounted_items_ajax_url = "<?php echo $data['get_saved_discounted_items_ajax_url']; ?>";
        get_saved_discounted_items_ajax_url = get_saved_discounted_items_ajax_url.replace(/&amp;/g, '&');

    var clickedAddBuyItems = 0;

    $(function () {

        // select all and unselect all option in select2 dropdown
        $.fn.select2.amd.define('select2/selectAllAdapter', [
            'select2/utils',
            'select2/dropdown',
            'select2/dropdown/attachBody'
        ], function (Utils, Dropdown, AttachBody) {

            function SelectAll() { }
            SelectAll.prototype.render = function (decorated) {
                var self = this,
                    $rendered = decorated.call(this),
                    $selectAll = $(
                        '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-check-square-o"></i> Select All</button>'
                    ),
                    $unselectAll = $(
                        '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-square-o"></i> Unselect All</button>'
                    ),
                    $btnContainer = $('<div style="margin-top:3px;">').append($selectAll).append($unselectAll);
                if (!this.$element.prop("multiple")) {
                    // this isn't a multi-select -> don't add the buttons!
                    return $rendered;
                }
                $rendered.find('.select2-dropdown').prepend($btnContainer);
                $selectAll.on('click', function (e) {
                    var $results = $rendered.find('.select2-results__option[aria-selected=false]');
                    $results.each(function () {
                        self.trigger('select', {
                            data: $(this).data('data')
                        });
                    });
                    self.trigger('close');
                });
                $unselectAll.on('click', function (e) {
                    var $results = $rendered.find('.select2-results__option[aria-selected=true]');
                    $results.each(function () {
                        self.trigger('unselect', {
                            data: $(this).data('data')
                        });
                    });
                    self.trigger('close');
                });
                return $rendered;
            };

            return Utils.Decorate(
                Utils.Decorate(
                    Dropdown,
                    AttachBody
                ),
                SelectAll
            );
        });

        $('#buydown_from_date').datepicker({format: 'mm-dd-yyyy',autoclose: true});
        $('#buydown_to_date').datepicker({format: 'mm-dd-yyyy',autoclose: true});
        <?php if(!$buydown_info->buydown_id) {?>
            $('#buydown_from_date').datepicker('setDate', 'today');
            $('#buydown_to_date').datepicker('setDate', 'today');
        <?php }?>
        // $('#buydown_from_time').datetimepicker({pickDate: false,format: 'hh:mm A',autoclose: true});
        // $('#buydown_to_time').datetimepicker({pickDate: false,format: 'hh:mm A',autoclose: true});

    });


    $(document).on("keypress keyup blur",".decimal_numbers",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $('input[type="number"]').on("keypress keyup blur",function (event) {
       $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });


    $(document).on('keyup', ".new_price", function () {
    	var sellingPrice = $(this).parent().prev('td').html();
    	var newPriceVal = this.value;

    	var promotionVal = sellingPrice - newPriceVal;
    // 	$(this).parent().next('td').html(promotionVal);
    });

    $(document).on('change', '#price_select_by', function(){
        var select_by = $(this).val();
        var select_by_value1 = $('#select_by_value1').val() === undefined?'':$('#select_by_value1').val();
        var select_by_value2 = $('#select_by_value2').val() === undefined?'':$('#select_by_value2').val();


        var html='';
        if(select_by === 'between'){

            html = '<input type="number" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box" placeholder="Enter" style="width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value="'+select_by_value1+'"/>';
            html += '<input type="number" autocomplete="off" name="select_by_value_2" id="select_by_value_2" class="search_text_box" placeholder="Amt" style="width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value="'+select_by_value2+'"/>'

            $(this).css({'width': 71});


        } else {

            html = '<input type="number" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box" placeholder="Enter Amt" style="width:70px;color:black;border-radius: 4px;height:28px;margin-left:5px;" value="'+select_by_value1+'"/>'
            // $('#selectByValuesSpan').html('not between');
            $(this).css({'width': 90});
        }
        $('#selectByValuesSpan').html(html);

    });

        // $(document).on('keyup change', '#select_by_value_1, #select_by_value_2', function(){
        //     console.log('change value input boxes');
        //     select_by_val1 = $('#select_by_value_1').val();
        //     select_by_val2 = $('#select_by_value_2').val();

        //     table.draw();
        // });

    $(document).ready(function(){

        var a = "<?php echo $buydown_info->buydown_id; ?>";
        if(a){

        clickedAddBuyItems = 1;
        var postData = {buydown_id:a};
                $(this).prop('checked',false);
                 $.ajax({
                    url: get_saved_buy_items_ajax_url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'post',
                    data : postData,
                    success:function(data){
                        if(data)
                        {
                            $("#item_listing2 tbody").append(data);
                        }
                        else
                        {

                        }
                    }
                })
        }

        $(document).on('keyup', '.buydown_amt', function(event){
            event.preventDefault();

            var invoice = parseFloat($(this).parents('tr').find('td.invoce').html());
            var price = parseFloat($(this).parents('tr').find('td.price').html());
            var currAmt = (($(this).val()) != "") ? (parseFloat($(this).val())) : 0.00;

            var costAfter = invoice - currAmt;
            var priceAfter = price - currAmt;
            $(this).parents('tr').find('td.cost_after_buydown').html(costAfter.toFixed(2));
            $(this).parents('tr').find('td.price_after_buydown').html(priceAfter.toFixed(2));
        });

        $(document).on('keyup', '#buydown_amount', function(event){
            event.preventDefault();

            if(($('#item_listing2 tbody tr').length) > 0) {

                $('.buydown_amt').val($(this).val());
                var  buyDownAmt = (($(this).val()) != "") ? (parseFloat($(this).val())) : 0.00;

                $('#item_listing2 tbody tr').each(function(){

                    invoice = parseFloat($(this).find('td.invoce').html());
                    price = parseFloat($(this).find('td.price').html());

                    costAfter = invoice - buyDownAmt;
                    priceAfter = price - buyDownAmt;
                    $(this).find('td.cost_after_buydown').html(costAfter);
                    $(this).find('td.price_after_buydown').html(priceAfter);
                });
            }
        });


        var url = "<?php echo $data['searchitem'];?>";
        url = url.replace(/&amp;/g, '&');
        var departments = "<?php echo $data['departments'];?>";
        var suppliers = "<?php echo $data['suppliers'];?>";
        var manufacturers = "<?php echo $data['manufacturers'];?>";
        var units = "<?php echo $data['units'];?>";
        var size = "<?php echo $data['size'];?>";
        var item_types = "<?php echo $data['item_types']; ?>";
        var price = "<?php echo $data['price']; ?>";

        var price_select_by = $('#price_select_by').val();
        var select_by_val1 = $('#select_by_value_1').val();
        var select_by_val2 = $('#select_by_value_2').val();

        $(document).on('input', '#select_by_value_2, #select_by_value_1', function(){

            select_by_val1 = parseFloat($('#select_by_value_1').val());
            select_by_val2 = parseFloat($('#select_by_value_2').val());

            if($('#price_select_by').val() == 'between' && typeof(select_by_val1) != "undefined" && select_by_val1 !== null && typeof(select_by_val2) != "undefined" && select_by_val2 !== null && select_by_val1 >= select_by_val2){
                    bootbox.alert({
                                size: 'small',
                                title: "Attention",
                                message: "Second value must be greater than first value!",
                              });
            }
        });
        var priceFilter = {
            "price_select_by": price_select_by,
            "select_by_value_1": select_by_val1,
            "select_by_value_2": select_by_val2
        }

        $('#item_listing thead tr').clone(true).appendTo( '#item_listing thead' );
        $('#item_listing thead tr:eq(1) th').each( function (i) {

            var title = $(this).text();
            if(i == 0)
            {
                $(this).html( '' );
            }
            else if(title == 'DEPT.')
            {
                $(this).html(departments)
            }
            else if(title == "TYPE")
            {
                $(this).html(item_types)
            }
            else if(title == "CATEGORY")
            {
                $(this).html('<select class="form-control" name="category_code" id="category_code" style="width: 90px; padding: 0px; font-size: 9px;" disabled><option>All</option></select>')
            }
            else if(title == "SUB CAT")
            {
                $(this).html('<select class="form-control" name="sub_category_id" id="sub_category_id" style="width: 90px; padding: 0px; font-size: 9px;" disabled><option>All</option></select>')
            }
            else if(title == 'SUPPLIER')
            {
                $(this).html(suppliers)
            }
            else if(title == 'MFR')
            {
                $(this).html(manufacturers)
            }
            else if(title == 'UNIT')
            {
                $(this).html(units)
            }
            else if(title == 'SIZE')
            {
                $(this).html(size)
            }
            else if(title == 'ITEM NAME')
            {
                $(this).html( '<input type="text" name="" class="search_text_box" id="item_name" placeholder="Search" style="width:70px;color:black;border-radius: 4px;height:28px;"/>' );
            } else if(title == 'SKU'){
                $(this).html( '<input type="text" name="" class="search_text_box" id="sku" placeholder="Search" style="width:70px;color:black;border-radius: 4px;height:28px;"/>' );
            }
            else if(title == 'PRICE')
            {
                $(this).html(price);
            } else
            {
                $(this).html( '' );
            }
            $(this).on( 'keyup change', '.search_text_box', function () {
                var selectBy = $("#price_select_by").val();
                var val1 = $("#select_by_value_1").val();
                var val2 = $("#select_by_value_2").val();
                var searchVal = ''
                if(selectBy){
                    searchVal += selectBy
                }
                if(val1){
                    searchVal += ('|' + val1)
                }
                if(val2){
                    searchVal += ('|'+val2)
                }
                if(this.id != 'select_by_value_2'  && this.id != 'select_by_value_1' && this.id != 'item_name' && this.id != 'sku'){
                    searchVal += this.value
                }
                if((this.id != 'item_name' || this.id != 'sku') && (this.id != 'select_by_value_2'  && this.id != 'select_by_value_1')){
                    searchVal = this.value
                }
                    table
                    .column(i)
                    .search(
                        searchVal, true, false
                    ).draw();
            } );
            $( 'select', this ).on( 'change', function () {
                var self = this;

                    if ( table.column(i).search() !== self.value ) {
                    table
                        .column(i)
                        .search( self.value )
                        .draw();
                }


            } );
        });

        $("div#divLoading").removeClass('show');

        var table =   $("#item_listing").DataTable({
                "bSort": false,
                "scrollY":"300px",
                // "autoWidth": true,
                "fixedHeader": true,
                "processing": true,
                "iDisplayLength": 20,
                "serverSide": true,
                "bLengthChange": false,
                "aoColumnDefs": [
                    { "sWidth": "70px", "aTargets": [ 1 ] },
                    { "sWidth": "70px", "aTargets": [ 2 ] },
                    { "sWidth": "55px", "aTargets": [ 8 ] },
                    { "sWidth": "50px", "aTargets": [ 4 ] },
                    { "sWidth": "50px", "aTargets": [ 5 ] },
                    { "sWidth": "85px", "aTargets": [ 6 ] },
                    { "sWidth": "85px", "aTargets": [ 7 ] },
                    { "sWidth": "195px", "aTargets": [ 3 ] },
                ],
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
                  "data": priceFilter,
                  type: 'POST',
                  "dataSrc": function ( json ) {
                        $("div#divLoading").removeClass('show');
                        return json.data;
                    }
                },

                columns :  [
                      {
                          data: "iitemid", render: function(data, type, row){

                              return $("<input>").attr({
                                    type: 'checkbox',
                                    class: "iitemid",
                                    value: data,
                                    name: "selected[]",
                                    "data-order": data,
                              })[0].outerHTML;
                            }
                      },
                      { "data": "vitemname", render: function(data, type, row){
                                return "<span style='max-width: 70px; word-wrap: break-word; display: block;'>"+data+"</span>";
                          }
                      },

                      { "data": "vbarcode"},
                      {"data": "dunitprice"},
                      { "data": "vunitname"},
                      { "data": "vsize"},
                      { "data": "vdepartmentname"},
                      { "data": "vcategoryname"},
                      { "data": "subcat_name"},
                //   fnDrawCallback : function() {
                //         if ($(this).find('tbody tr').length<=1) {
                //             $(this).find('.dataTables_empty').hide();
                //         }
                //     }
                ],
                fnDrawCallback : function() {
                        if ($(this).find('tbody tr').length<=1) {
                            $(this).find('.dataTables_empty').hide();
                            $('#item_listing_paginate').hide();
                        }else{
                            $('#item_listing_paginate').show();
                        }
                        
                       
                    }
        });
            $("#item_listing_filter").hide();
            $("#item_listing_processing").remove();

        $("#item_listing2_processing").remove();
        $("#item_listing2_filter").hide();

        $('[data-toggle="tooltip"]').tooltip();


        <?php if(!empty($data['selected_discount_items'])) { ?>
            var selected_discounted_items = "<?php echo  json_encode($data['selected_discount_items']); ?>";
            var prom_type = $("#promotion_type").val();

            $.each($.parseJSON(selected_discounted_items), function( index,value ) {
                itemsAddedToDiscountedItems.push(value);
                $(this).prop('checked',false);
                $.ajax({
                    url: get_saved_discounted_items_ajax_url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'post',
                    data : {discountedItems : [value],disc_type : disc_type, disc_value : disc_value,prom_type:prom_type},
                    success:function(data){
                        if(data)
                        {
                            $("#item_listing3 tbody").append(data);
                        }
                        else
                        {

                        }

                    }
                })
            });
        <?php }?>

        $('form#form-buydown').validate({ // initialize the plugin
            rules: {
                buydown_name: {
                    required: true,
                },
                buydown_code: {
                    required: true,
                },
            },
            messages: {
                buydown_name: "BuyDownn Name is Required",
                buydown_code: "BuyDownn Code is Required",
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        });

        var itemRow = ''; // table row for preselected items ie. in edit page
        var discountForItems = [];


    })

    $(document).on("change","#dept_code",function(){
        var get_category_ajax;
        if($(this).val() != "")
        {
            $('#category_code').attr("placeholder", "Loading...");
            var get_categories_url = "<?php echo $data['get_categories_url']; ?>";
            get_categories_url = get_categories_url.replace(/&amp;/g, '&');

            var get_department_items_url = "<?php echo $data['get_department_items_url']; ?>";
            get_department_items_url = get_department_items_url.replace(/&amp;/g, '&');
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

    $(document).on("change","#category_code",function(){
        var get_sub_category_ajax;
        if($(this).val() != "")
        {
            $('#sub_category_id').attr("placeholder", "Loading...");
            var get_sub_categories_url = "<?php echo $data['get_sub_categories_url']; ?>";
            get_sub_categories_url = get_sub_categories_url.replace(/&amp;/g, '&');

            var cat_code = [$(this).val()];

            if(get_sub_category_ajax && get_sub_category_ajax.readyState != 4 ){
                get_sub_category_ajax.abort();
            }

            get_sub_category_ajax = $.ajax({
                url: get_sub_categories_url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                type: 'post',
                data : {cat_code : cat_code},
                success:function(data){
                    if(data)
                    {
                        $( '#sub_category_id' ).html( data );
                        $('#sub_category_id').prop("disabled", false);
                    }
                    else
                    {
                        $( '#sub_category_id' ).html( '' );
                        $('#sub_category_id').prop("disabled", true);
                    }

                }
            })
        }
    });

    $("#add_to_buy_items").click(function(){


        if($('#form-buydown').valid() === false){

            bootbox.alert("Please fill in the mandatory fields to proceed.");
            return false;
        } else {

            clickedAddBuyItems = 1;
            existingBuydownItems = [];
            // $(".disabled-text").attr('readonly',true);
            // $(".disabled-select").attr('disabled',true);
            $.each($("input[name='added_buydown_items[]']"), function(){
                var iitemid = $(this).val();
                var vbarcode = $(this).data('vbarcode');
                existingBuydownItems.push({iitemid:iitemid,vbarcode:vbarcode});
            });
// console.log(existingBuydownItems);
            var existingItems = "";

            var sno = 0;
            $.each($("input[name='selected[]']:checked"), function(){
                var selecteditem = $(this).val();
                $.each(existingBuydownItems, function( index, value ) {console.log(value.iitemid);
                    if(value.iitemid == selecteditem)
                    { console.log(value.vbarcode);
                        sno++;
                        existingItems += sno+ ". " + " (" + value.vbarcode + ")<br />";
                    }
                });
            })

            var error_count = 0;
            if(existingItems != "")
            {
                // bootbox.confirm({
                //     message: "The following items already exists in Buydown: <br />" + existingItems + "<br/> Do you want to continue?",
                //     buttons: {
                //         confirm: {
                //             label: 'Yes',
                //             className: 'btn-success'
                //         },
                //         cancel: {
                //             label: 'No',
                //             className: 'btn-danger'
                //         }
                //     },
                //     callback: function (result) {
                //             if(result==true)
                //             {
                //                 addToBuyDown();
                //             }
                //         }
                //     });
                bootbox.alert({
                    size: "small",
                    title: "<span class='text-danger'>ALERT</span>",
                    message: "The following items already exists in Buydown: <br />" + existingItems + "<br/>",
                    callback: function(){ /* your callback code */ }
                });

                error_count++;
            }
            else
            {
                var validate_item_url = "<?php echo $data['validate_item_url'] ;?>";
                validate_item_url = validate_item_url.replace(/&amp;/g, '&');



                var iitemids = [];
                $.each($("input[name='selected[]']:checked"), function(){
                    iitemids.push($(this).val());
                });

                $.ajax({

                    url:validate_item_url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type:'POST',
                    data:{itemid:iitemids},
                    dataType:'json',
                    success:function(response){
                      console.log(response);
                        msg = '';
                        if(response.result != '' && typeof(response.result) != 'undefined'){

                            // $.each(iitemids, this, function(key, value){
                            $.each($("input[name='selected[]']:checked"), function(){
                                var checkbox = $(this);

                                var value = $(this).val();
                                if(jQuery.inArray(value, response.item_ids) !== -1){

                                    checkbox.prop("checked", false);
                                    $("#parent_select").prop('checked',false);
                                    error_count++;
                                }
                            });


                            msg = response.msg.join(',');

                            if(response.length > 1){
                                msg  = msg + ' are ';
                            } else {
                               msg = msg + ' is ';
                            }

                            msg = msg+" already used in other buydown.";

                            $('#error_msg').text(msg);
                            $('#errorModal').modal('show');
                        }

                    }
                });

                // setTimeout(function(){

                //     addToBuyDown();
                // }, 2000);

            }
            console.log(error_count);
            if(error_count <= 0){
                setTimeout(function(){

                    addToBuyDown();
                }, 1000);
            }else{
                error_count = 0;
                $('.iitemid').prop('checked', false);
                $("#parent_select").prop('checked',false);
                // console.log(error_count);
            }

        }
    })

    function addToBuyDown()
    {

        existingBuydownItems = [];
        $.each($("input[name='selected[]']:checked"), function(){

            var selectedItemsToAdd = $(this).val();

            console.log(itemsAddedToBuyItems);
            if(jQuery.inArray($(this).val(), itemsAddedToBuyItems) == -1)
            {
                itemsAddedToBuyItems.push(selectedItemsToAdd);
                console.log( existingBuydownItems );

                if(get_selected_buy_items_ajax && get_selected_buy_items_ajax.readyState != 4 ){
                    // get_selected_buy_items_ajax.abort();
                }
                var buydown_amount = $('#buydown_amount').val();

                var postData = {buyItemids : $(this).val(), buydown_amount: buydown_amount};

                get_selected_buy_items_ajax = $.ajax({
                    url: get_selected_buy_items_url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'post',
                    data : postData,
                    success:function(data){
                        if(data)
                        {
                            $("#item_listing2 tbody").append(data);
                        }
                        else{}
                    }
                })
            } else {
                $('#errorModal').modal('show');
            }
        });
    }


    $(document).on( 'click','#remove_buy_items',  function (e) {
        $('.buydown_items:checked').each(function () {
            itemsAddedToBuyItems.splice($.inArray($(this).val(), itemsAddedToBuyItems),1);
            $(this).closest('tr').remove();
        });
        $("#check_all_buy_items").prop('checked',false);
    });

    $(document).on('click', '#submit_budown', function(e){
        e.preventDefault();

        var added_items = new Array();
        $.each($("input[name='added_buydown_items[]']"), function(){
            var iitemid = $(this).val();
            var vbarcode = $(this).data('vbarcode');
            added_items.push({iitemid:iitemid,vbarcode:vbarcode});
        });
        if(added_items.length > 0){
            $("#form-buydown").submit();
        }else{
            bootbox.alert({
                size: "small",
                title: "<span class='text-danger'>ALERT</span>",
                message: "No any items are added",
                callback: function(){ /* your callback code */ }
            })
            return false;
        }

        $(".disabled-select").attr('disabled',false);
        $(".text-danger").remove();
    });

    $(document).bind( 'keyup change','#promotion_discount_type,#promotion_buy_qty, #promotion_slab_price, #promotion_addl_discount',  function (e) {
        if(clickedAddBuyItems === 1){
            $("#div_refresh_values").show();
        }
    });
</script>

<!-----Validation for item which are already added in some other buydown ----------->
<script>
    $(document).ready(function(){
        var status = "<?php echo $buydown_info->buydown_id; ?>";
        if(status != '' && status != 'undefined' && status == 'Inactive'){
            $('#form-buydown input').attr('readonly', 'readonly');
            $('#form-buydown select').prop("disabled", true);
            $('.btn').prop("disabled", true);
            setTimeout(function(){
                $('.buydown_amt').attr('readonly', 'readonly');
            }, 2000);
            
        }
        
    });
    
    
    // Restricts input for each element in the set of matched elements to the given inputFilter.
    (function($) {
      $.fn.inputFilter = function(inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
          if (inputFilter(this.value)) {
            this.oldValue = this.value;
            this.oldSelectionStart = this.selectionStart;
            this.oldSelectionEnd = this.selectionEnd;
          } else if (this.hasOwnProperty("oldValue")) {
            this.value = this.oldValue;
            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
          } else {
            this.value = "";
          }
        });
      };
    }(jQuery));
    
    $("#buydown_amount").inputFilter(function(value) {
        return /^-?\d*[.,]?\d{0,2}$/.test(value); 
        
    });
    
</script>

@endsection
