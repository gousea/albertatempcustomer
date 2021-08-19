@extends('layouts.layout')

@section('title')
    Customer
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

@section('main-content')

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue menu">
        <div class="container">
            <div class="row">
                <div class="collapse navbar-collapse" id="main_nav">
                    <div>
                        <h6 class="font-weight-bold text-uppercase">Customer</h6>
                    </div>
                    <div class="nav-submenu">
                        <a type="button" class="btn btn-gray headerblack buttons_menu "
                            href="{{ route('customers.create') }}">
                            ADD NEW
                        </a>
                        <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small"
                            id="customer_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a>
                    </div>
                </div> <!-- navbar-collapse.// -->
            </div>
        </div>
    </nav>
    {{-- <input type="button" id="customer_delete"> --}}

    <section class="section-content menu">
        @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        <div class="container">
            <form action="{{ route('customers.remove') }}" method="post" enctype="multipart/form-data" id="form-customer">
                @csrf
                <input type="hidden" name="MenuId" value="$filter_menuid;" />
                <div class="table-responsive">
                    <table id="vendor" class="table table-hover promotionview" style="width: 100%">
                        <thead>
                            <tr class="header-color">
                                <th class="text-center"><input type="checkbox"
                                        onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" id="selectAll" /></th>

                                <th class="col-xs-1 headername text-uppercase">Customer
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="CUSTOMER" id="customer">
                                    </div>
                                </th>

                                <th class="col-xs-1 headername text-uppercase">First Name
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields"
                                            placeholder="FIRST NAME" id="first_name">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Last Name
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields"
                                            placeholder="LAST NAME" id="last_name">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Phone
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="PHONE" id="phone">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Status
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="STATUS" id="status">
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr id="customer-row">
                                    <td data-order="" class="text-center">
                                        <input type="checkbox" name="selected[]" id=""
                                            value="{{ $customer->icustomerid }}" />
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}"
                                            data-toggle="tooltip" title="Edit"><span>{{ $customer->vcustomername }}</span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}"
                                            data-toggle="tooltip" title="Edit"><span>{{ $customer->vfname }}</span>
                                        </a>

                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}"
                                            data-toggle="tooltip" title="Edit"><span>{{ $customer->vlname }} </span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}"
                                            data-toggle="tooltip" title="Edit"><span>{{ $customer->vphone }}</span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}"
                                            data-toggle="tooltip" title="Edit"><span>{{ $customer->estatus }}</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

            <div id="errorModel" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header" style="border: none;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"></h4>
                        </div>

                        <div class="modal-body" style="border: none;">
                            <h3 style="font-size: 13px; text-transform: uppercase;">No Customer is selected </h3>
                        </div>
                        <div class="modal-footer" style="border: none;">
                            <button type="button" style="font-size: 13px; width: 65px;" class="btn btn-primary text-uppercase" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-sm-6 text-left">{{ $customers->links() }}</div>
                <div class="col-sm-6 text-right"></div>
            </div> --}}
        </div>
    </section>

@endsection

@section('page-script')
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">

    <script>
        var table = $('#vendor').DataTable({
            // "dom": 't<"bottom col-md-12 row"<"col-md-2"i><"col-md-3"l><"col-md-7"p>>',
            "dom": 't<<"float-right"p>><"clear">',
            "searching":true,
            "destroy": true,
            "ordering": false,
            "pageLength":10,
            "ordering" : false,
            "order": [[ 1, "asc" ]]
        });

        $("#selectAll").click(function() {
            var cols = table.column(0).nodes(),
                state = this.checked;
            for (var i = 0; i < cols.length; i += 1) {
                cols[i].querySelector("input[type='checkbox']").checked = state;
            }
        });

        $('#customer').on('input', function () {
            table.columns(1).search(this.value).draw();
        });
        $('#first_name').on('input', function () {
            table.columns(2).search(this.value).draw();
        });
        $('#last_name').on('input', function () {
            table.columns(3).search(this.value).draw();
        });
        $('#phone').on('input', function () {
            table.columns(4).search(this.value).draw();
        });
        $('#status').on('input', function () {
            table.columns(5).search(this.value).draw();
        });

      $("#vendor_paginate").addClass("pull-right");

        $('#customer_delete').click(function() {
                var customer_ids = [];
                $.each($("input[name='selected[]']:checked"), function(){
                    customer_ids.push($(this).val());
                });
                console.log(customer_ids);
                if(customer_ids.length > 0){
                    $('#form-customer').submit();
                }else{
                    $('#errorModel').modal('show');
                }

        });

    </script>
@stop

@section('page-script')

    <script>
        function myFunction() {
            alert('clicked here');
            $("form-users").sumbit();
        }


        $(document).on('keyup', '#automplete-product', function(event) {
            //  alert(12);
            event.preventDefault();

            $('#customer tbody tr').hide();
            var txt = $(this).val().toUpperCase();
            var td1, td2, td3, td4, td5;

            if (txt != '') {
                $('#customer tbody tr').each(function() {

                    td1 = $(this).find("td")[1];
                    td2 = $(this).find("td")[2];
                    td3 = $(this).find("td")[3];
                    td4 = $(this).find("td")[4];
                    td5 = $(this).find("td")[5];

                    if (td1 || td2 || td3 || td4 || td5) {
                        if (td1.innerHTML.toUpperCase().indexOf(txt) > -1 || td2.innerHTML.toUpperCase()
                            .indexOf(txt) > -1 || td3.innerHTML.toUpperCase().indexOf(txt) > -1 || td4
                            .innerHTML.toUpperCase().indexOf(txt) > -1 || td5.innerHTML.toUpperCase()
                            .indexOf(txt) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });
            } else {
                $('#customer tbody tr').show();
            }
        });


        $(function() {
            $('input[name="automplete-product"]').focus();
        });

    </script>
@endsection
