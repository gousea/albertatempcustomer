<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Model\SubCategory;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\App;
use App\Model\Item;

class SubCategoryController extends Controller
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
        $data['subcategories'] = array();

        if (isset($input['searchbox'])) {
            $searchbox =  $input['searchbox'];
            $results = SubCategory::where('subcat_id', $searchbox)
                        ->paginate(10);
        }else{
            $results = SubCategory::where('sid', $sid)->orderBy('subcat_name', 'ASC')
            ->paginate(10);
        }  
        $categories = Category::all();

        if(count($results)==0){ 
            $sub_category_total = 0;
            $data['sub_category_row'] = 1;
        }

        $data['sub_categories'] = $results;
        $data['categories'] = $categories;

        if (isset($input['selected'])) {
			$data['selected'] = (array)$input['selected'];
		} else {
			$data['selected'] = array();
        }
        return view('subcategorys.subcategory_list',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_list(Request $request)
    {
        $input = $request->all();
        //dd($input);
        $sid = $request->session()->get('sid');
        $data = array();
        $data = [
            'cat_id' => $input['subcategory'][0]['icategoryid'],
            'subcat_name' => $input['subcategory'][0]['subcat_name'],            
            'created_at' => date('Y-m-d'),
            'LastUpdate' => date('Y-m-d'),
            'status'     => "Active",
            'SID' => $sid,
        ];
        $subcategory_datas = SubCategory::select('*')   
                            ->where('subcat_name',$data['subcat_name'])                
                            ->get();
                            
        if(isset($subcategory_datas[0]['subcat_name'])){
            return redirect(route('subcategory'))->with('error','SubCategory Already Exist!!');
        }else{
            $sql = array();
            if(isset($input['stores_hq'])){
                if($input['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $input['stores_hq']);
                }
                
                $cat_name = DB::connection("mysql_dynamic")->select("Select * from mst_category where icategoryid = '".$input['subcategory'][0]['icategoryid']."' ");
                if(isset($cat_name[0])){
                    $catname        = $cat_name[0]->vcategoryname;
                    $cat_desc       = $cat_name[0]->vdescription;
                    $cat_type       = $cat_name[0]->vcategorttype;
                    $cat_isequence  = $cat_name[0]->isequence;
                    $cat_status     = $cat_name[0]->estatus;
                    $cat_dep_code   = $cat_name[0]->dept_code;
                }
                
                // foreach($cat_name as $category_name){
                //     $catname = $category_name->vcategoryname;
                //     $cat_desc = $category_name->vdescription;
                //     $cat_type = $category_name->vcategorttype;
                //     $cat_isequence = $category_name->isequence;
                //     $cat_status = $category_name->estatus;
                //     $cat_dep_code = $category_name->dept_code;
                // }
                
                foreach($stores as $store){
                    
                    $cat_id = DB::connection("mysql")->select("Select icategoryid from u".$store.".mst_category where  vcategoryname= '".$catname."' ");
                                 
                    //===========creating Category if not avilable==========
                    if(count($cat_id) > 0){
                        foreach($cat_id as $catid){
                            $category_id = $catid->icategoryid; 
                        }
                        $insert_query = "Insert INTO u".$store.".mst_subcategory (cat_id, subcat_name, status, created_at, LastUpdate, SID ) values('".$category_id."', '".$data['subcat_name']."', '".$data['status']."', '".$data['created_at']."', '".$data['LastUpdate']."', '".$store."') ";
                        
                        $inserting = DB::connection("mysql")->statement($insert_query); 
                    }else{
                        $depname = DB::connection("mysql_dynamic")->select("select * from mst_department where vdepcode = '".$cat_dep_code."' ");
                        // foreach($depname as $dep){
                        //     $department_name = $dep->vdepartmentname;
                        //     $dep_desc = $dep->vdescription;
                        //     $dep_s_time = $dep->starttime;
                        //     $dep_e_time = $dep->endtime;
                        //     $dep_isequence = $dep->isequence;
                        //     $dep_estatus = $dep->estatus; 
                        // }
                        if(isset($depname[0])){
                            $department_name    = $depname[0]->vdepartmentname;
                            $dep_desc           = $depname[0]->vdescription;
                            $dep_s_time         = $depname[0]->starttime;
                            $dep_e_time         = $depname[0]->endtime;
                            $dep_isequence      = $depname[0]->isequence;
                            $dep_estatus        = $depname[0]->estatus; 
                        }
                        
                        $department_code = DB::connection("mysql")->select("select vdepcode from u".$store.".mst_department where vdepartmentname = '".$department_name."' ");
                        
                        //====creating department if not available========
                        if(count($department_code) > 0){
                            if(isset($department_code[0])){
                                $depcode = $department_code[0]->vdepcode;
                            }
                            // foreach($department_code as $d_code){
                            //     $depcode = $d_code->vdepcode;
                            // }
                        }else {
                            $sql = "INSERT INTO u".$store.".mst_department(vdepartmentname, vdescription, starttime, endtime, isequence, estatus, SID) 
                                values('".$department_name."', '".$dep_desc."', '".$dep_s_time."', '".$dep_e_time."', '".$dep_isequence."', '".$dep_estatus."', '".$store."') ";
                            DB::connection('mysql')->statement($sql);
                            $query_data = "select idepartmentid from u".$store.".mst_department where vdepartmentname = '" .$department_name."' ";
                            $return_query_data = DB::connection('mysql')->select($query_data);
                            $return_data = $return_query_data[0];
                            $last_id = $return_data->idepartmentid;
                            
                            $sql2 = "UPDATE u".$store.".mst_department SET vdepcode = '".(int)$last_id."' WHERE idepartmentid = '" . (int)$last_id . "'";
                            DB::connection('mysql')->update($sql2);
                            $depcode = $last_id;
                        }
                        
                        $inserting = DB::connection("mysql")->statement("Insert INTO u".$store.".mst_category (vcategoryname, vdescription, vcategorttype, isequence, estatus, dept_code, SID) 
                        VAlues('".$catname."', '".$cat_desc."', '".$cat_type."',  '".$cat_isequence."', '".$cat_status."', '".$depcode."', '".$store."') ");
                        $lastinsertcat_id = DB::connection("mysql")->select("SELECT * FROM u".$store.".mst_category order by icategoryid DESC limit 1"); 
                        // foreach($lastinsertcat_id as $last_id){
                        //     $vcategorycode = $last_id->icategoryid;
                        // }
                        if(isset($lastinsertcat_id[0])){
                            $vcategorycode = $lastinsertcat_id[0]->icategoryid;
                        }
                        $updating = DB::connection("mysql")->statement("UPDATE u".$store.".mst_category SET vcategorycode = '".$vcategorycode."'  WHERE icategoryid = '".$vcategorycode."' ");
                        
                        
                        $created_at = date('Y-m-d');
                        $LastUpdate = date('Y-m-d');
                        $status     = "Active";
                        
                        $insert_query = "INSERT INTO u".$store.".mst_subcategory (cat_id,subcat_name,created_at,LastUpdate,SID) values ('".$vcategorycode."', '".$subcatname."', '".$created_at."', '".$LastUpdate."', '".$store."' )";
                        DB::connection('mysql')->statement($insert_query);
                        
                        $subcat_id = DB::connection("mysql")->select("select subcat_id from u".$store.".mst_subcategory order by subcat_id DESC limit 1 ");
                        // foreach($subcat_id as $s_cat_id) {
                        //     return $s_cat_id->subcat_id;
                        // }
                        if(isset($subcat_id[0])){
                            return $subcat_id[0]->subcat_id;
                        }
                    }
                                        
                    // $insert_query = "Insert INTO u".$store.".mst_subcategory (cat_id, subcat_name, status, created_at, LastUpdate, SID ) values('".$data['cat_id']."', '".$data['subcat_name']."', '".$data['status']."', '".$data['created_at']."', '".$data['LastUpdate']."', '".$store."') ";
                    // // array_push($sql,$insert_query);
                    // $inserting = DB::connection("mysql")->select($insert_query); 
                }
                
                 return redirect(route('subcategory'))->with('message','SubCategory Added Successfully!!');
            }else{
                DB::connection('mysql_dynamic')->insert('insert into mst_subcategory (cat_id,subcat_name,created_at,LastUpdate,SID) values(?,?,?,?,?)',[
                    $data['cat_id'], $data['subcat_name'], $data['created_at'], $data['LastUpdate'], $data['SID']
                ]);
                return redirect(route('subcategory'))->with('message','SubCategory Added Successfully!!');
            }
        }
    }

    public function search(Request $request){
        $input = $request->all();
        
		$return = array();
		if(isset($input['term']) && !empty($input['term'])){
            $search = $input['term'];

            $datas = SubCategory::select('*')   
                ->where('subcat_name', 'like', '%' . $search . '%')                
                ->get();
			foreach ($datas as $key => $value) {
				$temp = array();
				$temp['subcat_id'] = $value['subcat_id'];
				$temp['subcat_name'] = $value['subcat_name'];
				$return[] = $temp;
			}
        }
        return response()
            ->json($return);
	}

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $Category
     * @return \Illuminate\Http\Response
     */
    // public function show(Request $request)
    // {   
    //     $input = $request->all();      
    //     // $request->validate(
    //     //     [
    //     //         'subcat_name'=> 'required',    
    //     //         'cat_id'=>'required',
    //     //     ],
    //     // ); 
    //     if(isset($input['selected'])){
    //     if(isset($input['save']) == 'save'){
    //         foreach($input['selected'] as $keys => $value){
    //             $subcategoryid = $value;
    //             foreach($input['subcategory'] as $key => $val){
    //                 if($subcategoryid == $val['subcat_id']){
    //                     SubCategory::where('subcat_id',$subcategoryid)->update([
    //                                 'subcat_name'=>$val['subcat_name'],
    //                                 'subcat_id'=>$val['subcat_id'],
    //                                 'cat_id'=>$val['cat_id']
    //                             ]);
    //                 }
    //             }
    //         }
    //         return redirect(route('subcategory'))->with('message','SubCategory Saved Successfully!!');
    //     }elseif($input['delete'] == 'delete'){
    //         if(isset($input['delete']) && count($input['subcategory']) > 0){
    //             foreach($input['selected'] as $keys => $value){
    //                 $subcategoryid = $value;
    //                 foreach ($input['subcategory'] as $key => $value) {
    //                     if($value == 1){
    //                         return redirect(route('subcategory'))->with('error','Category already assigned to item in system please unselect it! ');   
    //                     }
    //                     SubCategory::where('subcat_id',$subcategoryid)->delete();
    //                 }  
    //             }  
    //         return redirect(route('subcategory'))->with('message','SubCategory Removed Successfully!!');
    //         }
    //     }
         
    //     }
    //     return redirect(route('subcategory'))->with('error','Please Select SubCategory!!');
    // }

    public function edit_list(Request $request)
    {
        $input = $request->all(); 
        //dd($input);
        if(isset($input['subcategory'])){
            foreach($input['selected'] as $keys => $value){ 
                $subcategoryid = $value;
                foreach($input['subcategory'] as $key =>$value){
                    if($subcategoryid == $value['subcat_id']){
                        
                        $data = SubCategory::where('subcat_name', '=', $value['subcat_name'])->get(); 
                        
                        
                        // if(count($data) > 0)
                        //  {
                        //     return redirect(route('subcategory'))->with('error','Already SubCategory Name Exist!!');
                        //  }
                        if (in_array($value['subcat_id'], $input['selected'])){
                            
                            $subcat_name = DB::connection("mysql_dynamic")->select("Select subcat_name from mst_subcategory where subcat_id = '".$value['subcat_id']."' ");
                            // foreach($subcat_name as $subcategoy){
                            //     $subcatname = $subcategoy->subcat_name;
                            // }
                            if(isset($subcat_name[0])){
                                $subcatname = $subcat_name[0]->subcat_name;
                            }
                            
                            $cat_name = DB::connection("mysql_dynamic")->select("Select * from mst_category where icategoryid = '".$value['cat_id']."' ");
                            // foreach($cat_name as $category_name){
                            //     $catname = $category_name->vcategoryname;
                            //     $cat_desc = $category_name->vdescription;
                            //     $cat_type = $category_name->vcategorttype;
                            //     $cat_isequence = $category_name->isequence;
                            //     $cat_status = $category_name->estatus;
                            //     $cat_dep_code = $category_name->dept_code;
                            // }
                            
                            if(isset($cat_name[0])){
                                $catname        = $cat_name[0]->vcategoryname;
                                $cat_desc       = $cat_name[0]->vdescription;
                                $cat_type       = $cat_name[0]->vcategorttype;
                                $cat_isequence  = $cat_name[0]->isequence;
                                $cat_status     = $cat_name[0]->estatus;
                                $cat_dep_code   = $cat_name[0]->dept_code;
                            }
                            $sql= array();
                             
                            if(isset($input['stores_hq'])){
                                if($input['stores_hq'] === session()->get('sid')){
                                    $stores = [session()->get('sid')];
                                }else{
                                    $stores = explode(",", $input['stores_hq']);
                                }
                                foreach($stores as $store){
                                    $invidual_subcatid= DB::connection("mysql")->select("SELECT subcat_id  FROM u".$store.".mst_subcategory WHERE subcat_name = '".$subcatname."' ");
                                    if(count($invidual_subcatid) > 0){
                                        
                                        $cat = DB::connection("mysql")->select("SELECT * FROM u".$store.".mst_category WHERE vcategoryname = '".$catname."' ");
                                        if(count($cat) > 0){
                                            $update_query = "Update u".$store.".mst_subcategory set  subcat_name ='".$value['subcat_name']."', cat_id='".$cat[0]->icategoryid."' where subcat_id = '".$invidual_subcatid[0]->subcat_id."' ";
                                        }
                                        
                                        DB::connection("mysql")->statement($update_query);  
                                    }else{ 
                                        $cat_id = DB::connection("mysql")->select("Select icategoryid from u".$store.".mst_category where  vcategoryname= '".$catname."' ");
                                        
                                        //===========creating Category if not avilable==========
                                        if(count($cat_id) > 0){
                                            // foreach($cat_id as $catid){
                                            //     $category_id = $catid->icategoryid;
                                            // }
                                            if(isset($cat_id[0])){
                                                $category_id = $cat_id[0]->icategoryid;
                                            }
                                            $insert_query = "Insert INTO u".$store.".mst_subcategory (cat_id, subcat_name, status, created_at, LastUpdate, SID ) values('".$category_id."', '".$value['subcat_name']."', 'Active', '".date('Y-m-d')."', '".date('Y-m-d')."', '".$store."') ";
                                            $inserting = DB::connection("mysql")->statement($insert_query); 
                                        }else{
                                            $depname = DB::connection("mysql_dynamic")->select("select * from mst_department where vdepcode = '".$cat_dep_code."' ");
                                            if(isset($depname[0])){
                                                $department_name    = $depname[0]->vdepartmentname;
                                                $dep_desc           = $depname[0]->vdescription;
                                                $dep_s_time         = $depname[0]->starttime;
                                                $dep_e_time         = $depname[0]->endtime;
                                                $dep_isequence      = $depname[0]->isequence;
                                                $dep_estatus        = $depname[0]->estatus; 
                                            }
                                            // foreach($depname as $dep){
                                            //     $department_name = $dep->vdepartmentname;
                                            //     $dep_desc = $dep->vdescription;
                                            //     $dep_s_time = $dep->starttime;
                                            //     $dep_e_time = $dep->endtime;
                                            //     $dep_isequence = $dep->isequence;
                                            //     $dep_estatus = $dep->estatus; 
                                            // }
                                            
                                            $department_code = DB::connection("mysql")->select("select vdepcode from u".$store.".mst_department where vdepartmentname = '".$department_name."' ");
                                            
                                            //====creating department if not available========
                                            if(count($department_code) > 0){
                                                if(isset($department_code[0])){
                                                    $depcode = $department_code[0]->vdepcode;
                                                }
                                                // foreach($department_code as $d_code){
                                                //     $depcode = $d_code->vdepcode;
                                                // }
                                            }else {
                                                $sql = "INSERT INTO u".$store.".mst_department(vdepartmentname, vdescription, starttime, endtime, isequence, estatus, SID) 
                                                    values('".$department_name."', '".$dep_desc."', '".$dep_s_time."', '".$dep_e_time."', '".$dep_isequence."', '".$dep_estatus."', '".$store."') ";
                                                DB::connection('mysql')->statement($sql);
                                                $query_data = "select idepartmentid from u".$store.".mst_department where vdepartmentname = '" .$department_name."' ";
                                                $return_query_data = DB::connection('mysql')->select($query_data);
                                                $return_data = $return_query_data[0];
                                                $last_id = $return_data->idepartmentid;
                                                
                                                $sql2 = "UPDATE u".$store.".mst_department SET vdepcode = '".(int)$last_id."' WHERE idepartmentid = '" . (int)$last_id . "'";
                                                DB::connection('mysql')->update($sql2);
                                                $depcode = $last_id;
                                            }
                                            
                                            $inserting = DB::connection("mysql")->statement("Insert INTO u".$store.".mst_category (vcategoryname, vdescription, vcategorttype, isequence, estatus, dept_code, SID) 
                                            VAlues('".$catname."', '".$cat_desc."', '".$cat_type."',  '".$cat_isequence."', '".$cat_status."', '".$depcode."', '".$store."') ");
                                            $lastinsertcat_id = DB::connection("mysql")->select("SELECT * FROM u".$store.".mst_category order by icategoryid DESC limit 1"); 
                                            // foreach($lastinsertcat_id as $last_id){
                                            //     $vcategorycode = $last_id->icategoryid;
                                            // }
                                            
                                            if(isset($lastinsertcat_id[0])){
                                                $vcategorycode = $lastinsertcat_id[0]->icategoryid;
                                            }
                                            $updating = DB::connection("mysql")->statement("UPDATE u".$store.".mst_category SET vcategorycode = '".$vcategorycode."'  WHERE icategoryid = '".$vcategorycode."' ");
                                            
                                            
                                            $created_at = date('Y-m-d');
                                            $LastUpdate = date('Y-m-d');
                                            $status     = "Active";
                                            
                                            $insert_query = "INSERT INTO u".$store.".mst_subcategory (cat_id,subcat_name,created_at,LastUpdate,SID) values ('".$vcategorycode."', '".$subcatname."', '".$created_at."', '".$LastUpdate."', '".$store."' )";
                                            DB::connection('mysql')->statement($insert_query);
                                            
                                            $subcat_id = DB::connection("mysql")->select("select subcat_id from u".$store.".mst_subcategory order by subcat_id DESC limit 1 ");
                                            foreach($subcat_id as $s_cat_id) {
                                                return $s_cat_id->subcat_id;
                                            }
                                        }
                                    }
                                }
                            }else{
                                SubCategory::where('subcat_id',$value['subcat_id'])->update([
                                'cat_id'=>$value['cat_id'],
                                'subcat_name'=>$value['subcat_name'],
                                'status'=>"Active",
                                'LastUpdate'=>date('Y-m-d'),
                                ]);
                            }
                            
                        }
                    }
                }
            }
            return redirect(route('subcategory'))->with('message','SubCategory Updated Successfully!!');
        }
    }
    
    public function delete(Request $request){
        
        $temp_arr = $request->all();
        // dd($temp_arr);
        $data = [];
        $checkError = "No";
        $sid = $request->session()->get('sid');
        
        foreach($temp_arr['data'] as $subcateg){
            $subcategoryid = $subcateg['subcat_id'];
            $subcategoryname = $subcateg['subcat_name'];
            if(isset($temp_arr['stores_hq'])){
                $subcategory = DB::connection('mysql_dynamic')->select("Select * from mst_subcategory where subcat_id = '".$subcateg['subcat_id']."' "); 
                
                
                if(isset($subcategory[0])){
                    $sub_cat_name = $subcategory[0]->subcat_name;
                }
                
                foreach($temp_arr['stores_hq'] as $store){
                    $subcat_id = DB::connection("mysql")->select("select * from u".$store.".mst_subcategory where subcat_name = '".$sub_cat_name."'");
                    if(isset($subcat_id[0])){
    	                $subcategory_id =  $subcat_id[0]->subcat_id;
    	            }
    	            
                    //  check if the General SubCategory is selected
                    if($subcategoryid == 1){
                        $checkError = "Yes";
        		        $data['error_msg'][] = 'General SubCategory cannot be deleted. Please unselect it.';
        		        break;
        		    }
        		    // check if the category is attached to any item
                     $item = DB::connection("mysql")->select("Select * from  u".$store.".mst_item where subcat_id = '".$subcategory_id."' ");
                    // $item = item::where ('subcat_id' , $subcategoryid)->count();
            		if(count($item) > 0){
            		    $checkError = "Yes";
            		    $data['error_msg'][] = $subcategoryname.' SubCategory is already assigned to item(s) in the store '.$store.'. Please unselect it.';
            		}
                }
            }else{
                //  check if the General SubCategory is selected
            
                if($subcategoryid == 1){
                    $checkError = "Yes";
    		        $data['error_msg'][] = 'General SubCategory cannot be deleted. Please unselect it.';
    		        break;
    		    }
    		    
    		    // check if the category is attached to any item
                
                $item = item::where ('subcat_id' , $subcategoryid)->count();
        		if($item > 0){
        		    $checkError = "Yes";
        		    $data['error_msg'][] = $subcategoryname.' SubCategory is already assigned to '.$item.' item(s). Please unselect it.';
        		}
            }
            
        }
        // check if the $data['error_msg'] is empty; if it is empty start deleting
            $counter = 0;
            if($checkError == "No"){
                foreach($temp_arr['data'] as $subcateg){
                    if(isset($temp_arr['stores_hq'])){
                        $stores= $temp_arr['stores_hq'];
                        $subcategory = DB::connection('mysql_dynamic')->select("Select * from mst_subcategory where subcat_id = '".$subcateg['subcat_id']."' "); 
                        if(isset($subcategory[0])){
                            $sub_cat_name = $subcategory[0]->subcat_name;
                        }
                        foreach($stores as $store){
                            $subcat_id = DB::connection("mysql")->select("select * from u".$store.".mst_subcategory where subcat_name = '".$sub_cat_name."'");
                            if(isset($subcat_id[0])){
            	                $subcategory_id =  $subcat_id[0]->subcat_id;
            	            }
                            DB::connection("mysql")->delete("DELETE FROM u".$store.".mst_subcategory WHERE subcat_id = '".$subcategory_id."' ");
                        }
                    }else{
                        SubCategory::find($subcateg['subcat_id'])->delete();
                    }
                    $counter++;
                 }
                 
                 $data['success_msg'] = 'Deleted '.$counter.' Sub category(s) successfully.';
            }
        return $data;
    }
   
    public function duplicatesubcategory(Request $request){
        $input = $request->all(); 
        
        $stores = session()->get('stores_hq');
        
        $notExistStore = [];
        if(isset($input['status']) && $input['status'] === 'new_add'){
            $category = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_category WHERE icategoryid = '".$input['vcategorycode']."' ");
            
            foreach($stores as $store){
                    
                    $query = DB::connection("mysql")->select("Select * from u".$store->id.".mst_category where vcategoryname = '".$category[0]->vcategoryname."' ");
                    
                    if(count($query) == 0){
                      array_push($notExistStore, $store->id); 
                    }
                }
        }else{
            $subcatvalues = $input['avArr'];
            foreach($subcatvalues as $k => $value ){
                $subcategory = DB::connection('mysql_dynamic')->select("Select sc.*, c.vcategoryname from mst_subcategory sc LEFT JOIN mst_category c on sc.cat_id = c.icategoryid where subcat_id = '".$value['subcat_id']."' "); 
                
                if(isset($subcategory[0])){
                    $sub_cat_name   = $subcategory[0]->subcat_name;
                    $catname        = $subcategory[0]->vcategoryname;
                }
                foreach($stores as $store){
                    $query = DB::connection("mysql")->select("Select * from u".$store->id.".mst_subcategory where subcat_name = '".$sub_cat_name."' "); 
                    
                    $query1 = DB::connection("mysql")->select("Select * from u".$store->id.".mst_category where vcategoryname = '".$catname."' "); 
                    
                    if(count($query) == 0 || count($query1) == 0){
                      array_push($notExistStore, $store->id); 
                    }
                }
            }
        }
        return $notExistStore;
    }
}
