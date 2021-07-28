@extends('layouts.layout')
@section('title')
  Manufaturer
@endsection

@section('main-content')
<div id="content">
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase" style="font-size: 22px"> Manufacturer</span>
                </div>
                <div class="nav-submenu">
                    <button type="button" id="save_button"  class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                    <button type="button" onclick="addManufacturer();" data-toggle="tooltip" class="btn btn-gray headerblack  buttons_menu " href="#"> <i class="fa fa-plus"></i>&nbsp;&nbsp; Add New</button>
                    <button type="button" id="delete_manufacturer_btn" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> <i class="fa fa-trash"></i>&nbsp;&nbsp; Delete</button>
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

          <div id='errorDiv'>
          </div>
          @if ($errors->any())
            <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
                <i class="fa fa-exclamation-circle"></i>{{$error}}<br/>
              @endforeach
              
            </div> 
          @endif

          <div class="panel panel-default">

            <div class="panel-body">
              <form action="/manufacturersearch" method="post" id="form_category_search">
                @csrf
                <input type="hidden" name="searchbox" id="icategoryid">
                <div class="row">
                  <div class="col-md-12">
                    <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                    <input style="height: 33px; font-size: 12px !important; font-weight: 600;" type="text" name="autocomplete-product" class="form-control ui-autocomplete-input" placeholder="Search Manufacturer..." id="autocomplete-product" autocomplete="off">
                  </div>
                </div>
              </form>
              <br>
              
              <form action="" method="post" id="form-manufacturer">
                @csrf
                @method('post')
                
                @if(session()->get('hq_sid') == 1)
                      <input type="hidden" id="edit_hidden_store_hq_val" name="stores_hq" value="">
                  @endif
                <input type="hidden" name="MenuId" value="">
                  <div class="table-responsive">
                    <table id="manufacturer" class="table table-hover" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                      <thead style="background-color: #286fb7!important;" >
                        <tr>

                          <th style="width: 1px;" class="text-center">
                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                          </th>
                          <th class="col-xs-1 headername text-uppercase text-light" data-field="supplier_code">Manufacturer Code</th>
                          <th class="col-xs-1 headername text-uppercase text-light" style="border-bottom-right-radius: 9px; border-top-right-radius: 9px" >Manufacturer Name</th>
                          <th class="text-left" style="display:none;">Sequence</th>
                        </tr>
                      </thead>
                      <tbody id="searchData"> 
                      @foreach($manufacturer as $manufacturers)
                        <tr id="category-row1">
                          <td data-order="1" class="text-center">
                            <span style="display:none;">{{$manufacturers->mfr_id}}</span>
                            <input type="checkbox" class='checkbox_c' name="selected[]" id="manufacturer[{{$manufacturers->mfr_id}}][select]" value="{{$manufacturers->mfr_id}}">
                          </td>
                          <td class="text-left">
                            <span style="display:none;">{{$manufacturers->mfr_code}}</span>
                            <input type="text" style="border:none;" maxlength="50" class="editable mfrCode" name="manufacturer[{{$manufacturers->mfr_id}}][mfr_code]" id="manufacturer[{{$manufacturers->mfr_id}}][mfr_code]" value="{{$manufacturers->mfr_code}}" onclick='document.getElementById("manufacturer[{{$manufacturers->mfr_id}}][select]").setAttribute("checked","checked");'>
                            <input type="hidden" name="manufacturer[{{$manufacturers->mfr_code}}][mfr_id]" value="{{$manufacturers->mfr_id}}">
                          </td>
                      
                          <td class="text-left">
                            <span style="display:none;">{{$manufacturers->mfr_name}}</span>
                            <input type="text" style="border:none;"  maxlength="50" class="editable mfrName" name="manufacturer[{{$manufacturers->mfr_id}}][mfr_name]" id="manufacturer[{{$manufacturers->mfr_id}}][mfr_name]" value="{{$manufacturers->mfr_name}}" onclick='document.getElementById("manufacturer[{{$manufacturers->mfr_id}}][select]").setAttribute("checked","checked");'>
                            <input type="hidden" name="manufacturer[{{$manufacturers->mfr_id}}][mfr_id]" value="{{$manufacturers->mfr_id}}">
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
              </form>
            </div>
          </div>
        </div>
    </section>
</div>

<!-- Add New form -->

<div class="modal fade in" id="addModal" role="dialog">
  <div class="modal-dialog">
       <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title">Add New Manufacturer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
      <div class="modal-body">
        <form action="/addmanufacturer" method="post" id="add_new_form">
          @csrf
          @method('post')
            @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
            @endif
          <input type="hidden" name="Id" value="0">
          <input type="hidden" name="Id" value="0">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">  
                  <input name="mfr_name" maxlength="50" class="form-control" id="add_manufacturer_name" autocomplete="off" >
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
                  <label>Code</label>
                </div>
                <div class="col-md-10">  
                  <input name="mfr_code" maxlength="50" class="form-control" id="add_manufacturer_code" autocomplete="off">
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="button" class="btn btn-success" value="Save" id="saveManufacturerButton">
              <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

 <?php if(session()->get('hq_sid') == 1){ ?>
<!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title">Select the stores in which you want to add the Manufacturer :</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
        
          <div class="modal-body">
             <table class="table" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                <thead id="table_green_header_tag"  style="background-color: #286fb7!important;">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="selectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check  stores" id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">
                                </div>
                            </td>
                            <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="save_btn_manufacturer" class="btn btn-danger" data-dismiss="modal">ADD</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
    
      </div>
    </div>
    
    <div id="EditModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         Modal content
        <div class="modal-content">
          <div class="modal-header">
              <h6 class="modal-title">Select the stores in which you want to Edit the Manufacturer :
                    <span style="color: #03A9F4">(Please Note: If a Manufacturer not exists in any of the stores those Manufacturer will be created)</span>
              </h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
        
          <div class="modal-body">
             <table class="table" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                <thead id="table_green_header_tag"  style="background-color: #286fb7!important;">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="editSelectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody id ="edit_data_stores"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="Edit_btn_manufacturer" class="btn btn-danger" data-dismiss="modal">Update</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    
      </div>
    </div>
    
    <div id="DeleteModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         Modal content
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title">Select the stores in which you want to delete the Manufacturer :</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
        
          <div class="modal-body">
             <table class="table" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                <thead id="table_green_header_tag"  style="background-color: #286fb7!important;">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="deleteSelectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody id="deletecat_stores"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="delete_btn_manufacturer" class="btn btn-danger" data-dismiss="modal">Delete</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
      </div>
    </div>
    
<?php } ?>

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
    function addManufacturer() {
      $('#addModal').modal('show');
    }
</script>
<script src="/javascript/bootbox.min.js" defer=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

<script src="{{ asset('javascript/bootbox.min.js')}}"></script>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">

$("#saveManufacturerButton").click(function(){
    $("#addModal").modal('hide');
    var manu_name = $("#add_manufacturer_name").val();
    var manu_code = $("#add_manufacturer_code").val();
    
    if(manu_name == ''){
        alert("manu_name is required");
    }else if(manu_code == ''){
        alert("manu_code is required");
    }else {
        <?php if(session()->get('hq_sid') == 1){ ?>
            $("#myModal").modal('show'); 
        <?php } else { ?>
            $("#add_new_form").submit();
        <?php } ?>
    }
})

var stores_hq = [];
stores_hq.push("{{ session()->get('sid') }}");
$('#selectAllCheckbox').click(function(){
    if($('#selectAllCheckbox').is(":checked")){
        $(".stores").prop( "checked", true );
    }else{
        $( ".stores" ).prop("checked", false );
    }
});

$('#save_btn_manufacturer').click(function(){
    $.each($("input[name='stores']:checked"), function(){            
        stores_hq.push($(this).val());
    });
    $("#hidden_store_hq_val").val(stores_hq.join(','));
    $("#add_new_form").submit();
});

$("#closeBtn").click(function(){
    $("div#divLoading").removeClass('show');
});

</script>

<script type="text/javascript">
  // $(document).ready(function($) {
  //   $("div#divLoading").addClass('show');
  // });
  // $(window).on('load', function() {
  //     $("div#divLoading").removeClass('show');
  // });
</script>
<!-- save data -->

<script type="text/javascript">
    $(document).on('click', '.mfrCode, .mfrName', function(e){
        e.preventDefault();
        let selectId = $(this).attr('id').replace("mfr_code", "select");
      
        let selectcode = $(this).attr('id').replace("mfr_name", "select");
      
        document.getElementById(selectId).setAttribute('checked','checked');
        document.getElementById(selectcode).setAttribute('checked','checked');

    });

  $(document).on('click','#save_button', function(e){
      
    e.preventDefault();
    $("div#divLoading").addClass('show');
    $('.mfrName').each(function(){
        var allManufactures = [];         
        allManufactures.push($(this).val());
        
      if($(this).val() == ''){
        $('#warning_msg').html("Please Enter Manufacturer Name");
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        all_category = false;
        return false;
      }else{
        all_category = true;
      }
    });
    
    // var subcatids = []; 
    
    $("div#divLoading").addClass('show');
    var avArr = [];
   $("input[name='selected[]']:checked").each(function () {
      var id = $(this).val();
      var code = $(this).closest('tr').find('.mfrCode').val();
      var name = $(this).closest('tr').find('.mfrName').val();
      avArr.push({
        mfr_id : id,
        mfr_code: code,
        mfr_name: name
            
      });
    });
    if(avArr.length < 1){
        $('#warning_msg').html("You did not select anything");
        $("div#divLoading").removeClass('show');
        $('#warningModal').modal('show');
        
        return false;
    }else{
        <?php if(session()->get('hq_sid') == 1){ ?>
            $.ajax({
                url : "<?php echo url('/manufacturer/duplicatemanufacurer'); ?>",
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                data : JSON.stringify(avArr),
                type : 'POST',
                contentType: "application/json",
                dataType: 'json',
                success: function(result) {
                    var popup = '';
                    @foreach (session()->get('stores_hq') as $stores)
                        if(result.includes({{ $stores->id }})){
                            var data = '<tr>'+
                                            '<td>'+
                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                    '<input type="checkbox" class="checks check  editstores" disabled id="hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                '</div>'+
                                            '</td>'+
                                            '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
                                        '</tr>';
                                    $('#editSelectAllCheckbox').attr('disabled', true);
                              
                        } else {
                            var data = '<tr>'+
                                            '<td>'+
                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                    '<input type="checkbox" class="checks check  editstores"  id="else_hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
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
            $("#EditModal").modal('show');
        <?php } else { ?>
            var request = $.ajax({
                url: "<?php echo url('/manufacturer/edit_list'); ?>",
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                contentType: 'application/json',
                data: JSON.stringify({data:avArr}),
                success: function(result) {
                    var avArr = [];
                    location.reload();
                },
                error: function(msg){
                    $('#error_msg').html('The manufacturer name is already taken !');
                    $("div#divLoading").removeClass('show');
                    $('#errorModal').modal('show');
                    setTimeout(function(){
                      $('#errorModal').modal('hide');
                      window.location.reload();
                    }, 2000);
                }
            });
        <?php } ?>
    }
  
  });
  
   var edit_stores = [];
    edit_stores.push("{{ session()->get('sid') }}");
    $('#editSelectAllCheckbox').click(function(){
        if($('#editSelectAllCheckbox').is(":checked")){
            $(".editstores").prop( "checked", true );
        }else{
            $( ".editstores" ).prop("checked", false );
        }
    });
    
    $('#Edit_btn_manufacturer').click(function(){
        $.each($("input[name='editstores']:checked"), function(){            
            edit_stores.push($(this).val());
        });
        var avArr = [];
        $("input[name='selected[]']:checked").each(function () {
          var id = $(this).val();
          var code = $(this).closest('tr').find('.mfrCode').val();
          var name = $(this).closest('tr').find('.mfrName').val();
          avArr.push({
            mfr_id : id,
            mfr_code: code,
            mfr_name: name
                
          });
        });
        
         var request = $.ajax({
                url: "<?php echo url('/manufacturer/edit_list'); ?>",
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                contentType: 'application/json',
                data: JSON.stringify({data: avArr, stores_hq: edit_stores}),
                success: function(msg){
                    avArr = [];
                    location.reload();
                },
                error: function( msg ) {
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

  $(document).on('submit', '#form_category_search', function (e) {
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
          url: '/manufacturersearch',
          headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
          data: {
              q: q
          },
          success: function (shelF) {
              // Do some nice animation to show results
              //let ageVeri = data['ageveri'];
              let html = '';
              $.each(shelF.manufacturer, function(k, v){
            
              html +='<tr id="category-row1">';
              html +='<td data-order="1" class="text-center">';
              html +='<input type="checkbox" name="selected[]" id="manufacturer['+v.mfr_id+'][\'select\']" class="checkbox_c" value="'+v.mfr_id+'">';
              html +='</td>';
              html +='<td class="text-left">';
              html +='<span style="display:none;">'+v.mfr_code+'</span>';
              html +='<input type="text" style="border:none" maxlength="50" class="editable mfrCode" name="manufacturer['+v.mfr_id+'][\'mfr_code\']" id="manufacturer['+v.mfr_id+'][\'mfr_code\']" value="'+v.mfr_code+'">';
              html +='<input type="hidden" name="manufacturer['+v.mfr_code+'][\mfr_id\]" value="'+v.mfr_id+'">';
              html += '</td>';
                
              html+='<td class="text-left">';
              html+='<span style="display:none;">'+v.mfr_name+'</span>';
              html+='<input type="text"  style="border:none" maxlength="50" class="editable mfrName" name="manufacturer['+v.mfr_id+'][\'mfr_name\']" id="manufacturer['+v.mfr_id+'][\'mfr_name\']" value="'+v.mfr_name+'">';
              html+='<input type="hidden" name="manufacturer['+v.mfr_id+'][\mfr_id\]" value="'+v.mfr_id+'">';
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

<!-- Delete Manufacturer -->

<script>
 
    $(document).on('click', '#delete_manufacturer_btn', function(event) {
        event.preventDefault();
        if($("input[name='selected[]']:checked").length == 0){
         
            $('#warning_msg').html("Please Select Manufacturer to Delete!");
            $("div#divLoading").removeClass('show');
            $('#warningModal').modal('show');
            return false;
        }
        var avArr = [];
        $("#manufacturer input[type=checkbox]:checked").each(function () {
          var id =$(this).val();
          var name = $(this).closest('tr').find('.mfrName').val();
          var code = $(this).closest('tr').find('.mfrCode').val();
          avArr.push({
            mfr_id:id,
            mfr_name:name,
            mfr_code:code
          });
        });
        $("div#divLoading").addClass('show');
        <?php if(session()->get('hq_sid') == 1){ ?>
            $.ajax({
                  url: "<?php echo url('/manufacturer/duplicatemanufacurer'); ?>",
                  method: 'post',
                  headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                  },
                  data: JSON.stringify(avArr),
                  success: function(result){
                            var popup = '';
                            @foreach (session()->get('stores_hq') as $stores)
                                if(result.includes({{ $stores->id }})){
                                    var data = '<tr>'+
                                                    '<td>'+
                                                        '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                            '<input type="checkbox" class="checks check  deletestores" disabled id="hq_sid_{{ $stores->id }}" name="deletestores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Manufacturer does not exist)</span></td>'+
                                                '</tr>';
                                            $('#selectAllCheckbox').attr('disabled', true);
                                      
                                } else {
                                    var data = '<tr>'+
                                                    '<td>'+
                                                        '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                            '<input type="checkbox" class="checks check  deletestores"  id="else_hq_sid_{{ $stores->id }}" name="deletestores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                '</tr>';
                                }
                                popup = popup + data;
                            @endforeach
                    $('#deletecat_stores').html(popup);
                }
            });
            $("#DeleteModal").modal('show');
            
        <?php } else { ?>
        
            $.ajax({
                url : "<?php echo url('/deletemanufacturer'); ?>",
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data : JSON.stringify(avArr),
                type : 'POST',
                contentType: "application/json",
                dataType: 'json',
                success: function(data) {
                    $('#success_msg').html('<strong>Manufacturer Deleted Successfully</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#successModal').modal('show');
                    setTimeout(function(){
                      $('#successModal').modal('hide');
                      var avArr = [];
                      window.location.reload();
                    }, 3000);
                },
                error: function(xhr) { 
            
                      $('#error_msg').html('<strong>Manufacturer Deletion failed</strong>');
                      $("div#divLoading").removeClass('show');
                      $('#errorModal').modal('show');
                      
                      setTimeout(function(){
                          $('#errorModal').modal('hide');
                          window.location.reload();
                      }, 4000);
                }
            });

            // $("input[name='selected[]']:checked").each(function (i){
            //   data.push(
            //     {mfr_id : parseInt($(this).val())}
            //   );
            // });
            // let input = {'input': data};
            // $("div#divLoading").addClass('show');
            // $.ajax({
            //     url : delete_aisle_url,
            //     headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            //     data : JSON.stringify(input),
            //     type : 'POST',
            //     contentType: "application/json",
            //     dataType: 'json',
            //   success: function(data) {
    
            //     if(data.status == 0){
            //       $('#success_msg').html('<strong>'+ data.success +'</strong>');
            //       $("div#divLoading").removeClass('show');
            //       $('#successModal').modal('show');
    
            //       setTimeout(function(){
            //           $('#successModal').modal('hide');
            //           window.location.reload();
            //       }, 2000);
            //     }else{
    
            //       $('#error_msg').html('<strong>'+ data.error +'</strong>');
            //       $("div#divLoading").removeClass('show');
            //       $('#errorModal').modal('show');
    
            //     }
            //   },
            //   error: function(xhr) { // if error occured
            //     var  response_error = $.parseJSON(xhr.responseText); //decode the response array
                
            //     var error_show = '';
    
            //     if(response_error.error){
            //       error_show = response_error.error;
            //     }else if(response_error.validation_error){
            //       error_show = response_error.validation_error[0];
            //     }
    
            //     $('#error_alias').html('<strong>'+ error_show +'</strong>');
            //     $('#errorModal').modal('show');
            //     return false;
            //   }
            // });
                
        <?php } ?>
        
    });
    
var deletestores = [];
deletestores.push("{{ session()->get('sid') }}");
$('#deleteSelectAllCheckbox').click(function(){
    if($('#deleteSelectAllCheckbox').is(":checked")){
        $(".deletestores").prop( "checked", true );
    }else{
        $( ".deletestores" ).prop("checked", false );
    }
});

$('#delete_btn_manufacturer').click(function(){
    var avArr = [];
    $.each($("input[name='deletestores']:checked"), function(){            
        deletestores.push($(this).val());
    });
    $("#manufacturer input[type=checkbox]:checked").each(function () {
        var id =$(this).val();
        var name = $(this).closest('tr').find('.mfrName').val();
        var code = $(this).closest('tr').find('.mfrCode').val();
        avArr.push({
          mfr_id:id,
          mfr_name:name,
          mfr_code:code,
        });
    });
    $.ajax({
        url : "<?php echo url('/deletemanufacturer'); ?>",
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        data : JSON.stringify({data:avArr, stores_hq:deletestores}),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
        success: function(data) {
            $('#success_msg').html('<strong>Manufacturer Deleted successfully</strong>');
            $("div#divLoading").removeClass('show');
            $('#successModal').modal('show');
            setTimeout(function(){
                $('#successModal').modal('hide');
                window.location.reload();
                avArr = [];
            }, 3000);
            
        },
        error: function(xhr) { 
            $('#error_msg').html('<strong>Manufacturer deletion failed</strong>');
            $("div#divLoading").removeClass('show');
            $('#errorModal').modal('show');
              
            setTimeout(function(){
                $('#errorModal').modal('hide');
                window.location.reload();
            }, 4000);
        }
    });
});

    

</script>

@endsection