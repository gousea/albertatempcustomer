<?php

namespace App\Http\Controllers;

use App\Model\Aisle;
use App\Model\DeleteTable;
use App\Model\Category;
use App\Model\Department;
use App\Model\FTPSetting;
use Illuminate\Http\Request;
use Session;

class FTPSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
		if (isset($request->get['searchbox'])) {
			$searchbox =  $request->get['searchbox'];
		}else{
			$searchbox = '';
		}

		$url = '';

		$data['ftp_settings'] = array();

		$filter_data = array(
			'searchbox'  => $searchbox,
		);
        
        $ftpsetting = new FTPSetting();
		
		$aisle_total = $ftpsetting->getTotalFtpSettings($filter_data);

		$results = $ftpsetting->getAllFtpSettings($filter_data);
        // 		echo "<pre>";
        // 		print_r($results);die;
        
        // 		foreach ($results as $result) {
        // 			$data['ftp_settings'][] = array(
        // 				'Id'     	 => $result['ftp_id'],
        // 				'manufacturer'  => $result['manufacturer'],
        // 				'view'   	 => url('administration/ftp/info','&Id=' .$result['Id']),
        // 				'edit'   => url('administration/ftp/edit', '&Id=' . $result['Id']),
        // 				'delete' => url('administration/ftp/delete','&Id=' . $result['Id'])
        // 			);
        // 		}
		
		if(count($results)==0){ 
			$data['ftp_settings'] =array();
			$aisle_total = 0;
			$data['aisle_row'] =1;
		}
		
		$data['sid'] = $request->session()->get('sid');

		$url = '';
		$department = new Department();
		$category = new Category();
		$departments = $department->getDepartments();
		$data['departments'] = json_decode(json_encode($departments), true); 	
		$categories = $category->getCategories();
		$data['categories'] = json_decode(json_encode($categories), true); 	

		$data['ftp_settings'] = $results;
// 		echo "<pre>";
// 		print_r($data);die;
		
        return view('ftpsettings.ftpsetting')->with($data);
        
    }
    
    public function edit_list() {

   		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->language('administration/ftp_settings');
    
		$this->load->model('api/ftp_settings');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateEditList()) {

			$this->model_api_ftp_settings->editlistAisle($this->request->post['ftp']);
			
			$url = '';

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('administration/ftp_settings', 'token=' . $this->session->data['token'] . $url, true));
		  }
  	}

    /**
     * Display the specified resource.
     *
     * @param  \App\FTPSetting  $FTPSetting
     * @return \Illuminate\Http\Response
     */
    public function get_ftp_settings(Request $request){
           if ($request->isMethod('get')) {
			$ftp_id = $request->get('ftp_id');
			$ftpsetting = new FTPSetting();
			$data_result = $ftpsetting->getFtpSettings($ftp_id);
			$data =$data_result[0];
        // 			echo "<pre>";
        //             print_r($data);
        //             die;
            $data['dept_code'] = json_decode($data['dept_code']);
            $data['cat_code'] = json_decode($data['cat_code']);
            $data['purpose'] = $data['purpose'];
            
		    echo json_encode($data);
			exit;
		}else{
			$data['error'] = 'Something went wrong';
			$this->response->addHeader('Content-Type: application/json');
		    echo json_encode($data);
			exit;
		}
	    exit;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $data = array();
		$sid = $request->session()->get('sid');
		if ($request->isMethod('post')) {
		    $ftpsetting = new FTPSetting();
			$data = $ftpsetting->addFtp($request->post('ftp'),$sid);
			if(isset($data['duplicate'])){
			    \Session::flash('error', 'Duplicate Entry Already Data Exist' );
			   return redirect()->route('ftpsetting')->with('error', 'Duplicate Entry Already Data Exist');
			 //   return redirect('ftpsetting')->with('error','Duplicate Entry Already Data Exist');        
			}elseif(isset($data['inserted'])){
			    \Session::flash('message', 'Added FTP Settings' );
			    return redirect()->route('ftpsetting')->with('message', 'Added FTP Settings');
			 //   return redirect('ftpsetting')->with('message','Added FTP Settings');    
			}else{
			    \Session::flash('message', $data['error'] );
			    return redirect()->route('ftpsetting')->with('message', $data['error']);
			}
		}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FTPSetting  $FTPSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request){
        // echo "<pre>";
        // print_r($request->post());
        // die;
		if ($request->isMethod('post')) {
		    $ftpsetting = new FTPSetting();
			$data = $ftpsetting->editFtp($request->post()); 
			if(isset($data['duplicate'])){
			    return redirect('ftpsetting')->with('error',$data['duplicate']);        
			}elseif(isset($data['updated'])){
			    return redirect('ftpsetting')->with('message',$data['updated']);    
			}else{
			    return redirect('ftpsetting')->with('message',$data['error']);
			}
		}
    }
    
    public function getcategories(Request $request){
        $input = $request->all();
		$data = array();
        $category = new Category();          
        $data = $category->getCategoriesInDepartment1($input);			
            $response = [];
            $obj= new \stdClass();
            $obj->id = "All";
            $obj->text = "All";
		    array_push($response, $obj);                    
			foreach($data as $k => $v){
			    $obj= new \stdClass();
			    $obj->id = $v['vcategorycode'];
			    $obj->text = $v['vcategoryname'];
			    array_push($response, $obj);
			}
	       echo json_encode($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FTPSetting  $FTPSetting
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request){
        $input = $request->all();
		$return = array();
		if(isset($input['term']) && !empty($input['term'])){
            $search = $input['term'];

        //             $datas = mst_aisle::select('*')   
        //                 ->where('aislename', 'like', '%' . $search . '%')                
        //                 ->get();
        // 			foreach ($datas as $key => $value) {
        // 				$temp = array();
        // 				$temp['Id'] = $value['Id'];
        // 				$temp['aislename'] = $value['aislename'];
        // 				$return[] = $temp;
        //             }
        //             echo "<pre>";
        //             print_r($return);
        //             die;
        }
        return response()
            ->json_encode($return);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FTPSetting  $FTPSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
		$json =array();
		$sid = $request->session()->get('sid');
		if ($request->isMethod('get')) {
            $ftpsetting = new FTPSetting();
			$temp_arr = $request->all();
        // 			dd($temp_arr);
			$data = $ftpsetting->deleteFtp($temp_arr,$sid);
		    echo json_encode($data);
			exit;
		}
    }
}
