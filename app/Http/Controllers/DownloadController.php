<?php

namespace App\Http\Controllers;

use App\Model\mst_aisle;
use App\Model\mst_item;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $sid = $request->session()->get('sid');
        $data['aisles'] = array();
        if (isset($input['searchbox'])) {
            $searchbox =  $input['searchbox'];
            $results = mst_aisle::where('Id', $searchbox)
                        ->orderBy('Id', 'DESC')
                        ->paginate(10);
		}else{
            $searchbox = '';
            $results = mst_aisle::orderBy('Id', 'DESC')
                        ->paginate(10);
        }
        
        if(count($results)==0){ 
			$data['aisles'] =array();
			$aisle_total = 0;
			$data['aisle_row'] =1;
        }
        return view('downloads.download',compact('data'));
    }

    public function search(Request $request){
        $input = $request->all();
		$return = array();
		if(isset($input['term']) && !empty($input['term'])){

            $datas = mst_aisle::select('*')->where('aislename', 'like', '%' . $input['term'] . '%')
                ->all();

			foreach ($datas as $key => $value) {
				$temp = array();
				$temp['Id'] = $value['Id'];
				$temp['aislename'] = $value['aislename'];
				$return[] = $temp;
			}
        }
        return response()->json($return);
    }
    
    public function delete() {
        $data_result = array();	
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data) && count($data) > 0){
                foreach ($data as $key => $value) {
                    $test = mst_aisle::where('Id', $value)->first();
                    $result = mst_item::where('aisleid', $test[0]['Id']);
                    if(count($result) > 0){
                        $check_db= mst_aisle::select('aislename')->where('Id', $value)->first();
                        $data_result['error'] = $check_db['aislename'].'  is already assigned to item in system. Please unselect it!';
                        return $data_result;
                    }else{
                        $mst_deleteunit = mst_aisle::select('*')->where('Id', $value)->first();
                        if(count($mst_deleteunit) > 0){
                            $exist_deleteunit[] = $mst_deleteunit['Id'];                            
                            mst_aisle::where('Id',$value)->delete();                            
                            $data_result['message'] =' Aisle Deleted  Successfully';
                        }
                    }
                }
            }
		    echo json_encode($data_result);
			exit;
        }
	}    
}
