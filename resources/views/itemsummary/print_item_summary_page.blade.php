<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{url('/image/logoreport.jpg')}}"  alt="Pos logo" width="120" height="50"></p>
 <div class="text-center">
            <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo $heading_title; ?></h3>
            <hr style="height:1px; background-color:black;">
 </div> 

<div class="row">
          <div class="col-md-6 pull-left">
            <p>From: <?php echo $p_start_date; ?> To <?php echo $p_end_date; ?></p>
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
      

                <table class="table table-hover">
                            
                    <tr>
                        <th>Sku</th>
                        <th>Item Name</th>
                        <th></th>
                        <th class='text-right'>Qty Sold</th>
                        <th class='text-right'>Avg. Price</th>
                        <th class='text-right'>Amount</th>
                        
                    </tr>
                    <?php $grand_total_qty = $grand_total_amount = 0;?>
                    <?php foreach($out as $r1 => $r) { ?>
                        <tr>
                            <th colspan='2'><?php echo isset($r['catagoryname']) ? $r['catagoryname']: ''; ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <?php $total_qty = $total_amount = 0;?>
                        <?php foreach($r['details'] as $itmes) { ?>
                            <?php $total_qty += $itmes['qtysold']; $total_amount += $itmes['amount']; ?>
                            <tr>
                                <td><?php echo isset($itmes['sku']) ? $itmes['sku']: 0; ?></td>
                                <td><?php echo isset($itmes['itemname']) ? $itmes['itemname']: ''; ?></td>
                                <td></td>
                                <td class='text-right'><?php echo isset($itmes['qtysold']) ? $itmes['qtysold']: 0; ?></td>
                                <td class='text-right'><?php echo isset($itmes['avgprice']) ? number_format($itmes['avgprice'],2): 0; ?></td>
                                <td class='text-right'><?php echo isset($itmes['amount']) ? $itmes['amount']: 0; ?></td>
                            </tr>
                        <?php } ?>
                        <tr> 
                            <th></th>
                            <th></th>
                            <th class='text-right' style="width:20%;"> Sub Total:</th>
                            <th class='text-right'> <?php echo number_format($total_qty,2); ?> </th>
                            <th>  </th>
                            <th class='text-right'> <?php echo number_format($total_amount,2); ?> </th>
                        </tr>
                         <?php $grand_total_qty    += $total_qty;
                                $grand_total_amount += $total_amount; ?>

                    <?php } ?>
                    <tr> 
                        <th></th>
                        <th></th>
                        <th class='text-right'> Grand Total:</th>
                        <th class='text-right'> <?php echo number_format($grand_total_qty,2); ?> </th>
                        <th>  </th>
                        <th class='text-right'> <?php echo number_format($grand_total_amount,2); ?> </th>
                    </tr>
                </table>
   

<style type="text/css">


tr{border:1pt solid;}

</style>
