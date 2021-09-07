@extends('layouts.layout')

@section('title')
Aisle
@endsection

@section('main-content')
<div id="content">
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Gift Card</span>
                </div>
                <div class="nav-submenu">
                    <button type="button" id="save_button" class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                    <button type="button" onclick="addAisle();" data-toggle="tooltip" class="btn btn-gray headerblack  buttons_menu " href="#"> <i class="fa fa-plus"></i>&nbsp;&nbsp; Add New</button>
                    <button type="button" id="aisle_delete" onclick="myFunction()" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> <i class="fa fa-trash"></i>&nbsp;&nbsp; Delete</button>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>

    <section class="section-content py-6">
        <div class="container">
            @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif

            @if (session()->has('error'))
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{session()->get('error')}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif
            <div id='errorDiv'></div>
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <i class="fa fa-exclamation-circle"></i>{{$error}}
                @endforeach
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif

            <div class="panel panel-default">

                <div class="panel-body">

                    <form action="" method="post" enctype="multipart/form-data" id="form-aisle">
                        @csrf
                        <div class="table-responsive">
                            <table id="aisleTable" class="text-center table  table-hover" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                                <thead style="background-color: #286fb7!important;">
                                    <tr>
                                        <th style="width: 1px;color:black;" class="text-center">
                                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                                        </th>
                                        <th class="col-xs-1 headername text-uppercase text-light">GIFT CARD
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                            <div class="form-group has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields" style="padding-left: 30px;" placeholder="SEARCH">
                                            </div>
                                        </th>
                                        <th class="col-xs-1 headername text-uppercase text-light">EXPIRATION
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                            <div class="form-group has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields" style="padding-left: 30px;" placeholder="SEARCH">
                                            </div>
                                        </th>
                                        <th class="col-xs-1 headername text-uppercase text-light">BALANCE
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                            <div class="form-group has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields" style="padding-left: 30px;" placeholder="SEARCH">
                                            </div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="searchData">

                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="checkbox_c" name="selected[]" id="" value="">
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="checkbox_c" name="selected[]" id="" value="">
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="checkbox_c" name="selected[]" id="" value="">
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="checkbox_c" name="selected[]" id="" value="">
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                        <td class="text-left">
                                            <span>Column Info</span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="successModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:none;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success text-center">
                    <p id="success_msg"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="warningModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:none;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning text-center">
                    <p id="warning_msg"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:none;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger text-center">
                    <p id="error_msg"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection