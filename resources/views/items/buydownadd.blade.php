@extends('layouts.layout')

@section('title')
  Buy Down
@endsection

@section('main-content')
<style type="text/css">
    .error {
        color:#f56b6b;
        border-color: #f56b6b;
    }
    .bottom{
        /*display: none;*/
        margin-top: 20px;
    }
    .dataTables_info{
        float: right;
    }
    #buydown_name-error{
        color: red;
    }
    #buydown_code-error{
        color: red;
    }
    .pagination{
        margin-left: 0px !important;
    }
    
</style>
<link rel="stylesheet" href="{{ asset('asset/css/promotion.css') }}">
<div id="content">
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase" > Buy Down</span>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
   
    <section class="section-content py-6">
        <div class="container">
            <div class="mytextdiv">
                <div class="mytexttitle font-weight-bold text-uppercase">
                    BUYDOWN INFO
                </div>
                <div class="divider font-weight-bold"></div>
            </div>
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

            <div class="panel">
                <form action="<?php echo $data['action'] ?>" method="post" enctype="multipart/form-data" id="form-buydown" class="form-horizontal">
                    @csrf
                    <?php if(session()->get('hq_sid') == 1){ ?>
                        <input type="hidden" name="stores_hq" id="hidden_stores_hq" val="">
                    <?php } ?>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputFirstname" class="p-2 float-left text-uppercase">BuyDown Name</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" placeholder="BUYDOWN NAME" name="buydown_name" autocomplete="off" id="buydown_name" maxlength="100" value="<?php echo isset($data['buydown_id']) ? $data['buydown_name'] : ''; ?>"   class="form-control promo-fields" />
                                        <div id="buydown_name_validate"></div>
                                        @if($data['error_buydown_name'])
                                            <div class="text-danger">{{ $data['error_buydown_name'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left text-uppercase">BuyDown Code</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" placeholder="Buydown Code" name="buydown_code" autocomplete="off" id="buydown_code" maxlength="10" value="<?php echo isset($data['buydown_id']) ? $data['buydown_code'] : ''; ?>"  class="form-control promo-fields" />
                                        <div id="buydown_code_validate"></div>
                                        @if($data['error_buydown_code'])
                                            <div class="text-danger">{{ $data['error_buydown_code'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left text-uppercase">Status</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <select name="status" id="status" class="form-control promo-fields">
                                            <option value="Active" selected="">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputFirstname" class="p-2 float-left text-uppercase">BuyDown Amount</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="buydown_to_amt" placeholder="BUYDOWN AMOUNT" autocomplete="off" id="buydown_amount" maxlength="10" value="<?php echo isset($data['buydown_id'] ) ? $data['buydown_to_amt'] : ''; ?>"  class="form-control promo-fields" />
                                        <div id="buydown_amt_validate"></div>
                                        <div id="buydown_amt"></div>
                                        @if(isset($data['error_buydown_to_amt']))
                                            <div class="text-danger">{{ $data['error_buydown_to_amt'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left text-uppercase">From Date</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" class="form-control promo-fields"   placeholder="From Date" name="buydown_from_date" autocomplete="off" id="buydown_from_date"  value="<?php echo isset($data['buydown_id']) ?  date("m-d-Y", strtotime($data['buydown_from_date'])) : ''; ?>" />
                                        <div id="buydown_from_date_validate"></div>
                                        @if($data['error_buydown_from_date'])
                                            <div class="text-danger">{{ $data['error_buydown_from_date'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left text-uppercase">TO DATE</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="buydown_to_date" placeholder="TO DATE" autocomplete="off" id="buydown_to_date" value="<?php echo isset($data['buydown_id']) ? date("m-d-Y", strtotime($data['buydown_to_date'])) : ''; ?>"  class="form-control promo-fields" />
                                        <div id="buydown_to_date_validate"></div>
                                        @if($data['error_buydown_to_date'])
                                            <div class="text-danger">{{ $data['error_buydown_to_date'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        
                        
                            <div class="mytextdiv">
                                <div class="mytexttitle font-weight-bold text-uppercase">
                                    ADD ITEMS <span class="bg-dark" style="font-size:8px !important; border-radius:5px; color: #fff !important; padding: 5px 10px;">Show Add items</span>
                                </div>
                                <div class="divider font-weight-bold"></div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="item_listing" class="table" style="font-size:11px; border-collapse: separate; border-spacing:0 5px !important;">
                                            <thead style="background-color: #286fb7!important;">
                                                <tr>
                                                    <!--<th style="width: 1px;" class="text-center"><input type="checkbox" id="parent_select" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" style="color: black;"/></th>-->
                                                    <th style="width: 1px;" class="text-center">
                                                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" style="color: black;"/>
                                                    </th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">ITEM NAME</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">SKU</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">PRICE</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">UNIT</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">SIZE</th>
                                                    <!--<th>TYPE</th>-->
                                                    <th class="col-xs-1 headername text-uppercase text-light">DEPT.</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">CATEGORY</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">SUB CAT</th>
                                                    <!--<th>SUPPLIER</th>
                                                    <th>MFR</th>-->
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>


                        
                            <div class="row mt-5 mb-5" style = "justify-content: center;">
                                <div class="text-center">
                                    <span>
                                        <input type="button" id="add_to_buy_items"  style="font-size: 16px; border-radius: 5px; background-color: #286fb7; " class="btn text-light" value="Add to BuyDown">
                                    </span>
                                </div>
                            </div>
                        
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="item_listing2" class="table" style="font-size:11px; border-collapse: separate; border-spacing:0 5px !important;">
                                            <thead style="background-color: #286fb7!important;">
                                                <tr>
                                                    <th style="vertical-align: inherit;" style="width: 1px;" class="text-center">
                                                        <input type="checkbox" id="check_all_buy_items" onclick="$('input[name*=\'added_promotion_items\']').prop('checked', this.checked);" style="color: black;"/>
                                                    </th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">ITEM NAME</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">SKU</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">Live Cost</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">Live Price</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">INVOICE COST</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">PRICE BEFORE BUYDOWN</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">BUYDOWN AMOUNT</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light">COST AFTER BUYDOWN</th>
                                                    <th class="col-xs-1 headername text-uppercase text-light" >PRICE AFTER BUYDOWN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-center" style="padding-bottom:10px; padding-top: 20px;  ">
                                        <input type="button" class="btn btn-danger" style="font-size: 16px; border-radius: 5px;"  id="remove_buy_items" value="Remove Buydown Items">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button"  style="font-size: 16px; border-radius: 5px;" form="form-customer" data-toggle="tooltip" id="submit_budown" title="save" class="btn btn-primary"><i class="fa fa-save"> Save</i></button>
                                <a href="/buydown"  style="font-size: 16px; border-radius: 5px;" data-toggle="tooltip" title="cancel" class="btn btn-danger"><i class="fa fa-reply"></i> Cancel</a>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </section>
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

<?php if(session()->get('hq_sid') == 1){ ?>
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Select the stores in which you want to Add New Buydown:</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div>
                <span style="color: blue;">Note: Please make sure all the items are exists in child store also</span>
                <br>
                <span style="color: red;">Note: Items present in any active buydowns will not be added</span>
                
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
                <tbody id="data_stores">
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check stores" id="stores" name="stores" value="{{ $stores->id }}">
                                </div>
                            </td>
                            <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" id="save_btn" class="btn btn-success" data-dismiss="modal">Save</button>
          </div>
        </div>
      </div>
    </div>
    
<?php } ?>

<style>
    .disabled {
    pointer-events:none; //This makes it not clickable
 
    }

</style>



@endsection

@section('page-script')

<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
 --}}

<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>


<link type="text/css" href="{{ asset('stylesheet/select2/css/select2.min.css') }}" rel="stylesheet" />

<script src="{{ asset('javascript/select2/js/select2.min.js') }}"></script>




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
        <?php if(!$data['buydown_id']) {?>
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

        var a = "<?php echo $data['buydown_id']; ?>";
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
                alert("Second value must be greater than first value!");
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
                ]
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
            alert("Please fill in the mandatory fields to proceed.");
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        } else {
            clickedAddBuyItems = 1;
            existingBuydownItems = [];
            $.each($("input[name='added_buydown_items[]']"), function(){
                var iitemid = $(this).val();
                var vbarcode = $(this).data('vbarcode');
                existingBuydownItems.push({iitemid:iitemid,vbarcode:vbarcode});
            });
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
            console.log(existingItems);
            if(existingItems != "")
            {
                alert("The following items already exists in Buydown: <br />" + existingItems + "<br/>");
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
                if(iitemids.length > 0){
                    $.ajax({
                        url:validate_item_url,
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                        },
                        type:'POST',
                        data:{itemid:iitemids},
                        dataType:'json',
                        success:function(response){
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
                                alert( msg );
                            }
    
                        }
                    });
                }else {
                    alert("No item Selected to add to buydown")
                }


            }
            if(error_count <= 0){
                setTimeout(function(){
                    addToBuyDown();
                }, 1000);
            }else{
                error_count = 0;
                $('.iitemid').prop('checked', false);
                $("#parent_select").prop('checked',false);
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
            <?php if(session()->get('hq_sid') == 1) { ?>
                $('#myModal').modal('show');
            <?php } else { ?>
                $("#form-buydown").submit();
            <?php } ?>
        }else{
            alert("No any items are added");
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
    
    
var stores = [];
stores.push("{{ session()->get('sid') }}");
$('#selectAllCheckbox').click(function(){
    if($('#selectAllCheckbox').is(":checked")){
        $(".stores").prop( "checked", true );
    }else{
        $( ".stores" ).prop("checked", false );
    }
});
       
        
$('#save_btn').click(function(){
    $.each($("input[name='stores']:checked"), function(){            
        stores.push($(this).val());
    });
    $("#hidden_stores_hq").val(stores);
    $("#form-buydown").submit();
})
</script>

<!-----Validation for item which are already added in some other buydown ----------->
<script>
    $(document).ready(function(){
        var status = "<?php echo $data['status']; ?>";
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
