@extends('layouts.layout')
@section('title')
Sales Transaction
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Sales Transaction</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($report_paid_out_no) && count($report_paid_out_no) > 0){ ?>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu "  href="{{route('Paidoutprint')}}" id="btnPrint">PRINT
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " id="pdf_export_btn" href="{{route('salesreportpdf_save_page')}}" > PDF
                            </a>
                        <?php } ?>
                </div>
            </div> 
        </div>
    </nav>



<section class="section-content py-6"> 
    <div class="container">
                <div class="col-md-12" >
                    <h6><span>SEARCH PARAMETERS </span></h6>
                    <br>
                </div>        
                <form method="GET" id="filter_form" action="{{ route('SalesTransactionForm') }}" style="padding-left: 12px;">
                  @csrf
                  <div class="row">
                      
                    <div class="col-auto ">
                         
                      <input type="" class="form-control rcorner" name="dates" value="<?php echo isset($start_date) ? $start_date : ''; ?>" id="dates" placeholder="Start Date" readonly >
                      <input type="hidden" class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : date('m-d-Y h:i', strtotime('now')); ?>" id="start_date" placeholder="Start Date" readonly>
                      <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : date('m-d-Y h:i', strtotime('now')); ?>" id="end_date" placeholder="End Date" readonly>
                    
                    </div>
                    <div class="col-auto" style="padding: 0px;">
                    
                        <select name="report_by" class="form-control rcorner" id="report_by" >
                        <option value="All"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='All') echo 'selected="selected"';?>>All</option>
                        <option value="Credit Card"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='Credit Card') echo 'selected="selected"';?>>Credit Card</option>
                        <option value="Cash" <?php if(isset($_GET['report_by']) && $_GET['report_by']=='Cash') echo 'selected="selected"';?>>Cash</option>
                        <option Value="EBT" <?php if(isset($_GET['report_by']) && $_GET['report_by']=='EBT') echo 'selected="selected"';?>>EBT</option>
                        <option value="Debit Card"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='Debit Card') echo 'selected="selected"';?>>Debit Card</option>
                        <option value="Check"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='Check') echo 'selected="selected"';?>>Check</option>
                        <option value="On Account"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='On Account') echo 'selected="selected"';?>>House Account</option>
                        <option value="Return"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='Return') echo 'selected="selected"';?>>Refund</option>
                        <option value="void"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='void') echo 'selected="selected"';?>>Void</option>
                        <option value="Discount"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='Discount') echo 'selected="selected"';?>>Discount</option>
                        <option value="Price"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='Price') echo 'selected="selected"';?>>Price Match</option>
                         <option value="Coupon"<?php if(isset($_GET['report_by']) && $_GET['report_by']=='Coupon') echo 'selected="selected"';?>>Coupon</option>
                     
                      </select>
                      
                    </div>
                    <div class="col-auto">
                      <select name="amount_by" class="form-control rcorner" id="amount_by" >
                        <option value=''>--No Amt. Filter--</option>
                        <option value="less"<?php if(isset($_GET['amount_by']) && $_GET['amount_by']=='less') echo 'selected="selected"';?>>Less Than</option>
                        <option value="greater" <?php if(isset($_GET['amount_by']) && $_GET['amount_by']=='greater') echo 'selected="selected"';?>>Greater Than</option>
                        <option Value="equal" <?php if(isset($_GET['amount_by']) && $_GET['amount_by']=='equal') echo 'selected="selected"';?>>Equal To</option>
                       
                      </select>
                      </div>
                      <div class="col-auto " style="width:196px;">
                      <input type="number" class="form-control rcorner"  name="amount"  id="amount" Placeholder="Enter amount"  step="any" value="<?php echo isset($_GET['amount'])? $_GET['amount']:'' ?>" autocomplete="off">
                      </div>
                       <div class="col-auto">
                       <select name="iuserid" class="form-control rcorner" id="regid">
                            <<option value="All"<?php if(isset($_GET['report_by'])&& $_GET['report_by']=='All') echo 'selected="selected"';?>>All Registers</option>
                            
                              <?php if(isset($userid_list) && count($userid_list) > 0){?>
                                <?php foreach($userid_list as $iusers){ ?>
                                
                                    <?php if(isset($selected_reg) && $selected_reg == $iusers->vterminalid){ ?>
                                
                                <option value="<?php echo $iusers->vterminalid;?>" selected="selected"><?php echo $iusers->vterminalid;?></option>
                                
                              <?php } else { ?>
                                <option value="<?php echo $iusers->vterminalid;?>"><?php echo $iusers->vterminalid;?></option>
                                <?php } ?>
                                <?php } ?>
                              <?php } ?>
                            </select>
                       </div>
                       
                      <div class="col-auto">
                      <input type="submit" class="btn btn-success rcorner header-color" name="Submit" value="Generate">
                    </div>
                    
              </form>
                </div>
                
            </form>   
            
                <br>    <br>
                <div class="col-md-12" >
                    <h6><span> SALES TRANSCATION </span></h6>
                </div> 
                
                
                <?php if(isset($reports) && count($reports) > 0){ ?>   
                
                    <div class="row" style="margin-left: 2%; display:none;">
                        <div class="col-md-12">
                            <p>From: <?php echo $start_date; ?> To <?php echo $end_date; ?></p>
                        </div>
                  </div>
                    <div class="row" style="margin-left: 2%;display:none;">
                        <div class="col-md-12">
                            <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
                        </div>
                        <div class="col-md-12">
                            <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
                        </div>
                        <div class="col-md-12">
                            <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
                        </div>
            </div>
            
            <div class="row" style="margin: 0px;">
                        
                  <div class="col-md-12 table-responsive">
                 
                     <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
                   data-click-to-select="true">
                      <thead class='header th_color text-uppercase'>
                        <tr style="border-top: 1px solid #ddd;">
                          <th>Date</th>
                          <th>Reg</th>
                          <th>Tran#</th>
                          <th >Total</th>
                         <!-- <th class="text-right">Change</th>-->
                          <th>Total Tax</th>
                          <th> Tender Type</th>
                          <!--<th>Trn Type</th>-->
                          
                          <th>Action</th>
                        </tr>
                      </thead>
                      

                      <tbody>
                      <?php  $nettotal=$ntaxtotal=0 ?>
                          <?php foreach ($reports as $key => $value){ 
                          $nettotal=$nettotal+$value->nnettotal;
                          $ntaxtotal=$ntaxtotal+$value->ntaxtotal;
                          }
                          ?>
                          <tr class="header-color text-uppercase">
                              <td><b>Grand Total</b></td>
                              <td></td>
                              <td></td>
                              <td> <b><?php echo "$",$nettotal;?></b></td>
                            
                              <td><b><?php echo "$",$ntaxtotal;?></b></td>
                             
                              <td></td>
                              <td></td>
                              
                          </tr>
                          <?php foreach ($reports as $key => $value){ ?>
                            <tr>
                              <td><?php echo date('m-d-Y g:i:sA', strtotime($value->dtrandate));?></td>
                              <td><?php echo $value->vterminalid;?></td>
                              <td><?php echo $value->trnsalesid;?></td>
                              <td><?php echo "$",$value->nnettotal;?></td>
                            
                              <td><?php echo "$",$value->ntaxtotal;?></td>
                             
                              <td><?php echo $value->tendername;?></td>
                             
                               <td><a class="btn btn-primary print-sales" id="print" data-id="<?php echo $value->isalesid;?>"><i class="fa fa-print"></i> Print </a></td>
                            </tr>
                          <?php } ?>
                      </tbody>
                    </table>
                  </div>
        </div>
                {{$reports->links()}}
                <?php }else{ ?>
                    <?php if(isset($start_date)){ ?>
                        <div class="row">
                          <div class="col-md-12"><br><br>
                            <div class="alert alert-info text-center">
                              <strong>Sorry no data found!</strong>
                            </div>
                          </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            
            
        </div>
</div>
</section>
@endsection
@section('page-script')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" ></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script> 
 <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<script>
  //  $(function(){
  //   $("#start_date").datepicker({
      
  //     format: 'mm-dd-yyyy hh:mm',
  //     todayHighlight: true,
  //     autoclose: true,
  //   });

  //   $("#end_date").datepicker({
  //     format: 'mm-dd-yyyy hh:mm',
  //     todayHighlight: true,
  //     autoclose: true,
  //   });
  // }); 
  
   $(document).ready(function() {
      $('input[name="dates"]').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          //format: 'M-D-Y hh:mm A'
          format: 'M/DD hh:mm A'
        }
      });
      
      $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('input[name="dates"]').html(start.format('MMMM D, YYYY hh:mm') + '-' + end.format('MMMM D, YYYY hh:mm'));
        // console.log(start.format('YYYY-MM-DD hh:mm'));
        //console.log(end.format('YYYY-MM-DD'));
        
        // $('input[name="start_date"]').val(start.format('YYYY-MM-DD'));
        // $('input[name="end_date"]').val(end.format('YYYY-MM-DD'));
    }

    $('input[name="dates"]').daterangepicker({
        //timePicker: true,
        //timePicker24Hour: true,
        // startDate: start,
        // endDate: 
        startDate: "<?php echo isset($start_date) ? $start_date : date('m/d/Y');?>",
        endDate: "<?php echo isset($end_date) ? $end_date : date('m/d/Y');?>",
        locale: {
          format: 'M/DD/YYYY '
        },
        ranges: {
           'Today': [moment().startOf('day'), moment()],
           'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
           'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
           'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    
    

});
      
      
      
    });


  
  $(document).on('submit', '#filter_form', function(event) {


  if($('#start_date').val() == ''){
    // alert('Please Select Start Date');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select Start Date", 
      callback: function(){}
    });
    return false;
  }

  if($('#end_date').val() == ''){
    // alert('Please Select End Date');
    bootbox.alert({ 
      size: 'small',
      title: "Attention", 
      message: "Please Select End Date", 
      callback: function(){}
    });
    return false;
  }


  
    var dates  = $("#dates").val();
    // console.log('date');
    // console.log(dates);
    dates_array =dates.split("-");
    start_date = dates_array[0];
    end_date = dates_array[1];   

    // console.log("Start Date: "+start_date + " End Date: "+end_date);

    //format the start date to month-date-year
    var formattedStartDate = new Date(start_date);
    var d = formattedStartDate.getDate();
    //console.log(d);
    var m =  formattedStartDate.getMonth();
    m += 1;  // JavaScript months are 0-11
    m = ('0'+m).slice(-2);
    
    var y = formattedStartDate.getFullYear();
    var h=formattedStartDate.getHours();
    var mi=formattedStartDate.getMinutes();
    // h += 1;  // JavaScript months are 0-11
     h = ('0'+h).slice(-2);
     mi=('0'+mi).slice(-2);
    // console.log(m);
    // console.log(h);
    
    //return false;
    $('input[name="start_date"]').val(m+'-'+d+'-'+y+' '+h+':'+mi);
    
    

    var formattedendDate = new Date(end_date);
    var de = formattedendDate.getDate();
    var me =  formattedendDate.getMonth();
    
    me += 1;  // JavaScript months are 0-11
    me = ('0'+me).slice(-2);
    
    var ye = formattedendDate.getFullYear();
    var he=formattedendDate.getHours();
    var mie=formattedendDate.getMinutes();
    he=('0'+he).slice(-2);
    mie=('0'+mie).slice(-2);
    $('input[name="end_date"]').val(me+'-'+de+'-'+ye+' '+he+':'+mie);
    
     

  $("div#divLoading").addClass('show');
  
});

</script>

</script>


<script type="text/javascript">



  $(document).on('click', '.print-sales', function(event) {
    event.preventDefault();
	var reportdata_url = '<?php echo route('Salevalue'); ?>';
    reportdata_url  = reportdata_url.replace(/&amp;/g, '&');

	var salesid =$(this).attr("data-id");

    $("div#divLoading").addClass('show');

    $.ajax({
        
        url     : reportdata_url,
        data    : {salesid:salesid},
        type    : 'GET',
        
    }).done(function(response){
		var  response = $.parseJSON(response); //decode the response array
      
		if(response.code == 1 ){
			$("div#divLoading").removeClass('show');
			$('.modal-body').html(response.data);
			$('#view_salesdetail_model').modal('show');
		
		}else if(response.code == 0){
			alert('Something Went Wrong!!!');
			$("div#divLoading").removeClass('show');
			return false;
      }		
	});

  });
</script>
<script> 
function openPrintDialogue(){
  $('<iframe>', {
    name: 'myiframe',
    class: 'printFrame'
  }).appendTo('body').contents().find('body').append($('#printme').html());

  window.frames['myiframe'].focus();
  window.frames['myiframe'].print();

  setTimeout(function(){$(".printFrame").remove(); }, 1000);
}; 
$(document).ready(function() {
	

    $(document).on('click', '.btnPrint', function(e){
       
        e.preventDefault();
        $("div#divLoading").addClass('show');
        $.ajax({
                type: 'GET',
                url: '/salestransaction/print',
                // data: formData,
                dataType: 'html',
                success: function (reponse) {
                    $("div#divLoading").removeClass('show');
                   

                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = reponse;

                    window.print();

                    document.body.innerHTML = originalContents;
                },
                error: function (data) {
                    $("div#divLoading").removeClass('show');

                    // console.log('Error:', data);
                }
                
            });
    });

	
});
 
</script>
<!-- Modal -->
  <div class="modal fade" id="view_salesdetail_model" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">        
        <div class="modal-body" id="printme">          
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btnPrint" data-dismiss="modal" >Print</button>
      </div>
      </div>
    </div>
  </div>
  


<script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>

<script type="text/javascript">
  
    
</script>
<style type="text/css">
  .tab-content.responsive{
      background: #f1f1f1;
      padding-top: 2%;
      padding-bottom: 2%;
      padding-left: 1%;
      padding-right: 2%;
  }F
  .nav-tabs{
      margin-bottom:0px;
  }

  .select2-container--default .select2-selection--single{
    /*border-radius: 0px !important;*/
    height: 35px !important;
    width:150px;
  }
  .select2.select2-container.select2-container--default{
  width: 100% !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 38px !important;
    border-radius: 9px !important;
  }
</style>
<script>
    $('select[name="report_by"]').select2();
    $('select[name="amount_by"]').select2();
    $('select[name="iuserid"]').select2();
    
    
</script>

<!--<script src="view/javascript/table-fixed-header.js" ></script>-->
<!--<script>-->

<!--$('.table').fixedHeader({-->
<!--    topOffset: 0-->
<!--});-->



<!--</script>-->
<style>
    .disabled {
    pointer-events:none; //This makes it not clickable
 
    }
    


</style>
<style>
.rcorner {
  border-radius:9px;
}
.th_color{
    background-color: #474c53 !important;
    color: #fff;
    
  
}
h6 {
   width: 100%; 
   text-align: left; 
   border-bottom: 2px solid; 
   line-height: 0.1em;
   margin: 0px 0 20px; 
   color:#286fb7;
} 

h6 span { 
    background:#f8f9fa!important; 
    padding:10px 0px; 
    color:#286fb7;
}

[class^='select2'] {
  border-radius: 9px !important;
}
table, .promotionview {
    width: 100% !important;
    position: relative;
    left: 0%;
}
</style>
@endsection 