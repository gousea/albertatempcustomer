@extends('layouts.master')
  
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
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
          
          <!-- <h1><?php //echo $heading_title; ?></h1> -->
          <ul class="breadcrumb">
            <?php //foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php //echo $breadcrumb['href']; ?>"><?php //echo $breadcrumb['text']; ?></a></li>
            <?php //} ?>
          </ul>
        </div>
    </div>
    
    <div class="container-fluid">
       <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i>Manual Sales Entry</h3>
            </div>
          
            <div class="panel-body">

                <div class="row">
                    <form method="POST" id="filter_form" action="{{ route('eodsetting_getlist') }}">
                        @csrf   
                        
                        <div class="container">
                            <div class="row">
                                
                                <div class='col-md-3' style="width:250px;">
                                    <div class="form-group">
                                        <div class='input-group date' id='start_date_container'>
                                            <input type='text' class="form-control datePicker" name="dates" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="dates" placeholder="Start Date" readonly/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
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
                                    <input type="submit" class="btn btn-success" value="Generate">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                @if(isset($data) && count($data))
                <table class="table table-bordered table-striped table-hover">
                    <tr>
                        <th><input type='checkbox' class='' id='selectAll'></th>
                        <th>EOD Date</th>
                        <th>Batch ID</th>
                        <th>Taxable Sales</th>
                        <th>Non Taxable Sales</th>
                        <th>Total Tax</th>
                        <th>Net Sales</th>
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
                        <input type="button" class="btn pull-right" id='adjust' value="Adjust" disabled>
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
        </div>
    </div>
</div>


<div class="modal fade" id="taxSettingsModal" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title" style="font-size:17px;">Select the appropriate options</h4>
    </div>
    <div class="modal-body">

        <div class='row row-margin-05'>
            <div class='col-md-4'>
                <i class="fa fa-info-circle" style='color:red;' title='You can subtract by entering negative values'></i>
                Add Amount By
            </div>
            <div class='col-md-4 checkboxes'><input type='radio' name='addAmtBy' value='Dollar' checked> Dollars ($)</div>
            <div class='col-md-4'><input type='radio' name='addAmtBy' value='Percent'> Percentage (%)</div>
        </div>
        
        
        <div class='row row-margin-10'>
            <div class='col-md-6'><b>Taxable</b></div>
            <div class='col-md-6'><b>Non -Taxable</b></div>
        </div>
        
        <div class='row row-margin-05'>
            <div class='col-md-6'> 
                <div class='col-md-6'><span class='pull-right'>Tax 1</span></div>
                <div class='col-md-6'><input type='text' class='floatValue col-md-10 eodSettings' id='taxableValue1' name='taxable_value_1' /></div>
            </div>
            <div class='col-md-6'> 
                <div class='col-md-6'><span class='pull-right'>Value</span></div>
                <div class='col-md-6'><input type='text' class='floatValue col-md-10 eodSettings' id='nonTaxableValue' name='non_taxable_value' /></div>
            </div>
        </div>
        
        <div class='row row-margin-05'>
            <div class='col-md-6'> 
                <div class='col-md-6'><span class='pull-right'>Tax 2</span></div>
                <div class='col-md-6'><input type='text' class='floatValue col-md-10 eodSettings' id='taxableValue2' name='taxable_value_2' /></div>
            </div>
        </div>
        
        <div class='row row-margin-05'>
            <div class='col-md-6'> 
                <div class='col-md-6'><span class='pull-right'>Tax 3</span></div>
                <div class='col-md-6'><input type='text' class='floatValue col-md-10 eodSettings' id='taxableValue3' name='taxable_value_3' /></div>
            </div>
        </div>
        
        <div class='row row-margin-05'>
            <div class='col-md-12'> 
                
            </div>
        </div>
        
        
        
        <!--<div class='row row-margin-10'>
            <div class='col-md-3'></div>
            <div class='col-md-3'>Tax 1</div>
            <div class='col-md-3'>Tax 2</div>
            <div class='col-md-3'>Tax 3</div>
        </div>
        
        <div class='row row-margin-05'>
            <div class='col-md-3'>Taxable Value</div>
            <div class='col-md-3'><input type='text' class='floatValue col-md-10' id='taxableValue' name='taxable_value' /></div>
            <div class='col-md-3'><input type='text' class='floatValue col-md-10' id='taxableValue' name='taxable_value' /></div>
            <div class='col-md-3'><input type='text' class='floatValue col-md-10' id='taxableValue' name='taxable_value' /></div>
        </div>
        
        <div class='row row-margin-05'>
            <div class='col-md-3'>Non-Taxable Value</div>
            <div class='col-md-3'><input type='text' class='floatValue col-md-10' id='nonTaxableValue' name='non_taxable_value'></div>
            <div class='col-md-3'><input type='text' class='floatValue col-md-10' id='nonTaxableValue' name='non_taxable_value'></div>
            <div class='col-md-3'><input type='text' class='floatValue col-md-10' id='nonTaxableValue' name='non_taxable_value'></div>
        </div>-->
        
        <div class='row row-margin-20'>
            <div class='col-md-4 col-md-offset-4'>
                <input class='btn btn-success col-md-5' type='button' id='saveValues' value='Ok'>
                <input class='btn btn-danger col-md-5 pull-right' type='button' value='Cancel' onclick="$('#taxSettingsModal').modal('hide');">
            </div>
        </div>
        
    </div>
  </div>
  
</div>
</div>
@endsection   
@section('scripts')  

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
            
            bootbox.alert({ 
                size: 'medium',
                title: "Warning !!", 
                message: 'Cannot proceed without any inputs !!', 
                callback: function(){}
            });
            
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
                // $('p').append('status: ' + status + ', data: ' + data);
                console.log('status: ' + status + ', data: ' + data.message);
                
                bootbox.alert({ 
                    size: 'small',
                    title: "Success", 
                    message: data.message, 
                    callback: function(){
                        window.location.href = '{{ route('eodsetting_getlist') }}';
                    }
                });
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
