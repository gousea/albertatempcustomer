@extends('layouts.master')

@section('title')
    Time Sheet
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
          <h3 class="panel-title"> Time Sheet<i class="fa fa-list"></i> </h3>

        </div>

        <div class="panel-body">
          <div class="row" style="padding-bottom: 9px;float: right;">
            <div class="col-md-12">
              <div class="">
                <!--<a href="" class="btn btn-primary add_new_btn_rotate"><i></i>&nbsp;&nbsp;Edit Hours</a>
                <a href="" class="btn btn-primary add_new_btn_rotate"><i></i>&nbsp;&nbsp;Save</a>
               <a href=""  id="publish_model" class="btn btn-primary button_color"  ><i></i>&nbsp;&nbsp;Publish </a>-->
                <a href="{{ route('time_sheet_publish') }}"  class="btn btn-primary button_color"  ><i></i>&nbsp;&nbsp;Publish </a>
              </div>
            </div>
          </div>
     </div>
        <div class="table-responsive">
            <table id="Parent_head" class="text-center table table-bordered table-hover" style="margin-bottom:0px;">
                <thead>
                    
                    <tr bgcolor="#1e91cf">
                     
                         <td  style="text-align:center">
                         <button type="button" onClick="backward()" > <i class="fa fa-caret-left "  style="font-size:20px"> </i> </button>&nbsp;&nbsp;&nbsp;&nbsp;
                         <span class="td_heder" id="date_range"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                        
                         <button type="button" onClick="forward()" > <i class="fa fa-caret-right"  style="font-size:20px"></i></button>
                         </td>
                    </tr>
                </thead>
            </table>
            
      </div>
      <br>
    
          <div>
                <table class=" table" >
                    <thead>
                        <tr bgcolor="#1e91cf">
                            <th class="emp_width bordr_set" > Employee</th>
                            <th>Sunday <span id="s"/></th>
                            <th>Monday <span id="m"/></th> 
                            <th>Tuesday <span id="t"/></th>
                            <th>Wednesday <span id="w"/></th>
                            <th>Thursday <span id="th"/></th>
                            <th>Friday <span id="f"/></th>
                            <th>Saturday <span id="sa"/></th>
                            <th>Total Hours</th>
                            <th>Pay</th>
                            <th>Tips Earned</th>
                        </tr>
                    <thead id="time_sheet" >
                   </thead> 
                     
                     </thead>   
                </table>
                
          </div>
   
      
    </div>
  </div>
 
  
<div class="modal" tabindex="-1" role="dialog" id="model_publish_model" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table id="Parent_head" class="text-center table table-bordered table-hover" style="margin-bottom:0px;">
                <thead>
                    
                    <tr bgcolor="#1e91cf">
                     
                         <td  style="text-align:center">
                         
                         <span class="td_heder" id="date_range_publish"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                        
                         
                         </td>
                    </tr>
                </thead>
            </table>
            
      </div>
      <br>
    
          <div>
                <table class=" table" >
                    <thead>
                        <tr bgcolor="#1e91cf">
                            <th class="emp_width bordr_set" > Employee</th>
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
                     
                     </thead>   
                </table>
                
          </div>
   
      
    </div>
    
      <div class="modal-footer">
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
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
var range_publish = document.getElementById('date_range_publish');

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
  
  //time sheet publish data fetch end ajax
  
  var data=call_ajax_publish(sunday_date,saturday_date)
    function call_ajax_publish(date1,date2){
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
                // $("thead#time_sheet_publish").html(data);
                
               
               
            }else{
               $("#time_sheet_publish").html(data);
                
            }
            
            }

        });
  
    } 
  range.innerHTML = 'week of ' + makeDateString(firstDay) + ' - ' + makeDateString(lastDay);
  range_publish.innerHTML = 'week of ' + makeDateString(firstDay) + ' - ' + makeDateString(lastDay);
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
     font-size: 10px;
   
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
body .modal-ku {
width: 750px;
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
$("#publish_model").click(function(){
    $('#model_publish_model').modal('show');
    
});    
</script>
<script>
   
</script>

@endsection



