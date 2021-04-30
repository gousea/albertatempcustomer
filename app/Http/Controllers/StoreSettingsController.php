<?php
namespace App\Http\Controllers;
use App\Model\StoreSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;
class StoreSettingsController extends Controller
{
	private $error = array();
	
	public function index(Request $request) {
		return $this->getList($request);
	}
	
	public function edit_list(Request $request) {
        ini_set('memory_limit', '2G');
        ini_set('max_execution_time', 0);
        $input = $request->all();
        $model = new StoreSettings();
		
		if ( $request->isMethod('POST') ) {
			$datas = $input['store_setting']; 
			
			$result = $model->editlistStoreSettings($datas);
            $result = json_decode(json_encode($result), true);
            
			if(isset($result['status']) && $result['status'] === 'success'){
                
                $EndOfDayTime = DB::connection('mysql_dynamic')->select("SELECT vsettingname, vsettingvalue FROM mst_storesetting WHERE vsettingname = 'EndOfDayTime' ");
                $EndOfDayTime = json_decode(json_encode($EndOfDayTime), true);
                
                //=====Convert into UTC time(simce crone job runs according to UTC Timezone(5 hours ahead of us time))============
                $newdate = new \DateTime($EndOfDayTime[0]['vsettingvalue']);
                
                // $newdate->modify('+5 hours');
                
                // Since they are following EDT so UTC is ahead by 4 hours
                // $newdate->modify('+4 hours');
                $newdate->modify('+5 hours');
                $EndOfDayTimeUTC = $newdate->format("H:i"); 
                // echo $EndOfDayTimeUTC; die;
                
                $hour = \DateTime::createFromFormat('H:i', $EndOfDayTimeUTC);
                $hour = $hour->format('H');
                $minute = \DateTime::createFromFormat('H:i', $EndOfDayTimeUTC);
                $minute = $minute->format('i');
                
                $cronfiles=exec('crontab -l',$output); //========check carefully====don't remove this
                
                //=====store all previous crone job in file==
                $sid = (int)(session()->get('sid'));
                $filename = "cronejob".$sid.".text";
                $file_path = storage_path("upload/{$filename}");
                
                $cronejobefile = fopen($file_path, 'a');
                
                $datetime = date("m-d-Y : H:i");
                $datetime = PHP_EOL.$datetime;
                fwrite($cronejobefile, $datetime);
                
                // $cronejob = "* * * * * wget -q -O /dev/null https://devportal.albertapayments.com/endofday/index?sid=".$sid." > /dev/null 2>&1";
                
                $cronejob = $minute." ".$hour." * * * wget -q -O /dev/null https://portal.albertapayments.com/endofday/index?sid=".$sid." > /dev/null 2>&1";
                foreach($output as $value){
                    // print_r($value);
                    $text = $value.PHP_EOL;
                    fwrite($cronejobefile, $text);
                    
                    preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $value, $match);
                    
                    if(isset($match[0][0])){
                        $check = $match[0][0];
                    }else{
                        $check = '';
                    }
                    $remove_cronejob_url = "https://portal.albertapayments.com/endofday/index?sid=".$sid;
                    
                    if($remove_cronejob_url === $check){
                        $key = array_search($value, $output);
                        unset($output[$key]);
                    }
                }
                
                fclose($cronejobefile);
                
                exec('crontab -r');//========check carefully====
                
                foreach($output as $v){
                    if(!empty($v)){
                        exec('echo -e "`crontab -l`\n'.$v.'" | crontab -');
                    }
                }
                
                 //   $cronejob = $minute." ".$hour." * * * https://devportal.albertapayments.com/endofday/test?sid=".$sid;
                 //   dd($cronejob);
                 //   $cronejob = "* * * * * wget -q -O /dev/null https://devportal.albertapayments.com/endofday/index?sid=".$sid." > /dev/null 2>&1";
			    
			    $EndOfDay = DB::connection('mysql_dynamic')->select("SELECT vsettingname, vsettingvalue FROM mst_storesetting WHERE vsettingname = 'EndOfDay' ");
                $EndOfDay = json_decode(json_encode($EndOfDay), true);
                
                if($EndOfDay[0]['vsettingvalue'] == 'Yes'){
                    exec('echo -e "`crontab -l`\n'.$cronejob.'" | crontab -');
                }
                //   exec('echo -e "`crontab -l`\n'.$cronejob.'" | crontab -');
			}
			
			session()->put('success', "Success: You have modified Store Settings!");
			$url = 'store_setting';
            // return redirect(route('store_setting'));
            return redirect($url);
		  }
    	$this->getList($request);
  	  }
	
	protected function getList($request) {
        $input = $request->all();
        $model = new StoreSettings();
		if (isset($input['filter_menuid'])) {
			$filter_menuid = $input['filter_menuid'];
			$data['filter_menuid'] = $input['filter_menuid'];
		}else if (isset($tinput['filter_menuid'])) {
			$filter_menuid = $tinput['filter_menuid'];
			$data['filter_menuid'] = $tinput['filter_menuid'];
		}else {
			$filter_menuid = null;
			$data['filter_menuid'] = null;
		}
		if (isset($input['sort'])) {
			$sort = $input['sort'];
		} else {
			$sort = 'Id';
		}
		if (isset($input['order'])) {
			$order = $input['order'];
		} else {
			$order = 'ASC';
		}
		if (isset($input['page'])) {
			$page = $input['page'];
		} else {
			$page = 1;
		}
		$url = '';
		if (isset($input['filter_menuid'])) {
			$url .= '&filter_menuid=' . urlencode(html_entity_decode($input['filter_menuid'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($input['sort'])) {
			$url .= '&sort=' . $input['sort'];
		}
		if (isset($input['order'])) {
			$url .= '&order=' . $input['order'];
		}
		if (isset($input['page'])) {
			$url .= '&page=' . $input['page'];
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => url('/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'text' => "Store Setting",
			'href' => url('/store_setting')
		);
		$data['add'] = url('/store_setting/add');
		$data['edit'] = url('/store_setting/edit');
		$data['delete'] = url('/store_setting/delete');
		$data['edit_list'] = url('/store_setting/edit_list');
		$data['gettime_url'] = url('/store_setting/get_time');
		$data['store_settings'] = array();
		$filter_data = array(
			'filter_menuid'  => $filter_menuid,
			// 'sort'  => $sort,
			// 'order' => $order,
			// 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			// 'limit' => $this->config->get('config_limit_admin')
		);
		$results = $model->getStoreSettings();
		$data['store_settings'] = array();
        $results = json_decode(json_encode($results), true);
		foreach ($results as $key => $result) {
			$data['store_settings'][$result['vsettingname']] = $result;
		}
		$data['heading_title'] = "Store Setting";
		$data['text_list'] = "Store Setting";
		$data['text_no_results'] = "No Results Found";
		$data['text_confirm'] = "";
		$data['column_setting_name'] = "Setting Name";
		$data['column_setting_value'] = "Setting Value";
		// $data['Yes'] = $this->language->get('text_yes');
		// $data['No'] = $this->language->get('text_no');
		$data['arr_y_n'][] = "Yes";
		$data['arr_y_n'][] = "No";
		$data['arr_upca_upce'][] = "A";
		$data['arr_upca_upce'][] = "E";
		$data['None'] = "None";
		$data['time_arr'] = array("12:00 am","01:00 am","02:00 am","03:00 am","04:00 am","05:00 am","06:00 am","07:00 am","08:00 am","09:00 am","10:00 am","11:00 am","12:00 pm","01:00 pm","02:00 pm","03:00 pm","04:00 pm","05:00 pm","06:00 pm","07:00 pm","08:00 pm","09:00 pm","10:00 pm","11:00 pm");
	
		$data['button_edit_list'] = 'Update Selected';
		$data['text_special'] = '<strong>Special:</strong>';
		$data['token'] = "";
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (session()->exists('success')) {
			$data['success'] = session()->get('success');
            Session::forget('success');
		} else {
			$data['success'] = '';
		}
		if (isset($tinput['selected'])) {
			$data['selected'] = (array)$tinput['selected'];
		} else {
			$data['selected'] = array();
		}
		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($input['page'])) {
			$url .= '&page=' . $input['page'];
		}
		$url = '';
		if (isset($input['sort'])) {
			$url .= '&sort=' . $input['sort'];
		}
		if (isset($input['order'])) {
			$url .= '&order=' . $input['order'];
		}
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['header'] = "";
		$data['column_left'] = "";
        $data['footer'] = "";
		return view('StoreSettings.store_setting_list', $data);
		// $this->response->setOutput($this->load->view('administration/store_setting_list', $data));
	}
	
	protected function validateEditList() {
    	if(!$this->user->hasPermission('modify', 'administration/tax')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
  	
  	public function get_time(){
	    date_default_timezone_set("US/Eastern");
	    $time  = date('H:i:s');
	   // return $time;
	    	http_response_code(200);
			// $this->response->addHeader('Content-Type: application/json');
	       // $this->response->setOutput(json_encode($data));
	       echo $time;
	}
}
