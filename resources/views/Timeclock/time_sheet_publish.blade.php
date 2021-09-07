@extends('layouts.master')

@section('title')
    Time Sheet Publish
@stop

@section('main-content')
{{-- {{ dd($users) }} --}}
<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        {{-- <h1>Users List</h1> --}}
        <ul class="breadcrumb">
          <li><a href="">users</a></li>
        </ul>
      </div>
    </div>
    <div class="container-fluid">
        @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                {{session()->get('message')}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
      <div class="panel panel-default">
        <div class="panel-heading head_title">
          <h3 class="panel-title"> Time Sheet publish<i class="fa fa-list"></i> </h3>

        </div>
        
        <div class="row" style="padding-bottom: 10px;float: right;">
          <div class="col-md-12">
           
            <a id="pdf_export_btn" href="" class="" style="margin-right:10px;">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
             </a>    
        </div>
    </div>        

        <div class="panel-body">
          <div class="row" style="padding-bottom: 9px;float: right;">
            <div class="col-md-12">
              <div class="">
                <!--<a href="" class="btn btn-primary add_new_btn_rotate"><i></i>&nbsp;&nbsp;Edit Hours</a>
                <a href="" class="btn btn-primary add_new_btn_rotate"><i></i>&nbsp;&nbsp;Save</a>
               <a href="javascript: history.go(-1)"class="btn add_new_btn_rotate button_color"  ><i></i>&nbsp;&nbsp;Cancel </a>-->
                
              </div>
            </div>
          </div>
     </div>
        
       <div style="text-align:center"><b>Week of {{date('F.d.Y', strtotime($start_date))}} - {{date('F.d.Y', strtotime($end_date))}} </b></div>
                         
              
                         
              
      <br>
    
          <div>
                <table class=" table" >
                    <thead>
                        <tr bgcolor="#1e91cf">
                            <th class="emp_width bordr_set" > Employee</th>
                            <th> Pay Type</th>
                            <th>Hours Worked</th>
                            
                            <th>Over Time</th>
                            <th>Pay Rate</th>
                            <th>Overtime Rate</th>
                            <th>Gross Pay</th>
                            <th>Tips</th>
                        </tr>
                        </tr>
                    <thead id="time_sheet_publish" >
                   </thead> 
                   @foreach($week_list as $week_list)
                     <tr class="working ">
                         <td class="emp_bg bordr_set  ">  {{ $week_list->empname}}</td>
                         <td > {{ $week_list->pay_type}}</td>
                            <?php 
                            
                                if(isset($week_list->sun_logid)){
                                $datetime1 = new DateTime($week_list->sun_login);$datetime2 = new DateTime($week_list->sun_logout);$interval = $datetime1->diff($datetime2);   
                                } 
                                if(isset($week_list->mon_login)){
                                $datetime1 = new DateTime($week_list->mon_login);$datetime2 = new DateTime($week_list->mon_logout);$interval_mon = $datetime1->diff($datetime2);   
                                }
                                if(isset($week_list->tue_login)){
                                $datetime1 = new DateTime($week_list->tue_login);$datetime2 = new DateTime($week_list->tue_logout);$interval_tue = $datetime1->diff($datetime2);   
                                }
                                if(isset($week_list->wed_login)){
                                $datetime1 = new DateTime($week_list->wed_login);$datetime2 = new DateTime($week_list->wed_logout);$interval_wed = $datetime1->diff($datetime2);   
                                }
                                if(isset($week_list->thu_login)){
                                $datetime1 = new DateTime($week_list->thu_login);$datetime2 = new DateTime($week_list->thu_logout);$interval_thu = $datetime1->diff($datetime2);   
                                }
                                if(isset($week_list->fri_login)){
                                $datetime1 = new DateTime($week_list->fri_login);$datetime2 = new DateTime($week_list->fri_logout);$interval_fri = $datetime1->diff($datetime2);   
                                }
                                if(isset($week_list->sat_login)){
                                $datetime1 = new DateTime($week_list->sat_login);$datetime2 = new DateTime($week_list->sat_logout);$interval_sat = $datetime1->diff($datetime2);   
                                }
                                
                                $sun= isset($interval) ? $interval->format('%h'):0;
                          
                           $mon= isset($interval_mon) ?$interval_mon->format('%h'):0;
                           $tue= isset($interval_tue) ?$interval_tue->format('%h'):0;
                           $wed= isset($interval_wed) ?$interval_wed->format('%h'):0;
                           $thu= isset($interval_thu) ?$interval_thu->format('%h'):0;
                           $fri= isset($interval_fri) ?$interval_fri->format('%h'):0;
                           $sat= isset($interval_sat) ?$interval_sat->format('%h'):0;
                           
                           $sun_min= isset($interval) ? $interval->format('%i'):0;
                           $mon_min= isset($interval_mon) ?$interval_mon->format('%i'):0;
                           $tue_min= isset($interval_tue) ?$interval_tue->format('%i'):0;
                           $wed_min= isset($interval_wed) ?$interval_wed->format('%i'):0;
                           $thu_min= isset($interval_thu) ?$interval_thu->format('%i'):0;
                           $fri_min= isset($interval_fri) ?$interval_fri->format('%i'):0;
                           $sat_min= isset($interval_sat) ?$interval_sat->format('%i'):0;
                           unset($interval,$interval_mon,$interval_tue,$interval_wed,$interval_thu,$interval_fri,$interv_sat);
                       
                           $total_hr=$sun+$mon+$tue+$wed+$thu+$fri+$sat;
                           
                           $total_min=$sun_min+$mon_min+$tue_min+$wed_min+$thu_min+$fri_min+$sat_min;
                           
                           
                            $add_min=$total_min%60;
                            $addhr=(int)($total_min/60);
                            $th=$addhr+$total_hr;
                            $tm=$add_min;
                                
                            ?>
                            <td>{{$th}} Hours {{ $tm}} Min</td>
                            
                            <?php 
                            $overtime=$overmin=$_40hrpay=$overpay=$overminpay=$gpay=0;
                             if($week_list->pay_type=='Salary')
                             {
                                 $overmin=0; $overtime=0;
                                 if($th>=40)
                                 {
                                     $overtime=$th-40;
                                     $overmin=$tm;
                                     $_40hrpay=40*$week_list->pay_rate;
                                     $overpay=$overtime*$week_list->over_time;
                                     $overminpay=($overmin/60)*$week_list->over_time;
                                     $gpay=$overpay+$overminpay+$_40hrpay;
                                     
                                 }else{
                                     $gpay=($th*$week_list->pay_rate)+($tm/60*$week_list->pay_rate);
                                 }
                            } 
                            if($week_list->pay_type=='Hourly')
                             {
                                 $overmin=0; $overtime=0;
                                 if($th>=60)
                                 {
                                     $overtime=$th-40;
                                     $overmin=$tm;
                                     $_40hrpay=60*$week_list->pay_rate;
                                     $overpay=$overtime*$week_list->over_time;
                                     $overminpay=($overmin/60)*$week_list->over_time;
                                     $gpay=$overpay+$overminpay+$_40hrpay;
                                     
                                 }
                                 else{
                                      $gpay=($th*$week_list->pay_rate)+($tm/60*$week_list->pay_rate);
                                 }
                            }  
                            else
                                 {
                                     $overtime=0;
                                 }
                             
                             
                            ?>
                            
                            <td>{{$overtime}} Hours {{ $overmin}} Min</td>
                           
                            <td>${{number_format($week_list->pay_rate,2)}}</td>
                            <td>${{number_format($week_list->over_time,2)}}</td>
                            <td>${{number_format($gpay,2)}}</td>
                            <td>$10</td>
                            
                     </tr>     
                  @endforeach   
                    </thead>   
                </table>
                
          </div>
   
      
    </div>
  </div>
@endsection

@section('scripts')
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<link type="text/css" href="{{ asset('stylesheet/select2/css/select2.min.css') }}" rel="stylesheet" />

<script type="text/javascript">
    $(window).load(function() {
        $("div#divLoading").removeClass('show');
    });
</script>

<script>
var range = document.getElementById('date_range');

var m= document.getElementById('m');
var t= document.getElementById('t');
var w= document.getElementById('w');
var th= document.getElementById('th');
var f= document.getElementById('f');
var sa= document.getElementById('sa');
var s= document.getElementById('s');

var offset = 0;
var today = new Date();
var dayOfWeekOffset = today.getDay();


function getWeek(offset) {
  offset = offset || 0; // if the function did not supply a new offset, the offset is 0
  
  var firstDay = new Date();
  firstDay.setDate(firstDay.getDate() - dayOfWeekOffset + (offset * 7));
  
  var lastDay = new Date(firstDay);
  lastDay.setDate(lastDay.getDate() + 6);
  
  
  
  var fri=new Date(firstDay);
  fri.setDate(fri.getDate() + 5);
  
  var thur=new Date(firstDay);
  thur.setDate(thur.getDate() + 4);
  
  var wed=new Date(firstDay);
  wed.setDate(wed.getDate() + 3);
  
  var tue=new Date(firstDay);
  tue.setDate(tue.getDate() + 2);
  
  var mon=new Date(firstDay);
  mon.setDate(mon.getDate() + 1);
  
  
  
  var sunday=makeDateString_for_dy(firstDay);
  var monday=makeDateString_for_dy(mon);
  var tuesday=makeDateString_for_dy(tue);
  var wednesday=makeDateString_for_dy(wed);
  var thursday=makeDateString_for_dy(thur);
  var friday=makeDateString_for_dy(fri);
  var saturday=makeDateString_for_dy(lastDay);
  
  //data fetch start ajax
  var sunday_date=convert(firstDay);
  var monday_date=convert(mon);
  var tuesday_date=convert(tue);
  var wednesday_date=convert(wed);
  var thursday_date=convert(thur);
  var friday_date=convert(fri);
  var saturday_date=convert(lastDay);
  
  var data=call_ajax(sunday_date,saturday_date)
    function call_ajax(date1,date2){
        var start_date=date1;
        var end_date=date2;
        var time_sheet_url = "{{route('time_sheet_data_week')}}";
        time_sheet_url = time_sheet_url.replace(/&amp;/g, '&');
       
        $.ajax({
            url : time_sheet_url,
            headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
            data : {start_date: start_date, end_date: end_date},
            type : 'get',
             contentType: "application/json",
            dataType: 'HTML',
            success: function(data) {
            if(data){
                $("thead#time_sheet").html(data);
                 $('.datetimepicker3').datetimepicker({
                   
                     format: 'LT'
                });
                       
               
            }else{
               $("#time_sheet").html(data);
                
            }
            
            }

        });
  
    }
  
  //time sheet data fetch end ajax
  
  range.innerHTML = 'week of ' + makeDateString(firstDay) + ' - ' + makeDateString(lastDay);
  m.innerHTML = monday;
  t.innerHTML = tuesday;
  w.innerHTML = wednesday;
  th.innerHTML = thursday;
  f.innerHTML = friday;
  sa.innerHTML = saturday;
  s.innerHTML = sunday;
  
  
}


function makeDateString(date) {
  var dd = date.getDate();
  var mm = date.getMonth() + 1;
  
  var y = date.getFullYear();
  
  if(mm==1){
        var mm="January";
  }
  if(mm==2 ){
      
    var mm="February";
    
  }
  
  if(mm==3){
        var mm="March";
  }
  if(mm==4 ){
      
    var mm="April";
    
  }

 if(mm==5){
        var mm="May";
  }
  if(mm==6 ){
      
    var mm="June";
    
  }

 if(mm==7){
        var mm="July";
  }
  if(mm==8 ){
      
    var mm="August";
    
  }

 if(mm==9){
        var mm="September";
  }
  if(mm==10 ){
      
    var mm="October";
    
  }

 if(mm==11){
        var mm="November";
  }
  if(mm==12 ){
      
    var mm="December";
    
  }


  var dateString = mm + '  '+ dd + ',  ' + y;
  return dateString;

}


function makeDateString_for_dy(date) {
  var dd = date.getDate();
  var mm = date.getMonth() + 1;
  
  var y = date.getFullYear();
  
  if(mm==1){
        var mm="Jan";
  }
  if(mm==2 ){
      
    var mm="Feb";
    
  }
  
  if(mm==3){
        var mm="Mar";
  }
  if(mm==4 ){
      
    var mm="Apr";
    
  }

 if(mm==5){
        var mm="May";
  }
  if(mm==6 ){
      
    var mm="June";
    
  }

 if(mm==7){
        var mm="July";
  }
  if(mm==8 ){
      
    var mm="Aug";
    
  }

 if(mm==9){
        var mm="Sept";
  }
  if(mm==10 ){
      
    var mm="Oct";
    
  }

 if(mm==11){
        var mm="Nov";
  }
  if(mm==12 ){
      
    var mm="Dec";
    
  }


  var dateString = mm + '  '+ dd ;
  return dateString;

}

function backward() {
  offset = offset - 1;
  getWeek(offset);
}

function forward() {
  offset = offset + 1;
  getWeek(offset);
}

window.onload = function() {
  getWeek();
}
</script>

  <style>
    .disabled {
    pointer-events:none; //This makes it not clickable
 
    }

</style>
<style>
    button, input[type="button"], input[type="button"] {
	background: none;
	color: inherit;
	border: none;
	padding: 0;
	font: inherit;
	cursor: pointer;
	outline: inherit;
}


</style>
<style>
table td {
    
     text-align: center;
     font-size: 15px;
   
}
.alignleft {
	float: left;
}
.alignright {
	float: right;
}
.span_class{
    
}
th{
    font-weight: normal;
    text-align: center;
    
}
.td_heder{
    font-weight: normal;
    
}

.normal_text{
    font-weight: normal;
  
}


.working{
    background:#75feac;
    color: black;
    font-size:15px;
    
    
}
.log_hours{
    font-size: 8px;
    color:#708b81;
    text-align: center;
    
}
.not_log_hours_color{
    background:#fb8383;
    color:#708b81;
}

.button_color{
    background:#1cbf4c;
     color: white;
}
.emp_bg{
    background:#f3f3f3;
}
.emp_width{
    width:125px;
}
.bordr_set{
  border-right: 15px solid white;  
}
.tips_style{
    width: 37px;
}

table, th, td {
  border: 2px solid white;
}
</style>
<script>
    function convert(str) {
    var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
    return [date.getFullYear(), mnth, day].join("-");
}
</script>
<script>
  

    $(document).on("click", "#pdf_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('time_sheet_publish_pdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "time_sheet_publish_pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "time_sheet_publish_pdf";

              // append the link to the document body

              document.body.appendChild(link);

              link.click();
               
            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();
        
    });
</script>
@endsection



