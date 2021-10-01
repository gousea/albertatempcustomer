<?php
namespace App\Http\Controllers;
use App\Model\UserDynamic;
use App\User;
use App\Model\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Model\Userpermission;
use Illuminate\Support\Facades\Auth;
use App\Model\Permissiongroup;
use App\Model\Userpermissiongroup;
use Illuminate\Auth\Access\Gate;
use App\Model\Reports;
use PDF;
use DateTime;
use Illuminate\Support\Facades\Session;


class TimeClockController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

   public function index(Request $request){

        $input = $request->all();

        return view('Timeclock.time_clock');

   }

    public function update(Request $request){

        $input = $request->all();
        $id=$input['emp_id'];
        $start=$input['start'];
        $end=$input['end'];
        $tc_login_id_list=$input['tc_login_id'];
        $statusClosed ='';
        foreach($id   as $key=>$id_val){

            $emp_id=$id[$key];

            foreach($tc_login_id_list as $key1=>$val){

              $tc_login_id_user=$tc_login_id_list[$key1];
              $start_time = $start[$key1];
              $end_time = $end[$key1];

              $login = date("H:i:s", strtotime($start_time));
              $logout = date("H:i:s", strtotime($end_time));
              //logout_time = CONCAT_WS(' ',DATE(login_time), '".$logout."'),
              $update_time_clock= "UPDATE time_clock_login  SET
              login_time = CONCAT_WS(' ',DATE(login_time), '".$login."'),
              final_logout_time = CONCAT_WS(' ',DATE(login_time), '".$logout."'), status = 'Closed'
              WHERE user_id=$emp_id AND tc_login_id= $tc_login_id_user  AND status = 'Active' ";

                $updateCheck = DB::connection('mysql_dynamic')->update($update_time_clock);
                $updatedResp[] = $updateCheck;
               /* // have to update 'status' = 'Closed' when update check return true  ,
                if($updateCheck){
                    $statusClosed ="UPDATE time_clock_login  SET status = 'Closed'
                                        WHERE user_id=$emp_id AND tc_login_id= $tc_login_id_user  AND status = 'Active' ";
                    DB::connection('mysql_dynamic')->update($statusClosed);
                }*/


            }

        }

        $data['msg']="Time clock Updated";
        return view('Timeclock.time_clock',$data);

    }

    public function time_clock_pdf()
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $data= session()->get('session_data') ;


        $Reports = new Reports;
        $store=$Reports->getStore();
        $store['store']=$store;
        $store['week_list']=$data['week_list'];
        $store['start_date']=$data['start_date'];
        $store['end_date']=$data['end_date'];


        $pdf = PDF::loadView('Timeclock.time_clock_pdf',$store)->setPaper('a4', 'landscape');;

        return $pdf->download('time_sheet.pdf');


    }
    public function time_clock_data_week(Request $request){
        $input = $request->all();
        $sessionData = session()->all();
        //dd($sessionData);

        /*$sql="select user_id,mu.vfname empname,
            max(case when DAYNAME(login_time) = 'Sunday' then login_time else null end) sun_login,
            max(case when DAYNAME(logout_time) = 'Sunday' then logout_time else DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59') end) sun_logout,
            max(case when DAYNAME(login_time) = 'Sunday' then tc_login_id else null end) sun_logid,


            max(case when DAYNAME(login_time) = 'Monday' then login_time else null end) mon_login,
            max(case when DAYNAME(logout_time) = 'Monday' then logout_time else DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59') end) mon_logout,
            max(case when DAYNAME(login_time) = 'Monday' then tc_login_id else null end) mon_logid,

            max(case when DAYNAME(login_time) = 'Tuesday' then login_time else null end) tue_login,
            max(case when DAYNAME(logout_time) = 'Tuesday' then logout_time else DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59') end) tue_logout,
            max(case when DAYNAME(login_time) = 'Tuesday' then tc_login_id else null end) tue_logid,

            max(case when DAYNAME(login_time) = 'Wednesday' then login_time else null end) wed_login,
            max(case when DAYNAME(logout_time) = 'Wednesday' then logout_time else DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59') end) wed_logout,
            max(case when DAYNAME(login_time) = 'Wednesday' then tc_login_id else null end) wed_logid,

            max(case when DAYNAME(login_time) = 'Thursday' then login_time else null end) thu_login,
            max(case when DAYNAME(logout_time) = 'Thursday' then logout_time else DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59') end) thu_logout,
            max(case when DAYNAME(login_time) = 'Thursday' then tc_login_id else null end) thu_logid,

            max(case when DAYNAME(login_time) = 'Friday' then login_time else null end) fri_login,
            max(case when DAYNAME(logout_time) = 'Friday' then logout_time else DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59') end) fri_logout,
            max(case when DAYNAME(login_time) = 'Friday' then tc_login_id else null end) fri_logid,


            max(case when DAYNAME(login_time) = 'Saturday' then login_time else null end) sat_login,
            max(case when DAYNAME(logout_time) = 'Saturday' then logout_time else DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59') end) sat_logout,
            max(case when DAYNAME(login_time) = 'Saturday' then tc_login_id else null end) sat_logid

            from time_clock_login tc
            LEFT JOIN mst_user mu ON mu.iuserid=tc.user_id
            Where login_time between '".$input['start_date']."' and '".$input['end_date']."'
            group by user_id ";*/

        $sql="select user_id,mu.vfname empname,
            min(case when DAYNAME(login_time) = 'Sunday' then login_time else null end) sun_login,
            max(case when DAYNAME(login_time) = 'Sunday' then
                 if(login_time < ifnull(final_logout_time, logout_time),ifnull(final_logout_time, logout_time),DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59'))
                else null end) sun_logout,
            max(case when DAYNAME(login_time) = 'Sunday' then tc_login_id else null end) sun_logid,


            min(case when DAYNAME(login_time) = 'Monday' then login_time else null end) mon_login,
            max(case when DAYNAME(login_time) = 'Monday' then
                 if(login_time < ifnull(final_logout_time, logout_time),ifnull(final_logout_time, logout_time),DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59'))
                else null end) mon_logout,
            max(case when DAYNAME(login_time) = 'Monday' then tc_login_id else null end) mon_logid,

            min(case when DAYNAME(login_time) = 'Tuesday' then login_time else null end) tue_login,
            max(case when DAYNAME(login_time) = 'Tuesday' then
                 if(login_time < ifnull(final_logout_time, logout_time),ifnull(final_logout_time, logout_time),DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59'))
                else null end) tue_logout,
            max(case when DAYNAME(login_time) = 'Tuesday' then tc_login_id else null end) tue_logid,

            min(case when DAYNAME(login_time) = 'Wednesday' then login_time else null end) wed_login,
            max(case when DAYNAME(login_time) = 'Wednesday' then
                 if(login_time < ifnull(final_logout_time, logout_time),ifnull(final_logout_time, logout_time),DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59'))
                else null end) wed_logout,
            max(case when DAYNAME(login_time) = 'Wednesday' then tc_login_id else null end) wed_logid,

            min(case when DAYNAME(login_time) = 'Thursday' then login_time else null end) thu_login,
            max(case when DAYNAME(login_time) = 'Thursday' then
                if(login_time < ifnull(final_logout_time, logout_time),ifnull(final_logout_time, logout_time),DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59'))
                else null end) thu_logout,
            max(case when DAYNAME(login_time) = 'Thursday' then tc_login_id else null end) thu_logid,

            min(case when DAYNAME(login_time) = 'Friday' then login_time else null end) fri_login,
            max(case when DAYNAME(login_time) = 'Friday' then
                 if(login_time < ifnull(final_logout_time, logout_time),ifnull(final_logout_time, logout_time),DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59'))
                else null end) fri_logout,
            max(case when DAYNAME(login_time) = 'Friday' then tc_login_id else null end) fri_logid,


            min(case when DAYNAME(login_time) = 'Saturday' then login_time else null end) sat_login,
            max(case when DAYNAME(login_time) = 'Saturday' then
                 if(login_time < ifnull(final_logout_time, logout_time),ifnull(final_logout_time, logout_time),DATE_FORMAT(login_time, '%Y-%m-%d 23:59:59'))
                else null end) sat_logout,
            max(case when DAYNAME(login_time) = 'Saturday' then tc_login_id else null end) sat_logid

            from time_clock_login tc
            LEFT JOIN mst_user mu ON mu.iuserid=tc.user_id
            Where login_time between '".$input['start_date']."' and '".$input['end_date']."'
            group by user_id ";



          $data['week_list']= DB::connection('mysql_dynamic')->select($sql);
          //dd(  $data['week_list']);
          $data['start_date']=$input['start_date'];
          $data['end_date']=$input['end_date'];


          $html='';

            foreach($data['week_list'] as $week_list){


                 $html.=  '<tr class="working ">

                 <td> <span class="normal_text">  '.$week_list->empname.'</span> <input type="hidden"  name="emp_id[]" value="'.$week_list->user_id.'"  ></span></td></td>';
                if(isset($week_list->sun_logid)){
                     $datetime1 = new DateTime($week_list->sun_login);$datetime2 = new DateTime($week_list->sun_logout);
                    if($week_list->sun_logout == Null || $week_list->sun_logout =='')
                    {
                        $newDate = date('Y-m-d', strtotime($week_list->sun_login));
                        $newDate = date("Y-m-d H:i:s", strtotime("+23 hours 59 minutes",strtotime($newDate)));
                        $datetime2 = new DateTime($newDate);
                    }else{
                        $newDate = date('Y-m-d H:i:s', strtotime($week_list->sun_logout));
                    }
                     $interval = $datetime1->diff($datetime2);
                    $now    = new DateTime();
                    $show= '';
                    $logoutTime = date('h:i A', strtotime($newDate));
                    if($datetime2 > $now){
                        if($logoutTime == '11:59 PM'){
                            $show='-Not Logged Out';
                            $logoutTime =date('h:i A', strtotime($week_list->sun_login));
                            $datetime2 = new DateTime($week_list->sun_login);
                            $interval = $datetime1->diff($datetime2);
                        }else{
                            $show='';
                            $logoutTime =date('h:i A', strtotime($newDate));
                            $interval = $datetime1->diff($datetime2);
                        }
                    }
                     $html.=  '<td class="loggedInT1"><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="'.date('h:i A', strtotime($week_list->sun_login)).'" name="start[]" class="datetimepicker3"> <input type="text" value="'.date('h:i A', strtotime($week_list->sun_logout)).'" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br><sapn class="normal_text">'.$interval->format('%h').' Hours '.$interval->format('%i').' Minutes'.$show.'</sapn><input type="hidden" value="'.$week_list->sun_logid.' " id=""   name="tc_login_id[]"  /></td>' ;
                } else{
                    $html.=  '<td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />';

                }
                if(isset($week_list->mon_login)){
                     $datetime1 = new DateTime($week_list->mon_login);
                     $datetime2 = new DateTime($week_list->mon_logout);
                    if($week_list->mon_logout == Null || $week_list->mon_logout =='')
                    {
                        $newDate = date('Y-m-d', strtotime($week_list->mon_login));
                        $newDate = date("Y-m-d H:i:s", strtotime("+23 hours 59 minutes",strtotime($newDate)));
                        $datetime2 = new DateTime($newDate);
                    }else{
                        $newDate = date('Y-m-d H:i:s', strtotime($week_list->mon_logout));
                    }
                     $interval_mon = $datetime1->diff($datetime2);
                    $now    = new DateTime();
                    $show= '';
                    $logoutTime = date('h:i A', strtotime($newDate));
                    if($datetime2 > $now){
                        if($logoutTime == '11:59 PM'){
                            $show='-Not Logged Out';
                            $logoutTime =date('h:i A', strtotime($week_list->mon_login));
                            $datetime2 = new DateTime($week_list->mon_login);
                            $interval_mon = $datetime1->diff($datetime2);
                        }else{
                            $show='';
                            $logoutTime =date('h:i A', strtotime($newDate));
                            $interval_mon = $datetime1->diff($datetime2);
                        }
                    }
                     $html.=  '<td class="loggedInT1"><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="'.date('h:i A', strtotime($week_list->mon_login)).'" name="start[]" class="datetimepicker3"> <input type="text" value="'.$logoutTime.'" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">'.$interval_mon->format('%h').' Hours '.$interval_mon->format('%i').' Minutes'.$show.'</sapn><input type="hidden" value="'.$week_list->mon_logid.' " id=""   name="tc_login_id[]"  /></td>' ;
                } else{
                    $html.=  '<td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />';

                }

                 if(isset($week_list->tue_login)){
                     $datetime1 = new DateTime($week_list->tue_login);$datetime2 = new DateTime($week_list->tue_logout);
                     if($week_list->tue_logout == Null || $week_list->tue_logout =='')
                     {
                         $newDate = date('Y-m-d', strtotime($week_list->tue_login));
                         $newDate = date("Y-m-d H:i:s", strtotime("+23 hours 59 minutes",strtotime($newDate)));
                         $datetime2 = new DateTime($newDate);
                     }else{
                         $newDate = date('Y-m-d H:i:s', strtotime($week_list->tue_logout));
                     }
                     $interval_tue = $datetime1->diff($datetime2);
                     $now    = new DateTime();
                     $show= '';
                     $logoutTime = date('h:i A', strtotime($newDate));
                     if($datetime2 > $now){
                         if($logoutTime == '11:59 PM'){
                             $show='-Not Logged Out';
                             $logoutTime =date('h:i A', strtotime($week_list->tue_login));
                             $datetime2 = new DateTime($week_list->tue_login);
                             $interval_tue = $datetime1->diff($datetime2);
                         }else{
                             $show='';
                             $logoutTime =date('h:i A', strtotime($newDate));
                             $interval_tue = $datetime1->diff($datetime2);
                         }
                     }
                     $html.=  '<td class="loggedInT1"><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="'.date('h:i A', strtotime($week_list->tue_login)).'" name="start[]" class="datetimepicker3" > <input type="text" value="'.$logoutTime.'" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">'.$interval_tue->format('%h').' Hours '.$interval_tue->format('%i').' Minutes'.$show.'</sapn><input type="hidden" value="'.$week_list->tue_logid.' " id=""   name="tc_login_id[]"  /></td>' ;
                } else{
                    $html.=  '<td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />';

                }

                  if(isset($week_list->wed_login)){
                     $datetime1 = new DateTime($week_list->wed_login);$datetime2 = new DateTime($week_list->wed_logout);
                      if($week_list->wed_logout == Null || $week_list->wed_logout =='')
                      {
                          $newDate = date('Y-m-d', strtotime($week_list->wed_login));
                          $newDate = date("Y-m-d H:i:s", strtotime("+23 hours 59 minutes",strtotime($newDate)));
                          $datetime2 = new DateTime($newDate);
                      }else{
                          $newDate = date('Y-m-d H:i:s', strtotime($week_list->wed_logout));
                      }
                     $interval_wed = $datetime1->diff($datetime2);
                      $now    = new DateTime();
                      $show= '';
                      $logoutTime = date('h:i A', strtotime($newDate));
                      if($datetime2 > $now){
                          if($logoutTime == '11:59 PM'){
                              $show='-Not Logged Out';
                              $logoutTime =date('h:i A', strtotime($week_list->wed_login));
                              $datetime2 = new DateTime($week_list->wed_login);
                              $interval_wed = $datetime1->diff($datetime2);
                          }else{
                              $show='';
                              $logoutTime =date('h:i A', strtotime($newDate));
                              $interval_wed = $datetime1->diff($datetime2);
                          }
                      }
                     $html.=  '<td class="loggedInT1"><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="'.date('h:i A', strtotime($week_list->wed_login)).'" name="start[]" class="datetimepicker3" > <input type="text" value="'.$logoutTime.'" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text"> '.$interval_wed->format('%h').' Hours '.$interval_wed->format('%i').' Minutes'.$show.'</sapn><input type="hidden" value="'.$week_list->wed_logid.' " id=""   name="tc_login_id[]"  /></td>' ;
                } else{
                    $html.=  '<td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />';

                }


                 if(isset($week_list->thu_login)){
                     $datetime1 = new DateTime($week_list->thu_login);$datetime2 = new DateTime($week_list->thu_logout);
                     if($week_list->thu_logout == Null || $week_list->thu_logout =='')
                     {
                         $newDate = date('Y-m-d', strtotime($week_list->thu_login));
                         $newDate = date("Y-m-d H:i:s", strtotime("+23 hours 59 minutes",strtotime($newDate)));
                         $datetime2 = new DateTime($newDate);
                     }else{
                         $newDate = date('Y-m-d H:i:s', strtotime($week_list->thu_logout));
                     }

                     $interval_thu = $datetime1->diff($datetime2);
                     $now    = new DateTime();
                     $show= '';
                     $logoutTime = date('h:i A', strtotime($newDate));
                     if($datetime2 > $now){
                         if($logoutTime == '11:59 PM'){
                             $show='-Not Logged Out';
                             $logoutTime =date('h:i A', strtotime($week_list->thu_login));
                             $datetime2 = new DateTime($week_list->thu_login);
                             $interval_thu = $datetime1->diff($datetime2);
                         }else{
                             $show='';
                             $logoutTime =date('h:i A', strtotime($newDate));
                             $interval_thu = $datetime1->diff($datetime2);
                         }
                     }
                     $html.=  '<td class="loggedInT1"><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="'.date('h:i A', strtotime($week_list->thu_login)).'" name="start[]" class="datetimepicker3" > <input type="text" value="'.$logoutTime.'" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">'.$interval_thu->format('%h').' Hours '.$interval_thu->format('%i').' Minutes'.$show.'</sapn><input type="hidden" value="'.$week_list->thu_logid.' " id=""   name="tc_login_id[]"  /></td>' ;
                } else{
                    $html.=  '<td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />';

                }

                if(isset($week_list->fri_login)){
                     $datetime1 = new DateTime($week_list->fri_login);$datetime2 = new DateTime($week_list->fri_logout);
                    if($week_list->fri_logout == Null || $week_list->fri_logout =='')
                    {
                        $newDate = date('Y-m-d', strtotime($week_list->fri_login));
                        $newDate = date("Y-m-d H:i:s", strtotime("+23 hours 59 minutes",strtotime($newDate)));
                        $datetime2 = new DateTime($newDate);
                    }else{
                        $newDate = date('Y-m-d H:i:s', strtotime($week_list->fri_logout));
                    }
                     $interval_fri = $datetime1->diff($datetime2);
                    $now    = new DateTime();
                    $show= '';
                    $logoutTime = date('h:i A', strtotime($newDate));
                    if($datetime2 > $now){
                        if($logoutTime == '11:59 PM'){
                            $show='-Not Logged Out';
                            $logoutTime =date('h:i A', strtotime($week_list->fri_login));
                            $datetime2 = new DateTime($week_list->fri_login);
                            $interval_fri = $datetime1->diff($datetime2);
                        }else{
                            $show='';
                            $logoutTime =date('h:i A', strtotime($newDate));
                            $interval_fri = $datetime1->diff($datetime2);
                        }
                    }
                     $html.=  '<td class="loggedInT1"><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="'.date('h:i A', strtotime($week_list->fri_login)).'" name="start[]" class="datetimepicker3" > <input type="text" value="'.$logoutTime.'" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">'.$interval_fri->format('%h').' Hours '.$interval_fri->format('%i').' Minutes'.$show.'</sapn><input type="hidden" value="'.$week_list->fri_logid.' " id=""   name="tc_login_id[]"  /></td>' ;
                } else{
                    $html.=  '<td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />';

                }

                 if(isset($week_list->sat_login)){
                     $datetime1 = new DateTime($week_list->sat_login);$datetime2 = new DateTime($week_list->sat_logout);
                     if($week_list->sat_logout == Null || $week_list->sat_logout =='')
                     {
                         $newDate = date('Y-m-d', strtotime($week_list->sat_login));
                         $newDate = date("Y-m-d H:i:s", strtotime("+23 hours 59 minutes",strtotime($newDate)));
                         $datetime2 = new DateTime($newDate);
                     }else{
                         $newDate = date('Y-m-d H:i:s', strtotime($week_list->sat_logout));
                     }
                     $interval_sat = $datetime1->diff($datetime2);
                     $now    = new DateTime();
                     $show= '';
                     $logoutTime = date('h:i A', strtotime($newDate));
                     if($datetime2 > $now){
                         if($logoutTime == '11:59 PM'){
                             $show='-Not Logged Out';
                             $logoutTime =date('h:i A', strtotime($week_list->sat_login));
                             $datetime2 = new DateTime($week_list->sat_login);
                             $interval_sat = $datetime1->diff($datetime2);
                         }else{
                             $show='';
                             $logoutTime =date('h:i A', strtotime($newDate));
                             $interval_sat = $datetime1->diff($datetime2);
                         }
                     }
                     $html.=  '<td class="loggedInT1"><sapn class="log_hours" > Hours logged </sapn><br> <span class="normal_text"><input type="text" value="'.date('h:i A', strtotime($week_list->sat_login)).'" name="start[]" class="datetimepicker3" > <input type="text" value="'.$logoutTime.'" class="datetimepicker3" name="end[]"> </span><br><sapn class="log_hours" >Total Hours</sapn> <br> <sapn class="normal_text">'.$interval_sat->format('%h').' Hours '.$interval_sat->format('%i').' Minutes'.$show.'</sapn><input type="hidden" value="'.$week_list->sat_logid.' " id=""   name="tc_login_id[]"  /></td>' ;
                } else{
                    $html.=  '<td class="not_log_hours_color"><sapn class="log_hours" > Hours logged </sapn><br> <br><sapn class="log_hours" >Total Hours</sapn> <br> </td> <input type="hidden" value="" id="saturday_date" name="saturday_date" />';

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


             $html.=  '<td>'.$th.' Hours '.$tm.'Min</td>';
             $html.=  '</tr>';

           }
          session()->put('session_data',  $data);
          return $html;

     }
     public function handleLogoutTime()
     {
         // should use in future
     }

}
