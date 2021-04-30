<?php

namespace App\Http\Controllers;

use App\Http\Requests\category\CategoryEditRequest;
use App\Model\Category;
use App\Model\Department;
use App\Model\SubCategory;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Cookie\CookieJar;
// use Cookie;
// use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Console\Input\Input;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $data['category'] = array();
        
        if (isset($input['searchbox'])) {
            $searchbox =  $input['searchbox'];
            $results = DB::connection('mysql_dynamic')->select('SELECT * FROM mst_category c left join (
                SELECT cat_id, count(*) as subcat_count from mst_subcategory group by cat_id
                ) s on c.icategoryid=s.cat_id where c.estatus = "Active" AND c.icategoryid = "'.$input['searchbox'].'" ORDER BY c.vcategoryname');
                $results = $this->arrayPaginator($results, $request);
		}else{
            $results = DB::connection('mysql_dynamic')->select('SELECT * FROM mst_category c left join (
                SELECT cat_id, count(*) as subcat_count from mst_subcategory group by cat_id
                ) s on c.icategoryid=s.cat_id where c.estatus = "Active" ORDER BY c.vcategoryname');
            $results = $this->arrayPaginator($results, $request);
		}      
        
        $data['categories'] = $results;
        $department = Department::orderBy('vdepartmentname', 'ASC')->get();
        $data['department'] = $department;

        if (isset($input['selected'])) {
			$data['selected'] = (array)$input['selected'];
		} else {
			$data['selected'] = array();
        }
        
		$data['edit_list'] = 'category/edit_list';
		$data['current_url'] = 'category';
		$data['searchcategory'] = '/category/search';

        return view('categorys.category_list',compact('data'));
    }

    public function arrayPaginator($array, $request)
    {
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $sid = $request->session()->get('sid');
        // $request->validate(
        //     [
        //         'vcategoryname'=> 'required',    
        //         'vdescription'=>'required',
        //         'vcategorttype'=>'required',
        //         'isequence'=>'required',
        //         'estatus'=>'required',
        //         'dept_code'=>'required',
        //     ],
        // );
        $data = array();
        $data = [
            'vcategoryname' => $input['category'][0]['vcategoryname'],
            'vdescription' => $input['category'][0]['vdescription'],
            'vcategorttype' => $input['category'][0]['vcategorttype'],
            'isequence' => $input['category'][0]['isequence'],
            'estatus' => 'Active',
            'dept_code' => $input['category'][0]['dept_code'],
            'SID' => $sid,
        ];
        
        $category_datas = Category::select('vcategoryname', 'icategoryid')   
                ->where('vcategoryname',$data['vcategoryname'])                
                ->get();
                
        if(isset($category_datas[0]['vcategoryname'])){
            return redirect(route('category'))->with('error_message','Category Already Exist!!');
        }else{
            $categoryid = Category::create($data)->icategoryid;
            
            Category::where('icategoryid',$categoryid)->update(['vcategorycode'=>$categoryid]);
            return redirect(route('category'))->with('message','Successfully Added Category!!');
        }
    }

    public function search(Request $request){
        $input = $request->all();
        
		$return = array();
		if(isset($input['term']) && !empty($input['term'])){
            $search = $input['term'];

            $datas = Category::select('vcategoryname', 'icategoryid')   
                ->where('vcategoryname', 'like', '%' . $search . '%')                
                ->get();
			foreach ($datas as $key => $value) {
				$temp = array();
				$temp['icategoryid'] = $value['icategoryid'];
				$temp['vcategoryname'] = $value['vcategoryname'];
				$return[] = $temp;
			}
        }
        return response()
            ->json($return);
    }
    
    public function edit_list(Request $request){
        $input = $request->all(); 
        if(isset($input)){
            foreach($input['selected'] as $keys => $value){
                $categoryid = $value;
                foreach($input['category'] as $key => $val){
                    if($categoryid == $val['icategoryid']){
                        // if($val['vdescription'] == ""){
                        //     return redirect(route('category'))->with('error_message','Please Fill Description!!');
                        // }
                        if($val['dept_code'] == "0" || $val['dept_code'] == NULL){
                            return redirect(route('category'))->with('error_message','Please Select Deparment Code!!');
                        }
                        
                        $category_datas = Category::select('vcategoryname', 'icategoryid')   
                            ->where('vcategoryname',$val['vcategoryname'])                
                            ->get();
                        // print_r($category_datas);die;
                        if(isset($category_datas[0]['vcategoryname']) == $val['vcategoryname']){
                            return redirect(route('category'))->with('error_message','Category Already Exist!!');
                        }else{
                            Category::where('icategoryid',$categoryid)->update([
                                    'vcategoryname'=>$val['vcategoryname'],
                                    'vcategorycode'=>$val['vcategorycode'],
                                    'vdescription'=>$val['vdescription'],
                                    'vcategorttype'=>$val['vcategorttype'],
                                    'isequence'=>$val['isequence'],
                                    'dept_code'=>$val['dept_code']
                                ]);    
                        }
                    }
                }
            }
        }    
        return redirect(route('category'))->with('message','Successfully Saved Category!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Category  $category
     * @return \Illuminate\Http\Response
     */
    // public function delete(Request $request)
    // {
    //     $input = $request->all(); 
    //     if($input){
    //         foreach ($input as $key => $value) {
    //             if($value==1){
    //                 $data['error'] =' Catgory already assigned to item in system please unselect it! ';                    
    //                 echo json_encode($data);
    //                 exit;
    //             }else{
    //                 Category::where('vcategorycode',$value)->update([
    //                     'estatus'=>'Inactive',
    //                 ]);
    //             }
    //         }   
    //         $data['success'] = 'Category Removed Successfully!';
    //         echo json_encode($data);
    //         exit;
    //     }
    // }
    
    public function delete(Request $request){
         
         $temp_arr = $request->all();
         $data = [];

       // $data['error_msg'] = [];
       $checkError = "No";
        
        $sid = $request->session()->get('sid');
        
        foreach($temp_arr as $catgory){
           
           $categorycode = $catgory['vcategorycode'];
           
           $categoryname = $catgory['vcategoryname'];
           
            //  check if the General Category is selected
            
            if($categorycode == 1){
                $checkError = "Yes";
		        $data['error'][] = 'General Category cannot be deleted. Please unselect it.';
		        break;
		    }
           
            // check if the category is attached to any item
            
            $item = item::where ('vcategorycode' , $categorycode)->count();
    		if($item > 0){
    		    $checkError = "Yes";
    		    $data['error'][] = $categoryname.' Category is already assigned to '.$item.' item(s). Please unselect it.';
    		}
    		
    		// 	check if the category is attached to any sub-category
		    $subcategory = SubCategory::where('cat_id' ,$categorycode)->count();
            if($subcategory > 0){
                $checkError = "Yes";
    		    $data['error'][] = $categoryname.' Category is already assigned to '.$subcategory.' category(s). Please unselect it.';
    		}
        }
        
            // check if the $data['error_msg'] is empty; if it is empty start deleting
            
            $counter = 0;
 	    
 	    if($checkError == "No"){
 	        
 	      //  dd('test');
 	        
 	        foreach($temp_arr as $category){
 	            
 	            Category::find($category['vcategorycode'])->delete();
 	            $counter++;
 	        }
 	        
 	        $data['success'] = 'Deleted '.$counter.' category(s) successfully.';
 	        
 	    }
 	    
		return $data;
     }

    public function getSubCategoriesByCatId(Request $request){
        $input = $request->all(); 
	    $category_id = $input['category_id'];
	    
	    if($category_id){
            $results = SubCategory::where('cat_id', $category_id)
                        ->orderBy('subcat_name');
            if(count($results) > 0){
            $html ="<ol class='list-group' style='margin:15px;'>";
                foreach($results as $result){
                    $html .="<li style='font-size:14px;'>".$result['subcat_name']."</li>";
                }
            }else{
                $html ="<p class='text-warning' style='font-size:14px;'>No Sub Categories</p>";
            }
	    
	        $html .="</ol>";
		    echo json_encode($html);
		   
	    }else{
			$data['error'] = 'Something went wrong';
			// http_response_code(401);
			$this->response->addHeader('Content-Type: application/json');
		    echo json_encode($data);
			exit;
		}
	}
}
