@extends('layouts.layout')
@section('title', 'Quick Inventory Update')

@section('main-content')


    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <div id="content">

        <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
            <div class="container">
                <div class="collapse navbar-collapse" id="main_nav">
                    <div class="menu">
                        <span class="font-weight-bold text-uppercase"> Quick Inventory Update</span>
                    </div>
                    <div class="nav-submenu">
                        <a type="button" href="" data-toggle="tooltip" title="Cancel"
                           class="btn btn-danger buttonred buttons_menu basic-button-small cancel_btn_rotate"
                           id="cancel_button"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
                    </div>
                </div> <!-- navbar-collapse.// -->
            </div>
        </nav>
        <div class="container section-content">
        <div class="row row-m-t">
            <div class="col-lg-4 col-md-4 col-xs-4">
                <!-- <div id="upper" class="quickSection"> -->
            @if(session()->has('error_warning'))
                <div class="alert alert-danger"><i
                        class="fa fa-exclamation-circle"></i> {{ session()->get('error_warning') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            @if (session()->has('success'))
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ session()->get('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
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

            <!-- <div class="panel-body padding-left-right"> -->
                <div class="divTop">
                    <!-- divTop1 -->
                   
                        <div class="form-group row">
                            
                       
                                     <div class="col-sm-6 col-xs-6">
                                                 <input type="text" name="barcode" class="form-control" id="barcode"
                                       placeholder="ENTER SKU" style="font-size: 12px;">
                                
                                <h5 id="barSearch" style="display: none;color: #495057;font-size: font-size: 12px; !important;">
                                    searching..please wait</h5>
                                    </div>
                                    <div class="col-sm-2 col-sm-2">
                                        <button class="btn btn-primary button-blue buttons_menu basic-button-small"
                                        id="barcodeSearchBtn" style="font-size: 12px;">Search UPC
                                </button>
                                    </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                            <label for="qtyOnHandBtn" class="col-form-label" style="font-size: 12px;">CURRENT QTY ON
                                    HAND</label>
                                </div>
                                <div class="col-sm-2">
                            <button class="btn btn-primary button-blue buttons_menu basic-button-small"
                                        style=" background-color: rgb(71, 77, 83);" id="qtyOnHandBtn">0
                                </button>
                            </div>
                           
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="text" name="appendQty" id="appendQty"
                                       class="form-control" placeholder="ENTER NEW QTY*"
                                       pattern="[0-9]{6}" maxlength="6" style="font-size: 12px;">
                            </div>         
                            <div class="col-sm-2">
                                <button class="btn btn-primary button-blue buttons_menu basic-button-small"
                                        id="appendQtyBtn" style="font-size: 12px;">APPEND QTY
                                </button>
                            </div>
                          
                        </div>
                    
                </div>
                
           <!--  </div> -->
              <!--   </div> -->
            </div>

            <div class="col-lg-8 col-md-8 col-xs8">
                <div class="table-wrapper-scroll-y my-custom-scrollbar divTop2">
                        <form action="{{route('quickInventoryUpdatePost')}}" method="POST" name="itemUpdate"
                              id="itemUpdate">
                            @csrf
                            <input type="hidden" name="updatedDate" id="updatedDate" value="">
                            <input type="hidden" name="newInvoice" id="newInvoice" value="">
                            <input type="hidden" name="newVendor" id="newVendor" value="">
                            <table class="table table-bordered table-striped mb-0" id="itemTable">
                                <thead style="color: white !important; background-color: #286fb7">
                                <tr>
                                    <th scope="col">BARCODE/SKU</th>
                                    <th scope="col">NAME</th>
                                    <th scope="col">NPACK</th>
                                    <th scope="col">QTY ON HAND</th>
                                    <th scope="col">RECEIVED QTY</th>
                                    <th scope="col">UPDATED QTY</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <!--                    <tr>
                                                        <td>Jacob</td>
                                                        <td>Thornton</td>
                                                        <td>@fat</td>
                                                        <td>11</td>
                                                        <td><input type="text" id="receivedQTYTest" name="vbarcode[1]" value="10" readonly></td>
                                                        <td>21</td>
                                                        <td><button class="btnDelete">Delete</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Larry</td>
                                                        <td>the Bird</td>
                                                        <td>@twitter</td>
                                                        <td>11</td>
                                                        <td><input type="text" id="receivedQTYTest2" name="vbarcode[2]" value="11" readonly></td>
                                                        <td>21</td>
                                                        <td><button class="btnDelete">Delete</button></td>
                                                    </tr>-->

                                </tbody>
                            </table>
                        </form>
                    </div>
            </div>
        </div>
        <div class="row row-m-t">
            <div class="col-lg-12 col-md-12 col-xs-12">
            <!--  <div id="lower" class="quickSection1"> -->
            <div class="container-fluid section-content">
                <div class="panel-body ">

                    <div class="mytextdiv">
                        <div class="mytexttitle font-weight-bold text-uppercase">
                            INVOICE DETAILS
                        </div>
                        <div class="divider font-weight-bold"></div>
                    </div>

                </div>
            </div>

          <!--   <div class="panel-body padding-left-right"> -->
                <div class="divTop padding-left-right">
                     <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="start_dt" class="col-form-label">DATE</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="start_dt" maxlength="25" value="" placeholder="DATE" id="start_dt"
                                   class="form-control " style="width: 163px;font-size: 12px;" required="">
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="invoice" class="col-form-label">INVOICE</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="invoice" maxlength="25" value="" placeholder="ENTER INVOICE" id="invoice"
                                   class="form-control " style="width: 163px;font-size: 12px;" required="">
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="vendor" class="col-form-label">VENDOR</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="vendor" maxlength="25" value="" placeholder="ENTER VENDOR" id="vendor"
                                   class="form-control " style="width: 163px;font-size: 12px;" required="">
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                               <button class="btn btn-primary button-blue buttons_menu basic-button-small"
                                        id="bactToItemBtn" style="font-size: 12px;">BACK TO UPC SEARCH
                                </button>
                            </div>         
                            <div class="col-sm-4">
                                <button class="btn btn-primary button-blue buttons_menu basic-button-small"
                                        id="finalizeBtn" style="font-size: 12px;">FINALIZE
                                </button>
                            </div>
                          
                        </div>

                    <!-- <div class="mb-3 row">
                        <label for="start_dt" class="col-form-label">DATE</label>
                        <div class="col-sm-10">
                            <input type="text" name="start_dt" maxlength="25" value="" placeholder="DATE" id="start_dt"
                                   class="form-control " style="width: 163px;" required="">
                        </div>
                    </div> -->
                    <!-- <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">INVOICE #</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="invoice">
                        </div>
                    </div> -->
                    <!-- <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">VENDOR</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="vendor">
                        </div>
                    </div> -->

                   <!--  <div class="py-3">
                        <div class="row form-inline">
                            <button class="btn btn-primary button-blue buttons_menu basic-button-small"
                                    style="width: 44%;" id="bactToItemBtn">BACK TO ITEM SECTION
                            </button>
                            &nbsp&nbsp&nbsp&nbsp
                            <button class="btn btn-primary button-blue buttons_menu basic-button-small"
                                    id="finalizeBtn">FINALIZE
                            </button>
                        </div>
                    </div> -->

                </div>
                
           <!--  </div>    -->         
         <!--        </div> -->
            </div>
        </div>

        </div>
    </div>

@endsection

@section('page-script')
    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">

    <style>
        .padding-left-right {
            padding: 0 5% 0 5%;
        }

        .edit_btn_rotate {
            line-height: 0.5;
            border-radius: 6px;
        }

        .outputColor {
            color: antiquewhite;
            margin-right: 5% !important;
        }

        .my-custom-scrollbar {
            position: relative;
            height: 200px;
            overflow: auto;
        }

        .table-wrapper-scroll-y {
            display: block;
        }

        .divTop {
            width: 95%;
            /*height: 500px;*/
            /*background: aqua;*/
            height: auto;
            margin: auto;
            padding: 10px;
        }

        .divTop1 {
            width: 31%;
            height: 200px;
            /*  background: red;*/
            float: left;
        }

        .divTop2 {
            margin-left: auto;
            height: 200px;
            /*background: black;*/
            /*background: rgb(71, 77, 83);*/
            /*background: #286fb7;*/
        }

        .removeOutline {
            outline: none;
            border-width: 0px;
            border: none;
        }

        .removeOutline input:focus {
            outline: none;
        }

        .success {
            color: #1d643b;
        }
    </style>
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
    <!--    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>-->


    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>
    <script>
        $(function () {
            $("#start_dt").datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                widgetPositioning: {
                    horizontal: 'auto',
                    vertical: 'bottom'
                }
            });
        });

        /* $(window).scroll(function() {
             if ($(this).scrollTop() > 0) {
                 $('.quickSection').fadeOut();
             } else {
                 $('.quickSection').fadeIn();
             }
         });*/

        $('#bactToItemBtn').click(function () {
            // $('.quickSection').fadeIn();
            //alert('hey');
            $('#barcode').focus();
        });

        $(document).ready(function () {
            let AQBID;
            let IQOH; //iqtyonhand
            let SBID; //(search barcode item details) holds the search item details tobe listed in list after append click
            let FINAL = 0;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#barcode').focus();

            $('#appendQty').bind('keyup paste', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#barcodeSearchBtn').click(function () {
                $('.alert-danger').hide();
                $('.alert-info').hide();
                $('.alert-success').hide();
                var barcode = $('#barcode').val();
                barcode = $.trim(barcode);
                $('#barSearch').hide();
                if (barcode == "") {
                    $('#barSearch').text("Barcode should not be blank, please enter correct barcode/sku ");
                    $('#barSearch').show();
                    return false;
                } else if (barcode.length < 3) {
                    $('#barSearch').text("Barcode should not be less than 3 characters");
                    $('#barSearch').show();
                    return false;
                } else if (barcode.length > 20) {
                    $('#barSearch').text("Barcode should not more than 20 characters");
                    $('#barSearch').show();
                    return false;
                }
                //alert('SB btn clikced '+ barcode);
                // $('#itemTable').append('<tr><td>my data</td><td>more data</td><td>more data</td><td>more data</td><td>more data</td><td>more data</td></tr>');
                //quickSearchItem
                $('#barSearch').text("Searching item, please wait..");
                $('#barSearch').show();
                $.ajax({
                    url: "{{url('quickSearchItem')}}",
                    type: "POST",
                    data: {
                        barcode: barcode,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#barSearch').hide();
                        console.log(result);
                        //$('.state-dropdown').html('<option value="">Select State</option>');

                        if (result.items && result.items.length > 0) {
                            $.each(result.items, function (key, value) {
                                //$(".state-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                                //"iitemid","vbarcode","vitemname","vsuppliercode","iqtyonhand","npack"
                                $("#qtyOnHandBtn").text(value.iqtyonhand);
                                AQBID = value.iitemid;
                                IQOH = value.iqtyonhand;
                                //$(this).removeClass('follow').addClass('unfollow');
                                $("#appendQtyBtn").addClass(value.iitemid);
                                //   $("#itemTable").append('<tr><td>' + value.vbarcode + '</td><td>' + value.vitemname + '</td><td>' + value.npack + '</td><td>' + value.iqtyonhand + '</td><td><input type="text" id="receivedQTY_' + value.iitemid + '" name="vbarcode[' + value.iitemid + ']" value="" readonly></td><td id="updatedQTY_' + value.iitemid + '"></td><td><button class="btnDelete">Delete</button></td></tr>');
                                SBID = '<tr><td>' + value.vbarcode + '</td><td>' + value.vitemname + '</td><td>' + value.npack + '</td><td>' + value.iqtyonhand + '</td><td><input type="text" id="receivedQTY_' + value.iitemid + '" name="vbarcode[' + value.iitemid + ']" value="" readonly class="removeOutline"></td><td id="updatedQTY_' + value.iitemid + '"></td><td><button class="btnDelete">Delete</button></td></tr>';
                                $('#barSearch').text("Item Name: " + value.vitemname);
                                //$('#barSearch').addClass('success');
                                $('#barSearch').show();
                            });

                        } else {
                            //alert('no records');
                            $('#barSearch').text("No Record Found. Try another barcode.");
                            $('#barSearch').show();
                        }
                    },
                    error: function (request, status, error) {
                        $("#barSearch").hide();
                        console.log('api resp error');
                        console.log(request.error);
                        alert('Please try after some time unable to connect.');
                        return false;

                    }
                });
            });

            $('#appendQtyBtn').click(function () {
                console.log('appendQtyBtn clicked');
                if (SBID == '') {
                    $('#qtyOnHandBtn').text(0);
                    $('#barcode').val('');
                    $('#appendQty').val('');
                    $('#barSearch').hide();
                    alert('Please search new item to update');
                    return false;
                }
                var newQty = $('#appendQty').val();
                if (newQty == 0 || newQty.length < 1) {
                    alert('Please enter some quantity to update');
                    return false;
                }
                //$('#receivedQTY_'+AQBID).text(newQty);
                $("#itemTable").append(SBID);
                $('#receivedQTY_' + AQBID).val(newQty);
                $('#updatedQTY_' + AQBID).text((+newQty) + (+IQOH));
                FINAL = 1; //check for any item update list added

                $('#qtyOnHandBtn').text(0);
                $('#barcode').val('');
                $('#appendQty').val('');
                $('#barSearch').hide();
                SBID = ''; // to unset the searched details

            });
            $('#nextItem').click(function () {
                console.log('nextItem clicked');
                $('#barcode').val('');
                $('#qtyOnHandBtn').text(0);

            });
            $('#doneBtn').click(function () {
                console.log('doneBtn clicked');
                alert('Saving the data');
                $('#start_dt').focus()
                $('#start_dt').select()
            });

            $("#itemTable").on('click', '.btnDelete', function () {
                $(this).closest('tr').remove();
            });

            $('#finalizeBtn').click(function () {
                console.log('finalizeBtn clicked');
                if (FINAL == 0) {
                    alert('Please add any item to update');
                    return false;
                }
                var updatedDate = $("#start_dt").datepicker({dateFormat: 'yy-mm-dd'}).val();
                var newVendor = $('#vendor').val();
                var newInvoice = $('#invoice').val();
                // alert('date=>'+updatedDate+' newVendor=>'+newVendor+' newInvoice'+newInvoice);
                console.log('updatedDate =>' + updatedDate + ' newvendor=> ' + newVendor + ' invoice=> ' + newInvoice);
                $('#updatedDate').val(updatedDate);
                $('#newVendor').val(newVendor);
                $('#newInvoice').val(newInvoice);

                //return false;
                $('form#itemUpdate').submit();
            })

            $('#appendQty').unbind('change input paste').bind('change input paste', function (e) {
                var $this = $(this);
                var val = $this.val();
                var valLength = val.length;
                var maxCount = $this.attr('maxlength');
                if (valLength > maxCount) {
                    $this.val($this.val().substring(0, maxCount));
                }
            });
            /*End document ready*/
        });

    </script>

@endsection
