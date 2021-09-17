<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "Profit Loss Report" ?></h3>
    <hr style="height:1px; background-color:black;">
   
</div> 

<div class="panel-body">
        
        <div class="row">
          <div class="col-md-6 pull-left">
            <p><b>From: </b><?php echo $p_start_date; ?> To <?php echo $p_end_date; ?></p>
          </div>
        </div>
        <div class="row">  
          <div class="col-md-6 pull-left">
            <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
          </div>
        </div>
        
        <div class="row">
          
          <div class="col-md-6 pull-left">
            <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
          </div>
        </div>
        
        <div class="row">
          
          <div class="col-md-6 pull-left">
            <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
          </div>
        </div>
        <hr>

        <?php if(isset($reports) && count($reports) > 0){ ?>
            <?php 
                              
            $total_qty_sold1=$total_total_cost1=$total_total_price1=$total_markup1=$total_gross_profit1=$total_gross_profit_percentage=0;
            ?>
        <div class="row">
          <div class="col-md-12">
          <br>
            <table class="table <!--table-bordered table-striped --> table-hover">
                            
                <tr>
                    <th>Name</th>
                    <th class='text-right'>Unit Cost</th>
                    <th class='text-right'>Price</th>
                    <th class='text-right'>Qty Sold</th>
                    <th class='text-right'>Total Cost</th>
                    <th class='text-right'>Total Price</th>
                    <th class='text-right'>Mark Up(%)</th>
                    <th class='text-right'>Gross Profit</th>
                    <th class='text-right'>Gross Profit(%)</th>
                    
                </tr>
                
                <?php foreach($out as $r) { ?>
                    
                    <tr>
                        <th><?php echo isset($r['vname']) ? $r['vname']: ''; ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        
                    </tr>
                    <?php $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = 0;?>
                    <?php foreach($r['details'] as $itmes) { ?>
                        <?php $total_qty_sold += $itmes['TotalQty']; $total_total_cost += $itmes['TotCostPrice']; $total_total_price += $itmes['TOTUNITPRICE']; $total_markup += $itmes['AmountPer']; $total_gross_profit += $itmes['Amount']; ?>
                        <tr>
                            <td><?php echo isset($itmes['vITemName']) ? $itmes['vITemName']: ''; ?></td>
                            <td class='text-right'><?php echo isset($itmes['DCOSTPRICE']) ? $itmes['DCOSTPRICE']: ''; ?></td>
                            <td class='text-right'><?php echo isset($itmes['dUnitPrice']) ? $itmes['dUnitPrice']: ''; ?></td>
                            <td class='text-right'><?php echo isset($itmes['TotalQty']) ? number_format($itmes['TotalQty'],2): ''; ?></td>
                            <td class='text-right'><?php echo isset($itmes['TotCostPrice']) ? $itmes['TotCostPrice']: ''; ?></td>
                            <td class='text-right'><?php echo isset($itmes['TOTUNITPRICE']) ? $itmes['TOTUNITPRICE']: ''; ?></td>
                            <td class='text-right'><?php echo isset($itmes['AmountPer']) ? number_format($itmes['AmountPer'],2): ''; ?></td>
                            <td class='text-right'><?php echo isset($itmes['Amount']) ? $itmes['Amount']: ''; ?></td>
                            
                            <?php if ($itmes['TOTUNITPRICE'] !=0){?>
                             <td class='text-right'><?php echo number_format(($itmes['Amount']/$itmes['TOTUNITPRICE'])*100,2); ?></td>
                            <?php } else { ?>
                            
                            <td class='text-right'><?php echo number_format(($itmes['Amount']),2); ?></td>
                            <?php }  ?>
                        </tr>
                    <?php } ?>
                    <tr> 
                        <th> Sub Total:</th>
                        <th></th>
                        <th></th>
                        <th class='text-right'> <?php echo number_format($total_qty_sold,2); ?> </th>
                        <th class='text-right'> <?php echo number_format($total_total_cost,2); ?> </th>
                        <th class='text-right'> <?php echo number_format($total_total_price,2); ?> </th>
                        <?php if($total_total_cost != '0.00'){
                                    $subtotal_markup = (($total_gross_profit/$total_total_cost) * 100);
                                    }
                                    else{
                                    $subtotal_markup=100.00;
                                    }?>
                        <th class='text-right'> <?php echo number_format($subtotal_markup,2); ?> </th>
                        <th class='text-right'> <?php echo number_format($total_gross_profit,2); ?> </th>
                        <th class='text-right'> <?php echo number_format(($total_gross_profit/$total_total_price)*100,2); ?> </th>
                    </tr>
                    <?php
                                   $total_qty_sold1+=$total_qty_sold;
                                   $total_total_cost1+=$total_total_cost;
                                   $total_total_price1+=$total_total_price;
                                   
                                   $total_gross_profit1+=$total_gross_profit;
                                   if($total_total_cost1 !=0 &&  $total_total_cost1 !=0)
                                   {
                                      $total_markup1=(($total_gross_profit1)/$total_total_cost1)*100;
                                   }
                                   else{
                                      $total_markup1=0;
                                   }
                                   if($total_gross_profit1 !=0 &&  $total_total_price1 !=0)
                                   {
                                   $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
                                   }
                                   else{
                                      $total_gross_profit_percentage=0;
                                   }
                                   ?>
                <?php } ?>
                  </tr><tr>
                            <br>
                            <br>
                            
                            <th> Total:</th>
                            <th></th>
                            <th></th>
                            <th class='text-right'> <?php echo $total_qty_sold1 ; ?> </th>
                                    <th class='text-right'> <?php echo "$",number_format($total_total_cost1,2); ?> </th>
                                    <th class='text-right'> <?php echo "$",number_format($total_total_price1,2); ?> </th>
                                     <th class='text-right'> <?php echo number_format($total_markup1,2),"%"; ?></th>
                                    <th class='text-right'> <?php echo "$",number_format($total_gross_profit1,2); ?> </th>
                                    <th class='text-right'> <?php echo number_format($total_gross_profit_percentage,2),"%"; ?> </th>
                            </tr>
                        </table>
            </table>
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