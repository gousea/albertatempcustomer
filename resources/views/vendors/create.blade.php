@extends('layouts.layout')

@section('title')
    Vendor Create
@stop

<link rel="stylesheet" href="{{ asset('asset/css/vendor.css') }}">

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase">Vendor Create</span>
            </div>
            {{-- <div class="nav-submenu"> --}}
                {{-- <button type="button" id="form-vendor" data-toggle="tooltip" title="" class="btn btn-gray headerblack  buttons_menu"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                <a href="{{ route('vendors') }}" data-toggle="tooltip" title="" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a> --}}

                {{-- <button type="submit" id="form-vendor" title="" class="btn btn-gray headerblack  buttons_menu"><i
                        class="fa fa-save"></i>&nbsp;&nbsp;Save</button> --}}
                {{-- <input type="submit" name="submit" id="form-vendor" title="" class="btn btn-gray headerblack  buttons_menu" value="Save"><i class="fa fa-save"></i> --}}
                {{-- <a href="{{ route('vendors') }}" data-toggle="tooltip" title=""
                    class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase"><i
                        class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a> --}}
            {{-- </div> --}}
        </div> <!-- navbar-collapse.// -->
    </div>
</nav>

@section('main-content')
<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <ul class="breadcrumb">
          <li><a href="">Vendor Create</a></li>
        </ul>
      </div>
    </div>
    <div class="container section-content">
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
          </ul>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

      <div class="panel panel-default">
        <div class="panel-body">
          <div class="clearfix"></div>

          <form action="{{ route('vendors.store') }}" method="post" enctype="multipart/form-data" id="vendorForm" class="form-horizontal">
            @csrf
            <div class="nav-submenu">
                <button type="submit" id="saveCustomer" class="btn btn-gray headerblack  buttons_menu"><i
                        class="fa fa-save" id="myButton"></i>&nbsp;&nbsp;Save</button>
                <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase"
                    href="{{ route('vendors') }}"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel
                </a>
            </div>
            <input type="hidden" name="estatus" value="Active">
            @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
            @endif
            <div class="row">
              <div class="col-md-6">
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-vendor-name">Vendor Name</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcompanyname" maxlength="50" value="{{ old('vcompanyname') }}" placeholder="vendor Name" id="input_vendor_name" class="form-control" required />
                    <span id="vcompanyname_error" style="color:red;"></span>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-vendor-type">Vendor Type</label>
                  <div class="col-sm-8">
                    <select name="vvendortype" id="input-vendor-type" class="form-control" >

                      <option value="vendor" selected="selected">Vendor</option>
                      <option value="other" >Other</option>

                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-first-name">First Name</label>
                    <div class="col-sm-8">
                      <input type="text" name="vfnmae" maxlength="25" value="{{ old('vfnmae') }}" placeholder="" id="input-first-name" class="form-control" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-last-name">Last Name</label>
                    <div class="col-sm-8">
                      <input type="text" name="vlname" maxlength="25" value="{{ old('vlname') }}" placeholder="" id="input-last-name" class="form-control" />
                    </div>
                  </div>
                </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-vendor-code">Vendor Code</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcode" maxlength="20" value="{{ old('vcode') }}" placeholder="" id="input-last-name" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-address">Address</label>
                  <div class="col-sm-8">
                    <input type="text" name="vaddress1" maxlength="100" value="{{ old('vaddress1') }}" placeholder="" id="input-address" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-city">City</label>
                    <div class="col-sm-8">
                     <input type="text" name="vcity" maxlength="50" value="{{ old('vcity') }}" placeholder="" id="input-city" class="form-control" onkeypress="cityCheck()" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-state">State</label>
                    <div class="col-sm-8">
                      <!--<input type="text" name="vstate" maxlength="20" value="{{ old('vstate') }}" placeholder="" id="input-state" class="form-control" onkeypress="return (event.charCode > 64 &&-->
                      <!--                    event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />-->

                    <input type="text" name="vstate" maxlength="50" value="{{ old('vstate') }}" placeholder="" id="input-state" class="form-control" onkeypress="" />
                    </div>
                  </div>
                </div>
              </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-phone">Phone</label>
                  <div class="col-sm-8">
                    <input type="text" name="vphone" maxlength="20"  onkeyup="this.value=this.value.replace(/[^\d]/,'')" value="{{ old('vphone') }}" placeholder="" id="input-phone" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-zip">Zip</label>
                  <div class="col-sm-8">
                    <input type="text" name="vzip" maxlength="10" value="{{ old('vzip') }}" placeholder="" id="input-zip" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-country">Country</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcountry" maxlength="20" value="USA" class="form-control" readonly />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-email">Email</label>
                  <div class="col-sm-8">
                    <input type="email"  id="vemail" name="vemail"  maxlength="100" value="{{ old('vemail') }}" placeholder=""  class="form-control" />
                  </div>
                </div>
                <span class="email_error"></span>
              </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="input-country">PLCB Type</label>
                      <div class="col-sm-8">
                        <select name="plcbtype" class="form-control">
                            <option value="None">None</option>
                            <option value="Schedule A">Schedule A</option>
                            <option value="Schedule B">Schedule B</option>
                            <option value="Schedule C">Schedule C</option>

                        </select>
                      </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="input-country">EDI</label>
                      <div class="col-sm-8">
                        <select name="edi" class="form-control" id="EDISelector">
                              <option value="0" >No</option>
                              <option value="1"  >Yes</option>
                        </select>
                      </div>
                    </div>
                </div>
            </div>
                 <!-- NEW EDI start Hanamant  16-12-2020-------->
                <div id="EDIID" style="background-color:#80808047;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="font-size:15px;">
                              <label class="col-sm-4 control-label" for="input-country">EDI Settings</label>
                              <div class="col-sm-4">
                                 <!-- <br><br><span><b> UPCA TO UPCE</b></span>
                                  <br>
                                 <br>
                                 <input type="checkbox" id="upc_convert" name="upc_convert" value="Y" >
                                 <label for="convert"> convert</label> -->

                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                       <div class="col-sm-2"></div>
                       <div class="col-sm-5">
                            <label style="font-size:15px; font-weight:normal;">Format</label>
                        </div>
                    </div>

                    <div class="row" style="font-size:12px;">
                        <div class="col-sm-2"></div>

                        <div class="col-sm-2">
                            <input type="radio" name="vendor_format" value="FEDWAY" style="vertical-align:middle;">
                            <label style="font-weight:normal;">FEDWAY</label>
                        </div>
                        <div class="col-sm-2">
                            <input type="radio" name="vendor_format" value="ALLEN BROTHERS" style="vertical-align:middle;">
                            <label for="convert" style="font-weight:normal;"> ALLEN BROTHERS</label>
                        </div>
                        <div class="col-sm-2">
                            <input type="radio" name="vendor_format" value="CORE MARK" style="vertical-align:middle;">
                            <label style="font-weight:normal;">CORE MARK</label>
                        </div>

                        <div class="col-sm-2">
                            <input type="radio" name="vendor_format" value="RESNICK" style="vertical-align:middle;">
                            <label for="convert" style="font-weight:normal;"> RESNICK</label>
                        </div>

                        <div class="col-sm-2">
                            <input type="radio" name="vendor_format" value="OTHERS" style="vertical-align:middle;" checked>
                            <label for="convert" style="font-weight:normal;"> OTHERS</label>
                        </div>

                    </div>
                    <br>

                    <div class="row">
                       <div class="col-sm-2"></div>
                       <div class="col-sm-5">
                            <label style="font-size:15px; font-weight:normal;">Conversion</label>
                        </div>
                    </div>
                    <div class="row" style="font-size:12px;">
                       <div class="col-sm-2"></div>

                       <div class="col-sm-2">
                            <input type="radio" id="upc_convert_E" name="upc_convert" value="E" style="vertical-align:middle;">
                            <label style="font-weight:normal;">UPCA -UPCE</label>
                        </div>
                        <div class="col-sm-2">
                            <input type="radio" id="upc_convert_A" name="upc_convert" value="A" style="vertical-align:middle;">
                            <label style="font-weight:normal;">UPCE -UPCA</label>
                        </div>
                        <div class="col-sm-2">
                            <input type="radio" id="upc_convert_None" name="upc_convert" value="0" style="vertical-align:middle;" checked>
                            <label for="convert" style="font-weight:normal;"> None</label>
                        </div>

                    </div>
                    <br>

                    <div class="row">
                       <div class="col-sm-2"></div>
                       <div class="col-sm-5">
                            <label style="font-size:15px; font-weight:normal;">Addl. Settings</label>
                        </div>
                    </div>
                    <div class="row" style="font-size:12px;">
                        <div class="col-sm-2"></div>

                        <div class="col-sm-2">
                             <input type="checkbox" id="upca_1" class="upca_1" name="remove_first_digit" value="Y" style="vertical-align:middle;">
                             <label for="convert" class="upca_1" style="font-weight:normal;"> Remove 1st Digit</label>
                        </div>
                        <div class="col-sm-2">
                             <input type="checkbox" id="upca_2" class="upca_2" name="remove_last_digit" value="Y" style="vertical-align:middle;">
                             <label for="convert" class="upca_2" style="font-weight:normal;"> Remove Last Digit</label>
                        </div>
                        <div class="col-sm-2">
                             <input type="checkbox" id="upca_3" class="upca_3" name="check_digit" value="Y" style="vertical-align:middle;">
                             <label for="convert" class="upca_3" style="font-weight:normal;"> With Check Digit</label>
                        </div>

                    </div>

                </div>


          </form>
        </div>
      </div>
    </div>

</div>

<?php if(session()->get('hq_sid') == 1){ ?>
<!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to add the Vendor:</h4>
            <span style="color: #03A9F4">(Please Note: If a vendor already exists in any of the stores those vendors will be updated)</span>
          </div>

          <div class="modal-body">
             <table class="table table-bordered">
                <thead id="table_green_header_tag">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="selectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check custom-control-input stores" id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">
                                </div>
                            </td>
                            <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="submit" id="save_btn" class="btn btn-danger" data-dismiss="modal">Save</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>

      </div>
    </div>
<?php } ?>

<style>
    .error{
        color: red;
    }
</style>


@endsection

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.js"></script>

    <script>
        $().ready(function () {
          $("#vendorForm").validate({
              rules: {
                vcompanyname: {
                  required: true,
                },
                vfnmae: {
                  letterswithbasicpunc: true,
                },
                vlname: {
                   letterswithbasicpunc: true,
                },
                vaddress1:{
                    minlength: 3,
                },
                vcity: {
                    letterswithbasicpunc: true,
                },
                vstate: {
                  letterswithbasicpunc: true,
                  minlength: 2,
                },
                vphone: {
                  phoneUS: true,
                },
                vzip: {
                  minlength: 5,
                  maxlength: 6,
                },
                vemail: {
                  email: true,
                },

              }
          });
        });

        function cityCheck(){
            var regex = new RegExp("^[a-zA-Z\s]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                    return true;
            } else{
                e.preventDefault();
                alert('Please Enter Alphabate');
                return false;
           }
        }
    </script>

    <script>
        $(document).ready(function () {
            $("#error").removeClass("text-danger");
            $("#form-vendor").click(function(){
                var vcompanyname = $("#input-vendor-name").val();
                if(vcompanyname === ''){
                    $("#vcompanyname_error").text("Vendor Name is required!");
                }else {

                    var edi = $('#EDISelector').val();
                    var vendor_formate = $('input[name="vendor_format"]:checked').val();

                    if(edi == '1' && (vendor_formate == '' || vendor_formate == undefined)){
                        alert("Seclect Vendor EDI Formate");
                    }else{
                        <?php if(session()->get('hq_sid') == 1) { ?>
                            if($("#vendorForm").valid()){
                                $('#myModal').modal('show');
                            }
                        <?php } else{ ?>
                            $('#vendorForm').submit();
                        <?php } ?>
                    }
                }
            });
        });
    </script>

<script>
        $("#input_vendor_name").change(function(){
            var vendor = $("#input_vendor_name").val();
            $.ajax({
                url: "<?php echo url('/vendors/dupilcateHQvendor'); ?>",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                data: {vcompanyname: vendor},
                success: function(result){
                    if(result != "success"){
                        $("#vcompanyname_error").text(result);
                        $("#form-vendor").attr("disabled", true);
                        $("#input_vendor_name").val("");
                        setTimeout(function(){
                            $("#vcompanyname_error").text("");
                            $("#form-vendor").attr("disabled", false);

                        }, 3000);
                    }
                }
            });
        });

        var stores = [];
        stores.push("{{ session()->get('sid') }}");
        $('#selectAllCheckbox').click(function(){
            if($('#selectAllCheckbox').is(":checked")){
                $(".stores").prop( "checked", true );
            }else{
                $( ".stores" ).prop("checked", false );
            }
        });


        $('#save_btn').click(function(){

            $.each($("input[name='stores']:checked"), function(){
                stores.push($(this).val());
            });
            $("#hidden_store_hq_val").val(stores);
            $('#vendorForm').submit();
        })
    </script>

     <script>
        $(document).ready(function(){
             $("#EDIID").hide();
            $('#EDISelector').on('change', function() {
              if ( this.value == '1' )
              {
                $("#EDIID").show();

              }
              else
              {
                $("#EDIID").hide();
              }
            });
            // $(".upca_1").hide();
            // $(".upca_2").hide();
            // $(".upca_3").hide();
           var checkboxes_new = $("#upc_convert_E");

            checkboxes_new.on('click',checkStatus2);



            function checkStatus2() {


              if($(checkboxes_new).is(':checked'))
              {

                   $(".upca_1").show();
                   $(".upca_2").show();
                   $(".upca_3").show();
              }

            }

            var checkboxes_new_A = $("#upc_convert_A");

            checkboxes_new_A.on('click',checkStatus3);

            function checkStatus3() {

                if($(checkboxes_new_A).is(':checked'))
                {
                   $(".upca_1").show();
                   $(".upca_2").show();
                   $(".upca_3").hide();
                }

            }

            var checkboxes_new_None = $("#upc_convert_None");

            checkboxes_new_None.on('click',checkStatus4);

            function checkStatus4() {

                if($(checkboxes_new_None).is(':checked'))
                {
                   $(".upca_1").show();
                   $(".upca_2").show();
                   $(".upca_3").show();
                }

            }


        });

</script>
@endsection
