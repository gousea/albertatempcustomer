@extends('layouts.layout')

@section('title')
    Customer Edit
@stop

<link rel="stylesheet" href="{{ asset('asset/css/customer.css') }}">

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase">Customer Edit</span>
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
                    <li><a href="">Create Customer</a></li>

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
                <div class="panel-body">
                    <div class="clearfix"></div>

                    <form action="{{ route('customers.update', $customer->icustomerid) }}" method="post"
                        enctype="multipart/form-data" id="form-customer" class="form-horizontal">
                        @csrf
                        @method('PATCH')
                        <div class="nav-submenu">
                            <button type="submit" id="saveCustomer" class="btn btn-gray headerblack  buttons_menu"><i
                                    class="fa fa-save" id="myButton"></i>&nbsp;&nbsp;Save</button>
                            <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase"
                                href="{{ route('customers') }}"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel
                            </a>
                        </div>
                        <input type="hidden" name="estatus" value="Active">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-customer">Customer</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vcustomername" maxlength="50"
                                            value="{{ $customer->vcustomername }}" placeholder="" id="input-customer"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-account-number">Account Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vaccountnumber" maxlength="50"
                                            value="{{ $customer->vaccountnumber }}" placeholder=""
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
                                        <input type="text" name="vfname" maxlength="25" value="{{ $customer->vfname }}"
                                            placeholder="" id="input-first-name" class="form-control" onkeypress="return (event.charCode > 64 &&
                                            event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-last-name">Last Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vlname" maxlength="25" value="{{ $customer->vlname }}"
                                            placeholder="" id="input-last-name" class="form-control" onkeypress="return (event.charCode > 64 &&
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
                                        <input type="text" name="vaddress1" maxlength="100"
                                            value="{{ $customer->vaddress1 }}" placeholder="" id="input-address"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-city">City</label>
                                    <div class="col-sm-8">
                                        <!--<input type="text" name="vcity" maxlength="20" value="{{ $customer->vcity }}" placeholder="" id="input-city" class="form-control" onkeypress="return (event.charCode > 64 &&-->
                                        <!--                    event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />-->

                                        <input type="text" name="vcity" maxlength="40" value="{{ $customer->vcity }}"
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
                                        <!--<input type="text" name="vstate" maxlength="20" value="{{ $customer->vstate }}" placeholder="" id="input-state" class="form-control" onkeypress="return (event.charCode > 64 &&-->
                                        <!--                    event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />-->

                                        <input type="text" name="vstate" maxlength="40" value="{{ $customer->vstate }}"
                                            placeholder="" id="input-state" class="form-control" onkeypress="" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Zip</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vzip" maxlength="10" value="{{ $customer->vzip }}"
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
                                            value="{{ $customer->vphone }}" placeholder="" id="input-phone"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-email">Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" name="vemail" maxlength="100" value="{{ $customer->vemail }}"
                                            placeholder="" id="input-email" class="form-control" />


                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-email">Price Level</label>
                                    <div class="col-sm-8">
                                        <select name="pricelevel" class="form-control">
                                            <option value="0" {{ $customer->pricelevel == '0' ? 'selected' : '' }}>0
                                            </option>
                                            <option value="2" {{ $customer->pricelevel == '2' ? 'selected' : '' }}>Level
                                                2</option>
                                            <option value="3" {{ $customer->pricelevel == '3' ? 'selected' : '' }}>Level
                                                3</option>
                                            <option value="4" {{ $customer->pricelevel == '4' ? 'selected' : '' }}>Level
                                                4</option>
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
                                        <input type="text" name="debitlimit" value="{{ $customer->debitlimit }}"
                                            placeholder="" id="input-debitlimit" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Credit Day</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="creditday" maxlength="11"
                                            value="{{ $customer->creditday }}" placeholder="" id="input-creditday"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($version->ver_no == '3.1.0') { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-id_type">Id Type</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="id_type" value="{{ $customer->id_type }}" placeholder=""
                                            id="id_type" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-id_number">Id Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="id_number" maxlength="25"
                                            value="{{ $customer->id_number }}" placeholder="" id="id_number"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-state">Id Expire Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="expire_dt" value="{{ $customer->id_expire_dt }}"
                                            placeholder="" id="expire_dt" class="datetimepicker form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Birth Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="birth_dt"
                                            value="{{ $customer->birth_dt }}"
                                            placeholder="" id="birth_dt" class="datetimepicker form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Status</label>
                                    <div class="col-sm-8">
                                        <select name="estatus" class="form-control">
                                            <option value="Active"
                                                {{ $customer->estatus == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Deactive"
                                                {{ $customer->estatus == 'Deactive' ? 'selected' : '' }}>Deactive
                                            </option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Note</label>
                                    <div class="col-sm-8">
                                        <textarea name="note" class="form-control">{{ $customer->note }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($version->ver_no == '3.1.0') { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Account Pin</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="account_pin" value="{{ $customer->acct_pin }}"
                                            placeholder="" id="account_pin" class="form-control"
                                            onkeypress="return isNumberKey(event);" />
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-taxable">&nbsp;</label>
                                    <div class="col-sm-10">
                                        @if ($customer->vtaxable == 'Yes')
                                            <input type='radio' name='vtaxable' value='Yes' checked="checked">&nbsp;&nbsp;
                                            Taxable &nbsp;&nbsp;
                                            <input type='radio' name='vtaxable' value='No'>&nbsp;&nbsp;Non Taxable
                                        @else
                                            <input type='radio' name='vtaxable' value='Yes'>&nbsp;&nbsp; Taxable
                                            &nbsp;&nbsp;
                                            <input type='radio' name='vtaxable' value='No' checked="checked">&nbsp;&nbsp;Non
                                            Taxable
                                        @endif
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

@section('page-script')
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js" type="text/javascript" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>
    <link type="text/css" href="{{ asset('javascript/bootstrap-datepicker.css') }}" rel="stylesheet" />

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    {{-- <script src="{{ asset('javascript/bootstrap-datepicker.js')}}"></script> --}}

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script type="text/javascript">
        $(document).on('change', 'input[name="vcustomername"]', function(event) {
            event.preventDefault();
            var name = $(this).val().toUpperCase();
            var new_name = name.substring(0, 3);
            var number = Math.floor(Math.random() * 90000) + 10000;

            var ac_number = new_name + '' + number;

            $('input[name="vaccountnumber"]').val(ac_number);
        });

        $(function() {
            $('#expire_dt').datepicker({
                dateFormat: 'yy-mm-dd',
                todayHighlight: true,
                autoclose: true,
            });
        });

        $(function() {
            $('#birth_dt').datepicker({
                dateFormat: 'yy-mm-dd',
                todayHighlight: true,
                autoclose: true,
            });
        });

        // $(function () {
        //     $('#expire_dt').datepicker({
        //         format: 'M-D-Y'
        //     });
        // });

        // $(function () {
        //     $('#birth_dt').datepicker({
        //         format: 'MM-DD-YYYY'
        //     });
        // });

    </script>
    <script>
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            return !(charCode > 31 && (charCode < 48 || charCode > 57));
        }

    </script>
@endsection
