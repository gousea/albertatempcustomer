@extends('layouts.master')

@section('title')

    Promotion

@endsection

@section('main-content')

<div id="content">
    <style type="text/css">
        .error {
            color:#f56b6b;
            border-color: #f56b6b;
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
        
        .datetime-block{background:gainsboro;};
        .row{margin-left:0px !important;margin-right:0px !important;};
        
        .panel-default .panel-heading {background:#F05430;!important}
    
        .select2-container .select2-selection--single {
            height: 32px !important;
        }
        /*******select2******/
        .select2-results__options{
            font-size:11px !important;
        }
        .select2-selection__rendered {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }
        /********************/
        .form-control{border-radius: 4px !important;height: 32px !important;}
        label {font-size: 12px !important;padding-right: 0px !important;}
        .radio-group{padding-top:6px;}
        .table.table-bordered.table-striped.table-hover thead > tr{
            background: #03a9f4 none repeat scroll 0 0 !important;
        }
        
        table#item_listing th td{
            table-layout: fixed;
            word-wrap:break-word;
        }
        
        /* table tbody tr:nth-child(even) td{*/
        /*	background-color: #f05a2814;*/
        /*}*/
        
        
        
    </style>

    <div class="container-fluid">

        <div class="page-header">
            <div class="container-fluid">
            <!-- <h1>Item Group</h1> -->
            
            </div>
        </div>
         
       
        
        @if (session()->has('message'))
      
          <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>      
        @endif
    
        @if (session()->has('error'))
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{session()->get('error')}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>      
        @endif
        
        <div class="panel panel-default">
            <div class="panel-heading head_title" >
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <b> Promotion </b></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $data['action'] ?>" method="post" enctype="multipart/form-data" id="form-promotion" class="form-horizontal">
                    @csrf
                    <?php if(session()->get('hq_sid') == 1){ ?>
                        <input type="hidden" name="stores_hq" value="" id="hidden_store_hq_val"  >
                    <?php } ?>
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="input-promotion-name"> Promotion Name </label>
                                            <div class="col-md-8">
                                                <input type="text" name="promotion_name" autocomplete="off" id="promotion_name" maxlength="100" value="<?php echo isset($data['prom_id']) ? $data['promotion_name'] : ''; ?>" placeholder="Promotion Name" class="form-control disabled-text" />
                                                <div id="promotion_name_validate"></div>
                                                @if (isset($data['error_promotion_name'])) 
                                                    <div class="text-danger">{{ $data['error_promotion_name'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="input-promotion-code">Promotion Code</label>
                                            <div class="col-md-8">
                                                <input type="text" name="promotion_code" autocomplete="off" id="promotion_code" maxlength="10" value="<?php echo isset($data['prom_id']) ? $data['promotion_code'] : ''; ?>" placeholder="Promotion Code" <?php echo isset($data['prom_id']) &&  $data['prom_id'] ? "readonly" : ""; ?>  class="form-control disabled-text" />
                                                <div id="promotion_code_validate"></div>
                                                @if (isset($data['error_promotion_code'])) 
                                                    <div class="text-danger"> {{ $data['error_promotion_code'] }}</div>
                                                @endif
                                                <div class="text-danger" id="check_promotion_code" style="display:none"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="entry_promotion_type">Promotion Type</label>
                                            <div class="col-md-8">
                                            
                                                <select name="<?php echo isset($data['prom_id']) && $data['prom_id'] ? 'promotion_type_select' : 'promotion_type'; ?>" id="<?php  echo isset($data['prom_id']) && $data['prom_id'] ? 'promotion_type_select' : 'promotion_type'; ?>" <?php echo isset($data['prom_id']) &&  $data['prom_id'] ? "disabled" : ""; ?> class="form-control disabled-select"> 
                                                <!-- <select name="promotion_type" id="promotion_type" class="form-control disabled-select" aria-invalid="false"> -->
            
                                                    <option value="">--Select Type--</option>
                                                    @if(!empty($data['promotion_types']))
                                                        @foreach (  $data['promotion_types'] as $val)
                                                            <?php $value = $val['prom_type_id']; $type_name = $val['prom_description']; ?>
                                                            @if($value != 1 && $value != 10)
                                                                <option style="background-color:grey;color:white;" disabled value="{{ $value }}" <?php echo isset($data['promotion_type']) && $data['promotion_type'] == $value ? 'selected' : '';?>>{{$type_name}}</option>
                                                            @else
                                                                <option value="{{ $value }}" <? echo isset($data['promotion_type']) && $data['promotion_type'] == $value ? 'selected' : '';?>>{{$type_name}}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                                
                                                @if(isset($data['prom_id']))
                                                <input type="hidden" id="promotion_type" name="promotion_type" value="{{$data['promotion_type']}}">

                                                @endif
                                                
                                                <div id="promotion_type_validate"></div>
                                                @if (isset($data['error_promotion_type']))
                                                    <div class="text-danger">{{ $data['error_promotion_type'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="entry_promotion_category">Promotion Category</label>
                                            <div class="col-md-8">
                                                <select name="promotion_category" id="promotion_category" class="form-control disabled-select">
                                                    <option value="">--Select Category--</option>
                                                    <option value="Time Bound" <?php echo isset($data['promotion_category']) && $data['promotion_category'] == 'Time Bound' ? 'selected' : '';?> >Time Bound</option>
                                                    <option value="Stock Bound" <?php echo isset($data['promotion_category']) && $data['promotion_category'] == 'Stock Bound' ? 'selected' : '';?>>Stock Bound</option>
                                                    <option value="Open Ended" <?php echo isset($data['promotion_category']) && $data['promotion_category'] == 'Open Ended' ? 'selected' : '';?>>Open Ended (On Going)</option>
                                                </select>
                                                
                                                <!--@if(isset($data['prom_id']))   
                                                        echo "<input type='hidden' id='promotion_category' name='promotion_category' value='".$data['promotion_type']."'>";
                                                    @endif-->
                                                
                                                <div id="promotion_category_validate"></div>
                                                @if (isset($data['error_promotion_category']))
                                                    <div class="text-danger">{{ $data['error_promotion_category'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div  style="padding-bottom: 10px;">
                                    <div class="row" id="datetime_div" hidden>
                                        <div class="col-md-4 col-md-offset-2 datetime-block">
                                            <div class="required form-group">
                                                <label class="col-md-5 control-label" for="input-promotion-name">Promotion From Date</label>
                                                <div class="col-md-7">
                                                    <input type="text" readonly name="promotion_from_date" id="promotion_from_date" maxlength="50" value="<?php echo isset($data['promotion_from_date']) ? $data['promotion_from_date'] : ''; ?>" placeholder="" class="form-control" />
                                                    <div id="promotion_from_date_validate"></div>
                                                    @if (isset($data['error_promotion_from_date']))
                                                        <div class="text-danger">{{ $data['error_promotion_from_date'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 datetime-block">
                                            <div class="required form-group">
                                                <label class="col-md-5 control-label" for="input-promotion-to-date">Promotion To Date</label>
                                                <div class="col-md-7">
                                                    <input type="text" readonly name="promotion_to_date" id="promotion_to_date" maxlength="50" value="<?php echo isset($data['promotion_to_date']) ? $data['promotion_to_date'] : ''; ?>" placeholder="<?php // echo $entry_promotion_to_date; ?>"  class="form-control" />
                                                    <div id="promotion_to_date_validate"></div>
                                                    @if (isset($data['error_promotion_to_date']))
                                                        <div class="text-danger">{{$data ['error_promotion_to_date'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="padding-bottom: 10px;">
                                    <div id="time_div" hidden>
                                        <div class="row">
                                            <div class="col-md-4 col-md-offset-2 datetime-block">
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label" for="input-promotion-from-time">Promotion From Time </label>
                                                    <div class="col-md-7">
                                                        <input type="text" readonly name="promotion_from_time" id="promotion_from_time" maxlength="50" value="<?php echo isset($data['promotion_from_time']) ? $data['promotion_from_time'] : ''; ?>" placeholder="<?php //echo $entry_promotion_from_time; ?>"  class="form-control" />
                                                        @if (isset($data['error_promotion_from_time'])) 
                                                            <div class="text-danger">{{ $data['error_promotion_from_time'] }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 datetime-block">
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label" for="input-promotion-to-time">Promotion To Time</label>
                                                    <div class="col-md-7">
                                                        <input type="text" readonly name="promotion_to_time" id="promotion_to_time" maxlength="50" value="<?php echo isset($data['promotion_to_time']) ? $data['promotion_to_time'] : ''; ?>" placeholder="<?php //echo $entry_promotion_to_time; ?>" class="form-control" />
                                                        @if (isset($data['error_promotion_to_time'])) 
                                                            <div class="text-danger">{{ $data['error_promotion_to_time']}}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mfg_desc" id="" hidden>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="input-promotion-name">MFG Promotion Desc</label>
                                                <div class="col-md-8">
                                                    <input type="text" autocomplete="off"  name="mfg_prom_desc" id="mfg_prom_desc" maxlength="50" value="<?php echo isset($mfg_prom_desc) ? $mfg_prom_desc : ''; ?>" placeholder="Manufacturer Promotion Description"  class="form-control" />
                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="input-promotion-to-date">MFG BuyDown Desc</label>
                                                <div class="col-md-8">
                                                    <input type="text" autocomplete="off" name="mfg_buydown_desc" id="mfg_buydown_desc" maxlength="50" value="<?php echo isset($mfg_buydown_desc) ? $mfg_buydown_desc : ''; ?>" placeholder="Manufacturer BuyDown Description"  class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row mfg_desc" id="" hidden>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="input-promotion-name">MFG Multipack Desc</label>
                                                <div class="col-md-8">
                                                    <input type="text" autocomplete="off" name="mfg_multipack_desc" id="mfg_multipack_desc" maxlength="50" value="<?php echo isset($mfg_multipack_desc) ? $mfg_multipack_desc : ''; ?>" placeholder="Manufacturer Multipack Description"  class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="input-promotion-name">MFG Discount</label>
                                                <div class="col-md-8">
                                                    <input type="text" autocomplete="off" name="mfg_discount" id="mfg_discount" maxlength="50" value="<?php echo isset($mfg_discount) ? $mfg_discount : ''; ?>" placeholder="Manufacturer Discount"  class="form-control decimal_numbers" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-md-6" id="div_period" hidden>
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="input-promotion-period">promotion period</label>
                                            <div class="col-md-8">
                                                <select name="promotion_period" id="promotion_period" class="form-control">
                                                    <option value="None" <?php echo isset($data['promotion_period']) && $data['promotion_period'] == 'None' ? 'selected' : '';?>>None</option>
                                                    <option value="Daily" <?php echo isset($data['promotion_period']) && $data['promotion_period'] == 'Daily' ? 'selected' : '';?>>Daily</option>
                                                    <option value="Weekly" <?php echo isset($data['promotion_period']) && $data['promotion_period'] == 'Weekly' ? 'selected' : '';?>>Weekly</option>
                                                    <!--<option value="Forthnightly" >Forthnightly</option>
                                                    <option value="Monthly" >Monthly</option>
                                                    <option value="Bi-Monthly">Bi-Monthly</option>
                                                    <option value="Quarterly" >Quarterly</option>-->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6" id="buyQty" hidden>
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="input-promotion-buy-qty">Promotion Buy Qty </label>
                                            <div class="col-md-8">
                                                <input type="number" autocomplete="off" name="promotion_buy_qty" id="promotion_buy_qty" maxlength="50" value="<?php echo isset($data['promotion_buy_qty']) ? $data['promotion_buy_qty'] : ''; ?>" placeholder="<?php //echo $entry_promotion_buy_qty; ?>"  class="form-control" />
                                                <div id="promotion_buy_qty_validate"></div>
                                                @if (isset($data['error_promotion_buy_qty']))
                                                    <div class="text-danger">{{$data['error_promotion_buy_qty']}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6" id="discOptions" hidden>
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="input-promotion-buy-qty">Promotion Disc Options</label>
                                            <div class="col-md-8">
                                                <select name="promotion_disc_options" id="promotion_disc_options" class="form-control">
                                                    <option value="Nth" <?php echo isset($data['promotion_disc_options']) && $data['promotion_disc_options'] == 'Nth' ? 'selected' : '';?>>For n&#x1D57;&#x02B0; item</option>
                                                    <option value="Each" <?php echo isset($data['promotion_disc_options']) && $data['promotion_disc_options'] == 'Each' ? 'selected' : '';?>>For each item</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6" id="billValue" hidden>
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="input-promotion-bill-value">Promotion Bill Value</label>
                                            <div class="col-md-8">
                                                <input type="text" autocomplete="off" name="promotion_bill_value" id="promotion_bill_value" maxlength="50" value="<?php echo isset($data['promotion_bill_value']) ? $data['promotion_bill_value'] : ''; ?>" placeholder="<?php //echo $entry_promotion_bill_value; ?>" class="form-control decimal_numbers" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    @if(isset($data['promotion_category']) && $data['promotion_category'] == 'Stock Bound')
                                        <div class="col-md-6" id="item_qty_limit_div" style="display:none;">
                                            <div class="required form-group">
                                                <label class="col-md-4 control-label" for="input-promotion-item-qty-limit">Promotion Item Quantity Limit</label>
                                                <div class="col-md-4">
                                                    <input type="number" autocomplete="off" name="promotion_item_qty_limit" id="promotion_item_qty_limit" maxlength="50" value="<?php echo isset($data['promotion_item_qty_limit']) ? $data['promotion_item_qty_limit'] : ''; ?>" placeholder="<?php echo $entry_promotion_item_quantity_limit??''; ?>"  class="form-control" />
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" autocomplete="off" name="promotion_item_qty_limit_bal" id="promotion_item_qty_limit_bal" maxlength="50" readonly value="Balance Qty (<?php echo isset($data['promotion_item_qty_limit_bal']) ? $data['promotion_item_qty_limit_bal'] : '-'; ?>)"  class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    @else 
                                        <div class="col-md-6" id="item_qty_limit_div" style="display:none;">
                                            <div class="required form-group">
                                                <label class="col-md-4 control-label" for="input-promotion-item-qty-limit">Promotion Item Quantity Limit</label>
                                                <div class="col-md-8">
                                                    <input type="number" autocomplete="off" name="promotion_item_qty_limit" id="promotion_item_qty_limit" maxlength="50" value="<?php echo isset($data['promotion_item_qty_limit']) ? $data['promotion_item_qty_limit'] : ''; ?>" placeholder="<?php //echo $entry_promotion_item_quantity_limit; ?>"  class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6" id="div_slab_price" hidden>
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="entry_promotion_slab_price">Promotion Slab Price</label>
                                            <div class="col-md-8">
                                                <input type="text" autocomplete="off" name="promotion_slab_price" id="promotion_slab_price" maxlength="50" value="<?php echo isset($data['promotion_slab_price']) ? $data['promotion_slab_price'] : ''; ?>" placeholder="<?php //echo $entry_promotion_slab_price; ?>"  class="form-control decimal_numbers" />
                                                @if (isset($data['error_promotion_slab_price'])) 
                                                    <div class="text-danger">{{$data['error_promotion_slab_price']}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6" id="div_discount_type">
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="entry_promotion_discount_type">Promotion Discount Type</label>
                                            <div class="col-md-8">
                                                <select name="promotion_discount_type" id="promotion_discount_type" class="form-control">
                                                    <option value="1" <?php echo isset($data['promotion_discount_type']) && $data['promotion_discount_type'] == '1' ? 'selected' : '';?>>Percentage (%)</option>
                                                    <option value="2" <?php echo isset($data['promotion_discount_type']) && $data['promotion_discount_type'] == '2' ? 'selected' : '';?>>Dollars ($)</option>
                                                    <option value="3" <?php echo isset($data['promotion_discount_type']) && $data['promotion_discount_type'] == '3' ? 'selected' : '';?>>Quantity (Same Item)</option>
                                                    <option value="4" <?php echo isset($data['promotion_discount_type']) && $data['promotion_discount_type'] == '4' ? 'selected' : '';?>>Quantity (Other Item)</option>
                                                    <option value="5" <?php echo isset($data['promotion_discount_type']) && $data['promotion_discount_type'] == '5' ? 'selected' : '';?>>% on Least Priced item</option>
                                                    <option value="6" <?php echo isset($data['promotion_discount_type']) && $data['promotion_discount_type'] == '6' ? 'selected' : '';?>>$ on Least Priced item</option>
                                                </select>
                                                @if (isset($data['error_promotion_discount_type']))
                                                    <div class="text-danger">{{$data['error_promotion_discount_type']}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" id="div_discount">
                                        <div class="required form-group" id="div_discount_required">
                                            <label class="col-md-4 control-label" for="entry_promotion_discounted_value">Promotion Discounted Value</label>
                                            <div class="col-md-8">
                                                <input type="text" autocomplete="off" name="promotion_discounted_value" id="promotion_discounted_value" maxlength="50" value="<?php echo isset($data['promotion_discounted_value']) ? $data['promotion_discounted_value'] : ''; ?>" placeholder="<?php //echo $entry_promotion_discounted_value; ?>"  class="form-control decimal_numbers" />
                                                @if (isset($data['error_promotion_discounted_value']))
                                                    <div class="text-danger">{{$data['error_promotion_discounted_value']}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="div_addl_discount">
                                            <label class="col-md-4 control-label" for="input-phone">
                                                <span data-toggle="tooltip" title="" data-original-title="Discount for Registered Customers" id="spanAddlDisc">Promotion Addl Discount</span>
                                            </label>
                                            <!--<label class="col-md-4 control-label" for="entry_promotion_addl_discount"><?php //echo $entry_promotion_addl_discount; ?></label>-->
                                            <div class="col-md-8">
                                                <input type="text" autocomplete="off" name="promotion_addl_discount" id="promotion_addl_discount" maxlength="50" value="<?php echo (isset($data['promotion_addl_discount']) && $data['promotion_addl_discount'] != 0) ? $data['promotion_addl_discount'] : ''; ?>" placeholder="<?php //echo $entry_promotion_addl_discount; ?>"  class="form-control decimal_numbers" />
                                                 @if (isset($data['error_promotion_addl_discount'])) 
                                                    <div class="text-danger">{{$data['error_promotion_addl_discount']}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                                <div class="row">
                                    <div class="col-md-6" id="div_discount_limt1" style="display: none;">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="input-promotion-discount_limit">Promotion_discount_limit</label>
                                            <div class="col-md-8">
                                                <input type="text" autocomplete="off" name="promotion_discount_limit" id="promotion_discount_limit" maxlength="50" value="<?php echo (isset($data['promotion_discount_limit']) && $data['promotion_discount_limit'] != 0) ? $data['promotion_discount_limit'] : ''; ?>" placeholder="<?php //echo $entry_promotion_discount_limit; ?>" class="form-control decimal_numbers" />
                                                @if (isset($data['error_promotion_discount_limit'])) 
                                                    <div class="text-danger">{{$data['error_promotion_discount_limit']}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6" id="div_item_type">
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="input-promotion-item-type">Promotion Item Type</label>
                                            <div class="col-md-3 radio-group">
                                                <input type="radio" name="promotion_same_itme" id="id_same_item" value="Same Item" <?php echo isset($data['promotion_same_itme']) && $data['promotion_same_itme'] == "Same Item" ? 'checked' : ''?> required > <b>Same Item</b>
                                            </div>
                                            <div class="col-md-3 radio-group">
                                                <input type="radio" name="promotion_same_itme" id="id_group_item" value="Group Item" <?php echo isset($data['promotion_same_itme']) && $data['promotion_same_itme'] == "Group Item" ? 'checked' : ''?> > <b> Group Item</b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="div_item_state" hidden>
                                        <div class="required form-group">
                                            <label class="col-md-4 control-label" for="input-promotion-item-state">Promotion Item State</label>
                                            <div class="col-md-3 radio-group">
                                                <input type="radio" name="promotion_item_state" value="I" required > <b>Include</b>
                                            </div>
                                            <div class="col-md-3 radio-group">
                                                <input type="radio" name="promotion_item_state" value="E" > <b>Exclude</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="required form-group">
                                            <label class="col-sm-4 control-label" for="qty_limit">Quantity Limit</label>
                                            <div class="col-sm-6">
                                                <input type='text' class="form-control" name="qty_limit" autocomplete="off" id="qty_limit" placeholder="Default: 0" value="<?php echo (isset($data['qty_limit']) && $data['qty_limit'] !== null)?$data['qty_limit']:0; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="required form-group">
                                            <label class="col-sm-4 control-label" for="allow_reg_price">
                                                <span data-toggle="tooltip" title="" data-original-title="Will be enabled if the Quantity Limit is a number and is greater than zero (Default: Yes)" id="">
                                                    Allow Regular Price
                                                </span>
                                            </label>
                                            <div class="col-sm-6">
                                                <select name="allow_reg_price" id="allow_reg_price" class="form-control" >
                                                    <option value="Y" <?php echo ((isset($data['allow_reg_price'])) && ($data['allow_reg_price'] == 'Y')) ? 'selected' : ''?>>Yes</option>
                                                    <option value="N" <?php echo ((isset($data['allow_reg_price'])) && ($data['allow_reg_price']== 'N')) ? 'selected' : ''?>>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="required form-group">
                                            <label class="col-sm-4 control-label" for="entry_promotion_status">Promotion Status</label>
                                            <div class="col-sm-6">
                                                <select name="promotion_status" id="promotion_status" class="form-control ">
                                                    <option value="Active" <?php echo isset($data['promotion_status']) && $data['promotion_status'] == 'Active' ? 'selected' : ''?>>Active</option>
                                                    <option value="Closed" <?php echo isset($data['promotion_status']) && $data['promotion_status'] == 'Closed' ? 'selected' : ''?>>Closed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" id="div_refresh_values" hidden>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <input  type="button" id="button_refresh_values" class="btn btn-primary" value="Refresh Values">
                                    </span>
                                    <div class="col-md-4"></div>
                                </div>
                                
                            </div>
                        </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row" id="div_item_listing">
                                        <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <div>
                                                <p class="text-danger" style="padding-left: 15px; font-size: 10px;">* MAX 2000 no. of Items can be Add per Invoice.</p>
                                            </div>
                                            <b>
                                                <span>
                                                    <lable class="control-label">Choose items for Promotion</lable> 
                                                </span> 
                                                <span style="float:right;color:red">
                                                    <lable class="required control-label">NOTE: New search will clear existing search and selected items</lable>
                                                </span>
                                            </b>
                                            <table id="item_listing" class="table table-bordered table-striped table-hover" style="font-size:9px; width:400px;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" style="color: black;"/></th>
                                                        <th style="width: 70px;">ITEM NAME</th>
                                                        <th style="width: 70px">SKU</th>
                                                        <th style="width:195px;">PRICE</th>
                                                        <th style="width:55px;">UNIT</th>
                                                        <th style="width:50px;">SIZE</th>
                                                        <!--<th>TYPE</th>-->
                                                        <th style="width:90px;">DEPT.</th>
                                                        <th style="width:90px;">CATEGORY</th>
                                                        <th style="width:90px;">SUB CAT</th>
                                                        <!--<th>SUPPLIER</th>
                                                        <th>MFR</th>-->
                                                        <th style="width:25px;">PROM</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="text-center">
                                            <span>
                                                <input type="button" id="add_to_buy_items" class="btn btn-primary" value="Add to Promotions">
                                            </span>
                                            <span style="padding-left:5px;"><input  type="button" id="add_to_discounted_items" class="btn btn-primary" value="Add to Discounted Items"></span>
                                            <span style="padding-left:5px;"></span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <table id="item_listing2" class="table table-bordered table-striped table-hover" style="font-size:11px;">
                                                <thead>
                                                    <tr>
                                                        <th style="vertical-align: inherit;" rowspan="2" style="width: 1px;" class="text-center">
                                                            <input type="checkbox" id="check_all_buy_items" onclick="$('input[name*=\'added_promotion_items\']').prop('checked', this.checked);" style="color: black;"/>
                                                        </th>
                                                        <th style="vertical-align: inherit;" rowspan="2">ITEM NAME</th>
                                                        <th style="vertical-align: inherit;" rowspan="2">SKU</th>
                                                        <th style="vertical-align: inherit;" rowspan="2">QOH</th>
                                                        <th style="vertical-align: inherit;" rowspan="2">LAST COST</th>
                                                        <th style="vertical-align: inherit;" rowspan="2">SELLING PRICE</th>
                                                        <th style="vertical-align: inherit;" rowspan="2" id="item_listing2_buy_down" hidden>BUY DOWN</th>
                                                        <th colspan="2" style="text-align:center" >NEW PRICE</th>
                                                        <th colspan="2" style="text-align:center">PROMOTION</th>
                                                    </tr>
                                                    <tr>
                                                        <th>OTHERS</th>
                                                        <th>REG.CUSTOMERS</th>
                                                        <th>OTHERS</th>
                                                        <th>REG.CUSTOMERS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="padding-bottom:10px;">
                                            <input type="button" class="btn btn-danger" id="remove_buy_items" value="Remove Promotion Items">
                                        </div>
                                    </div>
                                    
                                </div>
                                    <div class="row required form-group" id="discountedItem" hidden>
                                    <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <table id="item_listing3" class="table table-bordered table-striped table-hover" style="font-size:11px;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 1px;" class="text-center"><input type="checkbox" id="check_all_discounted_items" onclick="$('input[name*=\'promotion_discounted_item\']').prop('checked', this.checked);" style="color: black;"/></th>
                                                        <th>ITEM NAME</th>
                                                        <th>SKU</th>
                                                        <th>QOH</th>
                                                        <th>LAST COST</th>
                                                        <th>SELLING PRICE</th>
                                                        <th>NEW PRICE</th>
                                                        <th>PROMOTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <input  type="button" class="btn btn-danger" id="remove_discounted_items" value="Remove Discounted Items">
                                    </div>
                                    
                                </div>
                                    <div class="row">
                                    <div class="col-md-12" id="div_customers" hidden>
                                        <div class="required form-group">
                                            <label class="col-md-2 control-label" id="label_promotion_customers" for="input-promotion-customers">Customers</label>
                                            <div class="col-md-10">
                                                <span id="customers_span" style="">
                                                    <select name="promotion_customers[]" id="promotion_customers" class="form-control" multiple="multiple" style="width:100%;">
                                                        @if(!empty($selected_promotion_customers)) 
                                                        
                                                            @foreach($selected_promotion_customers as $value){
                                                                echo '<option value="'.$value['id'].'" selected>'.$value['text'].'</option>';
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </span>
                                                @if (isset($data['error_promotion_customers'])) 
                                                    <div class="text-danger">{{$data['error_promotion_customers']}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div id="add_discount" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                                            <h4 class="modal-title">Add Discount for items</h4>
                                        </div>
                                        <div class="modal-body">
                                        <table border="1" id="items_dicount_table" class="table table-responisve" style="overflow-y: scroll;height: 200px;display: block;width:100%;">
                                            <thead>
                                                <tr style="8fbb6c">
                                                    <!--<th style="width: 1px;color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>-->
                                                    <td style="width: 60%;text-align: center;">Item</td>
                                                    <td style="width: 10%;text-align: center;">cost price</td>
                                                    <td style="width: 10%;text-align: center;">unit price</td>
                                                    <td style="text-align: center;">Discount</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="btn btn-success" id="save_discounts" class="close" data-dismiss="modal" name="save_discounts" value="Continue">Save</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <span id="submit_promotion" data-toggle="tooltip" class="btn btn-primary" title="save">Save</span>
                                <!--<button id="submit_promotion" class="btn btn-primary"><i class="fa fa-save"> Save</i></button>-->
                                <!--<button type="submit" form="form-customer" data-toggle="tooltip" id="submit_promotion" title="save" class="btn btn-primary"><i class="fa fa-save"> Save</i></button>-->
                                <a href="/promotion"  data-toggle="tooltip" title="cancel" class="btn btn-default"><i class="fa fa-reply"></i> Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


<!--- ERROR MODAL STARTS -->
    <div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger text-center">
                    <p id="error_msg">That item is already added !!</p>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                </div> 
            </div>
        </div>
    </div>
<!-- ERROR MODAL ENDS -->

</div>

<?php if(session()->get('hq_sid') == 1){ ?>
    <?php if(isset($data['prom_id'])){ ?>
        <div id="editModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want to update the items:</h4>
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
                    <tbody id="editmodal_stores"></tbody>
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
        <div id="addModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the stores in which you want to add the Promotion:</h4>
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
                    <tbody id="addmodal_stores">
                        
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

<?php if(session()->get('hq_sid') == 1){ ?>
    
<?php } ?>
@endsection


@section ('script_files')
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">



<link type="text/css" href="/javascript/bootstrap-datepicker.css" rel="stylesheet" />
<link type="text/css" href="/stylesheet/select2/css/select2.min.css" rel="stylesheet" />

<script src="/javascript/jquery.dataTables.min.js"></script>
<script src="/javascript/dataTables.bootstrap.min.js"></script>
<script src="/javascript/bootbox.min.js" defer></script>
<script src="/javascript/select2/js/select2.min.js"></script>
<script src="/javascript/bootstrap-datepicker.js" defer></script>
<script src="/javascript/fancyTable/fancyTable.js"></script>

<script src="/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/datatables.min.js"></script>

@endsection


@section('scripts')
<script type="text/javascript">
    var get_department_item_ajax,get_category_ajax,get_category_items_ajax,get_group_items_ajax,get_subcategory_ajax,get_sub_category_items_ajax,get_selected_buy_items_ajax ;
    var itemsAddedToBuyItems = new Array();itemsAddedToDiscountedItems = new Array();
    var existingPromItems = new Array();
    var check_existingPromItems = new Array();
    var get_items_url = "<?php echo $data['get_items_url']; ?>";
        get_items_url = get_items_url.replace(/&amp;/g, '&');
    var get_customers_url = "<?php echo $data['get_customers_url']; ?>";
        get_customers_url = get_customers_url.replace(/&amp;/g, '&');
        
    var get_selected_buy_items_url = "<?php echo $data['get_selected_buy_items_url']; ?>";
        get_selected_buy_items_url = get_selected_buy_items_url.replace(/&amp;/g, '&');
        
    var get_saved_buy_items_ajax_url = "<?php echo $data['get_saved_buy_items_ajax_url']; ?>";
        get_saved_buy_items_ajax_url = get_saved_buy_items_ajax_url.replace(/&amp;/g, '&');
        
    var get_selected_discounted_items_url = "<?php echo $data['get_selected_discounted_items_url']; ?>";
        get_selected_discounted_items_url = get_selected_discounted_items_url.replace(/&amp;/g, '&');
        
    var get_saved_discounted_items_ajax_url = "<?php echo $data['get_saved_discounted_items_ajax_url']; ?>";
        get_saved_discounted_items_ajax_url = get_saved_discounted_items_ajax_url.replace(/&amp;/g, '&');
        
    var clickedAddBuyItems = 0;
        
    $(function () {
        
        // select all and unselect all option in select2 dropdown
        $.fn.select2.amd.define('select2/selectAllAdapter', [
            'select2/utils',
            'select2/dropdown',
            'select2/dropdown/attachBody'
        ], function (Utils, Dropdown, AttachBody) {
        
            function SelectAll() { }
            SelectAll.prototype.render = function (decorated) {
                var self = this,
                    $rendered = decorated.call(this),
                    $selectAll = $(
                        '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-check-square-o"></i> Select All</button>'
                    ),
                    $unselectAll = $(
                        '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-square-o"></i> Unselect All</button>'
                    ),
                    $btnContainer = $('<div style="margin-top:3px;">').append($selectAll).append($unselectAll);
                if (!this.$element.prop("multiple")) {
                    // this isn't a multi-select -> don't add the buttons!
                    return $rendered;
                }
                $rendered.find('.select2-dropdown').prepend($btnContainer);
                $selectAll.on('click', function (e) {
                    var $results = $rendered.find('.select2-results__option[aria-selected=false]');
                    $results.each(function () {
                        self.trigger('select', {
                            data: $(this).data('data')
                        });
                    });
                    self.trigger('close');
                });
                $unselectAll.on('click', function (e) {
                    var $results = $rendered.find('.select2-results__option[aria-selected=true]');
                    $results.each(function () {
                        self.trigger('unselect', {
                            data: $(this).data('data')
                        });
                    });
                    self.trigger('close');
                });
                return $rendered;
            };
        
            return Utils.Decorate(
                Utils.Decorate(
                    Dropdown,
                    AttachBody
                ),
                SelectAll
            );
        
        });
        
        $('#promotion_from_date').datepicker({format: 'mm-dd-yyyy',autoclose: true});
        $('#promotion_to_date').datepicker({format: 'mm-dd-yyyy',autoclose: true});
        <?php // if(!$data['prom_id']) {?>
            // $('#promotion_from_date').datepicker('setDate', 'today');
            // $('#promotion_to_date').datepicker('setDate', 'today');
        <?php // }?>
        $('#promotion_from_time').datetimepicker({pickDate: false,format: 'hh:mm A',autoclose: true});
        $('#promotion_to_time').datetimepicker({pickDate: false,format: 'hh:mm A',autoclose: true});
        
        $("#promotion_item_department").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false,placeholder: 'Select Department'});
        $("#promotion_item_category").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false,placeholder: 'Select Category'});
        $("#promotion_item_sub_category").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false,placeholder: 'Select Sub Category'});
        
        if($('input[type=radio][name=promotion_item_state]').val() == 'I') {
            $('#label_promotion_buy_itmes').text('Include Items');
        }
       else{
            $('#label_promotion_buy_itmes').text('Exlude Items');
        }
        
        $('#promotion_buy_items').select2({
            dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),
            closeOnSelect:false,
            placeholder:'Search for items',
            
            ajax: {
                    url: get_items_url,
                    dataType: 'json',
                    delay: 50,
                    processResults: function (data) {
                        return {
                            results: data,
                            more: false
                        };
                    },
                    pagination: {
                        more: true
                    },
                    cache: true
                }
         });
        $('#promotion_customers').select2({
            dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),
            closeOnSelect:false,
            placeholder: 'Search for Customers',
            ajax: {
                url: get_customers_url,
                dataType: 'json',
                delay: 50,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                pagination: {
                    more: true
                },
                cache: true
            }
        });
    });
    
    $("#promotion_category").change(function(){
        var promotion_category = $(this).val();
        if( promotion_category == 'Time Bound')
        {
            $("#datetime_div").show(1000);
            $("#time_div").show(1000);
            $("#item_qty_limit_div").hide(1000);
            //$('#promotion_to_date').datepicker('setDate', 'today');
        }
        else if(promotion_category == 'Stock Bound')
        {
            $("#datetime_div").hide(1000);
            $("#time_div").hide(1000);
            $("#item_qty_limit_div").show(1000);
        }
        else
        {
            $("#datetime_div").hide(1000);
            $("#item_qty_limit_div").hide(1000);
            $("#time_div").hide(1000);
        }
    });
    
    $("#promotion_type").change(function(){
        var promotionType = $(this).val();
        $("#buy_items_span").css('width','100%');
        $("#add_discount_span").css('display','none');
        $("#div_period").hide(1000);
        $("#div_discount").show();
        $("#div_addl_discount").show();
        $("#div_discount_limt").show();
        
       
        
        
        var item_type = $("[name='promotion_same_itme']:checked").val();
    
        if( (promotionType == 2 || promotionType == 3))
        {
            if( item_type == 'Group Item' )
            {
                $("#discountedItem").show();
                $("#add_to_discounted_items").show();
            }
            else
            {
                $("#discountedItem").hide();
                $("#add_to_discounted_items").hide();
            }
        }
        else
        {
            $("#discountedItem").hide();
            $("#add_to_discounted_items").hide();
        }
        
        if(promotionType == 1 || promotionType == 2 || promotionType == 5 || promotionType == 10 || promotionType == 11 || promotionType == 12 )
        {
            showTypePercentDollor();
            $('#promotion_discount_type').prop('selectedIndex',0);
        }
        else if(promotionType == 3)
        {
            showQuantity();
            $('#promotion_discount_type').prop('selectedIndex',2);
            if($("#promotion_discount_type").val() == 3)
            {
                $("#discountedItem").hide(1000);
                $("#add_to_discounted_items").hide(1000);
            }
        }
        else if(promotionType == 6)
        {
            $("#div_discount_type").hide(1000);
        }
        else
        {
            showAllTypes();
            $('#promotion_discount_type').prop('selectedIndex',1);
        }
        
        if(promotionType == 10)
        {
            $("#div_slab_price").show(1000);
            $("#div_addl_discount").show(1000);
            $("#div_item_state").hide(1000);
            $("#div_discount").hide(1000);
            $("#div_addl_discount").show(1000);
            $("#buyQty").show(1000);
            $('#billValue').hide(1000); // hide bill value for Slab Price
            $('#div_discount_type').hide(1000);
            $('#discOptions').hide(1000);
            
            $('#div_discount_limt').hide(1000);
        }
        else if( promotionType == 1 | promotionType == 2 | promotionType == 3 | promotionType == 4 | promotionType == 9)
        {
            $("#buyQty").show(1000);
            $("#discOptions").show(1000);
            $("#billValue").hide(1000);
            $("#div_slab_price").hide(1000);
            $("#div_item_state").hide(1000);
            
        }
        
        else if( promotionType == 5 | promotionType == 6 | promotionType == 7 | promotionType == 8 )
        {
            $("#buyQty").hide(1000);
            $("#div_slab_price").hide(1000);
            $("#billValue").show(1000);
            $("#div_item_state").show(1000);
            
        }
        else if( promotionType == 11 || promotionType == 12)
        {
            $("#buy_items_span").css('display','inline-block');
            $("#buy_items_span").css('width','80%');
            $("#add_discount_span").css('display','inline-block');
            $("#div_item_state").hide(1000);
            $("#div_slab_price").hide(1000);
            $("#div_discount").hide(1000);
            // $("#div_addl_discount").hide(1000);
            $("#div_discount_limt").hide(1000);
            // add_to_discounted_items
        }
        else
        {
            $("#buyQty").hide(1000);
            $("#div_slab_price").hide(1000);
            $("#billValue").hide(1000);
            $("#div_item_state").hide(1000);
            $("#discOptions").hide(1000);
        }
        
        if(promotionType == 11)
        {
            $("#div_addl_discount").hide(1000);
            $("#div_item_type").hide(1000);
            $("#discountedItem").hide(1000);
            $("#add_to_discounted_items").hide(1000);
        }
        else if(promotionType == 5 || promotionType == 3)
        {
            $("#div_item_type").hide(1000);
        }
        else
        {
            $("#div_item_type").show(1000);
        }
        
        if(promotionType == 4)
        {
            $("#div_period").show(1000);
            $("#div_item_type").hide(1000);
            $("#div_customers").show(1000);
            $("#discountedItem").hide(1000);
            $("#add_to_discounted_items").hide(1000);
            $("#id_same_item").prop("checked", true);
        }
        else
        {
            $("#div_customers").hide(1000);
        }
        
        if(promotionType == 12)
        {
            $("#item_listing2_buy_down").show();
            $("#div_item_type").hide(1000);
            $("#div_addl_discount").show(1000);
            $("#id_same_item").prop("checked", true);
            $(".mfg_desc").show(1000);
            $("#buyQty").show(1000);
            $("#promotion_discounted_value").val(0);
            $("#div_discount").show(1000);
            $("#div_discount_required").removeClass('required');
            
            $('#add_to_discounted_items').hide(1000);
            $('#discountedItem').hide(1000);
            
            showTypeDollor();
            $('#promotion_discount_type').prop('selectedIndex',1);
            
        }
        else
        {
            $(".mfg_desc").hide(1000);
            $("#item_listing2_buy_down").hide();
        }
        
        if(promotionType == 5)
        {
            $("#id_same_item").prop("checked", true);
        }

        $("[name='promotion_same_itme']").trigger('change');
        
        
        if(promotionType == 10){
            $('#spanAddlDisc').html("Slab Price for Regd. Customers");
            document.getElementById('promotion_addl_discount').placeholder='Slab Price for Regd. Customers';
        } else {
            $('#spanAddlDisc').html("Addl. Discount for Regd. Customers");
            document.getElementById('promotion_addl_discount').placeholder='Addl. Discount for Regd. Customers';
        }
    });
   
    $("#promotion_item_department").change(function(){
        if($(this).val() != ""  && $(this).val() !== null)
        {
            $('#promotion_item_category').attr("placeholder", "Loading...");
            var get_categories_url = "<?php echo $data['get_categories_url']; ?>";
            get_categories_url = get_categories_url.replace(/&amp;/g, '&');
            
            var get_department_items_url = "<?php echo $data['get_department_items_url']; ?>";
            get_department_items_url = get_department_items_url.replace(/&amp;/g, '&');
            
            var dep_code = $(this).val();
            
            if(get_department_item_ajax && get_department_item_ajax.readyState != 4 ){
                get_department_item_ajax.abort();
            }
            
            get_department_item_ajax = $.ajax({
                url: get_department_items_url,
                type: 'post',
                data : {dep_code : dep_code},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success:function(data){
                    if(data)
                    {
                        $('#promotion_buy_items').html('').select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false,data: data});
                    }
                }
            })
            
            if(get_category_ajax && get_category_ajax.readyState != 4 ){
                get_category_ajax.abort();
            }
            
            get_category_ajax = $.ajax({
                url: get_categories_url,
                type: 'post',
                data : {dep_code : dep_code},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success:function(data){
                    if(data)
                    {
                        $('#promotion_item_category').attr("placeholder", "Select Category");
                        $( '#promotion_item_category' ).html( data );
                        $('#promotion_item_category').prop("disabled", false);
                        $("#promotion_buy_items").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false});
                    }
                    else
                    {
                        $( '#promotion_item_category' ).html( '' );
                        $('#promotion_item_category').prop("disabled", true);
                    }
                    
                }
            })
        }
        else
        {
            $( '#promotion_item_category' ).html( '' );
            $('#promotion_item_category').prop("disabled", true);
            $("#promotion_buy_items").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false,placeholder:'Search for items',
                ajax: {
                    url: get_items_url,
                    dataType: 'json',
                    delay: 50,
                    processResults: function (data) {
                        return {
                            results: data,
                            more: false
                        };
                    },
                    pagination: {
                        more: true
                    },
                    cache: true
                }});
        }
    });
    
    $("#promotion_item_category").change(function(){
        if($(this).val() != "" && $(this).val() !== null)
        {
            $('#promotion_item_sub_category').attr("placeholder", "Loading...");
            var get_category_items_url = "<?php echo $data['get_category_items_url']; ?>";
            get_category_items_url = get_category_items_url.replace(/&amp;/g, '&');
            
            var get_sub_categories_url = '<?php echo $data['get_sub_categories_url']; ?>';
            get_sub_categories_url = get_sub_categories_url.replace(/&amp;/g, '&');
            
            var cat_code = $(this).val();
            var dep_code = $("#promotion_item_department").val();
            
            if(get_category_items_ajax && get_category_items_ajax.readyState != 4 ){
                get_category_items_ajax.abort();
            }
            
            if(get_subcategory_ajax && get_subcategory_ajax.readyState != 4 ){
                get_subcategory_ajax.abort();
            }
            
            
            get_subcategory_ajax = $.ajax({
                url: get_sub_categories_url,
                type: 'post',
                data : {cat_code : cat_code},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success:function(data){
                    if(data)
                    {
                        $('#promotion_item_sub_category').attr("placeholder", "Select Category");
                        $('#promotion_item_sub_category' ).html( data );
                        $('#promotion_item_sub_category').prop("disabled", false);
                        $("#promotion_buy_items").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false});
                        
                    }
                    else
                    {
                        $( '#promotion_item_sub_category' ).html( '' );
                        $('#promotion_item_sub_category').prop("disabled", true);
                    }
                    
                }
            })
            
            get_category_items_ajax = $.ajax({
                url: get_category_items_url,
                type: 'post',
                data : {dep_code : dep_code, cat_code:cat_code},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success:function(data){
                    $('#promotion_buy_items').html('').select2({data: data});
                    $("#promotion_buy_items").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false});
                }
            })
        }
        else
        {
            $("#promotion_buy_items").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false,placeholder:'Search for items',
                            ajax: {
                                url: get_items_url,
                                dataType: 'json',
                                delay: 50,
                                processResults: function (data) {
                                    return {
                                        results: data,
                                        more: false
                                    };
                                },
                                pagination: {
                                    more: true
                                },
                                cache: true
                            }});
        }
    });
    
    $("#promotion_item_sub_category").change(function(){
        if($(this).val() != "" && $(this).val() !== null)
        {
            var get_sub_category_items_url = "<?php echo $data['get_sub_category_items_url']; ?>";
            get_sub_category_items_url = get_sub_category_items_url.replace(/&amp;/g, '&');
            
            var subcat_id = $(this).val();
            var dep_code = $("#promotion_item_department").val();
            var cat_code = $("#promotion_item_category").val();
            
            if(get_sub_category_items_ajax && get_sub_category_items_ajax.readyState != 4 ){
                get_sub_category_items_ajax.abort();
            }
            
            get_sub_category_items_ajax = $.ajax({
                url: get_sub_category_items_url,
                type: 'post',
                data : {dep_code:dep_code ,cat_code:cat_code ,sub_cat_id:subcat_id},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success:function(data){
                    $('#promotion_buy_items').html('').select2({data: data});
                    $("#promotion_buy_items").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false});
                }
            })
        }
        else
        {
            $("#promotion_item_category").trigger("change");
            $("#promotion_buy_items").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false,placeholder:'Search for items',
                            ajax: {
                                url: get_items_url,
                                dataType: 'json',
                                delay: 50,
                                processResults: function (data) {
                                    return {
                                        results: data,
                                        more: false
                                    };
                                },
                                pagination: {
                                    more: true
                                },
                                cache: true
                            }});
        }
    });
    
    $("#promotion_item_group").change(function(){
        if($(this).val() != "")
        {
            var get_group_items_url = '<?php echo $data['get_group_items_url']; ?>';
            get_group_items_url = get_group_items_url.replace(/&amp;/g, '&');
            var group_id = $(this).val();
            
            if(get_group_items_ajax && get_group_items_ajax.readyState != 4 ){
                get_group_items_ajax.abort();
            }
            
            get_group_items_ajax = $.ajax({
                url: get_group_items_url,
                type: 'post',
                data : {group_id : group_id},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success:function(data){
                    $('#promotion_buy_items').html('').select2({data: data});
                    $("#promotion_buy_items").select2({dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),closeOnSelect:false});
                }
            })
        }
    });
    
    $('input[type=radio][name=promotion_item_state]').change(function() {
        if (this.value == 'I') {
            $('#label_promotion_buy_itmes').text('Include Items');
        }
        else if (this.value == 'E') {
            $('#label_promotion_buy_itmes').text('Exlude Items');
        }
    });
    
    $(document).on("keypress keyup blur",".decimal_numbers",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
        
    $('input[type="number"]').on("keypress keyup blur",function (event) {    
       $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
    
    $("[name='promotion_same_itme']").change(function(){
        var promotionType = $("#promotion_type").val();
        var promotion_item_type = $(this).val();
        if(promotion_item_type == 'Same Item')
        {
            $("#discountedItem").hide(1000);
            $("#add_to_discounted_items").hide(1000);
        }
        else if(promotion_item_type == 'Group Item' && promotionType != 10 && promotionType != 11 && promotionType != 1 && promotionType != 4 && promotionType != 3 && promotionType != 12 )
        {
            $("#discountedItem").show(1000);
            $("#add_to_discounted_items").show(1000);
        }
        else if(promotion_item_type == 'Group Item')
        {
            $("#discountedItem").hide(1000);
            $("#add_to_discounted_items").hide(1000);
        }
    })
    
    $("#promotion_discount_type").change(function (){
        var discountType = $(this).val();
        var promotionType = $("#promotion_type").val();
        
        if(discountType == 3)
        {
            $("#id_same_item").prop("checked", true);
        }
        
        if(promotionType == 3 && discountType == 4)
        {
            $("#discountedItem").show(1000);
            $("#add_to_discounted_items").show(1000);
        }
        else
        {
            $("#discountedItem").hide(1000);
            $("#add_to_discounted_items").hide(1000);
        }
    })
    
    $("#promotion_discounted_value").on("keypress keyup blur",function (event) {    
       if($(this).val() > 0 && $("#promotion_type").val() == "12")
        {
            $("#buy_items_span").css('width','100%');
            $("#add_discount_span").css('display','none');
        }
        else
        {
            $("#buy_items_span").css('display','inline-block');
            $("#buy_items_span").css('width','80%');
            $("#add_discount_span").css('display','inline-block');
        }
    })
    
    $(document).on('keyup', ".new_price", function () {
    	var sellingPrice = $(this).parent().prev('td').html();
    	var newPriceVal = this.value;
     	
    	var promotionVal = sellingPrice - newPriceVal;
    // 	$(this).parent().next('td').html(promotionVal);
    });
    
    $(document).on('change', '#price_select_by', function(){
        var select_by = $(this).val();
        var select_by_value1 = $('#select_by_value1').val() === undefined?'':$('#select_by_value1').val();
        var select_by_value2 = $('#select_by_value2').val() === undefined?'':$('#select_by_value2').val();
        
        $('#select_by_value_1').remove();
        $('#select_by_value_2').remove();
        
        var html='';
        if(select_by === 'between'){
            
            html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box" placeholder="Enter" style="width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value="'+select_by_value1+'"/>';
            html += '<input type="text" autocomplete="off" name="select_by_value_2" id="select_by_value_2" class="search_text_box" placeholder="Amt" style="width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value="'+select_by_value2+'"/>'
        
            $(this).css({'width': 71});
            
            
        } else {
            
            html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box" placeholder="Enter Amt" style="width:70px;color:black;border-radius: 4px;height:28px;margin-left:5px;" value="'+select_by_value1+'"/>'
            // $('#selectByValuesSpan').html('not between');
            $(this).css({'width': 90});
        }
        $('#selectByValuesSpan').html(html);
        
    });
    
        // $(document).on('keyup change', '#select_by_value_1, #select_by_value_2', function(){
        //     console.log('change value input boxes');
        //     select_by_val1 = $('#select_by_value_1').val();
        //     select_by_val2 = $('#select_by_value_2').val();
            
        //     table.draw();
        // });
    
    $(document).ready(function(){
        
        var url = '<?php echo $data['searchitem'];?>';
        url = url.replace(/&amp;/g, '&');
        var departments = "<?php echo $data['departments'];?>";
        var suppliers = "<?php echo $data['suppliers'];?>";
        var manufacturers = "<?php echo $data['manufacturers'];?>";
        var units = "<?php echo $data['units'];?>";
        var size = "<?php echo $data['size'];?>";
        var item_types = "<?php echo $data['item_types'];?>";
        var price = "<?php echo $data['price']; ?>";
        
        var price_select_by = $('#price_select_by').val();
        var select_by_val1 = $('#select_by_value_1').val();
        var select_by_val2 = $('#select_by_value_2').val();
        
        $(document).on('input', '#select_by_value_2, #select_by_value_1', function(){
            
            select_by_val1 = parseFloat($('#select_by_value_1').val());
            select_by_val2 = parseFloat($('#select_by_value_2').val());
            
            if($('#price_select_by').val() == 'between' && typeof(select_by_val1) != "undefined" && select_by_val1 !== null && typeof(select_by_val2) != "undefined" && select_by_val2 !== null && select_by_val1 >= select_by_val2){ 
                bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: "Second value must be greater than first value!", 
                });
            }
        });
        //console.log(select_by_val1);
        //console.log(select_by_val2);
        
        var priceFilter = {
            "price_select_by": price_select_by,
            "select_by_value_1": select_by_val1,
            "select_by_value_2": select_by_val2
        }
        
        $('#item_listing thead tr').clone(true).appendTo( '#item_listing thead' );
        $('#item_listing thead tr:eq(1) th').each( function (i) {
            
            var title = $(this).text();
            if(i == 0)
            {
                $(this).html( '' );
            }
            else if(title == 'DEPT.')
            {
                $(this).html(departments)
            }
            else if(title == "TYPE")
            {
                $(this).html(item_types)
            }
            else if(title == "CATEGORY")
            {
                $(this).html('<select class="form-control" name="category_code" id="category_code" style="width: 70px; padding: 0px; font-size: 9px;"></select>')
            }
            else if(title == "SUB CAT")
            {
                $(this).html('<select class="form-control" name="sub_category_id" id="sub_category_id" style="width: 90px; padding: 0px; font-size: 9px;"></select>')
            }
            else if(title == 'SUPPLIER')
            {
                $(this).html(suppliers)
            }
            else if(title == 'MFR')
            {
                $(this).html(manufacturers)
            }
            else if(title == 'UNIT')
            {
                $(this).html(units)
            }
            else if(title == 'SIZE')
            {
                $(this).html(size)
            }
            else if(title == 'ITEM NAME')
            {
                $(this).html( '<input type="text" name="" class="search_text_box" id="item_name" placeholder="Search" style="width:70px;color:black;border-radius: 4px;height:28px;"/>' );
            } else if(title == 'SKU'){
                $(this).html( '<input type="text" name="" class="search_text_box" id="sku" placeholder="Search" style="width:70px;color:black;border-radius: 4px;height:28px;"/>' );
            }
            else if(title == 'PRICE') 
            {
                $(this).html(price);
            } else
            {
                $(this).html( '' );
            }
            
            $(this).on( 'keyup change', '.search_text_box', function () {
                var selectBy = $("#price_select_by").val();
                var val1 = $("#select_by_value_1").val();
                var val2 = $("#select_by_value_2").val();
                
                // var val1 = ''
                // var val2 = ''
                // $("select_by_value_2").val()
                var searchVal = ''
                if(selectBy){
                    searchVal += selectBy
                } 
                if(val1){
                    searchVal += ('|' + val1)
                }
                if(val2){
                    searchVal += ('|'+val2)
                }
                if(this.id != 'select_by_value_2'  && this.id != 'select_by_value_1' && this.id != 'item_name' && this.id != 'sku'){
                    searchVal += this.value
                }
                if((this.id != 'item_name' || this.id != 'sku') && (this.id != 'select_by_value_2'  && this.id != 'select_by_value_1')){
                    searchVal = this.value
                }
                console.log(this.id);
                // if ( table.column(i).search() !== this.value ) {
                        // table
                        // .column(i)
                        // .search( this.value, selectBy, val1, val2 )
                        // .draw();               
                    table
                    .column(i)
                    .search(
                        searchVal, true, false
                    ).draw();
            // }
                // }
            } );
            
            $( 'select', this ).on( 'change', function () {
                
                console.log(this.id);
                
                //     var selectBy = $("#price_select_by").val()
                //     var val1 = $("#select_by_value_1").val()

                //     if(selectBy && selectBy == 'between')
                //         var val2 = $("#select_by_value_2").val()
                        
                //     var searchVal = ''
                //     if(selectBy){
                //         searchVal += selectBy
                //     } 
                //     if(val1){
                //         searchVal += ('|' + val1)
                //     }
                //     if(val2){
                //         searchVal += ('|'+val2)
                //     }
                    
                //     if ( table.column(i).search() !== this.value) {
                        
                //         if( val1 == "" || ( selectBy == 'between' && val2 == "") ){
                //             table
                //             .column(i)
                //             .search( false )
                //             .draw();
                            
                //         }else{
                //             table
                //             .column(i)
                //             .search( searchVal )
                //             .draw();
                //         }
                //     }
                
                var self = this;
                
                    if ( table.column(i).search() !== self.value ) {
                        table
                            .column(i)
                            .search( self.value )
                            .draw();
                    }
                
                
            });
        });
                
        $("div#divLoading").removeClass('show');
        
        var table =   $("#item_listing").DataTable({
            
                "bSort": false,
                "scrollY":"300px",
                // "autoWidth": true,
                "fixedHeader": true,
                "processing": true,
                "iDisplayLength": 20,
                "serverSide": true,
                "bLengthChange": false,
                "paging": true, 
                //"bPaginate": false, 
               
             
                "aoColumnDefs": [
                    { "sWidth": "70px", "aTargets": [ 1 ] },
                    { "sWidth": "70px", "aTargets": [ 2 ] },
                    { "sWidth": "55px", "aTargets": [ 8 ] },
                    { "sWidth": "50px", "aTargets": [ 4 ] },
                    { "sWidth": "50px", "aTargets": [ 5 ] },
                    { "sWidth": "85px", "aTargets": [ 6 ] },
                    { "sWidth": "85px", "aTargets": [ 7 ] },
                    { "sWidth": "195px", "aTargets": [ 3 ] },
                    { "sWidth": "25px", "aTargets": [ 9 ] }
                ],
                //"autoWidth": true,
                
                "language": {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    
                    infoFiltered: ""
                    
                },
               
                
                "dom": '<"mysearch"lf>rt<"bottom"ip>',
                "ajax": {
                  url: url,
                  "data": priceFilter,
                  headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                  type: 'POST',
                  "dataSrc": function ( json ) {
                        $("div#divLoading").removeClass('show');
                      
                        return json.data;
                    } 
                },
               
                columns :  [
                      {
                          data: "iitemid", render: function(data, type, row){
                             
                              return $("<input>").attr({
                                    type: 'checkbox',
                                    class: "iitemid",
                                    value: data,
                                    name: "selected[]",
                                    "data-order": data,
                              })[0].outerHTML;
                              
                          }
                      },
                      { "data": "vitemname", render: function(data, type, row){
                                return "<span style='max-width: 70px; word-wrap: break-word; display: block;'>"+data+"</span>";
                          }
                      },
                      { "data": "vbarcode"},
                      {"data": "dunitprice"},
                      { "data": "vunitname"},
                      { "data": "vsize"},
                      { "data": "vdepartmentname"},
                      { "data": "vcategoryname"},
                      { "data": "subcat_name"},
                    //   { "data": "vcompanyname"},
                    //   { "data": "mfr_name"},
                      { "data": "prom_id"},
                      
                    ],
                    rowCallback: function(row, data, index){
                      	if(data.prom_id == null || data.prom_id == "null"){
                        	$(row).find('td:eq(9)').css('background-color', 'green');
                        	
                        }
                        else
                        {
                            $(row).find('td:eq(9)').css('background-color', 'red');
                            
                            if (Object.values(check_existingPromItems).indexOf(data.iitemid) === -1) {
                                check_existingPromItems.push(data.iitemid);
                                existingPromItems.push({iitemid:data.iitemid,vitemname:data.vitemname,vbarcode:data.vbarcode});
                            }
                            
                        }
                        $(row).find('td:eq(9)').html('');
                  },
                  fnDrawCallback : function() {
                        if ($(this).find('tbody tr').length<=1) {
                            $(this).find('.dataTables_empty').hide();
                            $('#item_listing_paginate').hide();
                        }else{
                            $('#item_listing_paginate').show();
                        }
                        
                       
                    }
               
                
            });
            $("#item_listing_filter").hide();
            $("#item_listing_processing").remove();
            
        $("#item_listing2_processing").remove();
        $("#item_listing2_filter").hide();
            
        $('[data-toggle="tooltip"]').tooltip();
        
        var promotion_type_item = $("[name='promotion_same_itme']:checked").val();
        $("#discountedItem").show();
        $("#add_to_discounted_items").show();
        if(promotion_type_item == 'Same Item')
        {
            $("#discountedItem").hide();
            $("#add_to_discounted_items").hide();
        }
       
        var promotion_category = $("#promotion_category").val();
        if( promotion_category == 'Time Bound')
        {
            $("#datetime_div").show();
            $("#time_div").show();
            $("#item_qty_limit_div").hide();
        }
        else if(promotion_category == 'Stock Bound')
        {
            $("#datetime_div").hide();
            $("#item_qty_limit_div").show();
        }
        else
        {
            $("#time_div").hide();
        }
        
        var promotion_period = $("#promotion_period").val();
        var promotionType = $("#promotion_type").val();
        
        $("#buy_items_span").css('width','100%');
        $("#add_discount_span").css('display','none');
        $("#div_period").hide();
        $("#div_discount").show();
        $("#div_discount").show();
        $("#div_addl_discount").show();
        $("#div_discount_limt").show();
        if(promotionType == 5 || promotionType == 3)
        {
            $("#div_item_type").hide();
            $("#div_item_state").show();
        }
        else
        {
            $("#div_item_state").hide();
            $("#div_item_type").show();
        }
        
        var item_type = $("[name='promotion_same_itme']:checked").val();
        
        if(promotionType == 3)
        {
            $("#div_item_state").hide();
        }
    
        if( (promotionType == 2 || promotionType == 3))
        {
            if( item_type == 'Group Item' )
            {
                
                $("#discountedItem").show();
                $("#add_to_discounted_items").show();
            }
            else
            {
                $("#discountedItem").hide();
                $("#add_to_discounted_items").hide();
            }
        }
        else
        {
             $("#discountedItem").hide();
             $("#add_to_discounted_items").hide();
        }
        
        if(promotionType == 1 || promotionType == 2 || promotionType == 5 || promotionType == 10 )
        {
            showTypePercentDollor();
        }
        else if(promotionType == 3)
        {
            showQuantity();
        }
        else if(promotionType == 6)
        {
            $("#div_discount_type").hide();
        }
        else
        {
            showAllTypes();
        }
        
        if(promotionType == 10)
        {
            $("#div_slab_price").show();
            $("#div_discount").hide();
            $("#div_addl_discount").hide();
            $("#buyQty").show();
            $("#div_addl_discount").show();
            $('#billValue').hide(1000); // hide bill value for Slab Price
            $('#div_discount_type').hide(1000);
            $('#discOptions').hide(1000);
        }
        else if( promotionType == 1 | promotionType == 2 | promotionType == 3 | promotionType == 4 | promotionType == 9)
        {
            $("#buyQty").show();
            $("#discOptions").show();
            $("#billValue").hide();
            $("#div_slab_price").hide();
        }
        
        else if( promotionType == 5 | promotionType == 6 | promotionType == 7 | promotionType == 8 )
        {
            $("#buyQty").hide();
            $("#div_slab_price").hide();
            $("#billValue").show();
        }
        else if( promotionType == 11 || promotionType == 12)
        {
            $("#buy_items_span").css('display','inline-block');
            $("#buy_items_span").css('width','80%');
            $("#add_discount_span").css('display','inline-block');
            $("#div_discount").hide();
            // $("#div_addl_discount").hide();
            $("#div_discount_limt").hide();
        }
        else
        {
            $("#buyQty").hide();
            $("#div_slab_price").hide();
            $("#billValue").hide();
            $("#discOptions").hide();
        }
        
        if(promotionType == 11)
        {
            $("#div_item_type").hide();
            $("#div_addl_discount").hide();
        }
        
        if(promotionType == 4)
        {
            $("#div_period").show();
            $("#div_item_type").hide();
            
            $("#discountedItem").hide(); 
            $("#add_to_discounted_items").hide(); 
            $("#div_customers").show();
            $("#id_same_item").prop("checked", true);
        }
        else
        {
            $("#div_customers").hide();
        }
        
        if(promotionType == 12)
        {
            $("#item_listing2_buy_down").show();
            $("#div_item_type").hide();
            $(".mfg_desc").show();
            $("#buyQty").show();
            $("#div_discount").show();
            $("#div_addl_discount").show();
            
            $('#add_to_discounted_items').hide(1000);
            $('#discountedItem').hide(1000);
            
            showTypeDollor();
            $('#promotion_discount_type').prop('selectedIndex',1);
            
            if($("#promotion_discounted_value").val() > 0)
            {
                $("#buy_items_span").css('width','100%');
                $("#add_discount_span").css('display','none');
            }
            else
            {
                $("#buy_items_span").css('display','inline-block');
                $("#buy_items_span").css('width','80%');
                $("#add_discount_span").css('display','inline-block');
            }
            $("#div_discount_required").removeClass('required');
        }
        else
        {
            $("#item_listing2_buy_down").hide();
            $(".mfg_desc").hide();
        }
        
        var discountType = $("#promotion_discount_type").val();
        if(promotionType == 3 && discountType == 4)
        {
            $("#discountedItem").show();
            $("#add_to_discounted_items").show();
        }
        prom_type       = $("#promotion_type").val();
        disc_type       = $("#promotion_discount_type").val();
        disc_value      = $("#promotion_discounted_value").val();
        addl_discount   = $("#promotion_addl_discount").val();
        mfg_disc_value  = $("#mfg_discount").val();
        slab_price      = $('#promotion_slab_price').val();
        buy_qty         = $('#promotion_buy_qty').val();
        
        // console.log($data['selected_buy_items']);
        <?php if(!empty($data['selected_buy_items']) && $data['prom_id']) { ?>
        var prom_id = '<?php echo $data['prom_id'];?>'
        
        clickedAddBuyItems = 1;
        var selected_buy_items = '<?php echo  json_encode($data['selected_buy_items']); ?>';
            $.each($.parseJSON(selected_buy_items), function( index,value ) {
                itemsAddedToBuyItems.push(value);
                var postData = {buyItems : [value],prom_id:prom_id,prom_type : prom_type, disc_type : disc_type, disc_value : disc_value, mfg_disc_value : mfg_disc_value, addl_discount : addl_discount,slab_price : slab_price, buy_qty : buy_qty};
                $(this).prop('checked',false);
                 $.ajax({
                    url: get_saved_buy_items_ajax_url,
                    type: 'post',
                    data : postData,
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success:function(data){
                        if(data)
                        {
                            $("#item_listing2 tbody").append(data);
                        }
                        else
                        {
                            
                        }
                    }
                })
            });
            <?php }?>
        
        <?php if(!empty($selected_discount_items)) { ?>
            var selected_discounted_items = '<?php echo  json_encode($selected_discount_items); ?>';
            var prom_type = $("#promotion_type").val();
             
            $.each($.parseJSON(selected_discounted_items), function( index,value ) {
                itemsAddedToDiscountedItems.push(value);
                $(this).prop('checked',false);
                $.ajax({
                    url: get_saved_discounted_items_ajax_url,
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data : {discountedItems : [value],disc_type : disc_type, disc_value : disc_value,prom_type:prom_type},
                    success:function(data){
                        if(data)
                        {
                            $("#item_listing3 tbody").append(data);
                        }
                        else
                        {
                            
                        }
                        
                    }
                })
            });
        <?php }?>
        
        $('form#form-promotion').validate({ // initialize the plugin
            rules: {
                promotion_name: {
                    required: true,
                },
                promotion_code: {
                    required: true,
                },
                promotion_type: {
                    required: true,
                },
                promotion_category: {
                    required: true,
                },
                promotion_buy_qty: {
                    required: function(element){
                        var selected = $("#promotion_type option:selected").val();
                        return (selected == "1" || selected == 2 || selected == 3 || selected == 4 || selected == 9 || selected == 12 || selected == 10);
                    }
                },
                promotion_slab_price: {
                    required: function(element){
                        var selected = $("#promotion_type option:selected").val();
                        return (selected == 10);
                    }
                },
                promotion_discounted_value: {
                    required: function(element){
                        var selected = $("#promotion_type option:selected").val();
                        return ((selected != "10" && selected != "12") && $("#promotion_type option:selected").val() != "");
                    }
                },
               
                promotion_item_qty_limit: {
                    required: function(element){
                        return $("#promotion_category option:selected").val() == "Stock Bound";
                    }
                },
                
                promotion_bill_value:{
                    required: function(element){
                        var selected = $("#promotion_type option:selected").val();
                        return (selected != "1" && selected != 2 && selected != 3 && selected != 4 && selected != 9);
                    },
                }
                
                /*mfg_discount:{
                    required: function(element){
                        var selected = $("#promotion_type option:selected").val();
                        return (selected == "12");
                    },
                }*/
                
                
            },
            messages: {
                promotion_name: "Promotion Name is Required",
                promotion_code: "Promotion Code is Required",
                promotion_type: "Promotion Type is Required",
                promotion_category: "Promotion Category is Required",
                promotion_buy_qty: "Buy Qty is Required",
                promotion_discounted_value: "Discount Value is Required",
                promotion_slab_price: "Slab Price is Required",
                // mfg_discount: "MFG Discount Required",
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        });
        
        var itemRow = ''; // table row for preselected items ie. in edit page
        var discountForItems = [];
        if($("#promotion_buy_items option:selected").val())
        {
            $.each($("#promotion_buy_items option:selected"), function(){  
                 discountForItems[$(this).val()]=({
                    id: $(this).val(), 
                    text:  $(this).text(),
                    cost: $(this).data('cost'), 
                    unit: $(this).data('unit'),
                    discount: $(this).data('discount'),
                });
            });
            event.preventDefault();
        }
        
        $('#promotion_buy_items').on('select2:select', function(evt){
            // console.log(evt.params.data);
            discountForItems[evt.params.data.id]=({
                id: evt.params.data.id, 
                text:  evt.params.data.text,
                cost: evt.params.data.cost, 
                unit: evt.params.data.unit,
                discount: '',
            });
        });
        
        $(document).on('click', '#add_discount_price', function(event) {
            if($("#promotion_buy_items option:selected").val())
            {
                itemRow='';
                $.each($("#promotion_buy_items option:selected"), function(){
                    if($.inArray($(this).val(),discountForItems))
                    {
                        var i = $(this).val();
                        discountForItems[i]
                        itemRow += '<tr>';
                        itemRow += '<td style="padding-left:5px;">'+discountForItems[i].text+'</td>';
                        itemRow += '<td style="padding-left:5px;">'+discountForItems[i].cost+'</td>';
                        itemRow += '<td style="padding-left:5px;">'+discountForItems[i].unit+'</td>';
                        itemRow += '<td><input type="text" class="decimal_numbers" id="' + discountForItems[i].id + '" name="disc[' + discountForItems[i].id + ']" value="'+ discountForItems[i].discount +'" style="width: 100%;" /></td></tr>';
                        
                    }
                });
                $("#items_dicount_table tbody").html(itemRow);
                $('#add_discount').modal('show');
            }
            else
            {
                alert("please add items");
            }
        });
        
        $("#items_dicount_table").fancyTable({
			sortColumn:0,
			inputStyle: "color:black;",
			perPage:5,
			globalSearch:true,
			paginationClass: "btn btn-info",
			scrollY:        "200px",
            scrollCollapse: true,
            bPaginate:         false,
            
		});
	
    })
    
    $(document).on("change","#dept_code",function(){
        var get_category_ajax;
        if($(this).val() != "")
        {
            $('#category_code').attr("placeholder", "Loading...");
            var get_categories_url = "<?php echo $data['get_categories_url']; ?>";
            get_categories_url = get_categories_url.replace(/&amp;/g, '&');
            
            var get_department_items_url = "<?php echo $data['get_department_items_url']; ?>";
            get_department_items_url = get_department_items_url.replace(/&amp;/g, '&');
            var dep_code = [$(this).val()];
            
            if(get_category_ajax && get_category_ajax.readyState != 4 ){
                get_category_ajax.abort();
            }
            
            console.log(get_categories_url);

            get_category_ajax = $.ajax({
                url: get_categories_url,
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data : {dep_code : dep_code},
                success:function(data){
                    console.log('succ');
                    if(data)
                    {
                        $('#category_code').attr("placeholder", "Select Category");
                        $( '#category_code' ).html( data );
                        $('#category_code').prop("disabled", false);
                    }
                    else
                    {
                        $( '#category_code' ).html( '' );
                        $('#category_code').prop("disabled", true);
                    }
                }
            })
        }
        
    });
    
    $(document).on("change","#category_code",function(){
        var get_sub_category_ajax;
        if($(this).val() != "")
        {
            $('#sub_category_id').attr("placeholder", "Loading...");
            var get_sub_categories_url = "<?php echo $data['get_sub_categories_url']; ?>";
            get_sub_categories_url = get_sub_categories_url.replace(/&amp;/g, '&');
            
            var cat_code = [$(this).val()];
            
            if(get_sub_category_ajax && get_sub_category_ajax.readyState != 4 ){
                get_sub_category_ajax.abort();
            }
            
            get_sub_category_ajax = $.ajax({
                url: get_sub_categories_url,
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data : {cat_code : cat_code},
                success:function(data){
                    if(data)
                    {
                        $( '#sub_category_id' ).html( data );
                        $('#sub_category_id').prop("disabled", false);
                    }
                    else
                    {
                        $( '#sub_category_id' ).html( '' );
                        $('#sub_category_id').prop("disabled", true);
                    }
                    
                }
            })
        }
    });
    
    $("#add_to_buy_items").click(function(){
        
        if($('#form-promotion').valid() === false){
            
            bootbox.alert("Please fill in the mandatory fields to proceed.");
            return false;
        } else {
            
            clickedAddBuyItems = 1;
            
            // $(".disabled-text").attr('readonly',true);
            // $(".disabled-select").attr('disabled',true);

            var existingItems = "";
            
            var sno = 0;
            $.each($("input[name='selected[]']:checked"), function(){   
                var selecteditem = $(this).val();
                $.each(existingPromItems, function( index, value ) {
                    if(value.iitemid == selecteditem)
                    {
                        sno++;
                        existingItems += sno+ ". " + value.vitemname +" (" + value.vbarcode + ")<br />";
                    }
                });
            })
            
            if(existingItems != "")
            {
                bootbox.confirm({
                    message: "The following items already exists in other promotions: <br />" + existingItems + "<br/> Do you want to continue?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                            if(result==true)
                            {
                                addToPromotions();
                            }
                        }
                    });
            }
            else
            {
                addToPromotions();
            }
        }
    })
    
    function addToPromotions()
    {
        prom_type       = $("#promotion_type").val();
        disc_type       = $("#promotion_discount_type").val();
        disc_value      = $("#promotion_discounted_value").val();
        mfg_disc_value  = $("#mfg_discount").val();
        addl_discount   = $("#promotion_addl_discount").val();
        buy_qty         = $("#promotion_buy_qty").val();
        
        var itemCount = $("input[name='added_promotion_items[]']").length;
        
        if(itemCount > 2000){
          let diff = itemCount - 2000;
          
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: "Can not add more than 2000 items! Remove "+diff+" Items", 
            callback: function(){}
          });
          
          return false;
        }
        
        existingPromItems = [];
        $.each($("input[name='selected[]']:checked"), function(){            
                
            var selectedItemsToAdd = $(this).val();
            if(jQuery.inArray($(this).val(), itemsAddedToBuyItems) == -1)
            {
                itemsAddedToBuyItems.push(selectedItemsToAdd);
                console.log( existingPromItems );
                
                if(get_selected_buy_items_ajax && get_selected_buy_items_ajax.readyState != 4 ){
                    // get_selected_buy_items_ajax.abort();
                }
                
                var postData = {buyItems : [$(this).val()],prom_type : prom_type, disc_type : disc_type, disc_value : disc_value, mfg_disc_value : mfg_disc_value, addl_discount : addl_discount,buy_qty : buy_qty};
                
                console.log("Promo Type: "+prom_type);
                
                if(prom_type == 10){
                    
                    postData['slab_price'] = $('#promotion_slab_price').val();
                }
        
                // $(this).prop('checked',false);
                get_selected_buy_items_ajax = $.ajax({
                    url: get_selected_buy_items_url,
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data : postData,
                    success:function(data){
                        if(data)
                        {
                            $("#item_listing2 tbody").append(data);
                        }
                        else
                        {
                            
                        }
                        
                    }
                })
            } else {
                
                $('#errorModal').modal('show');
            }
                
        });
    }
    
    $("#add_to_discounted_items").click(function(){
        // var selectedItems = [];
        prom_type = $("#promotion_type").val();
        disc_type = $("#promotion_discount_type").val();
        disc_value = $("#promotion_discounted_value").val();
        if( prom_type > 0)
        {
            $.each($("input[name='selected[]']:checked"), function(){            
                // selectedItems.push($(this).val());
                if(jQuery.inArray($(this).val(), itemsAddedToDiscountedItems) == -1)
                {
                    itemsAddedToDiscountedItems.push($(this).val());
                    
                    // $(this).prop('checked',false);
                    $.ajax({
                        url: get_selected_discounted_items_url,
                        type: 'post',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        data : {discountedItems : [$(this).val()],prom_type : prom_type, disc_type : disc_type, disc_value : disc_value},
                        success:function(data){
                            if(data)
                            {
                                $("#item_listing3 tbody").append(data);
                            }
                            else
                            {
                                
                            }
                        }
                    })
                }
            });
        }
        else
        {
            alert("Please Select Promotion Type")
        }
    })
    
    $(document).on( 'click','#remove_buy_items',  function (e) {
        $('.promotion_buy_items:checked').each(function () {
            itemsAddedToBuyItems.splice($.inArray($(this).val(), itemsAddedToBuyItems),1);
            $(this).closest('tr').remove();
        });
        console.log(itemsAddedToBuyItems);
        //console.log("remove test")
        $("#check_all_buy_items").prop('checked',false);
    });
    
    $(document).on( 'click','#remove_discounted_items',  function (e) {
        $('.promotion_discounted_item:checked').each(function () {
            itemsAddedToDiscountedItems.splice($.inArray($(this).val(), itemsAddedToDiscountedItems),1);
            $(this).closest('tr').remove();
        });
        $("#check_all_discounted_items").prop('checked',false);
    });
    
    function showTypePercentDollor()
    {
        showAllTypes();
        var i;
        for(i=3; i<=6; i++)
        {
            $("#promotion_discount_type option[value='" + i + "']").hide();
        }
    }
    
    function showTypeDollor()
    {
        showAllTypes();
        var i;
        for(i=1; i<=6; i++)
        {
            if(i == 2){continue;}
            $("#promotion_discount_type option[value='" + i + "']").hide();
        }
    }
    
    function showQuantity()
    {
        showAllTypes();
        var i;
        for(i=1; i<=6; i++)
        {
            if(i == 3 || i== 4){continue;}
            $("#promotion_discount_type option[value='" + i + "']").hide();
        }
    }
    
    function showAllTypes()
    {
        $("#div_discount_type").show(1000);
        // $("#promotion_discount_type").val("");
        var i;
        for(i=1; i<=6; i++)
        {
            $("#promotion_discount_type option[value='" + i + "']").show();
        }
    }
    
    $("#submit_promotion").click(function(e)
    {   
        e.preventDefault();
        var promotion_name = $("#promotion_name").val();
        var promotion_code = $("#promotion_code").val();
        if(promotion_name != "" ||  promotion_code != ""){
            <?php if(session()->get('hq_sid') == 1){ ?>
                
                var added_items_barcode = [];
                $('input[name="added_promotion_items_text[]"]').each(function(i){
                    added_items_barcode[i] = $(this).val();
                });
                
                $('#selectAllCheckbox').attr('disabled', false);
                
                <?php if(isset($data['prom_id'])){ ?>
                    var promoid = '<?php echo isset($data['prom_id']) ? $data['prom_id'] : ''  ?>';
                    $.ajax({
                        url : "<?php echo url('/checkPromoIdExists'); ?>",
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                        },
                        data : JSON.stringify({promoId:promoid, added_items_barcode:added_items_barcode }),
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
                                                            '<input type="checkbox" class="checks check custom-control-input editStores" disabled id="hq_sid_{{ $stores->id }}" name="editStores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (This Promotion or Item does not exist)</span></td>'+
                                                '</tr>';
                                            $('#editSelectAllCheckbox').attr('disabled', true);
                                      
                                } else {
                                    var data = '<tr>'+
                                                    '<td>'+
                                                        '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                            '<input type="checkbox" class="checks check custom-control-input editStores"  id="else_hq_sid_{{ $stores->id }}" name="editStores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                '</tr>';
                                }
                                popup = popup + data;
                            @endforeach
                            $('#editmodal_stores').html(popup);    
                        }
                    });
                    $("#editModal").modal('show');
                <?php } else{ ?>
                    $.ajax({
                        url : "<?php echo url('/checkItemExist'); ?>",
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                        },
                        data : JSON.stringify({added_items_barcode:added_items_barcode }),
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
                                                            '<input type="checkbox" class="checks check custom-control-input stores" disabled id="hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
                                                            
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
                                                '</tr>';
                                            $('#selectAllCheckbox').attr('disabled', true);
                                      
                                } else {
                                    var data = '<tr>'+
                                                    '<td>'+
                                                        '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                            '<input type="checkbox" class="checks check custom-control-input stores"  id="else_hq_sid_{{ $stores->id }}" name="stores" value="{{ $stores->id }}">'+
                                                        '</div>'+
                                                    '</td>'+
                                                    '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                                '</tr>';
                                }
                                popup = popup + data;
                            @endforeach
                            $('#addmodal_stores').html(popup);    
                        }
                    });
                    $("#addModal").modal("show");
                <?php } ?>
            <?php } else { ?>
                $(".disabled-select").attr('disabled',false);
                $(".text-danger").remove();
                $('#form-promotion').submit();
            <?php } ?>
        }
    });
    
    var add_stores = [];
    add_stores.push("{{ session()->get('sid') }}");
     $('#selectAllCheckbox').click(function(){
            if($('#selectAllCheckbox').is(":checked")){
                $(".stores").prop( "checked", true );
            }else{
                $( ".stores" ).prop("checked", false );
            }
    });
    
    $('#save_btn').click(function(){
        $.each($("input[name='stores']:checked"), function(){            
            add_stores.push($(this).val());
        });
        $("#hidden_store_hq_val").val(add_stores);
        $('#form-promotion').submit();
    })
    
    var edit_stores = [];
    edit_stores.push("{{ session()->get('sid') }}");
     $('#editSelectAllCheckbox').click(function(){
            if($('#editSelectAllCheckbox').is(":checked")){
                $(".editStores").prop( "checked", true );
            }else{
                $( ".editStores" ).prop("checked", false );
            }
    });
    
    $('#update_btn').click(function(){
        $.each($("input[name='editStores']:checked"), function(){            
            edit_stores.push($(this).val());
        });
        $("#hidden_store_hq_val").val(edit_stores);
        $('#form-promotion').submit();
    });
    
    
    $(document).bind( 'keyup change','#promotion_discount_type,#promotion_buy_qty, #promotion_slab_price, #promotion_addl_discount',  function (e) {
        if(clickedAddBuyItems === 1){
            $("#div_refresh_values").show();
        }
    });
    
    $(document).on( 'click','#button_refresh_values',  function (e) {
        // $("#item_listing2 tbody").html("<tr><td colspan='10' style='text-align: center;'>Please wait... The values are getting refreshed...!!</td></tr>");
        changeTable2();
        $("#div_refresh_values").hide();
        // clickedAddBuyItems = 0;
    });    
    
    $(document).on('keyup click change','#qty_limit',  function (e) {
        var qtyLimit = this.value;
        qtyLimit = parseInt(qtyLimit);
        
        if(!isNaN(qtyLimit) && qtyLimit > 0){
            $("#allow_reg_price").prop("disabled", false);
        }else{
            $("#allow_reg_price").val("Y");
            $("#allow_reg_price").prop("disabled", true);
        };
    });
    
    var qtyLimit = '<?php echo (isset($data['qty_limit']) && ($data['qty_limit'] !== null)) ? $data['qty_limit'] : 0; ?>'
    qtyLimit = parseInt(qtyLimit);
    
    if(!isNaN(qtyLimit) && qtyLimit > 0){
        $("#allow_reg_price").prop("disabled", false);
    }else{
        $("#allow_reg_price").val("Y");
        $("#allow_reg_price").prop("disabled", true);
    };

    // Change the values in table2 when the parameters are changed
    function changeTable2() {
        prom_type       = $("#promotion_type").val();
        disc_type       = $("#promotion_discount_type").val();
        disc_value      = $("#promotion_discounted_value").val();
        mfg_disc_value  = $("#mfg_discount").val();
        addl_discount   = $("#promotion_addl_discount").val();
        buy_qty         = $("#promotion_buy_qty").val();

        var table2Listingdata = "";
        // existingPromItems = [];
        $("#item_listing2 tbody").html("");
        
        $.each(itemsAddedToBuyItems, function(i, v){            
            if(get_selected_buy_items_ajax && get_selected_buy_items_ajax.readyState != 4 ){
                // get_selected_buy_items_ajax.abort();
            }
        
            var postData = {buyItems : [v],prom_type : prom_type, disc_type : disc_type, disc_value : disc_value, mfg_disc_value : mfg_disc_value, addl_discount : addl_discount,buy_qty : buy_qty};
        
            if(prom_type == 10){
                
                postData['slab_price'] = $('#promotion_slab_price').val();
            }
    
            // $(this).prop('checked',false);
            get_selected_buy_items_ajax = $.ajax({
                url: get_selected_buy_items_url,
                type: 'post',
                data : postData,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success:function(data){
                    if(data) {
                        
                        $("#item_listing2 tbody").append(data);
                    } else {
                        
                    }
                }
            });
        });
    }
    
    $(document).on('keyup', '#promotion_code', function(){
        
        let promotion_code = $(this).val();
        
        let url = '<?php echo $data['check_promocode'] ?>';
        
        <?php if(!isset($data['prom_id'])){ ?>
            $.ajax({
                url : url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                data : {promotion_code: promotion_code},
                type : 'get',
                contentType: "application/json",
                dataType: 'json',
                success: function(data) {
                
                    if(data){
                        $('#check_promotion_code').html('This Promotion Code already exist');
                        $('#check_promotion_code').show();
                    }else{
                        $('#check_promotion_code').hide();
                    }
                    // setTimeout(function(){
                    //     window.location.reload();
                    //     $("div#divLoading").addClass('show');
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
            
                    $('#error_alias').html('<strong>'+ error_show +'</strong>');
                    $('#errorAliasModal').modal('show');
                    return false;
                }
            });
        <?php } ?>
    });
</script>

@endsection