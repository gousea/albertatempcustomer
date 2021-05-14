@extends('layouts.layout')

@section('title')
    Customer Create
@stop
<link rel="stylesheet" href="{{ asset('asset/css/customer.css') }}">

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase">Customer Create</span>
            </div>
            {{-- <div class="nav-submenu">
                <button type="submit" id="saveCustomer" class="btn btn-gray headerblack  buttons_menu"><i
                        class="fa fa-save" id="myButton"></i>&nbsp;&nbsp;Save</button>
                <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase"
                    href="{{ route('customers') }}"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel
                </a>
            </div> --}}

            <div class="clearfix"></div>
        </div> <!-- navbar-collapse.// -->
    </div>
</nav>

@section('main-content')
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li><a href="">Customer</a></li>

                </ul>
            </div>
        </div>
        <div class="container section-content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="panel panel-default">
                {{-- <div class="panel-heading head_title">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> Add New Customer</h3>

                </div> --}}
                <div class="panel-body">



                    <form action="{{ route('customers.store') }}" method="post" enctype="multipart/form-data"
                        id="customerForm" class="form-horizontal">
                        @csrf
                        @method('post')
                        {{-- <div class="row" style="padding-bottom: 9px;float: right;">
                            <div class="col-md-12">
                                <div class="">
                                    <input type="submit" id="saveCustomer" class="btn btn-primary" value="Save"><i
                                        class="fa fa-save"></i>&nbsp;&nbsp;
                                    <a href="{{ route('customers') }}" data-toggle="tooltip"
                                        class="btn btn-default cancel_btn_rotate"><i
                                            class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
                                </div>
                            </div>
                        </div> --}}

                        <div class="nav-submenu">
                            <button type="submit" id="saveCustomer" class="btn btn-gray headerblack  buttons_menu"><i
                                    class="fa fa-save" id="myButton"></i>&nbsp;&nbsp;Save</button>
                            <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase"
                                href="{{ route('customers') }}"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel
                            </a>
                        </div>




                        <input type="hidden" name="estatus" value="Active">
                        {{-- <input type="submit" style="display:none" /> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-customer">Customer</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vcustomername" maxlength="50"
                                            value="{{ old('vcustomername') }}" placeholder="" id="vcustomername"
                                            class="form-control" />
                                        <span id="vcustomernameerror" style="color: red"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-account-number">Account Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vaccountnumber" maxlength="50" value="" placeholder=""
                                            id="input-account-number" class="form-control" readonly />

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-first-name">First Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vfname" maxlength="25" value="{{ old('vfname') }}"
                                            placeholder="" id="input-first-name" class="form-control"
                                            onkeypress="return (event.charCode > 64 &&
                                                                                                                                                                                                                                                            event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"
                                            required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-last-name">Last Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vlname" maxlength="25" value="{{ old('vlname') }}"
                                            placeholder="" id="input-last-name" class="form-control"
                                            onkeypress="return (event.charCode > 64 &&
                                                                                                                                                                                                                                                            event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-address">Address</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vaddress1" maxlength="100" value="{{ old('vaddress1') }}"
                                            placeholder="" id="input-address" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-city">City</label>
                                    <div class="col-sm-8">

                                        <!--<input type="text" name="vcity" maxlength="20" value="{{ old('vcity') }}" placeholder="" id="input-city" class="form-control"  onkeypress="return (event.charCode > 64 &&-->
                                        <!--                    event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"  />-->

                                        <input type="text" name="vcity" maxlength="40" value="{{ old('vcity') }}"
                                            placeholder="" id="input-city" class="form-control" onkeypress="" />

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-state">State</label>
                                    <div class="col-sm-8">
                                        <!--<input type="text" name="vstate" maxlength="20" value="{{ old('vstate') }}" placeholder="" id="input-state" class="form-control"  onkeypress="return (event.charCode > 64 &&-->
                                        <!--                    event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"  />-->

                                        <input type="text" name="vstate" maxlength="40" value="{{ old('vstate') }}"
                                            placeholder="" id="input-state" class="form-control" onkeypress="" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Zip</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vzip" maxlength="10" value="{{ old('vzip') }}"
                                            placeholder="" id="input-zip" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-country">Country</label>
                                    <div class="col-sm-8">
                                        <input type="text" maxlength="20" name="vcountry" value="USA" class="form-control"
                                            readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-phone">Phone</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vphone" maxlength="20"
                                            onkeyup="this.value=this.value.replace(/[^\d]/,'')"
                                            value="{{ old('vphone') }}" placeholder="" id="vphone"
                                            class="form-control" />
                                        <span id="vphoneerror" style="color: red"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-email">Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" name="vemail" maxlength="100" value="{{ old('vemail') }}"
                                            placeholder="" id="email_field" class="form-control" />
                                        <span id="email-error" style="color: red"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-email">Price Level</label>
                                    <div class="col-sm-8">
                                        <select name="pricelevel" class="form-control">
                                            <option value="0" {{ old('pricelevel') == '0' ? 'selected' : '' }}>0</option>
                                            <option value="2" {{ old('pricelevel') == '2' ? 'selected' : '' }}>Level 2
                                            </option>
                                            <option value="3" {{ old('pricelevel') == '3' ? 'selected' : '' }}>Level 3
                                            </option>
                                            <option value="4" {{ old('pricelevel') == '4' ? 'selected' : '' }}>Level 4
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-state">Debit Limit</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="debitlimit" value="{{ old('debitlimit') }}"
                                            placeholder="" id="input-debitlimit" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Credit Day</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="creditday" maxlength="11" value="{{ old('creditday') }}"
                                            placeholder="" id="input-creditday" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Status</label>
                                    <div class="col-sm-8">
                                        <select name="estatus" class="form-control">
                                            <option value="Active" {{ old('estatus') == 'Active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="Deactive"
                                                {{ old('estatus') == 'Deactive' ? 'selected' : '' }}>
                                                Deactive</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Note</label>
                                    <div class="col-sm-8">
                                        <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-taxable">&nbsp;</label>
                                    <div class="col-sm-10">
                                        <input type='radio' name='vtaxable' value='Yes' checked="checked">&nbsp;&nbsp;
                                        Taxable &nbsp;&nbsp;
                                        <input type='radio' name='vtaxable' value='No'>&nbsp;&nbsp;Non Taxable
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection



@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#myButton").click(function() {
                $("#myForm").submit();
            });
        });

    </script>
    <script type="text/javascript">
        $(document).on('change', 'input[name="vcustomername"]', function(event) {
            event.preventDefault();
            var name = $(this).val().toUpperCase();
            var new_name = name.substring(0, 3);
            var number = Math.floor(Math.random() * 90000) + 10000;

            var ac_number = new_name + '' + number;

            $('input[name="vaccountnumber"]').val(ac_number);
        });

    </script>
    <script>
        $(document).ready(function() {
            $("#email_field").change(function() {
                var email = $("#email_field").val();
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (regex.test(email)) {
                    return true;
                } else {
                    $("#email-error").text("Invalid Email Address");
                    return false;
                }
            });
            var us_phone_regex = '1?\W*([2-9][0-8][0-9])\W*([2-9][0-9]{2})\W*([0-9]{4})(\se?x?t?(\d*))?';
            $('#saveCustomer').click(function() {
                alert('testing');
                var vcustomername = $('#vcustomername').val();
                var email = $("#email_field").val();
                var vphone = $('#vphone').val();
                if (vcustomername == '') {
                    $("#vcustomernameerror").text("Please enter Customer");
                    return false;
                }

                if (vphone == '') {
                    $("#vphoneerror").text("Please Enter Mobile Number");
                    return false;
                }
                if (IsPhone(vphone) == false) {
                    $("#vphoneerror").text("Invalid Phone number");
                    return false;
                }
                if (vcustomername != '' && vphone != '' && IsPhone(vphone) == true) {

                    $("#customerForm").submit();
                }
            });
        });

        function IsEmail(email) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email)) {
                return false;
            } else {
                return true;
            }
        }

        function IsPhone(vphone) {
            var regex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
            if (!regex.test(vphone)) {
                return false;
            } else {
                return true;
            }
        }

    </script>
@endsection
