<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Promotion;
use App\Model\promotiontype;
use App\Model\PromotionDetail;
use App\Model\Department;
use App\Model\Item;
use App\Model\Unit;
use App\Model\Size;
use App\Model\Category;
use App\Model\SubCategory;
use App\Model\WebAdminSetting;
use Session;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    
    public function promotion()
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        return view('items.promotion');
    }
    
    public function duplicatehqPermission(Request $request){
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();
        
        foreach($input as $promoid){
            $promocode = DB::connection("mysql_dynamic")->select("SELECT prom_code FROM trn_promotions where prom_id =  '".$promoid."' ");
            $stores = session()->get("stores_hq");
            // dd($stores);
            foreach($promocode as $pcode){
                $prom_code  = $pcode->prom_code;
            }
            $notExistStore = [];
            foreach($stores as $store){
                $promoId =  DB::connection("mysql")->select("SELECT prom_id FROM u".$store->id.".trn_promotions where prom_code =  '".$prom_code."' "); 
                if(count($promoId) == 0){
                    array_push($notExistStore, $store->id); 
                }
            }
        }
        
        return $notExistStore;
    }
    
    public function checkItemExist(Request $request){
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();
        
        $notExistStore = [];
        foreach($input['added_items_barcode'] as $barcode){
            
            $stores = session()->get("stores_hq");
            
            foreach($stores as $store){
                $iitemid =  DB::connection("mysql")->select("SELECT iitemid FROM u".$store->id.".mst_item where vbarcode =  '".$barcode."' "); 
            
                if(count($iitemid) == 0){
                    array_push($notExistStore, $store->id); 
                }
            }
        }
        
        return $notExistStore;
    }
    
    public function checkPromoIdExists(Request $request){
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();
        
        $promocode = DB::connection("mysql_dynamic")->select("SELECT prom_code FROM trn_promotions where prom_id =  '".$input['promoId']."' ");
        $stores = session()->get("stores_hq");
        // dd($stores);
        foreach($promocode as $pcode){
            $prom_code  = $pcode->prom_code;
        }
        $notExistStore = [];
        
        foreach($input['added_items_barcode'] as $barcode){
            foreach($stores as $store){
                $promoId =  DB::connection("mysql")->select("SELECT prom_id FROM u".$store->id.".trn_promotions where prom_code =  '".$prom_code."' "); 
                
                $iitemid =  DB::connection("mysql")->select("SELECT iitemid FROM u".$store->id.".mst_item where vbarcode =  '".$barcode."' "); 
                
                if(count($promoId) == 0 || count($iitemid) == 0){
                    array_push($notExistStore, $store->id); 
                }
            }
        }
        
        
        return $notExistStore;
    }
    
    protected function getForm(Request $request) 
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        

        $url = '';

        $input = $request->all();

       // dd($input['prom_id']);
        
        $departments = Department::orderBy('vdepartmentname', 'asc')->get()->toArray();
        $data['departments'] = $departments;

        $Promotion = new Promotion;

        // ================= to get promotion types =======================
        $promotion_data = $Promotion->getActivePromotionTypes();  
        $promotion_data = array_map(function ($value) {
            return (array)$value;
        }, $promotion_data);

        $data['promotion_types'] = $promotion_data;
        
        $data['action'] = '/savepromotion';
        $data['get_items_url']                      = url('/promotion/get_items');
        $data['get_customers_url']                  = url('/promotion/get_customers');
        $data['get_categories_url']                 = url('/promotion/get_item_categories');
        $data['get_sub_categories_url']             = url('/promotion/get_sub_categories_url');
        $data['get_department_items_url']           = url('/promotion/get_department_items');
        $data['get_category_items_url']             = url('/promotion/get_category_items');
        $data['get_sub_category_items_url']         = url('/promotion/get_sub_category_items');
        $data['get_selected_buy_items_url']         = url('/promotion/getSelectedBuyItems');
        $data['get_saved_buy_items_ajax_url']       = url('/promotion/getSavedItemsAjax');
        $data['get_selected_discounted_items_url']  = url('/promotion/getSelectedDiscountedItems');
        $data['get_saved_discounted_items_ajax_url']= url('/promotion/getSelectedDiscountedItemsAjax');
        $data['get_group_items_url']                = url('/promotion/get_group_items');
        $data['cancel']                             = url('/promotion');
        $data['searchitem']                         = url('/searchPromotionitems');
        $data['check_promocode']                    = url('/promotion/check_promocode');
        
        $departments_html ="";
        $departments_html = "<select class='form-control' name='dept_code' id='dept_code' style='width: 85px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach($departments as $department){
            if(isset($vdepcode) && $vdepcode == $department['vdepcode']){
                $departments_html .= "<option value='".$department['vdepcode']."' selected='selected'>".$department['vdepartmentname']."</option>";
            } else {
                $departments_html .= "<option value='".$department['vdepcode']."'>".$department['vdepartmentname']."</option>";
            }
        }
            $departments_html .="</select>";
            
            $data['departments'] = $departments_html;
            
            $item_types = ['Standard','Kiosk','Lot Matrix','Lottery'];
            
            $item_type_html ="";
            $item_type_html = "<select class='form-control' name='dept_code' id='dept_code' style='width: 100px;'>'<option value='all'>All</option>";
            foreach($item_types as $item_type){
                $item_type_html .= "<option value='".$item_type."'>".$item_type."</option>";
            }
            $item_type_html .="</select>";
            
            $data['item_types'] = $item_type_html;
            
            
         //   $suppliers = $this->model_administration_items->getSuppliers();
            $supplier_html ="";
            $supplier_html = "<select class='form-control' name='supplier_code' id='supplier_code' style='width: 100px;'>'<option value='all'>All</option>";
            // foreach($suppliers as $supplier){
            //     $supplier_html .= "<option value='".$supplier['vsuppliercode']."'>".$supplier['vcompanyname']."</option>";
            // }
            $supplier_html .="</select>";
            
            $data['suppliers'] = $supplier_html;
            
         //   $manufacturers = $this->model_administration_items->getManufacturers();
            $manufacturer_html ="";
            $manufacturer_html = "<select class='form-control' name='manufacturer_id' id='manufacturer_id' style='width: 100px;'>'<option value='all'>All</option>";
            // foreach($manufacturers as $manufacurer){
            //     $manufacturer_html .= "<option value='".$manufacurer['mfr_id']."'>".$manufacurer['mfr_name']."</option>";
            // }
            $manufacturer_html .="</select>";
            
            $data['manufacturers'] = $manufacturer_html;
            
            // $units = $this->model_administration_items->getItemUnits();
            $units = Unit::orderBy('vunitname', 'asc')->get()->toArray();
        //            $data['units'] = $units;

            $units_html ="";
            $units_html = "<select class='form-control' name='unit_id' id='unit_id' style='width: 50px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
            foreach($units as $unit){
                $units_html .= "<option value='".$unit['vunitcode']."'>".$unit['vunitname']."</option>";
            }
            $units_html .="</select>";
            
            $data['units'] = $units_html;
            
            // $sizes = $this->model_administration_items->getItemSize();
            $sizes = Size::orderBy('vsize','asc')->get()->toArray();

            $size_html ="";
            $size_html = "<select class='form-control' name='size_id' id='size_id' style='width: 50px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
            foreach($sizes as $size){
                $size_html .= "<option value='".$size['vsize']."'>".$size['vsize']."</option>";
            }
            $size_html .="</select>";
            
            $data['size'] = $size_html;
            
            
            //==================================== for price filter ====================================================
            
            
            $price_select_by_list = array(
                                    'greater'   => 'Greater than',
                                    'less'      => 'Less than',
                                    'equal'     => 'Equal to',
                                    'between'   => 'Between'
                                );        
                
            $price_select_by_html = "<select class='' id='price_select_by' name='price_select_by' style='width:90px; color:#000000'>";
            foreach($price_select_by_list as $k => $v){
                $price_select_by_html .= "<option value='".$k."'";
                
                if(isset($data['price_select_by']) && $k === $data['price_select_by']){
                    $price_select_by_html .= " selected";
                }
                
                $price_select_by_html .= ">".$v."</option>";
            }
            $price_select_by_html .= "</select>";
            $price_select_by_html .= "<span id='selectByValuesSpan'>";
            
            
            if(isset($data['price_select_by']) && $data['price_select_by'] === 'between'){                
                // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_2']."'/></span>";
                $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
                $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Amt' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
            } else {
                $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter Amt' style='width:70px;color:black;border-radius: 4px;height:28px;margin-left:5px;' value=''/>";
            }
            
            $price_select_by_html .= "</span>"; 
            
            
            $data['price'] = $price_select_by_html;  
        
        /********************/

         return view('items.addpromotion', compact('data'));
    }

    protected function editForm(Request $request) 
    {
        
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        $url = '';

        $input = $request->all();
        session()->put('prom_id',  $input['prom_id']);
      
        
        $departments = Department::orderBy('vdepartmentname', 'asc')->get()->toArray();
        $data['departments'] = $departments;

        $Promotion = new Promotion;

        // ================= to get promotion types =======================
        $promotion_data = $Promotion->getActivePromotionTypes();  
        $promotion_data = array_map(function ($value) {
            return (array)$value;
        }, $promotion_data);

        $data['promotion_types'] = $promotion_data;
        
        if (isset($input['prom_id']) ) {
            $data['action'] = '/updatepromotion';
             
        }else{
            $data['action'] = '/savepromotion';
        }
        $data['get_items_url']                      = url('/promotion/get_items');
        $data['get_customers_url']                  = url('/promotion/get_customers');
        $data['get_categories_url']                 = url('/promotion/get_item_categories');
        $data['get_sub_categories_url']             = url('/promotion/get_sub_categories_url');
        $data['get_department_items_url']           = url('/promotion/get_department_items');
        $data['get_category_items_url']             = url('/promotion/get_category_items');
        $data['get_sub_category_items_url']         = url('/promotion/get_sub_category_items');
        $data['get_selected_buy_items_url']         = url('/promotion/getSelectedBuyItems');
        $data['get_saved_buy_items_ajax_url']       = url('/promotion/getSavedItemsAjax');
        $data['get_selected_discounted_items_url']  = url('/promotion/getSelectedDiscountedItems');
        $data['get_saved_discounted_items_ajax_url']= url('/promotion/getSelectedDiscountedItemsAjax');
        $data['get_group_items_url']                = url('/promotion/get_group_items');
        $data['cancel']                             = url('/promotion');
        $data['searchitem']                         = url('/searchPromotionitems');
        $data['check_promocode']                    = url('/promotion/check_promocode');
        
        $departments_html ="";
        $departments_html = "<select class='form-control' name='dept_code' id='dept_code' style='width: 85px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach($departments as $department){
            if(isset($vdepcode) && $vdepcode == $department['vdepcode']){
                $departments_html .= "<option value='".$department['vdepcode']."' selected='selected'>".$department['vdepartmentname']."</option>";
            } else {
                $departments_html .= "<option value='".$department['vdepcode']."'>".$department['vdepartmentname']."</option>";
            }
        }
            $departments_html .="</select>";
            
            $data['departments'] = $departments_html;
            
            $item_types = ['Standard','Kiosk','Lot Matrix','Lottery'];
            
            $item_type_html ="";
            $item_type_html = "<select class='form-control' name='dept_code' id='dept_code' style='width: 100px;'>'<option value='all'>All</option>";
            foreach($item_types as $item_type){
                $item_type_html .= "<option value='".$item_type."'>".$item_type."</option>";
            }
            $item_type_html .="</select>";
            
            $data['item_types'] = $item_type_html;
            
            
         //   $suppliers = $this->model_administration_items->getSuppliers();
            $supplier_html ="";
            $supplier_html = "<select class='form-control' name='supplier_code' id='supplier_code' style='width: 100px;'>'<option value='all'>All</option>";
            // foreach($suppliers as $supplier){
            //     $supplier_html .= "<option value='".$supplier['vsuppliercode']."'>".$supplier['vcompanyname']."</option>";
            // }
            $supplier_html .="</select>";
            
            $data['suppliers'] = $supplier_html;
            
         //   $manufacturers = $this->model_administration_items->getManufacturers();
            $manufacturer_html ="";
            $manufacturer_html = "<select class='form-control' name='manufacturer_id' id='manufacturer_id' style='width: 100px;'>'<option value='all'>All</option>";
            // foreach($manufacturers as $manufacurer){
            //     $manufacturer_html .= "<option value='".$manufacurer['mfr_id']."'>".$manufacurer['mfr_name']."</option>";
            // }
            $manufacturer_html .="</select>";
            
            $data['manufacturers'] = $manufacturer_html;
            
            // $units = $this->model_administration_items->getItemUnits();
            $units = Unit::orderBy('vunitname', 'asc')->get()->toArray();
        //            $data['units'] = $units;

            $units_html ="";
            $units_html = "<select class='form-control' name='unit_id' id='unit_id' style='width: 50px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
            foreach($units as $unit){
                $units_html .= "<option value='".$unit['vunitcode']."'>".$unit['vunitname']."</option>";
            }
            $units_html .="</select>";
            
            $data['units'] = $units_html;
            
            // $sizes = $this->model_administration_items->getItemSize();
            $sizes = Size::orderBy('vsize','asc')->get()->toArray();

            $size_html ="";
            $size_html = "<select class='form-control' name='size_id' id='size_id' style='width: 50px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
            foreach($sizes as $size){
                $size_html .= "<option value='".$size['vsize']."'>".$size['vsize']."</option>";
            }
            $size_html .="</select>";
            
            $data['size'] = $size_html;
            
            
            //==================================== for price filter ====================================================
            
            $price_select_by_list = array(
                                    'greater'   => 'Greater than',
                                    'less'      => 'Less than',
                                    'equal'     => 'Equal to',
                                    'between'   => 'Between'
                                );        
                
            $price_select_by_html = "<select class='' id='price_select_by' name='price_select_by' style='width:90px; color:#000000'>";
            foreach($price_select_by_list as $k => $v){
                $price_select_by_html .= "<option value='".$k."'";
                
                if(isset($data['price_select_by']) && $k === $data['price_select_by']){
                    $price_select_by_html .= " selected";
                }
                
                $price_select_by_html .= ">".$v." </option>";
            }
            $price_select_by_html .= "</select>";
            $price_select_by_html .= "<span id='selectByValuesSpan'>";
        
            $price_select_by_html .= "</span>"; 
             
             
            if(isset($data['price_select_by']) && $data['price_select_by'] === 'between'){                
                // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_2']."'/></span>";
                $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
                $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Amt' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
            } else {
                $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter Amt' style='width:70px;color:black;border-radius: 4px;height:28px;margin-left:5px;' value=''/>";
            }
            $data['price'] = $price_select_by_html;            
    
        
        /********************/
        
        //  Error Messages
        // if (isset($this->error['warning'])) {
        //     $data['error_warning'] = $this->error['warning'];
        // } else {
        //     $data['error_warning'] = '';
        // }
        
        // if (isset($this->session->data['success'])) {
        //     $data['success'] = $this->session->data['success'];

        //     unset($this->session->data['success']);
        // } else {
        //     $data['success'] = '';
        // }
            
        // if (isset($this->error['promotion_name'])) {
        //     $data['error_promotion_name'] = $this->error['promotion_name'];
        // } else {
        //     $data['error_promotion_name'] = '';
        // }
        
        // if (isset($this->error['promotion_code'])) {
        //     $data['error_promotion_code'] = $this->error['promotion_code'];
        // } else {
        //     $data['error_promotion_code'] = '';
        // }
        
        // if (isset($this->error['promotion_type'])) {
        //     $data['error_promotion_type'] = $this->error['promotion_type'];
        // } else {
        //     $data['error_promotion_type'] = '';
        // }
        
        // if (isset($this->error['promotion_category'])) {
        //     $data['error_promotion_category'] = $this->error['promotion_category'];
        // } else {
        //     $data['error_promotion_category'] = '';
        // }
        
        // if (isset($this->error['promotion_buy_qty'])) {
        //     $data['error_promotion_buy_qty'] = $this->error['promotion_buy_qty'];
        // } else {
        //     $data['error_promotion_buy_qty'] = '';
        // }
        
        
        // if (isset($this->error['promotion_from_date'])) {
        //     $data['error_promotion_from_date'] = $this->error['promotion_from_date'];
        // } else {
        //     $data['error_promotion_from_date'] = '';
        // }
        
        // if (isset($this->error['promotion_to_date'])) {
        //     $data['error_promotion_to_date'] = $this->error['promotion_to_date'];
        // } else {
        //     $data['error_promotion_to_date'] = '';
        // }
        
        // if (isset($this->error['promotion_from_time'])) {
        //     $data['error_promotion_from_time'] = $this->error['promotion_from_time'];
        // } else {
        //     $data['error_promotion_from_time'] = '';
        // }
        
        // if (isset($this->error['promotion_to_time'])) {
        //     $data['error_promotion_to_time'] = $this->error['promotion_to_time'];
        // } else {
        //     $data['error_promotion_to_time'] = '';
        // }
        
        
        // if (isset($this->error['promotion_discounted_value'])) {
        //     $data['error_promotion_discounted_value'] = $this->error['promotion_discounted_value'];
        // } else {
        //     $data['error_promotion_discounted_value'] = '';
        // }
        
        // if (isset($this->error['promotion_discount_type'])) {
        //     $data['error_promotion_discount_type'] = $this->error['promotion_discount_type'];
        // } else {
        //     $data['error_promotion_discount_type'] = '';
        // }
        
        // if (isset($this->error['promotion_addl_discount'])) {
        //     $data['error_promotion_addl_discount'] = $this->error['promotion_addl_discount'];
        // } else {
        //     $data['error_promotion_addl_discount'] = '';
        // }
        
        // if (isset($this->error['promotion_discount_limit'])) {
        //     $data['error_promotion_discount_limit'] = $this->error['promotion_discount_limit'];
        // } else {
        //     $data['error_promotion_discount_limit'] = '';
        // }
        
        // if (isset($this->error['promotion_item_qty_limit'])) {
        //     $data['error_promotion_item_qty_limit'] = $this->error['promotion_item_qty_limit'];
        // } else {
        //     $data['error_promotion_item_qty_limit'] = '';
        // }
        
        // if (isset($this->error['promotion_slab_price'])) {
        //     $data['error_promotion_slab_price'] = $this->error['promotion_slab_price'];
        // } else {
        //     $data['error_promotion_slab_price'] = '';
        // }
        
        // if (isset($this->error['promotion_customers'])) {
        //     $data['error_promotion_customers'] = $this->error['promotion_customers'];
        // } else {
        //     $data['error_promotion_customers'] = '';
        // }
        
        //      echo "<pre>";print_r($data);exit;
        if (isset($input['prom_id']) ) {
            
            $PromotionDetail = new PromotionDetail;
            $promotion_info = $Promotion->getPromotion($input['prom_id']);
            
            $promotion_info = isset($promotion_info[0])?(array)$promotion_info[0]:[];
            
        }
      
        
        if (isset($input['prom_id'])) {
            $data['prom_id'] = $input['prom_id'];
        } elseif (!empty($promotion_info)) {
            $data['prom_id'] = $promotion_info['prom_id'];
        } else {
            $data['prom_id'] = '';
        }
        
        if (isset($input['promotion_name'])) {
            $data['promotion_name'] = $input['promotion_name'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_name'] = $promotion_info['prom_name'];
        } else {
            $data['promotion_name'] = '';
        }
        
        if (isset($input['promotion_code'])) {
            $data['promotion_code'] = $input['promotion_code'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_code'] = $promotion_info['prom_code'];
        } else {
            $data['promotion_code'] = '';
        }
        
        
        if (isset($input['promotion_type'])) {
            $data['promotion_type'] = $input['promotion_type'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_type'] = $promotion_info['prom_type_id'];
        } else {
            $data['promotion_type'] = '';
        }
        
        if (isset($input['promotion_category'])) {
            $data['promotion_category'] = $input['promotion_category'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_category'] = $promotion_info['category'];
        } else {
            $data['promotion_category'] = '';
        }
        
        
        
        if (isset($input['mfg_prom_desc'])) {
            $data['mfg_prom_desc'] = $input['mfg_prom_desc'];
        } elseif (!empty($promotion_info)) {
            $data['mfg_prom_desc'] = $promotion_info['mfg_prom_desc'];
        } else {
            $data['mfg_prom_desc'] = '';
        }
        
        if (isset($input['mfg_buydown_desc'])) {
            $data['mfg_buydown_desc'] = $input['mfg_buydown_desc'];
        } elseif (!empty($promotion_info)) {
            $data['mfg_buydown_desc'] = $promotion_info['mfg_buydown_desc'];
        } else {
            $data['mfg_buydown_desc'] = '';
        }
        
        if (isset($input['mfg_multipack_desc'])) {
            $data['mfg_multipack_desc'] = $input['mfg_multipack_desc'];
        } elseif (!empty($promotion_info)) {
            $data['mfg_multipack_desc'] = $promotion_info['mfg_multipack_desc'];
        } else {
            $data['mfg_multipack_desc'] = '';
        }
        
        if (isset($input['mfg_discount'])) {
            $data['mfg_discount'] = $input['mfg_discount'];
        } elseif (!empty($promotion_info)) {
            $data['mfg_discount'] = $promotion_info['mfg_discount'];
        } else {
            $data['mfg_discount'] = '';
        }
        
        
        if (isset($input['promotion_period'])) {
            $data['promotion_period'] = $input['promotion_period'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_period'] = $promotion_info['period'];
        } else {
            $data['promotion_period'] = '';
        }
        
        if (isset($input['promotion_buy_qty'])) {
            $data['promotion_buy_qty'] = $input['promotion_buy_qty'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_buy_qty'] = $promotion_info['buy_qty'];
        } else {
            $data['promotion_buy_qty'] = '';
        }
        
        if (isset($input['promotion_discount_type'])) {
            $data['promotion_discount_type'] = $input['promotion_discount_type'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_discount_type'] = $promotion_info['discount_type_id'];
        } else {
            $data['promotion_discount_type'] = '';
        }
        
        if (isset($input['promotion_bill_value'])) {
            $data['promotion_bill_value'] = $input['promotion_bill_value'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_bill_value'] = $promotion_info['bill_value'];
        } else {
            $data['promotion_bill_value'] = '';
        }
        
        if (isset($input['promotion_from_date'])) {
            $data['promotion_from_date'] = $input['promotion_from_date'];
        } elseif (!empty($promotion_info) && $promotion_info['start_date'] !=null) {
            $data['promotion_from_date'] = date("m-d-Y",strtotime($promotion_info['start_date']));
        } else {
            $data['promotion_from_date'] = '';
        }
        
        if (isset($input['promotion_to_date'])) {
            $data['promotion_to_date'] = $input['promotion_to_date'];
        } elseif (!empty($promotion_info) && $promotion_info['end_date'] !=null) {
            $data['promotion_to_date'] = date("m-d-Y",strtotime($promotion_info['end_date']));
        } else {
            $data['promotion_to_date'] = '';
        }
        
        if (isset($input['promotion_from_time'])) {
            $data['promotion_from_time'] = $input['promotion_from_time'];
        } elseif (!empty($promotion_info) && $promotion_info['from_time'] !=null) {
            $data['promotion_from_time'] = date('h:i A', strtotime($promotion_info['from_time']));
        } else {
            $data['promotion_from_time'] = '';
        }
        
        if (isset($input['promotion_to_time'])) {
            $data['promotion_to_time'] = $input['to_time'];
        } elseif (!empty($promotion_info) && $promotion_info['to_time'] !=null) {
            $data['promotion_to_time'] =  date('h:i A', strtotime($promotion_info['to_time']));
        } else {
            $data['promotion_to_time'] = '';
        }
        
        
        
        if (isset($input['addl_disc_cust'])) {
            $data['promotion_addl_discount'] = $input['promotion_addl_discount'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_addl_discount'] = $promotion_info['addl_disc_cust'];
        } else {
            $data['promotion_addl_discount'] = '';
        }
        
        if (isset($input['promotion_discounted_value'])) {
            $data['promotion_discounted_value'] = $input['promotion_discounted_value'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_discounted_value'] = $promotion_info['discounted_value'];
        } else {
            $data['promotion_discounted_value'] = '';
        }
        
        if (isset($input['promotion_discount_limit'])) {
            $data['promotion_discount_limit'] = $input['promotion_discount_limit'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_discount_limit'] = $promotion_info['discount_limit'];
        } else {
            $data['promotion_discount_limit'] = '';
        }
        
        if (isset($input['promotion_item_qty_limit'])) {
            $data['promotion_item_qty_limit'] = $input['promotion_item_qty_limit'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_item_qty_limit'] = $promotion_info['prom_qty_limit'];
        } else {
            $data['promotion_item_qty_limit'] = '';
        }
        
        if (isset($input['promotion_item_qty_limit'])) {
            $data['promotion_item_qty_limit'] = $input['promotion_item_qty_limit'];
        } 
        elseif (!empty($promotion_info)) {
            $data['promotion_item_qty_limit_bal'] = $promotion_info['qty_limit_bal'];
        } else {
            $data['promotion_item_qty_limit_bal'] = '';
        }
        
        if (isset($input['qty_limit'])) {
            $data['qty_limit'] = $input['qty_limit'];
        } 
        elseif (!empty($promotion_info) && !empty($promotion_info['qty_limit'])) {
            $data['qty_limit'] = $promotion_info['qty_limit'];
        } else {
            $data['qty_limit'] = 0;
        }
        
        if (isset($input['promotion_same_itme'])) {
            $data['promotion_same_itme'] = $input['promotion_same_itme'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_same_itme'] = $promotion_info['same_group'];
        } else {
            $data['promotion_same_itme'] = '';
        }
        
        if (isset($input['promotion_disc_options'])) {
            $data['promotion_disc_options'] = $input['promotion_disc_options'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_disc_options'] = $promotion_info['disc_each_nth'];
        } else {
            $data['promotion_disc_options'] = '';
        }
        //      echo "<pre>";print_r($data);exit;
        if (isset($input['promotion_item_state'])) {
            $data['promotion_item_state'] = $input['promotion_item_state'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_item_state'] = $promotion_info['exclude_include'];
        } else {
            $data['promotion_item_state'] = '';
        }
        
        if (isset($input['promotion_slab_price'])) {
            $data['promotion_slab_price'] = $input['promotion_slab_price'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_slab_price'] = $promotion_info['slab_price'];
        } else {
            $data['promotion_slab_price'] = '';
        }
        
        if (isset($input['allow_reg_price'])) {
            $data['allow_reg_price'] = $input['allow_reg_price'];
        } elseif (!empty($promotion_info)) {
            $data['allow_reg_price'] = $promotion_info['allow_reg_price'];
        } else {
            $data['allow_reg_price'] = 'Y';
        }
        
        if (isset($input['promotion_status'])) {
            $data['promotion_status'] = $input['promotion_status'];
        } elseif (!empty($promotion_info)) {
            $data['promotion_status'] = $promotion_info['status'];
        } else {
            $data['promotion_status'] = '';
        }
        //  echo "<pre>";print_r($promotion_info);exit;
        if (isset($input['added_promotion_items_text']) && !empty($input['added_promotion_items_text'])) {
            $data['selected_buy_items'] = $this->getSelectedItems($input['added_promotion_items_text']);
        }else if(!empty($promotion_info)){

            $saved_items = $this->getSavedBuyItems($promotion_info['prom_id']);
            $saved_items = array_map(function ($value) {
                return (array)$value;
            }, $saved_items);
            $data['selected_buy_items'] = $saved_items;
            
           // print_r($data['selected_buy_items']); die;
        }else {
            $data['selected_buy_items'] = '';
        }
        
            //  echo "<pre>";print_r($data);exit;
        
        if (isset($input['promotion_discounted_item_text']) && !empty($input['promotion_discounted_item_text'])) {
            $data['selected_discount_items'] = $this->getSelectedItems($input['promotion_discounted_item_text']);
        }else if(!empty($promotion_info) && $promotion_info['discounted_item'] != null){
            $data['selected_discount_items'] = json_decode($promotion_info['discounted_item']);
           
        }else {
            $data['selected_discount_items'] = '';
        }

        return view('items.addpromotion', compact('data'));
    }


    public function savepromotion(Request $req){
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '-1');
        $input = $req->all();
        
        if(isset($input['added_promotion_items_text'])){
        if(count($input['added_promotion_items_text']) > 2000){
            $req->session()->flash('message','You can not add more than 2000 items!');
                return redirect('/promotion');
        }
        }
        
        if($input['promotion_category'] == "Open Ended" || $input['promotion_category'] == "Stock Bound")
        {
            $from_date = date("Y-m-d");
            $to_date = NULL;
            
        }
        elseif($input['promotion_category'] == "Time Bound")
        {
            $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['promotion_from_date']))) ;
            $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['promotion_to_date']))) ;
        
        }
        else
        {
            $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['promotion_from_date']))) ;
            $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['promotion_to_date']))) ;
           
        }
        
        
      
        if($input['promotion_category'] == "Time Bound")
        {   
            if(isset($input['promotion_from_time'])){
                $from_time = date("H:i:s",strtotime($input['promotion_from_time']));
            }else{
                $from_time = date("H:i:s");
            }
            
            if(isset($input['promotion_to_time'])){
                $to_time  = date("H:i:s",strtotime($input['promotion_to_time']));
            }else{
                $to_time  = date("H:i:s", strtotime('23:59'));
            }
        }else {
            $from_time = date("H:i:s");
            $to_time  = date("H:i:s", strtotime('23:59'));
        }
       
       // $this->load->model('administration/items');
        
        if($input['promotion_type'] == 10)
        {
            $discount_type_id = 2;
            //$promotion_addl_discount = isset($input('promotion_addl_discount')? $input('promotion_addl_discount') : $input('promotion_slab_price'));
            
            if($input['promotion_addl_discount'] != null || $input['promotion_addl_discount'] !=""){
                 $promotion_addl_discount = $input['promotion_addl_discount'];
            }else{
                
                $promotion_addl_discount=$input['promotion_slab_price'];
            }
        } 
        else 
        {
            $discount_type_id = $input['promotion_discount_type'];
            //$promotion_addl_discount = isset($data('promotion_addl_discount') ? $input('promotion_addl_discount'):0);
            if($input['promotion_addl_discount'] != null || $input['promotion_addl_discount'] != ""){
                $promotion_addl_discount = $input['promotion_addl_discount'];
            }else{
                $promotion_addl_discount = 0;
            }
        }

        
        $same_group = $input['promotion_type'] == 12?"Group Item":$input['promotion_same_itme'];
        
        $qty_limit = $input['qty_limit'];
        
        $qty_limit = (!is_nan($qty_limit) && ($qty_limit > 0))?$qty_limit:0;

        
      
        $allow_reg_price = $qty_limit > 0?$input['allow_reg_price']:'Y';

        if(isset($input['stores_hq'])){
            if($input['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $input['stores_hq']);
            }
            
            foreach($stores as $store){
                
                $promotion_discounted_value = 0;
                
                if(isset($input['promotion_discounted_value']) && !empty($input['promotion_discounted_value']) ){
                    $promotion_discounted_value = $input['promotion_discounted_value'];
                }
                
                /*time being fix for promotion by venkat */
                $promo_qty_limit = ($input['promotion_item_qty_limit'] > 0) ? $input['promotion_item_qty_limit'] : 50;
                /*time being fix for promotion by venkat */
                
                if($input['promotion_category'] == "Open Ended" || $input['promotion_category'] == "Stock Bound")
                {
                    $inser_query = "INSERT INTO u".$store.".trn_promotions(prom_name, prom_code, category, period, start_date,  qty_limit, prom_type_id, buy_qty, same_group, disc_each_nth, bill_value, discount_type_id, discounted_value, addl_disc_cust, qty_limit_bal, discount_limit, slab_price, mfg_discount, mfg_prom_desc, mfg_buydown_desc, mfg_multipack_desc, prom_qty_limit, allow_reg_price, status) 
                    values ('".$input['promotion_name']."', '".$input['promotion_code']."', '".$input['promotion_category']."', '".$input['promotion_period']."', '".$from_date."', '".$qty_limit."', '".$input['promotion_type']."', '".$input['promotion_buy_qty']."', '".$input['promotion_same_itme']."', '".$input['promotion_disc_options']."', '".(float)$input['promotion_bill_value']."', '".$input['promotion_discount_type']."',  '".$promotion_discounted_value."', '".$promotion_addl_discount."', '".(int)$input['promotion_item_qty_limit']."', '".(float)$input['promotion_discount_limit']."', '".(float)$input['promotion_slab_price']."', '".(float)$input['mfg_discount']."', '".$input['mfg_prom_desc']."', '".$input['mfg_buydown_desc']."', '".$input['mfg_multipack_desc']."', '".(int)$promo_qty_limit."', '".$allow_reg_price."', '".$input['promotion_status']."'  ) ";  
                }
                elseif($input['promotion_category'] == "Time Bound")
                {
                    $inser_query = "INSERT INTO u".$store.".trn_promotions(prom_name, prom_code, category, period, start_date, end_date, from_time, to_time, qty_limit, prom_type_id, buy_qty, same_group, disc_each_nth, bill_value, discount_type_id, discounted_value, addl_disc_cust, qty_limit_bal, discount_limit, slab_price, mfg_discount, mfg_prom_desc, mfg_buydown_desc, mfg_multipack_desc, prom_qty_limit, allow_reg_price, status) values ('".$input['promotion_name']."', '".$input['promotion_code']."', '".$input['promotion_category']."', '".$input['promotion_period']."', '".$from_date."', '".$to_date."', '".$from_time."', '".$to_time."', '".$qty_limit."', '".$input['promotion_type']."', '".$input['promotion_buy_qty']."', '".$input['promotion_same_itme']."', '".$input['promotion_disc_options']."', '".(float)$input['promotion_bill_value']."', '".$input['promotion_discount_type']."',  '".$promotion_discounted_value."', '".$promotion_addl_discount."', '".(int)$input['promotion_item_qty_limit']."', '".(float)$input['promotion_discount_limit']."', '".(float)$input['promotion_slab_price']."', '".(float)$input['mfg_discount']."', '".$input['mfg_prom_desc']."', '".$input['mfg_buydown_desc']."', '".$input['mfg_multipack_desc']."', '".(int)$promo_qty_limit."', '".$allow_reg_price."', '".$input['promotion_status']."'  ) ";  
                }
                else
                {
                    $inser_query = "INSERT INTO u".$store.".trn_promotions(prom_name, prom_code, category, period, qty_limit, prom_type_id, buy_qty, same_group, disc_each_nth, bill_value, discount_type_id, discounted_value, addl_disc_cust, qty_limit_bal, discount_limit, slab_price, mfg_discount, mfg_prom_desc, mfg_buydown_desc, mfg_multipack_desc, prom_qty_limit, allow_reg_price, status) values ('".$input['promotion_name']."', '".$input['promotion_code']."', '".$input['promotion_category']."', '".$input['promotion_period']."',  '".$qty_limit."', '".$input['promotion_type']."', '".$input['promotion_buy_qty']."', '".$input['promotion_same_itme']."', '".$input['promotion_disc_options']."', '".(float)$input['promotion_bill_value']."', '".$input['promotion_discount_type']."',  '".$promotion_discounted_value."', '".$promotion_addl_discount."', '".(int)$input['promotion_item_qty_limit']."', '".(float)$input['promotion_discount_limit']."', '".(float)$input['promotion_slab_price']."', '".(float)$input['mfg_discount']."', '".$input['mfg_prom_desc']."', '".$input['mfg_buydown_desc']."', '".$input['mfg_multipack_desc']."', '".(int)$promo_qty_limit."', '".$allow_reg_price."', '".$input['promotion_status']."'  ) ";  
                }
                
                // dd($inser_query);
                
                DB::connection('mysql')->insert($inser_query);
                
                $promoId =  DB::connection('mysql')->select("Select prom_id from u".$store.".trn_promotions Order by  prom_id DESC LIMIT 1 ");
                foreach($promoId as $pid){
                    $prom_id = $pid->prom_id;
                }
                
                if(!empty($input['added_promotion_items_text']) && strlen($prom_id)>0)
                {
                    $buyItems = $input['added_promotion_items_text'];
                    
                    if(!empty($input['disc']))
                    {
                        $discount_price_list = $input['disc'];
                    }
                    
                    foreach($buyItems as $key => $value)
                    {
                        // $barcode = DB::connection('mysql_dynamic')->select("Select vbarcode from mst_item where iitemid = '".$value."'  ");
                        
                        // foreach($barcode as $bar){
                        //     $vbarcode = $bar->vbarcode;
                        // }
                        
                        $iitemid = DB::connection('mysql')->select("Select iitemid from u".$store.".mst_item where vbarcode = '".$value."'  ");
                        
                        $item_id = $iitemid[0]->iitemid;
                        
                        $itemDetails=$this->getItem($item_id, $store); 
                        if(!empty($itemDetails)>0)
                        {
                            $disc_value = isset($discount_price_list[$value]) ? $discount_price_list[$value] : 0;
                            if($input['promotion_type'] == 10) {
                                // $new_price = $promotion_addl_discount/$input['promotion_buy_qty'];
                                $new_price = $input['promotion_slab_price']/$input['promotion_buy_qty'];
                                $new_price = number_format($new_price , 2);
                                DB::connection('mysql')->insert("INSERT INTO u".$store.".trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$store."' ");
                            } else {
                                $insert_data = "INSERT INTO u".$store.".trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$store."' ";
                                DB::connection('mysql')->insert($insert_data);
                            }
                        }  
                    }                
                }
                
            } 
        }else{
            //dd($allow_reg_price);
            $promotion = new Promotion;
    
            $promotion->prom_name = $req->input('promotion_name');
            $promotion->prom_code =$req->input('promotion_code');
            $promotion->category =$req->input('promotion_category');
            $promotion->period =$req->input('promotion_period');
            $promotion->start_date =$from_date;
            $promotion->end_date =$to_date;
            $promotion->from_time =$from_time;
            $promotion->to_time =$to_time;
           // $promotion->qty_limit =$req->input('promotion_item_qty_limit');
            $promotion->qty_limit =$qty_limit;
            //new change 
            $promotion->prom_type_id =$req->input('promotion_type');
            $promotion->buy_qty =$req->input('promotion_buy_qty');
            $promotion->same_group =$req->input('promotion_same_itme');
            $promotion->disc_each_nth =$req->input('promotion_disc_options');
            $promotion->bill_value =$req->input('promotion_bill_value');
            $promotion->discount_type_id =$req->input('promotion_discount_type');
            $promotion->discounted_value =$req->input('promotion_discounted_value') ??'0';
           // $promotion->discounted_item =json_encode($req->input('promotion_discounted_item_text') ?? '';
            $promotion->addl_disc_cust = $promotion_addl_discount;
            $promotion->qty_limit_bal =$req->input('promotion_item_qty_limit');
            $promotion->discount_limit =$req->input('promotion_discount_limit');
            $promotion->slab_price =$req->input('promotion_slab_price');
            $promotion->mfg_discount =$req->input('mfg_discount');
            //$promotion->exclude_include =$req->input('promotion_item_state');
            $promotion->mfg_prom_desc =$req->input('mfg_prom_desc');
            $promotion->mfg_buydown_desc =$req->input('mfg_buydown_desc');
            $promotion->mfg_multipack_desc =$req->input('mfg_multipack_desc');
            //$promotion->img_url =$req->input('');
           // $promotion->prom_qty_limit = $qty_limit;
           
            /*Time being fix for  promotion by venkat*/
            // $promotion->prom_qty_limit = $req->input('promotion_item_qty_limit');
            $promotion->prom_qty_limit = ($req->input('promotion_item_qty_limit') > 0) ? $req->input('promotion_item_qty_limit') : 50 ;
            /*End of Time being fix for  promotion by venkat*/
           
           
            
            $promotion->allow_reg_price = $allow_reg_price;
            $promotion->status =$req->input('promotion_status');
            $promotion->save();
           
            $prom_id = $promotion->prom_id;
           //echo $prom_id." inserted successfully";
            
       //echo $prom_id." inserted successfully";

            if(!empty($input['added_promotion_items_text']) && strlen($prom_id)>0)
            {
                $buyItems = $input['added_promotion_items_text'];
                
                if(!empty($input['disc']))
                {
                    $discount_price_list = $input['disc'];
                }
                
                foreach($buyItems as $key => $value)
                {
                    $iitemid = DB::connection('mysql_dynamic')->select("Select iitemid from mst_item where vbarcode = '".$value."'  ");
                    // dd($iitemid[0]->iitemid);
                    foreach($iitemid as $id){
                        $item_id = $id->iitemid;
                    }
                    $itemDetails=$this->getItem($item_id);
                    
                    if(!empty($itemDetails)>0)
                    {
                        $disc_value = isset($discount_price_list[$value]) ? $discount_price_list[$value] : 0;
                        
                        
                        if($input['promotion_type'] == 10){
                            
                            //  $new_price = $data['promotion_slab_price']/$data['promotion_buy_qty'];
                            
                            // $new_price = $promotion_addl_discount/$input['promotion_buy_qty'];
                            $new_price = $input['promotion_slab_price']/$input['promotion_buy_qty'];
                            
                            $new_price = number_format($new_price , 2);
                            
                            \DB::connection('mysql_dynamic')->insert("INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', SID ='".session()->get('sid')."' ");
                        } else {
                            
                            $insert_data = "INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', SID ='".session()->get('sid')."' ";
                            \DB::connection('mysql_dynamic')->insert($insert_data);
                            
                        }
                    }  
                }              
            }
        }
        // if(!empty($data['promotion_customers']) && $prom_id)
        // {
        //     $customers = $data['promotion_customers'];
           
        //     foreach($customers as $value)
        //     {
        //         if(!empty($value))
        //         {
        //             $this->db2->query("INSERT INTO trn_prom_customers SET prom_id ='".$prom_id."', cust_id ='".$value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$itemDetails['SID']."' ");
        //         }
        //     }
            
        // }
        //echo $prom_id." inserted successfully"; 
       // return $prom_id;
       $req->session()->flash('message','Success: You have Added Promotion Successfully!');
        return redirect('/promotion');
    }
    
    public function updatepromotion(Request $req){
        //dd(date('Y-m-d H:i:s'));
        
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', "2G");
        
        
        $input = $req->all();
        
        if($input['promotion_bill_value']=='NULL' || $input['promotion_bill_value']==''){
            $promotion_bill_value=0;
            
        }
        else{
            $promotion_bill_value=$input['promotion_bill_value'];
        }
        
        if(isset($input['added_promotion_items_text'])){
        if(count($input['added_promotion_items_text']) > 2000){
            $req->session()->flash('message','You can not add more than 2000 items!');
                return redirect('/promotion');
        }
        }
        
        // dd($input);
        
        if($input['promotion_category'] == "Open Ended" || $input['promotion_category'] == "Stock Bound")
        {
            $from_date = date("Y-m-d");
            $to_date = NULL;
            
        }
        elseif($input['promotion_category'] == "Time Bound")
        {
            $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['promotion_from_date']))) ;
            $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['promotion_to_date']))) ;
            //$query .= ", end_date = '$to_date' ";
        }
        else
        {
            $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['promotion_from_date']))) ;
            $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['promotion_to_date']))) ;
            //$query .= ", end_date = '$to_date' ";
        }
        
        
        $timeQuery = "";
        if($input['promotion_category'] == "Time Bound")
        {
            $from_time = date("H:i:s",strtotime($input['promotion_from_time']));
            $to_time  = date("H:i:s",strtotime($input['promotion_to_time']));
        }else {
            $from_time = NULL;
            $to_time  = NULL;
        }
        
        
        if($input['promotion_type'] == 10)
        {
            $discount_type_id = 2;
            //$promotion_addl_discount = isset($input('promotion_addl_discount')? $input('promotion_addl_discount') : $input('promotion_slab_price'));
            
            if($input['promotion_addl_discount'] !== null || $input['promotion_addl_discount'] !== ""){
                 $promotion_addl_discount = $input['promotion_addl_discount'];
            }else{
                
                $promotion_addl_discount=$input['promotion_slab_price'];
                
            }
        } 
        else 
        {
            $discount_type_id = $input['promotion_discount_type'];
            //$promotion_addl_discount = isset($data('promotion_addl_discount') ? $input('promotion_addl_discount'):0);
            if($input['promotion_addl_discount'] !== null || $input['promotion_addl_discount'] !== ""){
                $promotion_addl_discount = $input['promotion_addl_discount'];
            }else{
                $promotion_addl_discount = 0;
            }
        }
        
        
        $same_group = $input['promotion_type'] == 12 ?"Group Item":$input['promotion_same_itme'];
        
        $qty_limit = $input['qty_limit'];
        
        $qty_limit = (!is_nan($qty_limit) && ($qty_limit > 0))?$qty_limit:0;
        
      
        $allow_reg_price = $qty_limit > 0?$input['allow_reg_price']:'Y';
        
        if(isset($input['stores_hq'])){
            if($input['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $input['stores_hq']);
            }
            
            $promoID =  session()->get('prom_id') ; 
            
            $promotion_code = DB::connection("mysql_dynamic")->select("select prom_code from trn_promotions  where prom_id = '".$promoID."' ");
            
            // foreach($promotion_code as $pcode){
            //     $promo_code = $pcode->prom_id;
            // }
            
            $promo_code = $promotion_code[0]->prom_code;
            
            foreach($stores as $store){
                
                $promotion_id = DB::connection('mysql')->select("Select prom_id from u".$store.".trn_promotions where prom_code = '".$promo_code."' ");
                
                // foreach($promotion_id as $pid){
                //     $prom_id = $pid->prom_id;
                // }
                
                $prom_id = $promotion_id[0]->prom_id;
                
                if($input['promotion_category'] == "Open Ended" || $input['promotion_category'] == "Stock Bound")
                {
                    // $from_date = date("Y-m-d");
                    $to_date = NULL;
                    $update_promo_query = "UPDATE u".$store.".trn_promotions  SET  
                                prom_name = '".$req->input('promotion_name')."', 
                                prom_code = '".$req->input('promotion_code')."', 
                                category = '".$req->input('promotion_category')."',
                                period ='".$req->input('promotion_period')."',
                               
                                qty_limit ='".$qty_limit."',
                                prom_type_id ='".$req->input('promotion_type')."',
                                buy_qty ='".$req->input('promotion_buy_qty')."',
                                same_group ='".$req->input('promotion_same_itme')."',
                                disc_each_nth ='".$req->input('promotion_disc_options')."',
                                bill_value ='".$promotion_bill_value."',
                                discount_type_id ='".$req->input('promotion_discount_type')."',
                                discounted_value ='".$req->input('promotion_discounted_value')."',
                                addl_disc_cust = '".(float)$promotion_addl_discount."',
                                qty_limit_bal ='".$req->input('promotion_item_qty_limit')."',
                                discount_limit ='".(float)$req->input('promotion_discount_limit')."',
                                slab_price ='".(float)$req->input('promotion_slab_price')."',
                                mfg_discount ='".(float)$req->input('mfg_discount')."',
                                exclude_include ='".$req->input('promotion_item_state')."',
                                mfg_prom_desc ='".$req->input('mfg_prom_desc')."',
                                mfg_buydown_desc ='".$req->input('mfg_buydown_desc')."',
                                mfg_multipack_desc ='".$req->input('mfg_multipack_desc')."',
                                prom_qty_limit ='".$req->input('promotion_item_qty_limit')."',
                                allow_reg_price = '".$allow_reg_price."',
                                status ='".$req->input('promotion_status')."' where prom_id = '" . (int)$prom_id . "'   ";
                                
                    
                }
                elseif($input['promotion_category'] == "Time Bound")
                {
                        $update_promo_query = "UPDATE u".$store.".trn_promotions  SET  
                                prom_name = '".$req->input('promotion_name')."', 
                                prom_code = '".$req->input('promotion_code')."', 
                                category = '".$req->input('promotion_category')."',
                                period ='".$req->input('promotion_period')."',
                                start_date ='".$from_date."',
                                end_date ='".$to_date."',
                                qty_limit ='".$qty_limit."',
                                prom_type_id ='".$req->input('promotion_type')."',
                                buy_qty ='".$req->input('promotion_buy_qty')."',
                                same_group ='".$req->input('promotion_same_itme')."',
                                disc_each_nth ='".$req->input('promotion_disc_options')."',
                                bill_value ='".$promotion_bill_value."',
                                discount_type_id ='".$req->input('promotion_discount_type')."',
                                discounted_value ='".$req->input('promotion_discounted_value')."',
                                addl_disc_cust = '".(float)$promotion_addl_discount."',
                                qty_limit_bal ='".$req->input('promotion_item_qty_limit')."',
                                discount_limit ='".(float)$req->input('promotion_discount_limit')."',
                                slab_price ='".(float)$req->input('promotion_slab_price')."',
                                mfg_discount ='".(float)$req->input('mfg_discount')."',
                                exclude_include ='".$req->input('promotion_item_state')."',
                                mfg_prom_desc ='".$req->input('mfg_prom_desc')."',
                                mfg_buydown_desc ='".$req->input('mfg_buydown_desc')."',
                                mfg_multipack_desc ='".$req->input('mfg_multipack_desc')."',
                                prom_qty_limit ='".$req->input('promotion_item_qty_limit')."',
                                allow_reg_price = '".$allow_reg_price."',
                                status ='".$req->input('promotion_status')."' where prom_id = '" . (int)$prom_id . "'   ";
    
                }
                else
                {
                       $update_promo_query = "UPDATE u".$store.".trn_promotions  SET  
                                prom_name = '".$req->input('promotion_name')."', 
                                prom_code = '".$req->input('promotion_code')."', 
                                category = '".$req->input('promotion_category')."',
                                period ='".$req->input('promotion_period')."',
                                qty_limit ='".$qty_limit."',
                                prom_type_id ='".$req->input('promotion_type')."',
                                buy_qty ='".$req->input('promotion_buy_qty')."',
                                same_group ='".$req->input('promotion_same_itme')."',
                                disc_each_nth ='".$req->input('promotion_disc_options')."',
                                bill_value ='".$promotion_bill_value."',
                                discount_type_id ='".$req->input('promotion_discount_type')."',
                                discounted_value ='".$req->input('promotion_discounted_value')."',
                                addl_disc_cust = '".(float)$promotion_addl_discount."',
                                qty_limit_bal ='".$req->input('promotion_item_qty_limit')."',
                                discount_limit ='".(float)$req->input('promotion_discount_limit')."',
                                slab_price ='".(float)$req->input('promotion_slab_price')."',
                                mfg_discount ='".(float)$req->input('mfg_discount')."',
                                exclude_include ='".$req->input('promotion_item_state')."',
                                mfg_prom_desc ='".$req->input('mfg_prom_desc')."',
                                mfg_buydown_desc ='".$req->input('mfg_buydown_desc')."',
                                mfg_multipack_desc ='".$req->input('mfg_multipack_desc')."',
                                prom_qty_limit ='".$req->input('promotion_item_qty_limit')."',
                                allow_reg_price = '".$allow_reg_price."',
                                status ='".$req->input('promotion_status')."' where prom_id = '" . (int)$prom_id . "'   ";
                    
                }                           
                
                $a = DB::connection('mysql')->update($update_promo_query);
                
                //time not updating currect dont change 
                $update_query_time = "UPDATE u".$store.".trn_promotions SET LastUpdate =now() WHERE prom_id = '" . (int)$prom_id . "' ";
                
                $query =  DB::connection('mysql')->update($update_query_time);
                
                if(!empty($input['added_promotion_items_text']) && $prom_id) {
                    
                    if(!empty($input['disc'])) {
                        $discount_price_list = $input['disc'];
                    }
                    $sql="SELECT tpd.prom_detail_id prom_detail_id, tpd.barcode barcode, mi.iitemid item_id FROM  u".$store.".trn_prom_details tpd LEFT JOIN u".$store.".mst_item mi ON tpd.barcode=mi.vbarcode WHERE tpd.prom_id = '" . (int)$prom_id . "'";
                    
                    $query =  DB::connection('mysql')->select($sql);
                    $existing_items = json_decode(json_encode($query), true); 
                    
                    $exist_item =array();
                    if(count($existing_items) > 0){
                        foreach($existing_items as $v){
                            $exist_item[] = $v['barcode'];
                        }
                    }
                    
                    foreach($input['added_promotion_items_text'] as $barcode){
                        
                        if(in_array($barcode, $exist_item)){
                            
                            $iitemid = DB::connection('mysql')->select("Select iitemid from u".$store.".mst_item where vbarcode = '".$barcode."'  ");
                        
                            $item_id = $iitemid[0]->iitemid;
                            
                            $itemDetails = $this->getItem($item_id, $store);
                            
                            if(!empty($itemDetails)) {
                                $disc_value = isset($discount_price_list[$item_id]) ? $discount_price_list[$item_id] : 0;
                                if($input['promotion_type'] == 10){
                                    
                                    // $new_price = $promotion_addl_discount/$input['promotion_buy_qty'];
                                    $new_price = $input['promotion_slab_price']/$input['promotion_buy_qty'];
                                    $new_price = number_format($new_price , 2);
                                    
                                    $update_query = "UPDATE u".$store.".trn_prom_details SET prom_id='".$prom_id."', barcode='".$itemDetails['vbarcode']."', unit_price = '".$itemDetails['dunitprice'] ."', discounted_price='".$new_price."', LastUpdate ='".date('Y-m-d H:i:s')."', SID = '".$store."' WHERE `prom_id`={$prom_id} AND barcode='".$itemDetails['vbarcode']."'";
                                    
                                } else {
                                    $update_query = "UPDATE u".$store.".trn_prom_details SET prom_id ='".$prom_id."', barcode='".$itemDetails['vbarcode']."', unit_price='".$itemDetails['dunitprice']."', discounted_price='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$store."' WHERE prom_id= '".$prom_id."' AND barcode='".$itemDetails['vbarcode']."' ";
                                }	            
                                $query =  DB::connection('mysql')->update($update_query);
                            }
                            
                        } else {
                            
                            $iitemid = DB::connection('mysql')->select("Select iitemid from u".$store.".mst_item where vbarcode = '".$barcode."'  ");
                            
                            $item_id = $iitemid[0]->iitemid;
                            
                            $itemDetails = $this->getItem($item_id, $store);
                            
                            if(!empty($itemDetails)) {
                                
                                $disc_value = isset($discount_price_list[$item_id]) ? $discount_price_list[$item_id] : 0;
                            
                                if($input['promotion_type'] == 10){
                                    
                                    // $new_price = $promotion_addl_discount/$input['promotion_buy_qty'];
                                    $new_price = $input['promotion_slab_price']/$input['promotion_buy_qty'];
                                    $new_price = number_format($new_price , 2);
                                    
                                    $query_insert = "INSERT INTO u".$store.".trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$store."' ";
                                } else {
                                    $query_insert = "INSERT INTO u".$store.".trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$store."' ";
                                }
                                
                              $query =  DB::connection('mysql')->insert($query_insert);
                        }
                        
                        
                        }
                    } 
                    
                    foreach($existing_items as $et){
                        
                        if(!in_array($et['barcode'], $input['added_promotion_items_text'])){
                            
                            $delete_query = "DELETE FROM  u".$store.".trn_prom_details WHERE prom_detail_id = '" . (int)$et['prom_detail_id'] . "'";
                            $query =  DB::connection('mysql')->delete($delete_query);
                            
                            $insert_delete_query = "INSERT INTO u".$store.".mst_delete_table (TableName, TableId, Action, LastUpdate, SID) 
                                                    VALUES('trn_prom_details', '". (int)$et['prom_detail_id'] . "', 'delete', '".date('Y-m-d H:i:s')."', '".$store."')";
                            $query =  DB::connection('mysql')->insert($insert_delete_query);
                        }
                    }
                    // foreach($existing_items as $et){
                        
                    //     if(in_array($et['barcode'], $input['added_promotion_items_text'])){
                            
                    //         $iitemid = DB::connection('mysql')->select("Select iitemid from u".$store.".mst_item where vbarcode = '".$et['barcode']."'  ");
                        
                    //         $item_id = $iitemid[0]->iitemid;
                            
                    //         $itemDetails = $this->getItem($et['item_id'], $store);
                            
                    //         if(!empty($itemDetails)) {
                    //             $disc_value = isset($discount_price_list[$et['item_id']]) ? $discount_price_list[$et['item_id']] : 0;
                    //             if($input['promotion_type'] == 10){
                                    
                    //                 $new_price = $promotion_addl_discount/$input['promotion_buy_qty'];
                    //                 $new_price = number_format($new_price , 2);
                                    
                    //                 $update_query = "UPDATE u".$store.".trn_prom_details SET prom_id='".$prom_id."', barcode='".$itemDetails['vbarcode']."', unit_price = '".$itemDetails['dunitprice'] ."', discounted_price='".$new_price."', LastUpdate ='".date('Y-m-d H:i:s')."', SID = '".$store."' WHERE `prom_detail_id`={$et['prom_detail_id']}";
                                    
                    //             } else {
                    //                 $update_query = "UPDATE u".$store.".trn_prom_details SET prom_id ='".$prom_id."', barcode='".$itemDetails['vbarcode']."', unit_price='".$itemDetails['dunitprice']."', discounted_price='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$store."' WHERE prom_detail_id= '".$et['prom_detail_id']."' ";
                    //             }	            
                    //             $query =  DB::connection('mysql')->select($update_query);
                    //         }
                            
                    //     } else {
                            
                    //         $delete_query = "DELETE FROM  u".$store.".trn_prom_details WHERE prom_detail_id = '" . (int)$et['prom_detail_id'] . "'";
                    //         $query =  DB::connection('mysql')->select($delete_query);
                            
                    //         $insert_delete_query = "INSERT INTO u".$store.".mst_delete_table (TableName, TableId, Action, LastUpdate, SID) 
                    //                                 VALUES('trn_prom_details', '". (int)$et['prom_detail_id'] . "', 'delete', '".date('Y-m-d H:i:s')."', '".$store."')";
                    //         $query =  DB::connection('mysql')->select($insert_delete_query);
                    //     }
                        
                    //     // Remove the items from the input that have already been dealt with.
                    //     $remove_key = array_search($et['barcode'], $input['added_promotion_items_text']);
                    //     if($remove_key !== FALSE){
                    //         unset($input['added_promotion_items_text'][$remove_key]);
                    //     }
                    // }
                    
                    
                    // foreach($input['added_promotion_items_text'] as $prom_item){
                        
                        // $iitemid = DB::connection('mysql')->select("Select iitemid from u".$store.".mst_item where vbarcode = '".$prom_item."'  ");
                        
                        // $item_id = $iitemid[0]->iitemid;
                        
                        // $itemDetails = $this->getItem($item_id, $store);
                        
                        // if(!empty($itemDetails)) {
                            
                        //     $disc_value = isset($discount_price_list[$item_id]) ? $discount_price_list[$item_id] : 0;
                        
                        //     if($input['promotion_type'] == 10){
                                
                        //         $new_price = $promotion_addl_discount/$input['promotion_buy_qty'];
                              
                        //         $new_price = number_format($new_price , 2);
                                
                        //         $query_insert = "INSERT INTO u".$store.".trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$store."' ";
                        //     } else {
                        //         $query_insert = "INSERT INTO u".$store.".trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$store."' ";
                        //     }
                            
                        //   $query =  DB::connection('mysql')->select($query_insert);
                            
                    //     }
                    // }
                  
        		}
            }
        }else{
            
            if($input['promotion_category'] == "Time Bound"){
               $prom_id= session()->get('prom_id') ;  
            
            $promotion = Promotion::find($prom_id);
            
            $promotion->prom_name = $req->input('promotion_name');
            $promotion->prom_code =$req->input('promotion_code');
            $promotion->category =$req->input('promotion_category');
            $promotion->period =$req->input('promotion_period');
            $promotion->start_date =$from_date;
            $promotion->end_date =$to_date;
            $promotion->from_time =$from_time;
            $promotion->to_time =$to_time;
            $promotion->qty_limit =$qty_limit;
            $promotion->prom_type_id =$req->input('promotion_type');
            $promotion->buy_qty =$req->input('promotion_buy_qty');
            $promotion->same_group =$req->input('promotion_same_itme');
            $promotion->disc_each_nth =$req->input('promotion_disc_options');
            $promotion->bill_value =$promotion_bill_value;
            $promotion->discount_type_id =$req->input('promotion_discount_type');
            $promotion->discounted_value =$req->input('promotion_discounted_value') ??'0';
           // $promotion->discounted_item =json_encode($req->input('promotion_discounted_item_text') ?? '';
            $promotion->addl_disc_cust = $promotion_addl_discount;
            $promotion->qty_limit_bal =$req->input('promotion_item_qty_limit');
            $promotion->discount_limit =$req->input('promotion_discount_limit');
            $promotion->slab_price =$req->input('promotion_slab_price');
            $promotion->mfg_discount =$req->input('mfg_discount');
            $promotion->exclude_include =$req->input('promotion_item_state');
            $promotion->mfg_prom_desc =$req->input('mfg_prom_desc');
            $promotion->mfg_buydown_desc =$req->input('mfg_buydown_desc');
            $promotion->mfg_multipack_desc =$req->input('mfg_multipack_desc');
            //$promotion->img_url =$req->input('');
            $promotion->prom_qty_limit = $req->input('promotion_item_qty_limit');
            $promotion->allow_reg_price = $allow_reg_price;
            $promotion->status =$req->input('promotion_status');
            $promotion->save();
            
            //time not updating currect 
            $update_query_time = "UPDATE trn_promotions SET LastUpdate =now() WHERE prom_id = '" . (int)$prom_id . "'";
            
            $query =  DB::connection('mysql_dynamic')->update($update_query_time); 
            }
            else{
            $prom_id= session()->get('prom_id') ;  
            
            $promotion = Promotion::find($prom_id);
            
            $promotion->prom_name = $req->input('promotion_name');
            $promotion->prom_code =$req->input('promotion_code');
            $promotion->category =$req->input('promotion_category');
            $promotion->period =$req->input('promotion_period');
            $promotion->qty_limit =$qty_limit;
            $promotion->prom_type_id =$req->input('promotion_type');
            $promotion->buy_qty =$req->input('promotion_buy_qty');
            $promotion->same_group =$req->input('promotion_same_itme');
            $promotion->disc_each_nth =$req->input('promotion_disc_options');
            $promotion->bill_value =$promotion_bill_value;
            $promotion->discount_type_id =$req->input('promotion_discount_type');
            $promotion->discounted_value =$req->input('promotion_discounted_value') ??'0';
           // $promotion->discounted_item =json_encode($req->input('promotion_discounted_item_text') ?? '';
            $promotion->addl_disc_cust = $promotion_addl_discount;
            $promotion->qty_limit_bal =$req->input('promotion_item_qty_limit');
            $promotion->discount_limit =$req->input('promotion_discount_limit');
            $promotion->slab_price =$req->input('promotion_slab_price');
            $promotion->mfg_discount =$req->input('mfg_discount');
            $promotion->exclude_include =$req->input('promotion_item_state');
            $promotion->mfg_prom_desc =$req->input('mfg_prom_desc');
            $promotion->mfg_buydown_desc =$req->input('mfg_buydown_desc');
            $promotion->mfg_multipack_desc =$req->input('mfg_multipack_desc');
            //$promotion->img_url =$req->input('');
            $promotion->prom_qty_limit = $req->input('promotion_item_qty_limit');
            $promotion->allow_reg_price = $allow_reg_price;
            $promotion->status =$req->input('promotion_status');
            $promotion->save();
            
            //time not updating currect 
            $update_query_time = "UPDATE trn_promotions SET LastUpdate =now() WHERE prom_id = '" . (int)$prom_id . "'";
            //dd($update_query_time);
            $query =  DB::connection('mysql_dynamic')->update($update_query_time);
            }
        // if(!empty($input['added_promotion_items_text']) && strlen($prom_id)>0)
        // {
        //     $buyItems = $input['added_promotion_items_text'];
            
        //     if(!empty($input['disc']))
        //     {
        //         $discount_price_list = $input['disc'];
        //     }
        
        //     foreach($buyItems as $key => $value)
        //     {
        //         //$itemDetails = Item :: where('iitemid',$value)->get()->toArray();
        //         $itemDetails = Item :: find($value);
        //       //dd($itemDetails);
        //         // dd($itemDetails);
        //         if(!empty($itemDetails)>0)
        //         {
        //             //$disc_value = $discount_price_list[$value] ? $discount_price_list[$value] : 0;
                  
        //           //  $disc_value = $value;
        //             if($input['promotion_type'] == 10){
                    
        //                 //  $new_price = $data['promotion_slab_price']/$data['promotion_buy_qty'];
                        
        //                 $new_price = $promotion_addl_discount/$input['promotion_buy_qty'];
                    
        //                 $new_price = number_format($new_price , 2);
                        
        //                 $promotion_detail = PromotionDetail::find($prom_id);
        //                 $promotion_detail->prom_id =$prom_id;
        //                 $promotion_detail->barcode =$itemDetails->vbarcode;
        //                 $promotion_detail->unit_price =$itemDetails->dunitprice;
        //                 $promotion_detail->discounted_price =$new_price;
        //                 $promotion_detail->discounted_price =$itemDetails->SID;
        //                 $promotion_detail->save();

        //                  //\DB::connection('mysql_dynamic')->insert("INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".session()->get('SID')."' ");
        //             } else {
                        
        //                 // $insert_data = "INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".session()->get('SID')."' ";
        //                 // \DB::connection('mysql_dynamic')->insert($insert_data);

        //                 $promotion_detail = PromotionDetail::find($req->input('$prom_id'));
        //                 $promotion_detail->prom_id =$prom_id;
        //                 $promotion_detail->barcode =$itemDetails->vbarcode;
        //                 $promotion_detail->unit_price =$itemDetails->dunitprice;
        //                 $promotion_detail->discounted_price =$disc_value;
        //                 $promotion_detail->SID ==$itemDetails->SID;
        //                 $promotion_detail->save();
        //             }
        //         }  
        //     }                
        // }
        
            if(!empty($input['added_promotion_items_text']) && $prom_id) {
                    		          
                if(!empty($input['disc'])) {
                    $discount_price_list = $input['disc'];
                }
                    		            
                //$existing_items = $this->db2->query(
                $sql="SELECT tpd.prom_detail_id prom_detail_id, tpd.barcode barcode, mi.iitemid item_id FROM  trn_prom_details tpd LEFT JOIN mst_item mi ON tpd.barcode=mi.vbarcode WHERE tpd.prom_id = '" . (int)$prom_id . "'";
                      	            
                $query =  DB::connection('mysql_dynamic')->select($sql);
                $existing_items = json_decode(json_encode($query), true); 
                
                $exist_item =array();
                if(count($existing_items) > 0){
                    foreach($existing_items as $v){
                        $exist_item[] = $v['barcode'];
                    }
                }
                
                foreach($input['added_promotion_items_text'] as $barcode){
                    
                    if(in_array($barcode, $exist_item)){
                        
                        $iitemid = DB::connection('mysql_dynamic')->select("Select iitemid from mst_item where vbarcode = '".$barcode."'  ");
                        
                        $item_id = $iitemid[0]->iitemid;
                        
                        $itemDetails = $this->getItem($item_id);
                        
                        if(!empty($itemDetails)) {
                            $disc_value = isset($discount_price_list[$item_id]) ? $discount_price_list[$item_id] : 0;
                            if($input['promotion_type'] == 10){
                                
                                // $new_price = $promotion_addl_discount/$input['promotion_buy_qty'];
                                $new_price = $input['promotion_slab_price']/$input['promotion_buy_qty'];
                                $new_price = number_format($new_price , 2);
                                
                                $update_query = "UPDATE trn_prom_details SET prom_id='".$prom_id."', barcode='".$itemDetails['vbarcode']."', unit_price = '".$itemDetails['dunitprice'] ."', discounted_price='".$new_price."', LastUpdate =now() , SID = '".session()->get('sid')."' WHERE `prom_id`={$prom_id} AND barcode='".$itemDetails['vbarcode']."'";
                                
                            } else {
                                $update_query = "UPDATE trn_prom_details SET prom_id ='".$prom_id."', barcode='".$itemDetails['vbarcode']."', unit_price='".$itemDetails['dunitprice']."', discounted_price='".$disc_value."', LastUpdate =now() , SID ='".session()->get('sid')."' WHERE prom_id= '".$prom_id."' AND barcode='".$itemDetails['vbarcode']."' ";
                            }
                            
                            // if(session()->get('sid') == '100763' && $prom_id == '209'){
                            //     $myfile = fopen(resource_path('views/promo_error.blade.php'), "a");
                            //     fwrite($myfile, $update_query);  
                                
                            //     fclose($myfile);
                            // }
                            $query =  DB::connection('mysql_dynamic')->update($update_query);
                        }
                        
                    } else {
                        
                        $iitemid = DB::connection('mysql_dynamic')->select("Select iitemid from mst_item where vbarcode = '".$barcode."'  ");
                        
                        $item_id = $iitemid[0]->iitemid;
                        
                        $itemDetails = $this->getItem($item_id);
                        
                        if(!empty($itemDetails)) {
                            
                            $disc_value = isset($discount_price_list[$item_id]) ? $discount_price_list[$item_id] : 0;
                            
                            if($input['promotion_type'] == 10){
                                
                                $new_price = $promotion_addl_discount/$input['promotion_buy_qty'];
                              
                                $new_price = number_format($new_price , 2);
                                
                                $query_insert = "INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', LastUpdate =now() , SID ='".session()->get('sid')."' ";
                            } else {
                                $query_insert = "INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', LastUpdate =now() , SID ='".session()->get('sid')."' ";
                            }
                            
                          $query =  DB::connection('mysql_dynamic')->insert($query_insert);
                        }
                    
                    }
                } 
                
                foreach($existing_items as $et){
                    
                    if(!in_array($et['barcode'], $input['added_promotion_items_text'])){
                        
                        $delete_query = "DELETE FROM  trn_prom_details WHERE prom_detail_id = '" . (int)$et['prom_detail_id'] . "'";
                        $query =  DB::connection('mysql_dynamic')->insert($delete_query);
                        
                        $insert_delete_query = "INSERT INTO mst_delete_table (TableName, TableId, Action, LastUpdate, SID) 
                                                VALUES('trn_prom_details', '". (int)$et['prom_detail_id'] . "', 'delete', now() , '".session()->get('sid')."')";
                        $query =  DB::connection('mysql_dynamic')->insert($insert_delete_query);
                    }
                }
    		}
		    
        }
        
        $req->session()->flash('message','You have edited Promotion Successfully!');
        return redirect('/promotion');
        
        
    }
    
    public function get_item_categories(Request $request)
    {
        $input = $request->all();

        if (isset($input['dep_code'])  && $input['dep_code'] != "") {
           // $this->load->model('administration/promotion');
          //  $categories = $this->model_administration_promotion->getCategoriesByDepartment($input['dep_code']);
            $categories = Category::Where('dept_code', $input['dep_code'])->orderBy('vcategoryname', 'ASC')->get()->toArray();
            $cat_list = "<option value='all'>All</option>";
            foreach($categories as $category){
                if(isset($category['vcategorycode'])){
                    $cat_code = $category['vcategorycode'];
                    $cat_name = $category['vcategoryname'];
                    $cat_list .= "<option value=".$cat_code.">".$cat_name."</option>";
                } 
            }

            echo $cat_list;
        }
    }
   
    public function getSelectedBuyItems(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();
        $promotion_model = new Promotion;
       // $this->load->model('administration/promotion');
        if (isset($input['buyItems'])  && !empty($input['buyItems'])) {
            $selectedBuyItems   = $input['buyItems'];
            $prom_type          = $input['prom_type'];
            $disc_type          = $input['disc_type'];
            $disc_value         = $input['disc_value'];
            $mfg_disc_value     = $input['mfg_disc_value'] ? $input['mfg_disc_value'] : 0;
            $addl_discount      = $input['addl_discount'] ? $input['addl_discount'] : 0;
            $buy_qty            = $input['buy_qty'] ? $input['buy_qty'] : 0;
            $slab_price         = array_key_exists('slab_price', $input)?$input['slab_price']:0.00;
            $slab_price         = $buy_qty > 0 ? number_format($slab_price/$buy_qty,2) : $slab_price;
        
            $items              = $promotion_model->getSelectedItems($selectedBuyItems);
            
            $items = array_map(function ($value) {
                return (array)$value;
            }, $items);
            
            $count              = count($items);
            $tableRows          = "";
            $disc_value         = $disc_value + $mfg_disc_value;
            $addlDiscountValue  = $disc_value + $addl_discount;
            
            foreach($items as $value){
                $itemId     = $value['iitemid'];
                $newPrice   = $promotion = $addlDiscount = $addlPromotion = 0 ;
                if($prom_type != 10)
                {
                    if($disc_type == 2)
                    {
                        $newPrice       = number_format($value['dunitprice'] - $disc_value , 2);
                        $addlDiscount   = number_format($value['dunitprice'] - $addlDiscountValue , 2);
                        $promotion      = number_format($disc_value,2);
                        $addlPromotion  = number_format($addlDiscountValue,2);
                    }
                    else if($disc_type == 1 && $disc_value > 0)
                    {
                        $promotion      = number_format( (( $disc_value / 100 ) * $value['dunitprice']), 2);
                        $addlPromotion  = number_format( (( $addlDiscountValue / 100 ) * $value['dunitprice']), 2);
                        
                        $newPrice       = number_format( $value['dunitprice'] - $promotion ,2 );
                        $addlDiscount   = number_format( $value['dunitprice'] - $addlPromotion ,2 );
                    }
                    
                }
                else
                {
                    if($addl_discount > 0)
                    {
                        $addl_discount  = number_format($addl_discount / $buy_qty,2);
                        $addlDiscount   = number_format($value['dunitprice'] - ($slab_price + $addl_discount) , 2);
                        $addlPromotion  = number_format(($slab_price + $addl_discount),2);
                    }
                    else
                    {
                        $addlDiscount   = number_format($value['dunitprice'] - $slab_price , 2);
                        $addlPromotion  = number_format(($slab_price),2);
                    }
                    
                }
                
                if($prom_type == 12)
                {
                    $promotion = $promotion;
                    $addlPromotion = $addlPromotion;
                    
                    // $promotion = $promotion + $value['ndiscountper'];
                    // $addlPromotion = $addlPromotion + $value['ndiscountper'];
                }
                
                
                $new_price_text = "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId." readonly></td>";
              
                if($prom_type ==  11 )
                {
                    $new_price_text = "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId."></td>";
                }
              
                if($prom_type == 11)
                {
                    $tableRows .= "<tr>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='added_promotion_items_text[]' value=".$value['vbarcode']."></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    if($prom_type == 12)
                    {
                        $tableRows .= "<td>".$value['ndiscountper']."</td>";
                    }
                       
                    if($newPrice > 0)
                    {
                        $tableRows .=  "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId."></td>";
                        $tableRows .= "<td>$addlDiscount</td>";
                    }
                    else
                    {
                        $tableRows .=  "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId." style='background-color:red'></td>";
                         $tableRows .= "<td style='background-color:red'>$addlDiscount</td>";
                    }
                   
                    
                    if($promotion < 0)
                    {
                      $tableRows .= "<td style='background-color:red;'>".$promotion."</td>";
                      $tableRows .= "<td style='background-color:red;'>".$addlPromotion."</td>";
                    }
                    else
                    {
                      $tableRows .= "<td>".$promotion."</td>";
                      $tableRows .= "<td>$addlPromotion</td>";
                    }
                    
                }
                else if($prom_type == 12)
                {
                    $tableRows .= "<tr>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='added_promotion_items_text[]' value=".$value['vbarcode']."></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    
                        $tableRows .= "<td>".$value['ndiscountper']."</td>";
                    
                       
                    if($newPrice > 0)
                    {
                        $tableRows .=  "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId." readonly style='border: 0px'></td>";
                        $tableRows .= "<td>$addlDiscount</td>";
                    }
                    else
                    {
                        $tableRows .=  "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId." readonly style='background-color:red;border: 0px'></td>";
                         $tableRows .= "<td style='background-color:red'>$addlDiscount</td>";
                    }
                   
                    
                    if($promotion < 0)
                    {
                      $tableRows .= "<td style='background-color:red;'>".$promotion."</td>";
                      $tableRows .= "<td style='background-color:red;'>".$addlPromotion."</td>";
                    }
                    else
                    {
                      $tableRows .= "<td>".$promotion."</td>";
                      $tableRows .= "<td>$addlPromotion</td>";
                    }
                    
                }
                elseif($prom_type == 10)
                {
                    // $addlDiscount = $slab_price;
                    //$slabPrice = $value['dunitprice'] - $slab_price;
                    
                    
                    $slabPrice = $slab_price;
                    $addlDiscount = $addl_discount == 0 ? $slab_price : $slab_price + $addl_discount;
                    
                    
                    $tableRows .= "<tr>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='added_promotion_items_text[]' value=".$value['vbarcode']."></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    // $tableRows .= "<td>".$value['ndiscountper']."</td>";

                    if($slabPrice < 0)
                    {
                        $tableRows .=  "<td style='background-color:red;' name=disc[".$itemId."] id=new_price_".$itemId.">$slabPrice</td>";
                        $tableRows .=  "<td style='background-color:red;'>$addlDiscount</td>";     
                    }
                    else
                    {
                        $tableRows .=  "<td name=disc[".$itemId."] id=new_price_".$itemId.">$slabPrice</td>";
                        $tableRows .= "<td>$addlDiscount</td>";
                    }
                    
                    
                    
                    // $promotion = $slab_price;
                    $promotion = $value['dunitprice'] - $slabPrice;
                    if($promotion < 0)
                    {
                        $tableRows .= "<td style='background-color:red;'>".$promotion."</td>";
                    }
                    else
                    {
                        $tableRows .= "<td>".$promotion."</td>";
                    }
                    
                    
                    
                    $addlPromotion = $addl_discount == 0 ? $value['dunitprice'] - $slab_price : $value['dunitprice'] - $addlDiscount;
                    
                    // $addlPromotion = $addlPromotion + $value['ndiscountper'];
                    if($addlPromotion < 0)
                    {
                        $tableRows .= "<td style='background-color:red;'>".$addlPromotion."</td>";
                    }
                    else
                    {
                        $tableRows .= "<td>$addlPromotion</td>";
                    }
                }
                else
                {
                    $tableRows .= "<tr>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='added_promotion_items_text[]' value=".$value['vbarcode']."></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    // $tableRows .= "<td>".$value['ndiscountper']."</td>";
                    if($newPrice > 0)
                    {
                        $tableRows .= "<td>".$newPrice."<input type='hidden' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId."></td>";
                        $tableRows .= "<td>".$addlDiscount."</td>";
                    }
                    else
                    {
                        $tableRows .= "<td>".$newPrice."<input type='hidden' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId."  style='background-color:red'></td>";
                        $tableRows .= "<td style='background-color:red'>".$addlDiscount."</td>";
                    }
                    
                    $tableRows .= "<td>$promotion</td>";
                    $tableRows .= "<td>$addlPromotion</td>";
                }
            }
            echo ($tableRows);
        }
    }
    
    public function getSavedItemsAjax(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();
        if (isset($input['buyItems'])  && !empty($input['buyItems'])) {
            $selectedBuyItems   = $input['buyItems'];
            $prom_id            = $input['prom_id'];
            $prom_type          = $input['prom_type'];
            $disc_type          = $input['disc_type'];
            $disc_value         = $input['disc_value'];
            $mfg_disc_value     = $input['mfg_disc_value'] ? $input['mfg_disc_value'] : 0;
            $addl_discount      = $input['addl_discount'] ? $input['addl_discount'] : 0;
            $slab_price         = array_key_exists('slab_price', $input)?$input['slab_price']:0.00;
            
            $buy_qty            = $input['buy_qty'];
            
            $Promotion          = new Promotion;
            $items              = $Promotion->getSavedItemsAjax($selectedBuyItems,$prom_id);
            $count              = count($items);
            $tableRows          = "";
            $disc_value         = $disc_value + $mfg_disc_value;
            $addlDiscountValue  = $disc_value + $addl_discount;
            
            $count = count($items);
            $tableRows = "";
            
            foreach($items as $value){
                $itemId     = $value['iitemid'];
                $newPrice   = $promotion = $addlDiscount = $addlPromotion = 0 ;
                if($prom_type != 10)
                {
                    if($disc_type == 2)
                    {
                        $newPrice       = number_format($value['dunitprice'] - $disc_value , 2);
                        $addlDiscount   = number_format($value['dunitprice'] - $addlDiscountValue , 2);
                        $promotion      = number_format($disc_value,2);
                        $addlPromotion  = number_format($addlDiscountValue,2);
                    }
                    else if($disc_type == 1 && $disc_value > 0)
                    {
                        $promotion      = number_format( (( $disc_value / 100 ) * $value['dunitprice']), 2);
                        $addlPromotion  = number_format( (( $addlDiscountValue / 100 ) * $value['dunitprice']), 2);
                        
                        $newPrice       = number_format( $value['dunitprice'] - $promotion ,2 );
                        $addlDiscount   = number_format( $value['dunitprice'] - $addlPromotion ,2 );
                    }
                }
                if($prom_type == 12)
                {
                    // $promotion = $promotion + $value['ndiscountper'];
                    // $addlPromotion = $addlPromotion + $value['ndiscountper'];
                    
                    $promotion = $promotion;
                    $addlPromotion = $addlPromotion;
                }
                
                $newPrice = $value['new_price'] > 0 ? $value['new_price'] : $newPrice;
                $new_price_text = "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId." readonly></td>";
              
                if($prom_type ==  11)
                {
                    $new_price_text = "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId."></td>";
                }
              
                if($prom_type == 11)
                {
                    $tableRows .= "<tr>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='added_promotion_items_text[]' value=".$value['vbarcode']."></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    if($prom_type == 12)
                    {
                        $tableRows .= "<td>".$value['ndiscountper']."</td>";
                    }
                    if($newPrice > 0)
                    {
                        $tableRows .=  "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId."></td>";
                        $tableRows .= "<td>$addlDiscount</td>";
                    }
                    else
                    {
                        $tableRows .=  "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId." style='background-color:red'></td>";
                         $tableRows .= "<td style='background-color:red'>$addlDiscount</td>";
                    }
                   
                    
                    if($promotion < 0)
                    {
                      $tableRows .= "<td style='background-color:red;'>".$promotion."</td>";
                      $tableRows .= "<td style='background-color:red;'>".$addlPromotion."</td>";
                    }
                    else
                    {
                      $tableRows .= "<td>".$promotion."</td>";
                      $tableRows .= "<td>$addlPromotion</td>";
                    }
                    
                }
                
                if($prom_type == 12)
                {
                    $tableRows .= "<tr>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='added_promotion_items_text[]' value=".$value['vbarcode']."></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    if($prom_type == 12)
                    {
                        $tableRows .= "<td>".$value['ndiscountper']."</td>";
                    }
                    if($newPrice > 0)
                    {
                        $tableRows .=  "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId." readonly style='border: 0px;background-color: transparent;'></td>";
                        $tableRows .= "<td>$addlDiscount</td>";
                    }
                    else
                    {
                        $tableRows .=  "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId." readonly style='border: 0px;background-color:red;background-color: transparent;'></td>";
                         $tableRows .= "<td style='background-color:red'>$addlDiscount</td>";
                    }
                   
                    
                    if($promotion < 0)
                    {
                      $tableRows .= "<td style='background-color:red;'>".$promotion."</td>";
                      $tableRows .= "<td style='background-color:red;'>".$addlPromotion."</td>";
                    }
                    else
                    {
                      $tableRows .= "<td>".$promotion."</td>";
                      $tableRows .= "<td>$addlPromotion</td>";
                    }
                    
                }
                elseif($prom_type == 10)
                {
                    $addlDiscount = $slab_price;
                    $tableRows .= "<tr>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='added_promotion_items_text[]' value=".$value['vbarcode']."></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    // $tableRows .= "<td>".$value['ndiscountper']."</td>";
                    
                    
                    $new_price = number_format($value['new_price'], 2);
                    $addlDiscount = number_format($addl_discount/$buy_qty , 2) + $new_price;

                    if($new_price < 0)
                    {
                        
                        $tableRows .=  "<td style='background-color:red;'>".$new_price."</td>";     
                        $tableRows .=  "<td style='background-color:red;'>".$addlDiscount."</td>";     
                    }
                    else
                    {
                        $tableRows .=  "<td>".$new_price."</td>";     
                        $tableRows .= "<td>$addlDiscount</td>";
                    }
                    
                    $promotion = ($value['dunitprice'] - $value['new_price'] );
                    $addlPromotion = ($value['dunitprice'] - $addlDiscount);
                    
                    if($promotion < 0)
                    {
                        $tableRows .= "<td style='background-color:red;'>".$promotion."</td>";
                        $tableRows .= "<td style='background-color:red;'>".$addlPromotion."</td>";
                    }
                    else
                    {
                        $tableRows .= "<td>".$promotion."</td>";
                        $tableRows .= "<td>$addlPromotion</td>";
                    }
                    
                }
                else
                {
                    $tableRows .= "<tr>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='added_promotion_items_text[]' value=".$value['vbarcode']."></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    // $tableRows .= "<td>".$value['ndiscountper']."</td>";
                    if($newPrice > 0)
                    {
                        $tableRows .= "<td>".$newPrice."<input type='hidden' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId."></td>";
                        $tableRows .= "<td>".$addlDiscount."</td>";
                    }
                    else
                    {
                        $tableRows .= "<td>".$newPrice."<input type='hidden' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId."  style='background-color:red'></td>";
                        $tableRows .= "<td style='background-color:red'>".$addlDiscount."</td>";
                    }
                    
                    
                    $tableRows .= "<td>$promotion</td>";
                    $tableRows .= "<td>$addlPromotion</td>";
                }
            }
            // return json_encode($json);
            echo ($tableRows);
        }
    }
    
    public function searchpromotion(Request $req)
    {
       
        $input = $req->all();

        $return = $datas = array();

        // $this->load->model('administration/promotion');
        // $input = $input;

        $search = $input['columns'];

        $promotion_records = [];
        $total_record_count = 0;
        
        if(!$search[1]['search']['value'] && !$search[2]['search']['value'] && !$search[3]['search']['value'] && !$search[4]['search']['value'] && !$search[5]['search']['value'])
        {
            // $promotions = $this->model_administration_promotion->getAllPromotions($input['start'],$input['length']);
            
            $start_from = $input['start'];
            $limit = $input['length'];

            $select_query = "SELECT * FROM trn_promotions ORDER BY prom_id DESC LIMIT $start_from, $limit";
            $query = \DB::connection('mysql_dynamic')->select($select_query);
        
            $count_query = "SELECT count(DISTINCT(prom_id)) count FROM trn_promotions";
            $run_count_query = \DB::connection('mysql_dynamic')->select($count_query);

            // dd($run_count_query[0]->count);
            $count_total = $run_count_query[0]->count;
            
            // $return = ['records'=>$query->rows,'total_count'=>$count_total];


            // $promotion_records = $promotions['records'];
            // $total_record_count = $promotions['total_count'];

            $promotion_records = $query;
            $total_record_count = $count_total;
        } else {
            // $promotions = $this->model_administration_promotion->searchPromotions($input['columns'],$input['start'],$input['length']);
            
            $start_from = $input['start'];
            $limit = $input['length'];

            $condition = "";
            if(isset($search[1]['search']['value']) && $search[1]['search']['value'])
            {
                $condition .= " AND prom_name LIKE  '%" . $search[1]['search']['value'] . "%'";
            }
            
            if(isset($search[2]['search']['value']) && $search[2]['search']['value'])
            {
                $condition .= " AND prom_code LIKE '%" . $search[2]['search']['value'] ."%' ";    
            }
            
            if(isset($search[3]['search']['value']) && $search[3]['search']['value'])
            {
                $condition .= " AND prom_type_id = '". $search[3]['search']['value'] ."' ";    
            }
            
            if(isset($search[4]['search']['value']) && $search[4]['search']['value'])
            {
                $condition .= " AND category = '". $search[4]['search']['value'] ."' ";    
            }
        
            if(isset($search[5]['search']['value']) && $search[5]['search']['value'])
            {
                if($search[5]['search']['value'] != 'All')
                {
                    $condition .= " AND status = '". $search[5]['search']['value'] ."' ";
                }
                    
            }
        

            $select_query = "SELECT * FROM trn_promotions WHERE prom_id != '' $condition ORDER BY prom_id DESC LIMIT $start_from, $limit";
            // $query = \DB::connection('mysql_dynamic')->select($select_query);
            $query = \DB::connection('mysql_dynamic')->select($select_query);
            
            // $run_count_query = \DB::connection('mysql_dynamic')->select("SELECT * FROM trn_promotions WHERE prom_id != '' $condition ORDER BY prom_id DESC");
            // $count_total = $run_count_query->num_rows;

            $count_query = "SELECT count(*) count FROM trn_promotions WHERE prom_id != '' $condition ORDER BY prom_id DESC";
            $run_count_query = \DB::connection('mysql_dynamic')->select($count_query);

            // dd($run_count_query[0]->count);
            $count_total = $run_count_query[0]->count;
            
            // $return = ['records'=>$query->rows,'total_count'=>$count_total];
   
            // $promotion_records = $promotions['records'];
            // $total_record_count = $promotions['total_count'];

            $promotion_records = $query;
            $total_record_count = $count_total;
        }
        

        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $total_record_count;
        $return['recordsFiltered'] = $total_record_count;
        $return['data'] = $promotion_records;
        // $promotionname = Promotion::where('prom_name','Like','%'.$req->input('prom_name').'%')
        //                             ->where('prom_code','Like','%'.$req->input('prom_code').'%')
        //                             ->where('prom_type_id',$req->input('prom_type'))
        //                             ->where('category',$req->input('prom_category'))
        //                             ->where('status',$req->input('prom_status'))->get();
        
        // return $return;

        return response()->json($return, 200);
    }

    public function get_items(Request $request) {
        //$this->load->model('administration/promotion');
        $input = $request->all();
        
        $json = array();
        if (isset($input['q'])  && $input['q'] != "") {
            $items = Promotion::getItems($input['q']);
            $count = count($items);
            foreach($items as $value){
                $json[] = ['id'=>$value['iitemid'], 'text'=>$value['vitemname']." [".$value['vbarcode']."]", 'cost'=>$value['dcostprice'], 'unit'=>$value['dunitprice'],'total_count'=>$count];
            }
        }

        return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');       
        
        // $this->response->addHeader('Content-Type: application/json');
        // return response()->json($json, 200);
    }

    public function searchPromotionitems(Request $request) {
       
        
        $input = $request->all();
        
        $return = $datas = array();
        
        $sort = "mi.LastUpdate DESC";
        if(isset($input['sort_items']) && !empty(trim($input['sort_items'])))
        {
            $sort_by = trim($input['sort_items']);
            $sort = "mi.vitemname $sort_by";
        }
        
        $show_condition = "WHERE mi.estatus='Active'";
        if(isset($input['show_items']) && !empty(trim($input['show_items'])))
        {
            $show_items = trim($input['show_items']);
            if($show_items == "All")
            {
                $show_condition = "WHERE mi.estatus !=''";
            }
            else
            {
                $show_condition = "WHERE mi.estatus='".$show_items."'";
            }
            
        }
       
        // print_r($input); die;
        
        $search_value = $input['columns'];
        
        $search_itmes = [];
        foreach($search_value as $value)
        {
            if($value["data"] == "vitemname")
            {
                $search_items['vitemname'] = $value['search']['value'];
            }
            else if($value["data"] == "vbarcode")
            {
                $search_items['vbarcode'] = $value['search']['value'];
            }
            else if($value["data"] == "dunitprice")
            {
                $search_items['dunitprice'] = $value['search']['value'];
                
            }
            else if($value["data"] == "vcategoryname")
            {
                $search_items['vcategoryname'] = $value['search']['value'];
            }
            else if($value["data"] == "vdepartmentname")
            {
                $search_items['vdepartmentname'] = $value['search']['value'];
            }
            else if($value["data"] == "vcategoryname")
            {
                $search_items['vcategoryname'] = $value['search']['value'];
            }
            else if($value["data"] == "subcat_name")
            {
                $search_items['subcat_name'] = $value['search']['value'];
            }
            else if($value["data"] == "vcompanyname")
            {
                $search_items['vcompanyname'] = $value['search']['value'];
            }
            else if($value["data"] == "mfr_name")
            {
                $search_items['mfr_name'] = $value['search']['value'];
            }
            else if($value["data"] == "vitemtype")
            {
                $search_items['vitemtype'] = $value['search']['value'];
            }
            else if($value["data"] == "vunitname")
            {
                $search_items['vunitname'] = $value['search']['value'];
            }
            else if($value["data"] == "vsize")
            {
                $search_items['vsize'] = $value['search']['value'];
            }
        }
        
        //if(empty(trim($search_items['vitemname'])) && empty(trim($search_items['vbarcode'])) && empty(trim($search_items['dunitprice'])) && empty(trim($search_items['vcategoryname'])) &&  empty(trim($search_items['vdepartmentname'])) && empty(trim($search_items['subcat_name'])) && empty(trim($search_items['vcompanyname'])) && empty(trim($search_items['mfr_name'])) && empty(trim($search_items['vitemtype'])) && empty(trim($search_items['vunitname'])) && empty(trim($search_items['vsize'])) )
        if(empty(trim($search_items['vitemname'])) && empty(trim($search_items['vbarcode'])) && empty(trim($search_items['dunitprice'])) && empty(trim($search_items['vcategoryname'])) &&  empty(trim($search_items['vdepartmentname'])) && empty(trim($search_items['subcat_name'])) && empty(trim($search_items['vunitname'])) && empty(trim($search_items['vsize'])) )
        {
            $limit = 20;
            

            $start_from = ($input['start']);
            
            $offset = $input['start']+$input['length'];
            $show_condition = "WHERE mi.iitemid=0";
            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.*, md.vdepartmentname, mc.vcategoryname,msc.subcat_name, msupp.vcompanyname, mi.manufacturer_id, mfr.mfr_name, unit.vunitname, mi.vunitcode, mi.dunitprice, (SELECT prom_id FROM trn_prom_details where barcode = mi.vbarcode limit 1) as prom_id FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) LEFT JOIN mst_manufacturer as mfr ON(mi.manufacturer_id = mfr.mfr_id) LEFT JOIN mst_unit as unit ON(mi.vunitcode = unit.vunitcode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) $show_condition ORDER BY $sort LIMIT ". $start_from.", ".$limit;
            
            // $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.*, md.vdepartmentname, mc.vcategoryname,msc.subcat_name, msupp.vcompanyname, mi.manufacturer_id, mfr.mfr_name, (SELECT prom_id FROM trn_prom_details where barcode = mi.vbarcode limit 1) as prom_id FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) LEFT JOIN mst_manufacturer as mfr ON(mi.manufacturer_id = mfr.mfr_id) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) $show_condition ORDER BY $sort";
     
           
            
            $query = \DB::connection('mysql_dynamic')->select($select_query);
            

            
            $count_query = "SELECT DISTINCT(iitemid) FROM mst_item mi $show_condition";
            
            $run_count_query = \DB::connection('mysql_dynamic')->select($count_query);
            
            $count_records = $count_total = $run_count_query;
            
        

                // $temp = array();
                // $temp['iitemid'] = "";
                // $temp['vitemname'] = "";
                // $temp['vitemtype'] = "";
                // $temp['vbarcode'] = "";
                // $temp['vdepartmentname'] = "";
                // $temp['vcategoryname'] = "";
                // $temp['subcat'] ="";
                // $temp['subcat_name'] = "";
                // $temp['nunitcost'] = "";
                // $temp['vsize'] = "";
                // $temp['vsuppliercode'] = "";
                // $temp['vcompanyname'] = "";
                // $temp['manufacturer_id'] = "";
                // $temp['last_costprice'] = "";
                // $temp['nsaleprice'] = "";
                // $temp['iqtyonhand'] = "";
                // $temp['mfr_name'] = "";
                // $temp['prom_id'] = "";
        
                // $return = [];
                // $return['draw'] = 1;
                // $return['recordsTotal'] = 1;
                // $return['recordsFiltered'] = 1;
                // $return['data'] = [];
        
                // $this->response->addHeader('Content-Type: application/json');
                // $this->response->setOutput(json_encode($return));exit;
        }
        else
        {
           $limit = 20;
            
            $start_from = ($input['start']);
            
            $offset = $input['start']+$input['length']; 
            $condition = "";
            if(isset($search_items['vitemname']) && !empty(trim($search_items['vitemname']))){
                $search = ($search_items['vitemname']);
                $condition .= " AND mi.vitemname LIKE  '%" . $search . "%'";
            }
            
            if(isset($search_items['vbarcode']) && !empty(trim($search_items['vbarcode']))){
                $search = ($search_items['vbarcode']);
                $condition .= " AND mi.vbarcode LIKE  '%" . $search . "%'";
            }
            
            if(isset($search_items['dunitprice']) && !empty(trim($search_items['dunitprice']))){
                $search = ($search_items['dunitprice']); 
                $search_conditions =explode("|",$search);
                
                if($search_conditions[0] == 'greater' && isset($search_conditions[1])){
                    $condition .= " AND mi.dunitprice > $search_conditions[1] ";
                    
                }elseif($search_conditions[0] == 'less' && isset($search_conditions[1])){
                    $condition .= " AND mi.dunitprice < $search_conditions[1] ";
                    
                }elseif($search_conditions[0] == 'equal' && isset($search_conditions[1])){
                    $condition .= " AND mi.dunitprice = $search_conditions[1] ";
                    
                }elseif($search_conditions[0] == 'between' && isset($search_conditions[1]) && isset($search_conditions[2])){
                    
                    $condition .= " AND mi.dunitprice BETWEEN $search_conditions[1] AND $search_conditions[2] ";
                    
                }
            }
            
            // if(isset($search_items['nunitcost']) && !empty(trim($search_items['nunitcost']))){
            //     $search = ($search_items['nunitcost']);
            //     $condition .= " AND mi.nunitcost LIKE  '%" . $search . "%'";
            // }
            
            // if(isset($search_items['vsize']) && !empty(trim($search_items['vsize']))){
            //     $search = ($search_items['vsize']);
            //     $condition .= " AND mi.vsize LIKE  '%" . $search . "%'";
            // }
            
            if(isset($search_items['vdepartmentname']) && !empty(trim($search_items['vdepartmentname']))){
                $search = ($search_items['vdepartmentname']);
                if($search != 'all')
                {
                    $condition .= " AND mi.vdepcode ='".$search."'";
                }
            }
            
            if(isset($search_items['vcategoryname']) && !empty(trim($search_items['vcategoryname']))){
                $search = ($search_items['vcategoryname']);
                if($search_items['vdepartmentname'] != 'all' && $search != 'all')
                {
                    $condition .= " AND mi.vcategorycode ='".$search."'";
                }
                
            }
            
            if(isset($search_items['subcat_name']) && !empty($search_items['subcat_name'])){
                $search = ($search_items['subcat_name']);
                if($search_items['vcategoryname'] != 'all' && $search_items['vdepartmentname'] != 'all' && $search != 'all')
                {
                    $condition .= " AND mi.subcat_id ='".$search."'";
                }
                
            }
            
            if(isset($search_items['vcompanyname']) && !empty($search_items['vcompanyname'])){
                $search = ($search_items['vcompanyname']);
                if($search != 'all')
                {
                    $condition .= " AND mi.vsuppliercode ='".$search."'";
                }
            }
            
            if(isset($search_items['mfr_name']) && !empty($search_items['mfr_name'])){
                $search = ($search_items['mfr_name']);
                if($search != 'all')
                {
                    $condition .= " AND mi.manufacturer_id ='".$search."'";
                }
            }
            
            if(isset($search_items['vitemtype']) && !empty($search_items['vitemtype'])){
                $search = ($search_items['vitemtype']);
                if($search != 'all')
                {
                    $condition .= " AND mi.vitemtype ='".$search."'";
                }
            }
            
            if(isset($search_items['vunitname']) && !empty($search_items['vunitname'])){
                $search = ($search_items['vunitname']);
                if($search != 'all')
                {
                    $condition .= " AND mi.vunitcode ='".$search."'";
                }
            }
            
            if(isset($search_items['vsize']) && !empty($search_items['vsize'])){
                $search = ($search_items['vsize']);
                if($search != 'all')
                {
                    $condition .= " AND mi.vsize ='".$search."'";
                }
            }
            
            
            // echo $condition;exit;
            
            $sid = "u".session()->get('sid');
            
            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.vitemtype,mi.npack,mi.new_costprice, md.vdepartmentname, mc.vcategoryname,msc.subcat_name,msupp.vcompanyname, mi.nsaleprice, mi.iqtyonhand, mi.LastUpdate, mi.subcat_id,mi.manufacturer_id,mfr.mfr_name,unit.vunitname, mi.vunitcode, mi.dunitprice, (SELECT prom_id FROM ".$sid.".trn_prom_details where barcode = mi.vbarcode limit 1) as prom_id, mi.* FROM ".$sid.".mst_item as mi LEFT JOIN ".$sid.".mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN ".$sid.".mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN ".$sid.".mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) LEFT JOIN ".$sid.".mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) LEFT JOIN ".$sid.".mst_manufacturer as mfr ON(mi.manufacturer_id = mfr.mfr_id) LEFT JOIN mst_unit as unit ON(mi.vunitcode = unit.vunitcode) LEFT JOIN ".$sid.".mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN ".$sid.".mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) $show_condition "." $condition ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
            
            // $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.vitemtype, md.vdepartmentname, mc.vcategoryname,msc.subcat_name,msupp.vcompanyname, mi.nsaleprice, mi.iqtyonhand, mi.LastUpdate, mi.subcat_id,mi.manufacturer_id,mfr.mfr_name, (SELECT prom_id FROM trn_prom_details where barcode = mi.vbarcode limit 1) as prom_id, mi.* FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) LEFT JOIN mst_manufacturer as mfr ON(mi.manufacturer_id = mfr.mfr_id) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) $show_condition "." $condition ORDER BY $sort";
            // echo $select_query;exit;
            
            $query = \DB::connection('mysql_dynamic')->select($select_query);
            
             //$count_select_query = "SELECT DISTINCT(mi.iitemid), COUNT(*) as count ,mi.vbarcode,mi.vitemname, mi.vitemtype,mi.npack,mi.new_costprice, md.vdepartmentname, mc.vcategoryname,msc.subcat_name,msupp.vcompanyname, mi.nsaleprice, mi.iqtyonhand, mi.LastUpdate, mi.subcat_id,mi.manufacturer_id,mfr.mfr_name,unit.vunitname, mi.vunitcode, mi.dunitprice, (SELECT prom_id FROM ".$sid.".trn_prom_details where barcode = mi.vbarcode limit 1) as prom_id, mi.* FROM ".$sid.".mst_item as mi LEFT JOIN ".$sid.".mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN ".$sid.".mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN ".$sid.".mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) LEFT JOIN ".$sid.".mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) LEFT JOIN ".$sid.".mst_manufacturer as mfr ON(mi.manufacturer_id = mfr.mfr_id) LEFT JOIN mst_unit as unit ON(mi.vunitcode = unit.vunitcode) LEFT JOIN ".$sid.".mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN ".$sid.".mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) $show_condition "." $condition ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
            
            $count_select_query = "SELECT COUNT(DISTINCT mi.iitemid) as count FROM ".$sid.".mst_item as mi
            LEFT JOIN ".$sid.".mst_department as md ON(mi.vdepcode = md.vdepcode)
            LEFT JOIN ".$sid.".mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
            LEFT JOIN ".$sid.".mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
            LEFT JOIN ".$sid.".mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) $show_condition "." $condition";
            // dd($count_select_query);
            $count_query1 = \DB::connection('mysql_dynamic')->select($count_select_query);
            //  dd($count_query1);
            $count_records = $count_total = (int)$count_query1[0]->count;
                
        }
        
        $search = $input['search']['value'];
        


//        $this->load->model('api/items');
        // $itemListings = $this->model_api_items->getItemListings();
        // if(!empty($itemListings['variablevalue']) && count(json_decode($itemListings['variablevalue'])) > 0){
        //     $itemListings = json_decode($itemListings['variablevalue']);
        // }

        $itemListings = WebAdminSetting::where('variablename', 'ItemListing')->get('variablevalue')->toArray();
        
        $itemListings = array();
    
        // if(!empty($itemListings[0]['variablevalue']) && count(json_decode($itemListings[0]['variablevalue'])) > 0){
        if(!empty($itemListings[0]['variablevalue'])){
            $itemListings = json_decode($itemListings[0]['variablevalue']);
        }
        
        $query = array_map(function ($value) {
            return (array)$value;
        }, $query);

        if(count($query) > 0){
            foreach ($query as $key => $value) {

                $temp = array();
                $temp['iitemid'] = $value['iitemid'];
                $temp['vitemname'] = $value['vitemname'];
                $temp['vitemtype'] = $value['vitemtype'];
                $temp['vbarcode'] = $value['vbarcode'];
                // if(count($itemListings) > 0){
                //     foreach($itemListings as $m => $v){
                //         if($m == 'vdepcode'){
                //             $temp['vdepcode'] = $value['vdepcode'];
                //         }else if($m == 'vcategorycode'){
                //             $temp['vcategorycode'] = $value['vdepcode'];
                //         }else if($m == 'vunitcode'){
                //           $temp['vunitcode'] = $value['vdepcode'];
                //         }else if($m == 'vsuppliercode'){
                //             $temp['vsuppliercode'] = $value['vsuppliercode'];
                //         }else if($m == 'stationid'){
                //             $temp['stationid'] = $value['stationid'];
                //         }else if($m == 'aisleid'){
                //             $temp['aisleid'] = $value['aisleid'];
                //         }else if($m == 'shelfid'){
                //             $temp['shelfid'] = $value['shelfid'];
                //         }else if($m == 'shelvingid'){
                //             $temp['shelvingid'] = $value['shelvingid'];
                //         }else{
                //             $temp[$m] = $value[$m];
                //         }
                //     }
                // }
                $temp['vdepartmentname'] = $value['vdepartmentname'];
                $temp['vcategoryname'] = $value['vcategoryname'];
                $temp['subcat'] = $value['subcat_id'];
                $temp['subcat_name'] = $value['subcat_name'];
                $temp['nunitcost'] = $value['nunitcost'];
                $temp['vunitname'] = $value['vunitname'];
                $temp['vsize'] = $value['vsize'];
                $temp['vsuppliercode'] = $value['vsuppliercode'];
                $temp['vcompanyname'] = $value['vcompanyname'];
                $temp['manufacturer_id'] = $value['manufacturer_id'];
                $temp['last_costprice'] = $value['last_costprice'];
                $temp['nsaleprice'] = $value['nsaleprice'];
                $temp['iqtyonhand'] = $value['iqtyonhand'];
                $temp['mfr_name'] = $value['mfr_name'];
                $temp['prom_id'] = $value['prom_id'];
                $temp['dunitprice'] = $value['dunitprice'];
               
                $temp['dcostprice']=($value['new_costprice']*$value['npack']);
              
                
                $datas[] = $temp;
            }
        }
        
        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $count_total;
        $return['recordsFiltered'] = $count_records;
        $return['data'] = $datas;

        return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
        
    }
    
    public function get_department_items(Request $request) {
        $this->load->model('administration/promotion');
        $json = array();
        if (isset($input['dep_code'])  && $input['dep_code'] != "") {
            $items = $this->model_administration_promotion->getDepartmentItems($input['dep_code']);
            $count = count($items);
            foreach($items as $value){
                if(isset($value['iitemid'])){
                    $json[] = ['id'=>$value['iitemid'], 'text'=>$value['vitemname']." [".$value['vbarcode']."]", 'cost'=>$value['dcostprice'], 'unit'=>$value['dunitprice'],'total_count'=>$count];
                } 
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function get_category_items(Request $request) {
        $this->load->model('administration/promotion');
        $json = array();
        if (isset($input['dep_code'])  && $input['dep_code'] != "" && isset($input['cat_code'])  && $input['cat_code'] != "") {
            $items = $this->model_administration_promotion->getCategoryItems($input['dep_code'],$input['cat_code']);
            $count = count($items);
            foreach($items as $value){
                if(isset($value['iitemid'])){
                    $json[] = ['id'=>$value['iitemid'], 'text'=>$value['vitemname']." [".$value['vbarcode']."]",'cost'=>$value['dcostprice'], 'unit'=>$value['dunitprice'],'total_count'=>$count];
                } 
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function get_sub_category_items(Request $request) {
        $this->load->model('administration/promotion');
        $json = array();
        if (isset($input['dep_code'])  && $input['dep_code'] != "" && isset($input['cat_code'])  && $input['cat_code'] != "" && isset($input['sub_cat_id'])  && $input['sub_cat_id'] != "") {
            $items = $this->model_administration_promotion->getSubCategoryItems($input['dep_code'],$input['cat_code'],$input['sub_cat_id']);
            $count = count($items);
            foreach($items as $value){
                if(isset($value['iitemid'])){
                    $json[] = ['id'=>$value['iitemid'], 'text'=>$value['vitemname']." [".$value['vbarcode']."]",'cost'=>$value['dcostprice'], 'unit'=>$value['dunitprice'],'total_count'=>$count];
                } 
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function get_group_items() {
        $this->load->model('administration/promotion');
        $json = array();
        if (isset($input['group_id'])  && $input['group_id'] != "") {
            $items = $this->model_administration_promotion->getGroupItems($input['group_id']);
            $count = count($items);
            foreach($items as $value){
                if(isset($value['iitemid'])){
                    $json[] = ['id'=>$value['iitemid'], 'text'=>$value['vitemname']." [".$value['vbarcode']."]",'cost'=>$value['dcostprice'], 'unit'=>$value['dunitprice'],'total_count'=>$count];
                } 
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    
    public function get_sub_categories_url(Request $request)
    {
        $input = $request->all();
        
        if (isset($input['cat_code'])  && $input['cat_code'] != "") {
            
           // $this->load->model('administration/promotion');
            $subCategories = SubCategory::where('cat_id',$input['cat_code'])->orderBy('subcat_name', 'ASC')->get()->toArray();
            
            $subcat_list = "<option value='all'>All</option>";
           // print_r($subCategories);exit;
            foreach($subCategories as $subCategory){
                if(isset($subCategory['subcat_id'])){
                    $sub_cat_id     = $subCategory['subcat_id'];
                    $sub_cat_name   = $subCategory['subcat_name'];
                    $subcat_list   .= "<option value=".$sub_cat_id.">".$sub_cat_name."</option>";
                } 
            }
            echo $subcat_list;
        }
    }
    
    public function get_department_categories()
    {
        
        if (isset($input['dep_code'])  && $input['dep_code'] != "") {
            $this->load->model('administration/promotion');
            $categories = $this->model_administration_promotion->getCategoriesByDepartment($input['dep_code']);
            $cat_list = "<option value='all'>All</option>";
            foreach($categories as $category){
                if(isset($category['vcategorycode'])){
                    $cat_code = $category['vcategorycode'];
                    $cat_name = $category['vcategoryname'];
                    $cat_list .= "<option value=".$cat_code.">".$cat_name."</option>";
                } 
            }
            echo $cat_list;
        }
    }
    
    public function get_sub_categories()
    {
        if (isset($input['cat_id'])  && $input['cat_id'] != "") {
            $this->load->model('administration/promotion');
            
            $subcategories = $this->model_administration_promotion->getSubCategories($input['cat_id']);
            $subcat_list = "<option value='all'>All</option>";
            foreach($subcategories as $subcategory){
                if(isset($subcategory['subcat_name'])){
                    $sub_cat_id = $category['subcat_id'];
                    $sub_cat_name = $category['subcat_name'];
                    $subcat_list .= "<option value=".$sub_cat_id.">".$sub_cat_name."</option>";
                } 
            }
            echo $subcat_list;
        }
    }
    
    protected function validateForm($input) {
      
       $promotion_model = new Promotion;
        
        if(isset($input['promotion_code']) && ($input['promotion_code'] != ""))
        {
            $promotion_info = $promotion_model->getPromotionCode($input['promotion_code']);
            if (!empty($promotion_info)) {
                $this->error['warning'] = 'Promotion Code Exists';
            }
        }
        
        if ((strlen($input['promotion_name']) < 1)) {
            $this->error['promotion_name'] = 'Promotion Name is Required';
        }
        
        if ((strlen($input['promotion_discount_type']) < 1)) {
            $this->error['promotion_discount_type'] = 'Discount Type is Required';
        }

        if ((strlen($input['promotion_code']) < 1) ) {
            $this->error['promotion_code'] = 'Promotion Code is Required';
        }

        if ((strlen($input['promotion_type']) < 1)) {
            $this->error['promotion_type'] = 'Promotion Type is Required';
        }

        if ((strlen($input['promotion_category']) < 1)) {
            $this->error['promotion_category'] = 'Promotion Category is Required';
        }

        if ((strlen($input['promotion_period']) < 1)) {
            $this->error['promotion_period'] = 'Promotion Period is Required';
        }

        if($input['promotion_type'])
        {
            $promotionType = $input['promotion_type'];
            if( $promotionType == 1 || $promotionType == 2 || $promotionType == 3 || $promotionType == 4 || $promotionType == 9 || $promotionType == 12)
            {
                if ((strlen($input['promotion_buy_qty']) < 1)) {
                    $this->error['promotion_buy_qty'] = 'Buy Qty is Required';
                }
            }
        }
        
        if (empty($input['added_promotion_items_text'])) {
            $this->error['warning'] = 'Promotion Items is Required';
        }
        
        if ($input['promotion_category'] == "Time Bound") {
            if ((strlen($input['promotion_from_date']) < 1)) {
                $this->error['promotion_from_date'] = 'From Date is Required';
            }
            if ((strlen($input['promotion_to_date']) < 1)) {
                $this->error['promotion_to_date'] = 'To Date is Required';
            }
        }
        
        if ($input['promotion_period'] == "Daily") {
            if ((strlen($input['promotion_from_time']) < 1)) {
                $this->error['promotion_from_time'] = 'From time is Required';
            }
            if ((strlen($input['promotion_to_time']) < 1)) {
                $this->error['promotion_to_time'] = 'To time is Required';
            }
        }
        
        if (  ($input['promotion_type'] == "3" || $input['promotion_type'] == "2") && $input['promotion_same_itme'] == "Group Item" && $input['promotion_type'] != "5" && $input['promotion_type'] != "11") {
            if (empty($input['promotion_discounted_item_text'])) {
                $this->error['warning'] = 'Discounted Item is Required';
            }
        }
        
        if( $input['promotion_type'] == "4" )
        {
            unset($this->error['warning']) ;
        }
        
        if ($input['promotion_type'] != "10" && $input['promotion_type'] != "11" && $input['promotion_type'] != "12") {
            if ((strlen($input['promotion_discounted_value']) < 1)) {
                $this->error['promotion_discounted_value'] = 'Discounted Value is Required';
            }
        }
        
//      if ((strlen($input['promotion_discount_limit']) < 1)) {
//          $this->error['promotion_discount_limit'] = 'Discount Limit is Required';
//      }
        
        if ($input['promotion_category'] == "Stock Bound") {
            if ((strlen($input['promotion_item_qty_limit']) < 1)) {
                $this->error['promotion_item_qty_limit'] = 'Qty Limit is Required';
            }
        }
        
    
        
        if ($input['promotion_type'] == "10") {
            if ((strlen($input['promotion_slab_price']) < 1)) {
                $this->error['promotion_slab_price'] = 'Slab Price is Required';
            }
        }
        
        if (  ($input['promotion_type'] == "4") ) {
            if (empty($input['promotion_customers'])) {
                $this->error['promotion_customers'] = 'Customers is Required';
            }
        }
    //  echo "<pre>";print_r($this->error);exit;
        return !$this->error;
        
    }
    
    
    public function insertPromotion ($data) {
        $query = "";
        if($input['promotion_category'] == "Open Ended" || $input['promotion_category'] == "Stock Bound")
        {
            $from_date = date("Y-m-d");
            $to_date = NULL;
            
        }
        elseif($input['promotion_category'] == "Time Bound")
        {
            $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_from_date']))) ;
            $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_to_date']))) ;
            $query .= ", end_date = '$to_date' ";
        }
        else
        {
            $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_from_date']))) ;
            $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_to_date']))) ;
            $query .= ", end_date = '$to_date' ";
        }
        
        
        $timeQuery = "";
        if($input['promotion_category'] == "Time Bound")
        {
            $timeQuery .= ", from_time = '".date("H:i:s",strtotime($data['promotion_from_time']))."' ";
            $timeQuery .= ", to_time = '".date("H:i:s",strtotime($data['promotion_to_time']))."' ";
        }
        
        $this->load->model('administration/items');
        
        if($data['promotion_type'] == 10){
            $discount_type_id = 2;
            $promotion_addl_discount = isset($data['promotion_addl_discount'])?($data['promotion_addl_discount']):($data['promotion_slab_price']);
        } else {
            $discount_type_id = ($data['promotion_discount_type']);
            $promotion_addl_discount = isset($data['promotion_addl_discount'])?($data['promotion_addl_discount']):0;
        }
        
        $same_group = $data['promotion_type'] == 12?"Group Item":($data['promotion_same_itme']);
        
        $qty_limit = ($data['qty_limit']);
        
        $qty_limit = (!is_nan($qty_limit) && ($qty_limit > 0))?$qty_limit:0;

        $allow_reg_price = $qty_limit > 0?($data['allow_reg_price']):'Y';
        
        /*$sql = "INSERT INTO trn_promotions 
                        SET prom_name = '" . ($data['promotion_name']) . "', 
                            prom_code = '" . ($data['promotion_code']) . "', 
                            category = '" . ($data['promotion_category']) . "', 
                            period = '" . ($data['promotion_period']) . "', 
                            start_date = '".$from_date."'";
                        $sql .= $query.$timeQuery;
                        $sql .=", qty_limit = '" . ($data['promotion_item_qty_limit']) . "', 
                            prom_type_id = '" . ($data['promotion_type']) . "',
                            buy_qty = '" . ($data['promotion_buy_qty']) . "', 
                            same_group = '" . $same_group . "', 
                            disc_each_nth = '" . ($data['promotion_disc_options']) . "', 
                            bill_value = '" . ($data['promotion_bill_value']) . "', 
                            discount_type_id = '" . $discount_type_id . "', 
                            discounted_value = '" . ($data['promotion_discounted_value']) . "', 
                            addl_disc_cust = '" . $promotion_addl_discount . "', 
                            discounted_item = '" . (json_encode($data['promotion_discounted_item_text'])) . "', 
                            qty_limit_bal = '" . ($data['promotion_item_qty_limit']) . "', 
                            discount_limit = '" . ($data['promotion_discount_limit']) . "', 
                            exclude_include = '" . ($data['promotion_item_state']) . "', 
                            slab_price = '" . ($data['promotion_slab_price']) . "', 
                            mfg_prom_desc = '" . ($data['mfg_prom_desc']) . "', 
                            mfg_buydown_desc = '" . ($data['mfg_buydown_desc']) . "', 
                            mfg_multipack_desc = '" . ($data['mfg_multipack_desc']) . "', 
                            mfg_discount = '" . ($data['mfg_discount']) . "', 
                            img_url = '', 
                            status = '" . ($data['promotion_status']) . "' ";*/
                            

        $sql = "INSERT INTO trn_promotions 
                        SET prom_name = '" . ($data['promotion_name']) . "', 
                            prom_code = '" . ($data['promotion_code']) . "', 
                            category = '" . ($data['promotion_category']) . "', 
                            period = '" . ($data['promotion_period']) . "', 
                            start_date = '".$from_date."'";
                        $sql .= $query.$timeQuery;
                        $sql .=", qty_limit = '" . ($data['promotion_item_qty_limit']) . "', 
                            prom_type_id = '" . ($data['promotion_type']) . "',
                            buy_qty = '" . ($data['promotion_buy_qty']) . "', 
                            same_group = '" . $same_group . "', 
                            disc_each_nth = '" . ($data['promotion_disc_options']) . "', 
                            bill_value = '" . ($data['promotion_bill_value']) . "', 
                            discount_type_id = '" . $discount_type_id . "', 
                            discounted_value = '" . ($data['promotion_discounted_value']) . "', 
                            addl_disc_cust = '" . $promotion_addl_discount . "', 
                            discounted_item = '" . (json_encode($data['promotion_discounted_item_text'])) . "', 
                            qty_limit_bal = '" . ($data['promotion_item_qty_limit']) . "', 
                            discount_limit = '" . ($data['promotion_discount_limit']) . "', 
                            exclude_include = '" . ($data['promotion_item_state']) . "', 
                            slab_price = '" . ($data['promotion_slab_price']) . "', 
                            mfg_prom_desc = '" . ($data['mfg_prom_desc']) . "', 
                            mfg_buydown_desc = '" . ($data['mfg_buydown_desc']) . "', 
                            mfg_multipack_desc = '" . ($data['mfg_multipack_desc']) . "', 
                            mfg_discount = '" . ($data['mfg_discount']) . "', 
                            img_url = '', 
                            prom_qty_limit = '" . $qty_limit . "',
                            allow_reg_price = '" . $allow_reg_price . "',
                            status = '" . ($data['promotion_status']) . "' ";
                            
                            
                // 			echo $sql;exit;
    //     $sql = "INSERT INTO trn_promotions 
                // 		SET prom_name = '" . ($data['promotion_name']) . "', 
                // 			prom_code = '" . ($data['promotion_code']) . "', 
                // 			category = '" . ($data['promotion_category']) . "', 
                // 			period = '" . ($data['promotion_period']) . "', 
                // 			start_date = '".$from_date."',
                // 			end_date = '".$to_date."', 
                // 			from_time = '" . date("0000-00-00 H:i:s",strtotime($data['promotion_from_time'])) . "',  
                // 			to_time = '" . date("0000-00-00 H:i:s",strtotime($data['promotion_to_time'])) . "',  
                // 			qty_limit = '" . ($data['promotion_item_qty_limit']) . "', 
                // 			prom_type_id = '" . ($data['promotion_type']) . "',
                // 			buy_qty = '" . ($data['promotion_buy_qty']) . "', 
                // 			same_group = '" . ($data['promotion_same_itme']) . "', 
                // 			bill_value = '" . ($data['promotion_bill_value']) . "', 
                // 			discount_type_id = '" . ($data['promotion_discount_type']) . "', 
                // 			discounted_value = '" . ($data['promotion_discounted_value']) . "', 
                // 			discounted_item = '" . (json_encode($data['promotion_discounted_item'])) . "', 
                // 			discount_limit = '" . ($data['promotion_discount_limit']) . "', 
                // 			exclude_include = '" . ($data['promotion_item_state']) . "', 
                // 			slab_price = '" . ($data['promotion_slab_price']) . "', 
                // 			img_url = '', 
                // 			status = '" . ($data['promotion_status']) . "'";
                            
        \DB::connection('mysql_dynamic')->insert($sql);

        $prom_id = $this->db2->getLastId();
        
        if(!empty($data['added_promotion_items_text']) && $prom_id)
        {
            $buyItems = $data['added_promotion_items_text'];
            
            if(!empty($data['disc']))
            {
                $discount_price_list = $data['disc'];
            }
            
            foreach($buyItems as $key => $value)
            {
                $itemDetails = $this->model_administration_items->getItem($value);
                if(!empty($itemDetails))
                {
                    $disc_value = isset($discount_price_list[$value]) ? $discount_price_list[$value] : 0;
                    
                    //  $disc_value = $value;
                    
                    if($data['promotion_type'] == 10){
                        
                        
                        //  $new_price = $data['promotion_slab_price']/$data['promotion_buy_qty'];
                        
                        $new_price = $promotion_addl_discount/$data['promotion_buy_qty'];
                        
                        $new_price = number_format($new_price , 2);
                        \DB::connection('mysql_dynamic')->insert("INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', 
                        SID ='".$itemDetails['SID']."' ");
                    } else {
                        
                        \DB::connection('mysql_dynamic')->insert("INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', 
                        barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', 
                        discounted_price ='".$disc_value."', SID ='".$itemDetails['SID']."' ");
                    }
                    
                    
                    
                }
            }
            
        }
        
        if(!empty($data['promotion_customers']) && $prom_id)
        {
            $customers = $data['promotion_customers'];
            
            foreach($customers as $value)
            {
                if(!empty($value))
                {
                    \DB::connection('mysql_dynamic')->insert("INSERT INTO trn_prom_customers SET prom_id ='".$prom_id."', cust_id ='".$value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$itemDetails['SID']."' ");
                }
            }
            
        }
        
        return $prom_id;
    }
    	
    
    // public function deletePromotions(Request $req) {
    //     $input = $req->all();
        
    //     foreach ($input as $del) {
    //         Promotion::find($del)->delete();
    //         // PromotionDetail::where('prom_detail_id',$del)->delete();
            
    //          \DB::connection('mysql_dynamic')->select("DELETE FROM  trn_prom_details WHERE prom_id = '" . (int)$del . "'");
            
    //          $insert_delete_trn_promotions = "INSERT INTO mst_delete_table SET  TableName = 'trn_promotions',`Action` = 'delete',`TableId` = '" . (int)$del . "',SID = '" . (int)(session()->get('sid'))."'";
    //          $query =  DB::connection('mysql_dynamic')->select($insert_delete_trn_promotions);
             
    //          $insert_delete_trn_prom_details = "INSERT INTO mst_delete_table SET  TableName = 'trn_prom_details',`Action` = 'delete',`TableId` = '" . (int)$del . "',SID = '" . (int)(session()->get('sid'))."'";
    //          $query =  DB::connection('mysql_dynamic')->select($insert_delete_trn_prom_details);
    //     }

    //     Session::flash('message','Promotion Deleted Successfully!');

    //     $data['success'] = 'Promotion Deleted Successfully!';
    //     return response(json_encode($data), 200)
    //         ->header('Content-Type', 'application/json');

    // }
    
    public function deletePromotions(Request $req) {
        $input = $req->all();
        
        if(isset($input['stores_hq'])){
            foreach ($input['data'] as $del) {
                $promocode = DB::connection("mysql_dynamic")->select("SELECT prom_code FROM trn_promotions where prom_id =  '".$del."' ");
                foreach($promocode as $pcode){
                    $prom_code  = $pcode->prom_code;
                }
                foreach($input['stores_hq'] as $store){
                    $promoId =  DB::connection("mysql")->select("SELECT prom_id FROM u".$store.".trn_promotions where prom_code =  '".$prom_code."' "); 
                    foreach($promoId as $p_id){
                        
                        $promo_id = $p_id->prom_id;
                        
                        $select_query = "SELECT * FROM u".$store.".trn_prom_details WHERE  prom_id = '" . (int)$promo_id . "'";
                        $query = DB::connection('mysql')->select($select_query);
                        
                        if(count($query)>0){
                            foreach($query as $depromo){
                              
                              $insert_delete_trn_prom_details = "INSERT INTO u".$store.".mst_delete_table SET  TableName = 'trn_prom_details',`Action` = 'delete',`TableId` = '" . (int)$depromo->prom_detail_id . "',SID = '" . (int)(session()->get('sid'))."'";
                              $query =  DB::connection('mysql')->insert($insert_delete_trn_prom_details);
                              
                            }
                        }
                    }
                    
                    
                    DB::connection()->delete("DELETE FROM u".$store.".trn_promotions where prom_id = '".$promo_id."' ");
                    DB::connection('mysql')->delete(" DELETE FROM u".$store.".trn_prom_details where prom_id = '".$promo_id."' ");
                    
                    $insert_delete_trn_promotions = "INSERT INTO u".$store.".mst_delete_table SET  TableName = 'trn_promotions',`Action` = 'delete',`TableId` = '" . (int)$promo_id . "',SID = '" . (int)(session()->get('sid'))."'";
                    $query =  DB::connection('mysql')->insert($insert_delete_trn_promotions);
                }
                // Promotion::find($del)->delete();
                // PromotionDetail::where('prom_detail_id',$del)->delete();
            }
        }else { 
            foreach ($input as $del) {
                
                //complete delete promotion   every item PK(prom_detail_id) inserting in mst_delete_table
                $select_query = "SELECT * FROM trn_prom_details WHERE  prom_id = '" . (int)$del . "'";
                $query = \DB::connection('mysql_dynamic')->select($select_query);
                
                if(count($query)>0){
                    foreach($query as $depromo){
                      
                      $insert_delete_trn_prom_details = "INSERT INTO mst_delete_table SET  TableName = 'trn_prom_details',`Action` = 'delete',`TableId` = '" . (int)$depromo->prom_detail_id . "',SID = '" . (int)(session()->get('sid'))."'";
                      $query =  DB::connection('mysql_dynamic')->insert($insert_delete_trn_prom_details);
                      
                    }
                }
                
                
                
                // Promotion::find($del)->delete();
                \DB::connection('mysql_dynamic')->delete("DELETE FROM  trn_promotions WHERE prom_id = '" . (int)$del . "'");
                
                \DB::connection('mysql_dynamic')->delete("DELETE FROM  trn_prom_details WHERE prom_id = '" . (int)$del . "'");
                
                $insert_delete_trn_promotions = "INSERT INTO mst_delete_table SET  TableName = 'trn_promotions',`Action` = 'delete',`TableId` = '" . (int)$del . "',SID = '" . (int)(session()->get('sid'))."'";
                $query =  DB::connection('mysql_dynamic')->insert($insert_delete_trn_promotions);
             
            }
        }
        
        Session::flash('message','Promotion Deleted Successfully!');

        $data['success'] = 'Promotion Deleted Successfully!';
        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');

    }


    public function getSelectedItems($selectedItems)
    {
        $Promotion = new Promotion;
        $items = $Promotion->getSelectedItems($selectedItems);
        $count = count($items);
        foreach($items as $value){
            $json[] = $value['iitemid'];
        }
        // return json_encode($json);
        return ($json);
    }

    public function getSavedBuyItems($prom_id) {
        $sql = "SELECT mi.iitemid,mi.vitemcode,REPLACE(mi.vitemname,'''','') as vitemname,
        mi.vbarcode, mi.dcostprice, mi.dunitprice, tpd.discounted_price FROM trn_prom_details tpd LEFT JOIN mst_item mi on mi.vbarcode = tpd.barcode  where prom_id = '".$prom_id."' ";
        
        $query = \DB::connection('mysql_dynamic')->select($sql);
        
        // $query = \DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode, mi.dcostprice, mi.dunitprice, tpd.discounted_price FROM trn_prom_details tpd LEFT JOIN mst_item mi on mi.vbarcode = tpd.barcode  where prom_id = '".$prom_id."' ");
        // $query = $this->db2->query("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode, mi.dcostprice, mi.dunitprice FROM mst_item as mi WHERE mi.vbarcode IN ( SELECT barcode from trn_prom_details WHERE prom_id = '".$prom_id."') AND mi.estatus='Active'");
        return $query;
    }

    public function getSelectedDiscountedItemsAjax(Request $request)
    {
        $input = $request->all();
        if (isset($input['discountedItems'])  && !empty($input['discountedItems'])) {
            $selectedBuyItems = $input['discountedItems'];
            $prom_type = $input['prom_type'];
            $disc_type = $input['disc_type'];
            $disc_value = $input['disc_value'];

            $Promotion = new Promotion;
            $items = $Promotion->getSelectedItems($selectedBuyItems); 
            $count = count($items);
            $tableRows = "";
            foreach($items as $value){
                $newPrice = $promotion = 0 ;
                $itemId = $value['iitemid'];
              
                if($disc_type == 2 && $disc_value > 0)
                {
                    $newPrice = number_format($value['dunitprice'] - $disc_value , 2);
                    $promotion = number_format($disc_value,2);
                }
                else if($disc_type == 1 && $disc_value > 0)
                {
                    
                    $promotion = number_format( ( $disc_value / $value['dunitprice'] ) * 100 , 2);
                    $newPrice = number_format($value['dunitprice'] - $promotion ,2);
                }
                
              //  $new_price_text = "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId." readonly></td>";
                 if($newPrice < 0)
                  {
                     $new_price_text = "<td style='background-color:red'>".$newPrice."</td>"; 
                  }
                  else
                  {
                      $new_price_text = "<td>".$newPrice."</td>";
                  }
                if($prom_type ==  11)
                {
                    $new_price_text = "<td><input type='text' class='new_price' value=".$newPrice."  name=disc[".$itemId."] id=new_price_".$itemId."></td>";
                }
                
                
                if($prom_type)
                {
                    $tableRows .= "<tr>";
                  //  $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='promotion_discounted_item_text[]' value=".$itemId."></td>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_discounted_item' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='promotion_discounted_item_text[]' value=".$itemId."></td>";
                  
                  //  $tableRows .= "<td></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    $tableRows .= $new_price_text;
                    $tableRows .= "<td>".$promotion."</td>";
                }
                else
                {
                    $tableRows .= "<tr>";
                  //  $tableRows .= "<td><input type='checkbox' class='promotion_buy_items' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='promotion_discounted_item_text[]' value=".$itemId."></td>";
                    $tableRows .= "<td><input type='checkbox' class='promotion_discounted_item' name='added_promotion_items[]' value=".$itemId."><input type='hidden' class='' name='promotion_discounted_item_text[]' value=".$itemId."></td>";
                  
                  //  $tableRows .= "<td></td>";
                    $tableRows .= "<td>".$value['vitemname']."</td>";
                    $tableRows .= "<td>".$value['vbarcode']."</td>";
                    $tableRows .= "<td>".$value['iqtyonhand']."</td>";
                    $tableRows .= "<td>".$value['dcostprice']."</td>";
                    $tableRows .= "<td>".$value['dunitprice']."</td>";
                    $tableRows .= "<td></td>";
                    $tableRows .= "<td></td>";
                }
               
            }
            // return json_encode($json);
            echo ($tableRows);
        }
    }

    public function editpromotion(Request $request) 
    {
          
        $Promotion = new Promotion;
        if ($request->isMethod('post')) {
            
            $input = $request->all();
            
            if($input['promotion_category'] == "Open Ended") {
                $input['promotion_from_date'] = date("Y-m-d");
            }
            $prom_id = $Promotion->editPromotion($input['prom_id'],$input);

            if($prom_id) {
               session()->put('success', 'Promotion Edited Successfully');
              
            } else {
                $this->session->data['warning'] = "Something went wrong!";
            }
            return redirect('/promotion');
        }

        $this->editForm();
    }
    
//  public function getItem($iitemid) {
//         $datas = array();
        
//         $sql = "SELECT * FROM mst_item where iitemid='". (int)$iitemid ."'";
//         $query1 =  DB::connection('mysql_dynamic')->select($sql);
//         $query = json_decode(json_encode($query1), true); 
        
//         if(count($query) > 0){
//                 $value = $query[0];
        
//                 $datas['iitemid'] = $value['iitemid'];
//                 $datas['webstore'] = $value['webstore'];
//                 $datas['vitemtype'] = $value['vitemtype'];
//                 $datas['vitemcode'] = $value['vitemcode'];
//                 $datas['vitemname'] = $value['vitemname'];
//                 $datas['vunitcode'] = $value['vunitcode'];
//                 $datas['vbarcode'] = $value['vbarcode'];
//                 $datas['vpricetype'] = $value['vpricetype'];
//                 $datas['vcategorycode'] = $value['vcategorycode'];
//                 $datas['vdepcode'] = $value['vdepcode'];
//                 $datas['vsuppliercode'] = $value['vsuppliercode'];
//                 $datas['iqtyonhand'] = $value['iqtyonhand'];
//                 $datas['ireorderpoint'] = $value['ireorderpoint'];
//                 $datas['dcostprice'] = $value['dcostprice'];
//                 $datas['dunitprice'] = $value['dunitprice'];
//                 $datas['nsaleprice'] = $value['nsaleprice'];
//                 $datas['nlevel2'] = $value['nlevel2'];
//                 $datas['nlevel3'] = $value['nlevel3'];
//                 $datas['nlevel4'] = $value['nlevel4'];
//                 $datas['iquantity'] = $value['iquantity'];
//                 $datas['ndiscountper'] = $value['ndiscountper'];
//                 $datas['ndiscountamt'] = $value['ndiscountamt'];
//                 $datas['vtax1'] = $value['vtax1'];
//                 $datas['vtax2'] = $value['vtax2'];
//                 $datas['vfooditem'] = $value['vfooditem'];
//                 $datas['vdescription'] = $value['vdescription'];
//                 $datas['dlastsold'] = $value['dlastsold'];
//                 $datas['visinventory'] = $value['visinventory'];
//                 $datas['dpricestartdatetime'] = $value['dpricestartdatetime'];
//                 $datas['dpriceenddatetime'] = $value['dpriceenddatetime'];
//                 $datas['estatus'] = $value['estatus'];
//                 $datas['nbuyqty'] = $value['nbuyqty'];
//                 $datas['ndiscountqty'] = $value['ndiscountqty'];
//                 $datas['nsalediscountper'] = $value['nsalediscountper'];
//                 $datas['vshowimage'] = $value['vshowimage'];
//                 if(isset($value['vshowimage']) && !empty($value['vshowimage'])){
//                     $datas['itemimage'] = $value['itemimage'];
//                 }else{
//                     $datas['itemimage'] = '';
//                 }
                
//                 $datas['vageverify'] = $value['vageverify'];
//                 $datas['ebottledeposit'] = $value['ebottledeposit'];
//                 $datas['nbottledepositamt'] = $value['nbottledepositamt'];
//                 $datas['vbarcodetype'] = $value['vbarcodetype'];
//                 $datas['ntareweight'] = $value['ntareweight'];
//                 $datas['ntareweightper'] = $value['ntareweightper'];
//                 $datas['dcreated'] = $value['dcreated'];
//                 $datas['dlastupdated'] = $value['dlastupdated'];
//                 $datas['dlastreceived'] = $value['dlastreceived'];
//                 $datas['dlastordered'] = $value['dlastordered'];
//                 $datas['nlastcost'] = $value['nlastcost'];
//                 $datas['nonorderqty'] = $value['nonorderqty'];
//                 $datas['vparentitem'] = $value['vparentitem'];
//                 $datas['nchildqty'] = $value['nchildqty'];
//                 $datas['vsize'] = $value['vsize'];
//                 $datas['npack'] = $value['npack'];
//                 $datas['nunitcost'] = $value['nunitcost'];
//                 $datas['ionupload'] = $value['ionupload'];
//                 $datas['nsellunit'] = $value['nsellunit'];
//                 $datas['ilotterystartnum'] = $value['ilotterystartnum'];
//                 $datas['ilotteryendnum'] = $value['ilotteryendnum'];
//                 $datas['etransferstatus'] = $value['etransferstatus'];
//                 $datas['vsequence'] = $value['vsequence'];
//                 $datas['vcolorcode'] = $value['vcolorcode'];
//                 $datas['vdiscount'] = $value['vdiscount'];
//                 $datas['norderqtyupto'] = $value['norderqtyupto'];
//                 $datas['vshowsalesinzreport'] = $value['vshowsalesinzreport'];
//                 $datas['iinvtdefaultunit'] = $value['iinvtdefaultunit'];
//                 $datas['LastUpdate'] = $value['LastUpdate'];
//                 $datas['SID'] = $value['SID'];
//                 $datas['stationid'] = $value['stationid'];
//                 $datas['shelfid'] = $value['shelfid'];
//                 $datas['aisleid'] = $value['aisleid'];
//                 $datas['shelvingid'] = $value['shelvingid'];
//                 $datas['rating'] = $value['rating'];
//                 $datas['vintage'] = $value['vintage'];
//                 $datas['PrinterStationId'] = $value['PrinterStationId'];
//                 $datas['liability'] = $value['liability'];
//                 $datas['isparentchild'] = $value['isparentchild'];
//                 $datas['parentid'] = $value['parentid'];
//                 $datas['parentmasterid'] = $value['parentmasterid'];
//                 $datas['wicitem'] = $value['wicitem'];
            
//         }  

//         return $datas;
//     }
    public function getItem($iitemid, $store = null) {
        $datas = array();
        
        if(isset($store)){
            $sql = "SELECT * FROM u".$store.".mst_item where iitemid='". (int)$iitemid ."'";
            $query1 =  DB::connection('mysql')->select($sql);
        }else{
            $sql = "SELECT * FROM mst_item where iitemid='". (int)$iitemid ."'";
            $query1 =  DB::connection('mysql_dynamic')->select($sql);
        }
        
        $query = json_decode(json_encode($query1), true); 
        
        if(count($query1) > 0){
                $value = $query1[0];
                //dd($value->iitemid);
        
                $datas['iitemid'] = $value->iitemid;
                $datas['webstore'] = $value->webstore;
                $datas['vitemtype'] = $value->vitemtype;
                $datas['vitemcode'] = $value->vitemcode;
                $datas['vitemname'] = $value->vitemname;
                $datas['vunitcode'] = $value->vunitcode;
                $datas['vbarcode'] = $value->vbarcode;
                $datas['vpricetype'] = $value->vpricetype;
                $datas['vcategorycode'] = $value->vcategorycode;
                $datas['vdepcode'] = $value->vdepcode;
                $datas['vsuppliercode'] = $value->vsuppliercode;
                $datas['iqtyonhand'] = $value->iqtyonhand;
                $datas['ireorderpoint'] = $value->ireorderpoint;
                $datas['dcostprice'] = $value->dcostprice;
                $datas['dunitprice'] = $value->dunitprice;
                $datas['nsaleprice'] = $value->nsaleprice;
                $datas['nlevel2'] = $value->nlevel2;
                $datas['nlevel3'] = $value->nlevel3;
                $datas['nlevel4'] = $value->nlevel4;
                $datas['iquantity'] = $value->iquantity;
                $datas['ndiscountper'] = $value->ndiscountper;
                $datas['ndiscountamt'] = $value->ndiscountamt;
                $datas['vtax1'] = $value->vtax1;
                $datas['vtax2'] = $value->vtax2;
                $datas['vfooditem'] = $value->vfooditem;
                $datas['vdescription'] = $value->vdescription;
                $datas['dlastsold'] = $value->dlastsold;
                $datas['visinventory'] = $value->visinventory;
                $datas['dpricestartdatetime'] = $value->dpricestartdatetime;
                $datas['dpriceenddatetime'] = $value->dpriceenddatetime;
                $datas['estatus'] = $value->estatus;
                $datas['nbuyqty'] = $value->nbuyqty;
                $datas['ndiscountqty'] = $value->ndiscountqty;
                $datas['nsalediscountper'] = $value->nsalediscountper;
                $datas['vshowimage'] = $value->vshowimage;
                if(isset($value->vshowimage) && !empty($value->vshowimage)){
                    $datas['itemimage'] = $value->itemimage;
                }else{
                    $datas['itemimage'] = '';
                }
                
                $datas['vageverify'] = $value->vageverify;
                $datas['ebottledeposit'] = $value->ebottledeposit;
                $datas['nbottledepositamt'] = $value->nbottledepositamt;
                $datas['vbarcodetype'] = $value->vbarcodetype;
                $datas['ntareweight'] = $value->ntareweight;
                $datas['ntareweightper'] = $value->ntareweightper;
                $datas['dcreated'] = $value->dcreated;
                $datas['dlastupdated'] = $value->dlastupdated;
                $datas['dlastreceived'] = $value->dlastreceived;
                $datas['dlastordered'] = $value->dlastordered;
                $datas['nlastcost'] = $value->nlastcost;
                $datas['nonorderqty'] = $value->nonorderqty;
                $datas['vparentitem'] = $value->vparentitem;
                $datas['nchildqty'] = $value->nchildqty;
                $datas['vsize'] = $value->vsize;
                $datas['npack'] = $value->npack;
                $datas['nunitcost'] = $value->nunitcost;
                $datas['ionupload'] = $value->ionupload;
                $datas['nsellunit'] = $value->nsellunit;
                $datas['ilotterystartnum'] = $value->ilotterystartnum;
                $datas['ilotteryendnum'] = $value->ilotteryendnum;
                $datas['etransferstatus'] = $value->etransferstatus;
                $datas['vsequence'] = $value->vsequence;
                $datas['vcolorcode'] = $value->vcolorcode;
                $datas['vdiscount'] = $value->vdiscount;
                $datas['norderqtyupto'] = $value->norderqtyupto;
                $datas['vshowsalesinzreport'] = $value->vshowsalesinzreport;
                $datas['iinvtdefaultunit'] = $value->iinvtdefaultunit;
                $datas['LastUpdate'] = $value->LastUpdate;
                $datas['SID'] = $value->SID;
                $datas['stationid'] = $value->stationid;
                $datas['shelfid'] = $value->shelfid;
                $datas['aisleid'] = $value->aisleid;
                $datas['shelvingid'] = $value->shelvingid;
                $datas['rating'] = $value->rating;
                $datas['vintage'] = $value->vintage;
                $datas['PrinterStationId'] = $value->PrinterStationId;
                $datas['liability'] = $value->liability;
                $datas['isparentchild'] = $value->isparentchild;
                $datas['parentid'] = $value->parentid;
                $datas['parentmasterid'] = $value->parentmasterid;
                $datas['wicitem'] = $value->wicitem;
            
        }  

        return $datas;
    }
    
    public function getItemByBarcode($vbarcode, $store = null) {
        $datas = array();
        
        if(isset($store)){
            $sql = "SELECT * FROM u".$store.".mst_item where vbarcode='". (int)$vbarcode ."'";
        }else{
            $sql = "SELECT * FROM mst_item where vbarcode='". (int)$vbarcode ."'";
        }
        $query1 =  DB::connection('mysql')->select($sql);
        $query = json_decode(json_encode($query1), true); 
        
        if(count($query1) > 0){
                $value = $query1[0];
                //dd($value->iitemid);
        
                $datas['iitemid'] = $value->iitemid;
                $datas['webstore'] = $value->webstore;
                $datas['vitemtype'] = $value->vitemtype;
                $datas['vitemcode'] = $value->vitemcode;
                $datas['vitemname'] = $value->vitemname;
                $datas['vunitcode'] = $value->vunitcode;
                $datas['vbarcode'] = $value->vbarcode;
                $datas['vpricetype'] = $value->vpricetype;
                $datas['vcategorycode'] = $value->vcategorycode;
                $datas['vdepcode'] = $value->vdepcode;
                $datas['vsuppliercode'] = $value->vsuppliercode;
                $datas['iqtyonhand'] = $value->iqtyonhand;
                $datas['ireorderpoint'] = $value->ireorderpoint;
                $datas['dcostprice'] = $value->dcostprice;
                $datas['dunitprice'] = $value->dunitprice;
                $datas['nsaleprice'] = $value->nsaleprice;
                $datas['nlevel2'] = $value->nlevel2;
                $datas['nlevel3'] = $value->nlevel3;
                $datas['nlevel4'] = $value->nlevel4;
                $datas['iquantity'] = $value->iquantity;
                $datas['ndiscountper'] = $value->ndiscountper;
                $datas['ndiscountamt'] = $value->ndiscountamt;
                $datas['vtax1'] = $value->vtax1;
                $datas['vtax2'] = $value->vtax2;
                $datas['vfooditem'] = $value->vfooditem;
                $datas['vdescription'] = $value->vdescription;
                $datas['dlastsold'] = $value->dlastsold;
                $datas['visinventory'] = $value->visinventory;
                $datas['dpricestartdatetime'] = $value->dpricestartdatetime;
                $datas['dpriceenddatetime'] = $value->dpriceenddatetime;
                $datas['estatus'] = $value->estatus;
                $datas['nbuyqty'] = $value->nbuyqty;
                $datas['ndiscountqty'] = $value->ndiscountqty;
                $datas['nsalediscountper'] = $value->nsalediscountper;
                $datas['vshowimage'] = $value->vshowimage;
                if(isset($value->vshowimage) && !empty($value->vshowimage)){
                    $datas['itemimage'] = $value->itemimage;
                }else{
                    $datas['itemimage'] = '';
                }
                
                $datas['vageverify'] = $value->vageverify;
                $datas['ebottledeposit'] = $value->ebottledeposit;
                $datas['nbottledepositamt'] = $value->nbottledepositamt;
                $datas['vbarcodetype'] = $value->vbarcodetype;
                $datas['ntareweight'] = $value->ntareweight;
                $datas['ntareweightper'] = $value->ntareweightper;
                $datas['dcreated'] = $value->dcreated;
                $datas['dlastupdated'] = $value->dlastupdated;
                $datas['dlastreceived'] = $value->dlastreceived;
                $datas['dlastordered'] = $value->dlastordered;
                $datas['nlastcost'] = $value->nlastcost;
                $datas['nonorderqty'] = $value->nonorderqty;
                $datas['vparentitem'] = $value->vparentitem;
                $datas['nchildqty'] = $value->nchildqty;
                $datas['vsize'] = $value->vsize;
                $datas['npack'] = $value->npack;
                $datas['nunitcost'] = $value->nunitcost;
                $datas['ionupload'] = $value->ionupload;
                $datas['nsellunit'] = $value->nsellunit;
                $datas['ilotterystartnum'] = $value->ilotterystartnum;
                $datas['ilotteryendnum'] = $value->ilotteryendnum;
                $datas['etransferstatus'] = $value->etransferstatus;
                $datas['vsequence'] = $value->vsequence;
                $datas['vcolorcode'] = $value->vcolorcode;
                $datas['vdiscount'] = $value->vdiscount;
                $datas['norderqtyupto'] = $value->norderqtyupto;
                $datas['vshowsalesinzreport'] = $value->vshowsalesinzreport;
                $datas['iinvtdefaultunit'] = $value->iinvtdefaultunit;
                $datas['LastUpdate'] = $value->LastUpdate;
                $datas['SID'] = $value->SID;
                $datas['stationid'] = $value->stationid;
                $datas['shelfid'] = $value->shelfid;
                $datas['aisleid'] = $value->aisleid;
                $datas['shelvingid'] = $value->shelvingid;
                $datas['rating'] = $value->rating;
                $datas['vintage'] = $value->vintage;
                $datas['PrinterStationId'] = $value->PrinterStationId;
                $datas['liability'] = $value->liability;
                $datas['isparentchild'] = $value->isparentchild;
                $datas['parentid'] = $value->parentid;
                $datas['parentmasterid'] = $value->parentmasterid;
                $datas['wicitem'] = $value->wicitem;
            
        }  

        return $datas;
    }
    
    public function check_promocode(Request $request)
    {
        $input = $request->all();
        $result = Promotion::where('prom_code', $input['promotion_code'])->get();
        if(count($result) > 0){
            return 1;
        }else{
            return 0;
        }
    }
}