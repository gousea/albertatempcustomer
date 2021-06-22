@extends('layouts.layout')

@section('title')
Quick Items
@endsection

@section('main-content')

<div id="content">
    <div class="page-header">



    <div class="container">

      @if (session()->has('message'))
          <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
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


      <div class="panel panel-default" style="">
    <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i>Edit Quick Items</h3>
    </div>


        <div class="panel-body">

        <div class="row" style="padding-bottom: 9px;float: right;">
          <div class="col-md-12">
            <div class="">
              <button title="Update" class="btn btn-primary" id="update_button"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>

      <br>

    <form action="{{ url('/item/quick_item/update') }}" method="post" id="form-quick-item">
      @csrf
      <!-- <input type="hidden" name="MenuId" value=""> -->
            @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
            @endif
          <div class="table-responsive">
            @if ($items)
            <table id="vendor" class="table table-hover promotionview" style="width: 100%">
                <thead>
                    <tr class="header-color">
                        <th class="text-center"><input type="checkbox"
                                onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>

                        <th class="col-xs-1 headername text-uppercase">Group Name
                            {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control table-heading-fields" placeholder="GROUP NAME" id="group_name">
                            </div>
                        </th>

                        <th class="col-xs-1 headername text-uppercase">Register Name
                            {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control table-heading-fields"
                                    placeholder="REGISTER NAME" id="register_name">
                            </div>
                        </th>
                        <th class="col-xs-1 headername text-uppercase">Sequence
                            {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control table-heading-fields"
                                    placeholder="SEQUENCE" id="sequence">
                            </div>
                        </th>
                        <th class="col-xs-1 headername text-uppercase">Status
                            {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i> --}}
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control table-heading-fields" placeholder="STATUS" id="status">
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="table-body">
                    @foreach ($items as $i => $item)
                        <tr id="customer-row">
                            <td data-order="" class="text-center">
                                <input type="checkbox" name="selected[]" id="quick_item[{{$i}}][select]" class="checkboxId" value="{{$item['iitemgroupid']}}">
                                <input type="hidden"  name="quick_item[{{$i}}][iitemgroupid]" value="{{ $item['iitemgroupid'] }}">
                            </td>
                            <td>
                                {{-- <a href="{{route('users.edit', $user->iuserid)}}"
                                    data-toggle="tooltip" title="Edit"><span>{{ $user->vfname }}</span>
                                </a> --}}
                                <input type="text" class="editable quick_vitemgroupname" name="quick_item[{{$i}}][vitemgroupname]" value="{{ $item['vitemgroupname'] }}" onclick='document.getElementById("quick_item[{{ $i }}][select]").setAttribute("checked","checked");' />
                            </td>
                            <td>
                                <a href="#"
                                    data-toggle="tooltip" title="Edit"><span>{{ $item['vterminalid'] }}</span>
                                </a>
                            </td>
                            <td>
                                {{-- <a href="{{route('users.edit', $user->iuserid)}}"
                                    data-toggle="tooltip" title="Edit"><span>{{ $user->vemail }}</span>
                                </a> --}}
                                <input type="text" class="editable quick_sequence" name="quick_item[{{ $i }}][isequence]" value="{{ $item['isequence'] }}" onclick='document.getElementById("quick_item[{{$i}}][select]").setAttribute("checked","checked");' style="text-align: right;" />
                            </td>
                            <td>
                                <a href="#"
                                    data-toggle="tooltip" title="Edit"><span>{{ $item['estatus'] }}</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
            @else
            <tr>
              <td colspan="7" class="text-center">No results Found</td>
            </tr>
            @endif
          </div>
        </form>
        </div>
      </div>
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

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<style>
    #item_paginate{
        float: right;
    }
</style>

<script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>


<script type="text/javascript">
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
            title: "Attention",
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
          title: "Attention",
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
            title: "Attention",
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
              title: "Attention",
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

    $(document).on('keypress keyup blur', '.quick_sequence', function(event) {

        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }

    });
</script>

<!-- DataTables -->

<script src="{{ asset('javascript/select2/js/select2.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    $(document).ready(function() {
      var table = $('#item').DataTable({
        "searching": false,
        "destroy": true,
        "bLengthChange": false,
        "pageLength":20
      });
    });
    $(window).load(function() {
        $("div#divLoading").removeClass('show');
    });

    var table = $('#vendor').DataTable({
        // "dom": 't<"bottom col-md-12 row"<"col-md-2"i><"col-md-3"l><"col-md-7"p>>',
        "dom": 't<<"float-right"p>><"clear">',
        "searching":true,
        "destroy": true,
        "ordering": false,
        "pageLength":10,
        "order": [[ 3, "asc" ]]
    });

    $('#name').on('input', function () {
        table.columns(1).search(this.value).draw();
    });
    $('#cell_number').on('input', function () {
        table.columns(2).search(this.value).draw();
    });
    $('#email').on('input', function () {
        table.columns(3).search(this.value).draw();
    });
    $('#status').on('input', function () {
        table.columns(4).search(this.value).draw();
    });

  $("#vendor_paginate").addClass("pull-right");
</script>

@endsection
