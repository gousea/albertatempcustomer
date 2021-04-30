<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "Vendor Paid Out Report" ?></h3>
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

<table border="0" width = "100%;">
   
   <tbody>
       <tr>
           <td>
               
               
               <div class="row">
                   <div class="col-md-12 table-responsive">
                       <br>
                       
                       <table class="table table-bordered table-striped table-hover">
                   
                   
                           <tr>
                               <th>Serial No.</th>
                               <th>Paid Date</th>
                               <th>Vendor Name</th>
                               <th>Amount</th>
                               <th>Tender Type</th>
                               <th>Register No</th>
                               <th>Time</th>
                               <th>User ID</th>
                           </tr>
                           <?php $total=0;?>
                            <?php foreach($report_paid_out as $v){
                                 if($v->Vendor === "Total"){
                                  continue;
                                }?>
                               <?php  $total= $total+$v->Amount; 
                            
                               } 
                            ?>
                            <tr> 
                               <th></th>
                               <th></th>
                               <th>Total</th>
                               <th><?php echo "$",number_format($total,2); ?></th>
                               <th></th>
                               <th></th>
                               <th></th>
                               <th></th>
                               </tr>
                           <?php 
                               $count = 0; 
                               foreach($report_paid_out as $v){ ?>
                                <?php if($v->Vendor === "Total"){
                                continue;
                                }?>
                                       <tr>
                                           <td><?php echo ++$count; ?></td>
                                           <td style="width:80px;"><?php echo isset($v->dt) ? $v->dt: ""; ?></td>
                                           <td><?php echo isset($v->Vendor) ? $v->Vendor: ""; ?></td>
                                           <td><?php echo "$",isset($v->Amount) ? $v->Amount: 0.00; ?></td>
                                           
                                           <td><?php echo isset($v->TenderType) ? $v->TenderType: 0.00; ?></td>
                                           <td><?php echo isset($v->RegNo) ? $v->RegNo: 0.00; ?></td>
                                           <td><?php echo isset($v->ttime) ? $v->ttime: 0.00; ?></td>
                                           <td><?php echo isset($v->UserId) ? $v->UserId: 0.00; ?></td>
                                       </tr>
                           <?php }?>
                   
                   
                   
                       </table>
                   </div>
               </div>
               
               
           </td>

       </tr>

   </tbody>
</table>

<style type="text/css">

/*table {            
   page-break-after: always;
   page-break-inside: avoid;
   break-inside: avoid;
}*/

tr.applyBorder{border:1pt solid;}

</style>