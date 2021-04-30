<?php

namespace App\Http\Controllers;

use App\Model\EndOfDayShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class EndOfDayShiftController extends Controller
{
    
	private $error = array();

// 	public function index(Request $request) {
// 		return $this->getList($request);
// 	}
	  
	protected function getList(Request $request) {
        $model = new EndOfDayShift();
        $input = $request->all();
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
		$searchbox = '';
		if (isset($input['start_date'])) {
			$data['start_date'] = $input['start_date'];
		} elseif (isset($input['start_date'])) {
			$data['start_date'] = $input['start_date'];
		} else {
			$data['start_date'] = date('m-d-Y');
		}
		
		$data['selected_batch_ids'] = array();
		if ($request->isMethod('POST')) {
			$model->editlist($input);
			$url = '';
            // $data['selected_batch_ids'] = $input['batch'];
			session()->put('success',"Success: You have modified End of Day!");
		  }
		$url = '';
		
		$data['batchdata_assoicited'] = $model->getBatches_assoiciated($data['start_date']);
		$data['batchdata_assoicited'] = json_decode(json_encode($data['batchdata_assoicited']), true);
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
			'text' => "End Of Day Shift",
			'href' => url('/end_of_day')
		);
		$data['add'] = url('/end_of_day/add');
		$data['edit'] = url('/end_of_day/edit');
		$data['delete'] = url('/end_of_day/delete');
        $data['edit_list'] = url('/end_of_day/edit_list');
		$data['reportdata_associated'] = url('/end_of_day/batchdata_assoiciated');
		$data['current_url'] =  url('/end_of_day');
		$data['reopen_close_batch'] = url('/end_of_day/reopen_close_batch');
		$filter_data = array(
			'searchbox'  => $searchbox,
			'sort'  => $sort,
			'order' => $order,
			// 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			// 'limit' => $this->config->get('config_limit_admin')
		);
		$data['batches'] = json_decode(json_encode($model->getBatches()), true);
		$data['heading_title'] = "End of Day";
		$data['text_list'] = "End of Day";
		$data['text_no_results'] = "No Result Found";
		$data['text_confirm'] = "";
		$data['column_name'] = "Name";
		$data['column_value'] = "Value";
		$data['column_action'] = "Action";
		$data['entry_name'] = "Name";
		$data['entry_value'] = "Value";
		$data['button_remove'] = "Remove";
        $data['button_save'] = "Save";
        $data['button_view'] = "View";
        $data['button_add'] = "Add";
        $data['button_edit'] = "Edit";
        $data['button_delete'] = "Delete";
        $data['button_rebuild'] = "Rebuild";
		$data['button_edit_list'] = 'Update Selected';
		$data['text_special'] = '<strong>Special:</strong>';
		$data['token'] = "";
		$data['sid'] = session()->get('sid');
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
        $data['footer'] ="";
        // dd($data);
        return view('EndOfDayShift.end_of_day_shift_list', $data);
	}
	
	protected function validateEditList() {
    	if(!$this->user->hasPermission('modify', 'administration/end_of_shift_printing')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
	
	public function batchdata_assoiciated(Request $request){
        $model = new EndOfDayShift();
	    if ($request->isMethod('POST')) {
            $start_date = $_POST['start_date'];
            $batchdata = $model->getBatches_assoiciated($start_date);
            echo json_encode($batchdata);exit;
	       
	    }
	    
	}
	
    
    public function reopen_close_batch(Request $request){
        
        $input = $request->all();
        
        // $dstartdatetime = \DateTime::createFromFormat('m-d-Y', $input['start_date']);
        // $dstartdatetime = $dstartdatetime->format('Y-m-d');
        // $denddatetime = \DateTime::createFromFormat('m-d-Y', $input['start_date']);
        // $denddatetime = $denddatetime->format('Y-m-d');
        // $dstartdatetime = $dstartdatetime.' '.date('H:i:s');
        // $denddatetime = $denddatetime.' '.date('H:i:s');
        
        try{
            if(count($input['reopen_batches']) == 1){
                
                $check_query = "SELECT * FROM trn_endofday WHERE id ='". $input['reopen_batches'][0]['eodid'] ."'";
                $check_exist = DB::connection('mysql_dynamic')->select($check_query); 
                if(count($check_exist) > 0){
                    $check_exist = json_decode(json_encode($check_exist), true)[0];
                } else {
                    $check_exist = [];
                }
                
                if(count($check_exist) > 0){
                    
                    $batch_data = DB::connection('mysql_dynamic')->select("SELECT ifnull((nnetsales),0.00) as nnetsales, ifnull((nnetpaidout),0.00) as nnetpaidout, ifnull((nnetcashpickup),0.00) as nnetcashpickup, ifnull((nopeningbalance),0.00) as nopeningbalance, ifnull((nclosingbalance),0.00) as nclosingbalance, ifnull((nuserclosingbalance),0.00) as nuserclosingbalance, ifnull((nnetaddcash),0.00) as nnetaddcash, ifnull((ntotalnontaxable),0.00) as ntotalnontaxable, ifnull((ntotaltaxable),0.00) as ntotaltaxable, ifnull((ntotalsales),0.00) as ntotalsales, ifnull((ntotaltax),0.00) as ntotaltax, ifnull((ntotalcreditsales),0.00) as ntotalcreditsales, ifnull((ntotalcashsales),0.00) as ntotalcashsales, ifnull((ntotalgiftsales),0.00) as ntotalgiftsales, ifnull((ntotalchecksales),0.00) as ntotalchecksales, ifnull((ntotalreturns),0.00) as ntotalreturns, ifnull((ntotaldiscount),0.00) as ntotaldiscount, ifnull((ntotaldebitsales),0.00) as ntotaldebitsales, ifnull((ntotalebtsales),0.00) as ntotalebtsales FROM trn_batch WHERE ibatchid = '".$input['reopen_batches'][0]['batchid']."'");
                    $batch_data = json_decode(json_encode($batch_data), true)[0];
                        
                    $query = "UPDATE trn_endofday SET nnetsales =nnetsales- '" . ($batch_data['nnetsales']) . "', nnetpaidout =nnetpaidout- '" . ($batch_data['nnetpaidout']) . "', 
                        nnetcashpickup =nnetcashpickup- '" . ($batch_data['nnetcashpickup']) . "', 
                        nopeningbalance =nopeningbalance- '" . ($batch_data['nopeningbalance']) . "', 
                        nclosingbalance =nclosingbalance- '" . ($batch_data['nclosingbalance']) . "', 
                        nuserclosingbalance =nuserclosingbalance- '" . ($batch_data['nuserclosingbalance']) . "', 
                        nnetaddcash =nnetaddcash- '" . ($batch_data['nnetaddcash']) . "',  ntotalnontaxable =ntotalnontaxable- '" . ($batch_data['ntotalnontaxable']) . "',
                        ntotaltaxable =ntotaltaxable- '" . ($batch_data['ntotaltaxable']) . "', ntotalsales =ntotalsales- '" . ($batch_data['ntotalsales']) . "', 
                        ntotaltax =ntotaltax- '" . ($batch_data['ntotaltax']) . "', ntotalcreditsales =ntotalcreditsales- '" . ($batch_data['ntotalcreditsales']) . "', 
                        ntotalcashsales =ntotalcashsales- '" . ($batch_data['ntotalcashsales']) . "', 
                        ntotalgiftsales =ntotalgiftsales- '" . ($batch_data['ntotalgiftsales']) . "', 
                        ntotalchecksales =ntotalchecksales- '" . ($batch_data['ntotalchecksales']) . "', 
                        ntotalreturns =ntotalreturns- '" . ($batch_data['ntotalreturns']) . "', ntotaldiscount =ntotaldiscount- '" . ($batch_data['ntotaldiscount']) . "',
                        ntotaldebitsales =ntotaldebitsales- '" . ($batch_data['ntotaldebitsales']) . "', 
                        ntotalebtsales =ntotalebtsales- '" . ($batch_data['ntotalebtsales']) . "'
                        WHERE id='". $check_exist['id'] ."' ";
                    DB::connection('mysql_dynamic')->update($query) ;
                    
                    $sid = session()->get('sid');
                    $a = DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_endofdaydetail',`Action` = 'delete',`TableId` = '" . (int)($input['reopen_batches'][0]['batchid']) . "',SID = '" . (int)($sid)."'");
                    
                    DB::connection('mysql_dynamic')->statement("DELETE FROM trn_endofdaydetail where batchid = '" . ($input['reopen_batches'][0]['batchid']) . "' AND eodid = '".$check_exist['id']."'");
                    DB::connection('mysql_dynamic')->update("UPDATE trn_batch SET endofday = '0' WHERE ibatchid='". ($input['reopen_batches'][0]['batchid']) ."'");
                }
                
            }else{
                $reopen_batches = array();
                foreach ($input['reopen_batches'] as $k => $v) {
                    $reopen_batches[] = $v['batchid'];
                    $eodid = $v['eodid'];
                }
                
                $check_query = "SELECT * FROM trn_endofday WHERE id ='". $eodid ."'";
                $check_exist = DB::connection('mysql_dynamic')->select($check_query);
                if(count($check_exist) > 0){
                    $check_exist = json_decode(json_encode($check_exist), true)[0];
                } else {
                    $check_exist = [];
                }
                
                if(count($check_exist) > 0){
                    $exist_batch_ids = DB::connection('mysql_dynamic')->select("SELECT batchid FROM trn_endofdaydetail WHERE eodid='". $eodid ."'");
                    $exist_batch_ids = json_decode(json_encode($exist_batch_ids), true);
                    
                    $old_batch_ids = array();
                    if(count($exist_batch_ids) > 0){
                        foreach ($exist_batch_ids as $k => $v) {
                            $old_batch_ids[] = $v['batchid'];
                        }
                    }
                    
                    $batches = array();
                    //===seprating the batches which are not going to reopen====
                    foreach ($old_batch_ids as $new_v) { 
                        if(!in_array($new_v, $reopen_batches)){
                            $batches[]= $new_v;
                        } 
                    }
                    
                    if(count($batches) > 0){
                        $batch_ids = implode(',', $batches);
                        $batch_data_query = "SELECT ifnull(SUM(nnetsales),0.00) as nnetsales, ifnull(SUM(nnetpaidout),0.00) as nnetpaidout, ifnull(SUM(nnetcashpickup),0.00) as nnetcashpickup, ifnull(SUM(nopeningbalance),0.00) as nopeningbalance, ifnull(SUM(nclosingbalance),0.00) as nclosingbalance, ifnull(SUM(nuserclosingbalance),0.00) as nuserclosingbalance, ifnull(SUM(nnetaddcash),0.00) as nnetaddcash, ifnull(SUM(ntotalnontaxable),0.00) as ntotalnontaxable, ifnull(SUM(ntotaltaxable),0.00) as ntotaltaxable, ifnull(SUM(ntotalsales),0.00) as ntotalsales, ifnull(SUM(ntotaltax),0.00) as ntotaltax, ifnull(SUM(ntotalcreditsales),0.00) as ntotalcreditsales, ifnull(SUM(ntotalcashsales),0.00) as ntotalcashsales, ifnull(SUM(ntotalgiftsales),0.00) as ntotalgiftsales, ifnull(SUM(ntotalchecksales),0.00) as ntotalchecksales, ifnull(SUM(ntotalreturns),0.00) as ntotalreturns, ifnull(SUM(ntotaldiscount),0.00) as ntotaldiscount, ifnull(SUM(ntotaldebitsales),0.00) as ntotaldebitsales, ifnull(SUM(ntotalebtsales),0.00) as ntotalebtsales FROM trn_batch WHERE ibatchid IN($batch_ids)";
                        $batch_data = DB::connection('mysql_dynamic')->select($batch_data_query);
                        $batch_data = json_decode(json_encode($batch_data), true)[0];
                        
                        $update_query = "UPDATE trn_endofday SET nnetsales = '" . ($batch_data['nnetsales']) . "', nnetpaidout = '" . ($batch_data['nnetpaidout']) . "', nnetcashpickup = '" . ($batch_data['nnetcashpickup']) . "', nopeningbalance = '" . ($batch_data['nopeningbalance']) . "', nclosingbalance = '" . ($batch_data['nclosingbalance']) . "', nuserclosingbalance = '" . ($batch_data['nuserclosingbalance']) . "', nnetaddcash = '" . ($batch_data['nnetaddcash']) . "',  ntotalnontaxable = '" . ($batch_data['ntotalnontaxable']) . "', ntotaltaxable = '" . ($batch_data['ntotaltaxable']) . "', ntotalsales = '" . ($batch_data['ntotalsales']) . "', ntotaltax = '" . ($batch_data['ntotaltax']) . "', ntotalcreditsales = '" . ($batch_data['ntotalcreditsales']) . "', ntotalcashsales = '" . ($batch_data['ntotalcashsales']) . "', ntotalgiftsales = '" . ($batch_data['ntotalgiftsales']) . "', ntotalchecksales = '" . ($batch_data['ntotalchecksales']) . "', ntotalreturns = '" . ($batch_data['ntotalreturns']) . "', ntotaldiscount = '" . ($batch_data['ntotaldiscount']) . "', ntotaldebitsales = '" . ($batch_data['ntotaldebitsales']) . "', ntotalebtsales = '" . ($batch_data['ntotalebtsales']) . "' WHERE id='". $check_exist['id'] ."'";
                        DB::connection('mysql_dynamic')->update($update_query);
                        // $last_id = $this->db2->getLastId();
                        foreach ($reopen_batches as $key => $value) {
                            
                            $sid = session()->get('sid');
                            $a = DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_endofdaydetail',`Action` = 'delete',`TableId` = '" . (int)($value) . "',SID = '" . (int)($sid)."'");
                            
                            DB::connection('mysql_dynamic')->delete("DELETE FROM trn_endofdaydetail where batchid = '" . ($value) . "' AND eodid = '".$check_exist['id']."'");
                            DB::connection('mysql_dynamic')->update("UPDATE trn_batch SET endofday = '0' WHERE ibatchid='". ($value) ."'");
                        }
                    }else{
                        DB::connection('mysql_dynamic')->statement("DELETE FROM trn_endofday where id = '".$check_exist['id']."'");
                        // $last_id = $this->db2->getLastId();
                        foreach ($reopen_batches as $key => $value) {
                            
                            $sid = session()->get('sid');
                            $a = DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_endofdaydetail',`Action` = 'delete',`TableId` = '" . (int)($value) . "',SID = '" . (int)($sid)."'");
                            
                            DB::connection('mysql_dynamic')->statement("DELETE FROM trn_endofdaydetail where batchid = '" . ($value) . "' AND eodid = '".$check_exist['id']."'");
                            DB::connection('mysql_dynamic')->update("UPDATE trn_batch SET endofday = '0' WHERE ibatchid='". ($value) ."'");
                        }
                    }
                }
                
            }
            
        }
        catch ( QueryException $e) {
            // not a MySQL exception
           
            $error['error'] = $e->getMessage(); 
            return $error; 
        }
        
        $success['success'] = 'Successfully Re-Open End of Day Batches';
        return $success;
        
    }

}
