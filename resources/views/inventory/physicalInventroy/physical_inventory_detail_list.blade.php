@extends('layouts.layout')

@section('title', 'Physical Inventory Detail')

@section('main-content')

<div id="content">

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase">Physical Inventory</span>
                </div>
                <div class="nav-submenu">
                  <button type="button" class="btn btn-gray headerblack basic-button-small text-dark" id="assign_users" data-toggle="modal" data-target="#userModal" style="width: 15%">Assign Users</button>
                  <button title="Commit" class="btn btn-gray headerblack basic-button-small text-dark" id="commit_button" style="width: 13%"><i class="fa fa-save"></i>&nbsp;&nbsp;Commit</button>
                  <button title="Submit" title="Update" class="btn btn-gray headerblack basic-button-small text-dark"  id="calculate" style="width: 13%">Calculate</button>
                  <button id="import_csv_button" title="Import Physical Inventory" class="btn btn-gray headerblack basic-button-small text-dark" id="import_inventory" style="width: 20%"><i class="fa fa-file"></i>&nbsp;&nbsp;Import Inventory</button>
                  <input type="submit" form="export_csv" id="export_csv_button" title="Export In CSV" value="Export In CSV" class="btn btn-gray headerblack basic-button-small text-dark"style="width: 15%" >
                  <a type="button" href="<?php echo $data['cancel'];?>" class="btn btn-danger buttonred buttons_menu basic-button-small" id="cancel_btn" style="width: 13%"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
  
    <div class="container-fluid section-content">
        
        @if (isset($data['error']))
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>{{ $data['error'] }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif
        @if (isset($data['error_warning']))
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ $data['error_warning'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif
        @if (isset($data['success']))
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ $data['success'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif
        
        <div class="panel panel-default">
          
            <div class="panel-body padding-left-right">

              <span class="text-danger">!WARRNING :- Don't use browser back button, use cancel button for back.</span>
              
                <form method="POST" id="calculateform" >
                    @csrf
                    <input type="hidden" name="action" value="<?php echo isset($data['action']) ? $data['action'] : ''; ?>">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            
                          <div class="mytextdiv">
                            <div class="mytexttitle font-weight-bold text-uppercase">
                                Physical Info
                            </div>
                            <div class="divider font-weight-bold"></div>
                          </div>

                          <div class="py-3">
                            <div class="row">
                                <div class="col-md-12 mx-auto">
                                    
                                    <div class="form-group row ">
                                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                            <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                                <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Ref. Number</label>
                                            </div>
                                            <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                              <input type="text" name="vrefnumber" maxlength="30" value="<?php echo isset($data['vrefnumber']) ? $data['vrefnumber'] : ''; ?>" placeholder="Ref. Number" class="form-control adjustment-fields" required id="vrefnumber" readonly/>
                                              <input type="hidden" name="ipiid" value="<?php echo $data['ipiid'] ?>" >
                                              <?php if (isset($data['error_vrefnumber'])) { ?>
                                                  <div class="text-danger"><?php echo $data['error_vrefnumber']; ?></div>
                                              <?php } ?>
                                                
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                          <div class="col-6 col-md-6 col-sm-6 col-lg-6">
          
                                            <label for="inputCreated" class="p-2 float-right text-uppercase">Title</label>
                                          </div>
                                          <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                            <input type="text" name="vordertitle" maxlength="50" value="<?php echo isset($data['vordertitle']) ? $data['vordertitle'] : ''; ?>" placeholder="Title" class="form-control adjustment-fields" id="vordertitle" required/>
                                        
                                            <?php if (isset($data['error_vordertitle'])) { ?>
                                              <div class="text-danger"><?php echo $data['error_vordertitle']; ?></div>
                                            <?php } ?>
                                          </div>
                                        </div>

                                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                            
                                          <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            <label for="inputReceived" class="p-2 float-right text-uppercase">Status</label>
                                          </div>
                                          <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            
                                            <input type="text" name="estatus" maxlength="10" value="<?php echo isset($data['estatus']) ? $data['estatus'] : 'Open'; ?>" placeholder="Status" class="form-control adjustment-fields" readonly/>
                                             
                                          </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
          
                            <div class="row">
                              <div class="col-md-12 mx-auto">
                                  
                                  <div class="form-group row">
                                      
                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
        
                                          <label for="inputInvoice" class="p-2 float-right text-uppercase">Created Date</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                          <?php
                                              if(isset($data['dcreatedate']) && !empty($data['dcreatedate']) && $data['dcreatedate'] != '0000-00-00 00:00:00'){
                                              
                                                $dcreatedate =  DateTime::createFromFormat('Y-m-d H:i:s', $data['dcreatedate'])->format('m-d-Y H:i:s');
                                              }else{
                                                $dcreatedate = '';
                                              }
                                          
                                          ?>
                                          
                                          <?php if(isset($data['ipiid']) && $data['ipiid'] != ''){?>
                                            <input type="text" name="dcreatedate" value="<?php echo $dcreatedate;?>" placeholder="Created Date" class="form-control adjustment-fields" id="dcreatedate" readonly style="pointer-events: none;" />
                                          <?php } else { ?>
                                            <input type="text" name="dcreatedate" value="<?php echo date('m-d-Y H:i:s');?>" placeholder="Created Date" class="form-control adjustment-fields" id="dcreatedate" readonly style="pointer-events: none;"/>
                                          <?php } ?>
                        
                                          <?php if (isset($data['error_dcreatedate'])) { ?>
                                            <div class="text-danger"><?php echo $data['error_dcreatedate']; ?></div>
                                          <?php } ?>

                                        </div>
                                      </div>

                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                            <?php if(isset($data['action']) && ($data['action'] == 'edit_calculate' || $data['action'] == 'selected_edit_calculate')){ ?>
                                            <label class="p-2 float-right text-uppercase" for="input-template">Last Calculated Date</label>
                                            <?php }else{ ?>
                                                <label class="p-2 float-right text-uppercase" for="input-template">Calculate Date</label>
                                            <?php }
                                                if(isset($data['dcalculatedate']) && !empty($data['dcalculatedate']) && $data['dcalculatedate'] != '0000-00-00 00:00:00'){
                                                  $dcalculatedate =  DateTime::createFromFormat('Y-m-d H:i:s', $data['dcalculatedate'])->format('m-d-Y H:i:s');
                                                }else{
                                                  $dcalculatedate = '';
                                                }
                                            
                                            ?>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                          <input type="text" name="dcalculatedate" value="<?php echo $dcalculatedate;?>" placeholder="" class="form-control adjustment-fields" id="dcalculatedate" readonly style="pointer-events: none;"/>
                                        
                                          <?php if (isset($data['error_dcalculatedate'])) { ?>
                                            <div class="text-danger"><?php echo $data['error_dcalculatedate']; ?></div>
                                          <?php } ?>
                                        </div>
                                      </div>
                                      
                                  </div>
                                  
                              </div>
                            </div>
                          </div>
                            
                        </div>
                        
                    </div>
                </form>
                
                <div class="mytextdiv">
                  <div class="mytexttitle font-weight-bold text-uppercase">
                      Items
                  </div>
                  <div class="divider font-weight-bold"></div>
                </div>
                
                <br>
                
                <div class="table-responsive">
                    <table id="item" class="table table-hover promotionview" style="width: 100%;">
                            
                        <thead>
                            <tr class="header-color">
                              <th class="text-left text-uppercase">SKU</th>
                              <?php $dynamic_data[] = "vbarcode";?>
                              <th class="text-left text-uppercase">Itemname</th>
                              <?php $dynamic_data[] = "vitemname";?>
                              <th class="text-center text-uppercase">Unit Cost</th>
                              <?php $dynamic_data[] = "unitcost";?>
                              <th class="text-center text-uppercase">Unit Price</th>
                              <?php $dynamic_data[] = "dunitprice";?>
                              <th class="text-center text-uppercase">QOH</th>
                              <?php $dynamic_data[] = "iqtyonhand";?>
                              <th class="text-center text-uppercase">Cost Total</th>
                              <?php $dynamic_data[] = "costtotal";?>
                              <th class="text-center text-uppercase">Price Total</th>
                              <?php $dynamic_data[] = "pricetotal";?>
                              <th class="text-center text-uppercase">Invt. Count</th>
                              <?php $dynamic_data[] = "ndebitqty";?>
                          
                            </tr>
                        </thead>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>

<form action="<?php echo $data['export_csv']; ?>" method="post" id="export_csv">
    @csrf
    <input type="hidden" id="ipiid" name="ipiid" value="<?php echo $data['ipiid'];?>">
    <input type="hidden" name="action" value="<?php echo $data['action']; ?>" >
</form>

@endsection


@section('page-script')

<style type="text/css">

  .padding-left-right{
    padding: 0 2% 0 2%;
  }

  .span_field span{
    display: inline-block;
  }
  
  .pagination{
    float: right !important;
    }
    
    .disabled, #item_ellipsis{
        pointer-events:none;
    }
</style>

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/purchaseorder.css') }}">

<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="{{ asset('javascript/bootbox.min.js') }} "></script>

<!-- DataTables -->

<script src="{{ asset('javascript/select2/js/select2.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script src=""></script>
<script>

$(document).ready(function() { 
    
    var results;
    var action = "<?=$data['action']; ?>";
    var ipiid = "<?=$data['ipiid']; ?>";
    
    var url;

    if(action == 'import'){
        url = "<?php echo $data['import_display'] ?? ''; ?>";
        url = url.replace(/&amp;/g, '&');
    }else{
        url = "<?php echo $data['display_selected_data']; ?>";
        url = url.replace(/&amp;/g, '&');
    }
    
    
    var dynamic_data = JSON.parse('<?php echo json_encode($dynamic_data);?>');
    var data_array = [];
    $.each(dynamic_data, function(key,value) {    
         data_array.push({ "data": value });
      });
    
    table =   $("#item").DataTable({
                "searching": false,
                "bSort": false,
                "autoWidth": false,
                "fixedHeader": false,
                "processing": true,
                "iDisplayLength": 20,
                "serverSide": true,
                
                "bLengthChange": false,
                
                "language": {
                                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                
                "dom": '<"mysearch"lf>tr<"bottom"ip>',
                
                "ajax": {
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'POST',
                    
                  "dataSrc": function ( json ) {
                        
                        $("div#divLoading").removeClass('show');
                        return json.data;
                    } 
                },
                columns : data_array,
                rowCallback: function(row, data, index){ 
                    
                    if(parseInt(data['inv_count']) > 0){
                        console.log("done");
                        $('td', row).css('background-color', '#33FF7A');
                    }else{
                        console.log("Ndone");
                        $('td', row).css('background-color', '#FFB533');
                    }
                    
                }
                
            });
        
    // var totalDisplayRecord = table.page.info().recordsDisplay;
    
    $("#export_csv_button").on("click", function() {
        
        $('#exportMsgModal').modal('show');
        setTimeout(function(){
            $('#exportMsgModal').modal('hide'); 
            $("div#divLoading").removeClass('show');
        },2000);
        
    });
    
});
</script>
    

<!----  Session ----->

<!--<script src="/view/javascript/jquery/jquery.session.js"></script>-->

<script type="text/javascript">

var iitemid_arr = [];
var vbarcode_arr = [];
function getinventorycount(invtcount, iitemid){
    var action = '<?=$data['action']; ?>';
    var invtcount = invtcount; 
    var iitemid = iitemid;
    var vbarcode = $('#ndebitqty_'+iitemid).data("vbarcode");
    
    // var unique_iitemid_arr=[];
    var unique_vbarcode_arr=[];
    $.each(vbarcode_arr, function(i,e){
        $.each(e,function(ind,el){
            if(el.vbarcode != vbarcode){
               unique_vbarcode_arr.push([{'vbarcode':el.vbarcode, 'invtcount':el.invtcount, 'iitemid':el.iitemid}]);
            }
        });
    });
    
    vbarcode_arr = [];
    vbarcode_arr = unique_vbarcode_arr;
    var arr_details = [{'vbarcode':vbarcode, 'invtcount':invtcount, 'iitemid':iitemid}];
    vbarcode_arr.push(arr_details);
    
    if(action == 'import'){
        
        var edit_import_inventory_count = "<?= $data['edit_import_inventory_count'] ?? ''; ?>";
        edit_import_inventory_count = edit_import_inventory_count.replace(/&amp;/g, '&');
        
        $.ajax({
                url: edit_import_inventory_count,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                type: 'post',
                data : {arr_details : arr_details},
                success:function(data){
                    if(data.success == true)
                    {
                        console.log(data.success_msg);
                        
                    }
                    else
                    {
                        
                        console.log('failed');
                    }
                    
                }
        });
    }else{
        
        var session_url = "<?= $data['create_inventory_session']; ?>";
        session_url = session_url.replace(/&amp;/g, '&');
        
        $.ajax({
                url: session_url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                type: 'post',
                data : {vbarcode_arr : vbarcode_arr},
                success:function(data){
                    if(data.success == true)
                    {
                        console.log(data.success_msg);
                        console.log(data.selected_vbarcode_arr);
                    }
                    else
                    {
                        console.log(data.selected_vbarcode_arr);
                        console.log('failed');
                    }
                    
                }
        });
    }
    // var unique_iitemid_arr = a.filter(function(itm, i, a) {
    //     return i == a.indexOf(itm);
    // });
    
    // console.log(invtcount); console.log(vbarcode); console.log(iitemid);
    // var vbarcode = $('#ndebitqty_').data("iitemid");
}
// $(this).data("id");
</script>

<script type="text/javascript">

$(document).ready(function(){
    var action = '<?=$data['action']; ?>';
    
    //======this is for commit=========
    var url_calculate_commit = "<?php echo $data['url_calculate_commit'] ?? ''; ?>";
    url_calculate_commit = url_calculate_commit.replace(/&amp;/g, '&');
    
    if(url_calculate_commit == ''){
        $('#commit_button').hide();
        $('#assign_users').css('width', '16%');
        $('#calculate').css('width', '14%');
        $('#import_csv_button').css('width', '25%');
        $('#export_csv_button').css('width', '18%');
        $('#cancel_btn').css('width', '15%');
    }
    
    $(document).on('click', '#commit_button', function(){
        
        if($('form#calculateform #vordertitle').val() == ''){
          // alert('please enter title');
          bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: "please enter title", 
            callback: function(){}
          });
          $('form#vordertitle #vordertitle').focus();
          return false;
        }
        $("div#divLoading").addClass('show');
        $.ajax({
          url : url_calculate_commit,
          headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
          data : $('form#calculateform').serialize(),
          type : 'POST',
          success: function(data) {
    
            $("div#divLoading").removeClass('show');
            $('#success_alias').html('<strong>'+ data.success +'</strong>');
            $('#successModal').modal('show');
            $('#estatus').val(data.estatus);
            
            var url_view_calculate = "<?php echo $data['url_view_calculate']; ?>";
            url_view_calculate = url_view_calculate.replace(/&amp;/g, '&');
            
                setTimeout(function(){
                  window.location.href = url_view_calculate;
                  
                }, 3000);
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
            check_invoice_number = false;
            $("div#divLoading").removeClass('show');
            return false;
          }
    });
    });
    
    
    //========this is for calculate==========
    var url_calculate = "<?php echo $data['url_calculate']; ?>";
    url_calculate = url_calculate.replace(/&amp;/g, '&');
    
    $(document).on('click', '#calculate', function(){
        
        if($('form#calculateform #vordertitle').val() == ''){
          // alert('please enter title');
          bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: "please enter title", 
            callback: function(){}
          });
          $('form#vordertitle #vordertitle').focus();
          return false;
        }
        
        $('.ndebitqty').each(function(i){
            if($('.ndebitqty').val() == ''){
                bootbox.alert({ 
                    size: 'small',
                    title: "  ", 
                    message: "please enter inventory count of all items", 
                    callback: function(){}
                });
              $('.ndebitqty').focus();
              return false;
            }
            return false;
        });
        $("div#divLoading").addClass('show');
        
        $.ajax({
          url : url_calculate,
          headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
          data : $('form#calculateform').serialize(),
          type : 'POST',
          success: function(data) {
            
            if(data.success){
                $("div#divLoading").removeClass('show');
                $('#success_alias').html('<strong>'+ data.success +'</strong>');
                $('#successModal').modal('show');
                $('#estatus').val(data.estatus);
                
                var url_view_calculate = "<?php echo $data['url_view_calculate']; ?>";
                url_view_calculate = url_view_calculate.replace(/&amp;/g, '&');
                window.location.href = url_view_calculate;
                $("div#divLoading").addClass('show');
            }
            else{
                $('#error_alias').html('<strong>'+ data.error +'</strong>');
                $('#errorModal').modal('show');
                check_invoice_number = false;
                $("div#divLoading").removeClass('show');
                return false;
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
        check_invoice_number = false;
        $("div#divLoading").removeClass('show');
        return false;
      }
    });
    });
});
 

 
 $(document).on('click', '#import_csv_button', function(event) {
    event.preventDefault();
    $('#myModalImport').modal('show');
 });
 
 $(document).on('click', '#import_invt', function(event) {
    //event.preventDefault();
    if($('#import_physical_inventory_file').val() == ''){
          // alert('please enter title');
          bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: "Please Select File", 
            callback: function(){}
          });
          
          return false;
    }else{
        $('#myModalImport').modal('hide');
        $('#importMsgModal').modal('show');
    }
 });
 
 
 </script>
 
 <script type="text/javascript">
 
 $(document).ready(function(){
    <?php if(isset($data['code']) && $data['code'] == 0){ ?> 
    
    // $('#commit_button').attr("disabled", true);
    // $('#calculate').attr("disabled", true);
    
    <?php }?>
    var unset_session_inventory_count = "<?= $data['unset_session_inventory_count']; ?>";
    unset_session_inventory_count = unset_session_inventory_count.replace(/&amp;/g, '&');

    $.ajax({
                url: unset_session_inventory_count,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                type: 'get',
                success:function(data){ 
                    if(data.success == true)
                    {
                        console.log(data);
                    }
                    else
                    {
                         console.log(data);   
                    }
                    
                }
    });
    
 });
 </script>
 
 <!-- Modal -->
<div id="myModalImport" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title text-center">Import Physical Inventory</h6>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="<?php echo $data['bulk_import']; ?>" method="post" enctype="multipart/form-data" id="import_physical_inventory">
            @csrf
          <?php if(isset($data['ipiid'])){?>
            <input type="hidden" name="ipiid" id="import_ipiid" value="<?php echo $data['ipiid'];?>">
            <input type="hidden" name="action" value="<?php echo $data['action']; ?>" >
          <?php } ?>
          <input type="hidden" name="ptitle" value="<?php echo isset($data['vordertitle']) ? $data['vordertitle'] : ''; ?>" >
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <span style="display:inline-block;width:8%;">File: </span> <span style="display:inline-block;width:85%;"><input type="file" name="import_physical_inventory_file" id="import_physical_inventory_file" class="" required></span>
              </div>
            </div>
            <div class="col-md-12 text-center">
              <div class="form-group">
                <input type="submit" class="btn button-blue basic-button-medium" name="import_invt" id="import_invt" value="Import Physical Inventory">&nbsp;<button type="button" class="btn btn-default basic-button-small text-dark" data-dismiss="modal" style="border-color: black;">Cancel</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

 
 <div class="modal fade" id="successModal" role="dialog">
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
  <div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger text-center">
            <p id="error_alias"></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  
  <div class="modal fade" id="exportMsgModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
         <div class="modal-body" >
          <div class="alert alert-success">
            <p id="exportmsg_alias">CSV File Exporting....</p>
          </div>
        </div>
        
      </div>
      
    </div>
  </div>
  
  
  <div class="modal fade" id="importMsgModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
        </div>
         <div class="modal-body" >
          <div class="alert alert-success">
            <p id="exportmsg_alias">Processing ....(CSV File Importing....)</p>
          </div>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="userModal" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Assign Users For Physical Inventory</h6>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <!--<div class="row">
                        <div class="col-md-12">
                        <button type="button" class="btn btn-primary pull-right" id="remove_button">Remove</button>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-12">
                        <div class="table-responsive">
                            <div class="form-group">
                                <form method="POST" enctype="multipart/form-data" >
                                    @csrf
                                    <table class="table table-hover promotionview" id="scanned_data_table">
                                    
                                        <thead id="txt" style="font-size:12px;">
                                            <tr class="header-color">
                                                <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" name="itemid_selected" onchange="$('input[name*=\'selectbox\']').prop('checked', this.checked);" /></th>
                                                <th class="text-center" style="vertical-align : middle;">User Name</th>
                                            </tr>
                                                
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['users'] as $user) { ?>
                                                <tr>
                                                    <td><input type="checkbox" class="form-control" name="selectbox[]" id="user_<?php echo $user['iuserid']; ?>" value="<?php echo $user['iuserid']; ?>" data-userid="<?php echo $user['iuserid']; ?>" ></td>
                                                    <td><?php echo $user['vfname']." (".$user['vemail'].")"; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="col-md-12 text-center">
                                        <div class="form-group">
                                            <input type="hidden" name="ipiid" id='ipiid_assign' value="<?php echo $data['ipiid'];?>">
                                            <button type="button" class="btn button-blue center basic-button-small" id="assign_users_btn" data-dismiss="modal" >Assign</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$(this).closest('tr').remove();">Close</button>
        </div>-->
      </div>
      
    </div>
</div>

<script>

$(document).ready(function(){
    
    $(document).on('click', '#assign_users_btn', function(){
        var selected_userid = [];
        $.each($('input[name="selectbox[]"]:checked'), function(){
            
            selected_userid.push($(this).val()); 
        });
        
        var ipiid = $('#ipiid_assign').val();
        
        var assign_inventory_users_url = "<?php echo $data['assign_inventory_users_url'] ;?>";
        assign_inventory_users_url = assign_inventory_users_url.replace(/&amp;/g, '&');
        
        $.ajax({
            
            url: assign_inventory_users_url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            type: 'POST',
            data: {selected_userid: selected_userid, ipiid: ipiid},
            success: function(response){
                
                console.log(response.msg);
                if(response.result){
                    $('#success_alias').text(response.msg);
                    $('#successModal').modal('show');
                }
            }
        });
    });
    
    <?php if(isset($data['assigned_users']) && !empty($data['assigned_users'])) { ?>
        var assigned_users = '<?php echo json_encode($data["assigned_users"]); ?>';
        
        $.each($.parseJSON(assigned_users), function( index, value ) {
            // console.log(value.user_id);
            $('#user_'+value.user_id).prop("checked", true);
        });
    <?php } ?>
    
    $(document).on('input', 'input[name="vordertitle"]', function(){
       
        var ptitle = $('input[name="vordertitle"]').val();
        $('input[name="ptitle"]').val(ptitle);
    });
});
    
</script>

@endsection