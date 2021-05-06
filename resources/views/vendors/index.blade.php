@extends('layouts.layout')

@section('title')
    Vendor
@stop

{{-- <link rel="stylesheet" href="{{ asset('asset/css/vendor.css') }}"> --}}

@section('main-content')

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Vendor</span>
                </div>
                <div class="nav-submenu">
                    <a type="button" class="btn btn-gray headerblack  buttons_menu " href="{{ route('vendors.create') }}">
                        ADD NEW
                    </a>
                    <button type="button" class="btn btn-danger buttonred buttons_menu basic-button-small"
                        id="vender_delete" title="Delete" style="border-radius: 0px;"><i
                            class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
                    {{-- <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> DELETE
                        </a> --}}
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>

    <section class="section-content py-6">
        @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        <div class="container-fluid">
            <form action="" method="post" enctype="multipart/form-data" id="form-vendor">
                @csrf
                @if (session()->get('hq_sid') == 1)
                    <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
                @endif
                <input type="hidden" name="MenuId" value="" />
                <div class="table-responsive">
                    <table id="vendor" class="table table-bordered table-hover employeeview">
                        <thead>
                            <tr class="header-color">
                                <th style="width: 1px; color:black;" class="text-center"><input type="checkbox"
                                        style="background: none !important;"
                                        onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>

                                <th class="col-xs-1 headername text-uppercase">Supplier Code
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields"
                                            placeholder="Supplier Code">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Vendor Name
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields"
                                            placeholder="VENDOR NAME">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Phone
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="PHONE">
                                    </div>
                                </th>
                                <th class="col-xs-1 headername text-uppercase">Email
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control table-heading-fields" placeholder="EMAIL">
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $vendor_row = 1;
                            $i = 0;
                            $selected = [];
                            ?>
                            @foreach ($vendors as $vendor)
                                <tr id="vendor-row{{ $vendor_row }}">
                                    <td data-order="" class="text-center">
                                        <input type="checkbox" name="selected[]" id=""
                                            value="{{ $vendor->isupplierid }}" />
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('vendors.edit', $vendor->isupplierid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $vendor->vsuppliercode }}</span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('vendors.edit', $vendor->isupplierid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $vendor->vcompanyname }}</span>
                                        </a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ route('vendors.edit', $vendor->isupplierid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $vendor->vphone }} </span>
                                        </a>
                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('vendors.edit', $vendor->isupplierid) }}" data-toggle="tooltip"
                                            title="Edit"><span>{{ $vendor->vemail }}</span>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                $vendor_row++;
                                $i++;
                                ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="row">
                <div class="col-sm-6 text-left">{{ $vendors->links() }}</div>
                <div class="col-sm-6 text-right"></div>
            </div>
        </div>
    </section>
    {{-- @endsection --}}

    <?php if (session()->get('hq_sid') == 1) { ?>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select the stores in which you want to delete the vendor:</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead id="table_green_header_tag">
                            <tr>
                                <th>
                                    <div class="custom-control custom-checkbox" id="table_green_check">
                                        <input type="checkbox" class="" id="selectAllCheckbox" name="" value=""
                                            style="background: none !important;">
                                    </div>
                                </th>
                                <th colspan="2" id="table_green_header">Select All</th>
                            </tr>
                        </thead>
                        <tbody id="data_stores"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id="save_btn" class="btn btn-danger" data-dismiss="modal">Delete</button>
                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select the stores in which you want to Edit the vendor:</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead id="table_green_header_tag">
                            <tr>
                                <th>
                                    <div class="custom-control custom-checkbox" id="table_green_check">
                                        <input type="checkbox" class="" id="editSelectAllCheckbox" name="" value=""
                                            style="background: none !important;">
                                    </div>
                                </th>
                                <th colspan="2" id="table_green_header">Select All</th>
                            </tr>
                        </thead>
                        <tbody id="edit_data_stores"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id="update_btn" class="btn btn-primary" data-dismiss="modal">Update</button>
                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div id="errorModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Error Model</h4>
                </div>

                <div class="modal-body">
                    <h3>No vendor is selected </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    <script src=" {{ asset('javascript/bootbox.min.js') }}"></script>
    {{-- <script src=" {{ asset('asset/js/vendor.js') }}"></script> --}}
    <script>
        $('#vender_delete').click(function(){
    var vendor_ids = [];
    $.each($("input[name='selected[]']:checked"), function(){
        vendor_ids.push($(this).val());
    });
    console.log(vendor_ids);
    if(vendor_ids.length > 0){
            <?php if(session()->get('hq_sid') == 1) { ?>
                $('#myModal').modal('show');
                $.ajax({
                      url: "<?php echo url('/vendors/duplicatehqvendors'); ?>",
                      method: 'post',
                      headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                      },
                      data: {vendor_ids},
                      success: function(result){
                                var popup = '';
                                @foreach (session()->get('stores_hq') as $stores)
                                    if(result.includes({{ $stores->id }})){
                                        var data = '<tr>'+
                                                        '<td>'+
                                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                '<input type="checkbox" class="checks check custom-control-input stores" disabled id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
                                                            '</div>'+
                                                        '</td>'+
                                                        '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Vendor does not exist)</span></td>'+
                                                    '</tr>';
                                                $('#selectAllCheckbox').attr('disabled', true);

                                    } else {
                                        var data = '<tr>'+
                                                        '<td>'+
                                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                '<input type="checkbox" class="checks check custom-control-input stores"  id="else_hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
                                                            '</div>'+
                                                        '</td>'+
                                                        '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                    '</tr>';
                                    }
                                    popup = popup + data;
                                @endforeach
                        $('#data_stores').html(popup);
                    }
                });

            <?php } else{ ?>
                $.ajax({
                      url: "<?php echo url('vendors/remove'); ?>",
                      method: 'post',
                      headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                      },
                      data: {vendor_ids},
                      success: function(result){
                        bootbox.alert({
                            size: 'small',
                            title: "Success",
                            message: result,
                            callback: function(){
                                location.reload();
                            }
                        });
                      }
                });
                // $('#form-vendor').submit();
            <?php } ?>
    }else{
        $('#errorModel').modal('show');
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
      var vendor_ids = [];
      $.each($("input[name='selected[]']:checked"), function(){
          vendor_ids.push($(this).val());
      });
      $.each($("input[name='stores']:checked"), function(){
          stores.push($(this).val());
      });
      $.ajax({
            url: "<?php echo url('vendors/remove'); ?>",
            method: 'post',
            headers: {
                  'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data: {vendor_ids, stores_hq: stores},
            success: function(result){
                  bootbox.alert({
                      size: 'small',
                      title: "Success",
                      message: result,
                      callback: function(){
                          location.reload();
                      }
                  });
            }
      });
  });

  $(document).on('click', '#save_button', function(event) {
    event.preventDefault();
    var edit_url = '<?php echo $data['edit_list']; ?>';


    edit_url = edit_url.replace(/&amp;/g, '&');

      var all_vendor = true;
      var vendor_ids = [];
      $.each($("input[name='selected[]']:checked"), function(){
          vendor_ids.push($(this).val());
      });

    $('.vendors_c').each(function(){
      if($(this).val() == ''){
        alert('Please Enter Vendor Name');
        all_vendor = false;
        return false;
      }else{
        all_vendor = true;
      }
    });

    var validNumerror = [];
    var emailerror = [];

  $('.vendors_phone').each(function(){
      if($(this).val() != ''){
          var phone_num = getValidNumber($(this).val());
          if(phone_num === false){
              validNumerror.push(phone_num + ' is not a valid us number')
          }
      }
  })

  $(".vendors_email").each(function(){
      if($(this).val() != ''){
          var ven_email = validateEmail($(this).val());
          if(ven_email === false){
              emailerror.push("email is not valid")
          }
      }
  })


  if(emailerror.length > 0){
      alert("Please check the all the emails few are not valid");
      return false;
  }
  if(validNumerror.length > 0){
      alert("Please check the all the Phone numbers few are not valid");
      return false;
  }

  if(vendor_ids.length > 0){
          <?php if(session()->get('hq_sid') == 1) { ?>
              $.ajax({
                    url: "<?php echo url('/vendors/duplicatehqvendors'); ?>",
                    method: 'post',
                    headers: {
                          'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    data: {vendor_ids},
                    success: function(result){
                              var popup = '';
                              @foreach (session()->get('stores_hq') as $stores)
                                  if(result.includes({{ $stores->id }})){
                                      var data = '<tr>'+
                                                      '<td>'+
                                                          '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                              '<input type="checkbox" class="checks check custom-control-input editstores" disabled id="editstores" name="editstores" value="{{ $stores->id }}">'+
                                                          '</div>'+
                                                      '</td>'+
                                                      '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Vendor does not exist)</span></td>'+
                                                  '</tr>';
                                              $('#editSelectAllCheckbox').attr('disabled', true);

                                  } else {
                                      var data = '<tr>'+
                                                      '<td>'+
                                                          '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                              '<input type="checkbox" class="checks check custom-control-input editstores"  id="editstores" name="editstores" value="{{ $stores->id }}">'+
                                                          '</div>'+
                                                      '</td>'+
                                                      '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                  '</tr>';
                                  }
                                  popup = popup + data;
                              @endforeach
                      $('#edit_data_stores').html(popup);
                  }
              });
              $('#editModal').modal('show');

          <?php } else{ ?>
              // $('#form-vendor').submit();
              $('#form-vendor').attr('action', edit_url);


              $('#form-vendor').submit();
              $("div#divLoading").addClass('show');
          <?php } ?>
  }else{
      bootbox.alert({
          size: 'small',
          title: "Attension",
          message: "No vendor is selected to edit.",
          callback: function(){}
      });
  }
  });


  function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      return emailReg.test( $email );
  }
  function getValidNumber(value)
  {
      value = $.trim(value).replace(/\D/g, '');

      if (value.substring(0, 1) == '1') {
          value = value.substring(1);
      }
      if (value.length == 10) {
          return value;
      }
      return false;
  }


  var edit_stores = [];

  edit_stores.push("{{ session()->get('sid') }}");
   $('#editSelectAllCheckbox').click(function(){
      if($('#editSelectAllCheckbox').is(":checked")){
          $(".editstores").prop( "checked", true );
      }else{
          $( ".editstores" ).prop("checked", false );
      }
  });

  $("#update_btn").click(function(){
      var edit_url = '<?php echo $data['edit_list']; ?>';
      edit_url = edit_url.replace(/&amp;/g, '&');

      $.each($("input[name='editstores']:checked"), function(){
          edit_stores.push($(this).val());
      });

      $("#hidden_store_hq_val").val(edit_stores.join(","));

      $('#form-vendor').attr('action', edit_url);
      $('#form-vendor').submit();
      $("div#divLoading").addClass('show');
  });


    </script>
    <style>

        #selectAllCheckbox: {
            color: white;
        }
        .disabled,
        #item_ellipsis {
            pointer-events: none;
        }

    </style>

@endsection
