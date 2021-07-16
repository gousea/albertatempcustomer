@extends('layouts.layout')

@section('title')
    Time Clock
@stop

@section('main-content')
<div id="content">
<form name="myForm" action="{{ route('time_clock_save') }}" method="get" enctype="multipart/form-data" class="form-horizontal"  >
  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase" style="font-size: 22px">TIME CLOCK</span>
                </div>
                <div class="nav-submenu">
                    <button type="submit" id="Submit"  class="btn btn-gray headerblack  buttons_menu" title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                    <button type="button" id="pdf_export_btn" class="btn btn-gray headerblack buttons_menu" title="Publish to Time Sheet" >Publish to Time Sheet &nbsp;&nbsp;&nbsp;&nbsp;</button>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
  </nav>
  <section class="section-content py-6">
    <div class="container">
          @if (isset($msg))
              <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                  {{$msg ?? ''}}
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
          @endif
        <div class="panel panel-default">
          <div class="table-responsive">
              <table id="Parent_head" class="text-center table" style="width:100%; margin-bottom:0px; margin-top: 10px; border-collapse: separate; border-spacing:0 5px !important;">
                  <thead style="background-color: #286fb7!important;">
                      <tr>
                          <td  style="text-align:center">
                            <button type="button" onClick="backward()" > <i class="fa fa-caret-left text-light"  style="font-size:20px"> </i> </button>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span  class="td_heder font-weight-bold text-uppercase text-light" id="date_range" style="font-size: 14px"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" onClick="forward()" > <i class="fa fa-caret-right text-light"  style="font-size:20px"></i></button>
                          </td>
                      </tr>
                  </thead>
              </table>
          </div>
          
          <input type="hidden" value="" id="week_number" name='week_number'/>
          
          <input type="hidden"  value=""  id="Year" name='Year' />
              
          <div>
                <table class="table" style="width:100%; margin-top: 10px; border-collapse: separate; border-spacing:0 5px !important;" >
                    <thead style="background-color: #286fb7!important;">
                        <tr>
                            <th class="col-xs-1 headername text-uppercase text-light">Employee</th>
                            <th class="col-xs-1 headername text-uppercase text-light">Sunday <span id="s"></span></th>
                            <th class="col-xs-1 headername text-uppercase text-light">Monday <span id="m"></span></th> 
                            <th class="col-xs-1 headername text-uppercase text-light">Tuesday <span id="t"></span></th>
                            <th class="col-xs-1 headername text-uppercase text-light">Wednesday <span id="w"></span></th>
                            <th class="col-xs-1 headername text-uppercase text-light">Thursday <span id="th"></span></th>
                            <th class="col-xs-1 headername text-uppercase text-light">Friday <span id="f"></span></th>
                            <th class="col-xs-1 headername text-uppercase text-light">Saturday <span id="sa"></span></th>
                            <th class="col-xs-1 headername text-uppercase text-light">Total Hours</th>
                        </tr>
                      <!-- </thead>  -->
                      <thead id="history_items" >
                      </thead> 
                </table>
          </div>
        </div>
      
    </div>
  </section>
  </form>
</div>
@endsection

@section('page-script')
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link type="text/css" href="{{ asset('stylesheet/select2/css/select2.min.css') }}" rel="stylesheet" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" rel="stylesheet"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js" type="text/javascript" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>




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
        var time_clock_url = "{{route('time_clock_data_week')}}";
        time_clock_url = time_clock_url.replace(/&amp;/g, '&');
       
        $.ajax({
            url : time_clock_url,
            headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
            data : {start_date: start_date, end_date: end_date},
            type : 'get',
             contentType: "application/json",
            dataType: 'HTML',
            success: function(data) {
            if(data){
                $("thead#history_items").html(data);
                 $('.datetimepicker3').datetimepicker({
                   
                     format: 'LT'
                });
                       
               
            }else{
               $("#history_items").html(data);
                
            }
            
            }

        });
  
    }
  
  
  
//   document.getElementById('sunday_date').value=sunday_date;
//   document.getElementById('monday_date').value=monday_date;
//   document.getElementById('tuesday_date').value=tuesday_date;
//   document.getElementById('wednesday_date').value=wednesday_date;
//   document.getElementById('thursday_date').value=thursday_date;
//   document.getElementById('friday_date').value=friday_date;
//   document.getElementById('saturday_date').value=saturday_date;
 
  
  
  range.innerHTML = 'week of ' + makeDateString(firstDay) + ' - ' + makeDateString(lastDay);
  m.innerHTML = monday;
  t.innerHTML = tuesday;
  w.innerHTML = wednesday;
  th.innerHTML = thursday;
  f.innerHTML = friday;
  sa.innerHTML = saturday;
  s.innerHTML = sunday;
  
  
  Date.prototype.getWeek = function () {
    var onejan = new Date(this.getFullYear(), 0, 1);
    return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
  };

    var current_Week=firstDay.getWeek(); //
    document.getElementById('week_number').value=current_Week;
    document.getElementById('Year').value=firstDay.getFullYear();


  
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
    font-weight: normal;
    
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
.datetimepicker3{
 outline: none;
 width:46px;
 background:#75feac;
 border-width:0px;
 border:none;
 color: black;
}
</style>
  
<script>
    
 $('.datetimepicker3').datetimepicker({
                   
                     format: 'LT'
});
          
            
</script>
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

    var pdf_export_url = "{{route('time_clock_pdf')}}";
  
    pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

    var req = new XMLHttpRequest();
    req.open("GET", pdf_export_url, true);
    req.responseType = "blob";
    req.onreadystatechange = function () {
      if (req.readyState === 4 && req.status === 200) {

        if (typeof window.navigator.msSaveBlob === 'function') {
          window.navigator.msSaveBlob(req.response, "time_clock.pdf");
        } else {
          var blob = req.response;
          var link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = "time_clock.pdf";

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



