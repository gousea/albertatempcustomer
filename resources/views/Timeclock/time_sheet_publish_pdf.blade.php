<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "Time Sheet" ?></h3>
    
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
         <div style="text-align:center"><b>Week of {{date('F.d.Y', strtotime($start_date))}} - {{date('F.d.Y', strtotime($end_date))}} </b></div>
                        
        <hr>
         
              
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
                         <td class="emp_bg" > {{ $week_list->pay_type}}</td>
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
                            <td class="emp_bg" >{{$th}} Hours {{ $tm}} Min</td>
                            
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
                            
                            <td class="emp_bg" >{{$overtime}} Hours {{ $overmin}} Min</td>
                           
                            <td class="emp_bg" >${{number_format($week_list->pay_rate,2)}}</td>
                            <td class="emp_bg" >${{number_format($week_list->over_time,2)}}</td>
                            <td class="emp_bg" >${{number_format($gpay,2)}}</td>
                            <td class="emp_bg" >$10</td>
                            
                     </tr>     
                  @endforeach   
                    </thead>   
                </table>
                
      </div>
    </div>
  </div>
  
  

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