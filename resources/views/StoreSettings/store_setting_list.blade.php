@extends('layouts.layout')
@section('title', 'Store Setting')
@section('main-content')
<div id="content">
  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase">STORE SETTINGS</span>
            </div>
            <div class="nav-submenu">
                <button type="button" id="save_button"  class="btn btn-gray headerblack  buttons_menu " title="Save" class="btn btn-gray headerblack  buttons_menu "><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
  </nav>
  <section class="section-content py-6">
    <div class="page-header">
      <div class="container">
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>


        <div class="panel panel-default">
          <div class="panel-body">
            <form action="" method="post" enctype="multipart/form-data" id="form-store-setting">
              @csrf
              <div class="table-responsive">
                <table id="table_store_setting" class="table table-hover" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                <?php if ($store_settings) { ?>
                  <thead style="background-color: #286fb7!important;">
                    <tr>
                      <th class="col-xs-1 headername text-uppercase text-light"><?php echo $column_setting_name; ?></th>
                      <th class="col-xs-1 headername text-uppercase text-light"><?php echo $column_setting_value; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                  
                    <tr>
                      <td class="text-left">
                        <b>Required Password </b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[RequiredPassword]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['RequiredPassword']) && $store_settings['RequiredPassword']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Same Product</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[SameProduct]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['SameProduct']) && $store_settings['SameProduct']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Geographical Information</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[Geographical Information]" id="" class="form-control">
                          <?php if(isset($store_settings['Geographical Information']) && $store_settings['Geographical Information']['vsettingvalue'] == $None){?>
                            <option value="<?php echo $None; ?>" selected="selected"><?php echo $None; ?></option>
                          <?php }else{ ?>
                            <option value="<?php echo $None; ?>"><?php echo $None; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Allow discount less then cost price</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[Allowdiscountlessthencostprice]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['Allowdiscountlessthencostprice']) && $store_settings['Allowdiscountlessthencostprice']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Allow Update Qoh </b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[AllowUpdateQoh]" id="" class="form-control">
                        <?php foreach($arr_y_n as $array_y_n){ ?>
                          <?php if(isset($store_settings['AllowUpdateQoh']) && $store_settings['AllowUpdateQoh']['vsettingvalue'] == $array_y_n){?>
                            <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                          <?php }else{ ?>
                            <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                          <?php } ?>
                        <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Start Time </b>
                      </td>
                      <?php
                        if(isset($store_settings['StoreTime_s']['vsettingvalue']) && $store_settings['StoreTime_s']['vsettingvalue'] != ''){
                          $s_time = explode(",",$store_settings['StoreTime_s']['vsettingvalue']);
                          $s_time_start = $s_time[0];
                        }else{
                          $s_time_start = '';
                        }
                        
                        if(isset($store_settings['StoreTime_e']['vsettingvalue']) && $store_settings['StoreTime_e']['vsettingvalue'] != ''){
                          $s_time = explode(",",$store_settings['StoreTime_e']['vsettingvalue']);
                          $s_time_end = $s_time[0];
                        }else{
                          $s_time_end = '';
                        }
                      ?>
                      <td class="text-left">
                        <select name="store_setting[StoreTime_s]" id="" class="form-control">
                          <?php if($s_time_start != ''){ ?>
                            <?php foreach ($time_arr as $t_arr) { ?>
                              <?php if($t_arr == $s_time_start){ ?>
                                  <option value="<?php echo $t_arr; ?>" selected="selected"><?php echo $t_arr; ?></option>  
                              <?php }else{ ?>
                                    <option value="<?php echo $t_arr; ?>" ><?php echo $t_arr; ?></option>
                              <?php } ?>
                            <?php } ?>
                          <?php }else{ ?>
                            <?php foreach ($time_arr as $t_arr) { ?>
                              <?php if($t_arr == '09:00 am'){ ?>
                                  <option value="<?php echo $t_arr; ?>" selected="selected"><?php echo $t_arr; ?></option>  
                              <?php }else{ ?>
                                    <option value="<?php echo $t_arr; ?>" ><?php echo $t_arr; ?></option>
                              <?php } ?>
                            <?php } ?>
                          <?php } ?>
              
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left"><b>End Time </b></td>
                    
                      <td class="text-left">
                      
                        <select name="store_setting[StoreTime_e]" id="" class="form-control">
                          <?php if($s_time_end != ''){ ?>
                            <?php foreach ($time_arr as $t_arr) { ?>
                              <?php if($t_arr == $s_time_end){ ?>
                                  <option value="<?php echo $t_arr; ?>" selected="selected"><?php echo $t_arr; ?></option>  
                              <?php }else{ ?>
                                    <option value="<?php echo $t_arr; ?>" ><?php echo $t_arr; ?></option>
                              <?php } ?>
                            <?php } ?>
                          <?php }else{ ?>
                            <?php foreach ($time_arr as $t_arr) { ?>
                              <?php if($t_arr == '09:00 am'){ ?>
                                  <option value="<?php echo $t_arr; ?>" selected="selected"><?php echo $t_arr; ?></option>  
                              <?php }else{ ?>
                                    <option value="<?php echo $t_arr; ?>" ><?php echo $t_arr; ?></option>
                              <?php } ?>
                            <?php } ?>
                          <?php } ?>
              
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left"><b>Default age verification date </b></td>
                    
                      <td class="text-left">
                        <input type="text" class="form-control" name="store_setting[Defaultageverificationdate]" id="datePicker" value="<?php echo isset($store_settings['Defaultageverificationdate']['vsettingvalue']) ? $store_settings['Defaultageverificationdate']['vsettingvalue']: ''; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Tax1 seleted for new Item? </b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[Tax1seletedfornewItem]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['Tax1seletedfornewItem']) && $store_settings['Tax1seletedfornewItem']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Display low inventory warnings </b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[ShowlowlevelInventory]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['ShowlowlevelInventory']) && $store_settings['ShowlowlevelInventory']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Ask Beginning balance in dollar</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[AskBeginningbalanceindollar]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['AskBeginningbalanceindollar']) && $store_settings['AskBeginningbalanceindollar']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Ask Reason for void transaction </b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[AskReasonforvoidtransaction]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['AskReasonforvoidtransaction']) && $store_settings['AskReasonforvoidtransaction']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Update all register quick item once</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[Updateallregisterquickitemonce]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['Updateallregisterquickitemonce']) && $store_settings['Updateallregisterquickitemonce']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Amount for item price confirmation</b>
                      </td>
                      <td class="text-left">
                      <?php 
                        if(isset($store_settings['AskItemPriceConfirmation']['vsettingvalue']) && $store_settings['AskItemPriceConfirmation']['vsettingvalue'] != ''){
                          $price_conf = $store_settings['AskItemPriceConfirmation']['vsettingvalue'];
                        }else{
                          $price_conf = 0;
                        }
                      ?>
                      <input type="text" name="store_setting[AskItemPriceConfirmation]" value="<?php echo $price_conf; ?>" class="form-control">
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Show quotation</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[Showquotation]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['Showquotation']) && $store_settings['Showquotation']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
    
                    <tr>
                      <td class="text-left">
                        <b>Update Inventory</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[QutaInventory]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['QutaInventory']) && $store_settings['QutaInventory']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Calculate tax</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[QutaTax]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['QutaTax']) && $store_settings['QutaTax']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Do backup when login</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[Dobackupwhenlogin]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['Dobackupwhenlogin']) && $store_settings['Dobackupwhenlogin']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Email (Separate by ;)</b>
                      </td>
                      <td class="text-left">
                      <input type="text" name="store_setting[AlertEmail]" value="<?php echo isset($store_settings['AlertEmail']['vsettingvalue']) ? $store_settings['AlertEmail']['vsettingvalue'] : ''; ?>" class="form-control">
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Allow zero item price to update?</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[ZeroItemPriceUpdate]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['ZeroItemPriceUpdate']) && $store_settings['ZeroItemPriceUpdate']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Show changeDue Dialog</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[ShowChangeDuewnd]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['ShowChangeDuewnd']) && $store_settings['ShowChangeDuewnd']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Enable Add Quick Item Screen</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[EnableQuickItemScreen]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['EnableQuickItemScreen']) && $store_settings['EnableQuickItemScreen']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Send Z ReportEmail :(Separate by ;)</b>
                      </td>
                      <td class="text-left">
                      <input type="text" name="store_setting[ZreportEmail]" value="<?php echo isset($store_settings['ZreportEmail']['vsettingvalue']) ? $store_settings['ZreportEmail']['vsettingvalue']: ''; ?>" class="form-control">
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Enable WICITEM</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[EnableWicItem]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['EnableWicItem']) && $store_settings['EnableWicItem']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>UPC Format</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[UpcType]" id="" class="form-control">
                          <?php foreach($arr_upca_upce as $arr_upca_upce){ ?>
                            <?php if(isset($store_settings['UpcType']) && $store_settings['UpcType']['vsettingvalue'] == $arr_upca_upce){?>
                              <option value="<?php echo $arr_upca_upce; ?>" selected="selected"><?php echo $arr_upca_upce; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $arr_upca_upce; ?>"><?php echo $arr_upca_upce; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                  <tr>
                      <td class="text-left">
                        <b>Add First Digit</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[UpcFirstDigitAdded]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['UpcFirstDigitAdded']) && $store_settings['UpcFirstDigitAdded']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Add Last Digit</b>
                      </td>
                      <td class="text-left">
                        <select name="store_setting[UpcLastDigitAdded]" id="" class="form-control">
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['UpcLastDigitAdded']) && $store_settings['UpcLastDigitAdded']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <b>Auto End Of Day</b>
                      </td>
                      <td class="text-left" style="white-space: nowrap">
                        <select name="store_setting[EndOfDay]" id="end_of_day" class="form-control" />
                          <?php foreach($arr_y_n as $array_y_n){ ?>
                            <?php if(isset($store_settings['EndOfDay']) && $store_settings['EndOfDay']['vsettingvalue'] == $array_y_n){?>
                              <option value="<?php echo $array_y_n; ?>" selected="selected"><?php echo $array_y_n; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $array_y_n; ?>"><?php echo $array_y_n; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                        <div class="input-group clockpicker" data-placement="right" data-align="top" data-autoclose="true" >
                            <input type="text" class="form-control" name="store_setting[EndOfDayTime]" value="<?php echo isset($store_settings['EndOfDayTime']['vsettingvalue']) ? $store_settings['EndOfDayTime']['vsettingvalue']: ''; ?>" readonly />
                            <span class="input-group-addon">
                                <span class="fa fa-clock-o fa-fw"></span>
                            </span>
                        </div>
                      </td>
                      
                    </tr>
                    
                    <!--<tr id="EndOfDayTime">
                      <td class="text-left">
                        <b>Auto End Of Day Time</b>
                      </td>
                      <td class="text-left">
                        <div class="input-group clockpicker" data-placement="right" data-align="top" data-autoclose="true">
                            <input type="text" class="form-control" name="store_setting[EndOfDayTime]" value="<?php echo isset($store_settings['EndOfDayTime']['vsettingvalue']) ? $store_settings['EndOfDayTime']['vsettingvalue']: ''; ?>">
                            <span class="input-group-addon">
                                <span class="fa fa-clock-o fa-fw"></span>
                            </span>
                        </div>
                      </td>
                    </tr> -->
                    <tr>
                      <td><b>Current Time (EDT)</b></td>
                      <td>
                        <b id="display_time">
                            
                        </b>
                      </td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                      <td colspan="7" class="text-center"><?php echo $text_no_results;?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </form>
          </div>
        </div>
      </div>
  </section>
</div>
@endsection

@section('page-script')

<script type="text/javascript">
    $(document).on('click', '#save_button', function(event) {
      event.preventDefault();
      
      var edit_url = '<?php echo $edit_list; ?>';
      
      edit_url = edit_url.replace(/&amp;/g, '&');
      
      $('#form-store-setting').attr('action', edit_url);
      $('#form-store-setting').submit();
      $("div#divLoading").addClass('show');
      
    });
  </script>
  
  <!-- date picker -->
  <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" defer></script>
  
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css">
  
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js"></script>
  
  <script>
    $(function(){
      $("#datePicker").datepicker({
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,
      });
    });
  </script>
  <!-- date picker -->
  
  <?php echo $footer; ?>
  
  
  
  <script type="text/javascript">
      $(document).ready(function(){
          $('.clockpicker').clockpicker({
              // twelvehour: true,
          });
          var end_of_day = $('#end_of_day').val();
          if(end_of_day === 'Yes'){
              $('.clockpicker').show();
          }else{
              $('.clockpicker').hide();
          }
      });
      
      $(document).on('change', '#end_of_day', function(){
          var end_of_day = $('#end_of_day').val();
          if(end_of_day === 'Yes'){
              $('.clockpicker').show();
          }else{
              $('.clockpicker').hide();
          }
      });
  </script>
  
  
  <script>
    $(document).ready(function() {
        function get_time(){
            var url = '<?php echo $gettime_url; ?>';
            url = url.replace(/&amp;/g, '&');
            $.ajax({
                url: url,
                success: function(data) {
                    $('#display_time').text(data);
                },
            });
        }
        // setInterval(get_time, 1000);
    });
  </script>
@endsection