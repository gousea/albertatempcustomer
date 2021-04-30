@extends('layouts.master')

@section('title')
  Item Group
@endsection

@section('main-content')
<div id="content">

  <div class="page-header">
    <div class="container-fluid">
      <!-- <h1>Item Group</h1> -->
      
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
        <h3 class="panel-title"><i class="fa fa-list"></i> Item Group</h3>
      </div>
    <div class="panel-body">
      <div class="row" style="padding-bottom: 9px;float: right;">
        <div class="col-md-12">
          <div class="">
            <a href="additemgroup" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
            <button type="button" class="btn btn-danger" id="itemgroup_delete" onclick="myFunction()" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>  
          </div>
        </div>
      </div>
      <div class="clearfix"></div>

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
        <table id="group" class="table table-bordered table-hover" style="width:60%;">
          <thead>
            <tr>
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
                  <span><a href="edititem/{{$itemgroups->iitemgroupid}}"  title="Edit" class="btn btn-sm btn-info edit_btn_rotate"><i class="fa fa-pencil">&nbsp;&nbsp;Edit</i>
                  </a></span>
                
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
          
          <!-- {{$itemgroup->links()}} -->
      </div>
        <div class="row" style="margin-left: 0px;margin-right: 0px;">
          <div class="col-sm-6 text-left">{{$itemgroup->links()}}</div>
          <!-- <div class="col-sm-6 text-right">Showing 1 to 11 of 11 (1 Pages)</div> -->
        </div>
      </div>
    </div>
  </div>
</div>

@endsection


@section('scripts')
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
            title: "Attention", 
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