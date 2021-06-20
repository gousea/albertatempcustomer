@extends('layouts.layout')

@section('title')
    Employee Edit
@stop

@section('styles')
<style>
    .checks.check.custom-control-input.checkboxes{
        position: absolute !important;
        opacity: none !important;
        z-index: 0 !important;
    }
    #table_orange_header_tag {
        background: none repeat scroll 0 0 !important;
        color: #f79204 !important;
    }

    .page-header{
        padding-bottom: 25px;
        border-bottom: 0px;
        margin: 0px;
    }

    #table_orange_check{
        color: #f79204;
    }

    #table_orange_header{
        text-align: center;
    }

    #table_green_header_tag {
        background: none repeat scroll 0 0 !important;
        color: #51b302 !important;
    }

    #table_green_check{
        color: #51b302;
    }

    #table_green_header{
        text-align: center;
    }

    #table_blue_header_tag {
        background: none repeat scroll 0 0 !important;
        color: #00b0ff !important;
    }

    #table_blue_check{
        color: #00b0ff;
    }

    #table_blue_header{
        text-align: center;
    }

    .control_system{
        margin-top: -5px;
    }

    .form-horizontal .form-group{
        margin-bottom: 0px;
    }
    .row{
        /*margin-left: 140px;*/
    }

    .row1{
        margin-left: 200px;
    }
    .custom-checkbox, .theadcol, .checks_content{
        text-align: center;
    }

    table tbody tr:nth-child(even) td {
        background-color: rgba(255,255,255,0.15);
    }

    #webmob {
        padding-top: 28px;
    }
    .heading {
        border-bottom: 1px solid #fcab0e;
        padding-bottom: 9px;
        position: relative;
        font-size: 14px;
        width: 100px;
        left: 24%;
        transform: translateX(-50%);
    }
    .heading span {
        background: #9e6600 none repeat scroll 0 0;
        bottom: -2px;
        height: 3px;
        left: 0;
        position: absolute;
        width: 75px;
    }

    #device_control{
        margin-left: 20px;
        display: flex;
        justify-content: space-around;
        position: relative;
        bottom: 10px;
    }
    .form-group input[type="checkbox"] {
        display: none;
    }

    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 20px;
    }

    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;
    }

    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;
    }
</style>
@endsection


@section('main-content')

    <link rel="stylesheet" href="{{ asset('asset/css/employee.css') }}">

    <form action="{{ url('/users/update', $users->iuserid) }}" method="post" enctype="multipart/form-data" id="vendorForm"
        class="form-horizontal">
        @csrf
        @method('PATCH')
        <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
            <div class="container">
                <div class="collapse navbar-collapse" id="main_nav">
                    <div class="menu">
                        <span class="font-weight-bold"> EDIT EMPLOYEE</span>
                    </div>
                    <div class="nav-submenu">
                        <button type="submit" id="saveCustomer" class="btn btn-gray headerblack  buttons_menu"><i
                            class="fa fa-save" id="myButton"></i>&nbsp;&nbsp;Save</button>
                        <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" href="{{ url('/users') }}"> CANCEL
                        </a>
                    </div>
                </div> <!-- navbar-collapse.// -->
            </div>
        </nav>

        <div class="container section-content">
            <div class="mytextdiv">
                <div class="mytexttitle font-weight-bold">
                    ACCESS
                </div>
                <div class="divider font-weight-bold"></div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 checkbox-data">
                                <div class="form-check col-xs-2 col-xs-offset-1">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" class="pos device" name="pos" value="pos"
                                                @if(isset(session()->get('userInput')['pos']))
                                                    checked
                                                @endif
                                                @if ($users->pos_user == 'Y')
                                                    checked
                                                @endif
                                        />POS
                                    </label>
                                </div>
                                <div class="form-check col-xs-2">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" class="web device" name="web" value="web"
                                                @if(isset(session()->get('userInput')['web']))
                                                    checked
                                                @endif
                                                @if ($users->web_user == 'Y')
                                                    checked
                                                @endif
                                        />
                                        WEB
                                    </label>
                                </div>
                                <div class="form-check col-xs-2">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" class="mob device" name="mob" value="mob"
                                        @if(isset(session()->get('userInput')['mob']))
                                            checked
                                        @endif
                                        @if ($users->mob_user == 'Y')
                                            checked
                                        @endif
                                     />MOBILE
                                    </label>
                                </div>
                                <div class="form-check col-xs-2">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" name="lb" value="lb" class="lb device"
                                            @if(isset(session()->get('userInput')['lb']))
                                                checked
                                            @endif
                                            @if ($users->lb_user == 'Y')
                                                checked
                                            @endif
                                        />LINEBUSTER
                                    </label>
                                </div>
                                <div class="form-check col-xs-2">
                                    <label class="form-check-label font-weight-bold">
                                        <input type="checkbox" name="time" value="time" class="time device"
                                            @if ($users->time_clock == 'Y')
                                                checked
                                            @endif
                                        />TIMECLOCK
                                    </label>
                                </div>
                            </div>
                            <!-- //col-lg-12 -->
                        </div>
                        <!-- //row -->
                    </div>

                </div>
            </div>
            <div class="mytextdiv">
                <div class="mytexttitle font-weight-bold">
                    GENERAL INFO
                </div>
                <div class="divider font-weight-bold"></div>
            </div>
            <div class="container py-3">
                <div class="row">
                    <div class="col-md-12 mx-auto">

                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputFirstname" class="p-2 float-left">FIRST NAME</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vfname" maxlength="25" value="{{ $users->vfname }}" placeholder="FIRST NAME" id="input-vfname" class="form-control" required onkeypress="return (event.charCode > 64 &&
                                        event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" style="width: 163px;"/>
                                    {{-- <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                        placeholder="PROMOTION NAME" value="{{ old('vfname') }}"> --}}
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputLastname" class="p-2 float-left">LAST NAME</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vlname" maxlength="25" value="{{ $users->vlname }}" placeholder="LAST NAME" id="input-vlname" class="form-control" required onkeypress="return (event.charCode > 64 &&
                                        event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" style="width: 163px;"/>
                                    {{-- <input type="text" class="form-control promo-fields" id="p_type"
                                        placeholder="PROMOTION TYPE" name="p_type"> --}}
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                    <label for="inputLastname" class="p-2 float-left">PHONE #</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vphone" maxlength="20" value="{{ $users->vphone }}" placeholder="PHONE" id="input-phone" class="form-control" style="width: 163px;"/>
                                    {{-- <input type="text" class="form-control promo-fields" id="p_category"
                                        placeholder="PROMOTION CATEGORY" name="p_category"> --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                    <label for="inputAddressLine1" class="p-2 float-left">ADDRESS LINE 1</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vaddress1" maxlength="75" value="{{ $users->vaddress1 }}" placeholder="ADDRESS LINE 1" id="input-vaddress1" class="form-control" style="width: 163px;"/>
                                    {{-- <input type="text" class="form-control promo-fields" id="p_bqty"
                                        placeholder="PROMOTION BUY QTY" name="p_bqty"> --}}
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                    <label for="inputAddressLine2" class="p-2 float-left">ADDRESS LINE 2</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vaddress2" maxlength="75" value="{{ $users->vaddress2 }}" placeholder="ADDRESS LINE 2" id="input-vaddress2" class="form-control" style="width: 163px;"/>
                                    {{-- <input type="text" class="form-control promo-fields" id="p_sprice"
                                        placeholder="PROMOTION SLAB PRICE" name="p_sprice"> --}}
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputAddressLine2" class="p-2 float-left">CITY</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vcity" maxlength="25" value="{{ $users->vcity }}" placeholder="CITY" id="input-city" class="form-control" onkeypress="return (event.charCode > 64 &&
                                    event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" style="width: 163px;"/>
                                    {{-- <input type="text" class="form-control promo-fields" id="f_gcustomers"
                                        placeholder="FOR REGD CUSTOMERS" name="f_gcustomers"> --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputAddressLine1" class="p-2 float-left">STATE</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vstate" maxlength="25" value="{{ $users->vstate }}" placeholder="STATE" id="input-state" class="form-control" onkeypress="return (event.charCode > 64 &&
                                        event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" style="width: 163px;"/>
                                    {{-- <input type="text" class="form-control promo-fields" id="p_itemtype"
                                        placeholder="PROMOTION ITEM TYPE" name="p_itemtype"> --}}
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputAddressLine2" class="p-2 float-left">ZIP CODE</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vzip" maxlength="10" value="{{ $users->vzip }}" placeholder="ZIP CODE" id="input-zip" class="form-control" style="width: 163px;"/>
                                    {{-- <input type="text" class="form-control promo-fields" id="q_limit"
                                        placeholder="QUANTITY LIMIT" name="q_limit"> --}}
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputAddressLine2" class="p-2 float-left">COUNTRY</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vcountry" maxlength="20" value="USA" class="form-control" readonly style="width: 163px;"/>
                                    {{-- <input type="text" class="form-control promo-fields" id="allow_reqular_price"
                                        placeholder="ALLOW REGULAR PRICE" name="allow_reqular_price"> --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputAddressLine1" class="p-2 float-left">USER TYPE</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <select name="vusertype" id="input-vusertype" class="form-control" required style="width: 163px;">
                                        <option value="">User Type</option>
                                        @foreach ($mstPermissiongroup as $group)
                                            <option value="{{$group->vgroupname}}"
                                                @if ($group->vgroupname == $users->vusertype)
                                                    selected
                                                @endif >{{ $group->vgroupname }}
                                            </option>
                                            {{-- <option value="{{$group->vgroupname}}">{{$group->vgroupname}}</option> --}}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputAddressLine2" class="p-2 float-left">STATUS</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <select name="estatus" id="input-estatus" class="form-control">
                                        <option value="Active"  {{ $users->estatus == 'Active' ? 'selected' : '' }} >Active</option>
                                        <option value="Inactive" {{ $users->estatus== 'Inactive' ? 'selected' : '' }}  >InActive</option>
                                    </select>
                                    {{-- <input type="text" class="form-control promo-fields" id="q_limit"
                                        placeholder="QUANTITY LIMIT" name="q_limit"> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="time_start">
                <div class="mytextdiv">
                    <div class="mytexttitle font-weight-bold">
                        TIME CLOCK INFO
                    </div>
                    <div class="divider font-weight-bold"></div>
                </div>
                <div class="container py-3">
                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="form-group row">
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputFirstname" class="p-2 float-left">SOCIAL SECURITY #</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="ssn" maxlength="25" value="{{ $users->ssn }}" placeholder="SOCIAL SECURITY" id="input-vuserbarcode" class="form-control" style="width: 163px;"/>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                            placeholder="SOCIAL SECURITY" > --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left">PAY TYPE</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <select name="pay_type" class="form-control">
                                            <option value="Salary" {{ $users->pay_type== 'Salary' ? 'selected' : '' }} >Salary </option>
                                            <option value="Hourly"  {{ $users->pay_type== 'Hourly' ? 'selected' : '' }} >Hourly</option>
                                        </select>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_type"
                                            placeholder="PROMOTION TYPE" name="p_type"> --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left">PAY RATE</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="pay_rate" maxlength="25" value="{{ $users->pay_rate }}" placeholder="PAY RATE" id="input-vuserbarcode" class="form-control" style="width: 163px;"/>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_category"
                                            placeholder="PROMOTION CATEGORY" name="p_category"> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                        <label for="inputAddressLine1" class="p-2 float-left">START DATE</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <?php
                                            // if(isset($users->start_dt) && $users->start_dt != '0000-00-00' && $users->start_dt != '0000-00-00 00:00:00'){
                                            //     $start_dt = DateTime::createFromFormat('Y-m-d', $users->start_dt);
                                            //     $start_dt = $start_dt->format('m-d-Y');
                                            // }
                                        ?>
                                        <input type="text" name="start_dt" maxlength="25" value="{{ $users->start_dt }}" placeholder="START DATE" id="start_dt" class="form-control " style="width: 163px;"/>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_bqty"
                                            placeholder="PROMOTION BUY QTY" name="p_bqty"> --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                        <label for="inputAddressLine2" class="p-2 float-left">TERMINATION DATE</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <?php
                                            // if(isset($users->termination_dt) && $users->termination_dt != '0000-00-00' && $users->termination_dt != '0000-00-00 00:00:00'){
                                            //     $termination_dt = DateTime::createFromFormat('Y-m-d', $users->termination_dt);
                                            //     $termination_dt = $termination_dt->format('m-d-Y');
                                            // }
                                        ?>
                                        <input type="text" name="termination_dt" maxlength="25" value="{{ $users->termination_dt }}" placeholder="TERMINATION DATE" id="termination_dt" class="form-control" style="width: 163px;" />
                                        {{-- <input type="text" class="form-control promo-fields" id="p_sprice"
                                            placeholder="PROMOTION SLAB PRICE" name="p_sprice"> --}}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mytextdiv">
                    <div class="mytexttitle font-weight-bold">
                        TIME CLOCK
                    </div>
                    <div class="divider font-weight-bold"></div>
                </div>
                <div class="container py-3">
                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="form-group row">
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form" style="width: 163px;">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputFirstname" class="p-2 float-left">TIME CLOCK ID</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="tc_id" maxlength="25" value="{{ $users->iuserid }}" id="input-vuserbarcode" class="form-control" readonly  />
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left">PASSWORD</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="tc_pass" maxlength="4" value="" placeholder="PASSWORD" id="input_tc_pass" class="form-control" style="width: 163px;"/>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_type"
                                            placeholder="PROMOTION TYPE" name="p_type"> --}}
                                        <input type="hidden" name="tc_pass" value="{{ $users->tc_pass }}" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left">CONFIRM PASSWORD</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="tcc_pass" maxlength="4" value="" placeholder="CONFIRM PASSWORD" id="input_tcc_pass" class="form-control" style="width: 163px;"/>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_category"
                                            placeholder="CONFIRM PASSWORD" name="p_category"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="web_mob_form check" id="web_mob">
                <div class="mytextdiv">
                    <div class="mytexttitle font-weight-bold">
                        WEB & MOBILE
                    </div>
                    <div class="divider font-weight-bold"></div>
                </div>
                <div class="container py-3">
                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="form-group row">
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputFirstname" class="p-2 float-left">EMAIL ID</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="email" name="vemail" maxlength="125" value="{{ $users->vemail }}" placeholder="EMAIL ID" id="input-email" class="form-control webemail" style="width: 163px;"/>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                            placeholder="PROMOTION NAME"> --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left">PASSWORD</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="mwpassword" value="" placeholder="PASSWORD" id="input-mwpassword" class="form-control webpass" style="width: 163px;"/>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_type"
                                            placeholder="PROMOTION TYPE" name="p_type"> --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left">CONFIRM PASSWORD</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="re_mwpassword" value="" placeholder="CONFIRM PASSWORD" id="input-re-mwpassword" class="form-control webconpass" style="width: 163px;"/>
                                        <div class="text-success" id="confirm-pass-msg1"></div>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_category"
                                            placeholder="PROMOTION CATEGORY" name="p_category"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="web_mob_form check" id="pos">
                <div class="mytextdiv">
                    <div class="mytexttitle font-weight-bold">
                        ALBERTA POS
                    </div>
                    <div class="divider font-weight-bold"></div>
                </div>
                <div class="container py-3">
                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="form-group row">
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputFirstname" class="p-2 float-left">POS USER ID</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="vuserid" maxlength="3" value="{{ $users->vuserid }}" placeholder="POS USER ID" id="input-vuserid" class="form-control posemail" style="width: 163px;"/>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                            placeholder="PROMOTION NAME"> --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left">PASSWORD</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="vpassword" maxlength="4" value="" placeholder="PASSWORD" id="input-vpassword" class="form-control pospass" style="width: 163px;"/>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_type"
                                            placeholder="PROMOTION TYPE" name="p_type"> --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-left">CONFIRM PASSWORD</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="re_vpassword" maxlength="4" value="" placeholder="CONFIRM PASSWORD" id="input-re-vpassword" class="form-control posconpass" style="width: 163px;"/>
                                        <div class="text-success" id="confirm-pass-msg2"></div>
                                        {{-- <input type="text" class="form-control promo-fields" id="p_category"
                                            placeholder="PROMOTION CATEGORY" name="p_category"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="mytextdiv" id="permission_divider" style="display: none;">
                <div class="mytexttitle font-weight-bold">
                    PERMISSIONS
                </div>
                <div class="divider font-weight-bold"></div>
            </div>

        </div>

        <section class="section-content py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-4" id="pos1" style="display: none;">
                        <table class="table table-bordered">
                            <thead id="table_green_header_tag">
                                <tr><th colspan="2" id="table_green_header">POS</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    @if ($permission->vpermissiontype != 'MOB' && $permission->vpermissiontype != 'WEB')
                                        <tr>
                                            <td style="height: 49.5; width: 61.8px;">
                                                <div class="custom-control custom-checkbox" id="table_green_check" style="padding: 5px; margin-left: 5px;">
                                                    <input style="position: absolute !important; opacity: 1 !important; left: 2px; min-height: 0;" type="checkbox" class="checks check custom-control-input checkboxes" id="customCheck5" name="permission[]" value="{{ $permission->vpermissioncode }}"
                                                    @if (in_array($permission->vpermissioncode, $dataPerCheck))
                                                    checked
                                                    @endif
                                                    />
                                                </div>
                                            </td>
                                            <td class="checks_content"><span>{{ strtoupper($permission->vdesc) }}</span></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4" id="web" style="display: none;">
                        <table class="table table-bordered">
                            <thead id="table_orange_header_tag">
                                    <tr>
                                    <th colspan="2" id="table_orange_header">Web Permissions</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    @if ($permission->vpermissiontype == 'WEB')
                                        @if($permission->vpermissionname!='LOYALITY')
                                            <tr>
                                                <td style="height: 49.5; width: 61.8px;">
                                                    <div class="custom-control custom-checkbox" id="table_orange_check" style="padding: 5px; margin-left: 5px;">
                                                        <input style="position: absolute !important; opacity: 1 !important; left: 2px; min-height: 0;" type="checkbox" class="checks check custom-control-input" id="customCheck5" name="permission[]" value="{{ $permission->vpermissioncode }}"
                                                            @if (in_array($permission->vpermissioncode, $dataPerCheck))
                                                            checked
                                                            @endif
                                                        />
                                                    </div>
                                                </td>
                                                <td class="checks_content">
                                                    <span>{{ $permission->vpermissionname }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4" id="mob" style="display: none;">
                        <table class="table table-bordered">
                            <thead id="table_blue_header_tag">
                                <tr>
                                <th colspan="2" id="table_blue_header">Mobile Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    @if ($permission->vpermissiontype == 'MOB')
                                    <tr>
                                        <td style="height: 49.5; width: 61.8px;">
                                            <div class="custom-control custom-checkbox" id="table_blue_check" style="padding: 5px; margin-left: 5px;">
                                                <input style="position: absolute !important; opacity: 1 !important; left: 2px; min-height: 0;" type="checkbox" class="checks check custom-control-input" id="customCheck5" name="permission[]" value="{{ $permission->vpermissioncode }}"
                                                @if (in_array($permission->vpermissioncode, $dataPerCheck))
                                                checked
                                                @endif
                                                />

                                            </div>
                                        </td>
                                        <td class="checks_content"><span>{{ $permission->vpermissionname }}</span></td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>
{{-- old date picker --}}
{{-- <link type="text/css" href="{{ asset('javascript/bootstrap-datepicker.css') }}" rel="stylesheet" /> --}}

<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
 <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script>
    $(function(){
        $("#termination_dt").datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            widgetPositioning:{
                horizontal: 'auto',
                vertical: 'bottom'
            }
        });
    });
    $(function(){
        $("#start_dt").datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            widgetPositioning:{
                horizontal: 'auto',
                vertical: 'bottom'
            }
        });
    });
    $(document).ready(function(){
        //  $("#time_start").hide();

    });
    // $().bfhtimepicker('toggle')

</script>


<script type="text/javascript">
    // $(window).load(function() {
    $(document).ready(function() {
      $('#web_mob').hide();
      $('#pos').hide();
      $("div#divLoading").removeClass('show');
    });
  </script>

<script type="text/javascript">
  $(document).on('keyup', 'input[name="re_vpassword"]', function(event) {
    event.preventDefault();
    var vpassword = $('input[name="vpassword"]').val();
    var re_vpassword = $(this).val();

    if(vpassword == ''){
      alert('Please Enter Password');
      return false;
    }

    if(vpassword != '' && vpassword == re_vpassword){
      $('#confirm-pass-msg2').removeClass('text-danger').addClass('text-success');
      $('#confirm-pass-msg2').html('POS Password Matched');
      $(':input[type="submit"]').prop('disabled', false);
      return false;
    }else{
      $('#confirm-pass-msg2').removeClass('text-success').addClass('text-danger');
      $('#confirm-pass-msg2').html('POS Password Not Matched');
      $(':input[type="submit"]').prop('disabled', true);
      return false;
    }
  });

  $(document).on('keyup', 'input[name="re_mwpassword"]', function(event) {
    event.preventDefault();
    var mwpassword = $('input[name="mwpassword"]').val();
    var re_mwpassword = $(this).val();

    if(mwpassword == ''){
      alert('Please Enter Password');
      return false;
    }

    if(mwpassword != '' && mwpassword == re_mwpassword){
      $('#confirm-pass-msg1').removeClass('text-danger').addClass('text-success');
      $('#confirm-pass-msg1').html('Web Password Matched');
      $(':input[type="submit"]').prop('disabled', false);
      return false;
    }else{
      $('#confirm-pass-msg1').removeClass('text-success').addClass('text-danger');
      $('#confirm-pass-msg1').html('Web Password Not Matched');
      $(':input[type="submit"]').prop('disabled', true);
      return false;
    }
  });


  $(document).on('keyup', 'input[name="vuserid"]', function(event) {
    event.preventDefault();
    var vuserid = $(this).val();

    if(!vuserid.match(/^\d{3}$/)){
      $('#user-id-msg').removeClass('text-success').addClass('text-danger');
      $('#user-id-msg').html('Please Enter Numeric User ID');
      return false;
    }else{
      $('#user-id-msg').removeClass('text-success').removeClass('text-danger');
      $('#user-id-msg').html('');
    }
  });

  $(document).on('keypress keyup blur', 'input[name="vzip"],input[name="vuserid"]', function(event) {

    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }

  });

</script>

<script src="{{ asset('javascript/jquery.maskedinput.min.js') }}"></script>
<script type="text/javascript">
  jQuery(function($){
    $("input[name='vphone']").mask("999-999-9999");
  });
</script>
<script>

setInterval(function() {
    if($('.mob').prop("checked") == true){
        $('#web_mob').show();
        $('#permission_divider').show();
        document.getElementById("Submit").disabled = false;
    }

    if($('.web').prop("checked") == true){
        $('#web_mob').show();
        $('#permission_divider').show();
        document.getElementById("Submit").disabled = false;
    }

    if($('.lb').prop("checked") == true){
        // $('#permission_divider').show();
        document.getElementById("Submit").disabled = false;
    }

    if($('.pos').prop("checked") == true){
        $('#pos').show();
        $('#permission_divider').show();
        // document.getElementById("Submit").disabled = false;

        var vpassword = $('input[name="vpassword"]').val();
        var re_vpassword = $('input[name="re_vpassword"]').val();

        if(vpassword == ''){
            $(':input[type="submit"]').prop('disabled', false);
        }else if(vpassword == re_vpassword){
            $(':input[type="submit"]').prop('disabled', false);
        }else{
          $(':input[type="submit"]').prop('disabled', true);

        }

        var vemailval = $('.webemail').val();
        if(vemailval == '--'){
          empty_val = "";
         $('.webemail').attr('value', empty_val);
        }
    }

    if($('.time').prop("checked") == true){
        $('#time_start').show();
        document.getElementById("Submit").disabled = false;
    }

}, 300);



$(function () {
  $('.mob').change(function () {

     $('#web_mob').toggle(this.checked);
     if(this.checked){
         $('.webemail').prop('required', true);
         $('.webpass').prop('required', true);
         $('.webconpass').prop('required', true);
         document.getElementById("Submit").disabled = false;
     }else{
         $('.webemail').prop('required', false);
         $('.webpass').prop('required', true);
         $('.webconpass').prop('required', true);
         document.getElementById("Submit").disabled = true;
     }
  }).change();
});

$(function () {
  $('.pos').change(function () {
     $('#pos').toggle(this.checked);
     if(this.checked){
         $('.posemail').prop('required', true);
        //  document.getElementById("Submit").disabled = false;
     }else{
         $('.posemail').prop('required', false);
        //  document.getElementById("Submit").disabled = true;
     }
  }).change();
});

//new code
// $('.web').click(function () {
//     $('#web_mob').toggle(this.checked);
//     if(this.checked){
//         $('#web').show();
//     }else{
//         $('#web').hide();
//     }
// });

// $('.pos').click(function () {
//     $('#pos').toggle(this.checked);
//     if(this.checked){
//         $('#pos1').show();
//     }else{
//         $('#pos1').hide();
//     }
// });

// $('.mob').click(function () {
//     $('#web_mob').toggle(this.checked);
//     if(this.checked){
//         $('#mob').show();
//     }else{
//         $('#mob').hide();
//     }
// });

$('.web').click(function () {
    $('#web_mob').toggle(this.checked);
    if(this.checked){
        $('#web').show();
        $("#input-email").attr("required", "required");
        // $("#input-mwpassword").attr("required", "required");
        // $("#input-re-mwpassword").attr("required", "required");
    }else{
        $('#web').hide();
        $('#permission_divider').hide();
        $("#input-email").removeAttr("required", "required");
        // $("#input-mwpassword").removeAttr("required", "required");
        // $("#input-re-mwpassword").removeAttr("required", "required");
    }
});

$('.pos').click(function () {
    $('#pos').toggle(this.checked);
    if(this.checked){
        $('#pos1').show();
        $("#input-vuserid").attr("required", "required");
        $("#input-vpassword").attr("required", "required");
        $("#input-re-vpassword").attr("required", "required");
    }else{
        $("#input-vuserid").removeAttr("required", "required");
        $("#input-vpassword").removeAttr("required", "required");
        $("#input-re-vpassword").removeAttr("required", "required");
        $('#pos1').hide();
        $('#permission_divider').hide();
    }

    if(!$(".web").is(':checked') && !$(".mob").is(':checked') ){
        // $("#input-mwpassword").removeAttr("required", "required");
        // $("#input-re-mwpassword").removeAttr("required", "required");
    }
});

$('.mob').click(function () {
    $('#web_mob').toggle(this.checked);
    if($(".web").is(':checked')){
        if(this.checked){
            $('#mob').show();
            $("#input-email").removeAttr("required", "required");
            // $("#input-mwpassword").removeAttr("required", "required");
            // $("#input-re-mwpassword").removeAttr("required", "required");
        }else {
            $('#mob').hide();
            $('#permission_divider').hide();
        }
    }else {
        if(this.checked){
            $('#mob').show();
            $("#input-email").attr("required", "required");
            // $("#input-mwpassword").attr("required", "required");
            // $("#input-re-mwpassword").attr("required", "required");
        }else{
            $("#input-email").removeAttr("required", "required");
            // $("#input-mwpassword").removeAttr("required", "required");
            // $("#input-re-mwpassword").removeAttr("required", "required");
            $('#mob').hide();
            $('#permission_divider').hide();
        }
    }
});

$('.time').click(function () {
    //$('#web_mob').toggle(this.checked);
    if(this.checked){
        $("#time_start").show();
        document.getElementById("Submit").disabled = false;

        if(!$(".web").is(':checked') && !$(".mob").is(':checked') ){
            // $("#input-mwpassword").removeAttr("required", "required");
            // $("#input-re-mwpassword").removeAttr("required", "required");
        }

        $("#input_tc_pass").attr("required", "required");
        $("#input_start_dt").attr("required", "required");
        $("#input_termination_dt").attr("required", "required");
    }
    else
    {
        $("#time_start").hide();
        $("#input_tc_pass").removeAttr("required", false);
        $("#input_start_dt").attr("required", "false");
        $("#input_termination_dt").attr("required", "false");
    }
});

$(function () {
  $('.web').change(function () {
     $('#web_mob').toggle(this.checked);
     if(this.checked){
         $('.webemail').prop('required', true);
         document.getElementById("Submit").disabled = false;
     }else{
         $('.webemail').prop('required', false);
         document.getElementById("Submit").disabled = true;
     }
  }).change();
});

if($(".mob").is(':checked')){
    $('#mob').show();
}

if($(".web").is(':checked')){
    $('#web').show();
}

if($(".pos").is(':checked')){
    $('#pos1').show();
}


jQuery('#input-vusertype').change(function () {
    var selected_option = $('#input-vusertype').val();

    if(selected_option == 'Manager'){
        $('.check').each(function(){ this.checked = false; });
        var ManagerValues = ["PER3012","PER3011","5","20","9","1","10","17","PER00074","PER1005", "PER1002", "PER1003", "PER1006", "PER1007", "PER1008", "PER1010", "PER1012", "PER2003", "PER2004", "PER2005", "PER2007", "PER2008", "PER003", "PER00071", "PER00072", "PER00075", "PER00077", "PER00080", "PER00083", "PER00084", "PER00086", "PER00087", "PER3001", "PER3002", "PER3003", "PER3004", "PER3005", "PER3006", "PER3009", "PER3010"];

        $.each(ManagerValues, function(i, val){
            $("input[value='" + val + "']").prop('checked', true);
        });
    }else if(selected_option == 'Cashier'){
        $('.check').each(function(){ this.checked = false; });
        var CashierValues = ["PER00074","PER3012","PER3011","10","1","9","20","5","PER1006", "PER3005", "PER3006", "PER3004", "PER3003", "PER3002", "PER3001", "PER00087", "PER00084", "PER003", "PER00083", "PER2003", "PER2004", "PER2005", "PER2008"];

        $.each(CashierValues, function(i, val){
            $("input[value='" + val + "']").prop('checked', true);
        });
    }else if(selected_option == 'Admin'){
        $('.check').each(function(){ this.checked = false; });
        var AdminValues = ["PER003", "PER00071", "PER00072", "PER00075", "PER00077", "PER00080", "PER00081",
        "PER00083", "PER00084", "PER00086", "PER00087", "PER1001", "PER1002", "PER1003", "PER1004",
        "PER1005", "PER1006", "PER1007", "PER1008", "PER1009", "PER1010", "PER1011", "PER1012",
        "PER2001", "PER2002", "PER2003", "PER2004", "PER2005", "PER2006", "PER2007", "PER2008",
        "PER3001", "PER3002", "PER2003", "PER3003", "PER3004", "PER3005", "PER3006", "PER3007",
        "PER3008", "PER3009", "PER2003", "PER3010", "PER2009", "PER00074", "PER3012", "PER3011", "PER00073",
        "5", "20", "9", "1", "10", "15", "16", "17","18", "PER00082", "PER00085"];

        $.each(AdminValues, function(i, val){
            $("input[value='" + val + "']").prop('checked', true);
        });
    }

});
</script>

<script type="text/javascript">
$(function () {
    $('.datetimepicker3').datetimepicker({
        format: 'HH:mm'
    });
});
</script>
@endsection
