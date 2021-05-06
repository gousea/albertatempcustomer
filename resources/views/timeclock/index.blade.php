@extends('layouts.layout')

@section('main-content')

    <link rel="stylesheet" href="{{ asset('asset/css/timeclock.css') }}">

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Time clock</span>
                </div>
                <div class="nav-submenu">
                    <a type="button" class="btn btn-gray headerblack  buttons_menu text-uppercase" href="#"> print time
                        clock
                    </a>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>



    <section class="section-content py-6">
        <div class="container-fluid week-header">
            <ul class="list-group px-5">
                <li class="list-group-item text-center background-main-color">
                    <a href="#" class="caret"><i class="fa fa-caret-left fa-2x"></i></a>
                    <span id="week" class="font-weight-bold text-uppercase"> Week of JANUARY 1, 2021 - JANUARY 7,
                        2021</span>
                    <a href="#" class="caret"><i class="fa fa-caret-right fa-2x"></i></a>
                </li>
            </ul>
        </div>
        <div class="container-fluid">
            <table data-toggle="table" data-classes="table promotionview" data-pagination="true">
                <thead>
                    <tr class="header-color">
                        <th class="col-xs-1 text-uppercase" data-field="Product_Name">Employee NAME
                        </th>
                        <th class="col-xs-1 text-uppercase" data-field="Quality1">Sun Jan 1
                        </th>
                        <th class="col-xs-1 text-uppercase" data-field="Quantity2"> Mon Jan 2
                        </th>
                        <th class="col-xs-1 text-uppercase" data-field="Quantity3"> Tues Jan 3
                        </th>
                        <th class="col-xs-1  text-uppercase" data-field="Quantity4"> Wed Jan 4
                        </th>
                        <th class="col-xs-1 text-uppercase" data-field="Quantity5"> Thur Jan 5
                        </th>
                        <th class="col-xs-1 text-uppercase" data-field="Quantity6"> Fri Jan 6
                        </th>
                        <th class="col-xs-1 text-uppercase" data-field="Quantity7"> Sat Jan 7
                        </th>
                        <th class="col-xs-1 text-uppercase" data-field="Quantity8">Total Hours
                            <!-- <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" class="form-control" placeholder="Search">
                                </div> -->
                        </th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    <tr>
                        <th rowspan="2" class="text-center text-uppercase font-weight-bold text-primary">First last name
                        </th>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <th rowspan="2" class="text-center text-uppercase">
                            <div>
                                <label class="text-uppercase font-weight-bold text-primary" for="">68 Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">03mins</p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center text-uppercase font-weight-bold text-primary">First last name
                        </th>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <th rowspan="2" class="text-center text-uppercase">
                            <div>
                                <label class="text-uppercase font-weight-bold text-primary" for="">68 Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">03mins</p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center text-uppercase font-weight-bold text-primary">First last name
                        </th>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <th rowspan="2" class="text-center text-uppercase">
                            <div>
                                <label class="text-uppercase font-weight-bold text-primary" for="">68 Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">03mins</p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center text-uppercase font-weight-bold text-primary">First last name
                        </th>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <th rowspan="2" class="text-center text-uppercase">
                            <div>
                                <label class="text-uppercase font-weight-bold text-primary" for="">68 Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">03mins</p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center text-uppercase font-weight-bold text-primary">First last name
                        </th>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <th rowspan="2" class="text-center text-uppercase">
                            <div>
                                <label class="text-uppercase font-weight-bold text-primary" for="">68 Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">03mins</p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center text-uppercase font-weight-bold text-primary">First last name
                        </th>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <th rowspan="2" class="text-center text-uppercase">
                            <div>
                                <label class="text-uppercase font-weight-bold text-primary" for="">68 Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">03mins</p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center text-uppercase font-weight-bold text-primary">First last name
                        </th>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Hours logged</label>
                                <p class="text-uppercase font-weight-bold text-primary">9:03AM - 8:56PM</p>
                            </div>
                        </td>
                        <th rowspan="2" class="text-center text-uppercase">
                            <div>
                                <label class="text-uppercase font-weight-bold text-primary" for="">68 Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">03mins</p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label class="text-uppercase text-secondary" for="">Total Hours</label>
                                <p class="text-uppercase font-weight-bold text-primary">11 Hours - 56MINS</p>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </section>

@endsection
