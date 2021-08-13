@extends('layouts.layout')
@section('title')
    Store
@endsection

<style>
    .form-control {
        font-size: 15px !important;
    }

    input::-webkit-input-placeholder {
        font-size: 15px !important;
        line-height: 3;
        text-transform: uppercase;
    }

</style>


@section('main-content')

    <form action="{{ route('store.update') }}" method="POST" enctype="multipart/form-data" id="form-store"
        class="form-horizontal">
        @csrf
        @method('put')
        <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
            <div class="container">
                <div class="collapse navbar-collapse" id="main_nav">
                    <div class="menu">
                        <span class="font-weight-bold"> UPDATE STORE</span>
                    </div>
                    <div class="nav-submenu">
                        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php //echo $button_save;
?>"
                            class="btn btn-gray headerblack  buttons_menu"><i
                                class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                    </div>
                </div> <!-- navbar-collapse.// -->
            </div>
        </nav>

        <div class="container section-content">

            @if (session()->has('message'))
                <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{ session()->get('message') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif
            <div class="panel panel-default" style="">
                <div class="panel-heading head_title">
                    {{-- <h3 class="panel-title"><i class="fa fa-pencil"></i> Store Information </h3> --}}
                    <div class="top_button">
                        <!--input type="submit" value="Save" class="btn btn-primary"-->
                        {{-- <button type="submit" form="form-store" data-toggle="tooltip" title="<?php //echo $button_save;
?>" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button> --}}
                    </div>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="estatus" value="Active">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">SID</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vcompanycode" maxlength="20"
                                        value="{{ $storeData['vcompanycode'] }}" placeholder="SID" id="input-vcompanycode"
                                        class="form-control" readonly required />
                                    <input type="hidden" name="istoreid" value="{{ $storeData['istoreid'] }}"
                                        id="input-istoreid" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Store Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vstorename" maxlength="40"
                                        value="{{ $storeData['vstorename'] }}" placeholder="Store Name"
                                        id="input-vstorename" class="form-control" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Store Abbr</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vstoreabbr" maxlength="50"
                                        value="{{ $storeData['vstoreabbr'] }}" placeholder="Store Abbr"
                                        id="input-vstoreabbr" class="form-control" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Address</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vaddress1" maxlength="100"
                                        value="{{ $storeData['vaddress1'] }}" placeholder="Address" id="input-vaddress1"
                                        class="form-control" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Description</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vstoredesc" maxlength="100"
                                        value="{{ $storeData['vstoredesc'] }}" placeholder="Description"
                                        id="input-vstoredesc" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">City</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vcity" maxlength="20" value="{{ $storeData['vcity'] }}"
                                        placeholder="City" id="input-vcity" class="form-control" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">State</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vstate" maxlength="20" value="{{ $storeData['vstate'] }}"
                                        placeholder="State" id="input-vstate" class="form-control" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Zip</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vzip" maxlength="10" value="{{ $storeData['vzip'] }}"
                                        placeholder="Zip" id="input-vzip" class="form-control" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Country</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vcountry" maxlength="20"
                                        value="{{ $storeData['vcountry'] }}" placeholder="Country" id="input-vcountry"
                                        class="form-control" readonly />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Phone 1</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vphone1" maxlength="20" value="{{ $storeData['vphone1'] }}"
                                        placeholder="Phone 1" id="input-vphone1" class="form-control" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Phone 2</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vphone2" maxlength="20" value="{{ $storeData['vphone2'] }}"
                                        placeholder="Phone 2" id="input-vphone2" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Fax</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vfax1" maxlength="20" value="{{ $storeData['vfax1'] }}"
                                        placeholder="Fax" id="input-vfax1" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Email</label>
                                <div class="col-sm-8">
                                    <input type="email" name="vemail" maxlength="30" value="{{ $storeData['vemail'] }}"
                                        placeholder="Email" id="input-vemail" class="form-control"
                                        pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Website</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vwebsite" maxlength="100"
                                        value="{{ $storeData['vwebsite'] }}" placeholder="Website" id="input-vwebsite"
                                        class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Contact
                                    Person</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vcontactperson" maxlength="25"
                                        value="{{ $storeData['vcontactperson'] }}" placeholder="Contact Person"
                                        id="input-vcontactperson" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Sequence</label>
                                <div class="col-sm-8">
                                    <input type="text" name="isequence" value="{{ $storeData['isequence'] }}"
                                        placeholder="Sequence" id="input-isequence" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Message 1</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vmessage1" maxlength="500"
                                        value="{{ $storeData['vmessage1'] }}" placeholder="Message 1"
                                        id="input-vmessage1" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label text-uppercase" style="font-size:15px"
                                    for="input-customer">Message 2</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vmessage2" maxlength="500"
                                        value="{{ $storeData['vmessage2'] }}" placeholder="Message 2"
                                        id="input-vmessage2" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function($) {

            $("div#divLoading").addClass('show');


            var token = "1";
            var sid = "2";

            $("div#divLoading").removeClass('show');
        });

        $(document).on('keypress keyup blur', 'input[name="vzip"],input[name="isequence"]', function(event) {

            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }

        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.js"></script>


    <script type="text/javascript">
        $().ready(function() {
            $("#form-store").validate({
                rules: {
                    vstorename: {
                        required: true,
                        minlength: 2,
                    },
                    vstoreabbr: {
                        required: true,
                    },
                    vaddress1: {
                        minlength: 3,
                        required: true,
                    },
                    vcity: {
                        required: true,
                        minlength: 2,
                        letterswithbasicpunc: true,
                        //   lettersonly: true,
                    },
                    vstate: {
                        lettersonly: true,
                        minlength: 2,
                        required: true,
                    },
                    vzip: {
                        minlength: 5,
                        maxlength: 6,
                        required: true,
                    },
                    vphone1: {
                        required: true,
                    },
                    vfax1: {
                        minlength: 5,
                        maxlength: 6,
                        number: true
                    },
                    vemail: {
                        email: true,
                    },
                    vwebsite: {
                        url: true,
                    },
                    vcontactperson: {
                        //   lettersonly: true,
                        letterswithbasicpunc: true,
                    },
                    vmessage1: {
                        minlength: 3,
                        maxlength: 25,
                    },
                    vmessage2: {
                        minlength: 3,
                        maxlength: 25,
                    },

                }
            });
        });
        $(document).ready(function($) {

            $("input[name='vphone1'],input[name='vphone2']").mask("999-999-9999");
        });
    </script>
    <style>
        .error {
            color: red;
        }

    </style>
@endsection
