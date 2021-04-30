@extends('layouts.master')

@section('title')
    Edit Users
@stop
@section('styles')
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
<style>
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
          margin-left: -15px;
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

<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <ul class="breadcrumb">
          <li><a href="">User Form</a></li>
        </ul>
      </div>
    </div>
    <div class="container-fluid">

        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
          </ul>
        </div>
        @endif
        {{-- <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div> --}}
        <div class="panel panel-default">
            <div class="panel-heading head_title">
            <h3 class="panel-title">Users<i class="fa fa-pencil"></i></h3>
            </div>
            <div class="panel-body">
                <div class="row" style="padding-bottom: 9px; padding-left: 10px;">
                    <div class="col-md-12"></div>
                </div>
                        <div class="clearfix"></div>
                        <form name="myForm" action="{{ route('users.update', $users->iuserid) }}" method="POST" enctype="multipart/form-data" id="form-user" class="form-horizontal" >
                            @csrf
                            @method('PATCH')
                            <div class="container col-md-12">
                                <div class="row col-md-10" id="device_control">
                                    <div class="col-md-12">
                                        <div class="col-md-3 form-group">
                                            <input type="checkbox" name="web" value="web" id="fancy-checkbox-warning" autocomplete="off" class="web device"
                                               @if(isset(session()->get('userInput')['web']))
                                                    checked
                                                @endif
                                                @if ($users->web_user == 'Y')
                                                  checked
                                                @endif
                                            />
                                            <div class="[ btn-group ]">
                                                <label for="fancy-checkbox-warning" class="[ btn btn-warning ]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span> </span>
                                                </label>
                                                <label for="fancy-checkbox-warning" class="[ btn btn-default active ]">
                                                    Web
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group required">
                                            <input type="checkbox" name="mob" value="mob" id="fancy-checkbox-primary" autocomplete="off" class="mob device"
                                                @if(isset(session()->get('userInput')['mob']))
                                                    checked
                                                @endif
                                                @if ($users->mob_user == 'Y')
                                                    checked
                                                @endif
                                            />
                                            <div class="[ btn-group ]">
                                                <label for="fancy-checkbox-primary" class="[ btn btn-primary ]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span> </span>
                                                </label>
                                                <label for="fancy-checkbox-primary" class="[ btn btn-default active ]">
                                                    Mobile
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group required">
                                            <input type="checkbox" name="pos" value="pos" id="fancy-checkbox-success" autocomplete="off" class="pos device"
                                                @if(isset(session()->get('userInput')['pos']))
                                                    checked
                                                @endif
                                                @if ($users->pos_user == 'Y')
                                                    checked
                                                @endif
                                            />
                                            <div class="[ btn-group ] required">
                                                <label for="fancy-checkbox-success" class="[ btn btn-success ]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span> </span>
                                                </label>
                                                <label for="fancy-checkbox-success" class="[ btn btn-default active ]">
                                                    POS
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group required">
                                            <input type="checkbox" name="lb" value="lb" id="fancy-checkbox-danger" autocomplete="off" class="lb device"
                                                @if(isset(session()->get('userInput')['lb']))
                                                    checked
                                                @endif
                                                @if ($users->lb_user == 'Y')
                                                    checked
                                                @endif
                                            />
                                            <div class="[ btn-group ]">
                                                <label for="fancy-checkbox-danger" class="[ btn btn-danger ]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span> </span>
                                                </label>
                                                <label for="fancy-checkbox-danger" class="[ btn btn-default active ]">
                                                    LineBuster
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row col-md-2">
                                    <div class="form-group control_system">
                                        <input type="submit" id="Submit" value="Save" class="btn btn-primary" disabled>
                                        <a href="{{ route('users') }}" data-toggle="tooltip" title="Cancel" class="btn btn-default cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-vfname">First Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vfname" maxlength="25" value="{{ $users->vfname }}" placeholder="" id="input-vfname" class="form-control" required onkeypress="return (event.charCode > 64 &&
                                        event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"/>

                                        {{-- <div class="text-danger" id="vfnameError"></div> --}}

                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-vlname">Last Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vlname" maxlength="25" value="{{ $users->vlname }}" placeholder="" id="input-vlname" class="form-control" required  onkeypress="return (event.charCode > 64 &&
                                        event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />

                                        {{-- <div class="text-danger" id="vlnameError"></div> --}}

                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-vaddress1">Address-1</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vaddress1" maxlength="75" value="{{ $users->vaddress1 }}" placeholder="" id="input-vaddress1" class="form-control"/>

                                        {{-- <div class="text-danger" id="addressError"></div> --}}

                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-vaddress2">Address-2</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vaddress2" maxlength="75" value="{{ $users->vaddress2 }}" placeholder="" id="input-vaddress2" class="form-control" />
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-city">City</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vcity" maxlength="25" value="{{ $users->vcity }}" placeholder="" id="input-city" class="form-control" onkeypress="return (event.charCode > 64 &&
                                        event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-state">State</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vstate" maxlength="25" value="{{ $users->vstate }}" placeholder="" id="input-state" class="form-control" onkeypress="return (event.charCode > 64 &&
                                        event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-zip">Zip</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vzip" maxlength="10" value="{{ $users->vzip }}" placeholder="" id="input-zip" class="form-control" />
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-country">Country</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vcountry" maxlength="20" value="USA" class="form-control" readonly />
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-phone">Phone</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vphone" maxlength="20" value="{{ $users->vphone }}" placeholder="" id="input-phone" class="form-control" />
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-vusertype">User Type</label>
                                        <div class="col-sm-8">
                                            <select name="vusertype" id="input-vusertype" class="form-control" required>
                                            <option value="">Select User Type</option>
                                            @foreach ($mstPermissiongroup as $group)
                                                <option value="{{$group->vgroupname}}"
                                                    @if ($group->vgroupname == $users->vusertype)
                                                        selected
                                                    @endif >{{ $group->vgroupname }}
                                                </option>
                                                {{-- <option value="{{$group->vgroupname}}">{{$group->vgroupname}}</option> --}}
                                            @endforeach
                                            </select>
                                            {{-- <div class="text-danger" id="usertypeError"></div> --}}
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!--<div class="row">-->
                            <!--    <div class="col-md-6">-->
                            <!--        <div class="form-group">-->
                            <!--            <label class="col-sm-4 control-label" for="input-vlocktype">Is UserLocked ?</label>-->
                            <!--            <div class="col-sm-8">-->
                            <!--                <select name="vlocktype" id="input-vlocktype" class="form-control" disabled="true">-->
                            <!--                <option value="unlock" >Unlock</option>-->
                            <!--                </select>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--    <div class="col-md-6">-->
                            <!--        <div class="form-group">-->
                            <!--            <label class="col-sm-4 control-label" for="input-vpasswordchange">Change Password at First Login</label>-->
                            <!--            <div class="col-sm-8">-->
                            <!--                <select name="vpasswordchange" id="input-vpasswordchange" class="form-control">-->
                            <!--                    <option value="No" {{ $users->vpasswordchange== 'No' ? 'selected' : '' }} >No</option>-->
                            <!--                    <option value="Yes" {{ $users->vpasswordchange == 'Yes' ? 'selected' : '' }} >Yes</option>-->
                            <!--                </select>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-vuserbarcode">Barcode #</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="vuserbarcode" maxlength="25" value="{{ $users->vuserbarcode }}" placeholder="" id="input-vuserbarcode" class="form-control" />
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-estatus">Status</label>
                                    <div class="col-sm-8">
                                        <select name="estatus" id="input-estatus" class="form-control">
                                            <option value="Active"  {{ $users->estatus == 'Active' ? 'selected' : '' }} >Active</option>
                                            <option value="Inactive" {{ $users->estatus== 'Inactive' ? 'selected' : '' }}  >InActive</option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="web_mob_form check" id="web_mob">
                                <h4 class="heading"><strong>Web & Mob </strong><span></span></h4>
                                <div class="row" id="webmob">
                                    <div class="col-md-6">
                                        <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-email">Email</label>
                                        <div class="col-sm-8">
                                            <input type="email" name="vemail" maxlength="125" value="{{ $users->vemail }}" placeholder="" id="input-email" class="form-control webemail" />

                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-mwpassword">Password</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="mwpassword" value="" placeholder="" id="input-mwpassword" class="form-control webpass" />

                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-re-mwpassword">Re-Type Password</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="re_mwpassword" value="" placeholder="" id="input-re-mwpassword" class="form-control webconpass" />
                                            <div class="text-success" id="confirm-pass-msg1"></div>

                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="web_mob_form check" id="pos">
                                <h4 class="heading"><strong>POS </strong><span></span></h4>
                                <div class="row" id="pos">
                                    <div class="col-md-6">
                                            <div class="form-group required" >
                                            <label class="col-sm-4 control-label" for="input-vuserid">POS User ID</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="vuserid" maxlength="3" value="{{ $users->vuserid }}" placeholder="" id="input-vuserid" class="form-control posemail" />
                                                <div class="text-success" id="user-id-msg"></div>



                                            </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3">
                                            <div class="form-group required">
                                            <label class="col-sm-5 control-label" for="input-vpassword">Password</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="vpassword" maxlength="4" value="" placeholder="" id="input-vpassword" class="form-control pospass" />



                                            </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3">
                                            <div class="form-group required">
                                            <label class="col-sm-4 control-label" for="input-re-vpassword">Re-Type Password</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="re_vpassword" maxlength="4" value="" placeholder="" id="input-re-vpassword" class="form-control posconpass" />
                                                <div class="text-success" id="confirm-pass-msg2"></div>

                                            </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row1">
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
                                                        <td>
                                                            <div class="custom-control custom-checkbox" id="table_orange_check">
                                                                <input type="checkbox" class="checks check custom-control-input" id="customCheck5" name="permission[]" value="{{ $permission->vpermissioncode }}"
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
                                <div class="col-md-3" id="mob" style="display: none;">
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
                                                    <td>
                                                        <div class="custom-control custom-checkbox" id="table_blue_check">
                                                            <input type="checkbox" class="checks check custom-control-input" id="customCheck5" name="permission[]" value="{{ $permission->vpermissioncode }}"
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
                                <div class="col-md-5 check" id="pos1" style="display: none;">
                                    <table class="table table-bordered">
                                        <thead id="table_green_header_tag">
                                            <tr><th colspan="2" id="table_green_header">POS Permissions</th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($permissions as $permission)
                                                @if ($permission->vpermissiontype != 'MOB' && $permission->vpermissiontype != 'WEB')
                                                    <tr>
                                                        <td>
                                                            <div class="custom-control custom-checkbox" id="table_green_check">
                                                                <input type="checkbox" class="checks check custom-control-input" id="customCheck5" name="permission[]" value="{{ $permission->vpermissioncode }}"
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
                            </div>
                        </form>
                    
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')

    <script type="text/javascript">
        $(window).load(function() {
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
          return false;
        }else{
          $('#confirm-pass-msg2').removeClass('text-success').addClass('text-danger');
          $('#confirm-pass-msg2').html('POS Password Not Matched');
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

    <script src="view/javascript/jquery.maskedinput.min.js"></script>
    <script type="text/javascript">
      jQuery(function($){
        $("input[name='vphone']").mask("999-999-9999");
      });
    </script>
    <script>

    setInterval(function() {
        if($('.mob').prop("checked") == true){
            $('#web_mob').show();
            document.getElementById("Submit").disabled = false;
        }
        
        if($('.lb').prop("checked") == true){
            document.getElementById("Submit").disabled = false;
        }else{
            document.getElementById("Submit").disabled = true;
        }
        
        if($('.pos').prop("checked") == true){
            $('#pos').show();
            document.getElementById("Submit").disabled = false;
             var vemailval = $('.webemail').val();
            if(vemailval == '--'){
              empty_val = "";
             $('.webemail').attr('value', empty_val); 
            }
        }

        if($('.web').prop("checked") == true){
            $('#web_mob').show();
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

 //new code
    $('.web').click(function () {
        $('#web_mob').toggle(this.checked);
        if(this.checked){
            $('#web').show();
            $("#input-email").attr("required", "required");
            $("#input-mwpassword").attr("required", "required");
            $("#input-re-mwpassword").attr("required", "required");
        }else{
            $('#web').hide();
            $("#input-email").removeAttr("required", "required");
            $("#input-mwpassword").removeAttr("required", "required");
            $("#input-re-mwpassword").removeAttr("required", "required");
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
        }
    });
    
    

    $('.mob').click(function () {
        $('#web_mob').toggle(this.checked);
        if($(".web").is(':checked')){
            if(this.checked){
                $('#mob').show();
                $("#input-email").removeAttr("required", "required");
                $("#input-mwpassword").removeAttr("required", "required");
                $("#input-re-mwpassword").removeAttr("required", "required");
            }else {
                $('#mob').hide();
            }
        }else {
            if(this.checked){
                $('#mob').show();
                $("#input-email").attr("required", "required");
                $("#input-mwpassword").attr("required", "required");
                $("#input-re-mwpassword").attr("required", "required");
            }else{
                $("#input-email").removeAttr("required", "required");
                $("#input-mwpassword").removeAttr("required", "required");
                $("#input-re-mwpassword").removeAttr("required", "required");
                $('#mob').hide();
            }
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
@endsection
