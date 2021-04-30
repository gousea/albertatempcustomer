<?php

namespace App\Http\Controllers\Item;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Model\Item;
use App\Model\QuickItem;
use App\Model\ParentChild;

class ParentChildController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {   
        $input = $request->all();
        $dataUrl['refresh_url'] = url('/item/parent_child_list');
		$dataUrl['add'] = url('/item/parent_child/add');
		
		$dataUrl['delete'] = url('/item/parent_child/delete');
	    $dataUrl['save'] = url('/item/parent_child/save');
		
		$dataUrl['searchitem'] = url('/item/parent_child/searchitem');
        $dataUrl['searchitem_child'] = url('/item/parent_child/searchitem_child');
        
        if(isset($input['parent_item_id'])){
            $data['parent_item_id'] = $input['parent_item_id'];
        }
        else{
            $data['parent_item_id'] = '';
        }

        if(isset($input['child_item_id'])){
            $data['child_item_id'] = $input['child_item_id'];
        }
        else{
            $data['child_item_id'] = '';
        }

        if(isset($input['cnpack'])){
            $data['cnpack'] = $input['cnpack'];
        }
        else{
            $data['cnpack'] = '';
        }

        if(isset($input['report_by_c'])){
            $data['report_by_c'] = $input['report_by_c'];
        }
        else{
            $data['report_by_c'] = '';
        }

        if(isset($input['report_by_p'])){
            $data['report_by_p'] = $input['report_by_p'];
        }
        else{
            $data['report_by_p'] = '';
        }

        if(isset($input['error_warning'])){
            $data['error_warning'] = $input['error_warning'];
        }
        

        
        $ParentChild = new ParentChild;
        
        $results = $ParentChild->parent_child();
        // dd($results);
        
        $pitemncitem = [];
        foreach ($results as $result) {
			
			$pitemncitem[] = array(
				'pname'     => $result->pname,
				'cname'     => $result->cname,
				'pid'       =>$result->pid,
				'cid'       =>$result->cid,
				'cpack'     =>$result->cpack,
				'csku'      =>$result->csku,
				'psku'      =>$result->psku,
				'item_link' => url('item/edit/'.$result->pid),
				'citem_link'=> url('item/edit/'.$result->cid),
			);
        }
        
        
		
        return view('items.parent_child', compact('pitemncitem', 'dataUrl', 'data'));
	}
	
    public function searchitem(Request $request)
    {
        $return = array();
        if ($request->isMethod('get')) {
            $input = $request->all();
            
            $datas = DB::connection('mysql_dynamic')->select("SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname FROM mst_item as mi WHERE mi.estatus='Active' AND (mi.vitemname LIKE  '%" .$input['term']. "%' OR mi.vbarcode LIKE  '%" .$input['term']. "%')");
            
            foreach ($datas as $key => $value) {
              $temp = array();
              $temp['iitemid'] = $value->iitemid;
              $temp['vbarcode'] = $value->vbarcode;
              $temp['vitemname'] = $value->vitemname;
              $return[] = $temp;
            }

            return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
          }
    }

    public function add(Request $request)
    {
        $input = $request->all();
        
        if ($request->isMethod('post')) {
            
            $data['parent_item_id'] = $input['Psearch_iitemid'];
            $data['child_item_id'] = $input['Csearch_iitemid'];
            $data['cnpack'] = $input['cpack'];
            $data['report_by_c'] = $input['report_by_c'];
            $data['report_by_p'] = $input['report_by_p'];
            
            $sql="SELECT * FROM mst_item WHERE (parentid='". (int)$data['parent_item_id'] ."' AND iitemid='". (int)$data['child_item_id'] ."')
            OR (parentid='". (int)$data['child_item_id'] ."' AND iitemid='". (int)$data['parent_item_id'] ."')";
            
            $sql2="Select parentid from mst_item where iitemid='". (int)$data['child_item_id'] ."'";
            
            $condition2=DB::connection('mysql_dynamic')->select($sql2); 
            $condition2 = isset($condition2[0])?(array)$condition2[0]:[];

            $count = DB::connection('mysql_dynamic')->select($sql);
            $count = isset($count[0])?(array)$count[0]:[];
        
            if($data['parent_item_id']==''){
                $data['error_warning'] ="Please Select Parent Item";    
                $data['child_item_id']="";
                $data['cnpack']="";
                $data['report_by_c']="";
                $data['report_by_p']="";
                $data['parent_item_id']="";
                
            }
            else if($data['child_item_id']==''){
                $data['error_warning'] ="Please Select Child Items";   
                $data['child_item_id']="";
                $data['cnpack']="";
                $data['report_by_c']="";
                $data['report_by_p']="";
                $data['parent_item_id']="";
                
            }
            else if($data['parent_item_id']== $data['child_item_id']){
                $data['error_warning'] ="Same item selected for parent and child.";  
                $data['parent_item_id']="";
                $data['child_item_id']="";
            }
            else if(!empty($count)){
                $data['error_warning'] ="That Parent and child relationship already exists"; 
                // $data['parent_item_id']="";
                // $data['child_item_id']="";
            }
            
            else if($condition2['parentid'] != 0 || !empty(trim($condition2['parentid']))  ){
                
                $data['error_warning'] ="That Parent and child relationship already exists";   
                // $data['parent_item_id']="";
                // $data['child_item_id']="";
            }
            else{
                if(isset($input['stores_hq'])){
                    $data['stores_hq'] = $input['stores_hq']; 
                }
                // $data['stores_hq'] = isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid');
                $ParentChild = new ParentChild;
                $datas = $ParentChild->addParentItem($data);
                // dd($datas);
                $request->session()->flash('message', "Added parent child relationship ");
                
                $data['cnpack']="";
                $data['report_by_c']="";
                $data['report_by_p']="";
            
            }
            
                         
            return redirect()->route('parent_child_list', $data);
        }
    }
    
    public function save(Request $request) 
    {        
        $json = array();
		
        if ($request->isMethod('post')) 
        {		    
			$temp_arr = json_decode(file_get_contents('php://input'), true);
			$data=[];
			if(count($temp_arr) === 0) {
			    $return['error'] = 'Please select at least one check box to save your changes';
                echo json_encode($return);
            	exit;
			}
			$cpack = $c_ids = $p_ids=[];
            // foreach($temp_arr['data'] as $value) {
            foreach($temp_arr['data'] as $value) {
               $temp = explode("-",$value);
               //print_r($temp);die;
               array_push($cpack,$temp[0]);
               array_push($c_ids,$temp[1]);
               array_push($p_ids,$temp[2]);
               
            }
		    
		    $stores = isset($temp_arr['stores_hq']) ? $temp_arr['stores_hq'] : [session()->get('sid')] ;
		    
			//print_r($p_ids);die;
	        foreach($c_ids as $key => $value) {

                $data['cid']=$c_ids[$key];
    	        $data['npack']=$cpack[$key] ;
    	        $data['pid']=$p_ids[$key] ;
                
                $parentId = DB::connection("mysql_dynamic")->select("select vbarcode from mst_item where iitemid = '".$data['pid']."' ");
                $childId  = DB::connection("mysql_dynamic")->select("select vbarcode from mst_item where iitemid = '".$data['cid']."' ");
                
                
                foreach($stores as $store){
                    $parentitem = DB::connection("mysql")->select("select iitemid from u".$store.".mst_item where vbarcode = '".$parentId[0]->vbarcode."' ");
                    $childitem = DB::connection("mysql")->select("select iitemid from u".$store.".mst_item where vbarcode = '".$childId[0]->vbarcode."' ");
                    
                    foreach($parentitem as $item){
                        $parentid = $item->iitemid;
                    }
                    
                    foreach($childitem as $item){
                        $childid = $item->iitemid;
                    }
                    
                    $parentupdate = DB::connection('mysql')->statement("Update u".$store.".mst_item set npack = '".(int)$data['npack']."' where iitemid = '".$parentid."'  ");
                    
                    $childupdate = DB::connection('mysql')->statement("Update u".$store.".mst_item set  npack = '".(int)$data['npack']."', nsellunit = '".(int)$data['npack']."' where iitemid = '".$childid."'  ");
                    
                }
                
    	        //print_r($data);die;
                // $items = ParentChild::find($data['cid']);
                // $items->npack = (int)$data['npack'];
                // $items->save();

                // $items = ParentChild::find($data['pid']);
                // $items->npack = (int)$data['npack'];
                // $items->nsellunit = (int)$data['npack'];
                // $items->save();
                
            }
            $data['success'] = 'Successfully Updated';
            echo json_encode($data);
    		exit;
		}
		
    }

    public function delete(Request $request) 
    {
	    $json =array();
		
		if ($request->isMethod('post')) 
        {   
			$temp_arr = json_decode(file_get_contents('php://input'), true);
			$data=[];
            
			$p_ids = $c_ids = [];
			
			$stores = isset($temp_arr['stores_hq']) ? $temp_arr['stores_hq'] : [session()->get('sid')] ;
			
			foreach($temp_arr['data'] as $value) {
			       $temp = explode("-",$value);
			       array_push($p_ids,$temp[0]);
			       array_push($c_ids,$temp[1]);
			 }
	        foreach($p_ids as $key => $value) {
                $data['selected_parent_item_id']=$p_ids[$key];
                $data['iitemid']=$c_ids[$key] ;
                $data['stores'] = $stores;
                
                $ParentChild = new ParentChild;
                $data = $ParentChild->removeParentItem($data);
           
            }
            echo json_encode($data);
    		exit;
		}
		
	}
	
	public function checkduplicateparentchild(Request $request)
    {
        $input = $request->all();
        
        $stores = session()->get('stores_hq');
        
        foreach($stores as $store){
            $parentId = DB::connection("mysql")->select("select * from u".$store->id.".mst_item where vbarcode = '".$input['parentItembarcode']."' ");
            $childId  = DB::connection("mysql")->select("select * from u".$store->id.".mst_item where vbarcode = '".$input['childitembarcode']."' ");
            
            $duplicate = [];
            if(count($parentId) == 0 || count($childId) == 0) {
                array_push($duplicate, $store->id );
            }
        }
        
        return $duplicate;
    } 
    
    public function parentChildDuplication(Request $request){
       
        $json =array();
		
        if ($request->isMethod('post')) 
        {		    
			$temp_arr = json_decode(file_get_contents('php://input'), true);
			$data=[];
			
			
			if(count($temp_arr) === 0) {
			    $return['error'] = 'Please select at least one check box to save your changes';
                echo json_encode($return);
            	exit;
			}
			
			$cpack = $c_ids = $p_ids=[];
			
			foreach($temp_arr as $value) {
    	       $temp = explode("-",$value);
    	       //print_r($temp);die;
    	       array_push($cpack,$temp[0]);
    	       array_push($c_ids,$temp[1]);
    	       array_push($p_ids,$temp[2]);
		       
		    }
			      //print_r($p_ids);die;
	        foreach($c_ids as $key => $value) {

                $data['cid']   =$c_ids[$key];
    	        $data['npack'] =$cpack[$key] ;
    	        $data['pid']   =$p_ids[$key] ;
    	        //print_r($data);die;
    	        
    	        $stores = session()->get('stores_hq');
        
                $parentId = DB::connection("mysql_dynamic")->select("select vbarcode from mst_item where iitemid = '".$data['pid']."' ");
                $childId  = DB::connection("mysql_dynamic")->select("select vbarcode from mst_item where iitemid = '".$data['cid']."' ");
            
                foreach($stores as $store){
                    
                    $parentbarcode = DB::connection("mysql")->select("select * from u".$store->id.".mst_item where vbarcode = '".$parentId[0]->vbarcode."' and isparentchild = '2' ");
                    $childbarcode = DB::connection("mysql")->select("select * from u".$store->id.".mst_item where vbarcode = '".$childId[0]->vbarcode."' and isparentchild = '1' ");
                    $duplicate = [];
                    if(count($parentbarcode) == 0 || count($childbarcode) == 0) {
                        array_push($duplicate, $store->id );
                    }
                }
                
            }
		}
		
		return $duplicate;
    }
    
    
    public function parentChildDuplicationfordelete(Request $request){
       
        $json =array();
		
        if ($request->isMethod('post')) 
        {		    
			$temp_arr = json_decode(file_get_contents('php://input'), true);
			$data=[];
			$duplicate = [];
			$cpack = $c_ids = $p_ids=[];
			foreach($temp_arr as $value) {
    	       $temp = explode("-",$value);
    	       array_push($p_ids,$temp[0]);
		    }
			     // print_r($p_ids);die;
	        foreach($p_ids as $key => $value) {
    	        $data['pid']   =$p_ids[$key] ;
    	        
    	        $stores = session()->get('stores_hq');
        
                $parentId = DB::connection("mysql_dynamic")->select("select vbarcode from mst_item where iitemid = '".$data['pid']."' ");
            
                foreach($stores as $store){
                    
                    $parentbarcode = DB::connection("mysql")->select("select * from u".$store->id.".mst_item where vbarcode = '".$parentId[0]->vbarcode."' and isparentchild = '2' ");
                    
                    if(count($parentbarcode) == 0 ) {
                        array_push($duplicate, $store->id );
                    }
                }
                
            }
		}
		return $duplicate;
    }
    
    
    
}
?>