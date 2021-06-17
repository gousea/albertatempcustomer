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
                    <span class="font-weight-bold"> EMPLOYEES1</span>
                </div>
                <div class="nav-submenu">
                    <a type="button" class="btn btn-gray headerblack  buttons_menu " href="{{route('employee.create')}}"> ADD NEW
                    </a>
                    <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> DELETE
                    </a>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </div>
</nav>

<section class="section-content py-6">
    <div class="container-fluid">
        <table data-toggle="table" data-classes="table table-hover table-condensed employeeview"
            data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
            data-pagination="true" data-click-to-select="true">
            <thead>
                <tr class="header-color">
                    <th data-field="state" data-checkbox="true"></th>
                    <th class="col-xs-1 headername" data-field="Product_Name">NAME
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="SEARCH">
                        </div>
                    </th>
                    <th class="col-xs-1 headername" data-field="Quality">CELL NUMBER
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="SEARCH">
                        </div>
                    </th>
                    <th class="col-xs-6 headername" data-field="Quantity">EMAIL
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="SEARCH">
                        </div>
                    </th>
                    <th class="col-xs-6 headername" data-field="Quantity">STATUS
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <!-- <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control" placeholder="Search">
                        </div> -->
                    </th>
                </tr>
            </thead>
            <tbody class="table-body">
                @foreach ($customers as $customer)
                    <tr id="customer-row">
                        <td data-order="" class="text-center">
                            <input type="checkbox" name="selected[]" id=""
                                value="{{ $user->iuserid }}" />
                        </td>
                        <td>
                            <a href="{{route('employee.edit', $user->iuserid)}}"
                                data-toggle="tooltip" title="Edit"><span>{{ $user->vfname }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="{{route('employee.edit', $user->iuserid)}}"
                                data-toggle="tooltip" title="Edit"><span>{{ $user->vphone }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="{{route('employee.edit', $user->iuserid)}}"
                                data-toggle="tooltip" title="Edit"><span>{{ $user->vemail }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="{{route('employee.edit', $user->iuserid)}}"
                                data-toggle="tooltip" title="Edit"><span>{{ $user->estatus }}</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@endsection
