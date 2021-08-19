@extends('layouts.layout')

@section('title')
    Customer Edit
@stop

@section('main-content')

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"><?php echo 'Customer Edit'; ?></span>
                </div>
                <div class="nav-submenu">
                    {{-- <button type="submit" id="saveCustomer" class="btn btn-gray headerblack  buttons_menu"><i
                        class="fa fa-save" id="myButton"></i>&nbsp;&nbsp;Save</button>
                <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase"
                    href="{{ route('customers') }}"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel
                </a> --}}
                    <button type="submit" form="form-customer" data-toggle="tooltip"
                        class="btn btn-gray headerblack buttons_menu"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                    <a href="{{ route('customers') }}" data-toggle="tooltip"
                        class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase"><i
                            class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>

    <div id="content" class="section-content">
        <div class="container">
            <div class="mytextdiv">
                <div class="mytexttitle font-weight-bold text-uppercase menu">
                    GENERAL INFO
                </div>
                <div class="divider font-weight-bold"></div>
            </div>
        </div>
        <br/>
        <form action="{{ route('customers.update', $customer->icustomerid) }}" method="post" enctype="multipart/form-data"
            id="form-customer" class="form-horizontal">
            @csrf
            @method('PATCH')
            <div class="container">
                <div class="row">
                    <div class="clearfix"></div>

                    <div class="col-md-12 mx-auto">
                        <input type="hidden" name="estatus" value="Active">
                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-customer">Customer</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vcustomername" maxlength="50"
                                        value="{{ $customer->vcustomername }}" placeholder="Customer" id="input-customer"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-first-name">First Name</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vfname" maxlength="25" value="{{ $customer->vfname }}"
                                        placeholder="First Name" id="input-first-name" class="form-control"
                                        onkeypress="return (event.charCode > 64 &&
                                                        event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-last-name">Last Name</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vlname" maxlength="25" value="{{ $customer->vlname }}"
                                        placeholder="last Name" id="input-last-name" class="form-control"
                                        onkeypress="return (event.charCode > 64 &&
                                                        event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-account-number">Account
                                        Number</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vaccountnumber" maxlength="50"
                                        value="{{ $customer->vaccountnumber }}" placeholder="account number"
                                        id="input-account-number" class="form-control" readonly />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-address">Address</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vaddress1" maxlength="100" value="{{ $customer->vaddress1 }}"
                                        placeholder="address" id="input-address" class="form-control" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-city">City</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vcity" maxlength="40" value="{{ $customer->vcity }}"
                                        placeholder="city" id="input-city" class="form-control" onkeypress="" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-state">State</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vstate" maxlength="40" value="{{ $customer->vstate }}"
                                        placeholder="state" id="input-state" class="form-control" onkeypress="" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-zip">Zip Code</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vzip" maxlength="10" value="{{ $customer->vzip }}"
                                        placeholder="zip code" id="input-zip" class="form-control" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-country">Country</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" maxlength="20" name="vcountry" value="USA" class="form-control"
                                        readonly />
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-email">Email</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="email" name="vemail" maxlength="100" value="{{ $customer->vemail }}"
                                        placeholder="email" id="input-email" class="form-control" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-phone">Phone</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="vphone" maxlength="20"
                                        onkeyup="this.value=this.value.replace(/[^\d]/,'')"
                                        value="{{ $customer->vphone }}" placeholder="phone" id="input-phone"
                                        class="form-control" />
                                </div>
                            </div>

                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-zip">Note</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <textarea name="note" class="form-control"
                                        placeholder="note">{{ $customer->note }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-email">Price Level</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
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
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-state">Debit Limit</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="debitlimit" value="{{ $customer->debitlimit }}"
                                        placeholder="debit limit" id="input-debitlimit" class="form-control" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-zip">Credit Day</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="creditday" maxlength="11" value="{{ $customer->creditday }}"
                                        placeholder="credit day" id="input-creditday" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                                    <label class="p-2 float-left text-uppercase" for="input-taxable">Taxable</label>
                                </div>
                                <div class="col-8 col-md-8 col-sm-8 col-lg-8">
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
                            <?php if ($version->ver_no == '3.1.0') { ?>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-zip">Account Pin</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="account_pin" value="{{ $customer->acct_pin }}"
                                        placeholder="account pin" id="account_pin" class="form-control"
                                        onkeypress="return isNumberKey(event);" />
                                </div>
                            </div>
                            <?php } ?>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-zip">Status</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <select name="estatus" class="form-control">
                                        <option value="Active" {{ $customer->estatus == 'Active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="Deactive"
                                            {{ $customer->estatus == 'Deactive' ? 'selected' : '' }}>Deactive
                                        </option>
                                    </select>
                                </div>
                                <span class="email_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($version->ver_no == '3.1.0') { ?>
            <div class="container">
                <div class="mytextdiv">
                    <div class="mytexttitle font-weight-bold text-uppercase menu">
                        GENERAL INFO
                    </div>
                    <div class="divider font-weight-bold"></div>
                </div>
            </div>
            <br/>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div class="form-group row">
                            <div class="col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-id_type">Id
                                        Type</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="id_type" value="{{ $customer->id_type }}"
                                        placeholder="id type" id="id_type" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-id_number">Id
                                        Number</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="id_number" maxlength="25" value="{{ $customer->id_number }}"
                                        placeholder="id number" id="id_number" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-state">Id Expire
                                        Date</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="expire_dt" value="{{ $customer->id_expire_dt }}"
                                        placeholder="id expire date" id="expire_dt" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label class="p-2 float-left text-uppercase" for="input-zip">Birth
                                        Date</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" name="birth_dt" value="{{ $customer->birth_dt }}"
                                        placeholder="birth date" id="birth_dt" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </form>
    </div>

@endsection

@section('page-script')
    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>

    {{-- old datepicker --}}
    {{-- <link type="text/css" href="{{ asset('javascript/bootstrap-datepicker.css') }}" rel="stylesheet" /> --}}

    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    {{-- <script src="{{ asset('javascript/bootstrap-datepicker.js')}}"></script> --}}

    {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}

    <script type="text/javascript">
        $(document).on('change', 'input[name="vcustomername"]', function(event) {
            event.preventDefault();
            var name = $(this).val().toUpperCase();
            var new_name = name.substring(0, 3);
            var number = Math.floor(Math.random() * 90000) + 10000;

            var ac_number = new_name + '' + number;

            $('input[name="vaccountnumber"]').val(ac_number);
        });

        $(function(){
            $("#expire_dt").datepicker({
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
            $("#birth_dt").datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                widgetPositioning:{
                    horizontal: 'auto',
                    vertical: 'bottom'
                }
            });
        });

        // $(function() {
        //     $('#expire_dt').datepicker({
        //         dateFormat: 'yy-mm-dd',
        //         todayHighlight: true,
        //         autoclose: true,
        //     });
        // });

        // $(function() {
        //     $('#birth_dt').datepicker({
        //         dateFormat: 'yy-mm-dd',
        //         todayHighlight: true,
        //         autoclose: true,
        //     });
        // });

        // $(function() {
        //     $('#expire_dt').datetimepicker({
        //         format: 'MM-DD-YYYY'
        //     });
        // });

        // $(function() {
        //     $('#birth_dt').datetimepicker({
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
