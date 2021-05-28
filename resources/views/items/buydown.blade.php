@extends('layouts.layout')

@section('title')

Buy Down

@endsection

@section('main-content')

<div id="content" >

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Buy Down</span>
                </div>
                <div class="nav-submenu">
                    <a type="button" href="/buydownadd" title="Add New" class="btn btn-gray headerblack buttons_menu add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a> 
                    <button type="submit" class="btn btn-danger buttonred buttons_menu basic-button-small" id="buydown_delete" title="Delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
                    
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
    
    <section class="section-content py-6">
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
                
                <div class="panel-body">
                        
                    <div class="box-body table-responsive col-xl-12 col-md-12">                  
                                        
                        <form action="<?php echo $data['delete_buydown']; ?>" method="post"  id="buydown_search">
                            @csrf
                            <?php if(session()->get('hq_sid') == 1){ ?>
                                <input type="hidden" name="stores_hq" id="hidden_stores_hq" value=""/>
                            <?php } ?>
                            <input type="hidden" name="MenuId" value=""/>
                            <table id="table_bydown" class="table table-hover" data-classes="table table-hover table-condensed promotionview"
                                data-row-style="rowColors" data-striped="true" data-click-to-select="true">
                                <thead>
                                    <tr role="row" class="header-color">
                                        <th class="text-center no-filter-checkbox">
                                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                                        </th>
                                        <th class="text-left text-uppercase">BuyDown Name
                                            <div class="form-group adjustment-has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields search_text_box" placeholder="SEARCH" id="adjustment_no">
                                            </div>
                                        </th>
                                        <th class="text-left text-uppercase">Code
                                            <div class="form-group adjustment-has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields search_text_box" placeholder="SEARCH" id="adjustment_no">
                                            </div>
                                        </th>
                                        <th class="text-left text-uppercase">Amount
                                            <div class="form-group adjustment-has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields search_text_box" placeholder="SEARCH" id="adjustment_no">
                                            </div>
                                        </th>
                                        <th class="text-left text-uppercase no-filter">Start Date</th>
                                        <th class="text-left text-uppercase no-filter">End Date</th>
                                        <th class="text-left text-uppercase no-filter">Status</th>
                                    </tr>
                                    
                                </thead>
                                
                            </table>
                        </form>
                                    
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php if(session()->get('hq_sid') == 1){ ?>
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to delete the Buydown:</h4>
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

<link type="text/css" href="/stylesheet/select2/css/select2.min.css" rel="stylesheet" />
<script src="/javascript/select2/js/select2.min.js"></script>

<link type="text/css" href="/javascript/bootstrap-datepicker.css" rel="stylesheet">
<script src="/javascript/bootstrap-datepicker.js" defer=""></script>

<link type="text/css" href="/stylesheet/select2/css/select2.min.css" rel="stylesheet" />
<script src="/javascript/select2/js/select2.min.js"></script>

<script src="/javascript/bootbox.min.js" defer=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/purchaseorder.css') }}">

<style>
    .no-filter{
        padding-bottom: 55px !important;
    }
  
    .no-filter-checkbox{
        padding-bottom: 30px !important;
    }
  </style>

<script>
    $(document).on('click', '#buydown_delete', function(event) {
        event.preventDefault();
        $('#deleteItemModal').modal('show');
    });
    
    
</script>


<script type="text/javascript">
  $(window).on('load', function() {
    $("div#divLoading").removeClass('show');
  });
  
    $(document).ready( function () {
        var search_url = "<?php echo $data['search_url'] ?>";
        search_url = search_url.replace(/&amp;/g, '&');
        
        //console.log('test123');
        var edit_url = "";
        edit_url = edit_url.replace(/&amp;/g, '&');
        
        // var promotion_types = null;
        // var types_length  = Object.keys(promotion_types).length;
        
        $('#table_bydown thead tr th').each( function (i) {
            
            var title = $(this).text();
            
     
            $( '.search_text_box', this ).on( 'keyup change', function () {
                console.log(table.column(i).search());
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
            
            $( 'select', this ).on( 'change', function () {
                if ( table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        } );
     
        var table = $("#table_bydown").DataTable({
                "bLengthChange" : false,
                "serverSide": true,
                "iDisplayLength": 20,
                "ordering": false,
                // "scrollX": true,
                // "searching": false,
                // "autoWidth": false,
                // "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0,1,2, 3,4,5 ] }],
                "dom": 't<"bottom col-md-12 row"<"col-md-3"i><"col-md-9"p>>',
                "ajax": {
                  url: search_url,
                  headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                  },
                  type: 'POST',
                  "dataSrc": function ( json ) {
                        $("div#divLoading").removeClass('show');
                        return json.data;
                    } 
                },
                
                columns: [
                      {
                          data: "buydown_id", render: function(data, type, row){
                              return $("<input>").attr({
                                    type: 'checkbox',
                                    class: "buydown_id",
                                    value: data,
                                    name: "selected[]",
                                    "data-order": data
                              })[0].outerHTML;
                          }
                      },
                      { "data": "buydown_name", render: function(data, type, row){
                          appended_url = edit_url+'buydown/'+row.buydown_id+'/edit';
                          return '<a href="'+appended_url+'">'+data+'</a>';
                      }},
                      { "data": "buydown_code"},
                      
                      { "data": "buydown_amount"},
                      { "data": "start_date"},
                      { "data": "end_date"},
                      { "data": "status" },
                ],
                 
                fnDrawCallback : function() {
                        if ($(this).find('tbody tr').length<=1) {
                            $(this).find('.dataTables_empty').hide();
                        }
                          
                        $(this).addClass('promotionview');
                }
                    
        });
        
            $("#items_status").select2();
            $("#prom_type").select2();
            $("#prom_category").select2();
            $("#prom_status").select2();
            $("#table_bydown_filter").hide();
            $("#table_bydown_paginate").addClass("pull-right");
    });
    var dataItemOrders = [];
    
    $(document).on('click', 'input[name="deleteItems"]', function(event) {
        var delete_buydowns = [];
        $.each($("input[name='selected[]']:checked"), function(){            
            delete_buydowns.push($(this).val());
        });
        console.log(delete_buydowns);
        <?php if(session()->get('hq_sid') == 1){ ?>
            $('#deleteItemModal').modal('hide');
            $.ajax({
                  url: "<?php echo url('/duplicatehqbuydown'); ?>",
                  method: 'post',
                  headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                  },
                  data: JSON.stringify(delete_buydowns),
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
                                                            '<input type="checkbox" class="checks check custom-control-input stores" disabled id="stores" name="stores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Vendor does not exist)</span></td>'+
                                                '</tr>';
                                            $('#selectAllCheckbox').attr('disabled', true);
                                } else {
                                    var data = '<tr>'+
                                                    '<td>'+
                                                        '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                            '<input type="checkbox" class="checks check custom-control-input stores"  id="stores" name="stores" value="{{ $stores->id }}">'+
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
            $("div#divLoading").addClass('show');
            $("#buydown_search").submit();
        <?php } ?>
    });
    
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
        
        $("#hidden_stores_hq").val(delete_stores);
         $("div#divLoading").addClass('show');
        $("#buydown_search").submit();
    })
    
          
 
</script>

<div id="deleteItemModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Delete Buydown</h4>
      </div>
      <div class="modal-body">
        <p>Are you Sure?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <input type="submit" class="btn btn-danger" name="deleteItems" value="Delete">
      </div>
    </div>

  </div>
</div>

<div class="modal fade" id="successModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success text-center">
            <p><b>Buydown Deleted Successfully</b></p>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection