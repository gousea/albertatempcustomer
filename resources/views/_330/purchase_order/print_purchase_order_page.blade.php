<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="data:image/png;base64, <?php echo $image; ?>"  alt="Pos logo" width="120" height="50"></p>
<div class="text-center">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo $heading_title; ?></h3>
    <?php echo $storename; ?>, <?php echo $storeaddress; ?>, <?php echo $storephone; ?></p>
    <hr style="height:1px; background-color:black;">
</div>  

<div class="row">
    <div class='col-md-6'><b>#Invoice: </b><?php echo $purchase_info['vrefnumber']; ?></div>
</div>

<div class="row">
    <div class='col-md-6'><p><b>Vendor Name: </b><?php echo $purchase_info['vvendorname']; ?></p></div>
</div>



<!=============================== DATA =================================================-
 
<div class="row">
    <div class="col-md-12" style="text-align: center;" >
        <table class="table table-bordered table-striped table-hover col-md-12" bgcolor="#ffffff" page-break-inside: auto;>
            
            <thead>
                <tr>
                   <th>Sl. No.</th> 
                   <th>Vendor</th> 
                   <th>SKU #</th> 
                   <th>Item</th> 
                   <th>Size</th> 
                   <th>New Cost</th>
                   <th>Units/Case</th>
                   <th>Order By</th>
                   <th>Suggested Cost</th>
                   <th>Total Cost</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach($purchase_info['items'] as $k=>$item) { ?>
                    <tr>
                        <td><?php echo ++$k; ?></td>
                        <td><?php echo $item['vendor_name']; ?></td>
                        <td><?php echo $item['vbarcode']; ?></td>
                        <td><?php echo $item['vitemname']; ?></td>
                        <td><?php echo $item['vsize']; ?></td>
                        <td><?php echo $item['new_costprice']; ?></td>
                        <td><?php echo $item['npack']; ?></td>
                        <td><?php echo $item['po_order_by']; ?></td>
                        <td><?php echo $item['po_total_suggested_cost']; ?></td>
                        <td><?php echo $item['nordextprice']; ?></td>
                    </tr>     
                <?php } ?>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Sub Total</b></td><td><b><?php echo $purchase_info['nsubtotal']; ?></b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Tax (+)</b></td><td><b><?php echo $purchase_info['ntaxtotal']; ?></b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Freight (+)</b></td><td><b><?php echo $purchase_info['nfreightcharge']; ?></b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Deposit (+)</b></td><td><b><?php echo $purchase_info['ndeposittotal']; ?></b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Fuel (+)</b></td><td><b><?php echo $purchase_info['nfuelcharge']; ?></b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Delivery (+)</b></td><td><b><?php echo $purchase_info['ndeliverycharge']; ?></b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Return (-)</b></td><td><b><?php echo $purchase_info['nreturntotal']; ?></b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Discount (-)</b></td><td><b><?php echo $purchase_info['ndiscountamt']; ?></b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Rips (-)</b></td><td><b><?php echo $purchase_info['nripsamt']; ?></b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Net Total</b></td><td><b><?php echo $purchase_info['nnettotal']; ?></b></td>
                </tr>
            </tbody>
        </table>
        
        
    </div>
</div>
<br>
           

<style type="text/css">
  
  
  .table {            
        /*page-break-after: always;*/
/*        page-break-inside: avoid;*/
        /*break-inside: avoid;*/
    }
 
 .tr{border:1pt solid;
    background-color: #ffffff;
 }

.column {
  float: left;
  width: 70%;
}

.row .col {
    margin: 0px !important;
    padding: 0px !important;
}
</style>
 