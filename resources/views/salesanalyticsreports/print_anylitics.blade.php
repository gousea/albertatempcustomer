<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<div id="content">
  <div class="container-fluid">
    <div class="" style="margin-top:2%;">
      <div class="text-center">
        <h3 class="panel-title" style="font-weight:bold;font-size:24px;"><?php echo $heading_title; ?></h3>
      </div>
      
        <div class="panel-body">
            <div class="row">
              <div class="col-md-6 pull-left">
                <p><b>Date Range: </b><?php echo $p_start_date; ?> to <?php echo $p_end_date; ?></p>
              </div>
              <div class="col-md-6 pull-right">
                <p><b>Store Name: </b><?php echo $storename; ?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 pull-left">
                <p></p>
              </div>
              <div class="col-md-6 pull-right">
                <p><b>Store Address: </b><?php echo $storeaddress; ?></p>
              </div>
            </div>
        
            <div class="row">
              <div class="col-md-6 pull-left">
                <p></p>
              </div>
              <div class="col-md-6 pull-right">
                <p><b>Store Phone: </b><?php echo $storephone; ?></p>
              </div>
            </div>
             <hr>
            <?php if (isset($reports) && count($reports) > 0){ ?>
        <div class="row">
          <div class="col-md-12">
          <br>
            <table class="table <!--table-bordered table-striped --> table-hover">
                <tr>
                    <th>Dep./Cat./Sub Cat. Name</th>
                    <th class='text-right' >Toatal Amount</th>
                    <th class='text-right'>Qty Sold</th>
                    <th class='text-right'>Ext. Cost Price</th>
                    <th class='text-right'>Ext. Unit Price</th>
                    <th class='text-right'>Gross Profit(%)</th>
                    
                </tr>
                <?php foreach ($out as $r) { ?>
                    <?php if (isset($filters_selected) && $filters_selected == 'NoCategory') { ?>
                        <?php if (isset($r['vdepname'])) { ?>
                            <tr>
                                <th><?php echo isset($r['vdepname']) ? $r['vdepname'] : ''; ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <?php $totalamount = $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = $counter = $subtotalgrossprofit = 0; ?>
                            <?php foreach ($r['details'] as $itmes){ ?>
                                <?php 
                                    $total_qty_sold += $itmes['totalqty'];
                                    $total_total_cost += $itmes['totalCost'];
                                    if($itmes['totalqty']!=0){
                                                $total_total_price += ($itmes['totalPrice']/$itmes['totalqty']);  
                                              }
                                              else{
                                                  $total_total_price =0; 
                                              }
                                    //$total_total_price += $itmes['totalPrice'];
                                    $subtotalgrossprofit += $itmes['totalPrice'] - $itmes['totalCost'];
                                    $totalamt = $itmes['totalPrice'] ;
                                    $totalamount += $totalamt;
                                ?>
                                <tr>
                                    <td><?php echo isset($itmes['vitemname']) ? $itmes['vitemname'] : ''; ?></td>
                                    <td class='text-right'><?php echo "$", isset($totalamt) ? number_format($totalamt, 2) : ''; ?></td>
                                    <td class='text-right'><?php echo isset($itmes['totalqty']) ? number_format($itmes['totalqty'], 2) : ''; ?></td>
                                    <td class='text-right'><?php echo  "$",isset($itmes['totalCost']) ? $itmes['totalCost'] : ''; ?></td>
                                    <?php if($itmes['totalqty']!=0) { ?>
                                    <td class='text-right'><?php echo  "$",isset($itmes['totalPrice']) ? number_format($itmes['totalPrice']/$itmes['totalqty'],2) : ''; ?></td>
                                    <?php } else{ ?>
                                    <td class='text-right'><?php echo  "$",isset($itmes['totalPrice']) ? 0 : ''; ?></td>
                                    <?php } ?>
                                    
                                    <td class='text-right'><?php echo  number_format((($itmes['totalPrice'] - $itmes['totalCost']) / $itmes['totalPrice']) * 100, 2),"%"; ?></td>
                                </tr>
                            <?php } ?>
                            <tr> 
                                <th> Sub Total:</th>
                                <th class='text-right'> <?php echo "$", number_format($totalamount, 2); ?> </th>
                                <th class='text-right'> <?php echo $total_qty_sold; ?> </th>
                                <th class='text-right'> <?php echo "$", number_format($total_total_cost, 2); ?> </th>
                                <th class='text-right'> <?php echo "$", number_format($total_total_price, 2); ?> </th>
                                <th class='text-right'> <?php echo number_format(($subtotalgrossprofit / $totalamount) * 100, 2) , "%"; ?> </th>
                            </tr>
                            <?php
                                $totalamount1 += $totalamount;
                                $total_qty_sold1 += $total_qty_sold;
                                $total_total_cost1 += $total_total_cost;
                                $total_total_price1 += $total_total_price;
                                $total_gross_profit1 += $subtotalgrossprofit;
                                $total_gross_profit_percentage = ((($totalamount1-$total_total_cost1 )/$totalamount1) * 100);?>
                            
                        <?php } ?>  
                    <?php } ?>
                    
                    <?php if (isset($filters_selected) && $filters_selected == 'NoSubcategory') { ?>
                        <?php if (isset($r['vdepname'])){ ?>    
                            <tr>
                                <th><?php echo isset($r['vdepname']) ? $r['vdepname'] : ''; ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <?php $totalamount = $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = $counter = $subtotalgrossprofit = 0; ?>
                            <?php foreach ($r as $cat) { ?>
                                <?php if (isset($cat['vcatname'])){ ?>
                                    <tr>
                                        <th><?php echo isset($cat['vcatname']) ? $cat['vcatname'] : ''; ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <?php foreach ($cat['details'] as $itmes) { ?>
                                        <?php 
                                            $total_qty_sold += $itmes['totalqty'];
                                            $total_total_cost += $itmes['totalCost'];
                                            if($itmes['totalqty']!=0){
                                                $total_total_price += ($itmes['totalPrice']/$itmes['totalqty']);  
                                              }
                                              else{
                                                  $total_total_price =0; 
                                              }
                                            //$total_total_price += $itmes['totalPrice'];
                                            $subtotalgrossprofit += $itmes['totalPrice'] - $itmes['totalCost'];
                                            $totalamt = $itmes['totalPrice'] ;
                                            $totalamount += $totalamt;
                                        ?>
                                        <tr>
                                            <td  class='text-right'><?php echo isset($itmes['vitemname']) ? $itmes['vitemname'] : ''; ?></td>
                                            <td class='text-right'><?php echo "$", isset($totalamt) ? number_format($totalamt, 2) : ''; ?></td>
                                            <td  class='text-right'><?php echo isset($itmes['totalqty']) ? $itmes['totalqty'] : ''; ?></td>
                                            <td  class='text-right'><?php echo isset($itmes['totalCost']) ? $itmes['totalCost'] : ''; ?>
                                            <?php if($itmes['totalqty']!=0) { ?>
                                            <td class='text-right'><?php echo  "$",isset($itmes['totalPrice']) ? number_format($itmes['totalPrice']/$itmes['totalqty'],2)  : ''; ?></td>
                                            <?php } else{ ?>
                                            <td class='text-right'><?php echo  "$",isset($itmes['totalPrice']) ? 0 : ''; ?></td>
                                            <?php } ?>
                                            <td  class='text-right'><?php echo number_format((($itmes['totalPrice'] - $itmes['totalCost']) / $itmes['totalPrice']) * 100, 2) , "%"; ?></td>
                                        </tr>   
                                    <?php } ?>
                                <?php } ?>                               
                            <?php } ?>
                            <tr> 
                                <th> Sub Total:</th>
                                <th class='text-right'> <?php echo "$", number_format($totalamount, 2); ?> </th>
                                <th class='text-right'> <?php echo $total_qty_sold; ?> </th>
                                <th class='text-right'> <?php echo "$", number_format($total_total_cost, 2); ?> </th>
                                <th class='text-right'> <?php echo "$", number_format($total_total_price, 2); ?> </th>
                                <th class='text-right'> <?php echo $total_total_price === 0 ? 0 : number_format(($subtotalgrossprofit / $totalamount) * 100, 2) , "%"; ?> </th>
                            </tr>
                        <?php
                            $totalamount1 += $totalamount;
                            $total_qty_sold1 += $total_qty_sold;
                            $total_total_cost1 += $total_total_cost;
                            $total_total_price1 += $total_total_price;
                            $total_gross_profit1 += $subtotalgrossprofit;
                           $total_gross_profit_percentage = ((($totalamount1-$total_total_cost1 )/$totalamount1) * 100);?>
                        <?php } ?>
                    <?php } ?>
                    
                    <?php if (isset($filters_selected) && $filters_selected == 'All'){ ?>
                        <?php if (isset($r['vdepname'])){ ?> 
                            <tr>
                                <th><?php echo isset($r['vdepname']) ? $r['vdepname'] : ''; ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <?php $totalamount = $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = $counter = $subtotalgrossprofit = 0; ?>
                            <?php foreach ($r as $cat){ ?>
                                <?php if (isset($cat['vcatname'])){ ?>
                                    <tr>
                                        <th><?php echo isset($cat['vcatname']) ? $cat['vcatname'] : ''; ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                <?php }?>
                                <?php foreach ($cat as $subcat) { ?>
                                    <?php if (isset($subcat['subcat_name'])){ ?>
                                        <tr>
                                            <th><?php echo isset($subcat['subcat_name']) ? $subcat['subcat_name'] : ''; ?></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    <?php } ?>   
                                    <?php foreach ($subcat['details'] as $itmes) { ?>
                                        <?php   $total_qty_sold += $itmes['totalqty'];
                                                $total_total_cost += $itmes['totalCost'];
                                                // $total_total_price += $itmes['totalPrice'];
                                                if($itmes['totalqty']!=0){
                                                $total_total_price += ($itmes['totalPrice']/$itmes['totalqty']);  
                                              }
                                              else{
                                                  $total_total_price =0; 
                                              }
                                                $subtotalgrossprofit += $itmes['totalPrice'] - $itmes['totalCost'];
                                                $totalamt = $itmes['totalPrice'] ;
                                                $totalamount += $totalamt;
                                        ?>
                                        <tr>
                                            <td class='text-right'><?php echo isset($itmes['vitemname']) ? $itmes['vitemname'] : ''; ?></td>
                                            <td class='text-right'><?php echo "$", isset($totalamt) ? number_format($totalamt, 2) : ''; ?></td>
                                            <td  class='text-right'><?php echo isset($itmes['totalqty']) ? $itmes['totalqty'] : ''; ?></td>
                                            <td  class='text-right'><?php echo isset($itmes['totalCost']) ? $itmes['totalCost'] : ''; ?>
                                            <?php if($itmes['totalqty']!=0) { ?>
                                            <td class='text-right'><?php echo  "$",isset($itmes['totalPrice']) ? number_format($itmes['totalPrice']/$itmes['totalqty'],2) : ''; ?></td>
                                            <?php } else{ ?>
                                            <td class='text-right'><?php echo  "$",isset($itmes['totalPrice']) ? 0 : ''; ?></td>
                                            <?php } ?>
                                            <td  class='text-right'><?php echo number_format((($itmes['totalPrice'] - $itmes['totalCost']) / $itmes['totalPrice']) * 100, 2) , "%"; ?></td>
                                           </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <tr> 
                                <th> Sub Total:</th>
                                <th class='text-right'> <?php echo "$", number_format($totalamount, 2); ?> </th>
                                <th class='text-right'> <?php echo $total_qty_sold; ?> </th>
                                <th class='text-right'> <?php echo "$", number_format($total_total_cost, 2); ?> </th>
                                <th class='text-right'> <?php echo "$", number_format($total_total_price, 2); ?> </th>
                                <th class='text-right'> <?php echo number_format(($subtotalgrossprofit / $totalamount) * 100, 2) , "%"; ?> </th>
                            </tr>
                            
                            <?php
                            $totalamount1 += $totalamount;
                            $total_qty_sold1 += $total_qty_sold;
                            $total_total_cost1 += $total_total_cost;
                            $total_total_price1 += $total_total_price;
            
                            $total_gross_profit1 += $subtotalgrossprofit;
            
                            $total_gross_profit_percentage = ((($totalamount1-$total_total_cost1 )/$totalamount1) * 100);?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                  </tr><tr>
                            <br>
                            <br>
                            
                                <th> Total:</th>
                                <th class='text-right' id="total_amount"> <?php echo  "$", number_format($totalamount1,2); ?> </th>
                                <th class='text-right'> <?php echo $total_qty_sold1; ?> </th>
                                <th class='text-right'> <?php echo "$", number_format($total_total_cost1, 2); ?> </th>
                                <th class='text-right'> <?php echo "$", number_format($total_total_price1, 2); ?> </th>
                                <th class='text-right'> <?php echo number_format($total_gross_profit_percentage, 2) , "%"; ?> </th>
                            </tr>
            </table>
          </div>
        </div>
            <?php }else{ ?>
                <?php if (isset($p_start_date)){ ?>
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