@extends('layouts.layout')

@section('title')
    Vendor
@stop
<style>
    .has-search .form-control {
        padding-left: 2.375rem;
    }

    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
    }

</style>

{{-- <link rel="stylesheet" href="{{ asset('asset/css/vendor.css') }}"> --}}

@section('main-content')

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue menu">
        <div class="container">
            <div class="row">
                <div class="collapse navbar-collapse" id="main_nav">
                    <h6 class="font-weight-bold text-uppercase"> Vendor</h6>
                    <div class="nav-submenu">
                        <a type="button" class="btn btn-gray headerblack  buttons_menu "
                            href="{{ route('vendors.create') }}">
                            ADD NEW
                        </a>
                        {{-- <a style="pointer-events:all;" href="{{ route('customers') }}" data-toggle="tooltip" --}}
                        {{-- title="<?php //echo $button_cancel;
?>" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a> --}}
                        <button type="button"
                            class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase cancel_btn_rotate"
                            id="vender_delete" title="Delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
                        {{-- <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> DELETE
                            </a> --}}
                    </div>
                </div> <!-- navbar-collapse.// -->
            </div>
        </div>
    </nav>

    <section class="section-content menu">
        @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data" id="form-vendor">
                @csrf
                @if (session()->get('hq_sid') == 1)
                    <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
                @endif
                <input type="hidden" name="MenuId" value="" />
                <div class="table-responsive">
                    <table id="vendor" class="table table-hover promotionview" style="width: 100%;">
                        <thead>
                            <tr class="header-color">
                                <th style="width: 1px; color:black;" class="text-center"><input type="checkbox"
                                        style="background: none !important;"
                                        onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"
                                        id="selectAll" /></th>

                                <th class="col-xs-1 headername text-uppercase">Supplier Code
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback" id="filter_supplier_code"></span>
                                        <input type="text" class="form-control table-heading-fields" id="supplier_code"
                                            placeholder="SUPPLIER CODE">
                                    </div>

                                    {{-- <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control" placeholder="SUPPLIER CODE">
                                    </div> --}}
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Vendor Name
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback" id="filter_vendor_name"></span>
                                        <input type="text" class="form-control table-heading-fields" id="vendor_name"
                                            placeholder="VENDOR NAME">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Phone
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback" id="filter_phone"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="PHONE"
                                            id="phone">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Email
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback" id="filter_email"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="EMAIL"
                                            id="email">
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $vendor_row = 1;
                            $i = 0;
                            $selected = [];
                            ?>
                            @foreach ($vendors as $vendor)
                                <tr id="vendor-row{{ $vendor_row }}" class="customer-row">
                                    <td data-order="" class="text-center">
                                        <input type="checkbox" name="selected[]" id=""
                                            value="{{ $vendor->isupplierid }}" />
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('vendors.edit', $vendor->isupplierid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $vendor->vsuppliercode }}</span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('vendors.edit', $vendor->isupplierid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $vendor->vcompanyname }}</span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('vendors.edit', $vendor->isupplierid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $vendor->vphone }} </span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('vendors.edit', $vendor->isupplierid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $vendor->vemail }}</span>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                $vendor_row++;
                                $i++;
                                ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
            {{-- <div class="row">
                <div class="col-sm-6 text-left">{{ $vendors->links() }}</div>
                <div class="col-sm-6 text-right"></div>
            </div> --}}
        </div>
    </section>
    {{-- @endsection --}}

    <?php if (session()->get('hq_sid') == 1) { ?>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Select the stores in which you want to delete the vendor:</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                        <thead id="table_green_header_tag" style="background-color: #286fb7!important;">
                            <tr>
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
                </div>
                <div class="modal-footer">
                    <button type="button" id="save_btn" class="btn btn-danger" data-dismiss="modal">Delete</button>
                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Select the stores in which you want to Edit the vendor:</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                        <thead id="table_green_header_tag" style="background-color: #286fb7!important;">
                            <tr>
                                <th>
                                    <div class="custom-control custom-checkbox" id="table_green_check">
                                        <input type="checkbox" class="" id="editSelectAllCheckbox" name="" value=""
                                            style="background: none !important;">
                                    </div>
                                </th>
                                <th colspan="2" id="table_green_header">Select All</th>
                            </tr>
                        </thead>
                        <tbody id="edit_data_stores"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id="update_btn" class="btn btn-primary" data-dismiss="modal">Update</button>
                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div id="errorModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border: none;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>

                <div class="modal-body" style="border: none;">
                    <h3 style="font-size: 13px; text-transform: uppercase;">No vendor is selected </h3>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" style="font-size: 13px; width: 65px;" class="btn btn-primary text-uppercase"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="http://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src=" {{ asset('javascript/bootbox.min.js') }}"></script>
    <script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}

    {{-- <script src=" {{ asset('asset/js/vendor.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            var table = $('#vendor').DataTable({
                // "dom": 't<"bottom col-md-12 row"<"col-md-2"i><"col-md-3"l><"col-md-7"p>>',
                "dom": 't<<"float-right"p>><"clear">',
                "searching": true,
                "destroy": true,
                "pageLength": 10,
                "ordering": false,
                "order": [
                    [1, "asc"]
                ]
            });
            $("#selectAll").click(function() {
                var cols = table.column(0).nodes(),
                    state = this.checked;
                for (var i = 0; i < cols.length; i += 1) {
                    cols[i].querySelector("input[type='checkbox']").checked = state;
                }
            });
        });

        // $(document).ready(function(){
        //     $('#filter_supplier_code').on('input', function () {
        //         var table = $('#vendor').DataTable({
        //             "ordering": false,
        //             "order": [[ 1, "asc" ]]
        //         });
        //     });
        // });

        $(document).ready(function() {
            var table = $('#vendor').DataTable();
            $('#supplier_code').on('input', function() {
                table.columns(1).search(this.value).draw();
            });
            $('#vendor_name').on('input', function() {
                table.columns(2).search(this.value).draw();
            });
            $('#phone').on('input', function() {
                table.columns(3).search(this.value).draw();
            });
            $('#email').on('input', function() {
                table.columns(4).search(this.value).draw();
            });

        });

        $("#vendor_paginate").addClass("pull-right");

        $('#vender_delete').click(function() {
            var vendor_ids = [];
            $.each($("input[name='selected[]']:checked"), function() {
                vendor_ids.push($(this).val());
            });
            console.log(vendor_ids);
            if (vendor_ids.length > 0) {
                <?php if(session()->get('hq_sid') == 1) { ?>
                $('#myModal').modal('show');
                $.ajax({
                    url: "<?php echo url('/vendors/duplicatehqvendors'); ?>",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                    },
                    data: {
                        vendor_ids
                    },
                    success: function(result) {
                        var popup = '';
                        @foreach (session()->get('stores_hq') as $stores)
                            if(result.includes({{ $stores->id }})){
                            var data = '<tr>'+
                                '<td>'+
                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                        '<input type="checkbox" class="checks check  stores" disabled id="hq_sid_{{ $stores->id }}"
                                            name="stores" value="{{ $stores->id }}">'+
                                        '</div>'+
                                    '</td>'+
                                '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Vendor does
                                        not exist)</span></td>'+
                                '</tr>';
                            $('#selectAllCheckbox').attr('disabled', true);

                            } else {
                            var data = '<tr>'+
                                '<td>'+
                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                        '<input type="checkbox" class="checks check  stores" id="else_hq_sid_{{ $stores->id }}" name="stores"
                                            value="{{ $stores->id }}">'+
                                        '</div>'+
                                    '</td>'+
                                '<td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                '</tr>';
                            }
                            popup = popup + data;
                        @endforeach
                        $('#data_stores').html(popup);
                    }
                });

                <?php } else{ ?>
                $.ajax({
                    url: "<?php echo url('vendors/remove'); ?>",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                    },
                    data: {
                        vendor_ids
                    },
                    success: function(result) {
                        bootbox.alert({
                            size: 'small',
                            title: "",
                            message: result,
                            callback: function() {
                                location.reload();
                            }
                        });
                    }
                });
                // $('#form-vendor').submit();
                <?php } ?>
            } else {
                $('#errorModel').modal('show');
            }
        });


        var stores = [];
        stores.push("{{ session()->get('sid') }}");
        $('#selectAllCheckbox').click(function() {
            if ($('#selectAllCheckbox').is(":checked")) {
                $(".stores").prop("checked", true);
            } else {
                $(".stores").prop("checked", false);
            }
        });

        $("#save_btn").click(function() {
            var vendor_ids = [];
            $.each($("input[name='selected[]']:checked"), function() {
                vendor_ids.push($(this).val());
            });
            $.each($("input[name='stores']:checked"), function() {
                stores.push($(this).val());
            });
            $.ajax({
                url: "<?php echo url('vendors/remove'); ?>",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                },
                data: {
                    vendor_ids,
                    stores_hq: stores
                },
                success: function(result) {
                    bootbox.alert({
                        size: 'small',
                        title: "Success",
                        message: result,
                        callback: function() {
                            location.reload();
                        }
                    });
                }
            });
        });

        $(document).on('click', '#save_button', function(event) {
            event.preventDefault();
            var edit_url = '<?php echo $data['edit_list']; ?>';


            edit_url = edit_url.replace(/&amp;/g, '&');

            var all_vendor = true;
            var vendor_ids = [];
            $.each($("input[name='selected[]']:checked"), function() {
                vendor_ids.push($(this).val());
            });

            $('.vendors_c').each(function() {
                if ($(this).val() == '') {
                    alert('Please Enter Vendor Name');
                    all_vendor = false;
                    return false;
                } else {
                    all_vendor = true;
                }
            });

            var validNumerror = [];
            var emailerror = [];

            $('.vendors_phone').each(function() {
                if ($(this).val() != '') {
                    var phone_num = getValidNumber($(this).val());
                    if (phone_num === false) {
                        validNumerror.push(phone_num + ' is not a valid us number')
                    }
                }
            })

            $(".vendors_email").each(function() {
                if ($(this).val() != '') {
                    var ven_email = validateEmail($(this).val());
                    if (ven_email === false) {
                        emailerror.push("email is not valid")
                    }
                }
            })


            if (emailerror.length > 0) {
                alert("Please check the all the emails few are not valid");
                return false;
            }
            if (validNumerror.length > 0) {
                alert("Please check the all the Phone numbers few are not valid");
                return false;
            }

            if (vendor_ids.length > 0) {
                <?php if(session()->get('hq_sid') == 1) { ?>
                $.ajax({
                    url: "<?php echo url('/vendors/duplicatehqvendors'); ?>",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                    },
                    data: {
                        vendor_ids
                    },
                    success: function(result) {
                        var popup = '';
                        @foreach (session()->get('stores_hq') as $stores)
                            if(result.includes({{ $stores->id }})){
                            var data = '<tr>'+
                                '<td>'+
                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                        '<input type="checkbox" class="checks check  editstores" disabled id="editstores" name="editstores"
                                            value="{{ $stores->id }}">'+
                                        '</div>'+
                                    '</td>'+
                                '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Vendor does
                                        not exist)</span></td>'+
                                '</tr>';
                            $('#editSelectAllCheckbox').attr('disabled', true);

                            } else {
                            var data = '<tr>'+
                                '<td>'+
                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                        '<input type="checkbox" class="checks check  editstores" id="editstores" name="editstores"
                                            value="{{ $stores->id }}">'+
                                        '</div>'+
                                    '</td>'+
                                '<td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                '</tr>';
                            }
                            popup = popup + data;
                        @endforeach
                        $('#edit_data_stores').html(popup);
                    }
                });
                $('#editModal').modal('show');

                <?php } else{ ?>
                // $('#form-vendor').submit();
                $('#form-vendor').attr('action', edit_url);


                $('#form-vendor').submit();
                $("div#divLoading").addClass('show');
                <?php } ?>
            } else {
                bootbox.alert({
                    size: 'small',
                    title: "Attension",
                    message: "No vendor is selected to edit.",
                    callback: function() {}
                });
            }
        });


        function validateEmail($email) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return emailReg.test($email);
        }

        function getValidNumber(value) {
            value = $.trim(value).replace(/\D/g, '');

            if (value.substring(0, 1) == '1') {
                value = value.substring(1);
            }
            if (value.length == 10) {
                return value;
            }
            return false;
        }


        var edit_stores = [];

        edit_stores.push("{{ session()->get('sid') }}");
        $('#editSelectAllCheckbox').click(function() {
            if ($('#editSelectAllCheckbox').is(":checked")) {
                $(".editstores").prop("checked", true);
            } else {
                $(".editstores").prop("checked", false);
            }
        });

        $("#update_btn").click(function() {
            var edit_url = '<?php echo $data['edit_list']; ?>';
            edit_url = edit_url.replace(/&amp;/g, '&');

            $.each($("input[name='editstores']:checked"), function() {
                edit_stores.push($(this).val());
            });

            $("#hidden_store_hq_val").val(edit_stores.join(","));

            $('#form-vendor').attr('action', edit_url);
            $('#form-vendor').submit();
            $("div#divLoading").addClass('show');
        });
    </script>
    <style>
        .modal-footer {
            border: none;
        }

        .modal-footer button {
            font-size: 13px;
        }

        .modal-body {
            text-transform: uppercase;
            font-size: 13px;
        }

        #selectAllCheckbox: {
            color: white;
        }

        .disabled,
        #item_ellipsis {
            pointer-events: none;
        }

    </style>

@endsection
