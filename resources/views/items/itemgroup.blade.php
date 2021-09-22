@extends('layouts.layout')

@section('title')
  Item Group
@endsection

@section('main-content')
<div id="content">

  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase"> Item Group</span>
            </div>
            <div class="nav-submenu">                
                <a type="button" href="additemgroup" class="btn btn-gray headerblack  buttons_menu add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
                <button type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" id="itemgroup_delete" onclick="myFunction()" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
  </nav>

  <div class="section-content py-6">
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
      
      <div class="panel-body">
        
        <form action="" method="post" id="form_group_search">
          @csrf
          <input type="hidden" name="searchbox" id="iitemgroupid">
          <div class="row">
            <div class="col-md-12">
              <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
              <input type="text" name="autocomplete-product" class="form-control ui-autocomplete-input" placeholder="Search Item Group..." id="autocomplete-product" autocomplete="off">
            </div>
          </div>
        </form>
        <br>
        
        <div class="table-responsive">
          <table id="group" class="table table-hover promotionview" style="width:100%;">
            <thead>
              <tr class="header-color">
                <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"></th>
                <th class="text-left">Group Name</th>
                <th class="text-left">Slab Pricing</th>
                <th class="text-left">Action</th>
              </tr>
            </thead>
            <tbody id="searchData">
              @foreach($itemgroup as $itemgroups)
                <tr>
                  <td data-order="110" class="text-center">
                    <span style="display:none;">{{$itemgroups->iitemgroupid}}</span>
                    <input type="checkbox" name="selected[]" id="select[{{$itemgroups->iitemgroupid}}][select]" value="{{$itemgroups->iitemgroupid}}">
                  </td>
                  <td class="text-left">
                    <span>{{$itemgroups->vitemgroupname}}</span>
                  </td>
                  <td class="text-left">
                    <span>No</span>
                  </td>
                  <td class="text-left">
                    <span><a href="edititem/{{$itemgroups->iitemgroupid}}"  title="Edit" class="btn btn-sm btn-info edit_btn_rotate header-color" style="line-height: 0.5; border-radius:6px;"><i class="fa fa-pencil">&nbsp;&nbsp;Edit</i>
                    </a></span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
            
            <!-- {{$itemgroup->links()}} -->
        </div>
          <div class="row pull-right" style="margin-left: 0px;margin-right: 0px;">
            <div class="col-sm-6 text-left">{{$itemgroup->links()}}</div>
            <!-- <div class="col-sm-6 text-right">Showing 1 to 11 of 11 (1 Pages)</div> -->
            <br>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if(session()->get('hq_sid') == 1){ ?>
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title">Select the stores in which you want to delete the Item Group:</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <table class="table" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                <thead id="table_green_header_tag" style="background-color: #286fb7!important;">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="selectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead >
                <tbody id="data_stores"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" id="delete_btn" class="btn btn-danger" data-dismiss="modal">Delete</button>
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

<script src="/javascript/bootbox.min.js" defer=""></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>


<!-- Search Code -->

<script>
  $(document).on('submit', '#form_group_search', function (e) {
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
            url: '/itemformgroupsearch',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: {
                q: q
            },
            success: function (itemGroup) {
              
              let html = '';
              
              $.each(itemGroup.itemgroup, function(k, v){
                //console.log(v);

                html +='<tr>';
                html +='<td data-order="110" class="text-center">';
                html +='<span style="display:none;">'+v.iitemgroupid+'</span>';
                html +='<input type="checkbox" name="selected[]" id="select['+v.iitemgroupid+'][\'select\']" value="'+v.iitemgroupid+'">';
                html +='</td>';
                
                html +='<td class="text-left">';
                html +='<span>'+v.vitemgroupname+'</span>';
                html +='</td>';

                html +='<td class="text-left">';
                html +='<span>No</span>';
                html +='</td>';

                html +='<td class="text-left">';
                html += '<span><a href="edititem/'+v.iitemgroupid+'"  title="Edit" class="btn btn-sm btn-info edit_btn_rotate"><i class="fa fa-pencil">&nbsp;&nbsp;Edit</i></a></span>';
                html +='</td>';
                html +='</tr>';

              });
              //console.log("1");

               $('#searchData').html(html);

              //console.log("2");
              $('#divLoading').removeClass('show');
              
            },
            done: function(){
              $('#divLoading').removeClass('show');
            }
        });
      });

</script>

<!-- Delete itemgroup Code -->

<script>
  function myFunction() {
    var result = confirm("Want to delete?");
    if (result) {
        $(document).on('click', '#itemgroup_delete', function(event) {
            event.preventDefault();
            var delete_aisle_url = 'deleteitemgroup';
            delete_aisle_url = delete_aisle_url.replace(/&amp;/g, '&');
            var data = [];
    
            if($("input[name='selected[]']:checked").length == 0){
              bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: 'Please Select itemgroup to Delete!', 
                callback: function(){}
              });
              return false;
            }

        
            $("input[name='selected[]']:checked").each(function (i)
            {
              data.push(
                {iitemgroupid : parseInt($(this).val())}
              );
            });
             let input = {'input': data};
            if(data.length > 0){
                <?php if(session()->get('hq_sid') == 1){ ?>
                    $.ajax({
                          url: "<?php echo url('/duplicatehqitemgroups'); ?>",
                          method: 'post',
                          headers: {
                                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                          },
                          data: JSON.stringify(data),
                          type : 'POST',
                          contentType: "application/json",
                          dataType: 'json',
                          success: function(result){
                                    var popup = '';
                                    @foreach (session()->get('stores_hq') as $stores)
                                        if(result.includes({{ $stores->id }})){
                                            var data = '<tr>'+
                                                            '<td>'+
                                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                    '<input type="checkbox" class="checks check  stores" disabled id="stores" name="stores" value="{{ $stores->id }}">'+
                                                                '</div>'+
                                                            '</td>'+
                                                            '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Vendor does not exist)</span></td>'+
                                                        '</tr>';
                                                    $('#selectAllCheckbox').attr('disabled', true);
                                              
                                        } else {
                                            var data = '<tr>'+
                                                            '<td>'+
                                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                    '<input type="checkbox" class="checks check  stores"  id="stores" name="stores" value="{{ $stores->id }}">'+
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
                    $('#myModal').modal('show');
                <?php } else { ?>
                   
                    //console.log(input);
                    $("div#divLoading").addClass('show');
        
                    $.ajax({
                        url : delete_aisle_url,
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        data : JSON.stringify(data),
                        type : 'POST',
                        contentType: "application/json",
                        dataType: 'json',
                        success: function(data) {
                            if(data.status == 0){
                                //console.log('status');
                                $('#success_msg').html('<strong>'+ data.success +'</strong>');
                                $("div#divLoading").removeClass('show');
                                $('#successModal').modal('show');
                                setTimeout(function(){
                                    $('#successModal').modal('hide');
                                    window.location.reload();
                                }, 1000);
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
                <?php } ?>
                
            }

            
        
      });
    }
  }
  
    var delete_stores = [];
    
    delete_stores.push("{{ session()->get('sid') }}");
     $('#selectAllCheckbox').click(function(){
        if($('#selectAllCheckbox').is(":checked")){
            $(".stores").prop( "checked", true );
        }else{
            $( ".stores" ).prop("checked", false );
        }
    });
    
    $("#delete_btn").click(function(){
        $.each($("input[name='stores']:checked"), function(){            
            delete_stores.push($(this).val());
        });
        
        var delete_aisle_url = 'deleteitemgroup';
        delete_aisle_url = delete_aisle_url.replace(/&amp;/g, '&');
        var data = [];

        if($("input[name='selected[]']:checked").length == 0){
          bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: 'Please Select itemgroup to Delete!', 
            callback: function(){}
          });
          return false;
        }

    
        $("input[name='selected[]']:checked").each(function (i)
        {
          data.push(
            {iitemgroupid : parseInt($(this).val())}
          );
        });
        
        $.ajax({
            url : delete_aisle_url,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data : JSON.stringify({data, stores_hq: delete_stores}),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                if(data.status == 0){
                    //console.log('status');
                    $('#success_msg').html('<strong>'+ data.success +'</strong>');
                    $("div#divLoading").removeClass('show');
                    $('#successModal').modal('show');
                    setTimeout(function(){
                        $('#successModal').modal('hide');
                        window.location.reload();
                    }, 1000);
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
    })
</script>

@endsection