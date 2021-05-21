@extends('layouts.layout')

@section('title')
    Customer
@stop

@section('main-content')

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue menu">
        <div class="container-fluid">
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
    </nav>
    {{-- <input type="button" id="customer_delete"> --}}

    <section class="section-content menu">
        @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        <div class="container-fluid">
            <form action="{{ route('customers.remove') }}" method="post" enctype="multipart/form-data" id="form-customer">
                @csrf
                <input type="hidden" name="MenuId" value="$filter_menuid;" />
                <div class="table-responsive">
                    {{-- <table id="customer" class="table table-hover employeeview">
                        <thead>
                            <tr class="header-color">
                                <th class="text-center"><input type="checkbox"
                                        onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                                <th class="col-xs-1 headername text-uppercase">Customer
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="CUSTOMER">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">First Name
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="FIRST NAME">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Last Name
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="LAST NAME">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Phone
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="PHONE">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Status
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="STATUS">
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr id="customer-row">
                                    <td data-order="" class="text-center">
                                        <input type="checkbox" name="selected[]" id="" value="{{ $customer->icustomerid }}" />
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $customer->vcustomername }}</span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <span>{{ $customer->vfname }}</span>
                                    </td>

                                    <td class="text-left">
                                        <span>{{ $customer->vlname }} </span>
                                    </td>

                                    <td class="text-left">
                                        <span>{{ $customer->vphone }}</span>
                                    </td>

                                    <td class="text-left">
                                        <span>{{ $customer->estatus }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                    <table id="vendor" class="table table-hover employeeview">
                        <thead>
                            <tr class="header-color">
                                <th class="text-center"><input type="checkbox"
                                    onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>

                                <th class="col-xs-1 headername text-uppercase">Customer
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="CUSTOMER">
                                    </div>
                                </th>

                                <th class="col-xs-1 headername text-uppercase">First Name
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="FIRST NAME">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Last Name
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="LAST NAME">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Phone
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="PHONE">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Status
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="STATUS">
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr id="customer-row">
                                    <td data-order="" class="text-center">
                                        <input type="checkbox" name="selected[]" id="" value="{{ $customer->icustomerid }}" />
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $customer->vcustomername }}</span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $customer->vfname }}</span>
                                        </a>

                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $customer->vlname }} </span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $customer->vphone }}</span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('customers.edit', $customer->icustomerid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $customer->estatus }}</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="row">
                <div class="col-sm-6 text-left">{{ $customers->links() }}</div>
                <div class="col-sm-6 text-right"></div>
            </div>
        </div>
    </section>

@endsection

@section('page-script')
    <script>
        $('#customer_delete').click(function() {
            $('#form-customer').submit();
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
