<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "Time Clock" ?></h3>
    
   <br>
</div> 

<div class="panel-body">
        
       
        <div class="row">  
          <div class="col-md-6 pull-left">
            <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
          </div>
        </div>
        
        <div class="row">
          
          <div class="col-md-6 pull-left">
            <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
          </div>
        </div>
        
        <div class="row">
          
          <div class="col-md-6 pull-left">
            <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
          </div>
        </div>
        <hr>
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
                        </tr>
                    
                    <?php if(isset($week_list) && count($week_list) > 0){?>
            <?php foreach($week_list as $week_list){ ?> 
                   <tr class="working ">
                  
                           
                            <td class="emp_bg bordr_set  "> <span class="normal_text">{{$week_list->empname}} <input type="hidden"  name="emp_id[]" value="{{$week_list->user_id}}"  ></span></td>
                            
                            @if(isset($week_list->sun_logid))
                            
                            <?php
                                $datetime1 = new DateTime($week_list->sun_login);$datetime2 = new DateTime($week_list->sun_logout);$interval = $datetime1->diff($datetime2);
                            ?>       
                            <td ><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="{{date('h:i A', strtotime($week_list->sun_login))}}" name="start[]" class="datetimepicker3"> <input type="text" value="{{date('h:i A', strtotime($week_list->sun_logout))}}" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">{{ $interval->format('%h')." Hours ".$interval->format('%i')." Minutes"}}</sapn>  <input type="hidden" value="{{$week_list->sun_logid}}" id=""   name="tc_login_id[]"  /></td> 
                            @else
                            <td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />
                            @endif
                            
                            @if(isset($week_list->mon_login))
                            <?php
                                $datetime1 = new DateTime($week_list->mon_login);$datetime2 = new DateTime($week_list->mon_logout);$interval_mon = $datetime1->diff($datetime2);
                            ?>       
                            <td ><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="{{date('h:i A', strtotime($week_list->mon_login))}}" name="start[]" class="datetimepicker3"> <input type="text" value="{{date('h:i A', strtotime($week_list->mon_logout))}}" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">{{ $interval_mon->format('%h')." Hours ".$interval_mon->format('%i')." Minutes"}}</sapn>  <input type="hidden" value="{{$week_list->mon_logid}}" id=""   name="tc_login_id[]"  /></td> 
                            @else
                            <td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />
                            @endif
                            
                            @if(isset($week_list->tue_login))
                            <?php
                                $datetime1 = new DateTime($week_list->tue_login);$datetime2 = new DateTime($week_list->tue_logout);$interval_tue = $datetime1->diff($datetime2);
                            ?>       
                            <td ><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="{{date('h:i A', strtotime($week_list->tue_login))}}" name="start[]" class="datetimepicker3"> <input type="text" value="{{date('h:i A', strtotime($week_list->tue_logout))}}" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">{{ $interval_tue->format('%h')." Hours ".$interval_tue->format('%i')." Minutes"}}</sapn>  <input type="hidden" value="{{$week_list->tue_logid}}" id=""   name="tc_login_id[]"  /></td> 
                            @else
                            <td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />
                            @endif
                            
                             @if(isset($week_list->wed_login))
                            <?php
                                $datetime1 = new DateTime($week_list->wed_login);$datetime2 = new DateTime($week_list->wed_logout);$interval_wed = $datetime1->diff($datetime2);
                            ?>       
                            <td ><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="{{date('h:i A', strtotime($week_list->wed_login))}}" name="start[]" class="datetimepicker3"> <input type="text" value="{{date('h:i A', strtotime($week_list->wed_logout))}}" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">{{ $interval_wed->format('%h')." Hours ".$interval_wed->format('%i')." Minutes"}}</sapn>  <input type="hidden" value="{{$week_list->wed_logid}}" id=""   name="tc_login_id[]"  /></td> 
                            @else
                            <td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />
                            @endif
                            
                             @if(isset($week_list->thu_login))
                            <?php
                                $datetime1 = new DateTime($week_list->thu_login);$datetime2 = new DateTime($week_list->thu_logout);$interval_thu = $datetime1->diff($datetime2);
                            ?>       
                            <td ><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="{{date('h:i A', strtotime($week_list->thu_login))}}" name="start[]" class="datetimepicker3"> <input type="text" value="{{date('h:i A', strtotime($week_list->thu_logout))}}" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">{{ $interval_thu->format('%h')." Hours ".$interval_thu->format('%i')." Minutes"}}</sapn>  <input type="hidden" value="{{$week_list->thu_logid}}" id=""   name="tc_login_id[]"  /></td> 
                            @else
                            <td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />
                            @endif
                            
                             @if(isset($week_list->fri_login))
                            <?php
                                $datetime1 = new DateTime($week_list->fri_login);$datetime2 = new DateTime($week_list->fri_logout);$interval_fri = $datetime1->diff($datetime2);
                            ?>       
                            <td ><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="{{date('h:i A', strtotime($week_list->fri_login))}}" name="start[]" class="datetimepicker3"> <input type="text" value="{{date('h:i A', strtotime($week_list->fri_logout))}}" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">{{ $interval_fri->format('%h')." Hours ".$interval_fri->format('%i')." Minutes"}}</sapn>  <input type="hidden" value="{{$week_list->fri_logid}}" id=""   name="tc_login_id[]"  /></td> 
                            @else
                            <td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />
                            @endif
                            
                             @if(isset($week_list->sat_login))
                            <?php
                                $datetime1 = new DateTime($week_list->sat_login);$datetime2 = new DateTime($week_list->sat_logout);$interval_sat = $datetime1->diff($datetime2);
                            ?>       
                            <td ><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="{{date('h:i A', strtotime($week_list->sat_login))}}" name="start[]" class="datetimepicker3"> <input type="text" value="{{date('h:i A', strtotime($week_list->sat_logout))}}" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">{{ $interval_sat->format('%h')." Hours ".$interval_sat->format('%i')." Minutes"}}</sapn>  <input type="hidden" value="{{$week_list->sat_logid}}" id=""   name="tc_login_id[]"  /></td> 
                            @else
                            <td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />
                            @endif
                           <?php 
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
                           
                           <td>{{$th." Hours ".$tm."Min"}}</td>

                    </tr>
             <?php } ?>           
        <?php } ?>
                        
                        
                        
                        
                     </thead>   
                </table>
                
      </div>
    </div>
  </div>
  
  

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
.datetimepicker3{
 outline: none;
 width:46px;
 background:#75feac;
 border-width:0px;
 border:none;
 color: black;
}
</style>