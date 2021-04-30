@extends('layouts.master')
@section('title')
  POS Settings
@endsection
@section('main-content')

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!-- <h1><?php //echo $heading_title; ?></h1> -->
      <ul class="breadcrumb">
        <?php //foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php //echo $breadcrumb['href']; ?>"><?php //echo $breadcrumb['text']; ?></a></li>
        <?php //} ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i>POS Settings</h3>
      </div>
      <div class="panel-body">

        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
            <div class="">
              <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
          
      <form action="{{Route('end_of_shift_printing.store') }}" method="post" enctype="multipart/form-data" id="form-end-of-shift-printing">
        @csrf
        @method('post')
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
            
          <div class="row">
            <div class="col-md-12">
              <fieldset class="the_fieldset">
                <legend class="the_legend">POS Setting</legend>
                <p><b>Enable End of Shift Printing:&nbsp;&nbsp;&nbsp;</b><input style="top: 4px;" value="1" type="checkbox" name="EndOfShiftPrinting" 
                    <?php echo isset($data['EndOfShiftPrinting']['variablevalue']) && $data['EndOfShiftPrinting']['variablevalue'] == 1  ? "checked" : "" ?> />
                </p>
              </fieldset>
            </div>
          </div>
          <br>
          <br>
          <fieldset class="the_fieldset">
            <legend class="the_legend">Kiosk Setting</legend>
          <div class="row">
            <div class="col-md-12">
              <p><b>Enable Print Delivery Station:&nbsp;&nbsp;&nbsp;</b><input style="top: 4px;" value="1" type="checkbox" name="PrintDeliveryStation" 
                  <?php echo isset($data['PrintDeliveryStation']['variablevalue']) && $data['PrintDeliveryStation']['variablevalue'] == 1  ? "checked" : "" ?> />
                </p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <p><b>Enable Print Deli Itemwise:&nbsp;&nbsp;&nbsp;</b><input style="top: 4px;" value="1" type="checkbox" name="PrintDeliItemwise" 
                <?php echo isset($data['PrintDeliItemwise']['variablevalue']) && $data['PrintDeliItemwise']['variablevalue'] == 1  ? "checked" : "" ?> />
              </p>
            </div>
          </div>
          </fieldset>
        </form>
        
      </div>
    </div>
  </div>
</div>

@endsection


@section('scripts')
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