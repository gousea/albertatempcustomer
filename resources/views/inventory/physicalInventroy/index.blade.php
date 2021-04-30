@extends('layouts.master')

@section('title')
    Physical Inventory
@stop


@section('main-content')

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
          {{-- <h1>Users List</h1> --}}
          <ul class="breadcrumb">
            <li><a href="">Physical Inventory</a></li>
          </ul>
        </div>
      </div>
    <div class="container-fluid">
        @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                {{session()->get('message')}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
      <div class="panel panel-default">
        <div class="panel-heading head_title">
          <h3 class="panel-title"><i class="fa fa-list"></i> Physical Inventory</h3>
        </div>
        <div class="panel-body">
            
          <div class="row" style="padding-bottom: 15px;float: right;">
            <div class="col-md-12">
              <div class="">
                <a href="" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
            
          <form action="{{ url('inventory/physicalInventroy') }}" method="post" id="form_physical_search">
              @csrf
            <input type="hidden" name="searchbox" id="vordertitle">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" name="automplete-product" class="form-control" placeholder="Search Physical Inventory Through Ref. No..." id="automplete-product" autocomplete="off">
                </div>
            </div>
          </form>
          <br>
                
            <div class="table-responsive">
              <table id="physical_inventory_detail" class="table table-bordered table-hover" style="">
                <thead>
                  <tr>
                    <th class="text-left">Ref.Number</th>
                    <th class="text-left">Created</th>
                    <th class="text-left">Calculated</th>
                    <th class="text-left">Commited</th>
                    <th class="text-left">Title</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($physicalInventrorylists as $item)
                    <tr>
                        <td class="text-left">
                            <span>{{ $item->vrefnumber }}</span>
                        </td>
                        <td class="text-left">
                            <?php
                                if(isset($item->dcreatedate) && !empty($item->dcreatedate) && $item->dcreatedate != '0000-00-00 00:00:00'){
                                    $dcreatedate =  DateTime::createFromFormat('Y-m-d H:i:s', $item->dcreatedate)->format('m-d-Y H:i:s');
                                }else{
                                    $dcreatedate = '';
                                }
                            ?>
                            <span>{{ $dcreatedate }}</span>
                        </td>
                        <td class="text-left">

                            <?php
                                if(isset($item->dcalculatedate) && !empty($item->dcalculatedate) && $item->dcalculatedate != '0000-00-00 00:00:00'){
                                    $dcalculatedate =  DateTime::createFromFormat('Y-m-d H:i:s', $item->dcalculatedate)->format('m-d-Y H:i:s');
                                }else{
                                    $dcalculatedate = '';
                                }
                            ?>
                            <span>{{ $dcalculatedate }}</span>
                        </td>
                        <td class="text-left">
                            <?php
                                if(isset($item->dclosedate) && !empty($item->dclosedate) && $item->dclosedate != '0000-00-00 00:00:00'){
                                    $dclosedate =  DateTime::createFromFormat('Y-m-d H:i:s', $item->dclosedate)->format('m-d-Y H:i:s');
                                }else{
                                    $dclosedate = '';
                                }
                            ?>
                            <span>{{ $dclosedate }}</span>
                        </td>
                        <td class="text-left">
                            <span>{{ $item->vordertitle }}</span>
                        </td>
                        <td class="text-left">
                            <span>{{ $item->estatus }}</span>
                        </td>
                        <td class="text-left" style="width:136px;">
                            <?php if($item->estatus == 'Calculated'){ ?>
                                <a href="/inventory/physicalInventroy/show/?ipiid={{ $item->ipiid }}" data-toggle="tooltip" title="Edit" class="btn btn-sm btn-info edit_btn_rotate" ><i class="fa fa-pencil">&nbsp;&nbsp;Edit</i>
                                </a>
                            <?php }elseif($item->estatus == 'Open'){ ?>
                                <a href="/inventory/physicalInventroy/edit_open/?ipiid={{ $item->ipiid }}" data-toggle="tooltip" title="Edit" class="btn btn-sm btn-info edit_btn_rotate" ><i class="fa fa-pencil">&nbsp;&nbsp;Edit</i>
                                </a>
                            <?php }else{ ?>
                                <a href="/inventory/physicalInventroy/show/?ipiid={{ $item->ipiid }}" data-toggle="tooltip" title="Show" class="btn btn-sm btn-info edit_btn_rotate" ><i class="fa fa-eye">&nbsp;&nbsp;View</i>
                                </a>
                            <?php } ?>
                            
                            <a href="/inventory/physicalInventroy/delete_physical/?ipiid={{ $item->ipiid }}" data-toggle="tooltip" title="Delete" class="btn btn-sm btn-danger" ><i class="fa fa-delete">&nbsp;&nbsp;Delete</i>
                                </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
              </table>
            </div>
            <div class="row">
              <div class="col-sm-8 text-left">{{ $physicalInventrorylists->links() }}</div>
              <div class="col-sm-4 text-right"></div>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')

<script>
    $(document).ready(function(){
        
        $(document).on('click', '.add_new_btn_rotate', function(e){
            e.preventDefault();
            var url = 'physicalInventroy/check_status_url';
            url = url.replace(/&amp;/g, '&');
            
            $.ajax({
                
                url: url,
                type: 'GET',
                success: function(response){
                    
                    if(response.result == 'notclossed'){
                        
                        $("div#divLoading").removeClass('show');
                        
                        $('#success_alias').html('<strong>'+ response.msg +'</strong>');
                        $('#successAliasModal').modal('show');
                        
                        return false;
                    }else{
                        var add = "{{ route('inventory.physicalInventroy.get_item_list') }}";
                        add = add.replace(/&amp;/g, '&');
                        window.location.href = add;
                        
                    }
                }
                
            });
        });
    });
</script>

<!-- Modal -->
  <div class="modal fade" id="successAliasModal" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success text-center">
            <p id="success_alias"></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
@endsection