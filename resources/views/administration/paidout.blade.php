@extends('layouts.master')

@section('title')
  Paid Out
@endsection

@section('main-content')

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
        <!-- <h1>Paid Out</h1> -->
      <ul class="breadcrumb">
        <li><a href="https://customer.albertapayments.com/index.php?route=common/dashboard&amp;token=jmgaNjzjMEqu2hUXpd1x5TiaxI9n1lt2">Home</a></li>
        <li><a href="https://customer.albertapayments.com/index.php?route=administration/paid_out&amp;token=jmgaNjzjMEqu2hUXpd1x5TiaxI9n1lt2">Paid Out</a></li>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    @if (session()->has('message'))
      <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>      
    @endif

    @if (session()->has('error'))
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{session()->get('error')}}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>      
      @endif

    <div id='errorDiv'>
    </div>
    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
          <i class="fa fa-exclamation-circle"></i>{{$error}}
        @endforeach
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div> 
    @endif

    <div class="panel panel-default">
      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i> Paid Out</h3>
    </div>

    <div class="panel-body">

      <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
              <div class="">
                  <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
                  <button type="button" onclick="addPaidOut();" data-toggle="tooltip" title="Add New" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</button> 
                  <button type="button" class="btn btn-danger" id="paid_delete" onclick="myFunction()" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
              </div>
          </div>
      </div>
      <div class="clearfix"></div>
      <form action="/paidoutsearch" method="post" id="form_paid_out_search">
        @csrf
        <input type="hidden" name="searchbox" id="ipaidoutid">
        <div class="row">
          <div class="col-md-12">
            <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
            <input type="text" name="autocomplete-product" class="form-control ui-autocomplete-input" placeholder="Search Paid Out..." id="autocomplete-product" autocomplete="off">
          </div>
        </div>
      </form>
        <br>
      <form action="" method="post" enctype="multipart/form-data" id="form-paid-out">
        @csrf
        <input type="hidden" name="MenuId" value="">
        <div class="table-responsive">
          <table id="paid_out" class="table table-bordered table-hover" style="width:50%;">
            <thead>
              <tr>
                <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"></th>
                <th style="" class="text-left">Paid Out</th>
                <th class="text-left">Status</th>
              </tr>
            </thead>
            <tbody id="searchData">
              @foreach($paidout as $paidouts)
                <tr id="paidout">
                  <td data-order="102" class="text-center">
                    <span style="display:none;">{{$paidouts->ipaidoutid}}</span>
                    <input type="checkbox" name="selected[]" id="paidout[{{$paidouts->ipaidoutid}}][select]" value="{{$paidouts->ipaidoutid}}">
                  </td>
        
                  <td class="text-left">
                    <span style="display:none;">{{$paidouts->vpaidoutname}}</span>
                    <input type="text" maxlength="100" class="editable paidouts_c" name="paidout[{{$paidouts->ipaidoutid}}][vpaidoutname]" id="paidout[{{$paidouts->ipaidoutid}}][vpaidoutname]" value="{{$paidouts->vpaidoutname}}" onclick="">
                    <input type="hidden" name="paidout[{{$paidouts->ipaidoutid}}][ipaidoutid]" value="{{$paidouts->vpaidoutname}}">
                  </td>
        
                  <td class="text-left">
                    <select name="paidout[{{$paidouts->ipaidoutid}}][estatus]" id="paidout[{{$paidouts->ipaidoutid}}][estatus]" class="form-control status_c" onchange="">
                      @if($paidouts->estatus == 'Active')
                        <option value="Active" selected="selected">Active</option>
                        <option value="Inactive">Inactive</option>
                        @else
                        <option value="Active">Active</option>
                        <option value="Inactive" selected="selected">Inactive</option>
                      @endif

                    </select>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          {{$paidout->links()}}
        </div>
      </form>
        <div class="row">
          <div class="col-sm-6 text-left"></div>
          <div class="col-sm-6 text-right">Showing 1 to 3 of 3 (1 Pages)</div>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')


<script type="text/javascript">

    function addPaidOut() {
        $('#addModal').modal('show');
    }
</script>

<!-- Add Model -->

<div class="modal fade in" id="addModal" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Add New paid Out</h4>
      </div>
      <div class="modal-body">
          
        <form action="/addpaidout" method="post" id="add_new_form">
          @csrf
          <input type="hidden" name="Id" value="0">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">  
                  <input name="vpaidoutname" maxlength="100" id="add_vpaidoutname" class="form-control" autocomplete="off">
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Status</label>
                </div>
                <div class="col-md-10">  
                  <select name="paidoutestatus" class="form-control" >
                    <option value="Active" selected="selected">Active</option>
                    <option value="Inactive">Inactive</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 text-center">
              <input class="btn btn-success" type="submit" value="Save">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="/javascript/bootbox.min.js" defer=""></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>


<script type="text/javascript">

  $(document).on('submit', 'form#add_new_form', function(event) {
    
    $("div#divLoading").addClass('show');

    $('#addModal').modal('hide');
    $('.modal-backdrop').hide();

    if($('form#add_new_form #add_vpaidoutname').val() == ''){
      // alert('Please enter name!');
       bootbox.alert({ 
         size: 'small',
         title: "Attention", 
         message: "Please enter name!", 
         callback: function(){}
       });

      $("div#divLoading").removeClass('show');
        return false;
    }

    // if($('form#add_new_form #add_vpaidoutname').val() == ''){
    //   // alert('Please enter name!');
    //   bootbox.alert({ 
    //      size: 'small',
    //      title: "Attention", 
    //      message: "Please Enter Status!", 
    //      callback: function(){}
    //   });
    //   $("div#divLoading").removeClass('show');
    //   return false;
    // }

  });

</script>

<script type="text/javascript">
  $(document).ready(function($) {
    $("div#divLoading").addClass('show');
  });

  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>

<!-- Save data -->

<script type="text/javascript">

  $(document).on('click', '.paidouts_c, .status_c', function(e){
    e.preventDefault();
    let selectId = $(this).attr('id').replace("vpaidoutname", "select");
    //console.log(selectId);
    let selectStatus = $(this).attr('id').replace("estatus", "select");
    //console.log(selectStatus);
    
    document.getElementById(selectId).setAttribute('checked','checked');
    document.getElementById(selectStatus).setAttribute('checked','checked');

  });

  $(document).on('click','#save_button', function(e){

    e.preventDefault();

    $("div#divLoading").addClass('show');

    var avArr = [];

    $("#paidout input[type=checkbox]:checked").each(function () {

      var id = $(this).val();
      var name = $(this).closest('tr').find('.paidouts_c').val();
      var status = $(this).closest('tr').find('.status_c').val();
    //   console.log(id);
    //   console.log(name); 
    //   console.log(status); 
      avArr.push({
        ipaidoutid: id,
        vpaidoutname:name,
        estatus: status
            
      });
    });
    
    if(avArr.length < 1){
        bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: "You did not select anything", 
            callback: function(){location.reload(true);}
        });
        $("div#divLoading").removeClass('show');
        return false;
    }

    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        url: '/updatepaidout',
        contentType: 'application/json',
        data: JSON.stringify(avArr) // access in body
    }).success(function (e) {
        // console.log('SUCCESS');
        // console.log(e);
        location.reload();
    }).fail(function (msg) {
        
      //console.log('FAIL');
        let mssg = '<div class="alert alert-danger">';
        
        //console.log(msg);
        let errors = msg.responseJSON;
        //console.log(errors);

        $.each(errors, function(k, err){
        //   console.log(err);
          $.each(err, function(key, error){
            // console.log(error);
            mssg += '<p><i class="fa fa-exclamation-circle"></i>'+error+"</p>";
          });
        });

        mssg += '</div>';
        
        bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: mssg, 
            callback: function(){location.reload(true);}
        });
        
        $("div#divLoading").removeClass('show');

    }).done(function (msg) {
        //console.log('DONE');
        $("div#divLoading").removeClass('show');

    });

  });

  // Search Code

  $(document).on('submit', '#form_paid_out_search', function (e) {
     
    e.preventDefault();

  });

  $(document).on('keyup', '#autocomplete-product', function (e) {
    e.preventDefault();

      var q = $(this).val();
      //console.log(q);
      if(q.length != 0){
          if(q.length < 3){
          return false;
        }else{
          $('#divLoading').addClass('show');
        }
      }else{
        $('#divLoading').addClass('show');
      }

      $.ajax({
          type: 'POST',
          dataType: 'json',
          url: '/paidoutsearch',
          headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
          data: {
              q: q
          },
          success: function (paidouT) {
              // Do some nice animation to show results
              //let ageVeri = data['ageveri'];

              let html = '';
              $.each(paidouT.paidout, function(k, v){
              // console.log(v);
              html += '<tr id="paidout">'
              html +='<td data-order="102" class="text-center">';
              html +='<span style="display:none;">'+v.ipaidoutid+'</span>';
              html +='<input type="checkbox" name="selected[]" id="paidout['+v.ipaidoutid+'][\'select\']" value="'+v.ipaidoutid+'">';
              html +='</td>';
              html +='<td class="text-left">';
              html +='<span style="display:none;">'+v.vpaidoutname+'</span>';
              html +='<input type="text" maxlength="100" class="editable paidouts_c" name="paidout['+v.ipaidoutid+'][\'vpaidoutname\']" id="paidout['+v.ipaidoutid+'][\'vpaidoutname\']" value="'+v.vpaidoutname+'">';
              html +='<input type="hidden" name="paidout['+v.ipaidoutid+'][\ipaidoutid\]" value="'+v.vpaidoutname+'">';
              html +='</td>';
              html +='<td class="text-left">';
              html +='<select name="paidout['+v.ipaidoutid+'][\'estatus\']" id="paidout['+v.ipaidoutid+'][\'estatus\']" class="form-control status_c" onchange="">';
              html +='<option value="Active" selected="selected">Active</option>';
              html +='<option value="Inactive">Inactive</option>';
              html +='</select>';
              html +='</td>';
              html +='</tr>';

              });

              $('#searchData').html(html);
              $('#divLoading').removeClass('show');
          },
          done: function(){
            $('#divLoading').removeClass('show');
          }
      });
  });

</script>


<!-- Delete Shelfname -->

<script>
  function myFunction() {
    var result = confirm("Want to delete?");
    if (result) {

      $(document).on('click', '#paid_delete', function(event) {
        event.preventDefault();
        var delete_aisle_url = 'deletepaidout';
        delete_aisle_url = delete_aisle_url.replace(/&amp;/g, '&');
        var data = [];

        if($("input[name='selected[]']:checked").length == 0){
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: 'Please Select paidout to Delete!', 
            callback: function(){}
          });
          return false;
        }

        
        $("input[name='selected[]']:checked").each(function (i)
        {
          data.push(
            {ipaidoutid : parseInt($(this).val())}
          );
        });

        let input = {'input': data};
        //console.log(input);
        $("div#divLoading").addClass('show');

        $.ajax({
            url : delete_aisle_url,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify(input),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
          success: function(data) {
            //console.log('Success');

            if(data.status == 0){
              //console.log('status');
              $('#success_msg').html('<strong>'+ data.success +'</strong>');
              $("div#divLoading").removeClass('show');
              $('#successModal').modal('show');

              setTimeout(function(){
                  $('#successModal').modal('hide');
                  window.location.reload();
              }, 2000);
            }else{

              $('#error_msg').html('<strong>'+ data.error +'</strong>');
              $("div#divLoading").removeClass('show');
              $('#errorModal').modal('show');

            }


          },
          error: function(xhr) { // if error occured
            var  response_error = $.parseJSON(xhr.responseText); //decode the response array
            
            var error_show = '';

            if(response_error.error){
              error_show = response_error.error;
            }else if(response_error.validation_error){
              error_show = response_error.validation_error[0];
            }

            $('#error_alias').html('<strong>'+ error_show +'</strong>');
            $('#errorModal').modal('show');
            return false;
          }
        });
      });
    }
  }
</script>



@endsection