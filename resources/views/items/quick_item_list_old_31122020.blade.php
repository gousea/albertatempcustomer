@extends('layouts.master')

@section('title')
Quick Items
@endsection

@section('main-content')

<div id="content">
    <div class="page-header">


  
    <div class="container-fluid">
      
      @if (session()->has('message'))
          <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>      
      @endif
      
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
          </ul>
          
        </div>
            
        @endif
    
      
      <div class="panel panel-default" style="">
    <div class="panel-heading head_title"> 
        <h3 class="panel-title"><i class="fa fa-list"></i>Edit Quick Items</h3>
    </div>
        
       
        <div class="panel-body">
        
        <div class="row" style="padding-bottom: 9px;float: right;">
          <div class="col-md-12">
            <div class="">
              <button title="Update" class="btn btn-primary" id="update_button"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        
      <br>

    <form action="{{ url('/item/quick_item/update') }}" method="post" id="form-quick-item">
      @csrf    
      <!-- <input type="hidden" name="MenuId" value=""> -->
          <div class="table-responsive">
            @if ($items)
              <table id="item" class="text-center table table-bordered table-hover" >
                  <thead>
                    <tr>
                      <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                      <th class="text-left">Group Name</th>
                      <th class="text-left">Register Name</th>
                      <th class="text-right">Sequence</th>
                      <th class="text-left">Status</th>
                    </tr>
                  </thead>
                <tbody id='searchData'>

                  @foreach ($items as $i => $item)
                  <tr>
                      <td class="text-center">                        
                        <input type="checkbox" name="selected[]" id="quick_item[{{$i}}][select]" class="checkboxId" value="{{$item['iitemgroupid']}}">
                        <input type="hidden"  name="quick_item[{{$i}}][iitemgroupid]" value="{{ $item['iitemgroupid'] }}">
                      </td>
                                      
                      <td class="text-left">
                        <input type="text" class="editable quick_vitemgroupname" name="quick_item[{{$i}}][vitemgroupname]" value="{{ $item['vitemgroupname'] }}" onclick='document.getElementById("quick_item[{{ $i }}][select]").setAttribute("checked","checked");' />
                      </td>

                      <td class="text-left">
                        <span>{{ $item['vterminalid'] }}</span>
                      </td>
                                      
                      <td class="text-right">
                        <input type="text" class="editable quick_sequence" name="quick_item[{{ $i }}][isequence]" value="{{ $item['isequence'] }}" onclick='document.getElementById("quick_item[{{$i}}][select]").setAttribute("checked","checked");' style="text-align: right;" />
                      </td>
  
                      <td class="text-left">
                        <span>{{ $item['estatus'] }}</span>
                      </td>
                                      
                  </tr>
                  @endforeach
                </tbody>
              </table>

            @else
            <tr>
              <td colspan="7" class="text-center">No results Found</td>
            </tr>
            @endif
          </div>
        </form>
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

<script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>
<script type="text/javascript">
  $(document).on('click', '#update_button', function(event) {
    
    var all_itemgroupname = true;
    var all_itemgroupname_arr = [];

    var all_sequence = true;
    var all_sequence_arr = [];
    var all_correct = true;
    
    var checkedCheckBox = false;

    $("#searchData input[type=checkbox]:checked").each(function () {
        checkedCheckBox = true;
    });
    
    if(checkedCheckBox === false){
        bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: "You did not select anything", 
            callback: function(){location.reload(true);}
        });
        
        
        $("div#divLoading").removeClass('show');
        return false;
    }

    $('.quick_vitemgroupname').each(function(){
      if($(this).val() == ''){
        // alert('Please Enter Group Name');

        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please Enter Group Name", 
          callback: function(){}
        });

        all_itemgroupname = false;
        all_correct = false;
        return false;
      }else{
        if(jQuery.inArray($(this).val(), all_itemgroupname_arr) !== -1){
          // alert('Please Enter Unique Group Name');

          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: "Please Enter Unique Group Name", 
            callback: function(){}
          });

          all_itemgroupname = false;
          all_correct = false;
          return false;
        }else{
          all_itemgroupname_arr.push($(this).val());
          all_itemgroupname = true;
        }
      }
    });

    if(all_itemgroupname == true){
      $('.quick_sequence').each(function(){
        if($(this).val() == ''){
          // alert('Please Enter Sequence');

          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: "Please Enter Sequence", 
            callback: function(){}
          });

          all_sequence = false;
          all_correct = false;
          return false;
        }else{
          if(jQuery.inArray($(this).val(), all_sequence_arr) !== -1){
            // alert('Please Enter Unique Sequence');

            bootbox.alert({ 
              size: 'small',
              title: "Attention", 
              message: "Please Enter Unique Sequence", 
              callback: function(){}
            });

            all_sequence = false;
            all_correct = false;
            return false;
          }else{
            all_sequence_arr.push($(this).val());
            all_sequence = true;
          }
        }
      });
    }

    if(all_correct == true){
      $('#form-quick-item').submit();
      $("div#divLoading").addClass('show');
    }
  });

    $(document).on('keypress keyup blur', '.quick_sequence', function(event) {

        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        
    });
</script>

<!-- DataTables -->
<script src="{{ asset('javascript/jquery.dataTables.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('javascript/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('javascript/select2/js/select2.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    $(document).ready(function() {
        
      var table = $('#item').DataTable({
        "searching": false,
        "destroy": true,
        "bLengthChange": false,
        "pageLength":20
        
      });
      
    });
    
    
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>

@endsection