<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "RO History Report" ?></h3>
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
        
        <div class="row">
          <div class="col-md-12">
          <br>
            <table class="table table-bordered" style="border:none;">
              <thead>
                <tr style="border-top: 1px solid #ddd;">
                  <th>Vendor</th>
                  <th>Date</th>
                  <th class="text-right">Net Total</th>
                  <th class="text-right">RIP Total Amt</th>
                </tr>
              </thead>
              <tbody>
                  <?php 
                   $total_nnettotal = 0;
                   $rip_tot=0;
                  ?>
                  <?php foreach ($reports as $key => $value){ ?>
                  <tr>
                    <td class="text-left"><?php echo $value['vvendorname']; ?></td>
                    <td class="text-left"><?php echo $value['dcreatedate']; ?></td>
                    <td class="text-right"><?php echo number_format((float)$value['nnettotal'], 2, '.', '') ; ?></td>
                    <td class="text-right"><?php echo number_format((float)$value['rip_total'], 2, '.', '') ; ?></td>
                    <?php 
                      $total_nnettotal = $total_nnettotal + $value['nnettotal'];
                      $rip_tot=$rip_tot+$value['rip_total'];
                    ?>
                  </tr>
                  <?php } ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td><b>Total</b></td>
                    <td class="text-right"><b>$<?php echo $total_nnettotal; ?></b></td>
                    <td class="text-right"><b>$<?php echo $rip_tot; ?></b></td>
                  </tr>
              </tbody>
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