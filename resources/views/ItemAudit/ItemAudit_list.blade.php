@extends('layouts.layout')
@section('title', 'Item Audit')

@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue menu">
    <div class="container">
        <div class="row">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Item Audit</span>
                </div>
                <div class="nav-submenu">
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </div>
</nav>

<div id="content">
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h6><span>SEARCH PARAMETERS </span></h6>
            </div>
            <br>

            <div class="col-md-12">
                <form action="{{ route('ItemAuditListForm') }}"  method="GET" id="form_item_search" class="form-inline row">
                    <div class="col-md-2" style="margin-right: 70px;">
                        <input type='text' class="form-control adjustment-fields" name="dates" value="<?php echo isset($start_date) ? $start_date : ''; ?>" id="dates" placeholder="Select Date Range" autocomplete="off" style="width: 220px;" readonly/>
                        <input type='hidden' class="form-control" name="start_date" value="<?php echo isset($start_date) ? $start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly/>
                        <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($end_date) ? $end_date : ''; ?>" id="end_date" placeholder="End Date" autocomplete="off">
                    </div>
                    <div class="col-md-2" >
                        <select name="userid" class="sample-class adjustment-fields"  id="user_code"   placeholder="Users">
                            <option value="All">Select User</option>
                            <option value="All"<?php if(isset($_GET['userid']) && $_GET['userid']=='All') echo 'selected="selected"';?>>All</option>
                            <?php if(isset($users) && !empty($users) > 0){?>
                                <?php foreach($users as $users){ ?>
                                <?php if(isset($iuserid) && $iuserid == $users['iuserid']){?>
                                <option value="<?php echo $users['iuserid'];?>" selected="selected"><?php echo $users['vfname'];?></option>
                                <?php } else { ?>
                                <option value="<?php echo $users['iuserid'];?>"><?php echo $users['vfname'];?></option>
                                <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="itemname" class="sample-class1 adjustment-fields"  id="itemname"   placeholder="items">
                            <option value="All">Select Items</option>
                            <option value="All" <?php if(isset($_GET['itemname']) && $_GET['itemname']=='All') echo 'selected="selected"';?>>All</option>
                            <?php if(isset($itemname) && !empty($itemname)){?>
                                <?php foreach($itemname as $itemnames){ ?>
                                <?php if(isset($vitemname) && $vitemname == $itemnames['vitemname']){?>
                                <option value="<?php echo $itemnames['vitemname'];?>" selected="selected"><?php echo $itemnames['vitemname'];?></option>
                                <?php } else { ?>
                                <option value="<?php echo $itemnames['vitemname'];?>"><?php echo $itemnames['vitemname'];?></option>
                                <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="mtype" class="sample-class1 adjustment-fields"  id="mtype"   placeholder="modification" required>
                            <option value="">Select modification</option>
                            <option value="Item Type" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Item Type') echo 'selected="selected"';?>>Item Type</option>
                            <option value="Item Name" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Item Name') echo 'selected="selected"';?>>Item Name</option>
                            <option value="Unit" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Unit') echo 'selected="selected"';?>>Unit</option>
                            <option value="Cost" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Cost') echo 'selected="selected"';?>>Cost</option>
                            <option value="Price" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Price') echo 'selected="selected"';?>>Price</option>
                            <option value="Department"<?php if(isset($_GET['mtype']) && $_GET['mtype']=='Department') echo 'selected="selected"';?>>Department</option>
                            <option value="Category"<?php if(isset($_GET['mtype']) && $_GET['mtype']=='Category') echo 'selected="selected"';?>>Category</option>
                            <!-- <option value="Sub Category">Sub Category</option> not complited-->
                            <option value="Selling Unit"<?php if(isset($_GET['mtype']) && $_GET['mtype']=='Selling Unit') echo 'selected="selected"';?>>Selling Unit</option>
                            <option value="Size"<?php if(isset($_GET['mtype']) && $_GET['mtype']=='Size') echo 'selected="selected"';?>>Size</option>
                            <option value="Unit Per Case"<?php if(isset($_GET['mtype']) && $_GET['mtype']=='Unit Per Case') echo 'selected="selected"';?>>Unit Per Case</option>
                            <option value="Supplier"<?php if(isset($_GET['mtype']) && $_GET['mtype']=='Supplier') echo 'selected="selected"';?>>Supplier</option>
                            <option value="Qty on Hand" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Qty on Hand') echo 'selected="selected"';?>>Qty on Hand</option>
                            <option value="Bottle Deposit" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Bottle Deposit') echo 'selected="selected"';?>>Bottle Deposit</option>
                            <option value="Food Item" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Food Item') echo 'selected="selected"';?>>Food Item</option>
                            <!--<option value="Promotion">Promotion</option> not complited-->
                            <option value="Age Verification" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Age Verification') echo 'selected="selected"';?>>Age Verification</option>
                            <option value="WIC" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='WIC') echo 'selected="selected"';?>>WIC</option>
                            <option value="Status" <?php if(isset($_GET['mtype']) && $_GET['mtype']=='Status') echo 'selected="selected"';?>>Status</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" name="search_filter" value="Generate" class="btn btn-success rcorner header-color basic-button-small" style="margin-right:0px">
                    </div>
                </form>
            </div>
            <?php if(isset($list) && $count > 0){ ?>
                <div class="col-md-12">
                    <div class="row" style="padding-bottom: 10px;float: right; margin-right: 133px; margin-top: 15px;">
                        <a id="pdf_export_btn" href="" class="" style="margin-right:10px;">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                        </a>
                        <a  id="btnPrint" href="{{route('ItemAuditListprint')}}" class="" style="margin-right:10px;">
                            <i class="fa fa-print" aria-hidden="true"></i> Print
                        </a>
                        <a id="csv_export_btn" href="" class="" style="margin-right:10px;">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV
                        </a>
                    </div>
                </div>
            <?php } ?>

            <div class="col-md-12">
                <h6><span>ITEM AUDIT </span></h6>
            </div>
            <br>
            <div class="row col-md-12">
                <div class="table-responsive">
                    <table id="vendor" class="table table-hover promotionview dataTable no-footer" style="width: 100%;" role="grid">
                        <?php if(isset($list ) && !empty($list) > 0){ ?>
                            <thead class='header'>
                                <tr role="row" class="data_list">
                                    <th width="10%" class="text-left"><?php echo "SKU"; ?></th>
                                    <th width="10%" class="text-left"><?php echo "Item Name" ?></th>
                                    <th width="10%" class="text-left "><?php echo "Modification"; ?></th>
                                    <th width="10%" class="text-left "><?php echo "Before"; ?></th>
                                    <th width="10%" class="text-left "><?php echo "Current"; ?></th>
                                    <th width="10%" class="text-left "><?php echo "Location"; ?></th>
                                    <th width="10%" class="text-left "><?php echo "Date"; ?></th>
                                    <th width="10%" class="text-left "><?php echo "Time"; ?></th>
                                    <th width="10%" class="text-left "><?php echo "User Id" ;?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($list as $v) {?>
                                    <?php if($v['beforem']!=$v['afterm']) {?>
                                        <tr>

                                            <td class="text-left">
                                                <span><?php echo $v['vbarcode']; ?></span>
                                            </td>

                                            <td class="text-left">
                                                <span><?php echo $v['vitemname']; ?></span>
                                            </td>

                                            <td class="text-left">
                                                <span><?php echo $mtype; ?></span>
                                            </td>
                                            <td class="text-left">
                                                <?php if($mtype=='Department'){?>
                                                <a id="view_cat0" onclick="viewfunc('<?=$v['beforem']?>', '<?=$v['beforem']?>');" data-toggle="tooltip" title="View" class="btn small-btn"><?php echo $v['beforem']; ?></a>
                                                <?php }
                                                elseif($mtype=='Category'){?>
                                                <a id="view_cat1" onclick="viewcat('<?=$v['beforem']?>', '<?=$v['beforem']?>');" data-toggle="tooltip" title="View" class="btn small-btn"><?php echo $v['beforem']; ?></a>
                                                <?php }
                                                elseif (is_numeric($v['beforem'])) { ?>
                                                    <span><?php echo number_format($v['beforem'],2); ?></span>
                                                <?php }
                                                else { ?>
                                                    <span><?php echo $v['beforem']; ?></span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-left">
                                                <?php if($mtype=='Department'){?>
                                                <a id="view_cat3" onclick="viewfunc('<?=$v['afterm']?>', '<?=$v['afterm']?>');" data-toggle="tooltip" title="View" class="btn small-btn"><?php echo $v['afterm']; ?></a>
                                                <?php }
                                                elseif($mtype=='Category'){?>
                                                <a id="view_cat4" onclick="viewcat('<?=$v['afterm']?>', '<?=$v['afterm']?>');" data-toggle="tooltip" title="View" class="btn small-btn"><?php echo $v['afterm']; ?></a>
                                                <?php }

                                                elseif (is_numeric($v['afterm'])) { ?>
                                                    <span><?php echo $v['afterm']; ?></span>
                                                <?php }
                                                else { ?>
                                                    <span><?php echo $v['afterm']; ?></span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-left">
                                                <span><?php echo "Web"; ?></span>
                                            </td>
                                            <td class="text-left">
                                                <?php $date = date('m-d-Y',strtotime($v['historydatetime']));
                                                $time = date('H:i:s',strtotime($v['historydatetime']));
                                                ?>
                                                <span><?php echo $date; ?></span>
                                            </td>
                                            <td class="text-left">
                                                <span><?php echo $time; ?></span>
                                            </td>
                                            <td class="text-left">
                                                <a id="view_userid" onclick="view_userid('<?=$v['userid']?>', '<?=$v['userid']?>');" data-toggle="tooltip" title="View" class="btn small-btn"><?php echo $v['userid']; ?></a>
                                                <span><?php //echo $v['userid']; ?></span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                        <?php } else { ?>
                            <br>
                            <br>
                            <tr>
                                <td colspan="7" class="text-center"><?php echo "NO DATA FOUND!!";?></td>
                            </tr>
                        <?php } ?>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')

{{-- old javascript links --}}

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('javascript/table-fixed-header.js') }}"></script> --}}


{{-- New Javascript links  --}}


{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>
<script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> --}}





<script>


$(document).ready(function() {
    $('.sample-class').select2();

    $('.sample-class1').select2();
    //  var start = moment().startOf('day');
    // var end = moment().endOf('day');


        function cb(start, end) {
            $('input[name="dates"]').html(start.format('MMMM D, YYYY hh:mm') + '-' + end.format('MMMM D, YYYY hh:mm '));
            console.log(start.format('YYYY-MM-DD hh:mm'));

            $('input[name="start_date"]').val(start.format('MM-DD-YYYY'));
            $('input[name="end_date"]').val(end.format('MM-DD-YYYY' ));


        }

      $('input[name="dates"]').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),

        // startDate: start,
        // endDate: end,
        startDate: "<?php echo isset($start_date) ? $start_date :date('m/d/Y');?>",
        endDate: "<?php echo isset($end_date) ? $end_date : date('m/d/Y');?>",
         locale: {
          format: 'MM/DD/YYYY'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);

      $(function() {

    /*var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('input[name="dates"]').html(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
        console.log(start.format('YYYY-MM-DD'));

        $('input[name="start_date"]').val(start.format('YYYY-MM-DD'));
        $('input[name="end_date"]').val(end.format('YYYY-MM-DD'));
    }*/

    /*$('input[name="dates"]').daterangepicker({
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
    }, cb);*/


    /*this comment is maade by venkat to fix the Page not working functionality */
    // cb(start, end);
    /*this comment is maade by venkat to fix the Page not working functionality */


});
//  $('#item').DataTable({
//     "fixedHeader": {
//       header: true,
//     },
//     "pageLength": 15,
//     "bLengthChange": false,
//     "Filter": false,
//     "Info": false,
//   });
// $('.table').fixedHeader({
//     topOffset: 0
// });

});


 </script>




<script type="text/javascript">
  $(window).load(function(e) {
      console.log("272");
    $("div#divLoading").removeClass('show');
  });
  $(document).ready(function(e){
      console.log(279);
      $("div#divLoading").removeClass('show');
       $("#btnPrint").printPage();
  });
</script>


<style>
    .select2-container--default .select2-selection--single{
    border-radius: 0px !important;
    height: 35px !important;
    width
  }

  .data_list{
    background-color: #474c53 !important;
    border-radius: 9px;
    color: #fff;
    padding-bottom: 1px;
  }
  #view_userid{
    color: blue;
    text-decoration: underline;
    font-weight: bold;
  }

  #view_userid:hover{
    color: #286fb7;
    text-decoration: none;
    font-weight: bolder;
    text-decoration: underline;
  }
  .select2.select2-container.select2-container--default{
  width: 100% !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 35px !important;
  }

</style>
<script>
 function viewfunc(code, name)
      {

         console.log("dept");
         var department_name = name;
         var department_code = code;

         var url = '<?php echo route('ItemAuditListdepartment'); ?>';
               url += "?department_code="+department_code;
                url = url.replace(/&amp;/g, '&');

                //alert(url);

         $("#department_name").html(department_name);

         $.ajax({
                url : url,
                data : {department_code},
                type : 'GET',
                contentType: "application/json",
                dataType: 'json',
                success: function(data) {
                    console.log(data.html);
                    console.log(data.no_of_categories);

                    $("#department1").html(data.html);
                    // $("#department1").text(data);
                    //$("a#view_cat").text(data.no_of_categories);
                    $('#viewCatModal').modal('show');

                },
                error: function(xhr, status, error) {

                  console.log(xhr);
                  console.log(error);
                }
        });
      }
  </script>
   <script>
 function viewcat(code, name)
      {


         var department_name = name;
         var department_code = code;

         var url = '<?php echo route('ItemAuditListcat'); ?>';
               url += "?department_code="+department_code;
                url = url.replace(/&amp;/g, '&');

                //alert(url);

         $("#department_name").html(department_name);

         $.ajax({
                url : url,
                data : {department_code},
                type : 'GET',
                contentType: "application/json",
                dataType: 'json',
                success: function(data) {
                    console.log(data.html);
                    console.log(data.no_of_categories);

                    $("#department2").html(data.html);

                    $('#viewCatModal2').modal('show');

                },
                error: function(xhr, status, error) {

                  console.log(xhr);
                  console.log(error);
                }
        });
      }
  </script>
  <script>
   function  view_userid(code, name)
      {


         var department_name = name;
         var department_code = code;

         var url = '<?php echo route('ItemAuditListUserID'); ?>';
               url += "?department_code="+department_code;
                url = url.replace(/&amp;/g, '&');

                //alert(url);

         $("#department_name").html(department_name);

         $.ajax({
                url : url,
                data : {department_code},
                type : 'GET',
                contentType: "application/json",
                dataType: 'json',
                success: function(data) {
                    console.log(data.html);
                    console.log(data.no_of_categories);

                    $("#viewuserid").html(data.html);

                    $('#viewuserModal').modal('show');

                },
                error: function(xhr, status, error) {

                  console.log(xhr);
                  console.log(error);
                }
        });
      }
  </script>

     <div class="modal fade" id="viewCatModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="font-size:17px;">Department</h4>
        </div>
        <div class="modal-body">

          <div class="alert" id="department1">


          </div>
        </div>
      </div>

    </div>
    </div>

    <div class="modal fade" id="viewCatModal2" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="font-size:17px;">Category </h4>
        </div>
        <div class="modal-body">

          <div class="alert" id="department2">


          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="modal fade" id="viewuserModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="font-size:17px;">User Email </h4>
        </div>
        <div class="modal-body">

          <div class="alert" id="viewuserid">


          </div>
        </div>
      </div>

    </div>
  </div>

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

        var pdf_export_url = '<?php echo route('ItemAuditListpdf'); ?>';

        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "ItemAuditListpdf.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "ItemAuditListpdf.pdf";

              // append the link to the document body

              document.body.appendChild(link);

              link.click();

            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();

    });



    $(document).on("click", "#csv_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('ItemAuditListcsv'); ?>';

          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){

            const data = response,
            fileName = "ItemAuditListcsv.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');

          });

    });

</script>

<style>
    .th_color{
        background-color: #474c53 !important;
        color: #fff;
    }
    table, .promotionview {
        width: 100% !important;
        position: relative;
        left: 0%;

    }
    .table .table {
        background-color: #f8f9fa;
    }

    .th_white_color{
        background-color: #fff;
        border-top: 3px solid ##cccccc;
    }
    h6 {
    width: 100%;
    text-align: left;
    border-bottom: 2px solid;
    line-height: 0.1em;
    margin: 10px 0 20px;
    color:#286fb7;
    }

    h6 span {
        background:#f8f9fa!important;
        padding:0 10px;
        color:#286fb7;
    }
    .rcorner {
    border-radius:9px;
    }

</style>

@endsection
