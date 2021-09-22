@extends('layouts.layout')
@section('title', 'Purchase Order')
@section('main-content')

<div id="content">
    <style> 
        
        select{
            color:black;
            border-radius: 4px;
            height:30px;
            font-size: 9px;
            background-color: #fff;
        }
        
        .autocomplete {
          /*the container must be positioned relative:*/
          position: relative;
          display: inline-block;
        }
        
        .autocomplete-items {
          position: absolute;
          border: 1px solid #d4d4d4;
          border-bottom: none;
          border-top: none;
          z-index: 99;
          /*position the autocomplete items to be the same width as the container:*/
          top: 100%;
          left: 0;
          right: 0;
        }
        .autocomplete-items div {
          padding: 10px;
          cursor: pointer;
          background-color: #fff;
          color: #000;
          border-bottom: 1px solid #d4d4d4;
        }
        .autocomplete-items div:hover {
          /*when hovering an item:*/
          background-color: #e9e9e9;
        }
        .autocomplete-active {
          /*when navigating through the items using the arrow keys:*/
          background-color: DodgerBlue !important;
          color: #ffffff;
        }
        
        
        .ui-menu-item-wrapper{
            font-family:Liberation Sans;
            font-size:9px;
            font-weight:normal;
            backgound-colour: #ffff;
        }
        .ui-menu-item-wrapper:hover{
            font-size:10px;
            font-weight:bold;
        }

    </style>    
    
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
      <div class="container">
          <div class="collapse navbar-collapse" id="main_nav">
              <div class="menu">
                  <span class="font-weight-bold text-uppercase">Purchase Order (PAR Level)</span>
              </div>
              <div class="nav-submenu">
                <button type="submit" form="form-purchase-order" id="save_purchase_order" data-toggle="tooltip" title="Save" class="btn btn-gray headerblack  buttons_menu add_new_btn_rotate" <?php if(isset($data['estatus']) && $data['estatus'] == 'Close'){ ?> disabled <?php } ?>><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                <a href="<?php echo $data['cancel']; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-danger buttonred buttons_menu basic-button-small cancel_btn_rotate" id="cancel_button"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
                
              </div>
          </div> <!-- navbar-collapse.// -->
      </div>
    </nav>
    
  <div class="container-fluid section-content">
    <div class="container-fluid">
        @if ($data['error_warning'])
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ $data['error_warning'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif
        @if (isset($data['success']))
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ $data['success'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif
        
        <div class="panel panel-default">
            
            <div class="panel-body">
                
                <div class="row" style="padding-bottom: 15px;">
                    <div class="col-md-12">
                      
                      <?php if(isset($data['estatus'])) { ?>
                        <?php if($data['estatus'] == 'Close') { ?>
                            <span class="col-md-1 badge badge-danger">
                                <h6>Closed</h6>
                            </span>
                        <?php } else { ?>
                            <span class="col-md-1 badge badge-success">
                                <h6>Open</h6>
                            </span>
                        <?php } ?>    
                      <?php } else { ?>
                        <span class="col-md-1 badge badge-success">
                            <h6>Open</h6>
                        </span>
                      <?php } ?>
                        
                        
                        <div class="col-md-3 pull-right">
                          
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                
                <form action="<?php echo $data['action']; ?>" method="post" enctype="multipart/form-data" id="form-purchase-order" class="form-horizontal">
                    <?php if(isset($data['ipoid'])){ ?>
                      <input type="hidden" name="ipoid" value="<?php echo $data['ipoid']; ?>">
                    <?php } ?>
                    <input type="hidden" name="receive_po" id="receive_po" value="">
                    
                    <input type="hidden" name="vponumber" value="{{ $data['vponumber'] }}" >
                    
                    <!-- ITEM TAB -->
                    <div class="">
                      
                      <div class="" id="meta_div" style="display: none;">
                            
                        <div class="mytextdiv">
                          <div class="mytexttitle font-weight-bold text-uppercase">
                              General Info
                          </div>
                          <div class="divider font-weight-bold"></div>
                        </div>

                        <div class="py-3">
                          <div class="row">
                              <div class="col-md-12 mx-auto">
                                  
                                  <div class="form-group row ">
                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                          <div class="col-6 col-md-6 col-sm-6 col-lg-6 required">
                                              <label for="inputFirstname" class="p-2 float-right text-uppercase control-label">Vendor Name</label>
                                          </div>
                                          <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                            <input type="hidden" name="vvendorid" value="<?php echo $data['vvendorid']; ?>"><input type="hidden" name="vvendorname" value="<?php echo $data['vvendorname']; ?>">
                                            <select <?php if(isset($data['ipoid'])){?> disabled <?php } ?> name="" class="form-control adjustment-fields" id="loaded_vendor">
                                                <option value="">-- Select Vendor --</option>
                                                <?php if(isset($data['vendors']) && count($data['vendors']) > 0){?>
                                                    <?php foreach($data['vendors'] as $vendor){?>
                                                        <?php if($vendor['isupplierid'] == $data['vvendorid']){?>
                                                            <option value="<?php echo $vendor['isupplierid']; ?>" selected="selected"><?php echo $vendor['vcompanyname']; ?></option>
                                                        <?php }else{ ?>
                                                            <option value="<?php echo $vendor['isupplierid']; ?>"><?php echo $vendor['vcompanyname']; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                              
                                          </div>
                                      </div>

                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
        
                                          <label for="inputCreated" class="p-2 float-right text-uppercase">Created</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                          <?php 
                                          if(isset($data['dcreatedate'])){
                                            $dcreatedate = DateTime::createFromFormat('Y-m-d H:i:s', $data['dcreatedate']);
                                            $dcreatedate = $dcreatedate->format('m-d-Y');
                                          }
                                        ?>
                                        <input type="text" name="dcreatedate" value="<?php echo isset($dcreatedate) ? $dcreatedate : date('m-d-Y'); ?>" placeholder="Created" id="input-Created" class="form-control adjustment-fields" required/>
                                        </div>
                                      </div>

                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                          
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                          <label for="inputReceived" class="p-2 float-right text-uppercase">Received</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                          
                                          <?php 
                                            if(isset($data['dreceiveddate'])){
                                              $dreceiveddate = DateTime::createFromFormat('Y-m-d H:i:s', $data['dreceiveddate']);
                                              $dreceiveddate = $dreceiveddate->format('m-d-Y');
                                            }
                                          ?>
                                          <input type="text" name="dreceiveddate" value="<?php echo isset($dreceiveddate) ? $dreceiveddate : date('m-d-Y'); ?>" placeholder="Received" id="input-Received" class="form-control adjustment-fields" required/>
                                           
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
      
                                        <label for="inputInvoice" class="p-2 float-right text-uppercase">Invoice#</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <input type="text" name="vinvoiceno" maxlength="50" value="<?php echo isset($data['vinvoiceno']) ? $data['vinvoiceno'] : ''; ?>" placeholder="Invoice" id="input_invoice" class="form-control adjustment-fields" />      
                                       
                                        <input type="hidden" name="estatus" maxlength="10" value="<?php echo isset($data['estatus']) ? $data['estatus'] : 'Open'; ?>" placeholder="Status" id="input-Status" class="form-control" readonly/>

                                      </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                          <label for="inputConfirmBy" class="p-2 float-right text-uppercase">Confirm By</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                        <input type="text" name="vconfirmby" maxlength="30" value="<?php echo isset($data['vconfirmby']) ? $data['vconfirmby'] : ''; ?>" placeholder="Confirm By" id="input_confirm_by" class="form-control adjustment-fields" />
                                      </div>
                                    </div>

                                    
                                </div>
                                
                            </div>
                          </div>
                        </div> 
                        
                        <div class="mytextdiv">
                          <div class="mytexttitle font-weight-bold text-uppercase">
                              Order Info
                          </div>
                          <div class="divider font-weight-bold"></div>
                        </div>

                        <div class="py-3">
                          <div class="row">
                              <div class="col-md-12 mx-auto">
                                  
                                  <div class="form-group row">

                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                          <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                              <label for="inputSubtotal" class="p-2 float-right text-uppercase">Subtotal</label>
                                          </div>
                                          <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                            <input type="text" name="nsubtotal" maxlength="50" value="<?php echo isset($data['nsubtotal']) ? $data['nsubtotal'] : '0.00'; ?>" placeholder="Subtotal" id="input_subtotal" class="form-control  adjustment-fields" readonly/>

                                          </div>
                                      </div>

                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
        
                                          <label for="inputTax" class="p-2 float-right text-uppercase">Tax(+)</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">
                                          <input type="text" name="ntaxtotal" maxlength="50" value="<?php echo isset($data['ntaxtotal']) ? $data['ntaxtotal'] : '0.00'; ?>" placeholder="Tax(+)" id="input_tax" class="form-control adjustment-fields"/>
                                          
                                        </div>
                                      </div>

                                      <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                          
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                          <label for="inputFreight" class="p-2 float-right text-uppercase">Freight(+)</label>
                                        </div>
                                        <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                          
                                          <input type="text" name="nfreightcharge" maxlength="50" value="<?php echo isset($data['nfreightcharge']) ? $data['nfreightcharge'] : '0.00'; ?>" placeholder="Freight(+)" id="input_frieght" class="form-control adjustment-fields" />
                                           
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
      
                                        <label for="inputDeposit" class="p-2 float-right text-uppercase">Deposit(+)</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <input type="text" name="ndeposittotal" maxlength="50" value="<?php echo isset($data['ndeposittotal']) ? $data['ndeposittotal'] : '0.00'; ?>" placeholder="Deposit(+)" id="input_deposite" class="form-control adjustment-fields" />
                                        
                                      </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                          <label for="inputFuel" class="p-2 float-right text-uppercase">Fuel(+)</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <input type="text" name="nfuelcharge" maxlength="50" value="<?php echo isset($data['nfuelcharge']) ? $data['nfuelcharge'] : '0.00'; ?>" placeholder="Fuel(+)" id="input_fuel_charge" class="form-control adjustment-fields" />
                                      </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputDelivery" class="p-2 float-right text-uppercase">Delivery(+)</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        
                                        <input type="text" name="ndeliverycharge" maxlength="50" value="<?php echo isset($data['ndeliverycharge']) ? $data['ndeliverycharge'] : '0.00'; ?>" placeholder="Delivery(+)" id="input_delivery_charge" class="form-control adjustment-fields" />
                                          
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
      
                                        <label for="inputReturn" class="p-2 float-right text-uppercase">Return(-)</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <input type="text" name="nreturntotal" maxlength="50" value="<?php echo isset($data['nreturntotal']) ? $data['nreturntotal'] : '0.00'; ?>" placeholder="Return(-)" id="input_return" class="form-control adjustment-fields" />
                                        
                                      </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                          <label for="inputDiscount" class="p-2 float-right text-uppercase">Discount(-)</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <input type="text" name="ndiscountamt" maxlength="50" value="<?php echo isset($data['ndiscountamt']) ? $data['ndiscountamt'] : '0.00'; ?>" placeholder="Discount(-)" id="input_discount" class="form-control adjustment-fields" />
                                      </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                        
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        <label for="inputRips " class="p-2 float-right text-uppercase">Rips(-)</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                        
                                        <input type="text" name="nripsamt" maxlength="50" value="<?php echo isset($data['nripsamt']) ? $data['nripsamt'] : '0.00'; ?>" placeholder="Rips(-)" id="input_rips" class="form-control adjustment-fields" />
                                          
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
      
                                        <label for="inputNetTotal" class="p-2 float-right text-uppercase">Net Total</label>
                                      </div>
                                      <div class="col-6 col-md-6 col-sm-6 col-lg-6 form-group required">

                                        <input type="text" name="nnettotal" maxlength="50" value="<?php echo isset($data['nnettotal']) ? $data['nnettotal'] : '0.00'; ?>" placeholder="Net Total" id="input_net_total" class="form-control adjustment-fields" readonly/>
                                        
                                      </div>
                                    </div>

                                </div>
                                
                            </div>
                          </div>

                        </div>
                          
                        <div class="mytextdiv">
                          <div class="mytexttitle font-weight-bold text-uppercase">
                              Items
                          </div>
                          <div class="divider font-weight-bold"></div>
                        </div>                
                          
                      </div>
                        
                        <div class="">
                            
                            <div class="row" id="div_item_listing" style="height: 700px;">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                    		            <table id="item_listing" class="table table-striped table-hover promotionview" style="width: 100%; font-size:13px;">
                    		                <thead>
                    		                    <tr class="header-color" style="font-size:12px;">
                    		                        <th style="width: 1px;"><input type='checkbox' onclick="$('input[name*=\'selected_search_history_items\']').prop('checked', this.checked);"></th>
                                                    <th class="text-uppercase" style="width: 15%;position: relative;">Item Name
                                                      <div class="adjustment-has-search">
                                                        <input type="text" autocomplete="off" id="search_item_name" name="item_name" class="form-control table-heading-fields text-center search_text_box search_item_history" placeholder="SEARCH" style="padding-left: 0;">
                                                      </div>
                                                    </th>
                                                    <th class="text-uppercase">SKU
                                                      <div class="adjustment-has-search">
                                                        <input type="text" autocomplete="off" id="search_sku" name="barcode" class="form-control table-heading-fields text-center search_text_box search_item_history" placeholder="SEARCH" style="padding-left: 0;">
                                                      </div>
                                                    </th>
                                                    <th class="text-uppercase">Vendor
                                                      <div class="adjustment-has-search">
                                                        <select class='table-heading-fields' name='supplier_code' id='supplier_code' style='width: 100px; padding-left: 5px;'>
                                                          <option value='all'>All</option>
                                                          <?php
                                                            foreach($data['vendors'] as $supplier){
                                                          ?>
                                                              <option value="<?=$supplier['isupplierid'] ?>"><?=$supplier['vcompanyname'] ?> </option>
                                                          <?php } ?>
                                                        </select>
                                                      </div>
                                                    </th>
                                                    <th class="text-uppercase">Dept.
                                                      <div class="adjustment-has-search">
                                                        <select class='table-heading-fields' name='dept_code' id='dept_code' style='width: 100px;'>
                                                          <option value='all'>All</option>";
                                                              <?php 
                                                                foreach($data['departments'] as $department){
                                                              ?>  
                                                                  <option value='<?=$department['vdepcode']?>'><?=$department['vdepartmentname'] ?></option>;
                                                              <?php } ?>
                                                        </select>
                                                      </div>
                                                    </th>
                                                    <th class="text-uppercase">Category
                                                      <div class="adjustment-has-search">
                                                        <select class='table-heading-fields' name='category_code' id='category_code' style='width: 100px; padding-left: 5px;'>
                                                          <option value='all'>All</option>
                                                          
                                                        </select>
                                                      </div>
                                                    </th>
                                                    <th class="text-uppercase">Sub Cat
                                                      <div class="adjustment-has-search">
                                                        <select class='table-heading-fields' name='sub_category_id' id='sub_category_id' style='width: 100px; padding-left: 5px;'>
                                                          <option value='all'>All</option>
                                                        </select>
                                                      </div>
                                                    </th>
                                                    <th class="text-uppercase no-filter">Price</th>
                                                    <th class="text-uppercase no-filter">Cost</th>
                                                    <th class="text-uppercase no-filter">PAR</th>
                                                    <th class="text-uppercase no-filter">QoH</th>
                                                    <th class="text-uppercase no-filter">Sugg. Qty.</th>
                    		                    </tr>
                    		                </thead>
                    		            </table>
                    		         
                    		            
                    		        </div>
                                </div>
                            </div>
                                                      
                                     
                        </div>            
                        
                        
                        <div class='row'><!-- to leave a gap between the list of items and set of buttons --> &nbsp;</div>
                        
                        <div class="tab-pane" id="item_tab">
                          <div class="row" <?php if(isset($data['estatus']) && $data['estatus'] == 'Close'){ ?> style="pointer-events:none;" <?php } ?>>
                            <div class="" style="display: none;">
                              <input type="text" placeholder="Add New Item" id="automplete-product" class="form-control">
                            </div>
                            
                            <div class="col-md-6">
                              <button class="btn button-blue buttons_menu basic-button-small" id="add_selected_items" >Add Item</button>&nbsp;&nbsp;
                              <button class="btn btn-danger buttonred buttons_menu basic-button-small" style="<?php if(isset($data['estatus']) && $data['estatus'] == 'Close'){ ?> background-color: #ccc;border-color: #ccc; <?php } ?>" id="remove_item_btn">Remove Item</button>&nbsp;&nbsp;
                                <?php if(isset($data['ipoid'])){?>
                                    <button type="button" class="btn btn-info buttons_menu basic-button-small" style="<?php if(isset($data['estatus']) && $data['estatus'] == 'Close'){ ?> background-color: #ccc;border-color: #ccc; <?php } ?>" id="export" value="export">Export</button>&nbsp;&nbsp;
                                    <button type="button" class="btn btn-info buttons_menu basic-button-small" style="<?php if(isset($data['estatus']) && $data['estatus'] == 'Close'){ ?> background-color: #ccc;border-color: #ccc; <?php } ?>" id="save_receive_check" value="transfer_to_ro">Transfer to RO</button>
                                <?php } ?>
                              <span title="You can add more items even after exporting the data by clicking on Add Item" style="font-size:20px; color:red; cursor: pointer;">&#8505;</span>
                            </div>

                            <div class="col-md-4">
                              <div class="col-md-6 float-right">
                                <input type="text" class="form-control adjustment-fields" id="search_item_box" placeholder="Search Item...">
                              </div>
                            </div>

                            <div class="col-md-2" <?php if(isset($data['estatus']) && $data['estatus'] == 'Close'){ ?> style="pointer-events:none;" <?php } ?>>
                              <div class="float-right">
                                <input type="checkbox" name="advance_update" value="Yes" class="" id="advance_update">
                                <span style="font-size:14px;margin-top:12px;">&nbsp; Update pack qty in item</span>
                              </div>
                            </div>

                          </div>
                          
                          <br>
                          
                          <br>
                          <div class="row" <?php if(isset($data['estatus']) && $data['estatus'] == 'Close'){ ?> style="pointer-events:none;" <?php } ?>>
                            <div class="col-md-12" style="overflow-y:auto; font-size: 11px;">
                              <table class="table table-hover promotionview" style="width:100%;" id="po-items">
                                <thead>
            
                                  <tr class="header-color">
                                    <th style="vertical-align: middle;" class="text-cente text-uppercaser"><input type="checkbox" onclick="$('input[name*=\'selected_purchase_item\']').prop('checked', this.checked);" /></th>
                                    <th style="vertical-align: middle;" class="text-uppercase">Vendor</th>
                                    <th style="vertical-align: middle;" class="text-uppercase">SKU#</th>
                                    <th style="vertical-align: middle;" class="text-uppercase">Item Name</th>
                                    <th style="vertical-align: middle;" class="text-uppercase">Size</th>
                                    <th style="vertical-align: middle;" class="text-uppercase">New Cost</th>
                                    <th style="vertical-align: middle;" class="text-right text-uppercase">Unit per Case</th>
                                    <th style="vertical-align: middle;" class="text-right text-uppercase">Order By</th>
                                    <th class="text-center text-uppercase" style="vertical-align: middle;">Order Qty</th>
                                    <th class="text-center text-uppercase" style="vertical-align: middle;">Suggested Cost</th>
                                    <th class="text-center text-uppercase" style="vertical-align: middle;">Total Amount</th>
                                  </tr>
                                </thead>
                                <tbody id="purchase_order_items">
                                  <?php $total_amt = '0.00';?>
                                  <?php if(isset($data['items']) && count($data['items']) > 0){?>
                                    <?php foreach($data['items'] as $k => $item){ 
                                    $total_amt +=$item['nordextprice']; 
                                    ?>
                                      <tr id="tab_tr_<?php echo $item['vitemid']; ?>">
                                        <td class="text-center">
                                          <input type="checkbox" name="selected_purchase_item[]" value="<?php echo $item['ipodetid']; ?>"/>
                                          <input type="hidden" name="selected_added_item[]" value="<?php echo $item['vitemid']; ?>"/>
                                          <input type="hidden" name="items[<?php echo $k; ?>][vitemid]" value="<?php echo $item['vitemid']; ?>">
                                          <input type="hidden" name="items[<?php echo $k; ?>][nordunitprice]" value="<?php echo $item['nordunitprice']; ?>">
                                          <input type="hidden" name="items[<?php echo $k; ?>][vunitcode]" value="<?php echo $item['vunitcode']; ?>">
                                          <input type="hidden" name="items[<?php echo $k; ?>][vunitname]" value="<?php echo $item['vunitname']; ?>">
                                          <input type="hidden" name="items[<?php echo $k; ?>][ipodetid]" value="<?php echo $item['ipodetid']; ?>">
                                        </td>
                                        
                                        
                                        <td style="width:20%;" class="vendor_class">
                                          <?php echo $item['vendor_name']; ?>
                                          <input type="hidden" name="items[<?php echo $k; ?>][vendor_name]" value="<?php echo $item['vendor_name']; ?>">
                                        </td>
                                        
                                        
            
                                        <td style="width:10%;" class="vbarcode_class">
                                          <?php echo $item['vbarcode']; ?>
                                          <input type="hidden" name="items[<?php echo $k; ?>][vbarcode]" value="<?php echo $item['vbarcode']; ?>">
                                        </td>
            
                                        <td style="width:20%;" class="vitemname_class">
                                          <?php echo $item['vitemname']; ?>
                                          <input type="hidden" name="items[<?php echo $k; ?>][vitemname]" value="<?php echo $item['vitemname']; ?>">
                                        </td>
            
                                        <?php if(!empty($item['vsize'])){ ?>
                                          <td style="width:5%;">
                                          <?php echo $item['vsize']; ?>
                                          <input type="hidden" class="vsize_class" name="items[<?php echo $k; ?>][vsize]" value="<?php echo $item['vsize']; ?>" id="" >
                                          </td>
                                        <?php } else { ?>
                                          <td style="width:5%;">
                                            <input type="hidden" class="vsize_class" name="items[<?php echo $k; ?>][vsize]" value="" id="" >
                                          </td>
                                        <?php } ?>
            
                                        <td class="text-right">
                                          <input type="hidden" class="new_costprice_class" name="items[<?php echo $k; ?>][new_costprice]" id="" value="<?php echo $item['new_costprice']; ?>">
                                          <?php echo $item['new_costprice']; ?>
                                        </td>
                                        
                                        <td class="text-right">
                                          <input type="text" class="npackqty_class" name="items[<?php echo $k; ?>][npack]" value="<?php echo $item['npack']; ?>" id="" style="width:60px;text-align: right;">
                                        </td>
                                        
                                        
                                        <td class="text-right">
                                            <select class="po_order_by_class" name="items[<?php echo $k; ?>][po_order_by]">
                                                <option value="case" <?php if($item['po_order_by'] == 'case'){ echo "selected = 'selected'"; } ?> >Case</option>
                                                <option value="unit" <?php if($item['po_order_by'] == 'unit'){ echo "selected = 'selected'"; } ?> >Unit</option>
                                            </select>
                                        </td>
                                        
            
                                        <td class="text-right">
                                          <input type="text" class="nordqty_class" name="items[<?php echo $k; ?>][nordqty]" id="" style="width:60px;text-align: right;" value="<?php echo $item['nordqty']; ?>">
                                            <input type="hidden" class="itotalunit_class" name="items[<?php echo $k; ?>][itotalunit]" id="" style="width:60px;text-align: right;" value="<?php echo $item['itotalunit']; ?>">
                                                            
                                        </td>
            
                                        <td class="text-right">
                                          <span id="sggtdqty_id"><?php echo number_format((float)$item['po_total_suggested_cost'], 2, '.', ''); ?></span>
                                          <input type="hidden" class="sggtdqty_class" name="items[<?php echo $k; ?>][po_total_suggested_cost]" value="<?php echo number_format((float)$item['po_total_suggested_cost'], 2, '.', ''); ?>" id="">
                                        </td>
                                        
            
                                        <td class="text-right">
                                          <input type="text" class="nordextprice_class" name="items[<?php echo $k; ?>][nordextprice]" value="<?php echo number_format((float)$item['nordextprice'], 2, '.', '') ; ?>" id="" style="width:80px;text-align: right;">
                                            <input type="hidden" class="nunitcost_class" name="items[<?php echo $k; ?>][nunitcost]" value="<?php echo $item['nunitcost']; ?>" id="" style="width:80px;text-align: right;">
                                        </td>
            
                                      </tr>
                                    <?php } ?>
                                    
                                  <?php } ?>
                                      
                                </tbody>
                                
                                <tfoot>
                                    <tr>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td class="text-right"><b>Total</b></td>
                                      <td class="text-right"><b><span class="total_suggested_amount"><?php // echo number_format((float)$total_suggted_amt, 2, '.', '') ;?></span></b></td>
                                      <td class="text-right"><b><span class="total_amount"><?php echo number_format((float)$total_amt, 2, '.', '') ;?></span></b></td>
                                    </tr>
                                </tfoot>                    
                                
                              </table>
                            </div>
                          </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>

@endsection


@section('page-script')

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>

<link href = "https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="{{ asset('javascript/jquery.printPage.js') }}"></script>


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>


<style type="text/css">

  .no-filter{
      padding-bottom: 45px !important;
  }

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
  
    div.field-wrapper {
        display: block;
       
    }
    div.field-wrapper label::before { 
        content: "\00a0\00a0 "; /* keeps spacing consistent */
    }
    div.field-wrapper input {
        float: right;
    }
    div.required-field label::before { 
        content: "*";
        color: red;
    }

</style>

<script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>


<script type="text/javascript">
  $(document).on('keyup', '#search_item_box', function(event) {
    event.preventDefault();
    
    $('#purchase_order_items tr').hide();
    var txt = $('#search_item_box').val();
    
    if(txt != ''){
      $('#purchase_order_items tr').each(function(){
        
        // console.log("td vitemname: "+$(this).find('td.vitemname_class').text());
        
        if($(this).find('td.vitemname_class').text().toUpperCase().indexOf(txt.toUpperCase()) != -1 || $(this).find('td.vbarcode_class').text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
          $(this).show();
        }
      });
    }else{
      $('#purchase_order_items tr').show();
    }
  });
</script>


<script>  
    $(document).ready(function() {
        // $("#btnPrint").printPage();

        $('.header').toggleClass('expand').nextUntil('tr.add_space').slideUp(100);
    });

    $('.header').click(function(){
        $(this).toggleClass('expand').nextUntil('tr.add_space').slideToggle(100);
    });
</script>
<style type="text/css">
        
        
        .header .sign:after{
            content:"+";
            display:inline-block;      
        }
        .header.expand .sign:after{
            content:"-";
        }
        
        tr.add_space th {
            border: none !important;
            padding: 2px !important;
        }
</style>

<script type="text/javascript">
    
    var item_name_list = [];
    
    
    $(document).on('click', '#generateSalesHistoryReport', function(){
        
        $('item_history_err_div').hide();
        $('#tbodyItemListing').empty();


        var selectBy = $('#selectBy').val();
        if(selectBy === 'm' && $('#inputMonth').val() === '')
        {
            bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: "Please Enter the Number of Months", 
                callback: function(){}
            });
            return false;
        }

        if(selectBy === 'w' && $('#inputWeek').val() === '')
        {
            bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: "Please Enter the Number of Weeks", 
                callback: function(){}
            });
            return false;
        }
        
        if(selectBy === 'y' && $('#inputYear').val() === '')
        {
            bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: "Please Select the Year", 
                callback: function(){}
            });
            return false;
        }
        
        if(selectBy === 'c' && $('#customDateRange').val() === '')
        {
            bootbox.alert({ 
                size: 'small',
                title: "  ", 
                message: "Please Select the Date Range", 
                callback: function(){}
            });
            return false;
        } else {
            
            var itemName = $('#search_item_name').val();
            var size = $('#search_size').val();
            var sku = $('#search_sku').val();
            var value1 = $('#select_by_value_1').val();
            var value2 = $('#select_by_value_2').val();
            
            var dept_code = $('#dept_code').val();
            var category_code = $('#category_code').val();
            var sub_category_id = $('#sub_category_id').val();
            var supplier_code = $('#supplier_code').val();
            
            if(itemName === '' && size === '' && sku === '' && value1 === '' && dept_code === 'all' && dept_code === 'all' && category_code === 'all' && sub_category_id === 'all' && supplier_code === 'all'){
                bootbox.alert({ 
                    size: 'small',
                    title: "  ", 
                    message: "Please fill / select at least one of the filters", 
                    callback: function(){}
                });
                return false;
            }
            
            
            var dateBreakup = $('#customDateRange').val().split('-');
            var from = dateBreakup[0];
            var to = dateBreakup[2];
            
            // alert(dateBreakup[1]+" "+to);
            /*var fromBreakup = dateBreakup[0].split('\');
            var fromMonth = parseInt(fromBreakup[0], 10);
            var fromDate = parseInt(fromBreakup[1], 10);
            var fromYear = parseInt(fromBreakup[2], 10);
            
            var toBreakup = dateBreakup[2].split('\');
            var toMonth = parseInt(toBreakup[0], 10);
            var toDate = parseInt(toBreakup[1], 10);
            var toYear = parseInt(toBreakup[2], 10);*/
            
            if(moment(from, 'MM/DD/YYYY').isValid() !== true || moment(to , 'MM/DD/YYYY').isValid()){
                
                bootbox.alert({ 
                    size: 'small',
                    title: "  ", 
                    message: "Please Select the Date Range", 
                    callback: function(){}
                });
                return false;
                
            }
            
            var from = Date.parse(from);
            var to = Date.parse(to); 

            if(from > to){
                bootbox.alert({ 
                    size: 'small',
                    title: "  ", 
                    message: "From date must be <b>less than</b> To date!", 
                    callback: function(){}
                });
                return false;
            }
            
            $('#inputFrom').val(from);
            $('#inputTo').val(to);
            
            
        }
        
        $("div#divLoading").addClass('show');
        
        var url = '<?php echo $data['post_po_sales_history'];?>';
        url = url.replace(/&amp;/g, '&');
        
        var dataString = {};
        
        dataString['include_current_week'] =  ($("input[name='include_current_week']:checked").val() !== undefined)?$("input[name='include_current_week']:checked").val():'';
        dataString['include_current_month'] =  ($("input[name='include_current_month']:checked").val() !== undefined)?$("input[name='include_current_month']:checked").val():'';
        
        dataString['select_by'] = ($("#selectBy").val() !== undefined)?$("#selectBy").val():'';
        
        dataString['input_month'] = ($("#inputMonth").val() !== undefined)?$("#inputMonth").val():'';



        dataString['item_name'] = ($("#search_item_name").val() !== undefined)?$("#search_item_name").val():'';
        dataString['size'] = ($("#search_size").val() !== undefined)?$("#search_size").val():'';
        dataString['barcode'] = ($("#search_sku").val() !== undefined)?$("#search_sku").val():'';

        // console.log(dataString);
        
        // var jsonString = JSON.stringify(dataString);
        
        $.ajax({
            type: "POST",
            url: url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            dataType: 'json',
            data: {data : dataString}, 
            cache: false,
    
            success: function(response){
                
                // console.log(response);
                if(response === ''){
                    
                    console.log(747);
                    
                    $('#item_history_err_div').show();
                } else {
                    $('#tbodyItemListing').html(response);
                }
                $("div#divLoading").removeClass('show');
            }
        });
        
        
    });
    
    // $(document).ready(function(){
        
       
        $('#item_listing thead tr th').each( function (i) {
           
            $(this).on('keyup change', '.search_text_box', function(){  
                console.log("jjjhjhjhj");
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            });
            
            $(this).on('change', 'select', function(){  

                if ( table.column(i).search() !== this.value) {
                    
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            });
        })

        $("div#divLoading").removeClass('show');
        
        $("#item_listing_filter").hide();
        $("#item_listing_processing").remove();
        $(".dataTables_scrollBody").remove();

        
        var url = '<?php echo $data['search_items'];?>';
        url = url.replace(/&amp;/g, '&');
    
        var table =   $("#item_listing").DataTable({
            "bSort": false,
            "bSortCellsTop": true,
            "autoWidth": false,
            "fixedHeader": true,
            "processing": false,
            "iDisplayLength": 10,
            "serverSide": true,
            "bLengthChange": false,
            "language": {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
            "dom": 't<"bottom col-md-12 row"<"col-md-3"i><"col-md-9"p>>',
           
            "ajax": {
              url: url,
              headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
              type: 'POST',
              "dataSrc": function ( json ) {
                    $("div#divLoading").removeClass('show'); 
                    // console.log(json.data);
                    return json.data;
                } 
            },
            columns : [
                {
                    data: "iitemid", 
                    render: function(data, type, row){
                        return $("<input>").attr({
                            type: 'checkbox',
                            class: "iitemid",
                            value: data,
                            name: "selected_search_history_items[]",
                            "data-order": data
                        })[0].outerHTML;
                    }
                },
                {data: "vitemname"},
                {data: "vbarcode"},
                {data: "vendor"},
                {data: "department"},
                {data: "category"},
                {data: "sub_category"},
                {data: "price"},
                {data: "cost"},
                {data: "par"},
                {data: "qoh"},
                {data: "sug_qty"}
            ]
            
            
        });
        
        $("#item_listing_filter").hide();
        $("#item_listing_paginate").addClass("pull-right");

    
    $(document).on('change', '#dept_code', function(){
        /*console.log("Dept changed");
        console.log($(this).val());*/
        
        var get_categories_url = '<?php echo $data['get_categories_url']; ?>';
        get_categories_url  = get_categories_url.replace(/&amp;/g, '&');
        
        // var input = {ids: $(this).val()};
        
        var input = {dept_code: $(this).val()};
        
        // var data = JSON.stringify(input);
        
        $.ajax({
            
            url: get_categories_url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            type: 'POST',
            data: input,
            success:function(data) {
                
                $('#category_code').html(data);
                
            }
            
            
            
        });
    });
    
    
    $(document).on('change', '#category_code', function(){
        
        var get_subcategories_url = '<?php echo $data['get_subcategories_url']; ?>';
        get_subcategories_url  = get_subcategories_url.replace(/&amp;/g, '&');
        
        
        var input = {category_code: $(this).val()};
        
        $.ajax({
            
            url: get_subcategories_url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            type: 'POST',
            data: input,
            success:function(data) {
                
                $('#sub_category_id').html(data);
                
            }
            
            
            
        });
    });     
    
    
    
</script>


<script type="text/javascript">

    $(document).on('keypress', '.nordqty_class, .npackqty_class, .itotalunit_class', function(event) {
      $(this).val($(this).val().replace(/[^\d].+/, ""));
      if ((event.which < 48 || event.which > 57)) {
          event.preventDefault();
      }
    });

  $(document).on('keypress', 'input[name="ntaxtotal"], input[name="nfreightcharge"], input[name="ndeposittotal"], input[name="nfuelcharge"], input[name="ndeliverycharge"], input[name="nreturntotal"], input[name="ndiscountamt"], input[name="nripsamt"], .nordextprice_class, .new_costprice_class,.nnewunitprice_class, .nripamount_class', function(event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
  }); 
</script>

<script src="{{ asset('javascript/jquery.maskedinput.min.js') }}"></script>
<script type="text/javascript">
  jQuery(function($){
    $("input[name='vvendorphone']").mask("999-999-9999");
  });
</script>

<script type="text/javascript">
  $(document).on('click', '#select_vendor_btn', function(event) {
    event.preventDefault();
    $('#selectVendorModal').modal('show');
  });
</script>

<!-- Modal -->
<div class="modal fade" id="selectVendorModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Vendor</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-6">
            
          </div>
          <div class="col-md-3"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Select</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).on('change', '#loaded_vendor', function(event) {
    event.preventDefault();

    $("div#divLoading").addClass('show');

    var get_vendor_url = '<?php echo $data['get_vendor']; ?>';
    get_vendor_url = get_vendor_url.replace(/&amp;/g, '&');
    
    if($(this).val() != ''){
      get_vendor_url = get_vendor_url+'?isupplierid='+ $(this).val();
      $.getJSON(get_vendor_url, function(result){
        if(result.vendor){
          $('input[name="vvendorid"]').val(result.vendor.isupplierid);
          $('input[name="vvendorname"]').val(result.vendor.vcompanyname);
          $('input[name="vvendoraddress1"]').val(result.vendor.vaddress1);
          $('input[name="vvendorstate"]').val(result.vendor.vstate);
          $('input[name="vvendorzip"]').val(result.vendor.vzip);
          $('input[name="vvendorphone"]').val(result.vendor.vphone);

          $('#myTab li:eq(1)').css('pointer-events','all');
        }
      });
        
        //copied from line number 791
        $("div#divLoading").removeClass('show');


    }else{
      $('input[name="vvendorid"]').val('');
      $('input[name="vvendorname"]').val('');
      $('input[name="vvendoraddress1"]').val('');
      $('input[name="vvendorstate"]').val('');
      $('input[name="vvendorzip"]').val('');
      $('input[name="vvendorphone"]').val('');
    //   $('#myTab li:eq(1)').css('pointer-events','none');
      $("div#divLoading").removeClass('show');
    }

  });
</script>


<script>
    $(function() {
        <?php if(isset($data['items']) && count($data['items']) > 0){?>
          window.index_item = '<?php echo count($data['items']);?>';
        <?php }else{ ?>
          window.index_item = 0;
        <?php } ?>

        <?php if(isset($data['items_id']) && count($data['items_id']) > 0){?>
          window.items_added = '<?php echo json_encode($data['items_id']);?>';
          window.items_added = $.parseJSON(window.items_added);
        <?php }else{ ?>
          window.items_added = [];
        <?php } ?>
        
        var get_search_items_url = '<?php echo $data['get_search_items']; ?>';
        get_search_items_url = get_search_items_url.replace(/&amp;/g, '&');

        $( "#automplete-product" ).autocomplete({
            minLength: 2,
            source: function(req, add) {
                $.getJSON(get_search_items_url, req, function(data) {
                    var suggestions = [];
                    $.each(data.items, function(i, val) {
                        suggestions.push({
                            label: val.vitemname,
                            value: val.vitemname,
                            id: val.iitemid
                        });
                    });
                    add(suggestions);
                });
            },
            select: function(e, ui) {

                var get_search_item_url = '<?php echo $data['get_search_item']; ?>';
                get_search_item_url = get_search_item_url.replace(/&amp;/g, '&');
                var ivendorid = $('input[name="vvendorid"]').val();

                get_search_item_url = get_search_item_url+'?iitemid='+ui.item.id+'&ivendorid='+ivendorid;

                if ($.inArray(ui.item.id, window.items_added) != -1){
                  $('#error_alias').html('<strong>Item Already Added!</strong>');
                  $('#errorModal').modal('show');
                  return false;
                }else{
                  window.items_added.push(ui.item.id);
                }

                $("div#divLoading").addClass('show');
                
                $.getJSON(get_search_item_url, function(result){
                  var html_purchase_item = '';
                  if(result.item){
                    
                      html_purchase_item += '<tr>';
                      html_purchase_item += '<td class="text-center"><input type="checkbox" name="selected_purchase_item[]" value="0"/><input type="hidden" name="items['+window.index_item+'][vitemid]" value="'+result.item.iitemid+'"><input type="hidden" name="items['+window.index_item+'][nordunitprice]" value="'+result.item.dunitprice+'"><input type="hidden" name="items['+window.index_item+'][vunitcode]" value="'+result.item.vunitcode+'"><input type="hidden" name="items['+window.index_item+'][vunitname]" value="'+result.item.vunitname+'"><input type="hidden" name="items['+window.index_item+'][ipodetid]" value="0"><input type="hidden" name="selected_added_item[]" value="'+result.item.iitemid+'"/></td>';
                      html_purchase_item += '<td style="width:20%;" class="vbarcode_class">'+result.item.vbarcode+'<input type="hidden" name="items['+window.index_item+'][vbarcode]" value="'+result.item.vbarcode+'"></td>';
                      html_purchase_item += '<td style="width:20%;" class="vitemname_class">'+result.item.vitemname+'<input type="hidden" name="items['+window.index_item+'][vitemname]" value="'+result.item.vitemname+'"></td>';

                      if(result.item.vvendoritemcode != null){
                        html_purchase_item += '<td style="width:20%;"><input type="text" class="editable_text vvendoritemcode_class" name="items['+window.index_item+'][vvendoritemcode]" value="'+result.item.vvendoritemcode+'" id="" style="width:100px;"></td>';
                      }else{
                        html_purchase_item += '<td style="width:20%;"><input type="text" class="editable_text vvendoritemcode_class" name="items['+window.index_item+'][vvendoritemcode]" value="" id="" style="width:100px;"></td>';
                      }

                      if(result.item.vsize != null){
                        html_purchase_item += '<td style="width:10%;">'+result.item.vsize+'<input type="hidden" class="editable_text vsize_class" name="items['+window.index_item+'][vsize]" value="'+result.item.vsize+'" id="" ></td>';
                      }else{
                        html_purchase_item += '<td style="width:10%;">'+result.item.vsize+'<input type="hidden" class="editable_text vsize_class" name="items['+window.index_item+'][vsize]" value="" id="" ></td>';
                      }

                      html_purchase_item += '<td class="text-right"><input type="text" class="editable_text nnewunitprice_class" name="items['+window.index_item+'][nnewunitprice]" id="" style="width:60px;text-align:right;" value="'+ result.item.dunitprice +'"></td>';
                      html_purchase_item += '<td class="text-right"><input type="text" class="editable_text nordqty_class" name="items['+window.index_item+'][nordqty]" id="" style="width:60px;text-align:right;" value="0"></td>';
                      html_purchase_item += '<td class="text-right"><input type="text" class="editable_text npackqty_class" name="items['+window.index_item+'][npackqty]" value="'+result.item.npack+'" id="" style="width:60px;text-align:right;"></td>';
                      html_purchase_item += '<td class="text-right"><span class="itotalunit_span_class">0</span><input type="hidden" class="editable_text itotalunit_class" name="items['+window.index_item+'][itotalunit]" value="0" id="" style="width:80px;text-align:right;"></td>';
                      html_purchase_item += '<td class="text-right"><input type="text" class="editable_text nordextprice_class" name="items['+window.index_item+'][nordextprice]" value="0.0000" id="" style="width:80px;text-align:right;"></td>';
                      html_purchase_item += '<td class="text-right"><input type="text" class="editable_text new_costprice_class" name="items['+window.index_item+'][nunitcost]" value="0.0000" id="" style="width:80px;text-align:right;"></td>';
                      html_purchase_item += '<td class="text-right"><input type="text" class="editable_text nripamount_class" name="items['+window.index_item+'][nripamount]" value="0.0000" id="" style="width:50px;text-align:right;"></td>';
                      html_purchase_item += '</tr>';
                      window.index_item++;
                    
                  }
                  $('tbody#purchase_order_items').prepend(html_purchase_item);
                  $("div#divLoading").removeClass('show');
                });
            }
        });
        <!-- To highlight the row in red (when suggtd cost < Total Amount) on load -->
        
        $('#purchase_order_items tr').each(function() {
            
             var suggested_cost = $(this).find('.sggtdqty_class').val();
             var nordextprice = $(this).find('.nordextprice_class').val();
     
           if(parseFloat(suggested_cost) < parseFloat(nordextprice)){
                // $(this).children('td').css('background-color', '#ff0000');
                $(this).children('td').css('background-color', '#ff944d');
                $(this).children('td.noInput').css('color', '#FFFFFF');
            } else {
                $(this).children('td').css('background-color', '#FFFFFF');
                $(this).children('td.noInput').css('color', '#666666');
            }
        });
        
        
    });
</script>

<script type="text/javascript">

  $(document).on('change input', '.nordqty_class', function(event) {
    event.preventDefault();
    
    ;
    
    var nordqty = $(this).val();
    var npackqty = $(this).closest('tr').find('.npackqty_class').val();
    var itotalunit = $(this).closest('tr').find('.itotalunit_class').val();
    var nordextprice = $(this).closest('tr').find('.nordextprice_class').val();
    var nunitcost = $(this).closest('tr').find('.new_costprice_class').val();
    var po_order_by = $(this).closest('tr').find('.po_order_by_class').val();
    var last_costprice = $(this).closest('tr').find('.nlastunitprice_class').val();

    if(npackqty != ''){
      npackqty = npackqty;
    }else{
      npackqty = 0;
    }

    if(itotalunit != ''){
      itotalunit = itotalunit;
    }else{
      itotalunit = 0;
    }

    if(nordextprice != ''){
      nordextprice = nordextprice;
    }else{
      nordextprice = 0.00;
    }

    if(nunitcost != ''){
      nunitcost = nunitcost;
    }else{
      nunitcost = 0.0000;
    }
    
    
    if(po_order_by == 'case'){
        var closest_itotalunit = nordqty * npackqty;
    } else {
        var closest_itotalunit = nordqty;
    }
    
    var closest_nunitcost = nordextprice / closest_itotalunit;

    if(isNaN(closest_nunitcost)) {
      closest_nunitcost = 0.0000;
    }else{
      closest_nunitcost = closest_nunitcost.toFixed(4);
    }

    var closest_nordextprice = closest_itotalunit * closest_nunitcost;

    if(isNaN(closest_nordextprice)) {
      closest_nordextprice = 0.00;
    }else{
      closest_nordextprice = closest_nordextprice.toFixed(2);
    }
    
    // if()
    
    // to get suggested cost
    // suggested_cost = last_costprice * closest_itotalunit;
    suggested_cost = nunitcost * closest_itotalunit;
    // suggested_cost = closest_nunitcost * closest_itotalunit;

    console.log(closest_nunitcost);

    $(this).closest('tr').find('.itotalunit_span_class').html(closest_itotalunit);
    $(this).closest('tr').find('.itotalunit_class').val(closest_itotalunit);
    $(this).closest('tr').find('.nunitcost_class').val(closest_nunitcost);
    $(this).closest('tr').find('.nordextprice_class').val(closest_nordextprice);

    $(this).closest('tr').find('.sggtdqty_class').val(suggested_cost);
    $(this).closest('tr').find('#sggtdqty_id').html(suggested_cost);

    // var subtotal = 0.00;
    // $('.nordextprice_class').each(function() {
    //   subtotal = parseFloat(subtotal) + parseFloat($(this).val());
    // });
    // $('input[name="nsubtotal"]').val(subtotal.toFixed(2));
    //net total value;
    nettotal();
    total_amount();
    total_suggested_amount();
  });


 $(document).on('change input', '.npackqty_class', function(event) {
    event.preventDefault();
    
    ;
    
    var npackqty = $(this).val();
    var nordqty = $(this).closest('tr').find('.nordqty_class').val();
    var itotalunit = $(this).closest('tr').find('.itotalunit_class').val();
    var nordextprice = $(this).closest('tr').find('.nordextprice_class').val();
    var nunitcost = $(this).closest('tr').find('.new_costprice_class').val();

    if(nordqty != ''){
      nordqty = nordqty;
    }else{
      nordqty = 0;
    }

    if(itotalunit != ''){
      itotalunit = itotalunit;
    }else{
      itotalunit = 0;
    }

    if(nordextprice != ''){
      nordextprice = nordextprice;
    }else{
      nordextprice = 0.00;
    }

    if(nunitcost != ''){
      nunitcost = nunitcost;
    }else{
      nunitcost = 0.0000;
    }

    var closest_itotalunit = nordqty * npackqty;
    var closest_nunitcost = nordextprice / closest_itotalunit;

    if(isNaN(closest_nunitcost)) {
      closest_nunitcost = 0.0000;
    }else{
      closest_nunitcost = closest_nunitcost.toFixed(4);
    }

    var closest_nordextprice = closest_itotalunit * closest_nunitcost;

    if(isNaN(closest_nordextprice)) {
      closest_nordextprice = 0.00;
    }else{
      closest_nordextprice = closest_nordextprice.toFixed(2);
    }

    $(this).closest('tr').find('.itotalunit_span_class').html(closest_itotalunit);
    $(this).closest('tr').find('.itotalunit_class').val(closest_itotalunit);
    $(this).closest('tr').find('.nunitcost_class').val(closest_nunitcost);
    $(this).closest('tr').find('.nordextprice_class').val(closest_nordextprice);

    // var subtotal = 0.00;
    // $('.nordextprice_class').each(function() {
    //   subtotal = parseFloat(subtotal) + parseFloat($(this).val());
    // });
    // $('input[name="nsubtotal"]').val(subtotal.toFixed(2));

    //net total value;
    // nettotal();
    // total_amount();

  }); 
  


    $(document).on('change input', '.nordextprice_class', function(event) {
        // event.preventDefault();
            // var character = String.fromCharCode(event.keyCode)
            // var newValue = this.value + character; 
            // if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
            //     event.preventDefault();
            //     return false;
            // }
            this.value = this.value.match(/^\d+\.?\d{0,2}/);
            
        ;
        
        var nordextprice = $(this).val();
        var npackqty = $(this).closest('tr').find('.npackqty_class').val();
        var nordqty = $(this).closest('tr').find('.nordqty_class').val();
        var itotalunit = $(this).closest('tr').find('.itotalunit_class').val();
        var nunitcost = $(this).closest('tr').find('.new_costprice_class').val();
        
        var nunitprice = $(this).closest('tr').find('.nnewunitprice_class').val();
        var suggested_cost = $(this).closest('tr').find('.sggtdqty_class').val();
        var order_by = $(this).closest('tr').find('.po_order_by_class').val();
        
    
        if(npackqty != ''){
          npackqty = npackqty;
        }else{
          npackqty = 0;
        }
    
        if(nordqty != '' && nordqty != '0'){
          nordqty = nordqty;
        }else{
        //   nordqty = 0;
            $('#error_alias').html('<strong>Please enter the <u>Order Quantity</u> to calculate the Unit Cost...</strong>');
            $('#errorModal').modal('show');
            
            // var orderQtyId = $(this).closest('tr').find('.nordqty_class').attr('id');
            var orderQtyClass = $(this).closest('tr').find('.nordqty_class');
            
            $('#errorModal').on('hidden.bs.modal', function (e) {
                orderQtyClass.focus();
            })
            return false;
        }
    
        if(itotalunit != ''){
          itotalunit = itotalunit;
        }else{
          itotalunit = 0;
        }
    
        if(nunitcost != ''){
          nunitcost = nunitcost;
        }else{
          nunitcost = 0.0000;
        }
    
        if(nordextprice != ''){
          nordextprice = nordextprice;
        }else{
          nordextprice = 0.0000;
        }
    
        if(order_by == 'case'){
            var closest_itotalunit = nordqty * npackqty;
        } else {
            var closest_itotalunit = nordqty;
        }
        
        // console.log("nordqty: "+nordqty+" npackqty: "+npackqty+" order_by:"+order_by+" itotalunit: "+closest_itotalunit);
        
        // var closest_nordextprice = nunitcost * closest_itotalunit;
    
        // var closest_itotalunit = nordqty * npackqty;
        var closest_nunitcost = nordextprice / closest_itotalunit;
    
        if(isNaN(closest_nunitcost)) {
          closest_nunitcost = 0.0000;
        }else{
          closest_nunitcost = closest_nunitcost.toFixed(4);
        }
        
    
    
        if(parseFloat(suggested_cost) < parseFloat(nordextprice)){
            // $(this).closest('tr').children('td').css('background-color', '#ff0000');
            $(this).closest('tr').children('td.vitemname_class').css('background-color', '#ffb0b0');
            // $(this).closest('tr').children('td.noInput').css('color', '#FFFFFF');
        } else {
            $(this).closest('tr').children('td.vitemname_class').css('background-color', '#FFFFFF');
            // $(this).closest('tr').children('td.noInput').css('color', '#666666');
        }
        
        $(this).closest('tr').find('.nunitcost_class').val(closest_nunitcost);
    
        var subtotal = 0.00;
        $('.nordextprice_class').each(function() {
          subtotal = parseFloat(subtotal) + parseFloat($(this).val());
        });
        $('input[name="nsubtotal"]').val(subtotal.toFixed(2));
    
        //net total value;
        nettotal();
        total_amount();
        total_suggested_amount();

    });



$(document).on('change input', '.new_costprice_class', function(event) {
    event.preventDefault();
    
    var nunitcost = $(this).val();
    var nordextprice = $(this).closest('tr').find('.nordextprice_class').val();
    var npackqty = $(this).closest('tr').find('.npackqty_class').val();
    var nordqty = $(this).closest('tr').find('.nordqty_class').val();
    var itotalunit = $(this).closest('tr').find('.itotalunit_class').val();
    
    var nunitprice = $(this).closest('tr').find('.nnewunitprice_class').val();
    var suggested_cost = $(this).closest('tr').find('.sggtdqty_class').val();
    
    var order_by = $(this).closest('tr').find('.po_order_by_class').val();


    /*if(parseFloat(suggested_cost) < parseFloat(nordextprice)){
        $(this).closest('tr').children('td').css('background-color', '#CC0000');
        $(this).closest('tr').children('td.noInput').css('color', '#FFFFFF');
    } else {
        $(this).closest('tr').children('td').css('background-color', '#FFFFFF');
        $(this).closest('tr').children('td.noInput').css('color', '#666666');
    }*/

    if(npackqty != ''){
      npackqty = npackqty;
    }else{
      npackqty = 0;
    }

    if(nordqty != ''){
      nordqty = nordqty;
    }else{
      nordqty = 0;
    }

    if(itotalunit != ''){
      itotalunit = itotalunit;
    }else{
      itotalunit = 0;
    }

    if(nunitcost != ''){
      nunitcost = nunitcost;
    }else{
      nunitcost = 0.0000;
    }

    if(nordextprice != ''){
      nordextprice = nordextprice;
    }else{
      nordextprice = 0.00;
    }
    
    if(order_by == 'case'){
        var closest_itotalunit = nordqty * npackqty;
    } else {
        var closest_itotalunit = nordqty;
    }
    
    
    var closest_nordextprice = nunitcost * closest_itotalunit;

    if(isNaN(closest_nordextprice)) {
      closest_nordextprice = 0.00;
    }else{
      closest_nordextprice = closest_nordextprice.toFixed(2);
    }

    // console.log('Order By: '+order_by+' Order Qty: '+nordqty+' Total Unit: '+closest_itotalunit+' Unit Cost: '+nunitcost+' Total Cost: '+closest_nordextprice);

    $(this).closest('tr').find('.nordextprice_class').val(closest_nordextprice);
    
    if(parseFloat(suggested_cost) < parseFloat(closest_nordextprice)){
        $(this).closest('tr').children('td.vitemname_class').css('background-color', '#ffb0b0');
        // $(this).closest('tr').children('td.noInput').css('color', '#FFFFFF');
    } else {
        $(this).closest('tr').children('td.vitemname_class').css('background-color', '#FFFFFF');
        // $(this).closest('tr').children('td.noInput').css('color', '#666666');
    }
    

    // var subtotal = 0.00;
    // $('.nordextprice_class').each(function() {
    //   subtotal = parseFloat(subtotal) + parseFloat($(this).val());
    // });
    // $('input[name="nsubtotal"]').val(subtotal.toFixed(2));

    //net total value;
    // nettotal();
    // total_amount();

  });
  
  $(document).on('change', '.po_order_by_class', function(event) {

        event.preventDefault();
    
        var nordqty = $(this).closest('tr').find('.nordqty_class').val();
        var npackqty = $(this).closest('tr').find('.npackqty_class').val();
        var itotalunit = $(this).closest('tr').find('.itotalunit_class').val();
        var nordextprice = $(this).closest('tr').find('.nordextprice_class').val();
        var nunitcost = $(this).closest('tr').find('.new_costprice_class').val();
        var po_order_by = $(this).val();
        var last_costprice = $(this).closest('tr').find('.nlastunitprice_class').val();
    
        if(npackqty != ''){
          npackqty = npackqty;
        }else{
          npackqty = 0;
        }
    
        if(itotalunit != ''){
          itotalunit = itotalunit;
        }else{
          itotalunit = 0;
        }
    
        if(nordextprice != ''){
          nordextprice = nordextprice;
        }else{
          nordextprice = 0.00;
        }
    
        if(nunitcost != ''){
          nunitcost = nunitcost;
        }else{
          nunitcost = 0.0000;
        }
        
        
        if(po_order_by == 'case'){
            var closest_itotalunit = nordqty * npackqty;
        } else {
            var closest_itotalunit = nordqty;
        }
        
        var closest_nunitcost = nordextprice / closest_itotalunit;
    
        if(isNaN(closest_nunitcost)) {
          closest_nunitcost = 0.0000;
        }else{
          closest_nunitcost = closest_nunitcost.toFixed(4);
        }
    
        var closest_nordextprice = closest_itotalunit * closest_nunitcost;
    
        if(isNaN(closest_nordextprice)) {
          closest_nordextprice = 0.00;
        }else{
          closest_nordextprice = closest_nordextprice.toFixed(2);
        }
        
        // if()
        
        // to get suggested cost
        // suggested_cost = last_costprice * closest_itotalunit;
        suggested_cost = nunitcost * closest_itotalunit;
        
    
        $(this).closest('tr').find('.itotalunit_span_class').html(closest_itotalunit);
        $(this).closest('tr').find('.itotalunit_class').val(closest_itotalunit);
        $(this).closest('tr').find('.nunitcost_class').val(closest_nunitcost);
        $(this).closest('tr').find('.nordextprice_class').val(closest_nordextprice);
    
        $(this).closest('tr').find('.sggtdqty_class').val(suggested_cost);
        $(this).closest('tr').find('#sggtdqty_id').html(suggested_cost);
        
        total_suggested_amount();
    });
   
  
  //venkat: for sub total caluculations replacement in 4 places------
  $("#save_receive_check").click(function(){
    var subtotal = 0.00;
    $('.nordextprice_class').each(function() {
      subtotal = parseFloat(subtotal) + parseFloat($(this).val());
    });
    $('input[name="nsubtotal"]').val(subtotal.toFixed(2));
    nettotal();
    total_amount();
    total_suggested_amount();
  });
  
   $("#save_purchase_order").click(function(){
    var subtotal = 0.00;
    $('.nordextprice_class').each(function() {
      subtotal = parseFloat(subtotal) + parseFloat($(this).val());
    });
    $('input[name="nsubtotal"]').val(subtotal.toFixed(2));
    nettotal();
    total_amount();
    total_suggested_amount();
  });
  
  //venkat: -----this is the end of replacement
</script>

<script type="text/javascript">
  var nettotal = function() {
            var nsubtotal = $('input[name="nsubtotal"]').val();
            var ntaxtotal = $('input[name="ntaxtotal"]').val();
            var nfreightcharge = $('input[name="nfreightcharge"]').val();
            var ndeposittotal = $('input[name="ndeposittotal"]').val();
            var nreturntotal = $('input[name="nreturntotal"]').val();
            var ndiscountamt = $('input[name="ndiscountamt"]').val();
            var nripsamt = $('input[name="nripsamt"]').val();
            var nfuelcharge = $('input[name="nfuelcharge"]').val();
            var ndeliverycharge = $('input[name="ndeliverycharge"]').val();
            
            if(nfreightcharge === ''){
                nfreightcharge = 0;   
            }
            if(ntaxtotal === ''){
                ntaxtotal = 0;   
            }
            if(ndeposittotal === ''){
                ndeposittotal = 0;   
            }
            if(nreturntotal === ''){
                nreturntotal = 0;   
            }
            if(ndiscountamt === ''){
                ndiscountamt = 0;   
            }
            if(nfuelcharge === ''){
                nfuelcharge = 0;   
            }
            if(ndeliverycharge === ''){
                ndeliverycharge = 0;   
            }
            console.log("check"+nfreightcharge);
            var nettotal = parseFloat(nsubtotal) + parseFloat(ntaxtotal) + parseFloat(nfreightcharge) + parseFloat(ndeposittotal) + parseFloat(nfuelcharge) + parseFloat(ndeliverycharge) - parseFloat(nreturntotal) - parseFloat(ndiscountamt) - parseFloat(nripsamt);
            
            $('input[name="nnettotal"]').val(nettotal.toFixed(2));
        }

  $(document).on('keyup', 'input[name="ntaxtotal"], input[name="nfreightcharge"], input[name="ndeposittotal"], input[name="nfuelcharge"], input[name="ndeliverycharge"], input[name="nreturntotal"], input[name="ndiscountamt"], input[name="nripsamt"]', function(event) {

      nettotal();

  });
</script>

<script type="text/javascript">
  $(document).on('submit', 'form#form-purchase-order', function(event) {
    event.preventDefault();
    
    // console.log("Submitted Purchase Order Form.");
    // return false;
    var post_url = $(this).attr('action');
    var check_invoice_number = true;
    var check_invoice_number_with_prev = true;

    if($('input[name="vinvoiceno"]').val() == ''){
      // alert('Please Enter Invoice!');
        bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: "Please Enter Invoice!", 
            callback: function(){}
        });
        $('input[name="vinvoiceno"]').focus();
        $("#meta_div").css('display','block');
        $("#search_div").css('display','none');
      return false;
    }

    if(($.trim($('input[name="vinvoiceno"]').val())).length==0){
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: "Please Enter Invoice!", 
        callback: function(){}
      });
      $('input[name="vinvoiceno"]').focus();
      return false;
    }

    if($('input[name="dcreatedate"]').val() == ''){
      // alert('Please Select Created Date!');
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: "Please Select Created Date!", 
        callback: function(){}
      });
      $('input[name="dcreatedate"]').focus();
      return false;
    } 

    if($('input[name="dreceiveddate"]').val() == ''){
      // alert('Please Select Received Date!');
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: "Please Select Received Date!", 
        callback: function(){}
      });
      $('input[name="dreceiveddate"]').focus();
      return false;
    } 

    // if($('input[name="vordertitle"]').val() == ''){
    //   alert('Please Enter Order Title!');
    //   return false;
    // }

    if($('input[name="vvendorid"]').val() == ''){
      // alert('Please Select Vendor!');
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: "Please Select Vendor!", 
        callback: function(){}
      });
      return false;
    }

    $("div#divLoading").addClass('show');
    var data_invoice = {}
    var check_invoice_url = '<?php echo $data['check_invoice']; ?>';
        check_invoice_url = check_invoice_url.replace(/&amp;/g, '&');
        

    data_invoice['invoice'] = $('input[name="vinvoiceno"]').val();

    <?php if(isset($data['vinvoiceno'])){ ?>
      var previous_invoice = '<?php echo $data['vinvoiceno']; ?>';
      if(previous_invoice != $('input[name="vinvoiceno"]').val()){
        check_invoice_number_with_prev = true;
      }else{
        check_invoice_number_with_prev = false;
      }
    <?php } ?>

    if(check_invoice_number_with_prev == true){
      $.ajax({
        url : check_invoice_url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
        data : JSON.stringify(data_invoice),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
        success: function(data) {
          
          check_invoice_number = true;
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
          $('#errorModal').modal('show');
          check_invoice_number = false;
          $("div#divLoading").removeClass('show');
          return false;
        }
      });
    }
    
    setTimeout(function(){
      if(check_invoice_number){

        $.ajax({
            url : post_url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            data : $('form#form-purchase-order').serialize(),
            type : 'POST',
          success: function(data_response) {
            
            $("div#divLoading").removeClass('show');
            $('#success_alias').html('<strong>'+ data_response.success +'</strong>');
            $('#successModal').modal('show');

            
                var redirect_edit_url = '<?php echo $data['redirect_edit_url']; ?>';
                redirect_edit_url = redirect_edit_url.replace(/&amp;/g, '&');
                    
                    
            <?php if(!isset($data['ipoid'])){?>
              setTimeout(function(){
                
                var new_ipoid = data_response.ipoid;
                    redirect_edit_url = redirect_edit_url+'?ipoid='+new_ipoid;
                
                window.location.href = redirect_edit_url;
               $("div#divLoading").addClass('show');
              }, 3000);
            <?php }else{ ?>
              setTimeout(function(){
                window.location.reload();
               $("div#divLoading").addClass('show');
              }, 3000);
            <?php } ?>
          },
          error: function(xhr) { // if error occured

            var  response_error = $.parseJSON(xhr.responseText); //decode the response array
            
            var error_show = '';

            if(response_error.error){
              error_show = response_error.error;
            }else if(response_error.validation_error){
              error_show = response_error.validation_error[0];
            }
            $("div#divLoading").removeClass('show');
            $('#error_alias').html('<strong>'+ error_show +'</strong>');
            $('#errorModal').modal('show');
            return false;
          }
        });
      }
     
    
    }, 3000);
  });
</script>

<!-- Modal -->
  <div class="modal fade" id="successModal" role="dialog">
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
  <div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
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
  
  <style type="text/css">
  .editable_text {
    color: #000;
    border: none;
    background: none;
    cursor: pointer;
}</style>
<script type="text/javascript">
$('.editable_text').focus(function() {
  $(this).addClass("focusField");   
    if (this.value == this.defaultValue){
      this.select();
    }
    if(this.value != this.defaultValue){
      this.select();
    }
  });
</script>

<script type="text/javascript">
  $(document).on('click', '#remove_item_btn', function(event) {
    event.preventDefault();
   
    var delete_purchase_order_item_url = '<?php echo $data['delete_purchase_order_item']; ?>';
    
        delete_purchase_order_item_url = delete_purchase_order_item_url.replace(/&amp;/g, '&');
        // delete_purchase_order_item_url = delete_purchase_order_item_url+'&sid='+sid;

        var data_delete_items = {};
        var data_selected_added_item = {};

        if($("input[name='selected_purchase_item[]']:checked").length == 0){
          $('#error_alias').html('<strong>Please Select Items to Delete!</strong>');
          $('#errorModal').modal('show');
          return false;
        }

        $("div#divLoading").addClass('show');

        $("input[name='selected_purchase_item[]']:checked").each(function (i)
        {
          data_delete_items[i] = parseInt($(this).val());
          data_selected_added_item[i] = $(this).next('input[type="hidden"]').val();
        });

        $.ajax({
        url : delete_purchase_order_item_url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
        data : JSON.stringify(data_delete_items),
        type : 'POST',
        contentType: "application/json",
        dataType: 'json',
        success: function(data) {

          $.each(data_selected_added_item, function (i, selected_item){
            $('#tab_tr_'+selected_item).remove();
          });
          
          total_suggested_amount();
          total_amount();

          $("div#divLoading").removeClass('show');
          $('#success_alias').html('<strong>'+ data.success +'</strong>');
          $('#successModal').modal('show');

          setTimeout(function(){
            $('#successModal').modal('hide');
          }, 3000);

          return false;

          // setTimeout(function(){
          //   window.location.reload();
          // }, 3000);
        },
        error: function(xhr) { // if error occured
          var  response_error = $.parseJSON(xhr.responseText); //decode the response array
          
          var error_show = '';

          if(response_error.error){
            error_show = response_error.error;
          }else if(response_error.validation_error){
            error_show = response_error.validation_error[0];
          }
          $("div#divLoading").removeClass('show');
          $('#error_alias').html('<strong>'+ error_show +'</strong>');
          $('#errorModal').modal('show');
          return false;
        }
      });

  });
</script>

<!-- Save / receive Modal-->
<div class="modal fade" id="saveReceiveModal" role="dialog" style="z-index: 9999;">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <h5>This will transfer the Purchase Order to Receiving Order. Once done, cannot be reverted. Are you sure you want to continue?</h5>
        </div>
        
      </div>
      <div class="modal-footer" style="border-top:none;">
        
        <div class="col-md-12 text-center form-inline">
            <div class="col-md-6">
                <input type="button" class="btn btn-success basic-button-small col-md-12" id="save_receive_btn" value="Yes">
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-grey headerwhite basic-button-small col-md-12" data-dismiss="modal">No</button>
            </div>
            
        </div>
          
        <!--<input type="button" class="btn btn-success" id="save_receive_btn_to_warehouse" value="Send to Warehouse">-->
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

    $(document).on('click', '#export', function(){
        $("#save_data").modal('show');
    });
  
  $(document).on('click', '#save_receive_check', function(event) {
    event.preventDefault();
    
    var btnVal = $(this).val();

    // if(btnVal === "export"){
        
    //     //display save data pop-up
    //     $("#save_data").modal('show');
        
    //     // $('#export_po_as').modal('show');
    //     return false;
    // }

    if($('input[name="vinvoiceno"]').val() == ''){
      // alert('Please Enter Invoice!');
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: "Please Enter Invoice!", 
        callback: function(){}
      });
      $('input[name="vinvoiceno"]').focus();
      return false;
    }

    if(($.trim($('input[name="vinvoiceno"]').val())).length==0){
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: "Please Enter Invoice!", 
        callback: function(){}
      });
      $('input[name="vinvoiceno"]').focus();
      return false;
    }

    if($('input[name="dcreatedate"]').val() == ''){
      // alert('Please Select Created Date!');
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: "Please Select Created Date!", 
        callback: function(){}
      });
      $('input[name="dcreatedate"]').focus();
      return false;
    } 

    if($('input[name="dreceiveddate"]').val() == ''){
      // alert('Please Select Received Date!');
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: "Please Select Received Date!", 
        callback: function(){}
      });
      $('input[name="dreceiveddate"]').focus();
      return false;
    } 

    if($('input[name="vvendorid"]').val() == ''){
      // alert('Please Select Vendor!');
      bootbox.alert({ 
        size: 'small',
        title: "  ", 
        message: "Please Select Vendor!", 
        callback: function(){}
      });
      return false;
    }


    var check_save_receive = true;
    $('tbody#purchase_order_items tr').find('td').css('background-color','#fff');

    $('tbody#purchase_order_items tr').not(':last').each(function() {
      var current_nnewunitprice = parseFloat($(this).find('.nnewunitprice_class').val());
      var current_nunitcost = parseFloat($(this).find('.new_costprice_class').val());
      
      if(current_nnewunitprice < current_nunitcost){
        check_save_receive = false;
        $(this).find('td').css('background-color','#f0ad4e');
        // alert('price required more then unit cost');
        
      }
    });
    if(!check_save_receive)
    {
        bootbox.alert({ 
          size: 'small',
          title: "  ", 
          message: "price required more then unit cost", 
          callback: function(){}
        });
        return false;
    }
    
    if(check_save_receive){
      $('tbody#purchase_order_items tr').not(':last').each(function() {
        var current_itotalunit = parseFloat($(this).find('.itotalunit_class').val());
       
        if(current_itotalunit == 0){
          check_save_receive = false;
          $(this).find('td').css('background-color','#f0ad4e');
          // alert('price required more then unit cost');
         
        }
      });
      
      if(!check_save_receive)
      {
           bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: "Total Unit must not be zero OR delete Item!", 
            callback: function(){}
          });
          return false;
      }
    }

    if(check_save_receive){
      $('tbody#purchase_order_items tr').not(':last').each(function() {
        var current_tot_amt = $(this).find('.nordextprice_class').val();
       
        if(current_tot_amt == '0' || current_tot_amt == '0.00' || current_tot_amt == '0.000' || current_tot_amt == '0.0000'){
          check_save_receive = false;
          $(this).find('td').css('background-color','#f0ad4e');
          // alert('price required more then unit cost');
          
        }
      });
      if(!check_save_receive)
      {
      bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: "Total Amt must not be zero!", 
            callback: function(){}
          });
          return false;
      }
    }

    if(check_save_receive){
      $('tbody#purchase_order_items tr').not(':last').each(function() {
        var current_unitcost_amt = $(this).find('.new_costprice_class').val();
       
        if(current_unitcost_amt == '0' || current_unitcost_amt == '0.00' || current_unitcost_amt == '0.000' || current_unitcost_amt == '0.0000'){
          check_save_receive = false;
          $(this).find('td').css('background-color','#f0ad4e');
          // alert('price required more then unit cost');
          bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: "Unit Cost must not be zero!", 
            callback: function(){}
          });
          return false;
        }
      });
    }
   
    if(check_save_receive){
    //   if($('tbody#purchase_order_items tr').not(':last').length == 0){
      if($('tbody#purchase_order_items tr').length == 0){

        // alert('Please add items');
        bootbox.alert({ 
          size: 'small',
          title: "  ", 
          message: "Please add items", 
          callback: function(){}
        });
        return false;
      }else{
        $('#saveReceiveModal').modal('show');
      }
    } 

  });
// console.log(1781);
// return false;
  $(document).on('click', '#save_receive_btn', function(event) {
    event.preventDefault();
    $('#receive_po').val('receivetostore');
    $("div#divLoading").addClass('show');
    $('#saveReceiveModal').modal('hide');
    var save_receive_item_url = '<?php echo $data['save_receive_item']; ?>';
   
    save_receive_item_url = save_receive_item_url.replace(/&amp;/g, '&');

    $.ajax({
      url : save_receive_item_url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
      data : $('form#form-purchase-order').serialize(),
      type : 'POST',
      success: function(data) {
        $('#saveReceiveModal').modal('hide');
        $("div#divLoading").removeClass('show');
        $('#success_alias').html('<strong>'+ data.success +'</strong>');
        $('#successModal').modal('show');

        var purchase_order_list_url = '<?php echo $data['purchase_order_list']; ?>';
        purchase_order_list_url = purchase_order_list_url.replace(/&amp;/g, '&');
        
        <?php if(!isset($data['ipoid'])){?>
          setTimeout(function(){
            window.location.href = purchase_order_list_url;
          $("div#divLoading").addClass('show');
          }, 3000);
        <?php }else{ ?>
          setTimeout(function(){
            window.location.reload();
          $("div#divLoading").addClass('show');
          }, 3000);
        <?php } ?>
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }
        $('#saveReceiveModal').modal('hide');
        $("div#divLoading").removeClass('show');
        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorModal').modal('show');
        return false;
      }
    });

  });


</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

<script type="text/javascript">

  /*$(document).on('click', '#myTab li a', function() {
    
    if($(this).attr('href') == '#item_tab'){
      $.cookie("tab_selected_po", 'item_tab'); //set cookie tab
    }else{
      $.cookie("tab_selected_po", 'general_tab'); //set cookie tab
    }
    
  });*/

  /*$(document).on('click', '#cancel_button, #menu li a, .breadcrumb li a', function() {
    $.cookie("tab_selected_po", ''); //set cookie tab
  });*/
</script>

<script type="text/javascript">

  function total_amount(){
    var tot_amt = 0.00;
    $(".nordextprice_class").each(function(){
      tot_amt = parseFloat(tot_amt) + parseFloat($(this).val());
    });
    
    if(parseFloat(tot_amt).toFixed(2) === 'NaN'){
        $('span.total_amount').html('0.00');
    } else {
        $('span.total_amount').html(parseFloat(tot_amt).toFixed(2));
    }
  }
  
  
  function total_suggested_amount(){
      
    var tot_suggtd_cost = 0.00;
    $(".sggtdqty_class").each(function(){
      tot_suggtd_cost = parseFloat(tot_suggtd_cost) + parseFloat($(this).val());
    });
    
    if(parseFloat(tot_suggtd_cost).toFixed(2) === 'NaN'){
        $('span.total_suggested_amount').html('0.00');
    } else {
        $('span.total_suggested_amount').html(parseFloat(tot_suggtd_cost).toFixed(2));
    }
  }
</script>
<style type="text/css">
  .sortable{
    cursor: pointer;
  }
</style>



<!-- ==================================================== Start Gross Profit Modal ================================== -->
<div id="grossProfitModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style="">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="padding-bottom: 2px;padding-top: 3px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Records of last Purchases (Starting from the Latest Purchase)</h4>
      </div>
      <div class="modal-body">

        <div id="purchase_history_section">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive" style="height: 250px;">
                <table class="table table-bordered" id="table_show_purchase_history">
                  <thead>
                    <tr>
                      <th class="text-left">Cost</th>
                      <th class="">Selling Price</th>
                      <th class="">Gross Profit %</th>
                    </tr>
                  </thead>
                  <tbody id="show_purchase_history">
                    
                  </tbody>
                </table>
                <div class="text-center" id="rotating_logo_purchase_history">
                  <img src="{{ asset('image/loading1.gif') }}" alt="">
                </div>
                <div class="alert alert-info text-center" id="below_purchase_history_err_div">
                  <strong>Sorry no data found!</strong>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
<!-- ============================================================ End Gross Profit ===========================================-->


<script type="text/javascript">
    var search_ajax;
    $(document).ready(function(){
        
        /* To restrict exeeding the maximum variable limit while editin the items*/
        var total_items = "<?php echo count($data['items']); ?>";
        if(total_items >= 500)
        {
            // $("#add_item_btn").attr('disabled',true);
            // $("#add_selected_items").attr('disabled',true);
        }
        else
        {
            // $("#add_item_btn").attr('disabled',false);
            // $("#add_selected_items").attr('disabled',true);
        }
        
    });
    
    
    $(document).on('click', '.eye', function(event) {
        event.preventDefault();
        
        $('#purchase_history_section').show();
        $('#rotating_logo_purchase_history').show();
        $('#below_purchase_history_err_div').hide();
        $('tbody#show_purchase_history').empty();

        $('#grossProfitModal').modal('show');
        
        var vitemcode = this.id;
        
        // console.log('Clicked on Eye'+vitemcode);

        
        var get_purchase_history_url = '<?php echo $data['get_purchase_history']; ?>';
        get_purchase_history_url = get_purchase_history_url.replace(/&amp;/g, '&');
        get_purchase_history_url = get_purchase_history_url+'?vitemcode=' + vitemcode;

        
        $.ajax({
          url : get_purchase_history_url,
          headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
          type : 'GET',
          success: function(result) {
            // $('tbody#show_item_history').empty();
            var purchase_history_html = '';
    
            if(result.purchase_history){
                
                $.each(result.purchase_history, function(i, item) {
    
                    purchase_history_html += '<tr>';
                    purchase_history_html += '<td>'+parseFloat(item.cost).toFixed(2)+'</td>';
                    purchase_history_html += '<td>'+parseFloat(item.selling_price).toFixed(2)+'</td>';
                    purchase_history_html += '<td>'+parseFloat(item.profit_percent).toFixed(2) +' % </td>';
                    purchase_history_html += '<tr>';

                });
                
            }
    
            if(purchase_history_html == ''){
                $('#rotating_logo_purchase_history').hide();
                $('#below_purchase_history_err_div').show();
            }
            
            // console.log(purchase_history_html);
            
            $('tbody#show_purchase_history').html(purchase_history_html);
            $('#rotating_logo_purchase_history').hide();
    
            return false;
          },
          error: function(xhr) { // if error occured
            var  response_error = $.parseJSON(xhr.responseText); //decode the response array
            
            var error_show = '';
    
            if(response_error.error){
              error_show = response_error.error;
            }else if(response_error.validation_error){
              error_show = response_error.validation_error[0];
            }
            
            $("div#divLoading").removeClass('show');
            $('#error_alias').html('<strong>'+ error_show +'</strong>');
            $('#errorModal').modal('show');
            return false;
          }
        });        
        
        // show_purchase_history rotating_logo_purchase_history below_purchase_history_err_div
        
    });
    
    
  function OrderBy(a,b,n) {
    if (n) return a-b;
    if (a < b) return -1;
    if (a > b) return 1;
    return 0;
  }

  $(".sortable").click(function(){
    var $th = $(this).closest('th');
    $th.toggleClass('selected');
    var isSelected = $th.hasClass('selected');
    var isInput= $th.hasClass('input');
    var column = $th.index();
    var $table = $th.closest('table');
    var isNum= $table.find('tbody > tr').children('td').eq(column).hasClass('num');
    var rows = $table.find('tbody > tr').get();
    rows.sort(function(rowA,rowB) {
        if (isInput) {
            var keyA = $(rowA).children('td').eq(column).children('input').val().toUpperCase();
            var keyB = $(rowB).children('td').eq(column).children('input').val().toUpperCase();
        } else {
            var keyA = $(rowA).children('td').eq(column).text().toUpperCase();
            var keyB = $(rowB).children('td').eq(column).text().toUpperCase();
        }
        if (isSelected) return OrderBy(keyA,keyB,isNum);
        return OrderBy(keyB,keyA,isNum);
    });
    $.each(rows, function(index,row) {
        $table.children('tbody').append(row);
    });
    return false;
  });

  $(document).on('click', '#head_checkbox', function(event) {

    if ($(this).prop('checked')==true){ 
      $('input[name="selected_search_history_items[]"]').prop('checked',true);
    }else{
      $('input[name="selected_search_history_items[]"]').prop('checked',false);
    }

  });

  $(document).on('click', '#add_selected_items', function(event) {
    event.preventDefault();

        
        var add_purchase_order_item_url = '<?php echo $data['add_purchase_order_item']; ?>';
        add_purchase_order_item_url = add_purchase_order_item_url.replace(/&amp;/g, '&');
        
        var data_add_items = {};
        // var transfer_to_po = {};
        
        
        var ivendorid = $('input[name="vvendorid"]').val();

        if($("input[name='selected_search_history_items[]']:checked").length == 0){
          $('#error_alias').html('<strong>Please Select Items to Add!</strong>');
          $('#errorModal').modal('show');
          return false;
        }
        
        var pre_items_id = {};
        $('tbody#purchase_order_items > tr').not(':last').each(function(index_val, val) {
          pre_items_id[index_val] = $('input[name^="items['+index_val+'][vitemid]"]').val();
        });

        var send_post_data = {};
        send_post_data['items_id'] = data_add_items;
        send_post_data['pre_items_id'] = pre_items_id;
        send_post_data['ivendorid'] = ivendorid;
      
      
      var transfer_to_po = {};

      
      $("input[name='selected_search_history_items[]']:checked").each(function (i) {
          data_add_items[i] = parseInt($(this).val());
          
        //   console.log($(this).parent('tr').find('.orderBy').val());
          transfer_to_po[i] = {};
          /*transfer_to_po[i]['order_by'] = $(this).parents('tr').find('.orderBy').val();
          transfer_to_po[i]['order_qty'] = $(this).parents('tr').find('.orderQty').val();
          transfer_to_po[i]['amount'] = $(this).parents('tr').find('.amount').val();*/
          
          transfer_to_po[i]['order_by'] = 'case';
          transfer_to_po[i]['order_qty'] = 0;
          transfer_to_po[i]['amount'] = '0.00';
          
          
      });
    
    //   console.log(transfer_to_po);
      
      
      

      $.ajax({
      url : add_purchase_order_item_url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
      data : JSON.stringify(send_post_data),
      type : 'POST',
      contentType: "application/json",
      dataType: 'json',
      success: function(result) {

        var html_purchase_item = '';
        if(result.items){
            
          $.each(result.items, function(index, item) {
            html_purchase_item += '<tr id="tab_tr_'+item.iitemid+'">';
            html_purchase_item += '<td class="text-center noInput"><input type="checkbox" name="selected_purchase_item[]" value="0"/><input type="hidden" name="items['+window.index_item+'][vitemid]" value="'+item.iitemid+'"><input type="hidden" name="items['+window.index_item+'][nordunitprice]" value="'+item.dunitprice+'"><input type="hidden" name="items['+window.index_item+'][vunitcode]" value="'+item.vunitcode+'"><input type="hidden" name="items['+window.index_item+'][vunitname]" value="'+item.vunitname+'"><input type="hidden" name="items['+window.index_item+'][ipodetid]" value="0"><input type="hidden" name="selected_added_item[]" value="'+item.iitemid+'"/></td>';
            html_purchase_item += '<td style="width:20%;" class="vbarcode_class noInput">'+item.vendor_name+'<input type="hidden" name="items['+window.index_item+'][vendor_name]" value="'+item.vendor_name+'"></td>';
            html_purchase_item += '<td style="width:10%;" class="vbarcode_class noInput">'+item.vbarcode+'<input type="hidden" name="items['+window.index_item+'][vbarcode]" value="'+item.vbarcode+'"></td>';
            html_purchase_item += '<td style="width:20%;" class="vitemname_class noInput">'+item.vitemname+'<input type="hidden" name="items['+window.index_item+'][vitemname]" value="'+item.vitemname+'"></td>';

            if(item.vsize != null){
              html_purchase_item += '<td class="noInput" style="width:5%;">'+item.vsize+'<input type="hidden" class="vsize_class" name="items['+window.index_item+'][vsize]" value="'+item.vsize+'" id="" ></td>';
            }else{
              html_purchase_item += '<td class="noInput" style="width:5%;">'+item.vsize+'<input type="hidden" class="vsize_class" name="items['+window.index_item+'][vsize]" value="" id="" ></td>';
            }

            //Considering the new_costprice as unit cost
            html_purchase_item += '<td class="text-right">'+item.new_costprice+'<input type="hidden" class="new_costprice_class" name="items['+window.index_item+'][new_costprice]" value="'+item.new_costprice+'" id="" style="width:60px;text-align:right;"></td>';


            html_purchase_item += '<td class="text-right"><input type="text" class="npackqty_class" name="items['+window.index_item+'][npackqty]" value="'+item.npack+'" id="" style="width:60px;text-align:right;"></td>';
            
            // html_purchase_item += '<td class="text-right"><input type="text" class="npackqty_class" name="items['+window.index_item+'][npackqty]" value="'+item.npack+'" id="" style="width:60px;text-align:right;"></td>';
            
            html_purchase_item += '<td class="text-right"><select class="po_order_by_class" name="items['+window.index_item+'][po_order_by]"><option value="case"';
            
            if(transfer_to_po[index]['order_by'] == 'case'){
                html_purchase_item += 'selected ';
                qty_unit_case = transfer_to_po[index]['order_qty']*item.npack;
                
                // console.log('Ordr Qty: '+transfer_to_po[index]['order_qty']+' npack: '+item.npack);
                // nordqty = transfer_to_po[index]['order_qty'];
            }
            
            html_purchase_item += '>Case</option><option value="unit"';
            
            if(transfer_to_po[index]['order_by'] == 'unit'){
                html_purchase_item += 'selected ';
                qty_unit_case = transfer_to_po[index]['order_qty'];
            }
            
            html_purchase_item += '>Unit</option></select></td>';
            
            // suggested_cost = qty_unit_case * item.last_costprice;

            suggested_cost = qty_unit_case * item.new_costprice;
            
            // <input type="text" class="orderBy" name="items['+window.index_item+'][dunitprice]" id="" style="width:60px;text-align:right;" value="'+  transfer_to_po[index]['order_by'] +'">
            
            
            html_purchase_item += '<td class="text-right"><input type="text" class="nordqty_class" name="items['+window.index_item+'][nordqty]" id="" style="width:60px;text-align:right;" value="'+ transfer_to_po[index]['order_qty'] +'"><input type="hidden" class="itotalunit_class" name="items['+window.index_item+'][itotalunit]" value="'+ qty_unit_case +'" id="" style="width:80px;text-align:right;"></td>';
            // html_purchase_item += '<td class="text-right noInput"><span class="itotalunit_span_class">'+ qty_unit_case +'</span><input type="hidden" class="itotalunit_class" name="items['+window.index_item+'][itotalunit]" value="'+ qty_unit_case +'" id="" style="width:80px;text-align:right;"></td>';
            html_purchase_item += '<td class="text-right"><input type="text" class="sggtdqty_class" name="items['+window.index_item+'][po_total_suggested_cost]" id="" style="width:60px;text-align:right;" value="'+ suggested_cost +'" readonly></td>';
            
            unitCost = transfer_to_po[index]['amount'] / qty_unit_case;
            unitCost = (isNaN(unitCost)) ? 0 : unitCost;
                
            html_purchase_item += '<td class="text-right"><input type="text" class="nordextprice_class" name="items['+window.index_item+'][nordextprice]" value="' + transfer_to_po[index]['amount'] + '" id="" style="width:80px;text-align:right;"><input type="hidden" class="nunitcost_class" name="items['+window.index_item+'][nunitcost]" value="' + unitCost.toFixed(4) + '" id="" style="width:80px;text-align:right;" readonly></td>';
            
            /*unitCost = transfer_to_po[index]['amount'] / qty_unit_case;
            
            if(isNaN(unitCost)){
              unitCost='0.00';
            }else{
              unitCost = unitCost.toFixed(4)  
            }*/
            
            // html_purchase_item += '<td class="text-right"><input type="text" class="nunitcost_class" name="items['+window.index_item+'][nunitcost]" value="' + unitCost + '" id="" style="width:80px;text-align:right;" readonly></td>';
            // html_purchase_item += '<td class="text-right"><input type="text" class="nripamount_class" name="items['+window.index_item+'][nripamount]" value="0.00" id="" style="width:50px;text-align:right;"></td>';
            html_purchase_item += '</tr>';
            window.index_item++;
          });
        }
        $('tbody#purchase_order_items').append(html_purchase_item);
        
        
        $("div#divLoading").removeClass('show');
        
        total_suggested_amount();
        total_amount();

        if($('#advance_update').is(':checked')){
          advance_update_function(false);
        }else{
          advance_update_function(true);
        }
        
        //$('#success_alias').html('<strong>Items Added Successfully</strong>');
        //$('#successModal').modal('show');
        //setTimeout(function(){
         //$('#successModal').modal('hide');
        //}, 3000);

        /*$('#history_items').empty();
        $('#item_history_section').empty();
        $('#search_item_history').val('');
        $('#search_vendor_item_code').val('');
        $('#item_history_success_div').show();*/
        
        
        $('#tbodyItemListing').empty();
        $('#item_history_success_div').show();
        setTimeout(function(){
         $('#item_history_success_div').hide();
        }, 5000);
        return false;
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }
        
        $("div#divLoading").removeClass('show');
        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorModal').modal('show');
        return false;
      }
    });

    $('.iitemid').prop('checked', false);
    
  });
</script>

<script type="text/javascript">
  $(document).on('click', '.item_history_btn', function(event) {
    event.preventDefault();

    window.iitemid = $(this).attr('data-iitemid');
    window.vitemcode = $(this).attr('data-vitemcode');
    window.vitemname = $(this).attr('data-vitemname');

    if($('input[name="custom_start_date"]').val() != '' && $('input[name="custom_end_date"]').val() != ''){
      
      var get_item_history_url = '<?php echo $data['get_item_history_date']; ?>';
      get_item_history_url = get_item_history_url.replace(/&amp;/g, '&');
      var custom_start_date = $('input[name="custom_start_date"]').val();
      var custom_end_date = $('input[name="custom_end_date"]').val();
      var radio_search_by = '';

      get_item_history_url = get_item_history_url+'?iitemid='+ window.iitemid +'&vitemcode='+ window.vitemcode +'&start_date='+custom_start_date+'&end_date='+custom_end_date;

    }else{

      var get_item_history_url = '<?php echo $data['get_item_history']; ?>';
      get_item_history_url = get_item_history_url.replace(/&amp;/g, '&');

      var radio_search_by = $('input[name=radio_search_by]:checked').val();

      get_item_history_url = get_item_history_url+'?iitemid='+ window.iitemid +'&vitemcode='+ window.vitemcode +'&radio_search_by='+radio_search_by;

    }
    
    $('#item_history_section').show();
    $('#rotating_logo_item_history').show();
    $('#below_item_history_err_div').hide();
    $('tbody#show_item_history').empty();

    $.ajax({
      url : get_item_history_url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
      type : 'GET',
      success: function(result) {
        $('tbody#show_item_history').empty();

        if(result.item_detail){
          $('span#item_history_vitemname').html(window.vitemname);
          $('span#item_history_items_sold').html(parseInt(result.item_detail.items_sold));
          var total_selling_price1 = parseFloat(result.item_detail.total_selling_price);
          var items_sold1 = parseInt(result.item_detail.items_sold);

          if(total_selling_price1 == 0){
            var dis_average_selling_price = 0.00;
          }else{
            var dis_average_selling_price = total_selling_price1 / items_sold1;
          }
          
          $('span#item_history_average_selling_price').html('$'+dis_average_selling_price.toFixed(2));
        }

        if(result.purchase_items){
          var item_history_html = '';
          if(radio_search_by == 'pre_ytd'){
            $.each(result.purchase_items, function(i, item) {

              // item_history_html += '<tr>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '<b>'+i+'</b>';
              // item_history_html += '</td>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '&nbsp;';
              // item_history_html += '</td>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '&nbsp;';
              // item_history_html += '</td>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '&nbsp;';
              // item_history_html += '</td>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '&nbsp;';
              // item_history_html += '</td>';
              // item_history_html += '</tr>';

              $.each(item, function(index, purchase_item) {
                item_history_html += '<tr>';
                item_history_html += '<td>';
                item_history_html += purchase_item.purchase_date;
                item_history_html += '</td>';
                item_history_html += '<td class="text-right">';
                item_history_html += purchase_item.total_quantity;
                item_history_html += '</td>';
                item_history_html += '<td class="text-right">';
                item_history_html += purchase_item.total_cost_price;
                item_history_html += '</td>';
                item_history_html += '<td class="text-right">';
                item_history_html += purchase_item.nunitcost;
                item_history_html += '</td>';
                item_history_html += '<td>';
                item_history_html += purchase_item.vvendorname;
                item_history_html += '</td>';
                item_history_html += '</tr>';
              });
            });
          }else{
            $.each(result.purchase_items, function(index, purchase_item) {
              item_history_html += '<tr>';
              item_history_html += '<td>';
              item_history_html += purchase_item.purchase_date;
              item_history_html += '</td>';
              item_history_html += '<td class="text-right">';
              item_history_html += purchase_item.total_quantity;
              item_history_html += '</td>';
              item_history_html += '<td class="text-right">';
              item_history_html += purchase_item.total_cost_price;
              item_history_html += '</td>';
              item_history_html += '<td class="text-right">';
              item_history_html += purchase_item.nunitcost;
              item_history_html += '</td>';
              item_history_html += '<td>';
              item_history_html += purchase_item.vvendorname;
              item_history_html += '</td>';
              item_history_html += '</tr>';
            });
          }

          if(item_history_html == ''){
            $('#rotating_logo_item_history').hide();
            $('#below_item_history_err_div').show();
          }
          $('tbody#show_item_history').append(item_history_html);
          $('#rotating_logo_item_history').hide();

        }else{
          $('#rotating_logo_item_history').hide();
          $('#below_item_history_err_div').show();
        }

        return false;
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }
        
        $("div#divLoading").removeClass('show');
        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorModal').modal('show');
        return false;
      }
    });
  });

  $(document).on('change', 'input[name="custom_start_date"],input[name="custom_end_date"]', function(event) {
    event.preventDefault();

    if($('input[name="custom_start_date"]').val() != '' && $('input[name="custom_end_date"]').val() != ''){

      var d1 = Date.parse($('input[name="custom_start_date"]').val());
      var d2 = Date.parse($('input[name="custom_end_date"]').val()); 

      if(d1 > d2){
        bootbox.alert({ 
          size: 'small',
          title: "  ", 
          message: "Start date must be less then end date!", 
          callback: function(){}
        });
      return false;
      }
      
      var get_item_history_url = '<?php echo $data['get_item_history_date']; ?>';
      get_item_history_url = get_item_history_url.replace(/&amp;/g, '&');
      var custom_start_date = $('input[name="custom_start_date"]').val();
      var custom_end_date = $('input[name="custom_end_date"]').val();

      get_item_history_url = get_item_history_url+'?iitemid='+ window.iitemid +'&vitemcode='+ window.vitemcode +'&start_date='+custom_start_date+'&end_date='+custom_end_date;

      $('#item_history_section').show();
      $('#rotating_logo_item_history').show();
      $('#below_item_history_err_div').hide();
      $('tbody#show_item_history').empty();

      $("input:radio[name='radio_search_by']").each(function(i) {
        this.checked = false;
      });

      $.ajax({
        url : get_item_history_url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
        type : 'GET',
        success: function(result) {
          
          $('tbody#show_item_history').empty();

          if(result.item_detail){
            $('span#item_history_vitemname').html(window.vitemname);
            $('span#item_history_items_sold').html(parseInt(result.item_detail.items_sold));
            
            var total_selling_price1 = parseFloat(result.item_detail.total_selling_price);
            var items_sold1 = parseInt(result.item_detail.items_sold);
            
            if(total_selling_price1 == 0){
              var dis_average_selling_price = 0.00;
            }else{
              var dis_average_selling_price = total_selling_price1 / items_sold1;
            }

            $('span#item_history_average_selling_price').html('$'+dis_average_selling_price.toFixed(2));
          }
          if(result.purchase_items){
            var item_history_html = '';

              $.each(result.purchase_items, function(index, purchase_item) {
                item_history_html += '<tr>';
                item_history_html += '<td>';
                item_history_html += purchase_item.purchase_date;
                item_history_html += '</td>';
                item_history_html += '<td class="text-right">';
                item_history_html += purchase_item.total_quantity;
                item_history_html += '</td>';
                item_history_html += '<td class="text-right">';
                item_history_html += purchase_item.total_cost_price;
                item_history_html += '</td>';
                item_history_html += '<td class="text-right">';
                item_history_html += purchase_item.nunitcost;
                item_history_html += '</td>';
                item_history_html += '<td>';
                item_history_html += purchase_item.vvendorname;
                item_history_html += '</td>';
                item_history_html += '</tr>';
              });

            if(item_history_html == ''){
              $('#rotating_logo_item_history').hide();
              $('#below_item_history_err_div').show();
            }
            $('tbody#show_item_history').append(item_history_html);
            $('#rotating_logo_item_history').hide();

          }else{
            $('#rotating_logo_item_history').hide();
            $('#below_item_history_err_div').show();
          }

          return false;
        },
        error: function(xhr) { // if error occured
          var  response_error = $.parseJSON(xhr.responseText); //decode the response array
          
          var error_show = '';

          if(response_error.error){
            error_show = response_error.error;
          }else if(response_error.validation_error){
            error_show = response_error.validation_error[0];
          }
          
          $("div#divLoading").removeClass('show');
          $('#error_alias').html('<strong>'+ error_show +'</strong>');
          $('#errorModal').modal('show');
          return false;
        }
      });

    }

  }); 

  $(document).on('change', 'input[name="radio_search_by"]', function(event) {
    event.preventDefault();

    var get_item_history_url = '<?php echo $data['get_item_history']; ?>';
    get_item_history_url = get_item_history_url.replace(/&amp;/g, '&');
    var radio_search_by = $(this).val();

    get_item_history_url = get_item_history_url+'?iitemid='+ window.iitemid +'&vitemcode='+ window.vitemcode +'&radio_search_by='+radio_search_by;
    
    $('#item_history_section').show();
    $('#rotating_logo_item_history').show();
    $('#below_item_history_err_div').hide();
    $('tbody#show_item_history').empty();

    $('input[name="custom_start_date"]').val('');
    $('input[name="custom_end_date"]').val('');

    $.ajax({
      url : get_item_history_url,
      headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
      type : 'GET',
      success: function(result) {
        
        $('tbody#show_item_history').empty();

        if(result.item_detail){
          $('span#item_history_vitemname').html(window.vitemname);
          $('span#item_history_items_sold').html(parseInt(result.item_detail.items_sold));
          
          var total_selling_price1 = parseFloat(result.item_detail.total_selling_price);
          var items_sold1 = parseInt(result.item_detail.items_sold);
          
          if(total_selling_price1 == 0){
            var dis_average_selling_price = 0.00;
          }else{
            var dis_average_selling_price = total_selling_price1 / items_sold1;
          }

          $('span#item_history_average_selling_price').html('$'+dis_average_selling_price.toFixed(2));
        }
        if(result.purchase_items){
          var item_history_html = '';
          if(radio_search_by == 'pre_ytd'){
            $.each(result.purchase_items, function(i, item) {

              // item_history_html += '<tr>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '<b>'+i+'</b>';
              // item_history_html += '</td>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '&nbsp;';
              // item_history_html += '</td>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '&nbsp;';
              // item_history_html += '</td>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '&nbsp;';
              // item_history_html += '</td>';
              // item_history_html += '<td style="border:none;">';
              // item_history_html += '&nbsp;';
              // item_history_html += '</td>';
              // item_history_html += '</tr>';

              $.each(item, function(index, purchase_item) {
                item_history_html += '<tr>';
                item_history_html += '<td>';
                item_history_html += purchase_item.purchase_date;
                item_history_html += '</td>';
                item_history_html += '<td class="text-right">';
                item_history_html += purchase_item.total_quantity;
                item_history_html += '</td>';
                item_history_html += '<td class="text-right">';
                item_history_html += parseFloat(purchase_item.total_cost_price).toFixed(2);
                item_history_html += '</td>';
                item_history_html += '<td class="text-right">';
                item_history_html += parseFloat(purchase_item.nunitcost).toFixed(2);
                item_history_html += '</td>';
                item_history_html += '<td>';
                item_history_html += purchase_item.vvendorname;
                item_history_html += '</td>';
                item_history_html += '</tr>';
              });
            });
          }else{
            $.each(result.purchase_items, function(index, purchase_item) {
              item_history_html += '<tr>';
              item_history_html += '<td>';
              item_history_html += purchase_item.purchase_date;
              item_history_html += '</td>';
              item_history_html += '<td class="text-right">';
              item_history_html += purchase_item.total_quantity;
              item_history_html += '</td>';
              item_history_html += '<td class="text-right">';
              item_history_html += purchase_item.total_cost_price;
              item_history_html += '</td>';
              item_history_html += '<td class="text-right">';
              item_history_html += purchase_item.nunitcost;
              item_history_html += '</td>';
              item_history_html += '<td>';
              item_history_html += purchase_item.vvendorname;
              item_history_html += '</td>';
              item_history_html += '</tr>';
            });
          }

          if(item_history_html == ''){
            $('#rotating_logo_item_history').hide();
            $('#below_item_history_err_div').show();
          }
          $('tbody#show_item_history').append(item_history_html);
          $('#rotating_logo_item_history').hide();

        }else{
          $('#rotating_logo_item_history').hide();
          $('#below_item_history_err_div').show();
        }

        return false;
      },
      error: function(xhr) { // if error occured
        var  response_error = $.parseJSON(xhr.responseText); //decode the response array
        
        var error_show = '';

        if(response_error.error){
          error_show = response_error.error;
        }else if(response_error.validation_error){
          error_show = response_error.validation_error[0];
        }
        
        $("div#divLoading").removeClass('show');
        $('#error_alias').html('<strong>'+ error_show +'</strong>');
        $('#errorModal').modal('show');
        return false;
      }
    });

  });

// advance update checkbox

  $(document).ready(function(){
    advance_update_function(true);
  });

  $(document).on('change', '#advance_update', function(event) {
    event.preventDefault();
    if($(this).is(':checked')){
      advance_update_function(false);
    }else{
      advance_update_function(true);
    }
  });

  var advance_update_function = function(status){
    if(status == false){
      $(".vvendoritemcode_class, .nnewunitprice_class, .npackqty_class").attr("readonly", false); 
      $(".vvendoritemcode_class, .nnewunitprice_class, .npackqty_class").css("background", '#fff'); 
    }else{
      $(".vvendoritemcode_class, .nnewunitprice_class, .npackqty_class").attr("readonly", true); 
      $(".vvendoritemcode_class, .nnewunitprice_class, .npackqty_class").css("background", '#DCDCDC'); 
    }
  }

  $(document).on('click', '#advance_btn', function(event) {
    event.preventDefault();

    if($(this).attr('data-check') == 'unchecked'){
      $("#advance_update").prop( "checked", true );
      $("#advance_update").trigger('change');
      $('#advance_btn').attr('data-check', 'checked');
    }else{
      $("#advance_update").prop( "checked", false );
      $("#advance_update").trigger('change');
      $('#advance_btn').attr('data-check', 'unchecked');
    }
    
  });

  $(document).on('click', '.vvendoritemcode_class, .nnewunitprice_class, .nordqty_class, .npackqty_class, .nordextprice_class, .new_costprice_class, .nripamount_class', function(event) {
    event.preventDefault();
    $(this).select();
  });
</script>

<script type="text/javascript">

    $(document).on('keyup', '.orderQty', function(event) {
        var orderQty = parseFloat($(this).val());
        var order_by = $(this).closest('tr').find('.orderBy');
        var amount = $(this).closest('tr').find('.amount');
        var nunitCost = parseFloat($(this).closest('tr').find('.nunitcost').text());
        
        var nPack = parseFloat($(this).closest('tr').find('.npack').val());
        
        // console.log('npack: '+ nPack+' type of: '+typeof nPack+' ');
        
        if(order_by.val() == 'case'){
            
            var amountVal = nunitCost * orderQty * nPack;
            
            // console.log(amountVal);
            
            amount.val(amountVal.toFixed(2));
            
        } else if(order_by.val() == 'unit') {
            
            var amountVal = nunitCost * orderQty;
            
            amount.val(amountVal.toFixed(2));
            
        } else {
            
            amount.val(0);
        }
        
        if(amount.val() === 'NaN'){amount.val(0);}
        
        $(this).parents('tr').find("input[type='checkbox']").prop('checked', true);
    });


    $(document).on('change', '.orderBy', function(event) {
        var order_by = $(this);
        var orderQty = parseFloat($(this).closest('tr').find('.orderQty').val());
        var amount = $(this).closest('tr').find('.amount');
        var nunitCost = parseFloat($(this).closest('tr').find('.nunitcost').text());
        
        var nPack = parseFloat($(this).closest('tr').find('.npack').val());
        
        // console.log('npack: '+ nPack+' type of: '+typeof nPack+' ');
        
        if(order_by.val() == 'case'){
            
            var amountVal = nunitCost * orderQty * nPack;
            
            // console.log(amountVal);
            
            amountVal = Math.round(amountVal * 100) / 100;
            
            amount.val(amountVal.toFixed(2));
            
        } else if(order_by.val() == 'unit') {
            
            var amountVal = nunitCost * orderQty;
            
            amountVal = Math.round(amountVal * 100) / 100;
            
            amount.val(amountVal.toFixed(2)); 
            
        } else {
            
            amount.val(0.00);
        }
        
        if(amount.val() === 'NaN'){amount.val(0);}
        
        $(this).parents('tr').find("input[type='checkbox']").prop('checked', true);
    });
    
    /*$(document).on('keyup', '.amount', function(event) {
        var order_by = $(this).closest('tr').find('.orderBy');
        var orderQty = parseFloat($(this).closest('tr').find('.orderQty').val());
        var amount = $(this);
        var nunitCost = parseFloat($(this).closest('tr').find('.nunitcost').text());
        
        var nPack = parseFloat($(this).closest('tr').find('.npack').val());
        
        // console.log('npack: '+ nPack+' type of: '+typeof nPack+' ');
        
        if(order_by.val() == 'case'){
            
            var amountVal = nunitCost * orderQty * nPack;
            
            // console.log(amountVal);
            
            amount.val(amountVal)
            
        } else if(order_by.val() == 'unit') {
            
            var amountVal = nunitCost * orderQty;
            
            amount.val(amountVal)
            
        } else {
            
            amount.val(0);
        }
        
        if(amount.val() === 'NaN'){amount.val(0);}
    });*/   


    $(document).on('change','#selectBy', function(){

        var selectBy = $(this).val();
        // console.log(selectBy);
        
        $('.inputBox').hide();
        
        switch(selectBy) {
        
            case 'm':
                $('#divMonth').show();
                break;
                
            case 'y':
                $('#divYear').show();
                break;
                
            case 'c':
                $('#divCustom').show();
                break;
                
            default:
                $('#divWeek').show();
                break;
        } 
        
    });


    $(document).ready(function() {
      
      
      
      
      /*$('#customDateRange').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'M/DD hh:mm A'
        }
      });*/
      
            
            var is_custom_date = false;
            var from = "{{ $data['from'] ?? '' }}";
            if( from !== '' ){
                var start = from;
                is_custom_date = true;
            }else{
                var start = moment().subtract(1, 'month').startOf('day');
            }
            // var end = moment().subtract(1, 'month').endOf('month');
            
            var to = "{{ $data['to'] ?? '' }}";
            if( to !== '' ){
                var end = to;
                is_custom_date = true;
            }else{
                var end = moment().subtract(1, 'month').endOf('day');
            }
            
            
            // console.log(start);
            moment().format("dddd, MMMM Do YYYY, h:mm:ss a");
            var today = moment();
            minDate = new Date(today);
            // var minDate = new Date();
            var pastYear = minDate.getFullYear() - 1;
            
            // console.log('Past Year: '+pastYear);
            
            minDate = '01/01/'+pastYear;
            
            // minDate.setFullYear(pastYear);

            // var minDate = minDate.setFullYear(minDate.getFullYear() - 1);
            
            // console.log(minDate);
            
            function cb(start, end) {
                
                if(is_custom_date !== true){
                    $('#customDateRange').html(start.format('MMMM D, YYYY HH:mm') + ' - ' + end.format('MMMM D, YYYY HH:mm'));
                } else {
                    $('#customDateRange').html(start+' - '+end);
                }
            }
            
            // $('#daterange').daterangepicker({ startDate: '03/05/2005', endDate: '03/06/2005' });
        
            $('#customDateRange').daterangepicker({
                timePicker: true,
                startDate: start,
                endDate: end,
                maxDate: today,
                minDate: minDate, 
                locale: {
                  format: 'M/D/YYYY hh:mm A'
                },
                ranges: {
                   'Today': [moment().format('M/D/YYYY'), moment()],
                   'Yesterday': [moment().subtract(1, 'days').format('M/D/YYYY'), moment().subtract(1, 'days').format('M/D/YYYY 11:59 PM')],
                   'Last 7 Days': [moment().subtract(6, 'days').format('M/D/YYYY'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days').format('M/D/YYYY'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);
        
            cb(start, end);
        

      
      
    });



   

</script>

<script>
    $(document).on('click', '#continue_export', function(event) {
    //
        $("#save_data").modal('hide');
        // var btnVal = $('#save_receive_check').val();
    
        // if(btnVal === "export"){
        //     $('#export_po_as').modal('show');
            
        //     $('#save_receive_check').val("transfer_to_ro");
        //     $('#save_receive_check').html("Transfer to RO");
        //     $("#meta_div").css('display','block');
        //     $("#search_div").css('display','none');
            
        //     $('#add_item_btn').addClass('btn-info');
        //     $("#add_item_btn").attr('disabled',false);
        // }
        
        $('#export_po_as').modal('show');
        
        $("#meta_div").css('display','block');
        $("#search_div").css('display','none');
        
        $('#add_item_btn').addClass('btn-info');
        $("#add_item_btn").attr('disabled',false);
    
    });
      
    $(document).on('click', '#invoice_detail', function(event) {
        //
        $("#save_data").modal('hide');
        var btnVal = $('#save_receive_check').val();
    
        // if(btnVal === "export"){
            // $('#export_po_as').modal('show');
            
            // $('#save_receive_check').val("transfer_to_ro");
            // $('#save_receive_check').html("Transfer to RO");
            $("#meta_div").css('display','block');
            $("#search_div").css('display','none');
            
            // $('#add_item_btn').addClass('btn-info');
            // $("#add_item_btn").attr('disabled',false);
        // }
    
    });
    
   
  
</script>

<!-- Warnig to save data -->
<div class="modal fade" id="save_data" role="dialog" style="z-index: 9999;">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <h4>
              Only the saved data will be exported. Do you want to continue?
          </h4>
          <span>Click <input type="button" class="btn btn-link" id="invoice_detail" value="here" style="padding: 0;"> to enter invoice details</span>
        </div>
        
      </div>
      <div class="modal-footer" style="border-top:none;">
        <div class="col-md-12 text-center">
            <div class="col-md-6">
                <input type="button" class="btn btn-success col-md-12" id="continue_export" value="Yes">
            </div>
            <div class="col-md-6">
                <input type="button" class="btn btn-success col-md-12" id="stop_export"  data-dismiss="modal" value="No">
            </div>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>-->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Export PO as pdf, excel, csv, email -->
<div class="modal fade" id="export_po_as" role="dialog" style="z-index: 9999;">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <h4>
              Export Purchase Order as
              <i class="fa fa-info-circle" title="Only the saved data will be reflected" style="cursor: pointer; color:blue;font-size: 10px;position: absolute;margin-left: 10px;margin-top: -5px;"></i>
          </h4>
        </div>
        
      </div>
      <div class="modal-footer" style="border-top:none;">
        <div class="col-md-12 text-center">
            <div class="col-md-3">
                <input type="button" class="btn btn-success col-md-12" id="export_po_as_pdf" value="PDF">
            </div>
            <div class="col-md-3">
                <input type="button" class="btn btn-success col-md-12" id="export_po_as_excel" value="Excel">
            </div>
            <div class="col-md-3">
                <input type="button" class="btn btn-success col-md-12" id="export_po_as_csv" value="CSV">
            </div>
            <div class="col-md-3">
                <input type="button" class="btn btn-success col-md-12" id="export_po_as_email" value="Email">
            </div>
        
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>-->
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).on('click', '#export_po_as_pdf', function(event) {
        event.preventDefault();
        
        $("div#divLoading").addClass('show');
        $('#export_po_as').modal('hide');
        var export_as_pdf_url = '<?php echo $data['export_as_pdf']; ?>';
       
        export_as_pdf_url = export_as_pdf_url.replace(/&amp;/g, '&');
    
        var req = new XMLHttpRequest();
        req.open("GET", export_as_pdf_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {
    
            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "Purchase_Order.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "Purchase_Order.pdf";
    
              // append the link to the document body
              document.body.appendChild(link);
              link.click();
            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();
  });
  
  $(document).on('click', '#export_po_as_csv', function(event) {
      event.preventDefault();

      $("div#divLoading").addClass('show');
      $('#export_po_as').modal('hide');

      var csv_export_url = '<?php echo $data['export_as_csv']; ?>';
      csv_export_url = csv_export_url.replace(/&amp;/g, '&');

      $.ajax({
        url : csv_export_url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
        type : 'GET',
      }).done(function(response){
        
        const data = response,
        fileName = "purchase_order.csv";
        saveData(data, fileName);
        
        $("div#divLoading").removeClass('show');
      });
  });
  
  $(document).on('click', '#export_po_as_email', function(event) {
          event.preventDefault();
        
          $("div#divLoading").addClass('show');
          $('#export_po_as').modal('hide');
        
          var mail_export_url = '<?php echo $data['export_as_email']; ?>';
          mail_export_url = mail_export_url.replace(/&amp;/g, '&');
        
          $.ajax({
            url : mail_export_url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
            type : 'GET',
            dataType: 'JSON',
          }).done(function(response){

            if(response.status === 'true'){
                $('#success_alias').html('<strong>'+response.message+'</strong>');
                $('#successModal').modal('show');
            } else {
                $('#error_alias').html('<strong>'+response.message+'</strong>');
                $('#errorModal').modal('show');
            }
            
            $("div#divLoading").removeClass('show');
          });
  });
  
  $(document).on('click', '#export_po_as_excel', function(event) {
      event.preventDefault();

      $("div#divLoading").addClass('show');
      $('#export_po_as').modal('hide');

      var excel_export_url = '<?php echo $data['export_as_excel']; ?>';
      excel_export_url = excel_export_url.replace(/&amp;/g, '&');

      $.ajax({
        url : excel_export_url,
        headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
            },
        type : 'GET',
      }).done(function(response){
        
        const data = response,
        fileName = "purchase_order.xls";
        saveData(data, fileName);
        
        $("div#divLoading").removeClass('show');
      });
  });
  
    $(document).keypress(
        function(event){
        if (event.which == '13') {
          event.preventDefault();
        }
    });
</script>


@endsection