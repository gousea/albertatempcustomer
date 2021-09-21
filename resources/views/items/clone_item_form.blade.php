@extends('layouts.layout')

@section('title', 'Items')

@section('main-content')
<div id="content">
  
  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase"> Clone Item</span>
            </div>
            <div class="nav-submenu">
              <button class="btn btn-gray headerblack  buttons_menu save_btn_rotate formsubmit">Save</button>
              
              <a type="submit" id="cancel_button" href="{{ $data['cancel'] }}" data-toggle="tooltip" title="Cancel" class="btn btn-danger buttonred buttons_menu basic-button-small cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
              
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
  </nav>

  <div class="container-fluid section-content">
    @if ($data['error_warning'])
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ $data['error_warning'] }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif
    @if ($data['success'])
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ $data['success'] }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif
    <div class="panel panel-default">
      
      <div class="panel-body padding-left-right">

        <div class="mytextdiv">
          <div class="mytexttitle font-weight-bold text-uppercase">
              TAB
          </div>
          <div class="divider font-weight-bold"></div>
        </div>

        <div class="py-3">
          <div class="row">
              <div class="col-md-12 mx-auto">
                  
                  <div class="form-group row">
                      <div class="col-12 col-md-2 col-sm-2 col-lg-2 p-form">
                        
                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                        </div>

                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                            
                            <input type="checkbox" name="for_general" maxlength="30" id="item_tab_li" checked />
                            <label for="inputFirstname" class="p-2 text-uppercase">Item</label>
                        </div>
                          
                      </div>
                      
                      <div class="col-12 col-md-2 col-sm-2 col-lg-2 p-form">

                        <div class="col-12 col-md-12 col-sm-12 col-lg-12">
                            
                            <input type="checkbox" name="for_item" maxlength="30" id="alias_code_tab_li" <?php if(!isset($data['iitemid']) || empty($data['iitemid'])){?> style="pointer-events:none;" <?php } ?> />
                            <label for="inputFirstname" class="p-2 text-uppercase">Add Alias Code</label>
                        </div>
                      </div>

                      <div class="col-12 col-md-2 col-sm-2 col-lg-2 p-form">

                        <div class="col-12 col-md-12 col-sm-12 col-lg-12">
                            <input type="checkbox" name="for_item" maxlength="30" id="lot_matrix_tab_li" <?php if(isset($data['vitemtype']) && $data['vitemtype'] != 'Lot Matrix'){ ?> style="pointer-events:none;" <?php } ?> />
                            <label for="inputFirstname" class="p-2 text-uppercase">Lot Matrix</label>
                        </div>
                      </div>

                      <div class="col-12 col-md-2 col-sm-2 col-lg-2 p-form">

                        <div class="col-12 col-md-12 col-sm-12 col-lg-12">
                            <input type="checkbox" name="for_item" maxlength="30" id="vendor_tab_li" <?php if(!isset($data['iitemid']) || empty($data['iitemid'])){?> style="pointer-events:none;" <?php } ?> />
                            <label for="inputFirstname" class="p-2 text-uppercase">Vendor</label>
                        </div>
                      </div>

                  </div>
                  
              </div>
          </div>

        </div>

        <div class="responsive">
          <div class="tab-pane active" id="item_tab">
            <form action="{{ $data['action'] }}" method="post" enctype="multipart/form-data" id="form-item1" class="form-horizontal">
                @csrf
                @if(session()->get('hq_sid') == 1)
                    <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
                @endif
                <input type="hidden" name="clone_item_id" value="<?php echo $data['clone_item_id'];?>">
                <?php if(isset($data['iitemid']) && isset($data['edit_page'])){?>
                    <input type="hidden" name="iitemid" value="<?php echo $data['iitemid'];?>">
                <?php } ?>
                <?php if(isset($data['isparentchild']) && isset($data['edit_page'])){?>
                    <input type="hidden" name="isparentchild" value="<?php echo !empty($data['isparentchild']) ? $data['isparentchild'] : 0; ?>">
                <?php } ?>
                <?php if(isset($data['parentid']) && isset($data['edit_page'])){?>
                    <input type="hidden" name="parentid" value="<?php echo !empty($data['parentid']) ? $data['parentid'] : 0; ?>">
                <?php } ?>
                <?php if(isset($data['parentmasterid']) && isset($data['edit_page'])){?>
                    <input type="hidden" name="parentmasterid" value="<?php echo !empty($data['parentmasterid']) ? $data['parentmasterid'] : 0; ?>">
                <?php } ?>
              
                <div class="mytextdiv">
                  <div class="mytexttitle font-weight-bold text-uppercase">
                      Item Info
                  </div>
                  <div class="divider font-weight-bold"></div>
                </div>

                <div>

                    <div class="py-3">
                      <div class="row">
                          <div class="col-md-12 mx-auto">
                              
                              <div class="form-group row ">
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                          <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Item Type</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <select name="vitemtype" class="form-control adjustment-fields">
                                          <?php if(isset($data['item_types']) && count($data['item_types']) > 0){?>
                                            <?php foreach($data['item_types'] as $item_type){ ?>
                                              <?php if(isset($data['vitemtype']) && $data['vitemtype'] == $item_type){?>
                                                <option value="<?php echo $item_type;?>" selected="selected"><?php echo $item_type;?></option>
                                              <?php }else{ ?>
                                                <option value="<?php echo $item_type;?>"><?php echo $item_type;?></option>
                                              <?php } ?>
                                            <?php } ?>
                                          <?php } ?>
                                        </select>
                                          
                                      </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
    
                                      <label for="inputNumber" class="p-2 float-right text-uppercase">SKU</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                      <input type="text" name="vbarcode" maxlength="13" value="<?php echo isset($data['vbarcode']) ? $data['vbarcode'] : ''; ?>" placeholder="SKU" id="input-sku" class="form-control adjustment-fields" <?php if(isset($data['vbarcode']) && isset($data['edit_page'])){?>readonly <?php } ?> autocomplete="off"/>
                                      <?php if (isset($data['error_vbarcode'])) { ?>
                                          <div class="text-danger"><?php echo $data['error_vbarcode']; ?></div>
                                      <?php } ?>
                                    </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form notLottery">
                                      
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputVendor" class="p-2 float-right text-uppercase">Item Name</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      
                                      <input type="text" style="display: inline-block;width: 82%;" name="vitemname" maxlength="100" value="<?php echo isset($data['vitemname']) ? $data['vitemname'] : ''; ?>" placeholder="Item Name" id="input_itemname" class="form-control adjustment-fields" />
                                      <button class="btn btn-sm btn-info" title="Add Description" id="add_description" style="display: inline-block;width: 15%">..</button>
                                      <input type="hidden" id="description_value" name="vdescription" maxlength="100" value="<?php echo isset($data['vdescription']) ? $data['vdescription'] : ''; ?>" placeholder="Description" >
                                      
                                      <?php if (isset($data['error_vitemname'])) { ?>
                                          <div class="text-danger"><?php echo $data['error_vitemname']; ?></div>
                                      <?php } ?>
                                       
                                    </div>
                                  </div>

                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form Lottery" style="display:none">
                                      
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputVendor" class="p-2 float-right text-uppercase">Ticket Name</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      
                                      <input type="text" name="ticket_name" maxlength="50" value="<?php echo isset($data['vitemname']) ? $data['vitemname'] : ''; ?>" placeholder="Ticket Name" id="input-ticket_name" class="form-control adjustment-fields" autocomplete="off" />
                                      
                                      <?php if (isset($data['error_ticket_name'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_ticket_name']; ?></div>
                                      <?php } ?>
                                       
                                    </div>
                                  </div>
                              </div>
                              
                          </div>
                      </div>
    
                      <div class="row notLottery">
                        <div class="col-md-12 mx-auto">
                            
                            <div class="form-group row">
                                
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                  <div class="col-6 col-md-6 col-sm-6 col-lg-6">
  
                                    <label for="inputCreated" class="p-2 float-right text-uppercase">Cost</label>
                                  </div>
                                  <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                    <input type="text" name="new_costprice" style="display: inline-block;width: 82%;" value="<?php echo isset($data['new_costprice']) && !empty($data['new_costprice']) ? number_format((float)$data['new_costprice'], 2) : ''; ?>" placeholder="New Cost" id="input-new_cost" class="form-control adjustment-fields"/>
                                    
                                    <input type='hidden' id='input-unitcost' value=''>
                                    <button class="btn btn-sm btn-info" id="add_cost" style="display: inline-block;width: 15%" title="Add Costs">..</button>
                                    <input type="hidden" id="avgcost_value" name="dcostprice" value="<?php echo isset($data['dcostprice']) ? $data['dcostprice'] : ''; ?>" placeholder="Avg. Case Cost" class="form-control" autocomplete="off"/> 
                                    <input type="hidden" id="lastcost_value" name="last_costprice" value="<?php echo isset($data['last_costprice']) ? $data['last_costprice'] : ''; ?>" placeholder="Last Cost" class="form-control" readonly/>
                                        
                                    <?php if (isset($data['error_new_costprice'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_new_costprice']; ?></div>
                                    <?php } ?>
                                    
                                  </div>
                                </div>

                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                  <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputReceived" class="p-2 float-right text-uppercase">Price</label>
                                  </div>
                                  <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                    <input type="text" style="display: inline-block;width: 82%;" id="input-Selling_Price" name="dunitprice" value="<?php echo isset($data['dunitprice']) ? $data['dunitprice'] : ''; ?>" placeholder="Selling Price" class="form-control adjustment-fields" autocomplete="off"/>
                                    <button class="btn btn-sm btn-info" id="add_pricelevel" title="Add Description" style="display: inline-block;width: 15%">..</button>
                                    <input type="hidden" id="nlevel2_value" name="nlevel2" value="<?php echo isset($data['nlevel2']) ? $data['nlevel2'] : ''; ?>" class="form-control" />
                                    <input type="hidden" id="nlevel3_value" name="nlevel3" value="<?php echo isset($data['nlevel3']) ? $data['nlevel3'] : ''; ?>" class="form-control" />
                                    <input type="hidden" id="nlevel4_value" name="nlevel4" value="<?php echo isset($data['nlevel4']) ? $data['nlevel4'] : ''; ?>" class="form-control"/>
                                    <?php if (isset($data['error_dunitprice'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_dunitprice']; ?></div>
                                    <?php } ?>
                                  </div>
                                </div>

                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    
                                  <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputStatus" class="p-2 float-right text-uppercase">GP%</label>
                                  </div>

                                  <?php

                                    if(isset($data['new_costprice']) && $data['new_costprice'] >0 && isset($data['dunitprice'])){    
                                      
                                      // echo "new_costprice: $new_costprice";
                                      // echo "npack: $npack";
                                      
                                      
                                        // $nunit_cost = $data['new_costprice']/$data['npack'];
                                        $nunit_cost = (int)$data['npack'] !== 0?(int)($data['new_costprice']/$data['npack']):0;
                                        $nunit_cost = round($nunit_cost, 2);
                                      
                                      if(isset($ndiscountper)){
                                        $percent = $data['dunitprice'] - ($nunit_cost-$ndiscountper);
                                      }else{
                                        $percent = (float)$data['dunitprice'] - (float)$nunit_cost;
                                      }
                                      
                                      $percent = ($percent > 0) ? $percent : 0;
                                      
                                      if($percent > 0){
                                        $percent = $percent;
                                      }else{
                                        $percent = 0;
                                      }
                                      
                                      if($data['dunitprice'] == 0 || $data['dunitprice'] == '0.0000'){
                                        $dunitprice = 1;
                                      }else{
                                          $dunitprice = $data['dunitprice'];
                                      }
                                     
                                      $percent = (((float)$percent/(float)$dunitprice) * 100);
                                      
                                      
                                      $percent = number_format((float)$percent, 2, ".", '');
            
                                    }else{
                                      $percent = 0.00;
                                    }

                                  ?>
                                  <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    
                                      <!--<span style="display: inline-block;width: 87%;"><input type="text" name="gross_profit" value="<?php echo $percent;?>" placeholder="" id="input-profit-margin" class="form-control" readonly /></span>
                                    <span style="display: inline-block;width: 10%" id="selling_price_calculation_btn"><button class="btn btn-sm btn-info" title="">..</button></span>-->
                                    <input type="text" style="display: inline-block;width: 82%;" name="gross_profit" value="<?php echo $percent;?>" placeholder="" id="input-profit-margin" class="form-control adjustment-fields" readonly />
                                    <button class="btn btn-sm btn-info" id="selling_price_calculation_btn" title="Calculate Selling Price" style="display: inline-block;width: 15%">..</button>
                                      
                                  </div>
                                </div>
                            </div>
                            
                        </div>
                      </div>

                      <div class="row notLottery">
                          <div class="col-md-12 mx-auto">
                              
                              <div class="form-group row ">
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                          <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Department</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <span style="display: inline-block;width: 79%;">
                                          <select name="vdepcode" class="form-control adjustment-fields" id="dept_code" style="display: inline-block;width: 79% !important;">
                                              
                                            <option value="">Select Department</option>
                                            <?php if(isset($data['departments']) && count($data['departments']) > 0){?>
                                              <?php foreach($data['departments'] as $department){ ?>
                                              <?php if(isset($data['vdepcode']) && $data['vdepcode'] == $department['vdepcode']){?>
                                                <option value="<?php echo $department['vdepcode'];?>" selected="selected"><?php echo $department['vdepartmentname'];?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $department['vdepcode'];?>"><?php echo $department['vdepartmentname'];?></option>
                                              <?php } ?>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
                                        </span>
                                      <!--<span style="display: inline-block;width: 10%" title="Add Department" id="add_new_department"><button class="btn btn-success btn-sm"><i class="fa fa-plus-square" aria-hidden="true"></i></button></span>-->
                                      <button class="btn btn-success btn-sm" id="add_new_department" style="display: inline-block;width: 18%;"><i class="fa fa-plus-square" aria-hidden="true" ></i></button>
                                      <?php if (isset($data['error_vdepcode'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_vdepcode']; ?></div>
                                      <?php } ?>
                                          
                                      </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
    
                                      <label for="inputNumber" class="p-2 float-right text-uppercase">Category</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                      <span style="display: inline-block;width: 79%;">
                                        <select name="vcategorycode" class="form-control adjustment-fields" id="category_code"
                                            <?php
                                                if(!isset($data['vcategorycode'])){
                                                    echo "disabled='true'";
                                                }
                                            
                                            ?>
                                        >
                                            <option value="">Select Category</option>
                                            
                                            <?php if(isset($data['categories']) && count($data['categories']) > 0 && isset($data['vcategorycode'])){?>
                                                <?php foreach($data['categories'] as $category){ ?>
                                                    <?php echo "DB: ".$data['vcategorycode']." Current: ".$category['vcategorycode']; ?>
                                                    <?php if($data['vcategorycode'] == $category['vcategorycode']){ echo "DB: ".$data['vcategorycode']." Current: ".$category['vcategorycode']; ?>
                                                        <option value="<?php echo $category['vcategorycode'];?>" selected="selected"><?php echo $category['vcategoryname'];?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $category['vcategorycode'];?>"><?php echo $category['vcategoryname'];?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                          
                                        </select>
                                      </span>
                                      <!--<span style="display: inline-block;width: 10%" title="Add Category" id="add_new_category"><button class="btn btn-success btn-sm"><i class="fa fa-plus-square" aria-hidden="true"></i></button></span>-->
                                      <button class="btn btn-success btn-sm" style="display: inline-block;width: 18%" title="Add Category" id="add_new_category"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                      <?php if (isset($data['error_vcategorycode'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_vcategorycode']; ?></div>
                                      <?php } ?>
                                    </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputVendor" class="p-2 float-right text-uppercase">Sub Category</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      
                                      <span style="display: inline-block;width: 79%;">
                                        <select name="subcat_id" class="form-control adjustment-fields" id="subcat_id"
                                            <?php
                                                if(!isset($data['vcategorycode'])){
                                                    echo "disabled='true'";
                                                }
                                            
                                            ?>
                                        >
                                            <option value="">Select Sub Category</option>
                                            
                                            <?php if(isset($data['subcategories']) && count($data['subcategories']) > 0 && isset($data['subcat_id'])){?>
                                                <?php foreach($data['subcategories'] as $subcategory){ ?>
                                                
                                                    <?php if($data['subcat_id'] == $subcategory['subcat_id']){ ?>
                                                        <option value="<?php echo $subcategory['subcat_id'];?>" selected="selected"><?php echo $subcategory['subcat_name'];?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $subcategory['subcat_id'];?>"><?php echo $subcategory['subcat_name'];?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                          
                                        </select>
                                      </span>
                                      <!--<span style="display: inline-block;width: 10%" title="Add Sub Category" id="add_new_subcategory"><button class="btn btn-success btn-sm"><i class="fa fa-plus-square" aria-hidden="true"></i></button></span>-->
                                      <button class="btn btn-success btn-sm" style="display: inline-block;width: 18%" title="Add Sub Category" id="add_new_subcategory"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                      <?php if (isset($data['error_subcat_id'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_subcat_id']; ?></div>
                                      <?php } ?>
                                       
                                    </div>
                                  </div>
                              </div>
                              
                          </div>
                      </div>

                      <div class="row notLottery">
                          <div class="col-md-12 mx-auto">
                              
                              <div class="form-group row ">
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                          <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Supplier</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <span style="display: inline-block;width: 79%;">
                                          <select name="vsuppliercode" class="form-control adjustment-fields">
                                              <option value="">Select Supplier</option>
                                            <?php if(isset($data['suppliers']) && count($data['suppliers']) > 0){?>
                                              <?php foreach($data['suppliers'] as $supplier){ ?>
                                              <?php if(isset($data['vsuppliercode']) && $data['vsuppliercode'] == $supplier['vsuppliercode']){?>
                                                <option value="<?php echo $supplier['vsuppliercode'];?>" selected="selected"><?php echo $supplier['vcompanyname'];?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $supplier['vsuppliercode'];?>"><?php echo $supplier['vcompanyname'];?></option>
                                              <?php } ?>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
                                        </span>
                                        <!--<span style="display: inline-block;width: 10%" title="Add Supplier" id="add_new_supplier"><button class="btn btn-success btn-sm"><i class="fa fa-plus-square" aria-hidden="true"></i></button></span>-->
                                        <button class="btn btn-success btn-sm" style="display: inline-block;width: 18%" title="Add Supplier" id="add_new_supplier"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                        <?php if (isset($data['error_vsuppliercode'])) { ?>
                                          <div class="text-danger"><?php echo $data['error_vsuppliercode']; ?></div>
                                        <?php } ?>
                                          
                                      </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
    
                                      <label for="inputNumber" class="p-2 float-right text-uppercase">Taxable</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                      <select name="vtax" class="form-control adjustment-fields">
                                        <?php if(isset($data['all_taxes']) && count($data['all_taxes']) > 0 ) { ?>
                                            <?php foreach($data['all_taxes'] as $tax){ ?>
                                                <?php if(isset($data['vtax']) && $data['vtax'] == $tax['value']) { ?>
                                                    <option value="<?php echo $tax['value'] ?>" selected="selected" ><?php echo $tax['name'] ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $tax['value'] ?>" ><?php echo $tax['name'] ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputVendor" class="p-2 float-right text-uppercase">Unit</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      
                                      <select name="vunitcode" class="form-control adjustment-fields">
                                        <option value="">Select Unit</option>
                                        <?php if(isset($data['units']) && count($data['units']) > 0){?>
                                            <?php foreach($data['units'] as $unit){ ?>
                                                <?php if(isset($data['vunitcode']) && $data['vunitcode'] == $unit['vunitcode']){?>
                                                  <option value="<?php echo $unit['vunitcode'];?>" selected="selected"><?php echo $unit['vunitname'];?></option>
                                                <?php } else { ?>
                                                  <option value="<?php echo $unit['vunitcode'];?>"><?php echo $unit['vunitname'];?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                      </select>
                                       <?php if (isset($data['error_vunitcode'])) { ?>
                                          <div class="text-danger"><?php echo $data['error_vunitcode']; ?></div>
                                        <?php } ?>
                                    </div>
                                  </div>
                              </div>
                              
                          </div>
                      </div>

                      <div class="row notLottery">
                          <div class="col-md-12 mx-auto">
                              
                              <div class="form-group row ">
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                          <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Size</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <span style="display: inline-block;width: 79%;">
                                          <select name="vsize" class="form-control adjustment-fields">
                                              <option value="">--Select Size--</option>
                                            <?php if(isset($data['sizes']) && count($data['sizes']) > 0){?>
                                              <?php foreach($data['sizes'] as $size){ ?>
                                              <?php if(isset($data['vsize']) && $data['vsize'] == $size['vsize']){ ?>
                                                <option value="<?php echo $size['vsize'];?>" selected="selected"><?php echo $size['vsize'];?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $size['vsize'];?>"><?php echo $size['vsize'];?></option>
                                              <?php } ?>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
                                        </span>
                                  
                                        <button class="btn btn-success btn-sm" style="display: inline-block;width: 18%" title="Add Size" id="add_new_size"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                          
                                      </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
    
                                      <label for="inputNumber" class="p-2 float-right text-uppercase">Manufaturer</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                      <select name="manufacturer_id" class="form-control adjustment-fields">
                                        <option value="">--Select Manufacturer--</option>
                                        <?php if(isset($data['manufacturers']) && count($data['manufacturers']) > 0){?>
                                          <?php foreach($data['manufacturers'] as $manufacturer){ ?>
                                          <?php if(isset($data['manufacturer_id']) && $data['manufacturer_id'] == $manufacturer['mfr_id']){?>
                                            <option value="<?php echo $manufacturer['mfr_id'];?>" selected="selected"><?php echo $manufacturer['mfr_name'];?></option>
                                          <?php } else { ?>
                                            <option value="<?php echo $manufacturer['mfr_id'];?>"><?php echo $manufacturer['mfr_name'];?></option>
                                          <?php } ?>
                                          <?php } ?>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputVendor" class="p-2 float-right text-uppercase">Age Verification</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      
                                      <select name="vageverify" class="form-control adjustment-fields">
                                        <!--<option value="">--Select Age Verification--</option>-->
                                        <option value="0">None</option>
                                        <?php if(isset($data['ageVerifications']) && count($data['ageVerifications']) > 0){?>
                                            <?php foreach($data['ageVerifications'] as $ageVerification){ ?>
                                                <?php if(isset($data['vageverify']) && $data['vageverify'] == $ageVerification->vvalue){ ?>
                                                    <option value="<?php echo $ageVerification->vvalue;?>" selected="selected"><?php echo $ageVerification->vname;?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $ageVerification->vvalue;?>" ><?php echo $ageVerification->vname;?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                      </select>
                                       
                                    </div>
                                  </div>
                              </div>
                              
                          </div>
                      </div>

                      <div class="row notLottery">
                          <div class="col-md-12 mx-auto">
                              
                              <div class="form-group row ">
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                          <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Selling Unit</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <?php 
                                            if(isset($data['nsellunit']) && $data['nsellunit'] != ''){
                                              $nsellunit = $data['nsellunit'];
                                            }else{
                                              $nsellunit = 1;
                                            }
                                        ?>
                                        <input type="text" name="nsellunit" value="<?php echo $nsellunit; ?>" placeholder="Selling Unit" id="input-sellingunit" class="form-control adjustment-fields" <?php if(isset($data['vitemtype']) && $data['vitemtype'] == 'Lot Matrix'){?> readonly <?php } ?> />
                                          
                                      </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
    
                                      <label for="inputNumber" class="p-2 float-right text-uppercase">Unit Per Case</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                      <?php 
                                        if(isset($data['npack']) && $data['npack'] != ''){
                                          $npack = $data['npack'];
                                        }elseif($data['npack'] != 0){
                                            $npack = 1;
                                        }else{
                                            $npack = 0;
                                        }
                                      ?>
                                      <input type="text" name="npack" value="<?php echo $npack; ?>" placeholder="Unit Per Case" id="input-unitpercase" class="form-control adjustment-fields" />
                                    </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputVendor" class="p-2 float-right text-uppercase">Qty on Hand</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      
                                      <?php if(isset($data['edit_page'])){?>
                                        <?php
                                        
                                          if($data['iqtyonhand'] != 0 && $data['iqtyonhand']!='' && $data['npack']!=0){
                                            $quotient = (int)($data['iqtyonhand'] / $data['npack']);
                                            $remainder = $data['iqtyonhand'] % $data['npack'];
                                              
                                            $qty_on_hand = ''.$quotient .' ('.$remainder.')';
                                          }
                                          else{
                                            $qty_on_hand = 'Case: 0 [0]';
                                          }
                                          if(isset($data['itemparentitems']->IQTYONHAND)){
                                              $qty_on_hand=$data['itemparentitems']->IQTYONHAND % $data['npack'];
                                          }
                                          ?>
                                          <input type="text" value="<?php echo isset($data['QOH']) ? $qty_on_hand : ''; ?>" class="form-control" readonly>
                                          <input type="hidden" name="iqtyonhand" value="<?php echo isset($data['iqtyonhand']) ? $data['iqtyonhand'] : ''; ?>" class="form-control adjustment-fields" placeholder="Qty on Hand" <?php if((isset($data['isparentchild']) && $data['isparentchild'] == 1) || (isset($data['edit_page']))){?> readonly <?php } ?>  autocomplete="off"/>
                                      <?php }else{?>
                                          <input type="text" name="iqtyonhand" value="<?php echo isset($data['iqtyonhand']) ? $data['iqtyonhand'] : ''; ?>" class="form-control adjustment-fields" placeholder="Qty on Hand"  autocomplete="off"/>
                                      <?php }?>
                                       
                                    </div>
                                  </div>
                              </div>
                              
                          </div>
                      </div>

                      <div class="row notLottery">
                          <div class="col-md-12 mx-auto">
                              
                              <div class="form-group row ">
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                          <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Promotion</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <select name="itemPromotionid" id="promotionid" class="form-control adjustment-fields">
                                
                                          <?php if(isset($data['itemPromotion']) && count($data['itemPromotion']) > 0){?>
                                              <?php foreach($data['itemPromotion'] as $itemPromo){ ?>
                                              
                                              <?php if(isset($data['prom_id']) && $data['prom_id'] == $itemPromo->prom_id){ ?>
                                                <option value="<?php echo $itemPromo->prom_id;?>" selected="selected"><?php echo $itemPromo->prom_name;?></option>
                                              <?php } else { ?>
                                                <option class="disPro" value="<?php echo $itemPromo->prom_id;?>" ><?php echo $itemPromo->prom_name;?></option>
                                              <?php } ?>
                                              <?php } ?>
                                          <?php }else{ ?>
                                              <option>None</option>
                                          <?php } ?> 
                                        </select> 
                                          
                                      </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
    
                                      <label for="inputNumber" class="p-2 float-right text-uppercase">Buydown</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                      <input type="text" name="ndiscountper" value="<?php echo isset($data['buydown']) ? $data['buydown'] : 0; ?>" placeholder="Buydown" id="input_buydown; ?>" class="form-control adjustment-fields" readonly/>
                                    </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputVendor" class="p-2 float-right text-uppercase">Parent Barcode</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      
                                      <?php if(isset($data['itemparentitems']) && !empty($data['itemparentitems'])) { 
                        
                                          $Parent_barcode = $data['itemparentitems']->vitemname.' ['.$data['itemparentitems']->vbarcode.']';
                                        }
                                      ?>

                                      <input type="text" name="Parent_barcode" maxlength="50" value="<?php echo isset($Parent_barcode) ? $Parent_barcode : ''; ?>" placeholder="Parent Barcode" class="form-control small adjustment-fields" style="font-size:12px;" readonly >
                                       
                                    </div>
                                  </div>
                              </div>
                              
                          </div>
                      </div>

                      <div class="row notLottery">
                          <div class="col-md-12 mx-auto">
                              
                              <div class="form-group row ">
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                          <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Food Item</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required form-inline">

                                        <?php if(isset($data['array_yes_no']) && count($data['array_yes_no']) > 0){?>
                                          <?php foreach($data['array_yes_no'] as $k => $array_y_n){ ?>
                                            <label class="radio-inline">
                                              <input type="radio" name="vfooditem" value="<?php echo $k;?>" <?php if($data['vfooditem'] == $k) echo "checked"; ?>><?php echo $array_y_n;?>&nbsp;&nbsp;
                                            </label>
                                          <?php } ?>
                                        <?php } ?>
                                          
                                      </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
    
                                      <label for="inputNumber" class="p-2 float-right text-uppercase">Bottle Deposit</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                      
                                      <input name="nbottledepositamt" id="nbottledepositamt" value="<?php echo isset($data['nbottledepositamt']) ? $data['nbottledepositamt'] : '0.00'; ?>" type="text" class="form-control adjustment-fields">
                                    </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputVendor" class="p-2 float-right text-uppercase">Gross Markup</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      
                                      <input type="text" name="discount_price" maxlength="50" value="<?php echo isset($data['bydown']) ? $data['bydown'] : ''; ?>" placeholder="Gross Markup" id="input-discount_price" class="form-control adjustment-fields" <?php if(isset($data['bydown']) && isset($data['edit_page'])){?>readonly <?php } ?> readonly/>
                                                                             
                                    </div>
                                  </div>
                              </div>
                              
                          </div>
                      </div>

                      <div class="row Lottery" style="display:none">
                          <div class="col-md-12 mx-auto">
                              
                              <div class="form-group row ">
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                          <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Ticket Price</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required form-inline">

                                        <input type="number" name="ticket_price" maxlength="50" value="<?php echo isset($data['dunitprice']) ? $data['dunitprice'] : ''; ?>" placeholder="Ticket Price" id="input-ticket_price" class="form-control adjustment-fields" autocomplete="off"/>
                                        <?php if (isset($data['error_ticket_price'])) { ?>
                                            <div class="text-danger"><?php echo $data['error_ticket_price']; ?></div>
                                        <?php } ?>
                                          
                                      </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
    
                                      <label for="inputNumber" class="p-2 float-right text-uppercase">Games Per Book</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                      <input type="text" name="games_per_book" maxlength="50" value="<?php echo isset($data['npack']) ? $data['npack'] : '1'; ?>" placeholder="Games Per Book" id="input-games_per_book" class="form-control adjustment-fields" autocomplete="off"/>
                                      <?php if (isset($data['error_games_per_book'])) { ?>
                                          <div class="text-danger"><?php echo $data['error_games_per_book']; ?></div>
                                      <?php } ?>
                                    </div>
                                  </div>
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputVendor" class="p-2 float-right text-uppercase">Book Qoh</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <input type="text" name="book_qoh" maxlength="50" value="<?php echo isset($data['QOH']) ? $data['QOH'] : ''; ?>" placeholder="Book Qoh" id="input-book_qoh" class="form-control adjustment-fields" autocomplete="off" readonly/>
                                      <?php if (isset($data['error_book_qoh'])) { ?>
                                          <div class="text-danger"><?php echo $data['error_book_qoh']; ?></div>
                                      <?php } ?>                                      
                                    </div>
                                  </div>
                              </div>
                              
                          </div>
                      </div>

                      <div class="row Lottery" style="display:none">
                          <div class="col-md-12 mx-auto">
                              
                              <div class="form-group row ">
                                  <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                          <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Book Cost</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required form-inline">

                                        <input type="number" name="book_cost" maxlength="50" value="<?php echo isset($data['dcostprice']) ? number_format((float)$data['dcostprice'], 2) : ''; ?>" placeholder="Book Cost" id="input-book_cost" class="form-control adjustment-fields" autocomplete="off"/>
                                        <?php if (isset($data['error_book_cost'])) { ?>
                                            <div class="text-danger"><?php echo $data['error_book_cost']; ?></div>
                                        <?php } ?>
                                          
                                      </div>
                                  </div>
                                  
                              </div>
                              
                          </div>
                      </div>
                    </div>
                    
                    
                </div>
                
                <div class="notLottery">

                  <div class="mytextdiv">
                    <div class="mytexttitle font-weight-bold text-uppercase">
                      Advance Options
                    </div>
                    <a class="btn btn-small text-center text-white" id="advance_options_hideshow" style="line-height:5px; width:100px; border-radius:6px; background-color:grey; font-size:9px;">SHOW ADVANCE</a>
                    &nbsp;
                    <div class="divider font-weight-bold"></div>
                    <input type="hidden" value="1" name="advance_options" id="advance_options">
                  </div>
                  
                    <div id="advance_options_checkbox_div" style="display: none;">
                        
                      <div class="py-3">
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                
                                <div class="form-group row">
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            <label for="inputFirstname" class="p-2 float-right text-uppercase">Status</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            
                                          <select name="estatus" class="form-control adjustment-fields">
                                            <?php if(isset($data['array_status']) && count($data['array_status']) > 0){?>
                                              <?php foreach($data['array_status'] as $k => $array_sts){ ?>
                                                <?php if($data['estatus'] == $array_sts) {?>
                                                  <option value="<?php echo $array_sts;?>" selected="selected"><?php echo $array_sts;?></option>
                                                <?php }else{?>
                                                  <option value="<?php echo $array_sts;?>"><?php echo $array_sts;?></option>
                                                <?php } ?>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
      
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">WIC</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-inline">
      
                                        <?php if(isset($data['array_yes_no']) && count($data['array_yes_no']) > 0){?>
                                          <?php foreach($data['array_yes_no'] as $k => $array_y_n){ ?>
                                          <?php if($data['wicitem'] == '1') $wicitem = 'Y'; else $wicitem ='N'; ?>
                                            <label class="radio-inline">
                                              <input type="radio" name="wicitem" class="form-control" value="<?php echo $k;?>" <?php if($wicitem == $k) echo "checked"; ?> ><?php echo $array_y_n;?>&nbsp;&nbsp;
                                            </label>
                                          <?php } ?>
                                        <?php } ?>
                                      </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">Discount</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <select name="vdiscount" class="form-control adjustment-fields">
                                          <?php if(isset($data['arr_y_n']) && count($data['arr_y_n']) > 0){?>
                                            <?php foreach($data['arr_y_n'] as $k => $array_y_n){ ?>
                                              <?php if($data['vdiscount'] == $array_y_n) {?>
                                                <option value="<?php echo $array_y_n;?>" selected="selected"><?php echo $array_y_n;?></option>
                                              <?php }else{?>
                                                <option value="<?php echo $array_y_n;?>"><?php echo $array_y_n;?></option>
                                              <?php } ?>
                                            <?php } ?>
                                          <?php } ?>
                                        </select>
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mx-auto">

                                <div class="form-group row">
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            <label for="inputFirstname" class="p-2 float-right text-uppercase">Reorder Duration</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            
                                          <input type="text" name="reorder_duration" maxlength="45" value="<?php echo isset($data['reorder_duration']) ? $data['reorder_duration'] : ''; ?>" placeholder="Reorder Duration" id="input_reorder_duration" class="form-control adjustment-fields"  />
                                          <span class="text-small" style="position: absolute"><b>Enter Order Duration in Days.</b></span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
      
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">Re-Order Point</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
      
                                        <input type="text" style="display: inline-block;"  name="ireorderpoint" value="<?php echo isset($data['ireorderpoint']) ? $data['ireorderpoint'] : ''; ?>" placeholder="Re-Order Point" id="input_reorderpoint" class="form-control adjustment-fields"  />
                                        <span class="text-small" ><b>Enter Reorder Point in Unit.</b></span>
                                      </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">Order Qty Upto</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <input type="text" name="norderqtyupto" value="<?php echo isset($data['norderqtyupto']) ? $data['norderqtyupto'] : ''; ?>" placeholder="Order Qty Upto" id="input_orderqtyupto" class="form-control adjustment-fields" />
                                        <span class="text-small" style="position: absolute"><b>Enter Order Qty Upto in Case.</b></span>
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                
                                <div class="form-group row">
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            <label for="inputFirstname" class="p-2 float-right text-uppercase">Aisle</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            
                                          <select name="aisleid" class="form-control adjustment-fields">
                                            <option value="">Select Mfg. Promo Desc</option>
                                            <?php if(isset($data['aisles']) && count((array)$data['aisles']) > 0){?>
                                              <?php foreach($data['aisles'] as $aisle){ ?>
                                                <?php if(isset($data['aisleid']) && $data['aisleid'] == $aisle->Id) {?>
                                                
                                                  <option value="<?php echo $aisle->Id;?>" selected="selected"><?php echo $aisle->aislename;?></option>
                                                <?php } else { ?>
                                                  <option value="<?php echo $aisle->Id;?>"><?php echo $aisle->aislename;?></option>
                                                <?php } ?>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
      
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">Mfg Buy Down Desc.</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
      
                                        <select name="shelfid" class="form-control adjustment-fields">
                                            <option value="">Select Mfg Buy Down Desc.</option>
                                            <?php if(isset($data['shelfs']) && count((array)$data['shelfs']) > 0){?>
                                            <?php foreach($data['shelfs'] as $shelf){ ?>
                                              <?php if(isset($data['shelfid']) && $data['shelfid'] == $shelf->Id){ ?>
                                                <option value="<?php echo $shelf->Id;?>" selected="selected"><?php echo $shelf->shelfname;?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $shelf->Id;?>"><?php echo $shelf->shelfname;?></option>
                                              <?php } ?>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">Mfg MultiPack Desc.</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <select name="shelvingid" class="form-control adjustment-fields">
                                          <option value="">Select Mfg MultiPack Desc.</option>
                                          <?php if(isset($data['shelvings']) && count($data['shelvings']) > 0){?>
                                            <?php foreach($data['shelvings'] as $shelving){ ?>
                                            <?php if(isset($data['shelvingid']) && $data['shelvingid'] == $shelving->id){ ?>
                                              <option value="<?php echo $shelving->id;?>" selected="selected"><?php echo $shelving->shelvingname;?></option>
                                            <?php } else { ?>
                                              <option value="<?php echo $shelving->id;?>"><?php echo $shelving->shelvingname;?></option>
                                            <?php } ?>
                                            <?php } ?>
                                          <?php } ?>
                                        </select> 
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                
                                <div class="form-group row">
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            <label for="inputFirstname" class="p-2 float-right text-uppercase">Barcode Type</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            
                                          <select name="vbarcodetype" class="form-control adjustment-fields">
                                            <?php if(isset($data['barcode_types']) && count($data['barcode_types']) > 0){?>
                                              <?php foreach($data['barcode_types'] as $barcode_type){ ?>
                                              <?php if(isset($data['vbarcodetype']) && $data['vbarcodetype'] == $barcode_type) { ?>
                                                <option value="<?php echo $barcode_type;?>" selected="selected"><?php echo $barcode_type;?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $barcode_type;?>"><?php echo $barcode_type;?></option>
                                              <?php } ?>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
      
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">Station</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
      
                                        <select name="stationid" class="form-control adjustment-fields">
                                          <option value="">--Select Station--</option>
                                          <?php if(isset($data['stations']) && count($data['stations']) > 0){?>
                                            <?php foreach($data['stations'] as $station){ ?>
                                            <?php if(isset($data['stationid']) && $data['stationid'] == $station->Id){ ?>
                                              <option value="<?php echo $station->Id;?>" selected="selected"><?php echo $station->stationname;?></option>
                                            <?php } else { ?>
                                              <option value="<?php echo $station->Id;?>"><?php echo $station->stationname;?></option>
                                            <?php } ?>
                                            <?php } ?>
                                          <?php } ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">Show Image</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <select name="vshowimage" class="form-control adjustment-fields">
                                            <?php if(isset($data['arr_y_n']) && count($data['arr_y_n']) > 0){?>
                                              <?php foreach($data['arr_y_n'] as $k => $array_y_n){ ?>
                                                <?php if($data['vshowimage'] == $array_y_n) {?>
                                                  <option value="<?php echo $array_y_n;?>" selected="selected"><?php echo $array_y_n;?></option>
                                                <?php }else{?>
                                                  <option value="<?php echo $array_y_n;?>"><?php echo $array_y_n;?></option>
                                                <?php } ?>
                                              <?php } ?>
                                            <?php } ?>
                                        </select>
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="row">
                          <div class="col-md-12 mx-auto">
                              
                            <div class="form-group row">
                                <div class="col-12 col-md-12 col-sm-2 col-lg-12 p-form">
                                    <div class="col-2 col-md-2 col-sm-6 col-lg-2">
                                        <label for="inputFirstname" class="p-2 float-right text-uppercase">Image</label>
                                    </div>
                                    <div class="col-8 col-md-8 col-sm-6 col-lg-8">
                                      <?php if(isset($data['itemimage']) && $data['itemimage'] != ''){?>
                                        <img src="data:image/gif;base64,<?php echo $data['itemimage'];?>" class="img-responsive" width="100" height="100" alt="" id="showImage" style="margin-left: -30px;">
                                        <br>
                                        <button class="btn btn-info btn-sm" id="remove_item_img">Remove</button>
                                        <br><br>
                                        <input type="hidden" name="pre_itemimage" value="<?php echo $data['itemimage'];?>">
                                      <?php } else { ?>
                                        <img src="{{ asset('image/user-icon-profile.png') }}" class="img-responsive" width="600" height="200" alt="" id="showImage" style="margin-left: -30px;"><br>
                                        <input type="hidden" name="pre_itemimage" value="">
                                      <?php } ?>
                                      <input type="file" name="itemimage" accept="image/x-png,image/gif,image/jpeg" onchange="showImages(this)">
                                    </div>
                                </div>
                                
                            </div>
      
                          </div>
                        </div>
                        
                      </div>
                        
                    </div>
                </div>
                <br>
                
                <div class="notLottery">

                  <div class="mytextdiv">
                    <div class="mytexttitle font-weight-bold text-uppercase">
                      PLCB
                    </div>
                    <a class="btn btn-small text-center text-white" id="plcb_options_hideshow" style="line-height:5px; width:100px; border-radius:6px; background-color:grey; font-size:9px;">PLCB ADVANCE</a>
                    &nbsp;
                    <div class="divider font-weight-bold"></div>
                    <input type="hidden" value="1" name="plcb_options" id="plcb_options">
                  </div>

                  <div id="plcb_options_checkbox_div" style="<?php if($data['plcb_options_checkbox']){ echo 'display: block'; }else{ echo 'display: none'; }?>">
                    <div class="py-3">
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                
                                <div class="form-group row">
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            <label for="inputFirstname" class="p-2 float-right text-uppercase">Unit</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                            
                                          <select name="unit_id" class="form-control adjustment-fields">
                                            <option value="">-- Select Unit --</option>
                                            <?php if(isset($data['itemsUnits']) && count($data['itemsUnits']) > 0){ ?>
                                              <?php foreach($data['itemsUnits'] as $unit){ ?>
                                                <?php if($unit->id == $data['unit_id']) {?>
                                                  <option value="<?php echo $unit->id; ?>" selected="selected"><?php echo $unit->unit_name; ?></option>
                                                <?php } else {?>
                                                  <option value="<?php echo $unit->id; ?>"><?php echo $unit->unit_name; ?></option>
                                                <?php } ?>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
                                          <?php if (isset($data['error_unit_id'])) { ?>
                                            <div class="text-danger"><?php echo $data['error_unit_id']; ?></div>
                                          <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
      
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">Unit Value</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
      
                                        <input type="text" class="form-control adjustment-fields" value="<?php echo isset($data['unit_value']) ? $data['unit_value'] : ''; ?>" name="unit_value">
                                        
                                        <?php if (isset($data['error_unit_value'])) { ?>
                                          <div class="text-danger"><?php echo $data['error_unit_value']; ?></div>
                                        <?php } ?>
                                      </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputLastname" class="p-2 float-right text-uppercase">Bucket</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <select name="bucket_id" class="form-control adjustment-fields">
                                          <option value="">-- Select Bucket --</option>
                                          <?php if(isset($data['buckets']) && count($data['buckets']) > 0){?>
                                            <?php foreach($data['buckets'] as $bucket){ ?>
                                              <?php if($bucket->id == $data['bucket_id']) {?>
                                                <option value="<?php echo $bucket->id;?>" selected="selected"><?php echo $bucket->bucket_name;?></option>
                                              <?php } else {?>
                                                <option value="<?php echo $bucket->id;?>"><?php echo $bucket->bucket_name;?></option>
                                              <?php } ?>
                                            <?php } ?>
                                          <?php } ?>
                                        </select>
                                        <?php if (isset($data['error_bucket_id'])) { ?>
                                          <div class="text-danger"><?php echo $data['error_bucket_id']; ?></div>
                                        <?php } ?>
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="row">
                          <div class="col-md-12 mx-auto">
                              
                            <div class="form-group row">
                                <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputFirstname" class="p-2 float-right text-uppercase">Malt</label>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <input style="margin-top: 10px;" type="checkbox" class="" name="malt" value="1" <?php if($data['malt']){ echo 'checked'; }?>>
                                    </div>
                                </div>
                                
                            </div>
      
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
          
            </form>
            <br>
            <div class="row">
              <div class="col-md-12 text-center">
                <!--<input type="submit" form="form-item" title="Save" class="btn btn-primary save_btn_rotate" value="Save">-->
                <input type="button" title="Save" class="btn button-blue basic-button-small save_btn_rotate formsubmit" value="Save">
                <a id="cancel_button" href="<?php echo $data['cancel']; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-default basic-button-small text-dark cancel_btn_rotate" style="border-color: black;"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
              </div>
            </div>
          </div>

          <div class="tab-pane" id="alias_code_tab" style="display: none;">
            <form action="<?php echo $data['add_alias_code']; ?>" method="post" enctype="multipart/form-data" id="form-item-alias-code" class="form-horizontal">
              @csrf
              <?php if(isset($data['iitemid'])){?>
                <input type="hidden" name="iitemid" value="<?php echo $data['iitemid'];?>">
              <?php } ?>
              
              <div class="mytextdiv">
                <div class="mytexttitle font-weight-bold text-uppercase">
                    Alias Code
                </div>
                <div class="divider font-weight-bold"></div>
              </div>

              <div class="py-3">
                  
                <div class="row">
                  <div class="col-md-12 mx-auto">
                      
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputFirstname" class="p-2 float-right text-uppercase">Alias Code:</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                              <input type="text" id="valiassku"  name="valiassku" maxlength="50" class="form-control adjustment-fields" required>
                              <input type="hidden" name="vitemcode" id="vitemcode" value="<?php echo isset($data['vitemcode']) ? $data['vitemcode'] : ''; ?>">
                              <input type="hidden" name="vsku" name="vsku" value="<?php echo isset($data['vbarcode']) ? $data['vbarcode'] : ''; ?>">
                              <?php if(session()->get('hq_sid') == 1){ ?>
                                  <input type="hidden" name="alias_stores_hq" value="" id="alias_stores_hq"  >
                              <?php } ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                              <input type="submit" name="Alias_code" value="Add Alias Code" class="btn button-blue basic-button-small">
                            </div>
                            
                        </div>
                    </div>

                  </div>
                </div>
              </div>

            </form>
            <br><br>
            <form action="<?php echo $data['alias_code_deletelist']; ?>" method="post" enctype="multipart/form-data" id="form-item-alias-list" class="form-horizontal">
                @csrf
                <div class="mytextdiv">
                  <div class="mytexttitle font-weight-bold text-uppercase">
                      Existing Codes
                  </div>
                  <div class="divider font-weight-bold"></div>
                </div>

              <div class="row">
                <div class="col-md-12">
                  <table class="table table-hover promotionview" style="width:30%;">
                    <thead>
                      <tr class="header-color">
                        <th style="width: 1px;" class="text-left text-uppercase"><input type="checkbox" onclick="$('input[name*=\'selected_alias\']').prop('checked', this.checked);" /></th>
                        <th>Alias sku</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($data['itemalias']) && count((array)$data['itemalias']) > 0){ ?>
                      <?php foreach($data['itemalias'] as $k => $alias) { ?>
                        <tr>
                          <td><input type="checkbox" name="selected_alias[]" value="<?php echo $alias->iitemaliasid; ?>" /></td>
                          <td><?php echo $alias->valiassku;?></td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12 text-left">
                  <input type="submit" title="Delete" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase" value="Delete" style="border-radius:0px;" <?php if(isset($data['itemalias']) && count($data['itemalias']) == 0){ ?> disabled="true" <?php } ?> >
                </div>
              </div>
            </form>
          </div>
          
          <div class="tab-pane" id="lot_matrix_tab" style="display: none;">
            <div class="row">
              <div class="col-md-4">
                <button class="btn button-blue basic-button-small" data-toggle="modal"  id="button_addLotItemModal">Add Lot Item</button>&nbsp;&nbsp;
                <form action="<?php echo $data['lot_matrix_deletelist']; ?>" method="post" id="delete_lot_items" style="display: inline-block;">
                @csrf
                  <input type="submit" class="btn btn-danger buttonred basic-button-small" value="Delete Lot Item" style="border-radius:0px;">
                </form>
              </div>
            </div>
            <br><br>
            <form action="<?php echo $data['lot_matrix_editlist']; ?>" method="post" enctype="multipart/form-data" id="form-item-lot-matrix-list1" class="form-horizontal">
                @csrf
                
                <?php if(session()->get('hq_sid') == 1) { ?>
                    <input type="hidden" id="store_hq_for_edit" name="store_hq_for_edit" value="" >
                <?php } ?>
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-hover promotionview" style="width: 100%;">
                    <thead>
                      <tr class="header-color">
                        <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected_lot_matrix\']').prop('checked', this.checked);" /></th>
                        <th class="text-left">Pack Name</th>
                        <th class="text-left">Description</th>
                        <th class="text-left">Unit Cost</th>
                        <th class="text-left">Pack Qty</th>
                        <th class="text-left">Pack Cost</th>
                        <th class="text-left">Price</th>
                        <th class="text-left">Sequence</th>
                        <th class="text-left">Profit Margin(%)</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($data['itempacks']) && count((array)$data['itempacks']) > 0){ ?>
                      <?php foreach($data['itempacks'] as $k => $itempack) { ?>
                        <tr>
                          <input type="hidden" name="itempacks[<?php echo $k; ?>][iitemid]" value="<?php echo $itempack->iitemid; ?>">
                          <input type="hidden" name="itempacks[<?php echo $k; ?>][idetid]" value="<?php echo $itempack->idetid; ?>">
                          <?php if($itempack->iparentid == 1){ ?>
                            <td><input type="checkbox" name="selected_lot_matrix[]" value="<?php echo $itempack->idetid; ?>" class="selected_lot_matrix_checkbox" /></td>
                          <?php } else { ?>
                            <td><input type="checkbox" class="selected_lot_matrix_checkbox" name="selected_lot_matrix[]" value="<?php echo $itempack->idetid; ?>" /></td>
                          <?php } ?>
                          <td>
                            <input type="text" class="editable adjustment-fields" name="itempacks[<?php echo $k; ?>][vpackname]" value="<?php echo $itempack->vpackname;?>" />
                          </td>
                          <td>
                            <?php //echo $itempack->vdesc;?>
                            <input type="text" class="editable adjustment-fields" name="itempacks[<?php echo $k; ?>][vdesc]" value="<?php echo $itempack->vdesc;?>" />
                          </td>
                          <td class="text-left"><?php echo number_format($data['nunitcost'], 2);?></td>
                          <td class="text-left"><?php echo $itempack->ipack;?></td>
                          <td class="text-left">
                            <!--<?php echo $itempack->npackcost;?>-->
                            <input type="text" class="editable adjustment-fields" id="input_npackcost" name="itempacks[<?php echo $k; ?>][npackcost]" value="<?php echo number_format($itempack->npackcost, 2);?>" />
                            <input type="hidden" class="input_npackcost" value="<?php echo $itempack->npackcost;?>">
                          </td>
                          <td class="text-left">
                            <input type="text" class="editable input_npackprice adjustment-fields" id='input_npackprice' name="itempacks[<?php echo $k; ?>][npackprice]" value="<?php echo $itempack->npackprice; ?>" style="text-align: right;" />
                          </td>
                          <td class="text-left"><?php echo $itempack->isequence;?></td>
                          <td class="text-left">
                            <span class="npackmargins"><?php echo $itempack->npackmargin;?></span>
                            <input class="input_npackmargins" type="hidden" name="itempacks[<?php echo $k; ?>][npackmargin]" value="<?php echo $itempack->npackmargin; ?>" />
                          </td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <input type="button" title="Update" id="save_pack_edit_index" class="btn button-blue basic-button-small save_btn_rotate" value="Update" <?php if(isset($data['itempacks']) && count((array)$data['itempacks']) == 0){ ?> disabled="true" <?php } ?> >
                </div>
              </div>
            </form>
          </div>

          <div class="tab-pane" id="vendor_tab" style="display: none;">
            <form action="<?php echo $data['action_vendor']; ?>" method="post" enctype="multipart/form-data" id="form-item-vendor" class="form-horizontal">
              @csrf
              <?php if(isset($data['iitemid'])){?>
                <input type="hidden" name="iitemid" value="<?php echo $data['iitemid'];?>">
              <?php } ?>
              <?php if(session()->get('stores_hq')){?>
                <input type="hidden" id="hiddenvendorAssignsave" name="hiddenvendorAssignsave" value="">
              <?php } ?>

              <div class="mytextdiv">
                <div class="mytexttitle font-weight-bold text-uppercase">
                    Vendor
                </div>
                <div class="divider font-weight-bold"></div>
              </div>

              <div class="py-3">
                  <div class="row">
                      <div class="col-md-12 mx-auto">
                          
                          <div class="form-group row">
                              <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                  <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <label for="inputFirstname" class="p-2 float-right text-uppercase">Vendor Item Code:&nbsp;&nbsp;</label>
                                  </div>
                                  <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      
                                    <input type="text" name="vvendoritemcode" maxlength="100" class="form-control adjustment-fields" required>
                                  </div>
                              </div>
                              <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                  <label for="inputLastname" class="p-2 float-right text-uppercase">Vendor:&nbsp;&nbsp;</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                  <select name="ivendorid" id="ivendorid" class="form-control adjustment-fields" required>
                                    <option value="">--Select Vendor--</option>
                                      <?php if(isset($data['suppliers']) && count($data['suppliers']) > 0){?>
                                        <?php foreach($data['suppliers'] as $supplier){ ?>
                                            <option value="<?php echo $supplier['vsuppliercode'];?>"><?php echo $supplier['vcompanyname'];?></option>
                                        <?php } ?>
                                      <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                  
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                  <input type="button" id="form-item-vendor-submit-btn" name="Assign" value="Assign" class="btn button-blue basic-button-small text-uppercase">
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                  <button class="btn buttonred basic-button-small text-uppercase" id="delete_item_vendor_btn"">Delete</button>                                    
                                </div>
                              </div>
                          </div>
                          
                      </div>
                  </div>

              </div>
            </form>
            <br><br>
            <form action="<?php echo $data['action_vendor_editlist']; ?>" method="post" enctype="multipart/form-data" id="form-item-vendor-list" class="form-horizontal">
             @csrf
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-hover promotionview" style="width:70%;">
                    <thead>
                      <tr class="header-color">
                        <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected_vendor_code\']').prop('checked', this.checked);" /></td>
                        <th class="text-left text-uppercase">Vendor Name</th>
                        <th class="text-left text-uppercase">Vendor Item Code</th>
                        <th class="text-left text-uppercase">Address</th>
                        <th class="text-left text-uppercase">Phone</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php if(session()->get('hq_sid') == 1){ ?>
                        <input type="hidden" name="vendor_update_stores" id="vendor_update_stores" value="">
                    <?php } ?>
                    <?php if(isset($data['itemvendors']) && count((array)$data['itemvendors']) > 0){ ?>
                      <?php foreach($data['itemvendors'] as $k => $itemvendor) { ?>
                        <tr>
                        <input type="hidden" name="itemvendors[<?php echo $k; ?>][iitemid]" value="<?php echo $itemvendor->iitemid; ?>">
                        <input type="hidden" name="itemvendors[<?php echo $k; ?>][ivendorid]" value="<?php echo $itemvendor->ivendorid; ?>">
                        <input type="hidden" name="itemvendors[<?php echo $k; ?>][Id]" value="<?php echo $itemvendor->Id; ?>">
                          <td class="text-left text-uppercase"><input type="checkbox" class="item_vendor_ids" name="selected_vendor_code[]" value="<?php echo $itemvendor->Id; ?>" /></td>
                          <td class="text-left text-uppercase"><?php echo $itemvendor->vcompanyname;?></td>
                          <td class="text-left text-uppercase">
                            <input type="text" class="editable" maxlength="100" name="itemvendors[<?php echo $k; ?>][vvendoritemcode]" value="<?php echo $itemvendor->vvendoritemcode; ?>" />
                          </td>
                          <td class="text-left text-uppercase"><?php echo $itemvendor->vaddress1;?></td>
                          <td class="text-left text-uppercase"><?php echo $itemvendor->vphone;?></td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <br>
            </form>
              <div class="row">
                <div class="col-md-12">
                  <button id="updateAllvendorsAssigned" class="btn btn-primary" <?php if(isset($data['itemvendors']) && count($data['itemvendors']) == 0){ ?> disabled="true" <?php } ?> > Update </button>
                </div>
              </div>
          </div>
         
        </div>

      </div>
    </div>
  </div>
  
</div>

<?php if(session()->get('hq_sid') == 1) { ?>
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title">Select the stores in which you want to add the Items:</h6>
            <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
          </div>
        
          <div class="modal-body">
             <table class="table promotionview">
                <thead id="table_green_header_tag">
                    <tr class="header-color">
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="selectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody id="new_item_data_stores">
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check stores" id="stores" name="stores" value="{{ $stores->id }}">
                                </div>
                            </td>
                            <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="save_btn" class="btn btn-danger buttonred buttons_menu basic-button-small" data-dismiss="modal">Save</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
    
      </div>
    </div>
<?php } ?>

@endsection


@section('page-script')

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">

<style type="text/css">
  .padding-left-right{
    padding: 0 2% 0 2%;
  }

  .add_new_administrations{
    float: right;
    margin-right: -35px;
    margin-top: -30px;
    cursor: pointer !important; 
    position: relative;
    z-index: 10;
  }
  .add_new_administrations i{
    cursor: pointer !important; 
  }
</style>

<script type="text/javascript">
  function showImages(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#showImage')
                .attr('src', e.target.result)
                .width(100)
                .height(100);
        };
        reader.readAsDataURL(input.files[0]);
    }
  }
</script>

<?php if(!isset($edit_page)){?>
    <style type="text/css">
      #myTab > li:nth-child(2), #myTab > li:nth-child(3),#myTab > li:nth-child(4),#myTab > li:nth-child(5),#myTab > li:nth-child(6){
        pointer-events: none;
      }
    </style>
<?php } ?>

<script type="text/javascript">
  $(document).on('change', 'select[name="vitemtype"]', function(event) {
    event.preventDefault();
    if($(this).val() == 'Lot Matrix'){
        $('#input-sellingunit').attr('readonly', 'readonly');
        $('.notLottery').show();
        $('.Lottery').hide();
    }else if($(this).val() == 'Instant'){
        // console.log($(this).val());
        $('.notLottery').hide();
        $('.Lottery').show();
    }else{
        $('#input-sellingunit').removeAttr('readonly');
        $('.notLottery').show();
        $('.Lottery').hide();
    }
  });

  $(document).on('keyup', '#input-unitpercase', function(event) {
    event.preventDefault();

    var unitpercase = $(this).val();

    if($('select[name="vitemtype"]').val() == 'Lot Matrix'){
      if(unitpercase == ''){
        $('#input-sellingunit').val('');
        unitpercase = 1;
      }else{
        $('#input-sellingunit').val($(this).val());
      }
    }

    var avg_case_cost = $('#input-avg_case_cost').val();

    if(avg_case_cost == ''){
      avg_case_cost = 0;
    }

    var unitcost = '0.0000';
    if(unitpercase != ''){
      var unitcost = avg_case_cost / unitpercase;
      unitcost = unitcost.toFixed(4);
    }

    $('#input-unitcost').val(unitcost);

    if(unitpercase!= '' && avg_case_cost != '' && $('#input-Selling Price').val() !=''){
      var sell_price = $('#input-Selling-Price').val();
      var per = sell_price - $('#input-unitcost').val();

      if(sell_price == 0 || sell_price == ''){
        sell_price = 1;
      }

      if(per > 0){
        per = per;
      }else{
        per = 0;
      }

      var pro_margin = ((per/sell_price) * 100).toFixed(2);
      
    }

  });

  $(document).on('keyup', '#input-avg_case_cost', function(event) {
    event.preventDefault();
    
    var avg_case_cost = $(this).val();

    if(avg_case_cost == ''){
      avg_case_cost = 0;
    }

    var unitpercase = $('#input-unitpercase').val();

    if(unitpercase == ''){
      unitpercase = 0;
    }

    var unitcost = '0.0000';
    if(avg_case_cost != ''){
      var unitcost = avg_case_cost / unitpercase  ;
      unitcost = unitcost.toFixed(4);
    }

    $('#input-unitcost').val(unitcost);

    if(unitpercase!= '' && avg_case_cost != '' && $('#input-Selling Price').val() !=''){
      var sell_price = $('#input-Selling-Price').val();
      var per = sell_price - $('#input-unitcost').val();

      if(sell_price == 0 || sell_price == ''){
        sell_price = 1;
      }

      if(per > 0){
        per = per;
      }else{
        per = 0;
      }

      var pro_margin = ((per/sell_price) * 100).toFixed(2);
    
    }

  });

  $(document).on('keyup', '#input-Selling-Price', function(event) {
    event.preventDefault();

    var input_Selling_Price = $(this).val();
    var unitpercase = $('#input-unitpercase').val();
    var avg_case_cost = $('#input-avg_case_cost').val();

    if(input_Selling_Price == ''){
      input_Selling_Price = 0;
      $('#input-profit-margin').val('0.00');
      return false;
    }

    if(unitpercase!= '' && avg_case_cost != '' && input_Selling_Price !=''){
      var sell_price = $('#input-Selling-Price').val();
      var per = sell_price - $('#input-unitcost').val();

      if(sell_price == 0 || sell_price == ''){
        sell_price = 1;
      }

      if(per > 0){
        per = per;
      }else{
        per = 0;
      }

      var pro_margin = ((per/sell_price) * 100).toFixed(2);

    //   $('#input-profit-margin').val(pro_margin);
    }


  });
</script>

<script type="text/javascript">
  $(document).on('submit', 'form#form-item-alias-code', function(event) {
    event.preventDefault();
    
    var sid = "<?php echo $data['sid'];?>";
    var url = $(this).attr('action');
    var data = {};

    data['vitemcode']  = $(this).find('input[name=vitemcode]').val();
    data['vsku']  = $(this).find('input[name=vsku]').val();
    data['valiassku']  = $(this).find('input[name=valiassku]').val();

    $.ajax({
      url : url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
      },
      data : JSON.stringify(data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
    success: function(data) {
      $('#success_alias').html('<strong>'+ data.success +'</strong>');
      $('#successAliasModal').modal('show');
      $.cookie("tab_selected", 'alias_code_tab'); //set cookie tab
      setTimeout(function(){
       window.location.reload();
      }, 3000);
    },
    error: function(xhr) { // if error occured
      var  response_error = $.parseJSON(xhr.responseText); //decode the response array
      var error_show = '';
      if(response_error.error){
        error_show = response_error.error;
      }else if(response_error.validation_error){
        error_show = response_error.validation_error[0];
      }

      $('#error_alias').html('<strong>'+ error_show +'</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }
  });
    return false;
  });
</script>

<!-- Modal -->
  <div class="modal fade" id="successAliasModal" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success text-center">
            <p id="success_alias"></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <div class="modal fade" id="errorAliasModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger text-center">
            <p id="error_alias"></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>

  
<script type="text/javascript">
  $(document).on('submit', 'form#form-item-alias-list', function(event) {
    event.preventDefault();
    var sid = "<?php echo $data['sid'];?>";
    var url = $(this).attr('action');
    var data = {};

    if($("input[name='selected_alias[]']:checked").length == 0){
      $('#error_alias').html('<strong>Select Aliassku to delete</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }

    $("input[name='selected_alias[]']:checked").each(function (i)
    {
      data[i] = parseInt($(this).val());
    });

    $.ajax({
      url : url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
      },
      data : JSON.stringify(data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
    success: function(data) {

      $('#success_alias').html('<strong>'+ data.success +'</strong>');
      $('#successAliasModal').modal('show');
      $.cookie("tab_selected", 'alias_code_tab'); //set cookie tab
      setTimeout(function(){
       window.location.reload();
      }, 3000);
      
    },
    error: function(xhr) { // if error occured
      var  response_error = $.parseJSON(xhr.responseText); //decode the response array
      
      var error_show = '';

      if(response_error.error){
        error_show = response_error.error;
      }else if(response_error.validation_error){
        error_show = response_error.validation_error[0];
      }

      $('#error_alias').html('<strong>'+ error_show +'</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }
  });
    return false;
  });
</script>

<!-- Modal -->
<div id="addLotItemModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form action="<?php echo $data['add_lot_matrix'];?>" method="post" id="add_lot_matrix">
    @csrf
      <?php if(isset($data['iitemid'])){?>
        <input type="hidden" name="iitemid" value="<?php echo $data['iitemid'];?>">
      <?php } ?>
      <?php if(isset($data['vbarcode'])){?>
        <input type="hidden" name="vbarcode" value="<?php echo $data['vbarcode'];?>">
      <?php } ?>
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Item Pack</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-3 text-center">
              <span><b>Pack Name:&nbsp;&nbsp;&nbsp;</b></span>
            </div>
            <div class="col-md-5">
              <input class="form-control" type="text" name="vpackname" maxlength="30" required>
            </div>
            <div class="col-md-3"></div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-3 text-center">
              <span><b>Description:&nbsp;&nbsp;&nbsp;</b></span>
            </div>
            <div class="col-md-5">
              <input class="form-control" type="text" name="vdesc" maxlength="50" >
            </div>
            <div class="col-md-3"></div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-3 text-center">
              <span><b>Pack Qty:&nbsp;&nbsp;&nbsp;</b></span>
            </div>
            <div class="col-md-5">
              <input class="form-control" type="text" name="ipack" id="ipack" required>
            </div>
            <div class="col-md-3"></div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-3 text-center">
              <span><b>Cost Price:&nbsp;&nbsp;&nbsp;</b></span>
            </div>
            <div class="col-md-5">
              <input class="form-control" type="text" id="npackcost" name="npackcost" required value="<?php echo isset($data['nunitcost'])? number_format((float)$data['nunitcost'], 2, '.', '') : '';?>" readonly>
            </div>
            <div class="col-md-3"></div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-3 text-center">
              <span><b>Price:&nbsp;&nbsp;&nbsp;</b></span>
            </div>
            <div class="col-md-5">
              <input class="form-control" type="text" id="npackprice" name="npackprice">
            </div>
            <div class="col-md-3"></div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-3 text-center">
              <span><b>Sequence:&nbsp;&nbsp;&nbsp;</b></span>
            </div>
            <div class="col-md-5">
              <input class="form-control" type="text" name="isequence">
            </div>
            <div class="col-md-3"></div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-3 text-center">
              <span><b>Profit Margin(%):&nbsp;&nbsp;&nbsp;</b></span>
            </div>
            <div class="col-md-5">
              <input class="form-control" type="text" id="npackmargin" name="npackmargin" readonly>
            </div>
            <div class="col-md-3"></div>
          </div>
          <br>
          
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-success" value="Add">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $(document).on('keyup', '#ipack', function(event) {
    event.preventDefault();

    <?php if(isset($data['nunitcost'])){ ?>
      var unitcost = '<?php echo $data['nunitcost'];?>';
    <?php }else{ ?>
      var unitcost = 0;
    <?php } ?>

    var ipack = $('#ipack').val();
    if(ipack == ''){
      var ipack = 0;
      $('#npackcost').val(unitcost);
      return false;
    }
    
    var npackcost = 0;

    if(ipack != '' && unitcost != ''){
      npackcost = unitcost * ipack;
    }

    $('#npackcost').val(npackcost.toFixed(2));

    if($('#npackprice').val() != ''){

      var npackcost = $('#npackcost').val();
      var npackprice = $('#npackprice').val();
      
      var percent = npackprice - npackcost;

      if(npackprice == '' || npackprice == 0 ){
        npackprice = 1;
      }

      if(percent > 0){
        percent = percent;
      }else{
        percent = 0;
      }
        
      // percent = (percent/(npackprice*100)).toFixed(2);
      percent = ((percent/npackprice)*100).toFixed(2);

      $('#npackmargin').val(percent);
    }

  });

  $(document).on('keyup', '#npackprice', function(event) {
    event.preventDefault();
    var npackprice = $('#npackprice').val();

    if(npackprice != ''){
      if(npackprice == ''){
        var npackprice = 0;
      }

      var npackcost = $('#npackcost').val();

      if(npackcost == ''){
        <?php if(isset($data['nunitcost'])){ ?>
          var npackcost = '<?php echo $data['nunitcost'];?>';
        <?php }else{ ?>
          var npackcost = 0;
        <?php } ?>
      }

    var percent = npackprice - npackcost;

    if(npackprice == '' || npackprice == 0 ){
      npackprice = 1;
    }

    if(percent > 0){
      percent = percent;
    }else{
      percent = 0;
    }

    percent = ((percent/npackprice)*100).toFixed(2);
    // percent = (percent/(npackprice*100)).toFixed(2);
    
    $('#npackmargin').val(percent);
  }else{
    $('#npackmargin').val('');
  }
  
  });


$(document).on('keyup', '.input_npackprice', function(event) {
    event.preventDefault();
    $(this).closest('tr').find('.selected_lot_matrix_checkbox').attr('checked', 'checked');
    var input_npackprice = $(this).val();
    var input_npackcost = $(this).closest('tr').find('.input_npackcost').val();
    var input_npackmargins = input_npackprice - input_npackcost;
    if(input_npackprice == '' || input_npackprice == 0 ){
      input_npackprice = 1;
    }
    input_npackmargins = ((input_npackmargins/input_npackprice) * 100);
    input_npackmargins = input_npackmargins.toFixed(2);
    $(this).closest('tr').find('.input_npackmargins').val(input_npackmargins);
    $(this).closest('tr').find('.npackmargins').html(input_npackmargins);
});

$(document).on('keypress', '#input_npackcost', function(event){
    var character = String.fromCharCode(event.keyCode)
    var newValue = this.value + character;
    if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '#input_npackprice', function(event){
    
    var character = String.fromCharCode(event.keyCode)
    var newValue = this.value + character;
    if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '#nbottledepositamt', function(event) {
    
    var character = String.fromCharCode(event.keyCode)
    var newValue = this.value + character; 
    if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
        event.preventDefault();
        return false;
    }
});

</script>


<script type="text/javascript">
  $(document).on('submit', 'form#add_lot_matrix', function(event) {
    event.preventDefault();
    var sid = "<?php echo $data['sid'];?>";
    var url = $(this).attr('action');
    var data = {};

    data['iitemid'] = $('form#add_lot_matrix').find('input[name="iitemid"]').val();
    data['vbarcode'] = $('form#add_lot_matrix').find('input[name="vbarcode"]').val();
    data['vpackname'] = $('form#add_lot_matrix').find('input[name="vpackname"]').val();
    data['vdesc'] = $('form#add_lot_matrix').find('input[name="vdesc"]').val();
    data['ipack'] = $('form#add_lot_matrix').find('input[name="ipack"]').val();
    data['npackcost'] = $('form#add_lot_matrix').find('input[name="npackcost"]').val();
    data['npackprice'] = $('form#add_lot_matrix').find('input[name="npackprice"]').val();
    data['isequence'] = $('form#add_lot_matrix').find('input[name="isequence"]').val();
    data['npackmargin'] = $('form#add_lot_matrix').find('input[name="npackmargin"]').val();
    
    $.ajax({
      url : url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
      },
      data : JSON.stringify(data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
    success: function(data) {
      
      $('#success_alias').html('<strong>'+ data.success +'</strong>');
      $('#addLotItemModal').modal('hide');
      $('#successAliasModal').modal('show');
      $.cookie("tab_selected", 'lot_matrix_tab'); //set cookie tab
      setTimeout(function(){
       window.location.reload();
      }, 3000);
      
    },
    error: function(xhr) { // if error occured
      var  response_error = $.parseJSON(xhr.responseText); //decode the response array
      
      var error_show = '';
      
      if(response_error.error){
        error_show = response_error.error;
      }else if(response_error.validation_error){
        error_show = response_error.validation_error[0];
      }

      $('#error_alias').html('<strong>'+ error_show +'</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }
  });
    return false;
  });
</script>

<script type="text/javascript">
  $(document).on('submit', 'form#delete_lot_items', function(event) {
    event.preventDefault();
    var sid = "<?php echo $data['sid'];?>";
    var url = $(this).attr('action');
    var data = {};

    if($("input[name='selected_lot_matrix[]']:checked").length == 0){
      $('#error_alias').html('<strong>Select Lot Items to delete</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }

    $("input[name='selected_lot_matrix[]']:checked").each(function (i)
    {
      data[i] = parseInt($(this).val());
    });

    $.ajax({
      url : url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
      },
      data : JSON.stringify(data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
    success: function(data) {

      $('#success_alias').html('<strong>'+ data.success +'</strong>');
      $('#successAliasModal').modal('show');
      $.cookie("tab_selected", 'lot_matrix_tab'); //set cookie tab
      setTimeout(function(){
       window.location.reload();
      }, 3000);
      
    },
    error: function(xhr) { // if error occured
      var  response_error = $.parseJSON(xhr.responseText); //decode the response array
      
      var error_show = '';

      if(response_error.error){
        error_show = response_error.error;
      }else if(response_error.validation_error){
        error_show = response_error.validation_error[0];
      }

      $('#error_alias').html('<strong>'+ error_show +'</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }
  });
    return false;
  });
</script>


<script type="text/javascript">
/*  $(document).on('submit', 'form#form-item-add-slab-price', function(event) {
    event.preventDefault();
    
    var url = $(this).attr('action');
    var data = {};

    var slab_price_vsku = $(this).find('input[name="vsku"]').val();
    var slab_price_iitemgroupid = $(this).find('input[name="iitemgroupid"]').val();
    var slab_price_iqty = $(this).find('input[name="iqty"]').val();
    var slab_price_nprice = $(this).find('input[name="nprice"]').val();

    data['vsku'] = slab_price_vsku;
    data['iitemgroupid'] = slab_price_iitemgroupid;
    data['iqty'] = slab_price_iqty;
    data['nprice'] = slab_price_nprice;

    $.ajax({
      url : url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
      },
      data : JSON.stringify(data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
    success: function(data) {

      $('#success_alias').html('<strong>'+ data.success +'</strong>');
      $('#successAliasModal').modal('show');
      $.cookie("tab_selected", 'slab_price_tab'); //set cookie tab
      setTimeout(function(){
       window.location.reload();
      }, 3000);
      
    },
    error: function(xhr) { // if error occured
      var  response_error = $.parseJSON(xhr.responseText); //decode the response array
      
      var error_show = '';

      if(response_error.error){
        error_show = response_error.error;
      }else if(response_error.validation_error){
        error_show = response_error.validation_error[0];
      }

      $('#error_alias').html('<strong>'+ error_show +'</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }
  });
    return false;
  });
  */
</script>

<script type="text/javascript">
  $(document).on('keyup', '.slab_price_iqty', function(event) {
    event.preventDefault();
    $(this).closest('tr').find('.selected_slab_price_checkbox').attr('checked', 'checked');
    var slab_price_iqty = $(this).val();
    var slab_price_nprice = $(this).closest('tr').find('.slab_price_nprice').val();

    if(slab_price_iqty != ''){
      if(slab_price_nprice == ''){
        slab_price_nprice = 0;
      }

      var slab_price_nunitprice = slab_price_nprice / slab_price_iqty;
      slab_price_nunitprice = slab_price_nunitprice.toFixed(2);
      $(this).closest('tr').find('.slab_price_nunitprice').html(slab_price_nunitprice);
      $(this).closest('tr').find('.input_slab_price_nunitprice').val(slab_price_nunitprice);

    }
  });

  $(document).on('keyup', '.slab_price_nprice', function(event) {
    event.preventDefault();
    $(this).closest('tr').find('.selected_slab_price_checkbox').attr('checked', 'checked');

    var slab_price_nprice = $(this).val();
    var slab_price_iqty = $(this).closest('tr').find('.slab_price_iqty').val();

    if(slab_price_nprice != ''){
      if(slab_price_iqty == ''){
        slab_price_iqty = 0;
      }

      var slab_price_nunitprice = slab_price_nprice / slab_price_iqty;
      slab_price_nunitprice = slab_price_nunitprice.toFixed(2);
      $(this).closest('tr').find('.slab_price_nunitprice').html(slab_price_nunitprice);
      $(this).closest('tr').find('.input_slab_price_nunitprice').val(slab_price_nunitprice);

    }
  });
</script>

<script type="text/javascript">
/*  $(document).on('submit', 'form#delete_slab_price_items', function(event) {
    event.preventDefault();
    
    var url = $(this).attr('action');
    var data = {};

    if($("input[name='selected_slab_price[]']:checked").length == 0){
      $('#error_alias').html('<strong>Select Items to delete</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }

    $("input[name='selected_slab_price[]']:checked").each(function (i)
    {
      data[i] = parseInt($(this).val());
    });

    $.ajax({
      url : url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
      },
      data : JSON.stringify(data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
    success: function(data) {

      $('#success_alias').html('<strong>'+ data.success +'</strong>');
      $('#successAliasModal').modal('show');
      $.cookie("tab_selected", 'slab_price_tab'); //set cookie tab
      setTimeout(function(){
       window.location.reload();
      }, 3000);
      
    },
    error: function(xhr) { // if error occured
      var  response_error = $.parseJSON(xhr.responseText); //decode the response array
      
      var error_show = '';

      if(response_error.error){
        error_show = response_error.error;
      }else if(response_error.validation_error){
        error_show = response_error.validation_error[0];
      }

      $('#error_alias').html('<strong>'+ error_show +'</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }
  });
    return false;
  });
  */
</script>

<script type="text/javascript">
/*  $(document).on('submit', 'form#form_add_parent_item', function(event) {
    event.preventDefault();

    var url = $(this).attr('action');
    var data = {};
    
    var parent_item_id = $(this).find('select[name="parent_item_id"]').val();
    var child_item_id = $(this).find('input[name="child_item_id"]').val();

    data['parent_item_id'] = parent_item_id;
    data['child_item_id'] = child_item_id;
    
    $.ajax({
      url : url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
      },
      data : JSON.stringify(data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
    success: function(data) {

      $('#success_alias').html('<strong>'+ data.success +'</strong>');
      $('#successAliasModal').modal('show');
      $.cookie("tab_selected", 'parent_tab'); //set cookie tab
      setTimeout(function(){
       window.location.reload();
      }, 3000);
      
    },
    error: function(xhr) { // if error occured
      var  response_error = $.parseJSON(xhr.responseText); //decode the response array
      
      var error_show = '';

      if(response_error.error){
        error_show = response_error.error;
      }else if(response_error.validation_error){
        error_show = response_error.validation_error[0];
      }

      $('#error_alias').html('<strong>'+ error_show +'</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }
  });

});

*/
</script>

<script type="text/javascript">
/*
  $(document).on('submit', 'form#remove_parent_item', function(event) {
    event.preventDefault();
    
    var url = $(this).attr('action');

    var data = {};
    var selected_parent_item = []

    var iitemid = $(this).find('input[name="iitemid"]').val();

    if($("input[name='selected_parent_item[]']:checked").length == 0){
      $('#error_alias').html('<strong>Select Items to Remove</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }

    $("input[name='selected_parent_item[]']:checked").each(function (i)
    {
      selected_parent_item[i] = parseInt($(this).val());
    });

    var selected_parent_item_id =selected_parent_item[0];

    data['iitemid'] = iitemid;
    data['selected_parent_item_id'] = selected_parent_item_id;
    
    $.ajax({
      url : url,
      data : JSON.stringify(data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
    success: function(data) {
      
      $('#success_alias').html('<strong>'+ data.success +'</strong>');
      $('#successAliasModal').modal('show');
      $.cookie("tab_selected", 'parent_tab'); //set cookie tab
      setTimeout(function(){
       window.location.reload();
      }, 3000);
      
    },
    error: function(xhr) { // if error occured
      var  response_error = $.parseJSON(xhr.responseText); //decode the response array
      
      var error_show = '';

      if(response_error.error){
        error_show = response_error.error;
      }else if(response_error.validation_error){
        error_show = response_error.validation_error[0];
      }

      $('#error_alias').html('<strong>'+ error_show +'</strong>');
      $('#errorAliasModal').modal('show');
      return false;
    }
  });

});
*/
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    // if ((!!$.cookie('tab_selected')) && ($.cookie('tab_selected') != '')) {
    //   var tab_s = $.cookie('tab_selected');
    //   // have cookie
    //   $('#myTab li.active').removeClass('active');
    //   $('.tab-content div.tab-pane.active').removeClass('active');

    //   if(tab_s == 'alias_code_tab'){
    //     $('#myTab li:eq(1)').addClass('active');
    //     $('.tab-content #alias_code_tab').addClass('active');
    //   }else if(tab_s == 'lot_matrix_tab'){
    //     $('#myTab li:eq(3)').addClass('active');
    //     $('.tab-content #lot_matrix_tab').addClass('active');
    //   }else if(tab_s == 'vendor_tab'){
    //     $('#myTab li:eq(4)').addClass('active');
    //     $('.tab-content #vendor_tab').addClass('active');
    //   }
    // } else {
    //  // no cookie
    //  var tab_selected = "<?= $data['tab_selected']; ?>";

    //  <?php if(isset($data['tab_selected']) && !empty($data['tab_selected'])){?>
    //   $('#myTab li.active').removeClass('active');
    //   $('.tab-content div.tab-pane.active').removeClass('active');

    //   <?php if($data['tab_selected'] == 'alias_code_tab'){ ?>
    //     $('#myTab li:eq(1)').addClass('active');
    //     $('.tab-content #alias_code_tab').addClass('active');
    //   <?php }else if($data['tab_selected'] == 'lot_matrix_tab'){ ?>
    //     $('#myTab li:eq(3)').addClass('active');
    //     $('.tab-content #lot_matrix_tab').addClass('active');
    //   <?php }else if($data['tab_selected'] == 'vendor_tab'){ ?>
    //     $('#myTab li:eq(4)').addClass('active');
    //     $('.tab-content #vendor_tab').addClass('active');
    //   <?php }else { ?>
    //     $('#myTab li:eq(0)').addClass('active');
    //     $('.tab-content #item_tab').addClass('active');
    //   <?php } ?>

    // <?php } ?>
    // }

});

</script>

<script type="text/javascript">
  $(document).on('submit', 'form#form-item1', function() {
    $.cookie("tab_selected", ''); //set cookie tab
  });

  $(document).on('submit', 'form#form-item-lot-matrix-list', function() {
    $.cookie("tab_selected", ''); //set cookie tab
  });

  $(document).on('submit', 'form#form-item-vendor', function() {
    $.cookie("tab_selected", ''); //set cookie tab
  });

  $(document).on('submit', 'form#form-item-vendor-list', function() {
    $.cookie("tab_selected", ''); //set cookie tab
  });

  $(document).on('submit', 'form#form-item-slab-price-list', function() {
    $.cookie("tab_selected", ''); //set cookie tab
  });

  $(document).on('click', '#cancel_button, #menu li a, .breadcrumb li a', function() {
    $.cookie("tab_selected", ''); //set cookie tab
  });

  $(document).on('keypress keyup blur', '#input-unitpercase', function(event) {

    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    
  }); 

  $(document).on('keypress keyup blur', '.slab_price_iqty', function(event) {

    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    
  });

  $(document).on('keypress keyup blur', '.slab_price_nprice,.input_npackprice', function(event) {

    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
    
  });  

  $(document).on('keypress keyup blur', 'input[name="iqtyonhand"], input[name="norderqtyupto"],input[name="iqty"],input[name="ipack"],input[name="ireorderpoint"],input[name="isequence"]', function(event) {

    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    
  }); 

  $(document).on('keypress keyup blur', 'input[name="dcostprice"],input[name="nlevel2"],input[name="nlevel3"],input[name="nlevel4"],input[name="dunitprice"],input[name="ndiscountper"],input[name="nprice"],input[name="npackprice"]', function(event) {

    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }

  });

  $(document).on('focusout', 'input[name="dcostprice"],input[name="nlevel2"],input[name="nlevel3"],input[name="nlevel4"],input[name="dunitprice"],input[name="ndiscountper"],input[name="nprice"],input[name="npackprice"]', function(event) {
    event.preventDefault();

    if($(this).val() != ''){
      if($(this).val().indexOf('.') == -1){
        var new_val = $(this).val();
        $(this).val(new_val+'.00');
      }else{
        var new_val = $(this).val();
        if(new_val.split('.')[1].length == 0){
          $(this).val(new_val+'00');
        }
      }
    }
  });

  $(document).on('focusout', '.slab_price_nprice,.input_npackprice', function(event) {
    event.preventDefault();

    if($(this).val() != ''){
      if($(this).val().indexOf('.') == -1){
        var new_val = $(this).val();
        $(this).val(new_val+'.00');
      }else{
        var new_val = $(this).val();
        if(new_val.split('.')[1].length == 0){
          $(this).val(new_val+'00');
        }
      }
    }
  });  

</script>

<style type="text/css">
  .tab-content.responsive{
      background: #f1f1f1;
      padding-top: 2%;
      padding-bottom: 2%;
      padding-left: 1%;
      padding-right: 2%;
  }
  .nav-tabs{
      margin-bottom:0px;
  }

  .select2-container--default .select2-selection--single{
    border-radius: 7px !important;
    height: 35px !important;
  }
  .select2.select2-container.select2-container--default{
  width: 100% !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 35px !important;
  }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">
  $('select[name="vitemtype"]').select2();
  $('select[name="vdepcode"]').select2();
  $('select[name="vcategorycode"]').select2();
  $('select[name="aisleid"]').select2();
  $('select[name="vsuppliercode"]').select2();
  $('select[name="shelfid"]').select2();
  $('select[name="vunitcode"]').select2();
  $('select[name="iitemgroupid"]').select2();
  $('select[name="shelvingid"]').select2();
  $('select[name="vsize"]').select2();
  $('select[name="vageverify"]').select2();
  $('select[name="stationid"]').select2();
  $('select[name="vbarcodetype"]').select2();
</script>

<script type="text/javascript">
  $(document).on('click', '#remove_item_img', function(event) {
    event.preventDefault();
    $('#showImage').attr('src', 'view/image/user-icon-profile.png');
    $('input[name="pre_itemimage"]').val('');
    $(this).hide();
    $('select[name="vshowimage"]').val('No');

  });
</script>

<script type="text/javascript">
  $(document).on('click', '#add_new_category', function(event) {
    event.preventDefault();

    $('form#category_add_new_form').find('#add_vcategoryname').val('');
    $('form#category_add_new_form').find('#category_add_vdescription').val('');

    $('#addModalCatogory').modal('show');
  });

  $(document).on('submit', 'form#category_add_new_form', function(event) {
    event.preventDefault();
    
    if($(this).find('#add_vcategoryname').val() == ''){
      alert('Please enter category name!');
      return false;
    }

    var url = $(this).attr('action');

    var data = new Array();

    data[0]={};
    data[0]['vcategoryname'] = $(this).find('#add_vcategoryname').val();
    data[0]['vdescription'] = $(this).find('#category_add_vdescription').val();
    data[0]['vcategorttype'] = $(this).find('select[name="vcategorttype"]').val();
    data[0]['isequence'] = $(this).find('input[name="isequence"]').val();
    
      $.ajax({
        url : url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        data : JSON.stringify(data),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
      success: function(data) {
        
        $('#success_alias').html('<strong>'+ data.success +'</strong>');
        $('#addModalCatogory').modal('hide');
        $('#successAliasModal').modal('show');

        setTimeout(function(){
         $('#successAliasModal').modal('hide');
        }, 3000);
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }

        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorAliasModal').modal('show');
        return false;
      }
    });
      setTimeout(function(){
        var get_new_category = "<?php echo $data['get_new_category'];?>";
        get_new_category = get_new_category.replace(/&amp;/g, '&');
        // get_new_category = get_new_category+"&sid="+sid;

        $.getJSON(get_new_category, function(datas) {
          $('select[name="vcategorycode"]').empty();
          var category_html = '';
          $.each(datas, function(index,v) {
            category_html += '<option value="'+ v.icategoryid +'">'+ v.vcategoryname +'</option>';
          });
          $('select[name="vcategorycode"]').append(category_html);
        });
      }, 3000);
  });
</script>

<!-- Modal Add -->
  <div class="modal fade" id="addModalCatogory" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Category under <span id="span_dept_name"></span></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_category']; ?>" method="post" id="category_add_new_form">
             @csrf
            <input type="hidden" name="isequence" value="0">
            <div class="row">
              <div class="col-12 col-md-12 col-sm-12 col-lg-12 p-form">
                  <div class="col-3 col-md-3 col-sm-3 col-lg-3">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Name</label>
                  </div>
                  <div class="col-9 col-md-9 col-sm-9 col-lg-9">
                    
                    <input type="text" maxlength="50" class="form-control adjustment-fields" id="add_vcategoryname" name="vcategoryname">
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-md-12 col-sm-12 col-lg-12 p-form">
                  <div class="col-3 col-md-3 col-sm-3 col-lg-3">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Description</label>
                  </div>
                  <div class="col-9 col-md-9 col-sm-9 col-lg-9">
                    <textarea maxlength="100" name="vdescription" id="category_add_vdescription" class="form-control adjustment-fields"></textarea>
                    
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-md-12 col-sm-12 col-lg-12 p-form">
                  <div class="col-3 col-md-3 col-sm-3 col-lg-3">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Type</label>
                  </div>
                  <div class="col-9 col-md-9 col-sm-9 col-lg-9">
                    <select name="vcategorttype" id="" class="form-control adjustment-fields">
                      <option value="<?php echo $data['Sales']; ?>" selected="selected"><?php echo $data['Sales']; ?></option>
                      <option value="<?php echo $data['MISC']; ?>" ><?php echo $data['MISC']; ?></option>
                    </select>
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-md-12 col-sm-12 col-lg-12 p-form">
                  <div class="col-3 col-md-3 col-sm-3 col-lg-3">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Department</label>
                  </div>
                  <div class="col-9 col-md-9 col-sm-9 col-lg-9">
                    <input type="text" name="category_dept_name" id="category_dept_name" class="form-control adjustment-fields" value="" disabled="true">
                    <input type="hidden" name="category_dept_code" id="category_dept_code" class="form-control" value="<?php echo isset($data['vdepcode'])?$data['vdepcode']:''; ?>">
                    
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn button-blue basic-button-small" type="submit" value="Save">
                <button type="button" class="btn btn-default basic-button-small text-dark" data-dismiss="modal" style="border-color: black;">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
<!-- Modal Add-->

<script type="text/javascript">
  $(document).on('click', '#add_new_department', function(event) {
    event.preventDefault();

    $('form#department_add_new_form').find('#add_vdepartmentname').val('');
    $('form#department_add_new_form').find('#category_add_vdescription').val('');

    $('#addModalDepartment').modal('show');
  });

  $(document).on('submit', 'form#department_add_new_form', function(event) {
    event.preventDefault();
    
    if($(this).find('#add_vdepartmentname').val() == ''){
      alert('Please enter department name!');
      return false;
    }

    var url = $(this).attr('action');

    var data = new Array();

    data[0]={};
    data[0]['vdepartmentname'] = $(this).find('#add_vdepartmentname').val();
    data[0]['vdescription'] = $(this).find('#department_add_vdescription').val();
    data[0]['isequence'] = $(this).find('input[name="isequence"]').val();
    
      $.ajax({
        url : url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        data : JSON.stringify(data),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
      success: function(data) {
        
        $('#success_alias').html('<strong>'+ data.success +'</strong>');
        $('#addModalDepartment').modal('hide');
        $('#successAliasModal').modal('show');

        setTimeout(function(){
         $('#successAliasModal').modal('hide');
        }, 3000);
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }

        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorAliasModal').modal('show');
        return false;
      }
    });
      setTimeout(function(){
        var get_new_department = "<?php echo $data['get_new_department'];?>";
        get_new_department = get_new_department.replace(/&amp;/g, '&');
        // get_new_department = get_new_department+"&sid="+sid;

        $.getJSON(get_new_department, function(datas) {
          $('select[name="vdepcode"]').empty();
          var department_html = '';
          $.each(datas, function(index,v) {
            department_html += '<option value="'+ v.vdepcode +'">'+ v.vdepartmentname +'</option>';
          });
          $('select[name="vdepcode"]').append(department_html);
        });
      }, 3000);
  });
</script>

<!-- Modal Add -->
  <div class="modal fade" id="addModalDepartment" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Department</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_department']; ?>" method="post" id="department_add_new_form">
             @csrf
            <input type="hidden" name="isequence" value="0">
            <div class="row">
              
              <div class="col-12 col-md-12 col-sm-12 col-lg-12 p-form">
                  <div class="col-3 col-md-3 col-sm-3 col-lg-3">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Name</label>
                  </div>
                  <div class="col-9 col-md-9 col-sm-9 col-lg-9">
                    <input type="text" maxlength="50" class="form-control adjustment-fields" id="add_vdepartmentname" name="vdepartmentname">
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              
              <div class="col-12 col-md-12 col-sm-12 col-lg-12 p-form">
                  <div class="col-3 col-md-3 col-sm-3 col-lg-3">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Description</label>
                  </div>
                  <div class="col-9 col-md-9 col-sm-9 col-lg-9">
                    <textarea maxlength="100" name="vdescription" id="department_add_vdescription" class="form-control adjustment-fields"></textarea>
                  </div>
              </div>

            </div>
            <br>
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn button-blue basic-button-small" type="submit" value="Save">
                <button type="button" class="btn btn-default basic-button-small text-dark" data-dismiss="modal" style="border-color: black;">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
<!-- Modal Add-->

<!-- Modal Add New Sub Category -->

<script type="text/javascript">
   $(document).on('click', '#add_new_subcategory', function(event) {
    event.preventDefault();
    // console.log($("#category_code option:selected").text());
    
    
    var cat_code = $("#category_code").val();
    
    if(cat_code == ""){
        $("#span_cat_name").html($("#category_code option:selected").text());
        $('#success_alias').html('<strong>Please select a Category !!</strong>');
        $('#successAliasModal').modal('show');

        setTimeout(function(){
         $('#successAliasModal').modal('hide');
        }, 3000);
        
    } else {
        $("#span_cat_name").html($("#category_code option:selected").text());
        $('form#subcategory_add_new_form').find('#add_subcat_name').val('');
        //$('form#category_add_new_form').find('#category_add_vdescription').val('');

        $('#addModalSubCatogory').modal('show');
    }
    
    
  });
  
  $(document).on('submit', 'form#subcategory_add_new_form', function(event) {
    event.preventDefault();
    
    //console.log("The function is called");
    
    if($(this).find('#add_subcat_name').val() == ''){
      alert('Please enter subcategory name!');
      return false;
    }

    var url = $(this).attr('action');

    var data = new Array();
    var subcategoryId;

    data[0]={};
    data[0]['subcat_name'] = $(this).find('#add_subcat_name').val();
    
    data[0]['cat_id'] = $("#category_code").val();
    
      $.ajax({
        url : url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        data : JSON.stringify(data),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
      success: function(resp) {
         //console.log((resp);
         //console.log(resp.subcat_id);
        subcategoryId = resp.subcat_id;
        //console.log(subcategoryId);
        
        $('#success_alias').html('<strong>'+ resp.success +'</strong>');
        $('#addModalSubCatogory').modal('hide');
        $('#successAliasModal').modal('show');

        setTimeout(function(){
         $('#successAliasModal').modal('hide');
        }, 3000);
      },
    //   error: function(xhr) { // if error occured
    //     // var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
    //     var error_show = 'Somethning went Wrong';

    //     // if(response_error.error){
    //     //   error_show = response_error.error;
    //     // }else if(response_error.validation_error){
    //     //   error_show = response_error.validation_error[0];
    //     // }

    //     $('#error_alias').html('<strong>'+ error_show +'</strong>');
    //     $('#errorAliasModal').modal('show');
    //     return false;
    //   }
    // });
    error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }

        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorAliasModal').modal('show');
        return false;
      }
    });
      setTimeout(function(){
        var input = {};
        var cat_id = $("#category_code").val();
        input['cat_id'] = cat_id;
        
        var url = '<?php echo $data['get_subcategories_url']; ?>';
            // url += "&sid="+sid;
            url = url.replace(/&amp;/g, '&');  
        
        $.ajax({
            url : url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : JSON.stringify(input),
            type : 'POST',
            contentType: "application/json",
            dataType: 'text json',
            success: function(response) {
        
                // console.log(response);
                var category_html = '';
                if(response.length > 0){
                   // console.log(subcat_id);
                    $('select[name="subcat_id"]').select2().empty().select2({data: response});
                }
                //console.log(subcategoryId);
                $('#subcat_id').val(subcategoryId).trigger('change.select2');
            },
            error: function(xhr) { 
                // if error occured
                // console.log(xhr);
                return false;
            }
        });
      }, 3000);
  });
</script>

  <div class="modal fade" id="addModalSubCatogory" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Sub Category - <span id="span_cat_name">  </span></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_subcategory']; ?>" method="post" id="subcategory_add_new_form">
             @csrf
            <input type="hidden" name="isequence" value="0">
            <div class="row">
              <div class="col-12 col-md-12 col-sm-12 col-lg-12 p-form">
                  <div class="col-3 col-md-3 col-sm-3 col-lg-3">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Name</label>
                  </div>
                  <div class="col-9 col-md-9 col-sm-9 col-lg-9">
                    <input type="text" maxlength="50" class="form-control adjustment-fields" id="add_subcat_name" name="subcat_name">
                  </div>
              </div>
            </div>
            <br>
            
            <br>
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn button-blue basic-button-small" type="submit" value="Save">
                <button type="button" class="btn btn-default basic-button-small text-dark" data-dismiss="modal" style="border-color: black;">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>

<!-- Modal Add New Sub Category -->

<script type="text/javascript">
  $(document).on('click', '#add_new_size', function(event) {
    event.preventDefault();

    $('form#size_add_new_form').find('#add_vsize').val('');

    $('#addModalSize').modal('show');
  });

  $(document).on('submit', 'form#size_add_new_form', function(event) {
    event.preventDefault();
    
    if($(this).find('#add_vsize').val() == ''){
      alert('Please enter size!');
      return false;
    }

    var url = $(this).attr('action');

    var data = new Array();

    data[0]={};
    data[0]['vsize'] = $(this).find('#add_vsize').val();
    
      $.ajax({
        url : url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        data : JSON.stringify(data),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
      success: function(data) {
        
        $('#success_alias').html('<strong>'+ data.success +'</strong>');
        $('#addModalSize').modal('hide');
        $('#successAliasModal').modal('show');

        setTimeout(function(){
         $('#successAliasModal').modal('hide');
        }, 3000);
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }

        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorAliasModal').modal('show');
        return false;
      }
    });
      setTimeout(function(){
        var get_new_size = "<?php echo $data['get_new_size'];?>";
        get_new_size = get_new_size.replace(/&amp;/g, '&');
        // get_new_size = get_new_size+"&sid="+sid;

        $.getJSON(get_new_size, function(datas) {
          $('select[name="vsize"]').empty();
          var size_html = '';
          $.each(datas, function(index,v) {
            size_html += '<option value="'+ v.vsize +'">'+ v.vsize +'</option>';
          });
          $('select[name="vsize"]').append(size_html);
        });
      }, 3000);
  });
</script>

<!-- Modal Add -->
  <div class="modal fade" id="addModalSize" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Size</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_size']; ?>" method="post" id="size_add_new_form">
              @csrf
            <div class="row">
              <div class="col-12 col-md-12 col-sm-12 col-lg-12 p-form">
                  <div class="col-3 col-md-3 col-sm-3 col-lg-3">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Name</label>
                  </div>
                  <div class="col-9 col-md-9 col-sm-9 col-lg-9">
                    <input type="text" maxlength="50" class="form-control adjustment-fields" id="add_vsize" name="vsize">
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn button-blue basic-button-small" type="submit" value="Save">
                <button type="button" class="btn btn-default basic-button-small text-dark" data-dismiss="modal" style="border-color: black;">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
<!-- Modal Add-->

<script type="text/javascript">
  $(document).on('click', '#add_new_group', function(event) {
    event.preventDefault();

    $('form#group_add_new_form').find('#add_vitemgroupname').val('');

    $('#addModalGroup').modal('show');
  });

  $(document).on('submit', 'form#group_add_new_form', function(event) {
    event.preventDefault();
    
    if($(this).find('#add_vitemgroupname').val() == ''){
      alert('Please enter group name!');
      return false;
    }

    var url = $(this).attr('action');

    var data = new Array();

    data[0]={};
    data[0]['vitemgroupname'] = $(this).find('#add_vitemgroupname').val();
    data[0]['etransferstatus'] = '';
    
      $.ajax({
        url : url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        data : JSON.stringify(data),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
      success: function(data) {
        
        $('#success_alias').html('<strong>'+ data.success +'</strong>');
        $('#addModalGroup').modal('hide');
        $('#successAliasModal').modal('show');

        setTimeout(function(){
         $('#successAliasModal').modal('hide');
        }, 3000);
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }

        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorAliasModal').modal('show');
        return false;
      }
    });
      setTimeout(function(){
        var get_new_group = "<?php echo $data['get_new_group'];?>";
        get_new_group = get_new_group.replace(/&amp;/g, '&');
        // get_new_group = get_new_group+"&sid="+sid;

        $.getJSON(get_new_group, function(datas) {
          $('select[name="iitemgroupid"]').empty();
          var group_html = '';
          $.each(datas, function(index,v) {
            group_html += '<option value="'+ v.iitemgroupid +'">'+ v.vitemgroupname +'</option>';
          });
          $('select[name="iitemgroupid"]').append(group_html);
        });
      }, 3000);
  });
</script>

<!-- Modal Add -->
  <div class="modal fade" id="addModalGroup" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Group</h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_group']; ?>" method="post" id="group_add_new_form">
              @csrf
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="50" class="form-control" id="add_vitemgroupname" name="vitemgroupname">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn btn-success" type="submit" value="Save">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
<!-- Modal Add-->

<script type="text/javascript">
  $(document).on('click', '#add_new_supplier', function(event) {
    event.preventDefault();

    $('form#supplier_add_new_form').find('#add_vcompanyname').val('');
    $('form#supplier_add_new_form').find('input[name="vfnmae"]').val('');
    $('form#supplier_add_new_form').find('input[name="vlname"]').val('');
    $('form#supplier_add_new_form').find('input[name="vcode"]').val('');
    $('form#supplier_add_new_form').find('input[name="vaddress1"]').val('');
    $('form#supplier_add_new_form').find('input[name="vcity"]').val('');
    $('form#supplier_add_new_form').find('input[name="vstate"]').val('');
    $('form#supplier_add_new_form').find('input[name="vphone"]').val('');
    $('form#supplier_add_new_form').find('input[name="vzip"]').val('');
    $('form#supplier_add_new_form').find('input[name="vemail"]').val('');

    $('#addModalSupplier').modal('show');
  });

  $(document).on('submit', 'form#supplier_add_new_form', function(event) {
    event.preventDefault();
    
    if($(this).find('#add_vcompanyname').val() == ''){
      alert('Please enter vendor name!');
      return false;
    }
 
    var url = $(this).attr('action');

    var data = new Array();

    data[0]={};
    data[0]['vcompanyname'] = $(this).find('#add_vcompanyname').val();
    data[0]['vvendortype'] = $(this).find('select[name="vvendortype"]').val();
    data[0]['vfnmae'] = $(this).find('input[name="vfnmae"]').val();
    data[0]['vlname'] = $(this).find('input[name="vlname"]').val();
    data[0]['vcode'] = $(this).find('input[name="vcode"]').val();
    data[0]['vaddress1'] = $(this).find('input[name="vaddress1"]').val();
    data[0]['vcity'] = $(this).find('input[name="vcity"]').val();
    data[0]['vstate'] = $(this).find('input[name="vstate"]').val();
    data[0]['vphone'] = $(this).find('input[name="vphone"]').val();
    data[0]['vzip'] = $(this).find('input[name="vzip"]').val();
    data[0]['vcountry'] = $(this).find('input[name="vcountry"]').val();
    data[0]['vemail'] = $(this).find('input[name="vemail"]').val();
    data[0]['plcbtype'] = $(this).find('select[name="plcbtype"]').val();
    data[0]['estatus'] = 'Active';

      $.ajax({
        url : url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        data : JSON.stringify(data),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
      success: function(data) {
        
        $('#success_alias').html('<strong>'+ data.success +'</strong>');
        $('#addModalSupplier').modal('hide');
        $('#successAliasModal').modal('show');

        setTimeout(function(){
         $('#successAliasModal').modal('hide');
        }, 3000);
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }

        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorAliasModal').modal('show');
        return false;
      }
    });
      setTimeout(function(){
        var get_new_supplier = "<?php echo $data['get_new_supplier'];?>";
        get_new_supplier = get_new_supplier.replace(/&amp;/g, '&');
        // get_new_supplier = get_new_supplier+"&sid="+sid;

        $.getJSON(get_new_supplier, function(datas) {
          $('select[name="vsuppliercode"]').empty();
          var supplier_html = '';
          $.each(datas, function(index,v) {
            supplier_html += '<option value="'+ v.vsuppliercode +'">'+ v.vcompanyname +'</option>';
          });
          $('select[name="vsuppliercode"]').append(supplier_html);
        });
      }, 3000);

  });

  $(document).on('keypress keyup blur', '#add_vzip', function(event) {

    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    
  }); 
</script>

<!-- Modal Add -->
  <div class="modal fade" id="addModalSupplier" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Supplier</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body small">
          <form action="<?php echo $data['add_new_supplier']; ?>" method="post" id="supplier_add_new_form">
              @csrf
            
            <div class="form-group row">
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputFirstname" class="p-2 float-right text-uppercase">Vendor Name</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="50" class="form-control adjustment-fields" id="add_vcompanyname" name="vcompanyname">
                  </div>
              </div>
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Vendor Type</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <select name="vvendortype" class="form-control adjustment-fields"> 
                    <option value="Vendor">Vendor</option>
                    <option value="Other">Other</option>
                  </select>
                  </div>
              </div>
            </div>
            
            <br>
            <div class="form-group row">
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputFirstname" class="p-2 float-right text-uppercase">First Name</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="50" class="form-control adjustment-fields" id="add_vfnmae" name="vfnmae">
                  </div>
              </div>
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Last Name</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="25" class="form-control adjustment-fields" id="add_vlname" name="vlname">
                  </div>
              </div>
            </div>
            <br>
            <div class="form-group row">
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputFirstname" class="p-2 float-right text-uppercase">Vendor Code</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="50" class="form-control adjustment-fields" id="add_vcode" name="vcode">
                  </div>
              </div>
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Address</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="25" class="form-control adjustment-fields" id="add_vaddress1" name="vaddress1">
                  </div>
              </div>
            </div>
            <br>
            <div class="form-group row">
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputFirstname" class="p-2 float-right text-uppercase">City</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="50" class="form-control adjustment-fields" id="add_vcity" name="vcity">
                  </div>
              </div>
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">State</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="25" class="form-control adjustment-fields" id="add_vstate" name="vstate">
                  </div>
              </div>
            </div>
            <br>
            <div class="form-group row">
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputFirstname" class="p-2 float-right text-uppercase">Phone</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="50" class="form-control adjustment-fields" id="add_vphone" name="vphone">
                  </div>
              </div>
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Zip</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="25" class="form-control adjustment-fields" id="add_vzip" name="vzip">
                  </div>
              </div>
            </div>
            <br>
            <div class="form-group row">
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputFirstname" class="p-2 float-right text-uppercase">Country</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="text" maxlength="20" class="form-control adjustment-fields" id="add_vcountry" name="vcountry" value="USA" readonly>
                  </div>
              </div>
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputLastname" class="p-2 float-right text-uppercase">Email</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <input type="email" maxlength="100" class="form-control adjustment-fields" id="add_vemail" name="vemail" onkeyup="isEmail()" autocomplete="off" />
                    <span class="text-warning" id="email_error"></span>
                  </div>
              </div>
            </div>
            <br>
            <div class="form-group row">
              <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
                  <div class="col-4 col-md-4 col-sm-4 col-lg-4">
                      <label for="inputFirstname" class="p-2 float-right text-uppercase">PLCB Type</label>
                  </div>
                  <div class="col-8 col-md-8 col-sm-8 col-lg-8">
                    <select name="plcbtype" class="form-control adjustment-fields">
                      <option value="None">None</option>
                      <option value="Schedule A">Schedule A</option>
                      <option value="Schedule B">Schedule B</option>
                      <option value="Schedule C">Schedule C</option>
                    </select>
                  </div>
              </div>
              
            </div>
            <br>
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn button-blue basic-button-small" type="submit" id="save_supplier" value="Save">
                <button type="button" class="btn btn-default basic-button-small text-dark" data-dismiss="modal" style="border-color: black;">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
<!-- Modal Add-->

<script src="{{ asset('javascript/jquery.maskedinput.min.js') }}"></script>
<script type="text/javascript">
  jQuery(function($){
    $("input[name='vphone']").mask("999-999-9999");
  });
</script>


<script type="text/javascript">
  $(window).on('load', function() {
    $("div#divLoading").removeClass('show');
  });
</script>

<script type="text/javascript">

  $(document).on('keypress keyup blur', 'input[name="percent_selling_price"]', function(event) {

    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }

  });

  $(document).on('click', '#selling_price_calculation_btn', function(event) {
    event.preventDefault();
    $('#sellingPercentageModal').modal('show');
  });


  $(document).on('click', '#selling_percent_calculate_btn', function(event) {
    event.preventDefault();

    if($("input[name='percent_selling_price']").val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: 'Please Enter Profit Margin!', 
        callback: function(){}
      });
      return false;
    }

    var per = parseFloat($("input[name='percent_selling_price']").val());
    var prof_mar = parseFloat($("input[name='percent_selling_price']").val());

    if(per == '0' || per == 0){
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: 'Profit Margin Should not be Zero!', 
        callback: function(){}
      });
      return false;
    }

    //  per = 100 - per;
    //  if (per == 0){
    //   per = 100;
    //  }
    //  var nUnitCost = parseFloat($('input[name="nunitcost"]').val());
    //  var revenue = (100/per)*nUnitCost;

    //  revenue = revenue.toFixed(2);

    //  $('input[name="dunitprice"]').val(revenue);
    //  $('input[name="profit_margin"]').val(prof_mar.toFixed(2));
    
    var new_costprice = $('#input-new_cost').val();
    var selling_price = ((new_costprice*100)/(100-prof_mar));
    // console.log(selling_price);
    selling_price = selling_price.toFixed(2);
    //  revenue = revenue.toFixed(2);
  // console.log(per);
     $('input[name="dunitprice"]').val(selling_price);
     $('input[name="gross_profit"]').val(prof_mar.toFixed(2));

     $('#sellingPercentageModal').modal('hide');

  });
</script>

<!-- Modal -->
<div id="sellingPercentageModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Calculate Selling Price</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p class="text-center"><strong>Enter Your Profit Margin and it will Calculate Your Selling Price.</strong></p>
        <p class="text-center"><span style="display: inline-block;"><input type="text" name="percent_selling_price" class="form-control adjustment-fields"></span>&nbsp;<span><b>%</b></span></p>
        <p class="text-center">
          <button type="button" class="btn button-blue basic-button-small" id="selling_percent_calculate_btn">Calculate</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </p>
        
      </div>
    </div>

  </div>
</div>

<div id="descriptionModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Item Description</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <label>Description</label>
            </div>
            <div class="col-md-8">
                <input type="text" id="input-description" name="description" maxlength="100" value="<?php echo isset($data['vdescription']) ? $data['vdescription'] : ''; ?>" placeholder="Description" class="form-control adjustment-fields" />
            </div>
        </div>
        <br>
        <?php if (isset($data['error_vdescription'])) { ?>
          <div class="text-danger"><?php echo $data['error_vdescription']; ?></div>
        <?php } ?>
        <p class="text-center">
          <button type="button" class="btn button-blue basic-button-small" id="save_description_btn">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </p>
        
      </div>
    </div>

  </div>
</div>

<div id="levelPricingModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Level Pricing</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
              <div class="col-5 col-md-5 col-sm-5 col-lg-5">
                  <label for="inputFirstname" class="p-2 float-right text-uppercase">Level 2 Price</label>
              </div>
              <div class="col-7 col-md-7 col-sm-7 col-lg-7">
                <input type="text" class="form-control adjustment-fields" name="nlevel2" value="<?php echo isset($data['nlevel2']) ? $data['nlevel2'] : ''; ?>" placeholder="Level 2 Price" id="input-level2price">
              </div>
          </div>
          <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
              <div class="col-5 col-md-5 col-sm-5 col-lg-5">
                  <label for="inputLastname" class="p-2 float-right text-uppercase">Gross Profit(%)</label>
              </div>
              <div class="col-7 col-md-7 col-sm-7 col-lg-7">
                <span style="display: inline-block;width: 85%;"><input type="text" name="gross_profit2" value="" placeholder="" id="input-gross-profit2" class="form-control adjustment-fields" readonly /></span>
                <span style="display: inline-block;width: 10%" id="selling_price_calculation_btn_l2"><button class="btn btn-sm btn-info" title="">..</button></span>
              </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
              <div class="col-5 col-md-5 col-sm-5 col-lg-5">
                  <label for="inputFirstname" class="p-2 float-right text-uppercase">Level 3 Price</label>
              </div>
              <div class="col-7 col-md-7 col-sm-7 col-lg-7">
                <input type="text" class="form-control adjustment-fields" name="nlevel3" value="<?php echo isset($data['nlevel3']) ? $data['nlevel3'] : ''; ?>" placeholder="Level 3 Price" id="input-level3price">
              </div>
          </div>
          <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
              <div class="col-5 col-md-5 col-sm-5 col-lg-5">
                  <label for="inputLastname" class="p-2 float-right text-uppercase">Gross Profit(%)</label>
              </div>
              <div class="col-7 col-md-7 col-sm-7 col-lg-7">
                <span style="display: inline-block;width: 85%;"><input type="text" name="gross_profit3" value="" placeholder="" id="input-gross-profit3" class="form-control adjustment-fields" readonly /></span>
                <span style="display: inline-block;width: 10%" id="selling_price_calculation_btn_l3"><button class="btn btn-sm btn-info" title="">..</button></span>
              </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
              <div class="col-5 col-md-5 col-sm-5 col-lg-5">
                  <label for="inputFirstname" class="p-2 float-right text-uppercase">Level 4 Price</label>
              </div>
              <div class="col-7 col-md-7 col-sm-7 col-lg-7">
                <input type="text" class="form-control adjustment-fields" name="nlevel2" value="<?php echo isset($data['nlevel4']) ? $data['nlevel2'] : ''; ?>" placeholder="Level 4 Price" id="input-level4price">
              </div>
          </div>
          <div class="col-12 col-md-6 col-sm-2 col-lg-6 p-form">
              <div class="col-5 col-md-5 col-sm-5 col-lg-5">
                  <label for="inputLastname" class="p-2 float-right text-uppercase">Gross Profit(%)</label>
              </div>
              <div class="col-7 col-md-7 col-sm-7 col-lg-7">
                <span style="display: inline-block;width: 85%;"><input type="text" name="gross_profit4" value="" placeholder="" id="input-gross-profit4" class="form-control adjustment-fields" readonly /></span>
                <span style="display: inline-block;width: 10%" id="selling_price_calculation_btn_l4"><button class="btn btn-sm btn-info" title="">..</button></span>
              </div>
          </div>
        </div>
        <br>
        <p class="text-center">
          <button type="button" class="btn button-blue basic-button-small" id="save_price_btn">Save</button>
          <button type="button" class="btn btn-default basic-button-small text-dark" data-dismiss="modal" style="border-color: black;">Cancel</button>
        </p>
        
      </div>
    </div>

  </div>
</div>

<div id="costModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cost</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-4 form-group">
                <label>Last Cost</label>
            </div>
            <div class="col-md-8">
                <input type="text" name="lastcost" value="<?php echo isset($data['last_costprice']) ? $data['last_costprice'] : ''; ?>" placeholder="Last Cost" id="input-lastcost" class="form-control adjustment-fields" readonly/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label>Case Cost</label>
            </div>
            <div class="col-md-8">
                <input type="text" name="avgcost" name="dcostprice" value="<?php echo isset($data['dcostprice']) ? $data['dcostprice'] : ''; ?>" placeholder="Avg. Case Cost" id="input-avg_case_cost" class="form-control adjustment-fields" autocomplete="off" <?php if(isset($data['isparentchild']) && $data['isparentchild'] == 1){?> readonly <?php } ?>  />
            </div>
        </div>
        
        <p class="text-center">
          <button type="button" class="btn button-blue basic-button-small" id="save_cost_btn">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </p>
        
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
  $(document).on('change', 'input[name="plcb_options_checkbox"]', function(event) {
    event.preventDefault();
    if ($(this).prop('checked')==true){ 
        $('#plcb_options_checkbox_div').show('slow');
    }else{
        $('#plcb_options_checkbox_div').hide('slow');
    }
  });
  
  
  $(document).on('change', 'input[name="advance_options_checkbox"]', function(event) {
    event.preventDefault();
    if ($(this).prop('checked')==true){ 
        $('#advance_options_checkbox_div').show('slow');
    }else{
        $('#advance_options_checkbox_div').hide('slow');
    }
  });
  
  
  $(document).ready(function(){
      
    if ($('input[name="advance_options_checkbox"]').prop('checked')==true && input[name="advance_options_checkbox1"] == true){ 
        $('#advance_options_checkbox_div').show();
    }else{
      $('#advance_options_checkbox_div').hide();
    }
    
        var itemtype = '<?php echo $data['vitemtype'] ?>';
        
        if(itemtype == 'Instant'){
        
            $('.notLottery').hide();
            $('.Lottery').show();   
        }
  });
</script>

<script type="text/javascript">
  $(document).on('change', 'input[name="plcb_options_checkbox"]', function(event) {
    event.preventDefault();
    if ($(this).prop('checked')==true){ 
        $('#plcb_options_checkbox_div').show('slow');
    }else{
      $('#plcb_options_checkbox_div').hide('slow');
    }
  });
</script>

<script type="text/javascript">
//   $('input[name="vitemname"], input[name="vdescription"]').keypress(function(event) {
//     var character = String.fromCharCode(event.keyCode);
//     var t = isValid(character); 
//       if(!t){
//         event.preventDefault();
//       }    
//   });

// $(document).ready(function(){
    
//     var clone_item_id = '<?php echo $data['clone_item_id']; ?>';
    
//     if(clone_item_id == ''){
//         $('#parent_tab').addClass("disabled");
//         $('#alias_code_tab').addClass("disabled");
//         $('#vendor_tab').addClass("disabled");
//         // $('#slab_price_tab').addClass("disabled");
//     } 
// });

$(document).ready(function(){
  
  $(document).on('click', '#add_description', function(event) {
    event.preventDefault();
    $('#descriptionModal').modal('show');
  });
  
  $(document).on('click', '#add_cost', function(event) {
    event.preventDefault();
    $('#costModal').modal('show');
  });
  
  $(document).on('click', '#add_pricelevel', function(event) {
    event.preventDefault();
    $('#levelPricingModal').modal('show');
  });
  
  $(document).on('click', '#save_description_btn', function(event) {
    
    var description_value = $('#input-description').val();
    $('#description_value').val(description_value);
    $('#descriptionModal').modal('hide');
  });
  
  $(document).on('click', '#save_price_btn', function(event) {
    
    var nlevel2_value = $('#input-level2price').val();
    var nlevel3_value = $('#input-level3price').val();
    var nlevel4_value = $('#input-level4price').val();
    
    $('#nlevel2_value').val(nlevel2_value);
    $('#nlevel3_value').val(nlevel3_value);
    $('#nlevel4_value').val(nlevel4_value);
    
    $('#levelPricingModal').modal('hide');
  });
  
    $(document).on('click', '#save_cost_btn', function(event) {
    
        var lastcost_value = $('#input-lastcost').val();
        var avgcost_value = $('#input-avg_case_cost').val();
        
        $('#lastcost_value').val(lastcost_value);
        $('#avgcost_value').val(avgcost_value);
        
        $('#costModal').modal('hide');
    });
    
    $(document).on('click', '#selling_price_calculation_btn_l2', function(event) {
        event.preventDefault();
        $('#sellingPercentageModal2').modal('show');
    });
      
    $(document).on('click', '#selling_price_calculation_btn_l3', function(event) {
        event.preventDefault();
        $('#sellingPercentageModal3').modal('show');
    });
      
    $(document).on('click', '#selling_price_calculation_btn_l4', function(event) {
        event.preventDefault();
        $('#sellingPercentageModal4').modal('show');
    });
});
</script>

<!-------- For Level 2 Price-------->
<div id="sellingPercentageModal2" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Calculate Selling Price</h4>
      </div>
      <div class="modal-body">
        <p class="text-center"><strong>Enter Your Gross Profit and it will Calculate Your Selling Price Level 2.</strong></p>
        <p class="text-center"><span style="display: inline-block;"><input type="text" name="percent_selling_price_l2" class="form-control"></span>&nbsp;<span><b>%</b></span></p>
        <p class="text-center">
          <button type="button" class="btn btn-info" id="selling_percent_calculate_btn_l2">Calculate</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </p>
        
      </div>
    </div>

  </div>
</div>


<!-------- For Level 3 Price-------->
<div id="sellingPercentageModal3" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Calculate Selling Price</h4>
      </div>
      <div class="modal-body">
        <p class="text-center"><strong>Enter Your Gross Profit and it will Calculate Your Selling Price Level 3.</strong></p>
        <p class="text-center"><span style="display: inline-block;"><input type="text" name="percent_selling_price_l3" class="form-control"></span>&nbsp;<span><b>%</b></span></p>
        <p class="text-center">
          <button type="button" class="btn btn-info" id="selling_percent_calculate_btn_l3">Calculate</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </p>
        
      </div>
    </div>

  </div>
</div>


<!-------- For Level 4 Price-------->
<div id="sellingPercentageModal4" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Calculate Selling Price</h4>
      </div>
      <div class="modal-body">
        <p class="text-center"><strong>Enter Your Gross Profit and it will Calculate Your Selling Price Level 4.</strong></p>
        <p class="text-center"><span style="display: inline-block;"><input type="text" name="percent_selling_price_l4" class="form-control"></span>&nbsp;<span><b>%</b></span></p>
        <p class="text-center">
          <button type="button" class="btn btn-info" id="selling_percent_calculate_btn_l4">Calculate</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </p>
        
      </div>
    </div>

  </div>
</div>

<script>
    $(document).ready(function(){
        
        $(document).on('click', '#selling_percent_calculate_btn_l2', function(){
            
            if($("input[name='percent_selling_price_l2']").val() == ''){
              bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: 'Please Enter Profit Margin!', 
                callback: function(){}
              });
              return false;
            }
        
            var prof_mar = parseFloat($("input[name='percent_selling_price_l2']").val());
        
            if(prof_mar == '0' || prof_mar == 0){
              bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: 'Profit Margin Should not be Zero!', 
                callback: function(){}
              });
              return false;
            } 
            
            var new_costprice = $('#input-new_cost').val();
            var selling_price = ((new_costprice*100)/(100-prof_mar));
            
            selling_price = selling_price.toFixed(2);
            //  revenue = revenue.toFixed(2);
        // console.log(per);
             $('input[name="nlevel2"]').val(selling_price);
             $('input[name="gross_profit2"]').val(prof_mar.toFixed(2));
        
             $('#sellingPercentageModal2').modal('hide');
        });
        
        $(document).on('click', '#selling_percent_calculate_btn_l3', function(){
            
            if($("input[name='percent_selling_price_l3']").val() == ''){
              bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: 'Please Enter Profit Margin!', 
                callback: function(){}
              });
              return false;
            }
        
            var prof_mar = parseFloat($("input[name='percent_selling_price_l3']").val());
        
            if(prof_mar == '0' || prof_mar == 0){
              bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: 'Profit Margin Should not be Zero!', 
                callback: function(){}
              });
              return false;
            } 
            
            var new_costprice = $('#input-new_cost').val();
            var selling_price = ((new_costprice*100)/(100-prof_mar));
            
            selling_price = selling_price.toFixed(2);
            //  revenue = revenue.toFixed(2);
        // console.log(per);
             $('input[name="nlevel3"]').val(selling_price);
             $('input[name="gross_profit3"]').val(prof_mar.toFixed(2));
        
             $('#sellingPercentageModal3').modal('hide');
        });
        
        $(document).on('click', '#selling_percent_calculate_btn_l4', function(){
            
            if($("input[name='percent_selling_price_l4']").val() == ''){
              bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: 'Please Enter Profit Margin!', 
                callback: function(){}
              });
              return false;
            }
        
            var prof_mar = parseFloat($("input[name='percent_selling_price_l4']").val());
        
            if(prof_mar == '0' || prof_mar == 0){
              bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: 'Profit Margin Should not be Zero!', 
                callback: function(){}
              });
              return false;
            } 
            
            var new_costprice = $('#input-new_cost').val();
            var selling_price = ((new_costprice*100)/(100-prof_mar));
            selling_price = selling_price.toFixed(2);
            //  revenue = revenue.toFixed(2);
        // console.log(per);
             $('input[name="nlevel4"]').val(selling_price);
             $('input[name="gross_profit4"]').val(prof_mar.toFixed(2));
        
             $('#sellingPercentageModal4').modal('hide');
        });
        
        var itemtype = '<?php echo $data['vitemtype'] ?>';
        
        if(itemtype == 'Instant'){
         
            $('.notLottery').hide();
            $('.Lottery').show();   
        }
        
        //====onchange selling price========
        $(document).on('keyup', '#input-Selling_Price', function(event) {
            
            var character = String.fromCharCode(event.keyCode)
            var newValue = this.value + character;
            if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
                event.preventDefault();
                return false;
            }
    
            var buyDown = $('input[name="ndiscountper"]').val();
            var new_costprice = $('#input-new_cost').val();
            var selling_price = $('#input-Selling_Price').val();
            var level2_selling_price = $('#input-level2price').val();
            var level3_selling_price = $('#input-level3price').val();
            var level4_selling_price = $('#input-level4price').val();
            var gross_profit;
            var level2_gross_profit;
            var level3_gross_profit;
            var level4_gross_profit;
            
            var sellingunit = $('#input-sellingunit').val();
            
            var unitcost = parseFloat(new_costprice/sellingunit).toFixed(2);
            
            if(buyDown != ''){
                gross_profit = selling_price - (unitcost - buyDown);
                level2_gross_profit = level2_selling_price - (unitcost - buyDown);
                level3_gross_profit = level3_selling_price - (unitcost - buyDown);
                level4_gross_profit = level4_selling_price - (unitcost - buyDown);
            }else{
                gross_profit = selling_price - unitcost;
                level2_gross_profit = level2_selling_price - unitcost;
                level3_gross_profit = level3_selling_price - unitcost;
                level4_gross_profit = level4_selling_price - unitcost;
            }
            
            var prof_mar = ((gross_profit/selling_price)*100);
            var prof_mar2 = ((level2_gross_profit/level2_selling_price)*100);
            var prof_mar3 = ((level3_gross_profit/level3_selling_price)*100);
            var prof_mar4 = ((level4_gross_profit/level4_selling_price)*100);
            
            if(selling_price != ''){
                $('input[name="gross_profit"]').val(prof_mar.toFixed(2));
            }
            if(level2_selling_price != ''){
                $('#input-gross-profit2').val(prof_mar2.toFixed(2));
            }
            if(level3_selling_price != ''){
                $('#input-gross-profit3').val(prof_mar3.toFixed(2));
            }
            if(level4_selling_price != ''){
                $('#input-gross-profit4').val(prof_mar4.toFixed(2));  
            }
        
        });
          
          
          
          
            //buy down
          
        $(document).on('keyup', '#input-Buydown', function(event) {
            event.preventDefault();
            var buyDown = $('input[name="ndiscountper"]').val();
            var new_costprice = $('#input-new_cost').val();
            var selling_price = $('#input-Selling_Price').val();
            var level2_selling_price = $('#input-level2price').val();
            var level3_selling_price = $('#input-level3price').val();
            var level4_selling_price = $('#input-level4price').val();
            var gross_profit;
            var level2_gross_profit;
            var level3_gross_profit;
            var level4_gross_profit;
            
            var sellingunit = $('#input-sellingunit').val();
            
            var unitcost = parseFloat(new_costprice/sellingunit).toFixed(2);
            
            if(buyDown != ''){
                gross_profit = selling_price - (unitcost - buyDown);
                level2_gross_profit = level2_selling_price - (unitcost - buyDown);
                level3_gross_profit = level3_selling_price - (unitcost - buyDown);
                level4_gross_profit = level4_selling_price - (unitcost - buyDown);
            }else{
                gross_profit = selling_price - unitcost;
                level2_gross_profit = level2_selling_price - unitcost;
                level3_gross_profit = level3_selling_price - unitcost;
                level4_gross_profit = level4_selling_price - unitcost;
            }
            
            var prof_mar = ((gross_profit/selling_price)*100);
            var prof_mar2 = ((level2_gross_profit/level2_selling_price)*100);
            var prof_mar3 = ((level3_gross_profit/level3_selling_price)*100);
            var prof_mar4 = ((level4_gross_profit/level4_selling_price)*100);
            
            $('input[name="gross_profit"]').val(prof_mar.toFixed(2));
            $('#input-gross-profit2').val(prof_mar2.toFixed(2));
            $('#input-gross-profit3').val(prof_mar3.toFixed(2));
            $('#input-gross-profit4').val(prof_mar4.toFixed(2));
            
            //buy down
            
            
            
        });
        
        // ========onchange cost =========
        
        $(document).on('keypress', '#input-new_cost', function(event){
            
            var character = String.fromCharCode(event.keyCode)
            var newValue = this.value + character;
            if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
                event.preventDefault();
                return false;
            }
            
            var buyDown = $('input[name="ndiscountper"]').val();
            var new_costprice = $('#input-new_cost').val();
            var selling_price = $('#input-Selling_Price').val();
            var level2_selling_price = $('#input-level2price').val();
            var level3_selling_price = $('#input-level3price').val();
            var level4_selling_price = $('#input-level4price').val();
            var gross_profit;
            var level2_gross_profit;
            var level3_gross_profit;
            var level4_gross_profit;
            
            var sellingunit = $('#input-sellingunit').val();
            
            var unitcost = parseFloat(new_costprice/sellingunit).toFixed(2);
            
            if(buyDown != ''){
                gross_profit = selling_price - (unitcost - buyDown);
                level2_gross_profit = level2_selling_price - (unitcost - buyDown);
                level3_gross_profit = level3_selling_price - (unitcost - buyDown);
                level4_gross_profit = level4_selling_price - (unitcost - buyDown);
            }else{
                gross_profit = selling_price - unitcost;
                level2_gross_profit = level2_selling_price - unitcost;
                level3_gross_profit = level3_selling_price - unitcost;
                level4_gross_profit = level4_selling_price - unitcost;
            }
            
            var prof_mar = ((gross_profit/selling_price)*100);
            var prof_mar2 = ((level2_gross_profit/level2_selling_price)*100);
            var prof_mar3 = ((level3_gross_profit/level3_selling_price)*100);
            var prof_mar4 = ((level4_gross_profit/level4_selling_price)*100);
            
            if(selling_price != ''){
                $('input[name="gross_profit"]').val(prof_mar.toFixed(2));
            }
            if(level2_selling_price != ''){
                $('#input-gross-profit2').val(prof_mar2.toFixed(2));
            }
            if(level3_selling_price != ''){
                $('#input-gross-profit3').val(prof_mar3.toFixed(2));
            }
            if(level4_selling_price != ''){
                $('#input-gross-profit4').val(prof_mar4.toFixed(2));  
            }
            
        });
        
    });
</script>

<script>
    $(document).ready(function(){
        
        var buyDown = $('input[name="ndiscountper"]').val();
        var new_costprice = $('#input-new_cost').val();
        var selling_price = $('#input-Selling_Price').val();
        var level2_selling_price = $('#input-level2price').val();
        var level3_selling_price = $('#input-level3price').val();
        var level4_selling_price = $('#input-level4price').val();
        var gross_profit;
        var level2_gross_profit;
        var level3_gross_profit;
        var level4_gross_profit;
        
        var sellingunit = $('#input-sellingunit').val();
            
        var unitcost = parseFloat(new_costprice/sellingunit).toFixed(2);
        
        if(buyDown != ''){
            gross_profit = selling_price - (unitcost - buyDown);
            level2_gross_profit = level2_selling_price - (unitcost - buyDown);
            level3_gross_profit = level3_selling_price - (unitcost - buyDown);
            level4_gross_profit = level4_selling_price - (unitcost - buyDown);
        }else{
            gross_profit = selling_price - unitcost;
            level2_gross_profit = level2_selling_price - unitcost;
            level3_gross_profit = level3_selling_price - unitcost;
            level4_gross_profit = level4_selling_price - unitcost;
        }
        
        var prof_mar = ((gross_profit/selling_price)*100);
        var prof_mar2 = ((level2_gross_profit/level2_selling_price)*100);
        var prof_mar3 = ((level3_gross_profit/level3_selling_price)*100);
        var prof_mar4 = ((level4_gross_profit/level4_selling_price)*100);
        
        if(new_costprice != '' && new_costprice > 0 ){
            
            $('input[name="gross_profit"]').val(prof_mar.toFixed(2));
            $('#input-gross-profit2').val(prof_mar2.toFixed(2));
            $('#input-gross-profit3').val(prof_mar3.toFixed(2));
            $('#input-gross-profit4').val(prof_mar4.toFixed(2));
        }else{
            
            // $('input[name="gross_profit"]').val();
            // $('#input-gross-profit2').val();
            // $('#input-gross-profit3').val();
            // $('#input-gross-profit4').val();
            if(selling_price != ''){
                if(selling_price != '' & !isNaN(prof_mar) & isFinite(prof_mar)){
                    $('input[name="gross_profit"]').val(prof_mar.toFixed(2));
                }
            }
            if(level2_selling_price != ''){
                $('#input-gross-profit2').val(prof_mar2.toFixed(2));
            }
            if(level3_selling_price != ''){
                $('#input-gross-profit3').val(prof_mar3.toFixed(2));
            }
            if(level4_selling_price != ''){
                $('#input-gross-profit4').val(prof_mar4.toFixed(2));  
            }
        }
    });
</script>

<script>
    $(document).ready(function(){
        
        $("#dept_code").change(function(){
      
            // console.log($(this).text);
            var deptCode = $(this).val();
        
            
            var input = {};
            input['dept_code'] = deptCode;
            
            if(deptCode != ""){
        //      console.log(deptCode);
        
                var url = '<?php echo $data['get_categories']; ?>';
                    // url += "&sid="+sid;
                    url = url.replace(/&amp;/g, '&');
                
        
        
                $.ajax({
                    url : url,
                    headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    data : JSON.stringify(input),
                    type : 'POST',
                    contentType: "application/json",
                    dataType: 'text json',
                    success: function(response) {
                        
                        // console.log(response);
                        if(response.length > 0){
                            $("#span_dept_name").html($("#dept_code option:selected").text());
                            $("#category_dept_name").val($("#dept_code option:selected").text());
                            $("#category_dept_code").val(deptCode);
                            $('select[name="vcategorycode"]').select2().empty().select2({data: response});
                        }
                
                        setTimeout(function(){
                            $('#successAliasModal').modal('hide');
                        }, 3000);
                    },
                    error: function(xhr) { 
                        // if error occured
                        // console.log(xhr);
                        return false;
                    }
                });
            }
        });
        
        $("#category_code").change(function(){
          
            // console.log($(this).text);
            var cat_id = $(this).val();
        
            
            var input = {};
            input['cat_id'] = cat_id;
            
            if(cat_id != ""){
        //      console.log(deptCode);
        
                var url = '<?php echo $data['get_subcategories_url']; ?>';
                    
                    url = url.replace(/&amp;/g, '&');
                
        
        
                $.ajax({
                    url : url,
                    headers: {
                                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    data : JSON.stringify(input),
                    type : 'POST',
                    contentType: "application/json",
                    dataType: 'text json',
                    success: function(response) {
                
                        // console.log(response);
                        if(response.length > 0){
                            // $("#span_dept_name").html($("#dept_code option:selected").text());
                            // $("#category_dept_name").val($("#dept_code option:selected").text());
                             $("#category_code").val(cat_id);
                            $('#subcat_id').select2().empty().select2({data: response});
                        }
                
                        setTimeout(function(){
                            $('#successAliasModal').modal('hide');
                        }, 3000);
                    },
                    error: function(xhr) { 
                        // if error occured
                        // console.log(xhr);
                        return false;
                    }
                });
            }
        });
    });
    
    function isEmail() { 
        
        var email = $('#add_vemail').val()
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        
        var result = regex.test(email);
        
        if(result == false && email != ''){
            $('#email_error').text("Incorrect format");
            $('#save_supplier').prop('disabled', true);
        }else{
            $('#email_error').hide();
            $('#save_supplier').prop('disabled', false);
        }
    }
    
 $(document).on('click', '.formsubmit', function(e){
    $("div#divLoading").removeClass('show');
    var iqtyonhand = $('input[name="iqtyonhand"]').val();
    var itemtype = $('select[name="vitemtype"]').val();
    if((typeof(iqtyonhand) == "undefined" || iqtyonhand == null || iqtyonhand == '') && itemtype != 'Instant'){
        
        var result = confirm('Qty On Hand is empty. Do you really want to submit ?');
        
        if(result == true){
            // $('form#form-item').submit();
            <?php if(session()->get('hq_sid') == 1) { ?>
                
                // if($('#input-sku').val() == ""){
                //     $("#input-sku").css({"border": "1px solid red"});
                //     $("#input_sku_error").text("SKU is required");
                //     $(window).scrollTop({ top: 0, behavior: 'smooth' });
                // }else {
                    $('#myModal').modal('show');
                // }
            <?php } else { ?>
                $('form#form-item1').submit();
            <?php } ?>
        }else{
            $(".show").hide();
            $("div#divLoading").removeClass('show');
            // return false;
            e.stopPropagation();
        }
    }else{
        // $('form#form-item').submit();
        <?php if(session()->get('hq_sid') == 1) { ?>
            $('#myModal').modal('show');
        <?php } else { ?>
            $('form#form-item1').submit();
        <?php } ?>
    }

});

var stores_hq = []
stores_hq.push("{{ session()->get('sid') }}");
$('#selectAllCheckbox').click(function(){
    if($('#selectAllCheckbox').is(":checked")){
        $(".stores").prop( "checked", true );
    }else{
        $( ".stores" ).prop("checked", false );
    }
});
$('#save_btn').click(function(){
    $.each($("input[name='stores']:checked"), function(){            
        stores_hq.push($(this).val());
    });
    $("#hidden_store_hq_val").val(stores_hq);
    
    $('form#form-item1').submit();
});
$("#closeBtn").click(function(){
    $("div#divLoading").removeClass('show');
});

$(document).ready(function(){
            
    $('#add_vdepartmentname, #add_vcategoryname, #add_subcat_name').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9. _]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    });
    
})
        

</script>

<script>
    $(document).on('click', '#alias_code_tab_li', function(){

      if($('#alias_code_tab_li').is(":checked") == true){

        $('#item_tab_li').prop('checked', false);
        $('#lot_matrix_tab_li').prop('checked', false);
        $('#vendor_tab_li').prop('checked', false);
        
        $('#alias_code_tab').show();
        $('#item_tab').hide();
        $('#lot_matrix_tab').hide();
        $('#vendor_tab').hide();
        
        $('#save_btn_row').hide();

        // $.cookie("tab_selected", 'alias_code_tab'); //set cookie tab
      }
    });

    $(document).on('click', '#item_tab_li', function(){

      if($('#item_tab_li').is(":checked") == true){

        $('#alias_code_tab_li').prop('checked', false);
        $('#lot_matrix_tab_li').prop('checked', false);
        $('#vendor_tab_li').prop('checked', false);
        
        $('#item_tab').show();
        $('#alias_code_tab').hide();
        $('#lot_matrix_tab').hide();
        $('#vendor_tab').hide();
        
        $('#save_btn_row').show();

        // $.cookie("tab_selected", 'item_tab'); //set cookie tab
      }
    });

    $(document).on('click', '#lot_matrix_tab_li', function(){

      if($('#lot_matrix_tab_li').is(":checked") == true){

        $('#item_tab_li').prop('checked', false);
        $('#alias_code_tab_li').prop('checked', false);
        $('#vendor_tab_li').prop('checked', false);
        
        $('#lot_matrix_tab').show();
        $('#alias_code_tab').hide();
        $('#item_tab').hide();
        $('#vendor_tab').hide();
        
        $('#save_btn_row').hide();

        // $.cookie("tab_selected", 'lot_matrix_tab'); //set cookie tab
      }
    });

    $(document).on('click', '#vendor_tab_li', function(){

      if($('#vendor_tab_li').is(":checked") == true){

        $('#item_tab_li').prop('checked', false);
        $('#alias_code_tab_li').prop('checked', false);
        $('#lot_matrix_tab_li').prop('checked', false);
        
        $('#vendor_tab').show();
        $('#lot_matrix_tab').hide();
        $('#alias_code_tab').hide();
        $('#item_tab').hide();
        
        $('#save_btn_row').hide();

        // $.cookie("tab_selected", 'vendor_tab'); //set cookie tab
      }
    });

    $(document).on('click', '#plcb_options_hideshow', function(event) {
      event.preventDefault();
      if ($('#plcb_options').val()==1){ 
          $('#plcb_options_checkbox_div').show('slow');
          $('#plcb_options').val(0);
      }else{
          $('#plcb_options_checkbox_div').hide('slow');
          $('#plcb_options').val(1);
      }
    });
    
    $(document).on('click', '#advance_options_hideshow', function(event) {
      event.preventDefault();
      if ($('#advance_options').val()==1){ 
          $('#advance_options_checkbox_div').show('slow');
          $('#advance_options').val(0);
      }else{
          $('#advance_options_checkbox_div').hide('slow');
          $('#advance_options').val(1);
      }
    });
  

  </script>

@endsection