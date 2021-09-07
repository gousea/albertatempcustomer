@extends('layouts.layout')
@section('title')
Employee Loss Prevention Report
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Employee Loss Prevention Report</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($reports) && count($reports) > 0){ ?>
                              <a type="button" class="btn btn-gray headerblack  buttons_menu " href="{{ route('send_mail') }}" id="send_mail_btn" > Mail
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="{{ route('csv_export') }}" id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu "  href="{{ route('print_page') }}" id="btnPrint">PRINT
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " id="pdf_export_btn" href="{{ route('pdf_save_page') }}" > PDF
                            </a>
                        <?php } ?>
                </div>
            </div> 
        </div>
    </nav>

  <div class="container">    
  
        <?php if(isset($reports_css) && count($reports_css) > 0){ ?>
        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
            <a id="send_mail_btn" href="{{ route('send_mail') }}" class="pull-right" style="margin-right:10px;" title="Send the report via email"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Mail</a>
            <a id="csv_export_btn" href="{{ route('csv_export') }}" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</a>
            <a href="{{ route('print_page') }}" id="btnPrint" class="pull-right" style="margin-right:10px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
            <a id="pdf_export_btn" href="{{ route('pdf_save_page') }}" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
          </div>
        </div>
        <?php } ?>
         <br>
         <h6><span> DATE SELECTION</span></h6>
         <br>
          <form method="get" id="filter_form">
              <div class="row">
              @csrf
              @method('get')
               <div class="col-md-3">
              <input type="text" class="form-control rcorner" name="dates"  id="dates" placeholder="Start Date" readonly>
            </div>
              
            <div class="col-md-0">
              <input type="hidden" class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly>
            </div>
            <div class="col-md-0">
              <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" readonly>
            </div>
            <div class="col-md-2">
              <input type="submit" class="btn btn-success rcorner header-color " value="Generate" id="generate">
            </div>
            </div>
          </form>
        
          <br>
                 <h6><span> LOSS PREVENTION REPORT</span></h6>
        
        <?php if(isset($reports_css) && count($reports_css) > 0){ ?>
        <br><br><br>
        <div class="row">
          <div class="col-md-12">
            <p><b>Store Name: </b><?php echo $storename; ?></p>
          </div>
          <div class="col-md-12">
            <p><b>Store Address: </b><?php echo $storeaddress; ?></p>
          </div>
          <div class="col-md-12">
            <p><b>Store Phone: </b><?php echo $storephone; ?></p>
          </div>
          <div class="col-md-12">
            <p><b>From:</b><?php echo $p_start_date; ?> <b>To:</b><?php echo $p_end_date; ?></p>
          </div>
          <?php }?>
           <?php if(isset($reports) && count($reports) > 0){ ?>
          
          <br>
            <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"
                    data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
                   data-click-to-select="true">
              <thead class="header-color text-uppercase">
                <tr style="border-top: 1px solid #ddd;">
                  <th>Username</th>
                   <th>User ID</th>
                  <th>Transaction Type</th>
                  <th>Transaction ID</th>
                  <th>Transaction Time</th>
                  <th>Product Name</th>
                  <th class="text-right">Amount</th>
                </tr>
              </thead>
              <tbody>
                  <?php if(isset($reports) && count($reports) > 0){ ?>
                    <?php foreach($reports as $report){?>
                      <tr>
                        <td><?php echo $report['vusername'];?></td>
                        <td><?php echo $report['iuserid'];?></td>
                        <td><?php echo $report['TrnType'];?></td>
                        <td><?php echo $report['isalesid'];?></td>
                        <td><?php echo $report['trn_date_time'];?></td>
                        <td><?php echo $report['vitemname'];?></td>
                        <td class="text-right"><?php echo $report['nextunitprice'];?></td>
                      </tr>
                    <?php } ?>
                  <?php } ?>
              </tbody>              
            </table>
            
          
        </div>
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


@endsection

@section('page-script')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" type="text/css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="{{ asset('javascript/table-fixed-header.js') }}"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
<link type="text/css" href="{{ asset('javascript/bootstrap-datepicker.css')}}" rel="stylesheet" />
<script src="{{ asset('javascript/bootstrap-datepicker.js')}}"></script>
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/reportline.css') }}">


<style type="text/css">
  .table.table-bordered.table-striped.table-hover thead > tr {
    background-color: #2486c6;
    color: #fff;
  }
</style>

<script type="text/javascript">
        
  $(function () {
	$("#start_date").datepicker({
		format: 'mm-dd-yyyy',
		todayHighlight: true,
		autoclose: true,
	});

	$("#end_date").datepicker({
		format: 'mm-dd-yyyy',
		todayHighlight: true,
		autoclose: true,
	});
});

$(document).ready(function () {
	$('input[name="dates"]').daterangepicker({
		timePicker: true,
		startDate: moment().startOf('hour'),
		endDate: moment().startOf('hour').add(32, 'hour'),
		locale: {
			format: 'M-D-Y'
		}
	});

	$(function () {
	    var p_start_date = "<?php echo isset($p_start_date) ? $p_start_date : '';?>";
        var p_end_date = "<?php echo isset($p_end_date) ? $p_end_date : '';?>";
        
        if(p_start_date !== '' && p_end_date !== ''){
            var start = p_start_date;
            var end = p_end_date;
        } else {
            var start = moment().subtract(1, 'days');
		    var end = moment();
        }
		

		function cb(start, end) {
			$('input[name="dates"]').html(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
			console.log(start.format('YYYY-MM-DD'));
		}

		$('input[name="dates"]').daterangepicker({
			startDate: start,
			endDate: end,
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

$(document).on('submit', '#filter_form', function (event) {

	if ($('#dates').val() == '') {
		bootbox.alert({
			size: 'small',
			title: "Attention",
			message: "Please Select Start Date",
			callback: function () {}
		});
		return false;
	}

	if ($('#dates').val() == '') {	
		bootbox.alert({
			size: 'small',
			title: "Attention",
			message: "Please Select End Date",
			callback: function () {}
		});
		return false;
	}

	if ($('input[name="dates"]').val() != '') {

		var d1 = Date.parse($('input[name="start_date"]').val());
		var d2 = Date.parse($('input[name="end_date"]').val());
		if (d1 > d2) {
			bootbox.alert({
				size: 'small',
				title: "Attention",
				message: "Start date must be less then end date!",
				callback: function () {}
			});
			return false;
		}
	}


	var dates = $("#dates").val();
	dates_array = dates.split("-");
	start_date = dates_array[0];
	end_date = dates_array[1];

	//format the start date to month-date-year
	var formattedStartDate = new Date(start_date);
	var d = formattedStartDate.getDate();
	d = d < 10 ? '0' + d : d;
	var m = formattedStartDate.getMonth();
	m += 1; // JavaScript months are 0-11
	m = ('0' + m).slice(-2);
	var y = formattedStartDate.getFullYear();
	$('input[name="start_date"]').val(m + '-' + d + '-' + y);
	$('input[name="end_date"]').val(end_date);
	var formattedendDate = new Date(end_date);
  var de = formattedendDate.getDate();	
  de = de < 10 ? '0' + de : de;
	var me = formattedendDate.getMonth();
	me += 1; // JavaScript months are 0-11
	me = ('0' + me).slice(-2);
	var ye = formattedStartDate.getFullYear();
	$('input[name="end_date"]').val(me + '-' + de + '-' + ye);
	$("div#divLoading").addClass('show');

});

$(document).ready(function () {
	$("#btnPrint").printPage();
});
// $(window).load(function () {
// 	$("div#divLoading").removeClass('show');
// });
const saveData = (function () {
	const a = document.createElement("a");
	document.body.appendChild(a);
	a.style = "display: none";
	return function (data, fileName) {
		const blob = new Blob([data], {
				type: "octet/stream"
			}),
			url = window.URL.createObjectURL(blob);
		a.href = url;
		a.download = fileName;
		a.click();
		window.URL.revokeObjectURL(url);
	};
}());

$(document).on("click", "#csv_export_btn", function (event) {
	event.preventDefault();
	$("div#divLoading").addClass('show');
	var csv_export_url = "{{route('csv_export')}}";
	csv_export_url = csv_export_url.replace(/&/g, '&');

	$.ajax({
		url: csv_export_url,
		type: 'GET',
	}).done(function (response) {
		console.log(response);
		const data = response,
			fileName = "Employee-Loss-Prevention-Report.csv";
		saveData(data, fileName);
		$("div#divLoading").removeClass('show');
	});
});

$(document).on("click", "#pdf_export_btn", function (event) {
	event.preventDefault();
	$("div#divLoading").addClass('show');

	var pdf_export_url = "{{route('pdf_save_page')}}";
	pdf_export_url = pdf_export_url.replace(/&/g, '&');

	var req = new XMLHttpRequest();
	req.open("GET", pdf_export_url, true);
	req.responseType = "blob";
	req.onreadystatechange = function () {
		if (req.readyState === 4 && req.status === 200) {
			if (typeof window.navigator.msSaveBlob === 'function') {
				window.navigator.msSaveBlob(req.response, "Employee-Loss-Prevention-Report.pdf");
			} else {
				var blob = req.response;
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = "Employee-Loss-Prevention-Report.pdf";

				// append the link to the document body
				document.body.appendChild(link);
				link.click();
			}
		}
		$("div#divLoading").removeClass('show');
	};
	req.send();

});


$(document).on("click", "#send_mail_btn", function (event) {
	event.preventDefault();
	$("div#divLoading").addClass('show');
	var send_mail_url = "{{route('send_mail')}}";
	send_mail_url = send_mail_url.replace(/&/g, '&');
	$.ajax({
		url: send_mail_url,
		type: 'GET',
		success: function (d) {

			bootbox.alert({
				size: 'small',
				title: "Sent",
				message: "A mail has been sent to your email id i.e. " + d + "!",
				callback: function () {}
			});
		}
	}).done(function (response) {
		$("div#divLoading").removeClass('show');
	});

});
$(document).on('change', 'input[name="start_date"],input[name="end_date"]', function (event) {
	event.preventDefault();

	if ($('input[name="start_date"]').val() != '' && $('input[name="end_date"]').val() != '') {

		var d1 = Date.parse($('input[name="start_date"]').val());
		var d2 = Date.parse($('input[name="end_date"]').val());

		if (d1 > d2) {
			bootbox.alert({
				size: 'small',
				title: "Attention",
				message: "Start date must be less then end date!",
				callback: function () {}
			});
			return false;
		}
	}
});

$(document).ready(function() {
    $('#dates').val('');
    var dates = $("#dates").val();
	dates_array = dates.split("-");
	start_date = dates_array[0];
	end_date = dates_array[1];
    $('#dates').val(start_date+"-"+end_date);
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