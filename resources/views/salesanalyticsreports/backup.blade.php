@extends('layouts.master')
@section('title','Sales Analytics') @show
@section('main-content')

<?php
// dd($category); 
// $cat['vcategorycode'], $category
?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!-- <h1><?php //echo $heading_title; ?></h1> -->
      <ul class="breadcrumb">
        <?php //foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php //echo $breadcrumb['href']; ?>"><?php //echo $breadcrumb['text']; ?></a></li>
        <?php //} ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>Sales Analytics</h3>
      </div>
      <div class="panel-body">

        <?php if(isset($p_start_date)){ ?>
        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
            <a id="csv_export_btn" href=""{{route('salesanalyticsreport_gettingCSV')}}"" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</a>
            <a href="{{route('salesanalyticsreport_getprintpage')}}" id="btnPrint" class="pull-right" style="margin-right:10px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
            <a id="pdf_export_btn" href="{{route('salesanalyticsreport_savePDF')}}" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
          </div>
        </div>
        <?php } ?>
        <div class="clearfix"></div>
        
        <form method="post" id="filter_form">
          @csrf
          @method('post')
            <div class="row">                    
                <div class="col-md-2">
                    <select name="department[]" class="form-control" id="department_code" multiple="true" placeholder="Select Department">                        
                       <option value="All">All</option>
                       <?php if(isset($departments) && !empty($departments) ) { ?>
                            <?php foreach($departments as $dep) { ?>
                                <option value="<?= $dep['vdepcode'] ?>" <?php if(isset($department) && in_array($dep['vdepcode'], $department) ){ ?> selected <?php }?> ><?= $dep['vdepartmentname']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <?php 
                // dd($category);
                // foreach($categories as $cat) { 
                // dd($cat['vcategorycode']);
                // }
                
                ?>                
                <div class="col-md-2">
                    <select name="category[]" class="form-control" id="category_code" multiple="true">                        
                            <?php if(isset($categories) && !empty($categories)) { ?>
                                <?php foreach($categories as $cat) { ?>
                                   <option value="<?php echo $cat['vcategorycode'] ?>" <?php if(isset($category1) && in_array($cat['vcategorycode'], $category1) ){ ?> selected <?php }?> ><?php echo $cat['vcategoryname']; ?></option>
                                <?php } ?>
                            <?php } ?>
                    </select>
                </div>
                <?php 
                    // echo "<pre>";
                    // print_r($subcategories);
                    // die;
                ?>
                <div class="col-md-2">
                    <select name="subcategory[]" class="form-control" id="subcategory_code" multiple="true">                        
                            <?php if(isset($subcategories) && !empty($subcategories) ) { ?>
                                <?php foreach($subcategories as $subcat) { ?>
                                    <option value="<?php echo $subcat->subcat_id; ?>" <?php if(isset($subcategory1) && in_array($subcat->subcat_id, $subcategory1) ){ ?> selected <?php }?> ><?php echo $subcat->subcat_name; ?></option>
                                <?php } ?>
                            <?php } ?>
                    </select>
                </div>
                    
                <div class="col-md-2">
                  <input type="text" class="form-control" name="dates" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="dates" placeholder="Start Date" readonly>
                </div>
              
                <div class="col-md-0">
                  <input type="hidden" class="form-control" name="start_date" value="<?php echo isset($p_start_date) ? $p_start_date : ''; ?>" id="start_date" placeholder="Start Date" readonly>
                </div>
                <div class="col-md-0">
                  <input type="hidden" class="form-control" name="end_date" value="<?php echo isset($p_end_date) ? $p_end_date : ''; ?>" id="end_date" placeholder="End Date" readonly>
                </div>
                
                <div class="col-md-2">
                  <input type="submit" class="btn btn-success" value="Generate">
                </div>
              
            </div>
            
        </form>
        <?php if(isset($reports) && count($reports) > 0){ ?>

        <?php 
          

        ?>


        <br><br><br>
        <div class="row">
          <div class="col-md-12">
            <p><b>Date Range: </b><?php echo $p_start_date; ?> to <?php echo $p_end_date; ?></p>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <p><b>Store Name: </b><?php echo $storename; ?></p>
          </div>
          <div class="col-md-12">
            <p><b>Store Address: </b><?php echo $storeaddress; ?></p>
          </div>
          <div class="col-md-12">
            <p><b>Store Phone: </b><?php echo $storephone; ?></p>
          </div>
          
        </div>
        
        <div class="row">
                
              <div class="table-responsive">
                  
                <div class="col-md-12">
                    
                    <div class="col-md-10">
                        
                        <table class="table table-hover table_display sticky-header" >
                            <thead class="header">
                                <tr>
                                    <th>Dep./Cat./Sub Cat./Item Name</th>
                                    <th class='text-right' >Total Amount</th>
                                    <th class='text-right' >Qty Sold</th>
                                    <th class='text-right' >Ext. Cost Price</th>
                                    <th class='text-right' >Ext. Unit Price</th>
                                    <th class='text-right' >Gross Profit(%)</th>
                                </tr>
                            </thead>
                            <tr>
                                <th> Total:</th>
                                <th class='text-right' id="total_amount"> </th>
                                <th class='text-right' id="total_qty"> <?php echo $total_qty_sold1 ; ?> </th>
                                <th class='text-right' id="total_cost"> <?php echo "$",number_format($total_total_cost1,2); ?> </th>
                                <th class='text-right' id="total_price"> <?php echo "$",number_format($total_total_price1,2); ?> </th>
                                <th class='text-right' id="gross_profit_percent"> <?php echo number_format($total_gross_profit_percentage,2),"%"; ?> </th>
                            </tr>
                            <?php $i=0;?>
                            <?php foreach($out as $r) { ?>
                                
                                <?php if(isset($filters_selected) && $filters_selected == 'NoCategory') { ?>
                                    
                                    <?php if(isset($r['vdepname'])) { ?>
                                        <?php $i++;?>
                                        <tr class="first_row" id="child_<?php echo $i;?>">
                                            <th><?php echo isset($r['vdepname']) ? $r['vdepname']: ''; ?></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <?php $totalamount = $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = $counter =$subtotalgrossprofit= 0;?>
                                        
                                        <?php  foreach($r['details'] as $itmes) { ?>
                                            <?php 
                                              $total_qty_sold += $itmes['totalqty']; $total_total_cost += $itmes['totalCost']; 
                                              if($itmes['totalqty']!=0){
                                                $total_total_price += ($itmes['totalPrice']/$itmes['totalqty']);  
                                              }
                                              else{
                                                  $total_total_price =0; 
                                              }
                                                                                          
                                              $subtotalgrossprofit +=$itmes['totalPrice']-$itmes['totalCost'];                                              
                                              $totalamt = $itmes['totalPrice'];                                              
                                              $totalamount += $totalamt; 
                                            ?>
                                            <tr>
                                                <td style="display:none;" class="child_<?php echo $i;?>"><?php echo isset($itmes['vitemname']) ? $itmes['vitemname']: ''; ?></td>
                                                <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo "$", isset($totalamt) ? number_format($totalamt, 2): ''; ?></td>
                                                <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo isset($itmes['totalqty']) ? $itmes['totalqty']: ''; ?></td>                                                
                                                <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo isset($itmes['totalCost']) ? $itmes['totalCost']: ''; ?>
                                               
                                                <?php if($itmes['totalqty']!=0) { ?>
                                                <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo "$", isset($itmes['totalPrice']) ? number_format(($itmes['totalPrice']/$itmes['totalqty']),2): ''; ?></td>                                                
                                                <?php } else{ ?>
                                                    <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo "$", isset($itmes['totalPrice']) ? number_format((0),2): ''; ?></td>
                                                <?php } ?>
                                                
                                                <?php if($itmes['totalPrice']!=0) {?>
                                                <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo number_format((($itmes['totalPrice'] - $itmes['totalCost'])/$itmes['totalPrice'])*100,2),"%"; ?></td>                                              
                                                <?php } else{ ?>
                                                <td style="display:none;" class='child_<?php echo $i;?> text-right'><?php echo number_format(0,2),"%"; ?></td>
                                                <?php } ?>
                                                
                                            </tr>                                                
                                        <?php } ?>
                                        <tr> 
                                            <th> Sub Total:</th>
                                            <th class='text-right'> <?php echo "$",number_format($totalamount, 2); ?> </th>
                                            <th class='text-right'> <?php echo $total_qty_sold ;?> </th>
                                            <th class='text-right'> <?php echo "$",number_format($total_total_cost,2); ?> </th>
                                            <th class='text-right'> <?php echo "$",number_format($total_total_price,2); ?> </th>
                                            <?php if($total_total_price!=0) {?>
                                            <th class='text-right'> <?php echo number_format(($subtotalgrossprofit/$total_total_price)*100,2),"%"; ?> </th>
                                            <?php } else{ ?>
                                            <th class='text-right'> <?php echo number_format((0)*100,2),"%"; ?> </th>
                                            <?php } ?>
                                                
                                        </tr>
                                        
                                        <?php
                                            $totalamount1+=$totalamount;
                                            $total_qty_sold1+=$total_qty_sold;
                                            $total_total_cost1+=$total_total_cost;
                                            $total_total_price1+=$total_total_price;                                       
                                            $total_gross_profit1+=$subtotalgrossprofit; 
                                            if($total_total_price1!=0){
                                            $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
                                            }
                                            else{
                                                $total_gross_profit_percentage=0;
                                            }
                                        ?>
                                    <?php } ?>
                                <?php } ?>
                                
                                <?php if(isset($filters_selected) && $filters_selected == 'NoSubcategory') { ?>                                    
                                    <?php if(isset($r['vdepname'])) { ?>
                                        <?php $i++;?>
                                        <tr class="department_first_row" id="depchild_<?php echo $i;?>">
                                            <th><?php echo isset($r['vdepname']) ? $r['vdepname']: ''; ?></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <?php $totalamount = $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = $counter =$subtotalgrossprofit= 0;?>                                        
                                        <?php $m=0;?>
                                        <?php foreach($r as $cat) { ?>                                        
                                            <?php if(isset($cat['vcatname'])) { ?>
                                                <?php $m++;?>
                                                <tr class="category_row" id="catchild_<?php echo $m;?>">
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"><?php echo isset($cat['vcatname']) ? $cat['vcatname']: ''; ?></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                </tr>
                                            
                                                <?php  foreach($cat['details'] as $itmes) { ?>
                                                    <?php $total_qty_sold += $itmes['totalqty']; $total_total_cost += $itmes['totalCost']; 
                                                         if($itmes['totalqty']!=0){
                                                            $total_total_price += ($itmes['totalPrice']/$itmes['totalqty']);  
                                                          }
                                                          else{
                                                              $total_total_price =0; 
                                                          }
                                                        // $total_total_price += ($itmes['totalPrice']/$itmes['totalqty']);
                                                        $subtotalgrossprofit +=$itmes['totalPrice']-$itmes['totalCost'];
                                                        $totalamt = $itmes['totalPrice'];
                                                        $totalamount += $totalamt; 
                                                    ?>
                                                    <tr>
                                                        <td style="display:none;" class="catchild_<?php echo $m;?>"><?php echo isset($itmes['vitemname']) ? $itmes['vitemname']: ''; ?></td>
                                                        <td style="display:none;" class='catchild_<?php echo $m;?> text-right'><?php echo isset($totalamt) ? number_format($totalamt, 2): ''; ?></td>
                                                        <td style="display:none;" class='catchild_<?php echo $m;?> text-right'><?php echo isset($itmes['totalqty']) ? $itmes['totalqty']: ''; ?></td>
                                                        <td style="display:none;" class='catchild_<?php echo $m;?> text-right'><?php echo isset($itmes['totalCost']) ? $itmes['totalCost']: ''; ?>
                                                        <?php if($itmes['totalqty']!=0) { ?>
                                                        <td style="display:none;" class='catchild_<?php echo $m;?> text-right'><?php echo "$", isset($itmes['totalPrice']) ? number_format(($itmes['totalPrice']/$itmes['totalqty']),2): ''; ?></td>
                                                        <?php } else{ ?>
                                                        <td style="display:none;" class='catchild_<?php echo $m;?> text-right'><?php echo "$", isset($itmes['totalPrice']) ? number_format((0),2): ''; ?></td>
                                                        <?php } ?>
                                                        
                                                        <?php if($itmes['totalPrice']!=0) { ?>
                                                        <td style="display:none;" class='catchild_<?php echo $m;?> text-right'><?php echo number_format((($itmes['totalPrice'] - $itmes['totalCost'])/$itmes['totalPrice'])*100,2),"%"; ?></td>                                                      
                                                        <?php } else{ ?>
                                                        <td style="display:none;" class='catchild_<?php echo $m;?> text-right'><?php echo number_format((0)*100,2),"%"; ?></td> 
                                                         <?php } ?>
                                                       </tr>                                                    
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        <tr> 
                                            <th> Sub Total:</th>
                                            <th class='text-right'> <?php echo "$",number_format($totalamount, 2);?> </th>
                                            <th class='text-right'> <?php echo $total_qty_sold ;?> </th>
                                            <th class='text-right'> <?php echo "$",number_format($total_total_cost,2); ?> </th>
                                            <th class='text-right'> <?php echo "$",number_format($total_total_price,2); ?> </th>
                                            <?php if($total_total_price!=0) { ?>
                                            <th class='text-right'> <?php echo number_format(($subtotalgrossprofit/$total_total_price)*100,2),"%"; ?> </th>
                                            <?php } else{ ?>
                                             <th class='text-right'> <?php echo number_format((0)*100,2),"%"; ?> </th>
                                            <?php } ?>
                                            
                                        </tr>
                                        <?php
                                            $totalamount1+=$totalamount;
                                            $total_qty_sold1+=$total_qty_sold;
                                            $total_total_cost1+=$total_total_cost;
                                            $total_total_price1+=$total_total_price;                                       
                                            $total_gross_profit1+=$subtotalgrossprofit;    
                                            if($total_total_price1!=0){
                                            $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
                                            }
                                            else{
                                                $total_gross_profit_percentage=0;
                                            }
                                        ?>
                                    <?php } ?>
                                <?php } ?>
                                
                                <?php if(isset($filters_selected) && $filters_selected == 'All') { ?>                                    
                                    <?php if(isset($r['vdepname'])) { ?>
                                        <?php $i++;?>
                                        <tr class="department_first_row" id="depchild_<?php echo $i;?>">
                                            <th><?php echo isset($r['vdepname']) ? $r['vdepname']: ''; ?></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <?php $totalamount = $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = $counter =$subtotalgrossprofit= 0;?>
                                        <?php $m=0; $n=0;?>
                                        <?php foreach($r as $cat) { ?>                                            
                                            <?php if(isset($cat['vcatname'])) { ?>
                                                <?php $m++;?>
                                                <tr class="category_row" id="catchild_<?php echo $m;?>">
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"><?php echo isset($cat['vcatname']) ? $cat['vcatname']: ''; ?></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                    <th style="display:none;" class="depchild_<?php echo $i;?>"></th>
                                                </tr>
                                                <?php foreach($cat as $subcat) { ?>
                                                    <?php if(isset($subcat['subcat_name'])) { ?>
                                                        <?php $n++;?>
                                                        <tr class="subcategory_row" id="subcatchild_<?php echo $n;?>">
                                                            <th style="display:none;" class="catchild_<?php echo $m;?>"><?php echo isset($subcat['subcat_name']) ? $subcat['subcat_name']: ''; ?></th>
                                                            <th style="display:none;" class="catchild_<?php echo $m;?>"></th>
                                                            <th style="display:none;" class="catchild_<?php echo $m;?>"></th>
                                                            <th style="display:none;" class="catchild_<?php echo $m;?>"></th>
                                                            <th style="display:none;" class="catchild_<?php echo $m;?>"></th>
                                                            <th style="display:none;" class="catchild_<?php echo $m;?>"></th>
                                                        </tr>
                                                        <?php  foreach($subcat['details'] as $itmes) { ?>
                                                            <?php $total_qty_sold += $itmes['totalqty']; $total_total_cost += $itmes['totalCost']; 
                                                              if($itmes['totalqty']!=0){
                                                              $total_total_price += ($itmes['totalPrice']/$itmes['totalqty']);     
                                                              }
                                                              else{
                                                                $total_total_price += 0;       
                                                              }
                                                              $subtotalgrossprofit +=$itmes['totalPrice']-$itmes['totalCost'];
                                                              $totalamt = $itmes['totalPrice'];
                                                              $totalamount += $totalamt; 
                                                            ?>
                                                            <tr>
                                                                <td style="display:none;" class="subcatchild_<?php echo $n;?>"><?php echo isset($itmes['vitemname']) ? $itmes['vitemname']: ''; ?></td>
                                                                <td style="display:none;" class='subcatchild_<?php echo $n;?> text-right'><?php echo "$", isset($totalamt) ? number_format($totalamt, 2): ''; ?></td>
                                                                <td style="display:none;" class='subcatchild_<?php echo $n;?> text-right'><?php echo isset($itmes['totalqty']) ? $itmes['totalqty']: ''; ?></td>
                                                                
                                                                <td style="display:none;" class='subcatchild_<?php echo $n;?> text-right'><?php echo isset($itmes['totalCost']) ? $itmes['totalCost']: ''; ?>
                                                                <?php if($itmes['totalqty']!=0) { ?>
                                                                <td style="display:none;" class='subcatchild_<?php echo $n;?> text-right'><?php echo "$", isset($itmes['totalPrice']) ? number_format(($itmes['totalPrice']/$itmes['totalqty']),2): ''; ?></td>
                                                                <?php } else { ?>
                                                                 <td style="display:none;" class='subcatchild_<?php echo $n;?> text-right'><?php echo "$", isset($itmes['totalPrice']) ? number_format(0,2): ''; ?></td>
                                                                <?php } ?>
                                                                
                                                                <?php if($itmes['totalPrice']!=0) { ?>
                                                                <td style="display:none;" class='subcatchild_<?php echo $n;?> text-right'><?php echo number_format((($itmes['totalPrice'] - $itmes['totalCost'])/$itmes['totalPrice'])*100,2),"%"; ?></td>
                                                                <?php } else { ?>
                                                                 <td style="display:none;" class='subcatchild_<?php echo $n;?> text-right'><?php echo number_format(0,2),"%"; ?></td>
                                                                <?php } ?>
                                                                   
                                                            </tr>                                                            
                                                        <?php } ?>
                                                    <?php } ?>  
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        <tr> 
                                            <th> Sub Total:</th>
                                            <th class='text-right'> <?php echo "$",number_format($totalamount, 2); ?> </th>
                                            <th class='text-right'> <?php echo $total_qty_sold ;?> </th>
                                            <th class='text-right'> <?php echo "$",number_format($total_total_cost,2); ?> </th>
                                            <th class='text-right'> <?php echo "$",number_format($total_total_price,2); ?> </th>
                                            <?php if($total_total_price!=0){?>
                                            <th class='text-right'> <?php echo number_format(($subtotalgrossprofit/$total_total_price)*100,2),"%"; ?> </th>
                                            <?php } else { ?>
                                            <th class='text-right'> <?php echo number_format((0)*100,2),"%"; ?> </th>
                                            <?php } ?> 
                                            
                                        </tr>
                                        <?php
                                            $totalamount1+=$totalamount;
                                            $total_qty_sold1+=$total_qty_sold;
                                            $total_total_cost1+=$total_total_cost;
                                            $total_total_price1+=$total_total_price;                                       
                                            $total_gross_profit1+=$subtotalgrossprofit; 
                                            if($total_total_price1!=0){
                                            $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
                                            }
                                            else{
                                                $total_gross_profit_percentage=0;
                                            }
                                        ?>
                                    <?php } ?>
                                <?php } ?>                                   
                            <?php } ?>
                            <tr style="display:none;">
                             <br>
                            <br>
                                <th> Total:</th>
                                <th class='text-right' id="bottom_total_amount"> <?php echo "$",number_format($totalamount1, 2); ?> </th>
                                <th class='text-right' id="bottom_total_qty"> <?php echo $total_qty_sold1 ; ?> </th>
                                <th class='text-right' id="bottom_total_cost"> <?php echo "$",number_format($total_total_cost1,2); ?> </th>
                                <th class='text-right' id="bottom_total_price"> <?php echo "$",number_format($total_total_price1,2); ?> </th>
                                <th class='text-right' id="bottom_gross_profit_percent"> <?php echo number_format($total_gross_profit_percentage,2),"%"; ?> </th>
                            </tr>
                        </table>
                    </div>
                </div>
              </div>
            </div>
        <?php }else{ ?>
          <?php if(isset($p_start_date)){ ?>
            <div class="row">
              <div class="col-md-12"><br><br>
                <div class="alert alert-info text-center">
                  <strong>Sorry no data found!</strong>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" type="text/css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="{{ asset('javascript/table-fixed-header.js') }}"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>


<style type="text/css">
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

  .table.table-bordered.table-striped.table-hover thead > tr {
    background-color: #2486c6;
    color: #fff;
  }
  tr.first_row{
        cursor:pointer;
      }
    
      tr.first_row > th{
        background-color: #585858;
        color: #fff;
        /*border: 1px solid #808080 !important;*/
      }

  tr.department_first_row{
    cursor:pointer;
  }

  tr.department_first_row > th{
    background-color: #585858;
    color: #fff;
    /*border: 1px solid #808080 !important;*/
  }
  
  tr.category_row{
    cursor:pointer;
  }

  tr.category_row > th{
    background-color: #83888f;
    color: #fff;
    /*border: 1px solid #808080 !important;*/
  }
  
  tr.subcategory_row{
    cursor:pointer;
  }

  tr.subcategory_row > th{
    background-color: #d1cdcd;
    color: #fff;
    /*border: 1px solid #808080 !important;*/
  }

  tr.header > th, tr.header > th > span{
    font-size: 15px;
  }

  tr.header > th > span{
    float: right;
  }

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
  
   
    $(document).ready(function() {
      $('input[name="dates"]').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'M-D-Y'
        }
      });
      
      $(function() {
       var p_start_date = "<?php echo isset($p_start_date) ? $p_start_date : '';?>";
        var p_end_date = "<?php echo isset($p_end_date) ? $p_end_date : '';?>";
        
        if(p_start_date !== '' && p_end_date !== ''){
            var start = moment(p_start_date);
            var end = moment(p_end_date);
        } else {
            var start = moment().subtract(29, 'days');
            var end = moment();
        }
        
        function cb(start, end) {
            $('input[name="dates"]').html(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
            console.log(start.format('YYYY-MM-DD'));
        }

        $('input[name="dates"]').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
              'Today': [moment(), moment()],
              'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
      });
    });

  $('.first_row').click(function(){
    var trclass = $(this).attr('id');
    $("."+trclass).toggle();
  });

  $('.department_first_row').click(function(){
      var trclass = $(this).attr('id');
      $("."+trclass).toggle();
  });

  $('.category_row').click(function(){
      var trclass = $(this).attr('id');
      $("."+trclass).toggle();
  });

  $('.subcategory_row').click(function(){
      var trclass = $(this).attr('id');
      $("."+trclass).toggle();
  });

  $(document).on('submit', '#filter_form', function(event) { 
    if ($('#department_code').val() == null) {
        bootbox.alert({
            size: 'small',
            title: "Attention",
            message: "Please Select Department ",
            callback: function() {}
        });
        return false;
    } 
    
    // if ($('#category_code').val() == null) {
    //     bootbox.alert({
    //         size: 'small',
    //         title: "Attention",
    //         message: "Please Select Category ",
    //         callback: function() {}
    //     });
    //     return false;
    // } 
    
    if($('#report_by').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Report", 
        callback: function(){}
      });
      return false;
    }

    if($('#report_by').val() == '1'){
        if($('#report_data > option:selected').length == 0){
          bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: "Please Select Category", 
            callback: function(){}
          });
          return false;
        }
    }else if($('#report_by').val() == '2'){
      if($('#report_data > option:selected').length == 0){
        bootbox.alert({ 
            size: 'small',
            title: "Attention", 
            message: "Please Select Department", 
            callback: function(){}
          });
        return false;
      }
    }

    if($('#report_data').val() == ''){
      if($('#report_by').val() == '1'){
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please Select Category", 
          callback: function(){}
        });
        return false;
      }else if($('#report_by').val() == '2'){
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please Select Department", 
          callback: function(){}
        });
        return false;
      }
    }

    if($('#dates').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select Start Date", 
        callback: function(){}
      });
      return false;
    }

    if($('#dates').val() == ''){
      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "Please Select End Date", 
        callback: function(){}
      });
      return false;
    }

    if($('input[name="dates"]').val() != ''){
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
      var dates  = $("#dates").val();
      dates_array =dates.split("-");
      start_date = dates_array[0];
      end_date = dates_array[1];   
    
      //format the start date to month-date-year
      var formattedStartDate = new Date(start_date);
      var d = formattedStartDate.getDate();
      var m =  formattedStartDate.getMonth();
      m += 1;  // JavaScript months are 0-11
      m = ('0'+m).slice(-2);
      
      var y = formattedStartDate.getFullYear();
      $('input[name="start_date"]').val(m+'-'+d+'-'+y);     
      $('input[name="end_date"]').val(end_date);
      
      var formattedendDate = new Date(end_date);
      var de = formattedendDate.getDate();
      var me =  formattedendDate.getMonth();
      me += 1;  // JavaScript months are 0-11
      me = ('0'+me).slice(-2);      
      var ye = formattedendDate.getFullYear();      
      $('input[name="end_date"]').val(me+'-'+de+'-'+ye);  
      $("div#divLoading").addClass('show');
    
  });

    $(document).ready(function() {
      $("#btnPrint").printPage();
    });

    $('.header').click(function(){
      $(this).toggleClass('expand').nextUntil('tr.add_space').slideToggle(100);
    });

    $(window).load(function() {
      $("div#divLoading").removeClass('show');
    });

  const saveData = (function () {
    const a = document.createElement("a");
    document.body.appendChild(a);
    a.style = "display: none";
    return function (data, fileName) {
        const blob = new Blob([data], {type: "octet/stream"}),
            url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = fileName;
        a.click();
        window.URL.revokeObjectURL(url);
    };
  }());

  $(document).on("click", "#csv_export_btn", function (event) {
    event.preventDefault();
    $("div#divLoading").addClass('show');
      var csv_export_url = "{{route('salesanalyticsreport_gettingCSV')}}";    
      csv_export_url = csv_export_url.replace(/&amp;/g, '&');
      $.ajax({
        url : csv_export_url,
        type : 'GET',
      }).done(function(response){
        const data = response,
        fileName = "profit-loss-report.csv";
        saveData(data, fileName);
        $("div#divLoading").removeClass('show');        
      });
  });
  
  $(document).on("click", "#pdf_export_btn", function (event) {

    event.preventDefault();

    $("div#divLoading").addClass('show');

    var pdf_export_url = "{{route('salesanalyticsreport_savePDF')}}";
  
    pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

    var req = new XMLHttpRequest();
    req.open("GET", pdf_export_url, true);
    req.responseType = "blob";
    req.onreadystatechange = function () {
      if (req.readyState === 4 && req.status === 200) {

        if (typeof window.navigator.msSaveBlob === 'function') {
          window.navigator.msSaveBlob(req.response, "Profit-and-loss-Report.pdf");
        } else {
          var blob = req.response;
          var link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = "Profit-and-loss-Report.pdf";

          // append the link to the document body

          document.body.appendChild(link);

          link.click();
        }
      }
      $("div#divLoading").removeClass('show');
    };
    req.send();
    
  });

  $(document).on('change', 'input[name="start_date"],input[name="end_date"]', function(event) {
    event.preventDefault();
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
  });
  $(document).ready(function(){     
        $selectElement = $('select[name="department[]"]').select2({
            placeholder: "Please select Department",
        });
        
        $selectElement2 = $('select[name="category[]"]').select2({
            placeholder: "Please select Category",
        });
        
        $selectElement3 = $('select[name="subcategory[]"]').select2({
            placeholder: "Please select Sub Category",
        });
    });
  
    $("#department_code").change(function(){
        var deptCode = $(this).val();
        var input = {};
        input['dept_code'] = deptCode;  
        if(deptCode != ""){            
            var url = "{{route('salesanalyticsreport_getcategory')}}";                
                url = url.replace(/&amp;/g, '&');
            $.ajax({
                url : url,
                data : input,
                type : 'GET',
                contentType: "application/json",
                dataType: 'text json',
                success: function(response) {            
                    console.log(response);
                    if(response.length > 0){                        
                        $('select[name="category[]"]').select2().empty().select2({placeholder: "Please Select Category",data: response});                       
                    }
                    setTimeout(function(){
                        $('#successAliasModal').modal('hide');
                    }, 3000);
                },
                error: function(xhr) { 
                    return false;
                }
            });
        }
    });
    
     $("#category_code").change(function(){
        var cat_id = $(this).val();
        var input = {};
        input['cat_id'] = cat_id;
        if(cat_id != ""){    
            var url = "{{route('salesanalyticsreport_getsubcategory') }}";
                url = url.replace(/&amp;/g, '&');
            $.ajax({
                url : url,
                data : input,
                type : 'GET',
                contentType: "application/json",
                dataType: 'text json',
                success: function(response) {
                    if(response.length > 0){
                       $('select[name="subcategory[]"]').select2().empty().select2({placeholder: "Select Sub Category", data: response});
                    }
                    setTimeout(function(){
                        $('#successAliasModal').modal('hide');
                    }, 3000);
                },
                error: function(xhr) {
                    return false;
                }
            });
        }
    });
    $('.table').fixedHeader({
      topOffset: 0
    });
  $(document).ready(function(){
      var total_amount = $('#bottom_total_amount').text();
      var total_qty = $('#bottom_total_qty').text();
      var total_cost = $('#bottom_total_cost').text();
      var total_price = $('#bottom_total_price').text();
      var gross_profit_percent = $('#bottom_gross_profit_percent').text();
      
      $('#total_amount').text(total_amount);
      $('#total_qty').text(total_qty);
      $('#total_cost').text(total_cost);
      $('#total_price').text(total_price);
      $('#gross_profit_percent').text(gross_profit_percent);
  });
</script>

@endsection