@extends('layouts.layout')

@section('title')
  Aisle
@endsection

@section('main-content')
<div id="content">
  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
      <div class="container">
          <div class="collapse navbar-collapse" id="main_nav">
              <div class="menu">
                  <span class="font-weight-bold text-uppercase" > Aisle</span>
              </div>
              <div class="nav-submenu">
                  <button type="button" id="save_button"  class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                  <button type="button" onclick="addAisle();" data-toggle="tooltip" class="btn btn-gray headerblack  buttons_menu " href="#"> <i class="fa fa-plus"></i>&nbsp;&nbsp; Add New</button>
                  <button type="button" id="aisle_delete" onclick="myFunction()"  class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> <i class="fa fa-trash"></i>&nbsp;&nbsp; Delete</button>
              </div>
          </div> <!-- navbar-collapse.// -->
      </div>
  </nav>

  <section class="section-content py-6">
      <div class="container">
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
        <div id='errorDiv'></div>
        @if ($errors->any())
          <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
              <i class="fa fa-exclamation-circle"></i>{{$error}}
            @endforeach
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div> 
        @endif

        <div class="panel panel-default">
          
          <div class="panel-body">

            <form action="/aislesearch" method="post" id="form_aisle_search">
              @csrf
              <input type="hidden" name="searchbox" id="Id">
              <div class="row">
                <div class="col-md-12">
                  <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                  <input type="text" style="height: 33px; font-size: 12 !important; font-weight: 600" name="autocomplete-product" class="form-control ui-autocomplete-input" placeholder="Search Aisle..." id="autocomplete-product" autocomplete="off">
                </div>
              </div>
            </form>
              <br>
          
            <form action="" method="post" enctype="multipart/form-data" id="form-aisle">
              @csrf
              <div class="table-responsive">
                <table id="aisleTable" class="text-center table  table-hover" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                  <thead style="background-color: #286fb7!important;">
                    <tr>
                      <th style="width: 1px;color:black;" class="text-center">
                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                      </th>
                      <!-- previous code <td style="width: 1px;" class="text-center">
                      <input type="checkbox"  onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td> -->
                        <th class="col-xs-1 headername text-uppercase text-light" data-field="supplier_code">Name</th>

                      <!-- <td class="text-center">Action</td> -->
                    </tr>
                  </thead>

                  <tbody id="searchData">
                    @foreach($aisle as $aisles)
                      <tr>
                        <td class="text-center">
                          <input type="checkbox" class="checkbox_c" name="selected[]" id="aisle[{{$aisles->Id}}][select]"  value="{{$aisles->Id}}">
                        </td>
                        <td class="text-center">
                          <span style="display:none;">{{$aisles->aislename}}</span>
                          <input type="text" style="border:none;" maxlength="45" class="editable aisle_c" name="aisle[{{$aisles->Id}}][{{$aisles->aislename}}]" id="aisle[{{$aisles->Id}}][aislename]" value="{{$aisles->aislename}}" onclick="">
                          <input type="hidden" name="aisle[{{$aisles->Id}}][Id]" value="{{$aisles->Id}}">
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                {{$aisle->links()}}
              </div>
            </form>
          </div>
        </div>
      </div>
  </section>
</div>

<div class="modal fade" id="successModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success text-center">
          <p id="success_msg"></p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="warningModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning text-center">
          <p id="warning_msg"></p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="errorModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger text-center">
          <p id="error_msg"></p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page-script')

<script type="text/javascript">
  function addAisle(){
    $('#addModal').modal('show');
  }
</script>

<div class="modal fade" id="addModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title">Add New Aisle</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
      </div>
      <div class="modal-body">
        <form action="/addaisle" method="post" id="add_new_form">
          @csrf
          <input type="hidden" name="Id" value="0">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">  
                  <input name="aislename" maxlength="45" id="add_aislename" class="form-control" autocomplete="off">
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 text-center">
              <input class="btn btn-success" type="submit" value="Save" id="saveAisleButton">
              <button type="button" class="btn  btn-outline-primary" data-dismiss="modal">Cancel</button>
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
    
    $(document).on('click', '.aisle_c', function(e){
      e.preventDefault();
      let selectId = $(this).attr('id').replace("aislename", "select");

      document.getElementById(selectId).setAttribute('checked','checked');

    });

    $(document).on('click','#save_button', function(e){
      e.preventDefault();
      $("div#divLoading").addClass('show');
      var avArr = [];
      $("#aisleTable input[type=checkbox]:checked").each(function () {
        var id =$(this).val();
        var name = $(this).closest('tr').find('.aisle_c').val();
        avArr.push({
          id: id,
          aislename: name
        });
      });
      
      if(avArr.length < 1){
          $('#warning_msg').html("You did not select anything");
          $("div#divLoading").removeClass('show');
          $('#warningModal').modal('show');
          return false;
      }

      $.ajax({
          type: 'POST',
          headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
          url: '/updateaisle',
          contentType: 'application/json',
          datatype: 'json',
          data: JSON.stringify(avArr), // access in body
          success: function(result) {
                location.reload();
          },
          error : function (msg) {
              let mssg = '<div class="alert alert-danger">';
              let errors = msg.responseJSON;
              $.each(errors, function(k, err){
                $.each(err, function(key, error){
                  mssg += '<p><i class="fa fa-exclamation-circle"></i>'+error+"</p>";
                });
              });
              mssg += '</div>';
              $('#error_msg').html(mssg);
              $("div#divLoading").removeClass('show');
              $('#errorModal').modal('show');
          }
      });

    });

      // Serch Code

    $(document).on('submit', '#form_aisle_search', function (e) {
        e.preventDefault();
    });

    $(document).on('keyup', '#autocomplete-product', function (e) {
        e.preventDefault();

        var q = $(this).val();

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
            url: '/aislesearch',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: {
                q: q
            },
            success: function (aislE) {
                // Do some nice animation to show results
                //let ageVeri = data['ageveri'];

                let html = '';
                $.each(aislE.aisle, function(k, v){

                    html +=  '<tr>';
    
                    html +=  '<td class="text-center">';
                    html +=  '<input type="checkbox" name="selected[]" id="aisle['+v.Id+'][\'select\']" class="checkboxId" value="'+v.Id+'">';
                    html +=  '</td>';
                    
                    html +=  '<td class="text-left">';
                    html +=  '<span style="display:none;">'+v.aislename+'</span>';
                    html +=  '<input type="text" style="border:none;" maxlength="45" class="editable aisle_c" name="aisle['+v.Id+'][\'aislename\']" id="aisle['+v.Id+'][\'aislename\']" value="'+v.aislename+'" onclick="document.getElementById(\'aisle['+v.Id+'][\'select\']\').setAttribute(\'checked\',\'checked\');">';
                    html +=  '<input type="hidden" name="aisle['+v.Id+'][\'Id\']" value="'+v.Id+'">';
                    html +=  '</td>';
    
                    html +=  '</tr>';
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
      if($('form#add_new_form #add_aislename').val() == ''){
          $('#warning_msg').html("Please enter Aisle Name!");
          $("div#divLoading").removeClass('show');
          $('#warningModal').modal('show');
          return false;
      }
  });
</script>

<script type="text/javascript">
  $(document).ready(function($) {
    $("div#divLoading").addClass('show');
  });

</script>

<!-- Delete Shelfname -->

<script>
  function myFunction() {
    var result = confirm("Want to delete?");
    if (result) {

      $(document).on('click', '#aisle_delete', function(event) {
        event.preventDefault();
        var delete_aisle_url = 'deleteaisle';
        delete_aisle_url = delete_aisle_url.replace(/&amp;/g, '&');
        var data = [];

        if($("input[name='selected[]']:checked").length == 0){
         
          $('#warning_msg').html("Please Select Aisle to Delete!");
          $("div#divLoading").removeClass('show');
          $('#warningModal').modal('show');
          
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
              $('#success_msg').html('<strong>Asile Deleted successfully</strong>');
              $("div#divLoading").removeClass('show');
              $('#successModal').modal('show');

              setTimeout(function(){
                  $('#successModal').modal('hide');
                  window.location.reload();
              }, 3000);
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