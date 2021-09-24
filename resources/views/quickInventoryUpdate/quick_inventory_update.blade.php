@extends('layouts.layout')
@section('title', 'Quick Inventory Update')
@section('main-content')


    <div id="content">

        <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
            <div class="container">
                <div class="collapse navbar-collapse" id="main_nav">
                    <div class="menu">
                        <span class="font-weight-bold text-uppercase"> Quick Inventory Update</span>
                    </div>
                    <div class="nav-submenu">
                        <a type="button" href="" data-toggle="tooltip" title="Cancel" class="btn btn-danger buttonred buttons_menu basic-button-small cancel_btn_rotate" id="cancel_button"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
                    </div>
                </div> <!-- navbar-collapse.// -->
            </div>
        </nav>
        <div id="upper" class="quickSection">
        <div class="container-fluid section-content">
            <div class="panel-body padding-left-right">

                <div class="mytextdiv">
                    <div class="mytexttitle font-weight-bold text-uppercase">
                        Item Selection
                    </div>
                    <div class="divider font-weight-bold"></div>
                </div>

            </div>
        </div>

        <div class="panel-body padding-left-right">
            <div class="col-md-5">
                <div class="py-3">
                    <div class="row form-inline">
                        <input type="text" name="" class="form-control adjustment-fields">&nbsp&nbsp&nbsp&nbsp
                        <button class="btn btn-primary button-blue buttons_menu basic-button-small">Search UPC</button>
                    </div>
                </div>
                <div class="py-3">
                    <div class="row form-inline">
                        <label class="form-control  adjustment-fields" style="bor"> CURRENT QTY ON HAND</label>
                        <input type="text" name="" class="form-control adjustment-fields" style="background-color: #1b1e21;" readonly>&nbsp&nbsp&nbsp&nbsp
<!--                        <button class="btn btn-primary button-blue buttons_menu basic-button-small">Search UPC</button>-->
                    </div>
                </div>
                <div class="py-3">
                    <div class="row form-inline">
                        <input type="text" name="" class="form-control adjustment-fields">&nbsp&nbsp&nbsp&nbsp
                        <button class="btn btn-primary button-blue buttons_menu basic-button-small">Search UPC</button>
                    </div>
                </div>
                <div class="py-3">
                    <div class="row form-inline">
                        <input type="text" name="" class="form-control adjustment-fields">&nbsp&nbsp&nbsp&nbsp
                        <button class="btn btn-primary button-blue buttons_menu basic-button-small">Search UPC</button>
                    </div>
                </div>
            </div>

            <div class="col-md-7">

            </div>
        </div>
        </div>
        <div id="lower" class="quickSection1">
            <div class="container-fluid section-content">
                <div class="panel-body padding-left-right">

                    <div class="mytextdiv">
                        <div class="mytexttitle font-weight-bold text-uppercase">
                            INVOICE DETAILS
                        </div>
                        <div class="divider font-weight-bold"></div>
                    </div>

                </div>
            </div>

            <div class="panel-body padding-left-right">
                <div class="col-md-5">
                    <div class="py-3">
                        <div class="row form-inline">
                            <input type="text" name="" class="form-control adjustment-fields">&nbsp&nbsp&nbsp&nbsp
                            <button class="btn btn-primary button-blue buttons_menu basic-button-small">Search UPC</button>
                        </div>
                    </div>
                    <div class="py-3">
                        <div class="row form-inline">
                            <input type="text" name="" class="form-control adjustment-fields">&nbsp&nbsp&nbsp&nbsp
                            <button class="btn btn-primary button-blue buttons_menu basic-button-small">Search UPC</button>
                        </div>
                    </div>
                    <div class="py-3">
                        <div class="row form-inline">
                            <input type="text" name="" class="form-control adjustment-fields">&nbsp&nbsp&nbsp&nbsp
                            <button class="btn btn-primary button-blue buttons_menu basic-button-small">Search UPC</button>
                        </div>
                    </div>
                    <div class="py-3">
                        <div class="row form-inline">
                            <input type="text" name="" class="form-control adjustment-fields">&nbsp&nbsp&nbsp&nbsp
                            <button class="btn btn-primary button-blue buttons_menu basic-button-small">Search UPC</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">

                </div>
            </div>
        </div>

    </div>

@endsection

@section('page-script')

    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">

    <style>
        .padding-left-right{
            padding: 0 5% 0 5%;
        }

        .edit_btn_rotate{
            line-height: 0.5;
            border-radius: 6px;
        }
    </style>
    <script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 0) {
                $('.quickSection').fadeOut();
            } else {
                $('.quickSection').fadeIn();
            }
        });

    </script>

@endsection
