@extends('layouts.layout')

@section('title')
    Physical Inventory
@stop

@section('main-content')

<div id="content">
    
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
      <div class="container">
          <div class="collapse navbar-collapse" id="main_nav">
              <div class="menu">
                  <span class="font-weight-bold text-uppercase"> Physical Inventory</span>
              </div>
              <div class="nav-submenu">
                
                <a type="button" href="" title="Add" class="btn btn-gray headerblack  buttons_menu add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>  
                            
                
              </div>
          </div> <!-- navbar-collapse.// -->
      </div>
    </nav>

    <section class="section-content py-6">
      <div class="container">
          @if (session()->has('message'))
              <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                  {{session()->get('message')}}
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
          @endif
        <div class="panel panel-default">
          
          <div class="panel-body">
                 
              <div class="table-responsive col-xl-12 col-md-12">
                <table id="physical_inventory_detail" class="table table-hover" style="">
                  <thead>
                    <tr class="header-color">
                      <th class="text-left text-uppercase">Ref.Number
                        <div class="form-group has-search">
                          <span class="fa fa-search form-control-feedback" style="padding-left: 6px;"></span>
                          <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                        </div>
                      </th>
                      <th class="text-left text-uppercase">Created
                        <div class="form-group has-search">
                          <span class="fa fa-search form-control-feedback" style="padding-left: 6px;"></span>
                          <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                        </div>
                      </th>
                      <th class="text-left text-uppercase">Calculated
                        <div class="form-group has-search">
                          <span class="fa fa-search form-control-feedback" style="padding-left: 6px;"></span>
                          <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                        </div>
                      </th>
                      <th class="text-left text-uppercase">Commited
                        <div class="form-group has-search">
                          <span class="fa fa-search form-control-feedback" style="padding-left: 6px;"></span>
                          <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                        </div>
                      </th>
                      <th class="text-left text-uppercase">Title
                        <div class="form-group has-search">
                          <span class="fa fa-search form-control-feedback" style="padding-left: 6px;"></span>
                          <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                        </div>
                      </th>
                      <th class="text-left text-uppercase">Status
                        <div class="form-group has-search">
                          <span class="fa fa-search form-control-feedback" style="padding-left: 6px;"></span>
                          <input type="text" class="form-control table-heading-fields text-center" placeholder="SEARCH" id="adjustment_no">
                        </div>
                      </th>
                      <th class="text-left text-uppercase no-filter">Action</th>
                    </tr>
                  </thead>
                  
                </table>
              </div>
              
          </div>
        </div>
      </div>
    </section>
</div>
@endsection

@section('page-script')

<script>
    $(document).ready(function(){
        
        $(document).on('click', '.add_new_btn_rotate', function(e){
            e.preventDefault();
            var url = 'physicalInventroy/check_status_url';
            url = url.replace(/&amp;/g, '&');
            
            if($('#physical_inventory_detail').find('tbody tr').length < 1){
                
                var add = "{{ route('inventory.physicalInventroy.get_item_list') }}";
                    add = add.replace(/&amp;/g, '&');
                    window.location.href = add;
            }else{
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
            }
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

  <script>
    var url = "{{ url('/inventory/physicalInventroy/search_inventory_list') }}";
  
    $('#physical_inventory_detail thead tr th').each( function (i) {
        
        $( this ).on( 'keyup', '.table-heading-fields', function () {
  
              var self = this;
              if ( table.column(i).search() !== self.value ) {
                  
                  
                  table
                      .column(i)
                      .search( self.value )
                      .draw();
                      
                  $("div#divLoading").addClass('show');
                  
              }
        });
        
    });
  
   
    var table =   $("#physical_inventory_detail").DataTable({
        "bSort": false,
        "fixedHeader": true,
        "processing": true,
        "iDisplayLength": 20,
        "serverSide": true,
        "bLengthChange": false,
        "aoColumnDefs": [
            { "sWidth": "190px", "aTargets": [ 6 ] },
            
        ],
        
        "language": {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        },
        // "fnPreDrawCallback": function( oSettings ) {
        
        //     main_checkbox = $('#main_checkbox').is(":checked");
    
        // },
        "dom": 't<"bottom col-md-12 row"<"col-md-3"i><"col-md-9"p>>',
        "ajax": {
        url: url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        "data": function(d){
            d["m_check"] = $('#main_checkbox').is(":checked");
            
        },
        type: 'POST',
        "dataSrc": function ( json ) {
                
                if(json.data.length != 0){
                $(".bottom").show();  
                
                } else {
                $(".bottom").hide(); 
                
                }
                
                return json.data;
            } 
        },
        
        columns :  [
                    
          
                      { "data": "vrefnumber"},
                      { "data": "dcreatedate"},
                      { "data": "dcalculatedate"},
                      { "data": "dclosedate"},
                      {"data": "vordertitle"},
                      { "data": "estatus"},
                    
                      { render: function(data, type, row){
                        
                          let view_edit = row['view_edit'];
                          let delete_inventory = row['delete_inventory'];
                            return "<a href="+view_edit+" data-toggle='tooltip' title='View' class='btn btn-sm btn-info edit_btn_rotate header-color' ><i class='fa fa-eye'></i>&nbsp;&nbsp;View</a>&nbsp;<a href="+delete_inventory+" data-toggle='tooltip' title='Delete' class='btn btn-sm btn-danger buttonred' ><i class='fa fa-delete'>&nbsp;&nbsp;Delete</i></a>";
                        }
                      },
                    
            ],
            rowCallback: function(row, data, index){
                
        },
        fnDrawCallback : function() {
                if ($(this).find('tbody tr').length<=1) {
                    $(this).find('.dataTables_empty').hide();
                }
                
                // $(this).removeClass('promotionview');
                $(this).addClass('promotionview');
                console.log("check")
        }
    }).on('draw', function(){
                if($('#main_checkbox').prop("checked") == true){
                    
                    $('.iitemid').prop('checked', true);
                } else{ 
                    $('.iitemid').prop('checked', false);   
                }
                // console.log($(this).find('tbody tr .iitemid').length);
            if ($(this).find('tbody tr .iitemid').length>0) {
                $('#buttonEditMultipleItems').prop('disabled', false);
            }
            $("div#divLoading").removeClass('show');
    });
  
    $("#physical_inventory_detail_paginate").addClass("pull-right");
  </script>

<style>
  .no-filter{
      padding-bottom: 45px !important;
  }

  .no-filter-checkbox{
      padding-bottom: 20px !important;
  }

  .edit_btn_rotate, .buttonred{
      line-height: 0.5;
      border-radius: 6px;
    }
</style>
@endsection