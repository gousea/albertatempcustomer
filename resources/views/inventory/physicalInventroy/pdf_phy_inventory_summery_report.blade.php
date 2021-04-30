 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 <style type="text/css">
   @page { size: landscape; }
   @page :left {
      margin-left: 1px;
      margin-right: 1px;
   }

   @page :right {
      margin-left: 1px;
      margin-right: 1px;
   }
 </style>
 
<div id="content">
  <div class="container-fluid">
    <div class="" style="margin-top:2%;">
        <div class="page-header">
          <div class="text-center">
            <h2 class="panel-title" style="font-weight:bold;font-size:24px;"><?php echo $heading_title; ?></h2>
          </div>
        </div>
      <div class="panel-body">
        <br><br><br>
        <div class="row">
          <div class="col-md-6 pull-left">
            <p><b>Ref. Number: </b><?php echo $vrefnumber; ?></p>
          </div>
          <div class="col-md-6 pull-right">
            <p><b>Store Name: </b><?php echo $storename; ?></p>
          </div>
        </div>
        <div class="row">
            <div class="col-md-6 pull-left">
                <p><b>Title: </b><?php echo $vordertitle; ?></p>
            </div>
            <div class="col-md-6 pull-right">
                <p><b>Store Address: </b><?php echo $storeaddress; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 pull-left">
                <p><b>Create Date: </b><?php echo $dcreatedate; ?></p>
            </div>
            <div class="col-md-6 pull-right">
                <p><b>Store Phone: </b><?php echo $storephone; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 pull-left">
                <p><b>Calculate Date: </b><?php echo $dcalculatedate; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 pull-left">
                <p><b>Commit Date: </b><?php echo $dclosedate; ?></p>
            </div>
        </div>
      </div>
      
        <div class="panel panel-default">
            <div class="panel-heading head_title">
                <h3 class="panel-title"><i class="fa fa-list"></i>Report</h3>
            </div>
            <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <p><b>Total Counted Qty: </b><?php echo (int)$total_counted; ?></p>
                        </div>
                        <div class="col-xs-3">
                            <p><b>Total Expected Qty: </b><?php echo (int)$total_expected; ?></p>
                        </div>
                        <div class="col-xs-4">
                            <p><b>Total Difference Qty: </b><?php echo (int)$total_difference; ?></p>
                        </div>
                        <div class="col-xs-2">
                            <p></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <p><b>Total Counted cost: </b><?php $total_counted_cost = $total_counted_cost < 0 ? '-$'.number_format(abs($total_counted_cost), 2): '$'.number_format($total_counted_cost, 2); echo $total_counted_cost; ?></p>
                        </div>
                        <div class="col-xs-3">
                            <p><b>Total Expected cost: </b><?php $total_expected_cost = $total_expected_cost < 0 ? '-$'.number_format(abs($total_expected_cost), 2): '$'.number_format($total_expected_cost, 2); echo $total_expected_cost; ?></p>
                        </div>
                        <div class="col-xs-4">
                            <p><b>Total Difference cost: </b><?php $total_difference_cost = $total_difference_cost < 0 ? '-$'.number_format(abs($total_difference_cost), 2): '$'.number_format($total_difference_cost, 2); echo $total_difference_cost; ?></p>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <p><b>Total Counted price: </b><?php $total_counted_price = $total_counted_price < 0 ? '-$'.number_format(abs($total_counted_price), 2): '$'.number_format($total_counted_price, 2); echo $total_counted_price; ?></p>
                        </div>
                        <div class="col-xs-3">
                            <p><b>Total Expected price: </b><?php $total_expected_price = $total_expected_price < 0 ? '-$'.number_format(abs($total_expected_price), 2): '$'.number_format($total_expected_price, 2); echo $total_expected_price; ?></p>
                        </div>
                        <div class="col-xs-4">
                            <p><b>Total Difference price: </b><?php $total_difference_price = $total_difference_price < 0 ? '-$'.number_format(abs($total_difference_price), 2): '$'.number_format($total_difference_price, 2); echo $total_difference_price; ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <p></p>
                        </div>
                        <div class="col-xs-3">
                            <p></p>
                        </div>
                        <div class="col-xs-4">
                            <p><b>Total Profit loss difference: </b><?php $total_profit_loss_difference = $total_profit_loss_difference < 0 ? '-$'.number_format(abs($total_profit_loss_difference), 2): '$'.number_format($total_profit_loss_difference, 2); echo $total_profit_loss_difference; ?></p>
                        </div>
                    </div>
                </div>
        </div>
    </div>
  </div>
  
</div>