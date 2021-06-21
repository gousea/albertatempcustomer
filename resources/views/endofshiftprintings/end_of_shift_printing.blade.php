@extends('layouts.layout')
@section('title')
  POS Settings
@endsection
@section('main-content')
<link rel="stylesheet" href="{{ asset('asset/css/promotion.css') }}">
<div id="content">
  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
      <div class="container">
          <div class="collapse navbar-collapse" id="main_nav">
              <div class="menu">
                  <span class="font-weight-bold text-uppercase" >POS SETTINGS</span>
              </div>
              <div class="nav-submenu">
                  <button type="button" id="save_button"  class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
              </div>
          </div> <!-- navbar-collapse.// -->
      </div>
  </nav>

  <div class="container section-content">
      @if (session()->has('message'))
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>      
      @endif

      @if (session()->has('error'))
        <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> {{session()->get('error')}}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>      
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
          </ul>
        </div>                
      @endif
    <form action="{{Route('end_of_shift_printing.store') }}" method="post" enctype="multipart/form-data" id="form-end-of-shift-printing">
        @csrf
        <div class="mytextdiv">
            <div class="mytexttitle font-weight-bold text-uppercase">
              POS Setting
            </div>
            <div class="divider font-weight-bold"></div>
        </div>
        <div class="container-fluid py-3">
            <div class="row">
                <div class="col-md-12 mx-auto">
                  
                    @method('post')
                    
                      
                    <div class="row">
                      <div class="col-md-12">
                        <fieldset class="the_fieldset">
                          <p>
                            <input style="top: 4px;" value="1" type="checkbox" name="EndOfShiftPrinting" 
                              <?php echo isset($data['EndOfShiftPrinting']['variablevalue']) && $data['EndOfShiftPrinting']['variablevalue'] == 1  ? "checked" : "" ?> />
                              <b style="color: #286fb7 !important">&nbsp;&nbsp;&nbsp;Enable End of Shift Printing</b>
                          </p>
                        </fieldset>
                      </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <div class="mytextdiv">
            <div class="mytexttitle font-weight-bold text-uppercase">
              Kiosk Setting
            </div>
            <div class="divider font-weight-bold"></div>
        </div>
        <div class="container-fluid py-3">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <p><input style="top: 4px;" value="1" type="checkbox" name="PrintDeliveryStation" 
                            <?php echo isset($data['PrintDeliveryStation']['variablevalue']) && $data['PrintDeliveryStation']['variablevalue'] == 1  ? "checked" : "" ?> />
                            <b style="color: #286fb7 !important">&nbsp;&nbsp;&nbsp;Enable Print Delivery Station</b>
                          </p>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                          <p><input style="top: 4px;" value="1" type="checkbox" name="PrintDeliItemwise" 
                            <?php echo isset($data['PrintDeliItemwise']['variablevalue']) && $data['PrintDeliItemwise']['variablevalue'] == 1  ? "checked" : "" ?> />
                            <b style="color: #286fb7 !important"> &nbsp;&nbsp;&nbsp;Enable Print Deli Itemwise</b>
                          </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>

    </div>
  
</div>

@endsection


@section('page-script')
<script type="text/javascript">
 $(document).on('click', '#save_button', function(event) {
    $('#form-end-of-shift-printing').submit();
    $("div#divLoading").addClass('show');
  });
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script> 
@endsection