@extends('layouts.master')

@section('title')
  Shelf
@endsection

@section('main-content')

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!-- <h1>Shelf</h1> -->
      <ul class="breadcrumb">
          <li><a href="https://customer.albertapayments.com/index.php?route=common/dashboard&amp;token=Q6rNoAPw3h16aA0Z63BxtRQg2zBe3MIm">Home</a></li>
          <li><a href="https://customer.albertapayments.com/index.php?route=administration/shelf&amp;token=Q6rNoAPw3h16aA0Z63BxtRQg2zBe3MIm">Shelf</a></li>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> Shelf</h3>
      </div>
      <div class="panel-body">
        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
            <div class="">
              <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
              <button type="button" onclick="addShelf();" data-toggle="tooltip" title="Add New" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</button>  
              <button type="button" class="btn btn-danger" id="shelf_delete" onclick="myFunction()" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
            </div>
          </div>
        </div>

        <div class="clearfix"></div>
        <form action="/shelfsearch" method="post" id="form_shelf_search">
          @csrf
          <input type="hidden" name="searchbox" id="Id">
          <div class="row">
            <div class="col-md-12">
              <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
              <input type="text" name="autocomplete-product" class="form-control ui-autocomplete-input" placeholder="Search Shelf..." id="autocomplete-product" autocomplete="off">
            </div>
          </div>
        </form>
          <br>
        
        <form action="" method="post" enctype="multipart/form-data" id="form-shelf">
          @csrf
          <div class="table-responsive">
            <table id="shelfTable" class="text-center table table-bordered table-hover" style="width:50%;">
              <thead>
                <tr>
                  <td style="width: 1px;color:black;" class="text-center">
                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                  </td>
                  <td style="" class="text-left">Name</td>
                  <!-- <td class="text-center">Action</td> -->
                </tr>
              </thead>
              <tbody id="searchData">
              @foreach($shelf as $shelfs)
                <tr>
                  <td class="text-center">
                    <input type="checkbox" name="selected[]" id="shelf[{{$shelfs->Id}}][select]" value="{{$shelfs->Id}}" >
                  </td>

                  <td class="text-left">
                    <span style="display:none;">{{$shelfs->shelfname}}</span>
                    <input type="text" maxlength="45" class="editable shelf_c" name="shelf[{{$shelfs->Id}}][{{$shelfs->shelfname}}]" id="shelf[{{$shelfs->Id}}][shelfname]" value="{{$shelfs->shelfname}}" onclick="">
                    <input type="hidden" name="shelf[{{$shelfs->Id}}][Id]" value="{{$shelfs->Id}}">
                  </td>                               
                </tr>
              @endforeach
              </tbody>
            </table>
            {{$shelf->links()}}
          </div>
        </form>  
        <!-- <div class="row">
            <div class="col-sm-6 text-left"></div>
            <div class="col-sm-6 text-right">Showing 1 to 1 of 1 (1 Pages)</div>
        </div> -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')

<!-- Add Shelf -->

<script type="text/javascript">
  function addShelf() {
    $('#addModal').modal('show');
  }
</script>

<script type="text/javascript">
    $(document).ready(function($) {
  
      $("div#divLoading").addClass('show');
  
      var token = "1";
      var sid = "2";
            
      $("div#divLoading").removeClass('show');
    });
  
    $(document).on('keypress keyup blur', 'input[name="vzip"],input[name="isequence"]', function(event) {
  
      $(this).val($(this).val().replace(/[^\d].+/, ""));
      if ((event.which < 48 || event.which > 57)) {
          event.preventDefault();
      }
      
    }); 
  
</script>

<div class="modal fade" id="addModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Add New Shelf</h4>
      </div>
      <div class="modal-body">
        <form action="/addshelf" method="post" id="add_new_form">
          @csrf
          <input type="hidden" name="Id" value="0">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">  
                  <input type="text" name="shelfname" maxlength="45" id="add_shelfname" class="form-control" autocontroller="off"> 
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 text-center">
              <input class="btn btn-success" type="submit" value="Save" id="saveShelfButton">
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

<!-- Save data -->

<script type="text/javascript">

  $(document).on('click', '.shelf_c', function(e){
    e.preventDefault();
    let selectId = $(this).attr('id').replace("shelfname", "select");
    //console.log(selectId);
    document.getElementById(selectId).setAttribute('checked','checked');

  });

    
  $(document).on('click','#save_button', function(e){

    e.preventDefault();

    $("div#divLoading").addClass('show');

    var avArr = [];

    $("#shelfTable input[type=checkbox]:checked").each(function () {

      var id =$(this).val();
      var name = $(this).closest('tr').find('.shelf_c').val();
        
      avArr.push({
        Id: id,
        shelfname: name
            
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
        url: '/updateshelf',
        contentType: 'application/json',
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

  $(document).on('submit', '#form_shelf_search', function (e) {
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
          url: '/shelfsearch',
          headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
          data: {
              q: q
          },
          success: function (shelF) {
              // Do some nice animation to show results
              //let ageVeri = data['ageveri'];

              let html = '';
              $.each(shelF.shelf, function(k, v){
                //console.log(v);
              html +='<tr>';
              html +='<td class="text-center">';
              html +='<input type="checkbox" name="selected[]" id="shelf['+v.Id+'][\'select\']" class="checkboxId" value="'+v.Id+'">';
              html +='</td>';

              html +='<td class="text-left">';
              html +='<span style="display:none;">'+v.shelfname+'</span>';
              html +='<input type="text" maxlength="45" class="editable shelf_c" name="shelf['+v.Id+'][\'shelfname\']" id="shelf['+v.Id+'][\'shelfname\']" value="'+v.shelfname+'">';
              html +='<input type="hidden" name="shelf['+v.Id+'][\Id\]" value="'+v.Id+'">';
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

<!-- Add New form -->

<script type="text/javascript">
  $(document).on('submit', 'form#add_new_form', function(event) {
    $("div#divLoading").addClass('show');

    $('#addModal').modal('hide');
    $('.modal-backdrop').hide();

    if($('form#add_new_form #add_shelfname').val() == ''){
      // alert('Please enter Shelf Name!');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please enter Shelf Name!", 
        callback: function(){}
      });

      $("div#divLoading").removeClass('show');
      return false;
    }

    
  });
</script>


<!-- Delete Shelfname -->

<script>
  function myFunction() {
    var result = confirm("Want to delete?");
    if (result) {

      $(document).on('click', '#shelf_delete', function(event) {
        event.preventDefault();
        var delete_aisle_url = 'deleteshelf';
        delete_aisle_url = delete_aisle_url.replace(/&amp;/g, '&');
        var data = [];

        if($("input[name='selected[]']:checked").length == 0){
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: 'Please Select Shelf to Delete!', 
            callback: function(){}
          });
          return false;
        }

        
        $("input[name='selected[]']:checked").each(function (i)
        {
          data.push(
            {id : parseInt($(this).val())}
          );
        });

        let input = {'input': data};
        
        $("div#divLoading").addClass('show');

        $.ajax({
            url : delete_aisle_url,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify(input),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
          success: function(data) {

            if(data.status == 0){
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
