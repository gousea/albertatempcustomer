@extends('layouts.master')

@section('title')
    Vendor
@stop

@section('main-content')
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li><a href="">Vendor</a></li>

                </ul>
            </div>
        </div>
        <div class="container-fluid">
            @if (session()->has('message'))
                <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                    {{ session()->get('message') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading head_title">
                    <h3 class="panel-title"><i class="fa fa-list"></i> Vendor</h3>

                </div>
                <div class="panel-body">

                    <div class="row" style="padding-bottom: 9px;float: right;">
                        <div class="col-md-12">
                            <div class="">
                                <a id="save_button" class="btn btn-primary" title="Save"><i
                                        class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
                                <a href="{{ route('vendors.create') }}" title=""
                                    class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add
                                    New</a>
                                <!--<button type="button" class="btn btn-danger" id="vender_delete" onclick="myFunction()" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>-->
                                <button type="button" class="btn btn-danger" id="vender_delete" title="Delete"
                                    style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('vendors') }}" method="post" id="form_vendor_search">
                        @csrf
                        <input type="hidden" name="searchbox" id="vendor_name">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" autocomplete="off" name="automplete-product" class="form-control"
                                    placeholder="Search Vendor..." id="automplete-product">
                            </div>
                        </div>
                    </form>
                    <br>
                    <form action="" method="post" enctype="multipart/form-data" id="form-vendor">
                        @csrf
                        @if (session()->get('hq_sid') == 1)
                            <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
                        @endif
                        <input type="hidden" name="MenuId" value="" />
                        <div class="table-responsive">
                            <table id="vendor" class="table table-bordered table-hover">

                                <thead>
                                    <tr>
                                        <th style="width: 1px;" class="text-center"><input type="checkbox"
                                                style="background: none !important;"
                                                onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                                        </th>
                                        <th class="text-left">Supplier Code</th>
                                        <th class="text-left">Vendor Code</th>
                                        <th class="text-left">Vendor Name</th>
                                        <th class="text-right">Phone</th>
                                        <th class="text-left">Email</th>
                                        <th class="text-left">Status</th>
                                        <th class="text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $vendor_row = 1;
                                    $i = 0;
                                    $selected = [];
                                    ?>
                                    @foreach ($vendors as $vendor)
                                        <tr id="vendor-row{{ $vendor_row }}">
                                            <td data-order="<?php echo $vendor->isupplierid; ?>"
                                                class="text-center">
                                                <span style="display:none;"><?php echo $vendor->isupplierid;
                                                    ?></span>
                                                <?php if (in_array($vendor->isupplierid, $selected)) { ?>
                                                <input type="checkbox" name="selected[]"
                                                    id="vendor[<?php echo $vendor_row; ?>][select]"
                                                    value="<?php echo $vendor->isupplierid; ?>"
                                                    checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" name="selected[]"
                                                    id="vendor[<?php echo $vendor_row; ?>][select]"
                                                    value="<?php echo $vendor->isupplierid; ?>" />
                                                <?php } ?>
                                            </td>


                                            <td class="text-left">
                                                <span style=""><?php echo $vendor->vsuppliercode; ?></span>
                                                <input type="hidden"
                                                    name="vendor[<?php echo $i; ?>][vsuppliercode]"
                                                    value="<?php echo $vendor->vsuppliercode; ?>" />
                                                <input type="hidden"
                                                    name="vendor[<?php echo $i; ?>][isupplierid]"
                                                    value="<?php echo $vendor->isupplierid; ?>" />
                                            </td>

                                            <td class="text-left">
                                                <span style=""><?php echo $vendor->vcode; ?></span>
                                                <input type="hidden"
                                                    name="vendor[<?php echo $i; ?>][vcode]"
                                                    value="<?php echo $vendor->vcode; ?>" />
                                                <input type="hidden"
                                                    name="vendor[<?php echo $i; ?>][isupplierid]"
                                                    value="<?php echo $vendor->isupplierid; ?>" />
                                            </td>

                                            <td class="text-left">
                                                <span style="display:none;"><?php echo $vendor->vcompanyname;
                                                    ?></span>
                                                <input type="text" class="editable vendors_c" maxlength="50"
                                                    name="vendor[<?php echo $i; ?>][vcompanyname]"
                                                    id="vendor[<?php echo $i; ?>][vcompanyname]"
                                                    value="<?php echo $vendor->vcompanyname; ?>"
                                                    onclick='document.getElementById("vendor[<?php echo $vendor_row; ?>][select]").setAttribute("checked","checked");' />
                                            </td>

                                            <td class="text-right">
                                                <input type="text" class="editable vendors_phone" maxlength="20"
                                                    name="vendor[<?php echo $i; ?>][vphone]"
                                                    id="vendor[<?php echo $i; ?>][vphone]"
                                                    value="<?php echo $vendor->vphone; ?>"
                                                    onclick='document.getElementById("vendor[<?php echo $vendor_row; ?>][select]").setAttribute("checked","checked");'
                                                    style="text-align: right;" />
                                            </td>

                                            <td class="text-left">
                                                <span style="display:none;"><?php echo $vendor->vemail; ?></span>
                                                <input type="email" class="editable vendors_email" maxlength="100"
                                                    name="vendor[<?php echo $i; ?>][vemail]"
                                                    id="vendor[<?php echo $i; ?>][vemail]"
                                                    value="<?php echo $vendor->vemail; ?>"
                                                    onclick='document.getElementById("vendor[<?php echo $vendor_row; ?>][select]").setAttribute("checked","checked");' />
                                            </td>

                                            <td class="text-left">
                                                <span>{{ $vendor->estatus }}</span>
                                            </td>

                                            <td class="text-left">
                                                <a href="{{ route('vendors.edit', $vendor->isupplierid) }}"
                                                    data-toggle="tooltip" title="Edit"
                                                    class="btn btn-sm btn-info edit_btn_rotate"><i
                                                        class="fa fa-pencil"></i>&nbsp;&nbsp;Edit</a>
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
                    <div class="row">
                        <div class="col-sm-6 text-left">{{ $vendors->links() }}</div>
                        <div class="col-sm-6 text-right"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->get('hq_sid') == 1) { ?>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select the stores in which you want to delete the vendor:</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead id="table_green_header_tag">
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
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select the stores in which you want to Edit the vendor:</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead id="table_green_header_tag">
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
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Error Model</h4>
                </div>

                <div class="modal-body">
                    <h3>No vendor is selected </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        #selectAllCheckbox: {
            color: white;
        }

    </style>
@endsection

@section('scripts')
    <script src=" {{ asset('javascript/bootbox.min.js') }}" defer></script>
    <style>
        .disabled,
        #item_ellipsis {
            pointer-events: none;
        }

    </style>

    <script>
        $('#vender_delete').click(function() {
            var vendor_ids = [];
            $.each($("input[name='selected[]']:checked"), function() {
                vendor_ids.push($(this).val());
            });
            console.log(vendor_ids);
            if (vendor_ids.length > 0) {
                <?php
                if (session() - > get('hq_sid') == 1) {
                    ?>
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
                                            '<input type="checkbox" class="checks check custom-control-input stores" disabled
                                                id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
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
                                            '<input type="checkbox" class="checks check custom-control-input stores"
                                                id="else_hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
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

                    <?php
                } else {
                    ?>
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
                                title: "Success",
                                message: result,
                                callback: function() {
                                    location.reload();
                                }
                            });
                        }
                    });
                    // $('#form-vendor').submit();
                    <?php
                } ?>
            } else {
                $('#errorModel').modal('show');
            }
        })

    </script>
    <script>
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

    </script>

    <script type="text/javascript">
        $(document).on('click', '#save_button', function(event) {
            event.preventDefault();
            var edit_url = '<?php echo $data['
            edit_list ']; ?>';


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
                <?php
                if (session() - > get('hq_sid') == 1) {
                    ?>
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
                                            '<input type="checkbox" class="checks check custom-control-input editstores" disabled id="editstores"
                                                name="editstores" value="{{ $stores->id }}">'+
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
                                            '<input type="checkbox" class="checks check custom-control-input editstores" id="editstores"
                                                name="editstores" value="{{ $stores->id }}">'+
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

                    <?php
                } else {
                    ?>
                    // $('#form-vendor').submit();
                    $('#form-vendor').attr('action', edit_url);


                    $('#form-vendor').submit();
                    $("div#divLoading").addClass('show');
                    <?php
                } ?>
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
            var edit_url = '<?php echo $data['
            edit_list ']; ?>';
            edit_url = edit_url.replace(/&amp;/g, '&');

            $.each($("input[name='editstores']:checked"), function() {
                edit_stores.push($(this).val());
            });

            $("#hidden_store_hq_val").val(edit_stores.join(","));

            $('#form-vendor').attr('action', edit_url);
            $('#form-vendor').submit();
            $("div#divLoading").addClass('show');
        })

    </script>

@endsection
