@extends('layouts.master')

@section('title', 'Items')

@section('main-content')

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      
      <ul class="breadcrumb">
        @foreach ($data['breadcrumbs'] as $breadcrumb)
            <li><a href="{{ $breadcrumb['href'] }}">{{ $breadcrumb['text'] }} </a></li>
        @endforeach
      </ul>
    </div>
  </div>

  <div class="container-fluid">
      
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
      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Item</h3>
        
      </div>
      <div class="panel-body">

        <div class="row" style="padding-bottom: 9px;float: right;">
          <div class="col-md-12">
            <div class="">
              <!--<button class="btn btn-info save_btn_rotate" onclick="$('#form-item').submit();">Save</button>-->
              <button class="btn btn-info save_btn_rotate formsubmit">Save</button>
              <?php if(isset($data['clone_item']) && $data['clone_item'] != ''){?>
                <a href="<?php echo $data['clone_item']; ?>" data-toggle="tooltip" title="Clone Item" class="btn btn-info add_new_btn_rotate"><i class="fa fa-clone"></i>&nbsp;&nbsp;Clone</a>
              <?php } ?>
              <a id="cancel_button" href="{{ $data['cancel'] }}" data-toggle="tooltip" title="Cancel" class="btn btn-default cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
            </div>
          </div>
          
        </div>
        
        <div class="clearfix"></div>

        <ul class="nav nav-tabs responsive" id="myTab">
            <li id="item_tab_li" class="active"><a href="#item_tab" data-toggle="tab">Item</a></li>
            <li id="alias_code_tab_li"><a href="#alias_code_tab" data-toggle="tab" >Add Alias Code</a></li>
            <!--<li><a href="#parent_tab" data-toggle="tab" >Parent / Child</a></li> -->
            <li id="lot_matrix_tab_li" <?php if(isset($data['vitemtype']) && $data['vitemtype'] != 'Lot Matrix'){ ?> style="pointer-events:none;" <?php } ?> ><a <?php if(isset($vitemtype) && $vitemtype != 'Lot Matrix'){ ?> style="background:#ccc;" <?php } ?> href="#lot_matrix_tab" data-toggle="tab" >Lot Matrix</a></li>
            <li id="vendor_tab_li"><a href="#vendor_tab" data-toggle="tab" >Vendor</a></li>
            <li id="item_movement_tab_li"><a href="#item_movement_tab" data-toggle="tab" >Item Movement</a></li>
            
        </ul>

        <div class="tab-content responsive">
          <div class="tab-pane active" id="item_tab">
          <form action="{{ $data['action'] }}" method="post" enctype="multipart/form-data" id="form-item1" class="form-horizontal">
            @csrf
        
          <?php if(isset($data['iitemid']) && isset($data['edit_page'])){?>
            <input type="hidden" id="hidden_iitemid" name="iitemid" value="<?php echo $data['iitemid'];?>">
          <?php } ?>
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
          @if(session()->get('hq_sid') == 1)
                <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
          @endif
            
            <div>
                <div class="panel panel-default" style="border-top:none;margin-bottom:0px;">
                    <div class="panel-body" style="padding: 10px 10px 0px 10px;">
                        <h4>
                            <b>Add Item</b> 
                            <?php if(isset($data['visited_zero_movement_report']) && $data['visited_zero_movement_report'] =="Yes"){?>
                                <span style="float:right"> <a href="<?php echo $data['zero_movement_item_link'] ;?>" > << Back to Zero Movement Report</a></span>
                            <?php }?>
                        
                        
                        
                        <?php if(isset($data['visited_below_cost_report']) && $data['visited_below_cost_report'] =="Yes"){?>
                            <span style="float:right"> <a href="<?php echo $data['below_cost_item_link'] ;?>" > << Back to Below Cost Report</a></span>
                        <?php }?></h4>
                        
                    </div>
              </div>
              
                    <div style="background:#fff;padding-top:1%;padding-right:1%;">
                    <br>
                    <div class="row" style="margin-left:-50px;">
                        <div class="col-md-6 required">
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="input-customer">Item Type</label>
                              <div class="col-md-8">
                                <select name="vitemtype" class="form-control">
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
                        </div>
                      
                        <div class="col-md-6 notLottery">
                            <div class="form-group required">
                                <label class="col-md-4 control-label" for="input-state">Cost</label>
                                <div class="col-md-8">
                                    
                                    <input type="text" name="new_costprice" style="display: inline-block;width: 87%;" value="<?php echo isset($data['new_costprice']) && !empty($data['new_costprice']) ? number_format((float)$data['new_costprice'], 2) : ''; ?>" placeholder="New Cost" id="input-new_cost" class="form-control"/>
                                    
                                    <input type='hidden' id='input-unitcost' value=''>
                                    <button class="btn btn-sm btn-info" id="add_cost" style="display: inline-block;width: 10%" title="Add Costs">..</button>
                                    <input type="hidden" id="avgcost_value" name="dcostprice" value="<?php echo isset($data['dcostprice']) ? $data['dcostprice'] : ''; ?>" placeholder="Avg. Case Cost" class="form-control" autocomplete="off"/> 
                                    <input type="hidden" id="lastcost_value" name="last_costprice" value="<?php echo isset($data['last_costprice']) ? $data['last_costprice'] : ''; ?>" placeholder="Last Cost" class="form-control" readonly/>
                                        
                                    <?php if (isset($data['error_new_costprice'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_new_costprice']; ?></div>
                                    <?php } ?>
                              </div>
                            </div>
                        </div>
                      
                    </div>
                    
                    <div class="row" style="margin-left:-50px;">
                        
                        <div class="col-md-6 required">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-account-number">SKU</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vbarcode" maxlength="50" value="<?php echo isset($data['vbarcode']) ? $data['vbarcode'] : ''; ?>" placeholder="SKU" id="input-sku" class="form-control" <?php if(isset($data['vbarcode']) && isset($data['edit_page'])){?>readonly <?php } ?> autocomplete="off"/>
                                    <?php if (isset($data['error_vbarcode'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_vbarcode']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 notLottery">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-first-name">Price</label>
                                <div class="col-sm-8">
                                    
                                    <input type="text" style="display: inline-block;width: 87%;" id="input-Selling_Price" name="dunitprice" value="<?php echo isset($data['dunitprice']) ? $data['dunitprice'] : ''; ?>" placeholder="Selling Price" class="form-control" autocomplete="off"/>
                                    <button class="btn btn-sm btn-info" id="add_pricelevel" title="Add Description" style="display: inline-block;width: 10%">..</button>
                                    <input type="hidden" id="nlevel2_value" name="nlevel2" value="<?php echo isset($data['nlevel2']) ? $data['nlevel2'] : ''; ?>" class="form-control" />
                                    <input type="hidden" id="nlevel3_value" name="nlevel3" value="<?php echo isset($data['nlevel3']) ? $data['nlevel3'] : ''; ?>" class="form-control" />
                                    <input type="hidden" id="nlevel4_value" name="nlevel4" value="<?php echo isset($data['nlevel4']) ? $data['nlevel4'] : ''; ?>" class="form-control"/>
                                    <?php if (isset($data['error_dunitprice'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_dunitprice']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row notLottery" style="margin-left:-50px;">
                        
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-first-name">Item Name</label>
                                <div class="col-sm-8">
                                    
                                    <input type="text" style="display: inline-block;width: 88%;" name="vitemname" maxlength="100" value="<?php echo isset($data['vitemname']) ? $data['vitemname'] : ''; ?>" placeholder="Item Name" id="input_itemname" class="form-control" />
                                    <button class="btn btn-sm btn-info" title="Add Description" id="add_description" style="display: inline-block;width: 10%">..</button>
                                    <input type="hidden" id="description_value" name="vdescription" maxlength="100" value="<?php echo isset($data['vdescription']) ? $data['vdescription'] : ''; ?>" placeholder="Description" >
                                    
                                    <?php if (isset($data['error_vitemname'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_vitemname']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group" style="border-top:none;">
                              <label class="col-sm-4 control-label" for="input-phone">Gross Profit(%)</label>
                              <?php 
                                /*if(isset($nunitcost) && isset($dunitprice)){
            
                                  $percent = $dunitprice - $nunitcost;
            
                                  if($percent > 0){
                                    $percent = $percent;
                                  }else{
                                    $percent = 0;
                                  }
                                  
                                  if($dunitprice == 0 || $dunitprice == '0.0000'){
                                    $dunitprice = 1;
                                  }
                                 
                                  $percent = (($percent/$dunitprice) * 100);
                                  $percent = number_format((float)$percent, 2, '.', '');
                            
                                }else{
                                  $percent = 0.00;
                                }*/
                                
                                /*--if(isset($dcostprice) && isset($dunitprice)){
                                    
                                  if(isset($ndiscountper)){
                                        $percent = $dunitprice - ($dcostprice-$ndiscountper);
                                      }else{
                                        $percent = $dunitprice - $dcostprice;
                                      }--*/
                                        
                                 if(isset($data['new_costprice']) && $data['new_costprice'] >0 && isset($data['dunitprice'])){    
                                      
                                      // echo "new_costprice: $new_costprice";
                                      // echo "npack: $npack";
                                      
                                      
                                        // $nunit_cost = $data['new_costprice']/$data['npack'];
                                        $nunit_cost = (int)$data['npack'] !== 0?(int)($data['new_costprice']/$data['npack']):0;
                                        $nunit_cost = round($nunit_cost, 2);
                                      
                                      if(isset($ndiscountper)){
                                        $percent = $data['dunitprice'] - ($nunit_cost-$ndiscountper);
                                      }else{
                                        $percent = $data['dunitprice'] - $nunit_cost;
                                      }
                                      
                                      $percent = $percent > 0?$percent:0;
                                      
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
                                     
                                      $percent = (($percent/$dunitprice) * 100);
                                      $percent = number_format((float)$percent, 2, '.', '');
            
                                }else{
                                  $percent = 0.00;
                                }
                              ?>
                                <div class="col-md-8">
                                    <!--<span style="display: inline-block;width: 87%;"><input type="text" name="gross_profit" value="<?php echo $percent;?>" placeholder="" id="input-profit-margin" class="form-control" readonly /></span>
                                    <span style="display: inline-block;width: 10%" id="selling_price_calculation_btn"><button class="btn btn-sm btn-info" title="">..</button></span>-->
                                    <input type="text" style="display: inline-block;width: 87%;" name="gross_profit" value="<?php echo $percent;?>" placeholder="" id="input-profit-margin" class="form-control" readonly />
                                    <button class="btn btn-sm btn-info" id="selling_price_calculation_btn" title="Calculate Selling Price" style="display: inline-block;width: 10%">..</button>
                                </div>
                              
                            </div>
                        </div>
                      
                    </div>
                    
                    <div class="row notLottery" style="margin-left:-50px;">
                        
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-address">Unit</label>
                                <div class="col-sm-8">
                                    <select name="vunitcode" class="form-control">
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
                        
                        <div class="col-md-6 ">
                            <div class="form-group"><!--- Discount Price changes to Gross Markup on 03-03-20 ---->
                                <label class="col-sm-4 control-label" for="input-account-number">Gross Markup</label>
                                <div class="col-sm-8">
                                    <input type="text" name="discount_price" maxlength="50" value="<?php echo isset($data['bydown']) ? $data['bydown'] : ''; ?>" placeholder="Gross Markup" id="input-discount_price" class="form-control" <?php if(isset($data['bydown']) && isset($data['edit_page'])){?>readonly <?php } ?> readonly/>
                
                                </div>
                            </div>
                        </div>
                      
                    </div>
                    
                    <div class="row notLottery" style="margin-left:-50px;">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-phone">Taxable</label>
                                <div class="col-md-8">                                   
                                    <select name="vtax" class="form-control">
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
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-account-number">Buydown</label>
                                <div class="col-sm-8">
                                    <input type="text" name="ndiscountper" value="<?php echo isset($data['buydown']) ? $data['buydown'] : 0; ?>" placeholder="Buydown" id="input_buydown; ?>" class="form-control" readonly/>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row notLottery" style="margin-left:-50px;">
                        
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-md-4 control-label" for="input-state">Department</label>
                                <div class="col-md-8">
                                <span style="display: inline-block;width: 87%;">
                                    <select name="vdepcode" class="form-control" id="dept_code" style="display: inline-block;width: 87% !important;">
                                        
                                    <option value="">--Select Department--</option>
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
                                </span>&nbsp;
                                <!--<span style="display: inline-block;width: 10%" title="Add Department" id="add_new_department"><button class="btn btn-success btn-sm"><i class="fa fa-plus-square" aria-hidden="true"></i></button></span>-->
                                <button class="btn btn-success btn-sm" id="add_new_department" style="display: inline-block;width: 10%;"><i class="fa fa-plus-square" aria-hidden="true" ></i></button>
                                <?php if (isset($data['error_vdepcode'])) { ?>
                                  <div class="text-danger"><?php echo $data['error_vdepcode']; ?></div>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-country">Qty on Hand</label>
                                <div class="col-sm-8">
                                    
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
                                        <input type="hidden" name="iqtyonhand" value="<?php echo isset($data['iqtyonhand']) ? $data['iqtyonhand'] : ''; ?>" class="form-control" placeholder="Qty on Hand" <?php if((isset($data['isparentchild']) && $data['isparentchild'] == 1) || (isset($data['edit_page']))){?> readonly <?php } ?>  autocomplete="off"/>
                                    <?php }else{?>
                                        <input type="text" name="iqtyonhand" value="<?php echo isset($data['iqtyonhand']) ? $data['iqtyonhand'] : ''; ?>" class="form-control" placeholder="Qty on Hand"  autocomplete="off"/>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row notLottery" style="margin-left:-50px;">
                        
                        <div class="col-md-6" >
                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-zip">Category</label>
                                <div class="col-sm-8">
                                    <span style="display: inline-block;width: 87%;">
                                        <select name="vcategorycode" class="form-control" id="category_code"
                                            <?php
                                                if(!isset($data['vcategorycode'])){
                                                    echo "disabled='true'";
                                                }
                                            
                                            ?>
                                        >
                                        <option value="">--Select Category--</option>
                                        
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
                                    </span>&nbsp;
                                    <!--<span style="display: inline-block;width: 10%" title="Add Category" id="add_new_category"><button class="btn btn-success btn-sm"><i class="fa fa-plus-square" aria-hidden="true"></i></button></span>-->
                                    <button class="btn btn-success btn-sm" style="display: inline-block;width: 10%" title="Add Category" id="add_new_category"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                    <?php if (isset($data['error_vcategorycode'])) { ?>
                                      <div class="text-danger"><?php echo $data['error_vcategorycode']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php 
                            if(isset($data['nsellunit']) && $data['nsellunit'] != ''){
                              $nsellunit = $data['nsellunit'];
                            }else{
                              $nsellunit = 1;
                            }
                        ?>
                        <div class="col-md-6">
                            <div class="form-group" style="border-top:none;">
                              <label class="col-sm-4 control-label" for="input-phone">Selling Unit</label>
                              <div class="col-md-8">
                                <input type="text" name="nsellunit" value="<?php echo $nsellunit; ?>" placeholder="Selling Unit" id="input-sellingunit" class="form-control" <?php if(isset($data['vitemtype']) && $data['vitemtype'] == 'Lot Matrix'){?> readonly <?php } ?> />
                              </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row notLottery" style="margin-left:-50px;">
                        
                        <! --Sub-category-- ->
                        <div class="col-md-6">
                            <div class="form-group ">
                              <label class="col-sm-4 control-label" for="input-zip">Sub Category</label>
                              <div class="col-sm-8">
                                <span style="display: inline-block;width: 87%;">
                                    <select name="subcat_id" class="form-control" id="subcat_id"
                                        <?php
                                            if(!isset($data['vcategorycode'])){
                                                echo "disabled='true'";
                                            }
                                        
                                        ?>
                                    >
                                    <option value="">--Select Sub Category--</option>
                                    
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
                                </span>&nbsp;
                                <!--<span style="display: inline-block;width: 10%" title="Add Sub Category" id="add_new_subcategory"><button class="btn btn-success btn-sm"><i class="fa fa-plus-square" aria-hidden="true"></i></button></span>-->
                                <button class="btn btn-success btn-sm" style="display: inline-block;width: 10%" title="Add Sub Category" id="add_new_subcategory"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                <?php if (isset($data['error_subcat_id'])) { ?>
                                  <div class="text-danger"><?php echo $data['error_subcat_id']; ?></div>
                                <?php } ?>
                              </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                              <label class="col-sm-4 control-label" for="input-phone">Unit Per Case</label>
                              <?php 
                                if(isset($data['npack']) && $data['npack'] != ''){
                                  $npack = $data['npack'];
                                }elseif($data['npack'] != 0){
                                    $npack = 1;
                                }else{
                                    $npack = 0;
                                }
                              ?>
                              <div class="col-md-8">
                                <input type="text" name="npack" value="<?php echo $npack; ?>" placeholder="Unit Per Case" id="input-unitpercase" class="form-control" />
                              </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row notLottery" style="margin-left:-50px;">
                        
                        <! --Supplier-- >
                        <div class="col-md-6">
                            <div class="form-group required">
                              <label class="col-sm-4 control-label" for="input-city">Supplier</label>
                              <div class="col-sm-8">
                                <span style="display: inline-block;width: 87%;">
                                    <select name="vsuppliercode" class="form-control">
                                    <option value="">--Select Supplier--</option>
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
                                </span>&nbsp;
                                <!--<span style="display: inline-block;width: 10%" title="Add Supplier" id="add_new_supplier"><button class="btn btn-success btn-sm"><i class="fa fa-plus-square" aria-hidden="true"></i></button></span>-->
                                <button class="btn btn-success btn-sm" style="display: inline-block;width: 10%" title="Add Supplier" id="add_new_supplier"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                <?php if (isset($data['error_vsuppliercode'])) { ?>
                                    <div class="text-danger"><?php echo $data['error_vsuppliercode']; ?></div>
                                  <?php } ?>
                              </div>
                            </div>
                        </div>
                        
                        <?php if(isset($data['itemparentitems']) && !empty($data['itemparentitems'])) { 
                        
                            $Parent_barcode = $data['itemparentitems']->vitemname.' ['.$data['itemparentitems']->vbarcode.']';
                        }
                        ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-account-number">Parent Barcode</label>
                                <div class="col-sm-8 small">
                                    <input type="text" name="Parent_barcode" maxlength="50" value="<?php echo isset($Parent_barcode) ? $Parent_barcode : ''; ?>" placeholder="Parent Barcode" class="form-control small" style="font-size:12px;" readonly >
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                   
                    <div class="row notLottery" style="margin-left:-50px;">
                       
                       <! --SIZE- ->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-country">Size</label>
                                <div class="col-sm-8">
                                    <span style="display: inline-block;width: 87%;">
                                        <select name="vsize" class="form-control">
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
                                    </span>&nbsp;
                                
                                <button class="btn btn-success btn-sm" style="display: inline-block;width: 10%" title="Add Size" id="add_new_size"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                              <label class="col-sm-4 control-label" for="input-country">Bottle Deposit</label>
                              <div class="col-md-8">
                                <input name="nbottledepositamt" id="nbottledepositamt" value="<?php echo isset($data['nbottledepositamt']) ? $data['nbottledepositamt'] : '0.00'; ?>" type="text" class="form-control">
                              </div>
                            </div>
                       </div>
                       
                    </div>
                   
                    <div class="row notLottery" style="margin-left:-50px;">
                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >Manufacturer</label>
                                <div class="col-sm-8">
                                    <select name="manufacturer_id" class="form-control">
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
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group" style="border-top:none;">
                                <label class="col-sm-4 control-label" for="input-phone">Food Item</label>
                                <div class="col-md-8">
                                    <?php if(isset($data['array_yes_no']) && count($data['array_yes_no']) > 0){?>
                                      <?php foreach($data['array_yes_no'] as $k => $array_y_n){ ?>
                                        <label class="radio-inline">
                                          <input type="radio" name="vfooditem" value="<?php echo $k;?>" <?php if($data['vfooditem'] == $k) echo "checked"; ?>><?php echo $array_y_n;?>
                                        </label>
                                      <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                   
                    <div class="row notLottery" style="margin-left:-50px;">
                       
                        <div class="col-md-6">
                            <div class="form-group">
                              <label class="col-sm-4 control-label" for="input-phone"><?php echo "Promotion"; ?></label>
                              <div class="col-sm-8">
                                <select name="itemPromotionid" id="promotionid" class="form-control">
                                
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
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group" style="border-top:none;">
                                <label class="col-sm-4 control-label" for="input-phone">Age Verification</label>
                                  
                                <div class="col-md-8">
                                    <select name="vageverify" class="form-control">
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
                    
                    <div class="row Lottery" style="margin-left:-50px; display:none">
                        
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-account-number">Ticket Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="ticket_name" maxlength="50" value="<?php echo isset($data['vitemname']) ? $data['vitemname'] : ''; ?>" placeholder="Ticket Name" id="input-ticket_name" class="form-control" autocomplete="off"/>
                                    <?php if (isset($data['error_ticket_name'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_ticket_name']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row Lottery" style="margin-left:-50px; display:none">
                        
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-account-number">Ticket Price</label>
                                <div class="col-sm-8">
                                    <input type="number" name="ticket_price" maxlength="50" value="<?php echo isset($data['dunitprice']) ? $data['dunitprice'] : ''; ?>" placeholder="Ticket Price" id="input-ticket_price" class="form-control" autocomplete="off"/>
                                    <?php if (isset($data['error_ticket_price'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_ticket_price']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row Lottery" style="margin-left:-50px; display:none">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-account-number">Games Per Book</label>
                                <div class="col-sm-8">
                                    <input type="text" name="games_per_book" maxlength="50" value="<?php echo isset($data['npack']) ? $data['npack'] : '1'; ?>" placeholder="Games Per Book" id="input-games_per_book" class="form-control" autocomplete="off"/>
                                    <?php if (isset($data['error_games_per_book'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_games_per_book']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row Lottery" style="margin-left:-50px; display:none">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-account-number">Book Qoh</label>
                                <div class="col-sm-8">
                                    <input type="text" name="book_qoh" maxlength="50" value="<?php echo isset($data['QOH']) ? $data['QOH'] : ''; ?>" placeholder="Book Qoh" id="input-book_qoh" class="form-control" autocomplete="off" readonly/>
                                    <?php if (isset($data['error_book_qoh'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_book_qoh']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row Lottery" style="margin-left:-50px; display:none">
                        
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-account-number">Book Cost</label>
                                <div class="col-sm-8">
                                    <input type="number" name="book_cost" maxlength="50" value="<?php echo isset($data['dcostprice']) ? number_format((float)$data['dcostprice'], 2) : ''; ?>" placeholder="Book Cost" id="input-book_cost" class="form-control" autocomplete="off"/>
                                    <?php if (isset($data['error_book_cost'])) { ?>
                                        <div class="text-danger"><?php echo $data['error_book_cost']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    
                </div>
              
                <div class="panel panel-default notLottery" style="border-top:none;margin-bottom:0px;margin-top:1%;">
                    <div class="panel-body" style="padding: 10px 10px 0px 10px;"><h5>Advance Options&nbsp;&nbsp;<input type="checkbox" name="advance_options_checkbox" ></h5></div>
                </div>
                <div class="notLottery" style="background:#fff;padding-top:1%;padding-right:1%; padding-left:3%;"> 
                    <div id="advance_options_checkbox_div">
                        
                        <div class="row" style="margin-left:-50px;">
                            <div class="col-md-6">
                                
                                <div class="form-group" style="border-top:none;">
                                    <label class="col-sm-4 control-label" for="input-country">Status</label>
                                    <div class="col-md-8">
                                      <select name="estatus" class="form-control">
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
                                
                                <div class="form-group" style="border-top:none;">
                                    <label class="col-sm-4 control-label" for="input-country">Reorder Duration</label>
                                    
                                    <div class="col-md-8">
                                        <input type="text" name="reorder_duration" maxlength="45" value="<?php echo isset($data['reorder_duration']) ? $data['reorder_duration'] : ''; ?>" placeholder="Reorder Duration" id="input_reorder_duration" class="form-control"  />
                                        <span class="text-small" style="position: absolute"><b>Enter Order Duration in Days.</b></span>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="border-top:none;">
                                    <label class="col-sm-4 control-label" for="input-phone">Re-Order Point</label>
                                    
                                    <div class="col-md-8">
                                        <input type="text" style="display: inline-block;"  name="ireorderpoint" value="<?php echo isset($data['ireorderpoint']) ? $data['ireorderpoint'] : ''; ?>" placeholder="Re-Order Point" id="input_reorderpoint" class="form-control"  />
                                        <span class="text-small" ><b>Enter Reorder Point in Unit.</b></span>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="border-top:none;">
                                    <label class="col-sm-4 control-label" for="input-phone">Order Qty Upto</label>
                                    <div class="col-md-8">
                                        <input type="text" name="norderqtyupto" value="<?php echo isset($data['norderqtyupto']) ? $data['norderqtyupto'] : ''; ?>" placeholder="Order Qty Upto" id="input_orderqtyupto" class="form-control" />
                                        <span class="text-small" style="position: absolute"><b>Enter Order Qty Upto in Case.</b></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-country">Aisle</label>
                                    <div class="col-sm-8">
                                        <select name="aisleid" class="form-control">
                                          <option value="">--Select Mfg. Promo Desc--</option>
                                          <?php if(isset($data['aisles']) && count((array)$data['aisles']) > 0){?>
                                            <?php foreach($data['aisles'] as $aisle){ ?>
                                              <?php if(isset($aisleid) && $aisleid == $aisle->Id) {?>
                                                <option value="<?php echo $aisle->Id;?>" selected="selected"><?php echo $aisle->aislename;?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $aisle->Id;?>"><?php echo $aisle->aislename;?></option>
                                              <?php } ?>
                                            <?php } ?>
                                          <?php } ?>
                                        </select>
                                     
                                    </div>
                                </div>
                                
                                <! --Mfg Buy Down Desc-->
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-phone">
                                        <?php 
                                        
                                        echo "Mfg Buy Down Desc."; 
                                        ?></label>
                                    <div class="col-sm-8">
                                        <select name="shelfid" class="form-control">
                                            <option value="">--Select Mfg Buy Down Desc.--</option>
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
                                
                                <! --Mfg. Multipack Desc-- >
                                <div class="form-group">
                                  <label class="col-sm-4 control-label" for="input-phone">
                                      <?php 
                                             
                                            echo "Mfg MultiPack Desc."; 
                                      ?>
                                  </label>
                                  <div class="col-sm-8">
                                    <select name="shelvingid" class="form-control">
                                      <option value="">--Select Mfg MultiPack Desc.--</option>
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
                            
                            <div class="col-md-6">
                                
                                <div class="form-group" style="border-top:none;">
                                    <label class="col-sm-4 control-label" for="input-phone">WIC</label>
                                    <div class="col-md-8">
                                        <?php if(isset($data['array_yes_no']) && count($data['array_yes_no']) > 0){?>
                                          <?php foreach($data['array_yes_no'] as $k => $array_y_n){ ?>
                                          <?php if($data['wicitem'] == 1) $data['wicitem'] = 'Y'; else $data['wicitem'] ='N'; ?>
                                            <label class="radio-inline">
                                              <input type="radio" name="wicitem" value="<?php echo $k;?>" <?php if($data['wicitem'] == $k) echo "checked"; ?> ><?php echo $array_y_n;?>
                                            </label>
                                          <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="border-top:none;">
                                    <label class="col-sm-4 control-label" for="input-phone">Barcode Type</label>
                                    <div class="col-md-8">
                                      <select name="vbarcodetype" class="form-control">
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
                                
                                <div class="form-group" style="border-top:none;">
                                    <label class="col-sm-4 control-label" for="input-country">Discount</label>
                                    <div class="col-md-8">
                                        <select name="vdiscount" class="form-control">
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
                                
                                <div class="form-group" style="border-top:none;">
                                    <label class="col-sm-4 control-label" for="input-phone">Station</label>
                                    <div class="col-md-8">
                                        <select name="stationid" class="form-control">
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
                                
                                <div class="form-group" style="border-top:none;">
                                    <label class="col-md-4 control-label" for="input-phone">Show Image</label>
                                    <div class="col-md-8">
                                        <select name="vshowimage" class="form-control">
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
                                
                                <div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-8">
                                      <?php if(isset($data['itemimage']) && $data['itemimage'] != ''){?>
                                        <img src="data:image/gif;base64,<?php echo $data['itemimage'];?>" class="img-responsive" width="100" height="100" alt="" id="showImage">
                                        <br>
                                        <button class="btn btn-info btn-sm" id="remove_item_img">Remove</button>
                                        <br><br>
                                        <input type="hidden" name="pre_itemimage" value="<?php echo $data['itemimage'];?>">
                                      <?php } else { ?>
                                        <img src="{{ asset('image/user-icon-profile.png') }}" class="img-responsive" width="100" height="100" alt="" id="showImage"><br>
                                        <input type="hidden" name="pre_itemimage" value="">
                                      <?php } ?>
                                      <input type="file" name="itemimage" accept="image/x-png,image/gif,image/jpeg" onchange="showImages(this)">
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
                <br>
                <div class="panel panel-default notLottery" style="border-top:none;margin-bottom:0px;">
                    <div class="panel-body" style="padding: 10px 10px 0px 10px;"><h5>PLCB &nbsp;&nbsp; <input type="checkbox" name="plcb_options_checkbox" value="1" <?php if($data['plcb_options_checkbox']){ echo 'checked'; }?>></h5></div>
                </div>
                <div class="notLottery" style="background:#fff;padding-top:1%;padding-right:1%;">
                <div id="plcb_options_checkbox_div" style="<?php if($data['plcb_options_checkbox']){ echo 'display: block'; }else{ echo 'display: none'; }?>">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group required">
                      <label class="col-sm-4 control-label" for="input-phone">
                      Unit</label>
                      <div class="col-sm-8">
                        <select name="unit_id" class="form-control">
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
                  </div>
                  <div class="col-md-6">
                    <div class="form-group required">
                      <label class="col-sm-4 control-label" for="input-phone">Unit Value</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" value="<?php echo isset($data['unit_value']) ? $data['unit_value'] : ''; ?>" name="unit_value">
    
                        <?php if (isset($data['error_unit_value'])) { ?>
                          <div class="text-danger"><?php echo $data['error_unit_value']; ?></div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="input-phone">Malt</label>
                      <div class="col-sm-8">
                        <input style="margin-top: 10px;" type="checkbox" class="form-control" name="malt" value="1" <?php if($data['malt']){ echo 'checked'; }?>>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group required">
                      <label class="col-sm-4 control-label" for="input-phone">
                      Bucket</label>
                      <div class="col-sm-8">
                        <select name="bucket_id" class="form-control">
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
              </div>
            </div>
          </form>
          <br>
          <div class="row">
            <div class="col-md-12 text-center">
              <input type="button" title="Save" class="btn btn-primary save_btn_rotate formsubmit" value="Save">
              <a id="cancel_button" href="<?php echo $data['cancel']; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-default cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
              <?php if(isset($data['iitemid']) && $data['iitemid'] != ''){?>
                <button type="button" class="btn btn-danger" id="delete_btn" style="border-radius: 0px;float: right;"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;Delete Item</button>
              <?php } ?>
            </div>
          </div>
          </div>
          
          <div class="tab-pane" id="alias_code_tab">
            <form action="<?php echo $data['add_alias_code']; ?>" method="post" enctype="multipart/form-data" id="form-item-alias-code" class="form-horizontal">
            @csrf
            <?php if(isset($data['iitemid'])){?>
              <input type="hidden" name="iitemid" value="<?php echo $data['iitemid'];?>">
            <?php } ?>
              <div class="row">
                <div class="col-md-4"> 
                    <span>Alias Code:&nbsp;&nbsp;</span><span style="display:inline-block;"><input type="text" id="valiassku"  name="valiassku" maxlength="50" class="form-control" required></span>
                    <input type="hidden" name="vitemcode" id="vitemcode" value="<?php echo isset($data['vitemcode']) ? $data['vitemcode'] : ''; ?>">
                    <input type="hidden" name="vsku" name="vsku" value="<?php echo isset($data['vbarcode']) ? $data['vbarcode'] : ''; ?>">
                    <?php if(session()->get('hq_sid') == 1){ ?>
                        <input type="hidden" name="alias_stores_hq" value="" id="alias_stores_hq"  >
                    <?php } ?>
                </div>
                <div class="col-md-4">
                  <span style="display:inline-block;"><input type="submit" name="Alias_code" value="Add Alias Code" class="btn btn-info"></span>
                </div>
              </div>
            </form>
            <br><br>
            <form action="<?php echo $data['alias_code_deletelist']; ?>" method="post" enctype="multipart/form-data" id="form-item-alias-list" class="form-horizontal">
                @csrf
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-bordered table-hover" style="width:30%;">
                    <thead>
                      <tr>
                        <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected_alias\']').prop('checked', this.checked);" /></th>
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
                  <input type="submit" title="Delete" class="btn btn-danger" value="Delete" style="border-radius:0px;" <?php if(isset($data['itemalias']) && count($data['itemalias']) == 0){ ?> disabled="true" <?php } ?> >
                </div>
              </div>
            </form>
          </div>
          
          <div class="tab-pane" id="lot_matrix_tab">
            <div class="row">
              <div class="col-md-4">
                <button class="btn btn-primary" data-toggle="modal"  id="button_addLotItemModal">Add Lot Item</button>&nbsp;&nbsp;
                <form action="<?php echo $data['lot_matrix_deletelist']; ?>" method="post" id="delete_lot_items" style="display: inline-block;">
                @csrf
                  <input type="submit" class="btn btn-danger" value="Delete Lot Item" style="border-radius:0px;">
                </form>
              </div>
            </div>
            <br><br>
            <form action="<?php echo $data['lot_matrix_editlist']; ?>" method="post" enctype="multipart/form-data" id="form-item-lot-matrix-list" class="form-horizontal">
                @csrf
              <div class="row">
                <div class="col-md-12" style="overflow: scroll;">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected_lot_matrix\']').prop('checked', this.checked);" /></th>
                        <th>Pack Name</th>
                        <th>Description</th>
                        <th class="text-right">Unit Cost</th>
                        <th class="text-right">Pack Qty</th>
                        <th class="text-right">Pack Cost</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Sequence</th>
                        <th class="text-right">Profit Margin(%)</th>
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
                            <input type="text" class="editable" name="itempacks[<?php echo $k; ?>][vpackname]" value="<?php echo $itempack->vpackname;?>" />
                          </td>
                          <td>
                            <?php //echo $itempack->vdesc;?>
                            <input type="text" class="editable" name="itempacks[<?php echo $k; ?>][vdesc]" value="<?php echo $itempack->vdesc;?>" />
                          </td>
                          <td class="text-right"><?php echo number_format($data['nunitcost'], 2);?></td>
                          <td class="text-right"><?php echo $itempack->ipack;?></td>
                          <td class="text-right">
                            <!--<?php echo $itempack->npackcost;?>-->
                            <input type="text" class="editable" id="input_npackcost" name="itempacks[<?php echo $k; ?>][npackcost]" value="<?php echo number_format($itempack->npackcost, 2);?>" />
                            <input type="hidden" class="input_npackcost" value="<?php echo $itempack->npackcost;?>">
                          </td>
                          <td class="text-right">
                            <input type="text" class="editable input_npackprice" id='input_npackprice' name="itempacks[<?php echo $k; ?>][npackprice]" value="<?php echo $itempack->npackprice; ?>" style="text-align: right;" />
                          </td>
                          <td class="text-right"><?php echo $itempack->isequence;?></td>
                          <td class="text-right">
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
                  <input type="submit" title="Update" class="btn btn-primary save_btn_rotate" value="Update" <?php if(isset($data['itempacks']) && count((array)$data['itempacks']) == 0){ ?> disabled="true" <?php } ?> >
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane" id="vendor_tab">
            <form action="<?php echo $data['action_vendor']; ?>" method="post" enctype="multipart/form-data" id="form-item-vendor" class="form-horizontal">
            @csrf
            <?php if(isset($data['iitemid'])){?>
              <input type="hidden" name="iitemid" value="<?php echo $data['iitemid'];?>">
            <?php } ?>
            <?php if(session()->get('stores_hq')){?>
              <input type="hidden" id="hiddenvendorAssignsave" name="hiddenvendorAssignsave" value="">
            <?php } ?>
              <div class="row">
                <div class="col-md-4">
                  <span>Vendor Item Code:&nbsp;&nbsp;</span><span style="display:inline-block;width: 60%;"><input type="text" name="vvendoritemcode" maxlength="100" class="form-control" required></span>
                </div>
                <div class="col-md-3">
                  <span>Vendor:&nbsp;&nbsp;</span>
                  <span style="display:inline-block;width: 75%;">
                    <select name="ivendorid" id="ivendorid" class="form-control" required>
                      <option value="">--Select Vendor--</option>
                        <?php if(isset($data['suppliers']) && count($data['suppliers']) > 0){?>
                          <?php foreach($data['suppliers'] as $supplier){ ?>
                              <option value="<?php echo $supplier['vsuppliercode'];?>"><?php echo $supplier['vcompanyname'];?></option>
                          <?php } ?>
                        <?php } ?>
                    </select>
                  </span>
                </div>
                <div class="col-md-1">
                  <input type="button" id="form-item-vendor-submit-btn" name="Assign" value="Assign" class="btn btn-info">
                </div>
                <div class="col-md-1">
                  <button class="btn btn-danger" id="delete_item_vendor_btn" style="border-radius: 0px;">Delete</button>
                </div>
              </div>
            </form>
            <br><br>
            <form action="<?php echo $data['action_vendor_editlist']; ?>" method="post" enctype="multipart/form-data" id="form-item-vendor-list" class="form-horizontal">
             @csrf
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-bordered table-hover" style="width:70%;">
                    <thead>
                      <tr>
                        <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected_vendor_code\']').prop('checked', this.checked);" /></td>
                        <th>Vendor Name</th>
                        <th>Vendor Item Code</th>
                        <th>Address</th>
                        <th class="text-right">Phone</th>
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
                          <td><input type="checkbox" class="item_vendor_ids" name="selected_vendor_code[]" value="<?php echo $itemvendor->Id; ?>" /></td>
                          <td><?php echo $itemvendor->vcompanyname;?></td>
                          <td>
                            <input type="text" class="editable" maxlength="100" name="itemvendors[<?php echo $k; ?>][vvendoritemcode]" value="<?php echo $itemvendor->vvendoritemcode; ?>" />
                          </td>
                          <td><?php echo $itemvendor->vaddress1;?></td>
                          <td class="text-right"><?php echo $itemvendor->vphone;?></td>
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
          
            <div class="tab-pane" id="item_movement_tab">
                <?php if(isset($reports) && count($reports['item_data']) > 0){ ?>
                    <br><br>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="table-responsive">
                          <table class="table" style="border: 1px solid #ccc;">
                            <thead>
                                
                              <?php  if(isset($parentreports) && !empty($parentreports)){ ?>
                               <tr>
                                <!--<th colspan="7" class="text-center text-uppercase"><b style="font-size: 16px;"><?php echo $reports['item_data'][0]['vitemname']; ?> [QOH: UNITS <?php echo ($parentreports[0]['item_data'][0]['npack'] != 0) ? $parentreports[0]['item_data'][0]['QOH'] % $parentreports[0]['item_data'][0]['npack'] : 0 ; ?> ]</b></th>-->
                              </tr>
                              <?php } else { ?>    
                              <tr>
                                <th colspan="7" class="text-center text-uppercase"><b style="font-size: 16px;"><?php echo $reports['item_data'][0]['vitemname']; ?> [QOH: CASE <?php echo $reports['item_data'][0]['IQTYONHAND']; ?> ]</b></th>
                              </tr>
                              <?php } ?>
                           
                            </thead>
                            <tbody>
                              <?php 
                                $current_year = date('Y'); 
                                $previous_year = date("Y",strtotime("-1 year"));
                              ?>
                              <tr>
                                <td colspan="2" style="background-color: #fff;"></td>
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                  <b class="text-uppercase text-info" style="font-size: 14px;">
                                  <?php echo $previous_year;?> YTD SOLD   
                                 <?php 
                                    echo !empty($reports['year_arr_sold'][$previous_year]['total_sold']) ?(int) $reports['year_arr_sold'][$previous_year]['total_sold'] : '0' ;
                                  ?>
                                 
                                  <?php  $value1=!empty($reports['year_arr_adjustment'][$previous_year]['total_adjustment'])? $reports['year_arr_adjustment'][$previous_year]['total_adjustment']:'0';  /*adjustment */
                                         $value2=!empty($reports['year_arr_oqoh'][$previous_year]['total_oqoh']) ?  $reports['year_arr_oqoh'][$previous_year]['total_oqoh'] : '0';        /*Opening Qoh Web*/   
                                         $value3=!empty($reports['year_arr_qoh'][$previous_year]['total_qoh']) ? $reports['year_arr_qoh'][$previous_year]['total_qoh'] :'0';            /*Quick Update web*/
                                         $value4=!empty($reports['year_arr_inv'][$previous_year]['total_inv'])  ? $reports['year_arr_inv'][$previous_year]['total_inv']:'0';       /* Parent update*/ 
                                         $value6=!empty($reports['year_arr_cqoh'][$previous_year]['total_cqoh']) ? $reports['year_arr_cqoh'][$previous_year]['total_cqoh'] : '0';           /*Child Upadte*/ 
                                         $value7=!empty($reports['year_arr_phqoh'][$previous_year]['total_phqoh']) ? $reports['year_arr_phqoh'][$previous_year]['total_phqoh']:'0';     /*Phone update  Qoh update by sku api*/
                                         $value8=!empty($reports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh'])? $reports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh']:'0';       /*Opening Qoh by phone */
                                         $value9=!empty($reports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment'])? $reports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment']:'0'; 
                                         $totaladjpreviousyr=$value1+$value2+$value3+$value4+$value6+$value7+$value8+$value9;?>
                                  
                                </b>
                                  </td>
                                
                                
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                    <b class="text-uppercase text-info" style="font-size: 14px;">
                                        <?php echo $previous_year;?> YTD ADJUSTMENT  
                                          <?php echo $totaladjpreviousyr;?>  
                                        <?php //echo !empty($reports['year_arr_adjustment'][$previous_year]['total_adjustment']) ? $reports['year_arr_adjustment'][$previous_year]['total_adjustment'] : '0' ;?>
                                    </b>
                                </td>
                                  
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                  <b class="text-uppercase text-info" style="font-size: 14px;">
                                  <?php echo $previous_year; ?> YTD RECEIVE     
                                  <?php
                                    echo !empty($reports['year_arr_receive'][$previous_year]['total_receive']) ? $reports['year_arr_receive'][$previous_year]['total_receive']: '0' ;
                                  ?>
                                  </b>
                                  </td>
                              </tr>
                              <tr>
                                <th colspan="2" style="background-color: #fff;border-top: none;"></td>
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                  <b class="text-uppercase text-danger" style="font-size: 14px;">
                                  <?php echo $current_year;?> YTD SOLD   
                                  <?php 
                                    echo !empty($reports['year_arr_sold'][$current_year]['total_sold']) ?(int) $reports['year_arr_sold'][$current_year]['total_sold'] : '0' ;
                                  ?>
                                </b>
                                  </td>
                                  
                                  
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                    <b class="text-uppercase text-danger" style="font-size: 14px;">
                                        <?php echo $current_year;?> YTD ADJUSTMENT  
                                        <!--  Old code 
                                        <?php //echo !empty($reports['year_arr_adjustment'][$current_year]['total_adjustment']) ? $reports['year_arr_adjustment'][$current_year]['total_adjustment'] : '0' ;?>
                                    -->
                                   
                            
                                      <?php  $value1=!empty($reports['year_arr_adjustment'][$current_year]['total_adjustment'])? $reports['year_arr_adjustment'][$current_year]['total_adjustment']:'0';  /*adjustment */
                                         $value2=!empty($reports['year_arr_oqoh'][$current_year]['total_oqoh']) ? $reports['year_arr_oqoh'][$current_year]['total_oqoh'] : '0';        /*Opening Qoh Web*/   
                                         $value3=!empty($reports['year_arr_qoh'][$current_year]['total_qoh']) ? $reports['year_arr_qoh'][$current_year]['total_qoh'] :'0';            /*Quick Update web*/
                                         $value4=!empty($reports['year_arr_inv'][$current_year]['total_inv'])  ? $reports['year_arr_inv'][$current_year]['total_inv']:'0';       
                                         $value6=!empty($reports['year_arr_cqoh'][$current_year]['total_cqoh']) ? $reports['year_arr_cqoh'][$current_year]['total_cqoh'] : '0';           /*Child Upadte*/ 
                                         $value5=!empty($reports['year_arr_pqoh'][$current_year]['total_pqoh']) ? $reports['year_arr_pqoh'][$current_year]['total_pqoh'] : '0';  /* Parent update*/ 
                                         $value7=!empty($reports['year_arr_phqoh'][$current_year]['total_phqoh']) ? $reports['year_arr_phqoh'][$current_year]['total_phqoh']:'0';     /*Phone update  Qoh update by sku api*/
                                         $value8=!empty($reports['year_arr_ophoqoh'][$current_year]['total_ophoqoh'])? $reports['year_arr_ophoqoh'][$current_year]['total_ophoqoh']:'0';       /*Opening Qoh by phone */
                                         $value9=!empty($reports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment'])? $reports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment']:'0'; 
                                         $TotalAdjustment=$value1+$value2+$value3+$value4+$value6+$value7+$value8+$value5+$value9;?>
                                         
                                        <?php echo $TotalAdjustment;?>
                                    </b>
                                </td>
                                  
                                  
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                  <b class="text-uppercase text-danger" style="font-size: 14px;">
                                  <?php echo $current_year; ?> YTD RECEIVE  
                                  <?php
                                    echo !empty($reports['year_arr_receive'][$current_year]['total_receive']) ? $reports['year_arr_receive'][$current_year]['total_receive']: '0' ;
                                  ?>
                                  </b>
                                  </td>
                              </tr>
                              <tr>
                                  <th colspan="2" style="border-right: 1px solid #cdd0d4;"></th>
                                  <th colspan="3" class="text-center" style="border-right: 1px solid #cdd0d4;">Previous Year</th>
                                  <th colspan="3" class="text-center" style="border-right: 2px solid #cdd0d4;">Current Year</th>
                                  
                              </tr>
                              
                              <?php for($i = 1; $i <= 12; ++$i){ ?>
                                <tr>
                                  <td colspan="2" style="border-right: 1px solid #cdd0d4;">
                                    <b><?php echo DateTime::createFromFormat('!m', $i)->format('F'); ?></b>
                                    </td>
                                  <td colspan="3" style="border-right: 1px solid #cdd0d4;">
                                    <?php if(!empty($reports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']) || !empty($reports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){?>
                                      (<?php echo $previous_year;?>)&nbsp;
                                    <?php } ?>
            
                                    <?php if(!empty($reports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold'])){ ?>
            
                                      SOLD (<?php echo (int)$reports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']; ?>)
                                      
            
                                    <?php } else { ?>
                                      &nbsp;
                                    <?php } ?>
                                    
                                    <!-- Adjustment Deatils -->
                                    <?php if(!empty($reports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment'])){ ?>
                                         Phy Adj. (<?php echo (int)$reports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <!-- qoh Deatils 
                                    <?php if(!empty($reports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        QU Adj (<?php echo (int)$reports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>-->
            
                                    <?php if(!empty($reports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){ ?>
            
                                      &nbsp;
                                      Receive (<?php echo (int)$reports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive']; ?>)
            
                                    <?php } else { ?>
                                      &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($reports['month_year_arr_oqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh'])){ ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)$reports['month_year_arr_oqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <?php $adjvaluereset =0 ?>
                                    <?php if(!empty($reports['month_year_arr_adjustment'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                                        &nbsp;
                                        <?php $adjustvalue= (int)$reports['month_year_arr_adjustment'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; $adjvaluereset += $adjustvalue; ?>
                                        
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($reports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        &nbsp;
                                        <?php $quickupdatevalue= (int)$reports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; $adjvaluereset += $quickupdatevalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($reports['month_year_arr_inv'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv'])){ ?>
                                        &nbsp;
                                       <?php $invresetvalue= (int)$reports['month_year_arr_inv'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv']; $adjvaluereset += $invresetvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    
                                   
                                    <?php if(!empty($reports['month_year_arr_pqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        <?php $pqohvalue= (int)$reports['month_year_arr_pqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh']; $adjvaluereset += $pqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <!---->
                                    
                                    
                                    <!------>
                                    <?php if(!empty($reports['month_year_arr_cqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                    <?php $cqohvalue= (int)$reports['month_year_arr_cqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; $adjvaluereset += $cqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if( isset($adjvaluereset) && $adjvaluereset!=0){?>
                                            Adj. (<?php echo $adjvaluereset; $adjvaluereset=0; ?>)
                                            
                                    <?php } else {?>
                                    
                                    <?php $adjvaluereset=0 ?>
                                    
                                    <?php } ?>
                                    <?php if(!empty($reports['month_year_arr_phqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh'])){ ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)$reports['month_year_arr_phqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($reports['month_year_arr_ophqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh'])){ ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)$reports['month_year_arr_ophqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
            
                                  </td>
                                  
                                  <td colspan="3" style="border-right: 1px solid #cdd0d4;border-right: 2px solid #cdd0d4;">
                                    <?php if(!empty($reports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']) || !empty($reports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){?>
                                     <!-- (<?php //echo $current_year;?>)&nbsp;-->
                                    <?php } ?>
            
                                    <?php if(!empty($reports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold'])){ ?>
            
                                      SOLD (<?php echo (int)$reports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']; ?>)
                                      
                                      
                                    <?php } ?>
                                    
                                    <?php if(!empty($reports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){ ?>
            
                                      &nbsp;
                                      Receive (<?php echo (int)$reports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive']; ?>)
            
                                    <?php }  ?>
                                    
                                     <?php if(!empty($reports['month_year_arr_were'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_were'])){ ?>
            
                                      &nbsp;
                                      WareHouse (<?php echo (int)$reports['month_year_arr_were'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_were']; ?>)
            
                                    <?php } else { ?>
                                      &nbsp;
                                    <?php } ?>
                                    
                                    
                                    <?php if(!empty($reports['month_year_arr_oqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh'])){ ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)$reports['month_year_arr_oqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($reports['month_year_arr_adjustment_phy'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment'])){ ?>
                                         Phy Adj. (<?php echo (int)$reports['month_year_arr_adjustment_phy'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <!-- Adjustment Details -->
                                    
            
                                    <?php if(!empty($reports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                                        &nbsp;
                                        <?php $adjustvalue= (int)$reports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; $adjvaluereset += $adjustvalue; ?>
                                        
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($reports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        &nbsp;
                                        <?php $quickupdatevalue= (int)$reports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; $adjvaluereset += $quickupdatevalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($reports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv'])){ ?>
                                        &nbsp;
                                       <?php $invresetvalue= (int)$reports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv']; $adjvaluereset += $invresetvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    
                                   
                                    <?php if(!empty($reports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        <?php $pqohvalue= (int)$reports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'];
                                        //$adjvaluereset += $pqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <!---->
                                    
                                    
                                    <!------>
                                    <?php if(!empty($reports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                    <?php $cqohvalue= (int)$reports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; 
                                   // $adjvaluereset += $cqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if($adjvaluereset!=0){?>
                                            Adj. (<?php echo $adjvaluereset; $adjvaluereset=0; ?>)
                                            
                                    <?php } else {?>
                                    
                                    <?php $adjvaluereset=0 ?>
                                    <?php } ?>
                                    
                                    <?php if(!empty($reports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        Parent Adj. (<?php echo (int)$reports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(isset($parentreports) && !empty($parentreports)) { ?>
                                    <?php if(!empty($reports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                        Tfr to Parent 
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php }  else { ?>
                                    
                                    <?php if(!empty($reports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                        Child Adj. (<?php echo (int)$reports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } } ?>
                                    
                                    
                                    <?php if(!empty($reports['month_year_arr_phqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh'])){ ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)$reports['month_year_arr_phqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($reports['month_year_arr_ophqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh'])){ ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)$reports['month_year_arr_ophqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($reports['month_year_arr_posqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_posqoh'])){ ?>
                                        &nbsp;
                                        Opening Qoh POS. (<?php echo (int)$reports['month_year_arr_posqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_posqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <!-- adjustment detail end ------>
                                    
                                    
                                    <!-- old code start
                                    
                                    <?php if(!empty($reports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                                        &nbsp;
                                        P Adj. (<?php echo (int)$reports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($reports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        &nbsp;
                                        QU Adj (<?php echo (int)$reports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($reports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv'])){ ?>
                                        &nbsp;
                                       IR Adj (<?php echo (int)$reports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                              
                                    <?php if(!empty($reports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        Child Update QoH(<?php echo (int)$reports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                  
                                    <?php if(!empty($reports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                        Parent Update QoH(<?php echo (int)$reports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
            
                                    
                                     old code end -->
                                    
                                  </td>
                                </tr>
                              <?php } ?>
                              
                            </tbody>
                            
                          </table>
                        </div>
                      </div>
                    </div>
                    
                    <br>
                    <br>
                <?php } ?>      
                <!-- new parent and child relationship // Hanamant B --->
                <?php if(isset($childreports)){ ?>
                <?php foreach($childreports as $childreports) { ?>
                    <?php if(isset($childreports) && count($childreports['item_data']) > 0){ ?>
                        <h3>Child</h3>
                    <?php }?>
                    
                    <?php if(isset($childreports) && count($childreports['item_data']) > 0){ ?>
                    <br><br>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="table-responsive">
                          <table class="table" style="border: 1px solid #ccc;">
                            <thead>
                              <tr>
                                <th colspan="7" class="text-center text-uppercase"><b style="font-size: 16px;"><?php echo $childreports['item_data'][0]['vitemname']; ?> [QOH: CASE <?php echo $childreports['item_data'][0]['IQTYONHAND']; ?> ]</b></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $current_year = date('Y'); 
                                $previous_year = date("Y",strtotime("-1 year"));
                              ?>
                              <tr>
                                <td colspan="2" style="background-color: #fff;"></td>
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                  <b class="text-uppercase text-info" style="font-size: 14px;">
                                  <?php echo $previous_year;?> YTD SOLD   
                                 <?php 
                                    echo !empty($childreports['year_arr_sold'][$previous_year]['total_sold']) ?(int) $childreports['year_arr_sold'][$previous_year]['total_sold'] : '0' ;
                                  ?>
                                 
                                  <?php  $value1=!empty($childreports['year_arr_adjustment'][$previous_year]['total_adjustment'])? $childreports['year_arr_adjustment'][$previous_year]['total_adjustment']:'0';  /*adjustment */
                                         $value2=!empty($childreports['year_arr_oqoh'][$previous_year]['total_oqoh']) ?  $childreports['year_arr_oqoh'][$previous_year]['total_oqoh'] : '0';        /*Opening Qoh Web*/   
                                         $value3=!empty($childreports['year_arr_qoh'][$previous_year]['total_qoh']) ? $childreports['year_arr_qoh'][$previous_year]['total_qoh'] :'0';            /*Quick Update web*/
                                         $value4=!empty($childreports['year_arr_inv'][$previous_year]['total_inv'])  ? $childreports['year_arr_inv'][$previous_year]['total_inv']:'0';       /* Parent update*/ 
                                         $value6=!empty($childreports['year_arr_cqoh'][$previous_year]['total_cqoh']) ? $childreports['year_arr_cqoh'][$previous_year]['total_cqoh'] : '0';           /*Child Upadte*/ 
                                         $value7=!empty($childreports['year_arr_phqoh'][$previous_year]['total_phqoh']) ? $childreports['year_arr_phqoh'][$previous_year]['total_phqoh']:'0';     /*Phone update  Qoh update by sku api*/
                                         $value8=!empty($childreports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh'])? $childreports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh']:'0';       /*Opening Qoh by phone */
                                         $value9=!empty($childreports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment'])? $childreports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment']:'0'; 
                                         $totaladjpreviousyr=$value1+$value2+$value3+$value4+$value6+$value7+$value8+$value9;?>
                                                              
                                      
                                  
                                  
                                </b>
                                  </td>
                                
                                
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                    <b class="text-uppercase text-info" style="font-size: 14px;">
                                        <?php echo $previous_year;?> YTD ADJUSTMENT  
                                          <?php echo $totaladjpreviousyr;?>  
                                        <?php //echo !empty($childreports['year_arr_adjustment'][$previous_year]['total_adjustment']) ? $childreports['year_arr_adjustment'][$previous_year]['total_adjustment'] : '0' ;?>
                                    </b>
                                </td>
                                  
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                  <b class="text-uppercase text-info" style="font-size: 14px;">
                                  <?php echo $previous_year; ?> YTD RECEIVE     
                                  <?php
                                    echo !empty($childreports['year_arr_receive'][$previous_year]['total_receive']) ? $childreports['year_arr_receive'][$previous_year]['total_receive']: '0' ;
                                  ?>
                                  </b>
                                  </td>
                              </tr>
                              <tr>
                                <th colspan="2" style="background-color: #fff;border-top: none;"></td>
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                  <b class="text-uppercase text-danger" style="font-size: 14px;">
                                  <?php echo $current_year;?> YTD SOLD   
                                  <?php 
                                    echo !empty($childreports['year_arr_sold'][$current_year]['total_sold']) ?(int) $childreports['year_arr_sold'][$current_year]['total_sold'] : '0' ;
                                  ?>
                                </b>
                                  </td>
                                  
                                  
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                    <b class="text-uppercase text-danger" style="font-size: 14px;">
                                        <?php echo $current_year;?> YTD ADJUSTMENT  
                                        <!--  Old code 
                                        <?php //echo !empty($childreports['year_arr_adjustment'][$current_year]['total_adjustment']) ? $childreports['year_arr_adjustment'][$current_year]['total_adjustment'] : '0' ;?>
                                    -->
                                   
                            
                                      <?php  $value1=!empty($childreports['year_arr_adjustment'][$current_year]['total_adjustment'])? $childreports['year_arr_adjustment'][$current_year]['total_adjustment']:'0';  /*adjustment */
                                         $value2=!empty($childreports['year_arr_oqoh'][$current_year]['total_oqoh']) ? $childreports['year_arr_oqoh'][$current_year]['total_oqoh'] : '0';        /*Opening Qoh Web*/   
                                         $value3=!empty($childreports['year_arr_qoh'][$current_year]['total_qoh']) ? $childreports['year_arr_qoh'][$current_year]['total_qoh'] :'0';            /*Quick Update web*/
                                         $value4=!empty($childreports['year_arr_inv'][$current_year]['total_inv'])  ? $childreports['year_arr_inv'][$current_year]['total_inv']:'0';       
                                         $value6=!empty($childreports['year_arr_cqoh'][$current_year]['total_cqoh']) ? $childreports['year_arr_cqoh'][$current_year]['total_cqoh'] : '0';           /*Child Upadte*/ 
                                         $value5=!empty($childreports['year_arr_pqoh'][$current_year]['total_pqoh']) ? $childreports['year_arr_pqoh'][$current_year]['total_pqoh'] : '0';  /* Parent update*/ 
                                         $value7=!empty($childreports['year_arr_phqoh'][$current_year]['total_phqoh']) ? $childreports['year_arr_phqoh'][$current_year]['total_phqoh']:'0';     /*Phone update  Qoh update by sku api*/
                                         $value8=!empty($childreports['year_arr_ophoqoh'][$current_year]['total_ophoqoh'])? $childreports['year_arr_ophoqoh'][$current_year]['total_ophoqoh']:'0';       /*Opening Qoh by phone */
                                         $value9=!empty($childreports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment'])? $childreports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment']:'0'; 
                                         $TotalAdjustment=$value1+$value2+$value3+$value4+$value6+$value7+$value8+$value5+$value9;?>
                                         
                                        <?php echo $TotalAdjustment;?>
                                    </b>
                                </td>
                                  
                                  
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                  <b class="text-uppercase text-danger" style="font-size: 14px;">
                                  <?php echo $current_year; ?> YTD RECEIVE  
                                  <?php
                                    echo !empty($childreports['year_arr_receive'][$current_year]['total_receive']) ? $childreports['year_arr_receive'][$current_year]['total_receive']: '0' ;
                                  ?>
                                  </b>
                                  </td>
                              </tr>
                              <tr>
                                  <th colspan="2" style="border-right: 1px solid #cdd0d4;"></th>
                                  <th colspan="3" class="text-center" style="border-right: 1px solid #cdd0d4;">Previous Year</th>
                                  <th colspan="3" class="text-center" style="border-right: 2px solid #cdd0d4;">Current Year</th>
                                  
                              </tr>
                              
                              <?php for($i = 1; $i <= 12; ++$i){ ?>
                                <tr>
                                  <td colspan="2" style="border-right: 1px solid #cdd0d4;">
                                    <b><?php echo DateTime::createFromFormat('!m', $i)->format('F'); ?></b>
                                    </td>
                                  <td colspan="3" style="border-right: 1px solid #cdd0d4;">
                                    <?php if(!empty($childreports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']) || !empty($childreports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){?>
                                      (<?php echo $previous_year;?>)&nbsp;
                                    <?php } ?>
            
                                    <?php if(!empty($childreports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold'])){ ?>
            
                                      SOLD (<?php echo (int)$childreports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']; ?>)
                                      
            
                                    <?php } else { ?>
                                      &nbsp;
                                    <?php } ?>
                                    
                                    <!-- Adjustment Deatils -->
                                    <?php if(!empty($childreports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment'])){ ?>
                                         Phy Adj. (<?php echo (int)$childreports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <!-- qoh Deatils 
                                    <?php if(!empty($childreports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        QU Adj (<?php echo (int)$childreports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>-->
            
                                    <?php if(!empty($childreports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){ ?>
            
                                      &nbsp;
                                      Receive (<?php echo (int)$childreports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive']; ?>)
            
                                    <?php } else { ?>
                                      &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($childreports['month_year_arr_oqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh'])){ ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)$childreports['month_year_arr_oqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($childreports['month_year_arr_adjustment'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                                        &nbsp;
                                        <?php $adjustvalue= (int)$childreports['month_year_arr_adjustment'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; $adjvaluereset += $adjustvalue; ?>
                                        
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($childreports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        &nbsp;
                                        <?php $quickupdatevalue= (int)$childreports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; $adjvaluereset += $quickupdatevalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($childreports['month_year_arr_inv'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv'])){ ?>
                                        &nbsp;
                                       <?php $invresetvalue= (int)$childreports['month_year_arr_inv'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv']; $adjvaluereset += $invresetvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    
                                   
                                    <?php if(!empty($childreports['month_year_arr_pqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        <?php $pqohvalue= (int)$childreports['month_year_arr_pqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh']; $adjvaluereset += $pqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <!---->
                                    
                                    
                                    <!------>
                                    <?php if(!empty($childreports['month_year_arr_cqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                    <?php $cqohvalue= (int)$childreports['month_year_arr_cqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; $adjvaluereset += $cqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if($adjvaluereset!=0){?>
                                            Adj. (<?php echo $adjvaluereset; $adjvaluereset=0; ?>)
                                            
                                    <?php } else {?>
                                    
                                    <?php $adjvaluereset=0 ?>
                                    
                                    <?php } ?>
                                    <?php if(!empty($childreports['month_year_arr_phqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh'])){ ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)$childreports['month_year_arr_phqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($childreports['month_year_arr_ophqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh'])){ ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)$childreports['month_year_arr_ophqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
            
                                  </td>
                                  
                                  <td colspan="3" style="border-right: 1px solid #cdd0d4;border-right: 2px solid #cdd0d4;">
                                    <?php if(!empty($childreports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']) || !empty($childreports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){?>
                                     <!-- (<?php //echo $current_year;?>)&nbsp;-->
                                    <?php } ?>
            
                                    <?php if(!empty($childreports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold'])){ ?>
            
                                      SOLD (<?php echo (int)$childreports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']; ?>)
                                      
                                      
                                    <?php } ?>
                                    
                                    <?php if(!empty($childreports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){ ?>
            
                                      &nbsp;
                                      Receive (<?php echo (int)$childreports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive']; ?>)
            
                                    <?php }  ?>
                                    
                                     <?php if(!empty($childreports['month_year_arr_were'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_were'])){ ?>
            
                                      &nbsp;
                                      WareHouse (<?php echo (int)$childreports['month_year_arr_were'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_were']; ?>)
            
                                    <?php } else { ?>
                                      &nbsp;
                                    <?php } ?>
                                    
                                    
                                    <?php if(!empty($childreports['month_year_arr_oqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh'])){ ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)$childreports['month_year_arr_oqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($childreports['month_year_arr_adjustment_phy'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment'])){ ?>
                                         Phy Adj. (<?php echo (int)$childreports['month_year_arr_adjustment_phy'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <!-- Adjustment Details -->
                                    
            
                                    <?php if(!empty($childreports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                                        &nbsp;
                                        <?php $adjustvalue= (int)$childreports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; $adjvaluereset += $adjustvalue; ?>
                                        
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($childreports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        &nbsp;
                                        <?php $quickupdatevalue= (int)$childreports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; $adjvaluereset += $quickupdatevalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($childreports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv'])){ ?>
                                        &nbsp;
                                       <?php $invresetvalue= (int)$childreports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv']; $adjvaluereset += $invresetvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    
                                   
                                    <?php if(!empty($childreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        <?php $pqohvalue= (int)$childreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'];
                                        //$adjvaluereset += $pqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <!---->
                                    
                                    
                                    <!------>
                                    <?php if(!empty($childreports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                    <?php $cqohvalue= (int)$childreports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; 
                                   // $adjvaluereset += $cqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if($adjvaluereset!=0){?>
                                            Adj. (<?php echo $adjvaluereset; $adjvaluereset=0; ?>)
                                            
                                    <?php } else {?>
                                    
                                    <?php $adjvaluereset=0 ?>
                                    <?php } ?>
                                    
                                    <?php if(!empty($childreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        Parent Adj. (<?php echo (int)$childreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    
                                    
                                     <?php if(!empty($childreports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                        Tfr to Parent
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php }  ?>
                                    
                                    <?php if(!empty($childreports['month_year_arr_phqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh'])){ ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)$childreports['month_year_arr_phqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($childreports['month_year_arr_ophqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh'])){ ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)$childreports['month_year_arr_ophqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <!-- adjustment detail end ------>
                                    
                                    
                                    <!-- old code start
                                    
                                    <?php if(!empty($childreports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                                        &nbsp;
                                        P Adj. (<?php echo (int)$childreports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($childreports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        &nbsp;
                                        QU Adj (<?php echo (int)$childreports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($childreports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv'])){ ?>
                                        &nbsp;
                                       IR Adj (<?php echo (int)$childreports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                              
                                    <?php if(!empty($childreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        Child Update QoH(<?php echo (int)$childreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                  
                                    <?php if(!empty($childreports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                        Parent Update QoH(<?php echo (int)$childreports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
            
                                    
                                     old code end -->
                                    
                                  </td>
                                </tr>
                              <?php } ?>
                              
                            </tbody>
                            
                          </table>
                        </div>
                      </div>
                    </div>
                    
                    <br>
                    <br>
                    <?php } ?>  
                    
                   
                <?php }
                } ?>  
                
                <!-- child End-- parent start -->
                
                <?php if(isset($parentreports)){ ?>
                <?php foreach($parentreports as $parentreports) { ?>
                    <?php if(isset($parentreports) && count($parentreports['item_data']) > 0){ ?>
                        <h3>Parent</h3>
                    <?php }?>
                    
                    <?php if(isset($parentreports) && count($parentreports['item_data']) > 0){ ?>
                    <br><br>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="table-responsive">
                          <table class="table" style="border: 1px solid #ccc;">
                            <thead>
                              <tr>
                                <th colspan="7" class="text-center text-uppercase"><b style="font-size: 16px;"><?php echo $parentreports['item_data'][0]['vitemname']; ?> [QOH: CASE <?php echo $parentreports['item_data'][0]['IQTYONHAND']; ?> ]</b></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $current_year = date('Y'); 
                                $previous_year = date("Y",strtotime("-1 year"));
                              ?>
                              <tr>
                                <td colspan="2" style="background-color: #fff;"></td>
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                  <b class="text-uppercase text-info" style="font-size: 14px;">
                                  <?php echo $previous_year;?> YTD SOLD   
                                 <?php 
                                    echo !empty($parentreports['year_arr_sold'][$previous_year]['total_sold']) ?(int) $parentreports['year_arr_sold'][$previous_year]['total_sold'] : '0' ;
                                  ?>
                                 
                                  <?php  $value1=!empty($parentreports['year_arr_adjustment'][$previous_year]['total_adjustment'])? $parentreports['year_arr_adjustment'][$previous_year]['total_adjustment']:'0';  /*adjustment */
                                         $value2=!empty($parentreports['year_arr_oqoh'][$previous_year]['total_oqoh']) ?  $parentreports['year_arr_oqoh'][$previous_year]['total_oqoh'] : '0';        /*Opening Qoh Web*/   
                                         $value3=!empty($parentreports['year_arr_qoh'][$previous_year]['total_qoh']) ? $parentreports['year_arr_qoh'][$previous_year]['total_qoh'] :'0';            /*Quick Update web*/
                                         $value4=!empty($parentreports['year_arr_inv'][$previous_year]['total_inv'])  ? $parentreports['year_arr_inv'][$previous_year]['total_inv']:'0';       /* Parent update*/ 
                                         $value6=!empty($parentreports['year_arr_cqoh'][$previous_year]['total_cqoh']) ? $parentreports['year_arr_cqoh'][$previous_year]['total_cqoh'] : '0';           /*Child Upadte*/ 
                                         $value7=!empty($parentreports['year_arr_phqoh'][$previous_year]['total_phqoh']) ? $parentreports['year_arr_phqoh'][$previous_year]['total_phqoh']:'0';     /*Phone update  Qoh update by sku api*/
                                         $value8=!empty($parentreports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh'])? $parentreports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh']:'0';       /*Opening Qoh by phone */
                                         $value9=!empty($parentreports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment'])? $parentreports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment']:'0'; 
                                         $totaladjpreviousyr=$value1+$value2+$value3+$value4+$value6+$value7+$value8+$value9;?>
                                                              
                                      
                                  
                                  
                                </b>
                                  </td>
                                
                                
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                    <b class="text-uppercase text-info" style="font-size: 14px;">
                                        <?php echo $previous_year;?> YTD ADJUSTMENT  
                                          <?php echo $totaladjpreviousyr;?>  
                                        <?php //echo !empty($parentreports['year_arr_adjustment'][$previous_year]['total_adjustment']) ? $parentreports['year_arr_adjustment'][$previous_year]['total_adjustment'] : '0' ;?>
                                    </b>
                                </td>
                                  
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                  <b class="text-uppercase text-info" style="font-size: 14px;">
                                  <?php echo $previous_year; ?> YTD RECEIVE     
                                  <?php
                                    echo !empty($parentreports['year_arr_receive'][$previous_year]['total_receive']) ? $parentreports['year_arr_receive'][$previous_year]['total_receive']: '0' ;
                                  ?>
                                  </b>
                                  </td>
                              </tr>
                              <tr>
                                <th colspan="2" style="background-color: #fff;border-top: none;"></td>
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                  <b class="text-uppercase text-danger" style="font-size: 14px;">
                                  <?php echo $current_year;?> YTD SOLD   
                                  <?php 
                                    echo !empty($parentreports['year_arr_sold'][$current_year]['total_sold']) ?(int) $parentreports['year_arr_sold'][$current_year]['total_sold'] : '0' ;
                                  ?>
                                </b>
                                  </td>
                                  
                                  
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                    <b class="text-uppercase text-danger" style="font-size: 14px;">
                                        <?php echo $current_year;?> YTD ADJUSTMENT  
                                        <!--  Old code 
                                        <?php //echo !empty($parentreports['year_arr_adjustment'][$current_year]['total_adjustment']) ? $parentreports['year_arr_adjustment'][$current_year]['total_adjustment'] : '0' ;?>
                                    -->
                                   
                                    
                                      <?php  $value1=!empty($parentreports['year_arr_adjustment'][$current_year]['total_adjustment'])? $parentreports['year_arr_adjustment'][$current_year]['total_adjustment']:'0';  /*adjustment */
                                         $value2=!empty($parentreports['year_arr_oqoh'][$current_year]['total_oqoh']) ? $parentreports['year_arr_oqoh'][$current_year]['total_oqoh'] : '0';        /*Opening Qoh Web*/   
                                         $value3=!empty($parentreports['year_arr_qoh'][$current_year]['total_qoh']) ? $parentreports['year_arr_qoh'][$current_year]['total_qoh'] :'0';            /*Quick Update web*/
                                         $value4=!empty($parentreports['year_arr_inv'][$current_year]['total_inv'])  ? $parentreports['year_arr_inv'][$current_year]['total_inv']:'0';       
                                         $value6=!empty($parentreports['year_arr_cqoh'][$current_year]['total_cqoh']) ? $parentreports['year_arr_cqoh'][$current_year]['total_cqoh'] : '0';           /*Child Upadte*/ 
                                         $value5=!empty($parentreports['year_arr_pqoh'][$current_year]['total_pqoh']) ? $parentreports['year_arr_pqoh'][$current_year]['total_pqoh'] : '0';  /* Parent update*/ 
                                         $value7=!empty($parentreports['year_arr_phqoh'][$current_year]['total_phqoh']) ? $parentreports['year_arr_phqoh'][$current_year]['total_phqoh']:'0';     /*Phone update  Qoh update by sku api*/
                                         $value8=!empty($parentreports['year_arr_ophoqoh'][$current_year]['total_ophoqoh'])? $parentreports['year_arr_ophoqoh'][$current_year]['total_ophoqoh']:'0';       /*Opening Qoh by phone */
                                         $value9=!empty($parentreports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment'])? $parentreports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment']:'0'; 
                                         $TotalAdjustment=$value1+$value2+$value3+$value4+$value6+$value7+$value8+$value5+$value9;?>
                                         
                                        <?php echo $TotalAdjustment;?>
                                    </b>
                                </td>
                                  
                                  
                                <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                  <b class="text-uppercase text-danger" style="font-size: 14px;">
                                  <?php echo $current_year; ?> YTD RECEIVE  
                                  <?php
                                    echo !empty($parentreports['year_arr_receive'][$current_year]['total_receive']) ? $parentreports['year_arr_receive'][$current_year]['total_receive']: '0' ;
                                  ?>
                                  </b>
                                  </td>
                              </tr>
                              <tr>
                                  <th colspan="2" style="border-right: 1px solid #cdd0d4;"></th>
                                  <th colspan="3" class="text-center" style="border-right: 1px solid #cdd0d4;">Previous Year</th>
                                  <th colspan="3" class="text-center" style="border-right: 2px solid #cdd0d4;">Current Year</th>
                                  
                              </tr>
                              
                              <?php for($i = 1; $i <= 12; ++$i){ ?>
                                <tr>
                                  <td colspan="2" style="border-right: 1px solid #cdd0d4;">
                                    <b><?php echo DateTime::createFromFormat('!m', $i)->format('F'); ?></b>
                                    </td>
                                  <td colspan="3" style="border-right: 1px solid #cdd0d4;">
                                    <?php if(!empty($parentreports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']) || !empty($parentreports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){?>
                                      (<?php echo $previous_year;?>)&nbsp;
                                    <?php } ?>
            
                                    <?php if(!empty($parentreports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold'])){ ?>
            
                                      SOLD (<?php echo (int)$parentreports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']; ?>)
                                      
            
                                    <?php } else { ?>
                                      &nbsp;
                                    <?php } ?>
                                    
                                    <!-- Adjustment Deatils -->
                                    <?php if(!empty($parentreports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment'])){ ?>
                                         Phy Adj. (<?php echo (int)$parentreports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <!-- qoh Deatils 
                                    <?php if(!empty($parentreports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        QU Adj (<?php echo (int)$parentreports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>-->
            
                                    <?php if(!empty($parentreports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){ ?>
            
                                      &nbsp;
                                      Receive (<?php echo (int)$parentreports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive']; ?>)
            
                                    <?php } else { ?>
                                      &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($parentreports['month_year_arr_oqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh'])){ ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)$parentreports['month_year_arr_oqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($parentreports['month_year_arr_adjustment'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                                        &nbsp;
                                        <?php $adjustvalue= (int)$parentreports['month_year_arr_adjustment'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; $adjvaluereset += $adjustvalue; ?>
                                        
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($parentreports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        &nbsp;
                                        <?php $quickupdatevalue= (int)$parentreports['month_year_arr_qoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; $adjvaluereset += $quickupdatevalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($parentreports['month_year_arr_inv'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv'])){ ?>
                                        &nbsp;
                                       <?php $invresetvalue= (int)$parentreports['month_year_arr_inv'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv']; $adjvaluereset += $invresetvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    
                                   
                                    <?php if(!empty($parentreports['month_year_arr_pqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        <?php $pqohvalue= (int)$parentreports['month_year_arr_pqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh']; $adjvaluereset += $pqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <!---->
                                    
                                    
                                    <!------>
                                    <?php if(!empty($parentreports['month_year_arr_cqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                    <?php $cqohvalue= (int)$parentreports['month_year_arr_cqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; $adjvaluereset += $cqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if($adjvaluereset!=0){?>
                                            Adj. (<?php echo $adjvaluereset; $adjvaluereset=0; ?>)
                                            
                                    <?php } else {?>
                                    
                                    <?php $adjvaluereset=0 ?>
                                    
                                    <?php } ?>
                                    <?php if(!empty($parentreports['month_year_arr_phqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh'])){ ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)$parentreports['month_year_arr_phqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($parentreports['month_year_arr_ophqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh'])){ ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)$parentreports['month_year_arr_ophqoh'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
            
                                  </td>
                                  
                                  <td colspan="3" style="border-right: 1px solid #cdd0d4;border-right: 2px solid #cdd0d4;">
                                    <?php if(!empty($parentreports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']) || !empty($parentreports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){?>
                                     <!-- (<?php //echo $current_year;?>)&nbsp;-->
                                    <?php } ?>
            
                                    <?php if(!empty($parentreports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold'])){ ?>
            
                                      SOLD (<?php echo (int)$parentreports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']; ?>)
                                      
                                      
                                    <?php } ?>
                                    
                                    <?php if(!empty($parentreports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){ ?>
            
                                      &nbsp;
                                      Receive (<?php echo (int)$parentreports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive']; ?>)
            
                                    <?php }  ?>
                                    
                                     <?php if(!empty($parentreports['month_year_arr_were'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_were'])){ ?>
            
                                      &nbsp;
                                      WareHouse (<?php echo (int)$parentreports['month_year_arr_were'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_were']; ?>)
            
                                    <?php } else { ?>
                                      &nbsp;
                                    <?php } ?>
                                    
                                    
                                    <?php if(!empty($parentreports['month_year_arr_oqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh'])){ ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)$parentreports['month_year_arr_oqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_oqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($parentreports['month_year_arr_adjustment_phy'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment'])){ ?>
                                         Phy Adj. (<?php echo (int)$parentreports['month_year_arr_adjustment_phy'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <!-- Adjustment Details -->
                                    
            
                                    <?php if(!empty($parentreports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                                        &nbsp;
                                        <?php $adjustvalue= (int)$parentreports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; $adjvaluereset += $adjustvalue; ?>
                                        
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($parentreports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        &nbsp;
                                        <?php $quickupdatevalue= (int)$parentreports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; $adjvaluereset += $quickupdatevalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($parentreports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv'])){ ?>
                                        &nbsp;
                                       <?php $invresetvalue= (int)$parentreports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv']; $adjvaluereset += $invresetvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    
                                   
                                    <?php if(!empty($parentreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        <?php $pqohvalue= (int)$parentreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'];
                                        //$adjvaluereset += $pqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <!---->
                                    
                                    
                                    <!------>
                                    <?php if(!empty($parentreports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                    <?php $cqohvalue= (int)$parentreports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; 
                                    $adjvaluereset += $cqohvalue; ?>
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if($adjvaluereset!=0){?>
                                            Adj. (<?php echo $adjvaluereset; $adjvaluereset=0; ?>)
                                            
                                    <?php } else {?>
                                    
                                    <?php $adjvaluereset=0 ?>
                                    
                                    <?php if(!empty($parentreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        Parent Adj. (<?php echo (int)$parentreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php } ?>
                                    <?php if(!empty($parentreports['month_year_arr_phqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh'])){ ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)$parentreports['month_year_arr_phqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_phqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($parentreports['month_year_arr_ophqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh'])){ ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)$parentreports['month_year_arr_ophqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    <!-- adjustment detail end ------>
                                    
                                    
                                    <!-- old code start
                                    
                                    <?php if(!empty($parentreports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                                        &nbsp;
                                        P Adj. (<?php echo (int)$parentreports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                     <?php if(!empty($parentreports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh'])){ ?>
                                        &nbsp;
                                        QU Adj (<?php echo (int)$parentreports['month_year_arr_qoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_qoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                                    <?php if(!empty($parentreports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv'])){ ?>
                                        &nbsp;
                                       IR Adj (<?php echo (int)$parentreports['month_year_arr_inv'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_inv']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                    
                              
                                    <?php if(!empty($parentreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh'])){ ?>
                                        &nbsp;
                                        Child Update QoH(<?php echo (int)$parentreports['month_year_arr_pqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_pqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
                                  
                                    <?php if(!empty($parentreports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh'])){ ?>
                                        &nbsp;
                                        Parent Update QoH(<?php echo (int)$parentreports['month_year_arr_cqoh'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_cqoh']; ?>)
                                    <?php } else { ?>
                                        &nbsp;
                                    <?php } ?>
            
                                    
                                     old code end -->
                                    
                                  </td>
                                </tr>
                              <?php } ?>
                              
                            </tbody>
                            
                          </table>
                        </div>
                      </div>
                    </div>
                    
                    <br>
                    <br>
                    <?php } ?>  
                    
                   
                <?php }
                } ?> 
                <!---parent end --->
                
                <?php if(isset($reports) && count($reports['item_data']) > 0){ ?>      
                <div class="row">
                  <div class="col-md-12" id="item_movement_by_date_selection" >
                    <h3 class="text-danger">Item Movement By Date</h3>
                    <div class="row">
                      <div class="col-md-2">
                        <input type="" class="form-control" name="start_date" value="" id="start_date" placeholder="Start Date" readonly>
                      </div>
                      <div class="col-md-2">
                        <input type="" class="form-control" name="end_date" value="" id="end_date" placeholder="End Date" readonly>
                      </div>
                      <div class="col-md-3">
                        <select class="form-control" name="search_by_item">
                          <option value="sold">Sold</option>
                          <option value="receive">Receive</option>
                          <option value="adjustment">Adjustment</option>
                          <option value="openingqoh">Opening Qoh WEB</option>
                          <option value="openingpos">Opening Qoh POS</option>
                          <option value="phoneadjustment">Phone Adjustment</option>
                          <option value="openingqohphone">Opening QoH Phone</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <input type="button" class="btn btn-success item_movement_btn" value="Search">
                      </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="item_movement_by_date_selection_table" style="display: none;">
                        <thead>
                          <tr>
                            <th id="first_th">Print Receipt</th>
                            <th>Action</th>
                            <th>Date</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Pack Qty</th>
                            <th class="text-right">Size</th>
                            <th class="text-right">Price</th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
                <?php } ?>
            </div>
            
        </div>

      </div>
    </div>
  </div>
  
</div>

<?php if(session()->get('hq_sid') == 1){ ?>
    <?php if(isset($data['iitemid']) && isset($data['edit_page'])){ ?>
        <div id="editModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want to update the items: </h4>
              </div>
              <div class="modal-body">
                 <table class="table table-bordered">
                    <thead id="table_green_header_tag">
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="" id="editSelectAllCheckbox" name="" value="" style="background: none !important;">
                                </div>
                            </th>
                            <th colspan="2" id="table_green_header">Select All</th>
                        </tr>
                    </thead >
                    <tbody id="data_stores"></tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button id="update_btn" class="btn btn-danger" data-dismiss="modal">Update</button>
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
              </div>
            </div>
          </div>
        </div>
    <?php } else { ?>
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want to add the Items:</h4>
              </div>
            
              <div class="modal-body">
                 <table class="table table-bordered">
                    <thead id="table_green_header_tag">
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="" id="selectAllCheckbox" name="" value="" style="background: none !important;">
                                </div>
                            </th>
                            <th colspan="2" id="table_green_header">Select All</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session()->get('stores_hq') as $stores)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox" id="table_green_check">
                                        <input type="checkbox" class="checks check custom-control-input itemstores" id="hq_sid_{{ $stores->id }}" name="itemstores" value="{{ $stores->id }}">
                                    </div>
                                </td>
                                <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button id="save_btn" class="btn btn-danger" data-dismiss="modal">Save</button>
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
              </div>
            </div>
        
          </div>
        </div>
    <?php } ?>
<?php } ?>

<?php if(session()->get('hq_sid') == 1){  ?>
    <div id="aliaModalStores" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want to add the Items Alias Code:</h4>
              </div>
            
              <div class="modal-body">
                 <table class="table table-bordered">
                    <thead id="table_green_header_tag">
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="" id="selectAllCheckboxAlias" name="" value="" style="background: none !important;">
                                </div>
                            </th>
                            <th colspan="2" id="table_green_header">Select All</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session()->get('stores_hq') as $stores)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox" id="table_green_check">
                                        <input type="checkbox" class="checks check custom-control-input stores" id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">
                                    </div>
                                </td>
                                <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button id="alias_save_btn" class="btn btn-danger" data-dismiss="modal">Save</button>
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
              </div>
            </div>
        
          </div>
        </div>
<?php } ?>

    <div id="vendorAddition" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">In the following stores this vendor is not present.</h4>
                <h5>Do you want to Add?</h5>
              </div>
            
              <div class="modal-body">
                 <table class="table table-bordered">
                    <thead id="table_green_header_tag">
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="" id="selectAllCheckboxVendor" name="" value="" style="background: none !important;">
                                </div>
                            </th>
                            <th colspan="2" id="table_green_header">Select All</th>
                        </tr>
                    </thead>
                    <tbody id="storesVendorAddition"></tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button id="vendor_save_btn" class="btn btn-success" data-dismiss="modal">Add</button>
                <button id="cancel_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
            </div>
        
          </div>
        </div>
        
    <?php if(session()->get('hq_sid') == 1){ ?>
        <div id="vendorAssignModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want assign vendors.</h4>
              </div>
            
              <div class="modal-body">
                 <table class="table table-bordered">
                    <thead id="table_green_header_tag">
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="" id="selectAllCheckboxVendorAssign" name="" value="" style="background: none !important;">
                                </div>
                            </th>
                            <th colspan="2" id="table_green_header">Select All</th>
                        </tr>
                    </thead>
                    <tbody id="data_vendor_stores"></tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button id="vendor_assign_btn" class="btn btn-success" data-dismiss="modal">Assign</button>
              </div>
            </div>
        
          </div>
        </div>
        <div id="vendorAssignUpdateModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want assign vendors.</h4>
              </div>
              <div class="modal-body">
                 <table class="table table-bordered">
                    <thead id="table_green_header_tag">
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="" id="selectAllCheckboxUpdateItemCode" name="" value="" style="background: none !important;">
                                </div>
                            </th>
                            <th colspan="2" id="table_green_header">Select All</th>
                        </tr>
                    </thead>
                    <tbody id="update_vendor_itemcode"></tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button id="vendor_itemcode_update" class="btn btn-success" data-dismiss="modal">Update</button>
              </div>
            </div>
        
          </div>
        </div>
    <?php } ?>

@endsection


@section('scripts')

<style type="text/css">

 .nav.nav-tabs .active a{
    background-color: #f05a28 !important; 
    color: #fff !important; 
  }

  .nav.nav-tabs li a{
    color: #fff !important; 
    background-color: #03A9F4; 
  }

  .nav.nav-tabs li a:hover{
    color: #fff !important; 
    background-color: #f05a28 !important; 
  }

  .nav.nav-tabs li a:hover{
    color: #fff !important; 
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
  
/*-----for remove up/down arrows from input type number--------*/
  /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

/* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
    
    .disabled {
        pointer-events:none; //This makes it not clickable
 
    }
  
</style>

<link type="text/css" href="{{ asset('javascript/bootstrap-datepicker.css') }}" rel="stylesheet" />
<script src="{{ asset('javascript/bootstrap-datepicker.js') }}" defer></script>

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
  
  
  
//   For vendor update for head Quaters
  <?php if(session()->get('hq_sid') == 1){  ?>
    $("#ivendorid").change(function(){
        var vsuppliercode = $("#ivendorid").val();
        dataItemOrders  = [];
        dataItemOrders.push($("#ivendorid").val());
         $.ajax({
            url : "<?php echo url('/item/checkVendorExists'); ?>",
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : JSON.stringify(dataItemOrders),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {
                var storecount = data.length;
                var tableContent = '';
                for(i=0; i < data.length; i++ ){
                    tableContent += '<tr>'+
                                            '<td>'+
                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                    '<input type="checkbox" class="checks check custom-control-input vendorstores" id="'+ data[i] +'" name="vendorstores" value="'+ data[i] +'">'+
                                                '</div>'+
                                            '</td>'+
                                            '<td class="checks_content"><span> '+ data[i] +'</span></td>'+
                                    '</tr>';
                }
                $("#storesVendorAddition").html(tableContent);
                if(data.length > 0){
                    $('#vendorAddition').modal('show');
                }
            },
          });
    });
<?php } ?>
    
var vendorStores = [];
$('#selectAllCheckboxVendor').click(function(){
    if($('#selectAllCheckboxVendor').is(":checked")){
        $(".vendorstores").prop( "checked", true );
    }else{
        $( ".vendorstores" ).prop("checked", false );
    }
});
        
        
$("#vendor_save_btn").click(function(){
    $.each($('input[name="vendorstores"]:checked'), function(){            
        vendorStores.push($(this).val());
    });
    console.log(vendorStores);
    var vsuppliercode = $("#ivendorid").val();
    var d = JSON.stringify({ stores_hq: vendorStores, vendorecode:vsuppliercode});
    
    vendorStores = [];
    $.ajax({
        url : "<?php echo url('/item/addVendorsFromitems'); ?>",
        headers: {
            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        data : d,
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
        success: function(data) {
           alert("succesfully added vendors in the selected stores");
        },
    });
  
});
    
$("#closeBtn").click(function(){
    $("div#divLoading").removeClass('show');
});
</script>

<?php if(!isset($data['edit_page'])){?>
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
        //input-profit-margin
    
        if(unitpercase!= '' && avg_case_cost != '' && $('#input-Selling Price').val() !=''){
          var sell_price = $('#input-Selling-Price').val();
          
          
          var buyDown = $('input[name="ndiscountper"]').val();
          var per = sell_price - ($('#input-unitcost').val()-buyDown);
    
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

    $(document).on('keypress', '#nbottledepositamt', function(event) {
    
    // var character = String.fromCharCode(event.keyCode)
    // var newValue = this.value + character; 
    // if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
    //     event.preventDefault();
    //     return false;
    // }
    this.value = this.value.match(/^\d+\.?\d{0,2}/);
});

$(document).on('keyup', '#input-Selling_Price', function(event) {
    
    this.value = this.value.match(/^\d+\.?\d{0,2}/);

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
    
    // if(buyDown != ''){
    //     gross_profit = selling_price - (new_costprice - buyDown);
    //     level2_gross_profit = level2_selling_price - (new_costprice - buyDown);
    //     level3_gross_profit = level3_selling_price - (new_costprice - buyDown);
    //     level4_gross_profit = level4_selling_price - (new_costprice - buyDown);
    // }else{
    //     gross_profit = selling_price - new_costprice;
    //     level2_gross_profit = level2_selling_price - new_costprice;
    //     level3_gross_profit = level3_selling_price - new_costprice;
    //     level4_gross_profit = level4_selling_price - new_costprice;
    // }
    
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
    
    if(selling_price != '' && selling_price != 0){
        $('input[name="gross_profit"]').val(prof_mar.toFixed(2));
    }else{
        $('input[name="gross_profit"]').val();   
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
  
  $(document).on('keyup', '#input_buydown', function(event) {
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
  
  
</script>

<script type="text/javascript">
  $(document).on('submit', 'form#form-item-alias-code', function(event) {
    event.preventDefault();
    <?php if(session()->get('hq_sid') == 1){  ?>
        $('#aliaModalStores').modal('show');
    <?php  } else { ?>
    
        var sid = "<?php echo $data['sid'];?>";
        var url = $(this).attr('action');
        // var url = $(this).attr('action')+"&sid="+sid;
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
                if(data.error){
                    $('#error_alias').html('<strong>'+ data.error +'</strong>');
                    $('#errorAliasModal').modal('show');
                }else{
                    $('#success_alias').html('<strong>'+ data.success +'</strong>');
                    $('#successAliasModal').modal('show');   
                }
                $.cookie("tab_selected", 'alias_code_tab'); //set cookie tab
                setTimeout(function(){
                    window.location.reload();
                    $("div#divLoading").addClass('show');
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
    <?php } ?>
  });
  
  
    var aliasStores = []; 
    aliasStores.push("{{ session()->get('sid') }}");
    $('#selectAllCheckboxAlias').click(function(){
        if($('#selectAllCheckboxAlias').is(":checked")){
            $(".stores").prop( "checked", true );
        }else{
            $( ".stores" ).prop("checked", false );
        }
    }); 
        
    $('#alias_save_btn').click(function(){
        $.each($("input[name='stores']:checked"), function(){            
            aliasStores.push($(this).val());
        });
        $("#alias_stores_hq").val(aliasStores);
        
        var sid = "<?php echo $data['sid'];?>";
        var url = '<?php echo $data['add_alias_code']; ?>';
        // var url = $(this).attr('action')+"&sid="+sid;
        var vitemcode = '<?php echo isset($data['vbarcode']) ? $data['vbarcode'] : ''; ?>';
        var vsku = '<?php echo isset($data['vbarcode']) ? $data['vbarcode'] : ''; ?>';
        var stores_hq = $("#alias_stores_hq").val();
        var valiassku = $("#valiassku").val();
       
        var data = {vitemcode: vitemcode, vsku: vsku, stores_hq: stores_hq, valiassku: valiassku};
    
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
                
                if(data.error){
                    $('#error_alias').html('<strong>'+ data.error +'</strong>');
                    $('#errorAliasModal').modal('show');
                }else{
                    $('#success_alias').html('<strong>'+ data.success +'</strong>');
                    $('#successAliasModal').modal('show');   
                }
                
                $.cookie("tab_selected", 'alias_code_tab'); //set cookie tab
                setTimeout(function(){
                    window.location.reload();
                    $("div#divLoading").addClass('show');
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
    })
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
        
        if(data.error){
            $('#error_alias').html('<strong>'+ data.error +'</strong>');
            $('#errorAliasModal').modal('show');
        }else{
            $('#success_alias').html('<strong>'+ data.success +'</strong>');
            $('#successAliasModal').modal('show');
        }
        
        $.cookie("tab_selected", 'alias_code_tab'); //set cookie tab
        setTimeout(function(){
            window.location.reload();
            $("div#divLoading").addClass('show');
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
          var npackcost = "<?php echo $data['nunitcost'];?>";
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
    this.value = this.value.match(/^\d+\.?\d{0,2}/);
});

$(document).on('keypress', '#input_npackprice', function(event){
    
    var character = String.fromCharCode(event.keyCode)
    var newValue = this.value + character;
    if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
        event.preventDefault();
        return false;
    }
    this.value = this.value.match(/^\d+\.?\d{0,2}/);
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
        
        if(data.success){
          $('#success_alias').html('<strong>'+ data.success +'</strong>');
          $('#addLotItemModal').modal('hide');
          $('#successAliasModal').modal('show');
        }else{
            $('#error_alias').html('<strong>'+ data.error +'</strong>');
            $('#addLotItemModal').modal('hide');
            $('#errorAliasModal').modal('show');
            // return false;
        }
      $.cookie("tab_selected", 'lot_matrix_tab'); //set cookie tab
      setTimeout(function(){
       window.location.reload();
       $("div#divLoading").addClass('show');
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
       $("div#divLoading").addClass('show');
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
       $("div#divLoading").addClass('show');
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
      /*setTimeout(function(){
       window.location.reload();
       $("div#divLoading").addClass('show');
      }, 3000);*/
      
 /*   },
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
/*  $(document).on('submit', 'form#remove_parent_item', function(event) {
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
       $("div#divLoading").addClass('show');
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
    if ((!!$.cookie('tab_selected')) && ($.cookie('tab_selected') != '')) {
      var tab_s = $.cookie('tab_selected');
      // have cookie
      $('#myTab li.active').removeClass('active');
      $('.tab-content div.tab-pane.active').removeClass('active');
    // console.log(tab_s);
      if(tab_s == 'alias_code_tab'){
        $('#myTab li:eq(1)').addClass('active');
        $('.tab-content #alias_code_tab').addClass('active');
      }else if(tab_s == 'lot_matrix_tab'){
        $('#myTab li:eq(2)').addClass('active');
        $('.tab-content #lot_matrix_tab').addClass('active');
      }else if(tab_s == 'vendor_tab'){
        $('#myTab li:eq(3)').addClass('active');
        $('.tab-content #vendor_tab').addClass('active');
      }else{
            $('#myTab li:eq(0)').addClass('active');
            $('.tab-content #item_tab').addClass('active');
      }
    } else {
     // no cookie
     var tab_selected = "<?= $data['tab_selected']; ?>";

     <?php if(isset($data['tab_selected']) && !empty($data['tab_selected'])){?>
      $('#myTab li.active').removeClass('active');
      $('.tab-content div.tab-pane.active').removeClass('active');
    
      <?php if($data['tab_selected'] == 'alias_code_tab'){ ?>
        $('#myTab li:eq(1)').addClass('active');
        $('.tab-content #alias_code_tab').addClass('active');
      <?php }else if($data['tab_selected'] == 'lot_matrix_tab'){ ?>
        $('#myTab li:eq(2)').addClass('active');
        $('.tab-content #lot_matrix_tab').addClass('active');
      <?php }else if($data['tab_selected'] == 'vendor_tab'){ ?>
        $('#myTab li:eq(3)').addClass('active');
        $('.tab-content #vendor_tab').addClass('active');
      <?php }else { ?>
        $('#myTab li:eq(0)').addClass('active');
        $('.tab-content #item_tab').addClass('active');
      <?php } ?>

    <?php } ?>
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
    // console.log(unitcost);
    // console.log(buyDown);
    if(new_costprice != '' && new_costprice > 0 ){
        
        if(selling_price != '' & isFinite(prof_mar)){
            $('input[name="gross_profit"]').val(prof_mar.toFixed(2));
        }else{
            $('input[name="gross_profit"]').val('');
        }
        
        if(level2_selling_price != '' && level2_selling_price > 0 ){
            $('#input-gross-profit2').val(prof_mar2.toFixed(2));
        }else{
            $('#input-gross-profit2').val();
        }
        
        if(level3_selling_price != '' && level3_selling_price > 0 ){
            $('#input-gross-profit3').val(prof_mar3.toFixed(2));
        }else{
            $('#input-gross-profit3').val();
        }
        
        if(level4_selling_price != '' && level4_selling_price > 0 ){
            $('#input-gross-profit4').val(prof_mar4.toFixed(2));
        }else{
            $('#input-gross-profit4').val();
        }
    }else{
        
        $('input[name="gross_profit"]').val();
        $('#input-gross-profit2').val();
        $('#input-gross-profit3').val();
        $('#input-gross-profit4').val();
    }
    
    // console.log(buyDown);
    

});

</script>

<script type="text/javascript">
  $(document).on('submit', 'form#form-item1', function(e) {
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

  $(document).on('keypress keyup blur', 'input[name="nbottledepositamt"], .slab_price_nprice,.input_npackprice', function(event) {

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
//$(document).on('keypress keyup blur', 'input[name="dcostprice"],input[name="nlevel2"],input[name="nlevel3"],input[name="nlevel4"],input[name="dunitprice"],input[name="ndiscountper"],input[name="nprice"],input[name="npackprice"]', function(event) {

  $(document).on('keypress keyup blur', 'input[name="dcostprice"],input[name="nlevel2"],input[name="nlevel3"],input[name="nlevel4"],input[name="ndiscountper"],input[name="nprice"],input[name="npackprice"]', function(event) {

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

    .department {
        width: 87% !important;
    }

  .select2-container--default .select2-selection--single{
    border-radius: 0px !important;
    height: 35px !important;
    width
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $('select[name="vitemtype"]').select2();
  $('select[name="taxlist[]"]').select2();
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
  $('select[name="subcat_id"]').select2();
  $('select[name="manufacturer_id"]').select2();
  $('select[name="itemPromotionid"]').select2();
  
</script>

<script type="text/javascript">
  $(document).on('click', '#remove_item_img', function(event) {
    event.preventDefault();
    var path = '{{ asset('image/user-icon-profile.png') }}';
    $("#showImage").attr("src", path);
    $('input[name="pre_itemimage"]').val('');
    $(this).hide();
    $('select[name="vshowimage"]').val('No');

  });
</script>

<script type="text/javascript">
  $(document).on('click', '#add_new_category', function(event) {
    event.preventDefault();
    
    var deptCode = $("#dept_code").val();
    
    if(deptCode == ""){
        
        $('#success_alias').html('<strong>Please select a Department !!</strong>');
        $('#successAliasModal').modal('show');

        setTimeout(function(){
         $('#successAliasModal').modal('hide');
        }, 3000);
        
    } else {
        $('form#category_add_new_form').find('#add_vcategoryname').val('');
        $('form#category_add_new_form').find('#category_add_vdescription').val('');
        
        $('#category_dept_name').val($("#dept_code option:selected").text());
        $('#category_dept_code').val($("#dept_code").val());
        $('#addModalCatogory').modal('show');
    }
    
    
  });

  $(document).on('submit', 'form#category_add_new_form', function(event) {
    event.preventDefault();
    
    if($(this).find('#add_vcategoryname').val() == ''){
      alert('Please enter category name!');
      return false;
    }

    var url = $(this).attr('action');

    var data = new Array();
    var categoryId;

    data[0]={};
    data[0]['vcategoryname'] = $(this).find('#add_vcategoryname').val();
    data[0]['vdescription'] = $(this).find('#category_add_vdescription').val();
    data[0]['vcategorttype'] = $(this).find('select[name="vcategorttype"]').val();
    data[0]['isequence'] = $(this).find('input[name="isequence"]').val();
    data[0]['dept_code'] = $("#dept_code").val();
    
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
        categoryId = resp.category_id;
        $('#success_alias').html('<strong>'+ resp.success +'</strong>');
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
          
        var input = {};
        var deptCode = $("#dept_code").val();
        input['dept_code'] = deptCode;
        
        var sid = "<?php echo $data['sid'];?>";
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
                var category_html = '';
                if(response.length > 0){
                    $('select[name="vcategorycode"]').select2().empty().select2({data: response});
                }
                // console.log(categoryId);
                $('#category_code').val(categoryId).trigger('change.select2');
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

<!-- Modal Add -->
  <div class="modal fade" id="addModalCatogory" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Category under <span id="span_dept_name"></span></h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_category']; ?>" method="post" id="category_add_new_form">
             @csrf
            <input type="hidden" name="isequence" value="0">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="50" class="form-control" id="add_vcategoryname" name="vcategoryname">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Description</label>
                  </div>
                  <div class="col-md-10">  
                    <textarea maxlength="100" name="vdescription" id="category_add_vdescription" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Type</label>
                  </div>
                  <div class="col-md-10">  
                    <select name="vcategorttype" id="" class="form-control ">
                      <option value="<?php echo $data['Sales']; ?>" selected="selected"><?php echo $data['Sales']; ?></option>
                      <option value="<?php echo $data['MISC']; ?>" ><?php echo $data['MISC']; ?></option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Department</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" name="category_dept_name" id="category_dept_name" class="form-control" value="" disabled="true">
                    <input type="hidden" name="category_dept_code" id="category_dept_code" class="form-control" value="<?php echo isset($data['vdepcode'])?$data['vdepcode']:''; ?>">
                    
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
        
        if($("#category_code").val() == '' || $("#category_code").val() == '--Select Category--'){
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: "Please Select Category!", 
            callback: function(){}
          });
          return false;
        }
        console.log($("#category_code").val());
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
            
            subcategoryId = resp.subcat_id;
            console.log(subcategoryId);
            $('#success_alias').html('<strong>'+ resp.success +'</strong>');
            $('#addModalSubCatogory').modal('hide');
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
                console.log(subcategoryId);
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
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Sub Category - <span id="span_cat_name">  </span></h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_subcategory']; ?>" method="post" id="subcategory_add_new_form">
             @csrf
            <input type="hidden" name="isequence" value="0">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="50" class="form-control" id="add_subcat_name" name="subcat_name">
                  </div>
                </div>
              </div>
            </div>
            <br>
            
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

<!-- Modal Add New Sub Category -->

<!-- Modal Add Manufacuturer-->
  <div class="modal fade" id="addModalManufacturer" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Manufaturer</h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_manufacturer']; ?>" method="post" id="manufacturer_add_new_form">
              @csrf
            <input type="hidden" name="isequence" value="0">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="50" class="form-control" id="add_mfr_name" name="mfr_name">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Code</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="10" name="mfr_code" id="add_mfr_code" class="form-control"></textarea>
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
<!-- Modal Add Manufacturer-->
<script type="text/javascript">
  $(document).on('click', '#add_new_department', function(event) {
    event.preventDefault();

    $('form#department_add_new_form').find('#add_vdepartmentname').val('');
    $('form#department_add_new_form').find('#category_add_vdescription').val('');

    $('#addModalDepartment').modal('show');
  });
    
    $(document).on('click', '#add_new_manufacturer', function(event) {
        event.preventDefault();
    
        $('form#manufacturer_add_new_form').find('#add_mfr_name').val('');
        $('form#manufacturer_add_new_form').find('#add_mfr_code').val('');
    
        $('#addModalManufacturer').modal('show');
      });
      
    $(document).on('submit', 'form#manufacturer_add_new_form', function(event) {
    event.preventDefault();
    
    if($(this).find('#add_mfr_name').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Enter Manufacturer Name!", 
        callback: function(){}
      });
      return false;
    }
    
    if($(this).find('#add_mfr_code').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Enter Manufacturer Code!", 
        callback: function(){}
      });
      return false;
    }

    var url = $(this).attr('action');

    var data = new Array();

    data[0]={};
    data[0]['mfr_name'] = $(this).find('#add_mfr_name').val();
    data[0]['mfr_code'] = $(this).find('#add_mfr_code').val();
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
        $('#addModalManufacturer').modal('hide');
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
        var get_new_manufacturer = "<?php echo $data['get_new_manufacturer'];?>";
        get_new_manufacturer = get_new_manufacturer.replace(/&amp;/g, '&');
        // get_new_manufacturer = get_new_manufacturer+"&sid="+sid;

        $.getJSON(get_new_manufacturer, function(datas) {
          $('select[name="manufacturer_id"]').empty();
          var department_html = '';
          $.each(datas, function(index,v) {
            department_html += '<option value="'+ v.mfr_id +'">'+ v.mfr_name +'</option>';
          });
          $('select[name="manufacturer_id"]').append(department_html);
        });
      }, 3000);
  });
      
  $(document).on('submit', 'form#department_add_new_form', function(event) {
    event.preventDefault();
    
    if($(this).find('#add_vdepartmentname').val() == ''){
      alert('Please enter department name!');
      return false;
    }

    var sid = "<?php echo $data['sid'];?>";
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
        var department_id = data.department_id;
        
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
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Department</h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_department']; ?>" method="post" id="department_add_new_form">
             @csrf
            <input type="hidden" name="isequence" value="0">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="50" class="form-control" id="add_vdepartmentname" name="vdepartmentname">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Description</label>
                  </div>
                  <div class="col-md-10">  
                    <textarea maxlength="100" name="vdescription" id="department_add_vdescription" class="form-control"></textarea>
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
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Size</h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo $data['add_new_size']; ?>" method="post" id="size_add_new_form">
              @csrf
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="50" class="form-control" id="add_vsize" name="vsize">
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
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Supplier</h4>
        </div>
        <div class="modal-body small">
          <form action="<?php echo $data['add_new_supplier']; ?>" method="post" id="supplier_add_new_form">
              @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Vendor Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="50" class="form-control" id="add_vcompanyname" name="vcompanyname">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Vendor Type</label>
                  </div>
                  <div class="col-md-10">  
                    <select name="vvendortype" class="form-control"> 
                      <option value="Vendor">Vendor</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>First Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="25" class="form-control" id="add_vfnmae" name="vfnmae">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Last Name</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="25" class="form-control" id="add_vlname" name="vlname">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Vendor Code</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="20" class="form-control" id="add_vcode" name="vcode">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Address&nbsp;&nbsp;</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="100" class="form-control" id="add_vaddress1" name="vaddress1">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>City</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="20" class="form-control" id="add_vcity" name="vcity">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>State</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="20" class="form-control" id="add_vstate" name="vstate">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Phone</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="20" class="form-control" id="add_vphone" name="vphone">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Zip</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="10" class="form-control" id="add_vzip" name="vzip">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Country</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="text" maxlength="20" class="form-control" id="add_vcountry" name="vcountry" value="USA" readonly>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Email</label>
                  </div>
                  <div class="col-md-10">  
                    <input type="email" maxlength="100" class="form-control" id="add_vemail" name="vemail" onkeyup="isEmail()" autocomplete="off" />
                    <span class="text-warning" id="email_error"></span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>PLCB Type</label>
                  </div>
                  <div class="col-md-10">  
                    <select name="plcbtype" class="form-control">
                      <option value="None">None</option>
                      <option value="Schedule A">Schedule A</option>
                      <option value="Schedule B">Schedule B</option>
                      <option value="Schedule C">Schedule C</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn btn-success" type="submit" id="save_supplier" value="Save">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
  $(document).on('change', 'select[name="visinventory"]', function(event) {
    event.preventDefault();
    if($(this).val() == 'No'){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Your existing inventory is zero!", 
        callback: function(){}
      });
    }
  });
</script>

<script>
    $(function() {
        
        var parent_child_search_url = '<?php echo $data['parent_child_search'];?>';
        
        parent_child_search_url = parent_child_search_url.replace(/&amp;/g, '&');
        
        
        $( "#search_parent_sku" ).autocomplete({
            minLength: 2,
            source: function(req, add) {
                $.getJSON(parent_child_search_url, req, function(data) {
                    var suggestions = [];
                    $.each(data, function(i, val) {
                      var present_iitemid = "<?php echo $data['iitemid']; ?>";
                      if(present_iitemid != val.iitemid){
                        suggestions.push({
                            label: val.vitemname,
                            value: val.vitemname,
                            id: val.iitemid
                        });
                      }
                    });
                    add(suggestions);
                });
            },
            select: function(e, ui) {
            //   $('form#form_add_parent_item select[name="parent_item_id"]').val(ui.item.id);
            
              $('form#form_add_parent_item select[name="parent_item_id"]').append(new Option(ui.item.value, ui.item.id));
              $('form#form_add_parent_item select[name="parent_item_id"]').val(ui.item.id);
              $('#form_add_parent_item').submit();
            }
        });
            
            
            
            
            
            
            
        var sid = "<?php echo $data['sid'];?>";
            var url = '<?php echo $data['get_categories']; ?>';
                // url += "&sid="+sid;
                url = url.replace(/&amp;/g, '&');
                
        <?php if(isset($data['vdepcode']) && $data['vcategorycode']){ ?>
            var input = {};
            var deptCode = <?php echo $data['vdepcode']; ?>;
            var categoryCode = '<?php echo $data['vcategorycode']; ?>';
            input['dept_code'] = deptCode;
                
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
                        // $('#category_code').val(categoryCode);
                        $('#category_code').val(categoryCode).trigger('change.select2');
                        
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
        <?php }  else {?>
            $('select[name="vcategorycode"]').select2();
        <?php } ?>
        
        
    });
</script>


<!-- Delete items -->

<script type="text/javascript">
  $(document).on('click', '#delete_btn', function(event) {
    event.preventDefault();
    
    $('#deleteItemModal').modal('show');

  });
</script>

<div id="deleteItemModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete Item</h4>
      </div>
      <div class="modal-body">
        <p>Are you Sure?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <input type="submit" class="btn btn-danger" name="deleteItems" value="Delete">
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
  $(document).on('click', 'input[name="deleteItems"]', function(event) {
    event.preventDefault();
    
    var data = {};
    var url = '<?php echo $data['delete']; ?>';
    url = url.replace(/&amp;/g, '&');

    <?php if(isset($data['iitemid']) && $data['iitemid'] != ''){?>
      var item_id = '<?php echo $data['iitemid']; ?>';
      data[0] = parseInt(item_id);
    <?php } else { ?>
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: 'Something Went Wrong!', 
        callback: function(){}
      });
      return false;
    <?php } ?>
    
    $('#deleteItemModal').modal('hide');
    $("div#divLoading").addClass('show');
    
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
        
        if(data.error){
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: data.error, 
            callback: function(){}
          });
            
          $("div#divLoading").removeClass('show');
          return false;
            
        }else{
          $("div#divLoading").removeClass('show');
          $('#deleteItemSuccessModal').modal('show');
            
          setTimeout(function(){
            var url_redirect = '<?php echo $data['current_url']; ?>';
            url_redirect = url_redirect.replace(/&amp;/g, '&');
            window.location.href = url_redirect;
           
          }, 3000);
        }
        
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';
        
        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }
            
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: error_show, 
          callback: function(){}
        });

        $("div#divLoading").removeClass('show');

        return false;
      }
    });


  });
</script>

<div class="modal fade" id="deleteItemSuccessModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success text-center">
            <p><b>Item Deleted Successfully</b></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>

<!-- Delete items -->

<script src=" {{ asset('javascript/bootbox.min.js') }}" defer></script>

<script type="text/javascript">
    $(document).on('click', '#form-item-vendor-submit-btn', function(event) {
    event.preventDefault();
    <?php if(session()->get('hq_sid') == 1){  ?>
        var item_id = $('form#form-item-vendor').find('input[name="iitemid"]').val();
        var vvendoritemcode = $('form#form-item-vendor').find('input[name="vvendoritemcode"]').val();
        var ivendorid = $('form#form-item-vendor').find('select[name="ivendorid"]').val();
        var ivendorid_name = $('form#form-item-vendor').find('select[name="ivendorid"] option:selected').text();
        
        dataItemOrders  = [];
        dataItemOrders.push(ivendorid, ivendorid_name, item_id, vvendoritemcode);
                    
        $.ajax({
            url: "<?php echo url('/item/duplicatehqitemvendorassign'); ?>",
            method: 'post',
            headers: {
                  'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data: {ivendorid: ivendorid, ivendorid_name:ivendorid_name, item_id: item_id, vvendoritemcode: vvendoritemcode},
            success: function(result){
                    var popup = '';
                    @foreach (session()->get('stores_hq') as $stores)
                        if(result.includes({{ $stores->id }})){
                            var data = '<tr>'+
                                            '<td>'+
                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                    '<input type="checkbox" class="checks check custom-control-input vendorstoreAssign" disabled id="hq_sid_{{ $stores->id }}" name="vendorstoreAssign" value="{{ $stores->id }}">'+
                                                '</div>'+
                                            '</td>'+
                                            '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
                                        '</tr>';
                                    $('#selectAllCheckbox').attr('disabled', true);
                                          
                        } else {
                            var data = '<tr>'+
                                            '<td>'+
                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                    '<input type="checkbox" class="checks check custom-control-input vendorstoreAssign"  id="else_hq_sid_{{ $stores->id }}" name="vendorstoreAssign" value="{{ $stores->id }}">'+
                                                '</div>'+
                                            '</td>'+
                                            '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                        '</tr>';
                        }
                        popup = popup + data;
                    @endforeach
                    $('#data_vendor_stores').html(popup);
                }
            });
            
        $('#vendorAssignModal').modal('show');
    <?php  } else { ?>
            var item_id = $('form#form-item-vendor').find('input[name="iitemid"]').val();
            var vvendoritemcode = $('form#form-item-vendor').find('input[name="vvendoritemcode"]').val();
            var ivendorid = $('form#form-item-vendor').find('select[name="ivendorid"]').val();
            var ivendorid_name = $('form#form-item-vendor').find('select[name="ivendorid"] option:selected').text();
        
            // if(vvendoritemcode == ''){
            //   bootbox.alert({ 
            //     size: 'small',
            //     title: "Attention", 
            //     message: 'Please Enter Vendor Item Code', 
            //     callback: function(){}
            //   });
            //   return false;
            // }
        
            if(ivendorid == ''){
              bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: 'Please Select Vendor', 
                callback: function(){}
              });
              return false;
            }
        
            var post_data = {iitemid:item_id,vvendoritemcode:vvendoritemcode,ivendorid:ivendorid};
        
            var check_vendor_item_code_url = '<?php echo $data['check_vendor_item_code']; ?>';
            check_vendor_item_code_url = check_vendor_item_code_url.replace(/&amp;/g, '&');
        
            $.ajax({
              url : check_vendor_item_code_url,
              headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
              },
              data : JSON.stringify(post_data),
              type : 'POST',
              contentType: "application/json",
              dataType: 'json',
              success: function(data) {
                
                if(data.error && vvendoritemcode != ""){
                  bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: 'Vendor Item Code "'+vvendoritemcode+'" Already Exist For '+ivendorid_name+' Vendor', 
                    callback: function(){}
                  });
                  return false;
        
                }else{
                 $('form#form-item-vendor').submit();
                }
                
              },
              error: function(xhr) { // if error occured
                var  response_error = $.parseJSON(xhr.responseText); //decode the response array
                
                var error_show = '';
        
                if(response_error.error){
                  error_show = response_error.error;
                }else if(response_error.validation_error){
                  error_show = response_error.validation_error[0];
                }
        
                bootbox.alert({ 
                  size: 'small',
                  title: "Attention", 
                  message: error_show, 
                  callback: function(){}
                });
        
                $("div#divLoading").removeClass('show');
        
                return false;
              }
            });
            return false;
    <?php } ?>
  });
  
    var vendorstoreAssign = []
    vendorstoreAssign.push("{{ session()->get('sid') }}");
    $('#selectAllCheckboxVendorAssign').click(function(){
        if($('#selectAllCheckboxVendorAssign').is(":checked")){
            $(".vendorstoreAssign").prop( "checked", true );
        }else{
            $( ".vendorstoreAssign" ).prop("checked", false );
        }
    });
  
  
  $("#vendor_assign_btn").click(function(){
      
        $.each($("input[name='vendorstoreAssign']:checked"), function(){            
            vendorstoreAssign.push($(this).val());
        });
        
        $("#hiddenvendorAssignsave").val(vendorstoreAssign);
        
        var item_id = $('form#form-item-vendor').find('input[name="iitemid"]').val();
        var vvendoritemcode = $('form#form-item-vendor').find('input[name="vvendoritemcode"]').val();
        var ivendorid = $('form#form-item-vendor').find('select[name="ivendorid"]').val();
        var ivendorid_name = $('form#form-item-vendor').find('select[name="ivendorid"] option:selected').text();
        if(ivendorid == ''){
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: 'Please Select Vendor', 
            callback: function(){}
          });
          return false;
        }
        var post_data = JSON.stringify({iitemid:item_id,vvendoritemcode:vvendoritemcode,ivendorid:ivendorid, stores_hq:vendorstoreAssign })
        vendorstoreAssign = [];
        vendorstoreAssign.push("{{ session()->get('sid') }}");
        var check_vendor_item_code_url = '<?php echo $data['check_vendor_item_code']; ?>';
            check_vendor_item_code_url = check_vendor_item_code_url.replace(/&amp;/g, '&');
            
        $.ajax({
              url : check_vendor_item_code_url,
              headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
              },
              data : post_data,
              type : 'POST',
              contentType: "application/json",
              dataType: 'json',
              success: function(data) {
                if(data.error && vvendoritemcode != ""){
                  bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: 'Vendor Item Code "'+vvendoritemcode+'" Already Exist For '+ivendorid_name+' Vendor', 
                    callback: function(){}
                  });
                  return false;
                }else{
                 $('form#form-item-vendor').submit();
                }
              },
              error: function(xhr) { // if error occured
                var  response_error = $.parseJSON(xhr.responseText); //decode the response array
                var error_show = '';
                if(response_error.error){
                  error_show = response_error.error;
                }else if(response_error.validation_error){
                  error_show = response_error.validation_error[0];
                }
                bootbox.alert({ 
                  size: 'small',
                  title: "Attention", 
                  message: error_show, 
                  callback: function(){}
                });
                $("div#divLoading").removeClass('show');
                return false;
              }
            });
        
            return false;
  });
  
  $("#updateAllvendorsAssigned").click(function(){
    // e.preventDefault();
    <?php if(session()->get('hq_sid') == 1) { ?>
        selected_vendor_code = [];
        $.each($("input[name='selected_vendor_code[]']:checked"), function(){            
            selected_vendor_code.push($(this).val());
        });
        $.ajax({
            url : "<?php echo url('/item/checkVendorExistsFromlist'); ?>",
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : JSON.stringify({vendor_ids: selected_vendor_code}),
            type : 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(result) {
                var popup = '';
                @foreach (session()->get('stores_hq') as $stores)
                    if(result.includes({{ $stores->id }})){
                        var data = '<tr>'+
                                        '<td>'+
                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                '<input type="checkbox" class="checks check custom-control-input storesUpdateitemcode" disabled id="hq_sid_{{ $stores->id }}" name="storesUpdateitemcode" value="{{ $stores->id }}">'+
                                            '</div>'+
                                        '</td>'+
                                        '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
                                    '</tr>';
                                $('#selectAllCheckbox').attr('disabled', true);
                          
                    } else {
                        var data = '<tr>'+
                                        '<td>'+
                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                '<input type="checkbox" class="checks check custom-control-input storesUpdateitemcode"  id="else_hq_sid_{{ $stores->id }}" name="storesUpdateitemcode" value="{{ $stores->id }}">'+
                                            '</div>'+
                                        '</td>'+
                                        '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                    '</tr>';
                    }
                    popup = popup + data;
                @endforeach
                $('#update_vendor_itemcode').html(popup);    
            }
        });
        $("#vendorAssignUpdateModal").modal('show');
    <?php } else { ?>
        $('#form-item-vendor-list').submit();
    <?php }?>
    
});

var updateStores = [];
updateStores.push("{{ session()->get('sid') }}");
$('#selectAllCheckboxUpdateItemCode').click(function(){
    if($('#selectAllCheckboxUpdateItemCode').is(":checked")){
        $(".storesUpdateitemcode").prop( "checked", true );
    }else{
        $( ".storesUpdateitemcode" ).prop("checked", false );
    }
});

$("#vendor_itemcode_update").click(function(){
    $.each($("input[name='storesUpdateitemcode']:checked"), function(){            
        updateStores.push($(this).val());
    });
    
    $("#vendor_update_stores").val(updateStores.join(","));
    // console.log(updateStores.join(","));
    $('form#form-item-vendor-list').submit();
});
</script>


<script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>

<script type="text/javascript">
  $(document).on('click', '#delete_item_vendor_btn', function(event) {
    event.preventDefault();
    var delete_vendor_code_url = '<?php echo $data['delete_vendor_code']; ?>';
    delete_vendor_code_url = delete_vendor_code_url.replace(/&amp;/g, '&');
    var data = {};

    if($("input[name='selected_vendor_code[]']:checked").length == 0){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: 'Please Select Vendor Code to Delete!', 
        callback: function(){}
      });
      return false;
    }

    $("input[name='selected_vendor_code[]']:checked").each(function (i)
    {
      data[i] = parseInt($(this).val());
    });
    
    $("div#divLoading").addClass('show');

    $.ajax({
        url : delete_vendor_code_url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        data : JSON.stringify(data),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
      success: function(data) {
        
        $('#success_alias').html('<strong>'+ data.success +'</strong>');
        $("div#divLoading").removeClass('show');
        $('#successAliasModal').modal('show');

        setTimeout(function(){
         $('#successAliasModal').modal('hide');
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
        setTimeout(function(){
         $('#errorAliasModal').modal('hide');
         window.location.reload();
        }, 3000);
       // return false;
      }
    });
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
        title: "Attention", 
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
        title: "Attention", 
        message: 'Profit Margin Should not be Zero!', 
        callback: function(){}
      });
      return false;
    }

//=========commented on 18-02-20=======
    //  per = 100 - per;
    //  if (per == 0){
    
    
    var new_costprice = $('#input-new_cost').val();
    var selling_price = ((new_costprice*100)/(100-prof_mar));
    // console.log(selling_price);
    selling_price = selling_price.toFixed(2);
    
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
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Calculate Selling Price</h4>
      </div>
      <div class="modal-body">
        <p class="text-center"><strong>Enter Your Profit Margin and it will Calculate Your Selling Price.</strong></p>
        <p class="text-center"><span style="display: inline-block;"><input type="text" name="percent_selling_price" class="form-control"></span>&nbsp;<span><b>%</b></span></p>
        <p class="text-center">
          <button type="button" class="btn btn-info" id="selling_percent_calculate_btn">Calculate</button>
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
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Item Description</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <label>Description</label>
            </div>
            <div class="col-md-8">
                <input type="text" id="input-description" name="description" maxlength="100" value="<?php echo isset($data['vdescription']) ? $data['vdescription'] : ''; ?>" placeholder="Description" class="form-control" />
            </div>
        </div>
        <br>
        <?php if (isset($data['error_vdescription'])) { ?>
          <div class="text-danger"><?php echo $data['error_vdescription']; ?></div>
        <?php } ?>
        <p class="text-center">
          <button type="button" class="btn btn-info" id="save_description_btn">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </p>
        
      </div>
    </div>

  </div>
</div>

<div id="levelPricingModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Level Pricing</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6 padding">
                <div class="form-group">
                    <div class="col-md-5">
                        <label>Level 2 Price</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="nlevel2" value="<?php echo isset($data['nlevel2']) ? $data['nlevel2'] : ''; ?>" placeholder="Level 2 Price" id="input-level2price" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 padding">
                <div class="form-group">
                    <div class="col-md-5">
                        <label>Gross Profit(%)</label>
                    </div>
                    <div class="col-md-7">
                        <span style="display: inline-block;width: 85%;"><input type="text" name="gross_profit2" value="" placeholder="" id="input-gross-profit2" class="form-control" readonly /></span>
                        <span style="display: inline-block;width: 10%" id="selling_price_calculation_btn_l2"><button class="btn btn-sm btn-info" title="">..</button></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 padding">
                <div class="form-group">
                    <div class="col-md-5">
                        <label>Level 3 Price</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="nlevel3" value="<?php echo isset($data['nlevel3']) ? $data['nlevel3'] : ''; ?>" placeholder="Level 3 Price" id="input-level3price" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 padding">
                <div class="form-group">
                    <div class="col-md-5">
                        <label>Gross Profit(%)</label>
                    </div>
                    <div class="col-md-7">
                        <span style="display: inline-block;width: 85%;"><input type="text" name="gross_profit3" value="" placeholder="" id="input-gross-profit3" class="form-control" readonly /></span>
                        <span style="display: inline-block;width: 10%" id="selling_price_calculation_btn_l3"><button class="btn btn-sm btn-info" title="">..</button></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 padding">
                <div class="form-group">
                    <div class="col-md-5">
                        <label>Level 4 Price</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="nlevel4" value="<?php echo isset($data['nlevel4']) ? $data['nlevel4'] : ''; ?>" placeholder="Level 4 Price" id="input-level4price" class="form-control"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6 padding">
                <div class="form-group">
                    <div class="col-md-5">
                        <label>Gross Profit(%)</label>
                    </div>
                    <div class="col-md-7">
                        <span style="display: inline-block;width: 85%;"><input type="text" name="gross_profit4" value="" placeholder="" id="input-gross-profit4" class="form-control" readonly /></span>
                        <span style="display: inline-block;width: 10%" id="selling_price_calculation_btn_l4"><button class="btn btn-sm btn-info" title="">..</button></span>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <p class="text-center">
          <button type="button" class="btn btn-info" id="save_price_btn">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cost</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-4 form-group">
                <label>Last Cost</label>
            </div>
            <div class="col-md-8">
                <input type="text" name="lastcost" value="<?php echo isset($data['last_costprice']) ? $data['last_costprice'] : ''; ?>" placeholder="Last Cost" id="input-lastcost" class="form-control" readonly/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label>Case Cost</label>
            </div>
            <div class="col-md-8">
                <input type="text" name="avgcost" name="dcostprice" value="<?php echo isset($data['dcostprice']) ? $data['dcostprice'] : ''; ?>" placeholder="Avg. Case Cost" id="input-avg_case_cost" class="form-control" autocomplete="off" <?php if(isset($data['isparentchild']) && $data['isparentchild'] == 1){?> readonly <?php } ?>  />
            </div>
        </div>
        
        <p class="text-center">
          <button type="button" class="btn btn-info" id="save_cost_btn">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </p>
        
      </div>
    </div>

  </div>
</div>

<style>
    .padding div div{
        padding:0;
        margin-bottom:4px;
    }
</style>
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
      
    if ($('input[name="advance_options_checkbox"]').prop('checked')==true){ 
        $('#advance_options_checkbox_div').show();
    }else{
      $('#advance_options_checkbox_div').hide();
    }
    
    if ($('input[name="plcb_options_checkbox"]').prop('checked')==true){ 
        $('#plcb_options_checkbox_div').show();
    }else{
      $('#plcb_options_checkbox_div').hide();
    }
  });
</script>

<script type="text/javascript">
  /*$('input[name="vitemname"]').keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    var t = isValid(character); 
      if(!t){
        event.preventDefault();
      }    
  });*/
  
  
    $("#dept_code").change(function(){
      
        // console.log($(this).text);
        var deptCode = $(this).val();
    
        
        var input = {};
        input['dept_code'] = deptCode;
        
        if(deptCode != ""){
         console.log(deptCode);
    
            var sid = "<?php echo $data['sid'];?>";
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
    
            var sid = "<?php echo $data['sid'];?>";
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
                    if(response.length > 0){
                        // $("#span_dept_name").html($("#dept_code option:selected").text());
                        // $("#category_dept_name").val($("#dept_code option:selected").text());
                         $("#category_code").val(cat_id);
                        $('#subcat_id').select2('destroy').empty().select2({data: response});
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

    $("#button_addLotItemModal").click(function(){
        $('#addLotItemModal').modal('show');
    });

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
        
        @if(!isset($data['itemPromotion']) || count($data['itemPromotion']) == 1 ||count($data['itemPromotion']) == 0)
            $("#promotionid").prop("disabled",true);
        @endif
        $(".disPro").prop("disabled",true);
        
        $(document).on('click', '#selling_percent_calculate_btn_l2', function(){
            
            if($("input[name='percent_selling_price_l2']").val() == ''){
              bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: 'Please Enter Profit Margin!', 
                callback: function(){}
              });
              return false;
            }
        
            var prof_mar = parseFloat($("input[name='percent_selling_price_l2']").val());
        
            if(prof_mar == '0' || prof_mar == 0){
              bootbox.alert({ 
                size: 'small',
                title: "Attention", 
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
                title: "Attention", 
                message: 'Please Enter Profit Margin!', 
                callback: function(){}
              });
              return false;
            }
        
            var prof_mar = parseFloat($("input[name='percent_selling_price_l3']").val());
        
            if(prof_mar == '0' || prof_mar == 0){
              bootbox.alert({ 
                size: 'small',
                title: "Attention", 
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
                title: "Attention", 
                message: 'Please Enter Profit Margin!', 
                callback: function(){}
              });
              return false;
            }
        
            var prof_mar = parseFloat($("input[name='percent_selling_price_l4']").val());
        
            if(prof_mar == '0' || prof_mar == 0){
              bootbox.alert({ 
                size: 'small',
                title: "Attention", 
                message: 'Profit Margin Should not be Zero!', 
                callback: function(){}
              });
              return false;
            } 
            
            var new_costprice = $('#input-new_cost').val();
            var selling_price = ((new_costprice*100)/(100-prof_mar));
            // console.log(selling_price);
            selling_price = selling_price.toFixed(2);
            
             $('input[name="nlevel4"]').val(selling_price);
             $('input[name="gross_profit4"]').val(prof_mar.toFixed(2));
        
             $('#sellingPercentageModal4').modal('hide');
        });
        
        var itemtype = '<?php echo $data['vitemtype'] ?>';
        
        if(itemtype == 'Instant'){
         
            $('.notLottery').hide();
            $('.Lottery').show();   
        }
        
        
        // ========onchange cost =========
        
        $(document).on('keyup', '#input-new_cost, #input-sellingunit', function(event){
            
            // var character = String.fromCharCode(event.keyCode)
            // var newValue = this.value + character; 
            // if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
            //     event.preventDefault();
            //     return false;
            // }
            this.value = this.value.match(/^\d+\.?\d{0,2}/);
            
            var buyDown = $('input[name="ndiscountper"]').val();
            var new_costprice = $('#input-new_cost').val();
            var selling_price = $('#input-Selling_Price').val();
            
            var level2_selling_price = $('#input-level2price').val();
            var level3_selling_price = $('#input-level3price').val();
            var level4_selling_price = $('#input-level4price').val();
            
            var sellingunit = $('#input-sellingunit').val();
            
            if(sellingunit === '0'){
                $('#input-sellingunit').val(1);
            }
            
            var unitcost = parseFloat(new_costprice/sellingunit).toFixed(2);
            
            var gross_profit;
            var level2_gross_profit;
            var level3_gross_profit;
            var level4_gross_profit;
            
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
            // console.log(gross_profit);
            // gross_profit = gross_profit.toFixed(2);
            
            if(sellingunit != ''){
                if(selling_price != '' & !isNaN(prof_mar) & isFinite(prof_mar)){
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
            }
            
        });
        
        $(document).on('focusout', '#input-new_cost', function () {
            
            var new_costprice = $('#input-new_cost').val();
            if(new_costprice == 0){
                $('#input-new_cost').val('0.00');
            }else{
             $('#input-new_cost').val(parseFloat(new_costprice).toFixed(2)); 
            }
        });
        
        var check = $('#lot_matrix_tab_li').hasClass('active');
        if(check){
            $('.formsubmit').attr('disabled', true);
        }
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
    var iqtyonhand = $('input[name="iqtyonhand"]').val();
    var itemtype = $('select[name="vitemtype"]').val();
    if((typeof(iqtyonhand) == "undefined" || iqtyonhand == null || iqtyonhand == '') && itemtype != 'Instant'){
        var result = confirm('Qty On Hand is empty. Do you really want to submit ?');
        if(result == true){
            <?php if(session()->get('hq_sid') == 1) { ?>
                $("div#divLoading").removeClass('show');
                $('#myModal').modal('show');
                <?php if(isset($data['iitemid']) && isset($data['edit_page'])){?>
                    dataItemOrders  = [];
                    dataItemOrders.push($("#hidden_iitemid").val());
                    $.ajax({
                        url: "<?php echo url('/item/duplicatehqitems'); ?>",
                        method: 'post',
                        headers: {
                              'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                        },
                        data: {dataItemOrders},
                        success: function(result){
                                    var popup = '';
                                    @foreach (session()->get('stores_hq') as $stores)
                                        if(result.includes({{ $stores->id }})){
                                            var data = '<tr>'+
                                                            '<td>'+
                                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                    '<input type="checkbox" class="checks check custom-control-input editstores" disabled id="hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                                '</div>'+
                                                            '</td>'+
                                                            '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
                                                        '</tr>';
                                                    $('#editSelectAllCheckbox').attr('disabled', true);
                                        } else {
                                            var data = '<tr>'+
                                                            '<td>'+
                                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                    '<input type="checkbox" class="checks check custom-control-input editstores"  id="else_hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                                '</div>'+
                                                            '</td>'+
                                                            '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                        '</tr>';
                                        }
                                        popup = popup + data;
                                    @endforeach
                            $('#data_stores').html(popup);
                        }
                    });
                    $('#editModal').modal('show');
                <?php } ?>
            <?php } else{ ?>
                $('form#form-item1').submit();
            <?php } ?>
        }else{
            $(".show").hide();
            $("div#divLoading").removeClass('show');
            // return false;
            e.stopPropagation();
        }
    }else{
        <?php if(session()->get('hq_sid') == 1) { ?>
            $("div#divLoading").removeClass('show');
            $('#myModal').modal('show');
            <?php if(isset($data['iitemid']) && isset($data['edit_page'])){?>
                dataItemOrders  = [];
                dataItemOrders.push($("#hidden_iitemid").val());
                $.ajax({
                    url: "<?php echo url('/item/duplicatehqitems'); ?>",
                    method: 'post',
                    headers: {
                          'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    data: {dataItemOrders},
                    success: function(result){
                                var popup = '';
                                @foreach (session()->get('stores_hq') as $stores)
                                    if(result.includes({{ $stores->id }})){
                                        var data = '<tr>'+
                                                        '<td>'+
                                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                '<input type="checkbox" class="checks check custom-control-input editstores" disabled id="hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                            '</div>'+
                                                        '</td>'+
                                                        '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
                                                    '</tr>';
                                                $('#editSelectAllCheckbox').attr('disabled', true);
                                          
                                    } else {
                                        var data = '<tr>'+
                                                        '<td>'+
                                                            '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                                '<input type="checkbox" class="checks check custom-control-input editstores"  id="else_hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                            '</div>'+
                                                        '</td>'+
                                                        '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                    '</tr>';
                                    }
                                    popup = popup + data;
                                @endforeach
                        $('#data_stores').html(popup);
                    }
                });
                $('#editModal').modal('show');
            <?php } ?>
        <?php } else{ ?>
            $('form#form-item1').submit();
        <?php } ?>
    }
});

// function formsubmit(){
// $(document).on('click', '.formsubmit', function(e){
    
//     var iqtyonhand = $('input[name="iqtyonhand"]').val();
//     var itemtype = $('select[name="vitemtype"]').val();
//     if((typeof(iqtyonhand) == "undefined" || iqtyonhand == null || iqtyonhand == '') && itemtype != 'Instant'){
        
//         var result = confirm('Qty On Hand is empty. Do you really want to submit ?');
        
//         if(result == true){
//             $('form#form-item').submit();
//         }else{
//             $(".show").hide();
//             $("div#divLoading").removeClass('show');
//             // return false;
//             e.stopPropagation();
//         }
//     }else{
//         $('form#form-item').submit();
//     }

// });

$(window).on('beforeunload', function(){

    var url = '<?php echo $data['unset_visited_below_zero']; ?>';
    url = url.replace(/&amp;/g, '&');
    
    $.ajax({
        url : url,
        headers: {
                  'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
        },
        type : 'POST',
        
        success: function(data) {
            console.log(data);
            console.log("done");
        },
        error : function(request,error){
            console.log(error);
            console.log("Not done");
        }
    });
    
    
});

$(document).on('click', '#lot_matrix_tab_li, #vendor_tab_li, #alias_code_tab_li, #item_tab_li', function(){
    
    setTimeout(function(){
        var check = $('#lot_matrix_tab_li').hasClass('active'); console.log(check);
        if(check){
            $('.formsubmit').attr('disabled', true);
        }else{
            $('.formsubmit').attr('disabled', false);
        }
    }, 1000);
    
    
});

$(document).on('input', '#input-level2price, #input-level3price, #input-level4price', function(){
    
    var buyDown = $('input[name="ndiscountper"]').val();
    var new_costprice = $('#input-new_cost').val();
    var level2_selling_price = $('#input-level2price').val();
    var level3_selling_price = $('#input-level3price').val();
    var level4_selling_price = $('#input-level4price').val();
    
    var sellingunit = $('#input-sellingunit').val();
    
    var unitcost = parseFloat(new_costprice/sellingunit).toFixed(2);

    var gross_profit;
    var level2_gross_profit;
    var level3_gross_profit;
    var level4_gross_profit;
    
    if(buyDown != ''){
        
        level2_gross_profit = level2_selling_price - (unitcost - buyDown);
        level3_gross_profit = level3_selling_price - (unitcost - buyDown);
        level4_gross_profit = level4_selling_price - (unitcost - buyDown);
    }else{
        
        level2_gross_profit = level2_selling_price - unitcost;
        level3_gross_profit = level3_selling_price - unitcost;
        level4_gross_profit = level4_selling_price - unitcost;
    }
    
    var prof_mar2 = ((level2_gross_profit/level2_selling_price)*100);
    var prof_mar3 = ((level3_gross_profit/level3_selling_price)*100);
    var prof_mar4 = ((level4_gross_profit/level4_selling_price)*100);
    
    if(level2_selling_price != '' && level2_selling_price != 0){
        $('#input-gross-profit2').val(prof_mar2.toFixed(2));
    }
    if(level3_selling_price != '' && level3_selling_price != 0){
        $('#input-gross-profit3').val(prof_mar3.toFixed(2));
    }
    if(level4_selling_price != '' && level4_selling_price != 0){
        $('#input-gross-profit4').val(prof_mar4.toFixed(2));  
    }
            
});

$(document).on('click', '.item_movement_btn', function(event) {
    event.preventDefault();

    var vbarcode = $('#input-sku').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var data_by = $('select[name="search_by_item"]').val();

    if($('#start_date').val() == ''){
      // alert('Please Select Start Date');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Start Date", 
        callback: function(){}
      });
      return false;
    }

    if($('#end_date').val() == ''){
      // alert('Please Select End Date');
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select End Date", 
        callback: function(){}
      });
      return false;
    }

    if($('input[name="start_date"]').val() != '' && $('input[name="end_date"]').val() != ''){

        var d1 = Date.parse($('input[name="start_date"]').val());
        var d2 = Date.parse($('input[name="end_date"]').val()); 
        
        if(d1 > d2){
            bootbox.alert({ 
              size: 'small',
              title: "Attention", 
              message: "Start date must be less then end date!", 
              callback: function(){}
            });
        return false;
        }
    }
    
    var item_movement_data_url = '<?php echo $data['item_movement_data']; ?>';
  
    item_movement_data_url = item_movement_data_url.replace(/&amp;/g, '&');

    item_movement_data_url = item_movement_data_url + '?vbarcode='+ vbarcode +'&start_date='+start_date+'&end_date='+end_date+'&data_by='+data_by;
    
    if(data_by == 'receive')
        {
            $('#item_movement_by_date_selection_table > thead > tr').html('<th>Action</th><th>Date</th> <th class="text-right">Qty</th><th class="text-right">Pack Qty</th><th class="text-right">Size</th><th class="text-right">Price</th><th class="text-right">PO Number</th><th>Before QOH</th><th>After QOH</th>');
        }
    else if(data_by == 'sold')
        {
            $('#item_movement_by_date_selection_table > thead > tr').html('<th id="first_th">Print Receipt</th><th>Action</th><th>Date</th><th class="text-right">Qty</th><th class="text-right">Pack Qty</th><th class="text-right">Size</th><th class="text-right">Price</th><th>Before QOH</th><th>After QOH</th>');
        }
    else if(data_by == 'adjustment')
        {
          $('#item_movement_by_date_selection_table > thead > tr').html('<th>Action</th><th>Date</th><th>Before QOH</th><th>Qty</th> <th>After QOH</th> <th class="text-right">Ref Number</th>');
        }
    else if(data_by == 'adjustment')
        {
          $('#item_movement_by_date_selection_table > thead > tr').html('<th>Action</th><th>Date</th><th>Before QOH</th><th>Qty</th> <th>After QOH</th> <th class="text-right">Ref Number</th>');
        }    
    else if(data_by == 'openingpos')
        {
          $('#item_movement_by_date_selection_table > thead > tr').html('<th>Action</th><th>Date</th><th>Before QOH</th><th>Qty</th> <th>After QOH</th> <th class="text-right">Ref Number</th>');
        }
    else if(data_by == 'phoneadjustment')
        {
          $('#item_movement_by_date_selection_table > thead > tr').html('<th>Action</th><th>Date</th><th>Before QOH</th><th>Qty</th> <th>After QOH</th><th class="text-right">Ref Number</th>');
        }
        
    else if(data_by == 'openingqohphone')
        {
          $('#item_movement_by_date_selection_table > thead > tr').html('<th>Action</th><th>Date</th><th>Qty</th><th class="text-right">Ref Number</th>');
        }
        

    $.getJSON(item_movement_data_url, function(result){
      
      $("div#divLoading").addClass('show');
        
      var html = '';
      $('#item_movement_by_date_selection_table > tbody').empty();
        
      if(result.length){
        var total_qty = total_amount = 0;
        $.each(result,function(i, v) {
          html += '<tr>';
          if(data_by == 'sold'){
            html += '<td><button data-idettrnid="'+ v.idettrnid +'" data-isalesid="'+ v.isalesid +'" class="btn btn-info print-sales"><i class="fa fa-print"></i> Print</button></td>';
          }
          html += '<td>';
          if(data_by == 'sold'){
            html += 'Sales';
          }
          else if(data_by == 'receive'){
            html += 'Receive';
          }
          else if(data_by == 'openingqoh'){
            html += 'Opening Qoh';
          }
          else if(data_by == 'adjustment'){
              if(v.vtype=='Conv Case')
              {
                html +='Conv. Case to Unit';  
              }
              else{
               html +=v.vtype;
              }
           
          }
          else if(data_by == 'phoneadjustment'){
            html +='Phone Adjustment';
           
          }
           else if(data_by == 'openingqohphone'){
            html +='Opening QoH Phone';
           
          }
          else if(data_by == 'openingpos'){
            html +='Opening Qoh WEB';
           
          }
          
          
          html += '</td>';
          html += '<td>';
          html += v.ddate;
          html += '</td>';
          if(data_by == "adjustment" || data_by=="phoneadjustment" || data_by=="openingpos"){
              html += '<td>';
              html += parseInt(v.beforeQOH);
              html += '</td>';
         }
          
          html += '<td class="text-right">';
          html += parseInt(v.items_count);
          total_qty += parseInt(v.items_count);
          html += '</td>';
          if(data_by == "adjustment" || data_by=="phoneadjustment"  || data_by=="openingpos"){
              html += '<td>';
              html += parseInt(v.afterQOH);
              html += '</td>';
          }
          if(data_by != "adjustment" && data_by != "qohupdate" && data_by!="invreset" && data_by!="openingqoh" && data_by!="perentchild" && data_by!="childparent" && data_by!="phoneadjustment" && data_by!="openingqohphone" && data_by!="openingpos" && data_by!="receive")
          {
              html += '<td class="text-right">';
              html += parseInt(v.npack);
              html += '</td>';
              html += '<td class="text-right">';
              html += v.size;
              html += '</td>';
              html += '<td class="text-right">';
              html += parseFloat(v.total_price).toFixed(2);
              total_amount += parseFloat(v.total_price).toFixed(2);;
              html += '</td>';
              html += '<td class="text-right">';
              html += v.before_sold_qoh;
              html += '</td>';
              html += '<td class="text-right">';
              html +=v.after_sold_qoh;
              html += '</td>';
          }
          if(data_by == 'receive')
          {   html += '<td class="text-right">';
              html += parseInt(v.npack);
              html += '</td>';
              html += '<td class="text-right">';
              html += v.size;
              html += '</td>';
              html += '<td class="text-right">';
              html += parseFloat(v.total_price).toFixed(2);
              total_amount += parseFloat(v.total_price).toFixed(2);;
              html += '</td>';
              html += '<td class="text-right">';
              html += v.ponumber;
              html += '</td>';
              html += '<td class="text-right">';
              html += v.before_rece_qoh;
              html += '</td>';
              html += '<td class="text-right">';
              html += v.after_rece_qoh;
              html += '</td>';
          }
          if(data_by == 'adjustment')
          {
              html += '<td class="text-right">';
              html += v.vrefnumber;
              html += '</td>';
          }
          
           if(data_by =='openingqoh')
          {
              html += '<td class="text-right">';
              html += v.vrefnumber;
              html += '</td>';
          }
          
          if(data_by =='phoneadjustment')
          {
              html += '<td class="text-right">';
              html += v.vrefnumber;
              html += '</td>';
          }
           if(data_by =='openingqohphone')
          {
              html += '<td class="text-right">';
              html += v.vrefnumber;
              html += '</td>';
          }
         if(data_by == 'openingpos')
          {
              html += '<td class="text-right">';
              html += v.vrefnumber;
             
              html += '</td>';
          }
          
          html += '</tr>';
        });
        if(data_by == 'sold')
        {
            html += '<tr><th colspan="3" class="text-right">Total</th><th class="text-right">'+total_qty+'</th><th></th><th></th><th class="text-right">'+parseFloat(total_amount).toFixed(2)+'</th></tr>';
        }
        else if(data_by == 'receive')
        {
            html += '<tr><th colspan="2" class="text-right">Total</th><th class="text-right">'+total_qty+'</th><th></th><th></th><th class="text-right">'+parseFloat(total_amount).toFixed(2)+'</th><th></th><th></th><th></th></tr>';
        }
        else if(data_by == 'adjustment'){
            html += '<tr><th colspan="3" class="text-right">Total</th><th class="text-right">'+total_qty+'</th><th></th><th></th></tr>';
        }
        console.log(html);
        $('#item_movement_by_date_selection_table > tbody').append(html);
        
      }else{
        
        $('#item_movement_by_date_selection_table > tbody').append('<tr><td class="text-center" colspan="6">Sorry no data found!</td> </tr>');
      }
      $('#item_movement_by_date_selection').show();
      $('#item_movement_by_date_selection_table').show();
        
      $("div#divLoading").removeClass('show');

    });

});

$(function(){
    $("#start_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });

    $("#end_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });
});

</script>

<script>
        var itemstores = [];
        itemstores.push("{{ session()->get('sid') }}");
        $('#selectAllCheckbox').click(function(){
            if($('#selectAllCheckbox').is(":checked")){
                $(".itemstores").prop( "checked", true );
            }else{
                $( ".itemstores" ).prop("checked", false );
            }
        });
        
        $('#save_btn').click(function(){
            $.each($("input[name='itemstores']:checked"), function(){            
                itemstores.push($(this).val());
            });
            console.log(itemstores);
            $("#hidden_store_hq_val").val(itemstores);
            $('form#form-item1').submit();
        })
        
        var edit_stores = []
        edit_stores.push("{{ session()->get('sid') }}");
        $('#editSelectAllCheckbox').click(function(){
            if($('#editSelectAllCheckbox').is(":checked")){
                $(".editstores").prop( "checked", true );
            }else{
                $( ".editstores" ).prop("checked", false );
            }
        });
        
        // edit_stores.push("{{ session()->get('sid') }}");
        // $('#selectAllCheckbox').click(function(){
        //     if($('#selectAllCheckbox').is(":checked")){
        //         $(".stores").prop( "checked", true );
        //     }else{
        //         $( ".stores" ).prop("checked", false );
        //     }
        // });
        
        $('#update_btn').click(function(){
            $.each($("input[name='editstores']:checked"), function(){            
                edit_stores.push($(this).val());
            });
            console.log(edit_stores);
            $("#hidden_store_hq_val").val(edit_stores);
            $('form#form-item1').submit();
        })
        
        $("#closeBtn").click(function(){
            $("div#divLoading").removeClass('show');
        })
        
    </script>

@endsection
