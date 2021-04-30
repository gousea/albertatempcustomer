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
            <p><b>Date: </b><?php echo date("m-d-Y"); ?></p>
          </div>
          <div class="col-md-6 pull-right">
            <p><b>Store Name: </b><?php echo $storename; ?></p>
            <p><b>Store Address: </b><?php echo $storeaddress; ?></p>
            <p><b>Store Phone: </b><?php echo $storephone; ?></p>
          </div>
        </div>
        <hr>
        <?php if(isset($reports) && count($reports) > 0){ ?>
        <div class="row">
          <div class="col-md-12">
          <br>
            <table class="table table-bordered table-striped table-hover" style="border:none;width:100%;width:100%;">
              <thead>
                <tr style="border-top: 1px solid #ddd;">
                  <th style="width:auto;">Username</th>
                  <th style="width:auto;">User ID</th>
                  <th style="width:auto;">Transaction Type</th>
                  <th style="width:auto;">Transaction ID</th>
                  <th style="width:auto;">Transaction Time</th>
                  <th style="width:auto;">Product Name</th>
                  <th  style="width:auto;" class="text-right">Amount</th>
                </tr>
              </thead>
              <tbody>
                  <?php if(isset($reports) && count($reports) > 0){ ?>
                    <?php foreach($reports as $report){?>
                      <tr>
                        <td><?php echo $report['vusername'];?></td>
                        <td><?php echo $report['iuserid'];?></td>
                        <td><?php echo $report['TrnType'];?></td>
                        <td><?php echo $report['isalesid'];?></td>
                        <td><?php echo $report['trn_date_time'];?></td>
                        <td><?php echo $report['vitemname'];?></td>
                        <td class="text-right"><?php echo $report['nextunitprice'];?></td>
                      </tr>
                    <?php } ?>
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