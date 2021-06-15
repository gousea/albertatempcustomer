@extends('layout')

@section('main-content')

<link rel="stylesheet" href="{{ asset('asset/css/employee.css') }}">

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold"> ADD EMPLOYEE</span>
            </div>
            <div class="nav-submenu">
                <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#"> SAVE
                </a>
                <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" href="{{route('employee.index')}}"> CANCEL
                </a>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
</nav>

<div class="container-fluid section-content">
   <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold">
            ACCESS
        </div>
        <div class="divider font-weight-bold"></div>
    </div>
    <div class="container">
        <div class="row">
                <form action="#">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 checkbox-data">
                                <div class="form-check col-xs-2 col-xs-offset-1">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" class="form-check-input" value="">ALBERTA POS
                                    </label>
                                </div>
                                <div class="form-check col-xs-2">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" class="form-check-input " value="">WEB
                                    </label>
                                </div>
                                <div class="form-check col-xs-2">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" class="form-check-input" value="">MOBILE
                                    </label>
                                </div>
                                <div class="form-check col-xs-2">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" class="form-check-input" value="">LINEBUSTER
                                    </label>
                                </div>
                                <div class="form-check col-xs-2">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" class="form-check-input" value="">TIMECLOCK
                                    </label>
                                </div>
                            </div>
                    <!-- //col-lg-12 -->
                        </div>
          <!-- //row -->
                    </div>
                </form>
        </div>
    </div>
    <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold">
            GENERAL INFO
        </div>
        <div class="divider font-weight-bold"></div>
    </div>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <form>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputFirstname" class="p-2 float-left">FIRST NAME</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                    placeholder="PROMOTION NAME">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left">LAST NAME</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_type"
                                    placeholder="PROMOTION TYPE" name="p_type">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <label for="inputLastname" class="p-2 float-left">PHONE #</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <input type="text" class="form-control promo-fields" id="p_category"
                                    placeholder="PROMOTION CATEGORY" name="p_category">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <label for="inputAddressLine1" class="p-2 float-left">ADDRESS LINE 1</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <input type="text" class="form-control promo-fields" id="p_bqty"
                                    placeholder="PROMOTION BUY QTY" name="p_bqty">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <label for="inputAddressLine2" class="p-2 float-left">ADDRESS LINE 2</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <input type="text" class="form-control promo-fields" id="p_sprice"
                                    placeholder="PROMOTION SLAB PRICE" name="p_sprice">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputAddressLine2" class="p-2 float-left">CITY</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="f_gcustomers"
                                    placeholder="FOR REGD CUSTOMERS" name="f_gcustomers">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputAddressLine1" class="p-2 float-left">STATE</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_itemtype"
                                    placeholder="PROMOTION ITEM TYPE" name="p_itemtype">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputAddressLine2" class="p-2 float-left">ZIP CODE</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="q_limit"
                                    placeholder="QUANTITY LIMIT" name="q_limit">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputAddressLine2" class="p-2 float-left">COUNTRY</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="allow_reqular_price"
                                    placeholder="ALLOW REGULAR PRICE" name="allow_reqular_price">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputAddressLine1" class="p-2 float-left">USER TYPE</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_status"
                                    placeholder="PROMOTION STATUS" name="p_status">
                            </div>
                        </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputAddressLine2" class="p-2 float-left">STATUS</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="q_limit"
                                    placeholder="QUANTITY LIMIT" name="q_limit">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold">
            TIME CLOCK INFO
        </div>
        <div class="divider font-weight-bold"></div>
    </div>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <form>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputFirstname" class="p-2 float-left">SOCIAL SECURITY #</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                    placeholder="PROMOTION NAME">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left">PAY TYPE</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_type"
                                    placeholder="PROMOTION TYPE" name="p_type">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <label for="inputLastname" class="p-2 float-left">PAY RATE</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <input type="text" class="form-control promo-fields" id="p_category"
                                    placeholder="PROMOTION CATEGORY" name="p_category">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <label for="inputAddressLine1" class="p-2 float-left">START DATE</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <input type="text" class="form-control promo-fields" id="p_bqty"
                                    placeholder="PROMOTION BUY QTY" name="p_bqty">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <label for="inputAddressLine2" class="p-2 float-left">TERMINATION DATE</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <input type="text" class="form-control promo-fields" id="p_sprice"
                                    placeholder="PROMOTION SLAB PRICE" name="p_sprice">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
     <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold">
            WEB & MOBILE
        </div>
        <div class="divider font-weight-bold"></div>
    </div>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <form>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputFirstname" class="p-2 float-left">EMAIL ID</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                    placeholder="PROMOTION NAME">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left">PASSWORD</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_type"
                                    placeholder="PROMOTION TYPE" name="p_type">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left">CONFIRM PASSWORD</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_category"
                                    placeholder="PROMOTION CATEGORY" name="p_category">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold">
            ALBERTA POS
        </div>
        <div class="divider font-weight-bold"></div>
    </div>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <form>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputFirstname" class="p-2 float-left">POS USER ID</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                    placeholder="PROMOTION NAME">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left">PASSWORD</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_type"
                                    placeholder="PROMOTION TYPE" name="p_type">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left">CONFIRM PASSWORD</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_category"
                                    placeholder="PROMOTION CATEGORY" name="p_category">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

     <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold">
            TIME CLOCK
        </div>
        <div class="divider font-weight-bold"></div>
    </div>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <form>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputFirstname" class="p-2 float-left">TIME CLOCK ID</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                    placeholder="PROMOTION NAME">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left">PASSWORD</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_type"
                                    placeholder="PROMOTION TYPE" name="p_type">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left">CONFIRM PASSWORD</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_category"
                                    placeholder="PROMOTION CATEGORY" name="p_category">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold">
            PERMISSIONS
        </div>
        <div class="divider font-weight-bold"></div>
    </div>

</div>

<section class="section-content py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <table data-toggle="table" data-classes="table table-hover table-condensed"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality"
                    data-click-to-select="true">
                    <thead>
                        <tr class="">
                            <th data-field="state" data-checkbox="true"></th>
                            <th class="col-xs-1 table-headername" data-field="item_name">
                                ALBERTA POS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION1</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION2</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION3</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION4</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION5</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION6</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION7</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION8</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION9</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION10</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION11</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table data-toggle="table" data-classes="table table-hover table-condensed"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality"
                    data-click-to-select="true">
                    <thead>
                        <tr class="">
                            <th data-field="state" data-checkbox="true"></th>
                            <th class="col-xs-1 table-headername" data-field="item_name">
                                WEB
                            </th>
                        </tr>
                    </thead>
                     <tbody>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION1</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION2</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION3</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION4</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION5</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION6</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION7</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION8</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION9</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION10</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION11</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table data-toggle="table" data-classes="table table-hover table-condensed"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality"
                    data-click-to-select="true">
                    <thead>
                        <tr class="">
                            <th data-field="state" data-checkbox="true"></th>
                            <th class="col-xs-1 table-headername" data-field="item_name">
                                MOBILE
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION1</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION2</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION3</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION4</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION5</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION6</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION7</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION8</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION9</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION10</td>
                        </tr>
                        <tr id="tr-id-2" class="tr-class-2">
                            <td></td>
                            <td>PERMISSION11</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection
