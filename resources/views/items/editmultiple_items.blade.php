@extends('layouts.layout')

@section('title', 'Edit Multiple Items')

@section('main-content')

    <div id="content">

        <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
            <div class="container">
                <div class="collapse navbar-collapse" id="main_nav">
                    <div class="menu">
                        <span class="font-weight-bold text-uppercase">Edit Items</span>
                    </div>
                    <div class="nav-submenu">
                        <button title="Update" id='buttonEditMultipleItems'
                            class="btn btn-gray headerblack  buttons_menu"><i class="fa fa-save"></i>&nbsp;&nbsp;Edit
                            multiple item</button>
                    </div>
                </div> <!-- navbar-collapse.// -->
            </div>
        </nav>

        <div class="container section-content padding-left-right">
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

                    <div class="row col-md-12">
                        <p class="text-warning col-md-8" style="margin-left:0px; display:inline-block;">
                            <strong>Warning!</strong> If you are filter through serach input box, then after input click
                            FILTER button.
                        </p>
                        <div class="col-md-2"></div>
                        <div class="col-md-2" style="display:inline-block; padding-bottom: 12px;">
                            <button title="Filter" id='buttonfillter' class="btn button-blue basic-button-small"><i
                                    class=""></i>&nbsp;&nbsp;FILTER</button>

                        </div>

                    </div>

                    <div class="row" id="div_item_listing">
                        <div class="col-md-12">
                            <div class="box-body table-responsive">
                                <table id="item_listing" class="table table-striped table-hover promotionview"
                                    style="width: 100%; font-size:13px;">
                                    <thead>
                                        <tr class="header-color" style="font-size: 11px;">
                                            <th style="width: 1px;" class="text-center">
                                                <input type="checkbox" name="selected_items_id[]" id="main_checkbox"
                                                    value=""
                                                    onclick="$('input[name*=\'selected_items_id\']').prop('checked', this.checked);"
                                                    style="color:black;" checked />
                                            </th>
                                            <th class="text-left text-uppercase" style="width: 10%;">&nbsp;&nbsp;SKU
                                                <div class="po-has-search">
                                                    <span class="fa fa-search form-control-feedback"></span>
                                                    <input type="text" name="" id="sku"
                                                        class="form-control table-heading-fields text-center search_text_box"
                                                        placeholder="SEARCH" style="width: 110%; padding-left: 5px;">
                                                </div>
                                            </th>
                                            <th class="text-left text-uppercase" style="width: 10%;">&nbsp;&nbsp;Item
                                                Name</a>
                                                <div class="po-has-search">
                                                    <span class="fa fa-search form-control-feedback"></span>
                                                    <input type="text"
                                                        class="form-control table-heading-fields text-center search_text_box"
                                                        id="item_name" placeholder="SEARCH"
                                                        style="width: 100%; padding-left: 5px;">
                                                </div>
                                            </th>
                                            <th class="text-uppercase no-sort" style="width: 9%; padding-left: 0px;">
                                                &nbsp;Department
                                                <div class="adjustment-has-search">
                                                    <select class='table-heading-fields' name='dept_code' id='dept_code'
                                                        style="padding-left: 7px;">
                                                        <option value='all'>All</option>";
                                                        <?php
                                                      foreach($data['departments'] as $department){
                                                    ?>
                                                        <option value='<?= $department['vdepcode'] ?>'>
                                                            <?= $department['vdepartmentname'] ?></option>;
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </th>

                                            <th class="text-uppercase no-sort" style="width: 9%; padding-left: 0px;">
                                                Category
                                                <div class="adjustment-has-search">
                                                    <select class='table-heading-fields' name="category_code"
                                                        id="category_code" style="padding-left: 7px;">
                                                        <option value='all'>All</option>

                                                    </select>
                                                </div>
                                            </th>
                                            <th class="text-uppercase no-sort" style="width: 9%; padding-left: 0px;">Sub
                                                Category
                                                <div class="adjustment-has-search">
                                                    <select class='table-heading-fields' name="sub_category_id"
                                                        id="sub_category_id" style="padding-left: 7px;">
                                                        <option value='all'>All</option>

                                                    </select>
                                                </div>
                                            </th>
                                            <th class="text-uppercase no-sort" style="width: 9%; padding-left: 0px;">Item
                                                Group
                                                <div class="adjustment-has-search">
                                                    <select class='table-heading-fields' name='item_group'
                                                        id='item_group_id' style="padding-left: 7px;">
                                                        <option value='all'>All</option>";
                                                        <?php
                                                      foreach($data['itemGroups'] as $itemgroup){
                                                    ?>
                                                        <option value='<?= $itemgroup['iitemgroupid'] ?>'>
                                                            <?= $itemgroup['vitemgroupname'] ?></option>;
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </th>

                                            <th class="text-uppercase no-sort" style="width: 23%; padding-left: 0px;">PRICE
                                                <div class="adjustment-has-search">

                                                    <select class='table-heading-fields' id='price_select_by'
                                                        name='price_select_by' style='width:55%; padding-left: 5px;'>
                                                        <option value="greater" selected>Greater than</option>
                                                        <option value="less">Less than</option>
                                                        <option value="equal">Equal to</option>
                                                        <option value="between">Between</option>
                                                    </select>
                                                    <span id='selectByValuesSpan'>
                                                        <input type='text' autocomplete='off' name='select_by_value_1'
                                                            id='select_by_value_1'
                                                            class='search_text_box table-heading-fields'
                                                            placeholder='Enter Amount'
                                                            style='width:40%; color:black; height:28px; padding-left: 0px;'
                                                            value='' />
                                                    </span>

                                                </div>
                                            </th>

                                            <th class="text-uppercase no-sort" style="width: 9%; padding-left: 0px;">Tax
                                                <div class="adjustment-has-search">

                                                    <select class='table-heading-fields' name='tax' id='tax'
                                                        style="padding-left: 0px;">
                                                        <option value='all'>All Taxes</option>
                                                        <option value='tax1'>Tax 1</option>
                                                        <option value='tax2'>Tax 2</option>
                                                        <option value='tax3'>Tax 3</option>
                                                        <option value='notax'>No Tax</option>
                                                    </select>

                                                </div>
                                            </th>

                                            <th class="text-uppercase no-sort" style="width: 9%; padding-left: 0px;">Vendor
                                                <div class="adjustment-has-search">
                                                    <select class='table-heading-fields' name='supplier_code'
                                                        id='supplier_code' style="padding-left: 7px;">
                                                        <option value='all'>All</option>
                                                        <?php
                                                    foreach($data['suppliers'] as $supplier){
                                                  ?>
                                                        <option value="<?= $supplier['isupplierid'] ?>">
                                                            <?= $supplier['vcompanyname'] ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </th>

                                            <th class="text-uppercase no-sort" style="width: 9%; padding-left: 0px;">Food
                                                Item
                                                <div class="adjustment-has-search">

                                                    <select class='table-heading-fields' name='food_item' id='food_item'
                                                        style="padding-left: 7px;">
                                                        <option value='all'>All</option>
                                                        <option value='Y'>Yes</option>
                                                        <option value='N'>No</option>
                                                    </select>

                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('page-script')

    <style type="text/css">
        .span_field span {
            display: inline-block;
        }

        .padding-left-right {
            padding: 0 4% 0 4%;
        }

    </style>
    <style type="text/css">
        .table.table-bordered.table-striped.table-hover thead>tr {
            background: #03a9f4 none repeat scroll 0 0 !important;
        }

        table#item_listing th td {
            table-layout: fixed;
            word-wrap: break-word;
        }

        .scroll-dialog {
            overflow-y: initial !important
        }

        .scroll-body {
            height: 500px;
            overflow-y: auto;
        }

        .padding-left-right {
            padding: 0 2% 0 2%;
        }

    </style>

    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/purchaseorder.css') }}">

    <link href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('javascript/bootbox.min.js') }} "></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('asset/css/reportline.css') }}">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    <script>
        var itemTotalIds;
        var uncheckedBoxes = {};

        var session_items_total_ids;
        var items_total_ids;

        var main_checkbox = $('#main_checkbox').is(":checked");


        $(document).on('change', '#price_select_by', function() {
            var select_by = $(this).val();
            var select_by_value1 = $('#select_by_value1').val() === undefined ? '' : $('#select_by_value1').val();
            var select_by_value2 = $('#select_by_value2').val() === undefined ? '' : $('#select_by_value2').val();

            var html = '';
            if (select_by === 'between') {

                html =
                    '<input type="number" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box table-heading-fields" placeholder="Enter" style="color:#000000; height:28px; padding-left: 1px; padding-right: 1px; width:50px;" value="' +
                    select_by_value1 + '"/>';
                html +=
                    '<input type="number" autocomplete="off" name="select_by_value_2" id="select_by_value_2" class="search_text_box table-heading-fields" placeholder="Amt" style="color:#000000; height:28px; padding-left: 1px; padding-right: 1px; width:50px;" value="' +
                    select_by_value2 + '"/>'

                $(this).css({
                    'width': 100
                });

            } else {

                html =
                    '<input type="number" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box table-heading-fields" placeholder="Amt" style="color:#000000; height:28px; width:60px; padding-left: 1px;" value="' +
                    select_by_value1 + '"/>'
                // $('#selectByValuesSpan').html('not between');
                $(this).css({
                    'width': 64
                });
            }
            $('#selectByValuesSpan').html(html);

        });



        $(document).ready(function() {

            var url = "<?php echo $data['searchitem']; ?>";

            var price_select_by = $('#price_select_by').val();
            var select_by_val1 = $('#select_by_value_1').val();
            var select_by_val2 = $('#select_by_value_2').val();

            $('.search_text_box', this).on('keyup change', function() {

                if (this.value != '') {
                    $(this).closest('div').find('.fa-search').hide();

                } else {
                    $(this).closest('div').find('.fa-search').show();
                }
            });

            $(document).on('input', '#select_by_value_2, #select_by_value_1', function() {

                select_by_val1 = parseFloat($('#select_by_value_1').val());
                select_by_val2 = parseFloat($('#select_by_value_2').val());

                if ($('#price_select_by').val() == 'between' && typeof(select_by_val1) != "undefined" &&
                    select_by_val1 !== null && typeof(select_by_val2) != "undefined" && select_by_val2 !==
                    null && select_by_val1 >= select_by_val2) {
                    bootbox.alert({
                        size: 'small',
                        title: " ",
                        message: "Second value must be greater than first value!"
                    });
                }
            });

            var priceFilter = {
                "price_select_by": price_select_by,
                "select_by_value_1": select_by_val1,
                "select_by_value_2": select_by_val2
            }



            $('#item_listing thead tr th').each(function(i) {


                $(this).on('blur', '.search_text_box', function() {

                    if (this.value != '') {
                        $(this).closest('div').find('.fa-search').hide();

                    } else {
                        $(this).closest('div').find('.fa-search').show();
                    }

                    var selectBy = $("#price_select_by").val();
                    var val1 = $("#select_by_value_1").val();
                    var val2 = $("#select_by_value_2").val();

                    // var val1 = ''
                    // var val2 = ''
                    // $("select_by_value_2").val()
                    var searchVal = ''
                    if (selectBy) {
                        searchVal += selectBy
                    }
                    if (val1) {
                        searchVal += ('|' + val1)
                        // $('#main_checkbox').prop('checked', true);
                        $('#main_checkbox').css('color', 'black');
                    }
                    if (val2) {
                        searchVal += ('|' + val2)
                        // $('#main_checkbox').prop('checked', true);
                        $('#main_checkbox').css('color', 'black');
                    }
                    if (this.id != 'select_by_value_2' && this.id != 'select_by_value_1' && this
                        .id != 'item_name' && this.id != 'sku') {
                        searchVal += this.value
                    }
                    if ((this.id != 'item_name' || this.id != 'sku') && (this.id !=
                            'select_by_value_2' && this.id != 'select_by_value_1')) {
                        searchVal = this.value
                        // $('#main_checkbox').prop('checked', true);
                        $('#main_checkbox').css('color', 'black');
                    }

                    $('.alert').remove();
                    uncheckedBoxes = {};

                    table
                        .column(i)
                        .search(
                            searchVal, true, false
                        ).draw();

                    $("div#divLoading").addClass('show');
                    $('#buttonEditMultipleItems').prop('disabled', true);
                });

                $('select', this).on('change', function() {

                    var self = this;
                    if (table.column(i).search() !== self.value) {

                        uncheckedBoxes = {};
                        $('.alert').remove();

                        table
                            .column(i)
                            .search(self.value)
                            .draw();

                        $("div#divLoading").addClass('show');
                        $('#buttonEditMultipleItems').prop('disabled', true);
                    }
                });
            });


            var showPaginationPrevNextButtons = false;
            var table = $("#item_listing").DataTable({
                "bSort": false,
                // "scrollY":"350px",
                // "autoWidth": true,
                "fixedHeader": true,
                "processing": true,
                "iDisplayLength": 20,
                "serverSide": true,
                "bLengthChange": false,
                "aoColumnDefs": [{
                        "sWidth": "190px",
                        "aTargets": [7]
                    },

                ],
                //"autoWidth": true,

                "language": {
                    search: "_INPUT_",
                    searchPlaceholder: "Search..."
                },
                // "fnPreDrawCallback": function( oSettings ) {

                //     main_checkbox = $('#main_checkbox').is(":checked");

                // },
                "dom": '<"mysearch"lf>rt<"bottom"ip>',
                "ajax": {
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                    },
                    "data": function(d) {
                        d["m_check"] = $('#main_checkbox').is(":checked");

                    },
                    type: 'POST',
                    "dataSrc": function(json) {
                        // $("div#divLoading").removeClass('show');

                        // if(showPaginationPrevNextButtons){
                        //   $("#item_listing_paginate").show();
                        // } else {
                        //   $("#item_listing_paginate").hide();
                        // }

                        if (json.data.length != 0) {
                            $(".bottom").show();

                        } else {
                            $(".bottom").hide();

                        }
                        session_items_total_ids = json.session_items_total_ids;
                        items_total_ids = json.items_total_ids;
                        return json.data;
                    }
                },

                columns: [{
                        data: "iitemid",
                        render: function(data, type, row) {

                            return $("<input>").attr({
                                checked: !uncheckedBoxes[data],
                                type: 'checkbox',
                                class: "iitemid",
                                value: data,
                                name: "selected_items_id[]",
                                "data-order": data,
                            })[0].outerHTML;

                        }
                    },
                    {
                        "data": "barcode"
                    },
                    {
                        "data": "itemname",
                        render: function(data, type, row) {
                            return "<span style='max-width: 70px; word-wrap: break-word; display: block;'>" +
                                data + "</span>";
                        }
                    },
                    {
                        "data": "department"
                    },
                    {
                        "data": "category"
                    },
                    {
                        "data": "sub_category"
                    },
                    {
                        "data": "item_group"
                    },
                    {
                        "data": "unitprice"
                    },
                    {
                        "data": "taxes"
                    },
                    {
                        "data": "supplier"
                    },
                    {
                        "data": "fooditem"
                    },

                ],
                rowCallback: function(row, data, index) {

                },
                fnDrawCallback: function() {
                    if ($(this).find('tbody tr').length <= 1) {
                        $(this).find('.dataTables_empty').hide();
                    }


                    // var get_session_value_url = "<?php echo $data['get_session_value']; ?>";
                    // get_session_value_url = get_session_value_url.replace(/&amp;/g, '&');

                    // $.getJSON(get_session_value_url, function(result){

                    //     console.log(result);

                    // });

                }
            }).on('draw', function() {
                if ($('#main_checkbox').prop("checked") == true) {

                    $('.iitemid').prop('checked', true);
                } else {
                    $('.iitemid').prop('checked', false);
                }
                // console.log($(this).find('tbody tr .iitemid').length);
                if ($(this).find('tbody tr .iitemid').length > 0) {
                    $('#buttonEditMultipleItems').prop('disabled', false);
                }
                $("div#divLoading").removeClass('show');
            });
            
            /*$('button').click( function() {
                var data = table.$('input, select').serialize();
                alert(
                    "The following data would have been submitted to the server: \n\n"+
                    data.substr( 0, 120 )+'...'
                );
                return false;
            } );*/
            
            $(document).on('change', '#main_checkbox', function(event) {
                event.preventDefault();
                var set_unset_session_value_url = "<?php echo $data['set_unset_session_value']; ?>";

                set_unset_session_value_url = set_unset_session_value_url.replace(/&amp;/g, '&');

                if ($(this).is(":checked")) {
                    var check_value = 'set';
                    items_total_ids = session_items_total_ids;
                } else {
                    var check_value = 'unset';
                    items_total_ids = '';
                }

                // console.log(check_value);
                set_unset_session_value_url = set_unset_session_value_url + '?checkbox_value=' +
                    check_value;

                $.getJSON(set_unset_session_value_url, function(result) {

                });

                // main_checkbox = $('#main_checkbox').is(":checked");
                // url = url+'&main_checkbox='+main_checkbox;
                // console.log(main_checkbox);
            });

            $(document).on('click', '.paginate_button', function(event) {
                event.preventDefault();
                $('#buttonEditMultipleItems').prop('disabled', true);
                console.log(394);
                var set_pagination_session_url = "<?php echo $data['set_pagination_session_url']; ?>";

                set_pagination_session_url = set_pagination_session_url.replace(/&amp;/g, '&');

                // set_pagination_session_url = set_pagination_session_url+'&clicked_pagination=1';

                $.getJSON(set_pagination_session_url, function(result) {
                        console.log(result);
                    })
                    .done(console.log(404));
            });



            $("#item_listing_filter").hide();
            $("#item_listing_processing").remove();
            $("#item_listing_paginate").addClass("pull-right");

            // console.log(showPaginationPrevNextButtons);
            /*if(showPaginationPrevNextButtons){
              $("#item_listing_paginate").show();
            } else {
              $("#item_listing_paginate").hide();
            }*/
            // console.log(showPaginationPrevNextButtons);




            $(document).on("change", "#dept_code", function() {

                $('#buttonEditMultipleItems').prop('disabled', true);

                var get_category_ajax;
                if ($(this).val() != "") {
                    $('#category_code').attr("placeholder", "Loading...");
                    var get_categories_url_index = "<?php echo $data['get_categories_url_index']; ?>";
                    get_categories_url_index = get_categories_url_index.replace(/&amp;/g, '&');

                    var dep_code = [$(this).val()];

                    if (get_category_ajax && get_category_ajax.readyState != 4) {
                        get_category_ajax.abort();
                    }

                    get_category_ajax = $.ajax({
                        url: get_categories_url_index,
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                        },
                        type: 'post',
                        data: {
                            dep_code: dep_code
                        },
                        success: function(data) {
                            $("div#divLoading").removeClass('show');
                            // setTimeout(function(){
                            
                            // }, 3500);
                            if (data) {
                                $('#category_code').attr("placeholder", "Select Category");
                                $('#category_code').html(data);
                                $('#category_code').prop("disabled", false);
                                // $('#main_checkbox').prop('checked', true);
                                $('#main_checkbox').css('color', 'black');
                            } else {
                                $('#category_code').html('');
                                $('#category_code').prop("disabled", true);
                            }
                        }
                    })
                }

            });

            $(document).on("change", "#category_code", function() {

                $('#buttonEditMultipleItems').prop('disabled', true);

                var get_sub_category_ajax;
                if ($(this).val() != "") {
                    $('#sub_category_id').attr("placeholder", "Loading...");
                    var get_sub_categories_url = "<?php echo $data['get_sub_categories_url']; ?>";
                    get_sub_categories_url = get_sub_categories_url.replace(/&amp;/g, '&');

                    var cat_code = [$(this).val()];

                    if (get_sub_category_ajax && get_sub_category_ajax.readyState != 4) {
                        get_sub_category_ajax.abort();
                    }

                    get_sub_category_ajax = $.ajax({
                        url: get_sub_categories_url,
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                        },
                        type: 'post',
                        data: {
                            cat_code: cat_code
                        },
                        success: function(data) {
                            if (data) {

                                $('#sub_category_id').html(data);
                                $('#sub_category_id').prop("disabled", false);
                                //   $('#main_checkbox').prop('checked', true);
                                $('#main_checkbox').css('color', 'black');
                            } else {
                                $('#sub_category_id').html('');
                                $('#sub_category_id').prop("disabled", true);
                            }

                        }
                    })
                }
            });


            $(document).on('change', '#main_checkbox', function(event) {
                event.preventDefault();

                if ($(this)[0]['checked']) {
                    $(this).css('color', 'black');
                } else {
                    $(this).prop('checked', false);
                }
            });


            $("#item_listing").on("change", "tbody input[type=checkbox]", function() {
                var check = ($("#item_listing").find('tbody input[type=checkbox]').filter(':checked')
                    .length == $("#item_listing").find('tbody input[type=checkbox]').length);
                if (check) {
                    $("#main_checkbox").prop("checked", check);
                    $("#main_checkbox").css('color', 'black');
                } else {
                    $("#main_checkbox").prop("checked", check);
                }
            });





            $('#div_search_vcategorycode').hide();
            $('#div_search_vdepcode').hide();
            $('#div_search_vsuppliercode').hide();
            $('#div_search_box').hide();
            $('#div_search_vitem_group').hide();
            $('#div_search_vfood_stamp').hide();

        });


        // $("div#divLoading").removeClass('show');

        $(document).on('change', 'input:radio[name="search_radio"]', function(event) {
            event.preventDefault();
            if ($(this).is(':checked') && $(this).val() == 'category') {
                $('#div_search_vcategorycode').show();
                $('#div_search_vdepcode').hide();
                $('#div_search_vsuppliercode').hide();
                $('#div_search_vitem_group').hide();
                $('#div_search_vfood_stamp').hide();
                $('#div_search_box').hide();
            } else if ($(this).is(':checked') && $(this).val() == 'department') {
                $('#div_search_vdepcode').show();
                $('#div_search_vcategorycode').hide();
                $('#div_search_vsuppliercode').hide();
                $('#div_search_vitem_group').hide();
                $('#div_search_vfood_stamp').hide();
                $('#div_search_box').hide();
            } else if ($(this).is(':checked') && $(this).val() == 'supplier') {
                $('#div_search_vsuppliercode').show();
                $('#div_search_vdepcode').hide();
                $('#div_search_vcategorycode').hide();
                $('#div_search_vitem_group').hide();
                $('#div_search_vfood_stamp').hide();
                $('#div_search_box').hide();
            } else if ($(this).is(':checked') && $(this).val() == 'item_group') {
                $('#div_search_vitem_group').show();
                $('#div_search_vsuppliercode').hide();
                $('#div_search_vdepcode').hide();
                $('#div_search_vcategorycode').hide();
                $('#div_search_vfood_stamp').hide();
                $('#div_search_box').hide();
            } else if ($(this).is(':checked') && $(this).val() == 'food_stamp') {
                $('#div_search_vfood_stamp').show();
                $('#div_search_vdepcode').hide();
                $('#div_search_vcategorycode').hide();
                $('#div_search_vsuppliercode').hide();
                $('#div_search_vitem_group').hide();
                $('#div_search_box').hide();
            } else if ($(this).is(':checked') && $(this).val() == 'search') {
                $('#div_search_box').show();
                $('#div_search_vdepcode').hide();
                $('#div_search_vsuppliercode').hide();
                $('#div_search_vitem_group').hide();
                $('#div_search_vfood_stamp').hide();
                $('#div_search_vcategorycode').hide();
            } else {
                $('#div_search_vcategorycode').show();
                $('#div_search_vdepcode').hide();
                $('#div_search_vsuppliercode').hide();
                $('#div_search_vitem_group').hide();
                $('#div_search_vfood_stamp').hide();
                $('#div_search_box').hide();
            }

        });

        $('[data-target="#myModal"]').click(function(event) {
            var checked_items_val = [];
            $('input[name="selected_items_id[]"]:checked').each(function(i) {
                checked_items_val[i] = $(this).val();
            });
            var checked_items_val_string = JSON.stringify(checked_items_val);

            $('input[name="items_id_array"]').val(checked_items_val);
            //$('input[name="items_id_array"]').val(checked_items_val_string);

        });

        $(document).on('submit', 'form#form_item_update', function(event) {

            if ($('input[name="update_dcostprice"]').val() == '') {
                // alert('Please enter cost!');
                bootbox.alert({
                    size: 'small',
                    title: " ",
                    message: "Please enter cost!",
                    callback: function() {}
                });
                return false;
            }

            if ($('input[name="update_dunitprice"]').val() == '') {
                // alert('Please enter price!');
                bootbox.alert({
                    size: 'small',
                    title: " ",
                    message: "Please enter price!",
                    callback: function() {}
                });
                return false;
            }

            if ($('input[name="update_npack_checkbox"]').is(':checked')) {
                if ($('input[name="update_npack"]').val() == '') {
                    // alert('Please enter pack!');
                    bootbox.alert({
                        size: 'small',
                        title: " ",
                        message: "Please enter pack!",
                        callback: function() {}
                    });
                    return false;
                }
            }

            $("div#divLoading").addClass('show');
            // $('form#form_item_update').submit();
        });

        $(document).on('keypress keyup blur', 'input[name="update_dcostprice"],input[name="update_dunitprice"]', function(
            event) {

            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }

        });

        $(document).on('keypress keyup blur', 'input[name="update_npack"]', function(event) {

            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }

        });

        $(document).on('keypress keyup blur',
            'input[name="update_iqtyonhand"], input[name="update_norderqtyupto"],input[name="update_iqty"],input[name="update_ipack"],input[name="update_ireorderpoint"],input[name="update_isequence"]',
            function(event) {

                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }

            });

        $(document).on('keypress keyup blur',
            'input[name="update_dcostprice"],input[name="update_nlevel2"],input[name="update_nlevel3"],input[name="update_nlevel4"],input[name="update_dunitprice"],input[name="update_ndiscountper"],input[name="update_nprice"],input[name="update_npackprice"]',
            function(event) {

                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }

            });

        $(document).on('submit', 'form#form_item_search', function(event) {
            if ($('input:radio[name="search_radio"]').is(':checked') == false) {
                // alert('Please select filter type!');
                bootbox.alert({
                    size: 'small',
                    title: " ",
                    message: "Please select filter type!",
                    callback: function() {}
                });
                return false;
            }

            $("div#divLoading").addClass('show');

        });
    </script>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl scroll-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="<?php echo $data['edit_list']; ?>" method="post" id="form_item_update">
                    @csrf
                    @if (session()->get('hq_sid') == 1)
                        <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
                    @endif
                    <input type="hidden" name="items_id_array" value=''>
                    <input type="hidden" name="search_radio_btn" value="<?php echo isset($data['search_radio']) ? $data['search_radio'] : ''; ?>">
                    <input type="hidden" name="search_find_btn" value="<?php echo isset($data['search_find']) ? $data['search_find'] : ''; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Multiple Items</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body scroll-body">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="mytextdiv">
                                    <div class="mytexttitle font-weight-bold text-uppercase">
                                        PRICE OPTIONS
                                    </div>
                                    <div class="divider font-weight-bold"></div>
                                </div>
                                <br>

                            </div>
                            <div class="panel-body padding-left-right">
                                <div class="row">
                                    <div class="col-md-4 span_field" style="padding-left:10px;padding-right:10px;">
                                        <!--<p><span style="width:10%;">Pack:</span>&nbsp;&nbsp;<span style="width:40%;"><input type="text" name="update_npack" value="1" class="form-control"></span>&nbsp;&nbsp;<span style="width:45%;"><input type="checkbox" name="update_npack_checkbox" value="Y">&nbsp;Update Pack Qty</span></p>-->

                                        <p>
                                            <span>
                                                <input type="checkbox" name="update_dcostprice_checkbox" id="update_dcostprice_checkbox" class="c_checkbox">
                                            </span>
                                            <span style="width:60%; text-transform:uppercase;" class="font-style-13px">COST</span>

                                            <span style="width:30%;">
                                                <input type="text" name="update_dcostprice" value="0" class="form-control font-style-13px" id="cost" readonly>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <input type="checkbox" name="update_dcostprice_increment_checkbox" id="update_dcostprice_increment_checkbox" class="c_checkbox">
                                            </span>
                                            <span style="width:60%; text-transform:uppercase;" class="font-style-13px">increment cost
                                                ($)</span>

                                            <span style="width:30%;">
                                                <input type="text" name="update_dcostprice_increment" value="0" class="form-control font-style-13px" id="inc_cost" readonly>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <input type="checkbox" name="update_dcostprice_increment_percent_checkbox" id="update_dcostprice_increment_percent_checkbox" class="c_checkbox">
                                            </span>
                                            <span style="width:60%; text-transform:uppercase;">increment cost
                                                by (%)</span>

                                            <span style="width:30%;">
                                                <input type="text" name="update_dcostprice_increment_percent" value="0" class="form-control font-style-13px" id="inc_cost_dol" readonly>
                                            </span>
                                        </p>

                                    </div>
                                    <div class="col-md-4" style=" padding-left: 170px;">
                                        <div class="verticalLine"></div>
                                    </div>
                                    <div class="col-md-4 span_field" style="padding-left:10px;padding-right:10px;">
                                        <!--<p><span style="width:20%;">Selling Unit:</span>&nbsp;&nbsp;<span style="width:35%;font-size:1rem;"><input type="text" name="update_nsellunit" value="1" class="form-control"></span>&nbsp;&nbsp;<span style="width:45%;"><input type="checkbox" name="update_nsellunit_checkbox" value="Y">&nbsp;Update Zero Unit</span></p>-->
                                        <!--<p>-->

                                        <p>
                                            <span>
                                                <input type="checkbox" name="update_dunitprice_checkbox" class="p_checkbox" id="update_dunitprice_checkbox">
                                            </span>
                                            <span style="width:60%;font-size:1rem;">PRICE</span>
                                            <span style="width:30%;"><input type="text" name="update_dunitprice" value="0"
                                                    class="form-control" id="price" readonly>
                                            </span>
                                        </p>
                                        <!--  <span style="width:45%;"><input type="checkbox" name="update_dunitprice_checkbox" value="Y">&nbsp;Update Zero Price</span>-->
                                        <!--</p>-->
                                        <p>
                                            <span>
                                                <input type="checkbox" name="update_dunitprice_increment_checkbox" class="p_checkbox" id="update_dunitprice_increment_checkbox">
                                            </span>
                                            <span style="width:60%;font-size:1rem; text-transform:uppercase;">increment
                                                price
                                                ($)</span>
                                                
                                            <span style="width:30%;">
                                                <input type="text" name="update_dunitprice_increment" value="0" class="form-control" id="inc_price" readonly>
                                            </span>
                                        </p>
                                            
                                        <p>
                                            <span>
                                                <input type="checkbox" name="update_dunitprice_increment_percent_checkbox" class="p_checkbox" id="update_dunitprice_increment_percent_checkbox">
                                            </span>
                                            <span style="width:60%;font-size:1rem; text-transform:uppercase;">increment
                                                price
                                                by (%)</span>

                                            <span style="width:30%;">
                                                <input type="text" name="update_dunitprice_increment_percent" value="0" class="form-control" id="inc_price_dol" readonly>
                                            </span>
                                        </p>

                                        {{-- <p>
                                            <span style="width:100%;">

                                                <input type="hidden" name="update_dunitprice_select" value="set as price">
                                                <br>
                                                <input type="checkbox" value="Y"
                                                    name="update_dunitprice_increment">&nbsp;&nbsp;increment price
                                                ($)<br><br>
                                                <input type="checkbox" value="Y"
                                                    name="update_dunitprice_increment_percent">&nbsp;&nbsp;increment price
                                                by (%)
                                            </span>&nbsp;&nbsp;
                                            <!--<span style="width:20%;">Buydown:</span>&nbsp;&nbsp;<span style="width:35%;font-size:1rem;">-->
                                            <input type="hidden" name="update_ndiscountper" value="" class="form-control">
                                        </p> --}}
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="mytextdiv">
                                    <div class="mytexttitle font-weight-bold text-uppercase">
                                        GENERAL OPTIONS
                                    </div>
                                    <div class="divider font-weight-bold"></div>
                                </div>
                            </div>
                            <br><br>
                            <div class="panel-body padding-left-right">
                                <div class="row text-uppercase">
                                    <div class="col-md-4 span_field">

                                        <p><span style="width:35%;" class="font-style-13px">Department</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vdepcode" id="update_vdepcode" class="form-control font-style-13px">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['departments']) && count($data['departments']) > 0){?>
                                                    <?php foreach($data['departments'] as $department){?>
                                                    <option value="<?php echo $department['vdepcode']; ?>"><?php echo $department['vdepartmentname']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p>

                                            <span style="width:35%;" class="font-style-13px">Category</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vcategorycode" id="update_vcategorycode"
                                                    class="form-control font-style-13px">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['categories']) && count($data['categories']) > 0){?>
                                                    <?php foreach($data['categories'] as $category){?>
                                                    <option value="<?php echo $category['vcategorycode']; ?>"><?php echo $category['vcategoryname']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>

                                        <p>
                                            <span style="width:35%;" class="font-style-13px">Sub Category</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_subcat_id" id="update_subcat_id" class="form-control font-style-13px">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['subcategories']) && count($data['subcategories']) > 0 && $data['new_database'] === true){?>
                                                    <?php foreach($data['subcategories'] as $subcategory){ ?>
                                                    <option value="<?php echo $subcategory['subcat_id']; ?>"><?php echo $subcategory['subcat_name']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p style="display:none" ;>
                                            <span style="width:35%;" class="font-style-13px">Item Type</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vitemtype" class="form-control font-style-13px">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['item_types']) && count($data['item_types']) > 0){?>
                                                    <?php foreach($data['item_types'] as $item_type){ ?>
                                                    <option value="<?php echo $item_type; ?>"><?php echo $item_type; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>

                                        <p style="display:none;">
                                            <span style="width:35%;" class="font-style-13px">Unit</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vunitcode" class="form-control font-style-13px; position: relative; left: 7px;">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['units']) && count($data['units']) > 0){?>
                                                    <?php foreach($data['units'] as $unit){ ?>
                                                    <option value="<?php echo $unit['vunitcode']; ?>"><?php echo $unit['vunitname']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p style="display:none;">
                                            <span style="width:35%;" class="font-style-13px">Size</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vsize" class="form-control font-style-13px">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['sizes']) && count($data['sizes']) > 0){?>
                                                    <?php foreach($data['sizes'] as $size){ ?>
                                                    <option value="<?php echo $size['vsize']; ?>"><?php echo $size['vsize']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p>
                                            <span style="width:35%;" class="font-style-13px"> ITEM Group</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_iitemgroupid" class="form-control font-style-13px">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['itemGroups']) && count($data['itemGroups']) > 0){?>
                                                    <?php foreach($data['itemGroups'] as $itemGroup){ ?>
                                                    <option value="<?php echo $itemGroup['iitemgroupid']; ?>"><?php echo $itemGroup['vitemgroupname']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-4 span_field">

                                        <p>
                                            <span style="width:35%;font-size:1rem;">Tax</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="tax[]" id="tax_update" class="form-control tax_update" multiple='true' placeholder="--No Update--" style="width:200px;">
                                                    
                                                    <option value='tax1'>Tax 1</option>
                                                    <option value='tax2'>Tax 2</option>
                                                    <option value='tax3'>Tax 3</option>
                                                    <option value='no'>No Tax</option>
                                                </select>
                                            </span>
                                        </p>
                                        <p>
                                            <span style="width:35%;font-size:1rem;">Age Verification</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vageverify" class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['ageVerifications']) && count($data['ageVerifications']) > 0){?>
                                                    <?php foreach($data['ageVerifications'] as $ageVerification){ ?>
                                                    <option value="<?php echo $ageVerification['vvalue']; ?>"><?php echo $ageVerification['vname']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        {{-- <p class="text-uppercase no-sort" style="width: 9%; padding-left: 0px;">Tax
                                        <div class="adjustment-has-search">

                                            <select class='table-heading-fields' name='tax' id='tax'
                                                style="padding-left: 0px;">
                                                <option value='all'>All Taxes</option>
                                                <option value='tax1'>Tax 1</option>
                                                <option value='tax2'>Tax 2</option>
                                                <option value='tax3'>Tax 3</option>
                                                <option value='no'>No Tax</option>
                                            </select>

                                        </div>
                                        </p> --}}

                                        {{-- <p>
                                            <span style="width:35%;font-size:1rem;">Tax1</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vtax1" id="update_vtax1" class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php //if(isset($data['array_yes_no']) && count($data['array_yes_no']) > 0){
                                                    ?>
                                                    <?php //foreach($data['array_yes_no'] as $k => $array_y_n){
                                                    ?>
                                                    <option value="<?php //echo $k;
?>"><?php //echo $k;
?></option>
                                                    <?php //}
                                                    ?>
                                                    <?php //}
                                                    ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p>
                                            <span style="width:35%;font-size:1rem;">Tax2</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vtax2" id="update_vtax2" class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php //if(isset($data['array_yes_no']) && count($data['array_yes_no']) > 0){
                                                    ?>
                                                    <?php //foreach($data['array_yes_no'] as $k => $array_y_n){
                                                    ?>
                                                    <option value="<?php //echo $k;
?>"><?php //echo $k;
?></option>
                                                    <?php //}
                                                    ?>
                                                    <?php //}
                                                    ?>
                                                </select>
                                            </span>
                                        </p> --}}
                                        <p>
                                            <span style="width:35%;font-size:1rem;">Food Item</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vfooditem" id="update_vfooditem" class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['array_yes_no']) && count($data['array_yes_no']) > 0){?>
                                                    <?php foreach($data['array_yes_no'] as $k => $array_y_n){ ?>
                                                    <option value="<?php echo $k; ?>"><?php echo $array_y_n; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p>
                                            <span style="width:35%;font-size:1rem;">VENDOR</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_vsuppliercode" id="update_vsuppliercode"
                                                    class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['suppliers']) && count($data['suppliers']) > 0 ){?>
                                                    <?php foreach($data['suppliers'] as $supplier){ ?>
                                                    <option value="<?php echo $supplier['vsuppliercode']; ?>"><?php echo $supplier['vcompanyname']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>

                                    </div>
                                    <div class="col-md-4 span_field">
                                        <p>
                                            <span style="width:35%;font-size:1rem;">Unit</span>
                                            <span style="width:60%;">
                                                <select name="update_unit_id" id="update_unit_id" class="form-control" style="position: relative; left: 8px;">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['itemsUnits']) && count($data['itemsUnits']) > 0){ ?>
                                                    <?php foreach($data['itemsUnits'] as $unit){ ?>
                                                    <option value="<?php echo $unit['id']; ?>"><?php echo $unit['unit_name']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p>
                                            <span style="width:35%;font-size:1rem;"> UNIT PER CASE</span>
                                            &nbsp;&nbsp;<span style="width:60%;"><input type="text" name="update_npack"
                                                    value="1" class="form-control">
                                            </span>
                                        </p>

                                        <p>
                                            <span style="width:35%; font-size:1rem;">QOH:</span>
                                            &nbsp;&nbsp;
                                            <span style="width:60%;"><input type="text" name="update_iqtyonhand" value="0"
                                                    class="form-control" disabled style="position: relative; right: 2px;">
                                            </span>
                                            &nbsp;&nbsp;
                                            <span style="width:45%;display:none">
                                                <input type="checkbox" name="update_iqtyonhand" value="Y"
                                                    disabled>&nbsp;Update Zero QOH
                                            </span>
                                        </p>


                                        <p style="position: relative; bottom: 8px;"> <span style="width:35%;font-size:1rem;">Bottle Deposit</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <input name="update_nbottledepositamt" value="" type="text"
                                                    class="form-control" style="position: relative; left: 2px;">
                                            </span>
                                        </p>



                                        <p style="display:none;">
                                            <span style="width:35%;font-size:1rem;">Manufacturer</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_manufacturerid" class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['manufacturers']) && count($data['manufacturers']) > 0 && $data['new_database'] === true){?>
                                                    <?php foreach($data['manufacturers'] as $manufacturer){ ?>
                                                    <option value="<?php echo $manufacturer['mfr_id']; ?>"><?php echo $manufacturer['mfr_name']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p style="display:none;">
                                            <span style="width:35%;font-size:1rem;">Mfg Promo Desc</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_aisleid" class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['aisles']) && count($data['aisles']) > 0 && $data['new_database'] === true){?>
                                                    <?php foreach($data['aisles'] as $aisle){ ?>
                                                    <option value="<?php echo $aisle['Id']; ?>"><?php echo $aisle['aislename']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p style="display:none;">
                                            <span style="width:35%;font-size:1rem;">Mfg Buy Down Desc</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_shelfid" class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['shelfs']) && count($data['shelfs']) > 0 && $data['new_database'] === true){?>
                                                    <?php foreach($data['shelfs'] as $shelf){ ?>
                                                    <option value="<?php echo $shelf['Id']; ?>"><?php echo $shelf['shelfname']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                        <p style="display:none;">
                                            <span style="width:35%;font-size:1rem;">Mfg MultiPack Desc</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_shelvingid" class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['shelvings']) && count($data['shelvings']) > 0 && $data['new_database'] === true){?>
                                                    <?php foreach($data['shelvings'] as $shelving){ ?>
                                                    <option value="<?php echo $shelving['id']; ?>"><?php echo $shelving['shelvingname']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>



                        <div class="panel panel-default" style="display:none;">

                            <div class="panel-heading" style="display:none;">
                                <div class="mytextdiv">
                                    <div class="mytexttitle font-weight-bold text-uppercase">
                                        OTHER OPTIONS
                                    </div>
                                    <div class="divider font-weight-bold"></div>
                                </div>
                            </div>
                            <br><br>
                        </div>
                        <div class="panel-body padding-left-right" style="display:none;">
                            <div class="row text-uppercase">
                                <div class="col-md-4 span_field" style="padding-left:10px;padding-right:10px;">

                                    <p>
                                        <span style="width:35%;font-size:1rem;">WCI Item</span>&nbsp;&nbsp;
                                        <span style="width:60%;">
                                            <select name="update_wicitem" class="form-control">
                                                <option value="no-update">-- No Update --</option>
                                                <?php if(isset($data['arr_y_n']) && count($data['arr_y_n']) > 0){?>
                                                <?php foreach($data['arr_y_n'] as $k => $array_y_n){ ?>
                                                <option value="<?php echo $k; ?>"><?php echo $array_y_n; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </p>
                                    <p>
                                        <span style="width:35%;font-size:1rem;">Station</span>&nbsp;&nbsp;
                                        <span style="width:60%;">
                                            <select name="update_stationid" class="form-control">
                                                <option value="no-update">-- No Update --</option>
                                                <?php if(isset($data['stations']) && count($data['stations']) > 0){?>
                                                <?php foreach($data['stations'] as $station){ ?>
                                                <option value="<?php echo $station['Id']; ?>"><?php echo $station['stationname']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </p>
                                    <p>
                                        <span style="width:35%;font-size:1rem;">Barcode Type</span>&nbsp;&nbsp;
                                        <span style="width:60%;">
                                            <select name="update_vbarcodetype" class="form-control">
                                                <option value="no-update">-- No Update --</option>
                                                <?php if(isset($data['barcode_types']) && count($data['barcode_types']) > 0){?>
                                                <?php foreach($data['barcode_types'] as $barcode_type){ ?>
                                                <option value="<?php echo $barcode_type; ?>"><?php echo $barcode_type; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </p>
                                    <p>
                                        <span style="width:35%;font-size:1rem;">Discount</span>&nbsp;&nbsp;
                                        <span style="width:60%;">
                                            <select name="update_vdiscount" class="form-control">
                                                <option value="no-update">-- No Update --</option>
                                                <?php if(isset($data['arr_y_n']) && count($data['arr_y_n']) > 0){?>
                                                <?php foreach($data['arr_y_n'] as $k => $array_y_n){ ?>
                                                <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </p>
                                    <p>
                                        <span style="width:35%;font-size:1rem;">Status</span>&nbsp;&nbsp;
                                        <span style="width:60%;">
                                            <select name="update_estatus" class="form-control">
                                                <?php if(isset($data['array_status']) && count($data['array_status']) > 0){?>
                                                <?php foreach($data['array_status'] as $k => $array_sts){ ?>
                                                <option value="<?php echo $k; ?>"><?php echo $array_sts; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-4 span_field" style="padding-left:10px;padding-right:10px;">
                                    <p>
                                        <span style="width:35%;font-size:1rem;">Liability</span>&nbsp;&nbsp;
                                        <span style="width:60%;">
                                            <select name="update_liability" class="form-control">
                                                <option value="no-update">-- No Update --</option>
                                                <?php if(isset($data['array_yes_no']) && count($data['array_yes_no']) > 0){?>
                                                <?php foreach($data['array_yes_no'] as $k => $array_y_n){ ?>
                                                <option value="<?php echo $k; ?>"><?php echo $array_y_n; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </p>
                                    <p><span style="width:35%;font-size:1rem;">Re-Order Point</span>&nbsp;&nbsp;<span
                                            style="width:60%;"><input type="text" name="update_ireorderpoint" value=""
                                                class="form-control"></span></p>
                                    <p><span style="width:35%;font-size:1rem;"></span>&nbsp;&nbsp;<span
                                            style="width:60%;"><span style="font-size: 10px;" class="text-small"><b>Enter
                                                    Reorder Point in Unit.</b></span></span></p>
                                    <p><span style="width:35%;font-size:1rem;">Order Qty Upto</span>&nbsp;&nbsp;<span
                                            style="width:60%;"><input type="text" name="update_norderqtyupto" value=""
                                                class="form-control"></span></p>
                                    <p><span style="width:35%;font-size:1rem;"></span>&nbsp;&nbsp;<span
                                            style="width:60%;"><span style="font-size: 10px;" class="text-small"><b>Enter
                                                    Order Qty Upto in Case.</b></span></span></p>
                                    <p><span style="width:35%;font-size:1rem;">Vintage</span>&nbsp;&nbsp;<span
                                            style="width:60%;"><input type="text" name="update_vintage" value=""
                                                class="form-control"></span></p>
                                </div>
                                <div class="col-md-4 span_field" style="padding-left:10px;padding-right:10px;">
                                    <p>
                                        <span style="width:35%;font-size:1rem;">Inventory Item</span>&nbsp;&nbsp;
                                        <span style="width:60%;">
                                            <select name="update_visinventory" class="form-control">
                                                <option value="no-update">-- No Update --</option>
                                                <?php if(isset($data['arr_y_n']) && count($data['arr_y_n']) > 0){?>
                                                <?php foreach($data['arr_y_n'] as $k => $array_y_n){ ?>
                                                <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </p>


                                    <p><span style="width:35%;font-size:1rem;">Rating</span>&nbsp;&nbsp;<span
                                            style="width:60%;"><input type="text" name="update_rating" value=""
                                                class="form-control"></span></p>
                                    <p>
                                        <span style="width:35%;font-size:1rem;">Sales Item</span>&nbsp;&nbsp;
                                        <span style="width:60%;">
                                            <select name="update_vshowsalesinzreport" class="form-control">
                                                <option value="no-update">-- No Update --</option>
                                                <?php if(isset($data['arr_y_n']) && count($data['arr_y_n']) > 0){?>
                                                <?php foreach($data['arr_y_n'] as $k => $array_y_n){ ?>
                                                <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <div class="mytextdiv" style="padding-left:20px;">
                                <div class="mytexttitle font-weight-bold text-uppercase">
                                    ADVANCE OPTIONS
                                    <!--<input type="checkbox" name="options_checkbox" value="1">-->
                                    <a class="btn btn-small text-center text-white" id="advance_options_hideshow"
                                        style="line-height:5px; width:100px; border-radius:6px; background-color:grey; font-size:9px;">SHOW
                                        ADVANCE</a>
                                    &nbsp;
                                </div>
                                <div class="divider font-weight-bold"></div>
                            </div>

                            <br>

                            <div class="panel-body padding-left-right" id="options_checkbox_div" style="display: none;">
                                <div class="row text-uppercase">

                                    <div class="col-md-4 span_field" style="padding-left:35px;padding-right:10px; padding-top: 10px;">
                                        <p>
                                            <span style="width:35%;font-size:1rem;">Malt</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <input style="" type="checkbox" name="update_malt" value="1">
                                            </span>
                                        </p>

                                    </div>
                                    <div class="col-md-4 span_field" style="padding-left:0px;padding-right:10px;">

                                        <p>
                                            <span style="width:35%;font-size:1rem;">Unit Value</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <input type="text" class="form-control" id="update_unit_value" value=""
                                                    name="update_unit_value">
                                            </span>
                                        </p>

                                    </div>
                                    <div class="col-md-4 span_field" style="padding-left:0px;padding-right:10px;">
                                        <p>
                                            <span style="width:35%;font-size:1rem;">Bucket</span>&nbsp;&nbsp;
                                            <span style="width:60%;">
                                                <select name="update_bucket_id" id="update_bucket_id" class="form-control">
                                                    <option value="no-update">-- No Update --</option>
                                                    <?php if(isset($data['buckets']) && count($data['buckets']) > 0){?>
                                                    <?php foreach($data['buckets'] as $bucket){ ?>
                                                    <option value="<?php echo $bucket['id']; ?>"><?php echo $bucket['bucket_name']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn headermenublue basic-button-small"
                            id="update_item_btn">Update</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div id="storesModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title row">Select the stores in which you want to add the Items:</h5>
                    <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
                </div>
                <div>
                    <span style="color:skyblue">Note: In child store if item exists then it will update otherwise it will
                        update only in HQ</span>

                </div>
                <div class="modal-body">
                    <table class="table promotionview">
                        <thead id="table_green_header_tag">
                            <tr class="header-color">
                                <th>
                                    <div class="custom-control custom-checkbox" id="table_green_check">
                                        <input type="checkbox" class="" id="selectAllCheckbox" name="" value=""
                                            style="background: none !important;">
                                    </div>
                                </th>
                                <th colspan="2" id="table_green_header">Select All</th>
                            </tr>
                        </thead>
                        <tbody id="data_stores"></tbody>
                    </table>
                    <div id="loader"></div>
                </div>
                <div class="modal-footer">
                    <button id="save_btn" class="btn btn-danger buttonred basic-button-small"
                        data-dismiss="modal">Save</button>
                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        
        $('.tax_update').select2({
            placeholder: "--No update--",
            allowClear: true,
        });
        // cost checkbox
        $('#update_dcostprice_checkbox').click(function() {
            $('#cost').attr('readonly', false);
            $('#inc_cost').attr('readonly', true);
            $('#inc_cost_dol').attr('readonly', true);
            
            $("#update_dcostprice_increment_checkbox").prop("checked", false);
            $("#update_dcostprice_increment_percent_checkbox").prop("checked", false);
        });
        $('#update_dcostprice_increment_checkbox').click(function() {
            $('#inc_cost').attr('readonly', false);
            $('#cost').attr('readonly', true);
            $('#inc_cost_dol').attr('readonly', true);
            
            $("#update_dcostprice_checkbox").prop("checked", false);
            $("#update_dcostprice_increment_percent_checkbox").prop("checked", false);
        });
        $('#update_dcostprice_increment_percent_checkbox').click(function() {
            $('#inc_cost_dol').attr('readonly', false);
             $('#inc_cost').attr('readonly', true);
            $('#cost').attr('readonly', true);
            
            $("#update_dcostprice_checkbox").prop("checked", false);
            $("#update_dcostprice_increment_checkbox").prop("checked", false);
        });


        // price check box
        $('#update_dunitprice_checkbox').click(function() {
            $('#price').attr('readonly', false);
            $('#inc_price').attr('readonly', true);
            $('#inc_price_dol').attr('readonly', true);
            
            $("#update_dunitprice_increment_checkbox").prop("checked", false);
            $("#update_dunitprice_increment_percent_checkbox").prop("checked", false);
        });
        $('#update_dunitprice_increment_checkbox').click(function() {
            $('#inc_price').attr('readonly', false);
            $('#price').attr('readonly', true);
            $('#inc_price_dol').attr('readonly', true);
            
            $("#update_dunitprice_checkbox").prop("checked", false);
            $("#update_dunitprice_increment_percent_checkbox").prop("checked", false);
        });
        $('#update_dunitprice_increment_percent_checkbox').click(function() {
            $('#inc_price_dol').attr('readonly', false);
             $('#inc_price').attr('readonly', true);
            $('#price').attr('readonly', true);
            
            $("#update_dunitprice_checkbox").prop("checked", false);
            $("#update_dunitprice_increment_checkbox").prop("checked", false);
        });





         $(document).on('change', '#cost_checkbox', function(event) {
            event.preventDefault();

            // alert($("#cost_checkbox").prop("checked", true));
            // console.log($("#cost_checkbox").prop("checked", true));

            // if($(this).is(":checked")){
            //     $('#cost').prop('readonly',true);
            // }else{
            //     $('#cost').prop('readonly',false);
            // }
        });

        $(document).on('change', '.iitemid', function(event) {

            event.preventDefault();

            if ($(this).is(":checked")) {
                uncheckedBoxes[$(this).val()] = false;
            } else {
                uncheckedBoxes[$(this).val()] = true;

            }


            var main_arr = {};
            var add_items_id = {};
            var remove_items_id = {};

            $("input[name='selected_items_id[]']").each(function(i) {
                if ($(this).val() != '') {
                    if ($(this).is(":checked")) {
                        add_items_id[i] = parseInt($(this).val());
                    } else {
                        remove_items_id[i] = parseInt($(this).val());
                    }
                }
            });

            main_arr.add = add_items_id;
            main_arr.remove = remove_items_id;

            var add_remove_ids_url = "<?php echo $data['add_remove_ids_url']; ?>";
            add_remove_ids_url = add_remove_ids_url.replace(/&amp;/g, '&');

            /*var keys = Object.keys(uncheckedBoxes)

            var itemIds = keys.filter(function(key){
                return uncheckedBoxes[key]
                });*/
            console.log(items_total_ids);
            $.ajax({
                url: add_remove_ids_url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                },
                data: {
                    session_items_total_ids: session_items_total_ids,
                    items_total_ids: items_total_ids,
                    main_arr: main_arr
                },
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    console.log(1179);
                    console.log(data);
                    items_total_ids = data;
                },
                error: function(xhr, status, error) { // if error occured
                    console.log(error);
                }
            });


        });

        $(document).on('click',
            '#alberta_listing_btn,#cancel_button, .breadcrumb li a, #header > li:not(:eq(0)) > a, #mySidenavStore > div.side_content_div > div.side_inner_content_div > p:not(:eq(0)), #mySidenavReports > div.side_content_div > div.side_inner_content_div > p:not(:eq(0))',
            function() {

                var unset_session_value_url = "<?php echo $data['unset_session_value']; ?>";
                unset_session_value_url = unset_session_value_url.replace(/&amp;/g, '&');

                $.getJSON(unset_session_value_url, function(datas) {});
            });

        $(document).on('click', '#menu li a', function(event) {
            if (!$(this).hasClass("parent")) {
                var unset_session_value_url = "<?php echo $data['unset_session_value']; ?>";
                unset_session_value_url = unset_session_value_url.replace(/&amp;/g, '&');

                $.getJSON(unset_session_value_url, function(datas) {});
            }
        });
    </script>

    <script type="text/javascript">
        $(document).on('click',
            'input[name="update_dcostprice_increment"],input[name="update_dcostprice_increment_percent"]',
            function(event) {
                //event.preventDefault();

                if ($('input[name="update_dcostprice_increment"]').is(':checked') && $(
                        'input[name="update_dcostprice_increment_percent"]').is(':checked')) {
                    bootbox.alert({
                        size: 'small',
                        title: " ",
                        message: "Please check only one!",
                        callback: function() {}
                    });
                    return false;
                }

            });

        $(document).on('click',
            'input[name="update_dunitprice_increment"],input[name="update_dunitprice_increment_percent"]',
            function(event) {
                //event.preventDefault();

                if ($('input[name="update_dunitprice_increment"]').is(':checked') && $(
                        'input[name="update_dunitprice_increment_percent"]').is(':checked')) {
                    bootbox.alert({
                        size: 'small',
                        title: " ",
                        message: "Please check only one!",
                        callback: function() {}
                    });
                    return false;
                }

            });
    </script>

    <!-- Modal sure update -->
    <div id="myModalUpdateItems_1" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Items</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body ">
                    <div class="text-center alert-danger" style="overflow: hidden; BACKGROUND: WHITE;">
                        <p style="font-size: 18px;font-weight: bold;">TOTAL <span id="item_tot"></span> ITEMS</p><br>
                        <strong style="font-size: 16px;">ARE YOU SURE YOU WANT TO UPDATE? </strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn headermenublue basic-button-small"
                        id="item_update_sure_btn_1">SURE</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>

                </div>
            </div>

        </div>
    </div>

    <div id="myModalUpdateItems" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Items</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body ">
                    <div class="text-center alert-danger" style="overflow: hidden; BACKGROUND: WHITE;">
                        <p style="font-size: 18px;font-weight: bold;"> DEPARTMENT, CATEGORY ARE CHANGING</p><br>
                        <strong style="font-size: 16px;">ARE YOU SURE YOU WANT TO UPDATE ?</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn headermenublue basic-button-small"
                        id="item_update_sure_btn">SURE</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>

                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        $(document).on('click', '#update_item_btn', function(event) {
            event.preventDefault();

            let depcode = $('#update_vdepcode').val();
            if (depcode != 'no-update') {
                let catcode = $('#update_vcategorycode').val();
                if (!catcode) {
                    bootbox.alert({
                        size: 'small',
                        title: " ",
                        message: "Please Select Category!(If you select department then You have to select category too, because its mandetory)",
                        callback: function() {}
                    });
                    return false;
                }
            }

            var get_session_value_url = "<?php echo $data['get_session_value']; ?>";

            get_session_value_url = get_session_value_url.replace(/&amp;/g, '&');
            // get_session_value_url = get_session_value_url+'?session_items_total_ids='+JSON.stringify(session_items_total_ids)+'&items_total_ids='+JSON.stringify(items_total_ids);

            if ($('input[name="update_dcostprice"]').val() == '') {
                // alert('Please enter cost!');
                bootbox.alert({
                    size: 'small',
                    title: " ",
                    message: "Please enter cost!",
                    callback: function() {}
                });
                return false;
            }

            if ($('input[name="update_dunitprice"]').val() == '') {
                // alert('Please enter price!');
                bootbox.alert({
                    size: 'small',
                    title: " ",
                    message: "Please enter price!",
                    callback: function() {}
                });
                return false;
            }

            if ($('input[name="update_npack_checkbox"]').is(':checked')) {
                if ($('input[name="update_npack"]').val() == '') {
                    // alert('Please enter pack!');
                    bootbox.alert({
                        size: 'small',
                        title: " ",
                        message: "Please enter pack!",
                        callback: function() {}
                    });
                    return false;
                }
            }

            if ($('input[name="options_checkbox"]').is(':checked')) {
                if ($('select[name="update_unit_id"]').val() == 'no-update') {
                    // alert('Please enter pack!');
                    bootbox.alert({
                        size: 'small',
                        title: " ",
                        message: "Please select unit!",
                        callback: function() {}
                    });
                    return false;
                }

                if ($('input[name="update_unit_value"]').val() == '') {
                    // alert('Please enter pack!');
                    bootbox.alert({
                        size: 'small',
                        title: " ",
                        message: "Please enter unit value!",
                        callback: function() {}
                    });
                    return false;
                }

                if ($('select[name="update_bucket_id"]').val() == 'no-update') {
                    // alert('Please enter pack!');
                    bootbox.alert({
                        size: 'small',
                        title: " ",
                        message: "Please select bucket!",
                        callback: function() {}
                    });
                    return false;
                }
            }



            // $.getJSON(get_session_value_url, function(result){
            //     $('span#item_tot').html(result.total_items);
            //     $('#myModalUpdateItems').modal('show');
            // });

            $.ajax({
                url: get_session_value_url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                },
                data: {
                    session_items_total_ids: session_items_total_ids,
                    items_total_ids: items_total_ids
                },
                success: function(result) {
                    $('span#item_tot').html(result.total_items);
                    $('#myModalUpdateItems_1').modal('show');
                }
            });

        });

        $(document).on('click', '#buttonEditMultipleItems', function() {

            var count = 0;
            var parentcount = 0;
            $('input[name="selected_items_id[]"]:checked').each(function(i) {
                count = count + 1;
                // selected[i] = $(this).val();
            });

            $('#update_vdepcode').val('no-update');
            var html = '<option value="no-update">--No Update--</option>';
            $('#update_vcategorycode').empty().html(html);
            $('#update_subcat_id').empty().html(html);
            $("div#divLoading").addClass('show');

            setTimeout(function() {
                $("div#divLoading").removeClass('show');
                // Something you want delayed.
                if (count <= 0) {
                    bootbox.confirm({
                        size: 'small',
                        title: " ",
                        message: "Not any Item is Selected",
                        callback: function(result) {}
                    });
                    $('#myModal').modal('hide');
                } else {
                    $('#myModal').modal('show');
                }


                var set_itemids_final = '<?php echo $data['set_itemids_final_url']; ?>';
                set_itemids_final = set_itemids_final.replace(/&amp;/g, '&');

                //   var inputUncheckedBoxes ={keys:  Object.keys(uncheckedBoxes)};
                var inputUncheckedBoxes = {
                    keys: uncheckedBoxes
                };


                var set_final_session = '<?php echo $data['set_final_session']; ?>';
                set_final_session = set_final_session.replace(/&amp;/g, '&');

                $.ajax({
                    url: set_final_session,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                    },
                    type: 'POST',
                    data: {
                        items_total_ids: items_total_ids
                    },
                    dataType: "json",
                    // contentType: "json",
                    success: function(data) {
                        // console.log(1179);
                        var response = data;

                        console.log(response);
                    },
                    error: function(xhr, text, errorThrown) {
                        // if error occured
                        console.log(xhr);
                        console.log(text);
                        console.log(errorThrown);
                    }
                }).done(function(result) {

                    $.ajax({
                        url: set_itemids_final,
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                        },
                        type: 'POST',
                        data: inputUncheckedBoxes,
                        dataType: "json",
                        // contentType: "json",
                        success: function(data) {
                            // console.log(1179);
                            var response = data;

                            console.log(response);
                        },
                        error: function(xhr, text, errorThrown) {
                            // if error occured
                            console.log(xhr);
                            console.log(text);
                            console.log(errorThrown);
                        }
                    });
                });

            }, 3500);

        });

        $(document).on('click', '#item_update_sure_btn_1', function(event) {
            $('#myModalUpdateItems_1').modal('hide');
            $("#myModalUpdateItems").modal('show');
        });



        $(document).on('click', '#item_update_sure_btn', function(event) {
            // $('form#form_item_update').trigger('submit')
            <?php if(session()->get('hq_sid') == 1){ ?>

            $('#selectAllCheckbox').attr('disabled', false);

            var div =
                '<center><button class="btn btn-danger buttonload"><i class="fa fa-circle-o-notch fa-spin"></i>Loading</button></center>';
            $('#loader').html(div);
            $.ajax({
                url: "<?php echo url('/item/edit_multiple_item/checkforduplicate'); ?>",
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                },
                data: {},
                success: function(result) {
                    var popup = '';
                    @foreach (session()->get('stores_hq') as $stores)
                        if(result.includes({{ $stores->id }})){
                        var data = '<tr>'+
                            '<td>'+
                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                    '<input type="checkbox" class="checks check  editstores" id="hq_sid_{{ $stores->id }}"
                                        name="editstores" value="{{ $stores->id }}">'+
                                    '</div>'+
                                '</td>'+
                            '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>'+
                            '</tr>';
                        // $('#selectAllCheckbox').attr('disabled', true);

                        } else {
                        var data = '<tr>'+
                            '<td>'+
                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                    '<input type="checkbox" class="checks check  editstores" id="else_hq_sid_{{ $stores->id }}"
                                        name="editstores" value="{{ $stores->id }}">'+
                                    '</div>'+
                                '</td>'+
                            '<td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                            '</tr>';
                        }
                        popup = popup + data;
                    @endforeach
                    $('#loader center').remove();
                    $('#data_stores').html(popup);
                }
            });
            $('#myModalUpdateItems').modal('hide');
            $("#storesModal").modal('show');
            <?php } else {?>
            $('form#form_item_update').trigger('submit');
            <?php } ?>
        });

        var edit_stores = [];
        edit_stores.push("{{ session()->get('sid') }}");
        $('#selectAllCheckbox').click(function() {
            if ($('#selectAllCheckbox').is(":checked")) {
                $(".editstores").prop("checked", true);
            } else {
                $(".editstores").prop("checked", false);
            }
        });

        $("#save_btn").click(function() {

            $.each($("input[name='editstores']:checked"), function() {
                edit_stores.push($(this).val());
            });
            $("#hidden_store_hq_val").val(edit_stores.join());
            // console.log($("#hidden_store_hq_val").val());
            $('form#form_item_update').trigger('submit');
        });
    </script>

    <script type="text/javascript">
        $(document).on('keypress', 'input[name="update_unit_value"]', function(event) {

            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }

        });

        $(document).on('change', 'input[name="options_checkbox"]', function(event) {
            event.preventDefault();
            if ($(this).prop('checked') == true) {
                $('#options_checkbox_div').show('slow');
            } else {
                $('#options_checkbox_div').hide('slow');
            }
        });

        //new code hide show

        $("#advance_options_hideshow").click(function() {
            $("#options_checkbox_div").toggle();
        });


        $(document).on('change', '#update_vdepcode', function() {
            var category_url = "<?php echo $data['get_categories_url']; ?>";
            category_url = category_url.replace(/&amp;/g, '&');

            $.ajax({
                url: category_url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                },
                data: {
                    depcode: $(this).val()
                },
                type: 'POST',
                success: function(data) {
                    $('#update_vcategorycode').empty().html(data);
                    var html = '<option value="no-update">--No Update--</option>';
                    $('#update_subcat_id').empty().html(html);
                }
            });
        })

        $(document).on('change', '#update_vcategorycode', function() {
            console.log("test");
            var subcategory_url = "<?php echo $data['get_subcategories_url']; ?>";
            subcategory_url = subcategory_url.replace(/&amp;/g, '&');
            console.log($(this).val());
            $.ajax({
                url: subcategory_url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                },
                data: {
                    category_code: $(this).val()
                },
                type: 'POST',
                success: function(data) {
                    $('#update_subcat_id').empty().html(data);
                }
            });
        })
    </script>
    <style>
        #item_tot{
            font-size: 18px !important;
        }
        input { 
            font-size: 13px !important; 
        }
        select { 
            font-size: 13px !important; 
        }
        span{
            font-size: 13px !important;
        }
        .font-style-13px{
            font-style:13px;
        }
        .disabled {
            pointer-events: none; //This makes it not clickable

        }

    </style>
    <style>
        .verticalLine {
            border-left: 2px solid blue;
            height: 150px;
        }
        
          
          .selection{
              width:200px;
          }
          
          .select2-container--default .select2-selection--multiple:before {
                content: ' ';
                display: block;
                position: absolute;
                border-color: #888 transparent transparent transparent;
                border-style: solid;
                border-width: 5px 4px 0 4px;
                height: 0;
                right: 6px;
                margin-left: -4px;
                margin-top: -2px;top: 50%;
                width: 0;cursor: pointer
            }
            
            .select2-container--open .select2-selection--multiple:before {
                content: ' ';
                display: block;
                position: absolute;
                border-color: transparent transparent #888 transparent;
                border-width: 0 4px 5px 4px;
                height: 0;
                right: 6px;
                margin-left: -4px;
                margin-top: -2px;top: 50%;
                width: 0;cursor: pointer
            }

    </style>
@endsection
