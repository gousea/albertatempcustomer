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

        <?php if(isset($zeromovement_reports) && count($zeromovement_reports) > 0){ ?>
        <div class="row">
          <div class="col-md-12">
          <br>
            <table class="table table-bordered">
              <thead>
                <tr>
                 
                  <th style="width: 20%;">Item Name</th>
                  <th style="width: 15%;">SKU</th>
                  <th style="width: 10%;">QOH</th>
                  <th style="width: 10%;" class="text-right">Cost</th>
                  <th style="width: 10%;" class="text-right">Price</th>
                  <th style="width: 10%;" class="text-right">Total cost</th>
                  <th style="width: 10%;">Total Price</th>
                  <th style="width: 10%;">GP%</th>
                 
                </tr>
              </thead>
              <tbody>
                  
                  <?php foreach ($zeromovement_reports as $k => $value){ ?>
                 
                     <tr>
                    
                    <td style="width: 20%;"><?php echo $value['ItemName'];?></td>
                   
                    <td style="width: 15%;"><?php echo $value['SKU'];?></td>
                    <td style="width: 10%;"><?php echo $value['QOH'];?></td>
                    <td style="width: 10%;"><?php echo $value['Cost'];?></td>
                    <td style="width: 10%;"><?php echo $value['Price'];?></td>
                    <td style="width: 10%;"><?php echo $value['TotalCost'];?></td>
                    <td style="width: 10%;"><?php echo $value['TotalPrice'];?></td>
                    <td style="width: 10%;"><?php echo $value['GPP'];?></td>
                    </tr>
                  <?php } ?>
                  
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