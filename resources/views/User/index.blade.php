@extends('layouts.layout')

@section('title')
    Employee
@stop

@section('main-content')


<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue menu">
    <div class="container">
        <div class="row">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold"> EMPLOYEE</span>
                </div>
                <div class="nav-submenu">
                    <a type="button" class="btn btn-gray headerblack  buttons_menu " href="{{route('users.create')}}"> ADD NEW
                    </a>
                    <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small"
                            id="employee_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a>
                    {{-- <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" href="{{ route('users.remove') }}"> DELETE
                    </a> --}}
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </div>
</nav>
<div id='errorDiv'>
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <i class="fa fa-exclamation-circle"></i>{{ $error }}
            @endforeach
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
</div>

<section class="section-content menu">
    @if (session()->has('message'))
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
            {{ session()->get('message') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    <div class="container">
        <form action="{{ route('users.remove') }}" method="post" enctype="multipart/form-data" id="form-customer">
            @csrf
            <input type="hidden" name="MenuId" value="$filter_menuid;" />
            <div class="table-responsive">
                <table id="vendor" class="table table-hover promotionview" style="width: 100%">
                    <thead>
                        <tr class="header-color">
                            <th class="text-center"><input type="checkbox"
                                    onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>

                            <th class="col-xs-1 headername text-uppercase">NAME
                                {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" class="form-control table-heading-fields" placeholder="NAME" id="name">
                                </div>
                            </th>

                            <th class="col-xs-1 headername text-uppercase">CELL NUMBER
                                {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" class="form-control table-heading-fields"
                                        placeholder="CELL NUMBER" id="cell_number">
                                </div>
                            </th>
                            <th class="col-xs-1 headername text-uppercase">EMAIL
                                {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" class="form-control table-heading-fields"
                                        placeholder="EMAIL" id="email">
                                </div>
                            </th>
                            <th class="col-xs-1 headername text-uppercase">STATUS
                                {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" class="form-control table-heading-fields" placeholder="STATUS" id="status">
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @foreach ($users as $user)
                            <tr id="customer-row">
                                <td data-order="" class="text-center">
                                    <input type="checkbox" name="selected[]" id=""
                                        value="{{ $user->iuserid }}" />
                                </td>
                                <td>
                                    <a href="{{route('users.edit', $user->iuserid)}}"
                                        data-toggle="tooltip" title="Edit"><span>{{ $user->vfname }}</span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('users.edit', $user->iuserid)}}"
                                        data-toggle="tooltip" title="Edit"><span>{{ $user->vphone }}</span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('users.edit', $user->iuserid)}}"
                                        data-toggle="tooltip" title="Edit"><span>{{ $user->vemail }}</span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('users.edit', $user->iuserid)}}"
                                        data-toggle="tooltip" title="Edit"><span>{{ $user->estatus }}</span>
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
                        <h3 style="font-size: 13px; text-transform: uppercase;">No Employee is selected </h3>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" style="font-size: 13px; width: 65px;" class="btn btn-primary text-uppercase" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('page-script')
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    var table = $('#vendor').DataTable({
        // "dom": 't<"bottom col-md-12 row"<"col-md-2"i><"col-md-3"l><"col-md-7"p>>',
        "dom": 't<<"float-right"p>><"clear">',
        "searching":true,
        "destroy": true,
        "ordering": false,
        "pageLength":10,
        "order": [[ 3, "asc" ]]
    });

    $('#name').on('input', function () {
        table.columns(1).search(this.value).draw();
    });
    $('#cell_number').on('input', function () {
        table.columns(2).search(this.value).draw();
    });
    $('#email').on('input', function () {
        table.columns(3).search(this.value).draw();
    });
    $('#status').on('input', function () {
        table.columns(4).search(this.value).draw();
    });

  $("#vendor_paginate").addClass("pull-right");

    $('#employee_delete').click(function() {
            var employee_ids = [];
            $.each($("input[name='selected[]']:checked"), function(){
                employee_ids.push($(this).val());
            });
            console.log(employee_ids);
            if(employee_ids.length > 0){
                $('#form-customer').submit();
            }else{
                $('#errorModel').modal('show');
            }

    });

</script>

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
