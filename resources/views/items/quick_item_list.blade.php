@extends('layouts.layout')

@section('title')
    Quick Items
@stop

@section('main-content')
<div id="content">
  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
      <div class="container">
          <div class="collapse navbar-collapse" id="main_nav">
              <div class="menu">
                  <span class="font-weight-bold text-uppercase fontvalue"> Quick Items</span>
              </div>
              <div class="nav-submenu">
                <button title="Update" class="btn btn-gray headerblack  buttons_menu" id="update_button"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
              </div>
          </div> <!-- navbar-collapse.// -->
      </div>
  </nav>
  <section class="section-content py-6">
      <div class="container">
          <div class="container-fluid">
              <div class="panel panel-default">
                  @if (session()->has('message'))
                    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                  @endif
                  @if (session()->has('error_message'))
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{session()->get('error_message')}}
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                  @endif

                  <div id='errorDiv'>
                      @if ($errors->any())
                        <div class="alert alert-danger">
                          @foreach ($errors->all() as $error)
                            <i class="fa fa-exclamation-circle"></i>{{$error}}
                          @endforeach
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                      @endif
                  </div>
                  <div class="panel-body">
                    <form action="{{ url('/item/quick_item/update') }}" method="post" id="form-quick-item">
                    @csrf
                    @method('post')
                        @if(session()->get('hq_sid') == 1)
                          <input type="hidden" id="edit_hidden_store_hq_val" name="stores_hq" value="">
                        @endif
                    <div class="table-responsive">
                        <table id="vendor" class="table table-hover promotionview" style="width: 100%">
                            <?php if ($items) { ?>
                                <thead>
                                <tr style="background-color: #286fb7!important;" >
                                        <th style="width: 1px;color:black; border-bottom-left-radius: 9px" class="text-center">
                                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                                        </th>
                                        <th class="col-xs-1 headername text-uppercase text-light" data-field="group_name">Group Name
                                            <div class="row">
                                                <div class="col-md-12 form-group has-search" style="height: 33px">
                                                    <span class="fa fa-search form-control-feedback"></span>
                                                    <input type="text" class="form-control table-heading-fields" placeholder="GROUP NAME" id="group_name">
                                                </div>
                                            </div>
                                        </th>
                                        <th class="col-xs-1 headername text-uppercase  text-light" data-field="register_name">Register Name
                                            <div class="row">
                                                <div class="col-md-12 form-group has-search" style="height: 33px">
                                                    <span class="fa fa-search form-control-feedback"></span>
                                                    <input type="text" class="form-control table-heading-fields" placeholder="REGISTER NAME" id="register_name">
                                                </div>
                                            </div>

                                        </th>
                                        <th class="col-xs-1 headername text-uppercase  text-light" data-field="sequence">Sequence
                                        <div class="row">
                                                <div class="col-md-12 form-group has-search" style="height: 33px">
                                                    <span class="fa fa-search form-control-feedback"></span>
                                                    <input type="text" class="form-control table-heading-fields" placeholder="SEQUENCE" id="sequence">
                                                </div>
                                            </div>
                                        </th>
                                        <th class="col-xs-1 headername text-uppercase  text-light" data-field="status">Status
                                            <div class="row">
                                                <div class="col-md-12 form-group has-search" style="height: 33px">
                                                    <span class="fa fa-search form-control-feedback"></span>
                                                    <input type="text" class="form-control table-heading-fields" placeholder="STATUS" id="status">
                                                </div>
                                            </div>
                                        </th>
                                </tr>
                                </thead>
                                <tbody id="searchData">

                                    <?php //$department_row = 1;$i=0; ?>
                                    <?php foreach ($items as $i => $item) { ?>
                                        <tr id="customer-row">

                                            <td style="border-bottom-left-radius: 9px !important;"  data-order="<?php echo $item['iitemgroupid']; ?>" class="text-center">
                                            <span style="display:none;"><?php echo $item['iitemgroupid']; ?></span>
                                            <?php //if (in_array($item['iitemgroupid'], $selected)) { ?>
                                                {{-- <input type="checkbox" name="selected[]" id="quick_item[{{$i}}][select]" value="{{$item['iitemgroupid']}}" checked="checked" /> --}}
                                            <?php //} else { ?>
                                                {{-- <input type="checkbox" name="selected[]" id="quick_item[{{$i}}][select]" value="{{$item['iitemgroupid']}}" /> --}}
                                            <?php //} ?>
                                                <input type="checkbox" name="selected[]" id="quick_item[{{$i}}][select]" class="checkboxId" value="{{$item['iitemgroupid']}}">
                                                <input type="hidden"  name="quick_item[{{$i}}][iitemgroupid]" value="{{ $item['iitemgroupid'] }}">
                                            </td>

                                            <td>
                                                {{-- <span style="display:none;">{{ $item['vitemgroupname'] }}</span>
                                                <input type="text" maxlength="50" style="border:none;" class="editable quick_vitemgroupname" name="quick_item[{{$i}}][vitemgroupname]" id="quick_item[{{$i}}][vitemgroupname]" value="{{ $item['vitemgroupname'] }}" onclick='document.getElementById("quick_item[{{ $i }}][select]").setAttribute("checked","checked");' />
                                                <input type="hidden" name="quick_item[{{$i}}][vitemgroupname]" value="{{ $item['vitemgroupname'] }}"/> --}}
                                                <input style="border: none;" type="text" class="editable quick_vitemgroupname" name="quick_item[{{$i}}][vitemgroupname]" value="{{ $item['vitemgroupname'] }}" onclick='document.getElementById("quick_item[{{ $i }}][select]").setAttribute("checked","checked");' />
                                            </td>

                                            <td>
                                                <span>{{ $item['vterminalid'] }}</span>
                                            </td>

                                            <td>
                                                {{-- <span style="display:none;">{{ $item['isequence'] }}</span>
                                                <input type="text" maxlength="50" style="border:none;" class="editable quick_sequence" name="quick_item[{{ $i }}][isequence]" id="quick_item[{{ $i }}][isequence]" value="{{ $item['isequence'] }}" onclick='document.getElementById("quick_item[{{$i}}][select]").setAttribute("checked","checked");' />
                                                <input type="hidden" name="quick_item[{{ $i }}][isequence]" value="{{ $item['isequence'] }}"/> --}}
                                                <input style="border: none;" type="text" class="editable quick_sequence" name="quick_item[{{ $i }}][isequence]" value="{{ $item['isequence'] }}" onclick='document.getElementById("quick_item[{{$i}}][select]").setAttribute("checked","checked");' style="text-align: right;" />
                                            </td>
                                            <td>
                                                <span>{{ $item['estatus'] }}</span>
                                            </td>
                                        </tr>
                                    <?php //$department_row++; $i++;?>
                                    <?php } ?>
                            <?php } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center"><?php echo 'No Data Found';?></td>
                                    </tr>
                            <?php } ?>
                                </tbody>
                        </table>
                          {{-- <div class="pull-right" style="margin-right: 29px">
                            {{ $departments->links() }}
                          </div> --}}
                    </div>
                  </form>
                  </div>
              </div>
          </div>
      </div>
  </section>
  <!-- Modal Add -->
</div>

<?php if(session()->get('hq_sid') == 1){ ?>
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to delete the Quick item:</h4>
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
                </thead >
                <tbody>
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check custom-control-input stores" id="stores" name="stores" value="{{ $stores->id }}">
                                </div>
                            </td>
                            <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" id="save_btn" class="btn btn-primary" data-dismiss="modal">Update</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
      </div>
    </div>
<?php } ?>

@endsection

@section('page-script')
<style>
    #item_paginate{
        float: right;
    }
</style>

{{-- <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}

    {{-- <script src=" {{ asset('javascript/bootbox.min.js') }}"></script> --}}
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">

var table = $('#vendor').DataTable({
        // "dom": 't<"bottom col-md-12 row"<"col-md-2"i><"col-md-3"l><"col-md-7"p>>',
        "dom": 't<<"float-right"p>><"clear">',
        "searching":true,
        "destroy": true,
        "ordering": false,
        "pageLength":10,
        "order": [[ 3, "asc" ]]
    });

    $('#group_name').on('input', function () {
        table.columns(1).search(this.value).draw();
    });
    $('#register_name').on('input', function () {
        table.columns(2).search(this.value).draw();
    });
    $('#sequence').on('input', function () {
        table.columns(3).search(this.value).draw();
    });
    $('#status').on('input', function () {
        table.columns(4).search(this.value).draw();
    });

  $("#vendor_paginate").addClass("pull-right");

var stores = [];
stores.push("{{ session()->get('sid') }}");
    $('#selectAllCheckbox').click(function(){
    if($('#selectAllCheckbox').is(":checked")){
        $(".stores").prop( "checked", true );
    }else{
        $( ".stores" ).prop("checked", false );
    }
});

$("#save_btn").click(function(){
    $.each($("input[name='stores']:checked"), function(){
        stores.push($(this).val());
    });
    $("#hidden_store_hq_val").val(stores);
    $('#form-quick-item').submit();
    $("div#divLoading").addClass('show');
});


$(document).on('click', '#update_button', function(event) {

var all_itemgroupname = true;
var all_itemgroupname_arr = [];

var all_sequence = true;
var all_sequence_arr = [];
var all_correct = true;

var checkedCheckBox = false;

$("#searchData input[type=checkbox]:checked").each(function () {
    checkedCheckBox = true;
});

if(checkedCheckBox === false){
    bootbox.alert({
        size: 'small',
        title: "",
        message: "You did not select anything",
        callback: function(){location.reload(true);}
    });
    $("div#divLoading").removeClass('show');
    return false;
}

$('.quick_vitemgroupname').each(function(){
  if($(this).val() == ''){
    // alert('Please Enter Group Name');

    bootbox.alert({
      size: 'small',
      title: "",
      message: "Please Enter Group Name",
      callback: function(){}
    });

    all_itemgroupname = false;
    all_correct = false;
    return false;
  }else{
    if(jQuery.inArray($(this).val(), all_itemgroupname_arr) !== -1){
      // alert('Please Enter Unique Group Name');

      bootbox.alert({
        size: 'small',
        title: "",
        message: "Please Enter Unique Group Name",
        callback: function(){}
      });

      all_itemgroupname = false;
      all_correct = false;
      return false;
    }else{
      all_itemgroupname_arr.push($(this).val());
      all_itemgroupname = true;
    }
  }
});

if(all_itemgroupname == true){
  $('.quick_sequence').each(function(){
    if($(this).val() == ''){
      // alert('Please Enter Sequence');

      bootbox.alert({
        size: 'small',
        title: "Attention",
        message: "Please Enter Sequence",
        callback: function(){}
      });

      all_sequence = false;
      all_correct = false;
      return false;
    }else{
      if(jQuery.inArray($(this).val(), all_sequence_arr) !== -1){
        // alert('Please Enter Unique Sequence');
        bootbox.alert({
          size: 'small',
          title: "",
          message: "Please Enter Unique Sequence",
          callback: function(){}
        });

        all_sequence = false;
        all_correct = false;
        return false;
      }else{
        all_sequence_arr.push($(this).val());
        all_sequence = true;
      }
    }
  });
}

    if(all_correct == true){
        <?php if(session()->get('hq_sid') == 1) { ?>
            $("div#divLoading").removeClass('show');
            $("#myModal").modal('show');
        <?php } else { ?>
            $('#form-quick-item').submit();
            $("div#divLoading").addClass('show');
        <?php } ?>
    }
    });
    $("#save_btn").click(function(){
        $.each($("input[name='stores']:checked"), function(){
            stores.push($(this).val());
        });
        $("#hidden_store_hq_val").val(stores);
        $('#form-quick-item').submit();
        $("div#divLoading").addClass('show');
    });

    $(document).on('keypress keyup blur', '.quick_sequence', function(event) {

    $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }

    });

//  ================ Delete Code =======================

    // $(document).ready(function() {
    //   var table = $('#item').DataTable({
    //     "searching": true,
    //     "destroy": true,
    //     "bLengthChange": false,
    //     "pageLength":10
    //   });
    // });
    // $(window).load(function() {
    //     $("div#divLoading").removeClass('show');
    // });
</script>
<style>
    .disabled {
        pointer-events:none; //This makes it not clickable
    }
</style>
@endsection
