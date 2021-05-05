@extends('layouts.layout')

@section('main-content')


    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> customer</span>
                </div>
                <div class="nav-submenu">
                    <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#"> ADD NEW
                    </a>
                    <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> DELETE
                    </a>
                </div>
            </div> <!-- navbar-collapse.// -->
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
                        <th class="col-xs-1 headername text-uppercase" data-field="supplier_code">Customer
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control table-heading-fields" placeholder="SEARCH">
                            </div>
                        </th>
                        <th class="col-xs-1 headername text-uppercase" data-field="vendor_name">First Name
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control table-heading-fields" placeholder="SEARCH">
                            </div>
                        </th>
                        <th class="col-xs-6 headername text-uppercase" data-field="phone">Last Name
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control table-heading-fields" placeholder="SEARCH">
                            </div>
                        </th>
                        <th class="col-xs-6 headername text-uppercase" data-field="eamil">Phone
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control table-heading-fields" placeholder="SEARCH">
                            </div>
                        </th>
                        <th class="col-xs-6 headername text-uppercase" data-field="eamil">Status
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control table-heading-fields" placeholder="SEARCH">
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Wheat</a></td>
                        <td>Good</td>
                        <td>200 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Rice</a></td>
                        <td>Good</td>
                        <td>100 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Rice</a></td>
                        <td>Good</td>
                        <td>100 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Sugar</a></td>
                        <td>Prime</td>
                        <td>200 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Maze</a></td>
                        <td>Fine</td>
                        <td>10 Packs</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Sugar</a></td>
                        <td>Prime</td>
                        <td>200 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Sugar</a></td>
                        <td>Prime</td>
                        <td>200 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Sugar</a></td>
                        <td>Prime</td>
                        <td>200 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Sugar</a></td>
                        <td>Prime</td>
                        <td>200 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Sugar</a></td>
                        <td>Prime</td>
                        <td>200 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                    <tr id="tr-id-2" class="tr-class-2">
                        <td></td>
                        <td><a href="{{ route('employee.edit') }}">Sugar</a></td>
                        <td>Prime</td>
                        <td>200 Bags</td>
                        <td>Active</td>
                        <td>Active</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

@endsection
