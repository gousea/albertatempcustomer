@extends('layouts.master')

@section('title')

Buy Down

@endsection

@section('main-content')

<div id="content" style="background-color=#ccc">
    <div class="page-header">
        <div class="container-fluid">
        <!-- <div class="pull-right"><a href="https://customer.albertapayments.com/index.php?route=administration/buydown&amp;token=UhkFgeQo4wrAEsHIwWsWwlhZ53sjW5XX" data-toggle="tooltip" title="Add New" class="btn btn-primary"><i class="fa fa-plus"></i></a>
            <button type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger" onclick="confirm('Are you sure?') ? $('#form-customer').submit() : false;"><i class="fa fa-trash-o"></i></button>
        </div> -->
            <ul class="breadcrumb">
                <li><a href="">Home</a></li>
                <li><a href="">Buy Down</a></li>
            </ul>
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
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> Buy Down</h3>
            </div>
            <div class="panel-body">
            <div class="row" style="padding-bottom: 15px;">
                
                <div class="col-md-3 pull-right">
                   
                    <a href="/buydownadd" title="Add New" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a> 
                    <button type="submit" class="btn btn-danger" id="buydown_delete" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>

                    <!--<button type="button" class="btn btn-danger" id="delete_btn" style="border-radius: 0px;"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;Delete</button>-->
                </div>
            </div>

      
            <div class="box-body table-responsive">
                
                <div id="table_bydown_wrapper" class="dataTables_wrapper no-footer">
                    
                    <div class="dataTables_scroll">
                        
                        <div class="dataTables_scrollHead" style="">
                            <div class="dataTables_scrollHeadInner" style="">
                                
                                <form action="<?php echo $data['delete_buydown']; ?>" method="post"  id="buydown_search">
                                    @csrf
                                    <?php if(session()->get('hq_sid') == 1){ ?>
                                        <input type="hidden" name="stores_hq" id="hidden_stores_hq" value=""/>
                                    <?php } ?>
                                    <input type="hidden" name="MenuId" value=""/>
                                    <table id="table_bydown" class="table table-bordered table-striped table-hover display dataTable no-footer" style="" role="grid">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 1px;color:black;" class="text-center" rowspan="1" colspan="1">
                                                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                                                </th>
                                                <th rowspan="1" colspan="1">BuyDown Name</th>
                                                <th rowspan="1" colspan="1">Code</th>
                                                <th rowspan="1" colspan="1">Amount</th>
                                                <th rowspan="1" colspan="1">Start Date</th>
                                                <th rowspan="1" colspan="1">End Date</th>
                                                <th rowspan="1" colspan="1">Status</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody id="searchdata">
                                            
                                        </tbody>
                                    </table>
                                </form>
                            </div>
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

<style>
    
     span.select2-container{
        width: 100% !important;  
        min-width:125px; !important;
    }
     #items_status + span.select2-container{
        max-width: 20%;  
    }
    thead input {
        width: 100%;
    }
    
    
     .table.table-bordered.table-striped.table-hover thead > tr{
     	background: #03a9f4 none repeat scroll 0 0 !important;
     }
     
     table tbody tr:nth-child(even) td{
    	background-color: #f05a2814;
    }
    /*table tbody tr:nth-child(even) td{*/
    /*	background-color: #f05a2814;*/
    /*}*/
    table.dataTable tbody td {
        padding: 5px 10px;
    }
    table.dataTable thead th{
        padding: 8px 10px;
    }
    
    
</style>
@endsection


@section('script_files')
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/datatables.min.css">
 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/datatables.min.js"></script>
<link type="text/css" href="view/stylesheet/select2/css/select2.min.css" rel="stylesheet">
<script src="view/javascript/select2/js/select2.min.js"></script> -->

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/datatables.min.js"></script>

<link type="text/css" href="/stylesheet/select2/css/select2.min.css" rel="stylesheet" />
<script src="/javascript/select2/js/select2.min.js"></script>

<link type="text/css" href="/javascript/bootstrap-datepicker.css" rel="stylesheet">
<script src="/javascript/bootstrap-datepicker.js" defer=""></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/datatables.min.js"></script>
<link type="text/css" href="/stylesheet/select2/css/select2.min.css" rel="stylesheet" />
<script src="/javascript/select2/js/select2.min.js"></script>

<script src="/javascript/bootbox.min.js" defer=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
@endsection

@section('scripts')

<script>
    $(document).on('click', '#buydown_delete', function(event) {
        event.preventDefault();
        $('#deleteItemModal').modal('show');
    });
    
    
</script>


<script type="text/javascript">
  $(window).load(function() {
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
        
        $('#table_bydown thead tr').clone(true).appendTo( '#table_bydown thead' );
        $('#table_bydown thead tr:eq(1) th').each( function (i) {
            
            var title = $(this).text();
            if(title == "Start")
            {
                $(this).html('<input type="text" name="buydown_name" class="search_text_box" placeholder="Search" style="color:black;border-radius: 4px;height:28px;"/>' );
            }
            else if(title == "BuyDown Name")
            {
                $(this).html( '<input type="text" name="buydown_name" class="search_text_box" placeholder="Search" style="color:black;border-radius: 4px;height:28px;"/>' );
            }
            else if(title == "Code")
            {
                $(this).html( '<input type="text" name="buydown_code" class="search_text_box" placeholder="Search" style="color:black;border-radius: 4px;height:28px;"/>' );
            }
            else if(title == "Amount")
            {
              $(this).html( '<input type="number" name="buydown_amt" class="search_text_box" placeholder="Search" style="color:black;border-radius: 4px;height:28px;"/>');
            } 
            
            else
            {
                $(this).html('');
            }
            
     
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
                "scrollX": true,
                // "searching": false,
                "autoWidth": false,
                // "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0,1,2, 3,4,5 ] }],
                "dom": '<"mysearch"lf>rt<"bottom"ip>',
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
                    
                    
            });
        
            $("#items_status").select2();
            $("#prom_type").select2();
            $("#prom_category").select2();
            $("#prom_status").select2();
            $("#table_bydown_filter").hide();
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