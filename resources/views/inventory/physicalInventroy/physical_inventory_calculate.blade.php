<style>
    table { table-layout:fixed; }

    table td { 
        overflow: hidden;
        /*white-space: wrap;*/
    }
    
</style>
@extends('layouts.layout')

@section('title', 'Physical Inventory Calculate')

@section('main-content')

<div id="content">

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase">Physical Inventory</span>
                </div>
                <div class="nav-submenu">
                  <button type="button" class="btn btn-gray headerblack basic-button-small text-dark" id="report" data-toggle="modal" data-target="#reportModal"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Report</button>
                  <button title="Commit" class="btn btn-gray headerblack basic-button-small text-dark" id="commit_button" href="#downloadMsgModal" data-backdrop="static" data-toggle="modal"><i class="fa fa-save"></i>&nbsp;&nbsp;Commit</button>
                  <button title="Update" class="btn btn-gray headerblack basic-button-small text-dark" id="recalculate_all_button" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;Re Calculate(All)</button>
                  <button title="Update" class="btn btn-gray headerblack basic-button-medium text-dark" id="recalculate_selected_button" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;Re Calculate(Selected)</button>
                  <!--<a href="<?= $data['edit_calculate']; ?>" title="Update" class="btn btn-primary" id="edit_button" ><i class="fa fa-save"></i>&nbsp;&nbsp;Re Calculate</a> -->    
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
  
    <div class="container-fluid section-content">
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
                
                <form method="post" id="form-physical_inventory-detail">
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
                                            
                                            <input type="text" name="vordertitle" maxlength="50" value="<?php echo isset($data['vordertitle']) ? $data['vordertitle'] : ''; ?>" placeholder="Title" class="form-control adjustment-fields" id="vordertitle" readonly/>
                                        
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
                                            
                                            <input type="text" name="status" maxlength="10" value="<?php echo isset($data['estatus']) ? $data['estatus'] : 'Calculated'; ?>" placeholder="Status" class="form-control adjustment-fields" id="estatus" readonly/> 
                                             
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
                                            <input type="text" name="createdate" value="<?php echo $dcreatedate;?>" placeholder="Created Date" class="form-control adjustment-fields" id="dcreatedate" readonly style="pointer-events: none;" />
                                          <?php } else { ?>
                                            <input type="text" name="createdate" value="<?php echo date('m-d-Y');?>" placeholder="Created Date" class="form-control adjustment-fields" id="dcreatedate" readonly style="pointer-events: none;"/>
                                          <?php } ?>
                        
                                          <?php if (isset($data['error_dcreatedate'])) { ?>
                                            <div class="text-danger"><?php echo $data['error_dcreatedate']; ?></div>
                                          <?php } ?>

                                        </div>
                                      </div>

                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                            <label class="p-2 float-right text-uppercase" for="input-template">Calculated Date</label>
                                            
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                          <?php
                                              if(isset($data['dcalculatedate']) && !empty($data['dcalculatedate']) && $data['dcalculatedate'] != '0000-00-00 00:00:00'){
                                                $dcalculatedate =  DateTime::createFromFormat('Y-m-d H:i:s', $data['dcalculatedate'])->format('m-d-Y H:i:s');
                                              }else{
                                                $dcalculatedate = '';
                                              }
                                          
                                          ?>

                                          <?php if(isset($dcalculatedate) && $dcalculatedate != '') { ?>
                                              <input type="text" name="calculatedate" value="<?php echo $dcalculatedate;?>" placeholder="Calculated Date" class="form-control adjustment-fields" id="dcalculatedate" readonly style="pointer-events: none;" />
                                          <?php } else { ?>  
                                            <input type="text" name="calculatedate" value="<?php echo date('m-d-Y');?>" placeholder="Calculated Date" class="form-control adjustment-fields" id="dcalculatedate" readonly style="pointer-events: none;"/>
                                          <?php } ?>

                                          <?php if (isset($data['error_dcalculatedate'])) { ?>
                                            <div class="text-danger"><?php echo $data['error_dcalculatedate']; ?></div>
                                          <?php } ?>
                                        </div>
                                      </div>

                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                          <?php
                                            if(isset($data['dclosedate']) && !empty($data['dclosedate']) && $data['dclosedate'] != '0000-00-00 00:00:00'){ ?>
                                                <label class="col-sm-4 control-label" for="input-template">Commited Date</label>
                                            <?php $dclosedate =  DateTime::createFromFormat('Y-m-d H:i:s', $data['dclosedate'])->format('m-d-Y H:i:s');
                                            }else{
                                              $dclosedate = '';
                                            }
                                    
                                          ?>
                                            
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                          <?php if(isset($dclosedate) && $dclosedate != '') { ?>
                                              <input type="text" name="dclosedate" value="<?php echo $dclosedate;?>" placeholder="Commited Date" class="form-control adjustment-fields" id="dclosedate" readonly style="pointer-events: none;" />
                                          <?php } ?>  
                                          
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
                <br>

                <div class="mytextdiv">
                  <div class="mytexttitle font-weight-bold text-uppercase">
                      Items Info
                  </div>
                  <div class="divider font-weight-bold"></div>
                </div>
                
                <br>

                <div class="panel panel-default">

                    <div class="panel-body padding-left-right">

                        <div class="row">
                            <div class="col-md-3">
                                <p><b>Total Counted Qty: </b><?php echo (int)$data['total_counted']; ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><b>Total Expected Qty: </b><?php echo (int)$data['total_expected']; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Total Difference Qty: </b><?php echo (int)$data['total_difference']; ?></p>
                            </div>
                            <div class="col-md-2">
                                <p></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p><b>Total Counted cost: </b><?php $total_counted_cost = $data['total_counted_cost'] < 0 ? '-$'.number_format(abs($data['total_counted_cost']), 2): '$'.number_format($data['total_counted_cost'], 2); echo $total_counted_cost; ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><b>Total Expected cost: </b><?php $total_expected_cost = $data['total_expected_cost'] < 0 ? '-$'.number_format(abs($data['total_expected_cost']), 2): '$'.number_format($data['total_expected_cost'], 2); echo $total_expected_cost; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Total Difference cost: </b><?php $total_difference_cost = $data['total_difference_cost'] < 0 ? '-$'.number_format(abs($data['total_difference_cost']), 2): '$'.number_format($data['total_difference_cost'], 2); echo $total_difference_cost; ?></p>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p><b>Total Counted price: </b><?php $total_counted_price = $data['total_counted_price'] < 0 ? '-$'.number_format(abs($data['total_counted_price']), 2): '$'.number_format($data['total_counted_price'], 2); echo $total_counted_price; ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><b>Total Expected price: </b><?php $total_expected_price = $data['total_expected_price'] < 0 ? '-$'.number_format(abs($data['total_expected_price']), 2): '$'.number_format($data['total_expected_price'], 2); echo $total_expected_price; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Total Difference price: </b><?php $total_difference_price = $data['total_difference_price'] < 0 ? '-$'.number_format(abs($data['total_difference_price']), 2): '$'.number_format($data['total_difference_price'], 2); echo $total_difference_price; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p></p>
                            </div>
                            <div class="col-md-3">
                                <p></p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Total Profit loss difference: </b><?php $total_profit_loss_difference = $data['total_profit_loss_difference'] < 0 ? '-$'.number_format(abs($data['total_profit_loss_difference']), 2): '$'.number_format($data['total_profit_loss_difference'], 2); echo $total_profit_loss_difference; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="loading"><span>Items Loading..</span><div class="row animate" id="loader" ontenteditable="false" data-placeholder="Ldding.."><div id="progress"></div></div></div>
                    
                <div class="table">
                
                    <table id="item" class="table table-bordered table-condensed promotionview" style="width:100%;">
                        
                        <thead  class="header-color">
                            
                          <tr>
                            <th class="text-center" style="vertical-align : middle;">SKU</th>
                            <th class="text-center" style="vertical-align : middle;">Item Name</th>
                            <th class="text-center">Counted Qty</th>
                            <th class="text-center">Counted Cost</th>
                            <th class="text-center">Counted Price</th>
                            <th class="text-center">Expected Qty<small style="font-size: 8px;">(QOH - Sale)</small></th></th>
                            <th class="text-center">Expected Cost</th>
                            <th class="text-center">Expected Price</th>
                            <th class="text-center">Diff Qty</th>
                            <th class="text-left text-uppercase" style="vertical-align : middle; width: 75px;">Diff(%)</th>
                            <th class="text-left text-uppercase" style="vertical-align : middle;">Cost Diff.</th>
                            <th class="text-left text-uppercase" style="vertical-align : middle;">Price Diff.</th>
                            <th class="text-center" style="vertical-align : middle;">Profit/Loss Diff.</th>
                        </tr>
                            
                              <?php $dynamic_data[] = "vbarcode";?>
                              <?php $dynamic_data[] = "vitemname";?>
                              <?php $dynamic_data[] = "ndebitqty";?>
                              <?php $dynamic_data[] = "counted_cost";?>
                              <?php $dynamic_data[] = "counted_price";?>
                              <?php $dynamic_data[] = "expected";?>
                              <?php $dynamic_data[] = "expected_cost";?>
                              <?php $dynamic_data[] = "expected_price";?>
                              <?php $dynamic_data[] = "difference";?>
                              <?php $dynamic_data[] = "difference_percentage";?>
                              <?php $dynamic_data[] = "diff_total_cost";?>
                              <?php $dynamic_data[] = "diff_total_price";?>
                              <?php $dynamic_data[] = "diff_profit_loss";?>
                            
                        </thead>
                        <tbody>
                        
                        </tbody>
                      
                    </table>
                </div>
                <div class="form-group">
                    <span class="row" id="recordsTotal"></span>
                    <span style="width: 1px;color:black;" class="text-center"><input type="checkbox" name="selected" id="hideshow" checked /></span>
                    <label class="control-label" for="input-template">Hide Rows whose Difference Column is zero</label>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('page-script')

<style type="text/css">

  .padding-left-right{
    padding: 0 2% 0 2%;
  }

  .span_field span{
    display: inline-block;
  }
  
  .dataTables_scrollHeadInner{
      height: 60px;
  }
  div.dataTables_processing {
      z-index: 1;
    }

.pagination{
    float: right !important;
}

.table-condensed{
      font-size: 11px;
    }
    
    
    
</style>

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/purchaseorder.css') }}">

<link href="{{ asset('stylesheet/loader.css') }}" rel = "stylesheet">

<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="{{ asset('javascript/bootbox.min.js') }} "></script>

<!-- DataTables -->
<!--<script src="/view/javascript/jquery/dataTables.scroller.js"></script>-->
<script src="https://cdn.datatables.net/scroller/2.0.1/js/dataTables.scroller.min.js"></script>
<!--<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>-->

<script src="{{ asset('javascript/select2/js/select2.min.js') }}"></script>

<script type="text/javascript">
$(document).ready(function(){
    
    $('#loading').hide();
    
    var recordsTotal;
    var url = '<?php echo $data['getshow_data']; ?>';
    url = url.replace(/&amp;/g, '&');
    
    var dynamic_data = JSON.parse('<?php echo json_encode($dynamic_data);?>');
    var data_array = [];
    $.each(dynamic_data, function(key,value) {    
         data_array.push({ "data": value });
    });
    
    
    table =   $("#item").DataTable({
                "scrollY" : "500px",
                "paging" : true,
                "info" : false,
                "iDisplayLength": 20,
                "autoWidth": false,
                "bSort": false,
                "processing" : true,
                "serverSide" : true,
                "language": {
                                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
            
                "dom": 'tr<"bottom"i>',
                
                "ajax": {
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'POST',
                    
                  "dataSrc": function ( json ) {
                        recordsTotal = "Showing "+json.recordsTotal+" Items";
                        $('#recordsTotal').text(recordsTotal);
                        $("div#divLoading").removeClass('show');
                        $('#loading').show();
                        return json.data;
                    }
                },
                // "columnDefs": [
                //     { "width": "90px", "targets": 0 },
                //     { "width": "120px", "targets": 1 },
                //     { "width": "41px", "targets": 2 },
                //     { "width": "55px", "targets": 3 },
                //     { "width": "55px", "targets": 4 },
                //     { "width": "90px", "targets": 5 },
                //     { "width": "58px", "targets": 6 },
                //     { "width": "60px", "targets": 7 },
                //     { "width": "63px", "targets": 8 },
                //     { "width": "105px", "targets": 9 },
                //     { "width": "64px", "targets": 10 },
                //     { "width": "83px", "targets": 11 },
                //     { "width": "102px", "targets": 12 }
                //   ],
                "deferRender": true,
                "columns" : data_array,
                rowCallback: function(row, data, index){ 
                    
                    $(row).find('td:eq(4)').attr('id', 'row_'+index);
                    
                  	if(parseInt(data['difference']) > 0){
                    	$(row).find('td:eq(8)').css('background-color', 'red');
                    	$(row).find('td:eq(8)').css('color', 'white');
                    	$(row).find('td:eq(9)').css('background-color', 'red');
                    	$(row).find('td:eq(9)').css('color', 'white');
                    	$(row).find('td:eq(10)').css('background-color', 'red');
                    	$(row).find('td:eq(10)').css('color', 'white');
                    	$(row).find('td:eq(11)').css('background-color', 'red');
                    	$(row).find('td:eq(11)').css('color', 'white');
                    	// $(row).find('td:eq(11)').css('width', '83px');
                    	$(row).find('td:eq(12)').css('background-color', 'red');
                    	$(row).find('td:eq(12)').css('color', 'white');
                    	// $(row).find('td:eq(12)').css('width', '102px');
                    	// $(row).find('td:eq(1)').css('width', '120px');
                    	
                    }else if(parseInt(data['difference']) < 0){
                        $(row).find('td:eq(8)').css('background-color', 'green');
                        $(row).find('td:eq(8)').css('color', 'white');
                        $(row).find('td:eq(9)').css('background-color', 'green');
                        $(row).find('td:eq(9)').css('color', 'white');
                        $(row).find('td:eq(10)').css('background-color', 'green');
                    	$(row).find('td:eq(10)').css('color', 'white');
                    	$(row).find('td:eq(11)').css('background-color', 'green');
                    	$(row).find('td:eq(11)').css('color', 'white');
                    	// $(row).find('td:eq(11)').css('width', '83px');
                    	$(row).find('td:eq(12)').css('background-color', 'green');
                    	$(row).find('td:eq(12)').css('color', 'white');
                    	// $(row).find('td:eq(12)').css('width', '102px');
                    	// $(row).find('td:eq(1)').css('width', '120px');
                    	
                    }
                
                } 
            });
    
    // add_row();	$(row).find('td:eq(1)').attr('style', 'width:120px !important');
    // $('.dataTables_scrollBody').scroll(function(){ 
        
        
        
    // });
});

</script>

<script>
    $(document).ready(function(){
        // var info = table.page.info(); console.log(info);
        var sum = 0;
        // setTimeout( function(){ var totalData = $('#recordsTotal').text().match(/\d+/)[0]; console.log(totalData); }, 5000);
        var count = 0;
        $('#hideshow').on('click', function(){
            count = 0;
            sum = 0;
            $('#progress').css('width', '1%');
            $('#loading').hide();
            if($(this).prop("checked") == false){
               var searchVal = 'show';
                    table
                        .column(4)
                        .search( searchVal)
                        .draw();
               // $('.showall').hide();
            }
            else if($(this).prop("checked") == true){
               var searchVal = 'hide';
                    table
                        .column(4)
                        .search( searchVal)
                        .draw();
            }
            add_row();
        });
        
        setTimeout( function(){
            if(count == 0){
                add_row();
            }
        }, 3000);
        
        function add_row(){
            var ur = '<?php echo $data['addshow_data']; ?>'; 
            ur = ur.replace(/&amp;/g, '&');
            
            var type;
            if($('#hideshow').prop("checked") == false){
                type = 'show';
            }else if($('#hideshow').prop("checked") == true){
                type = 'hide';
            }
            count = count+1;
            var start = 20*count;
          
            console.log(count);
            $.ajax({
                type: "POST",
                url: ur,
                headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                data:{start:start, type:type},
                cache: false,
                success: function(data){
                    var data1 = data.data;
                    sum = sum+20;
                    
                    var width = ((sum/data.totalData)*100)+10;
                    if(width >100){
                       width = 100;  
                    }
                    width = width+'%';
                    $('#progress').css('width', width);
                    console.log(width);
                   
                    $('#item_processing').hide();
                    $('#item tbody tr:last').after(data1);
                    delete data;
                    
                    if(data1.length != 0){
                        add_row();
                        $('#loading').show();
                    }else{
                        $('#loading').hide();
                        return false;
                    }
                }
            });
            
            start = 0;
        }
          
    });
</script>


<script type="text/javascript">
$(document).ready(function(){
    
    $('#report').attr("disabled", true);
    
    var estatus = '<?php echo $data['estatus'] ?>';
    if(estatus == 'Close'){
        $('#report').attr("disabled", false);
        $('#commit_button').attr("disabled", true);
        $('#recalculate_all_button').attr("disabled", true);
        $('#recalculate_selected_button').attr("disabled", true);
    }
        
    $(document).on('click', '#recalculate_all_button', function(){
        
        var edit_calculate = '<?php echo $data['edit_calculate']; ?>';
        edit_calculate = edit_calculate.replace(/&amp;/g, '&');
        window.location.href = edit_calculate;
        $("div#divLoading").addClass('show');
    });
    
    $(document).on('click', '#recalculate_selected_button', function(){
        
        var selected_edit_calculate = '<?php echo $data['selected_edit_calculate']; ?>';
        selected_edit_calculate = selected_edit_calculate.replace(/&amp;/g, '&');
        window.location.href = selected_edit_calculate;
        $("div#divLoading").addClass('show');
    });
    
    var url_commit = '<?php echo $data['url_commit']; ?>';
    url_commit = url_commit.replace(/&amp;/g, '&');
    
    $(document).on('click', '#commit_button', function(){
        
        // $("div#divLoading").addClass('show');
        $.ajax({
            url : url_commit,
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : $('form#form-physical_inventory-detail').serialize(),
            type : 'POST',
            success: function(data) {
                
                $("#downloadMsgModal").hide();
                $(".modal-backdrop").remove();
                $('#success_alias').html('<strong>'+ data.success +'</strong>');
                $('#successModal').modal('show');
                $('#estatus').val(data.estatus);
                
                if(data.estatus == 'Close'){
                    $('#commit_button').attr("disabled", true);
                    $('#edit_button').attr("disabled", true);
                    $('#report').attr("disabled", false);
                    var url_index = '<?php echo $data['url_index']; ?>';
                    url_index = url_index.replace(/&amp;/g, '&');
                    window.location.href = url_index;
                    
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
                $("div#divLoading").removeClass('show');
                return false;
            }
        });
    });

    
    
});
</script>

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

  <div class="modal fade" id="reportModal" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <center><b>Select Type Of Report ?</b></center>
        </div>
        <div class="modal-footer form-inline">
            <button id="pdf_summery" class="btn button-blue basic-button-small col-md-5" data-dismiss="modal">Summary</button>
            <a id="pdf_details" class="btn button-blue basic-button-small col-md-5" data-dismiss="modal" href="#downloadMsgModal" data-backdrop="static" data-toggle="modal">Details</a>
        </div>
      </div>
      
    </div>
  </div>
  
  <div class="modal fade" id="downloadMsgModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
            <center><span>Loading...</span></center>
        </div>
         <div class="modal-body" >
          <div class="row animate" id="dwnloader" ontenteditable="false" data-placeholder="Ldding.."><div id="progress"></div></div>
            
        </div>
      </div>
    </div>
  </div>
<script>
    $(document).ready(function(){
        
        const saveData = (function () {
            const a = document.createElement("a");
            document.body.appendChild(a);
            a.style = "display: none";
            return function (data, fileName) {
                const blob = new Blob([data], {type: "octet/stream"}),
                    url = window.URL.createObjectURL(blob);
                a.href = url;
                a.download = fileName;
                a.click();
                window.URL.revokeObjectURL(url);
            };
        }());
        
        $(document).on('click', '#pdf_summery', function(){
            
            var url = '<?= $data['pdf_summery']; ?>';
            url = url.replace(/&amp;/g, '&');
            
            var req = new XMLHttpRequest();
            req.open("GET", url, true);
            req.responseType = "blob";
            req.onreadystatechange = function () {
              if (req.readyState === 4 && req.status === 200) {
                
                if (typeof window.navigator.msSaveBlob === 'function') {
                  window.navigator.msSaveBlob(req.response, "phy-inv-summery-Report.pdf");
                } else {
                  var blob = req.response;
                  var link = document.createElement('a');
                  link.href = window.URL.createObjectURL(blob);
                  link.download = "phy-inv-summery-Report.pdf";
                    
                  // append the link to the document body
                    
                  document.body.appendChild(link);
                    
                  link.click();
                   
                }
              }
              $("div#divLoading").removeClass('show');
                $("#downloadMsgModal").hide();
                $(".modal-backdrop").remove();
                
            };
            
            req.send();
        });
        
        
        $(document).on('click', '#pdf_details', function(){
            
            var url = '<?= $data['pdf_details']; ?>';
            url = url.replace(/&amp;/g, '&');
            
            var req = new XMLHttpRequest();
            req.open("GET", url, true);
            req.responseType = "blob";
            req.onreadystatechange = function () {
              if (req.readyState === 4 && req.status === 200) {
    
                if (typeof window.navigator.msSaveBlob === 'function') {
                  window.navigator.msSaveBlob(req.response, "phy-inv-details-Report.pdf");
                } else {
                  var blob = req.response;
                  var link = document.createElement('a');
                  link.href = window.URL.createObjectURL(blob);
                  link.download = "phy-inv-details-Report.pdf";
    
                  // append the link to the document body
    
                  document.body.appendChild(link);
    
                  link.click();
                   
                }
              }
              $("div#divLoading").removeClass('show');
                $("#downloadMsgModal").hide();
                $(".modal-backdrop").remove();
                
            };
            
            req.send();
            
        
            // $.ajax({
            //     url: url,
            //     headers: {
            //         'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            //     },
            //     type: 'GET',
            //     success: function(response){
                    
            //         var blob=new Blob([response]);
            //         var link=document.createElement('a');
            //         link.href=window.URL.createObjectURL(blob);
            //         link.download="phy-inv-details-Report.pdf";
            //         document.body.appendChild(link);
            //         link.click();
            //     },
            //     complete: function(){
                    
            //         $("#downloadMsgModal").hide();
            //         $(".modal-backdrop").remove();
            //     },
            //     error: function(response){
            //         console.log(response);
            //     }
            // });
            
        });
    });
</script>

@endsection