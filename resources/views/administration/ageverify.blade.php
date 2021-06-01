@extends('layouts.layout')

@section('title')
  Age Verification
@endsection

@section('main-content')
<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase" > Age Verification</span>
            </div>
            <div class="nav-submenu">
                <button type="button" id="save_button"  class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
</nav>
<section class="section-content py-6">
  <div id="content">
    <div class="page-header">
      <div class="container-fluid">
        
        @if (session()->has('message'))
          <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>      
        @endif

      <div id='errorDiv'>
      </div>
      @if ($errors->any())
        <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          @foreach ($errors->all() as $error)
            <i class="fa fa-exclamation-circle"></i>{{$error}}<br/>
          @endforeach
          
        </div> 
      @endif
      
      
      
        <div class="panel panel-default" style="">
        
          <div class="panel-body">
            <form action="/ageverifysearch" method="post" id="form_age_verification_search">
              @csrf
              <input type="hidden" name="searchbox" id="Id">
              <div class="row">
                <div class="col-md-12">
                  <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                  <input type="text" style="height:33px; font-size: 12 !important; font-weight: 600;" name="autocomplete-product" class="form-control ui-autocomplete-input" placeholder="Search Age Verification..." id="autocomplete-product" autocomplete="off">
                </div>
              </div>
            </form>
            <br>

            <form action="" method="post" enctype="multipart/form-data" id="form-age-verification">
              <input type="hidden" name="MenuId" value="">
                <div class="table-responsive">
                  <table id="age-verification" class="text-center table table-hover" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                    <thead style="background-color: #286fb7!important;">
                      <tr>
                        <td style="width: 1px;" class="text-center">
                          <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                          </td>
                        
                        <th class="col-xs-1 headername text-uppercase text-light" data-field="supplier_code">Description</th>
                        <th class="col-xs-1 headername text-uppercase text-light" data-field="supplier_code">Age</th>
                        
                      </tr> 
                    </thead>
                    <tbody id='searchData'>

                      @foreach ($agedata as $k => $age)
                      <tr>
                        <td class="text-center">                        
                      <input type="checkbox" name="selected[]" id="age_verification[{{$age->Id}}]['select']" class="checkboxId" value="{{$age->Id}}">
                        </td>
                                          
                        <!-- <td>{{ $age->vname }}</td>
                        <td>{{ $age->vvalue }}</td> -->

                        <td >
                          <span style="display:none;">{{ $age->vname }}</span>
                          <input type="text" style="border:none;" maxlength="50" class="editable age_verification_c" name="age_verification[{{$age->Id}}]['vname']" id="age_verification[{{$age->Id}}]['vname']" value="{{ $age->vname }}" onclick="">
                          <!--<input type="hidden" maxlength="50" class="editable age_verification_c" name="age_verification[1][vname]" id="age_verification[1][vname]" value="LOTTERY" onclick='document.getElementById("age_verification[2][select]").setAttribute("checked","checked");' />-->
                          <input type="hidden"  name="age_verification[{{$age->Id}}]['Id']" value="{{$age->Id}}">
                        </td>
                                          
                        <td>
                          <input type="text" style="border:none;" class="editable age_verification_s" maxlength="50" name="age_verification[{{$age->Id}}]['vvalue']" id="age_verification[{{$age->Id}}]['vvalue']" value="{{ $age->vvalue }}" onclick="">
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
    </div>
  </div>
</section>


<?php if(session()->get('hq_sid') == 1){ ?>
     <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to Update the Age :</h4>
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
                <tbody id="age_stores"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="Edit_btn_age" class="btn btn-danger" data-dismiss="modal">Update</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
    
      </div>
    </div>
<?php } ?>
    
@endsection

@section('page-script')
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

    <script src="/javascript/bootbox.min.js" defer=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  
  <script type="text/javascript">

  $(document).on('click', '.age_verification_c,.age_verification_s', function(e){
    e.preventDefault();
    let selectId = $(this).attr('id').replace("vname", "select");
    let selectId2 = $(this).attr('id').replace("vvalue", "select");
    
    //console.log(selectId);
    document.getElementById(selectId).setAttribute('checked','checked');
    document.getElementById(selectId2).setAttribute('checked','checked');

  });

$(document).on('click','#save_button', function(){

    //   $("div#divLoading").addClass('show');

    var avArr = [];

    $("input[name='selected[]']:checked").each(function () {
          
          var id = $(this).val();
          var name = $(this).closest('tr').find('.age_verification_c').val();
          var age = $(this).closest('tr').find('.age_verification_s').val();
          
          avArr.push({
              Id: id, 
              vname: name,
              vvalue: age
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
    <?php if(session()->get('hq_sid') == 1){ ?>
    
        console.log(avArr);
        $.ajax({
            url : "<?php echo url('/duplicateage'); ?>",
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : {data: avArr},
            type : 'POST',
            success: function(result) {
                var popup = '';
                @foreach (session()->get('stores_hq') as $stores)
                    if(result.includes({{ $stores->id }})){
                        var data = '<tr>'+
                                        '<td>'+
                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                '<input type="checkbox" class="checks check custom-control-input editstores" disabled id="hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                            '</div>'+
                                        '</td>'+
                                        '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
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
                $('#age_stores').html(popup);    
            }
        });
        $("#myModal").modal('show');
    <?php } else { ?>
          //do an ajax request to send the values 
          $.ajax({
              type: 'POST',
              headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
              url: '/ageverification',
              contentType: 'application/json',
              data: JSON.stringify(avArr), // access in body
          }).success(function (e) {
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
                  });
    <?php } ?>
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

$('#Edit_btn_age').click(function(){
    $.each($("input[name='editstores']:checked"), function(){            
        edit_stores.push($(this).val());
    });
    
    var avArr = [];

    $("input[name='selected[]']:checked").each(function () {
        var id = $(this).val();
        var name = $(this).closest('tr').find('.age_verification_c').val();
        var age = $(this).closest('tr').find('.age_verification_s').val();
        avArr.push({
            Id: id, 
            vname: name,
            vvalue: age
        });
    });
    
    $.ajax({
          type: 'POST',
          headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
          url: '/ageverification',
          contentType: 'application/json',
          data: JSON.stringify({data:avArr, stores_hq: edit_stores}), // access in body
    }).success(function (e) {
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
    });
});

    // Serch Code
    $(document).on('submit', '#form_age_verification_search', function (e) {
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
        } else{
          $('#divLoading').addClass('show');
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/ageverifysearch',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: {
                q: q
            },
            success: function (ageVerify) {
                // Do some nice animation to show results
                //let ageVeri = data.ageverify;

                let html = '';
                $.each(ageVerify.ageverify, function(k, v){
                  //console.log(v);
                  html += '<tr>';
                  html +=  '<td class="text-center">';                        
                  html +=  '<input type="checkbox" name="selected[]" id="age_verification['+v.Id+'][\'select\']" class="checkboxId" value="'+v.Id+'">';
                  html +=  '</td>';
                                    
                  html += '<td class="text-left">';
                  html += '<span style="display:none;">'+v.vname+'</span>';
                  html += '<input type="text" style="border:none;" maxlength="50" class="editable age_verification_c" name="age_verification['+v.Id+'][\'vname\']" id="age_verification['+v.Id+'][\'vname\']" value="'+v.vname+'">';
                  html += '<input type="hidden" name="age_verification['+v.Id+'][\'Id\']" value="'+v.Id+'">';
                  html += '</td>';
                   
                  html +=  '<td class="text-right">';
                  html +=  '<input type="text" style="border:none;" class="editable age_verification_s" maxlength="50" name="age_verification['+v.Id+'][\'vvalue\']" id="age_verification['+v.Id+'][\'vvalue\']" value="'+v.vvalue+'">';
                  html +=  '</td>';
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
@endsection