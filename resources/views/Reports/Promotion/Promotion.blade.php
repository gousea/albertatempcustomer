@extends('layouts.layout')
@section('title')
PROMOTION REPORT
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Promotion Report</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($report_paid_out) && count($report_paid_out) > 0){ ?>
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
         
    
        <form method="POST" id="filter_form" action="{{ route('PromotionReportForm') }}">
                @csrf   
                    
                            <div class="row">
                                
                                 <div class='mx-sm-4 mb-2'>
                                    <div class="form-group">
                                        <div class='input-group date ' id='start_date_container'>
                                            <input type='text' class="form-control datePicker"  style="border-radius: 9px"; name="dates" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="dates" placeholder="Start Date" readonly />
                                           
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar rcorner"></span>
                                            </span>
                                        </div>
                                    </div>
                                    
                                             
                             
                                    <div class="form-group  mx-sm-2 mb-2">
                                        <div class='input-group date' id='start_date_container'>
                                            <input type='hidden' class="form-control datePicker"  name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly/>
                
                                        </div>
                                    </div>
                               

                                <div class='col-md-0'>
                                    <div class="form-group  mx-sm-2 mb-2">
                                        <div class='input-group date' id='end_date_container'>
                                            <input type='hidden' class="form-control datePicker rcorner" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" readonly/>
                                    
                                      </div>
                                    </div>
                                </div> 
                            </div>
                            
                <div class=" mx-sm-2 mb-2">
               <select name="prom_id" class="form-control "   id="prom_id">
                   <option value ='' >Select Promotion</option>
                   <!--<option value='All' selected="selected">All </option>-->
                    
                    
                      <?php if(isset($promo_list) && count($promo_list) > 0){?>
                        <?php foreach($promo_list as $promo){ ?>
                        
                         <?php if(isset($selected_reg) && $selected_reg == $promo->prom_id){ ?>
                        
                           <option value="<?php echo $promo->prom_id;?>" selected="selected"><?php echo $promo->prom_name;?></option>
                          
                              <?php } else { ?>
                              <option value="<?php echo $promo->prom_id;?>"><?php echo $promo->prom_name ?></option>
                              <?php } ?>
                         <?php } ?>
                      <?php } ?>
                    </select>
               </div>
           
              
               <div class=" mx-sm-2 mb-2">
                    <input type="submit" class="btn btn-success rcorner header-color" value="Generate">
                </div>
        </div> 
                
    </form>
        <br>    
        <div class="col-md-12" >
            <h6><span> PROMOTION REPORT </span></h6>
        </div>    
                
            
             
            <?php if(isset($promo_data) && count($promo_data) > 0){ ?>
              
              <div class="col-md-12 table-responsive">
                <table data-toggle="table" data-classes="table table-hover table-condensed promotionview" data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"data-click-to-select="true">
                   <thead>
                     <tr class="th_color text-uppercase">
                                
                               
                            <th>DATE</th>
                            <th>SKU</th>
                             <th>ITEM NAME</th>
                            <th>PRICE</th>
                            <th>DISCOUNTED AMOUNT</th>
                            <th>DISCOUNTED PRICE</th>
                            <th>QTY</th>
                            <th>TRANSACTION #</th>
                               
                    </tr>
                          
                            <?php $total=0;?>
                            <?php foreach($promo_data as $v){
                                $total= $total+$v->DISCOUNTED_AMOUNT; 
                             
                                } ?>
                             
                     <tr class="header-color"> 
                               
                                <th> GRAND TOTAL</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><?php echo "$",number_format($total,2); ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                </tr>
                    </thead>              
                            <?php 
                            
                                $count = 0; 
                                foreach($promo_data as $v){?>
                                
                                    <tr>
                                            
                                           <td><?php echo isset($v->TDATE) ? $v->TDATE: ""; ?></td>
                                            
                                       
                                           <td><?php echo isset($v->SKU) ? $v->SKU: 0.00; ?></td>
                                            <td><?php echo isset($v->ITEMNAME) ? $v->ITEMNAME: ""; ?></td>
                                            <td><?php echo "$",isset($v->PRICE) ? $v->PRICE: 0.00; ?></td>
                                            <td><?php echo "$",isset($v->DISCOUNTED_AMOUNT) ? $v->DISCOUNTED_AMOUNT: 0.00; ?></td>
                                            
                                            <td><?php echo isset($v->DISCOUNTED_PRICE) ? $v->DISCOUNTED_PRICE: 0.00; ?></td>
                                            <td><?php echo isset($v->QTY) ? $v->QTY : 0; ?></td>
                                            
                                            <td><?php echo isset($v->TRANSACTION_NO) ? $v->TRANSACTION_NO: 0; ?></td>
                                    </tr>
                                <?php } ?>
                    
                         
                        
                        </table>
                        </div>
                      
              
               
                <br>
            <?php }else{ ?>
                <?php if(isset($p_start_date)){ ?>
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

<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>

<script type="text/javascript">

   $(document).ready(function() {
      $('input[name="dates"]').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'YYYY-M-D'
        }
      });
      
      $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('input[name="dates"]').html(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
        console.log(start.format('YYYY-MM-DD'));
        
        // $('input[name="start_date"]').val(start.format('YYYY-MM-DD'));
        // $('input[name="end_date"]').val(end.format('YYYY-MM-DD'));
    }

    $('input[name="dates"]').daterangepicker({
        // startDate: start,
        // endDate: end,
        startDate: "<?php echo isset($p_start_date) ? $p_start_date : date('m/d/Y');?>",
        endDate: "<?php echo isset($p_end_date) ? $p_end_date : date('m/d/Y');?>",
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    
    

});
      
      
      
    });


    $(document).on('submit', '#filter_form', function(event) 
    {
        if($('#dates').val() == '')
        {
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: "Please Select Start Date", 
                callback: function(){}
            });
            return false;
        }

        if($('#dates').val() == '')
        {
            bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: "Please Select End Date", 
                callback: function(){}
            });
            return false;
        } 

          if($('input[name="dates"]').val() != ''){
            var d1 = Date.parse($('input[name="start_date"]').val());
            var d2 = Date.parse($('input[name="end_date"]').val()); 

            if(d1 > d2){
                bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: "Start date must be less then end date!", 
                    callback: function(){}
                });
                return false;
            }
        }
        
        
        var dates  = $("#dates").val();
    dates_array =dates.split("-");
    start_date = dates_array[0];
    end_date = dates_array[1];   
  
    //format the start date to month-date-year
    var formattedStartDate = new Date(start_date);
    var d = formattedStartDate.getDate();
    var m =  formattedStartDate.getMonth();
    m += 1;  // JavaScript months are 0-11
    m = ('0'+m).slice(-2);
    
    var y = formattedStartDate.getFullYear();
  
    $('input[name="start_date"]').val(y+'-'+m+'-'+d);
    
    
    $('input[name="end_date"]').val(end_date);
    
     var formattedendDate = new Date(end_date);
    var de = formattedendDate.getDate();
    var me =  formattedendDate.getMonth();
    me += 1;  // JavaScript months are 0-11
    me = ('0'+me).slice(-2);
    
    var ye = formattedendDate.getFullYear();
    
    $('input[name="end_date"]').val(ye+'-'+me+'-'+de);
  

        
        $("div#divLoading").addClass('show');
    });
</script>

<style type="text/css">

/*.table.table-bordered.table-striped.table-hover thead > tr {*/
/*    background-color: #2486c6;*/
/*    color: #fff;*/
/*}*/
.select2-selection__rendered {
    line-height: 31px !important;
}
.select2-container .select2-selection--single {
    height: 38px !important;
}
.select2-selection__arrow {
    height: 34px !important;
}
</style>
<script>
    $('select[name="vendorid"]').select2();
    $('select[name="amount_by"]').select2();
    $('select[name="amount"]').select2();
    
    
</script>

<style type="text/css">
tr.header{
    cursor:pointer;
}

tr.header > th{
    background-color: #DCDCDC;
    border: 1px solid #808080 !important;
}

tr.header > th, tr.header > th > span{
    font-size: 15px;
}

tr.header > th > span{
    float: right;
}

.header .sign:after{
    content:"+";
    display:inline-block;      
}
.header.expand .sign:after{
    content:"-";
}

tr.add_space th {
    border: none !important;
    padding: 2px !important;
}
</style>

<script type="text/javascript">
    $(window).load(function() {
        $("div#divLoading").removeClass('show');
    });
</script>

<script>

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


</script>
<script>  
$(document).ready(function() {
  $("#btnPrint").printPage();
});
</script> 
<script>  

    $(document).on("click", "#pdf_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('Paidoutpdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "Paidoutpdf.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "Paidoutpdf.pdf";

              // append the link to the document body

              document.body.appendChild(link);

              link.click();
               
            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();
        
    });

    // $(document).on('click', '#btnPrint', function(e){
    //     e.preventDefault();
    //     $("div#divLoading").addClass('show');
    //     $.ajax({
    //             type: 'GET',
    //             url: '/paidoutreport/print',
    //             // data: formData,
    //             dataType: 'html',
    //             success: function (reponse) {
    //                 $("div#divLoading").removeClass('show');

    //                 var originalContents = document.body.innerHTML;

    //                 document.body.innerHTML = reponse;

    //                 window.print();

    //                 document.body.innerHTML = originalContents;
    //             },
    //             error: function (data) {
    //                 $("div#divLoading").removeClass('show');

    //                 console.log('Error:', data);
    //             }
    //         });
    // });


    $(document).on("click", "#csv_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('Paidoutcsv'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "Paidoutcsv.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });

</script>
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
