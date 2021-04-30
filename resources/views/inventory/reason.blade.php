@extends('layouts.master')

@section('title')
  Adjustment Reason
@endsection

@section('main-content')

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      
      <!-- <h1>Adjustment Reason</h1> -->
      <ul class="breadcrumb">
        <li><a href="https://customer.albertapayments.com/index.php?route=common/dashboard&amp;token=bhzqOCxBoEj7SfMf0xISckJCy5Fwn64F">Home</a></li>
        <li><a href="https://customer.albertapayments.com/index.php?route=administration/adjustment_reason&amp;token=bhzqOCxBoEj7SfMf0xISckJCy5Fwn64F">Adjustment Reason</a></li>
      </ul>
    </div>
  </div>
  <div class="container-fluid">

    
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
        <h3 class="panel-title"><i class="fa fa-list"></i> Adjustment Reason</h3>
      </div>

      <div class="panel-body">
        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
            <div class="">
              <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
              <button type="button" onclick="addAdjustmentReason();" data-toggle="tooltip" title="Add New" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</button>  
            </div>
          </div>
        </div>

        <div class="clearfix"></div>

          <form action="/reasonsearch" method="post" id="form_reason_search">
            @csrf
            <input type="hidden" name="searchbox" id="ireasonid">
            <div class="row">
                <div class="col-md-12">
                    <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input type="text" name="autocomplete-product" class="form-control ui-autocomplete-input" placeholder="Search Reason..." id="autocomplete-product" autocomplete="off">
                </div>
            </div>
          </form>
          <br>
        
          <form action="" method="post"  id="form-adjustment-reason">
            @csrf
            <div class="table-responsive">
              <table id="adjustment_reason" class="table table-bordered table-hover" style="width:50%">
                <thead>
                  <tr>
                    <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"></th>
                    <th class="text-left">Name</th>
                    <th class="text-left">Status</th>
                  </tr>
                </thead>
                <tbody id="searchData">
                  @foreach($reason as $reasons)
                  <tr id="adjustment_reason">
                    <td data-order="101" class="text-center">
                      <span style="display:none;">{{$reasons->ireasonid}}</span>
                      <input type="checkbox" name="selected[]" id="adjustment_reason[{{$reasons->ireasonid}}][select]" value="{{$reasons->ireasonid}}">
                    </td>

                    <td class="text-left">
                      <span style="display:none;">{{$reasons->vreasonename}}</span>
                      <input type="text" maxlength="50" class="editable adjustment_reason" name="adjustment_reason[{{$reasons->ireasonid}}][vreasonename]" id="adjustment_reason[{{$reasons->ireasonid}}][vreasonename]" value="{{$reasons->vreasonename}} " onclick="">
                      <input type="hidden" name="adjustment_reason[{{$reasons->ireasonid}}][ireasonid]" value="{{$reasons->vreasonename}}">
                    </td>
                    <td class="text-left">
                      <span>Active</span>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              {{$reason->links()}}
            </div>
          </form>
          <!--<div class="row">-->
          <!--  <div class="col-sm-6 text-left"></div>-->
          <!--  <div class="col-sm-6 text-right">Showing 1 to 2 of 2 (1 Pages)</div>-->
          <!--</div>-->
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">

  function addAdjustmentReason() {
    $('#addModal').modal('show');
  }
</script>

<!-- Add Model -->

<div class="modal fade in" id="addModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Add New Reason</h4>
      </div>
      <div class="modal-body">
        <form action="/addreason" method="post" id="add_new_form">
          @csrf
          <input type="hidden" name="adjustment_reason[0][ireasonid]" value="0">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">  
                  <input name="vreasonename" maxlength="50" name="adjustment_reason[0][vreasonename]" id="add_vreasonename" class="form-control" autocomplete="off">
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

<!-- Add New form -->

<script type="text/javascript">
  $(document).on('submit', 'form#add_new_form', function(event) {
    $("div#divLoading").addClass('show');

    $('#addModal').modal('hide');
    $('.modal-backdrop').hide();

    if($('form#add_new_form #add_vreasonename').val() == ''){
      ///alert('Please enter Reason Name!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please enter Reason Name!", 
        callback: function(){}
      });

      $("div#divLoading").removeClass('show');
       return false;
    }
    
  });
</script>

<script src="/javascript/bootbox.min.js" defer=""></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>


<!-- Save data -->

<script type="text/javascript">
    
    $(document).on('click', '.adjustment_reason', function(e){
      e.preventDefault();
      let selectId = $(this).attr('id').replace("vreasonename", "select");
     // console.log(selectId);
      document.getElementById(selectId).setAttribute('checked','checked');

    });

    $(document).on('click','#save_button', function(e){

      e.preventDefault();

      $("div#divLoading").addClass('show');

      var avArr = [];

      $("#adjustment_reason input[type=checkbox]:checked").each(function () {

        var id =$(this).val();
        var name = $(this).closest('tr').find('.adjustment_reason').val();
         
        avArr.push({
          ireasonid: id,
          vreasonename: name
              
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
          url: '/updatereason',
          contentType: 'application/json',
          datatype: 'json',
          data: JSON.stringify(avArr) // access in body
      }).success(function (e) {
          //console.log('SUCCESS');
          //console.log(e);
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
         // console.log('DONE');
          $("div#divLoading").removeClass('show');

      });

    });

      // Serch Code

    $(document).on('submit', '#form_reason_search', function (e) {
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
            url: '/reasonsearch',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: {
                q: q
            },
            success: function (aislE) {
                // Do some nice animation to show results
                //let ageVeri = data['ageveri'];

                let html = '';
                $.each(aislE.reason, function(k, v){
                  //console.log(v);
                  //console.log('test');
                 html +='<tr id="adjustment_reason">';
                 html +='<td data-order="101" class="text-center">';
                 html +='<span style="display:none;">'+v.ireasonid+'</span>';
                 html +='<input type="checkbox" name="selected[]" id="adjustment_reason['+v.ireasonid+'][\'select\']" value="'+v.ireasonid+'">';
                 html +='</td>';
                 html +='<td class="text-left">';
                 html +='<span style="display:none;">'+v.vreasonename+'</span>';
                 html +='<input type="text" maxlength="50" class="editable adjustment_reason" name="adjustment_reason['+v.ireasonid+'][\'vreasonename\']" id="adjustment_reason['+v.ireasonid+'][\'vreasonename\']" value="'+v.vreasonename+' ">';
                 html +='<input type="hidden" name="adjustment_reason['+v.ireasonid+'][\'ireasonid\']" value="'+v.vreasonename+'">';
                 html +='</td>';
                 html +='<td class="text-left">';
                 html +='<span>Active</span>';
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


@endsection