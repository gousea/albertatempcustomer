<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "Inventory On Hand Report" ?></h3>
    <hr style="height:1px; background-color:black;">
   
</div> 

<div id="content">
  <div class="container-fluid">
    <div class="" style="margin-top:2%;">
     
 
        <div class="row">  
          <div class="col-md-6 pull-left">
            <p><b>Date </b><?php echo date("m-d-Y"); ?></p>
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
        
        
        
        <div class="row">
          <div class="col-md-12">
   
            <p style="font-size: 14px;"><span><b>Total Cost Value - </b>$ <?php echo number_format((float)$toal_value, 2) ;?></span></p>
            <p style="font-size: 14px;"><span><b>Total Retail Value - </b>$ <?php echo number_format((float)$total_retail_value, 2) ;?></span></p>
         
        </div>
        <?php if(isset($reports) && count($reports) > 0){ ?>
        <div class="row">
          <div class="col-md-12">
          <br>
            <table class="table table-bordered" style="border:none;">
              <thead>
                <tr style="border-top: 1px solid #ddd;">
                  <?php if(isset($selected_report) && $selected_report == 1){ ?>
                  <th>Department</th>
                  <?php } else if(isset($selected_report) && $selected_report == 2) {?>
                    <th>Department</th>
                  <?php } else if(isset($selected_report) && $selected_report == 3) {?>
                    <th>Department</th>
                  <?php } else {?>
                    <th>Department</th>
                  <?php } ?>
                  <th>Item</th>
                  <th class="text-right">QOH</th>
                  <th class="text-right">Cost Value</th>
                  <th class="text-right">Total Cost Value</th>
                  <th class="text-right">Retail Value</th>
                  <th class="text-right">Total Retail Value</th>
                </tr>
              </thead>
              <tbody>
                
                  <?php foreach ($reports as $key => $value){ ?>
                    <tr>
                      <td><b><?php //echo $value[0]['search_name']; ?></b></td>
                      <td style="border-right: none;"></td>
                      <td style="border-right: none;"></td>
                      <td style="border-right: none;"></td>
                      <td style="border-right: none;"></td>
                      <td></td>
                    </tr>
                    <?php 
                      $total_qty = 0;
                      $total_total_cost = 0;
                      $total_total_value = 0;
                      $total_total_retail_value = 0;
                    ?>
                    <?php foreach ($value as $k => $v){
                      $tot_value = $v['snapshot_qty'] * number_format((float)$v['cost'], 2, '.', '');
                      $tot_ret_value = $v['snapshot_qty'] * number_format((float)($v['price']/$v['nsellunit']), 2, '.', '');

                      $total_total_retail_value = $total_total_retail_value + $tot_ret_value;

                      $total_qty = $total_qty + $v['snapshot_qty'];
                      $total_total_cost = $total_total_cost + number_format((float)$v['cost'], 2, '.', '');
                      $total_total_value = $total_total_value + $tot_value;

                    ?>
                   
                      <tr>
                        <td><?php echo $v['vname'];?></td>
                        <td><?php echo $v['itemname'];?></td>
                        <td class="text-right"><?php echo $v['snapshot_qty'];?></td>
                        <td class="text-right"><?php echo number_format((float)$v['cost'], 2, '.', '') ;?></td>
                        <td class="text-right"><?php echo number_format((float)$tot_value, 2, '.', '') ;?></td>
                        <td class="text-right"><?php echo number_format((float)($v['price']/$v['nsellunit']), 2, '.', '') ;?></td>
                        <td class="text-right"><?php echo number_format((float)$tot_ret_value, 2, '.', '') ;?></td>
                      </tr>
                    <?php } ?>
                    <tr>
                      <td></td>
                      <td class="text-right"><b>Total</b></td>
                      <td class="text-right"><b><?php echo $total_qty;?></b></td>
                      <td></td>
                      <td class="text-right"><b>$ <?php echo number_format((float)$total_total_value, 2) ;?></b></td>
                      <td class="text-right"></td>
                      <td class="text-right"><b>$ <?php echo number_format((float)$total_total_retail_value, 2) ;?></b></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td></td>
                    <td class="text-right"><b>Grand Total</b></td>
                    <td class="text-right"><b><?php echo $total_qoh;?></b></td>
                    <td></td>
                    <td class="text-right"><b>$ <?php echo number_format((float)$toal_value, 2) ;?></b></td>
                    <td class="text-right"></td>
                    <td class="text-right"><b>$ <?php echo number_format((float)$total_retail_value, 2) ;?></b></td>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>
        <?php }else{ ?>
          <?php if(isset($total_qoh)){ ?>
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