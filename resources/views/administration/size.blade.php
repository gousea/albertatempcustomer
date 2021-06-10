@extends('layouts.layout')
@section('title')
  Size
@endsection
@section('main-content')
<div id="content">
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Size </span>
                </div>
                <div class="nav-submenu">
                    <button type="button" id="save_button"  class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                    <button type="button" onclick="addSize();" data-toggle="tooltip" class="btn btn-gray headerblack  buttons_menu " > <i class="fa fa-plus"></i>&nbsp;&nbsp; Add New</button>
                    <button type="button" id="size_delete"  title="Delete" class="btn btn-danger buttonred buttons_menu basic-button-small" > <i class="fa fa-trash"></i>&nbsp;&nbsp; Delete</button>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
    <section class="section-content py-6">
      
        <div class="container">

        @if(session()->has('message'))
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
                <form action="/sizesearch" method="post" id="form_size_search">
                  @csrf
                  <input type="hidden" name="searchbox" id="isizeid">
                    <div class="row">
                      <div class="col-md-12">
                          <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                          <input style="height: 33px; font-size: 12 !important; font-weight: 600" type="text" name="automplete-product" class="form-control ui-autocomplete-input" placeholder="Search Size..." id="autocomplete-product" autocomplete="off">
                      </div>
                    </div>
                </form>
                
                <form action="{{route('size.edit_list')}}" method="post" enctype="multipart/form-data" id="form-size">
                  @csrf
                  @method('post')
                  @if(session()->get('hq_sid') == 1)
                      <input type="hidden" id="edit_hidden_store_hq_val" name="stores_hq" value="">
                  @endif
                  <div class="table-responsive">
                    <table id="sizeTable" class="text-center table table-hover" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                      <thead style="background-color: #286fb7!important;">
                        <tr>
                          <th style="width: 1px;color:black;" class="col-xs-1 headername text-uppercase  text-light  text-center">
                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                          </th>
                          <th class="col-xs-1 headername text-uppercase text-light" data-field="supplier_code">Name</th>
                        </tr>
                      </thead>
                      <tbody id="searchData">
                      @foreach($sizedata as $sizes)
                        <tr>
                          <td class="text-center">
                            <input type="checkbox" name="selected[]" id="size[{{$sizes->isizeid}}][select]" value="{{$sizes->isizeid}}">
                          </td>
                          <td class="text-left">
                            <span style="display:none;">{{$sizes->vsize}}</span>
                            <input type="text" style="border:none;" maxlength="45" class="editable size_c" name="size[{{$sizes->isizeid}}][vsize]" id="size[{{$sizes->isizeid}}][vsize]" value="{{$sizes->vsize}}" onclick="">
                            <input type="hidden" name="size[{{$sizes->isizeid}}][isizeid]" value="{{$sizes->isizeid}}">
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                    <div class="pull-right" style="margin-right: 5px">
                      {{$sizedata->links()}}
                    </div>
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

    function addSize() {
        $('#addModal').modal('show');
    }
    $(document).on('click', '.size_c', function(e){
      e.preventDefault();
      let selectId = $(this).attr('id').replace("vsize", "select");
      //console.log(selectId);
      document.getElementById(selectId).setAttribute('checked','checked');
    });
</script>

  <!-- Modal Add-->

<div class="modal fade" id="addModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title">Add New Size</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
      </div>
      <div class="modal-body">
        <form action="/addsize" method="post" id="add_new_form">
          @csrf
          @method('post')
            @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
            @endif
          <input type="hidden" name="Id" value="0">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input name="vsize" maxlength="45" id="add_vsize" class="form-control" autocomplete="off">
                  </div>
                </div>
              </div>
            </div>
            <br>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="button" class="btn btn-success"  value="Save" id="saveSizeButton">
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
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to add the Manufacturer :</h4>
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
                </thead>
                <tbody>
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check custom-control-input stores" id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">
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
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to Edit the Manufacturer :</h4>
            <span style="color: #03A9F4">(Please Note: If a Manufacturer not exists in any of the stores those Manufacturer will be created)</span>
          </div>
        
          <div class="modal-body">
             <table class="table table-bordered">
                <thead id="table_green_header_tag">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="editSelectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody id="edit_size_stores"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="Edit_btn_size" class="btn btn-danger" data-dismiss="modal">Update</button>
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
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to delete the Size :</h4>
          </div>
          <div class="modal-body">
             <table class="table table-bordered">
                <thead id="table_green_header_tag">
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
            <button id="delete_btn_category" class="btn btn-danger" data-dismiss="modal">Delete</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
      </div>
    </div>
    
<?php } ?>

  <script src="/javascript/bootbox.min.js" defer=""></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
  
  
  <!-- Add New form -->

<script type="text/javascript">

$("#saveSizeButton").click(function(){
    $('#addModal').modal('hide');
    var size_name = $("#add_vsize").val();
    
    if(size_name == ''){
        alert("size_name is required");
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
        
        console.log(stores_hq);
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

  
  
  <!-- Save data -->
  
  <script type="text/javascript">
    
    $(document).on('click','#save_button', function(e){
        e.preventDefault();
        var edit_url = '{{route('size.edit_list')}}';
        edit_url = edit_url.replace(/&amp;/g, '&');
        $("div#divLoading").addClass('show');
        var avArr = [];
        $("input[name='selected[]']:checked").each(function () {
          var id = $(this).val();
          var name = $(this).closest('tr').find('.size_c').val();
          avArr.push({
            isizeid: id,
            vsize: name
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
        }else{
            var numericReg = /^[0-9]*(?:\.\d{1,2})?$/;
        
            <?php if(session()->get('hq_sid') == 1){ ?>
                $.ajax({
                      url: "<?php echo url('/size/duplicatesize'); ?>",
                      method: 'post',
                      headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                      },
                      data: {avArr},
                      success: function(result){
                            var popup = '';
                            @foreach (session()->get('stores_hq') as $stores)
                                if(result.includes({{ $stores->id }})){
                                    var data = '<tr>'+
                                                    '<td>'+
                                                        '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                            '<input type="checkbox" class="checks check custom-control-input editstores" disabled id="hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Category does not exist)</span></td>'+
                                                '</tr>';
                                            $('#editSelectAllCheckbox').attr('disabled', true);
                                      
                                } else {
                                    var data = '<tr>'+
                                                    '<td>'+
                                                        '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                            '<input type="checkbox" class="checks check custom-control-input editstores"  id="else_hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                '</tr>';
                                }
                                popup = popup + data;
                            @endforeach
                    $('#edit_size_stores').html(popup);
                }
                });
                $("#EditModal").modal('show');
            <?php } else { ?>
                $.ajax({
                    url: "<?php echo url('/size/edit_list'); ?>",
                    method: 'post',
                    headers: {
                          'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    data: {data:avArr},
                    success: function (e) {
                        alert("hi 434");
                        location.reload();
                    },
                    error: function (msg) {
                          $('#error_msg').html("The name has already been taken !");
                          $("div#divLoading").removeClass('show');
                          $('#errorModal').modal('show');
                    },
                    // success: function(result){
                    //   console.log(result);
                    //   // if(result){
                    //   //     $('#success_msg').html('<strong>Size Updated Successfully</strong>');
                    //   //     $("div#divLoading").removeClass('show');
                    //   //     $('#successModal').modal('show');
            
                    //   //     setTimeout(function(){
                    //   //         $('#successModal').modal('hide');
                    //   //         window.location.reload();
                    //   //     }, 2000);
                    //   // }else{
                    //   //     var errorMsg = '';
                    //   //     $.each(data.error_msg, function (k, v){
                    //   //         errorMsg += v+'<br/>';
                    //   //     });
                    //   //     $('#error_msg').html('<strong>Size Updating Failed</strong>');
                    //   //     $("div#divLoading").removeClass('show');
                    //   //     $('#errorModal').modal('show');
                    //   //     setTimeout(function(){
                    //   //         $('#errorModal').modal('hide');
                    //   //         window.location.reload();
                    //   //     }, 4000);
                    //   // }
                    // },
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
    
    $('#Edit_btn_size').click(function(){
        $.each($("input[name='editstores']:checked"), function(){            
            edit_stores.push($(this).val());
        });
        $("#edit_hidden_store_hq_val").val(edit_stores.join(","));
        
       
        var avArr = [];
        $("input[name='selected[]']:checked").each(function () {
          var id = $(this).val();
          var name = $(this).closest('tr').find('.size_c').val();
          avArr.push({
            isizeid: id,
            vsize: name
          });
        });
        
        $.ajax({
                url: "<?php echo url('/size/edit_list'); ?>",
                method: 'post',
                headers: {
                      'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                data: {data:avArr, stores_hq: edit_stores},
                success: function(result){
                    bootbox.alert({ 
                        size: 'small',
                        title: "Success", 
                        message: "Size Updated Successfully", 
                        callback: function(){location.reload(true);}
                    });
                }
        });
    });
  

    $(document).on('submit', '#form_size_search', function (e) {
        e.preventDefault();
    });

    $(document).on('keyup', '#autocomplete-product', function (e) {
        e.preventDefault();

        var q = $(this).val();
        //console.log(q);
        if(q.length !=0){
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
            url: '/sizesearch',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: {
                q: q
            },
            success: function (sizE) {
                // Do some nice animation to show results
                //let ageVeri = data['ageveri'];

                let html = '';
                $.each(sizE.size, function(k, v){
                  //console.log(v);
                  html += '<tr>';
                  html +=  '<td class="text-center">';                        
                  html +=  '<input type="checkbox" name="selected[]" id="size['+v.isizeid+'][\'select\']" class="checkboxId" value="'+v.isizeid+'">';
                  html +=  '</td>';
                                    
                  html += '<td class="text-left">';
                  html += '<span style="display:none;">'+v.vsize+'</span>';
                  html += '<input type="text" style="border:none;"  maxlength="45" class="editable size_c " name="size['+v.isizeid+'][\'vsize\']"  id="size['+v.isizeid+'][\'vsize\']" value="'+v.vsize+'" >';
                  html += '<input type="hidden" name="size['+v.isizeid+'][\'isizeid\']" value="'+v.isizeid+'">';
                  html += '</td>'; 
                  
                  html += '</tr>';
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


<!-- Delete size -->

<script>

    $(document).on('click', '#size_delete', function(event) {
        event.preventDefault();
        
        if($("input[name='selected[]']:checked").length == 0){
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: 'Please Select Size to Delete!', 
            callback: function(){}
          });
          return false;
        }
        var avArr = [];
        $("#sizeTable input[name='selected[]']:checked").each(function () {
          var id =$(this).val();
          var name = $(this).closest('tr').find('.size_c').val();
          avArr.push({
            isizeid:id,
            vsize:name
          });
        });
        
        
    <?php if(session()->get('hq_sid') == 1){ ?>
        $.ajax({
              url: "<?php echo url('/size/duplicatesize'); ?>",
              
              method: 'post',
              headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
              },
              data: {avArr},
              success: function(result){
                        var popup = '';
                        @foreach (session()->get('stores_hq') as $stores)
                            if(result.includes({{ $stores->id }})){
                                var data = '<tr>'+
                                                '<td>'+
                                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                        '<input type="checkbox" class="checks check custom-control-input deletestores" disabled id="hq_sid_{{ $stores->id }}" name="deletestores" value="{{ $stores->id }}">'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Category does not exist)</span></td>'+
                                            '</tr>';
                                        $('#deleteSelectAllCheckbox').attr('disabled', true);
                                  
                            } else {
                                var data = '<tr>'+
                                                '<td>'+
                                                    '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                        '<input type="checkbox" class="checks check custom-control-input deletestores"  id="else_hq_sid_{{ $stores->id }}" name="deletestores" value="{{ $stores->id }}">'+
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
    
     var delete_size_url = "{{route('size.delete')}}";
     delete_size_url = delete_size_url.replace(/&amp;/g, '&');
     var data = {};

        $("div#divLoading").addClass('show');
    
        $.ajax({
            url : delete_size_url,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify(avArr),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                if(data.status == 0){
                    $('#success_msg').html('<strong>Size Deleted Successfully</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#successModal').modal('show');
      
                    setTimeout(function(){
                        $('#successModal').modal('hide');
                        window.location.reload();
                    }, 2000);
                }else{
                    var errorMsg = '';
                    $.each(data.error_msg, function (k, v){
                        errorMsg += v+'<br/>';
                    });
                    $('#error_msg').html('<strong>'+ errorMsg +'</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#errorModal').modal('show');
                    setTimeout(function(){
                        $('#errorModal').modal('hide');
                        window.location.reload();
                    }, 4000);
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

$('#delete_btn_category').click(function(){
    var avArr = [];
    $.each($("input[name='deletestores']:checked"), function(){            
        deletestores.push($(this).val());
    });
    $("#sizeTable input[type=checkbox]:checked").each(function () {
     var id =$(this).val();
     var name = $(this).closest('tr').find('.size_c').val();
     
      avArr.push({
        isizeid:id,
        vsize:name,
        stores_hq: deletestores
      });
    });
    console.log(avArr);
     var delete_size_url = "{{route('size.delete')}}";
     delete_size_url = delete_size_url.replace(/&amp;/g, '&');
     var data = {};
    
        $("div#divLoading").addClass('show');
    
        $.ajax({
            url : delete_size_url,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify(avArr),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
          success: function(data) {

            if(data.status == 0){
              $('#success_msg').html('<strong>Size Deleted Successfully</strong>');
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


</script>
<style>
    .disabled {
    pointer-events:none; //This makes it not clickable
 
    }

</style>
@endsection