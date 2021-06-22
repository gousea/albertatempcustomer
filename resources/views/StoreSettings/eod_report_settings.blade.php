@extends('layouts.layout')
  
@section('title', 'Manual Sales Entry')

@section('styles')
    <style>
        .row-margin-05 { margin-top: 0.5em; }
        .row-margin-10 { margin-top: 1.0em; }
        .row-margin-20 { margin-top: 2.0em; }
        .row-margin-30 { margin-top: 3.0em; }
        
        .checkboxes {
            align-items: center;
            vertical-align: middle;
        }
    </style>
@endsection

@section('main-content')

<link rel="stylesheet" href="{{ asset('asset/css/promotion.css') }}">

<div id="content">
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase">Manual Sales Entry</span>
                </div>
                <div class="nav-submenu">
                    <button type="button"  class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu ">&nbsp;&nbsp;Print Options</button>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
    <section class="section-content py-6">
        <div class="container">
            <form method="POST" id="filter_form" action="{{ route('eodsetting_getlist') }}">
                @csrf   
                <div class="mytextdiv mb-3">
                    <div class="mytexttitle font-weight-bold text-uppercase">
                    Date Selection
                    </div>
                    <div class="divider font-weight-bold"></div>
                </div>
                <div class="container">
                    <div class="row">
                        
                        <div class='col-md-3' style="width:250px;">
                            <div class="form-group">
                                <div class='input-group date' id='start_date_container'>
                                    <input type='text' style="height: 33px !important; font-size: 15px;" class="form-control datePicker" name="dates" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="dates" placeholder="Start Date" readonly/>
                                </div>
                            </div>
                            
                                        
                            <div class='col-md-0'>
                                <div class="form-group">
                                    <div class='input-group date' id='start_date_container'>
                                        <input type='hidden' class="form-control datePicker" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly/>
            
                                    </div>
                                </div>
                            </div>
                        

                            <div class='col-md-0'>
                                <div class="form-group">
                                    <div class='input-group date' id='end_date_container'>
                                        <input type='hidden' class="form-control datePicker" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" readonly/>
                                    </div>
                                </div>
                            </div> 
                        </div>
        
                        <div class="col-md-2">
                            <input type="submit" class="btn buttons_menu" style="background-color: #286fb7 !important; height: 33px !important; color: #fff; font-size: 13px;"  value="Generate">
                        </div>
                    </div>
                </div>
            </form>

            <div class="mytextdiv mb-3">
                <div class="mytexttitle font-weight-bold text-uppercase">
                BATCHES
                </div>
                <div class="divider font-weight-bold"></div>
            </div>
            @if(isset($data) && count($data))
                <table class="table table-striped table-hover"  style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                    <tr style="background-color: #286fb7!important;">
                        <th><input type='checkbox' class='' id='selectAll'></th>
                        <th class="col-xs-1 headername text-uppercase text-light">EOD Date</th>
                        <th class="col-xs-1 headername text-uppercase text-light">Batch ID</th>
                        <th class="col-xs-1 headername text-uppercase text-light">Taxable Sales</th>
                        <th class="col-xs-1 headername text-uppercase text-light">Non Taxable Sales</th>
                        <th class="col-xs-1 headername text-uppercase text-light">Total Tax</th>
                        <th class="col-xs-1 headername text-uppercase text-light">Net Sales</th>
                    </tr>
                    
                    @foreach($data as $batch)
                        <tr>
                            <td><input type='checkbox' class='chkbx' name='chk[]' value='{{$batch->ibatchid}}'></td>
                            <td>{{$batch->eod_date}}</td>
                            <td>{{$batch->ibatchid}}</td>
                            <td>{{$batch->total_taxable_sales}}</td>
                            <td>{{$batch->total_nontaxable_sales}}</td>
                            <td>{{$batch->total_tax}}</td>
                            <td>{{$batch->net_sales}}</td>
                        </tr>
                    @endforeach
                </table>
                <div class='row'>
                    <div class='col-md-12'>
                        <input type="button" class="btn buttons_menu pull-left" style="background-color: #286fb7 !important; height: 33px !important; color: #fff; font-size: 13px;" id='adjust' value="Adjust" disabled>
                    </div>
                </div>
                @elseif(isset($data))
                <div class='row'>
                    <div class='col-md-10 col-md-offset-1 alert alert-danger' style='text-align: center;'>
                        There are no batches related to that date range
                    </div>
                </div>
            @else
                    <!-- To display when the page is loaded for the first time -->
            @endif
        </div>
    </section>

</div>




<div class="modal fade bd-example-modal-lg"  id="taxSettingsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg">

  <!-- Modal content-->
  <div class="modal-content p-3" style=" border-radius: 1.3rem !important;">
    
    <div class="modal-body">
        <div class="mytextdiv mb-3">
            <div class="mytexttitle font-weight-bold text-uppercase">
            Add Amount By
            </div>
            <div class="divider font-weight-bold"></div>
        </div>
        <div style="margin-left: 20px;">
            <input type='radio' name='addAmtBy' value='Dollar' checked> Dollars ($) <br />
            <input type='radio' name='addAmtBy' value='Percent'> Percentage (%)
        </div> 
       
        <div class="mytextdiv mb-4 mt-3" >
            <div class="mytexttitle font-weight-bold text-uppercase">
            Values
            </div>
            <div class="divider font-weight-bold"></div>
        </div>

        <div class="row" style="margin-left: 20px;">
            <div class="col-md-6" style="border-right: 1px solid #286fb7; height: 200px;">
                <h5>Taxable</h5>
                <div class='row m-5'> 
                    <span class='pull-left'>Tax 1</span>
                    <input type='text' style="border-radius: 5px;" class='floatValue col-md-8 eodSettings ml-2' id='taxableValue1' name='taxable_value_1' />
                </div>

                <div class='row m-5'> 
                    <span class='pull-left'>Tax 2</span>
                    <input type='text' style="border-radius: 5px;" class='floatValue col-md-8 eodSettings ml-2' id='taxableValue2' name='taxable_value_2' />
                </div>

                <div class='row m-5'> 
                    <span class='pull-left'>Tax 3</span>
                    <input type='text' style="border-radius: 5px;" class='floatValue col-md-8 eodSettings ml-2' id='taxableValue3' name='taxable_value_3' />
                </div>
            </div>
            <div class="col-md-6">
                <h5>Non - Taxable</h5>
                <div class='row m-5'> 
                    <span class='pull-left'>Value</span>
                    <input type='text' style="border-radius: 5px;" class='floatValue col-md-8 eodSettings ml-2' id='nonTaxableValue' name='non_taxable_value' />
                </div>
            </div>
        </div>
        
        <div class='row row-margin-05'>
            <div class='col-md-12'> 
                
            </div>
        </div>
        
        <div class='row' >
            <div class="col-md-5"></div>
            <div class="col-md-7 pull-right mt-3">
                <input class='btn buttons_menu'  style="background-color: #286fb7 !important; color: #fff;padding: 3px 20px; font-size: 12px; margin-right: 3px !important; " type='button' id='saveValues' value='Save'>
                <input class='btn buttons_menu btn-danger' type='button' style="padding: 3px 20px; font-size: 12px;" value='Cancel' onclick="$('#taxSettingsModal').modal('hide');">
            </div>
        </div>
        
    </div>
  </div>
  
</div>
</div>



<div class="modal fade" id="successModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success text-center">
          <p id="success_msg"></p>
        </div>
      </div>
    </div>
  </div>
</div> 

<div class="modal fade" id="warningModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning text-center">
          <p id="warning_msg"></p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="errorModal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger text-center">
          <p id="error_msg"></p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection   
@section('page-script') 

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('javascript/bootbox.min.js')}}"></script>



<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" ></script>

<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
<script type="text/javascript">

    $(document).on('click', '#saveValues', function(){

        var type = $('input[name="addAmtBy"]:checked').val();
        var tax1 = $('#taxableValue1').val();
        var tax2 = $('#taxableValue2').val();
        var tax3 = $('#taxableValue3').val();
        
        var nonTax = $('#nonTaxableValue').val();
        
        if(tax1 === '' && tax2 === '' && tax3 === '' && nonTax === ''){
            
            // bootbox.alert({ 
            //     size: 'medium',
            //     title: "Warning !!", 
            //     message: 'Cannot proceed without any inputs !!', 
            //     callback: function(){}
            // });

            $('#warning_msg').html('Cannot proceed without any inputs !!');
            $("div#divLoading").removeClass('show');
            $('#warningModal').modal('show');
            
            return false;
        }
        
        $('#taxSettingsModal').modal('hide');
        var batchIds = [];
        $('input[name="chk[]"]:checked').each(function(k, v){
            batchIds.push(v.value);
        });
        
        var data = {
            type: type,
            tax1: tax1,
            tax2: tax2,
            tax3: tax3,
            non_tax: nonTax,
            batch_ids: batchIds
        };
        
        // console.log(type+' '+tax1+' '+tax2+' '+tax3+' '+nonTax+' '+batchIds);
        
        $("div#divLoading").addClass('show');
        
        $.ajax('/manual_sales_submit_values',{
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            type: 'POST',
            data: data,
            success: function (data, status, xhr) {
                // bootbox.alert({ 
                //     size: 'small',
                //     title: "Success", 
                //     message: data.message, 
                //     callback: function(){
                //         window.location.href = '{{ route('eodsetting_getlist') }}';
                //     }
                // });

                $('#success_msg').html("Setting saved Sucessfully");
                $("div#divLoading").removeClass('show');
                $('#successModal').modal('show');
                setTimeout(function(){
                    $('#successModal').modal('hide');
                    window.location.href = '{{ route('eodsetting_getlist') }}';
                }, 2000);
            },
            error: function (jqXhr, textStatus, errorMessage) {
                // $('p').append('Error' + errorMessage);
                console.log(jqXhr);
                console.log(textStatus);
                console.log(errorMessage);
            }
            
        })
        .done(function(){
            $("div#divLoading").removeClass('show');
            // $('#taxSettingsModal').modal('hide');
            $('.eodSettings').val('');
        });
        
    });


    $('input.floatValue').on('input', function() {
    //   this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');
         this.value = this.value.replace(/(?!^-)[^0-9.]/g, "").replace(/(\..*)\./g, '$1');
    });
    $(document).on('click', '#adjust', function(){
        $('#taxSettingsModal').modal('show');
        // $('#taxableValue').focus();
    });
    
    $('#adjust').on('shown.bs.modal', function () {
        $('#taxableValue').focus();
    })
    
    $(document).on('click', '.chkbx', function(){
        
        var checkedCheckboxes = $('input[name="chk[]"]:checked').length;
        var atLeastOneIsChecked = checkedCheckboxes > 0;
        
        var totalCheckbox = $('input[name="chk[]"]').length;
        
        // console.log('checked: '+$('input[name="chk[]"]:checked').length+':'+atLeastOneIsChecked);
        // console.log($('#selectAll').prop('checked'));
        
        if(checkedCheckboxes === totalCheckbox){
            $("#selectAll").prop('checked', true);
        } else {
            $("#selectAll").prop('checked', false);
        }
        
        if(atLeastOneIsChecked === false && $('#selectAll').prop('checked') === false){
            $('#adjust').prop('disabled', true);
            $('#adjust').removeClass('btn-info');
        } else {
            $('#adjust').prop('disabled', false);
            $('#adjust').addClass('btn-info');
        }
    });
    
    $(document).on('click','#selectAll', function(){
        $(".chkbx").prop('checked', $(this).prop('checked'));
        
        if($(this).prop('checked') === false){
            $('#adjust').prop('disabled', true);
            $('#adjust').removeClass('btn-info');
        } else {
            $('#adjust').prop('disabled', false);
            $('#adjust').addClass('btn-info');
        }
    });
    

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
                // console.log(start.format('YYYY-MM-DD'));
                
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
            $('#warning_msg').html('Please Select Start Date');
            $("div#divLoading").removeClass('show');
            $('#warningModal').modal('show');
            
            return false;
        }

        if($('#dates').val() == '')
        {
            $('#warning_msg').html('Please Select End Date');
            $("div#divLoading").removeClass('show');
            $('#warningModal').modal('show');
            return false;
        } 

          if($('input[name="dates"]').val() != ''){
            var d1 = Date.parse($('input[name="start_date"]').val());
            var d2 = Date.parse($('input[name="end_date"]').val()); 

            if(d1 > d2){
                // bootbox.alert({ 
                //     size: 'small',
                //     title: "Attention", 
                //     message: "Start date must be less then end date!", 
                //     callback: function(){}
                // });
                $('#warning_msg').html('Start date must be less then end date!');
                $("div#divLoading").removeClass('show');
                $('#warningModal').modal('show');
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

.table.table-bordered.table-striped.table-hover thead > tr {
    background-color: #2486c6;
    color: #fff;
}
.select2-selection__rendered {
    line-height: 31px !important;
}
.select2-container .select2-selection--single {
    height: 35px !important;
}
.select2-selection__arrow {
    height: 34px !important;
}
</style>
<script>
    $('select[name="vendorid"]').select2();
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




<style>

</style>
@endsection 
